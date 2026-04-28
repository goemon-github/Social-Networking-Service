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
        private string $account_name = '',
        private string $display_name = '',
        private ?int $parent_post_id = null,
        private int $comment_count = 0,
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

    public function setAccountName(string $account_name): void {
        $this->account_name = $account_name;
    }

    public function getAccountName(): string{
        return $this->account_name; 
    }

    public function setDisplayName(string $display_name): void {
        $this->display_name = $display_name;
    }

    public function getDisplayName(): string{
        return $this->display_name; 
    }

    public function getParentPostId(): ?int{
        return $this->parent_post_id;
    }

    public function setParentPostId(int $parent_post_id): void{
        $this->parent_post_id = $parent_post_id;
    }

    public function getCommentCount(): int{
        return $this->comment_count;
    } 

    public function setCommnetCount(int $comment_count) {
        $this->comment_count = $comment_count;
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