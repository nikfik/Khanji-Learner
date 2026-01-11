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

const header = document.querySelector('#title');
console.log(header);

header.addEventListener('click', () => {
    header.style.color = 'green';
})


