// Podgląd zdjęcia
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

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
    
    const name = document.getElementById('name').value;
    const surname = document.getElementById('surname').value;
    const username = document.getElementById('username').value;
    const bio = document.getElementById('bio').value;
    const fileInput = document.getElementById('profilePicture');
    
    // Przygotuj dane do wysłania
    const formData = new FormData();
    formData.append('name', name);
    formData.append('surname', surname);
    formData.append('username', username);
    formData.append('bio', bio);
    
    // Jeśli jest wybrany plik, dodaj go
    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        
        // Sprawdź typ pliku
        if (!file.type.startsWith('image/')) {
            alert('Proszę wybrać plik obrazu');
            return;
        }
        
        // Sprawdź rozmiar (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Plik jest za duży. Maksymalny rozmiar to 5MB');
            return;
        }
        
        // Konwertuj plik na base64
        const reader = new FileReader();
        reader.onload = async function(e) {
            const base64Data = e.target.result.split(',')[1];
            formData.set('profilePictureBase64', base64Data);
            
            // Wyślij żądanie
            await sendProfileUpdate(formData);
        };
        reader.readAsDataURL(file);
    } else {
        // Wyślij bez zdjęcia
        await sendProfileUpdate(formData);
    }
}

// Pomocnicza funkcja do wysyłania danych profilu
async function sendProfileUpdate(formData) {
    try {
        // Konwertuj FormData do JSON
        const data = {
            name: formData.get('name'),
            surname: formData.get('surname'),
            username: formData.get('username'),
            bio: formData.get('bio')
        };
        
        if (formData.get('profilePictureBase64')) {
            data.profilePictureBase64 = formData.get('profilePictureBase64');
        }
        
        const response = await fetch('/api/profile/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert('Błąd: ' + (result.message || 'Nie udało się zapisać profilu'));
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
