// utils/theme.js

export function setTheme(theme) {
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
}

export function getTheme() {
    return localStorage.getItem('theme') || 'light';
}

export function initTheme() {
    const theme = getTheme();
    setTheme(theme);
}
