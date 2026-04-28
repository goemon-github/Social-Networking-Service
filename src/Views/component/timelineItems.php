<?php
use src\Helpers\Authenticate;
use src\Helpers\IconsHelper;
use src\Database\DataAccess\DAOFactory;


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$postDAO = DAOFactory::getPostDAO(); 
$user = Authenticate::getAuthenticatedUser();
if($path === '/profile'){
    $posts = $postDAO->getUserPostsAndIsLiked($user->getId());
}else{
    if($user === null) {
        $posts = $postDAO->getAll();
    
    }else{
        $posts = $postDAO->getAllPostsAndIsLiked($user->getId());
    }
}

?>


<?php foreach ($posts as $post):  ?>
    <div class="space-y-4 mb-2 w-2/3">
        <div class="p-4 bg-gray-100 rounded-lg shadow-md ">
            <div class="flex items-start space-x-4">
                <img src=<?php echo $post['image_url'] ?? '/images/user_image.png'; ?> class="w-12 h-12 rounded-full" alt="Avatar">
                <!-- 
                <img src="/images/user_image.png" class="w-12 h-12 rounded-full" alt="Avatar">
                
                <div data-postid=<?php //echo $post->getPostId(); ?>>
                -->
                <div data-postid=<?php echo $post['id']; ?>>

                    <!-- 
                <h3  class="text-sm font-bold userName"><?php //echo htmlspecialchars($post->getUserName()) ?> <span id="userId" class="text-gray-500" data-userid="<?php //echo $post->getUserId(); ?>">@<?php //echo htmlspecialchars($post->getUserId()) ?></span></h3>
                    <p  class="text-sm text-gray-700 mt-1 content"><?php //echo htmlspecialchars($post->getContent()) ?></p>
                    -->
                    <h3  class="text-sm font-bold userName"><?php echo htmlspecialchars($post['account_name'])?> <span id="userId" class="text-gray-500" data-userid="<?php echo $post['user_id']; ?>">@<?php echo $post['account_name'] ?></span></h3>
                    <p  class="text-sm text-gray-700 mt-1 content"><?php echo htmlspecialchars($post['content']) ?></p>
                    <div class="flex items-center space-x-4 mt-2 text-gray-500">

                <!-- 
                    <button  type='button' data-modal-target="crud-modal" data-modal-toggle="crud-modal" class='flex commentBtn'>
                        <?php //echo IconsHelper::getIcon('comment') ?>
                        <span class="commentCount"><?php //echo htmlspecialchars(($post->getCommentCount()));?></span>
                    </button>
                    <button  type='button' data-liked=<?php //echo $post['is_liked']; ?> class='likeBtn flex unlike'>
                        <div class='likeIcon' ><?php //echo IconsHelper::getIcon('outline-hart') ?></div>
                        <span class='likeCount' ><?php //echo htmlspecialchars($post->getLikeCount()); ?></span>
                    </button>
                    
                -->
                    <button  type='button' data-modal-target="crud-modal" data-modal-toggle="crud-modal" class='flex commentBtn'>
                        <?php echo IconsHelper::getIcon('comment') ?>
                        <span class="commentCount"><?php echo htmlspecialchars(($post['comment_count'] ?? 0));?></span>
                    </button>
                    <?php $isLiked = isset($post['is_liked']) && $post['is_liked'] === 1; ?>
                    <button  type='button' data-liked=<?php echo ($isLiked  ? 'true' : 'false'); ?> class='likeBtn flex unlike'>
                        <div class='likeIcon' ><?php echo IconsHelper::getIcon('outline-hart') ?></div>
                        <span class='likeCount' ><?php echo htmlspecialchars($post['likes_count']); ?></span>
                    </button>

                    </div>
                </div>
            </div>
        </div>
    <!-- 他のツイートも同様に -->
    </div>
<?php endforeach; ?>


