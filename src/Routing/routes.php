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
            flashData::setFlashData('error', 'Falied to login, wrong email and/or password.');
            return new RedirectRenderer('login');
       }catch(\InvalidArgumentException $e){
            error_log($e->getMessage());

            flashData::setFlashData('error', 'Invalid Data.');
            return new RedirectRenderer('login');
       }catch(Exception $e){
            error_log($e->getMessage());

            flashData::setFlashData('error', 'An error occurred.');
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
    'register' => Route::create('register', function(): HTTPRenderer{
        return  new HTMLRenderer('page/register');
    })->setMiddleware(['guest']),
    'form/register' => Route::create('form/regisiter', function(): HTTPRenderer{
        try{
            if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');
            $required_fields = [
                'username' => ValueType::STRING,
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
                userName: $validatedData['username'],
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
    // home
    'home' => Route::create('home', function(): HTTPRenderer{
        return  new HTMLRenderer('page/home');
    }),
    'generate-url'=>Route::create('generate-url', function(): HTTPRenderer{

        if(isset($_GET['lasts'])){
            $validatedData['expiration'] = time() + ValidationHelper::integer($_GET['lasts']);
        }

        return  new HTMLRenderer('page/home');
    }),
    'form/post' => Route::create("form/post", function(): HTTPRenderer {
        try{
            if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');
            $require_fields = [
                'post' => ValueType::STRING,
            ];
            $validatedData  = ValidationHelper::validateFields($require_fields, $_POST);

            $user = Authenticate::getAuthenticatedUser();

            $post = new Post($validatedData['post'], $user->getId());

            $postDAO = DAOFactory::getPostDAO();

            $postDAO->create($post);

            $getPost = $postDAO->getById();





        }catch(Exception $e){
            error_log($e->getMessage());
            flashData::setFlashData('error', 'An error occurred.');
        }


        return new HTMLRenderer('page/home');
    }),
];
// profile/userID
// notifications
// messages
// messages/messageID
