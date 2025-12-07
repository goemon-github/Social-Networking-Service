<?php
namespace src\Database\DataAccess\Implemetations;

use Exception;
use LDAP\Result;
use mysqli_sql_exception;
use src\Database\DataAccess\Interfaces\PostDAO;
use src\Database\DatabaseManager;
use src\Models\Post;
use src\Models\DateTimeStamp;
use src\Helpers\Authenticate;

class PostDAOImpl implements PostDAO {

    public function create(Post $post): bool {
        if($post->getPostId() !== null) throw new Exception("Cannot create a post with an existing ID. id: " . $post->getPostId());
        $mysqli = DatabaseManager::getMysqliConnection();
        $query = "INSERT INTO posts (user_id, user_name, comment_count, content) VALUES (?, ?, ?, ?)";
        $result = $mysqli->prepareAndExecute(
            $query,
            'isis',
            [
                $post->getUserId(),
                $post->getUserName(),
                $post->getCommentCount(),
                $post->getContent()
            ]
        );

        if(!$result) return false;
        //$post->setPostId($mysqli->insert_id);
        return $result;
    }

    public function delete(int $post_id): bool {
        $mysqli = DatabaseManager::getMysqliConnection();
        $query = "DELETE FROM posts WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $post_id);
        $result = $stmt->execute();

        return $result;
    
    }

    public function getById(int $post_id): ?Post {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM  posts WHERE id = ?";

        $result = $mysqli->prepareAndFetchAll($query, 'i', [$post_id]);

         
        if(!$result || count($result) === 0) return null;

        return $this->rawDataToPost($result[0]);
    }

    public function getByUserId(int $user_id): ?Post {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM posts WHERE user_id = ?";

        $result = $mysqli->prepareAndFetchAll($query, 'i', [$user_id]);

        if(!$result) return false;
        return $this->rawDataToPost($result);
    }

    public function getUserPosts(int $user_id): ?array {
        $mysqli = DatabaseManager::getMysqliConnection();
        $query = 
        'SELECT posts.*, users.user_name 
        FROM posts
        JOIN users ON users.id = posts.user_id
        WHERE posts.user_id = ?
        ORDER BY posts.created_at DESC';
        $reslut = $mysqli->prepareAndFetchAll($query, 'i', [$user_id]);

        if(!$reslut) return false;

        $posts = [];
        foreach($reslut as $data){
            $posts[] = $this->rawDataToPost($data);
        }

        return $posts;
    }

    public function getAll(): array {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM posts JOIN users ON users.id = posts.user_id  ORDER BY posts.created_at DESC";

        $result = $mysqli->query($query);
        if(!$result) return false;

        $posts = [];
        while($row = $result->fetch_assoc()){
            $posts[] = new Post(
                user_id: $row['user_id'],
                post_id: $row['id'],
                user_name: $row['user_name'],
                content: $row['content'],
                likes_count: $row['likes_count'],
                dateTimeStamp: new DateTimeStamp($row['created_at'], $row['updated_at'])
            );
        }

        return $posts;
    }


    public function updateContent(int $id, string $content): bool {
        $mysqli = DatabaseManager::getMysqliConnection();
        $query = "UPDATE posts SET content = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('si', $content, $id);
        $result = $stmt->execute();

        return $result;
    }

    /**
     * ポストのいいね数を+1 / -1 する
     *  @return bool
     */
    public function countLikes(int $post_id, bool $status): bool {
        $mysqli = DatabaseManager::getMysqliConnection();
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $likeDelta = $status ? 1 : -1;

        $query = "UPDATE posts SET likes_count = GREATEST(likes_count + ?, 0) WHERE id = ?";

        try{
            $result = $mysqli->prepareAndExecute($query, 'ii',  [$likeDelta, $post_id]);
            return $result;
        }catch (mysqli_sql_exception $e){
            error_log(sprintf(
                '[likes][ERR] (%d) %s [SQL: %s] [delta=%d, post_id=%d]',
                $e->getCode(),
                $e->getMessage(),
                $query,
                $likeDelta,
                $post_id
            ));
            throw $e;
        };
    }

    /**
     * いいねを押しているのかbooleanで取得する
     * @return bool
     */
    public function getIsLike(int $post_id, int $user_id): bool {
        $mysqli = DatabaseManager::getMysqliConnection();
        $query =  "SELECT EXISTS(
        SELECT 1 FROM post_likes
        WHERE user_id = ? AND post_id = ?) as liked";

        $result = $mysqli->prepareAndExecute($query, 'i', [$user_id, $post_id]) ;
        if(!$result) return 0;
        return $result;

    }

    /**
     * 全ての投稿の取得といいねがされているかをセットで取得する
     * @return array
     */
    public function getAllPostsAndIsLiked ( int $user_id): array {
        $mysqli = DatabaseManager::getMysqliConnection();
        $query =  "SELECT p.*, (pl.post_id IS NOT NULL) AS is_liked
        FROM posts AS p
        LEFT JOIN post_likes AS pl
        ON pl.post_id = p.id
        AND pl.user_id = ?
        ORDER BY p.created_at DESC ";

        $result = $mysqli->prepareAndFetchAll($query, 'i', [$user_id]) ;

        return $result;
    }

    /**
     * 自分投稿の取得といいねがされているかをセットで取得する
     * @return array
     */
    public function getUserPostsAndIsLiked ( int $user_id): array {
        $mysqli = DatabaseManager::getMysqliConnection();
        $query =  "SELECT p.*, (pl.post_id IS NOT NULL) AS is_liked
        FROM posts AS p
        LEFT JOIN post_likes AS pl
        ON pl.post_id = p.id
        AND pl.user_id = ?
        WHERE p.user_id = ?
        ORDER BY p.created_at DESC ";

        $result = $mysqli->prepareAndFetchAll($query, 'ii', [$user_id, $user_id]) ;

        return $result;
    }
    
    /**
     * ポストのいいね数を取得する
     *  @return int
     */
    public function getLikeCount(int $post_id): int{
        $mysqli = DatabaseManager::getMysqliConnection();
        $query =  "SELECT likes_count FROM posts WHERE id = ?";
        $result = $mysqli->prepareAndFetchAll($query, 'i', [$post_id]);
        if(!$result) return 0;
        return $result;
    }

    /**
     *  ユーザーが指定した投稿に「いいね」をつけます
     *  @return bool
     */
    public function likePost(int $post_id, int $user_id): bool{
        $mysqli = DatabaseManager::getMysqliConnection();
        #$query = "SELECT post_id FROM post_likes WHERE user_id = ? AND status = 1";

        $query = "INSERT INTO post_likes (user_id, post_id, status, created_at, updated_at)
        VALUES (?, ?, 1, NOW(), NOW()) as new
        ON DUPLICATE KEY UPDATE status = 1, updated_at = NOW()";

        $result = $mysqli->prepareAndExecute($query, 'ii', [$user_id, $post_id]);
        if(!$result) return 0;
        return $result;
    }

    /**
     *  ユーザーが指定した投稿に「いいね」を解除します
     *  @return bool
     */
    public function unLikePost(int $post_id, int $user_id): bool{
        $mysqli = DatabaseManager::getMysqliConnection();
        $query = "DELETE FROM post_likes WHERE user_id=? AND post_id=? ";
        $result = $mysqli->prepareAndExecute($query, 'ii', [$user_id, $post_id]);
        error_log($result);
        if(!$result) return 0;
        return $result;
        
    }
    /**
     * ポストのコメントを取得する
     */
    public function getCommentCount(int $post_id): int {
        $mysqli = DatabaseManager::getMysqliConnection();
        $query = 'SELECT COUNT(*) AS comment_count from posts WHERE parent_post_id = ?;';
        $result = $mysqli->prepareAndFetchAll($query, 'i', [$post_id]);
        if(!$result) return false;
        $resultRow= $result[0];
        return $resultRow['comment_count'] ? $resultRow['comment_count'] : 0;
    }

    public function insertParentPostId(int $user_id, string $content, int $parent_post_id): bool {
        $mysqli = DatabaseManager::getMysqliConnection();
        $query = 'INSERT INTO posts (user_id, content, parent_post_id) VALUES (?, ?, ?);';
        $result = $mysqli->prepareAndExecute($query, 'isi', [$user_id, $content, $parent_post_id]);
        if(!$result) return false;
        return $result;
    }

    private function rawDataToPost(array $data): Post {
        return  new Post(
            post_id: $data['id'],
            user_id: $data['user_id'],
            user_name: $data['user_name'],
            parent_post_id: $data['parent_post_id'],
            content: $data['content'],
            comment_count: $this->getCommentCount($data['user_id']),
            likes_count: $data['likes_count'],
            dateTimeStamp: new DateTimeStamp($data['created_at'], $data['updated_at'])
        );
    }

}