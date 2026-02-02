// Modules page functionality - filtering and search

document.addEventListener('DOMContentLoaded', function() {
    initializeModules();
});

function initializeModules() {
    const searchInput = document.getElementById('moduleSearch');
    const filterTabs = document.querySelectorAll('.filter-tab');
    const moduleCards = document.querySelectorAll('.module-card');
    const noResultsMessage = document.getElementById('noResultsMessage');
    
    let currentLevel = 'all';
    let currentSearchTerm = '';

    // Obsługa wyszukiwania
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            currentSearchTerm = e.target.value.toLowerCase().trim();
            filterModules();
        });
    }

    // Obsługa filtrowania po poziomie
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Usuń active z wszystkich tabów
            filterTabs.forEach(t => t.classList.remove('active'));
            
            // Dodaj active do klikniętego taba
            this.classList.add('active');
            
            // Pobierz wybrany poziom
            currentLevel = this.getAttribute('data-level');
            
            // Filtruj moduły
            filterModules();
        });
    });

    function filterModules() {
        let visibleCount = 0;

        moduleCards.forEach(card => {
            const cardLevel = card.getAttribute('data-level');
            const cardName = card.getAttribute('data-name');
            
            // Sprawdź czy karta pasuje do filtru poziom
            const levelMatch = currentLevel === 'all' || cardLevel === currentLevel;
            
            // Sprawdź czy karta pasuje do wyszukiwania
            const searchMatch = currentSearchTerm === '' || cardName.includes(currentSearchTerm);
            
            // Pokaż lub ukryj kartę
            if (levelMatch && searchMatch) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        // Pokaż komunikat jeśli brak wyników
        if (noResultsMessage) {
            if (visibleCount === 0) {
                noResultsMessage.style.display = 'block';
            } else {
                noResultsMessage.style.display = 'none';
            }
        }
    }

    // Inicjalne filtrowanie (może być przydatne jeśli są parametry URL)
    filterModules();
}
