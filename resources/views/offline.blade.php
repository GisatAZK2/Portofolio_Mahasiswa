@extends('Layout.Layout')

@section('title', 'Offline - Portofolio Mahasiswa')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center px-4">
    <div class="text-center max-w-md mx-auto">
        <div class="text-8xl mb-6 animate-bounce">
            📡
        </div>
        <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-700 to-gray-500 dark:from-gray-300 dark:to-gray-500 bg-clip-text text-transparent mb-4">
            Anda Offline
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mb-8">
            Sepertinya Anda tidak terhubung ke internet.<br>
            Silakan periksa koneksi Anda dan coba lagi.
        </p>
        
        <div class="space-y-4">
            <button onclick="location.reload()" 
                class="w-full px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors shadow-lg">
                🔄 Coba Lagi
            </button>
            
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" 
                class="inline-block w-full px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                🏠 Kembali ke Beranda
            </a>
        </div>
        
        <!-- Saved content indicator -->
        <div class="mt-8 text-xs text-gray-400 dark:text-gray-500">
            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Beberapa konten mungkin tersedia offline
        </div>
    </div>
</div>
@endsection