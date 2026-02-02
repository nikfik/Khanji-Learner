function openModal(symbol, romaji, strokePath) {
    const modal = document.getElementById('char-modal-overlay');
    document.getElementById('modal-jp-char').innerText = symbol;
    document.getElementById('modal-romaji-text').innerText = romaji;
    document.getElementById('modal-stroke-img').src = strokePath || 'public/img/default-stroke.png';

    modal.style.display = 'flex';
}

// Zamykanie
document.getElementById('close-modal').addEventListener('click', () => {
    document.getElementById('char-modal-overlay').style.display = 'none';
});

// Zamykanie po kliknięciu poza okienko
window.onclick = function(event) {
    const modal = document.getElementById('char-modal-overlay');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}


// ==================== SYSTEM NAUKI ====================

let learningSession = {
    characters: [],
    currentIndex: 0,
    results: [],
    setId: null,
    sessionId: null  // Unikalny ID dla każdej sesji nauki
};

let canvas, ctx;
let isDrawing = false;

// Inicjalizacja canvas i przycisku nauki
window.addEventListener('DOMContentLoaded', () => {
    // Canvas
    canvas = document.getElementById('drawing-canvas');
    if (canvas) {
        ctx = canvas.getContext('2d');
        ctx.lineWidth = 5;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#333';
        // Obsługa rysowania
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);
        // Wsparcie dla touch
        canvas.addEventListener('touchstart', handleTouch);
        canvas.addEventListener('touchmove', handleTouch);
        canvas.addEventListener('touchend', stopDrawing);
    }
    // Przycisk nauki
    const startBtn = document.getElementById('start-learning-btn');
    if (startBtn) {
        startBtn.addEventListener('click', function() {
            const setId = this.getAttribute('data-set-id') || 1;
            startLearningSession(setId);
        });
    }
});

function startDrawing(e) {
    isDrawing = true;
    const rect = canvas.getBoundingClientRect();
    ctx.beginPath();
    ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
}

function draw(e) {
    if (!isDrawing) return;
    
    const rect = canvas.getBoundingClientRect();
    ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
    ctx.stroke();
}

function stopDrawing() {
    isDrawing = false;
}

function handleTouch(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const mouseEvent = new MouseEvent(e.type === 'touchstart' ? 'mousedown' : 'mousemove', {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    canvas.dispatchEvent(mouseEvent);
}

function clearCanvas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

// Rozpocznij sesję nauki
async function startLearningSession(setId) {
    // Sprawdź czy użytkownik jest zalogowany (sprawdź czy toolbar pokazuje user-profile)
    const userProfile = document.querySelector('.user-profile');
    if (!userProfile || !userProfile.querySelector('.profile-avatar')) {
        alert('Musisz być zalogowany, aby rozpocząć naukę. Zaloguj się lub zarejestruj.');
        window.location.href = '/login';
        return;
    }
    
    try {
        const response = await fetch(`/api/learning/start?setId=${setId}`);
        const data = await response.json();
        
        if (data.success) {
            learningSession.characters = data.characters;
            learningSession.currentIndex = 0;
            learningSession.results = [];
            learningSession.setId = setId;
            // Generuj unikalny session_id na START sesji
            learningSession.sessionId = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            
            document.getElementById('learning-modal').style.display = 'flex';
            document.getElementById('total-cards').textContent = data.characters.length;
            
            showCurrentCharacter();
        } else {
            alert('Nie udało się rozpocząć nauki. Spróbuj ponownie.');
        }
    } catch (error) {
        console.error('Error starting learning session:', error);
        alert('Wystąpił błąd podczas rozpoczynania nauki.');
    }
}

// Pokaż aktualny znak
function showCurrentCharacter() {
    const char = learningSession.characters[learningSession.currentIndex];
    
    // Aktualizuj wskaźnik postępu
    document.getElementById('current-card').textContent = learningSession.currentIndex + 1;
    
    // Ustaw dane znaku - romaji do głównego wyświetlenia
    document.getElementById('learning-romaji').textContent = char.romaji;
    
    // Ustaw dane znaku dla podpowiedzi (hint)
    document.getElementById('learning-char-hint').textContent = char.symbol;
    document.getElementById('learning-stroke-img-hint').src = char.stroke_image_path || 'public/img/default-stroke.png';
    
    // Ukryj hint na początku
    document.getElementById('hint-char').style.display = 'none';
    
    // Pokaż fazę pokazania
    showPhase('show-phase');
}

// Pokaż podpowiedź - znak
function revealCharacter() {
    const hintDiv = document.getElementById('hint-char');
    if (hintDiv.style.display === 'none') {
        hintDiv.style.display = 'block';
    } else {
        hintDiv.style.display = 'none';
    }
}

// Przejdź do fazy rysowania
function switchToDrawPhase() {
    showPhase('draw-phase');
    clearCanvas();
}

// Pokaż wybraną fazę
function showPhase(phaseId) {
    document.querySelectorAll('.learning-phase').forEach(phase => {
        phase.classList.remove('active');
    });
    document.getElementById(phaseId).classList.add('active');
}

// Oznacz jako poprawne
function markCorrect() {
    recordResult(true);
    saveDrawingToServer();
    nextCharacter();
}

// Oznacz jako niepoprawne
function markIncorrect() {
    recordResult(false);
    saveDrawingToServer();
    nextCharacter();
}

// Zapisz wynik
function recordResult(correct) {
    const char = learningSession.characters[learningSession.currentIndex];
    learningSession.results.push({
        character_id: char.id,
        correct: correct
    });
}

// Wyślij rysunek do serwera
function saveDrawingToServer() {
    const canvas = document.getElementById('drawing-canvas');
    const char = learningSession.characters[learningSession.currentIndex];
    
    if (!canvas || !char) return;
    
    const drawingData = canvas.toDataURL('image/png');
    
    fetch('/api/learning/saveDrawing', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            character_id: char.id,
            romaji: char.romaji,
            drawing_data: drawingData,
            session_id: learningSession.sessionId  // Użyj tego samego session_id dla wszystkich rysunków
        })
    }).catch(error => {
        console.error('Error saving drawing:', error);
        // Nie przerywamy sesji jeśli zapis się nie uda
    });
}

// Przejdź do następnego znaku
function nextCharacter() {
    learningSession.currentIndex++;
    
    if (learningSession.currentIndex < learningSession.characters.length) {
        showCurrentCharacter();
    } else {
        showResults();
    }
}

// Pokaż wyniki
function showResults() {
    const correct = learningSession.results.filter(r => r.correct).length;
    const incorrect = learningSession.results.filter(r => !r.correct).length;
    
    document.getElementById('correct-count').textContent = correct;
    document.getElementById('incorrect-count').textContent = incorrect;
    
    showPhase('results-phase');
}

// Zakończ naukę
async function finishLearning() {
    try {
        const response = await fetch('/api/learning/finish', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                results: learningSession.results
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            closeLearningModal();
            // Odśwież stronę aby pokazać zaktualizowany postęp
            location.reload();
        } else {
            alert('Nie udało się zapisać postępów.');
        }
    } catch (error) {
        console.error('Error finishing learning:', error);
        alert('Wystąpił błąd podczas zapisywania postępów.');
    }
}

// Zamknij modal nauki
function closeLearningModal() {
    document.getElementById('learning-modal').style.display = 'none';
    learningSession = {
        characters: [],
        currentIndex: 0,
        results: [],
        setId: null
    };
}

// Pokaż podpowiedź (stroke order)
function showReference() {
    const char = learningSession.characters[learningSession.currentIndex];
    const img = new Image();
    img.src = char.stroke_image_path || 'public/img/default-stroke.png';
    img.onload = () => {
        ctx.globalAlpha = 0.3;
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        ctx.globalAlpha = 1.0;
    };
}


