<?php
use src\Database\MySQLWrapper;
use src\Helpers\Authenticate;
use src\Helpers\IconsHelper;
use src\Database\DataAccess\DAOFactory;

$mysqli = new MySQLWrapper();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$postDAO = DAOFactory::getPostDAO(); 
if($path === '/profile'){
    $user = Authenticate::getAuthenticatedUser();
    $posts = $postDAO->getUserPosts($user->getId());
}else {
    $posts = $postDAO->getAll();
}
/*
if($path === '/profile'){
    $user = Authenticate::getAuthenticatedUser();
    $userId = $user->getId();
    $query = 
    'SELECT 
    posts.id,
    posts.content,
    posts.created_at,
    users.user_name
    FROM posts
    JOIN users ON users.id = posts.user_id
    WHERE posts.user_id = ?
    ORDER BY posts.created_at DESC';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

}else {
    $query = 
    'SELECT 
    posts.user_id, 
    posts.content,
    posts.created_at, 
    users.user_name 
    FROM posts 
    JOIN users ON   users.id = posts.user_id
    ORDER BY posts.created_at DESC ';

    $result = $mysqli->query($query);
}
$posts =  ($result) ? $result->fetch_all(MYSQLI_ASSOC) : [];
*/

?>

<?php foreach ($posts as $post):  ?>
    
    <div class="space-y-4 mb-2 w-2/3">

        <div class="p-4 bg-gray-100 rounded-lg shadow-md ">
            <div class="flex items-start space-x-4">
                <img src="https://via.placeholder.com/50" class="w-12 h-12 rounded-full" alt="Avatar">
                <div data-postid=<?php echo $post->getPostId(); ?>>

                    <h3 id="userName" class="text-sm font-bold"><?php echo htmlspecialchars($post->getUserName()) ?> <span id="userId" class="text-gray-500" data-userid="<?php echo $post->getUserId(); ?>">@<?php echo htmlspecialchars($post->getUserId()) ?></span></h3>
                    <p id="content" class="text-sm text-gray-700 mt-1"><?php echo htmlspecialchars($post->getContent()) ?></p>
                    <div class="flex items-center space-x-4 mt-2 text-gray-500">
                    <button id='commentBtn' type='button' class='flex'>
                        <?php echo IconsHelper::getIcon('comment') ?>
                        <span id="commentCount"> 5</span>
                    </button>
                    <button  type='button' class='likeBtn flex like'>
                        <div class='likeIcon'><?php echo IconsHelper::getIcon('outline-hart') ?></div>
                        <span class='likeCount' ><?php echo htmlspecialchars($post->getLikeCount()); ?></span>
                    </button>

                    </div>
                </div>
            </div>
        </div>
    <!-- 他のツイートも同様に -->
    </div>
<?php endforeach; ?>

