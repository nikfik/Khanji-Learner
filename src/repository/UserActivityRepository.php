<?php

require_once 'Repository.php';

class UserActivityRepository extends Repository {

    public function logActivity(int $userId): void {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO user_activity (user_id, activity_date) 
            VALUES (:userId, CURRENT_DATE) 
            ON CONFLICT (user_id, activity_date) DO NOTHING
        ');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getRecentActivityCount(int $userId): int {
        $stmt = $this->database->connect()->prepare('
            SELECT COUNT(DISTINCT activity_date) 
            FROM user_activity 
            WHERE user_id = :userId 
            AND activity_date >= CURRENT_DATE - INTERVAL \'14 days\'
        ');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function calculateStreak(int $userId): int {
        $stmt = $this->database->connect()->prepare('
            SELECT activity_date 
            FROM user_activity 
            WHERE user_id = :userId 
            ORDER BY activity_date DESC
        ');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $dates = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($dates)) return 0;

        $streak = 0;
        $currentDate = new DateTime();
        $lastDate = new DateTime($dates[0]);
        
        // Jeśli przerwa od ostatniej aktywności jest większa niż 1 dzień, streak się zeruje
        if ($currentDate->diff($lastDate)->days > 1) return 0;

        foreach ($dates as $index => $date) {
            if ($index == 0) {
                $streak = 1;
                continue;
            }
            $prev = new DateTime($dates[$index-1]);
            $curr = new DateTime($date);
            
            // Sprawdzamy, czy data jest dokładnie o 1 dzień wcześniejsza od poprzedniej w pętli
            $diff = $prev->diff($curr)->days;
            if ($diff == 1) {
                $streak++;
            } elseif ($diff == 0) {
                continue; // Ten sam dzień (na wszelki wypadek)
            } else {
                break;
            }
        }
        return $streak;
    }
}