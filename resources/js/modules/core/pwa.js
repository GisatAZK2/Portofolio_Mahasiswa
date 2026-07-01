/**
 * modules/core/pwa.js
 * PWA install prompt, service worker registration.
 */

let deferredPrompt = null;
let installBtn = null;

function isAppInstalled() {
    return window.matchMedia('(display-mode: standalone)').matches
        || window.navigator.standalone === true;
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
            window.showSuccessAlert?.('Aplikasi sudah terpasang.');
            return;
        }
        if (!deferredPrompt) {
            window.showInfoAlert?.('Install prompt belum tersedia. Silakan gunakan icon install browser atau ikuti panduan Android/iOS di atas.');
            return;
        }
        deferredPrompt.prompt();
        window.showLoading?.('Installing...');
        const choiceResult = await deferredPrompt.userChoice;
        window.closeLoading?.();
        if (choiceResult.outcome === 'accepted') {
            window.showSuccessAlert?.('App installed successfully!');
        }
        deferredPrompt = null;
        updateInstallButton();
    });

    updateInstallButton();
}

export function initPWA() {
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
        window.showSuccessAlert?.('App installed successfully!');
    });

    // Register Service Worker
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => console.log('SW registered:', registration))
            .catch(error => console.log('SW registration failed:', error));
    }
}
