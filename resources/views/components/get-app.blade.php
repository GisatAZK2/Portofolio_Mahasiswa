@extends('Layout.Layout')

@section('title', 'Get the App')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8 md:py-12">
    <div class="container mx-auto px-4 max-w-5xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-2" data-translate="get_app_title" data-translate-page="get_app">Get the App</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm" data-translate="get_app_desc" data-translate-page="get_app">Install aplikasi Mahasiswa di perangkat Anda</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column - Install Instructions -->
            <div class="space-y-4">
                <!-- Android -->
                <div class="get-app-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-6 h-6">
                            <svg viewBox="-22.5 0 301 301" version="1.1" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" class="w-full h-full">
                                <g>
                                    <path d="M78.3890161,0.858476242 C76.9846593,0.871877584 75.5269206,1.21067383 74.1988355,1.94683705 C69.9813154,4.28464966 68.4344792,9.70448752 70.7705059,13.9187887 L80.2936432,31.1148585 C57.3501835,45.3109605 42.146676,69.5583356 42.146676,97.23264 C42.146676,97.3488107 42.1463538,97.5233203 42.146676,97.6951925 C42.1467894,97.7558421 42.1461099,97.7904107 42.146676,97.8584397 C42.1467112,97.9488816 42.146676,98.0809536 42.146676,98.1033235 L42.146676,102.37513 C37.7401995,97.3051619 31.2627337,94.103607 24.0255064,94.103607 C10.766574,94.103607 0,104.870185 0,118.129121 L0,192.137501 C0,205.396437 10.766574,216.163015 24.0255064,216.163015 C31.2627337,216.163015 37.7401995,212.96146 42.146676,207.891492 L42.146676,218.258109 C42.146676,232.234601 53.5833566,243.671281 67.5598484,243.671281 L74.0083724,243.671281 L74.0083724,276.594135 C74.0083724,289.853131 84.774955,300.619649 98.0338856,300.619649 C111.292821,300.619649 122.0594,289.853131 122.0594,276.594135 L122.0594,243.671281 L133.215081,243.671281 L133.215081,276.594135 C133.215081,289.853131 143.981659,300.619649 157.240595,300.619649 C170.499522,300.619649 181.266118,289.853131 181.266118,276.594135 L181.266118,243.671281 L187.714637,243.671281 C201.691129,243.671281 213.127809,232.234601 213.127809,218.258109 L213.127809,207.891492 C217.534299,212.96146 224.011752,216.163015 231.248984,216.163015 C244.507919,216.163015 255.274498,205.396437 255.274498,192.137501 L255.274498,118.129121 C255.274498,104.870185 244.507919,94.103607 231.248984,94.103607 C224.011752,94.103607 217.534299,97.3051619 213.127809,102.37513 L213.127809,98.1849514 L213.127809,98.1033407 C213.128367,97.9723769 213.127955,97.8421262 213.127809,97.8584655 C213.129527,97.5976548 213.127809,97.3898395 213.127809,97.2326572 C213.127809,69.5631979 197.890397,45.339215 174.95363,31.1420821 L184.503985,13.918763 C186.840011,9.70446174 185.293178,4.28462389 181.075655,1.94681128 C179.747565,1.21064805 178.289834,0.871868993 176.885477,0.85845047 C173.770979,0.828641074 170.714038,2.4700306 169.103704,5.37514094 L159.118011,23.4146964 C149.353914,19.811505 138.730068,17.8368515 127.637245,17.8368515 C116.555726,17.8368515 105.912363,19.7912913 96.1564693,23.3874813 L86.1707769,5.37514094 C84.5604527,2.47002201 81.503506,0.828709799 78.3890161,0.85845047 L78.3890161,0.858476242 Z" fill="#FFFFFF"></path>
                                    <path d="M24.0260725,100.361664 C14.1317,100.361664 6.25861893,108.234747 6.25861893,118.129121 L6.25861893,192.137501 C6.25861893,202.031875 14.1317,209.904958 24.0260725,209.904958 C33.9204441,209.904958 41.7935257,202.031875 41.7935257,192.137501 L41.7935257,118.129121 C41.7935257,108.234747 33.9204441,100.361664 24.0260725,100.361664 L24.0260725,100.361664 Z M231.249551,100.361664 C221.355176,100.361664 213.482094,108.234747 213.482094,118.129121 L213.482094,192.137501 C213.482094,202.031875 221.355176,209.904958 231.249551,209.904958 C241.143925,209.904958 249.016999,202.031875 249.016999,192.137501 L249.016999,118.129121 C249.016999,108.234747 241.143925,100.361664 231.249551,100.361664 L231.249551,100.361664 Z" fill="#A4C639"></path>
                                    <path d="M98.0338856,184.818075 C88.1395114,184.818075 80.2664341,192.691157 80.2664341,202.585531 L80.2664341,276.593963 C80.2664341,286.488363 88.1395114,294.361308 98.0338856,294.361308 C107.92826,294.361308 115.801342,286.488363 115.801342,276.593963 L115.801342,202.585531 C115.801342,192.691157 107.92826,184.818075 98.0338856,184.818075 L98.0338856,184.818075 Z M157.240595,184.818075 C147.346221,184.818075 139.473138,192.691157 139.473138,202.585531 L139.473138,276.593963 C139.473138,286.488363 147.346221,294.361308 157.240595,294.361308 C167.134969,294.361308 175.008043,286.488363 175.008043,276.593963 L175.008043,202.585531 C175.008043,192.691157 167.134969,184.818075 157.240595,184.818075 L157.240595,184.818075 Z" fill="#A4C639"></path>
                                    <path d="M78.4434341,7.11654228 C78.0234231,7.12083758 77.6320498,7.22919946 77.2462398,7.44304537 C75.9792855,8.14533584 75.5626532,9.60121987 76.2667168,10.8713836 L88.782836,33.4820338 C64.7023936,46.0117562 48.4373365,69.8232526 48.4047377,97.1510121 L206.869751,97.1510121 C206.837193,69.8232526 190.572096,46.0117562 166.491645,33.4820338 L179.007777,10.8713836 C179.711837,9.60121987 179.295201,8.14533584 178.02825,7.44304537 C177.642438,7.22919946 177.251067,7.1205455 176.831055,7.11654228 C175.931919,7.10786577 175.079646,7.55712 174.599912,8.42257181 L161.920533,31.2781058 C151.548297,26.6773219 139.914231,24.0949434 127.637245,24.0949434 C115.360249,24.0949434 103.726174,26.6773219 93.3539479,31.2781058 L80.6745686,8.42257181 C80.1948375,7.55712 79.3425576,7.10791732 78.4434341,7.11654228 L78.4434341,7.11654228 Z M48.4047377,103.40907 L48.4047377,218.258109 C48.4047377,228.870039 56.9479173,237.413214 67.5598484,237.413214 L187.714637,237.413214 C198.326576,237.413214 206.869751,228.870039 206.869751,218.258109 L206.869751,103.40907 L48.4047377,103.40907 L48.4047377,103.40907 Z" fill="#A4C639"></path>
                                    <path d="M91.0681772,54.9226953 C87.4507168,54.9226953 84.4563973,57.9170105 84.4563973,61.5344795 C84.4563973,65.1519399 87.4507168,68.146255 91.0681772,68.146255 C94.6856376,68.146255 97.6799528,65.1519399 97.6799528,61.5344795 C97.6799528,57.9170105 94.6856376,54.9226953 91.0681772,54.9226953 L91.0681772,54.9226953 Z M164.205874,54.9226953 C160.588413,54.9226953 157.59409,57.9170105 157.59409,61.5344795 C157.59409,65.1519399 160.588413,68.146255 164.205874,68.146255 C167.823326,68.146255 170.817649,65.1519399 170.817649,61.5344795 C170.817649,57.9170105 167.823326,54.9226953 164.205874,54.9226953 L164.205874,54.9226953 Z" fill="#FFFFFF"></path>
                                </g>
                            </svg>
                        </div>
                        <h2 class="font-semibold text-gray-800 dark:text-white" data-translate="android" data-translate-page="get_app">Android</h2>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400 ml-2">
                        <li class="flex gap-2" data-translate="android_step_1" data-translate-page="get_app">1. Buka dengan Chrome</li>
                        <li class="flex gap-2" data-translate="android_step_2" data-translate-page="get_app">2. Tap menu ⋮ → "Install App"</li>
                        <li class="flex gap-2" data-translate="android_step_3" data-translate-page="get_app">3. Tap "Install"</li>
                    </ul>
                </div>

                <!-- iOS -->
                <div class="get-app-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17.36 3 12.75 5.47 9.46c1.23-1.54 3.15-2.5 5-2.55 1.53-.05 2.98.86 3.92.86.93 0 2.67-1.06 4.5-.9.76.03 2.91.31 4.29 2.33-.11.07-2.56 1.5-2.53 4.48.03 2.58 2.26 3.44 2.28 3.45-.02.05-.36 1.22-1.18 2.42zM15.36 4.2c.78-.94 1.3-2.24 1.16-3.54-1.12.05-2.48.75-3.29 1.69-.72.84-1.35 2.18-1.18 3.47 1.25.1 2.52-.66 3.31-1.62z"/>
                        </svg>
                        <h2 class="font-semibold text-gray-800 dark:text-white" data-translate="ios" data-translate-page="get_app">iOS</h2>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400 ml-2">
                        <li class="flex gap-2" data-translate="ios_step_1" data-translate-page="get_app">1. Buka dengan Safari</li>
                        <li class="flex gap-2" data-translate="ios_step_2" data-translate-page="get_app">2. Tap icon Share</li>
                        <li class="flex gap-2" data-translate="ios_step_3" data-translate-page="get_app">3. Scroll → "Add to Home Screen"</li>
                        <li class="flex gap-2" data-translate="ios_step_4" data-translate-page="get_app">4. Tap "Add"</li>
                    </ul>
                </div>

                <!-- Desktop -->
                <div class="get-app-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-6 h-6">
                            <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 92.77" class="w-full h-full">
                                <path class="cls-1" d="M90.11,21.7h28.81a4,4,0,0,1,4,4V86.93a4,4,0,0,1-4,4H90.11a4,4,0,0,1-4-4V25.65a4,4,0,0,1,4-3.95ZM3,0H106.55a3.05,3.05,0,0,1,3,3v7.85h-4V7a2.69,2.69,0,0,0-2.69-2.69H6.67A2.69,2.69,0,0,0,4,7V61.87a2.7,2.7,0,0,0,2.68,2.69H76.1v11.6H3a3,3,0,0,1-3-3V3A3.05,3.05,0,0,1,3,0ZM41,80.36H68.59c.07,4.77,2,9,7.36,12.41H33.64C37.93,89.66,41,85.91,41,80.36Zm73.23-42.28h2.87v1.64h-2.87V38.08Zm-9.72,36.43a4.31,4.31,0,1,1-4.31,4.3,4.31,4.31,0,0,1,4.31-4.3Zm-13-40.18h26a.82.82,0,0,1,.82.82v4.47a.83.83,0,0,1-.82.83h-26a.82.82,0,0,1-.82-.82V35.15a.82.82,0,0,1,.82-.82Z"/>
                            </svg>
                        </div>
                        <h2 class="font-semibold text-gray-800 dark:text-white" data-translate="desktop" data-translate-page="get_app">Desktop</h2>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400 ml-2">
                        <li class="flex gap-2" data-translate="desktop_step_1" data-translate-page="get_app">1. Klik icon install di address bar</li>
                        <li class="flex gap-2" data-translate="desktop_step_2" data-translate-page="get_app">2. Klik "Install"</li>
                    </ul>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <!-- QR Code -->
                <div class="get-app-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 text-center">
                    <h2 class="font-semibold text-gray-800 dark:text-white mb-3" data-translate="scan_qr" data-translate-page="get_app">Scan QR Code</h2>
                    <div id="qrcode" class="flex justify-center"></div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3" data-translate="scan_qr_desc" data-translate-page="get_app">Scan dengan HP untuk install</p>
                </div>

                <!-- Install Button -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-500 rounded-xl shadow-md p-5 text-center text-white">
                    <button id="direct-install-btn" 
                        class="install-button w-full py-3 bg-white text-blue-600 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18v-5m0 0V8m0 5h5m-5 0H7m6 4v.01M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
                        </svg>
                        <span data-translate="install_button" data-translate-page="get_app">INSTALL</span>
                    </button>
                    <p class="text-xs text-white mt-3" data-translate="install_button_hint" data-translate-page="get_app">Atau dengan klik tombol install di atas</p>
                    <p class="text-xs text-white mt-3" data-translate="install_button_desc" data-translate-page="get_app">Klik untuk install langsung di perangkat Anda</p>
                </div>

                <!-- Features -->
                <div class="get-app-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <h3 class="font-semibold text-gray-800 dark:text-white mb-2" data-translate="keunggulan" data-translate-page="get_app">Keunggulan</h3>
                    <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center gap-2" data-translate="akses_cepat" data-translate-page="get_app">✓ Akses Cepat</div>
                        <div class="flex items-center gap-2" data-translate="tampilan_native" data-translate-page="get_app">✓ Tampilan native</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const qrcodeEl = document.getElementById('qrcode');
        if (!qrcodeEl || typeof QRCode === 'undefined') return;

        qrcodeEl.innerHTML = '';
        new QRCode(qrcodeEl, {
            text: '{{ url()->current() }}',
            width: 180,
            height: 180,
            colorDark: '#111827',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    });
</script>
@endsection