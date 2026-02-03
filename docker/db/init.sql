-- ============================================================================
-- KANJI LEARNER - DATABASE SCHEMA
-- ============================================================================
-- Zaktualizowany: 2026-02-03
-- PostgreSQL 18.1
-- 
-- Ten plik zawiera kompletny schemat bazy danych dla aplikacji Kanji Learner
-- wraz z przykładowymi danymi (bez użytkowników testowych).
-- ============================================================================

-- ============================================================================
-- 1. TABELA: users
-- ============================================================================
-- Przechowuje dane użytkowników aplikacji
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100),
    surname VARCHAR(100),
    bio TEXT,
    profile_picture BYTEA,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
CREATE INDEX IF NOT EXISTS idx_users_username ON users(username);

COMMENT ON TABLE users IS 'Użytkownicy aplikacji';
COMMENT ON COLUMN users.password IS 'Hasło zahashowane przez password_hash() w PHP';
COMMENT ON COLUMN users.profile_picture IS 'Zdjęcie profilowe w formacie binarnym (BYTEA)';

-- ============================================================================
-- 2. TABELA: modules
-- ============================================================================
-- Moduły/Zajęcia do nauki (np. Hiragana, Katakana, Kanji JLPT N5, etc.)
CREATE TABLE IF NOT EXISTS modules (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    level VARCHAR(10) NOT NULL, -- A1, A2, B1, B2, C1, C2
    description TEXT,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_modules_level ON modules(level);

COMMENT ON TABLE modules IS 'Moduły/zajęcia do nauki (Hiragana, Katakana, Kanji)';
COMMENT ON COLUMN modules.level IS 'Poziom trudności: A1, A2, B1, B2, C1, C2';
COMMENT ON COLUMN modules.display_order IS 'Kolejność wyświetlania w interfejsie';

-- ============================================================================
-- 3. TABELA: characters
-- ============================================================================
-- Znaki japońskie (Hiragana, Katakana, Kanji) przypisane do modułów
CREATE TABLE IF NOT EXISTS characters (
    id SERIAL PRIMARY KEY,
    set_id INT NOT NULL, -- Odniesienie do modules.id
    symbol VARCHAR(10) NOT NULL,
    romaji VARCHAR(20),
    meaning VARCHAR(100),
    stroke_order VARCHAR(255),
    place_order INT
);

CREATE INDEX IF NOT EXISTS idx_characters_set_id ON characters(set_id);
CREATE INDEX IF NOT EXISTS idx_characters_place_order ON characters(place_order);

COMMENT ON TABLE characters IS 'Znaki japońskie (Hiragana, Katakana, Kanji)';
COMMENT ON COLUMN characters.set_id IS 'ID modułu, do którego należy znak (referencja do modules.id)';
COMMENT ON COLUMN characters.symbol IS 'Znak japoński (np. あ, ア, 漢)';
COMMENT ON COLUMN characters.romaji IS 'Zapis łaciński (np. "a", "ka", "kanji")';
COMMENT ON COLUMN characters.meaning IS 'Znaczenie znaku (dla Kanji)';
COMMENT ON COLUMN characters.stroke_count IS 'Liczba kresek w znaku';

-- ============================================================================
-- 4. TABELA: user_progress
-- ============================================================================
-- Postęp użytkownika w nauce znaków
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
CREATE INDEX IF NOT EXISTS idx_user_progress_is_mastered ON user_progress(is_mastered);

COMMENT ON TABLE user_progress IS 'Postęp użytkownika w nauce znaków';
COMMENT ON COLUMN user_progress.view_count IS 'Ile razy użytkownik oglądał ten znak';
COMMENT ON COLUMN user_progress.is_mastered IS 'Czy użytkownik opanował ten znak';

-- ============================================================================
-- 5. TABELA: user_activity
-- ============================================================================
-- Aktywność użytkownika (login streak)
CREATE TABLE IF NOT EXISTS user_activity (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    activity_date DATE DEFAULT CURRENT_DATE,
    UNIQUE(user_id, activity_date)
);

CREATE INDEX IF NOT EXISTS idx_user_activity_user_id ON user_activity(user_id);
CREATE INDEX IF NOT EXISTS idx_user_activity_date ON user_activity(activity_date);

COMMENT ON TABLE user_activity IS 'Aktywność użytkownika - używane do login streaka';

-- ============================================================================
-- 6. TABELA: user_drawings
-- ============================================================================
-- Rysunki użytkownika podczas nauki
CREATE TABLE IF NOT EXISTS user_drawings (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    session_id VARCHAR(50),
    character_id INT NOT NULL REFERENCES characters(id) ON DELETE CASCADE,
    romaji VARCHAR(20),
    drawing_data BYTEA,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_user_drawings_user_id ON user_drawings(user_id);
CREATE INDEX IF NOT EXISTS idx_user_drawings_session_id ON user_drawings(session_id);
CREATE INDEX IF NOT EXISTS idx_user_drawings_character_id ON user_drawings(character_id);

COMMENT ON TABLE user_drawings IS 'Rysunki użytkownika podczas sesji nauki';
COMMENT ON COLUMN user_drawings.drawing_data IS 'Dane rysunku w formacie binarnym (PNG)';

-- ============================================================================
-- WIDOKI (VIEWS)
-- ============================================================================

-- Widok: Podsumowanie postępu użytkownika
CREATE OR REPLACE VIEW user_progress_summary AS
SELECT 
    u.id AS user_id,
    u.username,
    u.email,
    COUNT(DISTINCT up.character_id) AS total_characters_viewed,
    COUNT(DISTINCT CASE WHEN up.is_mastered = TRUE THEN up.character_id END) AS mastered_count,
    COALESCE(
        ROUND(
            COUNT(DISTINCT CASE WHEN up.is_mastered = TRUE THEN up.character_id END)::numeric / 
            NULLIF(COUNT(DISTINCT up.character_id), 0) * 100, 
            2
        ), 
        0
    ) AS mastery_percentage,
    MAX(up.last_practiced) AS last_activity
FROM users u
LEFT JOIN user_progress up ON u.id = up.user_id
GROUP BY u.id, u.username, u.email;

COMMENT ON VIEW user_progress_summary IS 'Podsumowanie postępu użytkowników w nauce znaków';

-- ============================================================================
-- FUNKCJE (FUNCTIONS)
-- ============================================================================

-- Funkcja: Obliczanie streaka użytkownika
CREATE OR REPLACE FUNCTION get_user_streak(p_user_id INT)
RETURNS INT AS $$
DECLARE
    streak_count INT := 0;
    check_date DATE := CURRENT_DATE;
BEGIN
    -- Sprawdzamy czy użytkownik był aktywny dzisiaj lub wczoraj
    IF NOT EXISTS (
        SELECT 1 FROM user_activity 
        WHERE user_id = p_user_id 
        AND activity_date IN (CURRENT_DATE, CURRENT_DATE - INTERVAL '1 day')
    ) THEN
        RETURN 0;
    END IF;
    
    -- Jeśli nie był aktywny dzisiaj, zaczynamy od wczoraj
    IF NOT EXISTS (
        SELECT 1 FROM user_activity 
        WHERE user_id = p_user_id 
        AND activity_date = CURRENT_DATE
    ) THEN
        check_date := CURRENT_DATE - INTERVAL '1 day';
    END IF;
    
    -- Liczymy streak
    LOOP
        IF EXISTS (
            SELECT 1 FROM user_activity 
            WHERE user_id = p_user_id 
            AND activity_date = check_date
        ) THEN
            streak_count := streak_count + 1;
            check_date := check_date - INTERVAL '1 day';
        ELSE
            EXIT;
        END IF;
    END LOOP;
    
    RETURN streak_count;
END;
$$ LANGUAGE plpgsql;

COMMENT ON FUNCTION get_user_streak(INT) IS 'Oblicza streak użytkownika (ile dni z rzędu był aktywny)';

-- Funkcja: Aktualizacja last_login
CREATE OR REPLACE FUNCTION update_last_login()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE users 
    SET last_login = CURRENT_TIMESTAMP 
    WHERE id = NEW.user_id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

COMMENT ON FUNCTION update_last_login() IS 'Automatycznie aktualizuje last_login w tabeli users';

-- ============================================================================
-- WYZWALACZE (TRIGGERS)
-- ============================================================================

-- Trigger: Automatyczna aktualizacja last_login przy aktywności
DROP TRIGGER IF EXISTS trigger_update_last_login ON user_activity;
CREATE TRIGGER trigger_update_last_login
AFTER INSERT ON user_activity
FOR EACH ROW
EXECUTE FUNCTION update_last_login();

COMMENT ON TRIGGER trigger_update_last_login ON user_activity IS 'Aktualizuje last_login gdy użytkownik jest aktywny';

-- ============================================================================
-- DANE TESTOWE - MODUŁY
-- ============================================================================

INSERT INTO modules (name, level, description, display_order) VALUES 
-- Poziom A1
('Hiragana', 'A1', 'Podstawowy japoński sylabariusz', 1),
('Katakana', 'A1', 'Do słów obcego pochodzenia', 2),
('Kanji Basics', 'A1', 'Podstawowe znaki logograficzne', 3),
('Rozdział 1: Liczby i dni', 'A1', 'Naucz się podstaw liczenia', 4),
('Kanji JLPT N5', 'A1', '103 Kanji do opanowania', 5),
('Rozdział 2: Rodzina i dom', 'A1', 'Podstawowe znaki o rodzinie', 6),

-- Poziom A2
('Rozdział 3: Jedzenie', 'A2', 'Kanji związane z jedzeniem', 7),
('Kanji JLPT N4', 'A2', '181 Kanji do opanowania', 8),
('Rozdział 4: Miasto i transport', 'A2', 'Poruszanie się po mieście', 9),

-- Poziom B1
('Rozdział 5: Szkoła i praca', 'B1', 'Znaki o edukacji i karierze', 10),
('Kanji JLPT N3', 'B1', '367 Kanji do opanowania', 11),
('Rozdział 6: Natura i pogoda', 'B1', 'Świat przyrody w Kanji', 12),

-- Poziom B2
('Rozdział 7: Zdrowie i ciało', 'B2', 'Zaawansowane medyczne Kanji', 13),
('Kanji JLPT N2', 'B2', '415 Kanji do opanowania', 14),
('Rozdział 8: Emocje i myśli', 'B2', 'Abstrakcyjne pojęcia', 15),

-- Poziom C1
('Rozdział 9: Historia i kultura', 'C1', 'Zaawansowane kulturowe Kanji', 16),
('Kanji JLPT N1', 'C1', '523 Kanji do opanowania', 17),

-- Poziom C2
('Rozdział 10: Literatura', 'C2', 'Literackie i rzadkie Kanji', 18),
('Rozdział 11: Nauka i technologia', 'C2', 'Specjalistyczne Kanji', 19)
ON CONFLICT DO NOTHING;

-- ============================================================================
-- DANE TESTOWE - UŻYTKOWNIK TESTOWY (opcjonalnie)
-- ============================================================================

-- Użytkownik testowy: testuser / Test1234
-- Hash wygenerowany przez: password_hash('Test1234', PASSWORD_BCRYPT, ['cost' => 12])
INSERT INTO users (username, email, password, name, surname) 
VALUES ('testuser', 'test@example.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5lW.2E62OhKlm', 'Jan', 'Kowalski')
ON CONFLICT (email) DO NOTHING;

-- ============================================================================
-- DANE TESTOWE - ZNAKI HIRAGANA (set_id = 1)
-- ============================================================================

INSERT INTO characters (set_id, symbol, romaji, meaning, place_order) VALUES 
-- Samogłoski
(1, 'あ', 'a', NULL, 1), 
(1, 'い', 'i', NULL, 2), 
(1, 'う', 'u', NULL, 3), 
(1, 'え', 'e', NULL, 4), 
(1, 'お', 'o', NULL, 5),

-- K-grupa
(1, 'か', 'ka', NULL, 6), 
(1, 'き', 'ki', NULL, 7), 
(1, 'く', 'ku', NULL, 8), 
(1, 'け', 'ke', NULL, 9), 
(1, 'こ', 'ko', NULL, 10),

-- S-grupa
(1, 'さ', 'sa', NULL, 11), 
(1, 'し', 'shi', NULL, 12), 
(1, 'す', 'su', NULL, 13), 
(1, 'せ', 'se', NULL, 14), 
(1, 'そ', 'so', NULL, 15),

-- T-grupa
(1, 'た', 'ta', NULL, 16), 
(1, 'ち', 'chi', NULL, 17), 
(1, 'つ', 'tsu', NULL, 18), 
(1, 'て', 'te', NULL, 19), 
(1, 'と', 'to', NULL, 20),

-- N-grupa
(1, 'な', 'na', NULL, 21), 
(1, 'に', 'ni', NULL, 22), 
(1, 'ぬ', 'nu', NULL, 23), 
(1, 'ね', 'ne', NULL, 24), 
(1, 'の', 'no', NULL, 25),

-- H-grupa
(1, 'は', 'ha', NULL, 26), 
(1, 'ひ', 'hi', NULL, 27), 
(1, 'ふ', 'fu', NULL, 28), 
(1, 'へ', 'he', NULL, 29), 
(1, 'ほ', 'ho', NULL, 30),

-- M-grupa
(1, 'ま', 'ma', NULL, 31), 
(1, 'み', 'mi', NULL, 32), 
(1, 'む', 'mu', NULL, 33), 
(1, 'め', 'me', NULL, 34), 
(1, 'も', 'mo', NULL, 35),

-- Y-grupa
(1, 'や', 'ya', NULL, 36), 
(1, 'ゆ', 'yu', NULL, 37), 
(1, 'よ', 'yo', NULL, 38),

-- R-grupa
(1, 'ら', 'ra', NULL, 39), 
(1, 'り', 'ri', NULL, 40), 
(1, 'る', 'ru', NULL, 41), 
(1, 'れ', 're', NULL, 42), 
(1, 'ろ', 'ro', NULL, 43),

-- W-grupa i N
(1, 'わ', 'wa', NULL, 44), 
(1, 'を', 'wo', NULL, 45), 
(1, 'ん', 'n', NULL, 46)
ON CONFLICT DO NOTHING;

-- ============================================================================
-- DANE TESTOWE - ZNAKI KATAKANA (set_id = 2)
-- ============================================================================

INSERT INTO characters (set_id, symbol, romaji, meaning, place_order) VALUES 
-- Samogłoski
(2, 'ア', 'a', NULL, 1), 
(2, 'イ', 'i', NULL, 2), 
(2, 'ウ', 'u', NULL, 3), 
(2, 'エ', 'e', NULL, 4), 
(2, 'オ', 'o', NULL, 5),

-- K-grupa
(2, 'カ', 'ka', NULL, 6), 
(2, 'キ', 'ki', NULL, 7), 
(2, 'ク', 'ku', NULL, 8), 
(2, 'ケ', 'ke', NULL, 9), 
(2, 'コ', 'ko', NULL, 10)
ON CONFLICT DO NOTHING;

-- ============================================================================
-- KONIEC SCHEMATU
-- ============================================================================

-- Informacja dla użytkownika
DO $$
BEGIN
    RAISE NOTICE '============================================================================';
    RAISE NOTICE 'KANJI LEARNER - Schemat bazy danych załadowany pomyślnie!';
    RAISE NOTICE '============================================================================';
    RAISE NOTICE 'Utworzono:';
    RAISE NOTICE '  - 6 tabel (users, modules, characters, user_progress, user_activity, user_drawings)';
    RAISE NOTICE '  - 1 widok (user_progress_summary)';
    RAISE NOTICE '  - 2 funkcje (get_user_streak, update_last_login)';
    RAISE NOTICE '  - 1 trigger (trigger_update_last_login)';
    RAISE NOTICE '  - 19 modułów do nauki';
    RAISE NOTICE '  - 56 znaków (46 Hiragana + 10 Katakana)';
    RAISE NOTICE '============================================================================';
END $$;
