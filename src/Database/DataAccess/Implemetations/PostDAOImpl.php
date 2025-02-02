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
        $query = "INSERT INTO posts (user_id, content) VALUES (? ?)";
        $result = $mysqli->prepareAndExecute(
            $query,
            'ds',
            [
                $post->getUserId(),
                $post->getCountet()
            ]
        );

        if(!$result) return false;
        $post->setPostId($mysqli->insert_id);
        return $result;
    }


    public function getById(int $id): ?Post {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT FROM * posts WHERE id = ?";

        $result = $mysqli->prepareAndFetchAll($query, 'i', [$id]);

        if(!$result) return false;
        return $this->rawDataToPost($result);
    }

    public function getByUserId(int $user_id): ?Post {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT FROM * posts WHERE user_id = ?";

        $result = $mysqli->prepareAndFetchAll($query, 'i', [$user_id]);

        if(!$result) return false;
        return $this->rawDataToPost($result);
    }

    public function getAll(): array {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT FROM * posts ORDER BY created_at DESC";

        $result = $mysqli->query($query);
        if(!$result) return false;

        $posts = [];
        while($row = $result->fetch_assoc()){
            $posts[] = new Post(
                user_id: $row['id'],
                post_id: $row['post_id'],
                content: $row['content'],
                like_count: $row['like_count'],
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

    private function rawDataToPost(array $data): Post {
        return new Post(
            post_id: $data['id'],
            user_id: $data['user_id'],
            content: $data['content'],
            like_count: $data['like_count'],
            dateTimeStamp: new DateTimeStamp($data['created_at'], $data['updated_at'])
        );
    }
}