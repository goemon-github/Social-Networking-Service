<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class AddCommentCountColumnToPosts implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE posts ADD COLUMN comment_count INT AFTER parent_post_id;'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ['ALTER TABLE posts DROP COLUMN comment_count'];
    }

}