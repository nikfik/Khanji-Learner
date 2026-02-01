-- WYTYCZNA #1: Ochrona przed SQL injection (prepared statements będą używane w kodzie PHP)
-- WYTYCZNA #10: Hasła przechowywane jako hash (kolumna password)
-- WYTYCZNA #23: Z bazy pobieram tylko minimalny zestaw danych o użytkowniku

-- 1. Tabela Użytkowników
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100),
    surname VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

CREATE INDEX idx_users_email ON users(email);

-- 2. Tabela Kategorii (Hiragana, Katakana, Kanji z poziomami)
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    level INT DEFAULT 1,
    description TEXT
);

-- 3. Tabela Znaków (Hiragana, Katakana, Kanji)
CREATE TABLE characters (
    id SERIAL PRIMARY KEY,
    set_id INT NOT NULL,
    symbol VARCHAR(10) NOT NULL,
    romaji VARCHAR(20),
    meaning VARCHAR(100),
    stroke_count INT,
    place_order INT
);

CREATE INDEX idx_characters_set_id ON characters(set_id);

-- 4. Tabela Postępu (Klucz do funkcjonalności!)
CREATE TABLE user_progress (
    user_id INT NOT NULL REFERENCES users(id),
    character_id INT NOT NULL REFERENCES characters(id),
    view_count INT DEFAULT 0,
    is_mastered BOOLEAN DEFAULT FALSE,
    last_practiced TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, character_id)
);

CREATE INDEX idx_user_progress_user_id ON user_progress(user_id);
CREATE INDEX idx_user_progress_character_id ON user_progress(character_id);

-- 5. Tabela Aktywności (do login streaka i sesji)
CREATE TABLE user_activity (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(id),
    activity_date DATE DEFAULT CURRENT_DATE,
    UNIQUE(user_id, activity_date)
);

CREATE INDEX idx_user_activity_user_id ON user_activity(user_id);

-- Przykładowe dane testowe
-- Użytkownik testowy: testuser / Test1234
INSERT INTO users (username, email, password, name, surname) 
VALUES ('testuser', 'test@example.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5lW.2E62OhKlm', 'Jan', 'Kowalski')
ON CONFLICT (email) DO NOTHING;

-- Przykładowe znaki Hiragana (set_id = 1)
INSERT INTO characters (set_id, symbol, romaji, meaning, place_order) VALUES 
-- Samogłoski
(1, 'あ', 'a', NULL, 1), (1, 'い', 'i', NULL, 2), (1, 'う', 'u', NULL, 3), (1, 'え', 'e', NULL, 4), (1, 'お', 'o', NULL, 5),
-- K-grupa
(1, 'か', 'ka', NULL, 6), (1, 'き', 'ki', NULL, 7), (1, 'く', 'ku', NULL, 8), (1, 'け', 'ke', NULL, 9), (1, 'こ', 'ko', NULL, 10),
-- S-grupa
(1, 'さ', 'sa', NULL, 11), (1, 'し', 'shi', NULL, 12), (1, 'す', 'su', NULL, 13), (1, 'せ', 'se', NULL, 14), (1, 'そ', 'so', NULL, 15),
-- T-grupa
(1, 'た', 'ta', NULL, 16), (1, 'ち', 'chi', NULL, 17), (1, 'つ', 'tsu', NULL, 18), (1, 'て', 'te', NULL, 19), (1, 'と', 'to', NULL, 20),
-- N-grupa
(1, 'な', 'na', NULL, 21), (1, 'に', 'ni', NULL, 22), (1, 'ぬ', 'nu', NULL, 23), (1, 'ね', 'ne', NULL, 24), (1, 'の', 'no', NULL, 25),
-- H-grupa
(1, 'は', 'ha', NULL, 26), (1, 'ひ', 'hi', NULL, 27), (1, 'ふ', 'fu', NULL, 28), (1, 'へ', 'he', NULL, 29), (1, 'ほ', 'ho', NULL, 30),
-- M-grupa
(1, 'ま', 'ma', NULL, 31), (1, 'み', 'mi', NULL, 32), (1, 'む', 'mu', NULL, 33), (1, 'め', 'me', NULL, 34), (1, 'も', 'mo', NULL, 35),
-- Y-grupa
(1, 'や', 'ya', NULL, 36), (1, 'ゆ', 'yu', NULL, 37), (1, 'よ', 'yo', NULL, 38),
-- R-grupa
(1, 'ら', 'ra', NULL, 39), (1, 'り', 'ri', NULL, 40), (1, 'る', 'ru', NULL, 41), (1, 'れ', 're', NULL, 42), (1, 'ろ', 'ro', NULL, 43),
-- W-grupa i N
(1, 'わ', 'wa', NULL, 44), (1, 'を', 'wo', NULL, 45), (1, 'ん', 'n', NULL, 46)
ON CONFLICT DO NOTHING;
