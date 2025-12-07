<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class CreateCommentImagesTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE IF NOT EXISTS comment_images(
                comment_id BIGINT NOT NULL,
                image_id BIGINT NOT NULL,
                PRIMARY KEY (comment_id, image_id),
                FOREIGN KEY (comment_id) REFERENCES comments(id) ON DELETE CASCADE,
                FOREIGN KEY (image_id) REFERENCES images(id) ON DELETE CASCADE
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ["DROP TABLE comment_images"];
    }


}