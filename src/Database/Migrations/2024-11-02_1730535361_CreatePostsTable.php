<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class CreatePostsTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'CREATE TABLE IF NOT EXISTS posts(
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id BIGINT NOT NULL,
            content TEXT NOT NULL,
            likes_count INT DEFAULT 0,
            created_at Datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
            )'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ['DROP TABLE posts'];
    }


}