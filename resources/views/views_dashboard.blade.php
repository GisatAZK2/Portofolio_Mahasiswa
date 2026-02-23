<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            @include('components.header')

            <!-- Main Content Area -->
            <main class="flex-1 overflow-auto p-6">
               

            <!-- Footer -->
            @include('components.footer')
        </div>
    </div>
</body>
</html>