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


    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/alert.js', 'resources/js/translate.js'])
</head>

<body class="bg-gray-50 dark:bg-gray-800 antialiased">

    @if (session('success') || session('error'))
        <div id="flash-message" class="hidden" 
             data-success="{{ session('success') }}" 
             data-error="{{ session('error') }}"></div>
    @endif

    @if(!request()->has('skip_splash') && empty($_COOKIE['splash_shown']))
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


    @stack('scripts')
</body>

</html>