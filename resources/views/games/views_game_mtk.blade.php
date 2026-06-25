@extends('Layout.Layout')

@section('title', 'Game Matematika')

@section('content')
<div id="math-game-container"
    data-postingan-id="{{ $postingan->id_postingan ?? '' }}"
    data-game-id="{{ $game->id_games ?? '' }}"
    data-get-score-url="{{ route('game.getHighestScore') }}"
    data-save-score-url="{{ route('game.saveScore') }}"
    data-leaderboard-url="{{ route('game.leaderboard', ['locale' => app()->getLocale()]) }}"
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-400 to-teal-600 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="w-full max-w-3xl mx-auto px-4">
        <div class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm rounded-2xl shadow-2xl overflow-hidden p-6 md:p-8 border border-white/20 dark:border-gray-700">
            
            <!-- Header Game -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 dark:from-teal-400 dark:to-cyan-400 bg-clip-text text-transparent">
                    Game Matematika
                </h1>
                <div class="flex gap-3">
                    <!-- Pilih Operasi -->
                    <select id="operatorSelect" class="px-3 py-1.5 rounded-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm font-medium">
                        <option value="+">➕ Game Matematika
                </h1>
                <div class="flex gap-3">
                    <!-- Pilih Operasi -->
                   <select id="operatorSelect"
                        class="px-3 py-1.5 rounded-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm font-medium">
                        <option value="+">➕ Tambah</option>
                        <option value="-">➖ Kurang</option>
                        <option value="*">✖️ Kali</option>
                        <option value="/">➗ Bagi</option>
                    </select>
                    <!-- Pilih Level -->
                    <select id="levelSelect" class="px-3 py-1.5 rounded-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm font-medium">
                        <option value="easy">🌱 Mudah</option>
                        <option value="medium" selected>⚡ Sedang</option>
                        <option value="hard">🔥 Sulit</option>
                    </select>
                    <!-- Tombol Reset Game -->
                    <button id="resetGameBtn" class="px-3 py-1.5 rounded-full bg-red-500 hover:bg-red-600 text-white text-sm shadow-md transition">
                        ↻ Ulang
                    </button>
                </div>
            </div>
            
            <!-- Timer & Progress -->
            <div class="mb-6">
                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                    <span>Waktu tersisa</span>
                    <span id="timerDisplay">02:00</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div id="timerProgress" class="bg-teal-500 h-2 rounded-full transition-all duration-1000" style="width: 100%"></div>
                </div>
            </div>
            
            <!-- Soal -->
            <div class="flex items-center justify-center gap-4 sm:gap-8 mb-8 flex-wrap">
                <div id="num1" class="w-28 h-28 md:w-36 md:h-36 flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-5xl md:text-6xl font-bold rounded-2xl shadow-inner text-gray-900 dark:text-white">0</div>
                <div id="operatorSymbol" class="text-5xl md:text-6xl font-bold text-teal-600 dark:text-teal-400">+</div>
                <div id="num2" class="w-28 h-28 md:w-36 md:h-36 flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-5xl md:text-6xl font-bold rounded-2xl shadow-inner text-gray-900 dark:text-white">0</div>
                <div class="text-4xl font-bold text-gray-400">=</div>
                <div class="w-28 h-28 md:w-36 md:h-36 flex items-center justify-center">
                    <input type="number" id="answerInput" class="w-full h-full text-center text-3xl md:text-4xl font-bold rounded-2xl border-2 border-teal-300 dark:border-teal-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-4 focus:ring-teal-300" placeholder="?">
                </div>
            </div>
            
            <!-- Tombol Submit & Statistik -->
            <div class="flex flex-col items-center gap-4">
                <button id="submitBtn" class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-full text-lg font-semibold shadow-lg transform transition hover:scale-105">
                    CEK JAWABAN
                </button>
                
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center w-full max-w-md mt-2">
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-xl p-2">
                        <div class="text-xs text-gray-500">Skor</div>
                        <div id="scoreValue" class="text-2xl font-bold text-teal-600">0</div>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-xl p-2">
                        <div class="text-xs text-gray-500">Benar</div>
                        <div id="correctCount" class="text-2xl font-bold text-green-600">0</div>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-xl p-2">
                        <div class="text-xs text-gray-500">Salah</div>
                        <div id="wrongCount" class="text-2xl font-bold text-red-600">0</div>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-xl p-2">
                        <div class="text-xs text-gray-500">Akurasi</div>
                        <div id="accuracy" class="text-2xl font-bold text-indigo-600">0%</div>
                    </div>
                </div>
                
                <div id="bestScoreInfo" class="text-sm text-gray-600 dark:text-gray-400 mt-2"></div>
                <div id="resultMessage" class="text-lg font-medium mt-2"></div>
            </div>
        </div>
    </div>
</div>
@endsection