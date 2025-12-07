<?php
namespace src\Database\DataAccess\Interfaces;
use src\Models\Post;

interface PostDAO {
    public function create(Post $post): bool;
    public function getById(int $id): ?Post;
    public function getByUserId(int $id): ?Post;
    public function getUserPosts(int $user_id): ?array;
    public function getAll(): array;
    public function updateContent(int $id, string $content): bool;
    public function countLikes(int $post_id, bool $status):bool;
    public function likePost(int $post_id, int $user_id):bool;
    public function unlikePost(int $post_id, int $user_id):bool;
    public function getLikeCount(int $post_id):int;
    public function getAllPostsAndIsLiked (int $user_id): array;
    public function getUserPostsAndIsLiked(int $user_id): array;
    public function getCommentCount(int $post_id): int;
    public function delete(int $post_id): bool;
}