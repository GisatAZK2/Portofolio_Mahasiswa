@extends('Layout.Layout')

@section('title', 'Game Puzzle')

@section('content')
    <div  id="puzzle-game-container"
    data-postingan-id="{{ $postingan->id_postingan ?? '' }}"
    data-get-score-url="{{ route('game.getHighestScore') }}"
    data-save-score-url="{{ route('game.saveScore') }}"
    data-leaderboard-url="{{ route('game.leaderboard', ['locale' => app()->getLocale()]) }}"
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-400 to-purple-600 dark:from-gray-900 dark:to-gray-800 py-8">
        <div class="w-full max-w-2xl mx-auto">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl overflow-hidden p-6 md:p-8">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        Game Puzzle
                    </h1>
                    <div class="text-sm">
                        <div id="timer" class="font-medium text-gray-900 dark:text-gray-100">Waktu tersisa: <span id="timer-seconds">180</span>s</div>
                        <div id="moves" class="text-xs text-gray-600 dark:text-gray-400">Langkah: <span id="moves-count">0</span></div>
                    </div>
                </div>

                <div class="flex justify-center mb-6">
                    <div id="puzzle-container" class="grid grid-cols-3 gap-1 bg-gray-200 dark:bg-gray-700 p-2 rounded-lg" style="width: 400px; height: 400px;">
                        <!-- Puzzle tiles will be generated here -->
                    </div>
                </div>

                <div class="flex flex-col items-center">
                    <div class="flex gap-4 mb-4">
                        <button id="shuffleBtn"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-full shadow-md">ACAK</button>
                    </div>

                    <div id="result" class="mt-4 text-gray-800 dark:text-gray-200 text-lg"></div>
                    <div class="mt-2 text-gray-800 dark:text-gray-200">Skor: <span
                            id="score">0</span></div>
                    <div class="mt-1 text-xs text-gray-600 dark:text-gray-400" id="bestScoreInfo"></div>
                </div>
            </div>
        </div>
    </div>
@endsection