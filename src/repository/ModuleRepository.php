<?php
require_once 'Repository.php';

class ModuleRepository extends Repository {
    
    /**
     * Pobiera wszystkie moduły posortowane według display_order
     */
    public function getAllModules(): array {
        $connection = $this->getConnection();
        $stmt = $connection->prepare('
            SELECT id, name, level, description, display_order 
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
            SELECT id, name, level, description, display_order 
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
            SELECT id, name, level, description, display_order 
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
            SELECT id, name, level, description, display_order 
            FROM modules 
            WHERE id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Pobiera wszystkie znaki należące do danego modułu (set_id)
     * set_id w characters odpowiada id w modules
     */
    public function getCharactersInModule(int $moduleId): array {
        $connection = $this->getConnection();
        $stmt = $connection->prepare('
            SELECT id, symbol, romaji, meaning, stroke_count
            FROM characters 
            WHERE set_id = :setId
            ORDER BY place_order ASC
        ');
        $stmt->bindParam(':setId', $moduleId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Pobiera postęp użytkownika dla danego modułu
     * Liczy ile znaków z danego modułu zostało opanowanych
     */
    public function getUserModuleProgress(int $userId, int $moduleId): array {
        $connection = $this->getConnection();
        
        // Pobierz liczbę wszystkich znaków w module
        $stmt = $connection->prepare('
            SELECT COUNT(*) as total
            FROM characters 
            WHERE set_id = :setId
        ');
        $stmt->bindParam(':setId', $moduleId, PDO::PARAM_INT);
        $stmt->execute();
        $totalResult = $stmt->fetch(PDO::FETCH_ASSOC);
        $total = (int)$totalResult['total'];
        
        // Pobierz liczbę opanowanych znaków
        $stmt = $connection->prepare('
            SELECT COUNT(*) as mastered
            FROM user_progress up
            JOIN characters c ON up.character_id = c.id
            WHERE up.user_id = :userId 
            AND c.set_id = :setId
            AND up.is_mastered = TRUE
        ');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':setId', $moduleId, PDO::PARAM_INT);
        $stmt->execute();
        $masteredResult = $stmt->fetch(PDO::FETCH_ASSOC);
        $mastered = (int)$masteredResult['mastered'];
        
        // Oblicz procent
        $percent = $total > 0 ? (int)(($mastered / $total) * 100) : 0;
        
        return [
            'total' => $total,
            'mastered' => $mastered,
            'percent' => $percent
        ];
    }
}
