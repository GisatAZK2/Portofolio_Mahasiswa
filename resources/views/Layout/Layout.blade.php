<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.90, maximum-scale=0.80, user-scalable=no">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/Logo.svg') }}">

    @PwaHead
    @livewireStyles

    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
    if (
        localStorage.getItem('theme') === 'dark' ||
        (!localStorage.getItem('theme') &&
            window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
        document.documentElement.classList.add('dark');
    }
</script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/alert.js', 'resources/js/translate.js'])
    
</head>

<body  class="bg-gray-50 dark:bg-gray-800 antialiased">

    <!-- Overlay backdrop untuk mobile -->
    <div id="sidebar-overlay" class="fixed inset- bg-black/50 z-30 lg:hidden hidden transition-opacity duration-300">
    </div>


    <div class="flex h-screen">
    
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main content area -->
        <div class="flex-1 flex flex-col">

            <!-- Header Search Filter -->
            @include('components.header')

            <!-- Page content -->
            <main class="flex-1 overflow-auto p-6">
                @yield('content')
            </main>

            <!-- Footer -->
            @include('components.up-page')
            @include('components.footer')

            @livewireScripts
        </div>
    </div>

   <script>
    // Fungsi inisialisasi sidebar & semua listener
    function initSidebar() {
        const toggleBtn = document.getElementById('toggle-sidebar');
        const hamburger = document.getElementById('sidebar-hamburger');
        const closeIcon = document.getElementById('sidebar-close');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const closeSidebarBtn = document.getElementById('close-sidebar');
        const toggleSearch = document.getElementById('toggle-search-mobile');
        const searchDrop = document.getElementById('mobile-search-dropdown');

        // Toggle collapse desktop
        const toggleDesktopBtn = document.getElementById('toggle-desktop-sidebar');
        const toggleIcon = document.getElementById('toggleCollapseIcon');

        if (toggleDesktopBtn && toggleIcon && sidebar) {
            // Hapus listener lama jika ada (hindari duplikat)
            toggleDesktopBtn.removeEventListener('click', handleCollapse);
            toggleDesktopBtn.addEventListener('click', handleCollapse);

            function handleCollapse() {
                const isCollapsed = sidebar.classList.contains('lg:w-20');
                if (isCollapsed) {
                    sidebar.classList.remove('lg:w-20');
                    sidebar.classList.add('lg:w-62');
                    toggleIcon.classList.remove('rotate-180');
                    fetch('/toggle-sidebar', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify({ collapsed: false })
                    });
                } else {
                    sidebar.classList.remove('lg:w-62');
                    sidebar.classList.add('lg:w-20');
                    toggleIcon.classList.add('rotate-180');
                    // Tutup dropdown Alpine jika ada
                    document.querySelectorAll('[x-data]').forEach(el => {
                        if (el.__x?.$data?.open !== undefined) {
                            el.__x.$data.open = false;
                        }
                    });
                    fetch('/toggle-sidebar', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify({ collapsed: true })
                    });
                }
            }
        }

        // Toggle search mobile (jika ada)
        if (toggleSearch && searchDrop) {
            toggleSearch.removeEventListener('click', handleSearchToggle);
            toggleSearch.addEventListener('click', handleSearchToggle);

            function handleSearchToggle() {
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
            }
        }

        // Mobile sidebar functions
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
            toggleBtn.removeEventListener('click', handleMobileToggle);
            toggleBtn.addEventListener('click', handleMobileToggle);

            function handleMobileToggle() {
                if (sidebar?.classList.contains('-translate-x-full')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            }
        }

        if (closeSidebarBtn) {
            closeSidebarBtn.removeEventListener('click', closeSidebar);
            closeSidebarBtn.addEventListener('click', closeSidebar);
        }

        if (overlay) {
            overlay.removeEventListener('click', closeSidebar);
            overlay.addEventListener('click', closeSidebar);
        }

        // Fungsi toggle dropdown global (untuk Setting)
        window.toggleDropdown = function (menuId) {
            const menu = document.getElementById(menuId + 'Menu');
            const arrow = document.getElementById(menuId + 'Arrow');
            if (menu && arrow) {
                menu.classList.toggle('hidden');
                arrow.classList.toggle('rotate-180');
            }
        };

        // Theme toggle (jika dipakai)
        window.toggleTheme = function () {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        };
    }

    // Jalankan pertama kali (load awal)
    document.addEventListener('DOMContentLoaded', initSidebar);

    // Jalankan setiap navigasi Livewire selesai
    document.addEventListener('livewire:navigated', initSidebar);
</script>

    @stack('scripts')
</body>

</html>