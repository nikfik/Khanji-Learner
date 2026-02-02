-- Tabela Modułów/Zajęć
CREATE TABLE IF NOT EXISTS modules (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    level VARCHAR(10) NOT NULL, -- A1, A2, B1, B2, C1, C2
    description TEXT,
    character_count INT DEFAULT 0,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_modules_level ON modules(level);

-- Przykładowe dane testowe
INSERT INTO modules (name, level, description, character_count, display_order) VALUES 
('Rozdział 1: Liczby i dni', 'A1', 'Naucz się podstaw liczenia', 50, 1),
('Kanji JLPT N5', 'A1', '103 Kanji do opanowania', 103, 2),
('Rozdział 2: Rodzina i dom', 'A1', 'Podstawowe znaki o rodzinie', 45, 3),
('Rozdział 3: Jedzenie', 'A2', 'Kanji związane z jedzeniem', 60, 4),
('Kanji JLPT N4', 'A2', '181 Kanji do opanowania', 181, 5),
('Rozdział 4: Miasto i transport', 'A2', 'Poruszanie się po mieście', 55, 6),
('Rozdział 5: Szkoła i praca', 'B1', 'Znaki o edukacji i karierze', 70, 7),
('Kanji JLPT N3', 'B1', '367 Kanji do opanowania', 367, 8),
('Rozdział 6: Natura i pogoda', 'B1', 'Świat przyrody w Kanji', 65, 9),
('Rozdział 7: Zdrowie i ciało', 'B2', 'Zaawansowane medyczne Kanji', 80, 10),
('Kanji JLPT N2', 'B2', '415 Kanji do opanowania', 415, 11),
('Rozdział 8: Emocje i myśli', 'B2', 'Abstrakcyjne pojęcia', 75, 12),
('Rozdział 9: Historia i kultura', 'C1', 'Zaawansowane kulturowe Kanji', 90, 13),
('Kanji JLPT N1', 'C1', '523 Kanji do opanowania', 523, 14),
('Rozdział 10: Literatura', 'C2', 'Literackie i rzadkie Kanji', 100, 15),
('Rozdział 11: Nauka i technologia', 'C2', 'Specjalistyczne Kanji', 95, 16)
ON CONFLICT DO NOTHING;
