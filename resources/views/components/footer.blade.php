<footer class="dark:bg-gray-900/70 border-t border-gray-200 dark:border-gray-800 mt-auto transition-all duration-300 relative" id="mainFooter">
    <!-- Toggle Button Center -->
    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 z-10">
        <button id="toggleFooterBtn" 
            class="w-7 h-7 bg-gray-300 dark:bg-gray-700 rounded-full flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300 shadow-md cursor-pointer active:scale-95">
            <svg id="toggleIcon" class="w-3.5 h-3.5 text-gray-600 dark:text-gray-300 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </div>

    <!-- Footer Content -->
    <div id="footerContent" class="transition-all duration-300 overflow-hidden">
        <div class="px-4 sm:px-6 py-2 sm:py-3">
            
            <!-- Row 1: Logo Kiri & Kontak Kanan (Mobile) -->
            <div class="flex flex-row justify-between items-start gap-3 mb-3">
                <!-- Left Side - Logo & Alamat -->
                <div class="flex flex-col items-start text-left flex-1">
                    <div class="bg-black rounded-lg p-1 inline-block mb-1">
                        <img src="{{ asset('assets/logoFooter.webp') }}" alt="POLMIND Logo" class="h-6 sm:h-7 w-auto">
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-[10px] sm:text-xs max-w-[160px]">
                        {{ autoTranslate('Kawasan Industri MM2100') }}
                    </p>
                </div>
                
                <!-- Right Side - Contact Info -->
                <div class="flex flex-col items-end gap-1 flex-1">
                    <a href="https://wa.me/{{ env('CONTACT_PHONE', '6282113296897') }}?text=Halo%20saya%20butuh%20informasi%20mengenai%20POLMIND" 
                       target="_blank" 
                       class="flex items-center gap-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 px-1.5 py-0.5 rounded transition-colors duration-200 group">
                        <svg class="w-3 h-3 text-gray-500 group-hover:text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="text-gray-600 dark:text-gray-400 text-[10px] sm:text-xs group-hover:text-green-600 transition-colors">+62 821-1329-6897</span>
                    </a>
                    
                    <a href="mailto:{{ env('CONTACT_EMAIL', 'info@polmind.ac.id') }}?subject=Informasi%20POLMIND&body=Halo%20saya%20ingin%20menanyakan%20..." 
                       class="flex items-center gap-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 px-1.5 py-0.5 rounded transition-colors duration-200 group">
                        <svg class="w-3 h-3 text-gray-500 group-hover:text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-600 dark:text-gray-400 text-[10px] sm:text-xs group-hover:text-blue-600 transition-colors break-all">{{ env('CONTACT_EMAIL', 'info@polmind.ac.id') }}</span>
                    </a>
                    
                    <a href="{{ env('CONTACT_MAPS', 'https://maps.app.goo.gl/UgUBmN7joH9fA2Jw5') }}" 
                       target="_blank" 
                       class="flex items-center gap-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 px-1.5 py-0.5 rounded transition-colors duration-200 group">
                        <svg class="w-3 h-3 text-gray-500 group-hover:text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-gray-600 dark:text-gray-400 text-[10px] sm:text-xs group-hover:text-red-600 transition-colors">{{ autoTranslate('Kawasan Industri MM2100') }}</span>
                    </a>
                </div>
            </div>
            
            <!-- Row 2: Navigation Links & Social Media -->
            <div class="flex flex-col items-center gap-2 pt-2 border-t border-gray-200 dark:border-gray-800">
                <div class="flex flex-wrap justify-center gap-2 text-[10px] sm:text-xs">
                    <a href="https://www.polmind.ac.id/beranda" target="_blank" 
                       class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition-colors">
                        {{ autoTranslate('About') }}
                    </a>
                    <span class="text-gray-400">|</span>
                    <a href="{{ route('help', ['locale' => app()->getLocale()]) }}" 
                       class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition-colors">
                        {{ autoTranslate('Help Center') }}
                    </a>
                    <span class="text-gray-400">|</span>
                    <a href="{{ route('get-app', ['locale' => app()->getLocale()]) }}" 
                       class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition-colors">
                        {{ autoTranslate('Get the App') }}
                    </a>
                </div>
                
                <div class="flex flex-wrap justify-center gap-1.5">
                    <a href="{{ env('SOCIAL_WEBSITE', 'https://polmind.ac.id') }}" target="_blank" 
                       class="w-6 h-6 bg-blue-500 hover:bg-blue-600 rounded-full flex items-center justify-center transition-all hover:scale-110">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                    </a>
                    <a href="{{ env('SOCIAL_INSTAGRAM', 'https://instagram.com') }}" target="_blank" 
                       class="w-6 h-6 bg-gradient-to-br from-pink-400 via-pink-500 to-red-500 rounded-full flex items-center justify-center transition-all hover:scale-110">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069z"/>
                        </svg>
                    </a>
                    <a href="{{ env('SOCIAL_YOUTUBE', 'https://youtube.com') }}" target="_blank" 
                       class="w-6 h-6 bg-red-600 hover:bg-red-700 rounded-full flex items-center justify-center transition-all hover:scale-110">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21.543 6.498C22 8.28 22 12 22 12s0 3.72-.457 5.502c-.254.985-.997 1.76-1.938 2.022C17.896 20 12 20 12 20s-5.893 0-6.605-.476c-.945-.266-1.687-1.04-1.938-2.022C3 15.72 3 12 3 12s0-3.72.457-5.502c.254-.985.997-1.76 1.938-2.022C6.107 4 12 4 12 4s5.896 0 6.605.476c.945.266 1.687 1.04 1.938 2.022zM10 15l6-3-6-3v6z"/>
                        </svg>
                    </a>
                    <a href="{{ env('SOCIAL_TIKTOK', 'https://tiktok.com') }}" target="_blank" 
                       class="w-6 h-6 bg-black rounded-full flex items-center justify-center transition-all hover:scale-110">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="text-center pt-2 mt-2 border-t border-gray-200 dark:border-gray-800">
                <p class="text-gray-500 dark:text-gray-400 text-[9px] sm:text-[10px]">
                    &copy; {{ date('Y') }} {{ config('app.name', 'POLMIND') }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</footer>

<script>
    // Footer Toggle Function
    document.addEventListener('DOMContentLoaded', function() {
        const footerContent = document.getElementById('footerContent');
        const toggleBtn = document.getElementById('toggleFooterBtn');
        const toggleIcon = document.getElementById('toggleIcon');
        
        let isFooterHidden = localStorage.getItem('footerHidden') === 'true';
        
        function getFooterHeight() {
            const clone = footerContent.cloneNode(true);
            clone.style.position = 'absolute';
            clone.style.visibility = 'hidden';
            clone.style.height = 'auto';
            clone.style.maxHeight = 'none';
            clone.style.opacity = '0';
            document.body.appendChild(clone);
            const height = clone.scrollHeight;
            document.body.removeChild(clone);
            return height;
        }
        
        if (isFooterHidden) {
            footerContent.style.maxHeight = '0';
            footerContent.style.padding = '0';
            footerContent.style.opacity = '0';
            if (toggleIcon) toggleIcon.style.transform = 'rotate(180deg)';
        } else {
            const initialHeight = getFooterHeight();
            footerContent.style.maxHeight = initialHeight + 'px';
            footerContent.style.opacity = '1';
        }
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (isFooterHidden) {
                    const newHeight = getFooterHeight();
                    footerContent.style.maxHeight = newHeight + 'px';
                    footerContent.style.opacity = '1';
                    if (toggleIcon) toggleIcon.style.transform = 'rotate(0deg)';
                    isFooterHidden = false;
                    localStorage.setItem('footerHidden', 'false');
                } else {
                    footerContent.style.maxHeight = '0';
                    footerContent.style.opacity = '0';
                    if (toggleIcon) toggleIcon.style.transform = 'rotate(180deg)';
                    isFooterHidden = true;
                    localStorage.setItem('footerHidden', 'true');
                }
            });
        }
        
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                if (!isFooterHidden) {
                    const newHeight = getFooterHeight();
                    footerContent.style.maxHeight = newHeight + 'px';
                }
            }, 150);
        });
    });
</script>