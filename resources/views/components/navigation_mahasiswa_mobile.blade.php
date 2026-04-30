{{-- 
    navigation_mahasiswa_mobile.blade.php
    Bottom navigation bar untuk role mahasiswa di mobile (lg:hidden)
    Untuk admin/dosen tetap menggunakan sidebar.blade.php biasa
--}}

@auth
    @if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'dosen')

    {{-- ===================== BOTTOM NAVIGATION BAR ===================== --}}
    @php
        $activeTab = '';
        if (request()->routeIs('dashboard')) $activeTab = 'home';
        elseif (request()->routeIs('dashboard.me')) $activeTab = 'mydash';
        elseif (request()->routeIs('postingan.*')) $activeTab = 'post';
        elseif (request()->routeIs('profile')) $activeTab = 'profile';
    @endphp

    <style>
        /* ── Bottom Nav Wrapper ── */
        #mobile-bottom-nav {
            bottom: 0;
            left: 0;
            right: 0;
            height: 70px;
            padding-bottom: env(safe-area-inset-bottom, 0px);
            background: transparent;
            pointer-events: none;
        }
        .bnav-bar {
            background: #ffffff;
            border-radius: 32px;
            height: 62px;
            margin: 0 16px;
            display: flex;
            align-items: center;
            justify-content: space-around;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.02);
            pointer-events: auto;
            transition: background 0.2s, box-shadow 0.2s;
        }
        .dark .bnav-bar {
            background: #1e293b;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.05);
        }

        /* ── Item umum ── */
        .bnav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            height: 62px;
            gap: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            color: #94a3b8;
            background: transparent;
            border: none;
            padding: 0;
            border-radius: 30px;
            -webkit-tap-highlight-color: transparent;
        }
        .bnav-item span {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.3px;
            transition: color 0.2s;
        }
        .bnav-item.active {
            color: #3b82f6;
        }
        .dark .bnav-item.active {
            color: #60a5fa;
        }

        /* ── Icon wrapper (inactive) ── */
        .bnav-icon-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            transition: all 0.2s;
        }
        .bnav-icon-wrap svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        /* ── Active item: icon elevated with background ── */
        .bnav-item.active .bnav-icon-wrap {
            background: #eff6ff;
            border-radius: 24px;
            width: 48px;
            height: 48px;
            margin-top: -16px;
            box-shadow: 0 4px 10px rgba(59,130,246,0.2);
        }
        .dark .bnav-item.active .bnav-icon-wrap {
            background: #1e3a8a;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .bnav-item.active .bnav-icon-wrap svg {
            width: 22px;
            height: 22px;
        }

        /* Avatar khusus di profile item */
        .bnav-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #e2e8f0;
        }
        .bnav-item.active .bnav-avatar {
            width: 34px;
            height: 34px;
            border: 2px solid white;
        }
        .dark .bnav-avatar {
            border-color: #334155;
        }

        /* ── FAB button style ── */
        .bnav-fab {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 12px rgba(59,130,246,0.4);
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: -8px;
        }
        .bnav-fab:active { transform: scale(0.94); }
        .bnav-fab svg {
            width: 22px;
            height: 22px;
            color: white;
            transition: transform 0.2s;
        }

        /* Padding bawah konten */
        @media (max-width: 1023px) {
            main, .content-wrapper {
                padding-bottom: 5.5rem !important;
            }
        }
        .safe-area-pb { padding-bottom: env(safe-area-inset-bottom, 0px); }
        .pb-safe { padding-bottom: calc(env(safe-area-inset-bottom, 0px) + 1rem); }

        /* Bottom sheet styling */
        .bottom-sheet {
            border-radius: 28px 28px 0 0;
            background: #ffffff;
        }
        .dark .bottom-sheet {
            background: #1e293b;
        }
    </style>

    <nav id="mobile-bottom-nav" class="lg:hidden fixed z-50">
        <div class="bnav-bar">
            {{-- Home --}}
            <a href="{{ route('dashboard', ['locale' => app()->getLocale()]) }}"
               class="bnav-item {{ $activeTab === 'home' ? 'active' : '' }}">
                <div class="bnav-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 10.5L12 3l9 7.5M5 10v9a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1v-9" />
                    </svg>
                </div>
                <span data-translate="dashboard_nonuser">Home</span>
            </a>

            {{-- My Dashboard --}}
            <a href="{{ route('dashboard.me', ['locale' => app()->getLocale()]) }}"
               class="bnav-item {{ $activeTab === 'mydash' ? 'active' : '' }}">
                <div class="bnav-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 0l7-4m-7 4L9 5m3 0l7 4" />
                    </svg>
                </div>
                <span data-translate="my_dashboard">My Page</span>
            </a>

            {{-- FAB + --}}
            <button type="button" id="mobile-fab-btn" onclick="toggleMobileFab()"
                    style="background:transparent;border:none;padding:0;margin-bottom:0">
                <div class="bnav-fab">
                    <svg id="mobile-fab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
            </button>

            {{-- Postingan --}}
            <a href="{{ route('postingan.index', ['locale' => app()->getLocale()]) }}"
               class="bnav-item {{ $activeTab === 'post' ? 'active' : '' }}">
                <div class="bnav-icon-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
                        <path d="M8 2v4"></path><path d="M16 2v4"></path>
                        <path d="M3 10h18"></path>
                        <circle cx="12" cy="14" r="2"></circle>
                    </svg>
                </div>
                <span data-translate="postingan">Post</span>
            </a>

            {{-- Profile --}}
            <button type="button" onclick="toggleMobileProfile()"
                    class="bnav-item {{ $activeTab === 'profile' ? 'active' : '' }}">
                <div class="bnav-icon-wrap">
                    @if(Auth::user()->photo_profile)
                        <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}"
                             alt="foto" class="bnav-avatar object-cover">
                    @else
                        <div class="bnav-avatar bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                            {{ substr(Auth::user()->nama_mahasiswa ?? Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                    @endif
                </div>
                <span>Profil</span>
            </button>
        </div>
    </nav>

    {{-- ===================== FAB BOTTOM SHEET ===================== --}}
    <div id="mobile-fab-overlay" onclick="closeMobileFab()"
         class="lg:hidden fixed inset-0 bg-black/30 backdrop-blur-sm z-40 hidden transition-all duration-300 opacity-0">
    </div>

    <div id="mobile-fab-sheet"
         class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bottom-sheet shadow-2xl transform translate-y-full transition-transform duration-300 ease-out pb-safe">

        <div class="flex justify-center pt-3 pb-1">
            <div class="w-12 h-1 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
        </div>

        <div class="px-6 pb-2 pt-1">
            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Tambah Baru</p>
        </div>

        <div class="px-4 pb-4 space-y-2">
            <a href="{{ route('project.create', ['locale' => app()->getLocale()]) }}" onclick="closeMobileFab()"
               class="flex items-center gap-4 px-4 py-3 rounded-xl bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition active:scale-[0.98]">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800 dark:text-white">Tambah Project Baru</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Upload project kamu</p>
                </div>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="{{ route('sertifikat.create', ['locale' => app()->getLocale()]) }}" onclick="closeMobileFab()"
               class="flex items-center gap-4 px-4 py-3 rounded-xl bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/30 transition active:scale-[0.98]">
                <div class="w-10 h-10 bg-purple-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
                        <path d="M8 2v4"></path><path d="M16 2v4"></path><path d="M3 10h18"></path><circle cx="12" cy="14" r="2"></circle>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800 dark:text-white">Tambah Sertifikat</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Upload sertifikatmu</p>
                </div>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="{{ route('postingan.create', ['locale' => app()->getLocale()]) }}" onclick="closeMobileFab()"
               class="flex items-center gap-4 px-4 py-3 rounded-xl bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 transition active:scale-[0.98]">
                <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800 dark:text-white">Tambah Postingan</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Bagikan sesuatu</p>
                </div>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="h-16"></div>
    </div>

    {{-- ===================== PROFILE BOTTOM SHEET ===================== --}}
    <div id="mobile-profile-overlay" onclick="closeMobileProfile()"
         class="lg:hidden fixed inset-0 bg-black/30 backdrop-blur-sm z-40 hidden transition-all duration-300 opacity-0">
    </div>

    <div id="mobile-profile-sheet"
         class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bottom-sheet shadow-2xl transform translate-y-full transition-transform duration-300 ease-out">

        <div class="flex justify-center pt-3 pb-1">
            <div class="w-12 h-1 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
        </div>

        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-blue-100 dark:border-blue-800 flex-shrink-0">
                @if(Auth::user()->photo_profile)
                    <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                        {{ substr(Auth::user()->nama_mahasiswa ?? Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">{{ Auth::user()->nama_mahasiswa ?? Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('profile') }}" onclick="closeMobileProfile()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-sm text-gray-700 dark:text-gray-200" data-translate="lihat_profil">Lihat Profil</span>
            </a>

            <a href="{{ route('passkeys.index') }}" onclick="closeMobileProfile()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                <span class="text-sm text-gray-700 dark:text-gray-200">{{ autoTranslate('Verifikasi 2langkah') }}</span>
            </a>

            <div class="border-t border-gray-100 dark:border-gray-800 my-2"></div>

            {{-- Dark Mode Toggle --}}
            <button type="button" onclick="toggleMobileDarkMode()"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <span class="text-sm text-gray-700 dark:text-gray-200 flex-1 text-left" data-translate="mode">Dark Mode</span>
                <div id="mobile-dark-toggle" class="relative inline-block w-10 h-5 rounded-full bg-gray-200 dark:bg-blue-600 transition-colors duration-300">
                    <span class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-300 dark:translate-x-5"></span>
                </div>
            </button>

            {{-- Language --}}
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                </svg>
                <span class="text-sm text-gray-700 dark:text-gray-200 flex-1" data-translate="bahasa">Bahasa</span>
                <select onchange="changeLanguageMobile(this.value)"
                        class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-2 py-1 rounded-lg border-0 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="id" {{ app()->getLocale() === 'id' ? 'selected' : '' }}>🇮🇩 Indonesia</option>
                    <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                </select>
            </div>

            <div class="border-t border-gray-100 dark:border-gray-800 my-2"></div>

            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="text-sm text-red-600 dark:text-red-400 font-medium" data-translate="logout">Logout</span>
                </button>
            </form>
        </div>
        <div class="h-16"></div>
    </div>

    <script>
        // ===================== FAB BOTTOM SHEET =====================
        function toggleMobileFab() {
            const sheet = document.getElementById('mobile-fab-sheet');
            const overlay = document.getElementById('mobile-fab-overlay');
            const icon = document.getElementById('mobile-fab-icon');

            const isOpen = !sheet.classList.contains('translate-y-full');

            if (isOpen) {
                closeMobileFab();
            } else {
                closeMobileProfile(); // tutup sheet lain
                overlay.classList.remove('hidden');
                overlay.offsetHeight; // force reflow
                overlay.classList.remove('opacity-0');
                sheet.classList.remove('translate-y-full');
                if (icon) icon.style.transform = 'rotate(45deg)';
            }
        }

        function closeMobileFab() {
            const sheet = document.getElementById('mobile-fab-sheet');
            const overlay = document.getElementById('mobile-fab-overlay');
            const icon = document.getElementById('mobile-fab-icon');

            sheet.classList.add('translate-y-full');
            overlay.classList.add('opacity-0');
            if (icon) icon.style.transform = 'rotate(0deg)';
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }

        // ===================== PROFILE BOTTOM SHEET =====================
        function toggleMobileProfile() {
            const sheet = document.getElementById('mobile-profile-sheet');
            const overlay = document.getElementById('mobile-profile-overlay');

            const isOpen = !sheet.classList.contains('translate-y-full');

            if (isOpen) {
                closeMobileProfile();
            } else {
                closeMobileFab();
                overlay.classList.remove('hidden');
                overlay.offsetHeight;
                overlay.classList.remove('opacity-0');
                sheet.classList.remove('translate-y-full');
            }
        }

        function closeMobileProfile() {
            const sheet = document.getElementById('mobile-profile-sheet');
            const overlay = document.getElementById('mobile-profile-overlay');

            sheet.classList.add('translate-y-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }

        // ===================== DARK MODE TOGGLE (sync dengan toggle switch) =====================
        function syncDarkModeToggle() {
            const isDark = document.documentElement.classList.contains('dark');
            const toggleSpan = document.querySelector('#mobile-dark-toggle span');
            const toggleDiv = document.getElementById('mobile-dark-toggle');
            if (toggleSpan) {
                if (isDark) {
                    toggleSpan.classList.add('translate-x-5');
                } else {
                    toggleSpan.classList.remove('translate-x-5');
                }
            }
            if (toggleDiv) {
                if (isDark) {
                    toggleDiv.classList.add('dark:bg-blue-600');
                } else {
                    toggleDiv.classList.remove('dark:bg-blue-600');
                }
            }
        }

        function toggleMobileDarkMode() {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            syncDarkModeToggle();
        }

        // ===================== LANGUAGE CHANGE =====================
        function changeLanguageMobile(lang) {
            localStorage.setItem('lang', lang);
            window.location.href = `${window.location.origin}/${lang}`;
        }

        // ===================== SWIPE TO CLOSE =====================
        (function() {
            let startY = 0;

            ['mobile-fab-sheet', 'mobile-profile-sheet'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;

                el.addEventListener('touchstart', (e) => {
                    startY = e.touches[0].clientY;
                }, { passive: true });

                el.addEventListener('touchend', (e) => {
                    const deltaY = e.changedTouches[0].clientY - startY;
                    if (deltaY > 60) {
                        if (id === 'mobile-fab-sheet') closeMobileFab();
                        if (id === 'mobile-profile-sheet') closeMobileProfile();
                    }
                }, { passive: true });
            });
        })();

        // Inisialisasi dark mode toggle switch saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            syncDarkModeToggle();
            // Cek juga dari localStorage dan sinkronkan class html
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            }
            syncDarkModeToggle();
        });
    </script>

    @endif
@endauth

{{-- ===================== GUEST BOTTOM NAV (simple) ===================== --}}
@guest
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 shadow-lg">
    <div class="flex items-center justify-around px-2 py-1 h-16">
        <a href="{{ route('dashboard', ['locale' => app()->getLocale()]) }}"
           class="flex flex-col items-center justify-center gap-0.5 flex-1 py-1 rounded-xl transition {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10.5L12 3l9 7.5M5 10v9a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1v-9"/>
            </svg>
            <span class="text-[10px] font-medium">Home</span>
        </a>

        <a href="{{ route('project.project_user', ['locale' => app()->getLocale()]) }}"
           class="flex flex-col items-center justify-center gap-0.5 flex-1 py-1 rounded-xl transition {{ request()->routeIs('project.project_user') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-[10px] font-medium" data-translate="project_mahasiswa">Project</span>
        </a>

        <a href="{{ route('login', ['locale' => app()->getLocale()]) }}"
           class="flex flex-col items-center justify-center gap-0.5 flex-1 py-1 rounded-xl transition text-gray-500 hover:text-blue-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            <span class="text-[10px] font-medium" data-translate="login">Masuk</span>
        </a>
    </div>
</nav>
@endguest