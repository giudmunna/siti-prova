// Menu Hamburger Mobile
const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');
if (hamburger && navLinks) {
    hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });
}

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

// Aggancia automaticamente la lightbox alle immagini della galleria
document.addEventListener('DOMContentLoaded', () => {
    const galleryGrid = document.getElementById('gallery-grid');
    if (galleryGrid) {
        const imgs = galleryGrid.querySelectorAll('img.js-lightbox');
        imgs.forEach((img) => {
            img.addEventListener('click', () => {
                const src = img.getAttribute('data-lightbox-src') || img.getAttribute('src');
                if (src) openLightbox(src);
            });
        });
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