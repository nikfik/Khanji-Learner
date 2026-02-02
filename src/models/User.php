<?php
class User {
    private $id;
    private $username;
    private $email;
    private $password_hash;
    private $name;
    private $surname;
    private $bio;
    private $created_at;
    private $last_login;

    public function __construct(
        ?int $id = null,
        string $username = '',
        string $email = '',
        string $password_hash = '',
        string $name = '',
        string $surname = '',
        ?string $bio = null,
        ?string $created_at = null,
        ?string $last_login = null
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->name = $name;
        $this->surname = $surname;
        $this->bio = $bio;
        $this->created_at = $created_at;
        $this->last_login = $last_login;
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
    }

    // WYTYCZNA #22: Hasło nie jest przekazywane do widoków ani echo/var_dump
    // Brak metody getPassword() - hasło nigdy nie jest zwracane
    
    public function getPasswordHash(): string {
        return $this->password_hash;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getSurname(): string {
        return $this->surname;
    }

    public function getBio(): ?string {
        return $this->bio;
    }

    public function getCreatedAt(): ?string {
        return $this->created_at;
    }

    public function getLastLogin(): ?string {
        return $this->last_login;
    }

    // WYTYCZNA #23: Z bazy pobieram tylko minimalny zestaw danych o użytkowniku
    // Metoda zwraca tylko podstawowe dane bez wrażliwych informacji
    public function getBasicInfo(): array {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'name' => $this->name,
            'surname' => $this->surname
        ];
    }
}
