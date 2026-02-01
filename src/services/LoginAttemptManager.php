<?php
class LoginAttemptManager {
    private const MAX_ATTEMPTS = 5;
    private const LOCKOUT_TIME = 900; // 15 minut w sekundach
    private const ATTEMPT_WINDOW = 300; // 5 minut
    
    // WYTYCZNA #16: Limit prób logowania / blokada czasowa / CAPTCHA po wielu nieudanych próbach
    
    public static function isBlocked(string $identifier): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $key = self::getKey($identifier);
        
        if (!isset($_SESSION[$key])) {
            return false;
        }

        $data = $_SESSION[$key];
        
        // Sprawdź czy blokada nadal obowiązuje
        if (isset($data['blocked_until']) && time() < $data['blocked_until']) {
            return true;
        }

        // Jeśli blokada wygasła, wyczyść dane
        if (isset($data['blocked_until']) && time() >= $data['blocked_until']) {
            unset($_SESSION[$key]);
            return false;
        }

        return false;
    }

    public static function recordFailedAttempt(string $identifier): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $key = self::getKey($identifier);
        $currentTime = time();

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'attempts' => 1,
                'first_attempt' => $currentTime,
                'last_attempt' => $currentTime
            ];
            return;
        }

        $data = $_SESSION[$key];

        // Jeśli pierwsze logowanie było dawno temu, resetuj licznik
        if ($currentTime - $data['first_attempt'] > self::ATTEMPT_WINDOW) {
            $_SESSION[$key] = [
                'attempts' => 1,
                'first_attempt' => $currentTime,
                'last_attempt' => $currentTime
            ];
            return;
        }

        // Zwiększ licznik prób
        $data['attempts']++;
        $data['last_attempt'] = $currentTime;

        // Jeśli przekroczono limit, zablokuj
        if ($data['attempts'] >= self::MAX_ATTEMPTS) {
            $data['blocked_until'] = $currentTime + self::LOCKOUT_TIME;
        }

        $_SESSION[$key] = $data;
    }

    public static function clearAttempts(string $identifier): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $key = self::getKey($identifier);
        unset($_SESSION[$key]);
    }

    public static function getRemainingAttempts(string $identifier): int {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $key = self::getKey($identifier);
        
        if (!isset($_SESSION[$key])) {
            return self::MAX_ATTEMPTS;
        }

        $data = $_SESSION[$key];
        $currentTime = time();

        // Sprawdź czy okno czasowe nie wygasło
        if ($currentTime - $data['first_attempt'] > self::ATTEMPT_WINDOW) {
            return self::MAX_ATTEMPTS;
        }

        return max(0, self::MAX_ATTEMPTS - $data['attempts']);
    }

    public static function getBlockedTimeRemaining(string $identifier): int {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $key = self::getKey($identifier);
        
        if (!isset($_SESSION[$key]['blocked_until'])) {
            return 0;
        }

        $remaining = $_SESSION[$key]['blocked_until'] - time();
        return max(0, $remaining);
    }

    private static function getKey(string $identifier): string {
        return 'login_attempts_' . hash('sha256', $identifier);
    }
}
