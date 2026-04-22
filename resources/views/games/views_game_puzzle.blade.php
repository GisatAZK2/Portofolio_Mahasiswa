@extends('Layout.Layout')

@section('title', 'Game Puzzle')

@section('content')
    <div class="min-h-[70vh] flex items-center justify-center bg-gradient-to-br from-purple-400 to-purple-600 dark:from-gray-900 dark:to-gray-800 py-8">
        <div class="w-full max-w-2xl mx-auto">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl overflow-hidden p-6 md:p-8">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ autoTranslate('Game Puzzle') }}
                    </h1>
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        <div id="timer" class="font-medium">{{ autoTranslate('Waktu tersisa:') }} 180s</div>
                        <div id="moves" class="text-xs text-gray-500">{{ autoTranslate('Langkah:') }} 0</div>
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
                            class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-full shadow-md">{{ autoTranslate('ACAK') }}</button>
                        <button id="resetBtn"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-full shadow-md">{{ autoTranslate('RESET') }}</button>
                    </div>

                    <div id="result" class="mt-4 text-gray-800 dark:text-gray-200 text-lg"></div>
                    <div class="mt-2 text-gray-700 dark:text-gray-300">{{ autoTranslate('Skor') }}: <span
                            id="score">0</span></div>
                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="bestScoreInfo"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const CONFIG = {
                SIZE: 3,
                TILE_COUNT: 9,
                EMPTY_INDEX: 8,
                timeLimit: 180,
                baseScore: 1000,
                movesPenalty: 10
            };

            let tiles = [];
            let moves = 0;
            let score = 0;
            let gameActive = true;
            let startTime = Date.now();
            let timerInterval = null;
            let gameStart = Date.now();
            let currentGameId = null;
            let highestScore = 0;
            
            const postinganId = @json($postingan->id_postingan ?? null);
            const storageKey = `puzzle_state_postingan_${postinganId}`;
            
            const container = document.getElementById('puzzle-container');
            const movesEl = document.getElementById('moves');
            const scoreEl = document.getElementById('score');
            const timerEl = document.getElementById('timer');
            const resultEl = document.getElementById('result');
            const bestScoreInfoEl = document.getElementById('bestScoreInfo');
            
            // Initialize puzzle
            function initTiles() {
                tiles = [];
                for (let i = 0; i < CONFIG.TILE_COUNT - 1; i++) {
                    tiles.push(i + 1);
                }
                tiles.push(null); // empty tile
                return tiles;
            }
            
            function shuffleTiles() {
                // Fisher-Yates shuffle
                for (let i = tiles.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [tiles[i], tiles[j]] = [tiles[j], tiles[i]];
                }
                // Ensure puzzle is solvable
                if (!isSolvable()) {
                    // Swap first two non-empty tiles
                    for (let i = 0; i < tiles.length - 1; i++) {
                        if (tiles[i] !== null && tiles[i + 1] !== null) {
                            [tiles[i], tiles[i + 1]] = [tiles[i + 1], tiles[i]];
                            break;
                        }
                    }
                }
            }
            
            function isSolvable() {
                let inversions = 0;
                const flatTiles = tiles.filter(t => t !== null);
                for (let i = 0; i < flatTiles.length; i++) {
                    for (let j = i + 1; j < flatTiles.length; j++) {
                        if (flatTiles[i] > flatTiles[j]) inversions++;
                    }
                }
                const emptyRow = Math.floor(tiles.indexOf(null) / CONFIG.SIZE);
                return (inversions % 2 === 0) === (CONFIG.SIZE % 2 !== 0 || emptyRow % 2 === 0);
            }
            
            function render() {
                container.innerHTML = '';
                const tileSize = 400 / CONFIG.SIZE;
                
                tiles.forEach((tile, index) => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center justify-center bg-gradient-to-br from-purple-500 to-purple-600 text-white font-bold rounded-lg shadow-md cursor-pointer transition-transform hover:scale-105';
                    div.style.width = `${tileSize - 4}px`;
                    div.style.height = `${tileSize - 4}px`;
                    div.style.fontSize = `${tileSize / 3}px`;
                    
                    if (tile === null) {
                        div.className = 'bg-gray-300 dark:bg-gray-600 rounded-lg';
                        div.innerHTML = '';
                    } else {
                        div.innerHTML = tile;
                    }
                    
                    div.addEventListener('click', () => moveTile(index));
                    container.appendChild(div);
                });
            }
            
            function moveTile(clickedIndex) {
                if (!gameActive) return;
                
                const emptyIndex = tiles.indexOf(null);
                const clickedRow = Math.floor(clickedIndex / CONFIG.SIZE);
                const clickedCol = clickedIndex % CONFIG.SIZE;
                const emptyRow = Math.floor(emptyIndex / CONFIG.SIZE);
                const emptyCol = emptyIndex % CONFIG.SIZE;
                
                const isAdjacent = (Math.abs(clickedRow - emptyRow) + Math.abs(clickedCol - emptyCol)) === 1;
                
                if (isAdjacent) {
                    // Swap tiles
                    [tiles[clickedIndex], tiles[emptyIndex]] = [tiles[emptyIndex], tiles[clickedIndex]];
                    moves++;
                    updateScore();
                    movesEl.innerText = `{{ autoTranslate('Langkah:') }} ${moves}`;
                    render();
                    
                    if (isComplete()) {
                        finishGame(true);
                    }
                }
            }
            
            function updateScore() {
                const timeElapsed = Math.floor((Date.now() - startTime) / 1000);
                const timeBonus = Math.max(0, CONFIG.timeLimit - timeElapsed) * 5;
                const movesPenalty = moves * CONFIG.movesPenalty;
                score = Math.max(0, CONFIG.baseScore + timeBonus - movesPenalty);
                scoreEl.innerText = score;
            }
            
            function isComplete() {
                for (let i = 0; i < CONFIG.TILE_COUNT - 1; i++) {
                    if (tiles[i] !== i + 1) return false;
                }
                return tiles[CONFIG.TILE_COUNT - 1] === null;
            }
            
            async function fetchHighestScore() {
                try {
                    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const response = await fetch('{{ route('game.getHighestScore') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify({
                            id_postingan: postinganId,
                            game_name: 'Puzzle'
                        }),
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        highestScore = data.highest_score || 0;
                        if (bestScoreInfoEl && highestScore > 0) {
                            bestScoreInfoEl.innerHTML = `🏆 {{ autoTranslate('Skor tertinggi Anda:') }} ${highestScore}`;
                        } else if (bestScoreInfoEl) {
                            bestScoreInfoEl.innerHTML = `🎯 {{ autoTranslate('Selesaikan puzzle dengan langkah sedikit!') }}`;
                        }
                    }
                } catch (err) {
                    console.error('Error fetching highest score:', err);
                }
            }
            
            async function sendScore(isFinal = false) {
                if (score <= highestScore && isFinal) {
                    return false;
                }
                
                try {
                    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const payload = {
                        id_games: currentGameId,
                        id_postingan: postinganId,
                        score: parseInt(score, 10),
                        playing_time: getPlayingTime(),
                        game_name: 'Puzzle',
                        moves: moves
                    };
                    
                    const res = await fetch('{{ route('game.saveScore') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify(payload),
                    });
                    
                    if (!res.ok) return false;
                    
                    const data = await res.json();
                    if (data.id_games) currentGameId = data.id_games;
                    
                    if (score > highestScore) {
                        highestScore = score;
                        if (bestScoreInfoEl) {
                            bestScoreInfoEl.innerHTML = `🏆 {{ autoTranslate('Skor tertinggi Anda:') }} ${highestScore} ✨ {{ autoTranslate('Rekor Baru!') }}`;
                        }
                    }
                    
                    return true;
                } catch (err) {
                    console.error('Error sending score:', err);
                    return false;
                }
            }
            
            function getPlayingTime() {
                const seconds = Math.floor((Date.now() - gameStart) / 1000);
                return seconds + 's';
            }
            
            function timeRemainingSeconds() {
                const elapsed = Math.floor((Date.now() - startTime) / 1000);
                return Math.max(0, CONFIG.timeLimit - elapsed);
            }
            
            function updateTimerDisplay() {
                if (timerEl) {
                    timerEl.innerText = `{{ autoTranslate('Waktu tersisa:') }} ${Math.ceil(timeRemainingSeconds())}s`;
                }
            }
            
            async function finishGame(completed = false) {
                if (!gameActive) return;
                gameActive = false;
                
                if (timerInterval) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                }
                
                if (completed) {
                    updateScore();
                    resultEl.innerText = '🎉 {{ autoTranslate('Selamat! Puzzle terselesaikan!') }} 🎉';
                    await sendScore(true);
                } else {
                    resultEl.innerText = '⏰ {{ autoTranslate('Waktu habis!') }}';
                }
                
                // Show result and redirect
                setTimeout(() => {
                    window.location.href = '{{ route('game.leaderboard', ['locale' => app()->getLocale()]) }}';
                }, 2000);
            }
            
            function shuffleGame() {
                if (!gameActive) return;
                shuffleTiles();
                moves = 0;
                movesEl.innerText = `{{ autoTranslate('Langkah:') }} ${moves}`;
                updateScore();
                render();
            }
            
            function resetGame() {
                if (!gameActive) return;
                initTiles();
                shuffleTiles();
                moves = 0;
                movesEl.innerText = `{{ autoTranslate('Langkah:') }} ${moves}`;
                updateScore();
                render();
            }
            
            // Timer
            timerInterval = setInterval(() => {
                if (!gameActive) return;
                
                updateTimerDisplay();
                updateScore();
                scoreEl.innerText = score;
                
                if (timeRemainingSeconds() <= 0) {
                    clearInterval(timerInterval);
                    finishGame(false);
                }
            }, 1000);
            
            // Event listeners
            document.getElementById('shuffleBtn').addEventListener('click', shuffleGame);
            document.getElementById('resetBtn').addEventListener('click', resetGame);
            
            // Initialize
            initTiles();
            shuffleTiles();
            render();
            fetchHighestScore();
        });
    </script>
@endpush