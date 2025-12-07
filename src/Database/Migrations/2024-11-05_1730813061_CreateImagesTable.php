<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class CreateImagesTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE IF NOT EXISTS images (
                id BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                user_id BIGINT NOT NULL,
                image_path VARCHAR(255) NOT NULL,
                upload_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id)

            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ["DROP TABLE images"];
    }


}