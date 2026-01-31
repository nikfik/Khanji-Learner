<?php
require_once "Routing.php";

// WYTYCZNA #13: Cookie sesyjne ma flagę HttpOnly
// WYTYCZNA #14: Cookie sesyjne ma flagę Secure
// WYTYCZNA #15: Cookie ma ustawione SameSite (np. Lax/Strict)
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Wymaga HTTPS
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

// WYTYCZNA #20: W produkcji nie pokazuję stack trace / surowych błędów użytkownikowi
// W produkcji ustaw display_errors na 0
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

// WYTYCZNA #12: Rozpoczęcie sesji z bezpiecznymi parametrami
session_start([
    'cookie_lifetime' => 0, // Sesja kończy się po zamknięciu przeglądarki
    'cookie_httponly' => true, // WYTYCZNA #13
    'cookie_secure' => true, // WYTYCZNA #14: Wymaga HTTPS
    'cookie_samesite' => 'Strict', // WYTYCZNA #15
    'use_strict_mode' => true,
    'sid_length' => 48,
    'sid_bits_per_character' => 6
]);

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::run($path);
