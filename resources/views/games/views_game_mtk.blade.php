@extends('Layout.Layout')

@section('title', 'Game Matematika')

@section('content')
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-400 to-teal-600 dark:from-gray-900 dark:to-gray-800 py-8">
        <div class="w-full max-w-2xl mx-auto">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl overflow-hidden p-6 md:p-8">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ autoTranslate('Game Matematika') }}
                    </h1>
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        <div id="timer" class="font-medium">{{ autoTranslate('Waktu tersisa:') }} 120s</div>
                        <div id="remaining" class="text-xs text-gray-500">{{ autoTranslate('Sisa soal:') }} 10</div>
                    </div>
                </div>

                <div class="flex items-center justify-center gap-6 mb-6 flex-col sm:flex-row">
                    <div id="num1"
                        class="w-28 h-24 md:w-32 md:h-28 flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-4xl md:text-5xl font-semibold rounded-lg text-gray-900 dark:text-white">
                        0</div>
                    <div class="text-4xl md:text-5xl font-bold text-gray-800 dark:text-white">+</div>
                    <div id="num2"
                        class="w-28 h-24 md:w-32 md:h-28 flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-4xl md:text-5xl font-semibold rounded-lg text-gray-900 dark:text-white">
                        0</div>
                </div>

                <div class="flex flex-col items-center">
                    <input id="answer" type="number" inputmode="numeric"
                        class="w-full sm:w-64 md:w-80 h-12 text-center text-lg rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-400"
                        placeholder="{{ autoTranslate('Masukkan jawaban') }}">

                    <button id="checkBtn"
                        class="mt-4 bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-full shadow-md">{{ autoTranslate('CHECK ANSWER') }}</button>

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
            // Game constants
            const maxQuestions = 10;
            const timeLimitSeconds = 120; // 2 minutes
            const pointPerCorrect = 10; // Fixed 10 points per correct answer
            
            const postinganId = @json($postingan->id_postingan ?? null);
            const storageKey = `game_state_postingan_${postinganId}`;
            
            // DOM elements
            const num1El = document.getElementById('num1');
            const num2El = document.getElementById('num2');
            const answerEl = document.getElementById('answer');
            const resultEl = document.getElementById('result');
            const scoreEl = document.getElementById('score');
            const checkBtn = document.getElementById('checkBtn');
            const timerEl = document.getElementById('timer');
            const remainingEl = document.getElementById('remaining');
            const bestScoreInfoEl = document.getElementById('bestScoreInfo');
            
            // Game state variables
            let num1, num2, correct;
            let score = 0;
            let questionsAnswered = 0;
            let startTime = Date.now();
            let gameStart = Date.now();
            let currentGameId = @json($game->id_games ?? null);
            let timerInterval = null;
            let gameActive = true;
            let waitingForNext = false;
            let highestScore = 0; // Store the player's highest score for this game
            
            // Navigation detection: only restore state for reloads
            let navType = 'navigate';
            try {
                const navEntries = performance.getEntriesByType && performance.getEntriesByType('navigation');
                navType = (navEntries && navEntries[0] && navEntries[0].type) || 
                         (performance.navigation && performance.navigation.type === 1 ? 'reload' : 'navigate');
            } catch (e) {
                // ignore
            }
            
            // Fetch the player's highest score for this game from server
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
                        }),
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        highestScore = data.highest_score || 0;
                        if (bestScoreInfoEl && highestScore > 0) {
                            bestScoreInfoEl.innerHTML = `🏆 {{ autoTranslate('Skor tertinggi Anda:') }} ${highestScore}`;
                        } else if (bestScoreInfoEl) {
                            bestScoreInfoEl.innerHTML = `🎯 {{ autoTranslate('Mainkan dan raih skor tertinggi!') }}`;
                        }
                    }
                } catch (err) {
                    console.error('Error fetching highest score:', err);
                }
            }
            
            // Restore state on reload if available
            if (navType === 'reload' && sessionStorage.getItem(storageKey)) {
                try {
                    const saved = JSON.parse(sessionStorage.getItem(storageKey));
                    if (saved && saved.postinganId == postinganId && !saved.gameFinished) {
                        num1 = saved.num1;
                        num2 = saved.num2;
                        correct = saved.correct;
                        score = saved.score || 0;
                        questionsAnswered = saved.questionsAnswered || 0;
                        startTime = saved.startTime || startTime;
                        gameStart = saved.gameStart || gameStart;
                        currentGameId = saved.currentGameId || currentGameId;
                        gameActive = saved.gameActive !== false;
                        
                        // Update display
                        scoreEl.innerText = score;
                        updateRemainingDisplay();
                        updateTimerDisplay();
                    }
                } catch (e) {
                    console.error('Failed to parse saved game state', e);
                }
            }
            
            // Clear any stale state for fresh navigation
            if (navType !== 'reload') {
                try { sessionStorage.removeItem(storageKey); } catch (e) { }
            }
            
            function saveState() {
                // Don't save if game is finished
                if (!gameActive) return;
                
                try {
                    const state = {
                        postinganId: postinganId,
                        num1: num1,
                        num2: num2,
                        correct: correct,
                        score: score,
                        questionsAnswered: questionsAnswered,
                        startTime: startTime,
                        gameStart: gameStart,
                        currentGameId: currentGameId,
                        gameActive: gameActive,
                        gameFinished: false
                    };
                    sessionStorage.setItem(storageKey, JSON.stringify(state));
                } catch (e) {
                    console.error('Failed to save game state', e);
                }
            }
            
            function generateQuestion() {
                // Generate new random numbers
                num1 = Math.floor(Math.random() * 10);
                num2 = Math.floor(Math.random() * 10);
                correct = num1 + num2;
                
                num1El.innerText = num1;
                num2El.innerText = num2;
                answerEl.value = '';
                resultEl.innerText = '';
                answerEl.focus();
                
                waitingForNext = false;
                saveState();
            }
            
            function getPlayingTime() {
                const seconds = Math.floor((Date.now() - gameStart) / 1000);
                return seconds + 's';
            }
            
            function timeRemainingSeconds() {
                const elapsed = Math.floor((Date.now() - startTime) / 1000);
                return Math.max(0, timeLimitSeconds - elapsed);
            }
            
            function updateTimerDisplay() {
                if (timerEl) {
                    timerEl.innerText = `{{ autoTranslate('Waktu tersisa:') }} ${Math.ceil(timeRemainingSeconds())}s`;
                }
            }
            
            function updateRemainingDisplay() {
                if (remainingEl) {
                    remainingEl.innerText = `{{ autoTranslate('Sisa soal:') }} ${Math.max(0, maxQuestions - questionsAnswered)}`;
                }
            }
            
            async function sendScore(isFinal = false) {
                // Only save if current score is higher than highest score
                if (score <= highestScore && isFinal) {
                    console.log(`Score ${score} is not higher than highest score ${highestScore}. Not saving.`);
                    return false;
                }
                
                try {
                    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const payload = {
                        id_games: currentGameId,
                        id_postingan: postinganId,
                        score: parseInt(score, 10),
                        playing_time: getPlayingTime(),
                    };
                    
                    if (isFinal) {
                        payload.is_final = true;
                    }
                    
                    const res = await fetch('{{ route('game.saveScore') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify(payload),
                    });
                    
                    if (!res.ok) {
                        console.warn('Failed to save score', await res.text());
                        return false;
                    }
                    
                    const data = await res.json();
                    if (data.id_games) currentGameId = data.id_games;
                    
                    // Update highest score if this is a new record
                    if (score > highestScore) {
                        highestScore = score;
                        if (bestScoreInfoEl) {
                            bestScoreInfoEl.innerHTML = `🏆 {{ autoTranslate('Skor tertinggi Anda:') }} ${highestScore} ✨ {{ autoTranslate('Rekor Baru!') }}`;
                        }
                    }
                    
                    saveState();
                    return true;
                } catch (err) {
                    console.error('Error sending score', err);
                    return false;
                }
            }
            
            async function finishGame() {
                if (!gameActive) return;
                gameActive = false;
                
                // Clear timer
                if (timerInterval) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                }
                
                // Check if this score is a new record
                const isNewRecord = score > highestScore;
                
                // Send final score only if it's higher than previous best
                if (isNewRecord) {
                    await sendScore(true);
                    // Show success message for new record
                    try {
                        if (window.showSuccessAlert) {
                            window.showSuccessAlert(`{{ autoTranslate('Skor Anda:') }} ${score}\n🎉 {{ autoTranslate('Rekor Baru!') }} 🎉`);
                        } else if (window.Swal) {
                            window.Swal.fire({ 
                                title: `{{ autoTranslate('Skor Anda:') }} ${score}`,
                                text: `🎉 {{ autoTranslate('Selamat! Anda mendapatkan rekor baru!') }} 🎉`,
                                icon: 'success'
                            });
                        } else {
                            alert(`{{ autoTranslate('Skor Anda:') }} ${score}\n🎉 {{ autoTranslate('Rekor Baru!') }} 🎉`);
                        }
                    } catch (e) {
                        console.error(e);
                        alert(`{{ autoTranslate('Skor Anda:') }} ${score}`);
                    }
                } else {
                    // Show message that score didn't beat the record
                    try {
                        if (window.showSuccessAlert) {
                            window.showSuccessAlert(`{{ autoTranslate('Skor Anda:') }} ${score}\n📊 {{ autoTranslate('Skor tertinggi Anda:') }} ${highestScore}`);
                        } else if (window.Swal) {
                            window.Swal.fire({ 
                                title: `{{ autoTranslate('Skor Anda:') }} ${score}`,
                                text: `📊 {{ autoTranslate('Skor tertinggi Anda:') }} ${highestScore}\n💪 {{ autoTranslate('Coba lagi untuk memecahkan rekor!') }}`,
                                icon: 'info'
                            });
                        } else {
                            alert(`{{ autoTranslate('Skor Anda:') }} ${score}\n{{ autoTranslate('Skor tertinggi Anda:') }} ${highestScore}`);
                        }
                    } catch (e) {
                        console.error(e);
                        alert(`{{ autoTranslate('Skor Anda:') }} ${score}`);
                    }
                }
                
                // Remove saved state
                try { sessionStorage.removeItem(storageKey); } catch (e) { }
                
                // Redirect to leaderboard
                setTimeout(() => {
                    window.location.href = '{{ route('game.leaderboard', ['locale' => app()->getLocale()]) }}';
                }, 2000);
            }
            
            function checkAnswer() {
                // Prevent multiple checks while waiting for next question or game finished
                if (waitingForNext || !gameActive) return;
                
                const userAnswer = answerEl.value.trim();
                
                if (userAnswer === '') {
                    resultEl.innerText = 'Masukkan jawaban dulu';
                    return;
                }
                
                const isCorrect = parseInt(userAnswer, 10) === correct;
                
                if (isCorrect) {
                    // Add fixed 10 points for correct answer
                    score += pointPerCorrect;
                    resultEl.innerText = `{{ autoTranslate('Benar') }} (+${pointPerCorrect})`;
                } else {
                    resultEl.innerText = '{{ autoTranslate('Salah, jawaban:') }} ' + correct;
                }
                
                // Update score display
                scoreEl.innerText = score;
                questionsAnswered++;
                updateRemainingDisplay();
                
                // Send score update (only if it's a new record during gameplay)
                if (score > highestScore) {
                    sendScore(false);
                }
                
                // Check if game should finish
                if (questionsAnswered >= maxQuestions || timeRemainingSeconds() <= 0) {
                    finishGame();
                    return;
                }
                
                // Disable input while loading next question
                waitingForNext = true;
                answerEl.disabled = true;
                checkBtn.disabled = true;
                
                // Generate next question after delay
                setTimeout(() => {
                    generateQuestion();
                    answerEl.disabled = false;
                    checkBtn.disabled = false;
                }, 1000);
            }
            
            // Initialize timer display
            updateTimerDisplay();
            updateRemainingDisplay();
            
            // Fetch the player's highest score first
            fetchHighestScore().then(() => {
                // Start countdown timer
                timerInterval = setInterval(() => {
                    if (!gameActive) return;
                    
                    const rem = timeRemainingSeconds();
                    updateTimerDisplay();
                    updateRemainingDisplay();
                    
                    if (rem <= 0) {
                        clearInterval(timerInterval);
                        timerInterval = null;
                        finishGame();
                    }
                }, 1000);
            });
            
            // Add event listeners
            checkBtn.addEventListener('click', checkAnswer);
            answerEl.addEventListener('keyup', function (e) {
                if (e.key === 'Enter' && !waitingForNext && gameActive) {
                    checkAnswer();
                }
            });
            
            // Generate first question (if not restored)
            if (typeof num1 === 'undefined' || typeof num2 === 'undefined') {
                generateQuestion();
            } else {
                // Restored state - display the saved question
                num1El.innerText = num1;
                num2El.innerText = num2;
                answerEl.value = '';
                resultEl.innerText = '';
                answerEl.focus();
                waitingForNext = false;
                saveState();
            }
        });
    </script>
@endpush