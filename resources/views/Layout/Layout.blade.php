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

    
    <div class="flex min-h-screen">

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
    // Elemen-elemen penting
    const sidebar         = document.getElementById('sidebar');
    const toggleBtn       = document.getElementById('toggle-sidebar');
    const hamburger       = document.getElementById('sidebar-hamburger');
    const closeIcon       = document.getElementById('sidebar-close');
    const overlay         = document.getElementById('sidebar-overlay');

    const searchToggle    = document.getElementById('toggle-search-mobile');
    const searchDropdown  = document.getElementById('mobile-search-dropdown');

    // ====================
    // Fungsi Sidebar
    // ====================
    function openSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove('-translate-x-full');
        if (overlay) {
            overlay.classList.remove('hidden');
            overlay.classList.add('opacity-100');
            overlay.classList.remove('opacity-0');
        }
        document.body.style.overflow = 'hidden'; // cegah scroll body

        hamburger?.classList.add('hidden');
        closeIcon?.classList.remove('hidden');
    }

    function closeSidebar() {
        if (!sidebar) return;
        sidebar.classList.add('-translate-x-full');
        if (overlay) {
            overlay.classList.remove('opacity-100');
            overlay.classList.add('opacity-0');
            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 300); // sesuai duration-300
        }
        document.body.style.overflow = '';

        hamburger?.classList.remove('hidden');
        closeIcon?.classList.add('hidden');
    }

    // Toggle sidebar via tombol
    toggleBtn?.addEventListener('click', () => {
        if (sidebar?.classList.contains('-translate-x-full')) {
            openSidebar();
        } else {
            closeSidebar();
        }
    });

    // Tutup via overlay (klik di luar sidebar)
    overlay?.addEventListener('click', closeSidebar);

    // Jika ada tombol close di dalam sidebar (opsional)
    document.getElementById('close-sidebar')?.addEventListener('click', closeSidebar);

    // ====================
    // Fungsi Mobile Search
    // ====================
    function openSearch() {
        if (!searchDropdown) return;
        searchDropdown.classList.remove('hidden');
        searchDropdown.classList.remove('max-h-0');
        // trigger reflow agar transisi jalan
        searchDropdown.offsetHeight;
    }

    function closeSearch() {
        if (!searchDropdown) return;
        searchDropdown.classList.add('max-h-0');
        setTimeout(() => {
            searchDropdown.classList.add('hidden');
        }, 300); // sesuai duration di CSS
    }

    // Toggle search via tombol
    searchToggle?.addEventListener('click', () => {
        if (searchDropdown?.classList.contains('hidden') || 
            searchDropdown?.classList.contains('max-h-0')) {
            openSearch();
        } else {
            closeSearch();
        }
    });

    // ====================
    // Klik di luar → tutup keduanya (sidebar & search mobile)
    // ====================
    document.addEventListener('click', function(e) {
        // --- Sidebar ---
        const sidebarIsOpen = sidebar && 
                             !sidebar.classList.contains('-translate-x-full') &&
                             window.innerWidth < 1024; // hanya mobile

        if (sidebarIsOpen) {
            const clickedInsideSidebar = sidebar.contains(e.target);
            const clickedToggleBtn     = toggleBtn?.contains(e.target);

            if (!clickedInsideSidebar && !clickedToggleBtn) {
                closeSidebar();
            }
        }

        // --- Mobile Search ---
        const searchIsOpen = searchDropdown && 
                            !searchDropdown.classList.contains('hidden') &&
                            !searchDropdown.classList.contains('max-h-0');

        if (searchIsOpen) {
            const clickedInsideSearch = searchDropdown.contains(e.target);
            const clickedSearchBtn    = searchToggle?.contains(e.target);

            if (!clickedInsideSearch && !clickedSearchBtn) {
                closeSearch();
            }
        }
    });

    // ====================
    // Handle resize → pastikan sidebar terbuka di desktop
    // ====================
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) { // lg breakpoint
            // Force buka sidebar di desktop
            sidebar?.classList.remove('-translate-x-full');
            hamburger?.classList.remove('hidden');
            closeIcon?.classList.add('hidden');
            overlay?.classList.add('hidden', 'opacity-0');

            // Tutup search mobile
            closeSearch();
        }
    });

    // ====================
    // Dropdown submenu (Project, Learning Corner, Sertifikat, dll)
    // ====================
    window.toggleDropdown = function(menuId) {
        const menu  = document.getElementById(menuId + 'Menu');
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