<?php

class CSRFToken {
    private const TOKEN_NAME = 'csrf_token';
    private const TOKEN_TIME_NAME = 'csrf_token_time';
    private const TOKEN_LIFETIME = 3600; // 1 godzina

    // WYTYCZNA #7, #8: CSRF token w formularzach logowania i rejestracji
    public static function generate(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION[self::TOKEN_NAME] = $token;
        $_SESSION[self::TOKEN_TIME_NAME] = time();

        return $token;
    }

    // WYTYCZNA #7, #8: Walidacja CSRF tokenu
    public static function validate(?string $token): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION[self::TOKEN_NAME]) || !isset($_SESSION[self::TOKEN_TIME_NAME])) {
            return false;
        }

        // Sprawdzenie czy token nie wygasł
        if (time() - $_SESSION[self::TOKEN_TIME_NAME] > self::TOKEN_LIFETIME) {
            self::destroy();
            return false;
        }

        // Porównanie tokenów w bezpieczny sposób (ochrona przed timing attacks)
        $isValid = hash_equals($_SESSION[self::TOKEN_NAME], $token ?? '');

        // Po użyciu tokenu, generujemy nowy (one-time token)
        if ($isValid) {
            self::destroy();
        }

        return $isValid;
    }

    public static function destroy(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION[self::TOKEN_NAME]);
        unset($_SESSION[self::TOKEN_TIME_NAME]);
    }

    public static function getTokenField(): string {
        return self::TOKEN_NAME;
    }
}
