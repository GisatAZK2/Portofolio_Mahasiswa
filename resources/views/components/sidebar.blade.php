@php
$sidebarMenus = [
    ['key' => 'dashboard_nonuser', 'url' => route('dashboard', ['locale' => app()->getLocale()])]
];
if (Auth::check()) {
    $user = Auth::user();
    if ($user->role !== 'admin' && $user->role !== 'dosen') {
        $sidebarMenus[] = ['key' => 'my_dashboard', 'url' => route('dashboard.me', ['locale' => app()->getLocale()])];
        $sidebarMenus[] = ['key' => 'project_mahasiswa_saya', 'url' => route('project.index', ['locale' => app()->getLocale()])];
        $sidebarMenus[] = ['key' => 'project_mahasiswa_saya_tambah', 'url' => route('project.create', ['locale' => app()->getLocale()])];
        $sidebarMenus[] = ['key' => 'sertifikat_mahasiswa_saya', 'url' => route('sertifikat.index', ['locale' => app()->getLocale()])];
        $sidebarMenus[] = ['key' => 'sertifikat_mahasiswa_saya_tambah', 'url' => route('sertifikat.create', ['locale' => app()->getLocale()])];
    }
    if ($user->role === 'admin') {
        $sidebarMenus[] = ['key' => 'admin_dashboard', 'url' => route('admin.index')];
        $sidebarMenus[] = ['key' => 'semua_user', 'url' => route('admin.users.index')];
        $sidebarMenus[] = ['key' => 'tambah_user', 'url' => route('admin.users.ViewCreate')];
        $sidebarMenus[] = ['key' => 'lihat_angkatan', 'url' => route('admin.angkatan.index')];
        $sidebarMenus[] = ['key' => 'tambah_angkatan', 'url' => route('admin.angkatan.create')];
        $sidebarMenus[] = ['key' => 'lihat_prodi', 'url' => route('admin.prodi.index')];
        $sidebarMenus[] = ['key' => 'tambah_prodi', 'url' => route('admin.prodi.create')];
        $sidebarMenus[] = ['key' => 'lihat_keahlian', 'url' => route('admin.keahlian.index')];
        $sidebarMenus[] = ['key' => 'tambah_keahlian', 'url' => route('admin.keahlian.create')];
        $sidebarMenus[] = ['key' => 'lihat_proyek', 'url' => route('admin.projects.index')];
        $sidebarMenus[] = ['key' => 'tambah_proyek', 'url' => route('admin.projects.create')];
        $sidebarMenus[] = ['key' => 'lihat_sertifikat', 'url' => route('admin.sertifikat.index')];
        $sidebarMenus[] = ['key' => 'tambah_sertifikat', 'url' => route('admin.sertifikat.create')];
        $sidebarMenus[] = ['key' => 'lihat_notifikasi', 'url' => route('admin.notifications.index')];
        $sidebarMenus[] = ['key' => 'tambah_notifikasi', 'url' => route('admin.notifications.create')];
    }
    if ($user->role === 'dosen') {
        $sidebarMenus[] = ['key' => 'dashboard_dosen', 'url' => route('dosen.dashboard')];
        $sidebarMenus[] = ['key' => 'see_mhs', 'url' => route('dosen.users.index')];
        $sidebarMenus[] = ['key' => 'add_mhs', 'url' => route('dosen.users.ViewCreate')];
        $sidebarMenus[] = ['key' => 'see_pjt', 'url' => route('dosen.projects.index')];
        $sidebarMenus[] = ['key' => 'add_pjt', 'url' => route('dosen.projects.create')];
        $sidebarMenus[] = ['key' => 'see_stk', 'url' => route('dosen.sertifikat.index')];
        $sidebarMenus[] = ['key' => 'add_stk', 'url' => route('dosen.sertifikat.create')];
    }
    $sidebarMenus[] = ['key' => 'postingan', 'url' => route('postingan.index')];
    $sidebarMenus[] = ['key' => 'postingan_tambah', 'url' => route('postingan.create')];
} else {
    $sidebarMenus[] = ['key' => 'project_mahasiswa', 'url' => route('project.project_user')];
}
@endphp
<div id="sidebar-config" class="hidden" data-menus="{{ json_encode($sidebarMenus) }}"></div>

<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 shadow-lg
           transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out
           lg:static lg:inset-auto lg:shadow-sm
           flex flex-col overflow-hidden">

    {{-- ======================== LOGO ======================== --}}
    <div class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3 shrink-0">
        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md overflow-hidden shrink-0">
            <img id="logo-zoom" class="cursor-pointer w-10 h-10 rounded-xl object-cover transition-all duration-300"
                src="{{ asset('assets/Logo.svg') }}" alt="Logo">
        </div>
        <div>
            <p class="text-gray-900 dark:text-white text-sm font-semibold leading-tight">PortofolioKu</p>
            <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">Politeknik Mitra Industri</p>
        </div>
    </div>

    {{-- ======================== SEARCH ======================== --}}
    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 shrink-0">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 01-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text" id="sidebarSearch" placeholder="{{ __('menu.search_placeholder') }}"
                data-translate-placeholder="search_placeholder"
                class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500
                       pl-9 pr-4 py-2 rounded-lg text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20
                       transition-all"
                autocomplete="off">
            <div id="searchResults"
                class="hidden absolute mt-2 w-full bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700
                       py-2 max-h-72 overflow-y-auto z-50">
            </div>
        </div>
    </div>

    {{-- ======================== NAV ======================== --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto overflow-x-hidden scrollbar-thin">

        {{-- ---------- SECTION: UTAMA ---------- --}}
        <p class="sb-section-label" data-translate="utama"></p>

        {{-- Home --}}
        <a href="{{ route('dashboard', ['locale' => app()->getLocale()]) }}"
            class="sb-item {{ request()->routeIs('dashboard') ? 'sb-active' : '' }}">
            <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5M5 10v9a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1v-9"/>
            </svg>
            <span data-translate="dashboard_nonuser">Dashboard</span>
        </a>

        {{-- My Dashboard (mahasiswa) --}}
        @auth
            @if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'dosen')
                <a href="{{ route('dashboard.me', ['locale' => app()->getLocale()]) }}"
                    class="sb-item {{ request()->routeIs('dashboard.me') ? 'sb-active' : '' }}">
                    <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span data-translate="my_dashboard">My Dashboard</span>
                </a>
            @endif
        @endauth

        {{-- ======================== ADMIN MENU ======================== --}}
        @auth
            @if(Auth::user()->role === 'admin')

                <a href="{{ route('admin.index') }}"
                    class="sb-item {{ request()->routeIs('admin.index') ? 'sb-active' : '' }}">
                    <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
                    </svg>
                    <span data-translate="admin_dashboard">Admin Dashboard</span>
                    <span class="sb-badge-role admin">Admin</span>
                </a>

                {{-- ---------- SECTION: MANAJEMEN DATA ---------- --}}
                <p class="sb-section-label mt-4" data-translate="manajemen_data">Manajemen Data</p>

                {{-- Users --}}
                <div x-data="{ open: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="sb-item w-full {{ request()->routeIs('admin.users.*') ? 'sb-active' : '' }}">
                        <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="flex-1 text-left" data-translate="manajemen_users">Manajemen Users</span>
                        <svg :class="open ? 'rotate-180' : ''" class="sb-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="sb-sub-group">
                        <a href="{{ route('admin.users.index') }}" class="sb-sub {{ request()->routeIs('admin.users.index') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span data-translate="semua_user">Lihat User</span>
                        </a>
                        <a href="{{ route('admin.users.ViewCreate') }}" class="sb-sub {{ request()->routeIs('admin.users.ViewCreate') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span data-translate="tambah_user">Tambah User</span>
                        </a>
                    </div>
                </div>

                {{-- Angkatan --}}
                <div x-data="{ open: {{ request()->routeIs('admin.angkatan.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="sb-item w-full {{ request()->routeIs('admin.angkatan.*') ? 'sb-active' : '' }}">
                        <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/>
                        </svg>
                        <span class="flex-1 text-left" data-translate="manajemen_angkatan">Manajemen Angkatan</span>
                        <svg :class="open ? 'rotate-180' : ''" class="sb-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="sb-sub-group">
                        <a href="{{ route('admin.angkatan.index') }}" class="sb-sub {{ request()->routeIs('admin.angkatan.index') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <span data-translate="lihat_angkatan">Lihat Angkatan</span>
                        </a>
                        <a href="{{ route('admin.angkatan.create') }}" class="sb-sub {{ request()->routeIs('admin.angkatan.create') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span data-translate="tambah_angkatan">Tambah Angkatan</span>
                        </a>
                    </div>
                </div>

                {{-- Prodi --}}
                <div x-data="{ open: {{ request()->routeIs('admin.prodi.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="sb-item w-full {{ request()->routeIs('admin.prodi.*') ? 'sb-active' : '' }}">
                        <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                        <span class="flex-1 text-left" data-translate="manajemen_prodi">Manajemen Prodi</span>
                        <svg :class="open ? 'rotate-180' : ''" class="sb-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="sb-sub-group">
                        <a href="{{ route('admin.prodi.index') }}" class="sb-sub {{ request()->routeIs('admin.prodi.index') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <span data-translate="lihat_prodi">Lihat Prodi</span>
                        </a>
                        <a href="{{ route('admin.prodi.create') }}" class="sb-sub {{ request()->routeIs('admin.prodi.create') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span data-translate="tambah_prodi">Tambah Prodi</span>
                        </a>
                    </div>
                </div>

                {{-- Keahlian --}}
                <div x-data="{ open: {{ request()->routeIs('admin.keahlian.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="sb-item w-full {{ request()->routeIs('admin.keahlian.*') ? 'sb-active' : '' }}">
                        <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        <span class="flex-1 text-left" data-translate="manajemen_keahlian">Manajemen Keahlian</span>
                        <svg :class="open ? 'rotate-180' : ''" class="sb-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="sb-sub-group">
                        <a href="{{ route('admin.keahlian.index') }}" class="sb-sub {{ request()->routeIs('admin.keahlian.index') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <span data-translate="lihat_keahlian">Lihat Keahlian</span>
                        </a>
                        <a href="{{ route('admin.keahlian.create') }}" class="sb-sub {{ request()->routeIs('admin.keahlian.create') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span data-translate="tambah_keahlian">Tambah Keahlian</span>
                        </a>
                    </div>
                </div>

                {{-- ---------- SECTION: KONTEN ---------- --}}
                <p class="sb-section-label mt-4" data-translate="konten"></p>

                {{-- Projects --}}
                <div x-data="{ open: {{ request()->routeIs('admin.projects.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="sb-item w-full {{ request()->routeIs('admin.projects.*') ? 'sb-active' : '' }}">
                        <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="flex-1 text-left" data-translate="manajemen_projects">Manajemen Project</span>
                        <svg :class="open ? 'rotate-180' : ''" class="sb-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="sb-sub-group">
                        <a href="{{ route('admin.projects.index') }}" class="sb-sub {{ request()->routeIs('admin.projects.index') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <span data-translate="lihat_proyek">Lihat Projek</span>
                        </a>
                        <a href="{{ route('admin.projects.create') }}" class="sb-sub {{ request()->routeIs('admin.projects.create') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span data-translate="tambah_proyek">Tambah Projek</span>
                        </a>
                    </div>
                </div>

                {{-- Sertifikat --}}
                <div x-data="{ open: {{ request()->routeIs('admin.sertifikat.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="sb-item w-full {{ request()->routeIs('admin.sertifikat.*') ? 'sb-active' : '' }}">
                        <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="16" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/><circle cx="12" cy="14" r="2"/>
                        </svg>
                        <span class="flex-1 text-left" data-translate="manajemen_sertifikat">Manajemen Sertifikat</span>
                        <svg :class="open ? 'rotate-180' : ''" class="sb-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="sb-sub-group">
                        <a href="{{ route('admin.sertifikat.index') }}" class="sb-sub {{ request()->routeIs('admin.sertifikat.index') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <span data-translate="lihat_sertifikat">Lihat Sertifikat</span>
                        </a>
                        <a href="{{ route('admin.sertifikat.create') }}" class="sb-sub {{ request()->routeIs('admin.sertifikat.create') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span data-translate="tambah_sertifikat">Tambah Sertifikat</span>
                        </a>
                    </div>
                </div>

                {{-- Notifikasi --}}
                <div x-data="{ open: {{ request()->routeIs('admin.notifications.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="sb-item w-full {{ request()->routeIs('admin.notifications.*') ? 'sb-active' : '' }}">
                        <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="flex-1 text-left" data-translate="manajemen_notifikasi">Manajemen Notifikasi</span>
                        <svg :class="open ? 'rotate-180' : ''" class="sb-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="sb-sub-group">
                        <a href="{{ route('admin.notifications.index') }}" class="sb-sub {{ request()->routeIs('admin.notifications.index') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <span data-translate="lihat_notifikasi">Lihat Notifikasi</span>
                        </a>
                        <a href="{{ route('admin.notifications.create') }}" class="sb-sub {{ request()->routeIs('admin.notifications.create') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span data-translate="tambah_notifikasi">Tambah Notifikasi</span>
                        </a>
                    </div>
                </div>

                {{-- Postingan --}}
                <div x-data="{ open: {{ request()->routeIs('postingan.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="sb-item w-full {{ request()->routeIs('postingan.*') ? 'sb-active' : '' }}">
                        <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                        <span class="flex-1 text-left" data-translate="postingan">Postingan</span>
                        <svg :class="open ? 'rotate-180' : ''" class="sb-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="sb-sub-group">
                        <a href="{{ route('postingan.index') }}" class="sb-sub {{ request()->routeIs('postingan.index') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <span data-translate="postingan"></span>
                        </a>
                        <a href="{{ route('postingan.create') }}" class="sb-sub {{ request()->routeIs('postingan.create') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span data-translate="postingan_tambah"></span>
                        </a>
                    </div>
                </div>

            @endif
        @endauth

        {{-- ======================== DOSEN MENU ======================== --}}
        @auth
            @if(Auth::user()->role === 'dosen')

                <a href="{{ route('dosen.dashboard') }}"
                    class="sb-item {{ request()->routeIs('dosen.dashboard') ? 'sb-active' : '' }}">
                    <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
                    </svg>
                    <span data-translate="dashboard_dosen">Dashboard Dosen</span>
                    <span class="sb-badge-role dosen">Dosen</span>
                </a>

                {{-- ---------- SECTION: BIMBINGAN ---------- --}}
                <p class="sb-section-label mt-4" data-translate="bimingan"></p>

                {{-- Mahasiswa Bimbingan --}}
                <div x-data="{ open: {{ request()->routeIs('dosen.users.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="sb-item w-full {{ request()->routeIs('dosen.users.*') ? 'sb-active' : '' }}">
                        <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/>
                        </svg>
                        <span class="flex-1 text-left" data-translate="mhs_bbg">Mahasiswa Bimbingan</span>
                        <svg :class="open ? 'rotate-180' : ''" class="sb-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="sb-sub-group">
                        <a href="{{ route('dosen.users.index') }}" class="sb-sub {{ request()->routeIs('dosen.users.index') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <span data-translate="see_mhs">Lihat Mahasiswa</span>
                        </a>
                        <a href="{{ route('dosen.users.ViewCreate') }}" class="sb-sub {{ request()->routeIs('dosen.users.ViewCreate') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span data-translate="add_mhs">Tambah Mahasiswa</span>
                        </a>
                    </div>
                </div>

                {{-- Projects Bimbingan --}}
                <div x-data="{ open: {{ request()->routeIs('dosen.projects.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="sb-item w-full {{ request()->routeIs('dosen.projects.*') ? 'sb-active' : '' }}">
                        <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="flex-1 text-left" data-translate="pjt_bbbg">Projects Bimbingan</span>
                        <svg :class="open ? 'rotate-180' : ''" class="sb-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="sb-sub-group">
                        <a href="{{ route('dosen.projects.index') }}" class="sb-sub {{ request()->routeIs('dosen.projects.index') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <span data-translate="see_pjt">Lihat Project</span>
                        </a>
                        <a href="{{ route('dosen.projects.create') }}" class="sb-sub {{ request()->routeIs('dosen.projects.create') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span data-translate="add_pjt">Tambah Project</span>
                        </a>
                    </div>
                </div>

                {{-- Sertifikat Bimbingan --}}
                <div x-data="{ open: {{ request()->routeIs('dosen.sertifikat.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="sb-item w-full {{ request()->routeIs('dosen.sertifikat.*') ? 'sb-active' : '' }}">
                        <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="16" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/><circle cx="12" cy="14" r="2"/>
                        </svg>
                        <span class="flex-1 text-left" data-translate="stk_bbg">Sertifikat Bimbingan</span>
                        <svg :class="open ? 'rotate-180' : ''" class="sb-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="sb-sub-group">
                        <a href="{{ route('dosen.sertifikat.index') }}" class="sb-sub {{ request()->routeIs('dosen.sertifikat.index') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <span data-translate="see_stk">Lihat Sertifikat</span>
                        </a>
                        <a href="{{ route('dosen.sertifikat.create') }}" class="sb-sub {{ request()->routeIs('dosen.sertifikat.create') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span data-translate="add_stk">Tambah Sertifikat</span>
                        </a>
                    </div>
                </div>

                {{-- Postingan --}}
                <p class="sb-section-label mt-4" data-translate="konten"></p>
                <div x-data="{ open: {{ request()->routeIs('postingan.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="sb-item w-full {{ request()->routeIs('postingan.*') ? 'sb-active' : '' }}">
                        <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
                        </svg>
                        <span class="flex-1 text-left" data-translate="postingan">Postingan</span>
                        <svg :class="open ? 'rotate-180' : ''" class="sb-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="sb-sub-group">
                        <a href="{{ route('postingan.index') }}" class="sb-sub {{ request()->routeIs('postingan.index') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <span data-translate="postingan"></span>
                        </a>
                        <a href="{{ route('postingan.create') }}" class="sb-sub {{ request()->routeIs('postingan.create') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span data-translate="postingan_tambah"></span>
                        </a>
                    </div>
                </div>

            @endif
        @endauth

        {{-- ======================== MAHASISWA MENU ======================== --}}
        @auth
            @if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'dosen')

                {{-- ---------- SECTION: AKADEMIK ---------- --}}
                <p class="sb-section-label mt-4" data-translate="akademik"></p>

                {{-- Project --}}
                <div x-data="{ open: {{ request()->routeIs('project.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="sb-item w-full {{ request()->routeIs('project.*') ? 'sb-active' : '' }}">
                        <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="flex-1 text-left" data-translate="project_mahasiswa">Project</span>
                        <svg :class="open ? 'rotate-180' : ''" class="sb-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="sb-sub-group">
                        <a href="{{ route('project.index') }}" class="sb-sub {{ request()->routeIs('project.index') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <span data-translate="project_mahasiswa_saya">Project Saya</span>
                        </a>
                        <a href="{{ route('project.create') }}" class="sb-sub {{ request()->routeIs('project.create') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span data-translate="project_mahasiswa_saya_tambah">Tambah Project Baru</span>
                        </a>
                    </div>
                </div>

                {{-- Sertifikat --}}
                <div x-data="{ open: {{ request()->routeIs('sertifikat.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="sb-item w-full {{ request()->routeIs('sertifikat.*') ? 'sb-active' : '' }}">
                        <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="16" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/><circle cx="12" cy="14" r="2"/>
                        </svg>
                        <span class="flex-1 text-left" data-translate="sertifikat_mahasiswa">Sertifikat</span>
                        <svg :class="open ? 'rotate-180' : ''" class="sb-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="sb-sub-group">
                        <a href="{{ route('sertifikat.index') }}" class="sb-sub {{ request()->routeIs('sertifikat.index') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <span data-translate="sertifikat_mahasiswa_saya">Sertifikat Saya</span>
                        </a>
                        <a href="{{ route('sertifikat.create') }}" class="sb-sub {{ request()->routeIs('sertifikat.create') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span data-translate="sertifikat_mahasiswa_saya_tambah">Tambah Sertifikat</span>
                        </a>
                    </div>
                </div>

                {{-- Postingan --}}
                <div x-data="{ open: {{ request()->routeIs('postingan.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="sb-item w-full {{ request()->routeIs('postingan.*') ? 'sb-active' : '' }}">
                        <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
                        </svg>
                        <span class="flex-1 text-left" data-translate="postingan">Postingan</span>
                        <svg :class="open ? 'rotate-180' : ''" class="sb-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="sb-sub-group">
                        <a href="{{ route('postingan.index') }}" class="sb-sub {{ request()->routeIs('postingan.index') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <span data-translate="postingan"></span>
                        </a>
                        <a href="{{ route('postingan.create') }}" class="sb-sub {{ request()->routeIs('postingan.create') ? 'sb-sub-active' : '' }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span data-translate="postingan_tambah"></span>
                        </a>
                    </div>
                </div>

            @endif
        @endauth

        {{-- ======================== GUEST MENU ======================== --}}
        @guest
            <a href="{{ route('project.project_user') }}"
                class="sb-item {{ request()->routeIs('project.project_user') ? 'sb-active' : '' }}">
                <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span data-translate="project_mahasiswa">Project Mahasiswa</span>
            </a>
        @endguest

    </nav>

    {{-- ======================== SETTING DROPDOWN ======================== --}}
    <div class="relative mt-auto shrink-0 border-t border-gray-200 dark:border-gray-700">
        <button id="sidebar-settings-button" onclick="toggleSidebarSettingDropdown()"
            class="w-full flex items-center gap-3 px-4 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <svg class="sb-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
            </svg>
            <span class="text-sm" data-translate="setting">Pengaturan</span>
            <svg id="settingArrow" class="w-4 h-4 ml-auto transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div id="settingMenu"
            class="hidden absolute bottom-full left-0 right-0 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-t-xl shadow-2xl p-4 z-50">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-700 dark:text-gray-300 text-sm" data-translate="mode">Mode Tampilan</span>
                <button id="darkModeBtn" onclick="toggleSidebarDarkMode()"
                    class="px-3 py-1 rounded-lg text-xs font-medium bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Dark Mode
                </button>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-700 dark:text-gray-300 text-sm" data-translate="bahasa">Bahasa</span>
                <select id="languageSelect" onchange="changeLanguage()"
                    class="bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-lg text-xs font-medium text-gray-800 dark:text-gray-200 border-0 outline-none">
                    <option value="id" {{ app()->getLocale() === 'id' ? 'selected' : '' }}>Indonesia</option>
                    <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                </select>
            </div>
        </div>
    </div>

    {{-- ======================== PROFILE ======================== --}}
    @auth
    <div class="px-3 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 shrink-0">
        <div x-data="{ open: false }">
            <button id="sidebar-profile-button" @click="open = !open" @click.away="open = false"
                class="w-full flex items-center gap-3 p-2 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors group">
                <div class="w-9 h-9 rounded-lg overflow-hidden shrink-0">
                    @if(Auth::user()->photo_profile)
                        <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}"
                             alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full
                            @if(Auth::user()->role === 'admin') bg-blue-600
                            @elseif(Auth::user()->role === 'dosen') bg-emerald-600
                            @else bg-violet-600 @endif
                            flex items-center justify-center text-white text-sm font-semibold">
                            {{ strtoupper(substr(Auth::user()->nama_mahasiswa ?? Auth::user()->name ?? 'U', 0, 2)) }}
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0 text-left">
                    <p class="text-gray-800 dark:text-gray-100 text-sm font-medium truncate">
                        {{ Auth::user()->nama_mahasiswa ?? Auth::user()->name ?? 'User' }}
                    </p>
                    <p class="text-gray-500 dark:text-gray-400 text-xs truncate">{{ Auth::user()->email }}</p>
                </div>
                @if(Auth::user()->role === 'admin')
                    <span class="sb-badge-role admin shrink-0">Admin</span>
                @elseif(Auth::user()->role === 'dosen')
                    <span class="sb-badge-role dosen shrink-0">Dosen</span>
                @endif
                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-cloak x-show="open"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mt-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

                @if(Auth::user()->role === 'mahasiswa')
                    <a href="{{ route('profile') }}" class="sb-profile-item">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span data-translate="lihat_profil">Lihat Profil</span>
                    </a>
                @endif

                <a href="{{ route('passkeys.index') }}" class="sb-profile-item">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    <span data-translate="verifikasi_2_langkah">Verifikasi 2 Langkah</span>
                </a>

                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'dosen')
                    <div x-data="{ showPhotoModal: false }">
                        <button @click.stop="showPhotoModal = true" class="sb-profile-item w-full text-left">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span data-translate="foto_profil">Foto Profil</span>
                        </button>
                        <div x-cloak x-show="showPhotoModal" x-transition.opacity
                            class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/60 px-4">
                            <div @click.stop class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white" data-translate="ubah_foto_profil">Ubah Foto Profil</h2>
                                    <button @click="showPhotoModal = false" class="text-gray-400 hover:text-red-500 text-xl">✕</button>
                                </div>
                                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf @method('PATCH')
                                    <div class="p-6">
                                        <div class="flex justify-center mb-5">
                                            <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-gray-200 dark:border-gray-700">
                                                <img id="preview-photo"
                                                    src="{{ Auth::user()->photo_profile ? asset('storage/'.Auth::user()->photo_profile) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}"
                                                    class="w-full h-full object-cover">
                                            </div>
                                        </div>
                                        <label class="block cursor-pointer">
                                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-4 text-center hover:border-blue-500 transition">
                                                <p class="text-sm text-gray-700 dark:text-gray-200" data-translate="pilih_gambar">Pilih Gambar</p>
                                                <p class="text-xs text-gray-400 mt-1">JPG, PNG, JPEG</p>
                                            </div>
                                            <input type="file" name="photo_profile" accept="image/*" class="hidden" onchange="previewPhoto(event)">
                                        </label>
                                    </div>
                                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 flex justify-end gap-2">
                                        <button type="button" @click="showPhotoModal = false"
                                            class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-white text-sm">
                                            <span data-translate="batal">Batal</span>
                                        </button>
                                        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm">
                                            <span data-translate="simpan">Simpan</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="absolute inset-0 -z-10" @click="showPhotoModal = false"></div>
                        </div>
                    </div>
                @endif

                <div class="border-t border-gray-200 dark:border-gray-700 mt-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sb-profile-item w-full text-left !text-red-500 dark:!text-red-400 hover:!bg-red-50 dark:hover:!bg-red-900/20">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span data-translate="logout">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 shrink-0">
        <p class="text-gray-500 dark:text-gray-400 text-xs text-center mb-3" data-translate="login_prompt">Silakan login untuk mengakses fitur</p>
        <a href="{{ route('login') }}"
            class="flex items-center justify-center gap-2 w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            <span data-translate="login" data-translate-page>Masuk</span>
        </a>
    </div>
    @endauth


</aside>