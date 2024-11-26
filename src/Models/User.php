<?php
namespace src\Models;

use src\Models\Interfaces\Model;
use src\Models\Traits\GenericModel;

class User implements Model{
    use GenericModel;

    public function __construct(
        private ?int $id,
        private string $userName,
        private string $accountName,
        private string $email,
        private bool $emaiVerified = false,
        private string $profile,
        private string $imageUrlHash,
        private ?DateTimeStamp $dateTimeStamp = null,
    ){}

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getId(): int {
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

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmailVerified(bool $verified):void {
        $this->emaiVerified = $verified;
    }

    public function getEmailVerified(): bool {
        return $this->emaiVerified;
    }

    public function setProfile(string $profile): void {
        $this->profile = $profile;
    }

    public function getProfile(): string {
        return $this->profile;
    }

    public function setImageUrlHash(string $imageUrlHash): void {
        $this->imageUrlHash = $imageUrlHash;
    }

    public function getImageUrlHash(): string {
        return $this->imageUrlHash;
    }
}