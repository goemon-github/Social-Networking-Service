<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class CreateCommentsLikesTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE IF NOT EXISTS comments_likes (
                id BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                user_id BIGINT NOT NULL,
                comment_id BIGINT,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (comment_id) REFERENCES comments(id) ON DELETE CASCADE
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ["DROP TABLE comments_likes"];
    }


}