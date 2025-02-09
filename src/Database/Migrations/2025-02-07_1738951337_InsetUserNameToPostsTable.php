<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class InsetUserNameToPostsTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'UPDATE posts
            JOIN users ON users.id = posts.user_id
            SET posts.user_name = users.user_name;
            '
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE posts DROP COLUMN IF EXISTS user_name;'
        ];
    }


}