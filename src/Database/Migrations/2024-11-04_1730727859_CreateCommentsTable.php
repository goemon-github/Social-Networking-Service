<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class CreateCommentsTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE comments(
                id BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                post_id INT NOT NULL,
                user_id BIGINT NOT NULL,
                content TEXT NOT NULL,
                parent_comment_id BIGINT,
                likes_count BIGINT DEFAULT 0,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (post_id) REFERENCES posts(id),
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (parent_comment_id) REFERENCES comments(id)
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ["DROP TABLE comments"];
    }


}