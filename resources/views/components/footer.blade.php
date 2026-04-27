<footer class="dark:bg-gray-900/70 border-t border-gray-200 dark:border-gray-800 transition-all duration-300 relative z-20 hidden"
    id="mainFooter">
    <div id="footerContent" class="transition-all duration-500 overflow-hidden">
        
        <!-- ================= MOBILE VIEW ================= -->
        <div class="block md:hidden px-5 py-6">
            <div class="grid grid-cols-2 gap-6">
                <!-- KIRI -->
                <div class="space-y-4">
                    <!-- Logo -->
                    <div class="bg-black rounded-lg p-2 inline-block">
                        <img src="{{ asset('assets/logoFooter.webp') }}" alt="POLMIND Logo" class="h-9 w-auto">
                    </div>
                    <!-- Menu -->
                    <div class="flex flex-col gap-2.5 text-sm">
                        <a href="https://www.polmind.ac.id/beranda" target="_blank"
                            class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition-colors">
                            {{ autoTranslate('About') }}
                        </a>
                        <a href="{{ route('help', ['locale' => app()->getLocale()]) }}"
                            class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition-colors">
                            {{ autoTranslate('Help') }}
                        </a>
                        <a href="{{ route('get-app', ['locale' => app()->getLocale()]) }}"
                            class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition-colors">
                            {{ autoTranslate('Get App') }}
                        </a>
                    </div>
                </div>

                <!-- KANAN -->
                <div class="space-y-4">
                    <!-- Contact -->
                    <div class="flex flex-col gap-3 text-sm">
                        <!-- WhatsApp -->
                        <a href="https://wa.me/{{ env('CONTACT_PHONE', '6282113296897') }}?text=Halo%20saya%20butuh%20informasi%20mengenai%20POLMIND"
                           target="_blank"
                           class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors group">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="text-sm group-hover:text-green-600">+62 821-1329-6897</span>
                        </a>
                       
                        <!-- Email -->
                        <a href="mailto:{{ env('CONTACT_EMAIL', 'info@polmind.ac.id') }}?subject=Informasi%20POLMIND&body=Halo%20saya%20ingin%20menanyakan%20..."
                           class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors group">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm group-hover:text-blue-600">{{ env('CONTACT_EMAIL', 'info@polmind.ac.id') }}</span>
                        </a>

                        <!-- Maps -->
                        <a href="{{ env('CONTACT_MAPS', 'https://maps.app.goo.gl/UgUBmN7joH9fA2Jw5') }}"
                           target="_blank"
                           class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors group">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-sm group-hover:text-red-600">{{ autoTranslate('Lokasi') }}</span>
                        </a>
                    </div>

                    <!-- Sosial Media -->
                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="{{ env('SOCIAL_WEBSITE', 'https://polmind.ac.id') }}" target="_blank"
                           class="w-8 h-8 bg-blue-500 hover:bg-blue-600 rounded-full flex items-center justify-center transition-all hover:scale-110"
                           title="Website">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                            </svg>
                        </a>
                        <a href="{{ env('SOCIAL_INSTAGRAM', 'https://instagram.com') }}" target="_blank"
                           class="w-8 h-8 bg-gradient-to-br from-purple-500 via-pink-500 to-orange-400 hover:from-purple-600 hover:via-pink-600 hover:to-orange-500 rounded-full flex items-center justify-center transition-all hover:scale-110"
                           title="Instagram">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 6.865a5.135 5.135 0 100 10.27 5.135 5.135 0 000-10.27zm0 8.468a3.333 3.333 0 110-6.666 3.333 3.333 0 010 6.666zm5.338-8.662a1.2 1.2 0 100 2.4 1.2 1.2 0 000-2.4z"/>
                            </svg>
                        </a>
                        <a href="{{ env('SOCIAL_YOUTUBE', 'https://youtube.com') }}" target="_blank"
                           class="w-8 h-8 bg-red-600 hover:bg-red-700 rounded-full flex items-center justify-center transition-all hover:scale-110"
                           title="YouTube">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21.543 6.498C22 8.28 22 12 22 12s0 3.72-.457 5.502c-.254.985-.997 1.76-1.938 2.022C17.896 20 12 20 12 20s-5.893 0-7.605-.476c-.945-.266-1.687-1.04-1.938-2.022C2 15.72 2 12 2 12s0-3.72.457-5.502c.254-.985.997-1.76 1.938-2.022C6.107 4 12 4 12 4s5.896 0 7.605.476c.945.266 1.687 1.04 1.938 2.022zM10 15l6-3-6-3v6z"/>
                            </svg>
                        </a>
                        <a href="{{ env('SOCIAL_TIKTOK', 'https://tiktok.com') }}" target="_blank"
                           class="w-8 h-8 bg-black hover:bg-gray-900 rounded-full flex items-center justify-center transition-all hover:scale-110"
                           title="TikTok">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="text-center border-t border-gray-200 dark:border-gray-800 mt-6 pt-4">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    &copy; {{ date('Y') }} {{ config('app.name', 'POLMIND') }}
                    {{ autoTranslate('All rights reserved.') }}
                </p>
            </div>
        </div>

        <!-- ================= DESKTOP VIEW ================= -->
        <div class="hidden md:block px-8 py-4">
            <div class="flex items-center justify-between gap-6">
                <!-- Left -->
                <div class="flex items-center gap-6">
                    <div class="bg-black rounded-lg p-2">
                        <img src="{{ asset('assets/logoFooter.webp') }}" alt="POLMIND Logo" class="h-9 w-auto">
                    </div>
                    <div class="flex items-center gap-6 text-sm">
                        <a href="https://www.polmind.ac.id/beranda" target="_blank"
                            class="text-gray-600 dark:text-gray-300 hover:text-blue-600">
                            {{ autoTranslate('About') }}
                        </a>
                        <a href="{{ route('help', ['locale' => app()->getLocale()]) }}"
                            class="text-gray-600 dark:text-gray-300 hover:text-blue-600">
                            {{ autoTranslate('Help') }}
                        </a>
                        <a href="{{ route('get-app', ['locale' => app()->getLocale()]) }}"
                            class="text-gray-600 dark:text-gray-300 hover:text-blue-600">
                            {{ autoTranslate('Get App') }}
                        </a>
                    </div>
                </div>

                <!-- Right -->
                <div class="flex items-center gap-6 text-sm">
                    <a href="https://wa.me/{{ env('CONTACT_PHONE', '6282113296897') }}" target="_blank"
                        class="text-gray-600 dark:text-gray-300 hover:text-green-600">
                        +62 821-1329-6897
                    </a>
                    <a href="mailto:{{ env('CONTACT_EMAIL', 'info@polmind.ac.id') }}"
                        class="text-gray-600 dark:text-gray-300 hover:text-blue-600">
                        {{ env('CONTACT_EMAIL', 'info@polmind.ac.id') }}
                    </a>
                    <a href="{{ env('CONTACT_MAPS', 'https://maps.app.goo.gl/UgUBmN7joH9fA2Jw5') }}"
                        target="_blank"
                        class="text-gray-600 dark:text-gray-300 hover:text-red-600">
                        Maps
                    </a>

                    <!-- Social Icons -->
                    <div class="flex items-center gap-3">
                        <a href="{{ env('SOCIAL_WEBSITE', 'https://polmind.ac.id') }}" target="_blank"
                           class="w-8 h-8 bg-blue-500 hover:bg-blue-600 rounded-full flex items-center justify-center transition-all hover:scale-110"
                           title="Website">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                            </svg>
                        </a>
                        <a href="{{ env('SOCIAL_INSTAGRAM', 'https://instagram.com') }}" target="_blank"
                           class="w-8 h-8 bg-gradient-to-br from-purple-500 via-pink-500 to-orange-400 hover:from-purple-600 hover:via-pink-600 hover:to-orange-500 rounded-full flex items-center justify-center transition-all hover:scale-110"
                           title="Instagram">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 6.865a5.135 5.135 0 100 10.27 5.135 5.135 0 000-10.27zm0 8.468a3.333 3.333 0 110-6.666 3.333 3.333 0 010 6.666zm5.338-8.662a1.2 1.2 0 100 2.4 1.2 1.2 0 000-2.4z"/>
                            </svg>
                        </a>
                        <a href="{{ env('SOCIAL_YOUTUBE', 'https://youtube.com') }}" target="_blank"
                           class="w-8 h-8 bg-red-600 hover:bg-red-700 rounded-full flex items-center justify-center transition-all hover:scale-110"
                           title="YouTube">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21.543 6.498C22 8.28 22 12 22 12s0 3.72-.457 5.502c-.254.985-.997 1.76-1.938 2.022C17.896 20 12 20 12 20s-5.893 0-7.605-.476c-.945-.266-1.687-1.04-1.938-2.022C2 15.72 2 12 2 12s0-3.72.457-5.502c.254-.985.997-1.76 1.938-2.022C6.107 4 12 4 12 4s5.896 0 7.605.476c.945.266 1.687 1.04 1.938 2.022zM10 15l6-3-6-3v6z"/>
                            </svg>
                        </a>
                        <a href="{{ env('SOCIAL_TIKTOK', 'https://tiktok.com') }}" target="_blank"
                           class="w-8 h-8 bg-black hover:bg-gray-900 rounded-full flex items-center justify-center transition-all hover:scale-110"
                           title="TikTok">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="text-center border-t border-gray-200 dark:border-gray-800 mt-4 pt-3">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    &copy; {{ date('Y') }} {{ config('app.name', 'POLMIND') }}
                    {{ autoTranslate('All rights reserved.') }}
                </p>
            </div>
        </div>
    </div>
</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const footer = document.getElementById("mainFooter");
    const main = document.querySelector("main.overflow-auto");
    if (!footer) return;

    function setBodyPadding() {
        const footerHeight = footer.offsetHeight;
        document.body.style.paddingBottom = footerHeight + "px";
        if (main) {
            main.style.paddingBottom = (footerHeight + 24) + "px";
        }
    }

    function toggleFooter() {
        let scrollTop;
        
        if (main) {
            scrollTop = main.scrollTop;
        } else {
            scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        }

        if (scrollTop > 300) {
            footer.classList.remove("hidden");
        } else {
            footer.classList.add("hidden");
        }
    }

    if (main) {
        main.addEventListener("scroll", toggleFooter);
    } else {
        window.addEventListener("scroll", toggleFooter);
    }

    window.addEventListener("resize", () => {
        setBodyPadding();
        toggleFooter();
    });

    // Initial call
    setTimeout(() => {
        toggleFooter();
    }, 100);
});
</script>