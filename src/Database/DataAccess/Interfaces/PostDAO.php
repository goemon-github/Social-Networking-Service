<?php
namespace src\Database\DataAccess\Interfaces;
use src\Models\Post;

interface PostDAO {
    public function create(Post $post): bool;
    public function getById(int $id): ?Post;
    public function getByUserId(int $id): ?Post;
    public function getAll(): array;
    public function updateContent(int $id, string $content): bool;
    public function delete(int $post_id): bool;
}