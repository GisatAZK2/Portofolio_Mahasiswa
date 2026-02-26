<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.90, maximum-scale=0.80, user-scalable=no">
    <meta name="theme-color" content="#ffffff">
    
    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 antialiased">

    <!-- Overlay backdrop untuk mobile -->
    <div id="sidebar-overlay" class="fixed inset- bg-black/50 z-30 lg:hidden hidden transition-opacity duration-300"></div>

    
    <div class="flex h-screen">

        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main content area -->
        <div class="flex-1 flex flex-col">

            <!-- Header / Navbar atas -->
            @include('components.header')

            <!-- Page content -->
            <main class="flex-1 overflow-auto p-6">
                @yield('content')
            </main>

            <!-- Footer -->

            @include('components.footer')
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', () => {
    // Sidebar toggle
    const toggleBtn       = document.getElementById('toggle-sidebar');
    const hamburger       = document.getElementById('sidebar-hamburger');
    const closeIcon       = document.getElementById('sidebar-close');
    const sidebar         = document.getElementById('sidebar');
    const overlay         = document.getElementById('sidebar-overlay');
    const closeSidebarBtn = document.getElementById('close-sidebar');
    const toggleSearch = document.getElementById('toggle-search-mobile');
    const searchDrop   = document.getElementById('mobile-search-dropdown');

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
            // Tutup
            searchDrop.style.maxHeight = searchDrop.scrollHeight + 'px'; 
            requestAnimationFrame(() => {
                searchDrop.style.maxHeight = '0px';
            });
            searchDrop.classList.add('opacity-0', '-translate-y-2', 'scale-y-95');
            searchDrop.classList.remove('opacity-100', 'translate-y-0', 'scale-y-100');

            setTimeout(() => {
                if (searchDrop.style.maxHeight === '0px') {
                }
            }, 350);
        }
    });
}

    function openSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove('-translate-x-full');
        if (overlay) {
            overlay.classList.remove('hidden');
            overlay.classList.add('block');
        }
        document.body.style.overflow = 'hidden';

        // Ganti icon ke X
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

        // Kembali ke hamburger
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

    // FIX: Definisi fungsi toggleDropdown() yang hilang
    window.toggleDropdown = function(menuId) {
        const menu = document.getElementById(menuId + 'Menu');
        const arrow = document.getElementById(menuId + 'Arrow');

        if (menu && arrow) {
            menu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }
    };
});
</script>

    @stack('scripts')
</body>
</html>