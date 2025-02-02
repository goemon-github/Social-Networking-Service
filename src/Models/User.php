<?php
namespace src\Models;

use src\Models\Interfaces\Model;
use src\Models\Traits\GenericModel;

class User implements Model{
    use GenericModel;

    public function __construct(
        private string $userName, 
        private string $password,
        private string $email,
        private ?int $id = null ,
        private ?string $accountName = '',
        private bool $emailVerified = false,
        private ?string $profile = '',
        private ?string $imagePath = '',
        private ?DateTimeStamp $dateTimeStamp  = null,
    ){}

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setUserName(string $userName): void{
        $this->userName = $userName;
    }
    
    public function getUserName(): string {
        return $this->userName ;
    }

    public function setAccountName(string $accountName): void {
        $this->accountName = $accountName;
    }

    public function getAccountName(): string {
        return $this->accountName;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmailVerified(bool $verified):void {
        $this->emailVerified = $verified;
    }

    public function getEmailVerified(): bool {
        return $this->emailVerified;
    }

    public function setProfile(string $profile): void {
        $this->profile = $profile;
    }

    public function getProfile(): string {
        return $this->profile;
    }

    public function setImagePath(string $imagePath): void {
        $this->imagePath= $imagePath;
    }

    public function getImagePath(): string {
        return $this->imagePath;
    }

    public function generateSignedURLQueryParams(int $time = 30): array {
        $lasts = 1 * 60 * $time;
        $queryParameters = [
            'id' => $this->getId(),
            'user' => hash('sha256', $this->getEmail()),
            'expiration' => time() + $lasts,
        ];
        return $queryParameters;
    }
}