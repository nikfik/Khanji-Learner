<?php
require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../services/CSRFToken.php';
require_once __DIR__ . '/../services/SecurityLogger.php';
require_once __DIR__ . '/../services/LoginAttemptManager.php';

class SecurityController extends AppController {

    private $userRepository;

    public function __construct() {
        // WYTYCZNA #4: UserRepository jako singleton
        $this->userRepository = UserRepository::getInstance();
    }

    // WYTYCZNA #6: Metoda login przyjmuje dane tylko na POST, GET tylko renderuje widok
    public function login() {
        // WYTYCZNA #5: Logowanie dostępne tylko przez HTTPS
        $this->requireHTTPS();

        if (!$this->isPost()) {
            // WYTYCZNA #7: CSRF token w formularzu logowania
            $csrfToken = CSRFToken::generate();
            return $this->render("login", [
                'message' => '',
                'csrf_token' => $csrfToken
            ]);
        }

        // WYTYCZNA #21: Zwracam sensowne kody HTTP
        http_response_code(200);

        // WYTYCZNA #7: Walidacja CSRF tokenu
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CSRFToken::validate($csrfToken)) {
            http_response_code(403); // WYTYCZNA #21
            return $this->render("login", [
                'message' => 'Nieprawidłowy token bezpieczeństwa. Odśwież stronę i spróbuj ponownie.',
                'csrf_token' => CSRFToken::generate()
            ]);
        }

        // WYTYCZNA #9: Ograniczam długość wejścia
        $email = isset($_POST['email']) ? substr(trim($_POST['email']), 0, 255) : '';
        $password = isset($_POST['password']) ? substr($_POST['password'], 0, 255) : '';

        // Podstawowa walidacja
        if (empty($email) || empty($password)) {
            http_response_code(400); // WYTYCZNA #21
            return $this->render("login", [
                'message' => 'Email i hasło są wymagane.',
                'csrf_token' => CSRFToken::generate()
            ]);
        }

        // WYTYCZNA #3: Walidacja formatu email po stronie serwera
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400); // WYTYCZNA #21
            return $this->render("login", [
                'message' => 'Podaj prawidłowy adres email.',
                'csrf_token' => CSRFToken::generate()
            ]);
        }

        $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        // WYTYCZNA #16: Limit prób logowania / blokada czasowa
        if (LoginAttemptManager::isBlocked($email)) {
            $remainingTime = LoginAttemptManager::getBlockedTimeRemaining($email);
            $minutes = ceil($remainingTime / 60);
            
            SecurityLogger::logBlockedAttempt($email, $clientIp);
            http_response_code(429); // WYTYCZNA #21: Too Many Requests
            
            return $this->render("login", [
                'message' => "Zbyt wiele nieudanych prób logowania. Spróbuj ponownie za {$minutes} minut.",
                'csrf_token' => CSRFToken::generate()
            ]);
        }

        // Pobierz użytkownika z bazy
        $user = $this->userRepository->getUserByEmail($email);

        // WYTYCZNA #2: Nie zdradzam, czy email istnieje – komunikat typu „Email lub hasło niepoprawne"
        if (!$user || !password_verify($password, $user->getPasswordHash())) {
            // WYTYCZNA #25: Loguję nieudane próby logowania (bez haseł) do audytu
            SecurityLogger::logFailedLogin($email, $clientIp);
            
            // WYTYCZNA #16: Zapisz nieudaną próbę
            LoginAttemptManager::recordFailedAttempt($email);
            
            http_response_code(401); // WYTYCZNA #21: Unauthorized
            
            $remaining = LoginAttemptManager::getRemainingAttempts($email);
            $message = 'Email lub hasło niepoprawne.';
            if ($remaining <= 2 && $remaining > 0) {
                $message .= " Pozostało prób: {$remaining}";
            }
            
            return $this->render("login", [
                'message' => $message,
                'csrf_token' => CSRFToken::generate()
            ]);
        }

        // Logowanie udane
        // WYTYCZNA #12: Po poprawnym logowaniu regeneruję ID sesji
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true);

        // WYTYCZNA #16: Wyczyść licznik prób logowania
        LoginAttemptManager::clearAttempts($email);

        // WYTYCZNA #25: Loguj udane logowanie (bez hasła)
        SecurityLogger::logSuccessfulLogin($email, $clientIp);

        // Zapisz dane użytkownika w sesji
        // WYTYCZNA #23: Zapisuję tylko minimalny zestaw danych
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_email'] = $user->getEmail();
        $_SESSION['user_name'] = $user->getName();
        $_SESSION['logged_in'] = true;

        // Aktualizuj last_login
        $this->userRepository->updateLastLogin($user->getId());

        // Przekieruj do dashboardu
        header("Location: /dashboard");
        exit();
    }

    // WYTYCZNA #6: Metoda register przyjmuje dane tylko na POST, GET tylko renderuje widok
    public function register() {
        // WYTYCZNA #5: Rejestracja dostępna tylko przez HTTPS
        $this->requireHTTPS();

        if (!$this->isPost()) {
            // WYTYCZNA #8: CSRF token w formularzu rejestracji
            $csrfToken = CSRFToken::generate();
            return $this->render("register", [
                'message' => '',
                'csrf_token' => $csrfToken
            ]);
        }

        // WYTYCZNA #21: Zwracam sensowne kody HTTP
        http_response_code(200);

        // WYTYCZNA #8: Walidacja CSRF tokenu
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CSRFToken::validate($csrfToken)) {
            http_response_code(403); // WYTYCZNA #21
            return $this->render("register", [
                'message' => 'Nieprawidłowy token bezpieczeństwa. Odśwież stronę i spróbuj ponownie.',
                'csrf_token' => CSRFToken::generate()
            ]);
        }

        // WYTYCZNA #9: Ograniczam długość wejścia (email, hasło, imię…)
        $email = isset($_POST['email']) ? substr(trim($_POST['email']), 0, 255) : '';
        $password = isset($_POST['password']) ? substr($_POST['password'], 0, 255) : '';
        $passwordConfirm = isset($_POST['password_confirm']) ? substr($_POST['password_confirm'], 0, 255) : '';
        $name = isset($_POST['name']) ? substr(trim($_POST['name']), 0, 100) : '';
        $surname = isset($_POST['surname']) ? substr(trim($_POST['surname']), 0, 100) : '';

        // Walidacja wymaganych pól
        if (empty($email) || empty($password) || empty($passwordConfirm) || empty($name) || empty($surname)) {
            http_response_code(400); // WYTYCZNA #21
            return $this->render("register", [
                'message' => 'Wszystkie pola są wymagane.',
                'csrf_token' => CSRFToken::generate()
            ]);
        }

        // WYTYCZNA #3: Walidacja formatu email po stronie serwera
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400); // WYTYCZNA #21
            return $this->render("register", [
                'message' => 'Podaj prawidłowy adres email.',
                'csrf_token' => CSRFToken::generate()
            ]);
        }

        // WYTYCZNA #17: Waliduję złożoność hasła (min. długość itd.)
        if (strlen($password) < 8) {
            http_response_code(400); // WYTYCZNA #21
            return $this->render("register", [
                'message' => 'Hasło musi zawierać minimum 8 znaków.',
                'csrf_token' => CSRFToken::generate()
            ]);
        }

        // WYTYCZNA #17: Dodatkowa walidacja złożoności hasła
        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            http_response_code(400); // WYTYCZNA #21
            return $this->render("register", [
                'message' => 'Hasło musi zawierać co najmniej jedną wielką literę, małą literę i cyfrę.',
                'csrf_token' => CSRFToken::generate()
            ]);
        }

        // Sprawdź czy hasła się zgadzają
        if ($password !== $passwordConfirm) {
            http_response_code(400); // WYTYCZNA #21
            return $this->render("register", [
                'message' => 'Hasła nie są zgodne.',
                'csrf_token' => CSRFToken::generate()
            ]);
        }

        // WYTYCZNA #18: Przy rejestracji sprawdzam, czy email jest już w bazie
        if ($this->userRepository->emailExists($email)) {
            http_response_code(409); // WYTYCZNA #21: Conflict
            return $this->render("register", [
                'message' => 'Konto z tym adresem email już istnieje.',
                'csrf_token' => CSRFToken::generate()
            ]);
        }

        // WYTYCZNA #10: Hasła przechowywane jako hash (w UserRepository)
        $success = $this->userRepository->createUser($email, $password, $name, $surname);

        if (!$success) {
            // WYTYCZNA #20: W produkcji nie pokazuję stack trace / surowych błędów użytkownikowi
            http_response_code(500); // WYTYCZNA #21
            return $this->render("register", [
                'message' => 'Wystąpił błąd podczas rejestracji. Spróbuj ponownie później.',
                'csrf_token' => CSRFToken::generate()
            ]);
        }

        // Rejestracja udana - przekieruj do logowania
        return $this->render("login", [
            'message' => 'Rejestracja zakończona pomyślnie! Możesz się teraz zalogować.',
            'csrf_token' => CSRFToken::generate()
        ]);
    }

    // WYTYCZNA #24: Mam poprawne wylogowanie – niszczę sesję użytkownika
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userEmail = $_SESSION['user_email'] ?? 'unknown';
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        // WYTYCZNA #25: Loguj wylogowanie
        SecurityLogger::logLogout($userEmail, $clientIp);

        // WYTYCZNA #24: Niszczenie sesji
        $_SESSION = array();

        // Usuń cookie sesyjne
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        session_destroy();

        // Przekieruj do strony logowania
        header("Location: /login");
        exit();
    }

    // WYTYCZNA #5: Logowanie i rejestracja dostępne tylko przez HTTPS
    private function requireHTTPS(): void {
        // W środowisku deweloperskim możemy pominąć tę weryfikację
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'off' && 
            !in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
            http_response_code(403); // WYTYCZNA #21
            die('Dostęp wymaga bezpiecznego połączenia HTTPS.');
        }
    }
}