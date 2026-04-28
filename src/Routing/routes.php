<?php
namespace src\Routing;

use Exception;
use Exceptions\AuthenticationFailureException;
use src\Database\DataAccess\DAOFactory;
use src\Helpers\DatabaseHeler;
use src\Helpers\ValidationHelper;
use src\Helpers\Authenticate;
use src\Helpers\CrossSiteForgeryProtection;
use src\Response\FlashData;
use src\Response\HTTPRenderer;
use src\Response\Render\RedirectRenderer;
use src\Response\Render\HTMLRenderer;
use src\Response\Render\JSONRenderer;
use src\Models\User;
use src\Models\Post;
use src\Types\ValueType;
use src\Routing\Route;


return  [
    // login
    'login' => Route::create('login', function(): HTTPRenderer{
        if(!Authenticate::isLoggedIn()){
            return  new HTMLRenderer('page/login');
        }
        return new RedirectRenderer('home');
    })->setMiddleware(['guest']),
    'form/login' => Route::create('form/login', function(): HTTPRenderer{
       try{
        if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');
        $require_fields = [
            'email' => ValueType::EMAIL,
            'password' => ValueType::STRING
        ];

        $validatedData  = ValidationHelper::validateFields($require_fields, $_POST);

        Authenticate::authenticate($validatedData['email'], $validatedData['password']);
        FlashData::setFlashData('success', 'Logged in successfully.');
        return new RedirectRenderer('home');

       }catch(AuthenticationFailureException $e){
            error_log($e->getMessage());
            FlashData::setFlashData('error', 'Falied to login, wrong email and/or password.');
            return new RedirectRenderer('login');
       }catch(\InvalidArgumentException $e){
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'Invalid Data.');
            return new RedirectRenderer('login');
       }catch(Exception $e){
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'An error occurred.');
            return new RedirectRenderer('login');
       }
    })->setMiddleware(['guest']),

    // guest login
    'guest/login' => Route::create('guest/login', function(): HTTPRenderer{
       try{
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                throw new Exception('Invalid request method!');
            } 

            $userDAO = DAOFactory::getUserDAO();
            $guestUser = $userDAO->getByAccountName('guest');
            if($guestUser === null) {
                throw new Exception('Guest user not found ');
            } 

            $result = Authenticate::loginAsUser($guestUser);
            if(!$result){
                throw new Exception('Failed to login as guest user.');
            }
            FlashData::setFlashData('success', 'Logged in as guest user.');
            return new RedirectRenderer('home');

       }catch(Exception $e){
            error_log($e->getMessage());
            FlashData::setFlashData('error', 'Failed to login as guest user.');
            return new RedirectRenderer('login');
       }
    })->setMiddleware(['guest']),

    // logout
    'logout' => Route::create('logout', function(): HTTPRenderer {
        if(!Authenticate::isLoggedIn()){
            FlashData::setFlashData('error', 'Alredy logged out.');
            return new RedirectRenderer('login');
        }

        Authenticate::logoutUser();
        FlashData::setFlashData('success', 'Logged out.');
        return new RedirectRenderer('login');
    }),

    // register
    'register' => Route::create('register', function(): HTTPRenderer{
        return  new HTMLRenderer('page/register');
    })->setMiddleware(['guest']),

    // form register
    'form/register' => Route::create('form/register', function(): HTTPRenderer{
        try{
            if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');
            $required_fields = [
                'accountname' => ValueType::STRING,
                'email' => ValueType::EMAIL,
                'password' => ValueType::PASSWORD,
                'confirm_password' => ValueType::PASSWORD,
            ];

            $userDao = DAOFactory::getUserDAO();

            // シンプルな検証
            $validatedData = ValidationHelper::validateFields($required_fields, $_POST);

            if($validatedData['confirm_password'] !== $validatedData['password']){
                FlashData::setFlashData('error', 'Invalid Password');
                return new RedirectRenderer('register');
            }

            // Eメールが一意であるか、確認
            if($userDao->getByEmail($validatedData['email'])){
                FlashData::setFlashData('error', 'Email is already in use!');
                return new RedirectRenderer('register');
            }
            // 新しいUserオブジェクトを作成
            $user = new User(
                accountName: $validatedData['accountname'],
                password: $validatedData['password'],
                email: $validatedData['email'],
            );

            // データベースにユーザーを作成
            $success = $userDao->create($user, $validatedData['password']);
            if(!$success) throw new Exception('Failed to create new user!');

            // ユーザーログイン
            Authenticate::loginAsUser($user);

            /* 署名付きメールを送る
            $loginUser = Authenticate::getAuthenticatedUser();
            $queryParameters = $loginUser->generateSignedURLQueryParams();

            $signeUri = Route::create('verify/email', function(){})->getSignedURL($queryParameters);
            $mail = new Mail();
            $mail->sendVerificationEmail(Settings::env('MAIL_TO_ADDRESS'), $signedUri);
            */

            FlashData::setFlashData('success', 'Account successfully creted.');
            return  new RedirectRenderer('home');
        }catch(\InvalidArgumentException $e){
            error_log($e->getMessage());
            FlashData::setFlashData('error', 'Invalid Data .');
            return new RedirectRenderer('register');
        }catch(Exception $e){
            error_log($e->getMessage());
            FlashData::setFlashData('error', 'An error occurred.');
            return new RedirectRenderer('register');
        }

    })->setMiddleware(['guest']),

    // home screen
    'home' => Route::create('home', function(): HTTPRenderer{
        return  new HTMLRenderer('page/home');
    }),

    // profile screen
    'profile' => Route::create("profile", function(): HTTPRenderer {
        $user = Authenticate::getAuthenticatedUser();
        //echo $user->getId();
       return new HTMLRenderer('page/home');
    }),

    'timeline/items' => Route::create('timeline/items', function(): HTTPRenderer{
        $postDAO = DAOFactory::getPostDAO();
        try{
            $result = $postDAO->syncAllPostCounts();
        }catch(Exception $e){
            error_log($e->getMessage());
             FlashData::setFlashData('error', 'Failed to load timeline items.');
        }
        return new HTMLRenderer('component/timelineItems');
        
    }),

    // form post
    'form/post' => Route::create("form/post", function(): HTTPRenderer {
        try{
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                throw new Exception('Invalid request method!');
            } 

            $require_fields = [
                'post' => ValueType::STRING,
            ];

            $validatedData  = ValidationHelper::validateFields($require_fields, $_POST);
            $user = Authenticate::getAuthenticatedUser();

            if($user === null){
                throw new Exception('User not authenticated!');
            }

            $parantPostId = isset($_POST['parent_post_id']) ? ValidationHelper::integer($_POST['parent_post_id']) : null;

            $post = new Post(
                user_id: $user->getId(), 
                content: $validatedData['post'], 
                parent_post_id: $parantPostId !== null ? $parantPostId : null
            );

            $postDAO = DAOFactory::getPostDAO();

            $success = $postDAO->create($post);
            if($success){
                $data = [
                    'success' => true,
                    'message' => 'Post created successfully.',
                ];
                error_log(print_r($post, true));
                FlashData::setFlashData('success', 'Post created successfully.');

                // 通常の投稿ならparantPostIdはない
                if($parantPostId !== null) {
                    // コメント数の更新
                    $count = $postDAO->getCountComment($post->getPostId());
                    $postDAO->updateCommentCount($post->getPostId(), $count);
                }

            }else {
                $data = [
                    'success' => false,
                    'message' => 'Failed to create post.',
                ];
            }

             return new JSONRenderer($data);


        } catch(\InvalidArgumentException $e){
            error_log($e->getMessage());
            FlashData::setFlashData('error', 'Invalid Data.');
           $data = [
                'success' => false,
                'message' => 'Invalid Data.',
            ];
            return new JSONRenderer($data);
       }catch(Exception $e){
            error_log($e->getMessage());
            FlashData::setFlashData('error', 'An error occurred.');
            $data = [
                'success' => false,
                'message' => 'An error occurred.',
            ];
            return new JSONRenderer($data); 
        }
    }),
    // post like
    'post/like' => Route::create('post/like', function(): HTTPRenderer{
        if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');
        try{
            $postId = $_POST['postId'];
            error_log('post/like1-----------');
            error_log($postId);
            $status = ($_POST['status'] === 'true') ? true : false;
            $postDAO = DAOFactory::getPostDAO();
            $user = Authenticate::getAuthenticatedUser();
            error_log('post/like2-----------');
            error_log($status);
            $isLike = $status 
                ?   $postDAO->likePost($postId, $user->getId()) 
                :   $postDAO->unLikePost($postId, $user->getId());

            // クエリが実行された場合、いいねの数を処理し、フロントに返す
            error_log('post/like3-----------');
            if($isLike){
                $countResult = $postDAO->countLikes($postId, $status);
                header('Content-Type: application/json');
                if($countResult){
                    //$post = $postDAO->getById($postId);
                    error_log('post/like-----------');
                    $data = [
                        "success" => true,
                        "likeCount" => $postDAO->getLikeCount($postId)
                    ];
                    return new JSONRenderer($data);
                };
            }

            return new HTMLRenderer('page/home');

        }catch(Exception $e){
            error_log($e->getMessage());
            $data = [
                "success" => false,
            ];
            return new JSONRenderer($data);
        }
    }),

    // post comment
    'post/comment/count' => Route::create('post/comment/count', function(): HTTPRenderer {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');
        $postId = $_POST['postId'];
        $postDAO = DAOFactory::getPostDAO();
        $count = $postDAO->getCountComment($postId);
        if($count === null) {
            $data = [
                "success" => false,
                "comment_count" => 0,
            ];
            return new JSONRenderer($data);
        };

        $data = [
            "success" => true,
            "comment_count" => $count,
        ];

        return new JSONRenderer($data);
    }),

    'generate-url'=>Route::create('generate-url', function(): HTTPRenderer{

        if(isset($_GET['lasts'])){
            $validatedData['expiration'] = time() + ValidationHelper::integer($_GET['lasts']);
        }

        return  new HTMLRenderer('page/home');
    }),
];
// profile/userID
// notifications
// messages
// messages/messageID
