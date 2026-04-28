<?php

namespace src\database\migrations;

use src\database\SchemaMigration;

class CreateUserTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE IF NOT EXISTS users (
            id BIGINT PRIMARY KEY AUTO_INCREMENT,
            account_name VARCHAR(255) NOT NULL UNIQUE,
            display_name VARCHAR(255),
            password VARCHAR(512) NOT NULL,
            profile TEXT,
            image_url VARCHAR(512),
            email VARCHAR(255) NOT NULL UNIQUE,
            email_verified BOOLEAN NOT NULL DEFAULT 0,
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