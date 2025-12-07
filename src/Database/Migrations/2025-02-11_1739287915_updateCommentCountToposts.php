<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class UpdateCommentCountToposts implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return ['UPDATE posts SET comment_count = 0 WHERE comment_count IS NULL'];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ['ALTER TABLE posts DROP COLUMN comment_count;'];
    }


}