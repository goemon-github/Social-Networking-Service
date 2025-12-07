<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class CreateLikesTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE IF NOT EXISTS post_likes (
                id BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                user_id BIGINT NOT NULL,
                post_id INT NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ["DROP TABLE post_likes"];
    }


}