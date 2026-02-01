<?php
require_once __DIR__.'/../repository/CharacterRepository.php';
require_once __DIR__.'/../repository/UserActivityRepository.php';

class CharacterController {
    // API: Rozpocznij sesję nauki
    public function startLearning() {
        header('Content-Type: application/json');
        $setId = $_GET['setId'] ?? 1;
        $userId = $_SESSION['user_id'] ?? 1; // TODO: Pobierz z sesji
        $characterRepository = new CharacterRepository();
        $characters = $characterRepository->getRandomCharactersForStudy($setId, 15);
        echo json_encode([
            'success' => true,
            'characters' => $characters
        ]);
    }

    // API: Zakończ sesję nauki i zapisz postęp
    public function finishLearning() {
        header('Content-Type: application/json');
        $userId = $_SESSION['user_id'] ?? 1; // TODO: Pobierz z sesji
        $input = json_decode(file_get_contents('php://input'), true);
        $results = $input['results'] ?? [];
        if (empty($results)) {
            echo json_encode([
                'success' => false,
                'message' => 'No results provided'
            ]);
            return;
        }
        try {
            $characterRepository = new CharacterRepository();
            $characterRepository->updateLearningProgress($userId, $results);
            echo json_encode([
                'success' => true,
                'message' => 'Progress updated successfully'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error updating progress: ' . $e->getMessage()
            ]);
        }
        $activityRepo = new UserActivityRepository();
        $activityRepo->logActivity($userId);
    }

    // API: Zapisz rysunek użytkownika
    public function saveDrawing() {
        header('Content-Type: application/json');
        $userId = $_SESSION['user_id'] ?? 1;
        $input = json_decode(file_get_contents('php://input'), true);
        
        $characterId = $input['character_id'] ?? null;
        $romaji = $input['romaji'] ?? null;
        $drawingData = $input['drawing_data'] ?? null;  // base64 encoded image
        $sessionId = $input['session_id'] ?? null;
        
        if (!$characterId || !$drawingData) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing required fields'
            ]);
            return;
        }
        
        try {
            // Konwertuj base64 na binary
            $binaryData = base64_decode(str_replace('data:image/png;base64,', '', $drawingData));
            
            $characterRepository = new CharacterRepository();
            $characterRepository->saveDrawing($userId, $characterId, $romaji, $binaryData, $sessionId);
            
            echo json_encode([
                'success' => true,
                'message' => 'Drawing saved successfully'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error saving drawing: ' . $e->getMessage()
            ]);
        }
    }
}
