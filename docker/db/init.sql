-- WYTYCZNA #1: Ochrona przed SQL injection (prepared statements będą używane w kodzie PHP)
-- WYTYCZNA #10: Hasła przechowywane jako hash (kolumna password_hash)
-- WYTYCZNA #23: Z bazy pobieram tylko minimalny zestaw danych o użytkowniku

-- Tworzenie tabeli users
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    -- WYTYCZNA #10: Przechowujemy hash hasła (bcrypt), nie czyste hasło
    password_hash VARCHAR(255) NOT NULL,
    -- WYTYCZNA #9: Ograniczam długość wejścia
    name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- WYTYCZNA #3, #18: Dodanie indeksu dla szybszego wyszukiwania po email
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);

-- Przykładowe dane testowe
-- Użytkownik testowy: test@example.com / Test1234
-- Hash wygenerowany przez: password_hash('Test1234', PASSWORD_BCRYPT, ['cost' => 12])
INSERT INTO users (email, password_hash, name, surname) 
VALUES ('test@example.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5lW.2E62OhKlm', 'Jan', 'Kowalski')
ON CONFLICT (email) DO NOTHING;
