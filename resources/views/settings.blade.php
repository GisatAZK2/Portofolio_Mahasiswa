
@extends('Layout.Layout')
@section('title', 'settings')

@section('content')
<div class="bg-gray-100 dark:bg-gray-700 rounded-2xl shadow-inner px-8 py-16 flex flex-col min-h-full flex-1">

    <!-- Form -->
    <div class="max-w-4/5 space-y-10">
        <!-- Theme -->
        <div class="flex items-center gap-6">
            <label class="font-medium w-20 text-black dark:text-white">
                Themes:
            </label>
            <select id="themeSelect" class="flex-1 text-black dark:bg-gray-600 dark:text-white px-6 py-4 rounded-2xl border border-gray-400 font-medium bg-gray-200 focus:outline-none">
                <option class="dark:bg-gray-800 dark:text-gray-200" value="light">Light Theme</option>
                <option class="dark:bg-gray-800 dark:text-gray-200" value="dark">Dark Theme</option>
            </select>
        </div>

        <!-- Language -->
        <div class="flex items-center gap-6 text-black dark:text-white">
            <label class="font-medium w-20">
                Language:
            </label>
            <select class="text-black dark:text-white dark:bg-gray-600 flex-1 px-6 py-4 rounded-2xl border border-gray-400 font-medium bg-gray-200 focus:outline-none" disabled>
                <option>-- W.I.P --</option>
                <option class="dark:bg-gray-800 dark:text-gray-200">Bahasa Indonesia</option>
                <option class="dark:bg-gray-800 dark:text-gray-200">English</option>
            </select>
        </div>
    </div>

    <!-- Buttons -->
    <div class="mt-auto flex justify-end gap-6 pt-5">
        <button id="saveTheme" class="px-4 py-2 font-medium rounded-2xl dark:text-gray-200 border border-gray-500 dark:border-gray-900 dark:bg-gray-800 bg-gray-200 hover:bg-gray-300 dark:hover:bg-gray-700 transition">
            Simpan Perubahan
        </button>

        <button value="cancelTheme" class="px-4 py-2 font-medium rounded-2xl dark:text-gray-200 border border-gray-500 dark:border-gray-900 dark:bg-gray-800 bg-gray-200 hover:bg-gray-300 dark:hover:bg-gray-700 transition">
            Batal
        </button>
    </div>

</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const select = document.getElementById('themeSelect');
    const html = document.documentElement;
    const saveBtn = document.getElementById('saveTheme');
    const cancelBtn = document.getElementById('cancelTheme');

    // Ambil theme yang sedang aktif
    const currentTheme = localStorage.getItem('theme') || 'light';

    // Set dropdown sesuai theme sekarang
    select.value = currentTheme;

    // === SAVE BUTTON ===
    saveBtn.addEventListener('click', function () {

        const selectedTheme = select.value;

        if (selectedTheme === 'dark') {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }

    });

    // === CANCEL BUTTON ===
    cancelBtn.addEventListener('click', function () {

        // Kembalikan dropdown ke theme lama
        select.value = currentTheme;

    });

});
</script>
@endpush
@endsection