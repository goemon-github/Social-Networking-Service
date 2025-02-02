<?php

namespace src\Database\DataAccess\Implemetations;

use Exception;
use src\Database\DataAccess\Interfaces\UserDAO;
use src\Database\DatabaseManager;
use src\Hepers\DatabaseHeler;
use src\Models\User;
use src\Models\DateTimeStamp;

class UserDAOImpl implements UserDAO {

    public function create(User $user, string $password): bool {
        if($user->getId() !== null) throw new Exception("Cannot create a user with an existing ID. id: " . $user->getId());

        $mysqli = DatabaseManager::getMysqliConnection();

        $query = " INSERT INTO users (user_name, password, email) VALUES (?, ?, ?)";

        $result = $mysqli->prepareAndExecute(
            $query,
            "sss",
            [
                $user->getUserName(),
                password_hash($password, PASSWORD_DEFAULT),
                $user->getEmail()
            ]
        );

        if(!$result) return false;

        $user->setId($mysqli->insert_id);

        return true;
    }


    public function getHashedPasswordById(int $id): ?string {

        return $this->getRawById($id)['password']??null;
    }

    // update
    public function update(int $id, array $data) {}


    public function getById(int $id): ?User {
        $userRaw = $this->getRawById($id);
        if($userRaw === null) return null;
        return $this->rawDataToUser($userRaw);
    }

    public function getByEmail(string $email): ?User{
       $userRaw = $this->getRawByEmail($email);
       if($userRaw === null) return null;

       return $this->rawDataToUser($userRaw);
    }

    private function getRawById(int $id): ?array {

        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM users WHERE id = ?";

        $result = $mysqli->prepareAndFetchAll($query, 'i', [$id])[0] ?? null;

        if(!$result === null) return null;
        return $result;
    }

    private function getRawByEmail(string $email): ?array {
        $mysqli = DatabaseManager::getMysqliConnection();
 
        $query = "SELECT * FROM users WHERE email = ?";

        $result = $mysqli->prepareAndFetchAll($query, 's', [$email])[0] ?? null;
 
        if ($result === null) return null;

        return $result;
    }

    private function rawDataToUser(array $rawData): User {
        return  new User(
            id: $rawData['id'],
            userName: $rawData['user_name'],
            accountName: $rawData['account_name'] ?? null,
            password: $rawData['password'],
            email: $rawData['email'],
            emailVerified: $rawData['email_verified'],
            profile: $rawData['profile'] ?? null,
            imagePath: $rawData['image_url'] ?? null,
            dateTimeStamp: new DateTimeStamp($rawData['created_at'], $rawData['updated_at']),
        );
    }


    public function updateEmailVerified(User $user): bool{
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = 
            <<< SQL
                update users
                set email_verified = ?
                where id = ? 
            SQL;


        $result = $mysqli->prepareAndExecute(
            $query,
            "ii",
            [
                $user->getEmailVerified(),
                $user->getId(),
            ],
        );

        if(!$result) throw new Exception("Falied to update emali Verified: " . $user->getId());

        return $result;
    }

    private function updateProfile(User $user): bool {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = 
            <<< SQL
                update users
                set profile = ?
                where id = ? 
            SQL;


        $result = $mysqli->prepareAndExecute(
            $query,
            "si",
            [
                $user->getProfile(),
                $user->getId(),
            ],
        );

        if(!$result) throw new Exception("Falied to update emali Verified: " . $user->getId());

        return $result;
    }
}