
<?php
require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../repository/CharacterRepository.php';
class DashboardController extends AppController{
    public function dashboard() {
    // W przyszłości te dane pobierzesz z bazy danych
    $userProgress = [
        'hiragana' => ['percent' => 10, 'label' => '5/46'], // Przykład z ekranu 7
        'katakana' => ['percent' => 0, 'label' => '0/46'],
        'kanji' => ['percent' => 7, 'label' => '150/2136']
    ];

    $this->render('dashboard', ['progress' => $userProgress]);
}
public function characters() {
    $setId = $_GET['id'] ?? 1; // ID zestawu z adresu URL
    $userId = 1; // TODO: Pobierz z sesji $_SESSION['user_id']

    $characterRepository = new CharacterRepository();
    $characters = $characterRepository->getCharactersBySet($setId, $userId);
    
    // Pobieramy nazwę zestawu dla nagłówka
    // (Możesz dodać metodę getSetTitle w repozytorium)
    $title = ($setId == 1) ? "Hiragana" : "Katakana"; 

    $this->render('characters', [
        'title' => $title,
        'characters' => $characters
    ]);
}
    public function index() {
    $cards = [
    [
        'id' => 1,
        'title' => 'Ace of Spades',
        'subtitle' => 'Legendary card',
        'imageUrlPath' => 'https://deckofcardsapi.com/static/img/AS.png',
        'href' => '/cards/ace-of-spades'
    ],
    [
        'id' => 2,
        'title' => 'Queen of Hearts',
        'subtitle' => 'Classic romance',
        'imageUrlPath' => 'https://deckofcardsapi.com/static/img/QH.png',
        'href' => '/cards/queen-of-hearts'
    ],
    [
        'id' => 3,
        'title' => 'King of Clubs',
        'subtitle' => 'Royal strength',
        'imageUrlPath' => 'https://deckofcardsapi.com/static/img/KC.png',
        'href' => '/cards/king-of-clubs'
    ],
    [
        'id' => 4,
        'title' => 'Jack of Diamonds',
        'subtitle' => 'Sly and sharp',
        'imageUrlPath' => 'https://deckofcardsapi.com/static/img/JD.png',
        'href' => '/cards/jack-of-diamonds'
    ],
    [
        'id' => 5,
        'title' => 'Ten of Hearts',
        'subtitle' => 'Lucky draw',
        'imageUrlPath' => 'https://deckofcardsapi.com/static/img/0H.png',
        'href' => '/cards/ten-of-hearts'
    ],
];

    //$userRepository = new UserRepository();
    //$users = $userRepository->getUsers();

    //var_dump($users);

return $this->render("dashboard", ['items' => $cards]);
}

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
        
        // Odbierz dane z POST
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