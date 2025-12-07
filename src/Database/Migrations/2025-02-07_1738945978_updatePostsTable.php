<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class UpdatePostsTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE posts ADD COLUMN user_name VARCHAR(255) NULL AFTER user_id;'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE posts
            DROP COLUMN user_name
        '];
    }


}