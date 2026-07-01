/**
 * modules/core/dark-mode.js
 * Dark mode toggle, sidebar, mobile nav, dan guest sheet.
 */

export function initDarkMode() {
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

// Sidebar dark mode toggle
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

window.toggleSidebarDarkMode = function () {
    const html = document.documentElement;
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('darkMode', isDark);
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    const btn = document.getElementById('darkModeBtn');
    if (btn) btn.textContent = isDark ? 'Light Mode' : 'Dark Mode';
};

window.toggleMobileDarkMode = function () {
    const html = document.documentElement;
    html.classList.toggle('dark');
    localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    window.syncDarkModeToggle?.();
};

window.toggleGuestDarkMode = function () {
    const html = document.documentElement;
    html.classList.toggle('dark');
    localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    window.syncGuestDarkToggle?.();
};

window.syncDarkModeToggle = function () {
    const isDark = document.documentElement.classList.contains('dark');
    const toggleSpan = document.querySelector('#mobile-dark-toggle span');
    const toggleDiv = document.getElementById('mobile-dark-toggle');
    if (toggleSpan) {
        toggleSpan.classList.toggle('translate-x-5', isDark);
        toggleSpan.classList.toggle('translate-x-0', !isDark);
    }
    if (toggleDiv) {
        toggleDiv.classList.toggle('dark:bg-blue-600', isDark);
    }
};

window.syncGuestDarkToggle = function () {
    const isDark = document.documentElement.classList.contains('dark');
    const toggleSpan = document.querySelector('#guest-dark-toggle span');
    if (toggleSpan) {
        toggleSpan.classList.toggle('translate-x-5', isDark);
    }
};

document.addEventListener('DOMContentLoaded', () => {
    // Legacy compat: jika darkMode di localStorage === 'true'
    if (localStorage.getItem('darkMode') === 'true' || localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
        const btn = document.getElementById('darkModeBtn');
        if (btn) btn.textContent = 'Light Mode';
    }
    window.syncDarkModeToggle?.();
    window.syncGuestDarkToggle?.();
});
