<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class AddAccountNameTousersTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'ALTER TABLE users 
            ADD COLUMN account_name VARCHAR(255)
            '];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ['ALTER TABLE users 
        DROP COLUMN account_name
        '];
    }


}