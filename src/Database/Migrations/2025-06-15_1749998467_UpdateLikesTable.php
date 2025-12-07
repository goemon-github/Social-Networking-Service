<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class UpdateLikesTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE post_likes ADD COLUMN status TINYINT(1) NOT NULL DEFAULT 1",
            "ALTER TABLE post_likes ADD COLUMN created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",
            "ALTER TABLE post_likes ADD COLUMN updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE post_likes DROP COLUMN status",
            "ALTER TABLE post_likes DROP COLUMN created_at", 
            "ALTER TABLE post_likes DROP COLUMN updated_at" 
        ];
    }


}