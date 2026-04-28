<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
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
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .flex.h-screen {
            overflow-x: hidden;
            min-height: 100vh;
            height: 100%;
        }
        main {
            position: relative;
            z-index: 1;
            min-width: 0;   
            width: 100%;
            overflow-x: auto;
            flex: 1 1 auto;
        }
        #sidebar {
            z-index: 40;
        }
        .content-wrapper {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
        }
        @media (max-width: 1023px) {
            main {
                z-index: 1;
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

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 lg:hidden hidden transition-opacity duration-300"></div>

    <div class="flex h-screen">
        @include('components.sidebar')

        <div class="flex-1 flex flex-col min-w-0 h-full">
            @include('components.header')

            <main class="overflow-x-auto flex-1">
                <div class="content-wrapper">
                    @yield('content')
                </div>
            </main>

            @include('components.footer')
            @include('components.chat-bot')
            @include('components.up-page')
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
                    const isClosed = searchDrop.classList.contains('max-h-0');
                    if (isClosed) {
                        searchDrop.style.maxHeight = '0px';
                        searchDrop.classList.remove('max-h-0', 'opacity-0', '-translate-y-2', 'scale-y-95');
                        searchDrop.classList.add('opacity-100', 'translate-y-0', 'scale-y-100');
                        requestAnimationFrame(() => {
                            searchDrop.style.maxHeight = searchDrop.scrollHeight + 'px';
                        });
                    } else {
                        searchDrop.style.maxHeight = searchDrop.scrollHeight + 'px';
                        requestAnimationFrame(() => {
                            searchDrop.style.maxHeight = '0px';
                        });
                        searchDrop.classList.add('opacity-0', '-translate-y-2', 'scale-y-95', 'max-h-0');
                        searchDrop.classList.remove('opacity-100', 'translate-y-0', 'scale-y-100');
                    }
                });
            }

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

            document.addEventListener('click', (e) => {
                if (!searchDrop || !toggleSearch) return;
                const isClickInsideSearch = searchDrop.contains(e.target);
                const isClickOnToggle = toggleSearch.contains(e.target);
                if (!isClickInsideSearch && !isClickOnToggle) {
                    if (!searchDrop.classList.contains('max-h-0')) {
                        searchDrop.style.maxHeight = searchDrop.scrollHeight + 'px';
                        requestAnimationFrame(() => {
                            searchDrop.style.maxHeight = '0px';
                        });
                        searchDrop.classList.add('opacity-0', '-translate-y-2', 'scale-y-95', 'max-h-0');
                        searchDrop.classList.remove('opacity-100', 'translate-y-0', 'scale-y-100');
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