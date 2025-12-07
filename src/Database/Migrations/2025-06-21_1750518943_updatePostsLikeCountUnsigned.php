<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class UpdatePostsLikeCountUnsigned implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE posts MODIFY likes_count INT UNSIGNED NOT NULL DEFAULT 0",
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ['ALTER TABLE posts MODIFY likes_count INT UNSIGNED NOT NULL DEFAULT 0'];
    }


}