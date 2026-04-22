@extends('Layout.Layout')

@section('title', autoTranslate('Help Center'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-2">
                {{ autoTranslate('Help Center') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm">
                {{ autoTranslate('Pusat bantuan dan informasi') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- FAQ Section -->
            <div class="md:col-span-2 space-y-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ autoTranslate('Frequently Asked Questions') }}
                    </h2>
                    
                    <!-- FAQ Items with Animation -->
                    <div class="divide-y divide-gray-100 dark:divide-gray-700" id="faqContainer">
                        <div class="faq-item py-3 transform transition-all duration-500 ease-in-out">
                            <h3 class="font-medium text-gray-800 dark:text-white text-sm cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex justify-between items-center">
                                {{ autoTranslate('Cara mendaftar?') }}
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </h3>
                            <div class="faq-answer mt-1 overflow-hidden transition-all duration-500 ease-in-out" style="max-height: 0; opacity: 0;">
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ autoTranslate('Klik tombol Daftar, isi formulir dengan data yang benar, lalu verifikasi email.') }}</p>
                            </div>
                        </div>
                        
                        <div class="faq-item py-3 transform transition-all duration-500 ease-in-out">
                            <h3 class="font-medium text-gray-800 dark:text-white text-sm cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex justify-between items-center">
                                {{ autoTranslate('Lupa password?') }}
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </h3>
                            <div class="faq-answer mt-1 overflow-hidden transition-all duration-500 ease-in-out" style="max-height: 0; opacity: 0;">
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ autoTranslate('Klik "Lupa Password" pada halaman login, ikuti instruksi reset password.') }}</p>
                            </div>
                        </div>
                        
                        <div class="faq-item py-3 transform transition-all duration-500 ease-in-out">
                            <h3 class="font-medium text-gray-800 dark:text-white text-sm cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex justify-between items-center">
                                {{ autoTranslate('Cara mengubah profil?') }}
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </h3>
                            <div class="faq-answer mt-1 overflow-hidden transition-all duration-500 ease-in-out" style="max-height: 0; opacity: 0;">
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ autoTranslate('Masuk ke akun, buka halaman Profil, klik Edit Profil.') }}</p>
                            </div>
                        </div>
                        
                        <div class="faq-item py-3 transform transition-all duration-500 ease-in-out">
                            <h3 class="font-medium text-gray-800 dark:text-white text-sm cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex justify-between items-center">
                                {{ autoTranslate('Aplikasi tidak bisa diinstall?') }}
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </h3>
                            <div class="faq-answer mt-1 overflow-hidden transition-all duration-500 ease-in-out" style="max-height: 0; opacity: 0;">
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ autoTranslate('Pastikan menggunakan Chrome/Safari terbaru, dan koneksi internet stabil.') }}</p>
                            </div>
                        </div>
                        
                        <div class="faq-item py-3 transform transition-all duration-500 ease-in-out">
                            <h3 class="font-medium text-gray-800 dark:text-white text-sm cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex justify-between items-center">
                                {{ autoTranslate('Bagaimana cara menghubungi admin?') }}
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </h3>
                            <div class="faq-answer mt-1 overflow-hidden transition-all duration-500 ease-in-out" style="max-height: 0; opacity: 0;">
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ autoTranslate('Anda dapat menghubungi kami melalui email info@polmind.ac.id atau WhatsApp +62 821-1329-6897.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ autoTranslate('Hubungi Kami') }}
                    </h2>
                    <div class="space-y-3 text-sm">
                        <a href="mailto:{{ env('CONTACT_EMAIL', 'info@polmind.ac.id') }}?subject=Bantuan%20POLMIND" 
                           class="flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-lg transition-colors group">
                            <svg class="w-4 h-4 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400 group-hover:text-blue-600 transition-colors">{{ env('CONTACT_EMAIL', 'info@polmind.ac.id') }}</span>
                        </a>
                        <a href="https://wa.me/{{ env('CONTACT_PHONE', '6282113296897') }}?text=Halo%20saya%20butuh%20bantuan%20mengenai%20POLMIND" 
                           target="_blank"
                           class="flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-lg transition-colors group">
                            <svg class="w-4 h-4 text-gray-500 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400 group-hover:text-green-600 transition-colors">+62 821-1329-6897</span>
                        </a>
                        <a href="{{ env('CONTACT_MAPS', 'https://maps.app.goo.gl/UgUBmN7joH9fA2Jw5') }}" 
                           target="_blank"
                           class="flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-lg transition-colors group">
                            <svg class="w-4 h-4 text-gray-500 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400 group-hover:text-red-600 transition-colors">{{ autoTranslate('Kawasan Industri MM2100') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl shadow-md p-5 text-center">
                    <svg class="w-10 h-10 mx-auto text-blue-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636L16.95 7.05m2.828 2.828l-1.414 1.414M12 4.5v2m4.95 9.95l-1.414-1.414m2.828-2.828l-1.414-1.414M12 19.5v-2M5.636 18.364l1.414-1.414M2.828 12.5l1.414-1.414M5.636 5.636l1.414 1.414"/>
                    </svg>
                    <h3 class="font-semibold text-gray-800 dark:text-white">{{ autoTranslate('Butuh Bantuan?') }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ autoTranslate('Tim support siap membantu Anda') }}</p>
                    <div class="mt-3 space-y-2">
                        <a href="https://wa.me/{{ env('CONTACT_PHONE', '6282113296897') }}?text=Halo%20saya%20butuh%20bantuan%20mengenai%20POLMIND" 
                           target="_blank"
                           class="block w-full px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition">
                        {{ autoTranslate('WhatsApp') }}
                        </a>
                        <button onclick="window.location.href='mailto:{{ env('CONTACT_EMAIL', 'info@polmind.ac.id') }}?subject=Bantuan%20POLMIND'" 
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                        {{ autoTranslate('Email Support') }}
                        </button>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <h3 class="font-semibold text-gray-800 dark:text-white mb-3">{{ autoTranslate('Informasi Lain') }}</h3>
                    <div class="space-y-2">
                        <a href="https://www.polmind.ac.id/beranda" target="_blank" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ autoTranslate('Tentang Kami') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all FAQ items
        const faqItems = document.querySelectorAll('.faq-item');
        
        // Auto-slide effect: open one by one with delay
        let currentIndex = 0;
        
        function openNextFAQ() {
            if (currentIndex < faqItems.length) {
                const item = faqItems[currentIndex];
                const answer = item.querySelector('.faq-answer');
                const icon = item.querySelector('.faq-icon');
                
                // Close all other FAQs
                faqItems.forEach((otherItem, idx) => {
                    if (idx !== currentIndex) {
                        const otherAnswer = otherItem.querySelector('.faq-answer');
                        const otherIcon = otherItem.querySelector('.faq-icon');
                        otherAnswer.style.maxHeight = '0';
                        otherAnswer.style.opacity = '0';
                        otherIcon.style.transform = 'rotate(0deg)';
                    }
                });
                
                // Open current FAQ with animation
                answer.style.maxHeight = answer.scrollHeight + 'px';
                answer.style.opacity = '1';
                icon.style.transform = 'rotate(180deg)';
                
                // Move to next after delay
                currentIndex++;
                setTimeout(openNextFAQ, 3000);
            } else {
                // Reset to first after all opened
                setTimeout(() => {
                    currentIndex = 0;
                    openNextFAQ();
                }, 2000);
            }
        }
        
        // Start auto-slide
        setTimeout(openNextFAQ, 500);
        
        // Add click functionality for manual toggle
        faqItems.forEach((item, index) => {
            const question = item.querySelector('h3');
            const answer = item.querySelector('.faq-answer');
            const icon = item.querySelector('.faq-icon');
            
            question.addEventListener('click', function(e) {
                e.stopPropagation();
                
                // Toggle current FAQ
                const isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';
                
                // Close all FAQs
                faqItems.forEach((otherItem) => {
                    const otherAnswer = otherItem.querySelector('.faq-answer');
                    const otherIcon = otherItem.querySelector('.faq-icon');
                    otherAnswer.style.maxHeight = '0';
                    otherAnswer.style.opacity = '0';
                    otherIcon.style.transform = 'rotate(0deg)';
                });
                
                // Open clicked FAQ if it was closed
                if (!isOpen) {
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                    answer.style.opacity = '1';
                    icon.style.transform = 'rotate(180deg)';
                    
                    // Update current index for auto-slide
                    currentIndex = index + 1;
                }
            });
        });
    });
</script>

<style>
    /* Smooth transitions for FAQ */
    .faq-answer {
        transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease-in-out;
    }
    
    .faq-icon {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .faq-item {
        animation: slideUpFade 0.5s ease-out backwards;
    }
    
    .faq-item:nth-child(1) { animation-delay: 0.1s; }
    .faq-item:nth-child(2) { animation-delay: 0.2s; }
    .faq-item:nth-child(3) { animation-delay: 0.3s; }
    .faq-item:nth-child(4) { animation-delay: 0.4s; }
    .faq-item:nth-child(5) { animation-delay: 0.5s; }
    
    @keyframes slideUpFade {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection