<?php
require_once 'Repository.php';

class ModuleRepository extends Repository {
    
    /**
     * Pobiera wszystkie moduły posortowane według display_order
     */
    public function getAllModules(): array {
        $connection = $this->getConnection();
        $stmt = $connection->prepare('
            SELECT id, name, level, description, character_count, display_order 
            FROM modules 
            ORDER BY display_order ASC
        ');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Pobiera moduły pogrupowane po poziomach trudności
     */
    public function getModulesByLevel(): array {
        $allModules = $this->getAllModules();
        $groupedModules = [];
        
        foreach ($allModules as $module) {
            $level = $module['level'];
            if (!isset($groupedModules[$level])) {
                $groupedModules[$level] = [];
            }
            $groupedModules[$level][] = $module;
        }
        
        return $groupedModules;
    }
    
    /**
     * Wyszukuje moduły po nazwie
     */
    public function searchModules(string $searchTerm): array {
        $connection = $this->getConnection();
        $stmt = $connection->prepare('
            SELECT id, name, level, description, character_count, display_order 
            FROM modules 
            WHERE LOWER(name) LIKE LOWER(:searchTerm)
            ORDER BY display_order ASC
        ');
        $searchPattern = '%' . $searchTerm . '%';
        $stmt->bindParam(':searchTerm', $searchPattern, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Pobiera moduły dla konkretnego poziomu
     */
    public function getModulesBySpecificLevel(string $level): array {
        $connection = $this->getConnection();
        $stmt = $connection->prepare('
            SELECT id, name, level, description, character_count, display_order 
            FROM modules 
            WHERE level = :level
            ORDER BY display_order ASC
        ');
        $stmt->bindParam(':level', $level, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Pobiera szczegóły pojedynczego modułu
     */
    public function getModuleById(int $id): ?array {
        $connection = $this->getConnection();
        $stmt = $connection->prepare('
            SELECT id, name, level, description, character_count, display_order 
            FROM modules 
            WHERE id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Pobiera postęp użytkownika dla danego modułu (jeśli będzie potrzebne w przyszłości)
     */
    public function getUserProgress(int $userId, int $moduleId): int {
        // TODO: Implementacja po dodaniu tabeli module_progress
        // Na razie zwraca losowy procent dla demonstracji
        return rand(0, 100);
    }
}
