/**
 * modules/ui/form-utils.js
 * Utilitas form: toggle password, button loading state, image preview, modal ESC close.
 */

// ==========================================
// TOGGLE PASSWORD VISIBILITY
// ==========================================

// Legacy: data-toggle-password pattern
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

// New pattern: named target
window.togglePasswordVisibility = function (target) {
    const input = typeof target === 'string'
        ? document.getElementById(target)
        : (target || document.getElementById('password'));
    if (!input) return;
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

// ==========================================
// BUTTON LOADING STATE (awaitButtonAction)
// ==========================================

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
    if (!button || typeof action !== 'function') return await action?.();
    if (button.dataset.awaiting === 'true') return;
    button.dataset.awaitText = awaitText;
    setActionButtonProcessing(button);
    try {
        return await action();
    } finally {
        restoreActionButton(button);
    }
};

// Auto-disable submit buttons on form submit
document.addEventListener('submit', function (event) {
    const form = event.target;
    if (!(form instanceof HTMLFormElement)) return;
    Array.from(form.querySelectorAll('button[type="submit"], input[type="submit"]'))
        .forEach(setActionButtonProcessing);
});

// ==========================================
// IMAGE PREVIEWS (profile photo & background)
// ==========================================

document.addEventListener('change', function (e) {
    if (e.target?.id === 'photo_profile') {
        const file = e.target.files[0];
        const preview = document.getElementById('profile-preview');
        const placeholder = document.getElementById('profile-placeholder') || document.getElementById('profile-preview-placeholder');
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (ev) {
                if (preview) { preview.src = ev.target.result; preview.classList.remove('hidden'); }
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

document.addEventListener('change', function (e) {
    if (e.target?.id === 'background_image') {
        const file = e.target.files[0];
        const previewContainer = document.getElementById('backgroundPreviewContainer');
        const previewImage = document.getElementById('backgroundPreview');
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (ev) {
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

// ==========================================
// MODAL ESC CLOSE
// ==========================================

document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    // Close any visible modal that has a data-modal attribute or common close fn
    const modals = document.querySelectorAll('.modal-backdrop:not(.hidden), [role="dialog"]:not(.hidden)');
    modals.forEach(m => {
        const closeBtn = m.querySelector('[data-modal-close], .modal-close-btn');
        if (closeBtn) closeBtn.click();
    });
});

// ==========================================
// ALERT BANNER (inline form, e.g. login)
// ==========================================

window.showAlert = function (message, type = 'error') {
    const alertDiv = document.getElementById('alertMessage');
    if (!alertDiv) return;
    alertDiv.classList.remove('hidden', 'bg-green-100', 'bg-red-100', 'bg-yellow-100', 'bg-blue-100',
        'text-green-800', 'text-red-800', 'text-yellow-800', 'text-blue-800');
    const colorMap = {
        success: ['bg-green-100', 'text-green-800'],
        warning: ['bg-yellow-100', 'text-yellow-800'],
        info: ['bg-blue-100', 'text-blue-800'],
    };
    const classes = colorMap[type] || ['bg-red-100', 'text-red-800'];
    alertDiv.classList.add(...classes);
    alertDiv.innerHTML = message;
    alertDiv.classList.remove('hidden');
    setTimeout(() => alertDiv.classList.add('hidden'), 5000);
};

// ==========================================
// COPY LINK UTILITY
// ==========================================

window.copyLink = function (url) {
    navigator.clipboard.writeText(url).then(() => {
        const n = document.createElement('div');
        n.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        n.innerHTML = '<div class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>Link berhasil disalin!</span></div>';
        document.body.appendChild(n);
        setTimeout(() => n.remove(), 3000);
    });
};
