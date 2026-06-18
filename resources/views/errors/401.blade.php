@extends('errors.layout')
@section('title', 'Unauthorized')
@section('error-content')
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full space-y-8 text-center">
            <!-- Error Icon -->
            <div class="flex justify-center">
                <div class="relative">
                    <div class="absolute inset-0 bg-orange-400 rounded-full opacity-20 blur-2xl"></div>
                    <svg class="relative w-24 h-24 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>

            <!-- Error Code -->
            <div>
                <h1 class="text-6xl font-bold text-gray-900 dark:text-white mb-2">401</h1>
                <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-4">Unauthorized</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Anda harus login untuk mengakses halaman ini. Silakan login atau hubungi support jika Anda membutuhkan bantuan.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('login') }}"
                    class="w-full block px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                    Masuk
                </a>
                <a href="{{ url('/') }}"
                    class="w-full block px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Support -->
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Jika Anda yakin ini adalah kesalahan, silakan hubungi support kami.
                 <a href="{{ route('help', app()->getLocale()) }}"
                    class="text-indigo-600 dark:text-indigo-400 hover:underline">
                        Hubungi Support
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection