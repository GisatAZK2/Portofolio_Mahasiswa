<div id="splash-screen-root" style="position: fixed; top: 0; left: 0; width: 100%; height: 100vh; z-index: 99999; background-color: rgba(0, 0, 0, 0.3); overflow: hidden; backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);">
    <div class="door-left" id="doorLeft" style="position: absolute; top: 0; bottom: 0; left: 0; width: 50%; transition: transform 1s ease-out; transform: translateX(0); background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%);">
        <div class="door-cutout-left" style="position: absolute; top: 50%; right: 0; width: 4rem; height: 8rem; transform: translateY(-50%); background-color: transparent; clip-path: polygon(0% 0%, 100% 15%, 100% 85%, 0% 100%);"></div>
    </div>
    
    <div class="door-right" id="doorRight" style="position: absolute; top: 0; bottom: 0; right: 0; width: 50%; transition: transform 1s ease-out; transform: translateX(0); background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%);">
        <div class="door-cutout-right" style="position: absolute; top: 50%; left: 0; width: 4rem; height: 8rem; transform: translateY(-50%); background-color: transparent; clip-path: polygon(100% 0%, 0% 15%, 0% 85%, 100% 100%);"></div>
    </div>
    
    <div class="center-circle" style="position: absolute; top: 50%; left: 50%; width: 8rem; height: 8rem; transform: translate(-50%, -50%); background-color: white; border-radius: 50%; overflow: hidden; z-index: 10; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);">
        <div class="spinner-wrapper" id="spinnerWrapper" style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; transition: opacity 0.5s; opacity: 1; background: white;">
            <div class="spinner" style="width: 3rem; height: 3rem; border: 4px solid #3b82f6; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
        </div>
        <div class="logo-wrapper" id="logoWrapper" style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; transition: opacity 0.5s; transition-delay: 0.5s; opacity: 0; background: white; border-radius: 50%;">
            <img src="{{ asset('assets/Logo.svg') }}" alt="POLMIND Logo" class="center-logo" style="width: 85%; height: 85%; object-fit: contain; border-radius: 50%;">
        </div>
    </div>
    
    <div class="gradient-overlay" id="gradientOverlay" style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(59, 130, 246, 0.2), transparent); opacity: 0; z-index: 5;"></div>
    
    <div class="welcome-text" id="welcomeText" style="position: absolute; bottom: 10rem; left: 0; right: 0; text-align: center; color: white; opacity: 0; transition: opacity 0.5s; transition-delay: 0.7s; z-index: 10;">
        <p class="welcome-title" style="font-size: 1.25rem; font-weight: 300; letter-spacing: 2px;" >{{ autoTranslate('SELAMAT DATANG') }}</p>
        <p class="welcome-subtitle" style="font-size: 2rem; font-weight: 700; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);" >{{ autoTranslate('DI') }} POLMIND PORTOFOLIO</p>
    </div>
    
    <button class="skip-button" id="skipButton" style="position: absolute; bottom: 1.5rem; right: 1.5rem; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); color: white; border: 1px solid rgba(255, 255, 255, 0.3); padding: 0.5rem 1.25rem; border-radius: 50px; font-size: 0.875rem; cursor: pointer; transition: all 0.3s ease; opacity: 0; z-index: 20;">{{ autoTranslate('Lewati') }} →</button>
</div>

<style>
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 0.2; }
        50% { opacity: 0.4; }
    }
    
    .door-left.animate {
        transform: translateX(-100%) !important;
    }
    
    .door-right.animate {
        transform: translateX(100%) !important;
    }
    
    .spinner-wrapper.hidden {
        opacity: 0 !important;
    }
    
    .logo-wrapper.visible {
        opacity: 1 !important;
    }
    
    .gradient-overlay.animate {
        opacity: 0.2 !important;
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    .welcome-text.visible {
        opacity: 1 !important;
    }
    
    .skip-button.visible {
        opacity: 1 !important;
    }
    
    .door-left::before,
    .door-right::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, 
            rgba(255,255,255,0.15) 0%, 
            transparent 50%,
            rgba(0,0,0,0.05) 100%);
    }
    
    .door-left::after {
        content: '';
        position: absolute;
        top: 50%;
        right: 1rem;
        width: 0.5rem;
        height: 3rem;
        transform: translateY(-50%);
        background: linear-gradient(to right, rgba(0,0,0,0.15), transparent);
        border-radius: 0.25rem;
    }
    
    .door-right::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 1rem;
        width: 0.5rem;
        height: 3rem;
        transform: translateY(-50%);
        background: linear-gradient(to left, rgba(0,0,0,0.15), transparent);
        border-radius: 0.25rem;
    }
    
    @media (max-width: 640px) {
        .center-circle {
            width: 6rem !important;
            height: 6rem !important;
        }
        
        .door-cutout-left,
        .door-cutout-right {
            width: 3rem !important;
            height: 6rem !important;
        }
        
        .welcome-title {
            font-size: 1rem !important;
        }
        
        .welcome-subtitle {
            font-size: 1.5rem !important;
        }
        
        .skip-button {
            bottom: 1rem !important;
            right: 1rem !important;
            padding: 0.4rem 1rem !important;
            font-size: 0.75rem !important;
        }
    }
    
    .dark .door-left,
    .dark .door-right {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%) !important;
    }
    
    .dark .center-circle {
        background: #1e293b !important;
    }
    
    .dark .spinner-wrapper {
        background: #1e293b !important;
    }
    
    .dark .logo-wrapper {
        background: #1e293b !important;
    }
    
    .dark .spinner {
        border-color: #60a5fa !important;
        border-top-color: transparent !important;
    }
</style>

<script>
    (function() {
        const SPLASH_KEY = 'splashShown';
        const SPLASH_VERSION = '1.0.0';
        const STORAGE_KEY = SPLASH_KEY + '_v' + SPLASH_VERSION;
        const EXPIRE_KEY = SPLASH_KEY + '_expire';
        
        const splashRoot = document.getElementById('splash-screen-root');
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
        
        function setSplashExpire() {
            const now = new Date();
            const tomorrow = new Date(now);
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(0, 0, 0, 0);
            localStorage.setItem(EXPIRE_KEY, tomorrow.getTime().toString());
            localStorage.setItem(STORAGE_KEY, 'true');
        }
        
        function isSplashExpired() {
            const expireTime = localStorage.getItem(EXPIRE_KEY);
            if (!expireTime) return true;
            
            const now = new Date().getTime();
            return now > parseInt(expireTime);
        }
        
        function hideSplash() {
            if (isHiding || !splashRoot) return;
            isHiding = true;
            
            if (hideTimer) clearTimeout(hideTimer);
            if (animationTimer) clearTimeout(animationTimer);
            
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
            const hasShown = localStorage.getItem(STORAGE_KEY) === 'true';
            const isExpired = isSplashExpired();
            const urlParams = new URLSearchParams(window.location.search);
            const skipSplash = urlParams.has('skip_splash');
            
            if ((hasShown && !isExpired) || skipSplash) {
                if (splashRoot) {
                    splashRoot.remove();
                }
                document.body.style.overflow = '';
                document.body.style.position = '';
                document.body.style.width = '';
                document.body.style.top = '';
                return;
            }
            
            if (isExpired) {
                localStorage.removeItem(STORAGE_KEY);
                localStorage.removeItem(EXPIRE_KEY);
            }
            
            const scrollY = window.scrollY;
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.width = '100%';
            document.body.style.top = `-${scrollY}px`;
            
            setSplashExpire();
            
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
            localStorage.removeItem(STORAGE_KEY);
            localStorage.removeItem(EXPIRE_KEY);
            window.location.reload();
        };
    })();
</script>