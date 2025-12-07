<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class CreatePostImagesTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE IF NOT EXISTS post_images(
                post_id INT NOT NULL,
                image_id BIGINT NOT NULL,
                PRIMARY KEY (post_id, image_id),
                FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
                FOREIGN KEY (image_id) REFERENCES images(id) ON DELETE CASCADE
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ["DROP TABLE post_images"];
    }


}