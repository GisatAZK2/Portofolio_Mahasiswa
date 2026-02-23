@extends('Layout.Layout')

@section('content')
<div class=" items-center  py-8">
    <div class=" transition-colors">
        <div>
            <label class="block font-semibold mb-3 text-gray-700 dark:text-gray-200 text-lg">Tema</label>
            <div class="flex gap-4">
                <button id="theme-light" class="px-6 py-2 rounded-lg font-semibold bg-gray-100 dark:bg-gray-700 dark:text-white text-gray-800 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-400">Normal</button>
                <button id="theme-dark" class="px-6 py-2 rounded-lg font-semibold bg-gray-800 text-white dark:bg-gray-600 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-400">Gelap</button>
            </div>
        </div>
        <div>
            <label class="block w-100 font-semibold mb-3 text-gray-700 dark:text-gray-200 text-lg">Bahasa</label>
            <select class="px-4 py-2 rounded border bg-gray-100 dark:bg-gray-700 dark:text-white w-full" disabled>
                <option>Indonesia</option>
                <option>English</option>
            </select>
            <p class="text-xs text-gray-500 mt-2">(Fitur bahasa segera hadir)</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
import { setTheme, getTheme } from '/resources/js/utils/theme.js';

const btnLight = document.getElementById('theme-light');
const btnDark = document.getElementById('theme-dark');

function updateActiveThemeButton() {
    if(getTheme() === 'dark') {
        btnDark.classList.add('ring', 'ring-blue-500', 'bg-gray-800', 'text-white');
        btnLight.classList.remove('ring', 'ring-blue-500', 'bg-gray-200');
    } else {
        btnLight.classList.add('ring', 'ring-blue-500', 'bg-gray-100', 'text-gray-800');
        btnDark.classList.remove('ring', 'ring-blue-500', 'bg-gray-800');
    }
}

btnLight.addEventListener('click', () => {
    setTheme('light');
    updateActiveThemeButton();
});
btnDark.addEventListener('click', () => {
    setTheme('dark');
    updateActiveThemeButton();
});

updateActiveThemeButton();
</script>
@endpush
