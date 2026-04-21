@extends('Layout.Layout')

@section('title', 'Game TTS')

@section('content')
    <div class="min-h-[70vh] flex items-center justify-center bg-gradient-to-br from-green-400 to-green-600 dark:from-gray-900 dark:to-gray-800 py-8">
        <div class="w-full max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl overflow-hidden p-6 md:p-8">
                <div class="flex items-center justify-between mb-4 flex-wrap gap-4">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ autoTranslate('Teka Teki Silang') }}
                    </h1>
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        <div id="timer" class="font-medium">{{ autoTranslate('Waktu tersisa:') }} 300s</div>
                        <div id="questions-left" class="text-xs text-gray-500">{{ autoTranslate('Sisa soal:') }} 5</div>
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
                    <div id="selected-question" class="text-sm text-gray-600 dark:text-gray-300 mb-2"></div>
                    <input id="answer" type="text" 
                        class="w-full sm:w-96 h-12 text-center text-lg rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400"
                        placeholder="{{ autoTranslate('Masukkan jawaban') }}">

                    <button id="checkBtn"
                        class="mt-4 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-full shadow-md">{{ autoTranslate('CEK JAWABAN') }}</button>

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
                        { row: 0, col: 0, length: 5, answer: 'BAHASA', clue: 'Alat komunikasi lisan dan tulisan (6 huruf)' },
                        { row: 2, col: 0, length: 5, answer: 'ANGKA', clue: 'Simbol untuk mewakili bilangan (5 huruf)' },
                        { row: 4, col: 0, length: 5, answer: 'SAINS', clue: 'Ilmu pengetahuan tentang alam (5 huruf)' }
                    ],
                    v: [
                        { row: 0, col: 0, length: 5, answer: 'BASIC', clue: 'Dasar, pokok (5 huruf)' },
                        { row: 0, col: 2, length: 5, answer: 'HARTA', clue: 'Kekayaan, barang berharga (5 huruf)' },
                        { row: 0, col: 4, length: 5, answer: 'ANAK', clue: 'Buah hati, keturunan (4 huruf)' }
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
            
            const postinganId = @json($postingan->id_postingan ?? null);
            const storageKey = `tts_state_postingan_${postinganId}`;
            
            const container = document.getElementById('crossword-container');
            const horizontalQuestionsEl = document.getElementById('horizontal-questions');
            const verticalQuestionsEl = document.getElementById('vertical-questions');
            const selectedQuestionEl = document.getElementById('selected-question');
            const answerEl = document.getElementById('answer');
            const resultEl = document.getElementById('result');
            const scoreEl = document.getElementById('score');
            const timerEl = document.getElementById('timer');
            const questionsLeftEl = document.getElementById('questions-left');
            const checkBtn = document.getElementById('checkBtn');
            const bestScoreInfoEl = document.getElementById('bestScoreInfo');
            
            function initGrid() {
                container.innerHTML = '';
                for (let i = 0; i < crosswordData.size; i++) {
                    for (let j = 0; j < crosswordData.size; j++) {
                        const cell = document.createElement('div');
                        cell.className = 'flex items-center justify-center bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 font-bold text-lg';
                        cell.style.width = '60px';
                        cell.style.height = '60px';
                        
                        // Check if this cell is part of any question
                        let isActive = false;
                        let cellQuestion = null;
                        
                        // Check horizontal
                        for (let q of crosswordData.answers.h) {
                            if (i === q.row && j >= q.col && j < q.col + q.length) {
                                isActive = true;
                                cellQuestion = q;
                                break;
                            }
                        }
                        
                        // Check vertical
                        for (let q of crosswordData.answers.v) {
                            if (j === q.col && i >= q.row && i < q.row + q.length) {
                                isActive = true;
                                cellQuestion = q;
                                break;
                            }
                        }
                        
                        if (isActive) {
                            cell.classList.add('cursor-pointer', 'hover:bg-green-100', 'dark:hover:bg-green-900');
                            const solved = crosswordData.solved.find(s => s.question === cellQuestion && s.direction === (cellQuestion.row ? 'h' : 'v'));
                            if (solved) {
                                const letterIndex = cellQuestion.direction === 'h' ? j - cellQuestion.col : i - cellQuestion.row;
                                cell.innerHTML = solved.answer[letterIndex] || '';
                                cell.classList.add('bg-green-100', 'dark:bg-green-900');
                            } else {
                                cell.innerHTML = '?';
                            }
                            
                            cell.addEventListener('click', () => selectQuestion(cellQuestion, cellQuestion.row !== undefined ? 'h' : 'v'));
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
                horizontalQuestionsEl.innerHTML = '<div class="space-y-2"></div>';
                crosswordData.answers.h.forEach((q, idx) => {
                    const solved = crosswordData.solved.find(s => s.question === q && s.direction === 'h');
                    const div = document.createElement('div');
                    div.className = `p-2 rounded cursor-pointer ${solved ? 'bg-green-100 dark:bg-green-900' : 'bg-gray-100 dark:bg-gray-800'} hover:bg-green-200 dark:hover:bg-green-800`;
                    div.innerHTML = `<strong>${idx + 1}.</strong> ${q.clue} ${solved ? '✓' : ''}`;
                    div.addEventListener('click', () => selectQuestion(q, 'h'));
                    horizontalQuestionsEl.appendChild(div);
                });
                
                // Load vertical questions
                verticalQuestionsEl.innerHTML = '<div class="space-y-2"></div>';
                crosswordData.answers.v.forEach((q, idx) => {
                    const solved = crosswordData.solved.find(s => s.question === q && s.direction === 'v');
                    const div = document.createElement('div');
                    div.className = `p-2 rounded cursor-pointer ${solved ? 'bg-green-100 dark:bg-green-900' : 'bg-gray-100 dark:bg-gray-800'} hover:bg-green-200 dark:hover:bg-green-800`;
                    div.innerHTML = `<strong>${idx + 1}.</strong> ${q.clue} ${solved ? '✓' : ''}`;
                    div.addEventListener('click', () => selectQuestion(q, 'v'));
                    verticalQuestionsEl.appendChild(div);
                });
            }
            
            function selectQuestion(question, direction) {
                if (!gameActive) return;
                currentQuestion = question;
                currentDirection = direction;
                selectedQuestionEl.innerHTML = `<strong>Pertanyaan:</strong> ${question.clue}`;
                answerEl.value = '';
                answerEl.focus();
                resultEl.innerHTML = '';
            }
            
            function checkAnswer() {
                if (!gameActive || !currentQuestion) {
                    resultEl.innerHTML = 'Pilih pertanyaan terlebih dahulu!';
                    return;
                }
                
                const userAnswer = answerEl.value.trim().toUpperCase();
                const isCorrect = userAnswer === currentQuestion.answer;
                
                if (isCorrect) {
                    // Check if already solved
                    const alreadySolved = crosswordData.solved.find(s => s.question === currentQuestion && s.direction === currentDirection);
                    if (!alreadySolved) {
                        const points = 100;
                        score += points;
                        questionsSolved++;
                        scoreEl.innerText = score;
                        
                        crosswordData.solved.push({
                            question: currentQuestion,
                            direction: currentDirection,
                            answer: currentQuestion.answer
                        });
                        
                        resultEl.innerHTML = `✅ Benar! +${points} poin`;
                        initGrid();
                        loadQuestions();
                        
                        if (questionsSolved >= totalQuestions) {
                            finishGame(true);
                        }
                    } else {
                        resultEl.innerHTML = 'Pertanyaan ini sudah terjawab!';
                    }
                } else {
                    resultEl.innerHTML = `❌ Salah! Jawaban yang benar adalah: ${currentQuestion.answer}`;
                }
                
                answerEl.value = '';
                updateQuestionsLeft();
                sendScore(false);
            }
            
            function updateQuestionsLeft() {
                const remaining = totalQuestions - questionsSolved;
                questionsLeftEl.innerText = `{{ autoTranslate('Sisa soal:') }} ${remaining}`;
            }
            
            function updateScoreDisplay() {
                const timeElapsed = Math.floor((Date.now() - startTime) / 1000);
                const timeBonus = Math.max(0, 300 - timeElapsed) * 2;
                const questionBonus = questionsSolved * 50;
                score = Math.max(0, questionBonus + timeBonus);
                scoreEl.innerText = score;
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
                            bestScoreInfoEl.innerHTML = `🏆 {{ autoTranslate('Skor tertinggi Anda:') }} ${highestScore}`;
                        } else if (bestScoreInfoEl) {
                            bestScoreInfoEl.innerHTML = `🎯 {{ autoTranslate('Selesaikan semua teka-teki!') }}`;
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
                return Math.max(0, 300 - elapsed);
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
                    updateScoreDisplay();
                    await sendScore(true);
                    resultEl.innerHTML = '🎉 {{ autoTranslate('Selamat! Anda menyelesaikan semua teka-teki!') }} 🎉';
                } else {
                    resultEl.innerHTML = '⏰ {{ autoTranslate('Waktu habis!') }}';
                }
                
                setTimeout(() => {
                    window.location.href = '{{ route('game.leaderboard', ['locale' => app()->getLocale()]) }}';
                }, 2000);
            }
            
            // Timer
            timerInterval = setInterval(() => {
                if (!gameActive) return;
                
                updateTimerDisplay();
                updateScoreDisplay();
                scoreEl.innerText = score;
                
                if (timeRemainingSeconds() <= 0) {
                    clearInterval(timerInterval);
                    finishGame(false);
                }
            }, 1000);
            
            // Event listeners
            checkBtn.addEventListener('click', checkAnswer);
            answerEl.addEventListener('keyup', (e) => {
                if (e.key === 'Enter' && gameActive) {
                    checkAnswer();
                }
            });
            
            // Initialize
            initGrid();
            loadQuestions();
            fetchHighestScore();
            updateQuestionsLeft();
        });
    </script>
@endpush