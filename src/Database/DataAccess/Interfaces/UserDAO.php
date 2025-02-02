<?php 
namespace src\Database\DataAccess\Interfaces;

use src\Models\User;

interface UserDAO {
    // create
    public function create(User $user, string $password): bool;
    // read
    public function getHashedPasswordById(int $id): ?string;
    public function getById(int $id): ?User;
    public function getByEmail(string $email): ?User;
    // update
    public function update(int $id, array $data);
}