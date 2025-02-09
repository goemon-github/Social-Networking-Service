<?php 
namespace src\Models;

use PDO;
use src\Models\Interfaces\Model;
use src\Models\Traits\GenericModel;
use src\Models\DateTimeStamp;

class Post implements Model {
    use GenericModel;

    public function __construct(
        private string $content,
        private int $user_id,
        private string $user_name,
        private int $likes_count = 0,
        private ?int $post_id = null,
        private ?DateTimeStamp $dateTimeStamp = null,
    ){}

    public function getPostId(): ?int {
        return $this->post_id;
    }

    public function setPostId(int $post_id) {
        $this->post_id = $post_id;
    }

    public function getUserId(): int{
        return $this->user_id;
    }

    public function setUserId(int $user_id) {
        $this->user_id = $user_id;
    }

    public function getUserName(): string{
        return $this->user_name; 
    }

    public function setUserName(int $user_name): void {
        $this->user_name = $user_name;
    }

    public function getLikeCount(): int{
        return $this->likes_count;
    } 

    public function setLikeCount(int $likes_count) {
        $this->likes_count = $likes_count;
    }

    public function getContent(): string {
        return $this->content;
    }
     
    public function setContent(string $content) {
        $this->content = $content;
    }

}