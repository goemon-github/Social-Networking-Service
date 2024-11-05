<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class CreateFollowsTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE follows(
                follower_id BIGINT NOT NULL,
                followed_id BIGINT NOT NULL,
                followed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (follower_id, followed_id),
                FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (followed_id) REFERENCES users(id) ON DELETE CASCADE
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ["DROP TABLE follows"];
    }


}