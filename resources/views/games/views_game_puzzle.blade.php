@extends('Layout.Layout')

@section('title', 'Game Puzzle')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-400 to-purple-600 dark:from-gray-900 dark:to-gray-800 py-8">
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
            let remainingTime = 180;
            
            const postinganId = @json($postingan->id_postingan ?? null);
            const storageKey = `puzzle_state_postingan_${postinganId}`;
            
            const container = document.getElementById('puzzle-container');
            const movesEl = document.getElementById('moves-count');
            const scoreEl = document.getElementById('score');
            const timerEl = document.getElementById('timer-seconds');
            const resultEl = document.getElementById('result');
            const bestScoreInfoEl = document.getElementById('bestScoreInfo');
            const shuffleBtn = document.getElementById('shuffleBtn');
            const resetBtn = document.getElementById('resetBtn');
            
            // Load saved state from localStorage
            function loadSavedState() {
                const savedState = localStorage.getItem(storageKey);
                if (savedState) {
                    try {
                        const state = JSON.parse(savedState);
                        
                        // Restore tiles
                        if (state.tiles && Array.isArray(state.tiles)) {
                            tiles = state.tiles;
                        }
                        
                        // Restore moves
                        if (state.moves !== undefined) {
                            moves = state.moves;
                            if (movesEl) movesEl.innerText = moves;
                        }
                        
                        // Restore score
                        if (state.score !== undefined) {
                            score = state.score;
                            scoreEl.innerText = score;
                        }
                        
                        // Restore remaining time
                        if (state.remainingTime !== undefined && state.timestamp) {
                            const timePassed = Math.floor((Date.now() - state.timestamp) / 1000);
                            remainingTime = Math.max(0, state.remainingTime - timePassed);
                            if (remainingTime <= 0) {
                                finishGame(false);
                                return false;
                            }
                        } else {
                            remainingTime = CONFIG.timeLimit;
                        }
                        
                        // Restore start time
                        if (state.startTime) {
                            startTime = Date.now() - (state.elapsedTime || 0);
                        }
                        
                        // Restore game start
                        if (state.gameStart) {
                            gameStart = Date.now() - (state.gameElapsed || 0);
                        }
                        
                        // Restore currentGameId
                        if (state.currentGameId) {
                            currentGameId = state.currentGameId;
                        }
                        
                        // Restore gameActive status
                        if (state.gameActive !== undefined) {
                            gameActive = state.gameActive;
                        }
                        
                        // Re-render the puzzle
                        render();
                        
                        return true;
                    } catch (e) {
                        console.error('Error loading saved state:', e);
                    }
                }
                return false;
            }
            
            // Save current state to localStorage
            function saveCurrentState() {
                if (!gameActive) return;
                
                const timeElapsed = Date.now() - startTime;
                const gameElapsed = Date.now() - gameStart;
                const currentRemainingTime = timeRemainingSeconds();
                
                const state = {
                    tiles: tiles,
                    moves: moves,
                    score: score,
                    remainingTime: currentRemainingTime,
                    elapsedTime: timeElapsed,
                    gameElapsed: gameElapsed,
                    startTime: startTime,
                    gameStart: gameStart,
                    currentGameId: currentGameId,
                    gameActive: gameActive,
                    timestamp: Date.now()
                };
                localStorage.setItem(storageKey, JSON.stringify(state));
            }
            
            
            // Initialize tiles
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
                if (!container) return;
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
                    if (movesEl) movesEl.innerText = moves;
                    render();
                    saveCurrentState(); // Save after each move
                    
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
                if (scoreEl) scoreEl.innerText = score;
                saveCurrentState(); // Save score changes
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
                            bestScoreInfoEl.innerHTML = `🏆 <span class="text-gray-900 dark:text-white">{{ 'Skor tertinggi Anda:' }} ${highestScore}</span>`;
                        } else if (bestScoreInfoEl) {
                            bestScoreInfoEl.innerHTML = `🎯 <span class="text-gray-700 dark:text-gray-300">{{ 'Selesaikan puzzle dengan langkah sedikit!' }}</span>`;
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
                            bestScoreInfoEl.innerHTML = `🏆 <span class="text-gray-900 dark:text-white">{{ 'Skor tertinggi Anda:' }} ${highestScore} ✨ {{ 'Rekor Baru!' }}</span>`;
                        }
                    }
                    
                    saveCurrentState(); // Save after score update
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
                if (remainingTime !== undefined && remainingTime !== null) {
                    const elapsed = Math.floor((Date.now() - startTime) / 1000);
                    return Math.max(0, remainingTime - elapsed);
                }
                const elapsed = Math.floor((Date.now() - startTime) / 1000);
                return Math.max(0, CONFIG.timeLimit - elapsed);
            }
            
            function updateTimerDisplay() {
                if (timerEl) {
                    const remaining = Math.ceil(timeRemainingSeconds());
                    timerEl.innerText = remaining;
                    
                    // Change color when time is low
                    if (remaining <= 10) {
                        timerEl.classList.add('text-red-600', 'dark:text-red-400');
                        timerEl.classList.remove('text-gray-900', 'dark:text-gray-100');
                    } else if (remaining <= 30) {
                        timerEl.classList.add('text-yellow-600', 'dark:text-yellow-400');
                        timerEl.classList.remove('text-red-600', 'dark:text-red-400');
                    } else {
                        timerEl.classList.remove('text-red-600', 'dark:text-red-400', 'text-yellow-600', 'dark:text-yellow-400');
                    }
                }
            }
            
            async function finishGame(completed = false) {
                if (!gameActive) return;
                gameActive = false;
                
                if (timerInterval) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                }
                
                // Clear saved state on game completion
                localStorage.removeItem(storageKey);
                
                if (completed) {
                    updateScore();
                    resultEl.innerHTML = '<span class="text-green-600 dark:text-green-400">🎉 {{ 'Selamat! Puzzle terselesaikan!' }} 🎉</span>';
                    await sendScore(true);
                } else {
                    resultEl.innerHTML = '<span class="text-red-600 dark:text-red-400">⏰ {{ 'Waktu habis!' }}</span>';
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
                if (movesEl) movesEl.innerText = moves;
                updateScore();
                render();
                saveCurrentState(); // Save after shuffle
            }
            
            // Save state periodically (every 5 seconds)
            setInterval(() => {
                if (gameActive) {
                    saveCurrentState();
                }
            }, 5000);
            
            // Save state before page unload
            window.addEventListener('beforeunload', () => {
                if (gameActive) {
                    saveCurrentState();
                }
            });
            
            // Timer
            function startTimer() {
                timerInterval = setInterval(() => {
                    if (!gameActive) return;
                    
                    updateTimerDisplay();
                    updateScore();
                    
                    if (timeRemainingSeconds() <= 0) {
                        clearInterval(timerInterval);
                        finishGame(false);
                    }
                }, 1000);
            }
            
            // Event listeners
            if (shuffleBtn) shuffleBtn.addEventListener('click', shuffleGame);
            if (resetBtn) resetBtn.addEventListener('click', resetGame);
            
            // Load saved state or initialize new game
            const hasSavedState = loadSavedState();
            
            if (!hasSavedState) {
                // New game
                initTiles();
                shuffleTiles();
                render();
                startTime = Date.now();
                gameStart = Date.now();
                remainingTime = CONFIG.timeLimit;
            } else {
                // Continue from saved state
                if (remainingTime <= 0) {
                    finishGame(false);
                    return;
                }
                // Adjust start time based on remaining time
                startTime = Date.now() - (CONFIG.timeLimit - remainingTime) * 1000;
            }
            
            // Fetch highest score
            fetchHighestScore();
            
            // Start timer
            startTimer();
        });
    </script>
@endpush