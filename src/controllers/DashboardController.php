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

        $userId = $_SESSION['user_id'];
        
        // Pobierz dane użytkownika z bazy (UserRepository jest singleton)
        $userRepository = UserRepository::getInstance();
        $user = $userRepository->getUserById($userId);
        
        if (!$user) {
            header("Location: /login");
            exit();
        }

        // Pobierz statystyki
        $characterRepository = new CharacterRepository();
        
        // Ilość opanowanych znaków (is_mastered = true)
        $masteredCount = $this->getMasteredCount($userId);
        
        // Streak (ilość dni zalogowań z rzędu)
        $streak = $this->getLoginStreak($userId);
        
        // Ilość sesji nauki
        $sessionsCount = $this->getSessionsCount($userId);
        
        // Ostatnie rysunki (6 ostatnich)
        $recentDrawings = $characterRepository->getUserDrawings($userId, 6);

        $userProfile = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'bio' => $user->getBio() ?? 'Brak bio',
            'created_at' => $this->formatDate($user->getCreatedAt()),
            'joined' => $this->formatDate($user->getCreatedAt()),
            'level' => 'Uczeń'
        ];

        $stats = [
            'streak' => $streak,
            'mastered_count' => $masteredCount,
            'sessions_count' => $sessionsCount
        ];

        $this->render('profile', [
            'user' => $userProfile,
            'stats' => $stats,
            'drawings' => $recentDrawings
        ]);
    }

    // WYTYCZNA #1: Prepared statements dla wszystkich zapytań
    private function getMasteredCount(int $userId): int {
        $stmt = Database::getInstance()->getConnection()->prepare('
            SELECT COUNT(*) as count FROM user_progress 
            WHERE user_id = :userId AND is_mastered = TRUE
        ');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    // Oblicz login streak
    private function getLoginStreak(int $userId): int {
        $stmt = Database::getInstance()->getConnection()->prepare('
            SELECT activity_date FROM user_activity 
            WHERE user_id = :userId 
            ORDER BY activity_date DESC 
            LIMIT 30
        ');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        $dates = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($dates)) {
            return 0;
        }
        
        $streak = 0;
        $currentDate = new DateTime();
        
        foreach ($dates as $date) {
            $logDate = new DateTime($date);
            $diff = $currentDate->diff($logDate);
            
            if ($diff->days === $streak) {
                $streak++;
                $currentDate = $logDate;
            } else {
                break;
            }
        }
        
        return $streak;
    }

    // Pobierz ilość sesji nauki
    private function getSessionsCount(int $userId): int {
        $stmt = Database::getInstance()->getConnection()->prepare('
            SELECT COUNT(DISTINCT session_id) as count FROM user_drawings 
            WHERE user_id = :userId AND session_id IS NOT NULL
        ');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    // Formatuj datę
    private function formatDate(?string $date): string {
        if (!$date) {
            return 'Brak daty';
        }
        return date('d.m.Y', strtotime($date));
    }

    // API: Zaktualizuj profil użytkownika
    public function updateProfile() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            echo json_encode([
                'success' => false,
                'message' => 'Nie zalogowany'
            ]);
            exit();
        }

        $userId = $_SESSION['user_id'];
        $input = json_decode(file_get_contents('php://input'), true);
        
        $username = $input['username'] ?? null;
        $bio = $input['bio'] ?? null;

        if (!$username) {
            echo json_encode([
                'success' => false,
                'message' => 'Username jest wymagany'
            ]);
            return;
        }

        try {
            $userRepository = UserRepository::getInstance();
            
            // Aktualizuj username
            if (!empty($username)) {
                $userRepository->updateUsername($userId, $username);
            }
            
            // Aktualizuj bio
            if ($bio !== null) {
                $userRepository->updateBio($userId, $bio);
            }

            echo json_encode([
                'success' => true,
                'message' => 'Profil zaktualizowany'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Błąd: ' . $e->getMessage()
            ]);
        }
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