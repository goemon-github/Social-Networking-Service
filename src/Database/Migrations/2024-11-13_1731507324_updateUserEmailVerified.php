<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class UpdateUserEmailVerified implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE users MODIFY COLUMN email_verified BOOLEAN NOT NULL DEFAULT 0'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE users MODIFY COLUMN email_verified BOOLEAN NOT NULL DEFAULT 0'
        ];
    }


}