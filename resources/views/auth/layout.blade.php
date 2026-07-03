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

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    
    <script src="https://cdn.jsdelivr.net/npm/@simplewebauthn/browser@10/dist/bundle/index.umd.min.js"></script>
    
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

<body class="">


    <div class="flex flex-col min-h-screen">

        <!-- Page content -->
        <main class="flex-1 overflow-auto">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('components.footer')

    </div>

    @include('components.chat-bot')
    @include('components.up-page')
</body>

</html>