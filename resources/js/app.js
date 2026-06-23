import './bootstrap';
import Alpine from 'alpinejs';
import { showSuccessAlert, showErrorAlert, showLoading, closeLoading, showConfirm, showInfoAlert } from './alert.js';
import './alert.js'
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

window.checkSessionAlerts = function() {
    const flashEl = document.getElementById('flash-message');
    if (flashEl) {
        const success = flashEl.getAttribute('data-success');
        const error = flashEl.getAttribute('data-error');
        if (success) {
            if (typeof window.showSuccessAlert === 'function') {
                window.showSuccessAlert(success);
            }
        }
        if (error) {
            if (typeof window.showErrorAlert === 'function') {
                window.showErrorAlert(error);
            }
        }
        flashEl.remove();
    }
};

// Jalankan inisialisasi saat DOM siap dan setelah Turbo load
document.addEventListener('DOMContentLoaded', () => {
    initDarkMode();
    if (typeof window.checkSessionAlerts === 'function') window.checkSessionAlerts();
    if (typeof window.runPageInitializers === 'function') window.runPageInitializers();
});
document.addEventListener('turbo:load', () => {
    initDarkMode();
    if (typeof window.checkSessionAlerts === 'function') window.checkSessionAlerts();
    if (typeof window.runPageInitializers === 'function') window.runPageInitializers();
});

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

/* ==========================================
   AUTHENTICATION & PROFILE JS HELPERS
   ========================================== */

/**
 * Toggle visibility of password fields.
 * Supports both static IDs and dynamic element context.
 * @param {string|HTMLElement} target
 */
window.togglePasswordVisibility = function(target) {
    const input = typeof target === 'string' 
        ? document.getElementById(target) 
        : (target || document.getElementById('password'));
        
    if (!input) return;
    
    // Find associated icons/buttons
    const parent = input.parentElement;
    const eyeShowIcon = document.getElementById('eye-icon-show') || parent.querySelector('.eye-icon-show') || parent.querySelector('.eye-icon');
    const eyeHideIcon = document.getElementById('eye-icon-hide') || parent.querySelector('.eye-icon-hide') || parent.querySelector('.toggle-password-btn svg:last-child');

    if (input.type === 'password') {
        input.type = 'text';
        if (eyeShowIcon) eyeShowIcon.classList.add('hidden');
        if (eyeHideIcon) eyeHideIcon.classList.remove('hidden');
    } else {
        input.type = 'password';
        if (eyeShowIcon) eyeShowIcon.classList.remove('hidden');
        if (eyeHideIcon) eyeHideIcon.classList.add('hidden');
    }
};

// Show Alert Banner in Login Form
window.showAlert = function(message, type = 'error') {
    const alertDiv = document.getElementById('alertMessage');
    if (!alertDiv) return;
    
    alertDiv.classList.remove('hidden', 'bg-green-100', 'bg-red-100', 'bg-yellow-100', 'bg-blue-100', 
                              'text-green-800', 'text-red-800', 'text-yellow-800', 'text-blue-800');
    
    if (type === 'success') {
        alertDiv.classList.add('bg-green-100', 'text-green-800');
    } else if (type === 'warning') {
        alertDiv.classList.add('bg-yellow-100', 'text-yellow-800');
    } else if (type === 'info') {
        alertDiv.classList.add('bg-blue-100', 'text-blue-800');
    } else {
        alertDiv.classList.add('bg-red-100', 'text-red-800');
    }
    
    alertDiv.innerHTML = message;
    alertDiv.classList.remove('hidden');
    
    setTimeout(() => {
        alertDiv.classList.add('hidden');
    }, 5000);
};

// Generic Profile Photo Preview
document.addEventListener('change', function(e) {
    if (e.target && e.target.id === 'photo_profile') {
        const file = e.target.files[0];
        const preview = document.getElementById('profile-preview');
        const placeholder = document.getElementById('profile-placeholder') || document.getElementById('profile-preview-placeholder');
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                if (preview) {
                    preview.src = ev.target.result;
                    preview.classList.remove('hidden');
                }
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            if (preview) preview.classList.add('hidden');
            if (placeholder) placeholder.classList.remove('hidden');
            if (file) alert('Hanya gambar yang diperbolehkan!');
        }
    }
});

// Generic Background Image Preview
document.addEventListener('change', function(e) {
    if (e.target && e.target.id === 'background_image') {
        const file = e.target.files[0];
        const previewContainer = document.getElementById('backgroundPreviewContainer');
        const previewImage = document.getElementById('backgroundPreview');
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                if (previewImage) previewImage.src = ev.target.result;
                if (previewContainer) previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            if (previewContainer) previewContainer.classList.add('hidden');
            if (previewImage) previewImage.src = '#';
            if (file) alert('Hanya gambar yang diperbolehkan!');
        }
    }
});

// OTP Timer Implementation
let otpTimerInterval;
let otpTimeLeft = 300;
let otpCanResend = false;
let otpIsTimerRunning = true;

window.initOtpTimer = function(initialSeconds = 300, resendUrl) {
    otpTimeLeft = initialSeconds;
    otpIsTimerRunning = true;
    otpCanResend = false;
    
    clearInterval(otpTimerInterval);
    updateOtpTimerDisplay();
    
    otpTimerInterval = setInterval(() => {
        if (otpTimeLeft > 0 && otpIsTimerRunning) {
            otpTimeLeft--;
            updateOtpTimerDisplay();
        } else if (otpTimeLeft <= 0) {
            clearInterval(otpTimerInterval);
            otpIsTimerRunning = false;
            otpCanResend = true;
            
            const resendBtn = document.getElementById('resend-otp-btn');
            if (resendBtn) {
                resendBtn.disabled = false;
                resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Kode OTP Kadaluarsa',
                    text: 'Kode OTP sudah kadaluarsa. Silakan kirim ulang kode baru.',
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: 'Kirim Ulang'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.resendOtp(resendUrl);
                    }
                });
            }
        }
    }, 1000);
};

function formatOtpTime(seconds) {
    const mins = Math.floor(Math.max(0, seconds) / 60);
    const secs = Math.max(0, seconds) % 60;
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}

function updateOtpTimerDisplay() {
    const timerElement = document.getElementById('timer');
    const timerBar = document.getElementById('timer-bar');
    
    if (timerElement) {
        timerElement.textContent = formatOtpTime(otpTimeLeft);
    }
    
    if (timerBar) {
        const percentage = (Math.max(0, otpTimeLeft) / 300) * 100;
        timerBar.style.width = `${percentage}%`;
        if (percentage < 20) {
            timerBar.classList.remove('bg-blue-500');
            timerBar.classList.add('bg-red-500');
        } else if (percentage < 50) {
            timerBar.classList.remove('bg-blue-500');
            timerBar.classList.add('bg-orange-500');
        } else {
            timerBar.classList.remove('bg-red-500', 'bg-orange-500');
            timerBar.classList.add('bg-blue-500');
        }
    }
}

window.resendOtp = function(resendUrl) {
    if (!otpCanResend && otpTimeLeft > 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Tunggu Sebentar',
                text: `Silakan tunggu ${formatOtpTime(otpTimeLeft)} sebelum meminta kode baru.`,
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'OK'
            });
        }
        return;
    }
    
    const emailInput = document.getElementById('email-input');
    const email = emailInput ? emailInput.value : '';
    
    if (!email) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Email tidak ditemukan. Silakan ulangi proses dari awal.',
                confirmButtonColor: '#3b82f6'
            });
        }
        return;
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Mengirim Kode OTP...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
    }
    
    fetch(resendUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (typeof Swal !== 'undefined') Swal.close();
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Kode OTP baru telah dikirim ke email Anda.',
                    confirmButtonColor: '#3b82f6',
                    timer: 2000
                });
            }
            window.initOtpTimer(300, resendUrl);
            const otpInput = document.getElementById('otp');
            if (otpInput) otpInput.value = '';
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Gagal mengirim kode OTP. Silakan coba lagi.',
                    confirmButtonColor: '#3b82f6'
                });
            }
        }
    })
    .catch(error => {
        if (typeof Swal !== 'undefined') {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan. Silakan coba lagi.',
                confirmButtonColor: '#3b82f6'
            });
        }
    });
};

window.clearOtp = function() {
    const otpInput = document.getElementById('otp');
    if (otpInput) {
        otpInput.value = '';
        otpInput.focus();
    }
};

// WebAuthn 2FA Login Verification
window.verifyWithPasskey = async function() {
    const btn = document.getElementById('verifyPasskeyBtn');
    if (!btn) return;
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Memproses...</span>
    `;

    try {
        const optionsResponse = await fetch('/webauthn/2fa/options', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (!optionsResponse.ok) {
            const error = await optionsResponse.json();
            throw new Error(error.message || 'Gagal mendapatkan options');
        }

        const options = await optionsResponse.json();
        const assertion = await SimpleWebAuthnBrowser.startAuthentication(options);

        const verifyResponse = await fetch('/webauthn/2fa/verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ credential: assertion })
        });

        const result = await verifyResponse.json();

        if (result.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Verifikasi berhasil, mengalihkan...',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
            setTimeout(() => {
                window.location.href = result.redirect;
            }, 1500);
        } else {
            throw new Error(result.message || 'Verifikasi gagal');
        }
    } catch (error) {
        console.error('2FA error:', error);
        let errorMessage = 'Verifikasi gagal. Silakan coba lagi.';
        if (error.name === 'NotAllowedError') {
            errorMessage = 'Verifikasi dibatalkan. Silakan coba lagi.';
        } else if (error.message) {
            errorMessage = error.message;
        }
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Verifikasi Gagal',
                text: errorMessage,
                confirmButtonText: 'Coba Lagi'
            });
        }
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    }
};

// WebAuthn Passkeys Management Functions
function getPasskeyLocale() {
    const path = window.location.pathname;
    const match = path.match(/^\/(id|en)/);
    return match ? match[1] : '';
}

function buildPasskeyUrl(path) {
    const locale = getPasskeyLocale();
    return locale ? `/${locale}${path}` : path;
}

window.loadPasskeys = async function() {
    const container = document.getElementById('passkeysList');
    if (!container) return;
    container.innerHTML = `
        <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
            <div class="flex flex-col items-center gap-2">
                <svg class="w-8 h-8 animate-spin text-gray-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p>Memuat data...</p>
            </div>
        </div>
    `;
    
    try {
        const url = buildPasskeyUrl('/webauthn/passkeys');
        const response = await fetch(url, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });
        
        if (response.status === 401) {
            window.location.href = buildPasskeyUrl('/login');
            return;
        }
        
        const result = await response.json();
        if (result.success && result.data && result.data.length > 0) {
            renderPasskeysList(result.data);
        } else {
            renderEmptyPasskeysState();
        }
    } catch (error) {
        console.error('Error loading passkeys:', error);
        renderEmptyPasskeysState('Gagal memuat data. Silakan refresh halaman.');
    }
};

window.deletePasskey = async function(id, name) {
    if (typeof Swal === 'undefined') return;
    const result = await Swal.fire({
        title: 'Hapus Passkey?',
        text: `Apakah Anda yakin ingin menghapus passkey "${name}"? Tindakan ini tidak dapat dibatalkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });
    
    if (result.isConfirmed) {
        Swal.fire({
            title: 'Memproses...',
            text: 'Menghapus passkey...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        try {
            const locale = getPasskeyLocale();
            const url = `/${locale}/webauthn/passkeys?id=${id}`;
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            });
            
            const data = await response.json();
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Passkey berhasil dihapus', timer: 2000, showConfirmButton: false });
                window.loadPasskeys();
            } else {
                throw new Error(data.message || 'Gagal menghapus passkey');
            }
        } catch (error) {
            console.error('Delete error:', error);
            Swal.fire({ icon: 'error', title: 'Error!', text: error.message || 'Terjadi kesalahan saat menghapus passkey', confirmButtonColor: '#3085d6' });
        }
    }
};

window.registerPasskey = async function() {
    const nameInput = document.getElementById('passkeyName');
    if (!nameInput) return;
    const name = nameInput.value.trim();
    
    if (!name) {
        if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Peringatan', text: 'Masukkan nama perangkat terlebih dahulu', confirmButtonColor: '#3085d6' });
        nameInput.focus();
        return;
    }
    
    if (!window.PublicKeyCredential) {
        if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Tidak Didukung', text: 'Browser Anda tidak mendukung WebAuthn.', confirmButtonColor: '#3085d6' });
        return;
    }
    
    const btn = document.getElementById('addPasskeyBtn');
    if (!btn) return;
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="inline w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Memproses...</span>
    `;
    
    try {
        const optionsUrl = buildPasskeyUrl('/webauthn/register/options');
        const optionsResponse = await fetch(optionsUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });
        
        if (!optionsResponse.ok) throw new Error('Gagal mendapatkan konfigurasi autentikasi');
        const options = await optionsResponse.json();
        
        let credential;
        try {
            credential = await SimpleWebAuthnBrowser.startRegistration(options);
        } catch (err) {
            if (err.name === 'NotAllowedError') throw new Error('Proses autentikasi dibatalkan oleh pengguna');
            else if (err.name === 'NotSupportedError') throw new Error('Perangkat Anda tidak mendukung metode autentikasi yang diminta');
            else throw new Error('Gagal melakukan autentikasi: ' + (err.message || err));
        }
        
        const verifyUrl = buildPasskeyUrl('/webauthn/register/verify');
        const verifyResponse = await fetch(verifyUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ credential, name }),
            credentials: 'same-origin'
        });
        
        const result = await verifyResponse.json();
        if (result.success) {
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Passkey berhasil ditambahkan!', timer: 2000, showConfirmButton: false });
            nameInput.value = '';
            window.loadPasskeys();
            nameInput.focus();
        } else {
            throw new Error(result.message || 'Gagal memverifikasi passkey');
        }
    } catch (error) {
        console.error('Registration error:', error);
        if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Gagal', text: error.message || 'Gagal menambahkan passkey.', confirmButtonColor: '#3085d6' });
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    }
};

function renderPasskeysList(passkeys) {
    const container = document.getElementById('passkeysList');
    if (!container) return;
    container.innerHTML = '';
    
    passkeys.forEach(passkey => {
        const div = document.createElement('div');
        div.className = 'group flex justify-between items-center p-5 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-150';
        div.innerHTML = `
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 transition-colors">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zm0 6h14M5 9v10a2 2 0 002 2h10a2 2 0 002-2V9H5z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white text-sm">${escapePasskeyHtml(passkey.name)}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Ditambahkan ${passkey.created_at_humans}
                        </p>
                    </div>
                </div>
            </div>
            <button onclick="deletePasskey(${passkey.id}, '${escapePasskeyHtml(passkey.name)}')" 
                    class="p-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        `;
        container.appendChild(div);
    });
}

function renderEmptyPasskeysState(message = 'Belum ada passkey terdaftar') {
    const container = document.getElementById('passkeysList');
    if (!container) return;
    container.innerHTML = `
        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
            <div class="flex flex-col items-center gap-3">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                </svg>
                <p class="text-sm">${message}</p>
                <p class="text-xs">Tambahkan passkey baru menggunakan formulir di bawah</p>
            </div>
        </div>
    `;
}

function escapePasskeyHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/* ==========================================
   PAGE-SPECIFIC INITIALIZERS
   ========================================== */

window.checkSessionAlerts = function() {
    const body = document.body;
    const sessionData = document.getElementById('session-alert-data');
    if (!body && !sessionData) return;
    
    const success = sessionData ? (sessionData.dataset.sessionSuccess || sessionData.getAttribute('data-session-success')) : (body ? (body.dataset.sessionSuccess || body.getAttribute('data-session-success')) : null);
    const error = sessionData ? (sessionData.dataset.sessionError || sessionData.getAttribute('data-session-error')) : (body ? (body.dataset.sessionError || body.getAttribute('data-session-error')) : null);
    const warning = sessionData ? (sessionData.dataset.sessionWarning || sessionData.getAttribute('data-session-warning')) : (body ? (body.dataset.sessionWarning || body.getAttribute('data-session-warning')) : null);
    const errorsFirst = sessionData ? (sessionData.dataset.errorsFirst || sessionData.getAttribute('data-errors-first')) : (body ? (body.dataset.errorsFirst || body.getAttribute('data-errors-first')) : null);
    const errorsAll = sessionData ? (sessionData.dataset.errorsAll || sessionData.getAttribute('data-errors-all')) : null;
    const isBlocked = sessionData ? (sessionData.dataset.isBlocked || sessionData.getAttribute('data-is-blocked')) : null;
    
    if (success) {
        if (typeof Swal !== 'undefined' && window.showSuccessAlert) {
            window.showSuccessAlert(success);
        } else if (window.showAlert) {
            window.showAlert(success, 'success');
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: success, confirmButtonColor: '#3b82f6' });
        }
    }
    if (error) {
        if (typeof Swal !== 'undefined' && window.showErrorAlert) {
            window.showErrorAlert(error);
        } else if (window.showAlert) {
            window.showAlert(error, 'error');
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'error', title: 'Gagal!', text: error, confirmButtonColor: '#3b82f6' });
        }
    }
    if (warning) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: warning,
                confirmButtonColor: '#2563eb'
            });
        }
    }
    if (isBlocked === 'true' && typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: 'Pendaftaran Dibatasi',
            text: 'Anda telah melebihi batas percobaan pendaftaran. Silakan coba lagi besok.',
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'Mengerti'
        });
    }
    if (errorsAll) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: errorsAll,
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'OK'
            });
        }
    } else if (errorsFirst) {
        if (errorsFirst === 'PENGAJUAN_DIPROSES') {
            window.showAlert('Pengajuan akun Anda sedang diproses. Mohon tunggu konfirmasi dari admin.', 'warning');
        } else if (errorsFirst === 'PENGAJUAN_DITOLAK') {
            window.showAlert('Pengajuan akun Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.', 'error');
        } else if (errorsFirst === 'AKUN_DIBLOKIR') {
            window.showAlert('Akun Anda diblokir. Silakan hubungi admin untuk informasi lebih lanjut.', 'error');
        } else {
            if (typeof Swal !== 'undefined' && window.showErrorAlert) {
                window.showErrorAlert(errorsFirst);
            } else if (window.showAlert) {
                window.showAlert(errorsFirst, 'error');
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Validasi Gagal', text: errorsFirst, confirmButtonColor: '#3b82f6' });
            }
        }
    }
};

window.runPageInitializers = function() {
    // Passkey Management Page
    if (document.getElementById('passkeyName') && document.getElementById('addPasskeyBtn')) {
        initPasskeyManagement();
    }
    // Verify Passkey Page
    if (document.getElementById('verifyPasskeyBtn') && document.getElementById('cancelBtn') && window.location.pathname.includes('verify')) {
        initVerifyPasskey();
    }
    // Verify OTP Page
    if (document.getElementById('otpForm') && document.getElementById('otp')) {
        initVerifyOtp();
    }
    // Forgot Password Page
    if (document.getElementById('email') && document.querySelector('form[action*="sendOtp"]')) {
        initForgotPassword();
    }
    // Reset Password Page
    if (document.getElementById('password') && document.getElementById('password-confirm')) {
        initResetPassword();
    }
    // Register Page (Photo Cropper)
    if (document.getElementById('photo_profile') && document.getElementById('cropper-modal')) {
        initRegisterPage();
    }
    // Profile Page
    if (document.getElementById('form-profile')) {
        initProfilePage();
    }
    // Postingan Detail Page
    const detailContainer = document.getElementById('postingan-detail-container');
    if (detailContainer) {
        window.initPostinganDetailPage(detailContainer);
    }
    // Project User Page
    const projectUserContainer = document.getElementById('project-user-container');
    if (projectUserContainer) {
        if (typeof window.showPageInfo === 'function') {
            window.showPageInfo("popup.project_saya");
        }
    }
    // All Projects Page
    const allProjectsContainer = document.getElementById('all-projects-container');
    if (allProjectsContainer) {
        if (typeof window.showPageInfo === 'function') {
            window.showPageInfo("popup.semua_project");
        }
    }
    // Project Detail Page
    const projectDetailContainer = document.getElementById('project-detail-container');
    if (projectDetailContainer) {
        window.initProjectDetailPage(projectDetailContainer);
    }
    // Project Create Page
    const projectCreateContainer = document.getElementById('project-create-container');
    if (projectCreateContainer) {
        window.initProjectCreatePage(projectCreateContainer);
    }
    // Project Edit Page
    const projectEditContainer = document.getElementById('project-edit-container');
    if (projectEditContainer) {
        window.initProjectEditPage(projectEditContainer);
    }
    // Sertifikat List Page
    const sertifikatListContainer = document.getElementById('sertifikat-list-container');
    if (sertifikatListContainer) {
        window.initSertifikatListPage(sertifikatListContainer);
    }
    // Sertifikat User Page
    const sertifikatUserContainer = document.getElementById('sertifikat-user-container');
    if (sertifikatUserContainer) {
        window.initSertifikatUserPage(sertifikatUserContainer);
    }
    // Sertifikat Create Page
    const sertifikatCreateContainer = document.getElementById('sertifikat-create-container');
    if (sertifikatCreateContainer) {
        window.initSertifikatCreatePage(sertifikatCreateContainer);
    }
    // Sertifikat Edit Page
    const sertifikatEditContainer = document.getElementById('sertifikat-edit-container');
    if (sertifikatEditContainer) {
        window.initSertifikatEditPage(sertifikatEditContainer);
    }
};

function initPasskeyManagement() {
    const nameInput = document.getElementById('passkeyName');
    const addBtn = document.getElementById('addPasskeyBtn');
    if (!nameInput || !addBtn) return;
    
    const validate = () => {
        addBtn.disabled = nameInput.value.trim() === '';
    };
    
    nameInput.addEventListener('input', validate);
    validate(); // initial state
    
    addBtn.addEventListener('click', window.registerPasskey);
    
    nameInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !addBtn.disabled) {
            e.preventDefault();
            window.registerPasskey();
        }
    });
}

function initVerifyPasskey() {
    const verifyBtn = document.getElementById('verifyPasskeyBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    if (!verifyBtn || !cancelBtn) return;
    
    verifyBtn.addEventListener('click', window.verifyWithPasskey);
    
    cancelBtn.addEventListener('click', async () => {
        if (typeof Swal === 'undefined') return;
        const result = await Swal.fire({
            title: 'Batalkan Login?',
            text: 'Anda akan kembali ke halaman login',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak'
        });
        if (result.isConfirmed) {
            window.location.href = '/login';
        }
    });
    
    // Auto trigger verification when page loads
    setTimeout(() => {
        window.verifyWithPasskey();
    }, 500);
}

function initVerifyOtp() {
    const form = document.getElementById('otpForm');
    const otpInput = document.getElementById('otp');
    const clearBtn = document.getElementById('clear-otp-btn');
    const resendBtn = document.getElementById('resend-otp-btn');
    const cancelButton = document.querySelector('.cancel-btn');
    
    if (!form || !otpInput) return;
    
    const resendUrl = resendBtn ? resendBtn.getAttribute('data-resend-url') : '';
    window.initOtpTimer(300, resendUrl);
    
    if (clearBtn) {
        clearBtn.addEventListener('click', window.clearOtp);
    }
    
    if (resendBtn) {
        resendBtn.addEventListener('click', () => {
            window.resendOtp(resendUrl);
        });
    }
    
    otpInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 6) {
            this.value = this.value.slice(0, 6);
        }
        if (clearBtn) {
            if (this.value.length > 0) {
                clearBtn.classList.remove('opacity-0', 'pointer-events-none');
                clearBtn.classList.add('opacity-100', 'pointer-events-auto');
            } else {
                clearBtn.classList.remove('opacity-100', 'pointer-events-auto');
                clearBtn.classList.add('opacity-0', 'pointer-events-none');
            }
        }
    });
    
    otpInput.addEventListener('paste', function(e) {
        e.preventDefault();
        const pastedText = (e.clipboardData || window.clipboardData).getData('text');
        const numbers = pastedText.replace(/[^0-9]/g, '').slice(0, 6);
        this.value = numbers;
        const event = new Event('input', { bubbles: true });
        this.dispatchEvent(event);
    });
    
    form.addEventListener('submit', function(e) {
        const submitButton = this.querySelector('button[type="submit"]');
        const otp = otpInput.value;
        if (!otp) {
            e.preventDefault();
            window.showErrorAlert('Silakan masukkan kode OTP.');
            return false;
        }
        if (otp.length !== 6) {
            e.preventDefault();
            window.showErrorAlert('Kode OTP harus 6 digit.');
            return false;
        }
        if (!/^\d+$/.test(otp)) {
            e.preventDefault();
            window.showErrorAlert('Kode OTP hanya boleh berisi angka.');
            return false;
        }
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = 'Memverifikasi...';
            window.showLoading('Memverifikasi kode OTP...');
        }
    });
    
    if (cancelButton) {
        cancelButton.addEventListener('click', function(e) {
            if (otpInput.value.trim() !== '') {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin ingin kembali?',
                    text: 'Kode OTP yang sudah dimasukkan akan hilang.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3b82f6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Kembali',
                    cancelButtonText: 'Tetap di Sini'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = cancelButton.getAttribute('href') || '/login';
                    }
                });
            }
        });
    }
    
    otpInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && this.value.length === 6) {
            e.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
    });
}

function initForgotPassword() {
    const cancelButton = document.querySelector('a[href*="login"]');
    if (cancelButton) {
        cancelButton.addEventListener('click', function(e) {
            const emailInput = document.getElementById('email');
            if (emailInput && emailInput.value.trim() !== '') {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin ingin kembali?',
                    text: 'Data email yang sudah diisi akan hilang.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#f97316',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Kembali',
                    cancelButtonText: 'Tetap di Sini'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = cancelButton.getAttribute('href') || '/login';
                    }
                });
            }
        });
    }
}

function initResetPassword() {
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');
    
    if (passwordInput && strengthBar && strengthText) {
        passwordInput.addEventListener('input', function() {
            const result = checkPasswordStrength(this.value);
            strengthBar.className = `h-full transition-all duration-300 rounded-full ${result.color}`;
            strengthBar.style.width = `${result.width}%`;
            strengthText.textContent = result.text;
            strengthText.className = `font-medium ${
                result.level === 'weak' ? 'text-red-600' : 
                result.level === 'medium' ? 'text-orange-600' : 
                result.level === 'strong' ? 'text-green-600' : ''
            }`;
        });
    }
    
    const passwordField = document.getElementById('password');
    const confirmField = document.getElementById('password-confirm');
    
    if (passwordField && confirmField) {
        const createMatchIndicator = () => {
            let indicator = document.getElementById('password-match-indicator');
            if (!indicator) {
                indicator = document.createElement('div');
                indicator.id = 'password-match-indicator';
                indicator.className = 'mt-2 text-xs';
                confirmField.parentElement.parentElement.appendChild(indicator);
            }
            return indicator;
        };
        
        const checkMatch = () => {
            const indicator = createMatchIndicator();
            if (confirmField.value === '') {
                indicator.innerHTML = '';
                indicator.className = 'mt-2 text-xs';
            } else if (passwordField.value === confirmField.value) {
                indicator.innerHTML = '<span class="text-green-600 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Password cocok</span>';
                indicator.className = 'mt-2 text-xs';
            } else {
                indicator.innerHTML = '<span class="text-red-600 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Password tidak cocok</span>';
                indicator.className = 'mt-2 text-xs';
            }
        };
        
        passwordField.addEventListener('input', checkMatch);
        confirmField.addEventListener('input', checkMatch);
    }
    
    const cancelButton = document.querySelector('a[href*="login"]');
    if (cancelButton) {
        cancelButton.addEventListener('click', function(e) {
            const passwordInput = document.getElementById('password');
            if (passwordInput && passwordInput.value.trim() !== '') {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin ingin kembali?',
                    text: 'Password yang sudah diisi akan hilang.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Kembali',
                    cancelButtonText: 'Tetap di Sini'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = cancelButton.getAttribute('href') || '/login';
                    }
                });
            }
        });
    }
    
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password-confirm').value;
            
            if (!password) {
                e.preventDefault();
                window.showErrorAlert('Silakan masukkan password baru Anda.');
                return false;
            }
            if (password.length < 8) {
                e.preventDefault();
                window.showErrorAlert('Password minimal 8 karakter.');
                return false;
            }
            if (password !== confirmPassword) {
                e.preventDefault();
                window.showErrorAlert('Konfirmasi password tidak cocok. Silakan periksa kembali.');
                return false;
            }
            
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = 'Memproses...';
                window.showLoading('Mereset password...');
            }
        });
    }
}

function checkPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    
    let strengthLevel = '';
    let strengthColor = '';
    let strengthText = '';
    
    if (password.length === 0) {
        strengthText = '-';
    } else if (strength <= 2) {
        strengthLevel = 'weak';
        strengthColor = 'bg-red-500';
        strengthText = 'Lemah';
    } else if (strength === 3 || strength === 4) {
        strengthLevel = 'medium';
        strengthColor = 'bg-orange-500';
        strengthText = 'Sedang';
    } else {
        strengthLevel = 'strong';
        strengthColor = 'bg-green-500';
        strengthText = 'Kuat';
    }
    
    return { level: strengthLevel, color: strengthColor, text: strengthText, width: (strength / 5) * 100 };
}

/* =====================================================================
   REGISTER PAGE FUNCTIONS
   ===================================================================== */
let registerCropperInstance = null;
let registerCropperObjectUrl = null;

function initRegisterPage() {
    const photoInput = document.getElementById('photo_profile');
    if (!photoInput) return;

    photoInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            openRegisterCropperModal(file);
        } else {
            const preview = document.getElementById('profile-preview');
            const placeholder = document.getElementById('profile-placeholder');
            if (preview) preview.classList.add('hidden');
            if (placeholder) placeholder.classList.remove('hidden');
            if (file) alert('Hanya gambar yang diperbolehkan!');
        }
    });
}

window.openRegisterCropperModal = function(file) {
    const modal = document.getElementById('cropper-modal');
    const img = document.getElementById('cropper-image');
    if (registerCropperObjectUrl) URL.revokeObjectURL(registerCropperObjectUrl);
    registerCropperObjectUrl = URL.createObjectURL(file);
    img.src = registerCropperObjectUrl;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    img.onload = function () {
        if (registerCropperInstance) registerCropperInstance.destroy();
        registerCropperInstance = new Cropper(img, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1,
            responsive: true,
            background: false,
        });
        document.getElementById('cropper-modal-content')?.classList.add('scale-100', 'opacity-100');
    };

    const zoomRange = document.getElementById('cropper-zoom-range');
    if (zoomRange) {
        zoomRange.value = 1;
        zoomRange.oninput = function (e) {
            if (registerCropperInstance) {
                registerCropperInstance.zoomTo(parseFloat(e.target.value));
            }
        };
    }
};

window.closeRegisterCropperModal = function() {
    const modal = document.getElementById('cropper-modal');
    if (modal) modal.classList.add('hidden');
    document.body.style.overflow = '';
    if (registerCropperInstance) {
        registerCropperInstance.destroy();
        registerCropperInstance = null;
    }
    if (registerCropperObjectUrl) {
        URL.revokeObjectURL(registerCropperObjectUrl);
        registerCropperObjectUrl = null;
    }
};

window.cancelCropper = function() {
    window.closeRegisterCropperModal();
};

window.confirmCrop = function() {
    if (!registerCropperInstance) return window.closeRegisterCropperModal();
    registerCropperInstance.getCroppedCanvas({ width: 800, height: 800, imageSmoothingQuality: 'high' }).toBlob(function (blob) {
        if (!blob) return alert('Gagal memproses gambar');

        const preview = document.getElementById('profile-preview');
        const placeholder = document.getElementById('profile-placeholder');
        const url = URL.createObjectURL(blob);
        if (preview) {
            preview.src = url;
            preview.classList.remove('hidden');
        }
        if (placeholder) placeholder.classList.add('hidden');

        const croppedFile = new File([blob], 'photo_profile.jpg', { type: blob.type });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(croppedFile);
        const input = document.getElementById('photo_profile');
        if (input) input.files = dataTransfer.files;

        window.closeRegisterCropperModal();
    }, 'image/jpeg', 0.9);
};


/* =====================================================================
   PROFILE PAGE FUNCTIONS
   ===================================================================== */
let profileCropperInstance = null;
let profileCropperFile = null;
let profileCropperObjectUrl = null;

function initProfilePage() {
    // Save button trigger on file inputs
    const photoProfileInput = document.getElementById('photo_profile_input');
    if (photoProfileInput) {
        photoProfileInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            window.openProfileCropperModal(file);
        });
    }

    const bgInput = document.getElementById('background_input');
    if (bgInput) {
        bgInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (ev) {
                const coverDiv = document.querySelector('#actual-content .relative.h-48');
                if (coverDiv) {
                    coverDiv.style.backgroundImage = `url('${ev.target.result}')`;
                    coverDiv.classList.add('bg-cover', 'bg-center');
                }
            };
            reader.readAsDataURL(file);
            document.getElementById('save-button-container')?.classList.remove('hidden');
        });
    }

    // Video preview setup
    window.updatePreview();

    // Show actual content and hide skeleton
    const skeleton = document.getElementById('skeleton-loading');
    const actualContent = document.getElementById('actual-content');
    if (skeleton && actualContent) {
        skeleton.classList.add('hidden');
        actualContent.style.display = 'block';
    }
}

window.toggleEdit = function(field) {
    const displayEl = document.getElementById(field + '-display');
    const inputEl   = document.getElementById(field + '-input');
    const customEl  = document.getElementById(field + '-custom-input');
    const saveBtn   = document.getElementById('save-button-container');
    if (!displayEl || !inputEl) return;
    displayEl.classList.add('hidden');
    inputEl.classList.remove('hidden');
    if (customEl) {
        customEl.classList.remove('hidden');
    }
    const container = document.getElementById(field + '-container');
    if (container) container.classList.add('hidden');
    inputEl.focus();
    if (saveBtn) saveBtn.classList.remove('hidden');
};

window.openProfileCropperModal = function(file) {
    const modal = document.getElementById('cropper-modal');
    const image = document.getElementById('cropper-image');
    const zoomRange = document.getElementById('cropper-zoom-range');

    if (!modal || !image || !zoomRange) return;
    if (profileCropperInstance) {
        profileCropperInstance.destroy();
        profileCropperInstance = null;
    }

    profileCropperFile = file;
    if (profileCropperObjectUrl) {
        URL.revokeObjectURL(profileCropperObjectUrl);
        profileCropperObjectUrl = null;
    }

    profileCropperObjectUrl = URL.createObjectURL(file);
    zoomRange.value = '1';

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    image.onload = function () {
        profileCropperInstance = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 1,
            movable: true,
            zoomable: true,
            responsive: true,
            autoCropArea: 1,
            background: false,
            preview: '#cropper-preview-container',
        });
    };
    image.src = profileCropperObjectUrl;
};

window.closeCropperModal = function() {
    const modal = document.getElementById('cropper-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    document.body.style.overflow = '';
    if (profileCropperInstance) {
        profileCropperInstance.destroy();
        profileCropperInstance = null;
    }
    if (profileCropperObjectUrl) {
        URL.revokeObjectURL(profileCropperObjectUrl);
        profileCropperObjectUrl = null;
    }
};

window.cancelProfileCropper = function() {
    const input = document.getElementById('photo_profile_input');
    if (input) {
        input.value = '';
    }
    window.closeCropperModal();
};

window.cropperZoom = function(amount) {
    if (!profileCropperInstance) return;
    profileCropperInstance.zoom(amount);
    const zoomRange = document.getElementById('cropper-zoom-range');
    if (zoomRange) {
        const current = parseFloat(zoomRange.value) + amount;
        zoomRange.value = Math.min(3, Math.max(0.5, current));
    }
};

// Listen to range zoom changes
document.getElementById('cropper-zoom-range')?.addEventListener('input', function (e) {
    if (profileCropperInstance) {
        profileCropperInstance.zoomTo(parseFloat(e.target.value));
    }
});

window.confirmProfileCrop = function() {
    if (!profileCropperInstance || !profileCropperFile) return;
    const outputType = ['image/png', 'image/jpeg'].includes(profileCropperFile.type) ? profileCropperFile.type : 'image/jpeg';
    const outputExt = outputType === 'image/png' ? 'png' : 'jpg';

    profileCropperInstance.getCroppedCanvas({ width: 512, height: 512, imageSmoothingQuality: 'high' }).toBlob(function (blob) {
        if (!blob) return;
        const fileName = profileCropperFile.name.replace(/\.[^/.]+$/, `.${outputExt}`);
        const croppedFile = new File([blob], fileName, { type: outputType });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(croppedFile);
        const input = document.getElementById('photo_profile_input');
        if (input) {
            input.files = dataTransfer.files;
        }

        const preview = document.getElementById('profile-preview');
        const placeholder = document.getElementById('profile-preview-placeholder');
        const objectUrl = URL.createObjectURL(blob);
        if (preview) {
            preview.src = objectUrl;
        } else if (placeholder) {
            const newImg = document.createElement('img');
            newImg.id = 'profile-preview';
            newImg.className = 'w-full h-full object-cover';
            newImg.src = objectUrl;
            placeholder.replaceWith(newImg);
        }

        document.getElementById('save-button-container')?.classList.remove('hidden');
        window.closeCropperModal();
    }, outputType, 0.92);
};

window.validateTanggalLahir = function(input) {
    const selectedDate = new Date(input.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    if (selectedDate > today) {
        alert('Tanggal lahir tidak boleh lebih dari hari ini!');
        input.value = '';
    } else if (selectedDate.getFullYear() < 1900) {
        alert('Tahun lahir minimal 1900!');
        input.value = '';
    } else {
        const displayEl = document.getElementById('tanggal_lahir-display');
        if (displayEl && input.value) {
            const date = new Date(input.value);
            displayEl.textContent = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }
    }
};

window.updatePreview = function() {
    const inputEl = document.getElementById('video-input');
    if (!inputEl) return;
    const input   = inputEl.value;
    const preview = document.getElementById('videoPreview');
    const iframe  = document.getElementById('previewFrame');
    if (!preview || !iframe) return;
    
    const embedUrl = convertToEmbed(input);
    if (embedUrl) { iframe.src = embedUrl; preview.classList.remove('hidden'); }
    else { preview.classList.add('hidden'); iframe.src = ''; }
};

function convertToEmbed(url) {
    if (!url) return '';
    if (url.includes('watch?v=')) return url.replace('watch?v=', 'embed/');
    if (url.includes('youtu.be/')) return url.replace('youtu.be/', 'youtube.com/embed/');
    return url;
}

window.playVideo = function(element, embedUrl) {
    const iframe = document.createElement('iframe');
    iframe.className = 'absolute inset-0 w-full h-full';
    iframe.src = embedUrl + '?autoplay=1&rel=0';
    iframe.setAttribute('frameborder', '0');
    iframe.allowFullscreen = true;
    element.innerHTML = '';
    element.appendChild(iframe);
};

window.copyLink = function(url) {
    navigator.clipboard.writeText(url).then(() => {
        const n = document.createElement('div');
        n.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        n.innerHTML = '<div class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>Link berhasil disalin!</span></div>';
        document.body.appendChild(n);
        setTimeout(() => n.remove(), 3000);
    });
};

// Modal Toggles (Education)
window.openPendidikanModal = function() {
    const modal   = document.getElementById('modal-pendidikan');
    const content = document.getElementById('modal-pendidikan-content');
    if (!modal || !content) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    });
};

window.closePendidikanModal = function() {
    const modal   = document.getElementById('modal-pendidikan');
    const content = document.getElementById('modal-pendidikan-content');
    if (!modal || !content) return;
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
    document.getElementById('sekolah-dropdown')?.classList.add('hidden');
};

// Modal Toggles (Experience)
window.openPengalamanModal = function() {
    const modal   = document.getElementById('modal-pengalaman');
    const content = document.getElementById('modal-pengalaman-content');
    if (!modal || !content) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    });
};

window.closePengalamanModal = function() {
    const modal   = document.getElementById('modal-pengalaman');
    const content = document.getElementById('modal-pengalaman-content');
    if (!modal || !content) return;
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
};

// Handle AJAX Pengalaman Submit
window.handlePengalamanSubmit = async function(event) {
    event.preventDefault();
    const form      = event.target;
    const submitBtn = document.getElementById('btn-submit-pengalaman');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const locale    = document.querySelector('html').getAttribute('lang') || 'id';

    const mulaiVal = document.getElementById('form-tahun_mulai')?.value;
    const akhirVal = document.getElementById('form-tahun_akhir')?.value;
    const masihChecked = document.getElementById('form-masih_bekerja')?.checked;

    if (!masihChecked && mulaiVal && akhirVal && akhirVal < mulaiVal) {
        const errEl = document.getElementById('add-pkj-akhir-error');
        if (errEl) errEl.classList.remove('hidden');
        if (window.showErrorAlert) window.showErrorAlert('Tahun selesai tidak boleh sebelum tahun mulai.');
        return;
    }

    const formData = new FormData(form);
    formData.set('masih_bekerja', masihChecked ? '1' : '0');

    try {
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span>Menyimpan...';
        }

        const response = await fetch(`/${locale}/pengalaman-kerja/store`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: formData
        });
        const result = await response.json();

        if (response.ok && result.success) {
            window.closePengalamanModal();
            if (window.showSuccessAlert) window.showSuccessAlert('Pengalaman kerja berhasil ditambahkan!');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            const errMsg = result.errors ? Object.values(result.errors).flat().join(', ') : (result.message || 'Gagal menyimpan pengalaman kerja');
            if (window.showErrorAlert) window.showErrorAlert(errMsg);
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Simpan Pengalaman';
            }
        }
    } catch (error) {
        console.error('Error:', error);
        if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan: ' + error.message);
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Simpan Pengalaman';
        }
    }
};

// Search Sekolah Logic
let searchSekolahTimer = null;
window.searchSekolah = function(query) {
    clearTimeout(searchSekolahTimer);
    const dropdown   = document.getElementById('sekolah-dropdown');
    const list       = document.getElementById('sekolah-dropdown-list');
    const loadingIcon = document.getElementById('sekolah-loading-icon');
    const searchIcon  = document.getElementById('sekolah-search-icon');
    const locale      = document.querySelector('html').getAttribute('lang') || 'id';

    if (query.length < 2) { if (dropdown) dropdown.classList.add('hidden'); return; }

    if (loadingIcon) loadingIcon.classList.remove('hidden');
    if (searchIcon) searchIcon.classList.add('hidden');

    searchSekolahTimer = setTimeout(async () => {
        try {
            const response = await fetch(`/${locale}/sekolah/search?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            if (list) list.innerHTML = '';
            
            if (data.length === 0) {
                if (list) list.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center">Tidak ada hasil. Ketik nama secara manual.</div>';
            } else {
                data.forEach(sekolah => {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'w-full text-left px-4 py-3 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition';
                    item.innerHTML = `<div class="font-medium text-sm text-gray-900 dark:text-white">${sekolah.nama}</div><div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">${sekolah.jenjang ? sekolah.jenjang + ' · ' : ''}${sekolah.kabupaten || sekolah.kota || ''}${sekolah.provinsi ? ', ' + sekolah.provinsi : ''}</div>`;
                    item.addEventListener('click', () => {
                        document.getElementById('sekolah-search-input').value = sekolah.nama;
                        if (dropdown) dropdown.classList.add('hidden');
                    });
                    if (list) list.appendChild(item);
                });
            }
            if (dropdown) dropdown.classList.remove('hidden');
        } catch (err) {
            console.error('Search sekolah error:', err);
        } finally {
            if (loadingIcon) loadingIcon.classList.add('hidden');
            if (searchIcon) searchIcon.classList.remove('hidden');
        }
    }, 400);
};

// Sertifikat Upload Helpers
window.handleSertifikatFile = function(input) {
    const file = input.files[0];
    if (!file) return;
    document.getElementById('sertifikat-upload-placeholder')?.classList.add('hidden');
    const preview = document.getElementById('sertifikat-file-preview');
    if (preview) preview.classList.remove('hidden');
    const nameEl = document.getElementById('sertifikat-file-name');
    if (nameEl) nameEl.textContent = file.name;
    const sizeEl = document.getElementById('sertifikat-file-size');
    if (sizeEl) sizeEl.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
};

window.clearSertifikatFile = function(event) {
    if (event) event.stopPropagation();
    const input = document.getElementById('sertifikat-file-input');
    if (input) input.value = '';
    document.getElementById('sertifikat-upload-placeholder')?.classList.remove('hidden');
    document.getElementById('sertifikat-file-preview')?.classList.add('hidden');
};

// Modal Detail/Edit Pendidikan
window.openDetailPendidikan = async function(id) {
    window.updateUrlParam('pend', id);
    const modal   = document.getElementById('modal-detail-pendidikan');
    const content = document.getElementById('modal-detail-pendidikan-content');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const locale    = document.querySelector('html').getAttribute('lang') || 'id';
    const TODAY = new Date().toISOString().split('T')[0];

    if (!modal || !content) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    });

    document.getElementById('pend-view-mode')?.classList.add('hidden');
    document.getElementById('pend-view-actions')?.classList.add('hidden');
    document.getElementById('pend-edit-mode')?.classList.add('hidden');
    document.getElementById('pend-edit-actions')?.classList.add('hidden');
    document.getElementById('pend-skeleton')?.classList.remove('hidden');

    try {
        const res  = await fetch(`/${locale}/pendidikan/detail?id=${id}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        });
        const json = await res.json();
        document.getElementById('pend-skeleton')?.classList.add('hidden');
        if (!json.success) return;
        const d = json.data;

        const idInput = document.getElementById('pend-edit-id');
        if (idInput) idInput.value = d.id;

        const jenjangIcons = {
            'S1':'🎓','S2':'🎓','S3':'🎓',
            'D1':'📚','D2':'📚','D3':'📚','D4':'📚',
            'SMA/SMK':'🏫','SMP':'🏫','SD':'🏫',
            'Kursus/Pelatihan':'📖'
        };
        const iconEl = document.getElementById('pend-view-icon');
        if (iconEl) iconEl.textContent   = jenjangIcons[d.jenjang] || '🏛️';
        const namaEl = document.getElementById('pend-view-nama');
        if (namaEl) namaEl.textContent    = d.nama_sekolah || '-';
        const jenjangEl = document.getElementById('pend-view-jenjang');
        if (jenjangEl) jenjangEl.textContent = d.jenjang || '';
        const jurusanEl = document.getElementById('pend-view-jurusan');
        if (jurusanEl) jurusanEl.textContent = d.jurusan_sek || '-';
        const lulusText = d.masih_kuliah ? 'Sekarang' : (d.tahun_lulus || 'Belum selesai');
        const periodEl = document.getElementById('pend-view-periode');
        if (periodEl) periodEl.textContent = `${d.tahun_masuk || '-'} — ${lulusText}`;

        const masukVal = window.toYMD(d.tahun_masuk);
        const lulusVal = window.toYMD(d.tahun_lulus);

        const editNama = document.getElementById('pend-edit-nama_sekolah');
        if (editNama) editNama.value = d.nama_sekolah || '';
        const editJenjang = document.getElementById('pend-edit-jenjang');
        if (editJenjang) editJenjang.value = d.jenjang || '';
        const editJurusan = document.getElementById('pend-edit-jurusan_sek');
        if (editJurusan) editJurusan.value = d.jurusan_sek || '';
        const editMasuk = document.getElementById('pend-edit-tahun_masuk');
        if (editMasuk) {
            editMasuk.value = masukVal;
            editMasuk.max = TODAY;
        }

        const lulusInput = document.getElementById('pend-edit-tahun_lulus');
        if (lulusInput) {
            lulusInput.min = masukVal;
            lulusInput.value = lulusVal;
        }

        const masihKuliah = !!d.masih_kuliah;
        const editMasih = document.getElementById('pend-edit-masih_kuliah');
        if (editMasih) editMasih.checked = masihKuliah;
        const lulusField = document.getElementById('pend-edit-tahun-lulus-field');
        if (lulusField) {
            lulusField.style.opacity = masihKuliah ? '0.4' : '1';
        }
        if (lulusInput) lulusInput.disabled = masihKuliah;
        
        const errEl = document.getElementById('edit-pend-lulus-error');
        if (errEl) errEl.classList.add('hidden');

        window.switchToPendidikanView();
    } catch (e) {
        document.getElementById('pend-skeleton')?.classList.add('hidden');
        console.error(e);
    }
};

window.closeDetailPendidikan = function() {
    window.updateUrlParam('pend', null);
    const modal   = document.getElementById('modal-detail-pendidikan');
    const content = document.getElementById('modal-detail-pendidikan-content');
    if (!modal || !content) return;
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
};

window.switchToPendidikanEdit = function() {
    document.getElementById('pend-view-mode')?.classList.add('hidden');
    document.getElementById('pend-edit-mode')?.classList.remove('hidden');
    document.getElementById('pend-view-actions')?.classList.add('hidden');
    document.getElementById('pend-edit-actions')?.classList.remove('hidden');
    const label = document.getElementById('modal-pend-mode-label');
    if (label) {
        label.textContent = 'Mode Edit';
        label.className = label.className.replace('text-blue-600', 'text-amber-600');
    }
};

window.switchToPendidikanView = function() {
    document.getElementById('pend-view-mode')?.classList.remove('hidden');
    document.getElementById('pend-edit-mode')?.classList.add('hidden');
    document.getElementById('pend-view-actions')?.classList.remove('hidden');
    document.getElementById('pend-edit-actions')?.classList.add('hidden');
    const label = document.getElementById('modal-pend-mode-label');
    if (label) {
        label.textContent = 'Mode Lihat';
        label.className = label.className.replace('text-amber-600', 'text-blue-600');
    }
};

window.savePendidikanEdit = async function() {
    const id           = document.getElementById('pend-edit-id').value;
    const nama_sekolah = document.getElementById('pend-edit-nama_sekolah').value.trim();
    const jenjang      = document.getElementById('pend-edit-jenjang').value;
    const jurusan_sek  = document.getElementById('pend-edit-jurusan_sek').value.trim();
    const tahun_masuk  = document.getElementById('pend-edit-tahun_masuk').value;
    const tahun_lulus  = document.getElementById('pend-edit-tahun_lulus').value;
    const masih_kuliah = document.getElementById('pend-edit-masih_kuliah').checked ? '1' : '0';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const locale    = document.querySelector('html').getAttribute('lang') || 'id';

    if (!nama_sekolah || !jenjang || !tahun_masuk) {
        if (window.showErrorAlert) window.showErrorAlert('Harap isi field yang wajib diisi.');
        return;
    }

    if (masih_kuliah === '0' && tahun_lulus && tahun_lulus < tahun_masuk) {
        const errEl = document.getElementById('edit-pend-lulus-error');
        if (errEl) errEl.classList.remove('hidden');
        if (window.showErrorAlert) window.showErrorAlert('Tahun lulus tidak boleh sebelum tahun masuk.');
        return;
    }

    const body = new URLSearchParams({ _method: 'PATCH', nama_sekolah, jenjang, jurusan_sek, tahun_masuk, masih_kuliah });
    if (masih_kuliah === '0' && tahun_lulus) body.append('tahun_lulus', tahun_lulus);

    try {
        const response = await fetch(`/${locale}/pendidikan/update?id=${id}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        });
        const json = await response.json();
        if (json.success) {
            window.closeDetailPendidikan();
            if (window.showSuccessAlert) window.showSuccessAlert('Pendidikan Berhasil diperbarui!');
            setTimeout(() => window.location.reload(), 1800);
        } else {
            const errMsg = json.errors ? Object.values(json.errors).flat().join(', ') : (json.message || 'Gagal menyimpan perubahan.');
            if (window.showErrorAlert) window.showErrorAlert(errMsg);
        }
    } catch (e) {
        console.error('Error:', e);
        if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan jaringan.');
    }
};

window.confirmDeletePendidikan = async function() {
    const id = document.getElementById('pend-edit-id').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const locale    = document.querySelector('html').getAttribute('lang') || 'id';
    const confirmed = await window.showConfirmAlert();
    if (!confirmed) return;
    try {
        const res  = await fetch(`/${locale}/pendidikan/destroy?id=${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        });
        const json = await res.json();
        if (json.success) {
            window.closeDetailPendidikan();
            if (window.showSuccessAlert) window.showSuccessAlert('Pendidikan Berhasil dihapus!');
            setTimeout(() => window.location.reload(), 1800);
        } else {
            if (window.showErrorAlert) window.showErrorAlert(json.message || 'Gagal menghapus.');
        }
    } catch (e) {
        if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan jaringan.');
    }
};

// Modal Detail/Edit Pengalaman Kerja
window.openDetailPengalaman = async function(id) {
    window.updateUrlParam('pkj', id);
    const modal   = document.getElementById('modal-detail-pengalaman');
    const content = document.getElementById('modal-detail-pengalaman-content');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const locale    = document.querySelector('html').getAttribute('lang') || 'id';
    const TODAY = new Date().toISOString().split('T')[0];

    if (!modal || !content) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    });

    document.getElementById('pkj-view-mode')?.classList.add('hidden');
    document.getElementById('pkj-view-actions')?.classList.add('hidden');
    document.getElementById('pkj-edit-mode')?.classList.add('hidden');
    document.getElementById('pkj-edit-actions')?.classList.add('hidden');
    document.getElementById('pkj-skeleton')?.classList.remove('hidden');

    try {
        const res  = await fetch(`/${locale}/pengalaman-kerja/detail?id=${id}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        });
        const json = await res.json();
        if (!json.success) return;
        const d = json.data;
        document.getElementById('pkj-skeleton')?.classList.add('hidden');
        
        const idInput = document.getElementById('pkj-edit-id');
        if (idInput) idInput.value = d.id;

        const namaEl = document.getElementById('pkj-view-nama');
        if (namaEl) namaEl.textContent = d.nama_pt || '-';
        const bagEl = document.getElementById('pkj-view-bagian');
        if (bagEl) bagEl.textContent = d.bagian_kerja || '-';
        const jenisEl = document.getElementById('pkj-view-jenis');
        if (jenisEl) jenisEl.textContent = d.jenis_pekerjaan || '';
        const descEl = document.getElementById('pkj-view-deskripsi');
        if (descEl) descEl.textContent = d.deskripsi || '-';
        const akhirText = d.masih_bekerja ? 'Sekarang' : (d.tahun_akhir || 'Selesai');
        const periodEl = document.getElementById('pkj-view-periode');
        if (periodEl) periodEl.textContent = `${d.tahun_mulai || '-'} — ${akhirText}`;

        let statusText = '';
        if (d.masih_bekerja) {
            statusText = '🟢 Aktif bekerja';
        } else if (d.tahun_akhir) {
            const akhirYMD = window.toYMD(d.tahun_akhir);
            if (akhirYMD >= TODAY) {
                statusText = d.jenis_pekerjaan ? `🟡 ${d.jenis_pekerjaan}` : '🟡 Sedang berjalan';
            } else {
                statusText = '✅ Selesai';
            }
        } else {
            statusText = '✅ Selesai';
        }
        const statusEl = document.getElementById('pkj-view-status');
        if (statusEl) statusEl.textContent = statusText;

        const sertWrap = document.getElementById('pkj-view-sertifikat-wrap');
        const sertLink = document.getElementById('pkj-view-sertifikat-link');
        if (d.sertifikat_pendukung && sertLink) {
            sertLink.href = `/storage/${d.sertifikat_pendukung}`;
            sertWrap?.classList.remove('hidden');
        } else {
            sertWrap?.classList.add('hidden');
        }

        const mulaiVal = window.toYMD(d.tahun_mulai);
        const akhirVal = window.toYMD(d.tahun_akhir);

        const editPt = document.getElementById('pkj-edit-nama_pt');
        if (editPt) editPt.value = d.nama_pt || '';
        const editBag = document.getElementById('pkj-edit-bagian_kerja');
        if (editBag) editBag.value = d.bagian_kerja || '';
        const editJenis = document.getElementById('pkj-edit-jenis_pekerjaan');
        if (editJenis) editJenis.value = d.jenis_pekerjaan || '';
        const editDesc = document.getElementById('pkj-edit-deskripsi');
        if (editDesc) editDesc.value = d.deskripsi || '';
        const editMulai = document.getElementById('pkj-edit-tahun_mulai');
        if (editMulai) {
            editMulai.value = mulaiVal;
            editMulai.max = TODAY;
        }

        const akhirInput = document.getElementById('pkj-edit-tahun_akhir');
        if (akhirInput) {
            akhirInput.min = mulaiVal;
            akhirInput.value = akhirVal;
        }

        const masihBekerja = !!d.masih_bekerja;
        const editMasih = document.getElementById('pkj-edit-masih_bekerja');
        if (editMasih) editMasih.checked = masihBekerja;
        const akhirField = document.getElementById('pkj-edit-tahun-akhir-field');
        if (akhirField) {
            akhirField.style.opacity = masihBekerja ? '0.4' : '1';
        }
        if (akhirInput) akhirInput.disabled = masihBekerja;
        
        const errEl = document.getElementById('edit-pkj-akhir-error');
        if (errEl) errEl.classList.add('hidden');

        window.switchToPengalamanView();
    } catch (e) {
        document.getElementById('pkj-skeleton')?.classList.add('hidden');
        console.error(e);
    }
};

window.closeDetailPengalaman = function() {
    window.updateUrlParam('pkj', null);
    const modal   = document.getElementById('modal-detail-pengalaman');
    const content = document.getElementById('modal-detail-pengalaman-content');
    if (!modal || !content) return;
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
};

window.switchToPengalamanEdit = function() {
    document.getElementById('pkj-view-mode')?.classList.add('hidden');
    document.getElementById('pkj-edit-mode')?.classList.remove('hidden');
    document.getElementById('pkj-view-actions')?.classList.add('hidden');
    document.getElementById('pkj-edit-actions')?.classList.remove('hidden');
    const label = document.getElementById('modal-pkj-mode-label');
    if (label) {
        label.textContent = 'Mode Edit';
        label.className = label.className.replace('text-emerald-600', 'text-amber-600');
    }
};

window.switchToPengalamanView = function() {
    document.getElementById('pkj-view-mode')?.classList.remove('hidden');
    document.getElementById('pkj-edit-mode')?.classList.add('hidden');
    document.getElementById('pkj-view-actions')?.classList.remove('hidden');
    document.getElementById('pkj-edit-actions')?.classList.add('hidden');
    const label = document.getElementById('modal-pkj-mode-label');
    if (label) {
        label.textContent = 'Mode Lihat';
        label.className = label.className.replace('text-amber-600', 'text-emerald-600');
    }
};

window.savePengalamanEdit = async function() {
    const id             = document.getElementById('pkj-edit-id').value;
    const nama_pt        = document.getElementById('pkj-edit-nama_pt').value.trim();
    const bagian_kerja   = document.getElementById('pkj-edit-bagian_kerja').value.trim();
    const jenis_pekerjaan = document.getElementById('pkj-edit-jenis_pekerjaan').value;
    const deskripsi      = document.getElementById('pkj-edit-deskripsi').value.trim();
    const tahun_mulai  = document.getElementById('pkj-edit-tahun_mulai').value;
    const tahun_akhir  = document.getElementById('pkj-edit-tahun_akhir').value;
    const masih_bekerja = document.getElementById('pkj-edit-masih_bekerja').checked ? '1' : '0';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const locale    = document.querySelector('html').getAttribute('lang') || 'id';

    if (!nama_pt || !bagian_kerja || !jenis_pekerjaan || !tahun_mulai) {
        if (window.showErrorAlert) window.showErrorAlert('Harap isi field yang wajib diisi.');
        return;
    }

    if (masih_bekerja === '0' && tahun_akhir && tahun_akhir < tahun_mulai) {
        const errEl = document.getElementById('edit-pkj-akhir-error');
        if (errEl) errEl.classList.remove('hidden');
        if (window.showErrorAlert) window.showErrorAlert('Tahun selesai tidak boleh sebelum tahun mulai.');
        return;
    }

    const body = new URLSearchParams({ _method: 'PATCH', nama_pt, bagian_kerja, jenis_pekerjaan, deskripsi, tahun_mulai, masih_bekerja });
    if (masih_bekerja === '0' && tahun_akhir) body.append('tahun_akhir', tahun_akhir);

    try {
        const res  = await fetch(`/${locale}/pengalaman-kerja/update?id=${id}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        });
        const json = await res.json();
        if (json.success) {
            window.closeDetailPengalaman();
            if (window.showSuccessAlert) window.showSuccessAlert('Pengalaman Berhasil diperbarui!');
            setTimeout(() => window.location.reload(), 1800);
        } else {
            const errMsg = json.errors ? Object.values(json.errors).flat().join(', ') : (json.message || 'Gagal menyimpan perubahan.');
            if (window.showErrorAlert) window.showErrorAlert(errMsg);
        }
    } catch (e) {
        if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan jaringan.');
    }
};

window.confirmDeletePengalaman = async function() {
    const id = document.getElementById('pkj-edit-id').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const locale    = document.querySelector('html').getAttribute('lang') || 'id';
    const confirmed = await window.showConfirmAlert();
    if (!confirmed) return;
    try {
        const res  = await fetch(`/${locale}/pengalaman-kerja/destroy?id=${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        });
        const json = await res.json();
        if (json.success) {
            window.closeDetailPengalaman();
            if (window.showSuccessAlert) window.showSuccessAlert('Pengalaman Berhasil dihapus!');
            setTimeout(() => window.location.reload(), 1800);
        } else {
            if (window.showErrorAlert) window.showErrorAlert(json.message || 'Gagal menghapus.');
        }
    } catch (e) {
        if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan jaringan.');
    }
};

// Helper: update URL parameter
window.updateUrlParam = function(key, value) {
    const url = new URL(window.location.href);
    if (value) url.searchParams.set(key, value);
    else url.searchParams.delete(key);
    window.history.replaceState({}, '', url.toString());
};

// Date helper logic for modals
window.onAddPendTahunMasukChange = function(masukVal) {
    const lulusInput = document.getElementById('add-pend-tahun_lulus');
    if (!lulusInput) return;
    lulusInput.min = masukVal;
    if (lulusInput.value && lulusInput.value < masukVal) {
        lulusInput.value = '';
        const errEl = document.getElementById('add-pend-lulus-error');
        if (errEl) errEl.classList.remove('hidden');
    } else {
        const errEl = document.getElementById('add-pend-lulus-error');
        if (errEl) errEl.classList.add('hidden');
    }
};

window.validateAddPendTahunLulus = function(input) {
    const masukVal = document.getElementById('add-pend-tahun_masuk')?.value;
    if (masukVal && input.value && input.value < masukVal) {
        const errEl = document.getElementById('add-pend-lulus-error');
        if (errEl) errEl.classList.remove('hidden');
        input.value = '';
    } else {
        const errEl = document.getElementById('add-pend-lulus-error');
        if (errEl) errEl.classList.add('hidden');
    }
};

window.toggleAddPendTahunLulus = function(checkbox) {
    const field = document.getElementById('add-pend-tahun-lulus-field');
    const input = document.getElementById('add-pend-tahun_lulus');
    if (!field || !input) return;
    field.style.opacity = checkbox.checked ? '0.4' : '1';
    input.disabled = checkbox.checked;
    if (checkbox.checked) {
        input.value = '';
        const errEl = document.getElementById('add-pend-lulus-error');
        if (errEl) errEl.classList.add('hidden');
    }
};

window.onEditPendTahunMasukChange = function(masukVal) {
    const lulusInput = document.getElementById('pend-edit-tahun_lulus');
    if (!lulusInput) return;
    lulusInput.min = masukVal;
    if (lulusInput.value && lulusInput.value < masukVal) {
        lulusInput.value = '';
        const errEl = document.getElementById('edit-pend-lulus-error');
        if (errEl) errEl.classList.remove('hidden');
    } else {
        const errEl = document.getElementById('edit-pend-lulus-error');
        if (errEl) errEl.classList.add('hidden');
    }
};

window.validateEditPendTahunLulus = function(input) {
    const masukVal = document.getElementById('pend-edit-tahun_masuk')?.value;
    if (masukVal && input.value && input.value < masukVal) {
        const errEl = document.getElementById('edit-pend-lulus-error');
        if (errEl) errEl.classList.remove('hidden');
        input.value = '';
    } else {
        const errEl = document.getElementById('edit-pend-lulus-error');
        if (errEl) errEl.classList.add('hidden');
    }
};

window.toggleEditTahunLulus = function(checkbox) {
    const field = document.getElementById('pend-edit-tahun-lulus-field');
    const input = document.getElementById('pend-edit-tahun_lulus');
    if (!field || !input) return;
    field.style.opacity = checkbox.checked ? '0.4' : '1';
    input.disabled = checkbox.checked;
    if (checkbox.checked) {
        input.value = '';
        const errEl = document.getElementById('edit-pend-lulus-error');
        if (errEl) errEl.classList.add('hidden');
    }
};

window.onAddPkjTahunMulaiChange = function(mulaiVal) {
    const akhirInput = document.getElementById('form-tahun_akhir');
    if (!akhirInput) return;
    akhirInput.min = mulaiVal;
    if (akhirInput.value && akhirInput.value < mulaiVal) {
        akhirInput.value = '';
        const errEl = document.getElementById('add-pkj-akhir-error');
        if (errEl) errEl.classList.remove('hidden');
    } else {
        const errEl = document.getElementById('add-pkj-akhir-error');
        if (errEl) errEl.classList.add('hidden');
    }
};

window.validateAddPkjTahunAkhir = function(input) {
    const mulaiVal = document.getElementById('form-tahun_mulai')?.value;
    if (mulaiVal && input.value && input.value < mulaiVal) {
        const errEl = document.getElementById('add-pkj-akhir-error');
        if (errEl) errEl.classList.remove('hidden');
        input.value = '';
    } else {
        const errEl = document.getElementById('add-pkj-akhir-error');
        if (errEl) errEl.classList.add('hidden');
    }
};

window.toggleAddPkjTahunAkhir = function(checkbox) {
    const field = document.getElementById('add-pkj-tahun-akhir-field');
    const input = document.getElementById('form-tahun_akhir');
    if (!field || !input) return;
    field.style.opacity = checkbox.checked ? '0.4' : '1';
    input.disabled = checkbox.checked;
    if (checkbox.checked) {
        input.value = '';
        const errEl = document.getElementById('add-pkj-akhir-error');
        if (errEl) errEl.classList.add('hidden');
    }
};

window.onEditPkjTahunMulaiChange = function(mulaiVal) {
    const akhirInput = document.getElementById('pkj-edit-tahun_akhir');
    if (!akhirInput) return;
    akhirInput.min = mulaiVal;
    if (akhirInput.value && akhirInput.value < mulaiVal) {
        akhirInput.value = '';
        const errEl = document.getElementById('edit-pkj-akhir-error');
        if (errEl) errEl.classList.remove('hidden');
    } else {
        const errEl = document.getElementById('edit-pkj-akhir-error');
        if (errEl) errEl.classList.add('hidden');
    }
};

window.validateEditPkjTahunAkhir = function(input) {
    const mulaiVal = document.getElementById('pkj-edit-tahun_mulai')?.value;
    if (mulaiVal && input.value && input.value < mulaiVal) {
        const errEl = document.getElementById('edit-pkj-akhir-error');
        if (errEl) errEl.classList.remove('hidden');
        input.value = '';
    } else {
        const errEl = document.getElementById('edit-pkj-akhir-error');
        if (errEl) errEl.classList.add('hidden');
    }
};

window.toggleEditTahunAkhir = function(checkbox) {
    const field = document.getElementById('pkj-edit-tahun-akhir-field');
    const input = document.getElementById('pkj-edit-tahun_akhir');
    if (!field || !input) return;
    field.style.opacity = checkbox.checked ? '0.4' : '1';
    input.disabled = checkbox.checked;
    if (checkbox.checked) {
        input.value = '';
        const errEl = document.getElementById('edit-pkj-akhir-error');
        if (errEl) errEl.classList.add('hidden');
    }
};

window.toYMD = function(dateStr) {
    if (!dateStr) return '';
    const s = String(dateStr).trim();
    if (/^\d{2}-\d{2}-\d{4}$/.test(s)) {
        const [d, m, y] = s.split('-');
        return `${y}-${m}-${d}`;
    }
    return s.substring(0, 10);
};

/* =====================================================================
   ALPINE.JS KEAHLIAN TAMBAHAN GLOBAL COMPONENT
   ===================================================================== */
window.keahlianTambahan = function(config) {
    return {
        keahlianOptions: config.options || [],
        mainSkillId: config.mainSkillId || null,
        keahlianList: config.list || [],
        selectedKeahlian: config.selected || '',
        loading: false,
        showAlert: false,
        alertMessage: '',
        alertType: 'success',
        get keahlianCount() { return this.keahlianList ? this.keahlianList.length : 0; },
        isKeahlianDisabled(keahlian) { return keahlian.id_keahlian === this.mainSkillId || this.isKeahlianExists(keahlian.id_keahlian); },
        isKeahlianSelectedInTambahan(id) { return this.keahlianList.some(item => item.id_keahlian == id); },
        init() { this.refreshKeahlianList(); },
        isKeahlianExists(id) { return this.keahlianList.some(item => item.id_keahlian == id); },
        async submitKeahlianFromDropdown() {
            if (!this.selectedKeahlian) return;
            this.loading = true;
            try {
                const locale = document.querySelector('html').getAttribute('lang') || 'id';
                const body = new URLSearchParams();
                body.append('id_keahlian_tambahan', this.selectedKeahlian);
                const response = await fetch(`/${locale}/keahlian-tambahan`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': config.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body
                });
                const data = await response.json();
                if (data.success) {
                    await this.refreshKeahlianList();
                    this.selectedKeahlian = '';
                    this.showNotification(data.message || 'Pengajuan keahlian berhasil dikirim', 'success');
                } else {
                    this.showNotification(data.message || 'Gagal mengirim keahlian', 'error');
                }
            } catch (error) {
                console.error('Error submitting keahlian:', error);
                this.showNotification('Terjadi kesalahan jaringan', 'error');
            } finally {
                this.loading = false;
            }
        },
        showNotification(message, type = 'success') {
            this.alertMessage = message; this.alertType = type; this.showAlert = true;
            setTimeout(() => { this.showAlert = false; }, 5000);
        },
        async deleteKeahlian(id, index) {
            if (!confirm('Hapus keahlian tambahan ini?')) return;
            this.loading = true;
            try {
                const locale = document.querySelector('html').getAttribute('lang') || 'id';
                const response = await fetch(`/${locale}/keahlian-tambahan/destroy?id=${id}`, {
                    method: 'DELETE',
                    headers: { 
                        'X-CSRF-TOKEN': config.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 
                        'Accept': 'application/json' 
                    }
                });
                const data = await response.json();
                if (data.success) { await this.refreshKeahlianList(); this.showNotification(data.message, 'success'); }
                else { this.showNotification(data.message || 'Gagal menghapus', 'error'); }
            } catch (error) { this.showNotification('Terjadi kesalahan jaringan', 'error'); }
            finally { this.loading = false; }
        },
        async refreshKeahlianList() {
            try {
                const url = config.indexUrl || '/keahlian-tambahan';
                const response = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await response.json();
                if (data.success) this.keahlianList = data.data;
            } catch (error) { console.error('Error refreshing keahlian list:', error); }
        }
    };
};

window.submitCustomKeahlian = async function() {
    const input = document.getElementById('custom-keahlian-input');
    if (!input) return;
    const customKeahlian = input.value.trim();
    
    if (!customKeahlian) {
        if (window.showErrorAlert) {
            window.showErrorAlert('Masukkan nama keahlian terlebih dahulu');
        } else {
            alert('Masukkan nama keahlian terlebih dahulu');
        }
        return;
    }
    
    if (customKeahlian.length > 100) {
        if (window.showErrorAlert) {
            window.showErrorAlert('Nama keahlian maksimal 100 karakter');
        } else {
            alert('Nama keahlian maksimal 100 karakter');
        }
        return;
    }
    
    const submitBtn = document.querySelector('#custom-keahlian-input + button');
    const originalText = submitBtn ? submitBtn.innerHTML : '';
    
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Mengirim...';
    }
    
    const locale = document.querySelector('html').getAttribute('lang') || 'id';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const formData = new FormData();
    formData.append('custom_keahlian_tambahan', customKeahlian);
    
    try {
        const response = await fetch(`/${locale}/keahlian-tambahan/custom`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            input.value = '';
            if (window.showSuccessAlert) {
                window.showSuccessAlert(data.message);
            } else {
                alert(data.message);
            }
            setTimeout(() => window.location.reload(), 1500);
        } else {
            if (window.showErrorAlert) {
                window.showErrorAlert(data.message);
            } else {
                alert(data.message);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        if (window.showErrorAlert) {
            window.showErrorAlert('Terjadi kesalahan jaringan');
        } else {
            alert('Terjadi kesalahan jaringan');
        }
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }
};

// Event listener for Enter on custom skill input
document.addEventListener('DOMContentLoaded', function() {
    const customInput = document.getElementById('custom-keahlian-input');
    if (customInput) {
        customInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                window.submitCustomKeahlian();
            }
        });
    }
});

/* ==========================================
   COMPONENT: CARD POSTINGAN
   ========================================== */
if (typeof window.playVideoInCard === 'undefined') {
    /**
     * Mainkan video langsung di dalam card tanpa navigasi.
     *
     * @param {string} wrapperId  - ID dari elemen .video-preview-wrapper
     * @param {string} type       - 'youtube' | 'direct'
     * @param {string} src        - YouTube video ID atau URL video langsung
     */
    window.playVideoInCard = function(wrapperId, type, src) {
        const wrapper = document.getElementById(wrapperId);
        if (!wrapper) return;

        const embedContainer = wrapper.querySelector('.video-embed-container');
        if (!embedContainer) return;

        if (type === 'youtube') {
            // Buat iframe YouTube dengan autoplay
            const iframe = document.createElement('iframe');
            iframe.src = 'https://www.youtube.com/embed/' + src + '?autoplay=1&rel=0&modestbranding=1';
            iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
            iframe.allowFullscreen = true;
            iframe.style.width = '100%';
            iframe.style.height = '100%';
            iframe.style.border = 'none';
            embedContainer.innerHTML = '';
            embedContainer.appendChild(iframe);
        } else if (type === 'direct') {
            // Buat native video element
            const video = document.createElement('video');
            video.src = src;
            video.controls = true;
            video.autoplay = true;
            video.style.width = '100%';
            video.style.height = '100%';
            video.style.objectFit = 'contain';
            video.style.background = '#000';
            embedContainer.innerHTML = '';
            embedContainer.appendChild(video);
        }

        // Tandai wrapper sebagai playing → sembunyikan thumbnail & overlay
        wrapper.classList.add('playing');
        // Hapus onclick agar tidak re-trigger
        wrapper.onclick = null;
    };
}

/* ==========================================
   COMPONENT: UP PAGE (BACK TO TOP)
   ========================================== */
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById("backToTop");
    if (!btn) return;

    window.addEventListener("scroll", handleScroll, true);

    function handleScroll(e) {
        const target = e.target;
        if (target === document || (target.tagName && target.tagName.toLowerCase() === 'main')) {
            const scrollTop = target === document ? (window.scrollY || document.documentElement.scrollTop) : target.scrollTop;
            if (scrollTop > 200) {
                btn.classList.remove("hidden");
            } else {
                btn.classList.add("hidden");
            }
        }
    }

    btn.addEventListener("click", function () {
        const main = document.querySelector('main');
        if (main && main.scrollTop > 0) {
            main.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
});

/* ==========================================
   COMPONENT: OFFLINE
   ========================================== */
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('offline-container');
    if (!container) return;

    const homeUrl = container.dataset.homeUrl || '/';
    const offlineMessage = container.dataset.offlineMessage || 'Masih offline, nih. Cek koneksi kamu lagi ya!';

    function checkAndRedirect() {
        if (navigator.onLine) {
            const destination = document.referrer && !document.referrer.includes('offline') 
                                ? document.referrer 
                                : homeUrl;
            window.location.href = destination;
        }
    }

    checkAndRedirect();
    window.addEventListener('online', checkAndRedirect);

    const reloadBtn = document.getElementById('offline-reload-btn');
    if (reloadBtn) {
        reloadBtn.addEventListener('click', () => {
            if (navigator.onLine) {
                checkAndRedirect();
            } else {
                alert(offlineMessage);
            }
        });
    }
});

/* ==========================================
   COMPONENT: GET APP
   ========================================== */
document.addEventListener('DOMContentLoaded', () => {
    const qrContainer = document.getElementById("qrcode");
    if (!qrContainer) return;

    if (typeof QRCode !== 'undefined') {
        new QRCode(qrContainer, {
            text: window.location.origin,
            width: 150,
            height: 150,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }
});

/* ==========================================
   COMPONENT: HELP
   ========================================== */
document.addEventListener('DOMContentLoaded', function() {
    // Get all FAQ items
    const faqItems = document.querySelectorAll('.faq-item');
    if (faqItems.length === 0) return;
    
    // Auto-slide effect: open one by one with delay
    let currentIndex = 0;
    
    function openNextFAQ() {
        if (currentIndex < faqItems.length) {
            const item = faqItems[currentIndex];
            const answer = item.querySelector('.faq-answer');
            const icon = item.querySelector('.faq-icon');
            
            if (!answer || !icon) return;

            // Close all other FAQs
            faqItems.forEach((otherItem, idx) => {
                if (idx !== currentIndex) {
                    const otherAnswer = otherItem.querySelector('.faq-answer');
                    const otherIcon = otherItem.querySelector('.faq-icon');
                    if (otherAnswer && otherIcon) {
                        otherAnswer.style.maxHeight = '0';
                        otherAnswer.style.opacity = '0';
                        otherIcon.style.transform = 'rotate(0deg)';
                    }
                }
            });
            
            // Open current FAQ with animation
            answer.style.maxHeight = answer.scrollHeight + 'px';
            answer.style.opacity = '1';
            icon.style.transform = 'rotate(180deg)';
            
            // Move to next after delay
            currentIndex++;
            setTimeout(openNextFAQ, 3000);
        } else {
            // Reset to first after all opened
            setTimeout(() => {
                currentIndex = 0;
                openNextFAQ();
            }, 2000);
        }
    }
    
    // Start auto-slide
    setTimeout(openNextFAQ, 500);
    
    // Add click functionality for manual toggle
    faqItems.forEach((item, index) => {
        const question = item.querySelector('h3');
        const answer = item.querySelector('.faq-answer');
        const icon = item.querySelector('.faq-icon');
        
        if (!question || !answer || !icon) return;

        question.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Toggle current FAQ
            const isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';
            
            // Close all FAQs
            faqItems.forEach((otherItem) => {
                const otherAnswer = otherItem.querySelector('.faq-answer');
                const otherIcon = otherItem.querySelector('.faq-icon');
                if (otherAnswer && otherIcon) {
                    otherAnswer.style.maxHeight = '0';
                    otherAnswer.style.opacity = '0';
                    otherIcon.style.transform = 'rotate(0deg)';
                }
            });
            
            // Open clicked FAQ if it was closed
            if (!isOpen) {
                answer.style.maxHeight = answer.scrollHeight + 'px';
                answer.style.opacity = '1';
                icon.style.transform = 'rotate(180deg)';
                
                // Update current index for auto-slide
                currentIndex = index + 1;
            }
        });
    });
});

/* ==========================================
   COMPONENT: SPLASH SCREEN
   ========================================== */
(function() {
    // Source-of-truth ada di SERVER (cookie 'splash_shown'), dicek di Layout.blade.php
    // sebelum @include('components.splash'). Kalau cookie itu sudah ada, server tidak
    // akan mengirim markup splash sama sekali -- jadi tidak ada flash di halaman berikutnya.
    // JS di sini hanya menjalankan animasi untuk kunjungan pertama, lalu menulis cookie
    // tersebut begitu splash selesai/dilewati, supaya request berikutnya tidak menampilkannya lagi.
    const SPLASH_COOKIE = 'splash_shown';
    const SPLASH_TTL_DAYS = 1;

    function setSplashShownCookie() {
        const expires = new Date(Date.now() + SPLASH_TTL_DAYS * 24 * 60 * 60 * 1000).toUTCString();
        document.cookie = `${SPLASH_COOKIE}=true; expires=${expires}; path=/; SameSite=Lax`;
    }

    function clearSplashShownCookie() {
        document.cookie = `${SPLASH_COOKIE}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; SameSite=Lax`;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const splashRoot = document.getElementById('splash-screen-root');
        if (!splashRoot) return; // Server sudah menentukan splash tidak perlu ditampilkan

        const doorLeft = document.getElementById('doorLeft');
        const doorRight = document.getElementById('doorRight');
        const spinnerWrapper = document.getElementById('spinnerWrapper');
        const logoWrapper = document.getElementById('logoWrapper');
        const gradientOverlay = document.getElementById('gradientOverlay');
        const welcomeText = document.getElementById('welcomeText');
        const skipButton = document.getElementById('skipButton');

        let isHiding = false;
        let hideTimer = null;
        let animationTimer = null;

        function hideSplash() {
            if (isHiding || !splashRoot) return;
            isHiding = true;

            if (hideTimer) clearTimeout(hideTimer);
            if (animationTimer) clearTimeout(animationTimer);

            setSplashShownCookie();

            splashRoot.style.transition = 'opacity 0.5s ease-out';
            splashRoot.style.opacity = '0';

            setTimeout(() => {
                if (splashRoot && splashRoot.parentNode) {
                    splashRoot.remove();
                }
                document.body.style.overflow = '';
                document.body.style.position = '';
                document.body.style.width = '';
                document.body.style.top = '';
            }, 500);
        }

        function startAnimation() {
            if (doorLeft) doorLeft.classList.add('animate');
            if (doorRight) doorRight.classList.add('animate');

            if (spinnerWrapper) spinnerWrapper.classList.add('hidden');

            setTimeout(() => {
                if (logoWrapper) logoWrapper.classList.add('visible');
            }, 500);

            if (gradientOverlay) gradientOverlay.classList.add('animate');

            setTimeout(() => {
                if (welcomeText) welcomeText.classList.add('visible');
                if (skipButton) skipButton.classList.add('visible');
            }, 700);

            hideTimer = setTimeout(() => {
                hideSplash();
            }, 4000);
        }

        function initSplash() {
            const scrollY = window.scrollY;
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.width = '100%';
            document.body.style.top = `-${scrollY}px`;

            if (typeof window.translations !== 'undefined' && typeof window.currentLang !== 'undefined') {
                const splashPage = window.translations[window.currentLang]?.splash || {};

                const welcomeTitle = document.querySelector('.welcome-title');
                const welcomeSubtitle = document.querySelector('.welcome-subtitle');
                const skipBtn = document.querySelector('.skip-button');

                if (welcomeTitle && splashPage.splash_welcome) {
                    welcomeTitle.textContent = splashPage.splash_welcome;
                }
                if (welcomeSubtitle && splashPage.splash_subtitle) {
                    welcomeSubtitle.textContent = splashPage.splash_subtitle;
                }
                if (skipBtn && splashPage.splash_skip) {
                    skipBtn.innerHTML = splashPage.splash_skip + ' →';
                }
            }

            animationTimer = setTimeout(() => {
                startAnimation();
            }, 300);

            if (skipButton) {
                skipButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    hideSplash();

                    const scrollY = document.body.style.top;
                    document.body.style.position = '';
                    document.body.style.top = '';
                    document.body.style.width = '';
                    document.body.style.overflow = '';
                    window.scrollTo(0, parseInt(scrollY || '0') * -1);
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !isHiding) {
                    hideSplash();

                    const scrollY = document.body.style.top;
                    document.body.style.position = '';
                    document.body.style.top = '';
                    document.body.style.width = '';
                    document.body.style.overflow = '';
                    window.scrollTo(0, parseInt(scrollY || '0') * -1);
                }
            });
        }

        initSplash();

        window.resetSplash = function() {
            clearSplashShownCookie();
            window.location.reload();
        };
    });
})();

/* ==========================================
   COMPONENT: MOBILE NAVIGATION
   ========================================== */
window.toggleMobileFab = function() {
    const sheet = document.getElementById('mobile-fab-sheet');
    const overlay = document.getElementById('mobile-fab-overlay');
    const icon = document.getElementById('mobile-fab-icon');
    if (!sheet || !overlay) return;

    const isOpen = !sheet.classList.contains('translate-y-full');

    if (isOpen) {
        window.closeMobileFab();
    } else {
        window.closeMobileProfile(); // tutup sheet lain
        overlay.classList.remove('hidden');
        overlay.offsetHeight; // force reflow
        overlay.classList.remove('opacity-0');
        sheet.classList.remove('translate-y-full');
        if (icon) icon.style.transform = 'rotate(45deg)';
    }
};

window.closeMobileFab = function() {
    const sheet = document.getElementById('mobile-fab-sheet');
    const overlay = document.getElementById('mobile-fab-overlay');
    const icon = document.getElementById('mobile-fab-icon');
    if (!sheet || !overlay) return;

    sheet.classList.add('translate-y-full');
    overlay.classList.add('opacity-0');
    if (icon) icon.style.transform = 'rotate(0deg)';
    setTimeout(() => overlay.classList.add('hidden'), 300);
};

window.toggleMobileProfile = function() {
    const sheet = document.getElementById('mobile-profile-sheet');
    const overlay = document.getElementById('mobile-profile-overlay');
    if (!sheet || !overlay) return;

    const isOpen = !sheet.classList.contains('translate-y-full');

    if (isOpen) {
        window.closeMobileProfile();
    } else {
        window.closeMobileFab();
        overlay.classList.remove('hidden');
        overlay.offsetHeight;
        overlay.classList.remove('opacity-0');
        sheet.classList.remove('translate-y-full');
    }
};

window.closeMobileProfile = function() {
    const sheet = document.getElementById('mobile-profile-sheet');
    const overlay = document.getElementById('mobile-profile-overlay');
    if (!sheet || !overlay) return;

    sheet.classList.add('translate-y-full');
    overlay.classList.add('opacity-0');
    setTimeout(() => overlay.classList.add('hidden'), 300);
};

window.syncDarkModeToggle = function() {
    const isDark = document.documentElement.classList.contains('dark');
    const toggleSpan = document.querySelector('#mobile-dark-toggle span');
    const toggleDiv = document.getElementById('mobile-dark-toggle');
    if (toggleSpan) {
        if (isDark) {
            toggleSpan.classList.add('translate-x-5');
        } else {
            toggleSpan.classList.remove('translate-x-5');
        }
    }
    if (toggleDiv) {
        if (isDark) {
            toggleDiv.classList.add('dark:bg-blue-600');
        } else {
            toggleDiv.classList.remove('dark:bg-blue-600');
        }
    }
};

window.toggleMobileDarkMode = function() {
    const html = document.documentElement;
    html.classList.toggle('dark');
    localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    window.syncDarkModeToggle();
};

window.changeLanguageMobile = function(lang) {
    window.persistLocaleChoice(lang);
    window.location.reload();
};

window.persistLocaleChoice = function(lang) {
    localStorage.setItem('lang', lang);
    const days = 365;
    const expires = new Date(Date.now() + days * 24 * 60 * 60 * 1000).toUTCString();
    document.cookie = `lang=${lang}; expires=${expires}; path=/; SameSite=Lax`;
};

window.toggleGuestSheet = function() {
    const sheet = document.getElementById('guest-sheet');
    const overlay = document.getElementById('guest-sheet-overlay');
    if (!sheet || !overlay) return;

    const isOpen = !sheet.classList.contains('translate-y-full');

    if (isOpen) {
        window.closeGuestSheet();
    } else {
        sheet.classList.remove('translate-y-full');
        overlay.classList.remove('hidden');
        overlay.offsetHeight; // force reflow
        overlay.classList.remove('opacity-0');
    }
};

window.closeGuestSheet = function() {
    const sheet = document.getElementById('guest-sheet');
    const overlay = document.getElementById('guest-sheet-overlay');
    if (!sheet || !overlay) return;

    sheet.classList.add('translate-y-full');
    overlay.classList.add('opacity-0');
    setTimeout(() => overlay.classList.add('hidden'), 300);
};

window.toggleGuestDarkMode = function() {
    const html = document.documentElement;
    html.classList.toggle('dark');
    localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    window.syncGuestDarkToggle();
};

window.syncGuestDarkToggle = function() {
    const isDark = document.documentElement.classList.contains('dark');
    const toggleSpan = document.querySelector('#guest-dark-toggle span');
    if (toggleSpan) {
        if (isDark) {
            toggleSpan.classList.add('translate-x-5');
        } else {
            toggleSpan.classList.remove('translate-x-5');
        }
    }
};

window.changeGuestLanguage = function(lang) {
    window.persistLocaleChoice(lang);
    window.location.reload();
};

document.addEventListener('DOMContentLoaded', function() {
    window.syncDarkModeToggle();
    window.syncGuestDarkToggle();

    // Swipe to close sheet (Auth & Guest)
    ['mobile-fab-sheet', 'mobile-profile-sheet', 'guest-sheet'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;

        let startY = 0;
        el.addEventListener('touchstart', (e) => {
            startY = e.touches[0].clientY;
        }, { passive: true });

        el.addEventListener('touchend', (e) => {
            const deltaY = e.changedTouches[0].clientY - startY;
            if (deltaY > 60) {
                if (id === 'mobile-fab-sheet') window.closeMobileFab();
                if (id === 'mobile-profile-sheet') window.closeMobileProfile();
                if (id === 'guest-sheet') window.closeGuestSheet();
            }
        }, { passive: true });
    });

    const guestMenuBtn = document.getElementById('guest-menu-btn');
    if (guestMenuBtn) {
        guestMenuBtn.addEventListener('click', window.toggleGuestSheet);
    }
});

/* ==========================================
   COMPONENT: SIDEBAR
   ========================================== */
window.previewPhoto = function(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('preview-photo');
        if (preview) preview.src = e.target.result;
    };
    reader.readAsDataURL(file);
};

window.toggleSidebarSettingDropdown = function() {
    const el = document.getElementById('settingMenu');
    const arrow = document.getElementById('settingArrow');
    if (el && arrow) {
        el.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }
};

window.toggleSidebarDarkMode = function() {
    const html = document.documentElement;
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('darkMode', isDark);
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    const btn = document.getElementById('darkModeBtn');
    if (btn) btn.textContent = isDark ? 'Light Mode' : 'Dark Mode';
};

// Inisialisasi dark mode dari localStorage saat halaman siap
document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('darkMode') === 'true' || localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
        const btn = document.getElementById('darkModeBtn');
        if (btn) btn.textContent = 'Light Mode';
    }
});

// Tutup setting dropdown saat klik di luar
document.addEventListener('click', function(e) {
    const settingMenu = document.getElementById('settingMenu');
    if (!settingMenu) return;
    const isToggleBtn = e.target.closest('button[onclick*="toggleSidebarSettingDropdown"]');
    if (!isToggleBtn && !settingMenu.contains(e.target)) {
        settingMenu.classList.add('hidden');
        document.getElementById('settingArrow')?.classList.remove('rotate-180');
    }
});

// Search functionality
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('sidebarSearch');
    const searchResults = document.getElementById('searchResults');
    const configContainer = document.getElementById('sidebar-config');
    if (!searchInput || !searchResults || !configContainer) return;

    let allMenus = [];
    try {
        allMenus = JSON.parse(configContainer.dataset.menus || '[]');
    } catch (err) {
        console.error('Failed to parse sidebar menus JSON:', err);
    }

    function resolveMenuLabel(key) {
        const lang = window.currentLang || 'id';
        const dict = window.translations || {};
        return dict?.[lang]?.sidebar?.[key]
            || dict?.id?.sidebar?.[key]
            || key;
    }

    searchInput.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        if (!q) { searchResults.classList.add('hidden'); return; }
        const filtered = allMenus
            .map(m => ({ ...m, name: resolveMenuLabel(m.key) }))
            .filter(m => m.name && m.name.toLowerCase().includes(q));
        if (!filtered.length) {
            searchResults.innerHTML = `<div class="px-4 py-3 text-gray-500 text-sm text-center">${resolveMenuLabel('menu_tidak_ditemukan')}</div>`;
        } else {
            searchResults.innerHTML = filtered.map(m =>
                `<a href="${m.url}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-700 text-black dark:text-white text-sm transition-colors">
                    <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                    ${m.name}
                </a>`
            ).join('');
        }
        searchResults.classList.remove('hidden');
    });

    document.addEventListener('click', e => {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target))
            searchResults.classList.add('hidden');
    });

    searchInput.addEventListener('keydown', e => {
        if (e.key === 'Escape') { searchResults.classList.add('hidden'); searchInput.blur(); }
    });
});

/* ==========================================
   COMPONENT: HEADER
   ========================================== */
(function () {
    const currentLocale = document.documentElement.lang || 'id';

    function isDashboard() {
        return !!document.getElementById('postingan-container');
    }

    // Live post filter logic (only runs on dashboard)
    function performPostSearch(term) {
        if (!isDashboard()) return;

        const allPosts   = document.querySelectorAll('#postingan-container .post-card');
        const pagination = document.getElementById('postingan-pagination');
        const noResultEl = document.getElementById('postingan-no-results');

        const badge      = document.getElementById('header-post-search-badge');
        const countEl    = document.getElementById('header-post-search-count');
        const badgeMob   = document.getElementById('header-post-search-badge-mobile');
        const countMobEl = document.getElementById('header-post-search-count-mobile');
        const clearBtn   = document.getElementById('header-post-search-clear');

        if (term.length < 2) {
            allPosts.forEach(p => { p.style.display = ''; restoreTitle(p); });
            if (pagination) pagination.style.display = '';
            if (badge)    badge.style.display    = 'none';
            if (badgeMob) badgeMob.classList.add('hidden');
            if (clearBtn) clearBtn.style.display = 'none';
            if (noResultEl) noResultEl.remove();
            if (window.performDashboardPostSearch) window.performDashboardPostSearch('', 0);
            return;
        }

        if (clearBtn) clearBtn.style.display = 'flex';
        if (pagination) pagination.style.display = 'none';

        let visible = 0;
        allPosts.forEach(post => {
            const dataTitle = post.getAttribute('data-post-title') || '';
            const dataDesc  = post.getAttribute('data-post-description') || '';
            const dataAuth  = post.getAttribute('data-post-author') || '';
            const uiTitleEl = post.querySelector('h3');
            const uiTitle   = uiTitleEl ? uiTitleEl.innerText.toLowerCase() : '';
            const lc  = term.toLowerCase();
            const hit = dataTitle.includes(lc) || dataDesc.includes(lc) || dataAuth.includes(lc) || uiTitle.includes(lc);

            if (hit) {
                post.style.display = '';
                visible++;
                highlightTitle(post, term);
            } else {
                post.style.display = 'none';
                restoreTitle(post);
            }
        });

        if (window.performDashboardPostSearch) window.performDashboardPostSearch(term, visible);

        let noRes = document.getElementById('postingan-no-results');
        if (visible === 0) {
            if (!noRes) {
                noRes = document.createElement('div');
                noRes.id = 'postingan-no-results';
                noRes.className = 'text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm mt-4';
                noRes.innerHTML = `
                    <svg class="w-14 h-14 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada postingan ditemukan</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Coba kata kunci lain</p>`;
                const container = document.getElementById('postingan-container');
                if (container) container.parentNode.insertBefore(noRes, container.nextSibling);
            } else {
                noRes.style.display = '';
            }
        } else if (noRes) {
            noRes.style.display = 'none';
        }
    }

    function highlightTitle(post, term) {
        const h3 = post.querySelector('h3');
        if (!h3) return;
        if (!h3.dataset.originalText) h3.dataset.originalText = h3.innerText;
        const original = h3.dataset.originalText;
        const regex = new RegExp(`(${term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
        h3.innerHTML = original.replace(regex, '<mark style="background:#fef08a;color:inherit;border-radius:2px;padding:0 1px;" class="search-highlight">$1</mark>');
    }

    function restoreTitle(post) {
        const h3 = post.querySelector('h3');
        if (!h3 || !h3.dataset.originalText) return;
        h3.innerHTML = h3.dataset.originalText;
    }

    // Global search suggestions
    function fetchSuggestions(query, containerEl, suggestionsUrl, searchUrl) {
        if (!containerEl) return;
        if (query.length < 2) { containerEl.classList.add('hidden'); return; }
        fetch(`${suggestionsUrl}?q=${encodeURIComponent(query)}`)
            .then(r => r.json())
            .then(data => {
                if (!data.length) {
                    containerEl.innerHTML = `<div class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">Tidak ada hasil</div>`;
                } else {
                    const grouped = data.reduce((acc, item) => { acc[item.type] = acc[item.type] || []; acc[item.type].push(item); return acc; }, {});
                    const titles  = { mahasiswa: 'Mahasiswa', project: 'Project', sertifikat: 'Sertifikat', postingan: 'Postingan' };
                    let html = '';
                    Object.keys(titles).forEach(type => {
                        const items = grouped[type] || [];
                        if (items.length) {
                            html += `<div class="border-b border-gray-100 dark:border-gray-700">
                                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">${titles[type]}</div>`;
                            items.forEach(item => {
                                html += `<a href="${item.url}" class="block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 text-sm text-gray-700 dark:text-gray-200">
                                            <div class="font-medium">${item.name}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">${item.label}</div>
                                        </a>`;
                            });
                            html += `</div>`;
                        }
                    });
                    html += `<div class="px-4 py-3 bg-white dark:bg-gray-800">
                                <a href="${searchUrl}?q=${encodeURIComponent(query)}" class="block text-center text-sm text-indigo-600 dark:text-indigo-400 font-medium">Lihat semua hasil</a>
                             </div>`;
                    containerEl.innerHTML = html;
                }
                containerEl.classList.remove('hidden');
            })
            .catch(() => containerEl.classList.add('hidden'));
    }

    function initUnifiedSearch() {
        const desktopInput = document.getElementById('unified-search-input');
        const mobileInput  = document.getElementById('unified-search-input-mobile');
        const clearBtn     = document.getElementById('header-post-search-clear');
        const suggDesktop  = document.getElementById('search-suggestions');
        const suggMobile   = document.getElementById('search-suggestions-mobile');

        if (!desktopInput && !mobileInput) return;

        const suggestionsUrl = desktopInput?.dataset.suggestionsUrl || mobileInput?.dataset.suggestionsUrl || '/search/suggestions';
        const searchUrl = desktopInput?.dataset.searchUrl || mobileInput?.dataset.searchUrl || '/search';

        // Check if any filter is active — show reset button if so
        const resetBtn = document.getElementById('filter-reset-btn');
        function checkFiltersActive() {
            if (!resetBtn) return;
            const j = document.getElementById('filter-jurusan')?.value;
            const k = document.getElementById('filter-keahlian')?.value;
            const a = document.getElementById('filter-angkatan')?.value;
            const q = desktopInput?.value?.trim();
            resetBtn.style.display = (j || k || a || q) ? 'flex' : 'none';
        }

        let postTimer, suggTimer;

        function onInput(e) {
            const term = e.target.value.trim();
            // Sync both inputs
            if (e.target === desktopInput && mobileInput) mobileInput.value = e.target.value;
            if (e.target === mobileInput && desktopInput)  desktopInput.value = e.target.value;

            // Live post filter (dashboard only)
            clearTimeout(postTimer);
            postTimer = setTimeout(() => performPostSearch(term), 280);

            // Suggestions (global search)
            clearTimeout(suggTimer);
            const targetSugg = e.target === desktopInput ? suggDesktop : suggMobile;
            suggTimer = setTimeout(() => fetchSuggestions(term, targetSugg, suggestionsUrl, searchUrl), 250);

            checkFiltersActive();
        }

        if (desktopInput) desktopInput.addEventListener('input', onInput);
        if (mobileInput)  mobileInput.addEventListener('input', onInput);

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                if (desktopInput) desktopInput.value = '';
                if (mobileInput)  mobileInput.value  = '';
                performPostSearch('');
                if (suggDesktop) suggDesktop.classList.add('hidden');
                if (suggMobile)  suggMobile.classList.add('hidden');
                checkFiltersActive();
            });
        }

        // Filter dropdowns — also trigger post search sync
        ['filter-jurusan','filter-keahlian','filter-angkatan'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', checkFiltersActive);
        });

        checkFiltersActive();

        // Close suggestions on outside click
        document.addEventListener('click', function (e) {
            if (!e.target.closest('#unified-search-input') && !e.target.closest('#search-suggestions') &&
                !e.target.closest('#unified-search-input-mobile') && !e.target.closest('#search-suggestions-mobile')) {
                if (suggDesktop) suggDesktop.classList.add('hidden');
                if (suggMobile)  suggMobile.classList.add('hidden');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initUnifiedSearch);
})();

// Navigate to search route helper
document.addEventListener('DOMContentLoaded', function () {
    const desktopInput = document.getElementById('unified-search-input');
    const mobileInput = document.getElementById('unified-search-input-mobile');
    if (!desktopInput && !mobileInput) return;

    const searchUrl = desktopInput?.dataset.searchUrl || mobileInput?.dataset.searchUrl || '/search';

    function buildSearchUrl(inputId, jurusanId, keahlianId, angkatanId) {
        const q        = document.getElementById(inputId)?.value?.trim() || '';
        const jurusan  = document.getElementById(jurusanId)?.value  || '';
        const keahlian = document.getElementById(keahlianId)?.value || '';
        const angkatan = document.getElementById(angkatanId)?.value || '';
        const params   = new URLSearchParams();
        if (q)        params.set('q', q);
        if (jurusan)  params.set('jurusan', jurusan);
        if (keahlian) params.set('keahlian', keahlian);
        if (angkatan) params.set('angkatan', angkatan);
        return `${searchUrl}${params.toString() ? '?' + params.toString() : ''}`;
    }

    // Desktop filter button
    const filterBtn = document.getElementById('filter-search-btn');
    if (filterBtn) {
        filterBtn.addEventListener('click', function () {
            window.location.href = buildSearchUrl(
                'unified-search-input',
                'filter-jurusan',
                'filter-keahlian',
                'filter-angkatan'
            );
        });
    }

    // Also allow pressing Enter on the unified search input
    const unifiedInput = document.getElementById('unified-search-input');
    if (unifiedInput) {
        unifiedInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                window.location.href = buildSearchUrl(
                    'unified-search-input',
                    'filter-jurusan',
                    'filter-keahlian',
                    'filter-angkatan'
                );
            }
        });
    }

    // Mobile filter button
    const filterBtnMobile = document.getElementById('filter-search-btn-mobile');
    if (filterBtnMobile) {
        filterBtnMobile.addEventListener('click', function () {
            window.location.href = buildSearchUrl(
                'unified-search-input-mobile',
                'filter-jurusan-mobile',
                'filter-keahlian-mobile',
                'filter-angkatan-mobile'
            );
        });
    }

    // Mobile Enter key
    const mobInput = document.getElementById('unified-search-input-mobile');
    if (mobInput) {
        mobInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                window.location.href = buildSearchUrl(
                    'unified-search-input-mobile',
                    'filter-jurusan-mobile',
                    'filter-keahlian-mobile',
                    'filter-angkatan-mobile'
                );
            }
        });
    }
});

// Notification Bell Alpine Component
window.notificationBell = function(data) {
    const currentLocale = document.documentElement.lang || 'id';

    return {
        userId: data.userId,
        userRole: data.userRole,
        isOpen: false,
        notifications: [],
        unreadCount: 0,
        page: 1,
        pollingInterval: null,
        isLoading: false,

        filterNotifications(notifications) {
            if (!notifications || !Array.isArray(notifications)) return [];
            return notifications.filter(item => {
                if (item.read === 1 || item.read === true) return false;
                const notifData = item.data || {};
                const selected  = notifData.selected_users;
                if (notifData.target_type === 'specific') {
                    if (!selected) return false;
                    if (Array.isArray(selected)) return selected.map(Number).includes(Number(this.userId));
                    if (typeof selected === 'string' && selected.startsWith('[')) {
                        try { const p = JSON.parse(selected); return Array.isArray(p) ? p.map(Number).includes(Number(this.userId)) : false; } catch(e) {}
                    }
                    return Number(selected) === Number(this.userId);
                }
                if (notifData.target_type === 'all') return true;
                if (notifData.target_role) return notifData.target_role === this.userRole;
                if (!notifData.admin_id && !notifData.sender_id) return this.userRole === 'admin';
                return false;
            });
        },

        async init() {
            await this.loadNotifications();
            this.startPolling();
            if (Notification.permission === 'default') Notification.requestPermission();
        },

        getCurrentNotificationIds() { return this.notifications.map(n => n.id); },
        getUnreadNotificationIds()  { return this.notifications.filter(n => !n.read).map(n => n.id); },

        getIconBg(type) {
            const colors = {
                'user-registered':      'bg-gradient-to-br from-blue-500 to-indigo-600',
                'project-created':      'bg-gradient-to-br from-green-500 to-emerald-600',
                'certificate-uploaded': 'bg-gradient-to-br from-purple-500 to-pink-600',
            };
            return colors[type] || 'bg-gradient-to-br from-gray-500 to-gray-600';
        },

        formatTime(timestamp) {
            if (!timestamp) return '';
            const date = new Date(timestamp);
            const now  = new Date();
            const diff = Math.floor((now - date) / 1000);
            if (diff < 60)    return 'Baru saja';
            if (diff < 3600)  return Math.floor(diff / 60) + ' menit lalu';
            if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        },

        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen && this.unreadCount > 0) this.loadNotifications();
        },

        startPolling() {
            if (this.pollingInterval) clearInterval(this.pollingInterval);
            this.pollingInterval = setInterval(async () => {
                try {
                    const res  = await fetch(`/${currentLocale}/api/notifications/unread-count`);
                    const data = await res.json();
                    if (data.count !== this.unreadCount) await this.loadNotifications();
                } catch (e) { console.error('Polling error:', e); }
            }, 10000);
        },

        async loadNotifications() {
            if (this.isLoading) return;
            this.isLoading = true;
            try {
                const res  = await fetch(`/${currentLocale}/api/notifications?page=${this.page}`);
                const data = await res.json();
                this.notifications = this.filterNotifications(data.data || []);
                this.updateUnreadCount();
            } catch (e) { console.error('Load notifications error:', e); }
            finally { this.isLoading = false; }
        },

        async handleNotificationClick(item) {
            if (!item.read) await this.markAsRead(item.id);
            const locale = document.documentElement.lang || 'id';
            if (item.data.link) {
                window.location.href = item.data.link;
            } else {
                const routes = {
                    'user-registered':      `/${locale}/admin/manageUser`,
                    'project-created':      `/${locale}/admin/manageProject`,
                    'certificate-uploaded': `/${locale}/admin/manageSertifikat`,
                };
                window.location.href = routes[item.type] || `/${locale}/dashboard`;
            }
        },

        async markAsRead(id) {
            try {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const res  = await fetch(`/${currentLocale}/api/notifications/mark-read?id=${id}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    credentials: 'same-origin'
                });
                const result = await res.json();
                if (result.success) await this.loadNotifications();
            } catch(e) { console.error('Mark as read error:', e); }
        },

        async markAllAsRead() {
            const ids = this.getUnreadNotificationIds();
            if (!ids.length) { this.showToast('Tidak ada notifikasi yang belum dibaca', 'info'); return; }
            try {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const res  = await fetch(`/${currentLocale}/api/notifications/mark-all-read`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    credentials: 'same-origin',
                    body: JSON.stringify({ notification_ids: ids })
                });
                const result = await res.json();
                if (result.success) { await this.loadNotifications(); this.showToast(result.message || 'Semua notifikasi telah ditandai dibaca', 'success'); }
                else this.showToast('Gagal menandai notifikasi', 'error');
            } catch(e) { this.showToast('Gagal menandai notifikasi. Silakan coba lagi.', 'error'); }
        },

        async clearAll() {
            const ids = this.getCurrentNotificationIds();
            if (!ids.length) { this.showToast('Tidak ada notifikasi yang dapat dihapus', 'info'); return; }
            if (!confirm(`Apakah Anda yakin ingin menghapus ${ids.length} notifikasi?`)) return;
            try {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const res  = await fetch(`/${currentLocale}/api/notifications/clear-all`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    credentials: 'same-origin',
                    body: JSON.stringify({ notification_ids: ids })
                });
                const result = await res.json();
                if (result.success) { await this.loadNotifications(); this.showToast(result.message || 'Notifikasi telah dihapus', 'success'); }
                else this.showToast('Gagal menghapus notifikasi', 'error');
            } catch(e) { this.showToast('Gagal menghapus notifikasi. Silakan coba lagi.', 'error'); }
        },

        updateUnreadCount() { this.unreadCount = this.notifications.filter(n => !n.read).length; },

        showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg text-sm ${
                type === 'success' ? 'bg-green-600' : (type === 'error' ? 'bg-red-600' : 'bg-gray-800')
            } text-white`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    };
};

// Placeholder typing effect
(function () {
    function getPlaceholderTexts() {
        const lang  = localStorage.getItem('lang') || 'id';
        const texts = {
            id: ['Cari Mahasiswa...', 'Cari Portofolio...', 'Cari Sertifikat...', 'Cari Postingan...'],
            en: ['Search Students...', 'Search Portfolio...', 'Search Certificate...', 'Search Posts...']
        };
        return texts[lang] || texts['id'];
    }

    const getInputs = () => [
        document.getElementById('unified-search-input'),
        document.getElementById('unified-search-input-mobile')
    ].filter(Boolean);

    let texts = getPlaceholderTexts();
    let textIndex = 0, charIndex = 0, isDeleting = false, speed = 80;

    function typeEffect() {
        const currentText = texts[textIndex];
        getInputs().forEach(input => {
            if (!input.value && document.activeElement !== input) {
                input.setAttribute('placeholder', currentText.substring(0, charIndex));
            }
        });
        if (!isDeleting) {
            charIndex++;
            if (charIndex > currentText.length) { isDeleting = true; setTimeout(typeEffect, 1500); return; }
            speed = 60 + Math.random() * 40;
        } else {
            charIndex--;
            if (charIndex === 0) { isDeleting = false; textIndex = (textIndex + 1) % texts.length; texts = getPlaceholderTexts(); }
            speed = 30 + Math.random() * 30;
        }
        setTimeout(typeEffect, speed);
    }

    document.addEventListener('DOMContentLoaded', typeEffect);
})();

// Outside click triggers suggestions box hide
document.addEventListener('click', function(e) {
    if (!e.target.closest('#search-input') && !e.target.closest('#search-suggestions') && 
        !e.target.closest('#search-input-mobile') && !e.target.closest('#search-suggestions-mobile')) {
        document.querySelectorAll('#search-suggestions, #search-suggestions-mobile').forEach(box => box.classList.add('hidden'));
    }
});

/* ==========================================
   COMPONENT: CHAT BOT
   ========================================== */
document.addEventListener('DOMContentLoaded', function () {
    const chatButton = document.getElementById('chatBotButton');
    const chatWidget = document.getElementById('chatWidget');
    const closeChatBtn = document.getElementById('closeChatWidget');
    const chatMessagesContainer = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const sendBtn = document.getElementById('sendMessageBtn');
    const typingIndicator = document.getElementById('typingIndicator');
    const quickQuestionsContainer = document.getElementById('quickQuestionsContainer');
    const suggestionsContainer = document.getElementById('suggestionsContainer');
    const suggestionsList = document.getElementById('suggestionsList');

    // Bubble elements
    const notificationBubble = document.getElementById('notificationBubble');
    const closeBubbleBtn = document.getElementById('closeBubbleBtn');

    if (!chatButton) return; // Only run if chatbot elements exist on current page

    let isChatOpen = false;
    let isTyping = false;
    let suggestionTimeout = null;

    // ============ DRAG TO MOVE CHAT BUTTON ============
    let isDragging = false;
    let dragStartX, dragStartY;
    let buttonStartLeft, buttonStartTop;
    let dragDistance = 0;

    // Get saved position from localStorage
    function loadButtonPosition() {
        const savedPos = localStorage.getItem('chatButtonPosition');
        if (savedPos) {
            try {
                const pos = JSON.parse(savedPos);
                chatButton.style.left = pos.left;
                chatButton.style.top = pos.top;
                chatButton.style.right = 'auto';
                chatButton.style.bottom = 'auto';
            } catch (e) { }
        }
    }

    function saveButtonPosition(left, top) {
        localStorage.setItem('chatButtonPosition', JSON.stringify({ left, top }));
    }

    function onMouseDown(e) {
        if (e.target.closest('svg') && !isChatOpen) {
            return;
        }

        isDragging = false;
        dragDistance = 0;
        dragStartX = e.clientX;
        dragStartY = e.clientY;

        const rect = chatButton.getBoundingClientRect();
        buttonStartLeft = rect.left;
        buttonStartTop = rect.top;

        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', onMouseUp);

        chatButton.style.cursor = 'grabbing';
        e.preventDefault();
    }

    function onMouseMove(e) {
        const dx = e.clientX - dragStartX;
        const dy = e.clientY - dragStartY;
        dragDistance = Math.sqrt(dx * dx + dy * dy);

        if (dragDistance > 5) {
            isDragging = true;
            chatButton.classList.add('dragging');

            let newLeft = buttonStartLeft + dx;
            let newTop = buttonStartTop + dy;

            const maxX = window.innerWidth - chatButton.offsetWidth - 16;
            const maxY = window.innerHeight - chatButton.offsetHeight - 16;
            newLeft = Math.min(Math.max(8, newLeft), maxX);
            newTop = Math.min(Math.max(8, newTop), maxY);

            chatButton.style.left = newLeft + 'px';
            chatButton.style.top = newTop + 'px';
            chatButton.style.right = 'auto';
            chatButton.style.bottom = 'auto';
        }
    }

    function onMouseUp(e) {
        document.removeEventListener('mousemove', onMouseMove);
        document.removeEventListener('mouseup', onMouseUp);
        chatButton.style.cursor = 'grab';
        chatButton.classList.remove('dragging');

        if (isDragging && dragDistance > 5) {
            saveButtonPosition(chatButton.style.left, chatButton.style.top);
            e.stopPropagation();
        }

        isDragging = false;
        dragDistance = 0;
    }

    function initDrag() {
        loadButtonPosition();
        chatButton.style.cursor = 'grab';
        chatButton.addEventListener('mousedown', onMouseDown);
        chatButton.addEventListener('touchstart', onTouchStart, { passive: false });
        chatButton.addEventListener('touchmove', onTouchMove, { passive: false });
        chatButton.addEventListener('touchend', onTouchEnd);
    }

    function onTouchStart(e) {
        if (e.target.closest('svg') && !isChatOpen) return;

        isDragging = false;
        dragDistance = 0;
        const touch = e.touches[0];
        dragStartX = touch.clientX;
        dragStartY = touch.clientY;

        const rect = chatButton.getBoundingClientRect();
        buttonStartLeft = rect.left;
        buttonStartTop = rect.top;

        e.preventDefault();
    }

    function onTouchMove(e) {
        const touch = e.touches[0];
        const dx = touch.clientX - dragStartX;
        const dy = touch.clientY - dragStartY;
        dragDistance = Math.sqrt(dx * dx + dy * dy);

        if (dragDistance > 5) {
            isDragging = true;
            chatButton.classList.add('dragging');

            let newLeft = buttonStartLeft + dx;
            let newTop = buttonStartTop + dy;

            const maxX = window.innerWidth - chatButton.offsetWidth - 16;
            const maxY = window.innerHeight - chatButton.offsetHeight - 16;
            newLeft = Math.min(Math.max(8, newLeft), maxX);
            newTop = Math.min(Math.max(8, newTop), maxY);

            chatButton.style.left = newLeft + 'px';
            chatButton.style.top = newTop + 'px';
            chatButton.style.right = 'auto';
            chatButton.style.bottom = 'auto';

            e.preventDefault();
        }
    }

    function onTouchEnd(e) {
        chatButton.classList.remove('dragging');

        if (isDragging && dragDistance > 5) {
            saveButtonPosition(chatButton.style.left, chatButton.style.top);
            e.preventDefault();
        } else {
            if (isChatOpen) {
                closeChat();
            } else {
                if (notificationBubble) {
                    notificationBubble.style.display = 'none';
                }
                openChat();
            }
        }

        isDragging = false;
        dragDistance = 0;
    }

    // ============ BUBBLE LOGIC - PERMANENT HIDE ============
    let bubblePermanentlyClosed = localStorage.getItem('bubblePermanentlyClosed') === 'true';

    function permanentlyCloseBubble() {
        localStorage.setItem('bubblePermanentlyClosed', 'true');
        bubblePermanentlyClosed = true;

        if (notificationBubble) {
            notificationBubble.style.display = 'none';
            notificationBubble.style.visibility = 'hidden';
        }
    }

    function showBubble() {
        if (bubblePermanentlyClosed) {
            if (notificationBubble) {
                notificationBubble.style.display = 'none';
                notificationBubble.style.visibility = 'hidden';
            }
            return;
        }

        if (notificationBubble) {
            notificationBubble.style.display = 'flex';
            notificationBubble.style.visibility = 'visible';

            setTimeout(() => {
                if (notificationBubble && notificationBubble.style.display === 'flex') {
                    notificationBubble.style.display = 'none';
                }
            }, 8000);
        }
    }

    if (closeBubbleBtn) {
        closeBubbleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            e.preventDefault();
            permanentlyCloseBubble();
        });
    }

    if (notificationBubble) {
        notificationBubble.addEventListener('click', function (e) {
            if (e.target === closeBubbleBtn || closeBubbleBtn.contains(e.target)) {
                return;
            }

            if (notificationBubble) {
                notificationBubble.style.display = 'none';
            }

            openChat();
        });
    }

    // ============ FAQ DATABASE ============
    const faqDatabase = {
        'apa website ini': 'Website ini adalah platform portofolio digital untuk mahasiswa POLMIND (Politeknik Mitra Indonesia). Mahasiswa dapat menampilkan proyek, sertifikat, dan keterampilan mereka kepada publik dan calon employer.',
        'apa itu website ini': 'Website ini adalah platform portofolio mahasiswa POLMIND. Di sini mahasiswa bisa memamerkan karya, proyek, dan sertifikat mereka.',
        'fungsi website': 'Website ini berfungsi sebagai platform portofolio digital untuk mahasiswa POLMIND, helping them tampilkan kompetensi dan pengalaman kepada dunia profesional.',
        'tentang polmind': 'POLMIND (Politeknik Mitra Indonesia) adalah institusi pendidikan vokasi yang berlokasi di Kawasan Industri MM2100, Cikarang Barat. POLMIND fokus pada pendidikan berbasis industri dengan program Teaching Factory.',
        'apa itu polmind': 'POLMIND adalah Politeknik Mitra Indonesia, kampus vokasi yang mengedepankan pembelajaran berbasis industri dan Teaching Factory (TeFa).',
        'visi misi polmind': 'POLMIND memiliki visi menjadi politeknik unggul berbasis industri manufaktur. Misi: menyelenggarakan pendidikan vokasi berkualitas, mengembangkan penelitian terapan, dan membangun kemitraan dengan industri.',
        'sejarah polmind': 'POLMIND didirikan untuk memenuhi kebutuhan tenaga kerja terampil di sektor manufaktur Indonesia. Kampus berlokasi strategis di Kawasan Industri MM2100, pusat industri manufaktur nasional.',
        'program studi polmind': 'POLMIND memiliki program studi unggulan: Teknologi Rekayasa Perangkat Lunak,Teknologi Rekayasa Manufaktur, Bisnis Digital.',
        'prodi polmind': 'Program studi di POLMIND: D4 Teknologi Rekayasa Perangkat Lunak, D4 Teknologi Rekayasa Manufaktur, D4 Bisnis Digital.',
        'fasilitas polmind': 'Fasilitas POLMIND: Teaching Factory, Lab Komputer, Perpustakaan Ruang Kelas dan area parkir luas.',
        'fasilitas kampus': 'Kampus POLMIND dilengkapi Teaching Factory, Lab Komputer, Perpustakaan Ruang Kelas dan area parkir luas.',
        'beasiswa polmind': 'POLMIND menyediakan beasiswa: Beasiswa Prestasi Akademik, Beasiswa Tidak Mampu, Beasiswa Mitra Industri, dan Beasiswa Pemerintah (KIP Kuliah).',
        'beasiswa': 'Tersedia beasiswa prestasi, beasiswa tidak mampu, beasiswa mitra industri, dan KIP Kuliah di POLMIND.',
        'kerjasama industri polmind': 'POLMIND bekerjasama dengan ratusan perusahaan manufaktur nasional dan multinasional di Kawasan MM2100 dan sekitarnya untuk program magang, TeFa, dan penempatan kerja.',
        'mitra polmind': 'POLMIND bermitra dengan perusahaan-perusahaan di Kawasan Industri MM2100 seperti Daihatsu, Epson, Denso dan lainnya',
        'prestasi polmind': 'POLMIND telah meraih berbagai prestasi di tingkat nasional dalam kompetisi robotik, inovasi manufaktur, dan karya ilmiah mahasiswa.',
        'cara mendaftar': 'Untuk mendaftar: 1) Klik "Login" di halaman utama, 2) Klik Ajukan Akun di halaman Login), 3) Isi formulir dengan data lengkap (nim, nama, email, password), 4). Mengajukan Akun Ke Admin, Tunggu Admin Menerima Pengajuan Akun 5) Login dengan akun Anda yang telah di setujui oleh Admin.',
        'pendaftaran': 'Proses pendaftaran: Klik Daftar > Isi formulir > Ajukan akun ke Admin > Login.',
        'register': 'Anda dapat mendaftar dengan mengklik tombol "Daftar" di halaman Login. Isi data diri anda.',
        'buat akun': 'Buat akun dengan klik "Daftar", isi nim, nama, email, password, dan ajukan akun ke admin dengan klik mendaftar.',
        'syarat pendaftaran': 'Syarat pendaftaran: Warga Negara Indonesia, lulusan SMA/SMK sederajat, memiliki email aktif, dan mengisi formulir dengan data benar.',
        'lupa password': 'Jika lupa password: 1) Klik "Lupa Password" di halaman login, 2) Masukkan email terdaftar, 3) Cek email untuk melihat kode OTP, 4) Masukkan kode OTP dan buat password baru.',
        'reset password': 'Reset password melalui fitur "Lupa Password" di halaman login. Kode OTP akan dikirim ke email Anda.',
        'ganti password': 'Anda dapat mengganti password melalui "Lupa Password" di halaman login',
        'cara mengubah profil': 'Untuk mengubah profil: 1) Login ke akun, 2) Buka menu "Profil" , 3) Akan Ada Sebuah Halaman Profil, Klik salah satu Yang ingin Di Ubah, 4) Ubah foto, bio, atau informasi kontak, 5) Simpan perubahan.',
        'edit profil': 'Edit profil dapat dilakukan melalui menu Profil > Edit Profil. Anda bisa mengubah foto, bio, dan informasi kontak.',
        'foto profil': 'Foto profil dapat diubah di halaman Edit Profil. Upload foto dengan format JPG, PNG, atau GIF (maks 5MB).',
        'menghubungi admin': 'Hubungi admin melalui:\n📧 Email: info@polmind.ac.id\n📱 WhatsApp: +62 821-1329-6897\n📍 Alamat: Kawasan Industri MM2100, Cikarang Barat\n🕐 Jam operasional: Senin-Jumat, 08:00-16:00 WIB',
        'kontak admin': '📧 info@polmind.ac.id | 📱 +62 821-1329-6897',
        'email admin': 'Email admin: info@polmind.ac.id',
        'wa admin': 'WhatsApp admin: +62 821-1329-6897',
        'alamat kampus': 'Kampus POLMIND berlokasi di Kawasan Industri MM2100, Cikarang Barat, Bekasi, Jawa Barat.',
        'lokasi polmind': 'POLMIND terletak di Jalan Kalimantan Blok CB-2, Kawasan Industri MM2100, Cikarang Barat, Kabupaten Bekasi, Jawa Barat.',
        'apa itu portofolio': 'Portofolio adalah kumpulan karya, proyek, dan pencapaian yang menunjukkan kemampuan dan pengalaman seseorang. Di website ini, mahasiswa dapat membangun portofolio digital profesional.',
        'portofolio': 'Portofolio digital adalah showcase karya dan proyek Anda. Ini membantu calon employer melihat kompetensi Anda secara nyata.',
        'pentingnya portofolio': 'Portofolio penting karena: 1) Bukti nyata kemampuan, 2) Meningkatkan peluang karir, 3) Personal branding, 4) Membedakan Anda dari kandidat lain.',
        'cara menambahkan portofolio': 'Menambah portofolio: 1) Login ke akun, 2) Buka menu "Portofolio", 3) Pilih "Tambah Proyek" atau "Tambah Sertifikat", 4) Isi judul, deskripsi, tanggal, 5) Upload file pendukung, 6) Simpan.',
        'tambah portofolio': '1) Login > Portofolio > Tambah Proyek, 2) Isi informasi proyek, 3) Upload file, 4) Simpan.',
        'cara upload proyek': 'Buka menu Portofolio > Tambah Proyek. Isi nama proyek, deskripsi, link (opsional), dan Mahasiswa Lain Yang ikut terlibat, Kemudian Upload Proyek',
        'cara menambah sertifikat': 'Menambah sertifikat: 1) Buka menu "Sertifikat", 2) Klik "Tambah Sertifikat", 3) Upload file sertifikat (PDF/JPG), 4) Isi nama sertifikat dan penerbit, 5) Simpan.',
        'upload sertifikat': 'Upload sertifikat melalui menu Sertifikat > Tambah Sertifikat. File yang didukung: PDF, JPG, PNG.',
        'apa itu tefa': 'Teaching Factory (TeFa) adalah model pembelajaran di POLMIND di mana mahasiswa mengerjakan proyek nyata dari industri. Mahasiswa mendapatkan pengalaman kerja aktual sambil belajar.',
        'tefa': 'Teaching Factory - sistem pembelajaran berbasis proyek industri nyata. Mahasiswa POLMIND belajar sambil mengerjakan proyek dari mitra industri.',
        'teaching factory': 'Teaching Factory adalah program unggulan POLMIND yang mengintegrasikan pembelajaran dengan proyek industri nyata.',
        'sistem tefa': 'Dalam sistem TeFa, mahasiswa mengerjakan proyek dari perusahaan mitra, mendapatkan pengalaman kerja, dan hasilnya masuk dalam portofolio.',
        'manfaat tefa': 'Manfaat TeFa: pengalaman industri nyata, portofolio profesional, jaringan dengan perusahaan, dan kesiapan kerja lebih tinggi.',
        'apa itu learning corner': 'Learning Corner adalah fitur di mana mahasiswa dapat berbagi materi pembelajaran, tutorial, atau artikel yang bermanfaat untuk mahasiswa lain.',
        'learning corner': 'Learning Corner adalah ruang berbagi pengetahuan antar mahasiswa. Anda bisa memposting tutorial, tips, atau materi pembelajaran.',
        'magang polmind': 'POLMIND memfasilitasi magang di perusahaan mitra di Kawasan Industri MM2100. Mahasiswa dapat mengajukan magang melalui program TeFa atau kerjasama industri.',
        'info magang': 'Informasi magang dapat diperoleh dari bagian kemahasiswaan atau dosen pembimbing. POLMIND memiliki banyak mitra industri untuk program magang.',
        'karir alumni': 'Alumni POLMIND banyak bekerja di perusahaan manufaktur nasional dan multinasional. Tersedia pusat karir yang membantu penyaluran kerja lulusan.',
        'kerja setelah lulus': 'Lulusan POLMIND memiliki tingkat terserap kerja tinggi (>90%) karena kompetensi yang sesuai kebutuhan industri.',
        'jam operasional': 'Jam operasional admin: Senin-Jumat, pukul 08:00 - 17:00 WIB. Minggu, dan hari libur nasional tutup.',
        'jam kerja': 'Admin tersedia Senin-Sabtu, 08:00-17:00 WIB.',
        'help': 'Saya bisa membantu dengan pertanyaan seputar: POLMIND, Pendaftaran, Portofolio, Sertifikat, TeFa, Magang, Beasiswa, dan Kontak. Ada yang bisa saya bantu?',
        'bantuan': 'Saya asisten Help Center POLMIND. Saya bisa menjawab pertanyaan tentang kampus, pendaftaran, portofolio, dan lainnya. Silakan bertanya!',
        'halo': 'Halo! 👋 Ada yang bisa saya bantu? Silakan pilih pertanyaan di atas atau ketik pertanyaan Anda.',
        'hai': 'Hai! Selamat datang di Help Center POLMIND. Ada yang ingin ditanyakan?',
        'terima kasih': 'Sama-sama! Senang bisa membantu Anda 😊 Jika ada pertanyaan lain, jangan ragu untuk bertanya lagi ya!',
        'makasih': 'Sama-sama! Senang bisa membantu 😊',
        'bye': 'Sampai jumpa! 👋 Kembali lagi jika ada pertanyaan lain. Semoga harimu menyenangkan!',
        'dimana' : 'Polmind Berada Di MM2100, No. S85, Vasanta Innopark'
    };

    const quickQuestionsPool = [
        { question: 'Apa itu POLMIND?', keywords: ['apa', 'polmind', 'politeknik', 'kampus'] },
        { question: 'Program studi apa saja?', keywords: ['prodi', 'program', 'studi', 'jurusan'] },
        { question: 'Cara mendaftar?', keywords: ['daftar', 'register', 'pendaftaran'] },
        { question: 'Apa itu TeFa?', keywords: ['tefa', 'teaching', 'factory'] },
        { question: 'Beasiswa POLMIND?', keywords: ['beasiswa', 'biaya', 'kuliah'] },
        { question: 'Lokasi kampus?', keywords: ['lokasi', 'alamat', 'dimana'] },
        { question: 'Cara menambah portofolio?', keywords: ['portofolio', 'tambah', 'upload', 'proyek'] },
        { question: 'Fasilitas POLMIND?', keywords: ['fasilitas', 'lab', 'kampus'] },
        { question: 'Magang di POLMIND?', keywords: ['magang', 'internship', 'kerja'] },
        { question: 'Kontak admin?', keywords: ['admin', 'kontak', 'hubungi', 'email', 'wa'] },
        { question: 'Dimana Lokasinya?', keywords: ['lokasi', 'letak', 'dimana'] },
    ];

    function getDailyQuestions() {
        const today = new Date();
        const dayOfYear = Math.floor((today - new Date(today.getFullYear(), 0, 0)) / (1000 * 60 * 60 * 24));
        const shuffled = [...quickQuestionsPool];

        for (let i = shuffled.length - 1; i > 0; i--) {
            const j = (dayOfYear + i) % shuffled.length;
            [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
        }

        return shuffled.slice(0, 4);
    }

    function renderQuickQuestions() {
        const dailyQuestions = getDailyQuestions();
        quickQuestionsContainer.innerHTML = '';

        dailyQuestions.forEach(item => {
            const btn = document.createElement('button');
            btn.className = 'quick-question text-[10px] sm:text-xs bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 sm:px-2.5 py-1 sm:py-1.5 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900/50 hover:text-blue-600 dark:hover:text-blue-400 transition-all';
            btn.textContent = item.question;
            quickQuestionsContainer.appendChild(btn);
        });
    }

    function findBestMatch(input) {
        const lowerInput = input.toLowerCase().trim();
        let bestMatch = null;
        let bestScore = 0;

        for (const [key, answer] of Object.entries(faqDatabase)) {
            const keywords = key.split(' ');
            let score = 0;

            for (const word of keywords) {
                if (lowerInput.includes(word)) score += word.length;
            }

            if (lowerInput.includes(key)) score += key.length * 2;

            if (score > bestScore) {
                bestScore = score;
                bestMatch = answer;
            }
        }

        return bestMatch;
    }

    function getSuggestions(input) {
        const lowerInput = input.toLowerCase().trim();
        if (lowerInput.length < 2) return [];

        const suggestions = [];

        for (const item of quickQuestionsPool) {
            for (const keyword of item.keywords) {
                if (lowerInput.includes(keyword) || keyword.includes(lowerInput)) {
                    if (!suggestions.includes(item.question)) {
                        suggestions.push(item.question);
                    }
                    break;
                }
            }
        }

        for (const key of Object.keys(faqDatabase)) {
            if (key.includes(lowerInput) && !suggestions.includes(key)) {
                suggestions.push(key);
            }
        }

        return suggestions.slice(0, 5);
    }

    function showSuggestions(suggestions) {
        if (suggestions.length === 0) {
            suggestionsContainer.classList.add('hidden');
            return;
        }

        suggestionsContainer.classList.remove('hidden');
        suggestionsList.innerHTML = '';

        suggestions.forEach(suggestion => {
            const btn = document.createElement('button');
            btn.className = 'suggestion-item text-[10px] sm:text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 sm:px-2.5 py-1 sm:py-1.5 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900/50 hover:text-blue-600 dark:hover:text-blue-400 transition-all';
            btn.textContent = suggestion.length > 25 ? suggestion.substring(0, 25) + '...' : suggestion;
            btn.addEventListener('click', () => {
                const question = suggestion;
                chatInput.value = '';
                suggestionsContainer.classList.add('hidden');
                handleSendMessage(question);
            });
            suggestionsList.appendChild(btn);
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function (m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    function addMessage(content, isUser = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex items-start space-x-2 ${isUser ? 'flex-row-reverse space-x-reverse' : ''} message-slide-in`;
        messageDiv.style.opacity = '0';

        if (isUser) {
            messageDiv.innerHTML = `
                <div class="flex-shrink-0 w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="flex-1 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl rounded-tr-none px-2.5 sm:px-3 py-1.5 sm:py-2 shadow-md">
                    <p class="text-xs sm:text-sm whitespace-pre-line">${escapeHtml(content)}</p>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="flex-shrink-0 w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <div class="flex-1 bg-white dark:bg-gray-800 rounded-2xl rounded-tl-none px-2.5 sm:px-3 py-1.5 sm:py-2 shadow-sm border border-gray-200 dark:border-gray-700">
                    <p class="text-xs sm:text-sm text-gray-800 dark:text-gray-100 whitespace-pre-line">${escapeHtml(content)}</p>
                </div>
            `;
        }

        chatMessagesContainer.appendChild(messageDiv);

        setTimeout(() => {
            messageDiv.style.opacity = '1';
        }, 10);

        chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
        return messageDiv;
    }

    function findAnswer(question) {
        const lowerQuestion = question.toLowerCase().trim();

        for (const [key, answer] of Object.entries(faqDatabase)) {
            if (lowerQuestion.includes(key) || key.includes(lowerQuestion)) {
                return answer;
            }
        }

        const bestMatch = findBestMatch(question);
        if (bestMatch) {
            return bestMatch;
        }

        return "Maaf, saya belum mengerti pertanyaan Anda. 😅\n\nCoba tanyakan hal seperti:\n• Apa itu POLMIND?\n• Program studi apa saja?\n• Cara mendaftar?\n• Apa itu TeFa?\n• Beasiswa POLMIND?\n• Lokasi kampus?\n\nAtau pilih pertanyaan di atas! 👆";
    }

    function sendBotReply(userText) {
        if (isTyping) return;

        isTyping = true;
        typingIndicator.classList.remove('hidden');
        chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;

        setTimeout(() => {
            typingIndicator.classList.add('hidden');
            const replyText = findAnswer(userText);
            addMessage(replyText, false);
            isTyping = false;
        }, 500 + Math.random() * 400);
    }

    function handleSendMessage(messageText = null) {
        const message = messageText || chatInput.value.trim();
        if (!message) return;

        addMessage(message, true);

        if (!messageText) {
            chatInput.value = '';
            suggestionsContainer.classList.add('hidden');
            chatInput.focus();
        }

        sendBotReply(message);
    }

    if (sendBtn) {
        sendBtn.addEventListener('click', () => handleSendMessage());
    }

    if (chatInput) {
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleSendMessage();
            }
        });

        chatInput.addEventListener('input', (e) => {
            clearTimeout(suggestionTimeout);
            suggestionTimeout = setTimeout(() => {
                const suggestions = getSuggestions(e.target.value);
                showSuggestions(suggestions);
            }, 200);
        });

        chatInput.addEventListener('blur', () => {
            setTimeout(() => suggestionsContainer.classList.add('hidden'), 200);
        });

        chatInput.addEventListener('focus', () => {
            const suggestions = getSuggestions(chatInput.value);
            if (suggestions.length > 0) {
                showSuggestions(suggestions);
            }
        });
    }

    if (quickQuestionsContainer) {
        quickQuestionsContainer.addEventListener('click', (e) => {
            const questionBtn = e.target.closest('.quick-question');
            if (questionBtn) {
                const question = questionBtn.textContent.trim();
                handleSendMessage(question);
            }
        });
    }

    function openChat() {
        if (!chatWidget) return;
        chatWidget.classList.remove('hidden');
        isChatOpen = true;
        chatButton.classList.add('chat-open');
        setTimeout(() => chatInput.focus(), 100);
    }

    function closeChat() {
        if (!chatWidget) return;
        chatWidget.classList.add('hidden');
        isChatOpen = false;
        chatButton.classList.remove('chat-open');
    }

    if (chatButton) {
        chatButton.addEventListener('click', (e) => {
            if ('ontouchstart' in window) return;
            if (isDragging && dragDistance > 5) {
                e.stopPropagation();
                return;
            }
            if (isChatOpen) {
                closeChat();
            } else {
                if (notificationBubble) {
                    notificationBubble.style.display = 'none';
                }
                openChat();
            }
        });
    }

    if (closeChatBtn) {
        closeChatBtn.addEventListener('click', closeChat);
    }

    document.addEventListener('click', (e) => {
        if (isChatOpen && chatWidget && chatButton) {
            if (!chatWidget.contains(e.target) && !chatButton.contains(e.target)) {
                closeChat();
            }
        }
    });

    if (chatWidget) {
        chatWidget.addEventListener('click', (e) => e.stopPropagation());
    }

    renderQuickQuestions();
    initDrag();

    setTimeout(() => {
        showBubble();
    }, 1000);
});

/* ==========================================
   COMPONENT: LAYOUT
   ========================================== */
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('toggle-sidebar');
    const hamburger = document.getElementById('sidebar-hamburger');
    const closeIcon = document.getElementById('sidebar-close');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const closeSidebarBtn = document.getElementById('close-sidebar');
    const toggleSearch = document.getElementById('toggle-search-mobile');
    const searchDrop = document.getElementById('mobile-search-dropdown');
    const toggleDesktopBtn = document.getElementById('toggle-desktop-sidebar');
    const toggleIcon = document.getElementById('toggleCollapseIcon');

    if (toggleDesktopBtn) {
        toggleDesktopBtn.addEventListener('click', () => {
            const isCollapsed = sidebar.classList.contains('lg:w-20');
            if (isCollapsed) {
                sidebar.classList.remove('lg:w-20');
                sidebar.classList.add('lg:w-62');
                toggleIcon.classList.remove('rotate-180');
                fetch('/toggle-sidebar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({ collapsed: false })
                });
            } else {
                sidebar.classList.remove('lg:w-62');
                sidebar.classList.add('lg:w-20');
                toggleIcon.classList.add('rotate-180');
                document.querySelectorAll('[x-data]').forEach(el => {
                    if (el.__x) el.__x.$data.open = false;
                });
                fetch('/toggle-sidebar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({ collapsed: true })
                });
            }
        });
    }

    if (toggleSearch && searchDrop) {
        toggleSearch.addEventListener('click', () => {
            const isHidden = searchDrop.classList.contains('hidden');
            if (isHidden) {
                searchDrop.classList.remove('hidden');
                searchDrop.style.maxHeight = '0px';
                searchDrop.style.opacity = '0';
                requestAnimationFrame(() => {
                    searchDrop.style.transition = 'max-height 0.3s ease, opacity 0.25s ease';
                    searchDrop.style.maxHeight = searchDrop.scrollHeight + 'px';
                    searchDrop.style.opacity = '1';
                });
            } else {
                searchDrop.style.transition = 'max-height 0.25s ease, opacity 0.2s ease';
                searchDrop.style.maxHeight = '0px';
                searchDrop.style.opacity = '0';
                setTimeout(() => searchDrop.classList.add('hidden'), 250);
            }
        });
    }

    document.addEventListener('click', (e) => {
        if (!searchDrop || !toggleSearch) return;
        if (!searchDrop.contains(e.target) && !toggleSearch.contains(e.target)) {
            if (!searchDrop.classList.contains('hidden')) {
                searchDrop.style.transition = 'max-height 0.25s ease, opacity 0.2s ease';
                searchDrop.style.maxHeight = '0px';
                searchDrop.style.opacity = '0';
                setTimeout(() => searchDrop.classList.add('hidden'), 250);
            }
        }
    });

    function openSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove('-translate-x-full');
        if (overlay) {
            overlay.classList.remove('hidden');
            overlay.classList.add('block');
        }
        document.body.style.overflow = 'hidden';
        if (hamburger) hamburger.classList.add('hidden');
        if (closeIcon) closeIcon.classList.remove('hidden');
    }

    function closeSidebar() {
        if (!sidebar) return;
        sidebar.classList.add('-translate-x-full');
        if (overlay) {
            overlay.classList.remove('block');
            overlay.classList.add('hidden');
        }
        document.body.style.overflow = '';
        if (hamburger) hamburger.classList.remove('hidden');
        if (closeIcon) closeIcon.classList.add('hidden');
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            if (sidebar?.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });
    }
    if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);

    window.toggleDropdown = function (menuId) {
        const menu = document.getElementById(menuId + 'Menu');
        const arrow = document.getElementById(menuId + 'Arrow');
        if (menu && arrow) {
            menu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }
    };

    window.toggleTheme = function () {
        const html = document.documentElement;
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    };
});

/* ==========================================
   COMPONENT: LEARNING CORNER
   ========================================== */
document.addEventListener('DOMContentLoaded', () => {
    // Page Info initialization
    const isLearningCornerPage = document.querySelector('[data-page-info="popup.user_create_learning_corner"]') ||
                                 document.querySelector('[data-page-info="popup.user_edit_learning_corner"]');
    if (!isLearningCornerPage) return;

    // Dynamic Items (Create & Edit)
    const itemsContainer = document.getElementById('items-container');
    const addItemBtn = document.getElementById('add-item');

    if (itemsContainer && addItemBtn) {
        let itemIndex = parseInt(itemsContainer.getAttribute('data-item-count') || '0', 10);
        const isCreatePage = itemsContainer.getAttribute('data-is-create') === 'true';

        function addItem() {
            const newItem = document.createElement('div');
            newItem.className = 'item bg-gray-50 border border-gray-200 dark:bg-gray-900 rounded-xl p-6 relative';
            newItem.dataset.index = itemIndex;

            if (isCreatePage) {
                newItem.innerHTML = `
                    <div class="flex justify-between items-start mb-4">
                        <select name="items[${itemIndex}][type]" class="type-select border dark:text-white border-gray-300 dark:bg-gray-400 rounded px-3 py-2 text-sm focus:border-indigo-500 outline-none w-44">
                            <option class="dark:text-white" value="text" data-translate="add_text" data-translate-page="msh_lrn_add">Teks tambahan</option>
                            <option class="dark:text-white" value="image" data-translate="add_img" data-translate-page="msh_lrn_add">Gambar</option>
                            <option class="dark:text-white" value="link" data-translate="add_link" data-translate-page="msh_lrn_add">Link / Referensi</option>
                        </select>
                        <button data-translate="del" data-translate-page="msh_lrn_add" type="button" class="remove-item text-red-500 hover:text-red-700 text-sm font-medium">
                            Hapus
                        </button>
                    </div>

                    <div class="content-area">
                        <input type="text" name="items[${itemIndex}][content]" class="text-input dark:placeholder:text-white w-full px-4 py-3 border border-gray-300 dark:bg-gray-400 rounded-lg focus:border-indigo-500 outline-none transition"
                               placeholder="Masukkan teks di sini...">

                        <div class="file-input hidden mt-2">
                            <input type="file" name="items[${itemIndex}][file]" accept="image/*"
                                   class="block w-full text-sm text-gray-500 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-xs text-gray-500">Maks 5MB • jpg, png, gif</p>
                        </div>

                        <input type="url" name="items[${itemIndex}][content]" class="link-input hidden w-full px-4 py-3 dark:placeholder:text-white dark:bg-gray-400 border border-gray-300 rounded-lg focus:border-indigo-500 outline-none transition"
                               placeholder="https://example.com">
                    </div>
                `;
                itemsContainer.appendChild(newItem);
                attachTypeListenerCreate(newItem);
            } else {
                newItem.innerHTML = `
                    <div class="flex justify-between items-start mb-4">
                        <select name="items[${itemIndex}][type]" class="type-select border border-gray-300 rounded px-3 py-2 text-sm focus:border-indigo-500 outline-none w-44">
                            <option value="text">Teks tambahan</option>
                            <option value="image">Gambar</option>
                            <option value="link">Link / Referensi</option>
                        </select>
                        <button type="button" class="remove-item text-red-500 hover:text-red-700 text-sm font-medium">
                            Hapus
                        </button>
                    </div>
                    <div class="content-area mt-3">
                        <input type="text" name="items[${itemIndex}][content]"
                               class="text-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 outline-none transition"
                               placeholder="Masukkan teks di sini...">
                    </div>
                `;
                itemsContainer.appendChild(newItem);
                attachTypeChangeListenerEdit(newItem);
                const firstInput = newItem.querySelector('input');
                if (firstInput) firstInput.focus();
            }

            itemIndex++;

            if (typeof window.refreshTranslations === 'function') {
                window.refreshTranslations();
            }
        }

        function attachTypeListenerCreate(itemElement) {
            const select = itemElement.querySelector('.type-select');
            const textInput = itemElement.querySelector('.text-input');
            const fileDiv = itemElement.querySelector('.file-input');
            const linkInput = itemElement.querySelector('.link-input');
            if (!select || !textInput || !fileDiv || !linkInput) return;

            function toggleFields() {
                const type = select.value;
                textInput.classList.toggle('hidden', type !== 'text');
                fileDiv.classList.toggle('hidden', type !== 'image');
                linkInput.classList.toggle('hidden', type !== 'link');

                textInput.disabled = type !== 'text';
                linkInput.disabled = type !== 'link';
                if (type === 'image') {
                    textInput.name = `items[${itemElement.dataset.index}][dummy]`;
                } else {
                    textInput.name = `items[${itemElement.dataset.index}][content]`;
                }
            }

            select.addEventListener('change', toggleFields);
            toggleFields();
        }

        function attachTypeChangeListenerEdit(itemElement) {
            const select = itemElement.querySelector('.type-select');
            if (!select) return;

            select.addEventListener('change', function () {
                const currentType = this.value;
                const contentArea = itemElement.querySelector('.content-area');
                const index = itemElement.dataset.index;
                if (!contentArea) return;

                if (currentType === 'image') {
                    contentArea.innerHTML = `
                        <label class="block text-sm text-gray-600 mb-1">Upload gambar baru (opsional):</label>
                        <input type="file" name="items[${index}][image_file]" accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <input type="hidden" name="items[${index}][content]" value="">
                    `;
                } else {
                    contentArea.innerHTML = `
                        <input type="text" name="items[${index}][content]"
                               class="text-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 outline-none transition"
                               placeholder="${currentType === 'link' ? 'https://...' : 'Masukkan teks di sini...'}">
                    `;
                }
            });
        }

        // Initialize existing items for Edit page
        if (!isCreatePage) {
            document.querySelectorAll('.item').forEach(item => {
                attachTypeChangeListenerEdit(item);
            });
        }

        // Add first item on Create page if empty
        if (isCreatePage && itemIndex === 0) {
            addItem();
        }

        // Add Item event listener
        addItemBtn.addEventListener('click', addItem);
    }

    // Global listener for remove items
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-item')) {
            const item = e.target.closest('.item');
            if (item) item.remove();
        }
    });

    // Delete confirmation (Index Page)
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

            if (typeof window.showConfirmAlert === 'function') {
                const confirmed = await window.showConfirmAlert({
                    title: 'Hapus Entri Learning Corner?',
                    text: 'Catatan ini akan dihapus permanen dan tidak bisa dikembalikan.',
                    icon: 'warning',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                });

                if (confirmed) {
                    if (typeof window.showLoading === 'function') {
                        window.showLoading('Menghapus catatan...');
                    }
                    this.closest('form').submit();
                }
            } else {
                if (confirm('Hapus Entri Learning Corner? Catatan ini akan dihapus permanen.')) {
                    this.closest('form').submit();
                }
            }
        });
    });
});

/* ==========================================
   COMPONENT: POSTINGAN
   ========================================== */
document.addEventListener('DOMContentLoaded', () => {
    const itemsContainer = document.getElementById('items-container');
    const addItemBtn = document.getElementById('add-item');

    const isPostinganPage = document.querySelector('[data-page-info="popup.semua_postingan"]') ||
                            document.querySelector('[data-page-info="popup.create_postingan"]') ||
                            document.querySelector('[data-page-info="popup.edit_postingan"]');

    if (isPostinganPage && itemsContainer && addItemBtn) {
        let itemIndex = parseInt(itemsContainer.getAttribute('data-item-count') || '0', 10);
        const isCreatePage = itemsContainer.getAttribute('data-is-create') === 'true';

        window.addItem = function() {
            const container = document.getElementById('items-container');
            const newItem = document.createElement('div');
            newItem.className = 'item p-5 bg-white dark:bg-gray-800 relative group';
            newItem.dataset.index = itemIndex;

            newItem.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center" id="icon-container-${itemIndex}">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <select name="items[${itemIndex}][type]" class="type-select px-3 py-1.5 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="image">Gambar</option>
                            <option value="link">Link</option>
                        </select>
                    </div>
                    <button type="button" class="remove-item w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 dark:hover:text-red-400 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 transition-all opacity-0 group-hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="content-area pl-11">
                    <div class="file-input">
                        <div class="relative border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:border-indigo-300 dark:hover:border-indigo-500 transition-colors" id="drop-area-${itemIndex}">
                            <input type="file" name="items[${itemIndex}][file]" accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 file-input-trigger"
                                data-index="${itemIndex}"
                                onchange="previewImage(this)">
                            <div class="text-center" id="upload-placeholder-${itemIndex}">
                                <svg class="mx-auto w-8 h-8 text-gray-400 dark:text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Klik atau drag & drop gambar</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Maks 5MB • jpg, png, gif, webp</p>
                            </div>
                            <div id="image-preview-${itemIndex}" class="hidden mt-2 flex justify-center"></div>
                        </div>
                    </div>

                    <div class="link-input hidden mt-3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 000-5.656l-4-4a4 4 0 00-5.656 5.656L6.343 9.17"></path>
                                </svg>
                            </div>
                            <input type="url" name="items[${itemIndex}][content]"
                                class="w-full pl-9 pr-3 py-2 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="https://example.com">
                        </div>
                    </div>
                </div>
            `;

            container.appendChild(newItem);
            attachTypeListener(newItem);
            itemIndex++;
        };

        window.previewImage = function(input) {
            const index = input.dataset.index;
            const previewContainer = document.getElementById(`image-preview-${index}`);
            const uploadPlaceholder = document.getElementById(`upload-placeholder-${index}`);

            if (!previewContainer) return;

            const file = input.files[0];

            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file maksimal 5MB');
                    input.value = '';
                    return;
                }

                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan jpg, jpeg, png, gif, atau webp');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.innerHTML = `
                        <div class="image-preview-container">
                            <img src="${e.target.result}" alt="Preview" class="max-h-48 rounded-lg shadow-md">
                            <span class="remove-preview" onclick="removePreview(this, ${index})" title="Hapus gambar">×</span>
                        </div>
                    `;
                    previewContainer.classList.remove('hidden');
                    if (uploadPlaceholder) uploadPlaceholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.innerHTML = '';
                previewContainer.classList.add('hidden');
                if (uploadPlaceholder) uploadPlaceholder.classList.remove('hidden');
            }
        };

        window.removePreview = function(btn, index) {
            const previewContainer = document.getElementById(`image-preview-${index}`);
            const uploadPlaceholder = document.getElementById(`upload-placeholder-${index}`);
            const fileInput = document.querySelector(`input[data-index="${index}"]`);
            const existingContentInput = btn.closest('.item')?.querySelector('.existing-content-value');

            if (fileInput) fileInput.value = '';
            if (existingContentInput) existingContentInput.value = '';
            if (previewContainer) {
                previewContainer.innerHTML = '';
                previewContainer.classList.add('hidden');
            }
            if (uploadPlaceholder) uploadPlaceholder.classList.remove('hidden');
        };

        window.attachTypeListener = function(itemElement) {
            const select = itemElement.querySelector('.type-select');
            const fileDiv = itemElement.querySelector('.file-input');
            const linkInput = itemElement.querySelector('.link-input');
            const iconContainer = itemElement.querySelector('[id^="icon-container"]');

            function toggleFields() {
                const type = select.value;

                if (iconContainer) {
                    if (type === 'image') {
                        iconContainer.innerHTML = `
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        `;
                    } else {
                        iconContainer.innerHTML = `
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 000-5.656l-4-4a4 4 0 00-5.656 5.656L6.343 9.17"></path>
                            </svg>
                        `;
                    }
                }

                if (fileDiv) fileDiv.classList.toggle('hidden', type !== 'image');
                if (linkInput) linkInput.classList.toggle('hidden', type !== 'link');

                if (type === 'image') {
                    const fileInput = fileDiv?.querySelector('input[type="file"]');
                    if (fileInput) fileInput.name = `items[${itemElement.dataset.index}][file]`;
                    const linkUrlInput = linkInput?.querySelector('input[type="url"]');
                    if (linkUrlInput) linkUrlInput.name = `items[${itemElement.dataset.index}][dummy]`;
                } else if (type === 'link') {
                    const fileInput = fileDiv?.querySelector('input[type="file"]');
                    if (fileInput) fileInput.name = `items[${itemElement.dataset.index}][dummy_file]`;
                    const linkUrlInput = linkInput?.querySelector('input[type="url"]');
                    if (linkUrlInput) linkUrlInput.name = `items[${itemElement.dataset.index}][content]`;
                }
            }

            select.addEventListener('change', toggleFields);
            toggleFields();
        };

        // Attach to existing server-rendered items
        document.querySelectorAll('.item').forEach(item => {
            attachTypeListener(item);
        });

        // Add first item on Create page
        if (isCreatePage && itemIndex === 0) {
            window.addItem();
        }

        addItemBtn.addEventListener('click', window.addItem);

        // ===== INISIALISASI TAMBAHAN UNTUK EDIT POSTINGAN =====
// Auto-resize textarea
const judul = document.getElementById('judul');
const deskripsi = document.getElementById('deskripsi');

if (judul) {
    judul.style.height = '';
    judul.style.height = judul.scrollHeight + 'px';
    judul.addEventListener('input', function() {
        this.style.height = '';
        this.style.height = this.scrollHeight + 'px';
    });
}

if (deskripsi) {
    deskripsi.style.height = '';
    deskripsi.style.height = deskripsi.scrollHeight + 'px';
    deskripsi.addEventListener('input', function() {
        this.style.height = '';
        this.style.height = this.scrollHeight + 'px';
    });
}

// Game toggle
const gameEnabledEl = document.getElementById('game_enabled');
if (gameEnabledEl) {
    const gameNameEl = document.getElementById('game_name');
    const gameThumbEl = document.getElementById('game_thumbnail');
    const thumbnailContainer = document.getElementById('thumbnail_container');
    const removeThumbBtn = document.getElementById('remove_thumbnail_btn');
    const removeThumbInput = document.getElementById('remove_thumbnail');

    function updateGameState() {
        const checked = gameEnabledEl.checked;
        if (gameNameEl) gameNameEl.disabled = !checked;
        if (gameThumbEl) gameThumbEl.disabled = !checked;
        if (thumbnailContainer) thumbnailContainer.style.display = checked ? 'block' : 'none';
        if (!checked && removeThumbInput) removeThumbInput.value = '1';
    }

    gameEnabledEl.addEventListener('change', updateGameState);

    if (removeThumbBtn) {
        removeThumbBtn.addEventListener('click', function() {
            const container = document.getElementById('current_thumbnail_container');
            if (container) container.remove();
            if (removeThumbInput) removeThumbInput.value = '1';
            if (gameThumbEl) gameThumbEl.value = '';
        });
    }
}
    }

    // Textarea auto-resize on load
    const judul = document.getElementById('judul');
    const deskripsi = document.getElementById('deskripsi');

    if (judul) {
        judul.style.height = '';
        judul.style.height = judul.scrollHeight + 'px';
        judul.addEventListener('input', function() {
            this.style.height = '';
            this.style.height = this.scrollHeight + 'px';
        });
    }

    if (deskripsi) {
        deskripsi.style.height = '';
        deskripsi.style.height = deskripsi.scrollHeight + 'px';
        deskripsi.addEventListener('input', function() {
            this.style.height = '';
            this.style.height = this.scrollHeight + 'px';
        });
    }

    // Game toggle
    const gameEnabledEl = document.getElementById('game_enabled');
    if (gameEnabledEl) {
        const gameNameEl = document.getElementById('game_name');
        const gameThumbEl = document.getElementById('game_thumbnail');
        const thumbnailContainer = document.getElementById('thumbnail_container');
        const removeThumbBtn = document.getElementById('remove_thumbnail_btn');
        const removeThumbInput = document.getElementById('remove_thumbnail');

        gameEnabledEl.addEventListener('change', function() {
            const checked = this.checked;
            if (gameNameEl) gameNameEl.disabled = !checked;
            if (gameThumbEl) gameThumbEl.disabled = !checked;
            if (thumbnailContainer) thumbnailContainer.style.display = checked ? 'block' : 'none';
            if (!checked && removeThumbInput) removeThumbInput.value = '1';
        });

        if (removeThumbBtn) {
            removeThumbBtn.addEventListener('click', function() {
                const container = document.getElementById('current_thumbnail_container');
                if (container) container.remove();
                if (removeThumbInput) removeThumbInput.value = '1';
                if (gameThumbEl) gameThumbEl.value = '';
            });
        }
    }

    // Remove postingan items globally
    document.addEventListener('click', (e) => {
        if (e.target.closest('.remove-item')) {
            const item = e.target.closest('.item');
            if (item) {
                item.style.opacity = '0';
                item.style.transform = 'translateY(-10px)';
                setTimeout(() => item.remove(), 150);
            }
        }
    });
});

/* ==========================================
   VIEWS_DETAIL_POSTINGAN.BLADE.PHP SCRIPTS
   ========================================== */

// ===================== GLOBAL CONFIG =====================
window.currentUserId   = null;
window.currentUserName = "";
window.currentUserPhoto = "";
window.locale = document.querySelector('html')?.getAttribute('lang') || 'id';
window.commentLastUpdated = {};

// ===================== HELPERS =====================
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

function getAvatarHtml(user, size = 'w-8 h-8', textSize = 'text-xs') {
    if (user && user.photo_profile && user.photo_profile !== 'null' && user.photo_profile !== '') {
        const photoPath = user.photo_profile.startsWith('http') ? user.photo_profile : `/storage/${user.photo_profile}`;
        return `<img src="${photoPath}" class="${size} rounded-full object-cover flex-shrink-0" onerror="this.src='https://ui-avatars.com/api/?background=6366f1&color=fff&size=100&name=${encodeURIComponent(user.nama_mahasiswa || 'U')}'">`;
    }
    const name = user?.nama_mahasiswa || window.currentUserName || 'User';
    const initial = name.charAt(0).toUpperCase();
    return `<div class="${size} rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                <span class="text-indigo-600 dark:text-indigo-400 ${textSize} font-semibold">${initial}</span>
            </div>`;
}

function getHeaders() {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfMeta ? csrfMeta.getAttribute('content') : '',
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    };
}

// ===================== LOAD COMMENTS =====================
window.loadComments = async function(postinganId) {
    const container = document.getElementById(`comments-container-${postinganId}`);
    if (!container) return;

    container.innerHTML = '<div class="text-center py-6 text-gray-400 text-sm"><div class="comment-loading inline-block mr-2"></div> Memuat komentar...</div>';

    try {
        const response = await fetch(`/${window.locale}/komentar?id_postingan=${postinganId}`);
        const data = await response.json();

        if (data && data.success === true) {
            window.commentLastUpdated[postinganId] = data.last_updated ?? null;

            let commentsArray = [];
            if (data.comments && Array.isArray(data.comments)) {
                commentsArray = data.comments;
            } else if (data.comments && data.comments.comments && Array.isArray(data.comments.comments)) {
                commentsArray = data.comments.comments;
            }

            if (commentsArray.length === 0) {
                const noCommentsText = (window.locale === 'id')
                    ? 'Belum ada komentar. Jadilah yang pertama!'
                    : 'No comments yet. Be the first!';
                container.innerHTML = `<p class="text-sm text-gray-500 dark:text-gray-400 text-center py-5">${noCommentsText}</p>`;
                return;
            }

            let html = '<div class="space-y-4">';
            commentsArray.forEach(comment => {
                html += renderCommentWithReplies(comment, 0, postinganId);
            });
            html += '</div>';
            container.innerHTML = html;

            attachCommentEventListeners(container, postinganId);
        } else {
            container.innerHTML = '<p class="text-center text-red-500 text-sm py-4">⚠️ Gagal memuat komentar</p>';
        }
    } catch (error) {
        console.error('Error loading comments:', error);
        container.innerHTML = '<p class="text-center text-red-500 text-sm py-4">⚠️ Gagal memuat komentar: ' + error.message + '</p>';
    }
};

// ===================== RENDER COMMENT =====================
function renderCommentWithReplies(comment, level, postinganId) {
    const marginLeft = Math.min(level * 28, 56);
    const isOwnComment = window.currentUserId && comment.id_user == window.currentUserId;
    const isLoggedIn = window.currentUserId !== null && window.currentUserId !== 'null';
    const userName = escapeHtml(comment.user?.nama_mahasiswa || 'User');
    const commentText = escapeHtml(comment.komentar);
    const commentId = String(comment.id_komentar);
    postinganId = String(postinganId);

    const replyText  = (window.locale === 'id') ? 'Balas'  : 'Reply';
    const editText   = (window.locale === 'id') ? 'Edit'   : 'Edit';
    const deleteText = (window.locale === 'id') ? 'Hapus'  : 'Delete';

    const userPortfolioUrl = comment.user && comment.user.username 
        ? `/${window.locale}/portofolio?user=${comment.user.username}` 
        : '#';
    const avatarHtml = comment.user && comment.user.username 
        ? `<a href="${userPortfolioUrl}">${getAvatarHtml(comment.user, 'w-8 h-8', 'text-xs')}</a>` 
        : getAvatarHtml(comment.user, 'w-8 h-8', 'text-xs');
    const nameHtml = comment.user && comment.user.username 
        ? `<a href="${userPortfolioUrl}" class="font-semibold text-sm text-gray-900 dark:text-gray-100 hover:text-indigo-600 transition-colors">${userName}</a>` 
        : `<span class="font-semibold text-sm text-gray-900 dark:text-gray-100">${userName}</span>`;

    let html = `
        <div class="comment-item transition-all duration-200 py-2" data-comment-id="${commentId}" data-postingan-id="${postinganId}" style="margin-left: ${marginLeft}px;">
            <div class="flex gap-3">
                ${avatarHtml}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        ${nameHtml}
                        <span class="text-xs text-gray-500">${formatDate(comment.tanggal || comment.created_at)}</span>
                    </div>
                    <p class="comment-text text-sm text-gray-700 dark:text-gray-300 mt-1.5 leading-relaxed" id="comment-text-${commentId}">${commentText}</p>
                    <div class="flex flex-wrap gap-3 mt-2">
    `;

    if (isLoggedIn) {
        html += `
                        <button class="reply-btn text-xs text-indigo-500 hover:text-indigo-700 font-medium transition-colors inline-flex items-center gap-1"
                            data-action="reply" data-comment-id="${commentId}" data-postingan-id="${postinganId}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                            ${replyText}
                        </button>
        `;
    }

    if (isOwnComment) {
        html += `
                        <button class="edit-comment-btn text-xs text-blue-500 hover:text-blue-700 transition-colors inline-flex items-center gap-1"
                            data-action="edit" data-comment-id="${commentId}" data-postingan-id="${postinganId}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            ${editText}
                        </button>
                        <button class="delete-comment-btn text-xs text-red-500 hover:text-red-700 transition-colors inline-flex items-center gap-1"
                            data-action="delete" data-comment-id="${commentId}" data-postingan-id="${postinganId}" data-type="full">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            ${deleteText}
                        </button>
        `;
    }

    html += `
                    </div>
                </div>
            </div>
            <div id="reply-form-${commentId}" class="reply-form-container hidden mt-3 ml-11"></div>
        </div>
    `;

    if (comment.balasan && comment.balasan.length > 0) {
        html += `<div class="replies-container ml-4 mt-1">`;
        comment.balasan.forEach(reply => {
            html += renderCommentWithReplies(reply, level + 1, postinganId);
        });
        html += `</div>`;
    }

    return html;
}

// ===================== EVENT DELEGATION =====================
function attachCommentEventListeners(container, postinganId) {
    if (!container.hasAttribute('data-delegated')) {
        container.setAttribute('data-delegated', 'true');
        container.addEventListener('click', function(e) {
            const btn = e.target.closest('[data-action]');
            if (!btn) return;

            const commentId  = btn.getAttribute('data-comment-id');
            const pid        = btn.getAttribute('data-postingan-id');
            const action     = btn.getAttribute('data-action');

            if (action === 'reply')       window.showReplyForm(commentId, pid);
            else if (action === 'edit')   window.showEditForm(commentId, pid);
            else if (action === 'delete') window.deleteComment(commentId, pid, btn.getAttribute('data-type') || 'full');
            else if (action === 'save-edit')   window.saveEdit(commentId, pid);
            else if (action === 'cancel-edit') window.cancelEdit(commentId);
        });
    }
}

// ===================== SUBMIT COMMENT =====================
window.submitComment = async function(postinganId) {
    const textarea  = document.getElementById(`comment-input-${postinganId}`);
    const commentText = textarea.value.trim();

    if (!commentText) {
        if (window.showPageInfo) window.showPageInfo('Komentar tidak boleh kosong', 'warning', 2000);
        else alert('Komentar tidak boleh kosong');
        return;
    }

    const submitBtn = document.getElementById(`submit-comment-btn-${postinganId}`);
    const originalText = submitBtn ? submitBtn.innerHTML : '';
    if (submitBtn) {
        submitBtn.classList.add('btn-loading');
        submitBtn.innerHTML = 'Mengirim...';
        submitBtn.disabled = true;
    }

    try {
        const response = await fetch(`/${window.locale}/komentar`, {
            method: 'POST',
            headers: getHeaders(),
            body: JSON.stringify({ id_postingan: postinganId, komentar: commentText })
        });

        const data = await response.json();

        if (data.success) {
            textarea.value = '';
            await window.loadComments(postinganId);
            updateTotalCommentCount(1);
            if (window.showPageInfo) window.showPageInfo('Komentar berhasil ditambahkan', 'success', 2000);
        } else {
            if (window.showErrorAlert) window.showErrorAlert(data.message || 'Gagal menambahkan komentar');
            else alert(data.message || 'Gagal menambahkan komentar');
        }
    } catch (error) {
        console.error('Error:', error);
        if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan: ' + error.message);
        else alert('Terjadi kesalahan: ' + error.message);
    } finally {
        if (submitBtn) {
            submitBtn.classList.remove('btn-loading');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }
};

// ===================== SUBMIT REPLY =====================
async function submitReply(form, postinganId) {
    if (form.hasAttribute('data-submitting')) return;
    form.setAttribute('data-submitting', 'true');

    const textarea   = form.querySelector('textarea[name="komentar"]');
    const commentText = textarea.value.trim();
    const parentId   = form.querySelector('input[name="parent_id"]').value;

    if (!commentText) {
        if (window.showPageInfo) window.showPageInfo('Balasan tidak boleh kosong', 'warning', 2000);
        else alert('Balasan tidak boleh kosong');
        form.removeAttribute('data-submitting');
        return;
    }

    const submitBtn = form.querySelector('button[type="submit"]');
    const cancelBtn = form.querySelector('button[type="button"]');
    submitBtn.disabled = true;
    if (cancelBtn) cancelBtn.disabled = true;

    try {
        const response = await fetch(`/${window.locale}/komentar`, {
            method: 'POST',
            headers: getHeaders(),
            body: JSON.stringify({
                id_postingan: postinganId,
                komentar: commentText,
                parent_id: parentId,
                reply_to_id: parentId
            })
        });

        const data = await response.json();

        if (data.success) {
            await window.loadComments(postinganId);
            updateTotalCommentCount(1);
            const replyContainer = document.getElementById(`reply-form-${parentId}`);
            if (replyContainer) {
                replyContainer.classList.add('hidden');
                replyContainer.innerHTML = '';
            }
            if (window.showPageInfo) window.showPageInfo('Balasan berhasil ditambahkan', 'success', 2000);
        } else {
            if (window.showErrorAlert) window.showErrorAlert(data.message || 'Gagal menambahkan balasan');
            else alert(data.message || 'Gagal menambahkan balasan');
        }
    } catch (error) {
        console.error('Error:', error);
        if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan: ' + error.message);
        else alert('Terjadi kesalahan: ' + error.message);
    } finally {
        submitBtn.disabled = false;
        if (cancelBtn) cancelBtn.disabled = false;
        form.removeAttribute('data-submitting');
    }
}

// ===================== SHOW REPLY FORM =====================
window.showReplyForm = function(parentCommentId, postinganId) {
    parentCommentId = String(parentCommentId);
    const replyFormContainer = document.getElementById(`reply-form-${parentCommentId}`);
    if (!replyFormContainer) return;

    const sendText        = (window.locale === 'id') ? 'Kirim'  : 'Send';
    const cancelText      = (window.locale === 'id') ? 'Batal'  : 'Cancel';
    const placeholderText = (window.locale === 'id') ? 'Tulis balasan...' : 'Write a reply...';

    if (replyFormContainer.innerHTML.trim() !== '' && !replyFormContainer.classList.contains('hidden')) {
        replyFormContainer.classList.add('hidden');
        replyFormContainer.innerHTML = '';
        return;
    }

    replyFormContainer.innerHTML = `
        <form class="reply-submit-form mt-3">
            <input type="hidden" name="parent_id" value="${parentCommentId}">
            <div class="flex flex-col gap-2">
                <textarea name="komentar" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm outline-none resize-none text-gray-900 dark:text-white" placeholder="${placeholderText}"></textarea>
                <div class="flex gap-2 justify-end">
                    <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg transition-colors">${sendText}</button>
                    <button type="button" onclick="this.closest('.reply-form-container').classList.add('hidden'); this.closest('.reply-form-container').innerHTML = '';" class="px-4 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">${cancelText}</button>
                </div>
            </div>
        </form>
    `;
    replyFormContainer.classList.remove('hidden');

    const form = replyFormContainer.querySelector('form');
    form.onsubmit = async (e) => {
        e.preventDefault();
        await submitReply(form, postinganId);
    };
};

// ===================== SHOW EDIT FORM =====================
window.showEditForm = function(commentId, postinganId) {
    commentId   = String(commentId);
    postinganId = String(postinganId);

    const commentTextEl = document.getElementById(`comment-text-${commentId}`);
    if (!commentTextEl) return;

    const originalText = commentTextEl.innerText;
    const commentItem  = commentTextEl.closest('.comment-item');

    const existingEditForm = document.getElementById(`edit-form-${commentId}`);
    if (existingEditForm) {
        existingEditForm.remove();
        commentTextEl.style.display = 'block';
        const editBtn = commentItem.querySelector('.edit-comment-btn');
        if (editBtn) editBtn.style.display = 'inline-flex';
        return;
    }

    const saveText   = (window.locale === 'id') ? 'Simpan' : 'Save';
    const cancelText = (window.locale === 'id') ? 'Batal'  : 'Cancel';

    const editForm = document.createElement('div');
    editForm.className = 'edit-form mt-2';
    editForm.id = `edit-form-${commentId}`;
    editForm.innerHTML = `
        <textarea class="edit-textarea w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none resize-none text-gray-900 dark:text-white" rows="2">${escapeHtml(originalText)}</textarea>
        <div class="flex gap-2 mt-2">
            <button class="save-edit-btn px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors"
                data-action="save-edit" data-comment-id="${commentId}" data-postingan-id="${postinganId}">${saveText}</button>
            <button class="px-3 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-400 transition-colors"
                data-action="cancel-edit" data-comment-id="${commentId}">${cancelText}</button>
        </div>
    `;

    commentTextEl.style.display = 'none';
    commentTextEl.parentNode.insertBefore(editForm, commentTextEl.nextSibling);

    const editBtn = commentItem.querySelector('.edit-comment-btn');
    if (editBtn) editBtn.style.display = 'none';
};

// ===================== SAVE EDIT =====================
window.saveEdit = async function(commentId, postinganId) {
    commentId = String(commentId);
    const editForm = document.getElementById(`edit-form-${commentId}`);
    if (!editForm) return;

    const editTextarea = editForm.querySelector('.edit-textarea');
    if (!editTextarea) return;

    const newText = editTextarea.value.trim();
    if (!newText) {
        if (window.showPageInfo) window.showPageInfo('Komentar tidak boleh kosong', 'warning', 2000);
        else alert('Komentar tidak boleh kosong');
        return;
    }

    const saveBtn = editForm.querySelector('.save-edit-btn');
    if (!saveBtn) return;

    const originalBtnHtml = saveBtn.innerHTML;
    saveBtn.innerHTML = '<div class="comment-loading" style="width:14px;height:14px;"></div>';
    saveBtn.disabled = true;

    try {
        const lastUpdated = window.commentLastUpdated?.[postinganId] ?? '';
        const response = await fetch(
            `/${window.locale}/komentar/update?id=${commentId}&id_postingan=${postinganId}&last_updated=${encodeURIComponent(lastUpdated)}`,
            {
                method: 'PUT',
                headers: getHeaders(),
                body: JSON.stringify({ komentar: newText })
            }
        );

        const data = await response.json();

        if (data.success) {
            await window.loadComments(postinganId);
            if (window.showPageInfo) window.showPageInfo('Komentar berhasil diperbarui', 'success', 2000);
        } else if (data.code === 'STALE_DATA') {
            await window.loadComments(postinganId);
            if (window.showPageInfo) window.showPageInfo('Komentar diperbarui pengguna lain. Edit ulang jika perlu.', 'warning', 3000);
        } else {
            if (window.showErrorAlert) window.showErrorAlert(data.message || 'Gagal mengupdate komentar');
            else alert(data.message || 'Gagal mengupdate komentar');
            saveBtn.innerHTML = originalBtnHtml;
            saveBtn.disabled = false;
        }
    } catch (error) {
        console.error('Error:', error);
        if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan: ' + error.message);
        else alert('Terjadi kesalahan: ' + error.message);
        saveBtn.innerHTML = originalBtnHtml;
        saveBtn.disabled = false;
    }
};

// ===================== CANCEL EDIT =====================
window.cancelEdit = function(commentId) {
    commentId = String(commentId);
    const commentTextEl = document.getElementById(`comment-text-${commentId}`);
    const editForm      = document.getElementById(`edit-form-${commentId}`);
    if (!commentTextEl) return;

    const commentItem = commentTextEl.closest('.comment-item');
    commentTextEl.style.display = 'block';
    if (editForm) editForm.remove();

    const editBtn = commentItem.querySelector('.edit-comment-btn');
    if (editBtn) editBtn.style.display = 'inline-flex';
};

// ===================== DELETE COMMENT =====================
window.deleteComment = async function(commentId, postinganId, type = 'full') {
    commentId   = String(commentId);
    postinganId = String(postinganId);

    const confirmMessageSingle = (window.locale === 'id')
        ? 'Apakah Anda yakin ingin menghapus balasan ini saja?'
        : 'Are you sure you want to delete this reply only?';
    const confirmMessageFull = (window.locale === 'id')
        ? 'Apakah Anda yakin ingin menghapus komentar ini beserta semua balasannya?'
        : 'Are you sure you want to delete this comment and all its replies?';
    const confirmMessage = type === 'single' ? confirmMessageSingle : confirmMessageFull;

    if (window.showConfirm) {
        const confirmed = await window.showConfirm(confirmMessage);
        if (!confirmed) return;
    } else if (window.showConfirmAlert) {
        const confirmed = await window.showConfirmAlert(confirmMessage);
        if (!confirmed) return;
    } else {
        if (!confirm(confirmMessage)) return;
    }

    if (window.showLoading) window.showLoading('Menghapus...');

    try {
        const response = await fetch(
            `/${window.locale}/komentar/destroy?id=${commentId}&id_postingan=${postinganId}&type=${type}`,
            { method: 'DELETE', headers: getHeaders() }
        );

        const data = await response.json();

        if (data.success) {
            await window.loadComments(postinganId);
            updateTotalCommentCount(-1);
            if (window.showPageInfo) window.showPageInfo('Komentar berhasil dihapus', 'success', 2000);
        } else {
            if (window.showErrorAlert) window.showErrorAlert(data.message || 'Gagal menghapus komentar');
            else alert(data.message || 'Gagal menghapus komentar');
        }
    } catch (error) {
        console.error('Error:', error);
        if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan: ' + error.message);
        else alert('Terjadi kesalahan: ' + error.message);
    } finally {
        if (window.closeLoading) window.closeLoading();
    }
};

// ===================== UPDATE TOTAL COUNT =====================
function updateTotalCommentCount(delta) {
    document.querySelectorAll('.comment-total-count, .comment-total-count-heading').forEach(el => {
        const current = parseInt(el.textContent) || 0;
        el.textContent = Math.max(0, current + delta);
    });
}

// ===================== SHARE =====================
window.toggleShare = function(btn) {
    const url = window.location.href;
    if (navigator.share) {
        navigator.share({ url }).catch(() => {});
        return;
    }
    navigator.clipboard.writeText(url).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
        setTimeout(() => { btn.innerHTML = original; }, 2000);
        if (window.showPageInfo) window.showPageInfo('Link berhasil disalin!', 'success', 1500);
    }).catch(() => alert('Gagal menyalin link'));
};

// ===================== PAGE INITIALIZER FOR DETAIL POSTINGAN =====================
window.initPostinganDetailPage = function(container) {
    window.currentUserId   = container.dataset.userId === 'null' ? null : parseInt(container.dataset.userId);
    window.currentUserName = container.dataset.userName || '';
    window.currentUserPhoto = container.dataset.userPhoto || '';
    window.locale = container.dataset.locale || document.querySelector('html').getAttribute('lang') || 'id';
    window.commentLastUpdated = {};
    const postinganId = parseInt(container.dataset.postinganId);

    // Auto-load comments on page load
    window.loadComments(postinganId);

    // Like button
    const likeBtn = container.querySelector('.like-btn');
    if (likeBtn) {
        likeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const pid = this.dataset.postinganId;
            fetch(`/${window.locale}/postingan/toggle-like?id=${pid}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const icon      = likeBtn.querySelector('svg');
                    const countSpan = likeBtn.querySelector('.like-count');
                    if (data.liked) {
                        likeBtn.classList.add('text-red-500', 'dark:text-red-400');
                        icon.classList.add('fill-current', 'text-red-500');
                    } else {
                        likeBtn.classList.remove('text-red-500', 'dark:text-red-400');
                        icon.classList.remove('fill-current', 'text-red-500');
                    }
                    if (countSpan) countSpan.textContent = data.like_count;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Comment toggle button
    const commentToggle = container.querySelector('.comment-toggle');
    if (commentToggle) {
        commentToggle.addEventListener('click', function(e) {
            e.preventDefault();
            const commentsSection = document.getElementById('comments');
            if (commentsSection) {
                commentsSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    // Share button
    const shareBtn = container.querySelector('.share-btn');
    if (shareBtn) {
        shareBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = container.dataset.postUrl;
            const title = container.dataset.postUserName;
            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: 'Cek postingan ini!',
                    url: url
                }).catch(err => console.log('Share cancelled:', err));
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(url).then(() => {
                    alert('Link disalin ke clipboard!');
                }).catch(err => console.error('Copy failed:', err));
            }
        });
    }

    // Post Menu Dropdown
    const postMenuBtn       = document.getElementById('postMenuButton');
    const postMenuDropdown  = document.getElementById('postMenuDropdown');
    const postMenuContainer = document.getElementById('postMenuContainer');

    if (postMenuBtn && postMenuDropdown) {
        postMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            postMenuDropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', function(e) {
            if (postMenuContainer && !postMenuContainer.contains(e.target)) {
                postMenuDropdown.classList.add('hidden');
            }
        });
        postMenuDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
};

/* ==========================================
   VIEWS_PROJECT_USER.BLADE.PHP SCRIPTS
   ========================================== */
window.puPlayVideo = function(wrapperId, type, src) {
    const wrapper = document.getElementById(wrapperId);
    if (!wrapper) return;

    const embedContainer = wrapper.querySelector('.pu-embed-container');
    if (!embedContainer) return;

    if (type === 'youtube') {
        const iframe = document.createElement('iframe');
        iframe.src = 'https://www.youtube.com/embed/' + src + '?autoplay=1&rel=0&modestbranding=1';
        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
        iframe.allowFullscreen = true;
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = 'none';
        embedContainer.innerHTML = '';
        embedContainer.appendChild(iframe);
    } else if (type === 'direct') {
        const video = document.createElement('video');
        video.src = src;
        video.controls = true;
        video.autoplay = true;
        video.style.width = '100%';
        video.style.height = '100%';
        video.style.objectFit = 'contain';
        video.style.background = '#000';
        embedContainer.innerHTML = '';
        embedContainer.appendChild(video);
    }

    wrapper.classList.add('playing');
    wrapper.onclick = null;
};

/* ==========================================
   VIEWS_DETAIL_PROJECT.BLADE.PHP SCRIPTS
   ========================================== */
window.openImageModal = function(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    if (modal && modalImage) {
        modalImage.src = imageSrc;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
};

window.closeImageModal = function() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
};

// Close modal with ESC key
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        window.closeImageModal();
    }
});

window.initProjectDetailPage = function(container) {
    // Individual delete buttons
    container.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

            const form = this.closest('.delete-form');
            if (!form) return;

            const confirmed = await window.showConfirmAlert({
                title: 'Hapus Catatan?',
                text: 'Catatan ini akan dihapus permanen dan tidak bisa dikembalikan.',
                icon: 'warning',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
            });

            if (confirmed) {
                if (window.showLoading) window.showLoading('Menghapus catatan...');
                form.submit();
            }
        });
    });

    // Mass delete functionality
    const checkboxes = container.querySelectorAll('.entry-checkbox');
    const massDeleteBtn = document.getElementById('massDeleteBtn');
    const massDeleteIds = document.getElementById('massDeleteIds');
    const massDeleteForm = document.getElementById('massDeleteForm');
    const selectedCount = document.getElementById('selectedCount');

    if (checkboxes.length > 0 && massDeleteBtn && massDeleteForm && selectedCount) {
        const updateMassDeleteButton = () => {
            const checkedBoxes = container.querySelectorAll('.entry-checkbox:checked');
            const checkedCount = checkedBoxes.length;

            selectedCount.textContent = checkedCount;

            // Update hidden input with selected IDs
            const selectedIds = Array.from(checkedBoxes).map(cb => cb.dataset.id);
            massDeleteIds.value = JSON.stringify(selectedIds);

            if (checkedCount > 0) {
                massDeleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                massDeleteBtn.disabled = false;
            } else {
                massDeleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
                massDeleteBtn.disabled = true;
            }
        };

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateMassDeleteButton);
        });

        massDeleteBtn.addEventListener('click', async function () {
            const checkedCount = container.querySelectorAll('.entry-checkbox:checked').length;

            if (checkedCount === 0) return;

            const confirmed = await window.showConfirmAlert({
                title: 'Hapus Banyak Catatan?',
                text: `Anda akan menghapus ${checkedCount} catatan. Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                confirmButtonText: 'Ya, Hapus Semua',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
            });

            if (confirmed) {
                if (window.showLoading) window.showLoading('Menghapus catatan terpilih...');

                // Parse IDs from hidden input
                const ids = JSON.parse(massDeleteIds.value);

                // Create a new form with the IDs as array
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = massDeleteForm.action;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfInput);

                ids.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    if (typeof window.showPageInfo === 'function') {
        window.showPageInfo("popup.user_detail_project");
    }
};

/* ==========================================
   VIEWS_CREATE_PROJECT.BLADE.PHP SCRIPTS
   ========================================== */
window.initProjectCreatePage = function(container) {
    let allUsers = []; 
    let allUsersMap = new Map(); 
    const currentUser = JSON.parse(container.dataset.currentUser || 'null');
    const userSelectionStorageKey = 'project_selected_users';

    let currentModalFilters = { search: '', angkatan: '', jurusan: '', keahlian: '' };
    let currentPage = 1;

    // State permanen (sudah di-confirm)
    let selectedUsers = { owner: null, leader: null, members: [] };

    // State sementara di dalam modal (sebelum confirm)
    let pendingUsers = { owner: null, leader: null, members: [] };

    let taskIndex = 0;

    function saveSelectedUsersToStorage() {
        localStorage.setItem(userSelectionStorageKey, JSON.stringify({
            owner: selectedUsers.owner,
            leader: selectedUsers.leader,
            members: selectedUsers.members
        }));
    }

    function restoreSelectedUsersFromStorage() {
        const stored = localStorage.getItem(userSelectionStorageKey);
        if (!stored) return false;
        try {
            const parsed = JSON.parse(stored);
            if (parsed.owner)  selectedUsers.owner   = parsed.owner;
            if (parsed.leader) selectedUsers.leader  = parsed.leader;
            if (Array.isArray(parsed.members)) selectedUsers.members = parsed.members;
            return true;
        } catch (e) {
            console.warn('Unable to restore selected users:', e);
            return false;
        }
    }

    function addUsersToAllUsers(usersArray) {
        if (!Array.isArray(usersArray)) return;
        usersArray.forEach(user => {
            if (!allUsersMap.has(String(user.id))) {
                allUsersMap.set(String(user.id), user);
                allUsers.push(user);
            }
        });
    }

    function getUserById(id) {
        const userId = String(id);
        if (allUsersMap.has(userId)) {
            return allUsersMap.get(userId);
        }
        return null;
    }

    function syncPendingFromSelected() {
        pendingUsers = {
            owner:   selectedUsers.owner   ? { ...selectedUsers.owner }   : null,
            leader:  selectedUsers.leader  ? { ...selectedUsers.leader }  : null,
            members: selectedUsers.members.map(m => ({ ...m }))
        };
    }

    function applyPendingToSelected() {
        selectedUsers.owner   = pendingUsers.owner   ? { ...pendingUsers.owner }   : null;
        selectedUsers.leader  = pendingUsers.leader  ? { ...pendingUsers.leader }  : null;
        selectedUsers.members = pendingUsers.members.map(m => ({ ...m }));
    }

    function discardPending() {
        pendingUsers = { owner: null, leader: null, members: [] };
    }

    window.openUserModal = function() {
        const toggle = document.getElementById('project-collaborative-toggle');
        if (!toggle || !toggle.checked) {
            alert('Harap aktifkan mode kolaboratif terlebih dahulu untuk menambah user.');
            return;
        }
        syncPendingFromSelected();
        document.getElementById('userModal').classList.remove('hidden');
        fetchUsers(1);
    }

    window.closeUserModal = function() {
        document.getElementById('userModal').classList.add('hidden');
        discardPending();
    }

    window.cancelUserModal = function() {
        discardPending();
        document.getElementById('userModal').classList.add('hidden');
    }

    window.confirmUserSelection = function() {
        applyPendingToSelected();
        updateFormInputs();
        renderSelectedUsers();
        updateTaskSectionVisibility();
        updateTaskUserOptions();
        updateSelectedUsersBadge();
        saveSelectedUsersToStorage();
        document.getElementById('userModal').classList.add('hidden');
        discardPending();
    }

    function fetchUsers(page = 1) {
        currentPage = page;
        const params = new URLSearchParams({
            page: page,
            search:   currentModalFilters.search,
            angkatan: currentModalFilters.angkatan,
            jurusan:  currentModalFilters.jurusan,
            keahlian: currentModalFilters.keahlian
        });

        const fetchUrl = container.dataset.fetchUrl;

        fetch(`${fetchUrl}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.userListHtml;
            
            const userElements = tempDiv.querySelectorAll('[data-user-id]');
            const usersInPage = [];
            
            userElements.forEach(el => {
                const userId = el.getAttribute('data-user-id');
                const nameEl = el.querySelector('.font-medium');
                const emailEl = el.querySelector('.text-sm.text-gray-500');
                const imgEl = el.querySelector('img');
                
                if (userId && nameEl) {
                    const user = {
                        id: parseInt(userId),
                        nama_mahasiswa: nameEl.textContent.trim(),
                        email: emailEl ? emailEl.textContent.trim() : '',
                        photo_profile: imgEl ? imgEl.getAttribute('src')?.replace('/storage/', '') : null
                    };
                    usersInPage.push(user);
                }
            });
            
            addUsersToAllUsers(usersInPage);
            renderUserListWithRoles(data.userListHtml);
            document.getElementById('modal-pagination').innerHTML = data.paginationHtml;
            refreshRoleSelections();
            
            if (typeof window.refreshTranslations === 'function') {
                window.refreshTranslations();
            }
        });
    }
    
    function renderUserListWithRoles(html) {
        const uListContainer = document.getElementById('modal-user-list');
        uListContainer.innerHTML = html;
        
        document.querySelectorAll('.user-role-select').forEach(select => {
            const userId = String(select.dataset.userId);
            const user = getUserById(userId);
            
            if (user) {
                const userDiv = select.closest('[data-user-id]');
                if (userDiv) {
                    const nameDiv = userDiv.querySelector('.font-medium');
                    if (nameDiv && nameDiv.textContent !== user.nama_mahasiswa) {
                        nameDiv.textContent = user.nama_mahasiswa;
                    }
                }
            }
        });
    }

    window.updateUserRole = function(selectElement, userId, role) {
        const user = getUserById(userId);
        if (!user) {
            console.error('User not found:', userId);
            return;
        }

        const isOwner         = pendingUsers.owner  && String(pendingUsers.owner.id)  === String(userId);
        const isCurrentLeader = pendingUsers.leader && String(pendingUsers.leader.id) === String(userId);

        if (isOwner && role === 'member') {
            alert('Owner tidak bisa menjadi member.');
            selectElement.value = isCurrentLeader ? 'leader' : '';
            refreshRoleSelections();
            return;
        }

        if (role === 'leader' && pendingUsers.leader && String(pendingUsers.leader.id) !== String(userId)) {
            const confirmChange = confirm(
                `Anda yakin ingin mengganti leader dari "${pendingUsers.leader.nama_mahasiswa}" menjadi "${user.nama_mahasiswa}"?`
            );
            if (!confirmChange) {
                selectElement.value = isCurrentLeader ? 'leader' : '';
                refreshRoleSelections();
                return;
            }
            pendingUsers.leader = null;
        }

        if (pendingUsers.leader && String(pendingUsers.leader.id) === String(userId)) {
            pendingUsers.leader = null;
        }
        pendingUsers.members = pendingUsers.members.filter(m => String(m.id) !== String(userId));

        if (role === 'leader') {
            pendingUsers.leader = user;
        } else if (role === 'member') {
            if (!pendingUsers.members.some(m => String(m.id) === String(userId))) {
                pendingUsers.members.push(user);
            }
        }

        refreshRoleSelections();
    }

    function refreshRoleSelections() {
        const leaderId = pendingUsers.leader ? String(pendingUsers.leader.id) : null;
        const ownerId  = pendingUsers.owner  ? String(pendingUsers.owner.id)  : null;

        document.querySelectorAll('.user-role-select').forEach(select => {
            const userId   = String(select.dataset.userId);
            const isOwner  = ownerId  === userId;
            const isLeader = leaderId === userId;
            const isMember = pendingUsers.members.some(m => String(m.id) === userId);

            if (isLeader) {
                select.value = 'leader';
            } else if (isMember) {
                select.value = 'member';
            } else {
                select.value = '';
            }

            const leaderOption = select.querySelector('option[value="leader"]');
            const memberOption = select.querySelector('option[value="member"]');

            if (leaderOption) {
                leaderOption.disabled = !!(leaderId && leaderId !== userId && !isLeader);
                leaderOption.title    = leaderOption.disabled ? 'Leader sudah dipilih' : '';
            }

            if (memberOption) {
                memberOption.disabled = false;
            }

            select.disabled = false;
        });
    }

    function updateSelectedUsersBadge() {
        const badge = document.getElementById('selected-users-badge');
        if (!badge) return;
        let count = 0;
        if (selectedUsers.owner)  count++;
        if (selectedUsers.leader) count++;
        count += selectedUsers.members.length;
        badge.innerHTML = count === 0
            ? ''
            : `<span class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full text-xs font-semibold">${count} user(s) terpilih</span>`;
    }

    function updateTaskSectionVisibility() {
        const taskSection = document.getElementById('task-section');
        if (!taskSection) return;
        const hasUsers = selectedUsers.owner || selectedUsers.leader || selectedUsers.members.length > 0;
        if (hasUsers) {
            taskSection.classList.remove('hidden');
        } else {
            taskSection.classList.add('hidden');
            const tContainer = document.getElementById('tasks-container');
            if (tContainer) { tContainer.innerHTML = ''; taskIndex = 0; }
        }
    }

    function updateFormInputs() {
        const ownerEl = document.getElementById('selected-owner-id');
        const leaderEl = document.getElementById('selected-leader-id');
        const membersEl = document.getElementById('selected-members-ids');
        if (ownerEl) ownerEl.value = selectedUsers.owner?.id  || '';
        if (leaderEl) leaderEl.value  = selectedUsers.leader?.id || '';
        if (membersEl) membersEl.value = selectedUsers.members.map(m => m.id).join(',');

        const memberInputs = document.getElementById('selected-members-inputs');
        if (memberInputs) {
            memberInputs.innerHTML = selectedUsers.members
                .map(member => `<input type="hidden" name="members[]" value="${member.id}">`)
                .join('');
        }
    }

    function renderSelectedUsers() {
        const containerSelected = document.getElementById('selected-users-container');
        const noUsersMsg   = document.getElementById('no-users-message');
        if (!containerSelected || !noUsersMsg) return;

        const selected = [];
        if (selectedUsers.owner) {
            const role = selectedUsers.leader && selectedUsers.leader.id === selectedUsers.owner.id
                ? 'Owner & Leader' : 'Owner';
            selected.push({ ...selectedUsers.owner, role });
        }
        if (selectedUsers.leader && (!selectedUsers.owner || selectedUsers.owner.id !== selectedUsers.leader.id)) {
            selected.push({ ...selectedUsers.leader, role: 'Leader' });
        }
        selectedUsers.members.forEach(member => selected.push({ ...member, role: 'Member' }));

        if (!selected.length) {
            containerSelected.innerHTML = '';
            noUsersMsg.classList.remove('hidden');
            return;
        }

        noUsersMsg.classList.add('hidden');
        containerSelected.innerHTML = selected.map(user => {
            const styles = {
                'Owner':           'bg-blue-50 dark:bg-blue-950 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
                'Leader':          'bg-green-50 dark:bg-green-950 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
                'Owner & Leader':  'bg-teal-50 dark:bg-teal-950 border-teal-200 dark:border-teal-800 text-teal-800 dark:text-teal-200',
                'Member':          'bg-purple-50 dark:bg-purple-950 border-purple-200 dark:border-purple-800 text-purple-800 dark:text-purple-200'
            }[user.role] || 'bg-gray-50 dark:bg-gray-950 border-gray-200 dark:border-gray-800 text-gray-800 dark:text-gray-200';

            return `
                <div class="flex items-center justify-between p-4 border rounded-2xl ${styles}">
                     <div class="flex items-center gap-3">
                         ${user.photo_profile
                             ? `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800">`
                             : `<div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center ring-2 ring-white dark:ring-gray-800">
                                    <span class="font-semibold text-current">${user.nama_mahasiswa.charAt(0).toUpperCase()}</span>
                                </div>`
                         }
                         <div>
                             <div class="font-medium">${user.role}: ${user.nama_mahasiswa}</div>
                             <div class="text-sm text-gray-500 dark:text-gray-400">${user.email || ''}</div>
                         </div>
                     </div>
                     <div class="flex items-center gap-2">
                         <button type="button" onclick="editUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80" title="Edit Role">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                             </svg>
                         </button>
                         ${user.role !== 'Owner' ? `
                         <button type="button" onclick="removeUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80" title="Remove">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                             </svg>
                         </button>` : ''}
                     </div>
                </div>
            `;
        }).join('');
    }

    function deleteTasksForUser(userId) {
        const userIdStr = String(userId);
        document.querySelectorAll('.task-item').forEach(taskItem => {
            const select = taskItem.querySelector('.task-user-select');
            if (select && String(select.value) === userIdStr) taskItem.remove();
        });
        if (!document.querySelectorAll('.task-item').length) window.addTaskRow();
    }

    window.removeUser = function(userId) {
        deleteTasksForUser(userId);
        if (selectedUsers.leader?.id == userId)  selectedUsers.leader  = null;
        selectedUsers.members = selectedUsers.members.filter(m => String(m.id) !== String(userId));
        updateFormInputs();
        renderSelectedUsers();
        updateTaskUserOptions();
        updateSelectedUsersBadge();
        saveSelectedUsersToStorage();
    }

    window.editUser = function(userId) {
        window.openUserModal();
        setTimeout(() => {
            const userElement = document.querySelector(`.user-role-select[data-user-id="${userId}"]`)?.closest('[data-user-id]');
            if (userElement) {
                userElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                userElement.style.backgroundColor = '#fef3c7';
                setTimeout(() => userElement.style.backgroundColor = '', 2000);
            }
        }, 500);
    }

    function getAllowedTaskUsers() {
        const users = [];
        const added = new Set();
        const add = user => {
            if (!user || added.has(user.id)) return;
            added.add(user.id);
            users.push({ id: user.id, name: user.nama_mahasiswa });
        };
        add(selectedUsers.owner);
        add(selectedUsers.leader);
        selectedUsers.members.forEach(add);
        return users;
    }

    function renderTaskUserOptions(selectedId = '') {
        const users = getAllowedTaskUsers();
        let html = '<option value=""> Pilih Penanggung Jawab </option>';
        users.forEach(user => {
            html += `<option value="${user.id}" ${String(user.id) === String(selectedId) ? 'selected' : ''}>${user.name}</option>`;
        });
        return html;
    }

    window.addTaskRow = function(taskData = null) {
        const tContainer = document.getElementById('tasks-container');
        if (!tContainer) return;
        const index    = taskIndex++;
        const userId   = taskData?.user_id ?? '';
        const taskName = taskData?.name_task ? taskData.name_task.replace(/"/g, '&quot;') : '';
        const hiddenId = taskData?.id ? `<input type="hidden" name="tasks[${index}][id]" value="${taskData.id}">` : '';

        const taskItem = document.createElement('div');
        taskItem.className = 'task-item p-4 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900';
        taskItem.innerHTML = `
            ${hiddenId}
            <div class="grid gap-4 md:grid-cols-3 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Penanggung Jawab</label>
                    <select name="tasks[${index}][user_id]" class="task-user-select w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                        ${renderTaskUserOptions(userId)}
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Nama Tugas</label>
                    <input type="text" name="tasks[${index}][name_task]" value="${taskName}"
                        class="task-name-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                        placeholder="Deskripsikan tugas...">
                </div>
                <button type="button" onclick="removeTaskRow(this)"
                    class="self-start mt-6 px-4 py-3 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl">Hapus</button>
            </div>
        `;
        tContainer.appendChild(taskItem);
        taskItem.querySelector('.task-user-select')?.addEventListener('change', updateTaskUserOptions);
    }

    window.removeTaskRow = function(button) {
        button.closest('.task-item')?.remove();
        if (!document.querySelectorAll('.task-item').length) window.addTaskRow();
    }

    function cleanupInvalidTaskRows() {
        const allowedIds = getAllowedTaskUsers().map(u => String(u.id));
        document.querySelectorAll('.task-item').forEach(taskItem => {
            const select = taskItem.querySelector('.task-user-select');
            if (!select || !select.value || !allowedIds.includes(select.value)) taskItem.remove();
        });
        if (!document.querySelectorAll('.task-item').length) window.addTaskRow();
    }

    function updateTaskUserOptions() {
        document.querySelectorAll('.task-user-select').forEach(select => {
            const currentValue = select.value;
            select.innerHTML   = renderTaskUserOptions(currentValue);
            if (currentValue) select.value = currentValue;
        });
        cleanupInvalidTaskRows();
    }

    function initializeTaskRows(existingTasks = []) {
        const tContainer = document.getElementById('tasks-container');
        if (!tContainer) return;
        tContainer.innerHTML = '';
        taskIndex = 0;
        if (Array.isArray(existingTasks) && existingTasks.length) {
            existingTasks.forEach(task => { if (task.user_id || task.name_task) window.addTaskRow(task); });
        } else {
            window.addTaskRow();
        }
        updateTaskUserOptions();
    }

    function setupModalFilters() {
        document.getElementById('modal-search')?.addEventListener('input', function () {
            currentModalFilters.search = this.value;
            fetchUsers(1);
        });
        document.getElementById('modal-angkatan')?.addEventListener('change', function () {
            currentModalFilters.angkatan = this.value;
            fetchUsers(1);
        });
        document.getElementById('modal-jurusan')?.addEventListener('change', function () {
            currentModalFilters.jurusan = this.value;
            fetchUsers(1);
        });
        document.getElementById('modal-keahlian')?.addEventListener('change', function () {
            currentModalFilters.keahlian = this.value;
            fetchUsers(1);
        });
    }

    // Modal pagination click
    const pagListener = function (e) {
        const link = e.target.closest('#modal-pagination a');
        if (link) {
            e.preventDefault();
            const url  = link.getAttribute('href');
            if (!url) return;
            const page = new URL(url).searchParams.get('page') || 1;
            fetchUsers(page);
        }
    };
    document.addEventListener('click', pagListener);

    function toggleUserSelectionSection() {
        const toggle             = document.getElementById('project-collaborative-toggle');
        const userSelectionSection = document.getElementById('user-selection-section');

        if (!toggle || !userSelectionSection) return;

        if (toggle.checked) {
            userSelectionSection.style.display = 'block';

            if (
                selectedUsers.leader &&
                selectedUsers.owner &&
                String(selectedUsers.leader.id) === String(selectedUsers.owner.id) &&
                selectedUsers.members.length === 0
            ) {
                selectedUsers.leader = null;
                updateFormInputs();
                renderSelectedUsers();
                updateSelectedUsersBadge();
                saveSelectedUsersToStorage();
            }
        } else {
            userSelectionSection.style.display = 'none';
            selectedUsers.owner   = currentUser;
            selectedUsers.leader  = currentUser;
            selectedUsers.members = [];
            updateFormInputs();
            renderSelectedUsers();
            updateTaskUserOptions();
            updateSelectedUsersBadge();
            saveSelectedUsersToStorage();
        }
    }

    function setupDateValidation() {
        const tanggalMulaiInput  = document.getElementById('tanggal_mulai');
        const tanggalAkhirInput  = document.getElementById('tanggal_akhir');
        if (!tanggalMulaiInput || !tanggalAkhirInput) return;

        if (tanggalMulaiInput.value) tanggalAkhirInput.min = tanggalMulaiInput.value;

        tanggalMulaiInput.addEventListener('change', function () {
            if (this.value) {
                tanggalAkhirInput.min = this.value;
                if (tanggalAkhirInput.value && tanggalAkhirInput.value < this.value) {
                    tanggalAkhirInput.value = '';
                }
            } else {
                tanggalAkhirInput.min = '';
            }
        });

        tanggalAkhirInput.addEventListener('change', function () {
            if (this.value && tanggalMulaiInput.value && this.value < tanggalMulaiInput.value) {
                this.value = '';
                alert('Tanggal selesai harus setelah atau sama dengan tanggal mulai.');
            }
        });
    }

    function onSubmitProjectForm() {
        if (currentUser) selectedUsers.owner = currentUser;
        if (selectedUsers.owner && !selectedUsers.leader && selectedUsers.members.length > 0) {
            selectedUsers.leader = selectedUsers.owner;
        }
        updateFormInputs();
        cleanupInvalidTaskRows();
        updateTaskUserOptions();
    }

    function loadSelectedUsersFromForm() {
        if (restoreSelectedUsersFromStorage()) {
            updateFormInputs();
            return;
        }
        const leaderId  = document.getElementById('selected-leader-id')?.value;
        const memberIds = document.getElementById('selected-members-ids')?.value.split(',').filter(id => id) || [];
        if (leaderId) selectedUsers.leader = getUserById(leaderId);
        selectedUsers.members = memberIds.map(id => getUserById(id)).filter(Boolean);
    }

    if (currentUser) {
        addUsersToAllUsers([currentUser]);
    }

    loadSelectedUsersFromForm();
    renderSelectedUsers();
    setupModalFilters();
    setupDateValidation();
    updateTaskSectionVisibility();
    updateSelectedUsersBadge();

    const oldTasks = JSON.parse(container.dataset.oldTasks || '[]');
    initializeTaskRows(oldTasks);

    const pForm = document.getElementById('projectForm');
    if (pForm) pForm.addEventListener('submit', onSubmitProjectForm);

    const collaborativeToggle = document.getElementById('project-collaborative-toggle');
    const toggleLabel         = document.getElementById('toggle-label');
    if (collaborativeToggle && toggleLabel) {
        toggleLabel.textContent = collaborativeToggle.checked ? 'Aktif' : 'Nonaktif';
        toggleUserSelectionSection();
        collaborativeToggle.addEventListener('change', function () {
            toggleLabel.textContent = this.checked ? 'Aktif' : 'Nonaktif';
            toggleUserSelectionSection();
        });
    }

    if (window.showPageInfo) {
        window.showPageInfo("popup.user_create_project");
    }
};

/* ==========================================
   VIEWS_EDIT_PROJECT.BLADE.PHP SCRIPTS
   ========================================== */
window.initProjectEditPage = function(container) {
    let allUsersMap = new Map();
    let allUsersArray = [];
    let pendingUsers = { owner: null, leader: null, members: [] };
    let selectedUsers = { owner: null, leader: null, members: [] };
    let taskIndex = 0;
    let currentModalFilters = { search: '', angkatan: '', jurusan: '', keahlian: '' };
    let tempSelectedRoles = {};
    let isCollaborativeMode = false;

    const initialUsers = JSON.parse(container.dataset.users || '[]');
    const currentUser = JSON.parse(container.dataset.currentUser || 'null');
    const existingTasksData = JSON.parse(container.dataset.existingTasks || '[]');
    const projectLeaderId = container.dataset.leaderId ? parseInt(container.dataset.leaderId) : null;
    const projectMemberIds = JSON.parse(container.dataset.memberIds || '[]');

    function addUsersToMap(users) {
        if (!Array.isArray(users)) return;
        users.forEach(user => {
            if (user && user.id && !allUsersMap.has(String(user.id))) {
                allUsersMap.set(String(user.id), user);
                allUsersArray.push(user);
            }
        });
    }

    function getUserById(id) {
        const userId = String(id);
        if (allUsersMap.has(userId)) {
            return allUsersMap.get(userId);
        }
        return null;
    }

    addUsersToMap(initialUsers);
    if (currentUser) addUsersToMap([currentUser]);

    function syncPendingFromSelected() {
        pendingUsers = {
            owner: selectedUsers.owner ? { ...selectedUsers.owner } : null,
            leader: selectedUsers.leader ? { ...selectedUsers.leader } : null,
            members: selectedUsers.members.map(m => ({ ...m }))
        };
    }

    function applyPendingToSelected() {
        selectedUsers.owner = pendingUsers.owner ? { ...pendingUsers.owner } : null;
        selectedUsers.leader = pendingUsers.leader ? { ...pendingUsers.leader } : null;
        selectedUsers.members = pendingUsers.members.map(m => ({ ...m }));
    }

    function saveCurrentModalRoles() {
        tempSelectedRoles = {};
        document.querySelectorAll('.user-role-select').forEach(select => {
            const userId = select.dataset.userId;
            if (userId && select.value) {
                tempSelectedRoles[userId] = select.value;
            }
        });
    }

    function restoreModalRoles() {
        document.querySelectorAll('.user-role-select').forEach(select => {
            const userId = select.dataset.userId;
            if (userId && tempSelectedRoles[userId]) {
                select.value = tempSelectedRoles[userId];
            }
        });
        refreshLeaderOptions();
    }

    function getAllowedTaskUsers(additionalUsers = []) {
        const users = [];
        const added = new Set();
        const add = user => {
            if (!user || added.has(String(user.id))) return;
            added.add(String(user.id));
            users.push({ id: user.id, name: user.nama_mahasiswa });
        };

        add(selectedUsers.owner);
        add(selectedUsers.leader);
        selectedUsers.members.forEach(add);
        additionalUsers.forEach(add);
        return users;
    }

    function renderTaskUserOptions(selectedId = '') {
        const additionalUsers = [];
        let fallbackName = null;

        if (selectedId) {
            const selectedTaskUser = getUserById(selectedId);
            if (selectedTaskUser) {
                additionalUsers.push(selectedTaskUser);
            } else {
                const taskData = existingTasksData.find(t => String(t.user_id) === String(selectedId));
                if (taskData && taskData.user_name) {
                    fallbackName = taskData.user_name;
                }
            }
        }

        const users = getAllowedTaskUsers(additionalUsers);
        let html = '<option value="">-- Pilih Penanggung Jawab --</option>';
        users.forEach(user => {
            html += `<option value="${user.id}" ${String(user.id) === String(selectedId) ? 'selected' : ''}>${user.name}</option>`;
        });

        if (selectedId && fallbackName && !users.some(u => String(u.id) === String(selectedId))) {
            html += `<option value="${selectedId}" selected>${fallbackName}</option>`;
        }

        return html;
    }

    window.addTaskRow = function(taskData = null) {
        const tContainer = document.getElementById('tasks-container');
        if (!tContainer) return;

        const index = taskIndex++;
        const userId = taskData?.user_id ?? '';
        const taskName = taskData?.name_task ? taskData.name_task.replace(/"/g, '&quot;') : '';
        const hiddenId = taskData?.id ? `<input type="hidden" name="tasks[${index}][id]" value="${taskData.id}">` : '';

        const taskItem = document.createElement('div');
        taskItem.className = 'task-item p-4 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900';
        
        if (!isCollaborativeMode) {
            taskItem.innerHTML = `
                ${hiddenId}
                <input type="hidden" name="tasks[${index}][user_id]" value="${currentUser.id}">
                <div class="grid gap-4 md:grid-cols-3 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Penanggung Jawab</label>
                        <input type="text" value="${currentUser.nama_mahasiswa} (Owner)" 
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400" 
                               readonly disabled>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Nama Tugas</label>
                        <input type="text" name="tasks[${index}][name_task]" value="${taskName}" 
                               class="task-name-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white" 
                               placeholder="Deskripsikan tugas...">
                    </div>
                    <button type="button" onclick="removeTaskRow(this)" 
                            class="self-start mt-6 px-4 py-3 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl">Hapus</button>
                </div>
            `;
        } else {
            taskItem.innerHTML = `
                ${hiddenId}
                <div class="grid gap-4 md:grid-cols-3 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Penanggung Jawab</label>
                        <select name="tasks[${index}][user_id]" class="task-user-select w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                            ${renderTaskUserOptions(userId)}
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Nama Tugas</label>
                        <input type="text" name="tasks[${index}][name_task]" value="${taskName}" 
                               class="task-name-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white" 
                               placeholder="Deskripsikan tugas...">
                    </div>
                    <button type="button" onclick="removeTaskRow(this)" 
                            class="self-start mt-6 px-4 py-3 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl">Hapus</button>
                </div>
            `;
        }

        tContainer.appendChild(taskItem);
        
        if (isCollaborativeMode) {
            const select = taskItem.querySelector('.task-user-select');
            if (select) select.addEventListener('change', () => updateTaskUserOptions());
        }
    }

    window.removeTaskRow = function(button) {
        const taskItem = button.closest('.task-item');
        if (taskItem) taskItem.remove();
        if (!document.querySelectorAll('.task-item').length) window.addTaskRow();
    }

    function updateTaskUserOptions() {
        if (!isCollaborativeMode) return;
        
        setTimeout(() => {
            document.querySelectorAll('.task-user-select').forEach(select => {
                const currentValue = select.value;
                const newOptions = renderTaskUserOptions(currentValue);
                select.innerHTML = newOptions;
                if (currentValue) select.value = currentValue;
            });
        }, 50);
    }

    function saveCurrentTasks() {
        const tasks = [];
        document.querySelectorAll('.task-item').forEach(taskItem => {
            const userIdInput = taskItem.querySelector('input[name$="[user_id]"], select[name$="[user_id]"]');
            const taskNameInput = taskItem.querySelector('input[name$="[name_task]"]');
            const taskIdInput = taskItem.querySelector('input[name$="[id]"]');
            
            let userId = null;
            if (userIdInput) {
                userId = userIdInput.value;
            }
            
            const taskName = taskNameInput ? taskNameInput.value : '';
            const taskId = taskIdInput ? taskIdInput.value : null;
            
            if (taskName) {
                tasks.push({
                    id: taskId,
                    user_id: userId || currentUser.id,
                    name_task: taskName
                });
            }
        });
        
        return tasks;
    }

    function restoreTasks(tasks) {
        const tContainer = document.getElementById('tasks-container');
        if (!tContainer) return;
        
        tContainer.innerHTML = '';
        taskIndex = 0;
        
        if (tasks && tasks.length > 0) {
            tasks.forEach(task => {
                if (task.name_task) {
                    window.addTaskRow(task);
                }
            });
        }
        
        if (tContainer.children.length === 0) {
            window.addTaskRow();
        }
        
        if (isCollaborativeMode) {
            setTimeout(() => updateTaskUserOptions(), 100);
        }
    }

    function initializeTaskRows() {
        const tContainer = document.getElementById('tasks-container');
        if (!tContainer) return;
        tContainer.innerHTML = '';
        taskIndex = 0;

        if (Array.isArray(existingTasksData) && existingTasksData.length) {
            existingTasksData.forEach(task => window.addTaskRow(task));
        }
        
        if (tContainer.children.length === 0) window.addTaskRow();
        
        if (isCollaborativeMode) {
            setTimeout(() => updateTaskUserOptions(), 100);
        }
    }

    function fetchUsers(page = 1) {
        const fetchUrl = container.dataset.fetchUrl;
        const params = new URLSearchParams({
            id: container.dataset.projectId,
            page: page,
            search: currentModalFilters.search,
            angkatan: currentModalFilters.angkatan,
            jurusan: currentModalFilters.jurusan,
            keahlian: currentModalFilters.keahlian
        });

        fetch(`${fetchUrl}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.userListHtml;
            
            const userElements = tempDiv.querySelectorAll('[data-user-id]');
            const usersInPage = [];
            
            userElements.forEach(el => {
                const userId = el.getAttribute('data-user-id');
                const nameEl = el.querySelector('.font-medium');
                const emailEl = el.querySelector('.text-sm.text-gray-500');
                const imgEl = el.querySelector('img');
                
                if (userId && nameEl) {
                    const user = {
                        id: parseInt(userId),
                        nama_mahasiswa: nameEl.textContent.trim(),
                        email: emailEl ? emailEl.textContent.trim() : '',
                        photo_profile: imgEl ? imgEl.getAttribute('src')?.replace('/storage/', '') : null
                    };
                    usersInPage.push(user);
                }
            });
            
            addUsersToMap(usersInPage);
            
            document.getElementById('modal-user-list').innerHTML = data.userListHtml;
            document.getElementById('modal-pagination').innerHTML = data.paginationHtml;
            
            attachRoleSelectEvents();
            restoreModalRoles();
            
            document.querySelectorAll('.user-role-select').forEach(select => {
                const userId = select.dataset.userId;
                if (pendingUsers.leader && String(pendingUsers.leader.id) === String(userId)) {
                    select.value = 'leader';
                } else if (pendingUsers.members.some(m => String(m.id) === String(userId))) {
                    select.value = 'member';
                }
            });
            
            refreshLeaderOptions();
            if (typeof window.refreshTranslations === 'function') window.refreshTranslations();
        })
        .catch(error => console.error('Error fetching users:', error));
    }

    function attachRoleSelectEvents() {
        document.querySelectorAll('.user-role-select').forEach(select => {
            select.addEventListener('change', function() {
                const userId = this.dataset.userId;
                const role = this.value;
                const user = getUserById(userId);
                
                if (!user) return;
                
                if (role === 'leader' && pendingUsers.leader && String(pendingUsers.leader.id) !== String(userId)) {
                    const confirmChange = confirm(`Anda yakin ingin mengganti leader dari "${pendingUsers.leader.nama_mahasiswa}" menjadi "${user.nama_mahasiswa}"?`);
                    if (!confirmChange) {
                        this.value = '';
                        return;
                    }
                    
                    const oldLeaderSelect = document.querySelector(`.user-role-select[data-user-id="${pendingUsers.leader.id}"]`);
                    if (oldLeaderSelect) oldLeaderSelect.value = '';
                }
                
                if (role === 'leader') {
                    pendingUsers.leader = user;
                    pendingUsers.members = pendingUsers.members.filter(m => String(m.id) !== String(userId));
                } else if (role === 'member') {
                    if (pendingUsers.leader && String(pendingUsers.leader.id) === String(userId)) {
                        pendingUsers.leader = null;
                    }
                    if (!pendingUsers.members.some(m => String(m.id) === String(userId))) {
                        pendingUsers.members.push(user);
                    }
                } else {
                    if (pendingUsers.leader && String(pendingUsers.leader.id) === String(userId)) {
                        pendingUsers.leader = null;
                    }
                    pendingUsers.members = pendingUsers.members.filter(m => String(m.id) !== String(userId));
                }
                
                refreshLeaderOptions();
            });
        });
    }

    function refreshLeaderOptions() {
        const hasLeader = !!pendingUsers.leader;
        document.querySelectorAll('.user-role-select').forEach(select => {
            const userId = select.dataset.userId;
            const optionLeader = select.querySelector('option[value="leader"]');
            
            if (optionLeader) {
                if (hasLeader && (!pendingUsers.leader || String(pendingUsers.leader.id) !== String(userId))) {
                    optionLeader.disabled = true;
                } else {
                    optionLeader.disabled = false;
                }
            }
        });
    }

    window.openUserModal = function() {
        if (!isCollaborativeMode) {
            alert('Harap aktifkan mode kolaboratif terlebih dahulu untuk menambah user.');
            return;
        }
        syncPendingFromSelected();
        document.getElementById('userModal').classList.remove('hidden');
        fetchUsers(1);
    }

    window.closeUserModal = function() {
        document.getElementById('userModal').classList.add('hidden');
    }

    window.cancelUserModal = function() {
        document.getElementById('userModal').classList.add('hidden');
    }

    window.confirmUserSelection = function() {
        applyPendingToSelected();
        updateFormInputs();
        renderSelectedUsers();
        updateTaskUserOptions();
        updateSelectedUsersBadge();
        document.getElementById('userModal').classList.add('hidden');
    }

    function updateSelectedUsersBadge() {
        const badge = document.getElementById('selected-users-badge');
        if (!badge) return;
        let count = 0;
        if (selectedUsers.owner) count++;
        if (selectedUsers.leader) count++;
        count += selectedUsers.members.length;
        badge.innerHTML = count === 0
            ? ''
            : `<span class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full text-xs font-semibold">${count} user(s) terpilih</span>`;
    }

    function updateFormInputs() {
        const ownerEl = document.getElementById('selected-owner-id');
        const leaderEl = document.getElementById('selected-leader-id');
        const membersEl = document.getElementById('selected-members-ids');
        if (ownerEl) ownerEl.value = selectedUsers.owner?.id || '';
        if (leaderEl) leaderEl.value = selectedUsers.leader?.id || '';
        if (membersEl) membersEl.value = selectedUsers.members.map(m => m.id).join(',');

        const memberInputs = document.getElementById('selected-members-inputs');
        if (memberInputs) {
            memberInputs.innerHTML = selectedUsers.members
                .map(member => `<input type="hidden" name="members[]" value="${member.id}">`)
                .join('');
        }
    }

    function renderSelectedUsers() {
        const containerSelected = document.getElementById('selected-users-container');
        const noUsersMsg = document.getElementById('no-users-message');
        if (!containerSelected || !noUsersMsg) return;

        const selected = [];
        if (selectedUsers.owner) {
            const role = selectedUsers.leader && selectedUsers.leader.id === selectedUsers.owner.id
                ? 'Owner & Leader' : 'Owner';
            selected.push({ ...selectedUsers.owner, role });
        }
        if (selectedUsers.leader && (!selectedUsers.owner || selectedUsers.owner.id !== selectedUsers.leader.id)) {
            selected.push({ ...selectedUsers.leader, role: 'Leader' });
        }
        selectedUsers.members.forEach(member => selected.push({ ...member, role: 'Member' }));

        if (!selected.length) {
            containerSelected.innerHTML = '';
            noUsersMsg.classList.remove('hidden');
            return;
        }

        noUsersMsg.classList.add('hidden');
        containerSelected.innerHTML = selected.map(user => {
            const styles = {
                'Owner': 'bg-blue-50 dark:bg-blue-950 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
                'Leader': 'bg-green-50 dark:bg-green-950 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
                'Owner & Leader': 'bg-teal-50 dark:bg-teal-950 border-teal-200 dark:border-teal-800 text-teal-800 dark:text-teal-200',
                'Member': 'bg-purple-50 dark:bg-purple-950 border-purple-200 dark:border-purple-800 text-purple-800 dark:text-purple-200'
            }[user.role] || 'bg-gray-50 dark:bg-gray-950 border-gray-200 dark:border-gray-800 text-gray-800 dark:text-gray-200';

            return `
                <div class="flex items-center justify-between p-4 border rounded-2xl ${styles}">
                    <div class="flex items-center gap-3">
                        ${user.photo_profile
                            ? `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800">`
                            : `<div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center ring-2 ring-white dark:ring-gray-800">
                                   <span class="font-semibold text-current">${user.nama_mahasiswa.charAt(0).toUpperCase()}</span>
                               </div>`
                        }
                        <div>
                            <div class="font-medium">${user.role}: ${user.nama_mahasiswa}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">${user.email || ''}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="editUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80" title="Edit Role">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        ${user.role !== 'Owner' ? `
                        <button type="button" onclick="removeUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80" title="Remove">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>` : ''}
                    </div>
                </div>
            `;
        }).join('');
    }

    window.removeUser = function(userId) {
        if (selectedUsers.leader?.id == userId) selectedUsers.leader = null;
        selectedUsers.members = selectedUsers.members.filter(m => String(m.id) !== String(userId));
        updateFormInputs();
        renderSelectedUsers();
        updateTaskUserOptions();
        updateSelectedUsersBadge();
    }

    window.editUser = function(userId) {
        window.openUserModal();
        setTimeout(() => {
            const userElement = document.querySelector(`.user-role-select[data-user-id="${userId}"]`)?.closest('[data-user-id]');
            if (userElement) {
                userElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                userElement.style.backgroundColor = '#fef3c7';
                setTimeout(() => userElement.style.backgroundColor = '', 2000);
            }
        }, 500);
    }

    function toggleUserSelectionSection() {
        const toggle = document.getElementById('project-collaborative-toggle');
        const userSelectionSection = document.getElementById('user-selection-section');

        if (!toggle || !userSelectionSection) return;

        if (toggle.checked) {
            userSelectionSection.style.display = 'block';
            isCollaborativeMode = true;
            
            if (selectedUsers.leader && selectedUsers.owner && String(selectedUsers.leader.id) === String(selectedUsers.owner.id) && selectedUsers.members.length === 0) {
                selectedUsers.leader = null;
                updateFormInputs();
                renderSelectedUsers();
                updateSelectedUsersBadge();
            }
            
            const currentTasks = saveCurrentTasks();
            restoreTasks(currentTasks);
        } else {
            userSelectionSection.style.display = 'none';
            isCollaborativeMode = false;
            
            selectedUsers.leader = null;
            selectedUsers.members = [];
            
            updateFormInputs();
            renderSelectedUsers();
            updateSelectedUsersBadge();
            
            const currentTasks = saveCurrentTasks();
            restoreTasks(currentTasks);
        }
    }

    function setupDateValidation() {
        const tanggalMulaiInput = document.getElementById('tanggal_mulai');
        const tanggalAkhirInput = document.getElementById('tanggal_akhir');
        
        if (!tanggalMulaiInput || !tanggalAkhirInput) return;

        if (tanggalMulaiInput.value) {
            tanggalAkhirInput.min = tanggalMulaiInput.value;
        }

        tanggalMulaiInput.addEventListener('change', function() {
            if (this.value) {
                tanggalAkhirInput.min = this.value;
                if (tanggalAkhirInput.value && tanggalAkhirInput.value < this.value) {
                    tanggalAkhirInput.value = '';
                }
            } else {
                tanggalAkhirInput.min = '';
            }
        });

        tanggalAkhirInput.addEventListener('change', function() {
            if (this.value && tanggalMulaiInput.value && this.value < tanggalMulaiInput.value) {
                this.value = '';
                alert('Tanggal selesai harus setelah atau sama dengan tanggal mulai.');
            }
        });
    }

    function loadSelectedUsersFromForm() {
        selectedUsers.owner = currentUser;
        
        if (projectLeaderId) {
            const lead = getUserById(projectLeaderId);
            if (lead) selectedUsers.leader = lead;
        }
        
        if (projectMemberIds && projectMemberIds.length > 0) {
            selectedUsers.members = projectMemberIds.map(id => getUserById(id)).filter(Boolean);
        }
        
        updateFormInputs();
    }

    function setupModalFilters() {
        const searchInput = document.getElementById('modal-search');
        const angkatanSelect = document.getElementById('modal-angkatan');
        const jurusanSelect = document.getElementById('modal-jurusan');
        const keahlianSelect = document.getElementById('modal-keahlian');
        
        const fetchWithSave = (page) => {
            saveCurrentModalRoles();
            fetchUsers(page);
        };
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                currentModalFilters.search = this.value;
                fetchWithSave(1);
            });
        }
        
        if (angkatanSelect) {
            angkatanSelect.addEventListener('change', function() {
                currentModalFilters.angkatan = this.value;
                fetchWithSave(1);
            });
        }
        
        if (jurusanSelect) {
            jurusanSelect.addEventListener('change', function() {
                currentModalFilters.jurusan = this.value;
                fetchWithSave(1);
            });
        }
        
        if (keahlianSelect) {
            keahlianSelect.addEventListener('change', function() {
                currentModalFilters.keahlian = this.value;
                fetchWithSave(1);
            });
        }
    }

    const pagListener = function(e) {
        const link = e.target.closest('#modal-pagination a');
        if (link) {
            e.preventDefault();
            const url = new URL(link.href);
            const page = url.searchParams.get('page') || 1;
            saveCurrentModalRoles();
            fetchUsers(page);
        }
    };
    document.addEventListener('click', pagListener);

    loadSelectedUsersFromForm();
    renderSelectedUsers();
    
    const collaborativeToggle = document.getElementById('project-collaborative-toggle');
    const toggleLabel = document.getElementById('toggle-label');
    
    if (collaborativeToggle && toggleLabel) {
        const hasMembers = selectedUsers.members.length > 0 || selectedUsers.leader;
        collaborativeToggle.checked = hasMembers;
        toggleLabel.textContent = hasMembers ? 'Aktif' : 'Nonaktif';
        isCollaborativeMode = hasMembers;
        
        toggleUserSelectionSection();
        initializeTaskRows();
        
        collaborativeToggle.addEventListener('change', function() {
            toggleLabel.textContent = this.checked ? 'Aktif' : 'Nonaktif';
            toggleUserSelectionSection();
        });
    } else {
        initializeTaskRows();
    }
    
    setupDateValidation();
    setupModalFilters();

    if (window.showPageInfo) {
        window.showPageInfo("popup.user_edit_project");
    }
};

/* ==========================================
   VIEWS_SERTIFIKAT.BLADE.PHP SCRIPTS
   ========================================== */
window.initSertifikatListPage = function(container) {
    window.filterStatus = function(status) {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('bg-indigo-600', 'text-white', 'hover:bg-indigo-700');
            if (btn.dataset.filter === status) {
                btn.classList.add('bg-indigo-600', 'text-white', 'hover:bg-indigo-700');
            } else {
                const filterValue = btn.dataset.filter;
                btn.classList.remove('bg-indigo-600', 'text-white', 'hover:bg-indigo-700');

                if (filterValue === 'Sedang Di Ajukan') {
                    btn.classList.add('bg-yellow-100', 'text-yellow-800', 'hover:bg-yellow-200', 'dark:bg-yellow-900/30', 'dark:text-yellow-300');
                } else if (filterValue === 'Di Terima') {
                    btn.classList.add('bg-green-100', 'text-green-800', 'hover:bg-green-200', 'dark:bg-green-900/30', 'dark:text-green-300');
                } else if (filterValue === 'Di Tolak') {
                    btn.classList.add('bg-red-100', 'text-red-800', 'hover:bg-red-200', 'dark:bg-red-900/30', 'dark:text-red-300');
                } else {
                    btn.classList.add('bg-gray-100', 'text-gray-800', 'hover:bg-gray-200', 'dark:bg-gray-700', 'dark:text-gray-300');
                }
            }
        });

        const cards = document.querySelectorAll('.sertifikat-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardStatus = card.dataset.status;
            if (status === 'all' || cardStatus === status) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const noDataMessage = document.querySelector('.no-data-message');
        if (visibleCount === 0) {
            if (!noDataMessage) {
                const gridContainer = document.querySelector('.grid');
                if (gridContainer) {
                    const message = document.createElement('div');
                    message.className = 'no-data-message col-span-full text-center py-12 bg-gray-50 dark:bg-gray-900 dark:border-gray-900 rounded-xl border border-gray-200';
                    message.innerHTML = `
                            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-4 text-gray-600 dark:text-gray-200" data-translate="no_data_filtered" data-translate-page="sertifikat"></p>
                        `;
                    gridContainer.parentNode.insertBefore(message, gridContainer.nextSibling);
                }
            }
        } else {
            const existingMessage = document.querySelector('.no-data-message');
            if (existingMessage) {
                existingMessage.remove();
            }
        }

        localStorage.setItem('sertifikatFilter', status);
    };

    container.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data sertifikat akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                if (confirm("Apakah Anda yakin ingin menghapus data sertifikat ini?")) {
                    form.submit();
                }
            }
        });
    });

    if (window.showPageInfo) {
        window.showPageInfo("popup.semua_sertifikat");
    }
};

/* ==========================================
   VIEWS_SERTIFIKAT_USER.BLADE.PHP SCRIPTS
   ========================================== */
window.initSertifikatUserPage = function(container) {
    container.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();
            const form = this.closest('form');

            if (typeof Swal !== 'undefined') {
                const confirmed = await Swal.fire({
                    title: 'Hapus Sertifikat?',
                    text: 'Sertifikat ini akan dihapus permanen dan tidak bisa dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                });

                if (confirmed.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    form.submit();
                }
            } else {
                if (confirm('Sertifikat ini akan dihapus permanen dan tidak bisa dikembalikan.')) {
                    form.submit();
                }
            }
        });
    });

    if (window.showPageInfo) {
        window.showPageInfo("popup.sertifikat_saya");
    }
};

/* ==========================================
   VIEWS_CREATE_SERTIFIKAT.BLADE.PHP SCRIPTS
   ========================================== */
window.initSertifikatCreatePage = function(container) {
    const fileInput = document.getElementById('link_sertifikat');
    const fileNameElement = document.getElementById('file-name');
    const previewContainer = document.getElementById('image-preview-container');
    const previewImage = document.getElementById('image-preview');

    window.updateFileLabel = function(input) {
        const fileName = input.files[0]?.name;

        if (fileName) {
            if (fileNameElement) fileNameElement.textContent = fileName;

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    if (previewImage) previewImage.src = e.target.result;
                    if (previewContainer) previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        } else {
            const defaultText = fileNameElement ? (fileNameElement.getAttribute('data-translate') || 'PNG, JPG, GIF up to 5MB') : 'PNG, JPG, GIF up to 5MB';
            if (fileNameElement) fileNameElement.textContent = defaultText;
            if (previewContainer) previewContainer.classList.add('hidden');
            if (previewImage) previewImage.src = '#';
        }
    };

    const dropZone = container.querySelector('.border-dashed');
    if (dropZone && fileInput) {
        const preventDefaults = (e) => {
            e.preventDefault();
            e.stopPropagation();
        };

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('border-indigo-500', 'bg-indigo-50'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('border-indigo-500', 'bg-indigo-50'), false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                fileInput.files = files;
                window.updateFileLabel(fileInput);
                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
        }, false);
    }

    const tanggalTerbitInput = document.getElementById('tanggal_terbit');
    const expiredDateInput = document.getElementById('expired_date');
    const permanentCheckbox = document.getElementById('permanent');
    const expiredDateBlock = document.getElementById('expired_date_block');
    
    function updateExpiredDateState() {
        if (!expiredDateInput) return;
        const isPermanent = permanentCheckbox?.checked;

        if (isPermanent) {
            expiredDateInput.value = '';
            expiredDateInput.disabled = true;
            expiredDateInput.required = false;
            expiredDateInput.classList.add('opacity-60');
            if (expiredDateBlock) expiredDateBlock.classList.add('opacity-60');
        } else {
            expiredDateInput.disabled = false;
            expiredDateInput.required = true;
            expiredDateInput.classList.remove('opacity-60');
            if (expiredDateBlock) expiredDateBlock.classList.remove('opacity-60');
        }
    }

    function validateExpiredDate() {
        if (permanentCheckbox?.checked) {
            if (expiredDateInput) expiredDateInput.setCustomValidity('');
            return true;
        }

        if (tanggalTerbitInput && expiredDateInput && tanggalTerbitInput.value && expiredDateInput.value) {
            const tanggalTerbit = new Date(tanggalTerbitInput.value);
            const expiredDate = new Date(expiredDateInput.value);
            
            if (expiredDate <= tanggalTerbit) {
                expiredDateInput.setCustomValidity('Tanggal expired harus setelah tanggal terbit');
                expiredDateInput.reportValidity();
                return false;
            } else {
                expiredDateInput.setCustomValidity('');
                return true;
            }
        }
        return true;
    }
    
    if (tanggalTerbitInput && expiredDateInput) {
        tanggalTerbitInput.addEventListener('change', function() {
            if (this.value) {
                const nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                expiredDateInput.min = nextDay.toISOString().split('T')[0];
            }
            validateExpiredDate();
        });
        expiredDateInput.addEventListener('change', validateExpiredDate);
    }

    if (permanentCheckbox) {
        permanentCheckbox.addEventListener('change', updateExpiredDateState);
    }

    updateExpiredDateState();
    
    const form = container.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            updateExpiredDateState();
            if (!validateExpiredDate()) {
                e.preventDefault();
            }
        });
    }

    if (window.showPageInfo) {
        window.showPageInfo("popup.user_create_sertifikat");
    }
};

/* ==========================================
   VIEWS_EDIT_SERTIFIKAT.BLADE.PHP SCRIPTS
   ========================================== */
window.initSertifikatEditPage = function(container) {
    const fileInput = document.getElementById('link_sertifikat');
    const fileNameElement = document.getElementById('file-name');
    const previewContainer = document.getElementById('image-preview-container');
    const previewImage = document.getElementById('image-preview');
    const currentPreview = document.getElementById('current-image-preview');
    const originalFileName = container.dataset.originalFileName || '';

    window.updateFileLabel = function(input) {
        const fileName = input.files[0]?.name;

        if (fileName) {
            if (fileNameElement) fileNameElement.textContent = fileName;

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    if (previewImage) previewImage.src = e.target.result;
                    if (previewContainer) previewContainer.classList.remove('hidden');
                    if (currentPreview) currentPreview.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        } else {
            if (fileNameElement) {
                if (originalFileName) {
                    fileNameElement.textContent = originalFileName;
                } else {
                    fileNameElement.textContent = fileNameElement.getAttribute('data-translate') || 'PNG, JPG, GIF up to 5MB';
                }
            }
            if (previewContainer) previewContainer.classList.add('hidden');
            if (previewImage) previewImage.src = '#';
            if (currentPreview) currentPreview.classList.remove('hidden');
        }
    };

    window.toggleFileUpload = function(checkbox) {
        const fileUploadSection = document.getElementById('file-upload-section');

        if (checkbox.checked) {
            if (fileUploadSection) fileUploadSection.classList.remove('hidden');
            if (currentPreview) currentPreview.classList.add('hidden');

            if (fileInput) {
                fileInput.value = '';
                window.updateFileLabel(fileInput);
            }
        } else {
            if (fileUploadSection) fileUploadSection.classList.add('hidden');
            if (currentPreview) currentPreview.classList.remove('hidden');

            if (previewContainer) previewContainer.classList.add('hidden');
        }
    };

    const dropZone = container.querySelector('.border-dashed');
    if (dropZone && fileInput) {
        const preventDefaults = (e) => {
            e.preventDefault();
            e.stopPropagation();
        };

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('border-indigo-500', 'bg-indigo-50'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('border-indigo-500', 'bg-indigo-50'), false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files && files.length > 0) {
                const replaceCheckbox = document.getElementById('replace-file-checkbox');
                if (replaceCheckbox && !replaceCheckbox.checked) {
                    replaceCheckbox.checked = true;
                    window.toggleFileUpload(replaceCheckbox);
                }

                fileInput.files = files;
                window.updateFileLabel(fileInput);

                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
        }, false);
    }

    const tanggalTerbitInput = document.getElementById('tanggal_terbit');
    const expiredDateInput = document.getElementById('expired_date');
    const permanentCheckbox = document.getElementById('permanent');
    const expiredDateBlock = document.getElementById('expired_date_block');
    
    function updateExpiredDateState() {
        if (!expiredDateInput) return;
        const isPermanent = permanentCheckbox?.checked;

        if (isPermanent) {
            expiredDateInput.value = '';
            expiredDateInput.disabled = true;
            expiredDateInput.required = false;
            expiredDateInput.classList.add('opacity-60');
            if (expiredDateBlock) expiredDateBlock.classList.add('opacity-60');
        } else {
            expiredDateInput.disabled = false;
            expiredDateInput.required = true;
            expiredDateInput.classList.remove('opacity-60');
            if (expiredDateBlock) expiredDateBlock.classList.remove('opacity-60');
        }
    }

    function validateExpiredDate() {
        if (permanentCheckbox?.checked) {
            if (expiredDateInput) expiredDateInput.setCustomValidity('');
            return true;
        }

        if (tanggalTerbitInput && expiredDateInput && tanggalTerbitInput.value && expiredDateInput.value) {
            const tanggalTerbit = new Date(tanggalTerbitInput.value);
            const expiredDate = new Date(expiredDateInput.value);
            
            if (expiredDate <= tanggalTerbit) {
                expiredDateInput.setCustomValidity('Tanggal expired harus setelah tanggal terbit');
                expiredDateInput.reportValidity();
                return false;
            } else {
                expiredDateInput.setCustomValidity('');
                return true;
            }
        }
        return true;
    }
    
    if (tanggalTerbitInput && expiredDateInput) {
        tanggalTerbitInput.addEventListener('change', function() {
            if (this.value) {
                const nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                expiredDateInput.min = nextDay.toISOString().split('T')[0];
            }
            validateExpiredDate();
        });
        
        expiredDateInput.addEventListener('change', validateExpiredDate);
        validateExpiredDate();
    }

    if (permanentCheckbox) {
        permanentCheckbox.addEventListener('change', updateExpiredDateState);
    }

    updateExpiredDateState();

    const form = container.querySelector('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (!validateExpiredDate()) {
                e.preventDefault();
                return false;
            }
            
            const replaceCheckbox = document.getElementById('replace-file-checkbox');
            if (fileInput && fileInput.files.length > 0 && replaceCheckbox && !replaceCheckbox.checked) {
                replaceCheckbox.checked = true;
                window.toggleFileUpload(replaceCheckbox);
            }
        });
    }

    if (window.showPageInfo) {
        window.showPageInfo("popup.user_edit_sertifikat");
    }
};

/* ==========================================
   PAGINATION AJAX & SCROLL RESTORATION HELPERS
   ========================================== */
document.addEventListener('click', function(e) {
    const link = e.target.closest('.pagination-link');
    if (link) {
        const groupName = link.getAttribute('data-group');
        sessionStorage.setItem('scrollToGroup', groupName);
    }
});

function handlePaginationScroll() {
    const groupName = sessionStorage.getItem('scrollToGroup');
    if (groupName) {
        const element = document.querySelector(`[data-pagination-group="${groupName}"]`);
        if (element) {
            setTimeout(function () {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }
        sessionStorage.removeItem('scrollToGroup');
    }
}
document.addEventListener('DOMContentLoaded', handlePaginationScroll);
document.addEventListener('turbo:load', handlePaginationScroll);

// ==========================================
//   PROJECT EDIT PAGE
// ==========================================
(function() {
    // ----- state -----
    let allUsersMap = new Map();
    let allUsersArray = [];
    let pendingUsers = { owner: null, leader: null, members: [] };
    let selectedUsers = { owner: null, leader: null, members: [] };
    let taskIndex = 0;
    let currentModalFilters = { search: '', angkatan: '', jurusan: '', keahlian: '' };
    let tempSelectedRoles = {};
    let isCollaborativeMode = false;
    let projectData = null;

    // ----- helpers -----
    function addUsersToMap(users) {
        if (!Array.isArray(users)) return;
        users.forEach(user => {
            if (user && user.id && !allUsersMap.has(String(user.id))) {
                allUsersMap.set(String(user.id), user);
                allUsersArray.push(user);
            }
        });
    }

    function getUserById(id) {
        const userId = String(id);
        if (allUsersMap.has(userId)) {
            return allUsersMap.get(userId);
        }
        return null;
    }

    // ----- pending / selected sync -----
    function syncPendingFromSelected() {
        pendingUsers = {
            owner: selectedUsers.owner ? { ...selectedUsers.owner } : null,
            leader: selectedUsers.leader ? { ...selectedUsers.leader } : null,
            members: selectedUsers.members.map(m => ({ ...m }))
        };
    }

    function applyPendingToSelected() {
        selectedUsers.owner = pendingUsers.owner ? { ...pendingUsers.owner } : null;
        selectedUsers.leader = pendingUsers.leader ? { ...pendingUsers.leader } : null;
        selectedUsers.members = pendingUsers.members.map(m => ({ ...m }));
    }

    function saveCurrentModalRoles() {
        tempSelectedRoles = {};
        document.querySelectorAll('.user-role-select').forEach(select => {
            const userId = select.dataset.userId;
            if (userId && select.value) {
                tempSelectedRoles[userId] = select.value;
            }
        });
    }

    function restoreModalRoles() {
        document.querySelectorAll('.user-role-select').forEach(select => {
            const userId = select.dataset.userId;
            if (userId && tempSelectedRoles[userId]) {
                select.value = tempSelectedRoles[userId];
            }
        });
        refreshLeaderOptions();
    }

    // ----- task functions -----
    function getAllowedTaskUsers(additionalUsers = []) {
        const users = [];
        const added = new Set();
        const add = user => {
            if (!user || added.has(String(user.id))) return;
            added.add(String(user.id));
            users.push({ id: user.id, name: user.nama_mahasiswa });
        };

        add(selectedUsers.owner);
        add(selectedUsers.leader);
        selectedUsers.members.forEach(add);
        additionalUsers.forEach(add);
        return users;
    }

    function renderTaskUserOptions(selectedId = '') {
        const additionalUsers = [];
        let fallbackName = null;

        if (selectedId) {
            const selectedTaskUser = getUserById(selectedId);
            if (selectedTaskUser) {
                additionalUsers.push(selectedTaskUser);
            } else {
                const taskData = (projectData?.existingTasks || []).find(t => String(t.user_id) === String(selectedId));
                if (taskData && taskData.user_name) {
                    fallbackName = taskData.user_name;
                }
            }
        }

        const users = getAllowedTaskUsers(additionalUsers);
        let html = '<option value="">-- Pilih Penanggung Jawab --</option>';
        users.forEach(user => {
            html += `<option value="${user.id}" ${String(user.id) === String(selectedId) ? 'selected' : ''}>${user.name}</option>`;
        });

        if (selectedId && fallbackName && !users.some(u => String(u.id) === String(selectedId))) {
            html += `<option value="${selectedId}" selected>${fallbackName}</option>`;
        }

        return html;
    }

    function addTaskRow(taskData = null) {
        const container = document.getElementById('tasks-container');
        if (!container) return;

        const index = taskIndex++;
        const userId = taskData?.user_id ?? '';
        const taskName = taskData?.name_task ? taskData.name_task.replace(/"/g, '&quot;') : '';
        const hiddenId = taskData?.id ? `<input type="hidden" name="tasks[${index}][id]" value="${taskData.id}">` : '';

        const taskItem = document.createElement('div');
        taskItem.className = 'task-item p-4 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900';

        if (!isCollaborativeMode) {
            const currentUser = projectData?.currentUser || { id: '', nama_mahasiswa: 'Unknown' };
            taskItem.innerHTML = `
                ${hiddenId}
                <input type="hidden" name="tasks[${index}][user_id]" value="${currentUser.id}">
                <div class="grid gap-4 md:grid-cols-3 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Penanggung Jawab</label>
                        <input type="text" value="${currentUser.nama_mahasiswa} (Owner)"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400"
                               readonly disabled>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Nama Tugas</label>
                        <input type="text" name="tasks[${index}][name_task]" value="${taskName}"
                               class="task-name-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                               placeholder="Deskripsikan tugas...">
                    </div>
                    <button type="button" onclick="window.removeTaskRow(this)"
                            class="self-start mt-6 px-4 py-3 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl">Hapus</button>
                </div>
            `;
        } else {
            taskItem.innerHTML = `
                ${hiddenId}
                <div class="grid gap-4 md:grid-cols-3 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Penanggung Jawab</label>
                        <select name="tasks[${index}][user_id]" class="task-user-select w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                            ${renderTaskUserOptions(userId)}
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Nama Tugas</label>
                        <input type="text" name="tasks[${index}][name_task]" value="${taskName}"
                               class="task-name-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                               placeholder="Deskripsikan tugas...">
                    </div>
                    <button type="button" onclick="window.removeTaskRow(this)"
                            class="self-start mt-6 px-4 py-3 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl">Hapus</button>
                </div>
            `;
        }

        container.appendChild(taskItem);

        if (isCollaborativeMode) {
            const select = taskItem.querySelector('.task-user-select');
            if (select) select.addEventListener('change', () => updateTaskUserOptions());
        }
    }

    function removeTaskRow(button) {
        const taskItem = button.closest('.task-item');
        if (taskItem) taskItem.remove();
        if (!document.querySelectorAll('.task-item').length) addTaskRow();
    }

    function updateTaskUserOptions() {
        if (!isCollaborativeMode) return;
        setTimeout(() => {
            document.querySelectorAll('.task-user-select').forEach(select => {
                const currentValue = select.value;
                const newOptions = renderTaskUserOptions(currentValue);
                select.innerHTML = newOptions;
                if (currentValue) select.value = currentValue;
            });
        }, 50);
    }

    function saveCurrentTasks() {
        const tasks = [];
        document.querySelectorAll('.task-item').forEach(taskItem => {
            const userIdInput = taskItem.querySelector('input[name$="[user_id]"], select[name$="[user_id]"]');
            const taskNameInput = taskItem.querySelector('input[name$="[name_task]"]');
            const taskIdInput = taskItem.querySelector('input[name$="[id]"]');
            let userId = null;
            if (userIdInput) userId = userIdInput.value;
            const taskName = taskNameInput ? taskNameInput.value : '';
            const taskId = taskIdInput ? taskIdInput.value : null;
            if (taskName) {
                tasks.push({
                    id: taskId,
                    user_id: userId || (projectData?.currentUser?.id || ''),
                    name_task: taskName
                });
            }
        });
        return tasks;
    }

    function restoreTasks(tasks) {
        const container = document.getElementById('tasks-container');
        if (!container) return;
        container.innerHTML = '';
        taskIndex = 0;
        if (tasks && tasks.length > 0) {
            tasks.forEach(task => {
                if (task.name_task) addTaskRow(task);
            });
        }
        if (container.children.length === 0) addTaskRow();
        if (isCollaborativeMode) setTimeout(() => updateTaskUserOptions(), 100);
    }

    // ----- modal / ajax -----
    function fetchUsers(page = 1) {
        if (!projectData) return;
        const params = new URLSearchParams({
            id: projectData.projectId,
            page: page,
            search: currentModalFilters.search,
            angkatan: currentModalFilters.angkatan,
            jurusan: currentModalFilters.jurusan,
            keahlian: currentModalFilters.keahlian
        });

        const url = `/project/edit/${projectData.projectId}?${params}`;
        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.userListHtml;

            const userElements = tempDiv.querySelectorAll('[data-user-id]');
            const usersInPage = [];
            userElements.forEach(el => {
                const userId = el.getAttribute('data-user-id');
                const nameEl = el.querySelector('.font-medium');
                const emailEl = el.querySelector('.text-sm.text-gray-500');
                const imgEl = el.querySelector('img');
                if (userId && nameEl) {
                    const user = {
                        id: parseInt(userId),
                        nama_mahasiswa: nameEl.textContent.trim(),
                        email: emailEl ? emailEl.textContent.trim() : '',
                        photo_profile: imgEl ? imgEl.getAttribute('src')?.replace('/storage/', '') : null
                    };
                    usersInPage.push(user);
                }
            });

            addUsersToMap(usersInPage);

            document.getElementById('modal-user-list').innerHTML = data.userListHtml;
            document.getElementById('modal-pagination').innerHTML = data.paginationHtml;

            attachRoleSelectEvents();
            restoreModalRoles();

            document.querySelectorAll('.user-role-select').forEach(select => {
                const userId = select.dataset.userId;
                if (pendingUsers.leader && String(pendingUsers.leader.id) === String(userId)) {
                    select.value = 'leader';
                } else if (pendingUsers.members.some(m => String(m.id) === String(userId))) {
                    select.value = 'member';
                }
            });

            refreshLeaderOptions();
            if (typeof window.refreshTranslations === 'function') window.refreshTranslations();
        })
        .catch(error => console.error('Error fetching users:', error));
    }

    function attachRoleSelectEvents() {
        document.querySelectorAll('.user-role-select').forEach(select => {
            const userId = select.getAttribute('data-user-id');
            if (userId) {
                select.removeEventListener('change', select._handler);
                const handler = function() {
                    updateUserRole(this, userId, this.value);
                    saveCurrentModalRoles();
                };
                select.addEventListener('change', handler);
                select._handler = handler;
            }
        });
    }

    function updateUserRole(selectElement, userId, role) {
        const user = getUserById(userId);
        if (!user) {
            console.error('User not found:', userId);
            return;
        }

        const isOwner = pendingUsers.owner && String(pendingUsers.owner.id) === String(userId);
        const isCurrentLeader = pendingUsers.leader && String(pendingUsers.leader.id) === String(userId);

        if (isOwner && role === 'member') {
            alert('Owner tidak bisa menjadi member.');
            selectElement.value = isCurrentLeader ? 'leader' : '';
            refreshLeaderOptions();
            return;
        }

        if (role === 'leader' && pendingUsers.leader && String(pendingUsers.leader.id) !== String(userId)) {
            const confirmChange = confirm(`Anda yakin ingin mengganti leader dari "${pendingUsers.leader.nama_mahasiswa}" menjadi "${user.nama_mahasiswa}"?`);
            if (!confirmChange) {
                selectElement.value = isCurrentLeader ? 'leader' : '';
                refreshLeaderOptions();
                return;
            }
            pendingUsers.leader = null;
        }

        if (pendingUsers.leader && String(pendingUsers.leader.id) === String(userId)) {
            pendingUsers.leader = null;
        }
        pendingUsers.members = pendingUsers.members.filter(m => String(m.id) !== String(userId));

        if (role === 'leader') {
            pendingUsers.leader = user;
        } else if (role === 'member') {
            if (!pendingUsers.members.some(m => String(m.id) === String(userId))) {
                pendingUsers.members.push(user);
            }
        }

        refreshLeaderOptions();
    }

    function refreshLeaderOptions() {
        const leaderId = pendingUsers.leader ? String(pendingUsers.leader.id) : null;
        document.querySelectorAll('.user-role-select').forEach(select => {
            const userId = select.dataset.userId;
            const isOwner = pendingUsers.owner && String(pendingUsers.owner.id) === String(userId);
            const leaderOption = select.querySelector('option[value="leader"]');
            if (!leaderOption) return;
            if (isOwner) {
                select.disabled = true;
                select.value = 'leader';
                return;
            }
            select.disabled = false;
            if (leaderId && String(userId) !== String(leaderId)) {
                leaderOption.disabled = true;
                leaderOption.textContent = 'Leader (Sudah Dipilih)';
                if (select.value === 'leader') {
                    select.value = '';
                }
            } else {
                leaderOption.disabled = false;
                leaderOption.textContent = 'Leader';
            }
        });
    }

    // ----- modal open/close -----
    function openUserModal() {
        const toggle = document.getElementById('project-collaborative-toggle');
        if (!toggle || !toggle.checked) {
            alert('Harap aktifkan mode kolaboratif terlebih dahulu untuk menambah user.');
            return;
        }
        syncPendingFromSelected();
        document.getElementById('userModal').classList.remove('hidden');
        fetchUsers(1);
    }

    function closeUserModal() {
        document.getElementById('userModal').classList.add('hidden');
    }

    function confirmUserSelection() {
        applyPendingToSelected();
        updateFormInputs();
        renderSelectedUsers();
        // Update tasks without losing data
        const currentTasks = saveCurrentTasks();
        isCollaborativeMode = true;
        restoreTasks(currentTasks);
        closeUserModal();
    }

    // ----- form / ui -----
    function updateFormInputs() {
        const ownerId = selectedUsers.owner?.id || (projectData?.currentUser?.id || '');
        document.getElementById('selected-owner-id').value = ownerId;
        document.getElementById('selected-leader-id').value = selectedUsers.leader?.id || '';

        const container = document.getElementById('members-hidden-container');
        if (!container) return;
        container.innerHTML = '';
        selectedUsers.members.forEach(member => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'members[]';
            input.value = member.id;
            container.appendChild(input);
        });
    }

    function renderSelectedUsers() {
        const container = document.getElementById('selected-users-container');
        const noMsg = document.getElementById('no-users-message');
        if (!container || !noMsg) return;

        const selected = [];
        if (selectedUsers.owner) {
            const isOwnerAsLeader = selectedUsers.leader && selectedUsers.leader.id === selectedUsers.owner.id;
            const role = isOwnerAsLeader ? 'Owner & Leader' : 'Owner';
            selected.push({ ...selectedUsers.owner, role, isOwner: true });
        } else if (projectData?.currentUser) {
            selected.push({ ...projectData.currentUser, role: 'Owner', isOwner: true });
        }

        if (selectedUsers.leader && (!selectedUsers.owner || selectedUsers.owner.id !== selectedUsers.leader.id)) {
            selected.push({ ...selectedUsers.leader, role: 'Leader', isOwner: false });
        }

        selectedUsers.members.forEach(member => {
            selected.push({ ...member, role: 'Member', isOwner: false });
        });

        if (selected.length === 0) {
            container.innerHTML = '';
            noMsg.classList.remove('hidden');
            return;
        }

        noMsg.classList.add('hidden');
        container.innerHTML = selected.map(user => {
            const styles = {
                'Owner': 'bg-blue-50 dark:bg-blue-950 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
                'Leader': 'bg-green-50 dark:bg-green-950 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
                'Owner & Leader': 'bg-teal-50 dark:bg-teal-950 border-teal-200 dark:border-teal-800 text-teal-800 dark:text-teal-200',
                'Member': 'bg-purple-50 dark:bg-purple-950 border-purple-200 dark:border-purple-800 text-purple-800 dark:text-purple-200'
            }[user.role] || 'bg-gray-50 dark:bg-gray-950 border-gray-200 dark:border-gray-800 text-gray-800 dark:text-gray-200';

            const showRemoveButton = user.role !== 'Owner' && user.role !== 'Owner & Leader';
            const photo = user.photo_profile ? `/storage/${user.photo_profile}` : null;
            const initial = (user.nama_mahasiswa || '?').charAt(0).toUpperCase();

            return `
                <div class="flex items-center justify-between p-4 border rounded-2xl ${styles}">
                    <div class="flex items-center gap-3">
                        ${photo ?
                            `<img src="${photo}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800">` :
                            `<div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center ring-2 ring-white dark:ring-gray-800">
                                <span class="font-semibold text-current">${initial}</span>
                            </div>`
                        }
                        <div>
                            <div class="font-medium">${user.role}: ${user.nama_mahasiswa || 'Unknown'}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">${user.email || ''}</div>
                        </div>
                    </div>
                    ${showRemoveButton ? `
                    <button type="button" onclick="window.removeUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80" title="Remove">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    ` : ''}
                </div>
            `;
        }).join('');
    }

    function removeUser(userId) {
        if (selectedUsers.owner && String(selectedUsers.owner.id) === String(userId)) {
            alert('Owner tidak dapat dihapus dari project.');
            return;
        }
        if (selectedUsers.leader && String(selectedUsers.leader.id) === String(userId)) {
            selectedUsers.leader = null;
        }
        selectedUsers.members = selectedUsers.members.filter(m => String(m.id) !== String(userId));
        updateFormInputs();
        renderSelectedUsers();
        updateTaskUserOptions();
    }

    function loadSelectedUsersFromForm() {
        if (!projectData) return;
        selectedUsers.owner = projectData.currentUser || null;

        const leaderId = projectData.leaderId;
        if (leaderId && String(leaderId) !== String(projectData.currentUser?.id)) {
            let leader = getUserById(leaderId);
            if (!leader && projectData.existingTasks) {
                const taskUser = projectData.existingTasks.find(t => String(t.user_id) === String(leaderId));
                if (taskUser && taskUser.user_name) {
                    leader = { id: leaderId, nama_mahasiswa: taskUser.user_name };
                }
            }
            selectedUsers.leader = leader || null;
        } else {
            selectedUsers.leader = null;
        }

        selectedUsers.members = [];
        const memberIds = projectData.memberIds || [];
        memberIds.forEach(memberId => {
            if (String(memberId) === String(projectData.currentUser?.id)) return;
            if (selectedUsers.leader && String(memberId) === String(selectedUsers.leader.id)) return;
            let member = getUserById(memberId);
            if (!member && projectData.existingTasks) {
                const taskUser = projectData.existingTasks.find(t => String(t.user_id) === String(memberId));
                if (taskUser && taskUser.user_name) {
                    member = { id: memberId, nama_mahasiswa: taskUser.user_name };
                }
            }
            if (member) selectedUsers.members.push(member);
        });

        syncPendingFromSelected();
    }

    // ----- toggle collaborative mode -----
    function toggleUserSelectionSection() {
        const toggle = document.getElementById('project-collaborative-toggle');
        const userSelectionSection = document.getElementById('user-selection-section');
        const taskSection = document.getElementById('task-section');

        if (toggle && userSelectionSection) {
            if (toggle.checked) {
                if (!isCollaborativeMode) {
                    const currentTasks = saveCurrentTasks();
                    isCollaborativeMode = true;
                    userSelectionSection.style.display = 'block';
                    if (taskSection) taskSection.classList.remove('hidden');
                    restoreTasks(currentTasks);
                } else {
                    userSelectionSection.style.display = 'block';
                    if (taskSection) taskSection.classList.remove('hidden');
                }
            } else {
                if (isCollaborativeMode) {
                    const currentTasks = saveCurrentTasks();
                    isCollaborativeMode = false;
                    userSelectionSection.style.display = 'none';
                    if (taskSection) taskSection.classList.remove('hidden');

                    selectedUsers.owner = projectData?.currentUser || null;
                    selectedUsers.leader = null;
                    selectedUsers.members = [];
                    updateFormInputs();
                    renderSelectedUsers();
                    restoreTasks(currentTasks);
                } else {
                    userSelectionSection.style.display = 'none';
                    if (taskSection) taskSection.classList.remove('hidden');
                }
            }
        }
    }

    // ----- date validation -----
    function setupDateValidation() {
        const tanggalMulaiInput = document.getElementById('tanggal_mulai');
        const tanggalAkhirInput = document.getElementById('tanggal_akhir');
        if (!tanggalMulaiInput || !tanggalAkhirInput) return;

        if (tanggalMulaiInput.value) tanggalAkhirInput.min = tanggalMulaiInput.value;

        tanggalMulaiInput.addEventListener('change', function() {
            if (this.value) {
                tanggalAkhirInput.min = this.value;
                if (tanggalAkhirInput.value && tanggalAkhirInput.value < this.value) {
                    tanggalAkhirInput.value = '';
                }
            } else {
                tanggalAkhirInput.min = '';
            }
        });

        tanggalAkhirInput.addEventListener('change', function() {
            if (this.value && tanggalMulaiInput.value && this.value < tanggalMulaiInput.value) {
                this.value = '';
                alert('Tanggal selesai harus setelah atau sama dengan tanggal mulai.');
            }
        });
    }

    // ----- modal filters -----
    function setupModalFilters() {
        const searchInput = document.getElementById('modal-search');
        const angkatanSelect = document.getElementById('modal-angkatan');
        const jurusanSelect = document.getElementById('modal-jurusan');
        const keahlianSelect = document.getElementById('modal-keahlian');

        const fetchWithSave = (page) => {
            saveCurrentModalRoles();
            fetchUsers(page);
        };

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                currentModalFilters.search = this.value;
                fetchWithSave(1);
            });
        }
        if (angkatanSelect) {
            angkatanSelect.addEventListener('change', function() {
                currentModalFilters.angkatan = this.value;
                fetchWithSave(1);
            });
        }
        if (jurusanSelect) {
            jurusanSelect.addEventListener('change', function() {
                currentModalFilters.jurusan = this.value;
                fetchWithSave(1);
            });
        }
        if (keahlianSelect) {
            keahlianSelect.addEventListener('change', function() {
                currentModalFilters.keahlian = this.value;
                fetchWithSave(1);
            });
        }
    }

    // ----- pagination (delegated) -----
    document.addEventListener('click', function(e) {
        const link = e.target.closest('#modal-pagination a');
        if (link) {
            e.preventDefault();
            const url = new URL(link.href);
            const page = url.searchParams.get('page') || 1;
            saveCurrentModalRoles();
            fetchUsers(page);
        }
    });

    // ----- initialize tasks -----
    function initializeTaskRows() {
        const container = document.getElementById('tasks-container');
        if (!container) return;
        container.innerHTML = '';
        taskIndex = 0;

        const tasks = projectData?.existingTasks || [];
        if (tasks.length) {
            tasks.forEach(task => addTaskRow(task));
        }
        if (container.children.length === 0) addTaskRow();

        if (isCollaborativeMode) setTimeout(() => updateTaskUserOptions(), 100);
    }

    // ----- expose to window (for onclick) -----
    window.openUserModal = openUserModal;
    window.closeUserModal = closeUserModal;
    window.confirmUserSelection = confirmUserSelection;
    window.addTaskRow = addTaskRow;
    window.removeTaskRow = removeTaskRow;
    window.updateUserRole = updateUserRole;
    window.fetchUsers = fetchUsers;
    window.refreshLeaderOptions = refreshLeaderOptions;
    window.applyPendingToSelected = applyPendingToSelected;
    window.syncPendingFromSelected = syncPendingFromSelected;
    window.saveCurrentModalRoles = saveCurrentModalRoles;
    window.restoreModalRoles = restoreModalRoles;
    window.getUserById = getUserById;
    window.addUsersToMap = addUsersToMap;
    window.renderSelectedUsers = renderSelectedUsers;
    window.updateFormInputs = updateFormInputs;
    window.removeUser = removeUser;
    window.loadSelectedUsersFromForm = loadSelectedUsersFromForm;
    window.toggleUserSelectionSection = toggleUserSelectionSection;
    window.setupDateValidation = setupDateValidation;
    window.setupModalFilters = setupModalFilters;
    window.initializeTaskRows = initializeTaskRows;
    window.saveCurrentTasks = saveCurrentTasks;
    window.restoreTasks = restoreTasks;
    window.updateTaskUserOptions = updateTaskUserOptions;
    window.renderTaskUserOptions = renderTaskUserOptions;
    window.getAllowedTaskUsers = getAllowedTaskUsers;
    window.attachRoleSelectEvents = attachRoleSelectEvents;

    // ----- auto-init when DOM ready -----
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('project-edit-data');
        if (!container) return;

        projectData = {
            users: JSON.parse(container.dataset.users || '[]'),
            currentUser: JSON.parse(container.dataset.currentUser || 'null'),
            existingTasks: JSON.parse(container.dataset.existingTasks || '[]'),
            projectId: container.dataset.projectId,
            leaderId: container.dataset.leaderId || null,
            memberIds: JSON.parse(container.dataset.memberIds || '[]')
        };

        addUsersToMap(projectData.users);
        if (projectData.currentUser) addUsersToMap([projectData.currentUser]);

        loadSelectedUsersFromForm();
        renderSelectedUsers();

        const toggle = document.getElementById('project-collaborative-toggle');
        const label = document.getElementById('toggle-label');
        if (toggle && label) {
            const hasMembers = selectedUsers.members.length > 0 || selectedUsers.leader;
            toggle.checked = hasMembers;
            label.textContent = hasMembers ? 'Aktif' : 'Nonaktif';
            isCollaborativeMode = hasMembers;
            toggleUserSelectionSection();
            initializeTaskRows();
            toggle.addEventListener('change', function() {
                label.textContent = this.checked ? 'Aktif' : 'Nonaktif';
                toggleUserSelectionSection();
            });
        } else {
            initializeTaskRows();
        }

        setupDateValidation();
        setupModalFilters();
    });
})();

// ==========================================
//   PROJECT CREATE PAGE (Direct)
//   Dijalankan jika elemen #project-create-data ada
// ==========================================
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        const dataEl = document.getElementById('project-create-data');
        if (!dataEl) return;

        // Ambil data dari dataset
        const currentUser = JSON.parse(dataEl.dataset.currentUser || 'null');
        const oldTasks = JSON.parse(dataEl.dataset.oldTasks || '[]');
        const routeCreate = dataEl.dataset.routeCreate || '';

        // ------------------------------------------------------------------
        // Semua fungsi dan state di-bungkus dalam scope agar tidak global
        // ------------------------------------------------------------------
        let allUsers = [];
        let allUsersMap = new Map();
        let currentModalFilters = { search: '', angkatan: '', jurusan: '', keahlian: '' };
        let currentPage = 1;
        let selectedUsers = { owner: null, leader: null, members: [] };
        let pendingUsers = { owner: null, leader: null, members: [] };
        let taskIndex = 0;
        const userSelectionStorageKey = 'project_selected_users';

        // ----- Storage helpers -----
        function saveSelectedUsersToStorage() {
            localStorage.setItem(userSelectionStorageKey, JSON.stringify({
                owner: selectedUsers.owner,
                leader: selectedUsers.leader,
                members: selectedUsers.members
            }));
        }

        function restoreSelectedUsersFromStorage() {
            const stored = localStorage.getItem(userSelectionStorageKey);
            if (!stored) return false;
            try {
                const parsed = JSON.parse(stored);
                if (parsed.owner) selectedUsers.owner = parsed.owner;
                if (parsed.leader) selectedUsers.leader = parsed.leader;
                if (Array.isArray(parsed.members)) selectedUsers.members = parsed.members;
                return true;
            } catch (e) {
                console.warn('Unable to restore selected users:', e);
                return false;
            }
        }

        // ----- allUsers helpers -----
        function addUsersToAllUsers(usersArray) {
            if (!Array.isArray(usersArray)) return;
            usersArray.forEach(user => {
                if (!allUsersMap.has(String(user.id))) {
                    allUsersMap.set(String(user.id), user);
                    allUsers.push(user);
                }
            });
        }

        function getUserById(id) {
            const userId = String(id);
            return allUsersMap.has(userId) ? allUsersMap.get(userId) : null;
        }

        // ----- pendingUsers helpers -----
        function syncPendingFromSelected() {
            pendingUsers = {
                owner: selectedUsers.owner ? { ...selectedUsers.owner } : null,
                leader: selectedUsers.leader ? { ...selectedUsers.leader } : null,
                members: selectedUsers.members.map(m => ({ ...m }))
            };
        }

        function applyPendingToSelected() {
            selectedUsers.owner = pendingUsers.owner ? { ...pendingUsers.owner } : null;
            selectedUsers.leader = pendingUsers.leader ? { ...pendingUsers.leader } : null;
            selectedUsers.members = pendingUsers.members.map(m => ({ ...m }));
        }

        function discardPending() {
            pendingUsers = { owner: null, leader: null, members: [] };
        }

        // ----- Modal open/close -----
        window.openUserModal = function() {
            const toggle = document.getElementById('project-collaborative-toggle');
            if (!toggle || !toggle.checked) {
                alert('Harap aktifkan mode kolaboratif terlebih dahulu untuk menambah user.');
                return;
            }
            syncPendingFromSelected();
            document.getElementById('userModal').classList.remove('hidden');
            fetchUsers(1);
        };

        window.closeUserModal = function() {
            document.getElementById('userModal').classList.add('hidden');
            discardPending();
        };

        window.cancelUserModal = function() {
            discardPending();
            document.getElementById('userModal').classList.add('hidden');
        };

        window.confirmUserSelection = function() {
            applyPendingToSelected();
            updateFormInputs();
            renderSelectedUsers();
            updateTaskSectionVisibility();
            updateTaskUserOptions();
            updateSelectedUsersBadge();
            saveSelectedUsersToStorage();
            document.getElementById('userModal').classList.add('hidden');
            discardPending();
        };

        // ----- Fetch users (AJAX) -----
        function fetchUsers(page = 1) {
            currentPage = page;
            const params = new URLSearchParams({
                page: page,
                search: currentModalFilters.search,
                angkatan: currentModalFilters.angkatan,
                jurusan: currentModalFilters.jurusan,
                keahlian: currentModalFilters.keahlian
            });

            fetch(`${routeCreate}?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.userListHtml;
                const userElements = tempDiv.querySelectorAll('[data-user-id]');
                const usersInPage = [];
                userElements.forEach(el => {
                    const userId = el.getAttribute('data-user-id');
                    const nameEl = el.querySelector('.font-medium');
                    const emailEl = el.querySelector('.text-sm.text-gray-500');
                    const imgEl = el.querySelector('img');
                    if (userId && nameEl) {
                        usersInPage.push({
                            id: parseInt(userId),
                            nama_mahasiswa: nameEl.textContent.trim(),
                            email: emailEl ? emailEl.textContent.trim() : '',
                            photo_profile: imgEl ? imgEl.getAttribute('src')?.replace('/storage/', '') : null
                        });
                    }
                });
                addUsersToAllUsers(usersInPage);
                renderUserListWithRoles(data.userListHtml);
                document.getElementById('modal-pagination').innerHTML = data.paginationHtml;
                refreshRoleSelections();
                if (typeof window.refreshTranslations === 'function') window.refreshTranslations();
            });
        }

        function renderUserListWithRoles(html) {
            const container = document.getElementById('modal-user-list');
            container.innerHTML = html;
            document.querySelectorAll('.user-role-select').forEach(select => {
                const userId = String(select.dataset.userId);
                const user = getUserById(userId);
                if (user) {
                    const userDiv = select.closest('[data-user-id]');
                    if (userDiv) {
                        const nameDiv = userDiv.querySelector('.font-medium');
                        if (nameDiv && nameDiv.textContent !== user.nama_mahasiswa) {
                            nameDiv.textContent = user.nama_mahasiswa;
                        }
                    }
                }
            });
        }

        // ----- Role assignment -----
        window.updateUserRole = function(selectElement, userId, role) {
            const user = getUserById(userId);
            if (!user) {
                console.error('User not found:', userId);
                return;
            }
            const isOwner = pendingUsers.owner && String(pendingUsers.owner.id) === String(userId);
            const isCurrentLeader = pendingUsers.leader && String(pendingUsers.leader.id) === String(userId);

            if (isOwner && role === 'member') {
                alert('Owner tidak bisa menjadi member.');
                selectElement.value = isCurrentLeader ? 'leader' : '';
                refreshRoleSelections();
                return;
            }

            if (role === 'leader' && pendingUsers.leader && String(pendingUsers.leader.id) !== String(userId)) {
                if (!confirm(`Anda yakin ingin mengganti leader dari "${pendingUsers.leader.nama_mahasiswa}" menjadi "${user.nama_mahasiswa}"?`)) {
                    selectElement.value = isCurrentLeader ? 'leader' : '';
                    refreshRoleSelections();
                    return;
                }
                pendingUsers.leader = null;
            }

            if (pendingUsers.leader && String(pendingUsers.leader.id) === String(userId)) {
                pendingUsers.leader = null;
            }
            pendingUsers.members = pendingUsers.members.filter(m => String(m.id) !== String(userId));

            if (role === 'leader') {
                pendingUsers.leader = user;
            } else if (role === 'member') {
                if (!pendingUsers.members.some(m => String(m.id) === String(userId))) {
                    pendingUsers.members.push(user);
                }
            }
            refreshRoleSelections();
        };

        function refreshRoleSelections() {
            const leaderId = pendingUsers.leader ? String(pendingUsers.leader.id) : null;
            const ownerId = pendingUsers.owner ? String(pendingUsers.owner.id) : null;

            document.querySelectorAll('.user-role-select').forEach(select => {
                const userId = String(select.dataset.userId);
                const isOwner = ownerId === userId;
                const isLeader = leaderId === userId;
                const isMember = pendingUsers.members.some(m => String(m.id) === userId);

                if (isLeader) {
                    select.value = 'leader';
                } else if (isMember) {
                    select.value = 'member';
                } else {
                    select.value = '';
                }

                const leaderOption = select.querySelector('option[value="leader"]');
                const memberOption = select.querySelector('option[value="member"]');

                if (leaderOption) {
                    leaderOption.disabled = !!(leaderId && leaderId !== userId && !isLeader);
                    leaderOption.title = leaderOption.disabled ? 'Leader sudah dipilih' : '';
                }
                if (memberOption) {
                    memberOption.disabled = false;
                }
                select.disabled = false;
            });
        }

        // ----- Form inputs & rendering -----
        function updateSelectedUsersBadge() {
            const badge = document.getElementById('selected-users-badge');
            if (!badge) return;
            let count = 0;
            if (selectedUsers.owner) count++;
            if (selectedUsers.leader) count++;
            count += selectedUsers.members.length;
            badge.innerHTML = count === 0
                ? ''
                : `<span class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full text-xs font-semibold">${count} user(s) terpilih</span>`;
        }

        function updateTaskSectionVisibility() {
            const taskSection = document.getElementById('task-section');
            if (!taskSection) return;
            const hasUsers = selectedUsers.owner || selectedUsers.leader || selectedUsers.members.length > 0;
            if (hasUsers) {
                taskSection.classList.remove('hidden');
            } else {
                taskSection.classList.add('hidden');
                const container = document.getElementById('tasks-container');
                if (container) { container.innerHTML = ''; taskIndex = 0; }
            }
        }

        function updateFormInputs() {
            document.getElementById('selected-owner-id').value = selectedUsers.owner?.id || '';
            document.getElementById('selected-leader-id').value = selectedUsers.leader?.id || '';
            document.getElementById('selected-members-ids').value = selectedUsers.members.map(m => m.id).join(',');

            const memberInputs = document.getElementById('selected-members-inputs');
            if (memberInputs) {
                memberInputs.innerHTML = selectedUsers.members
                    .map(member => `<input type="hidden" name="members[]" value="${member.id}">`)
                    .join('');
            }
        }

        function renderSelectedUsers() {
            const container = document.getElementById('selected-users-container');
            const noUsersMsg = document.getElementById('no-users-message');
            if (!container || !noUsersMsg) return;

            const selected = [];
            if (selectedUsers.owner) {
                const role = selectedUsers.leader && selectedUsers.leader.id === selectedUsers.owner.id ? 'Owner & Leader' : 'Owner';
                selected.push({ ...selectedUsers.owner, role });
            }
            if (selectedUsers.leader && (!selectedUsers.owner || selectedUsers.owner.id !== selectedUsers.leader.id)) {
                selected.push({ ...selectedUsers.leader, role: 'Leader' });
            }
            selectedUsers.members.forEach(member => selected.push({ ...member, role: 'Member' }));

            if (!selected.length) {
                container.innerHTML = '';
                noUsersMsg.classList.remove('hidden');
                return;
            }

            noUsersMsg.classList.add('hidden');
            container.innerHTML = selected.map(user => {
                const styles = {
                    'Owner': 'bg-blue-50 dark:bg-blue-950 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
                    'Leader': 'bg-green-50 dark:bg-green-950 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
                    'Owner & Leader': 'bg-teal-50 dark:bg-teal-950 border-teal-200 dark:border-teal-800 text-teal-800 dark:text-teal-200',
                    'Member': 'bg-purple-50 dark:bg-purple-950 border-purple-200 dark:border-purple-800 text-purple-800 dark:text-purple-200'
                }[user.role] || 'bg-gray-50 dark:bg-gray-950 border-gray-200 dark:border-gray-800 text-gray-800 dark:text-gray-200';

                return `
                    <div class="flex items-center justify-between p-4 border rounded-2xl ${styles}">
                        <div class="flex items-center gap-3">
                            ${user.photo_profile
                                ? `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800">`
                                : `<div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center ring-2 ring-white dark:ring-gray-800">
                                    <span class="font-semibold text-current">${user.nama_mahasiswa.charAt(0).toUpperCase()}</span>
                                </div>`
                            }
                            <div>
                                <div class="font-medium">${user.role}: ${user.nama_mahasiswa}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">${user.email}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="editUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80" title="Edit Role">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            ${user.role !== 'Owner' ? `
                            <button type="button" onclick="removeUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80" title="Remove">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>` : ''}
                        </div>
                    </div>
                `;
            }).join('');
        }

        function deleteTasksForUser(userId) {
            const userIdStr = String(userId);
            document.querySelectorAll('.task-item').forEach(taskItem => {
                const select = taskItem.querySelector('.task-user-select');
                if (select && String(select.value) === userIdStr) taskItem.remove();
            });
            if (!document.querySelectorAll('.task-item').length) addTaskRow();
        }

        window.removeUser = function(userId) {
            deleteTasksForUser(userId);
            if (selectedUsers.leader?.id == userId) selectedUsers.leader = null;
            selectedUsers.members = selectedUsers.members.filter(m => String(m.id) !== String(userId));
            updateFormInputs();
            renderSelectedUsers();
            updateTaskUserOptions();
            updateSelectedUsersBadge();
            saveSelectedUsersToStorage();
        };

        window.editUser = function(userId) {
            window.openUserModal();
            setTimeout(() => {
                const userElement = document.querySelector(`.user-role-select[data-user-id="${userId}"]`)?.closest('[data-user-id]');
                if (userElement) {
                    userElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    userElement.style.backgroundColor = '#fef3c7';
                    setTimeout(() => userElement.style.backgroundColor = '', 2000);
                }
            }, 500);
        };

        // ----- Task helpers -----
        function getAllowedTaskUsers() {
            const users = [];
            const added = new Set();
            const add = user => {
                if (!user || added.has(user.id)) return;
                added.add(user.id);
                users.push({ id: user.id, name: user.nama_mahasiswa });
            };
            add(selectedUsers.owner);
            add(selectedUsers.leader);
            selectedUsers.members.forEach(add);
            return users;
        }

        function renderTaskUserOptions(selectedId = '') {
            const users = getAllowedTaskUsers();
            let html = '<option value=""> Pilih Penanggung Jawab </option>';
            users.forEach(user => {
                html += `<option value="${user.id}" ${String(user.id) === String(selectedId) ? 'selected' : ''}>${user.name}</option>`;
            });
            return html;
        }

        window.addTaskRow = function(taskData = null) {
            const container = document.getElementById('tasks-container');
            if (!container) return;
            const index = taskIndex++;
            const userId = taskData?.user_id ?? '';
            const taskName = taskData?.name_task ? taskData.name_task.replace(/"/g, '&quot;') : '';
            const hiddenId = taskData?.id ? `<input type="hidden" name="tasks[${index}][id]" value="${taskData.id}">` : '';

            const taskItem = document.createElement('div');
            taskItem.className = 'task-item p-4 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900';
            taskItem.innerHTML = `
                ${hiddenId}
                <div class="grid gap-4 md:grid-cols-3 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Penanggung Jawab</label>
                        <select name="tasks[${index}][user_id]" class="task-user-select w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                            ${renderTaskUserOptions(userId)}
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Nama Tugas</label>
                        <input type="text" name="tasks[${index}][name_task]" value="${taskName}"
                            class="task-name-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                            placeholder="Deskripsikan tugas...">
                    </div>
                    <button type="button" onclick="removeTaskRow(this)"
                        class="self-start mt-6 px-4 py-3 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl">Hapus</button>
                </div>
            `;
            container.appendChild(taskItem);
            taskItem.querySelector('.task-user-select')?.addEventListener('change', updateTaskUserOptions);
        };

        window.removeTaskRow = function(button) {
            button.closest('.task-item')?.remove();
            if (!document.querySelectorAll('.task-item').length) window.addTaskRow();
        };

        function cleanupInvalidTaskRows() {
            const allowedIds = getAllowedTaskUsers().map(u => String(u.id));
            document.querySelectorAll('.task-item').forEach(taskItem => {
                const select = taskItem.querySelector('.task-user-select');
                if (!select || !select.value || !allowedIds.includes(select.value)) taskItem.remove();
            });
            if (!document.querySelectorAll('.task-item').length) window.addTaskRow();
        }

        function updateTaskUserOptions() {
            document.querySelectorAll('.task-user-select').forEach(select => {
                const currentValue = select.value;
                select.innerHTML = renderTaskUserOptions(currentValue);
                if (currentValue) select.value = currentValue;
            });
            cleanupInvalidTaskRows();
        }

        function initializeTaskRows(existingTasks = []) {
            const container = document.getElementById('tasks-container');
            if (!container) return;
            container.innerHTML = '';
            taskIndex = 0;
            if (Array.isArray(existingTasks) && existingTasks.length) {
                existingTasks.forEach(task => { if (task.user_id || task.name_task) window.addTaskRow(task); });
            } else {
                window.addTaskRow();
            }
            updateTaskUserOptions();
        }

        // ----- Modal filters -----
        function setupModalFilters() {
            document.getElementById('modal-search')?.addEventListener('input', function() {
                currentModalFilters.search = this.value;
                fetchUsers(1);
            });
            document.getElementById('modal-angkatan')?.addEventListener('change', function() {
                currentModalFilters.angkatan = this.value;
                fetchUsers(1);
            });
            document.getElementById('modal-jurusan')?.addEventListener('change', function() {
                currentModalFilters.jurusan = this.value;
                fetchUsers(1);
            });
            document.getElementById('modal-keahlian')?.addEventListener('change', function() {
                currentModalFilters.keahlian = this.value;
                fetchUsers(1);
            });
        }

        // ----- Pagination click (delegated) -----
        document.addEventListener('click', function(e) {
            const link = e.target.closest('#modal-pagination a');
            if (link) {
                e.preventDefault();
                const url = link.getAttribute('href');
                if (!url) return;
                const page = new URL(url).searchParams.get('page') || 1;
                fetchUsers(page);
            }
        });

        // ----- Collaborative toggle -----
        function toggleUserSelectionSection() {
            const toggle = document.getElementById('project-collaborative-toggle');
            const userSelectionSection = document.getElementById('user-selection-section');

            if (!toggle || !userSelectionSection) return;

            if (toggle.checked) {
                userSelectionSection.style.display = 'block';
                if (
                    selectedUsers.leader &&
                    selectedUsers.owner &&
                    String(selectedUsers.leader.id) === String(selectedUsers.owner.id) &&
                    selectedUsers.members.length === 0
                ) {
                    selectedUsers.leader = null;
                    updateFormInputs();
                    renderSelectedUsers();
                    updateSelectedUsersBadge();
                    saveSelectedUsersToStorage();
                }
            } else {
                userSelectionSection.style.display = 'none';
                selectedUsers.owner = currentUser;
                selectedUsers.leader = currentUser;
                selectedUsers.members = [];
                updateFormInputs();
                renderSelectedUsers();
                updateTaskUserOptions();
                updateSelectedUsersBadge();
                saveSelectedUsersToStorage();
            }
        }

        // ----- Date validation -----
        function setupDateValidation() {
            const tanggalMulaiInput = document.getElementById('tanggal_mulai');
            const tanggalAkhirInput = document.getElementById('tanggal_akhir');
            if (!tanggalMulaiInput || !tanggalAkhirInput) return;

            if (tanggalMulaiInput.value) tanggalAkhirInput.min = tanggalMulaiInput.value;

            tanggalMulaiInput.addEventListener('change', function() {
                if (this.value) {
                    tanggalAkhirInput.min = this.value;
                    if (tanggalAkhirInput.value && tanggalAkhirInput.value < this.value) {
                        tanggalAkhirInput.value = '';
                    }
                } else {
                    tanggalAkhirInput.min = '';
                }
            });

            tanggalAkhirInput.addEventListener('change', function() {
                if (this.value && tanggalMulaiInput.value && this.value < tanggalMulaiInput.value) {
                    this.value = '';
                    alert('Tanggal selesai harus setelah atau sama dengan tanggal mulai.');
                }
            });
        }

        // ----- Form submit -----
        function onSubmitProjectForm() {
            if (currentUser) selectedUsers.owner = currentUser;
            if (selectedUsers.owner && !selectedUsers.leader && selectedUsers.members.length > 0) {
                selectedUsers.leader = selectedUsers.owner;
            }
            updateFormInputs();
            cleanupInvalidTaskRows();
            updateTaskUserOptions();
        }

        // ----- Load from storage / form -----
        function loadSelectedUsersFromForm() {
            if (restoreSelectedUsersFromStorage()) {
                updateFormInputs();
                return;
            }
            const leaderId = document.getElementById('selected-leader-id')?.value;
            const memberIds = document.getElementById('selected-members-ids')?.value.split(',').filter(id => id) || [];
            if (leaderId) selectedUsers.leader = getUserById(leaderId);
            selectedUsers.members = memberIds.map(id => getUserById(id)).filter(Boolean);
        }

        // ----- Initialization -----
        if (currentUser) {
            addUsersToAllUsers([currentUser]);
        }

        loadSelectedUsersFromForm();
        renderSelectedUsers();
        setupModalFilters();
        setupDateValidation();
        updateTaskSectionVisibility();
        updateSelectedUsersBadge();
        initializeTaskRows(oldTasks);

        document.getElementById('projectForm')?.addEventListener('submit', onSubmitProjectForm);

        const collaborativeToggle = document.getElementById('project-collaborative-toggle');
        const toggleLabel = document.getElementById('toggle-label');
        if (collaborativeToggle && toggleLabel) {
            toggleLabel.textContent = collaborativeToggle.checked ? 'Aktif' : 'Nonaktif';
            toggleUserSelectionSection();
            collaborativeToggle.addEventListener('change', function() {
                toggleLabel.textContent = this.checked ? 'Aktif' : 'Nonaktif';
                toggleUserSelectionSection();
            });
        }


    });
})();

// Start Alpine AFTER all window.* components (e.g. notificationBell) are registered above.
// This must stay at the very bottom of the file.
// ============================================================
// DASHBOARD STUFF (EXTRACTED FROM views_dashboard & views_dashboard_me)
// ============================================================
window.cpCharts = window.cpCharts || {};

window.commentLastUpdated = {};

window.escapeHtml = function(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
};

window.formatDate = function(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
};

window.getAvatarHtml = function(user, size = 'w-8 h-8', textSize = 'text-sm') {
    if (user && user.photo_profile && user.photo_profile !== 'null' && user.photo_profile !== '') {
        const photoPath = user.photo_profile.startsWith('http') ? user.photo_profile : `/storage/${user.photo_profile}`;
        return `<img src="${photoPath}" class="${size} rounded-full object-cover" onerror="this.src='https://ui-avatars.com/api/?background=6366f1&color=fff&size=100&name=${encodeURIComponent(user.nama_mahasiswa || 'U')}'">`;
    }
    const name = user?.nama_mahasiswa || window.currentUserName || 'User';
    const initial = name.charAt(0).toUpperCase();
    return `<div class="${size} rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
            <span class="text-indigo-600 dark:text-indigo-400 ${textSize} font-semibold">${initial}</span>
        </div>`;
};

window.getHeaders = function() {
    return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    };
};

window.loadComments = async function (postinganId) {
    const container = document.getElementById(`comments-container-${postinganId}`);
    if (!container) return;
    container.innerHTML = '<div class="text-center py-6 text-gray-400 text-sm"><div class="comment-loading inline-block mr-2"></div> <span data-translate="loading_comments" data-translate-page="dashboard">Memuat komentar...</span></div>';
    try {
        const response = await fetch(`/${window.locale}/komentar?id_postingan=${postinganId}`);
        const data = await response.json();
        if (data && data.success === true) {
            window.commentLastUpdated[postinganId] = data.last_updated ?? null;
            let commentsArray = [];
            if (data.comments && Array.isArray(data.comments)) commentsArray = data.comments;
            else if (data.comments?.comments && Array.isArray(data.comments.comments)) commentsArray = data.comments.comments;
            if (commentsArray.length === 0) {
                const txt = window.locale === 'id' ? 'Belum ada komentar. Jadilah yang pertama!' : 'No comments yet. Be the first!';
                container.innerHTML = `<p class="text-sm text-gray-500 dark:text-gray-400 text-center py-5">${txt}</p>`;
                return;
            }
            let html = '<div class="space-y-4">';
            commentsArray.forEach(c => { html += window.renderCommentWithReplies(c, 0, postinganId); });
            html += '</div>';
            container.innerHTML = html;
            window.attachCommentEventListeners(container, postinganId);
            if (window.refreshTranslations) window.refreshTranslations();
        } else {
            container.innerHTML = '<p class="text-center text-red-500 text-sm py-4">⚠️ Gagal memuat komentar</p>';
        }
    } catch (error) {
        container.innerHTML = '<p class="text-center text-red-500 text-sm py-4">⚠️ Gagal memuat komentar: ' + error.message + '</p>';
    }
};

window.renderCommentWithReplies = function(comment, level, postinganId) {
    const marginLeft = Math.min(level * 28, 56);
    const isOwnComment = window.currentUserId && comment.id_user == window.currentUserId;
    const isLoggedIn = window.currentUserId !== null && window.currentUserId !== 'null';
    const userName = window.escapeHtml(comment.user?.nama_mahasiswa || 'User');
    const commentText = window.escapeHtml(comment.komentar);
    const commentId = String(comment.id_komentar);
    const replyText = window.locale === 'id' ? 'Balas' : 'Reply';
    const editText = window.locale === 'id' ? 'Edit' : 'Edit';
    const deleteText = window.locale === 'id' ? 'Hapus' : 'Delete';
    const userPortfolioUrl = comment.user && comment.user.username 
        ? `/${window.locale}/portofolio?user=${comment.user.username}` 
        : '#';
    const avatarHtml = comment.user && comment.user.username 
        ? `<a href="${userPortfolioUrl}">${window.getAvatarHtml(comment.user, 'w-8 h-8', 'text-xs')}</a>` 
        : window.getAvatarHtml(comment.user, 'w-8 h-8', 'text-xs');
    const nameHtml = comment.user && comment.user.username 
        ? `<a href="${userPortfolioUrl}" class="font-semibold text-sm text-gray-900 dark:text-gray-100 hover:text-indigo-600 transition-colors">${userName}</a>` 
        : `<span class="font-semibold text-sm text-gray-900 dark:text-gray-100">${userName}</span>`;

    let html = `
            <div class="comment-item transition-all duration-200 py-2" data-comment-id="${commentId}" data-postingan-id="${postinganId}" style="margin-left:${marginLeft}px;">
                <div class="flex gap-3">
                    ${avatarHtml}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            ${nameHtml}
                            <span class="text-xs text-gray-500">${window.formatDate(comment.tanggal || comment.created_at)}</span>
                        </div>
                        <p class="comment-text text-sm text-gray-700 dark:text-gray-300 mt-1.5 leading-relaxed" id="comment-text-${commentId}">${commentText}</p>
                        <div class="flex flex-wrap gap-3 mt-2">`;
    if (isLoggedIn) {
        html += `<button class="reply-btn text-xs text-indigo-500 hover:text-indigo-700 font-medium transition-colors inline-flex items-center gap-1"
                        data-action="reply" data-comment-id="${commentId}" data-postingan-id="${postinganId}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        ${replyText}</button>`;
    }
    if (isOwnComment) {
        html += `<button class="edit-comment-btn text-xs text-blue-500 hover:text-blue-700 transition-colors inline-flex items-center gap-1"
                        data-action="edit" data-comment-id="${commentId}" data-postingan-id="${postinganId}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        ${editText}</button>
                     <button class="delete-comment-btn text-xs text-red-500 hover:text-red-700 transition-colors inline-flex items-center gap-1"
                        data-action="delete" data-comment-id="${commentId}" data-postingan-id="${postinganId}" data-type="full">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        ${deleteText}</button>`;
    }
    html += `</div></div></div>
                <div id="reply-form-${commentId}" class="reply-form-container hidden mt-3 ml-11"></div>
            </div>`;
    if (comment.balasan?.length) {
        html += `<div class="replies-container ml-4 mt-1">`;
        comment.balasan.forEach(r => { html += window.renderCommentWithReplies(r, level + 1, postinganId); });
        html += `</div>`;
    }
    return html;
};

window.attachCommentEventListeners = function(container, postinganId) {
    if (container.hasAttribute('data-delegated')) return;
    container.setAttribute('data-delegated', 'true');
    container.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-action]');
        if (!btn) return;
        const commentId = btn.getAttribute('data-comment-id');
        const pid = btn.getAttribute('data-postingan-id');
        const action = btn.getAttribute('data-action');
        if (action === 'reply') window.showReplyForm(commentId, pid);
        else if (action === 'edit') window.showEditForm(commentId, pid);
        else if (action === 'delete') window.deleteComment(commentId, pid, btn.getAttribute('data-type') || 'full');
        else if (action === 'save-edit') window.saveEdit(commentId, pid);
        else if (action === 'cancel-edit') window.cancelEdit(commentId);
    });
};

window.submitComment = async function (postinganId) {
    const textarea = document.getElementById(`comment-input-${postinganId}`);
    const commentText = textarea.value.trim();
    if (!commentText) { window.showPageInfo?.('Komentar tidak boleh kosong', 'warning', 2000) || alert('Komentar tidak boleh kosong'); return; }
    const submitBtn = textarea.closest('.flex-1')?.querySelector('.submit-comment-btn');
    const origHtml = submitBtn ? submitBtn.innerHTML : '';
    if (submitBtn) { submitBtn.classList.add('btn-loading'); submitBtn.innerHTML = 'Mengirim...'; submitBtn.disabled = true; }
    try {
        const r = await fetch(`/${window.locale}/komentar`, { method: 'POST', headers: window.getHeaders(), body: JSON.stringify({ id_postingan: postinganId, komentar: commentText }) });
        const data = await r.json();
        if (data.success) { textarea.value = ''; await window.loadComments(postinganId); await window.updateCommentCount(postinganId, 1); window.showPageInfo?.('Komentar berhasil ditambahkan', 'success', 2000); }
        else window.showErrorAlert?.(data.message || 'Gagal') || alert(data.message || 'Gagal');
    } catch (e) { window.showErrorAlert?.('Terjadi kesalahan: ' + e.message) || alert('Terjadi kesalahan: ' + e.message); }
    finally { if (submitBtn) { submitBtn.classList.remove('btn-loading'); submitBtn.innerHTML = origHtml; submitBtn.disabled = false; } }
};

window.submitReply = async function(form, postinganId) {
    if (form.hasAttribute('data-submitting')) return;
    form.setAttribute('data-submitting', 'true');
    const textarea = form.querySelector('textarea[name="komentar"]');
    const commentText = textarea.value.trim();
    const parentId = form.querySelector('input[name="parent_id"]').value;
    if (!commentText) { window.showPageInfo?.('Balasan tidak boleh kosong', 'warning', 2000) || alert('Balasan tidak boleh kosong'); form.removeAttribute('data-submitting'); return; }
    const submitBtn = form.querySelector('button[type="submit"]');
    const cancelBtn = form.querySelector('button[type="button"]');
    submitBtn.disabled = true; if (cancelBtn) cancelBtn.disabled = true;
    try {
        const r = await fetch(`/${window.locale}/komentar`, { method: 'POST', headers: window.getHeaders(), body: JSON.stringify({ id_postingan: postinganId, komentar: commentText, parent_id: parentId, reply_to_id: parentId }) });
        const data = await r.json();
        if (data.success) {
            await window.loadComments(postinganId); await window.updateCommentCount(postinganId, 1);
            const rc = document.getElementById(`reply-form-${parentId}`);
            if (rc) { rc.classList.add('hidden'); rc.innerHTML = ''; }
            window.showPageInfo?.('Balasan berhasil ditambahkan', 'success', 2000);
        } else window.showErrorAlert?.(data.message || 'Gagal') || alert(data.message || 'Gagal');
    } catch (e) { window.showErrorAlert?.('Terjadi kesalahan: ' + e.message) || alert('Terjadi kesalahan: ' + e.message); }
    finally { submitBtn.disabled = false; if (cancelBtn) cancelBtn.disabled = false; form.removeAttribute('data-submitting'); }
};

window.showReplyForm = function (parentCommentId, postinganId) {
    parentCommentId = String(parentCommentId);
    const rc = document.getElementById(`reply-form-${parentCommentId}`);
    if (!rc) return;
    const sendText = window.locale === 'id' ? 'Kirim' : 'Send';
    const cancelText = window.locale === 'id' ? 'Batal' : 'Cancel';
    const ph = window.locale === 'id' ? 'Tulis balasan...' : 'Write a reply...';
    if (rc.innerHTML.trim() !== '' && !rc.classList.contains('hidden')) { rc.classList.add('hidden'); rc.innerHTML = ''; return; }
    rc.innerHTML = `<form class="reply-submit-form mt-3">
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
            <input type="hidden" name="parent_id" value="${parentCommentId}">
            <div class="flex flex-col gap-2">
                <textarea name="komentar" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm outline-none resize-none" placeholder="${ph}"></textarea>
                <div class="flex gap-2 justify-end">
                    <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg">${sendText}</button>
                    <button type="button" onclick="this.closest('.reply-form-container').classList.add('hidden');this.closest('.reply-form-container').innerHTML='';" class="px-4 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg">${cancelText}</button>
                </div>
            </div></form>`;
    rc.classList.remove('hidden');
    rc.querySelector('form').onsubmit = async (e) => { e.preventDefault(); await window.submitReply(rc.querySelector('form'), postinganId); };
};

window.showEditForm = function (commentId, postinganId) {
    commentId = String(commentId); postinganId = String(postinganId);
    const commentTextEl = document.getElementById(`comment-text-${commentId}`);
    if (!commentTextEl) return;
    const originalText = commentTextEl.innerText;
    const commentItem = commentTextEl.closest('.comment-item');
    const existing = document.getElementById(`edit-form-${commentId}`);
    if (existing) { existing.remove(); commentTextEl.style.display = 'block'; commentItem.querySelector('.edit-comment-btn')?.style && (commentItem.querySelector('.edit-comment-btn').style.display = 'inline-flex'); return; }
    const saveText = window.locale === 'id' ? 'Simpan' : 'Save';
    const cancelText = window.locale === 'id' ? 'Batal' : 'Cancel';
    const ef = document.createElement('div');
    ef.className = 'edit-form mt-2'; ef.id = `edit-form-${commentId}`;
    ef.innerHTML = `<textarea class="edit-textarea w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none resize-none" rows="2">${window.escapeHtml(originalText)}</textarea>
            <div class="flex gap-2 mt-2">
                <button class="save-edit-btn px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg" data-action="save-edit" data-comment-id="${commentId}" data-postingan-id="${postinganId}">${saveText}</button>
                <button class="px-3 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg" data-action="cancel-edit" data-comment-id="${commentId}">${cancelText}</button>
            </div>`;
    commentTextEl.style.display = 'none';
    commentTextEl.parentNode.insertBefore(ef, commentTextEl.nextSibling);
    const editBtn = commentItem.querySelector('.edit-comment-btn');
    if (editBtn) editBtn.style.display = 'none';
};

window.saveEdit = async function (commentId, postinganId) {
    commentId = String(commentId);
    const ef = document.getElementById(`edit-form-${commentId}`);
    if (!ef) return;
    const et = ef.querySelector('.edit-textarea');
    if (!et) return;
    const newText = et.value.trim();
    if (!newText) { window.showPageInfo?.('Komentar tidak boleh kosong', 'warning', 2000) || alert('Komentar tidak boleh kosong'); return; }
    const saveBtn = ef.querySelector('.save-edit-btn');
    if (!saveBtn) return;
    const origHtml = saveBtn.innerHTML;
    saveBtn.innerHTML = '<div class="comment-loading" style="width:14px;height:14px;"></div>'; saveBtn.disabled = true;
    try {
        const lu = window.commentLastUpdated?.[postinganId] ?? '';
        const r = await fetch(`/${window.locale}/komentar/update?id=${commentId}&id_postingan=${postinganId}&last_updated=${encodeURIComponent(lu)}`, { method: 'PUT', headers: window.getHeaders(), body: JSON.stringify({ komentar: newText }) });
        const data = await r.json();
        if (data.success) { await window.loadComments(postinganId); window.showPageInfo?.('Komentar berhasil diperbarui', 'success', 2000); }
        else if (data.code === 'STALE_DATA') { await window.loadComments(postinganId); window.showPageInfo?.('Komentar diperbarui pengguna lain. Edit ulang jika perlu.', 'warning', 3000); }
        else { window.showErrorAlert?.(data.message || 'Gagal') || alert(data.message || 'Gagal'); saveBtn.innerHTML = origHtml; saveBtn.disabled = false; }
    } catch (e) { window.showErrorAlert?.('Terjadi kesalahan: ' + e.message) || alert('Terjadi kesalahan: ' + e.message); saveBtn.innerHTML = origHtml; saveBtn.disabled = false; }
};

window.cancelEdit = function (commentId) {
    commentId = String(commentId);
    const ct = document.getElementById(`comment-text-${commentId}`);
    const ef = document.getElementById(`edit-form-${commentId}`);
    ct.style.display = 'block'; if (ef) ef.remove();
    const editBtn = ct.closest('.comment-item').querySelector('.edit-comment-btn');
    if (editBtn) editBtn.style.display = 'inline-flex';
};

window.deleteComment = async function (commentId, postinganId, type = 'full') {
    commentId = String(commentId); postinganId = String(postinganId);
    const msg = type === 'single'
        ? (window.locale === 'id' ? 'Apakah Anda yakin ingin menghapus balasan ini saja?' : 'Are you sure you want to delete this reply only?')
        : (window.locale === 'id' ? 'Apakah Anda yakin ingin menghapus komentar ini beserta semua balasannya?' : 'Are you sure you want to delete this comment and all its replies?');
    const confirmed = window.showConfirm ? await window.showConfirm(msg) : confirm(msg);
    if (!confirmed) return;
    window.showLoading?.('Menghapus...');
    try {
        const r = await fetch(`/${window.locale}/komentar/destroy?id=${commentId}&id_postingan=${postinganId}&type=${type}`, { method: 'DELETE', headers: window.getHeaders() });
        const data = await r.json();
        if (data.success) { await window.loadComments(postinganId); await window.updateCommentCount(postinganId, -1); window.showPageInfo?.('Komentar berhasil dihapus', 'success', 2000); }
        else window.showErrorAlert?.(data.message || 'Gagal') || alert(data.message || 'Gagal');
    } catch (e) { window.showErrorAlert?.('Terjadi kesalahan: ' + e.message) || alert('Terjadi kesalahan: ' + e.message); }
    finally { window.closeLoading?.(); }
};

window.updateCommentCount = async function(postinganId, delta) {
    const el = document.querySelector(`.comment-toggle[data-postingan-id="${postinganId}"] .comment-count`);
    if (el) el.innerText = Math.max(0, (parseInt(el.innerText) || 0) + delta);
};

window.cpCharts = window.cpCharts || {};

window.cpOpenModal = function (id) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
};

window.cpCloseModal = function (id) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.remove('show');
        document.body.style.overflow = '';
    }
};

window.cpOverlayClick = function (e, id) {
    if (e.target === document.getElementById(id)) {
        window.cpCloseModal(id);
    }
};

window.cpPreviewImage = function (e, imgId, wrapId) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        const img = document.getElementById(imgId);
        const wrap = document.getElementById(wrapId);
        if (img) img.src = ev.target.result;
        if (wrap) wrap.style.display = 'block';
    };
    reader.readAsDataURL(file);
};

window.cpRemovePreview = function (imgId, wrapId, inputId) {
    const img = document.getElementById(imgId);
    const wrap = document.getElementById(wrapId);
    const input = document.getElementById(inputId);
    if (img) img.src = '';
    if (wrap) wrap.style.display = 'none';
    if (input) input.value = '';
};

window.createSparkline = function (canvasId, borderColor) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    if (window.cpCharts[canvasId]) window.cpCharts[canvasId].destroy();
    const data = Array.from({ length: 7 }, () => Math.floor(Math.random() * 40) + 10);
    window.cpCharts[canvasId] = new Chart(canvas.getContext('2d'), {
        type: 'line',
        data: {
            labels: Array(7).fill(''),
            datasets: [{
                data, borderColor,
                backgroundColor: borderColor + '22',
                tension: 0.4, pointRadius: 0, borderWidth: 2, fill: true
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            scales: { x: { display: false }, y: { display: false } }
        }
    });
};

window.setupCommentToggleListeners = function () {
    document.querySelectorAll('.comment-toggle').forEach(btn => {
        btn.removeEventListener('click', window.handleCommentToggle);
        btn.addEventListener('click', window.handleCommentToggle);
    });
};

window.handleCommentToggle = async function (event) {
    const btn = event.currentTarget;
    const postCard = btn.closest('.post-card');
    if (!postCard) return;
    const commentSection = postCard.querySelector('.comment-section');
    const isHidden = commentSection.style.display !== 'block';

    commentSection.style.display = isHidden ? 'block' : 'none';

    if (isHidden) {
        const postinganId = btn.dataset.postinganId;
        if (postinganId) await window.loadComments(postinganId);
    }
};

window.setupShareListeners = function () {
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.removeEventListener('click', window.handleShare);
        btn.addEventListener('click', window.handleShare);
    });
};

window.fallbackCopyToClipboard = function (text, btn) {
    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);

    try {
        textarea.select();
        const successful = document.execCommand('copy');
        if (successful) {
            const origHtml = btn.innerHTML;
            btn.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
            setTimeout(() => { btn.innerHTML = origHtml; }, 2000);
            window.showPageInfo?.('Link berhasil disalin!', 'success', 1500);
        } else {
            window.showErrorAlert?.('Gagal menyalin URL') || alert('Gagal menyalin URL');
        }
    } catch (err) {
        console.error('Fallback copy error:', err);
        window.showErrorAlert?.('Gagal menyalin URL') || alert('Gagal menyalin URL');
    } finally {
        document.body.removeChild(textarea);
    }
};

window.handleShare = function (event) {
    const btn = event.currentTarget;
    const postCard = btn.closest('.post-card');
    if (!postCard) return;

    const url = postCard.dataset.shareUrl || window.location.href;
    const title = postCard.querySelector('h3')?.innerText || 'Postingan Menarik';

    if (navigator.share) {
        navigator.share({ title, url }).catch(() => { });
    } else {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(() => {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
                setTimeout(() => { btn.innerHTML = originalHtml; }, 2000);
                window.showPageInfo ? window.showPageInfo('Link berhasil disalin!', 'success', 1500) : (window.showPageInfo?.('Link berhasil disalin!', 'success', 1500));
            }).catch(err => {
                window.fallbackCopyToClipboard(url, btn);
            });
        } else {
            window.fallbackCopyToClipboard(url, btn);
        }
    }
};

window.setupLikeListeners = function () {
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.removeEventListener('click', window.handleLike);
        btn.addEventListener('click', window.handleLike);
    });
};

window.handleLike = async function (event) {
    event.preventDefault();
    event.stopPropagation();

    const likeBtn = event.currentTarget;
    const postinganId = likeBtn.getAttribute('data-postingan-id');

    try {
        const response = await fetch(`/${window.locale}/postingan/toggle-like?id=${postinganId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json();

        if (data.success) {
            const countEl = likeBtn.querySelector('.like-count');
            if (countEl) countEl.textContent = data.like_count;
            const svg = likeBtn.querySelector('svg');
            if (data.liked) {
                likeBtn.classList.add('text-red-500');
                svg.classList.add('fill-current', 'text-red-500');
            } else {
                likeBtn.classList.remove('text-red-500');
                svg.classList.remove('fill-current', 'text-red-500');
            }
        }
    } catch (error) { console.error('Error:', error); }
};

window.sortPostinganByGame = function () {
    const container = document.getElementById('postingan-container');
    if (!container) return;
    const posts = Array.from(container.children);
    posts.sort((a, b) => {
        const aHas = a.querySelector('.bg-teal-600') !== null;
        const bHas = b.querySelector('.bg-teal-600') !== null;
        return (aHas === bHas) ? 0 : aHas ? -1 : 1;
    });
    container.innerHTML = '';
    posts.forEach(p => container.appendChild(p));
};

window.initDosenCarousel = function () {
    const dosenCards = document.querySelectorAll('.dosen-card');
    const dots = document.querySelectorAll('.dot');
    if (!dosenCards.length) return;
    let current = 0;
    const visibleDots = 4, dotSize = 20;
    function updateCarousel() {
        dosenCards.forEach((card, i) => {
            const offset = i - current;
            const name = card.querySelector('.dosen-name');
            card.style.zIndex = offset === 0 ? '3' : (Math.abs(offset) === 1 ? '2' : '1');
            card.style.opacity = offset === 0 ? '1' : (Math.abs(offset) === 1 ? '0.6' : '0');
            card.style.transform = offset === 0 ? 'translateX(0) scale(1)' : (offset === -1 ? 'translateX(-120px) scale(0.8)' : (offset === 1 ? 'translateX(120px) scale(0.8)' : 'translateX(0) scale(0.5)'));
            if (name) name.style.opacity = offset === 0 ? '1' : '0';
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-blue-500', i === current);
            dot.classList.toggle('opacity-100', i === current);
            dot.classList.toggle('bg-gray-300', i !== current);
            dot.classList.toggle('opacity-40', i !== current);
        });
        const offsetIndex = Math.max(0, Math.min(current - Math.floor(visibleDots / 2), dots.length - visibleDots));
        const dt = document.getElementById('dosenDotsTrack');
        if (dt) dt.style.transform = `translateX(${-(offsetIndex * dotSize)}px)`;
    }
    const nextBtn = document.getElementById('dosenNextBtn');
    const prevBtn = document.getElementById('dosenPrevBtn');
    if (nextBtn) nextBtn.onclick = () => { current = (current + 1) % dosenCards.length; updateCarousel(); };
    if (prevBtn) prevBtn.onclick = () => { current = (current - 1 + dosenCards.length) % dosenCards.length; updateCarousel(); };
    dots.forEach(dot => { dot.onclick = () => { current = parseInt(dot.dataset.index); updateCarousel(); }; });
    updateCarousel();
    setInterval(() => { current = (current + 1) % dosenCards.length; updateCarousel(); }, 5000);
};

// Initialize Dashboard elements
document.addEventListener('DOMContentLoaded', () => {
    // Reveal skeletons
    setTimeout(() => {
        ['postingan-skeleton', 'project-skeleton', 'sertifikat-skeleton', 'learning-skeleton',
         'learning-skeleton-sidebar', 'total-lrn-skeleton', 'total-pjt-skeleton', 'total-stk-skeleton']
            .forEach(id => document.getElementById(id)?.classList.add('hidden'));

        ['postingan-content-wrapper', 'project-content-wrapper', 'sertifikat-content-wrapper', 'learning-content-wrapper',
         'learning-content-wrapper-sidebar', 'total-lrn-wrapper', 'total-pjt-wrapper', 'total-stk-wrapper']
            .forEach(id => document.getElementById(id)?.classList.remove('hidden'));

        window.sortPostinganByGame?.();
        window.setupCommentToggleListeners?.();
        window.setupShareListeners?.();
        window.setupLikeListeners?.();
        window.initDosenCarousel?.();
    }, 800);

    // Typing animation for create-post placeholder (if exists)
    const el = document.getElementById('cp-typed-text');
    if (el) {
        const phrases = [
            'Apa yang ingin Anda bagikan hari ini?',
            'Bagikan pengalaman terbaru Anda...',
            'Ceritakan sesuatu yang menarik...',
            'Tulis postingan baru sekarang...'
        ];
        let pIdx = 0, cIdx = 0, deleting = false;
        function tick() {
            const phrase = phrases[pIdx];
            if (!deleting) {
                cIdx++;
                el.textContent = phrase.slice(0, cIdx);
                if (cIdx === phrase.length) { deleting = true; setTimeout(tick, 2200); return; }
                setTimeout(tick, 60);
            } else {
                cIdx--;
                el.textContent = phrase.slice(0, cIdx);
                if (cIdx === 0) { deleting = false; pIdx = (pIdx + 1) % phrases.length; setTimeout(tick, 500); return; }
                setTimeout(tick, 30);
            }
        }
        tick();
    }

    // Modal Item Adder
    let cpExtraIdx = 0;
    const addBtn = document.getElementById('cp-add-item-post');
    if (addBtn) {
        addBtn.addEventListener('click', function () {
            const container = document.getElementById('cp-items-post');
            const row = document.createElement('div');
            row.className = 'cp-item-row';
            row.innerHTML = `
            <div class="cp-item-top">
                <select name="items[${cpExtraIdx}][type]" class="cp-item-select cp-type-sel">
                    <option value="image">Gambar</option>
                    <option value="link">Link / Referensi</option>
                </select>
                <button type="button" class="cp-item-remove" onclick="this.closest('.cp-item-row').remove()">Hapus</button>
            </div>
            <div class="cp-item-body">
                <input type="file" name="items[${cpExtraIdx}][file]" accept="image/*"
                       class="cp-file-field block w-full text-sm text-gray-500
                              file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                              file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700
                              hover:file:bg-indigo-100">
                <input type="url" name="items[${cpExtraIdx}][content]"
                       class="cp-input cp-link-field-extra hidden"
                       placeholder="https://example.com">
            </div>
        `;
            container.appendChild(row);
            const sel = row.querySelector('.cp-type-sel');
            const file = row.querySelector('.cp-file-field');
            const link = row.querySelector('.cp-link-field-extra');
            sel.addEventListener('change', function () {
                file.classList.toggle('hidden', this.value !== 'image');
                link.classList.toggle('hidden', this.value !== 'link');
                file.disabled = this.value !== 'image';
                link.disabled = this.value !== 'link';
            });
            link.classList.add('hidden'); link.disabled = true;
            cpExtraIdx++;
        });
    }

    // Sparkline charts initializers (if present)
    if (document.getElementById('learningChart')) {
        window.createSparkline('learningChart', '#8b5cf6');
    }
    if (document.getElementById('projectChart')) {
        window.createSparkline('projectChart', '#f97316');
    }
    if (document.getElementById('sertifikatChart')) {
        window.createSparkline('sertifikatChart', '#f59e0b');
    }
});

Alpine.start();