<!DOCTYPE html>
<html lang="id"
    class="{{ request()->cookie('theme') === 'dark' || (!request()->hasCookie('theme') && now()->hour >= 18) ? 'dark' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Error</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    <!-- Error Content -->
    <div class="min-h-[calc(100vh-80px)] flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full space-y-8 text-center">
            @yield('error-content')
        </div>
    </div>

    @include('components.footer')

</body>

</html>