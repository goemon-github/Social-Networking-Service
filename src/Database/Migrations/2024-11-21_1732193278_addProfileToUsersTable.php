<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class AddProfileToUsersTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE users 
            ADD COLUMN profile TEXT,
            ADD COLUMN image_url VARCHAR(512)
            '];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ['ALTER TABLE users 
        DROP COLUMN profile,
        DROP COLUMN image_url
        '];
    }


}