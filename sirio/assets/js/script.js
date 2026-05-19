// Menu Hamburger Mobile
document.querySelector('.hamburger').addEventListener('click', () => {
    document.querySelector('.nav-links').classList.toggle('active');
});

// Lightbox per la Galleria
function openLightbox(src) {
    const lightbox = document.createElement('div');
    lightbox.id = 'lightbox';
    lightbox.style.cssText = `
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.95); display: flex;
        align-items: center; justify-content: center; z-index: 9999;
    `;
    
    lightbox.innerHTML = `
        <img src="${src}" style="max-width: 95%; max-height: 95%; border-radius: 12px; box-shadow: 0 0 30px rgba(255,0,170,0.6);">
    `;
    
    lightbox.onclick = () => lightbox.remove();
    document.body.appendChild(lightbox);
}

// Carica automaticamente le immagini dalla cartella galleria
document.addEventListener('DOMContentLoaded', () => {
    const galleryGrid = document.getElementById('gallery-grid');
    if (galleryGrid) {
        // Qui puoi lasciare il caricamento manuale o aggiungere fetch se vuoi
        console.log('%cGalleria pronta - carica le foto in assets/img/galleria/', 'color: #ff00aa');
    }
});

// Messaggio di successo dal form
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('status') === 'success') {
    alert("✅ Messaggio inviato con successo!\nTi contatteremo il prima possibile.");
    // Pulisce l'URL
    window.history.replaceState(null, null, 'contatti.php');
}
if (urlParams.get('status') === 'error') {
    alert("❌ Si è verificato un errore. Riprova più tardi.");
}