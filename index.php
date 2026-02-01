<?php
// WYTYCZNA #20: W produkcji nie pokazuję stack trace / surowych błędów użytkownikowi
// Konfiguracja PHP MUSI być PRZED require_once, aby złapać wszystkie błędy
if (getenv('ENVIRONMENT') === 'production') {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/logs/php-errors.log');
} else {
    // W środowisku deweloperskim
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// WYTYCZNA #20: Custom error handler - pokazujemy przyjazny komunikat zamiast stack trace
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("Error [$errno]: $errstr in $errfile on line $errline");
    
    if (getenv('ENVIRONMENT') === 'production') {
        http_response_code(500);
        echo "Wystąpił błąd serwera. Spróbuj ponownie później.";
    }
    return true;
});

set_exception_handler(function($exception) {
    error_log("Exception: " . $exception->getMessage() . " in " . $exception->getFile() . ":" . $exception->getLine());
    
    if (getenv('ENVIRONMENT') === 'production') {
        http_response_code(500);
        echo "Wystąpił błąd serwera. Spróbuj ponownie później.";
    }
});

// WYTYCZNA #13: Cookie sesyjne ma flagę HttpOnly
// WYTYCZNA #14: Cookie sesyjne ma flagę Secure
// WYTYCZNA #15: Cookie ma ustawione SameSite (np. Lax/Strict)
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

// WYTYCZNA #12: Rozpoczęcie sesji z bezpiecznymi parametrami
// MUSI być PRZED require_once "Routing.php" bo Routing renderuje widoki
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 0,
        'cookie_httponly' => true,
        'cookie_secure' => true,
        'cookie_samesite' => 'Strict',
        'use_strict_mode' => true,
        'sid_length' => 48,
        'sid_bits_per_character' => 6
    ]);
}

require_once "Routing.php";

// Pobierz path z query string (ustawiony przez Nginx) lub z REQUEST_URI
$path = $_GET['path'] ?? $_SERVER['REQUEST_URI'];
$path = trim(parse_url($path, PHP_URL_PATH), '/');

Routing::run($path);
