@extends('Layout.Layout')

@section('title', 'Game TTS')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-400 to-green-600 dark:from-gray-900 dark:to-gray-800 py-8">
        <div class="w-full max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl overflow-hidden p-6 md:p-8">
                <div class="flex items-center justify-between mb-4 flex-wrap gap-4">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ autoTranslate('Teka Teki Silang') }}
                    </h1>
                    <div class="text-sm">
                        <div id="timer" class="font-medium text-gray-900 dark:text-gray-100">{{ autoTranslate('Waktu tersisa:') }} <span id="timer-seconds">300</span>s</div>
                        <div id="questions-left" class="text-xs text-gray-600 dark:text-gray-400">{{ autoTranslate('Sisa soal:') }} <span id="remaining-count">5</span></div>
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
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ autoTranslate('Pertanyaan Mendatar') }}</h3>
                            <div id="horizontal-questions" class="space-y-2"></div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ autoTranslate('Pertanyaan Menurun') }}</h3>
                            <div id="vertical-questions" class="space-y-2"></div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-center mt-6">
                    <div id="selected-question" class="text-sm text-gray-700 dark:text-gray-300 mb-2"></div>
                    <input id="answer" type="text" 
                        class="w-full sm:w-96 h-12 text-center text-lg rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400"
                        placeholder="{{ autoTranslate('Masukkan jawaban') }}">

                    <button id="checkBtn"
                        class="mt-4 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-full shadow-md">{{ autoTranslate('CEK JAWABAN') }}</button>

                    <div id="result" class="mt-4 text-gray-800 dark:text-gray-200 text-lg"></div>
                    <div class="mt-2 text-gray-800 dark:text-gray-200">{{ autoTranslate('Skor') }}: <span
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
            // Crossword data
            const crosswordData = {
                size: 5,
                grid: [
                    ['', '', '', '', ''],
                    ['', '', '', '', ''],
                    ['', '', '', '', ''],
                    ['', '', '', '', ''],
                    ['', '', '', '', '']
                ],
                answers: {
                    // Horizontal (row, col, length, answer, clue)
                    h: [
                        { row: 0, col: 0, length: 5, answer: 'BAHASA', clue: 'Alat komunikasi lisan dan tulisan (6 huruf)', id: 'h0' },
                        { row: 2, col: 0, length: 5, answer: 'ANGKA', clue: 'Simbol untuk mewakili bilangan (5 huruf)', id: 'h1' },
                        { row: 4, col: 0, length: 5, answer: 'SAINS', clue: 'Ilmu pengetahuan tentang alam (5 huruf)', id: 'h2' }
                    ],
                    v: [
                        { row: 0, col: 0, length: 5, answer: 'BASIC', clue: 'Dasar, pokok (5 huruf)', id: 'v0' },
                        { row: 0, col: 2, length: 5, answer: 'HARTA', clue: 'Kekayaan, barang berharga (5 huruf)', id: 'v1' },
                        { row: 0, col: 4, length: 5, answer: 'ANAK', clue: 'Buah hati, keturunan (4 huruf)', id: 'v2' }
                    ]
                },
                solved: []
            };
            
            let currentQuestion = null;
            let currentDirection = null;
            let score = 0;
            let questionsSolved = 0;
            const totalQuestions = crosswordData.answers.h.length + crosswordData.answers.v.length;
            let gameActive = true;
            let startTime = Date.now();
            let gameStart = Date.now();
            let timerInterval = null;
            let currentGameId = null;
            let highestScore = 0;
            let remainingTime = 300; // Store remaining time in seconds
            
            const postinganId = @json($postingan->id_postingan ?? null);
            const storageKey = `tts_state_postingan_${postinganId}`;
            
            // Load saved state from localStorage
            function loadSavedState() {
                const savedState = localStorage.getItem(storageKey);
                if (savedState) {
                    try {
                        const state = JSON.parse(savedState);
                        
                        // Restore solved questions
                        if (state.solved && Array.isArray(state.solved)) {
                            crosswordData.solved = state.solved;
                            questionsSolved = state.solved.length;
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
                            remainingTime = 300;
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
                        
                        updateQuestionsLeft();
                        return true;
                    } catch (e) {
                        console.error('Error loading saved state:', e);
                    }
                }
                return false;
            }
            
            // Save current state to localStorage
            function saveCurrentState() {
                const timeElapsed = Date.now() - startTime;
                const gameElapsed = Date.now() - gameStart;
                const currentRemainingTime = timeRemainingSeconds();
                
                const state = {
                    solved: crosswordData.solved,
                    score: score,
                    remainingTime: currentRemainingTime,
                    elapsedTime: timeElapsed,
                    gameElapsed: gameElapsed,
                    startTime: startTime,
                    gameStart: gameStart,
                    currentGameId: currentGameId,
                    timestamp: Date.now()
                };
                localStorage.setItem(storageKey, JSON.stringify(state));
            }
            
            
            const container = document.getElementById('crossword-container');
            const horizontalQuestionsEl = document.getElementById('horizontal-questions');
            const verticalQuestionsEl = document.getElementById('vertical-questions');
            const selectedQuestionEl = document.getElementById('selected-question');
            const answerEl = document.getElementById('answer');
            const resultEl = document.getElementById('result');
            const scoreEl = document.getElementById('score');
            const timerEl = document.getElementById('timer-seconds');
            const questionsLeftEl = document.getElementById('remaining-count');
            const checkBtn = document.getElementById('checkBtn');
            const resetBtn = document.getElementById('resetBtn');
            const bestScoreInfoEl = document.getElementById('bestScoreInfo');
            
            function initGrid() {
                container.innerHTML = '';
                for (let i = 0; i < crosswordData.size; i++) {
                    for (let j = 0; j < crosswordData.size; j++) {
                        const cell = document.createElement('div');
                        cell.className = 'flex items-center justify-center bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 font-bold text-lg text-gray-900 dark:text-white';
                        cell.style.width = '60px';
                        cell.style.height = '60px';
                        
                        // Check if this cell is part of any question
                        let isActive = false;
                        let cellQuestion = null;
                        let cellDirection = null;
                        
                        // Check horizontal
                        for (let q of crosswordData.answers.h) {
                            if (i === q.row && j >= q.col && j < q.col + q.length) {
                                isActive = true;
                                cellQuestion = q;
                                cellDirection = 'h';
                                break;
                            }
                        }
                        
                        // Check vertical
                        for (let q of crosswordData.answers.v) {
                            if (j === q.col && i >= q.row && i < q.row + q.length) {
                                isActive = true;
                                cellQuestion = q;
                                cellDirection = 'v';
                                break;
                            }
                        }
                        
                        if (isActive) {
                            cell.classList.add('cursor-pointer', 'hover:bg-green-100', 'dark:hover:bg-green-900');
                            const solved = crosswordData.solved.find(s => s.questionId === cellQuestion.id);
                            if (solved) {
                                const letterIndex = cellDirection === 'h' ? j - cellQuestion.col : i - cellQuestion.row;
                                cell.innerHTML = solved.answer[letterIndex] || '';
                                cell.classList.add('bg-green-100', 'dark:bg-green-900', 'text-gray-900', 'dark:text-white');
                            } else {
                                cell.innerHTML = '?';
                                cell.classList.add('text-gray-600', 'dark:text-gray-300');
                            }
                            
                            cell.addEventListener('click', () => selectQuestion(cellQuestion, cellDirection));
                        } else {
                            cell.classList.add('bg-gray-300', 'dark:bg-gray-700');
                            cell.innerHTML = '';
                        }
                        
                        container.appendChild(cell);
                    }
                }
            }
            
            function loadQuestions() {
                // Load horizontal questions
                horizontalQuestionsEl.innerHTML = '';
                crosswordData.answers.h.forEach((q, idx) => {
                    const solved = crosswordData.solved.find(s => s.questionId === q.id);
                    const div = document.createElement('div');
                    div.className = `p-2 rounded cursor-pointer ${solved ? 'bg-green-100 dark:bg-green-900' : 'bg-gray-100 dark:bg-gray-800'} hover:bg-green-200 dark:hover:bg-green-800 text-gray-900 dark:text-gray-100`;
                    div.innerHTML = `<strong>${idx + 1}.</strong> ${q.clue} ${solved ? '✓' : ''}`;
                    div.addEventListener('click', () => selectQuestion(q, 'h'));
                    horizontalQuestionsEl.appendChild(div);
                });
                
                // Load vertical questions
                verticalQuestionsEl.innerHTML = '';
                crosswordData.answers.v.forEach((q, idx) => {
                    const solved = crosswordData.solved.find(s => s.questionId === q.id);
                    const div = document.createElement('div');
                    div.className = `p-2 rounded cursor-pointer ${solved ? 'bg-green-100 dark:bg-green-900' : 'bg-gray-100 dark:bg-gray-800'} hover:bg-green-200 dark:hover:bg-green-800 text-gray-900 dark:text-gray-100`;
                    div.innerHTML = `<strong>${idx + 1}.</strong> ${q.clue} ${solved ? '✓' : ''}`;
                    div.addEventListener('click', () => selectQuestion(q, 'v'));
                    verticalQuestionsEl.appendChild(div);
                });
            }
            
            function selectQuestion(question, direction) {
                if (!gameActive) return;
                currentQuestion = question;
                currentDirection = direction;
                selectedQuestionEl.innerHTML = `<strong class="text-gray-900 dark:text-white">Pertanyaan:</strong> <span class="text-gray-700 dark:text-gray-300">${question.clue}</span>`;
                answerEl.value = '';
                answerEl.focus();
                resultEl.innerHTML = '';
            }
            
            function checkAnswer() {
                if (!gameActive || !currentQuestion) {
                    resultEl.innerHTML = '<span class="text-red-600 dark:text-red-400">Pilih pertanyaan terlebih dahulu!</span>';
                    return;
                }
                
                const userAnswer = answerEl.value.trim().toUpperCase();
                const isCorrect = userAnswer === currentQuestion.answer;
                
                if (isCorrect) {
                    // Check if already solved
                    const alreadySolved = crosswordData.solved.find(s => s.questionId === currentQuestion.id);
                    if (!alreadySolved) {
                        const points = 100;
                        score += points;
                        questionsSolved++;
                        scoreEl.innerText = score;
                        
                        crosswordData.solved.push({
                            questionId: currentQuestion.id,
                            question: currentQuestion,
                            direction: currentDirection,
                            answer: currentQuestion.answer
                        });
                        
                        resultEl.innerHTML = `<span class="text-green-600 dark:text-green-400">✅ Benar! +${points} poin</span>`;
                        initGrid();
                        loadQuestions();
                        saveCurrentState(); // Save after each correct answer
                        
                        if (questionsSolved >= totalQuestions) {
                            finishGame(true);
                        }
                    } else {
                        resultEl.innerHTML = '<span class="text-yellow-600 dark:text-yellow-400">Pertanyaan ini sudah terjawab!</span>';
                    }
                } else {
                    resultEl.innerHTML = `<span class="text-red-600 dark:text-red-400">❌ Salah! Jawaban yang benar adalah: ${currentQuestion.answer}</span>`;
                }
                
                answerEl.value = '';
                updateQuestionsLeft();
                sendScore(false);
            }
            
            function updateQuestionsLeft() {
                const remaining = totalQuestions - questionsSolved;
                if (questionsLeftEl) {
                    questionsLeftEl.innerText = remaining;
                }
            }
            
            function updateScoreDisplay() {
                const timeElapsed = Math.floor((Date.now() - startTime) / 1000);
                const timeBonus = Math.max(0, 300 - timeElapsed) * 2;
                const questionBonus = questionsSolved * 50;
                score = Math.max(0, questionBonus + timeBonus);
                scoreEl.innerText = score;
                saveCurrentState(); // Save score changes
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
                            game_name: 'TTS'
                        }),
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        highestScore = data.highest_score || 0;
                        if (bestScoreInfoEl && highestScore > 0) {
                            bestScoreInfoEl.innerHTML = `🏆 <span class="text-gray-900 dark:text-white">{{ autoTranslate('Skor tertinggi Anda:') }} ${highestScore}</span>`;
                        } else if (bestScoreInfoEl) {
                            bestScoreInfoEl.innerHTML = `🎯 <span class="text-gray-700 dark:text-gray-300">{{ autoTranslate('Selesaikan semua teka-teki!') }}</span>`;
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
                        game_name: 'TTS',
                        questions_solved: questionsSolved
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
                            bestScoreInfoEl.innerHTML = `🏆 <span class="text-gray-900 dark:text-white">{{ autoTranslate('Skor tertinggi Anda:') }} ${highestScore} ✨ {{ autoTranslate('Rekor Baru!') }}</span>`;
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
                return Math.max(0, 300 - elapsed);
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
                    updateScoreDisplay();
                    await sendScore(true);
                    resultEl.innerHTML = '<span class="text-green-600 dark:text-green-400">🎉 {{ autoTranslate('Selamat! Anda menyelesaikan semua teka-teki!') }} 🎉</span>';
                } else {
                    resultEl.innerHTML = '<span class="text-red-600 dark:text-red-400">⏰ {{ autoTranslate('Waktu habis!') }}</span>';
                }
                
                setTimeout(() => {
                    window.location.href = '{{ route('game.leaderboard', ['locale' => app()->getLocale()]) }}';
                }, 2000);
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
                    updateScoreDisplay();
                    
                    if (timeRemainingSeconds() <= 0) {
                        clearInterval(timerInterval);
                        finishGame(false);
                    }
                }, 1000);
            }
            
            // Event listeners
            checkBtn.addEventListener('click', checkAnswer);
            if (resetBtn) {
                resetBtn.addEventListener('click', resetGame);
            }
            answerEl.addEventListener('keyup', (e) => {
                if (e.key === 'Enter' && gameActive) {
                    checkAnswer();
                }
            });
            
            // Load saved state or initialize new game
            const hasSavedState = loadSavedState();
            
            // Initialize
            initGrid();
            loadQuestions();
            fetchHighestScore();
            updateQuestionsLeft();
            updateTimerDisplay();
            
            if (!hasSavedState) {
                // New game, set initial times
                startTime = Date.now();
                gameStart = Date.now();
                remainingTime = 300;
            } else {
                // Continue from saved state
                if (remainingTime <= 0) {
                    finishGame(false);
                    return;
                }
                // Adjust start time based on remaining time
                startTime = Date.now() - (300 - remainingTime) * 1000;
            }
            
            startTimer();
        });
    </script>
@endpush