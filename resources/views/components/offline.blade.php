@extends('Layout.Layout')

@section('title', 'Offline - Portofolio Mahasiswa')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center px-4">
    <div class="text-center max-w-md mx-auto">
        <div class="text-8xl mb-6 animate-bounce">
            📡
        </div>
        <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-700 to-gray-500 dark:from-gray-300 dark:to-gray-500 bg-clip-text text-transparent mb-4">
            {{ autoTranslate('Anda Offline') }}
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mb-8">
           {{ autoTranslate('Sepertinya Anda tidak terhubung ke internet. Silakan periksa koneksi Anda dan coba lagi.') }}
        </p>
        
        <div class="space-y-4">
            <button onclick="location.reload()" 
                class="w-full px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors shadow-lg">
                {{ autoTranslate('Muat Ulang') }}
            </button>
        </div>
        
    </div>
</div>


<script>
    function checkAndRedirect() {
        if (navigator.onLine) {
            const destination = document.referrer && !document.referrer.includes('offline') 
                                ? document.referrer 
                                : '{{ url('/') }}';
            window.location.href = destination;
        }
    }

    checkAndRedirect();

    window.addEventListener('online', checkAndRedirect);

    function handleReload() {
        if (navigator.onLine) {
            checkAndRedirect();
        } else {
            alert('{{ autoTranslate("Masih offline, nih. Cek koneksi kamu lagi ya!") }}');
        }
    }
</script>
@endsection


