<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class UpdatePostLikesAddUnique implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return ['ALTER TABLE post_likes ADD UNIQUE KEY unique_user_post(user_id, post_id)'];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ["ALTER TABLE post_likes DROP INDEX unique_user_post"];
    }


}