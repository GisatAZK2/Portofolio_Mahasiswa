
@vite(['resources/css/app.css', 'resources/js/app.js'])

<body class="bg-gray-50 antialiased">

    <!-- Overlay backdrop untuk mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 lg:hidden hidden transition-opacity duration-300"></div>

    
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

            <!-- Footer (jika ada) -->
            @include('components.footer')
        </div>
    </div>

    <!-- JavaScript toggle sidebar -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggle-sidebar');
        const closeBtn = document.getElementById('close-sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            overlay.classList.add('block');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.remove('block');
            overlay.classList.add('hidden');
        }

        if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);

        // Contoh toggle dropdown portfolio
        function togglePortfolioMenu() {
            const menu = document.getElementById('portfolioMenu');
            const arrow = document.getElementById('portfolioArrow');
            if (menu && arrow) {
                menu.classList.toggle('hidden');
                arrow.classList.toggle('rotate-180');
            }
        }

        function toggleProjectMenu() {
    const menu = document.getElementById('projectMenu');
    const arrow = document.getElementById('projectArrow');
    if (menu && arrow) {
        menu.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }
}
    </script>

    @stack('scripts')
</body>
</html>