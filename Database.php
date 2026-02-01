<?php
//TODO zamienic config na .env 
require_once "config.php";

// WYTYCZNA #4: UserRepository zarządzany jako singleton
class Database {
    private static $instance = null;
    private $connection;
    private $username;
    private $password;
    private $host;
    private $database;

    private function __construct()
    {
        $this->username = USERNAME;
        $this->password = PASSWORD;
        $this->host = HOST;
        $this->database = DATABASE;
        $this->connect();
    }

    // Zapobiegaj klonowaniu instancji
    private function __clone() {}

    // Zapobiegaj deserializacji instancji
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    private function connect(): void
    {
        try {
            $this->connection = new PDO(
                "pgsql:host=$this->host;port=5432;dbname=$this->database",
                $this->username,
                $this->password,
                ["sslmode"  => "prefer"]
            );

            // WYTYCZNA #20: W produkcji nie pokazuję stack trace / surowych błędów użytkownikowi
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Dodatkowa ochrona przed SQL injection
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        catch(PDOException $e) {
            // WYTYCZNA #20: W produkcji nie pokazuję stack trace / surowych błędów użytkownikowi
            error_log("Database connection failed: " . $e->getMessage());
            die("Wystąpił błąd połączenia z bazą danych. Spróbuj ponownie później.");
        }
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}