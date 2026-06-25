@extends('Layout.Layout')

@section('title', 'Game TTS')

@section('content')
    <div <div id="tts-game-container"
    data-postingan-id="{{ $postingan->id_postingan ?? '' }}"
    data-get-score-url="{{ route('game.getHighestScore') }}"
    data-save-score-url="{{ route('game.saveScore') }}"
    data-leaderboard-url="{{ route('game.leaderboard', ['locale' => app()->getLocale()]) }}"
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-400 to-green-600 dark:from-gray-900 dark:to-gray-800 py-8">
        <div class="w-full max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl overflow-hidden p-6 md:p-8">
                <div class="flex items-center justify-between mb-4 flex-wrap gap-4">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        Teka Teki Silang
                    </h1>
                    <div class="text-sm">
                        <div id="timer" class="font-medium text-gray-900 dark:text-gray-100">Waktu tersisa: <span id="timer-seconds">300</span>s</div>
                        <div id="questions-left" class="text-xs text-gray-600 dark:text-gray-400">Sisa soal: <span id="remaining-count">5</span></div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Crossword Grid -->
                    <div class="flex justify-center">
                        <div id="crossword-container" class="grid gap-1 bg-gray-200 dark:bg-gray-700 p-2 rounded-lg" style="grid-template-columns: repeat(5, 60px);">
                            <!-- Crossword grid will be generated here -->
                        </div>
                    </div>

                    <!-- Questions -->
                    <div>
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Pertanyaan Mendatar</h3>
                            <div id="horizontal-questions" class="space-y-2"></div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Pertanyaan Menurun</h3>
                            <div id="vertical-questions" class="space-y-2"></div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-center mt-6">
                    <div id="selected-question" class="text-sm text-gray-700 dark:text-gray-300 mb-2"></div>
                    <input id="answer" type="text" 
                        class="w-full sm:w-96 h-12 text-center text-lg rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400"
                        placeholder="Masukkan jawaban">

                    <button id="checkBtn"
                        class="mt-4 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-full shadow-md">CEK JAWABAN</button>

                    <div id="result" class="mt-4 text-gray-800 dark:text-gray-200 text-lg"></div>
                    <div class="mt-2 text-gray-800 dark:text-gray-200"> Skor : <span
                            id="score">0</span></div>
                    <div class="mt-1 text-xs text-gray-600 dark:text-gray-400" id="bestScoreInfo"></div>
                </div>
            </div>
        </div>
    </div>
@endsection