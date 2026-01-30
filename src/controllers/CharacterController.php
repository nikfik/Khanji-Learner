<?php
require_once __DIR__.'/../repository/CharacterRepository.php';

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
    }
}
