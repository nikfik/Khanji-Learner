<?php

require_once 'Repository.php';
//require_once __DIR__.'/../models/Character.php'; // Zakładam, że stworzysz prosty model

class CharacterRepository extends Repository {

    public function getCharactersBySet(int $setId, int $userId): array {
        $result = [];

        $stmt = $this->database->connect()->prepare('
            SELECT c.*, COALESCE(up.view_count, 0) as view_count, COALESCE(up.is_mastered, false) as is_mastered
            FROM characters c
            LEFT JOIN user_progress up ON c.id = up.character_id AND up.user_id = :userId
            WHERE c.set_id = :setId
            ORDER BY c.order_number ASC
        ');
        $stmt->bindParam(':setId', $setId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function incrementViewCount(int $userId, int $character_id) {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO user_progress (user_id, character_id, view_count)
            VALUES (:userId, :charId, 1)
            ON CONFLICT (user_id, character_id)
            DO UPDATE SET 
                view_count = user_progress.view_count + 1,
                is_mastered = CASE WHEN user_progress.view_count + 1 >= 10 THEN true ELSE false END,
                updated_at = CURRENT_TIMESTAMP
        ');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':charId', $character_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function getRandomCharactersForStudy(int $setId, int $limit = 15): array {
        $stmt = $this->database->connect()->prepare('
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

    public function updateLearningProgress(int $userId, array $results) {
        $conn = $this->database->connect();
        
        try {
            $conn->beginTransaction();
            
            foreach ($results as $result) {
                $characterId = $result['character_id'];
                $isCorrect = $result['correct'];
                
                // Zwiększ view_count dla wszystkich
                $stmt = $conn->prepare('
                    INSERT INTO user_progress (user_id, character_id, view_count, is_mastered)
                    VALUES (:userId, :charId, 1, false)
                    ON CONFLICT (user_id, character_id)
                    DO UPDATE SET 
                        view_count = user_progress.view_count + 1,
                        updated_at = CURRENT_TIMESTAMP
                ');
                $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':charId', $characterId, PDO::PARAM_INT);
                $stmt->execute();
                
                // Jeśli poprawna odpowiedź, zwiększ mastery level
                if ($isCorrect) {
                    $stmt = $conn->prepare('
                        UPDATE user_progress 
                        SET is_mastered = CASE 
                            WHEN view_count >= 10 THEN true 
                            ELSE false 
                        END
                        WHERE user_id = :userId AND character_id = :charId
                    ');
                    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                    $stmt->bindParam(':charId', $characterId, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
            
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }
    public function getUsersProgressBySets(int $userId): array {
    $stmt = $this->database->connect()->prepare('
        SELECT 
            s.id, 
            s.name as title,
            COUNT(c.id) as total_count,
            COUNT(up.character_id) FILTER (WHERE up.is_mastered = true) as mastered_count
        FROM sets s
        JOIN characters c ON s.id = c.set_id
        LEFT JOIN user_progress up ON c.id = up.character_id AND up.user_id = :userId
        GROUP BY s.id, s.name
        HAVING COUNT(up.character_id) FILTER (WHERE up.view_count > 0) > 0
    ');
    
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Przeliczamy na procenty w PHP dla wygody widoku
    foreach ($results as &$row) {
        $row['progress'] = ($row['total_count'] > 0) 
            ? round(($row['mastered_count'] / $row['total_count']) * 100) 
            : 0;
        $row['status'] = ($row['progress'] == 100) ? 'Ukończono' : 'W trakcie';
    }

    return $results;
}
}   