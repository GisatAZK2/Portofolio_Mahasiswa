@extends('errors.layout')
@section('title', 'Not Found')
@section('error-content')
    <!-- Error Icon -->
    <div class="flex justify-center">
        <div class="relative">
            <div class="absolute inset-0 bg-purple-400 rounded-full opacity-20 blur-2xl"></div>
            <svg class="relative w-24 h-24 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    <!-- Error Code -->
    <div>
        <h1 class="text-6xl font-bold text-gray-900 dark:text-white mb-2">404</h1>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-4">Page Not Found</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            Maaf, halaman yang Anda cari tidak ditemukan. Halaman mungkin telah dihapus atau URL yang Anda masukkan tidak
            benar.
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
            <a href="mailto:{{ config('support.email') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                Hubungi Support
            </a>
        </p>
    </div>
@endsection