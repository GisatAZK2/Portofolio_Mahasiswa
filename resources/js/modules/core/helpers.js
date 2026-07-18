/**
 * modules/core/helpers.js
 * Utility helpers yang digunakan di seluruh aplikasi.
 */

export function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

export function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

export function getAvatarHtml(user, size = 'w-8 h-8', textSize = 'text-xs') {
    if (user && user.photo_profile && user.photo_profile !== 'null' && user.photo_profile !== '') {
        const photoPath = user.photo_profile.startsWith('http')
            ? user.photo_profile
            : `/storage/${user.photo_profile}`;
        return `<img src="${photoPath}" class="${size} rounded-full object-cover flex-shrink-0"
            onerror="this.src='https://ui-avatars.com/api/?background=6366f1&color=fff&size=100&name=${encodeURIComponent(user.nama_mahasiswa || 'U')}'">`;
    }
    const name = user?.nama_mahasiswa || window.currentUserName || 'User';
    const initial = name.charAt(0).toUpperCase();
    return `<div class="${size} rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                <span class="text-indigo-600 dark:text-indigo-400 ${textSize} font-semibold">${initial}</span>
            </div>`;
}

export function getHeaders() {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfMeta ? csrfMeta.getAttribute('content') : '',
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    };
}

export function getLocale() {
    return document.querySelector('html')?.getAttribute('lang') || 'id';
}

export function buildLocaleUrl(lang) {
    const SUPPORTED = ['id', 'en'];
    const url = new URL(window.location.href);
    const segs = url.pathname.split('/').filter(Boolean);
    if (segs.length > 0 && SUPPORTED.indexOf(segs[0]) !== -1) {
        segs.shift();
    }
    url.pathname = '/' + [lang].concat(segs).join('/');
    // `locale` query param diprioritaskan lebih tinggi daripada segmen URL
    // oleh SetLocale middleware, jadi harus dibuang supaya tidak menimpa
    // balik pilihan bahasa yang baru saja dipilih user.
    url.searchParams.delete('locale');
    return url.toString();
}

export function persistLocaleChoice(lang) {
    localStorage.setItem('lang', lang);
    const days = 365;
    const expires = new Date(Date.now() + days * 24 * 60 * 60 * 1000).toUTCString();
    document.cookie = `lang=${lang}; expires=${expires}; path=/; SameSite=Lax`;
}

// Expose globally agar inline Blade onclick bisa akses
window.escapeHtml = escapeHtml;
window.formatDate = formatDate;
window.getAvatarHtml = getAvatarHtml;
window.persistLocaleChoice = persistLocaleChoice;
window.changeLanguageMobile = function (lang) {
    persistLocaleChoice(lang);
    window.location.href = buildLocaleUrl(lang);
};
window.changeGuestLanguage = function (lang) {
    persistLocaleChoice(lang);
    window.location.href = buildLocaleUrl(lang);
};
