@extends('Layout.Layout')

@section('title', autoTranslate('Kelola Passkey'))

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6">
    <!-- Header Section -->
    <div class="mb-8 text-center sm:text-left">
        <div class="flex items-center justify-center sm:justify-start gap-3 mb-2">
            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                {{ autoTranslate('Kelola Passkey') }}
            </h1>
        </div>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1 max-w-2xl">
            {{ autoTranslate('Tambahkan atau hapus passkey untuk verifikasi keamanan dua faktor') }}
        </p>
    </div>

    <!-- Info Banner -->
    <div class="mb-8 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-100 dark:border-blue-800/50 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-gray-800 dark:text-gray-200 text-sm">
                    {{ autoTranslate('Apa itu Passkey?') }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ autoTranslate('Passkey adalah metode autentikasi modern yang menggantikan password dengan biometrik (Face ID, Touch ID) atau kunci keamanan fisik. Lebih aman dan mudah digunakan.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Alert Container for dynamic messages -->
    <div id="alertContainer" class="mb-6"></div>

    <!-- Daftar Passkeys -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <h2 class="font-semibold text-gray-800 dark:text-gray-200">
                        {{ autoTranslate('Passkey Terdaftar') }}
                    </h2>
                </div>
                <span class="text-xs px-2 py-1 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    {{ autoTranslate('Milik Anda') }}
                </span>
            </div>
        </div>
        
        <div id="passkeysList" class="divide-y divide-gray-100 dark:divide-gray-700">
            <!-- Loading skeleton will appear here -->
            <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-8 h-8 animate-spin text-gray-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p>{{ autoTranslate('Memuat data...') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambah Passkey Baru -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <h2 class="font-semibold text-gray-800 dark:text-gray-200">
                    {{ autoTranslate('Tambah Passkey Baru') }}
                </h2>
            </div>
        </div>
        
        <div class="p-6">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ autoTranslate('Nama Perangkat') }}
                </label>
                <input type="text" 
                       id="passkeyName" 
                       placeholder="{{ autoTranslate('Contoh: iPhone 15, MacBook Pro, Kunci Keamanan') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm transition-all duration-200">
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    {{ autoTranslate('Nama yang mudah diingat untuk mengenali perangkat ini') }}
                </p>
            </div>

            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="text-xs text-gray-600 dark:text-gray-400">
                        {{ autoTranslate('Passkey akan menggunakan autentikasi bawaan perangkat Anda (Face ID, Touch ID, Windows Hello) atau kunci keamanan eksternal jika tersedia.') }}
                    </span>
                </div>
            </div>

            <button type="button" 
                    id="addPasskeyBtn"
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-2.5 rounded-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md text-sm">
                {{ autoTranslate('Tambah Passkey') }}
            </button>
        </div>
    </div>
</div>
@endsection