<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/User.php';

// WYTYCZNA #4: UserRepository zarządzany jako singleton
class UserRepository extends Repository
{
    private static $instance = null;

    private function __construct() {
        parent::__construct();
    }

    // Zapobiegaj klonowaniu
    private function __clone() {}

    // Zapobiegaj deserializacji
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }

    public static function getInstance(): UserRepository {
        if (self::$instance === null) {
            self::$instance = new UserRepository();
        }
        return self::$instance;
    }

    // WYTYCZNA #1: Ochrona przed SQL injection (prepared statements / brak konkatenacji SQL)
    // WYTYCZNA #18: Przy rejestracji sprawdzam, czy email jest już w bazie
    public function emailExists(string $email): bool {
        // WYTYCZNA #1: Używamy prepared statements
        $stmt = $this->getConnection()->prepare('
            SELECT COUNT(*) as count FROM users WHERE email = :email
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // WYTYCZNA #1: Ochrona przed SQL injection (prepared statements)
    // WYTYCZNA #10: Hasła przechowywane jako hash (bcrypt/Argon2, password_hash)
    public function createUser(string $email, string $password, string $name, string $surname): bool {
        try {
            // WYTYCZNA #10: Używamy password_hash z bcrypt
            $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

            // WYTYCZNA #1: Prepared statement
            $stmt = $this->getConnection()->prepare('
                INSERT INTO users (email, password_hash, name, surname, created_at)
                VALUES (:email, :password_hash, :name, :surname, NOW())
            ');

            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password_hash', $passwordHash, PDO::PARAM_STR);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            // WYTYCZNA #20: W produkcji nie pokazuję stack trace / surowych błędów użytkownikowi
            // WYTYCZNA #11: Hasła nigdy nie są logowane w logach / errorach
            error_log("User creation failed for email: " . $email);
            return false;
        }
    }

    // WYTYCZNA #1: Ochrona przed SQL injection (prepared statements)
    // WYTYCZNA #23: Z bazy pobieram tylko minimalny zestaw danych o użytkowniku
    public function getUserByEmail(string $email): ?User {
        // WYTYCZNA #1: Prepared statement
        $stmt = $this->getConnection()->prepare('
            SELECT id, email, password_hash, name, surname, created_at, last_login
            FROM users 
            WHERE email = :email
        ');
        
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            return null;
        }

        // WYTYCZNA #23: Zwracamy tylko potrzebne dane
        return new User(
            $userData['id'],
            $userData['email'],
            $userData['password_hash'],
            $userData['name'],
            $userData['surname'],
            $userData['created_at'],
            $userData['last_login']
        );
    }

    // WYTYCZNA #1: Ochrona przed SQL injection (prepared statements)
    public function updateLastLogin(int $userId): bool {
        try {
            $stmt = $this->getConnection()->prepare('
                UPDATE users 
                SET last_login = NOW() 
                WHERE id = :user_id
            ');
            
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            // WYTYCZNA #20: Nie pokazujemy błędów użytkownikowi
            error_log("Failed to update last login for user ID: " . $userId);
            return false;
        }
    }

    public function getUsers(): ?array
    {
        // WYTYCZNA #1: Prepared statement (nawet bez parametrów, dla spójności)
        $stmt = $this->getConnection()->prepare('
            SELECT * FROM users
        ');
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $users;
    }
}