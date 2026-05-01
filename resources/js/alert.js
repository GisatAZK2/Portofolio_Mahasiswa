import Swal from 'sweetalert2';
import { translations } from './translate';


const DISMISSED_PAGE_INFO_KEY = 'dismissedPageInfo';


function getTranslation(lang, key) {
    return key.split('.').reduce((obj, i) => obj?.[i], translations[lang]);
}

function getCurrentLang() {
    return localStorage.getItem('lang') || 'id';
}

function translate(key) {
    const lang = getCurrentLang();
    return getTranslation(lang, key) || key;
}

setTimeout(() => {
    if (window.flashSuccess) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: window.flashSuccess,
        });
    }
}, 50);


export function showSuccessAlert(message) {
    Swal.fire({
        icon: 'success',
        title: showPageInfo('Berhasil!', 'success', 3000),
        text: message,
        position: 'center',
        showConfirmButton: true,
        confirmButtonColor: '#4f46e5',
        confirmButtonText: t('Oke, Lanjut'),
        allowOutsideClick: true,
        backdrop: 'rgba(0,0,0,0.6)',
        customClass: {
            popup: 'rounded-2xl shadow-2xl bg-white/95 backdrop-blur-md border border-indigo-100/50 p-6 sm:p-8',
            title: 'text-gray-900 font-bold text-xl sm:text-2xl mb-3',
            htmlContainer: 'text-gray-700 text-base sm:text-lg',
            icon: 'text-indigo-600',
            confirmButton: 'px-8 py-3 text-base font-medium rounded-xl'
        },
        timer: 4000,
        timerProgressBar: true
    });
}

export function showErrorAlert(message) {
    Swal.fire({
        icon: 'error',
        title: showPageInfo('Ada Masalah...', 'error', 3000),
        text: t(message),
        position: 'center',
        showConfirmButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: t('Coba Lagi'),
        allowOutsideClick: true,
        backdrop: 'rgba(0,0,0,0.6)',
        customClass: {
            popup: 'rounded-2xl shadow-2xl bg-white/95 backdrop-blur-md border border-red-100/50 p-6 sm:p-8',
            title: 'text-gray-900 font-bold text-xl sm:text-2xl mb-3',
            htmlContainer: 'text-gray-700 text-base sm:text-lg',
            icon: 'text-red-600',
            confirmButton: 'px-8 py-3 text-base font-medium rounded-xl'
        }
    });
}

export function showLoading(message = 'Memproses...') {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

export function closeLoading() {
    Swal.close();
}
export async function showConfirm() {
    const result = await Swal.fire({
        title: translate('confirm_delete_title'),
        text: showPageInfo('confirm_delete_text', 'warning', 10000),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: translate('confirm_delete_yes'),
        cancelButtonText: translate('cancel'),
        reverseButtons: true,
        timer: 7000,
        timerProgressBar: true
    });

    return result.isConfirmed;
}

window.showConfirm = showConfirm;

// expose success/error alerts globally for inline scripts
window.showSuccessAlert = showSuccessAlert;
window.showErrorAlert = showErrorAlert;

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();
            e.stopPropagation();

            const confirmed = await showConfirm();

            if (confirmed) {
                showLoading('Menghapus...');
                this.closest('form').submit();
            }
        });
    });
});


function getDismissedPageInfo() {
    const raw = localStorage.getItem(DISMISSED_PAGE_INFO_KEY);
    try {
        return raw ? JSON.parse(raw) : {};
    } catch (error) {
        localStorage.removeItem(DISMISSED_PAGE_INFO_KEY);
        return {};
    }
}

function setDismissedPageInfo(key) {
    const dismissed = getDismissedPageInfo();
    dismissed[key] = true;
    localStorage.setItem(DISMISSED_PAGE_INFO_KEY, JSON.stringify(dismissed));
}

// Fungsi untuk menampilkan toast page info
function showPageInfo(message, type = "info", duration = 2000) {
    const lang = localStorage.getItem("lang") || "id";
    let messageKey = null;
    const originalMessage = message;

    if (typeof message === 'string' && !message.includes(' ')) {
        const translated = getTranslation(lang, message);
        if (translated) {
            message = translated;
            messageKey = originalMessage;
        }
    }

    if (messageKey) {
        const dismissed = getDismissedPageInfo();
        if (dismissed[messageKey]) {
            return;
        }
    }

    const container = document.getElementById("toast-container");
    if (!container) return;

    const colors = {
        info: "text-black bg-gray-200 dark:bg-blue-900 dark:text-white",
        success: "bg-green-50 border-green-200 text-green-800 dark:bg-green-900/30 dark:border-green-700 dark:text-green-200",
        warning: "bg-yellow-50 border-yellow-200 text-yellow-800 dark:bg-yellow-900/30 dark:border-yellow-700 dark:text-yellow-200",
        error: "bg-red-50 border-red-200 text-red-800 dark:bg-red-900/30 dark:border-red-700 dark:text-red-200"
    };

    const icons = {
        info: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
               d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/></svg>`,

        success: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5 13l4 4L19 7"/></svg>`,

        warning: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01M12 2l10 18H2L12 2z"/></svg>`,

        error: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12"/></svg>`
    };

    const toast = document.createElement("div");

    toast.className =
        `flex pointer-events-auto mt-15 items-start gap-3 rounded-xl shadow-lg px-4 py-3 text-sm
        transition-all duration-300 transform -translate-y-6 opacity-0
        ${colors[type]}`;

    toast.innerHTML = `
        <div class="flex-shrink-0 mt-0.5">${icons[type]}</div>
        <div class="text-center ">${message}</div>
        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">✕</button>
    `;

    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove("translate-x-10", "opacity-0");
    });

    const removeToast = (userClicked = false) => {
        toast.classList.add("opacity-0", "translate-x-10");

        setTimeout(() => {
            toast.remove();
        }, 300);

        if (userClicked && messageKey) {
            setDismissedPageInfo(messageKey);
        }
    };

    const closeBtn = toast.querySelector("button");
    if (closeBtn) {
        closeBtn.onclick = () => removeToast(true);
    }

    setTimeout(() => removeToast(false), duration);
}

export function showInstallAlert(onInstall) {
    Swal.fire({
        title: translate('install_app_title') || 'Install App',
        text: translate('install_app_text') || 'Install aplikasi untuk pengalaman terbaik',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: translate('install') || 'Install',
        cancelButtonText: translate('later') || 'Nanti',
        allowOutsideClick: false,
        customClass: {
            popup: 'rounded-2xl shadow-2xl bg-white/95 backdrop-blur-md border border-indigo-100/50 p-6 sm:p-8',
            title: 'text-gray-900 font-bold text-xl sm:text-2xl mb-3',
            htmlContainer: 'text-gray-700 text-base sm:text-lg',
            confirmButton: 'px-8 py-3 text-base font-medium rounded-xl bg-blue-600 hover:bg-blue-700',
            cancelButton: 'px-8 py-3 text-base font-medium rounded-xl'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            onInstall();
        } else {
            localStorage.setItem('pwaInstallDismissed', 'true');
        }
    });
}

export function showInfoAlert(message) {
    Swal.fire({
        icon: 'info',
        title: translate('info') || 'Info',
        text: t(message),
        confirmButtonColor: '#2563eb',
        confirmButtonText: translate('oke') || 'Oke',
        allowOutsideClick: true,
        customClass: {
            popup: 'rounded-2xl shadow-2xl bg-white/95 backdrop-blur-md border border-blue-100/50 p-6 sm:p-8',
            title: 'text-gray-900 font-bold text-xl sm:text-2xl mb-3',
            htmlContainer: 'text-gray-700 text-base sm:text-lg',
            confirmButton: 'px-8 py-3 text-base font-medium rounded-xl'
        }
    });
}

window.showInstallAlert = showInstallAlert;
window.showInfoAlert = showInfoAlert;
window.showPageInfo = showPageInfo;