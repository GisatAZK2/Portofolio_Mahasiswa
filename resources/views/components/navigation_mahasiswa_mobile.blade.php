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
                <span data-translate="dashboard_nonuser" data-translate-page="mobile_nav">Home</span>
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
                <span data-translate="my_dashboard" data-translate-page="mobile_nav">My Page</span>
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
                <span data-translate="postingan" data-translate-page="mobile_nav">Post</span>
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
                <span data-translate="profil" data-translate-page="mobile_nav">Profil</span>
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
            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider" data-translate="tambah_baru" data-translate-page="mobile_nav">Tambah Baru</p>
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
                    <p class="text-sm font-semibold text-gray-800 dark:text-white" data-translate="tambah_project_baru" data-translate-page="mobile_nav">Tambah Project Baru</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400" data-translate="upload_project_kamu" data-translate-page="mobile_nav">Upload project kamu</p>
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
                    <p class="text-sm font-semibold text-gray-800 dark:text-white" data-translate="tambah_sertifikat_mobile" data-translate-page="mobile_nav">Tambah Sertifikat</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400" data-translate="upload_sertifikatmu" data-translate-page="mobile_nav">Upload sertifikatmu</p>
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
                    <p class="text-sm font-semibold text-gray-800 dark:text-white" data-translate="tambah_postingan_mobile" data-translate-page="mobile_nav">Tambah Postingan</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400" data-translate="bagikan_sesuatu" data-translate-page="mobile_nav">Bagikan sesuatu</p>
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
                <span class="text-sm text-gray-700 dark:text-gray-200" data-translate="lihat_profil" data-translate-page="sidebar">Lihat Profil</span>
            </a>

            <a href="{{ route('passkeys.index') }}" onclick="closeMobileProfile()"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                <span class="text-sm text-gray-700 dark:text-gray-200" data-translate="verifikasi_2_langkah" data-translate-page="sidebar">Verifikasi 2 Langkah</span>
            </a>

            <div class="border-t border-gray-100 dark:border-gray-800 my-2"></div>

            {{-- Dark Mode Toggle --}}
            <button type="button" onclick="toggleMobileDarkMode()"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <span class="text-sm text-gray-700 dark:text-gray-200 flex-1 text-left" data-translate="mode" data-translate-page="sidebar">Dark Mode</span>
                <div id="mobile-dark-toggle" class="relative inline-block w-10 h-5 rounded-full bg-gray-200 dark:bg-blue-600 transition-colors duration-300">
                    <span class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-300 dark:translate-x-5"></span>
                </div>
            </button>

            {{-- Language --}}
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                </svg>
                <span class="text-sm text-gray-700 dark:text-gray-200 flex-1" data-translate="bahasa" data-translate-page="sidebar">Bahasa</span>
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
                    <span class="text-sm text-red-600 dark:text-red-400 font-medium" data-translate="logout" data-translate-page="sidebar">Logout</span>
                </button>
            </form>
        </div>
        <div class="h-16"></div>
    </div>

    @endif
@endauth

{{-- ===================== GUEST BOTTOM NAV (dengan menu dropdown) ===================== --}}
@guest
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 shadow-lg">
    <div class="flex items-center justify-around px-2 py-1 h-16">
        <a href="{{ route('dashboard', ['locale' => app()->getLocale()]) }}"
           class="flex flex-col items-center justify-center gap-0.5 flex-1 py-1 rounded-xl transition {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10.5L12 3l9 7.5M5 10v9a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1v-9"/>
            </svg>
            <span class="text-[10px] font-medium" data-translate="dashboard_nonuser" data-translate-page="mobile_nav">Home</span>
        </a>

        <a href="{{ route('project.project_user', ['locale' => app()->getLocale()]) }}"
           class="flex flex-col items-center justify-center gap-0.5 flex-1 py-1 rounded-xl transition {{ request()->routeIs('project.project_user') ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-[10px] font-medium" data-translate="project_mahasiswa" data-translate-page="sidebar">Project</span>
        </a>

        {{-- Tombol menu untuk guest (Login, Register, Dark Mode, Bahasa) --}}
        <button type="button" id="guest-menu-btn"
                class="flex flex-col items-center justify-center gap-0.5 flex-1 py-1 rounded-xl transition text-gray-500 hover:text-blue-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-[10px] font-medium" data-translate="akun" data-translate-page="mobile_nav">Akun</span>
        </button>
    </div>
</nav>

{{-- ===================== GUEST BOTTOM SHEET ===================== --}}
<div id="guest-sheet-overlay" onclick="closeGuestSheet()"
     class="lg:hidden fixed inset-0 bg-black/30 backdrop-blur-sm z-40 hidden transition-all duration-300 opacity-0">
</div>

<div id="guest-sheet"
     class="lg:hidden bg-white dark:bg-gray-900 fixed bottom-0 left-0 right-0 z-50 bottom-sheet shadow-2xl transform translate-y-full transition-transform duration-300 ease-out pb-safe">

    <div class="flex justify-center pt-3 pb-1">
        <div class="w-12 h-1 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
    </div>

    <div class="px-6 pb-2 pt-1">
        <p class="text-xs font-semibold text-black dark:text-white uppercase tracking-wider" data-translate="menu_tamu" data-translate-page="mobile_nav">Menu Tamu</p>
    </div>

    <div class="px-4 pb-4 space-y-2">
        {{-- Tombol Login --}}
        <a href="{{ route('login', ['locale' => app()->getLocale()]) }}"
           class="flex items-center gap-4 px-4 py-3 rounded-xl bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition active:scale-[0.98]">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-gray-800 dark:text-white" data-translate="login" data-translate-page="sidebar">Masuk</p>
                <p class="text-xs text-gray-500 dark:text-gray-400" data-translate="login_to_account" data-translate-page="profile">Login ke akun Anda</p>
            </div>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        {{-- Tombol Register (jika tersedia) --}}
        @if(Route::has('register'))
        <a href="{{ route('register', ['locale' => app()->getLocale()]) }}"
           class="flex items-center gap-4 px-4 py-3 rounded-xl bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 transition active:scale-[0.98]">
            <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-gray-800 dark:text-white" data-translate="register" data-translate-page="sidebar">Daftar</p>
                <p class="text-xs text-gray-500 dark:text-gray-400" data-translate="create_account" data-translate-page="profile">Buat akun baru</p>
            </div>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif

        <div class="border-t border-gray-100 dark:border-gray-800 my-2"></div>

        {{-- Dark Mode Toggle --}}
        <button type="button" onclick="toggleGuestDarkMode()"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <span class="text-sm text-gray-700 dark:text-gray-200 flex-1 text-left" data-translate="mode" data-translate-page="sidebar">Dark Mode</span>
            <div id="guest-dark-toggle" class="relative inline-block w-10 h-5 rounded-full bg-gray-200 dark:bg-blue-600 transition-colors duration-300">
                <span class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-300 dark:translate-x-5"></span>
            </div>
        </button>

        {{-- Language Selector --}}
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl">
            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
            </svg>
            <span class="text-sm text-gray-700 dark:text-gray-200 flex-1" data-translate="bahasa" data-translate-page="sidebar">Bahasa</span>
            <select onchange="changeGuestLanguage(this.value)"
                    class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-2 py-1 rounded-lg border-0 focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option value="id" {{ app()->getLocale() === 'id' ? 'selected' : '' }}>🇮🇩 Indonesia</option>
                <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
            </select>
        </div>
    </div>
    <div class="h-16"></div>
</div>
@endguest