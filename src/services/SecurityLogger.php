<?php
class SecurityLogger {
    private const LOG_FILE = __DIR__ . '/../../logs/security.log';
    private const MAX_LOG_SIZE = 10485760; // 10MB

    // WYTYCZNA #25: Loguję nieudane próby logowania (bez haseł) do audytu
    public static function logFailedLogin(string $email, string $ip): void {
        // WYTYCZNA #11: Hasła nigdy nie są logowane w logach / errorach
        $message = sprintf(
            "[%s] Failed login attempt - Email: %s, IP: %s\n",
            date('Y-m-d H:i:s'),
            $email,
            $ip
        );

        self::writeLog($message);
    }

    // WYTYCZNA #16: Limit prób logowania / blokada czasowa
    public static function logBlockedAttempt(string $email, string $ip): void {
        $message = sprintf(
            "[%s] Blocked login attempt (too many tries) - Email: %s, IP: %s\n",
            date('Y-m-d H:i:s'),
            $email,
            $ip
        );

        self::writeLog($message);
    }

    public static function logSuccessfulLogin(string $email, string $ip): void {
        $message = sprintf(
            "[%s] Successful login - Email: %s, IP: %s\n",
            date('Y-m-d H:i:s'),
            $email,
            $ip
        );

        self::writeLog($message);
    }

    public static function logLogout(string $email, string $ip): void {
        $message = sprintf(
            "[%s] User logout - Email: %s, IP: %s\n",
            date('Y-m-d H:i:s'),
            $email,
            $ip
        );

        self::writeLog($message);
    }

    private static function writeLog(string $message): void {
        $logDir = dirname(self::LOG_FILE);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        // Rotacja logów jeśli plik jest za duży
        if (file_exists(self::LOG_FILE) && filesize(self::LOG_FILE) > self::MAX_LOG_SIZE) {
            rename(self::LOG_FILE, self::LOG_FILE . '.' . date('Y-m-d-His'));
        }

        // WYTYCZNA #20: W produkcji nie pokazuję stack trace / surowych błędów użytkownikowi
        // Błędy zapisywane są do logów, nie wyświetlane użytkownikowi
        try {
            file_put_contents(self::LOG_FILE, $message, FILE_APPEND | LOCK_EX);
        } catch (Exception $e) {
            // W przypadku błędu zapisu do logu, nie robimy nic aby nie przerwać działania aplikacji
            error_log("Failed to write to security log: " . $e->getMessage());
        }
    }
}
