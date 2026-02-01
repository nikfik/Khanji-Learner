<?php
require_once __DIR__.'/../../Database.php';

class Repository {
    protected $database;

    public function __construct()
    {
        // WYTYCZNA #4: Używamy singletonu Database
        $this->database = Database::getInstance();
    }
    
    protected function getConnection(): PDO {
        return $this->database->getConnection();
    }
}