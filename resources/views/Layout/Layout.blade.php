@vite(['resources/css/app.css', 'resources/js/app.js'])

<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('components.sidebar')

        <div class="flex-1 flex flex-col">
            <!-- Header -->
            @include('components.header')

            <!-- Main Content Area -->
            <main class="flex-1 overflow-auto p-6">
                @yield('content')
            </main>

            <!-- Footer -->
            @include('components.footer')
        </div>
    </div>
</body>
</html>
