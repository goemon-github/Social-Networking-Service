<?php 
namespace src\Database;

interface SchemaMigration {
    public function up(): array;
    public function down(): array;
}