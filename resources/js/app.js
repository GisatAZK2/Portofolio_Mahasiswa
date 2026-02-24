
import './bootstrap';
import { showSuccessAlert, showErrorAlert } from './alert.js';

window.showSuccessAlert = showSuccessAlert;
window.showErrorAlert = showErrorAlert;



// Zoom logo image on click
window.addEventListener('DOMContentLoaded', () => {
    const logoImg = document.querySelector('.logo-zoom');
    if (!logoImg) return;

    logoImg.style.transition = 'transform 0.3s ease';

    logoImg.addEventListener('click', () => {
        if (logoImg.classList.contains('zoomed')) {
            logoImg.classList.remove('zoomed');
            logoImg.style.transform = 'scale(1)';
        } else {
            logoImg.classList.add('zoomed');
            logoImg.style.transform = 'scale(1.5)';
        }
    });

    // Optional: click outside to reset zoom
    document.addEventListener('click', (e) => {
        if (!logoImg.contains(e.target) && logoImg.classList.contains('zoomed')) {
            logoImg.classList.remove('zoomed');
            logoImg.style.transform = 'scale(1)';
        }
    });

    // Modal popup for logo image
    // Create modal element
    const modal = document.createElement('div');
    modal.id = 'logoModal';
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100vw';
    modal.style.height = '100vh';
    modal.style.background = 'rgba(0,0,0,0.6)';
    modal.style.display = 'none';
    modal.style.justifyContent = 'center';
    modal.style.alignItems = 'center';
    modal.style.zIndex = '9999';

    // Modal image
    const modalImg = document.createElement('img');
    modalImg.src = logoImg.src;
    modalImg.alt = logoImg.alt;
    modalImg.style.maxWidth = '60vw';
    modalImg.style.maxHeight = '60vh';
    modalImg.style.borderRadius = '50%';
    modalImg.style.boxShadow = '0 0 20px #0008';
    modalImg.style.background = '#fff';
    modalImg.style.padding = '24px';
    modal.appendChild(modalImg);

    // Close modal on click
    modal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    document.body.appendChild(modal);

    logoImg.addEventListener('click', () => {
        modal.style.display = 'flex';
    });
});

// Show Dropdown Menu Porto on click
function toggleDropdown(section) {
    const menuId  = section + 'Menu';
    const arrowId = section + 'Arrow';

    const menu  = document.getElementById(menuId);
    const arrow = document.getElementById(arrowId);

    if (!menu || !arrow) {
        console.warn(`Dropdown section "${section}" not found`);
        return;
    }

    menu.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
}

// Ekspor ke window supaya bisa dipanggil dari onclick
window.toggleDropdown = toggleDropdown;

// Optional: Tutup sidebar mobile setelah klik link (UX lebih baik)
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;

    document.querySelectorAll('#sidebar a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 1024) { // lg breakpoint
                sidebar.classList.add('-translate-x-full');
            }
        });
    });

    // Close button mobile
    document.getElementById('close-sidebar')?.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
    });
});

