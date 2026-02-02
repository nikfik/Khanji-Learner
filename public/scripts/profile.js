// Otwórz modal edytowania
function openEditModal() {
    document.getElementById('edit-modal').style.visibility = 'visible';
}

// Zamknij modal edytowania
function closeEditModal() {
    document.getElementById('edit-modal').style.visibility = 'hidden';
}

// Zamykanie modala po kliknięciu poza nim
window.addEventListener('click', function(event) {
    const modal = document.getElementById('edit-modal');
    if (event.target == modal) {
        closeEditModal();
    }
});

// Zapisz zmiany profilu
async function saveProfile(event) {
    event.preventDefault();
    
    const username = document.getElementById('username').value;
    const bio = document.getElementById('bio').value;
    
    try {
        const response = await fetch('/api/profile/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                username: username,
                bio: bio
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Przeładuj stronę aby wyświetlić zaktualizowane dane
            location.reload();
        } else {
            alert('Błąd: ' + (data.message || 'Nie udało się zapisać profilu'));
        }
    } catch (error) {
        console.error('Error saving profile:', error);
        alert('Błąd podczas zapisywania profilu');
    }
}

// Rozwiń wszystkie sesje
function toggleAllSessions() {
    const btn = event.target.closest('.expand-btn');
    const container = btn.parentElement;
    
    if (btn.classList.contains('expanded')) {
        // Zwin
        btn.classList.remove('expanded');
        btn.textContent = 'Rozwiń wszystkie';
        // Usuń dodatkowe sesje
        const extraSessions = container.querySelectorAll('.session-row:nth-child(n+5)');
        extraSessions.forEach(s => s.remove());
    } else {
        // Rozwiń
        btn.classList.add('expanded');
        btn.textContent = 'Zwiń sesje';
        // Dodaj pozostałe sesje (jeśli backend je przygotował)
        // To będzie obsługiwane przez PHP
    }
}
