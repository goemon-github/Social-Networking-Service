<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class CreateUserTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE users (
            id BIGINT PRIMARY KEY AUTO_INCREMENT,
            
            "

        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [];
    }


}