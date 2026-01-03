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
}   