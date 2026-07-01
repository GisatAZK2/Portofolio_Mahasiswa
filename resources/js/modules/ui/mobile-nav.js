/**
 * modules/ui/mobile-nav.js
 * Mobile bottom navigation, FAB sheet, profile sheet, guest sheet, splash screen.
 */

// ==========================================
// MOBILE FAB / PROFILE / GUEST SHEETS
// ==========================================

window.toggleMobileFab = function () {
    const sheet = document.getElementById('mobile-fab-sheet');
    const overlay = document.getElementById('mobile-fab-overlay');
    const icon = document.getElementById('mobile-fab-icon');
    if (!sheet || !overlay) return;
    const isOpen = !sheet.classList.contains('translate-y-full');
    if (isOpen) {
        window.closeMobileFab();
    } else {
        window.closeMobileProfile();
        overlay.classList.remove('hidden');
        overlay.offsetHeight;
        overlay.classList.remove('opacity-0');
        sheet.classList.remove('translate-y-full');
        if (icon) icon.style.transform = 'rotate(45deg)';
    }
};

window.closeMobileFab = function () {
    const sheet = document.getElementById('mobile-fab-sheet');
    const overlay = document.getElementById('mobile-fab-overlay');
    const icon = document.getElementById('mobile-fab-icon');
    if (!sheet || !overlay) return;
    sheet.classList.add('translate-y-full');
    overlay.classList.add('opacity-0');
    if (icon) icon.style.transform = 'rotate(0deg)';
    setTimeout(() => overlay.classList.add('hidden'), 300);
};

window.toggleMobileProfile = function () {
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

window.closeMobileProfile = function () {
    const sheet = document.getElementById('mobile-profile-sheet');
    const overlay = document.getElementById('mobile-profile-overlay');
    if (!sheet || !overlay) return;
    sheet.classList.add('translate-y-full');
    overlay.classList.add('opacity-0');
    setTimeout(() => overlay.classList.add('hidden'), 300);
};

window.toggleGuestSheet = function () {
    const sheet = document.getElementById('guest-sheet');
    const overlay = document.getElementById('guest-sheet-overlay');
    if (!sheet || !overlay) return;
    const isOpen = !sheet.classList.contains('translate-y-full');
    if (isOpen) {
        window.closeGuestSheet();
    } else {
        sheet.classList.remove('translate-y-full');
        overlay.classList.remove('hidden');
        overlay.offsetHeight;
        overlay.classList.remove('opacity-0');
    }
};

window.closeGuestSheet = function () {
    const sheet = document.getElementById('guest-sheet');
    const overlay = document.getElementById('guest-sheet-overlay');
    if (!sheet || !overlay) return;
    sheet.classList.add('translate-y-full');
    overlay.classList.add('opacity-0');
    setTimeout(() => overlay.classList.add('hidden'), 300);
};

// ==========================================
// SPLASH SCREEN
// ==========================================
(function () {
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
        if (!splashRoot) return;

        const doorLeft = document.getElementById('doorLeft');
        const doorRight = document.getElementById('doorRight');
        const spinnerWrapper = document.getElementById('spinnerWrapper');
        const logoWrapper = document.getElementById('logoWrapper');
        const gradientOverlay = document.getElementById('gradientOverlay');
        const welcomeText = document.getElementById('welcomeText');
        const skipButton = document.getElementById('skipButton');
        let isHiding = false, hideTimer = null, animationTimer = null;

        function hideSplash() {
            if (isHiding || !splashRoot) return;
            isHiding = true;
            if (hideTimer) clearTimeout(hideTimer);
            if (animationTimer) clearTimeout(animationTimer);
            setSplashShownCookie();
            splashRoot.style.transition = 'opacity 0.5s ease-out';
            splashRoot.style.opacity = '0';
            setTimeout(() => {
                if (splashRoot?.parentNode) splashRoot.remove();
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
            setTimeout(() => { if (logoWrapper) logoWrapper.classList.add('visible'); }, 500);
            if (gradientOverlay) gradientOverlay.classList.add('animate');
            setTimeout(() => {
                if (welcomeText) welcomeText.classList.add('visible');
                if (skipButton) skipButton.classList.add('visible');
            }, 700);
            hideTimer = setTimeout(hideSplash, 4000);
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
                if (welcomeTitle && splashPage.splash_welcome) welcomeTitle.textContent = splashPage.splash_welcome;
                if (welcomeSubtitle && splashPage.splash_subtitle) welcomeSubtitle.textContent = splashPage.splash_subtitle;
                if (skipBtn && splashPage.splash_skip) skipBtn.innerHTML = splashPage.splash_skip + ' →';
            }

            animationTimer = setTimeout(startAnimation, 300);

            if (skipButton) {
                skipButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    const scrollY = document.body.style.top;
                    hideSplash();
                    document.body.style.position = '';
                    document.body.style.top = '';
                    document.body.style.width = '';
                    document.body.style.overflow = '';
                    window.scrollTo(0, parseInt(scrollY || '0') * -1);
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !isHiding) {
                    const scrollY = document.body.style.top;
                    hideSplash();
                    document.body.style.position = '';
                    document.body.style.top = '';
                    document.body.style.width = '';
                    document.body.style.overflow = '';
                    window.scrollTo(0, parseInt(scrollY || '0') * -1);
                }
            });
        }

        initSplash();
        window.resetSplash = function () {
            clearSplashShownCookie();
            window.location.reload();
        };
    });
})();

// ==========================================
// SWIPE TO CLOSE & INIT
// ==========================================
document.addEventListener('DOMContentLoaded', function () {
    window.syncDarkModeToggle?.();
    window.syncGuestDarkToggle?.();

    const sheets = [
        { id: 'mobile-fab-sheet', close: () => window.closeMobileFab() },
        { id: 'mobile-profile-sheet', close: () => window.closeMobileProfile() },
        { id: 'guest-sheet', close: () => window.closeGuestSheet() },
    ];
    sheets.forEach(({ id, close }) => {
        const el = document.getElementById(id);
        if (!el) return;
        let startY = 0, currentY = 0, dragging = false;
        el.addEventListener('touchstart', e => {
            startY = e.touches[0].clientY; dragging = true; el.style.transition = 'none';
        }, { passive: true });
        el.addEventListener('touchmove', e => {
            if (!dragging) return;
            currentY = e.touches[0].clientY;
            const delta = Math.max(0, currentY - startY);
            el.style.transform = 'translateY(' + delta + 'px)';
        }, { passive: true });
        el.addEventListener('touchend', () => {
            dragging = false; el.style.transition = '';
            if (currentY - startY > 80) close();
            else el.style.transform = '';
        });
    });

    const guestMenuBtn = document.getElementById('guest-menu-btn');
    if (guestMenuBtn) guestMenuBtn.addEventListener('click', window.toggleGuestSheet);
});
