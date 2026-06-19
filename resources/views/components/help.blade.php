@extends('Layout.Layout')

@section('title', 'Help Center')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-2" data-translate="help_center" data-translate-page="help">Help Center</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm" data-translate="help_center_desc" data-translate-page="help">Pusat bantuan dan informasi</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- FAQ Section -->
            <div class="md:col-span-2 space-y-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span data-translate="faq_title" data-translate-page="help">Frequently Asked Questions</span>
                    </h2>
                    
                    <!-- FAQ Items with Animation -->
                    <div class="divide-y divide-gray-100 dark:divide-gray-700" id="faqContainer">
                        <div class="faq-item py-3 transform transition-all duration-500 ease-in-out">
                            <h3 class="font-medium text-gray-800 dark:text-white text-sm cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex justify-between items-center">
                                <span data-translate="faq_apa_website" data-translate-page="help">Apa website ini?</span>
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </h3>
                            <div class="faq-answer mt-1 overflow-hidden transition-all duration-500 ease-in-out" style="max-height: 0; opacity: 0;">
                                <p class="text-sm text-gray-600 dark:text-gray-400" data-translate="faq_apa_website_answer" data-translate-page="help">Website ini digunakan untuk para mahasiswa untuk menampilkan portofolio mereka.</p>
                            </div>
                        </div>
                        <div class="faq-item py-3 transform transition-all duration-500 ease-in-out">
                            <h3 class="font-medium text-gray-800 dark:text-white text-sm cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex justify-between items-center">
                                <span data-translate="faq_cara_daftar" data-translate-page="help">Cara mendaftar?</span>
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </h3>
                            <div class="faq-answer mt-1 overflow-hidden transition-all duration-500 ease-in-out" style="max-height: 0; opacity: 0;">
                                <p class="text-sm text-gray-600 dark:text-gray-400" data-translate="faq_cara_daftar_answer" data-translate-page="help">Klik tombol Daftar, isi formulir dengan data yang benar, lalu verifikasi email.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item py-3 transform transition-all duration-500 ease-in-out">
                            <h3 class="font-medium text-gray-800 dark:text-white text-sm cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex justify-between items-center">
                                <span data-translate="faq_lupa_password" data-translate-page="help">Lupa password?</span>
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </h3>
                            <div class="faq-answer mt-1 overflow-hidden transition-all duration-500 ease-in-out" style="max-height: 0; opacity: 0;">
                                <p class="text-sm text-gray-600 dark:text-gray-400" data-translate="faq_lupa_password_answer" data-translate-page="help">Klik "Lupa Password" pada halaman login, ikuti instruksi reset password.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item py-3 transform transition-all duration-500 ease-in-out">
                            <h3 class="font-medium text-gray-800 dark:text-white text-sm cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex justify-between items-center">
                                <span data-translate="faq_cara_ubah_profil" data-translate-page="help">Cara mengubah profil?</span>
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </h3>
                            <div class="faq-answer mt-1 overflow-hidden transition-all duration-500 ease-in-out" style="max-height: 0; opacity: 0;">
                                <p class="text-sm text-gray-600 dark:text-gray-400" data-translate="faq_cara_ubah_profil_answer" data-translate-page="help">Masuk ke akun, buka halaman Profil, klik Edit Profil.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item py-3 transform transition-all duration-500 ease-in-out">
                            <h3 class="font-medium text-gray-800 dark:text-white text-sm cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex justify-between items-center">
                                <span data-translate="faq_aplikasi_tidak_install" data-translate-page="help">Aplikasi tidak bisa diinstall?</span>
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </h3>
                            <div class="faq-answer mt-1 overflow-hidden transition-all duration-500 ease-in-out" style="max-height: 0; opacity: 0;">
                                <p class="text-sm text-gray-600 dark:text-gray-400" data-translate="faq_aplikasi_tidak_install_answer" data-translate-page="help">Pastikan menggunakan Chrome/Safari terbaru, dan koneksi internet stabil.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item py-3 transform transition-all duration-500 ease-in-out">
                            <h3 class="font-medium text-gray-800 dark:text-white text-sm cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex justify-between items-center">
                                <span data-translate="faq_hubungi_admin" data-translate-page="help">Bagaimana cara menghubungi admin?</span>
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </h3>
                            <div class="faq-answer mt-1 overflow-hidden transition-all duration-500 ease-in-out" style="max-height: 0; opacity: 0;">
                                <p class="text-sm text-gray-600 dark:text-gray-400" data-translate="faq_hubungi_admin_answer" data-translate-page="help">Anda dapat menghubungi kami melalui email info@polmind.ac.id atau WhatsApp +62 821-1329-6897.</p>
                            </div>
                        </div>
                        <div class="faq-item py-3 transform transition-all duration-500 ease-in-out">
                            <h3 class="font-medium text-gray-800 dark:text-white text-sm cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex justify-between items-center">
                                <span data-translate="faq_hubungi_mahasiswa" data-translate-page="help">Apakah bisa menghubungi para mahasiswa?</span>
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </h3>
                            <div class="faq-answer mt-1 overflow-hidden transition-all duration-500 ease-in-out" style="max-height: 0; opacity: 0;">
                                <p class="text-sm text-gray-600 dark:text-gray-400" data-translate="faq_hubungi_mahasiswa_answer" data-translate-page="help">Anda dapat menghubungi mahasiswa melalui email mereka atau dengan menghubungi admin untuk informasi mereka.</p>
                            </div>
                        </div>
                        <div class="faq-item py-3 transform transition-all duration-500 ease-in-out">
                            <h3 class="font-medium text-gray-800 dark:text-white text-sm cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex justify-between items-center">
                                <span data-translate="faq_portofolio" data-translate-page="help">Apa itu portofolio dan kenapa penting di website ini?</span>
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </h3>
                            <div class="faq-answer mt-1 overflow-hidden transition-all duration-500 ease-in-out" style="max-height: 0; opacity: 0;">
                                <p class="text-sm text-gray-600 dark:text-gray-400" data-translate="faq_portofolio_answer" data-translate-page="help">Portofolio adalah kumpulan hasil karya atau proyek yang pernah Anda kerjakan. Portofolio membantu pihak luar melihat kemampuan dan keterampilan Anda.</p>
                            </div>
                        </div>
                        <div class="faq-item py-3 transform transition-all duration-500 ease-in-out">
                            <h3 class="font-medium text-gray-800 dark:text-white text-sm cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex justify-between items-center">
                                <span data-translate="faq_tambah_portofolio" data-translate-page="help">Bagaimana cara menambahkan portofolio?</span>
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </h3>
                            <div class="faq-answer mt-1 overflow-hidden transition-all duration-500 ease-in-out" style="max-height: 0; opacity: 0;">
                                <p class="text-sm text-gray-600 dark:text-gray-400" data-translate="faq_tambah_portofolio_answer" data-translate-page="help">Masuk ke dashboard, pilih menu portofolio, lalu klik tombol tambah proyek atau tambah sertifikat dan isi informasi yang diperlukan seperti judul, deskripsi, dan file pendukung.</p>
                            </div>
                        </div>
                        <div class="faq-item py-3 transform transition-all duration-500 ease-in-out">
                            <h3 class="font-medium text-gray-800 dark:text-white text-sm cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex justify-between items-center">
                                <span data-translate="faq_tefa" data-translate-page="help">Apa itu sistem TeFa?</span>
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </h3>
                            <div class="faq-answer mt-1 overflow-hidden transition-all duration-500 ease-in-out" style="max-height: 0; opacity: 0;">
                                <p class="text-sm text-gray-600 dark:text-gray-400" data-translate="faq_tefa_answer" data-translate-page="help">Teaching Factory (TeFa) adalah sistem pembelajaran berbasis proyek nyata, di mana mahasiswa mengerjakan proyek dari dunia industri sebagai bagian dari proses akademik.</p>
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
                        <span data-translate="hubungi_kami" data-translate-page="help">Hubungi Kami</span>
                    </h2>
                    <div class="space-y-3 text-sm">
                        <a href="mailto:{{ env('CONTACT_EMAIL', 'info@polmind.ac.id') }}?subject=Bantuan%20POLMIND" 
                           class="flex items-center gap-3 hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-lg transition-colors group">
                            <svg class="w-4 h-4 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400 group-hover:text-blue-600 transition-colors">info@polmind.ac.id</span>
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
                            <span class="text-gray-600 dark:text-gray-400 group-hover:text-red-600 transition-colors">Kawasan Industri MM2100</span>
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
                    <h3 class="font-semibold text-gray-800 dark:text-white" data-translate="butuh_bantuan" data-translate-page="help">Butuh Bantuan?</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1" data-translate="tim_support" data-translate-page="help">Tim support siap membantu Anda</p>
                    <div class="mt-3 space-y-2">
                        <a href="https://wa.me/{{ env('CONTACT_PHONE', '6282113296897') }}?text=Halo%20saya%20butuh%20bantuan%20mengenai%20POLMIND" 
                           target="_blank"
                           class="block w-full px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition">
                           <span data-translate="whatsapp" data-translate-page="help">WhatsApp</span>
                        </a>
                        <button onclick="window.location.href='mailto:{{ env('CONTACT_EMAIL', 'info@polmind.ac.id') }}?subject=Bantuan%20POLMIND'" 
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                            <span data-translate="email_support" data-translate-page="help">Email Support</span>
                        </button>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <h3 class="font-semibold text-gray-800 dark:text-white mb-3" data-translate="informasi_lain" data-translate-page="help">Informasi Lain</h3>
                    <div class="space-y-2">
                        <a href="https://www.polmind.ac.id/beranda" target="_blank" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span data-translate="tentang_kami" data-translate-page="help">Tentang Kami</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection