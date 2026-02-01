-- WYTYCZNA #1: Ochrona przed SQL injection (prepared statements będą używane w kodzie PHP)
-- WYTYCZNA #10: Hasła przechowywane jako hash (kolumna password)
-- WYTYCZNA #23: Z bazy pobieram tylko minimalny zestaw danych o użytkowniku

-- 1. Tabela Użytkowników
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100),
    surname VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- WYTYCZNA #3, #18: Dodanie indeksu dla szybszego wyszukiwania po email
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);

-- 2. Tabela Kategorii (do przyszłościowych setów z poziomami trudności)
CREATE TABLE IF NOT EXISTS categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    level INT DEFAULT 1,
    description TEXT
);

-- 3. Tabela Znaków
CREATE TABLE IF NOT EXISTS characters (
    id SERIAL PRIMARY KEY,
    set_id INT NOT NULL,
    symbol VARCHAR(10) NOT NULL,
    romaji VARCHAR(20),
    meaning VARCHAR(100),
    stroke_count INT,
    place_order INT
);

CREATE INDEX IF NOT EXISTS idx_characters_set_id ON characters(set_id);

-- 4. Tabela Postępu Użytkownika
CREATE TABLE IF NOT EXISTS user_progress (
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    character_id INT NOT NULL REFERENCES characters(id) ON DELETE CASCADE,
    view_count INT DEFAULT 0,
    is_mastered BOOLEAN DEFAULT FALSE,
    last_practiced TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, character_id)
);

CREATE INDEX IF NOT EXISTS idx_user_progress_user_id ON user_progress(user_id);
CREATE INDEX IF NOT EXISTS idx_user_progress_character_id ON user_progress(character_id);

-- 5. Tabela Aktywności Użytkownika (do login streaka i sesji)
CREATE TABLE IF NOT EXISTS user_activity (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    activity_date DATE DEFAULT CURRENT_DATE,
    UNIQUE(user_id, activity_date)
);

CREATE INDEX IF NOT EXISTS idx_user_activity_user_id ON user_activity(user_id);

-- Przykładowe kategorie
INSERT INTO categories (name, level, description) VALUES
('Hiragana', 1, 'Alfabet Hiragana'),
('Katakana', 2, 'Alfabet Katakana'),
('Kanji A1', 3, 'Kanji poziom A1')
ON CONFLICT DO NOTHING;

-- Przykładowe dane testowe
-- Użytkownik testowy: test / Test1234
-- Hash wygenerowany przez: password_hash('Test1234', PASSWORD_BCRYPT, ['cost' => 12])
INSERT INTO users (username, email, password, name, surname) 
VALUES ('testuser', 'test@example.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5lW.2E62OhKlm', 'Jan', 'Kowalski')
ON CONFLICT (email) DO NOTHING;

-- Przykładowe znaki Hiragana (set_id = 1)
INSERT INTO characters (set_id, symbol, romaji, meaning, place_order) VALUES
(1, 'あ', 'a', NULL, 1), (1, 'い', 'i', NULL, 2), (1, 'う', 'u', NULL, 3),
(1, 'え', 'e', NULL, 4), (1, 'お', 'o', NULL, 5), (1, 'か', 'ka', NULL, 6),
(1, 'き', 'ki', NULL, 7), (1, 'く', 'ku', NULL, 8), (1, 'け', 'ke', NULL, 9),
(1, 'こ', 'ko', NULL, 10)
ON CONFLICT DO NOTHING;

-- Przykładowe znaki Katakana (set_id = 2)
INSERT INTO characters (set_id, symbol, romaji, meaning, place_order) VALUES
(2, 'ア', 'a', NULL, 1), (2, 'イ', 'i', NULL, 2), (2, 'ウ', 'u', NULL, 3),
(2, 'エ', 'e', NULL, 4), (2, 'オ', 'o', NULL, 5), (2, 'カ', 'ka', NULL, 6),
(2, 'キ', 'ki', NULL, 7), (2, 'ク', 'ku', NULL, 8), (2, 'ケ', 'ke', NULL, 9),
(2, 'コ', 'ko', NULL, 10)
ON CONFLICT DO NOTHING;
