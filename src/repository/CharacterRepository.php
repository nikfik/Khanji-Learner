<?php
require_once 'Repository.php';
//require_once __DIR__.'/../models/Character.php'; // Zakładam, że stworzysz prosty model

class CharacterRepository extends Repository {

    public function getCharactersBySet(int $setId, int $userId): array {
        $result = [];

        // WYTYCZNA #1: Prepared statements / ochrona SQL injection
        // Używamy getConnection() zamiast $this->database->connect()
        $stmt = $this->getConnection()->prepare('
            SELECT c.*, COALESCE(up.view_count, 0) as view_count, COALESCE(up.is_mastered, false) as is_mastered
            FROM characters c
            LEFT JOIN user_progress up ON c.id = up.character_id AND up.user_id = :userId
            WHERE c.set_id = :setId
            ORDER BY c.place_order ASC
        ');
        $stmt->bindParam(':setId', $setId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function incrementViewCount(int $userId, int $character_id) {
        // WYTYCZNA #1: Prepared statements / ochrona SQL injection
        $stmt = $this->getConnection()->prepare('
            INSERT INTO user_progress (user_id, character_id, view_count, last_practiced)
            VALUES (:userId, :charId, 1, NOW())
            ON CONFLICT (user_id, character_id) DO UPDATE SET
                view_count = user_progress.view_count + 1,
                last_practiced = NOW(),
                is_mastered = CASE WHEN user_progress.view_count + 1 >= 10 THEN TRUE ELSE FALSE END
        ');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':charId', $character_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // WYTYCZNA #1: Prepared statements / ochrona SQL injection
    // Zapisz wyniki sesji nauki
    public function updateLearningProgress(int $userId, array $results) {
        foreach ($results as $result) {
            $characterId = $result['character_id'];
            $correct = $result['correct'];
            
            // Jeśli odpowiedź była poprawna, zwiększ view_count
            if ($correct) {
                $this->incrementViewCount($userId, $characterId);
            }
        }
    }

    // WYTYCZNA #1: Prepared statements / ochrona SQL injection
    // Zapisz rysunek użytkownika
    public function saveDrawing(int $userId, int $characterId, string $romaji, string $drawingData, ?string $sessionId = null) {
        $stmt = $this->getConnection()->prepare('
            INSERT INTO user_drawings (user_id, character_id, romaji, drawing_data, session_id, created_at)
            VALUES (:userId, :characterId, :romaji, :drawingData, :sessionId, CURRENT_TIMESTAMP)
        ');
        
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':characterId', $characterId, PDO::PARAM_INT);
        $stmt->bindParam(':romaji', $romaji, PDO::PARAM_STR);
        $stmt->bindParam(':drawingData', $drawingData, PDO::PARAM_LOB);
        $stmt->bindParam(':sessionId', $sessionId, PDO::PARAM_STR);
        
        return $stmt->execute();
    }

    // WYTYCZNA #1: Prepared statements / ochrona SQL injection
    // Pobierz ostatnie rysunki użytkownika
    public function getUserDrawings(int $userId, int $limit = 10): array {
        $stmt = $this->getConnection()->prepare('
            SELECT ud.id, ud.user_id, ud.session_id, ud.character_id, ud.romaji, 
                   encode(ud.drawing_data, \'base64\') as drawing_data,
                   ud.created_at, c.symbol, c.romaji as character_romaji
            FROM user_drawings ud
            JOIN characters c ON ud.character_id = c.id
            WHERE ud.user_id = :userId
            ORDER BY ud.created_at DESC
            LIMIT :limit
        ');
        
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getRandomCharactersForStudy(int $setId, int $limit = 15): array {
        // WYTYCZNA #1: Prepared statements / ochrona SQL injection
        $stmt = $this->getConnection()->prepare('
            SELECT * FROM characters 
            WHERE set_id = :setId 
            ORDER BY RANDOM() 
            LIMIT :limit
        ');
        
        $stmt->bindParam(':setId', $setId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}   