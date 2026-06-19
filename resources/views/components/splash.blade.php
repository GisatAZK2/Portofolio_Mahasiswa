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
        <p class="welcome-title" style="font-size: 1.25rem; font-weight: 300; letter-spacing: 2px;" data-translate="splash_welcome" data-translate-page="splash">SELAMAT DATANG</p>
        <p class="welcome-subtitle" style="font-size: 2rem; font-weight: 700; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);" data-translate="splash_portofolio" data-translate-page="splash">DI POLMIND PORTOFOLIO</p>
    </div>
    
    <button class="skip-button" id="skipButton" style="position: absolute; bottom: 1.5rem; right: 1.5rem; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); color: white; border: 1px solid rgba(255, 255, 255, 0.3); padding: 0.5rem 1.25rem; border-radius: 50px; font-size: 0.875rem; cursor: pointer; transition: all 0.3s ease; opacity: 0; z-index: 20;" data-translate="splash_skip" data-translate-page="splash">Lewati →</button>
</div>