/**
 * resources/js/app.js  — Main Entrypoint
 *
 * Urutan import penting:
 *  1. Bootstrap & Alpine (harus sebelum komponen Alpine didaftarkan)
 *  2. Alert helpers (dipakai oleh hampir semua modul lain)
 *  3. Core utilities (helpers, dark-mode, PWA)
 *  4. UI Components (sidebar, mobile nav, form utils, notifications)
 *  5. Auth (OTP, passkey)
 *  6. Profile
 *  7. Feature modules (search, dashboard, postingan, project, sertifikat, game)
 *
 * Alpine.start() HARUS berada di baris paling akhir, setelah semua
 * window.* Alpine components (notificationBell, keahlianTambahan, dll) didaftarkan.
 */

import './bootstrap';
import Alpine from 'alpinejs';
import { showSuccessAlert, showErrorAlert, showLoading, closeLoading, showConfirm, showInfoAlert } from './alert.js';
import './alert.js';
import './translate';

// Expose alert helpers ke window (dipakai blade inline)
window.showSuccessAlert = showSuccessAlert;
window.showErrorAlert   = showErrorAlert;
window.showLoading      = showLoading;
window.closeLoading     = closeLoading;
window.showConfirmAlert = showConfirm;

// Alpine harus tersedia di window sebelum komponen didaftarkan
window.Alpine = Alpine;

// ==========================================
// CORE
// ==========================================
import './modules/core/helpers.js';
import './modules/core/dark-mode.js';
import { initPWA } from './modules/core/pwa.js';
import { initUserGuide } from './modules/core/user-guide.js';

// ==========================================
// UI COMPONENTS
// ==========================================
import './modules/ui/sidebar.js';
import './modules/ui/mobile-nav.js';
import './modules/ui/form-utils.js';
import './modules/ui/notifications.js';

// ==========================================
// AUTH
// ==========================================
import './modules/auth/otp.js';
import './modules/auth/passkey.js';

// ==========================================
// PROFILE
// ==========================================
import './modules/profile/cropper.js';
import './modules/profile/portfolio.js';

// ==========================================
// SEARCH
// ==========================================
import './modules/search/header-search.js';

// ==========================================
// DASHBOARD
// ==========================================
import './modules/dashboard/index.js';

// ==========================================
// POSTINGAN
// ==========================================
import './modules/postingan/detail.js';

// ==========================================
// PROJECT
// ==========================================
import './modules/project/pages.js';
import './modules/project/edit.js';
import './modules/project/create.js';

// ==========================================
// SERTIFIKAT
// ==========================================
import './modules/sertifikat/index.js';

// ==========================================
// GAMES
// ==========================================
import './modules/game/matematika.js';
import './modules/game/puzzle.js';
import './modules/game/tts.js';

// ==========================================
// GLOBAL SESSION ALERTS + PAGE INITIALIZERS
// ==========================================
import {
    initVerifyOtp,
    initForgotPassword,
    initResetPassword,
} from './modules/auth/otp.js';
import { initPasskeyManagement, initVerifyPasskey } from './modules/auth/passkey.js';
import { initRegisterPage } from './modules/profile/cropper.js';
import { initProfilePage } from './modules/profile/cropper.js';

window.checkSessionAlerts = function () {
    const flashEl = document.getElementById('flash-message');
    if (flashEl) {
        const success = flashEl.getAttribute('data-success');
        const error   = flashEl.getAttribute('data-error');
        if (success && typeof window.showSuccessAlert === 'function') window.showSuccessAlert(success);
        if (error   && typeof window.showErrorAlert   === 'function') window.showErrorAlert(error);
        flashEl.remove();
    }

    const sessionData = document.getElementById('session-alert-data');
    const body = document.body;
    const src = sessionData || body;
    if (!src) return;

    const get = (key) => src?.dataset?.[key] || src?.getAttribute?.('data-' + key);
    const success = get('sessionSuccess'); const error = get('sessionError');
    const warning = get('sessionWarning'); const errorsFirst = get('errorsFirst');
    const errorsAll = get('errorsAll'); const isBlocked = get('isBlocked');

    if (success) window.showSuccessAlert?.(success);
    if (error)   window.showErrorAlert?.(error);
    if (warning && typeof Swal !== 'undefined') Swal.fire({ icon: 'warning', title: 'Perhatian!', text: warning, confirmButtonColor: '#2563eb' });
    if (isBlocked === 'true' && typeof Swal !== 'undefined') Swal.fire({ icon: 'warning', title: 'Pendaftaran Dibatasi', text: 'Anda telah melebihi batas percobaan pendaftaran.', confirmButtonColor: '#2563eb', confirmButtonText: 'Mengerti' });
    if (errorsAll && typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Validasi Gagal', html: errorsAll, confirmButtonColor: '#2563eb', confirmButtonText: 'OK' });
    else if (errorsFirst) {
        if (errorsFirst === 'PENGAJUAN_DIPROSES') window.showAlert?.('Pengajuan akun Anda sedang diproses. Mohon tunggu konfirmasi dari admin.', 'warning');
        else if (errorsFirst === 'PENGAJUAN_DITOLAK') window.showAlert?.('Pengajuan akun Anda ditolak. Silakan hubungi admin.', 'error');
        else if (errorsFirst === 'AKUN_DIBLOKIR') window.showAlert?.('Akun Anda diblokir. Silakan hubungi admin.', 'error');
        else window.showErrorAlert?.(errorsFirst);
    }
};

window.runPageInitializers = function () {
    // Auth pages
    if (document.getElementById('passkeyName') && document.getElementById('addPasskeyBtn')) initPasskeyManagement();
    if (document.getElementById('verifyPasskeyBtn') && document.getElementById('cancelBtn') && window.location.pathname.includes('verify')) initVerifyPasskey();
    if (document.getElementById('otpForm') && document.getElementById('otp')) initVerifyOtp();
    if (document.getElementById('email') && document.querySelector('form[action*="sendOtp"]')) initForgotPassword();
    if (document.getElementById('password') && document.getElementById('password-confirm')) initResetPassword();
    // Register & Profile
    if (document.getElementById('photo_profile') && document.getElementById('cropper-modal')) initRegisterPage();
    if (document.getElementById('form-profile')) initProfilePage();
    // Postingan
    const detailContainer = document.getElementById('postingan-detail-container');
    if (detailContainer) window.initPostinganDetailPage?.(detailContainer);
    // Projects
    const projectUserContainer = document.getElementById('project-user-container');
    if (projectUserContainer) window.showPageInfo?.('popup.project_saya');
    const allProjectsContainer = document.getElementById('all-projects-container');
    if (allProjectsContainer) window.showPageInfo?.('popup.semua_project');
    const projectDetailContainer = document.getElementById('project-detail-container');
    if (projectDetailContainer) window.initProjectDetailPage?.(projectDetailContainer);
    const projectCreateContainer = document.getElementById('project-create-container');
    if (projectCreateContainer) window.initProjectCreatePage?.(projectCreateContainer);
    const projectEditContainer = document.getElementById('project-edit-container');
    if (projectEditContainer) window.initProjectEditPage?.(projectEditContainer);
    // Sertifikat
    const sertifikatListContainer = document.getElementById('sertifikat-page-container');
    if (sertifikatListContainer) window.initSertifikatListPage?.(sertifikatListContainer);
    const sertifikatUserContainer = document.getElementById('sertifikat-user-container');
    if (sertifikatUserContainer) window.initSertifikatUserPage?.(sertifikatUserContainer);
    const sertifikatCreateContainer = document.getElementById('sertifikat-create-container');
    if (sertifikatCreateContainer) window.initSertifikatCreatePage?.(sertifikatCreateContainer);
    const sertifikatEditContainer = document.getElementById('sertifikat-edit-container');
    if (sertifikatEditContainer) window.initSertifikatEditPage?.(sertifikatEditContainer);
    };

// Jalankan init saat DOM siap dan setelah Turbo navigasi
['DOMContentLoaded', 'turbo:load'].forEach(evt => {
    document.addEventListener(evt, () => {
        // Dark mode
        if (typeof initDarkMode === 'function') initDarkMode();
        window.checkSessionAlerts?.();
        window.runPageInitializers?.();
        // Lompat ke section pendidikan/pengalaman jika baru saja
        // melakukan CRUD di section tersebut (lihat portfolio.js).
        window.scrollToPendingSection?.();
        // Language dropdown sync
        const langSelect = document.getElementById('languageSelect');
        if (langSelect) langSelect.value = localStorage.getItem('lang') || 'id';
        // User guide
        initUserGuide();
    });
});

// PWA
initPWA();

// ==========================================
// Alpine.start() — HARUS PALING BAWAH
// ==========================================
Alpine.start();