@extends('errors.layout')
@section('title', 'Service Unavailable')
@section('error-content')
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full space-y-8 text-center">
            <!-- Error Icon -->
            <div class="flex justify-center">
                <div class="relative">
                    <div class="absolute inset-0 bg-gray-400 rounded-full opacity-20 blur-2xl"></div>
                    <svg class="relative w-24 h-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>

            <!-- Error Code -->
            <div>
                <h1 class="text-6xl font-bold text-gray-900 dark:text-white mb-2">503</h1>
                <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-4">Service Unavailable</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    {{ autoTranslate('Layanan kami sedang tidak tersedia untuk sementara waktu. Silakan coba lagi nanti atau hubungi support jika Anda membutuhkan bantuan.') }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <button onclick="window.location.reload()"
                    class="w-full px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                    {{ autoTranslate('Muat Ulang') }}
                </button>
                <a href="{{ url('/') }}"
                    class="w-full block px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    {{ autoTranslate('Kembali ke Beranda') }}
                </a>
            </div>

            <!-- Support -->
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ autoTranslate('Jika Anda yakin ini adalah kesalahan, silakan hubungi support kami.') }}
                    <a href="mailto:{{ config('support.email') }}"
                        class="text-indigo-600 dark:text-indigo-400 hover:underline">
                        {{ autoTranslate('Hubungi Support') }}
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection