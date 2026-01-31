<?php
require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../repository/CharacterRepository.php';
require_once __DIR__.'/../repository/UserActivityRepository.php';   
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

    // Dynamiczna nazwa alfabetu
    $title = 'Alfabet';
    switch ($setId) {
        case 1:
            $title = 'Hiragana';
            break;
        case 2:
            $title = 'Katakana';
            break;
        case 3:
            $title = 'Kanji';
            break;
    }

    $this->render('characters', [
        'title' => $title,
        'characters' => $characters
    ]);
}
    public function index() {

    $this->render('dashboard');
}

public function profile() {
    $userId = 1; // Docelowo z sesji
    $charRepo = new CharacterRepository();
    $activityRepo = new UserActivityRepository();

    $chapters = $charRepo->getUsersProgressBySets($userId);
    //var_dump($chapters); 
    //die();
    $this->render('profile', [
        'user' => ['name' => 'Kenji Tanaka', 'level' => 'N4 Master', 'joined' => 'Styczeń 2023'],
        'stats' => [
            'streak' => 15,
            'mastered_count' => array_sum(array_column($chapters, 'mastered_count')),
            'recent_activity' => $activityRepo->getRecentActivityCount($userId)
        ],
        'chapters' => $chapters
    ]);
}
}