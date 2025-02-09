<?php
namespace src\Database\DataAccess\Implemetations;

use Exception;
use src\Database\DataAccess\Interfaces\PostDAO;
use src\Database\DatabaseManager;
use src\Models\Post;
use src\Models\DateTimeStamp;

class PostDAOImpl implements PostDAO {

    public function create(Post $post): bool {
        if($post->getPostId() !== null) throw new Exception("Cannot create a post with an existing ID. id: " . $post->getPostId());
        $mysqli = DatabaseManager::getMysqliConnection();
        $query = "INSERT INTO posts (user_id, content) VALUES (?, ?)";
        $result = $mysqli->prepareAndExecute(
            $query,
            'is',
            [
                $post->getUserId(),
                $post->getContent()
            ]
        );

        if(!$result) return false;
        $post->setPostId($mysqli->insert_id);
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


    public function delete(int $post_id): bool {
        $mysqli = DatabaseManager::getMysqliConnection();
        $query = "DELETE FROM posts WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $post_id);
        $result = $stmt->execute();

        return $result;
    
    }

    public function countLikes(int $post_id, bool $status): bool {
        $mysqli = DatabaseManager::getMysqliConnection();
        if($status){
            $query = "UPDATE posts SET likes_count = likes_count + 1 WHERE id = ?";
        }else {
            $query = "UPDATE posts SET likes_count = likes_count - 1 WHERE id = ?";
        }

        $result = $mysqli->prepareAndExecute($query, 'i', [$post_id]);
        if(!$result) return false;
        return $result;
    }

    private function rawDataToPost(array $data): Post {
        return  new Post(
            post_id: $data['id'],
            user_id: $data['user_id'],
            user_name: $data['user_name'],
            content: $data['content'],
            likes_count: $data['likes_count'],
            dateTimeStamp: new DateTimeStamp($data['created_at'], $data['updated_at'])
        );
    }

}