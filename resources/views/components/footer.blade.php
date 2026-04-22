<footer class="dark:bg-gray-900/70 border-t border-gray-200 dark:border-gray-800 mt-auto transition-all">
    <div class="px-4 sm:px-6 py-4">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <!-- Left side - Copyright -->
            <p class="text-gray-600 dark:text-gray-200 text-xs text-center sm:text-left order-2 sm:order-1" 
               data-translate="footer_rights" data-translate-page="footer">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
            
            <!-- Center/Right side - Links and Social Icons -->
            <div class="flex flex-col sm:flex-row items-center gap-3 order-1 sm:order-2">
                <!-- Navigation Links -->
                <div class="flex flex-wrap justify-center gap-3 sm:gap-4 text-xs">
                    <a href="{{ route('about', ['locale' => app()->getLocale()]) }}" 
                       class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                        {{ autoTranslate('About') }}
                    </a>
                    <span class="text-gray-400 dark:text-gray-600 select-none">|</span>
                    <a href="{{ route('help', ['locale' => app()->getLocale()]) }}" 
                       class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                        {{ autoTranslate('Help Center') }}
                    </a>
                    <span class="text-gray-400 dark:text-gray-600 select-none">|</span>
                    <a href="{{ route('get-app', ['locale' => app()->getLocale()]) }}" 
                       class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                        {{ autoTranslate('Get the App') }}
                    </a>
                </div>
                
                <!-- Social Media Icons (Smaller size) -->
                <div class="flex gap-1.5 sm:gap-2">
                    <!-- Website -->
                    <a href="{{ env('SOCIAL_WEBSITE', 'https://example.com') }}" target="_blank" rel="noopener noreferrer"
                        class="w-7 h-7 sm:w-8 sm:h-8 bg-blue-500 hover:bg-blue-600 rounded-full flex items-center justify-center shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300"
                        title="Website">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                    </a>

                    <!-- Instagram -->
                    <a href="{{ env('SOCIAL_INSTAGRAM', 'https://instagram.com') }}" target="_blank" rel="noopener noreferrer"
                        class="w-7 h-7 sm:w-8 sm:h-8 bg-gradient-to-br from-pink-400 via-pink-500 to-red-500 rounded-full flex items-center justify-center shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300"
                        title="Instagram">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162z"/>
                        </svg>
                    </a>

                    <!-- YouTube -->
                    <a href="{{ env('SOCIAL_YOUTUBE', 'https://youtube.com') }}" target="_blank" rel="noopener noreferrer"
                        class="w-7 h-7 sm:w-8 sm:h-8 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300"
                        title="YouTube">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21.543 6.498C22 8.28 22 12 22 12s0 3.72-.457 5.502c-.254.985-.997 1.76-1.938 2.022C17.896 20 12 20 12 20s-5.893 0-6.605-.476c-.945-.266-1.687-1.04-1.938-2.022C3 15.72 3 12 3 12s0-3.72.457-5.502c.254-.985.997-1.76 1.938-2.022C6.107 4 12 4 12 4s5.896 0 6.605.476c.945.266 1.687 1.04 1.938 2.022zM10 15l6-3-6-3v6z"/>
                        </svg>
                    </a>
                    
                    <!-- TikTok -->
                    <a href="{{ env('SOCIAL_TIKTOK', 'https://tiktok.com') }}" target="_blank" rel="noopener noreferrer"
                        class="w-7 h-7 sm:w-8 sm:h-8 bg-gradient-to-br from-black to-gray-800 rounded-full flex items-center justify-center shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300"
                        title="TikTok">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>