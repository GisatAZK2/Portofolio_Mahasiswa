<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
     <script>
        (function () {
            var SUPPORTED = ['id', 'en'];
            var pathSegments = window.location.pathname.split('/').filter(Boolean);
            var urlLang = SUPPORTED.indexOf(pathSegments[0]) !== -1 ? pathSegments[0] : null;
            var storedLang = localStorage.getItem('lang');

            if (urlLang && storedLang && SUPPORTED.indexOf(storedLang) !== -1 && urlLang !== storedLang) {
                var newSegments = pathSegments.slice(1);
                var newPath = '/' + [storedLang].concat(newSegments).join('/');
                window.location.replace(newPath + window.location.search + window.location.hash);
                return;
            }
 
            var lang = urlLang || storedLang;
 
            if (lang && SUPPORTED.indexOf(lang) !== -1) {
                document.documentElement.lang = lang;
 
                if (urlLang && storedLang !== urlLang) {
                    localStorage.setItem('lang', urlLang);
                }

                var cookieMatch = document.cookie.split('; ').some(function (row) {
                    return row === 'lang=' + lang || row.startsWith('lang=' + lang + ';');
                });
                if (!cookieMatch) {
                    var expires = new Date(Date.now() + 365 * 24 * 60 * 60 * 1000).toUTCString();
                    document.cookie = 'lang=' + lang + '; expires=' + expires + '; path=/; SameSite=Lax';
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

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .app-layout {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .main-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            min-width: 0;
        }

        header {
            flex-shrink: 0;
            z-index: 30;
            position: sticky;
            top: 0;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1rem;
        }

        main::-webkit-scrollbar {
            width: 6px;
        }

        main::-webkit-scrollbar-track {
            background: transparent;
        }

        main::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }

        .dark main::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        @media (min-width: 1024px) {
            .dashboard-container {
                display: grid;
                grid-template-columns: 1fr 380px;
                gap: 1.5rem;
                align-items: start;
            }

            .sidebar-column {
                position: sticky;
                top: 20px;
                align-self: start;
                max-height: calc(100vh - 120px);
                overflow-y: auto;
            }

            .sidebar-column::-webkit-scrollbar {
                width: 4px;
            }

            .sidebar-column::-webkit-scrollbar-thumb {
                background: #d1d5db;
                border-radius: 4px;
            }

            .dark .sidebar-column::-webkit-scrollbar-thumb {
                background: #4b5563;
            }
        }

        @media (max-width: 1023px) {
        header {
            position: absolute;
            width: 100%;
        }
        main {
            padding-top: 70px !important;
            padding-bottom: calc(70px + env(safe-area-inset-bottom, 0px)) !important;
        }
    }

        #mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 30;
            height: 70px;
            padding-bottom: env(safe-area-inset-bottom, 0);
            pointer-events: none;
        }


    </style>

    @yield('meta')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@simplewebauthn/browser@10/dist/bundle/index.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        
    <!-- MapLibre GL JS -->
    <link rel="stylesheet" href="https://unpkg.com/maplibre-gl@4.7.0/dist/maplibre-gl.css" />
    <script src="https://unpkg.com/maplibre-gl@4.7.0/dist/maplibre-gl.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">



    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    @php
        $viteEntries = [
            'resources/css/app.css',
            'resources/js/app.js',
            'resources/js/alert.js',
            'resources/js/translate.js',
        ];

        if (auth()->check() && in_array(auth()->user()->role, ['admin', 'dosen'], true)) {
            $viteEntries[] = 'resources/js/admin.js';
        }
    @endphp

    @vite($viteEntries)
</head>

<body class="bg-gray-50 dark:bg-gray-800 antialiased">

    @if (session('success') || session('error'))
        <div id="flash-message" class="hidden" data-success="{{ session('success') }}" data-error="{{ session('error') }}">
        </div>
    @endif

    @if(!request()->has('skip_splash') && empty($_COOKIE['splash_shown']))
        @include('components.splash')
    @endif

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity duration-300">
    </div>

    <div class="app-layout">
        @include('components.sidebar')

        <div class="main-column">
            @include('components.header')

            <main class="overflow-auto">
                <div class="grow">
                    @yield('content')
                </div>
                @include('components.footer')
            </main>

            @include('components.navigation_mahasiswa_mobile')
        </div>
    </div>

    @hasSection('show_up_page')
        @include('components.up-page')
    @endif
    
    @include('components.chat-bot')

</body>

</html>