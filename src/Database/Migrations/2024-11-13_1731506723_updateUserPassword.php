<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class UpdateUserPassword implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE users MODIFY COLUMN password VARCHAR(512) NOT NULL'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            'ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NOT NULL'
        ];
    }
}