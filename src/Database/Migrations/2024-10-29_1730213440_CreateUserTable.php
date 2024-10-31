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
            user_name VARCHAR(255) NOT NULL,
            password VARCHAR(32) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            email_verified BOOLEAN NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE users"
        ];
    }


}