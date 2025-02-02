<?php 
namespace src\Models;

use src\Models\Interfaces\Model;
use src\Models\Traits\GenericModel;
use src\Models\DateTimeStamp;

class Post implements Model {
    use GenericModel;

    public function __construct(
        private string $content,
        private int $user_id,
        private int $like_count = 0,
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

    public function getLikeCount(): int{
        return $this->like_count;
    } 

    public function setLikeCount(int $like_count) {
        $this->like_count = $like_count;
    }

    public function getCountet(): string {
        return $this->content;
    }
     
    public function setContent(string $content) {
        $this->content = $content;
    }

}