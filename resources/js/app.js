import './bootstrap';
import Alpine from 'alpinejs';
import { showSuccessAlert, showErrorAlert, showLoading, closeLoading,showConfirm  } from './alert.js';

window.showSuccessAlert = showSuccessAlert;
window.showErrorAlert   = showErrorAlert;
window.showLoading      = showLoading;
window.closeLoading     = closeLoading;
window.showConfirmAlert = showConfirm;


window.Alpine = Alpine;
Alpine.start();


// Zoom logo image on click
document.addEventListener('DOMContentLoaded', () => {
    const zoomImages = document.querySelectorAll('#logo-zoom');

    zoomImages.forEach(img => {
        img.style.transition = 'transform 0.3s ease';

        img.addEventListener('click', (e) => {
            e.stopPropagation(); 

            const modal = document.createElement('div');
            modal.style.position = 'fixed';
            modal.style.inset = '0';
            modal.style.background = 'rgba(0,0,0,0.85)';
            modal.style.display = 'flex';
            modal.style.justifyContent = 'center';
            modal.style.alignItems = 'center';
            modal.style.zIndex = '9999';
            modal.style.cursor = 'zoom-out';

            const modalImg = document.createElement('img');
            modalImg.src = img.src;
            modalImg.alt = img.alt || 'Zoomed image';
            modalImg.style.maxWidth = '90vw';
            modalImg.style.maxHeight = '90vh';
            modalImg.style.objectFit = 'contain';
            modalImg.style.borderRadius = '12px';
            modalImg.style.boxShadow = '0 10px 40px rgba(0,0,0,0.6)';
            modalImg.style.background = '#fff';
            modalImg.style.padding = img.classList.contains('rounded-full') ? '16px' : '0';

            modal.appendChild(modalImg);

            modal.addEventListener('click', () => {
                modal.remove();
                document.body.style.overflow = '';
            });

            modalImg.addEventListener('click', (e) => e.stopPropagation());

            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden'; 
        });
    });
});


// Show/hide Toggle Password
    document.addEventListener('click', function (e) {

        const toggleBtn = e.target.closest('[data-toggle-password]');
        if (!toggleBtn) return;

        const input = toggleBtn.closest('div').querySelector('input[type="password"], input[type="text"]');
        if (!input) return;

        if (input.type === 'password') {
            input.type = 'text';
            toggleBtn.textContent = '🙈';
        } else {
            input.type = 'password';
            toggleBtn.textContent = '👁';
        }
    });

// Show Dropdown Menu on click
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

window.toggleDropdown = toggleDropdown;
document.addEventListener('DOMContentLoaded', () => {
    const langSelect = document.getElementById('languageSelect');
    if (langSelect) {
        langSelect.value = localStorage.getItem('lang') || 'id';
    }
});
document.addEventListener('turbo:load', () => {
    const langSelect = document.getElementById('languageSelect');
    if (langSelect) {
        langSelect.value = localStorage.getItem('lang') || 'id';
    }
});

window.toggleDarkMode = function() {
    const html = document.documentElement;
    const btn = document.getElementById('darkModeBtn');
    
    const isCurrentlyDark = html.classList.contains('dark');

    if (isCurrentlyDark) {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        if (btn) btn.innerHTML = 'Dark Mode';
    } else {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        if (btn) btn.innerHTML = 'Light Mode';
    }
};

function initDarkMode() {
    const html = document.documentElement;
    const btn = document.getElementById('darkModeBtn');
    const savedTheme = localStorage.getItem('theme');

    if (savedTheme === 'dark' || 
        (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        
        html.classList.add('dark');
        if (btn) btn.innerHTML = 'Light Mode';
    } else {
        html.classList.remove('dark');
        if (btn) btn.innerHTML = 'Dark Mode';
    }
}

// Jalankan inisialisasi saat DOM siap dan setelah Turbo load
document.addEventListener('DOMContentLoaded', initDarkMode);
document.addEventListener('turbo:load', initDarkMode);

// Pilihan Bahasa
window.changeLanguage = function() {
    const lang = document.getElementById('languageSelect').value;
    localStorage.setItem('lang', lang);
    location.reload();
}

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

