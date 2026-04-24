
import './bootstrap';
import Alpine from 'alpinejs';
import { showSuccessAlert, showErrorAlert, showLoading, closeLoading, showConfirm, showInfoAlert } from './alert.js';
import './translate';

window.showSuccessAlert = showSuccessAlert;
window.showErrorAlert = showErrorAlert;
window.showLoading = showLoading;
window.closeLoading = closeLoading;
window.showConfirmAlert = showConfirm;

let deferredPrompt = null;
let installBtn = null;

function isAppInstalled() {
    return window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
}

function updateInstallButton() {
    if (!installBtn) return;

    if (isAppInstalled()) {
        installBtn.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>Terinstall</span>
        `;
        installBtn.disabled = true;
        installBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        installBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        installBtn.disabled = deferredPrompt ? false : true;
    }
}

function setupInstallButton() {
    installBtn = document.getElementById('direct-install-btn');
    if (!installBtn) return;

    installBtn.disabled = true;

    installBtn.addEventListener('click', async () => {
        if (isAppInstalled()) {
            showSuccessAlert('Aplikasi sudah terpasang.');
            return;
        }

        if (!deferredPrompt) {
            showInfoAlert('Install prompt belum tersedia. Silakan gunakan icon install browser atau ikuti panduan Android/iOS di atas.');
            return;
        }

        deferredPrompt.prompt();
        showLoading('Installing...');
        const choiceResult = await deferredPrompt.userChoice;
        closeLoading();

        if (choiceResult.outcome === 'accepted') {
            showSuccessAlert('App installed successfully!');
        }

        deferredPrompt = null;
        updateInstallButton();
    });

    updateInstallButton();
}

window.addEventListener('DOMContentLoaded', setupInstallButton);

window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    deferredPrompt = event;
    window.deferredPrompt = event;
    updateInstallButton();
});

window.addEventListener('appinstalled', () => {
    deferredPrompt = null;
    updateInstallButton();
    showSuccessAlert('App installed successfully!');
});


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
    const menuId = section + 'Menu';
    const arrowId = section + 'Arrow';

    const menu = document.getElementById(menuId);
    const arrow = document.getElementById(arrowId);

    if (!menu || !arrow) {
        console.warn(`Dropdown section "${section}" not found`);
        return;
    }

    menu.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
}

window.toggleDropdown = toggleDropdown;

// Register Service Worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js')
        .then(registration => {
            console.log('SW registered: ', registration);
        })
        .catch(error => {
            console.log('SW registration failed: ', error);
        });
}

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

window.toggleDarkMode = function () {
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

function setActionButtonProcessing(button) {
    if (!button || button.dataset.awaiting === 'true') return;
    button.dataset.awaiting = 'true';
    button.dataset.wasDisabled = button.disabled ? 'true' : 'false';
    button.disabled = true;
    button.classList.add('cursor-not-allowed', 'opacity-70');

    const label = button.dataset.awaitText || button.getAttribute('data-await-text');

    if (button.tagName === 'INPUT') {
        button.dataset.originalValue = button.value;
        button.value = label || 'Memproses...';
        return;
    }

    button.dataset.originalHtml = button.innerHTML;
    const spinner = '<span class="inline-flex items-center justify-center h-4 w-4 mr-2 animate-spin rounded-full border-2 border-current border-t-transparent" aria-hidden="true"></span>';
    button.innerHTML = label ? `${spinner}<span>${label}</span>` : `${spinner}${button.dataset.originalHtml}`;
}

function restoreActionButton(button) {
    if (!button || button.dataset.awaiting !== 'true') return;
    button.disabled = button.dataset.wasDisabled === 'true';
    button.classList.remove('cursor-not-allowed', 'opacity-70');

    if (button.tagName === 'INPUT') {
        if (button.dataset.originalValue !== undefined) {
            button.value = button.dataset.originalValue;
            delete button.dataset.originalValue;
        }
    } else if (button.dataset.originalHtml !== undefined) {
        button.innerHTML = button.dataset.originalHtml;
        delete button.dataset.originalHtml;
    }

    delete button.dataset.awaiting;
    delete button.dataset.wasDisabled;
}

window.awaitButtonAction = async function (button, action, awaitText = 'Memproses...') {
    if (!button || typeof action !== 'function') {
        return await action?.();
    }

    if (button.dataset.awaiting === 'true') {
        return;
    }

    button.dataset.awaitText = awaitText;
    setActionButtonProcessing(button);

    try {
        return await action();
    } finally {
        restoreActionButton(button);
    }
};

function disableFormSubmitButtons(form) {
    if (!(form instanceof HTMLFormElement)) return;

    const buttons = Array.from(form.querySelectorAll('button[type="submit"], input[type="submit"]'));
    if (buttons.length === 0) return;

    buttons.forEach(setActionButtonProcessing);
}

document.addEventListener('submit', function (event) {
    const form = event.target;
    if (!(form instanceof HTMLFormElement)) return;
    disableFormSubmitButtons(form);
});

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

