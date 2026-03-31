@extends('Layout.Layout')
@section('title', 'Method Not Allowed')
@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full space-y-8 text-center">
            <!-- Error Icon -->
            <div class="flex justify-center">
                <div class="relative">
                    <div class="absolute inset-0 bg-pink-400 rounded-full opacity-20 blur-2xl"></div>
                    <svg class="relative w-24 h-24 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4v2m0 6v2M3 7.5a9 9 0 1118 0" />
                    </svg>
                </div>
            </div>

            <!-- Error Code -->
            <div>
                <h1 class="text-6xl font-bold text-gray-900 dark:text-white mb-2">405</h1>
                <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-4">Method Not Allowed</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Metode HTTP yang Anda gunakan tidak diizinkan untuk halaman ini. Silakan coba lagi atau hubungi
                    administrator.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ url('/') }}"
                    class="w-full block px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                    Kembali ke Beranda
                </a>
                <button onclick="window.history.back()"
                    class="w-full px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    Kembali
                </button>
            </div>

            <!-- Support -->
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Butuh bantuan?
                    <a href="mailto:support@example.com" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                        Hubungi Support
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection