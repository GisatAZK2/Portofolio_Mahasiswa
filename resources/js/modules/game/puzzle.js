/**
 * modules/game/puzzle.js
 * Game Puzzle — puzzle gambar IIFE.
 */
(function () {
    const container = document.getElementById('puzzle-game-container');
    if (!container) return;

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

    const postinganId = container.dataset.postinganId || null;
    const getScoreUrl = container.dataset.getScoreUrl || '';
    const saveScoreUrl = container.dataset.saveScoreUrl || '';
    const leaderboardUrl = container.dataset.leaderboardUrl || '';
    const storageKey = `puzzle_state_postingan_${postinganId}`;

    const containerEl = document.getElementById('puzzle-container');
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
                if (state.tiles && Array.isArray(state.tiles)) {
                    tiles = state.tiles;
                }
                if (state.moves !== undefined) {
                    moves = state.moves;
                    if (movesEl) movesEl.innerText = moves;
                }
                if (state.score !== undefined) {
                    score = state.score;
                    scoreEl.innerText = score;
                }
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
                if (state.startTime) {
                    startTime = Date.now() - (state.elapsedTime || 0);
                }
                if (state.gameStart) {
                    gameStart = Date.now() - (state.gameElapsed || 0);
                }
                if (state.currentGameId) {
                    currentGameId = state.currentGameId;
                }
                if (state.gameActive !== undefined) {
                    gameActive = state.gameActive;
                }
                render();
                return true;
            } catch (e) {
                console.error('Error loading saved state:', e);
            }
        }
        return false;
    }

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

    function initTiles() {
        tiles = [];
        for (let i = 0; i < CONFIG.TILE_COUNT - 1; i++) {
            tiles.push(i + 1);
        }
        tiles.push(null);
        return tiles;
    }

    function shuffleTiles() {
        for (let i = tiles.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [tiles[i], tiles[j]] = [tiles[j], tiles[i]];
        }
        if (!isSolvable()) {
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
        if (!containerEl) return;
        containerEl.innerHTML = '';
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
            containerEl.appendChild(div);
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
            [tiles[clickedIndex], tiles[emptyIndex]] = [tiles[emptyIndex], tiles[clickedIndex]];
            moves++;
            updateScore();
            if (movesEl) movesEl.innerText = moves;
            render();
            saveCurrentState();
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
        saveCurrentState();
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
            const response = await fetch(getScoreUrl, {
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
                    bestScoreInfoEl.innerHTML = `🏆 <span class="text-gray-900 dark:text-white">Skor tertinggi Anda: ${highestScore}</span>`;
                } else if (bestScoreInfoEl) {
                    bestScoreInfoEl.innerHTML = `🎯 <span class="text-gray-700 dark:text-gray-300">Selesaikan puzzle dengan langkah sedikit!</span>`;
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
            const res = await fetch(saveScoreUrl, {
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
                    bestScoreInfoEl.innerHTML = `🏆 <span class="text-gray-900 dark:text-white">Skor tertinggi Anda: ${highestScore} ✨ Rekor Baru!</span>`;
                }
            }
            saveCurrentState();
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
            if (remaining <= 10) {
                timerEl.classList.add('text-red-600', 'dark:text-red-400');
                timerEl.classList.remove('text-gray-900', 'dark:text-gray-100', 'text-yellow-600', 'dark:text-yellow-400');
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
        localStorage.removeItem(storageKey);
        if (completed) {
            updateScore();
            resultEl.innerHTML = '<span class="text-green-600 dark:text-green-400">🎉 Selamat! Puzzle terselesaikan! 🎉</span>';
            await sendScore(true);
        } else {
            resultEl.innerHTML = '<span class="text-red-600 dark:text-red-400">⏰ Waktu habis!</span>';
        }
        setTimeout(() => {
            window.location.href = leaderboardUrl;
        }, 2000);
    }

    function shuffleGame() {
        if (!gameActive) return;
        shuffleTiles();
        moves = 0;
        if (movesEl) movesEl.innerText = moves;
        updateScore();
        render();
        saveCurrentState();
    }

    function resetGame() {
        if (!gameActive) return;
        if (confirm('Mulai ulang permainan? Semua progres akan hilang.')) {
            localStorage.removeItem(storageKey);
            initTiles();
            shuffleTiles();
            moves = 0;
            score = 0;
            gameActive = true;
            remainingTime = CONFIG.timeLimit;
            startTime = Date.now();
            gameStart = Date.now();
            currentGameId = null;
            if (movesEl) movesEl.innerText = moves;
            if (scoreEl) scoreEl.innerText = score;
            resultEl.innerHTML = '';
            render();
            saveCurrentState();
            if (timerInterval) clearInterval(timerInterval);
            startTimer();
        }
    }

    function startTimer() {
        if (timerInterval) clearInterval(timerInterval);
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
        initTiles();
        shuffleTiles();
        render();
        startTime = Date.now();
        gameStart = Date.now();
        remainingTime = CONFIG.timeLimit;
    } else {
        if (remainingTime <= 0) {
            finishGame(false);
            return;
        }
        startTime = Date.now() - (CONFIG.timeLimit - remainingTime) * 1000;
    }

    fetchHighestScore();
    startTimer();

    // Save state periodically and on beforeunload
    setInterval(() => {
        if (gameActive) saveCurrentState();
    }, 5000);

    window.addEventListener('beforeunload', () => {
        if (gameActive) saveCurrentState();
    });
})();

// ==========================================
//   GAME TTS (TEKA TEKI SILANG)
// ==========================================
