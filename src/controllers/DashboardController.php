<?php
require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../repository/CharacterRepository.php';

class DashboardController extends AppController{
    
    public function dashboard() {
        // W przyszłości te dane pobierzesz z bazy danych
        $userProgress = [
            'hiragana' => ['percent' => 10, 'label' => '5/46'],
            'katakana' => ['percent' => 0, 'label' => '0/46'],
            'kanji' => ['percent' => 7, 'label' => '150/2136']
        ];

        $this->render('dashboard', ['progress' => $userProgress]);
    }

    public function characters() {
        $setId = $_GET['id'] ?? 1;
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

        $characterRepository = new CharacterRepository();
        $characters = $characterRepository->getCharactersBySet($setId, $userId);
        
        $title = ($setId == 1) ? "Hiragana" : "Katakana";

        $this->render('characters', [
            'title' => $title,
            'characters' => $characters
        ]);
    }

    public function profile() {
        // Sprawdź czy użytkownik jest zalogowany
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            header("Location: /login");
            exit();
        }

        // WYTYCZNA #23: Z bazy pobieram tylko minimalny zestaw danych o użytkowniku
        $userProfile = [
            'name' => $_SESSION['user_name'] ?? 'Nieznany',
            'email' => $_SESSION['user_email'] ?? 'brak@example.com',
            'id' => $_SESSION['user_id'] ?? 0
        ];

        $this->render('profile', ['user' => $userProfile]);
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

        return $this->render("dashboard", ['items' => $cards]);
    }
}