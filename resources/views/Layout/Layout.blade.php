<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <script>
        // Jalankan sesegera mungkin sebelum konten dirender
        (function() {
            const storedLang = localStorage.getItem('lang');
            if (storedLang && ['id','en'].includes(storedLang)) {
                document.documentElement.lang = storedLang;
                // Jika cookie belum sesuai, set cookie agar server juga pakai bahasa ini
                if (!document.cookie.split('; ').some(row => row.startsWith('lang=' + storedLang))) {
                    const expires = new Date(Date.now() + 365 * 24 * 60 * 60 * 1000).toUTCString();
                    document.cookie = 'lang=' + storedLang + '; expires=' + expires + '; path=/; SameSite=Lax';
                }
            }
        })();
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/Logo.svg') }}">
    <link rel="manifest" href="/manifest.json">
    @hasSection('title')
        <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>
    @else
        <title>{{ config('app.name', 'Laravel') }}</title>
    @endif
    @yield('meta')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@simplewebauthn/browser@10/dist/bundle/index.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth !important;
        }

        /* Wrapper utama: sidebar kiri + konten kanan, membentang penuh */
        .app-layout {
            display: flex;
            min-height: 100vh;
            align-items: stretch;
        }

        /* Sidebar tetap di sisi kiri, tinggi penuh layar, sticky */
        #sidebar {
            z-index: 40;
            position: sticky;
            top: 0;
            height: 100vh;
            flex-shrink: 0;
            overflow-y: auto;
        }

        /* Kolom kanan: header + main + footer mengalir secara natural */
        .main-column {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            min-width: 0;
            min-height: 100vh;
        }

        main {
            flex: 1 1 auto;
            min-width: 0;
            width: 100%;
            overflow-x: auto;
            margin-bottom: 5rem;
        }

        .content-wrapper {
            width: 100%;
            max-width: 100%;
        }

        #mobile-search-dropdown {
            overflow: hidden;
        }

        /* 1. Pastikan parent chain mendukung tinggi penuh */
        .dashboard-container {
            height: auto;
            /* biarkan fleksibel */
            min-height: 0;
            /* penting untuk overflow */
        }

        /* 2. Untuk layar >= 1024px (desktop) */
        @media (min-width: 1024px) {
            .dashboard-container {
                align-items: start;
                /* agar sticky bekerja */
                gap: 1.5rem;
            }

            /* Kolom kiri: scroll independen */
            .feed-column {
                overflow-y: auto;
                max-height: calc(100vh - 120px);
                /* sesuaikan 120px dengan tinggi header + padding */
                scrollbar-width: thin;
            }

            /* Kolom kanan: tetap di posisi saat scroll kiri */
            .sidebar-column {
                position: sticky;
                top: 20px;
                /* sesuai sticky top-6 di dalamnya */
                align-self: start;
            }
        }

        /* 3. Opsional: untuk mobile, tetap tumpuk (grid 1fr) */
        @media (max-width: 1023px) {
            .feed-column {
                overflow-y: visible;
                max-height: none;
            }

            .sidebar-column {
                position: static;
            }
        }

        @media (max-width: 1023px) {
            #sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
            }
        }
    </style>

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/alert.js', 'resources/js/translate.js'])
</head>

<body class="bg-gray-50 dark:bg-gray-800 antialiased">

    @if(!request()->has('skip_splash'))
        @include('components.splash')
    @endif

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 lg:hidden hidden transition-opacity duration-300">
    </div>

    <div class="app-layout">
        @include('components.sidebar')

        <div class="main-column">
            @include('components.header')

            <main class="overflow-auto">
                <div class="">
                    @yield('content')
                </div>
            </main>

            <!-- SESUDAH -->
            @auth
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'dosen')
                    {{-- Admin & Dosen: tampil di semua ukuran layar --}}
                    @include('components.footer')
                @else
                    {{-- Mahasiswa: hanya tampil di desktop --}}
                    <div class="hidden md:block">
                        @include('components.footer')
                    </div>
                @endif
            @else
                {{-- Guest/tidak login: hanya tampil di desktop --}}
                <div class="hidden md:block">
                    @include('components.footer')
                </div>
            @endauth

            @include('components.navigation_mahasiswa_mobile')
            @include('components.up-page')
            @include('components.chat-bot')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('toggle-sidebar');
            const hamburger = document.getElementById('sidebar-hamburger');
            const closeIcon = document.getElementById('sidebar-close');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const closeSidebarBtn = document.getElementById('close-sidebar');
            const toggleSearch = document.getElementById('toggle-search-mobile');
            const searchDrop = document.getElementById('mobile-search-dropdown');
            const toggleDesktopBtn = document.getElementById('toggle-desktop-sidebar');
            const toggleIcon = document.getElementById('toggleCollapseIcon');

            if (toggleDesktopBtn) {
                toggleDesktopBtn.addEventListener('click', () => {
                    const isCollapsed = sidebar.classList.contains('lg:w-20');
                    if (isCollapsed) {
                        sidebar.classList.remove('lg:w-20');
                        sidebar.classList.add('lg:w-62');
                        toggleIcon.classList.remove('rotate-180');
                        fetch('/toggle-sidebar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                            },
                            body: JSON.stringify({ collapsed: false })
                        });
                    } else {
                        sidebar.classList.remove('lg:w-62');
                        sidebar.classList.add('lg:w-20');
                        toggleIcon.classList.add('rotate-180');
                        document.querySelectorAll('[x-data]').forEach(el => {
                            if (el.__x) el.__x.$data.open = false;
                        });
                        fetch('/toggle-sidebar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                            },
                            body: JSON.stringify({ collapsed: true })
                        });
                    }
                });
            }

            if (toggleSearch && searchDrop) {
                toggleSearch.addEventListener('click', () => {
                    const isHidden = searchDrop.classList.contains('hidden');
                    if (isHidden) {
                        searchDrop.classList.remove('hidden');
                        searchDrop.style.maxHeight = '0px';
                        searchDrop.style.opacity = '0';
                        requestAnimationFrame(() => {
                            searchDrop.style.transition = 'max-height 0.3s ease, opacity 0.25s ease';
                            searchDrop.style.maxHeight = searchDrop.scrollHeight + 'px';
                            searchDrop.style.opacity = '1';
                        });
                    } else {
                        searchDrop.style.transition = 'max-height 0.25s ease, opacity 0.2s ease';
                        searchDrop.style.maxHeight = '0px';
                        searchDrop.style.opacity = '0';
                        setTimeout(() => searchDrop.classList.add('hidden'), 250);
                    }
                });
            }
            /*
            function deteksiJaringan() {
                if (navigator.onLine) {
                    console.log("Status: Online");
                } else {
                    console.log("Status: Offline");
                    const offlineUrl = "{{ route('offline', app()->getLocale()) }}";
            if (window.location.href !== offlineUrl) {
                window.location.href = offlineUrl;
            }
        }
            }
            deteksiJaringan();
        window.addEventListener('online', () => console.log("Kembali Online"));
        window.addEventListener('offline', deteksiJaringan);
            */

        document.addEventListener('click', (e) => {
            if (!searchDrop || !toggleSearch) return;
            if (!searchDrop.contains(e.target) && !toggleSearch.contains(e.target)) {
                if (!searchDrop.classList.contains('hidden')) {
                    searchDrop.style.transition = 'max-height 0.25s ease, opacity 0.2s ease';
                    searchDrop.style.maxHeight = '0px';
                    searchDrop.style.opacity = '0';
                    setTimeout(() => searchDrop.classList.add('hidden'), 250);
                }
            }
        });

        function openSidebar() {
            if (!sidebar) return;
            sidebar.classList.remove('-translate-x-full');
            if (overlay) {
                overlay.classList.remove('hidden');
                overlay.classList.add('block');
            }
            document.body.style.overflow = 'hidden';
            if (hamburger) hamburger.classList.add('hidden');
            if (closeIcon) closeIcon.classList.remove('hidden');
        }

        function closeSidebar() {
            if (!sidebar) return;
            sidebar.classList.add('-translate-x-full');
            if (overlay) {
                overlay.classList.remove('block');
                overlay.classList.add('hidden');
            }
            document.body.style.overflow = '';
            if (hamburger) hamburger.classList.remove('hidden');
            if (closeIcon) closeIcon.classList.add('hidden');
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                if (sidebar?.classList.contains('-translate-x-full')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            });
        }
        if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);

        window.toggleDropdown = function (menuId) {
            const menu = document.getElementById(menuId + 'Menu');
            const arrow = document.getElementById(menuId + 'Arrow');
            if (menu && arrow) {
                menu.classList.toggle('hidden');
                arrow.classList.toggle('rotate-180');
            }
        };

        window.toggleTheme = function () {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        };
        });
    </script>

    @stack('scripts')
</body>

</html>