<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class AddReplyColumnToPosts implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE posts ADD COLUMN parent_post_id INT NULL AFTER user_name;'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ['ALTER TABLE posts DROP COLUMN parent_post_id'];
    }


}