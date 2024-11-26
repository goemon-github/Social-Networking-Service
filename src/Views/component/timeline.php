<?php
use src\Database\MySQLWrapper;

$mysqli = new MySQLWrapper();
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
$posts =  $result->fetch_all(MYSQLI_ASSOC);

?>

<?php foreach ($posts as $post): ?>
    <!-- ツイート一覧 -->
    <div class="space-y-4 mb-2">
        <div class="p-4 bg-gray-100 rounded-lg shadow-md ">
            <div class="flex items-start space-x-4">
                <img src="https://via.placeholder.com/50" class="w-12 h-12 rounded-full" alt="Avatar">
                <div>
                    <h3 class="text-sm font-bold"><?php echo htmlspecialchars($post['user_name']) ?> <span class="text-gray-500">@username</span></h3>
                    <p class="text-sm text-gray-700 mt-1"><?php echo htmlspecialchars($post['content']) ?></p>
                    <div class="flex items-center space-x-4 mt-2 text-gray-500">
                    <span> 10</span>
                    <span> 5</span>
                    <span>️ 20</span>
                    </div>
                </div>
            </div>
        </div>
    <!-- 他のツイートも同様に -->
    </div>
<?php endforeach; ?>