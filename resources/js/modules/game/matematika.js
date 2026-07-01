/**
 * modules/game/matematika.js
 * Game Matematika — kuis matematika IIFE.
 */
(function () {
    const container = document.getElementById('math-game-container');
    if (!container) return;

    class MathGame {
        constructor(container) {
            // Ambil data dari container
            this.postinganId = container.dataset.postinganId || null;
            this.currentGameId = container.dataset.gameId || null;
            this.getScoreUrl = container.dataset.getScoreUrl || '';
            this.saveScoreUrl = container.dataset.saveScoreUrl || '';
            this.leaderboardUrl = container.dataset.leaderboardUrl || '';

            // DOM elements
            this.num1El = document.getElementById('num1');
            this.num2El = document.getElementById('num2');
            this.operatorSymbolEl = document.getElementById('operatorSymbol');
            this.answerInput = document.getElementById('answerInput');
            this.submitBtn = document.getElementById('submitBtn');
            this.resetBtn = document.getElementById('resetGameBtn');
            this.operatorSelect = document.getElementById('operatorSelect');
            this.levelSelect = document.getElementById('levelSelect');
            this.timerDisplay = document.getElementById('timerDisplay');
            this.timerProgress = document.getElementById('timerProgress');
            this.scoreValue = document.getElementById('scoreValue');
            this.correctCountEl = document.getElementById('correctCount');
            this.wrongCountEl = document.getElementById('wrongCount');
            this.accuracyEl = document.getElementById('accuracy');
            this.resultMessage = document.getElementById('resultMessage');
            this.bestScoreInfo = document.getElementById('bestScoreInfo');

            // Game state
            this.operator = '+';
            this.level = 'medium';
            this.num1 = 0;
            this.num2 = 0;
            this.correctAnswer = 0;
            this.score = 0;
            this.correct = 0;
            this.wrong = 0;
            this.questionsAnswered = 0;
            this.maxQuestions = 10;
            this.timeLimitSeconds = 120;
            this.timeRemaining = this.timeLimitSeconds;
            this.timerInterval = null;
            this.gameActive = true;
            this.waitingForSubmit = false;
            this.startTime = null;
            this.gameStartTime = null;
            this.highestScore = 0;
            this.storageKey = `math_game_${this.postinganId}`;

            this.pointMultiplier = { easy: 5, medium: 10, hard: 20 };

            this.init();
        }

        init() {
            const navType = this.getNavigationType();
            if (navType === 'reload') {
                this.restoreState();
            } else {
                this.resetGame(false);
            }

            this.fetchHighestScore();
            this.generateQuestion();
            this.startTimer();
            this.attachEvents();
        }

        getNavigationType() {
            try {
                const navEntries = performance.getEntriesByType && performance.getEntriesByType('navigation');
                if (navEntries && navEntries[0]) return navEntries[0].type;
                if (performance.navigation && performance.navigation.type === 1) return 'reload';
            } catch (e) { }
            return 'navigate';
        }

        attachEvents() {
            this.submitBtn.addEventListener('click', () => this.checkAnswer());
            this.resetBtn.addEventListener('click', () => this.resetGame(true));
            this.answerInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && this.gameActive && !this.waitingForSubmit) this.checkAnswer();
            });
            this.operatorSelect.addEventListener('change', () => {
                this.operator = this.operatorSelect.value;
                this.operatorSymbolEl.innerText = this.getOperatorSymbol();
                if (this.gameActive && !this.waitingForSubmit) {
                    this.resetGame(true);
                }
            });
            this.levelSelect.addEventListener('change', () => {
                this.level = this.levelSelect.value;
                if (this.gameActive && !this.waitingForSubmit) {
                    this.resetGame(true);
                }
            });
        }

        getOperatorSymbol() {
            const map = { '+': '+', '-': '−', '*': '×', '/': '÷' };
            return map[this.operator] || this.operator;
        }

        generateQuestion() {
            if (!this.gameActive) return;

            let maxNum = 10, maxNum2 = 10;
            if (this.level === 'easy') {
                maxNum = 10; maxNum2 = 10;
                if (this.operator === '*') maxNum = 5;
                if (this.operator === '/') maxNum = 10;
            } else if (this.level === 'medium') {
                maxNum = 20; maxNum2 = 20;
                if (this.operator === '*') maxNum = 12;
                if (this.operator === '/') maxNum = 20;
            } else { // hard
                maxNum = 50; maxNum2 = 50;
                if (this.operator === '*') maxNum = 20;
                if (this.operator === '/') maxNum = 30;
            }

            let valid = false;
            let attempts = 0;
            while (!valid && attempts < 20) {
                this.num1 = Math.floor(Math.random() * (maxNum + 1));
                this.num2 = Math.floor(Math.random() * (maxNum2 + 1));

                if (this.operator === '+') {
                    this.correctAnswer = this.num1 + this.num2;
                    valid = true;
                } else if (this.operator === '-') {
                    this.correctAnswer = this.num1 - this.num2;
                    valid = (this.correctAnswer >= 0);
                } else if (this.operator === '*') {
                    this.correctAnswer = this.num1 * this.num2;
                    valid = (this.correctAnswer <= 200);
                } else if (this.operator === '/') {
                    if (this.num2 === 0) { valid = false; continue; }
                    if (this.num1 % this.num2 === 0) {
                        this.correctAnswer = this.num1 / this.num2;
                        valid = true;
                    } else {
                        valid = false;
                    }
                }
                attempts++;
            }
            if (!valid) {
                this.num1 = 5; this.num2 = 3; this.correctAnswer = 8;
                if (this.operator === '-') { this.num1 = 10; this.num2 = 4; this.correctAnswer = 6; }
                if (this.operator === '*') { this.num1 = 4; this.num2 = 3; this.correctAnswer = 12; }
                if (this.operator === '/') { this.num1 = 12; this.num2 = 3; this.correctAnswer = 4; }
            }

            this.num1El.innerText = this.num1;
            this.num2El.innerText = this.num2;
            this.operatorSymbolEl.innerText = this.getOperatorSymbol();
            this.answerInput.value = '';
            this.resultMessage.innerText = '';
            this.waitingForSubmit = false;
            this.answerInput.disabled = false;
            this.submitBtn.disabled = false;
            this.answerInput.focus();
            this.saveState();
        }

        async checkAnswer() {
            if (this.waitingForSubmit || !this.gameActive) return;

            const userAnswer = parseInt(this.answerInput.value.trim(), 10);
            if (isNaN(userAnswer)) {
                this.resultMessage.innerText = '⚠️ Masukkan angka!';
                return;
            }

            const isCorrect = (userAnswer === this.correctAnswer);
            const points = this.pointMultiplier[this.level];

            if (isCorrect) {
                this.score += points;
                this.correct++;
                this.resultMessage.innerHTML = `✅ Benar! +${points} poin`;
                this.resultMessage.className = 'text-lg font-medium text-green-600';
            } else {
                this.wrong++;
                this.resultMessage.innerHTML = `❌ Salah! Jawaban: ${this.correctAnswer}`;
                this.resultMessage.className = 'text-lg font-medium text-red-600';
            }

            this.questionsAnswered++;
            this.updateStatsUI();

            if (this.score > this.highestScore) {
                await this.saveScore(false);
            }

            if (this.questionsAnswered >= this.maxQuestions || this.timeRemaining <= 0) {
                this.endGame();
                return;
            }

            this.waitingForSubmit = true;
            this.answerInput.disabled = true;
            this.submitBtn.disabled = true;

            setTimeout(() => {
                this.generateQuestion();
            }, 800);
        }

        updateStatsUI() {
            this.scoreValue.innerText = this.score;
            this.correctCountEl.innerText = this.correct;
            this.wrongCountEl.innerText = this.wrong;
            const total = this.correct + this.wrong;
            const acc = total === 0 ? 0 : Math.round((this.correct / total) * 100);
            this.accuracyEl.innerText = acc + '%';
        }

        startTimer() {
            if (this.timerInterval) clearInterval(this.timerInterval);
            this.startTime = Date.now();
            this.gameStartTime = Date.now();

            this.timerInterval = setInterval(() => {
                if (!this.gameActive) return;
                const elapsed = Math.floor((Date.now() - this.startTime) / 1000);
                this.timeRemaining = Math.max(0, this.timeLimitSeconds - elapsed);

                const mins = Math.floor(this.timeRemaining / 60);
                const secs = this.timeRemaining % 60;
                this.timerDisplay.innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                const percent = (this.timeRemaining / this.timeLimitSeconds) * 100;
                this.timerProgress.style.width = `${Math.max(0, percent)}%`;

                if (this.timeRemaining <= 0 && this.gameActive) {
                    clearInterval(this.timerInterval);
                    this.timerInterval = null;
                    this.endGame();
                }
            }, 1000);
        }

        async endGame() {
            if (!this.gameActive) return;
            this.gameActive = false;
            if (this.timerInterval) clearInterval(this.timerInterval);
            this.answerInput.disabled = true;
            this.submitBtn.disabled = true;

            const isNewRecord = this.score > this.highestScore;
            if (isNewRecord) {
                await this.saveScore(true);
            }

            const message = isNewRecord
                ? `🎉 Selesai! Skor akhir: ${this.score} 🎉\n🏆 Rekor Baru! 🏆`
                : `Selesai! Skor akhir: ${this.score}\n📊 Skor tertinggi Anda: ${this.highestScore}`;

            if (typeof Swal !== 'undefined') {
                await Swal.fire({
                    title: 'Permainan Selesai',
                    html: message.replace(/\n/g, '<br>'),
                    icon: isNewRecord ? 'success' : 'info',
                    confirmButtonText: 'Lihat Peringkat'
                });
            } else {
                alert(message);
            }

            sessionStorage.removeItem(this.storageKey);
            window.location.href = this.leaderboardUrl;
        }

        async resetGame(confirmReset = true) {
            if (confirmReset) {
                const ok = confirm('Mulai permainan baru? Skor saat ini akan hilang.');
                if (!ok) return;
            }
            if (this.timerInterval) clearInterval(this.timerInterval);

            this.score = 0;
            this.correct = 0;
            this.wrong = 0;
            this.questionsAnswered = 0;
            this.timeRemaining = this.timeLimitSeconds;
            this.gameActive = true;
            this.waitingForSubmit = false;
            this.operator = this.operatorSelect.value;
            this.level = this.levelSelect.value;

            this.updateStatsUI();
            this.timerDisplay.innerText = '02:00';
            this.timerProgress.style.width = '100%';
            this.resultMessage.innerText = '';
            this.answerInput.disabled = false;
            this.submitBtn.disabled = false;

            this.startTimer();
            this.generateQuestion();
            sessionStorage.removeItem(this.storageKey);
            this.saveState();
        }

        saveState() {
            if (!this.gameActive) return;
            const state = {
                operator: this.operator,
                level: this.level,
                num1: this.num1,
                num2: this.num2,
                correctAnswer: this.correctAnswer,
                score: this.score,
                correct: this.correct,
                wrong: this.wrong,
                questionsAnswered: this.questionsAnswered,
                timeRemaining: this.timeRemaining,
                startTime: this.startTime,
                gameStartTime: this.gameStartTime,
                gameActive: this.gameActive
            };
            try {
                sessionStorage.setItem(this.storageKey, JSON.stringify(state));
            } catch (e) { }
        }

        restoreState() {
            const saved = sessionStorage.getItem(this.storageKey);
            if (!saved) return false;
            try {
                const s = JSON.parse(saved);
                this.operator = s.operator;
                this.level = s.level;
                this.num1 = s.num1;
                this.num2 = s.num2;
                this.correctAnswer = s.correctAnswer;
                this.score = s.score;
                this.correct = s.correct;
                this.wrong = s.wrong;
                this.questionsAnswered = s.questionsAnswered;
                this.timeRemaining = s.timeRemaining;
                this.startTime = s.startTime;
                this.gameStartTime = s.gameStartTime;
                this.gameActive = s.gameActive;

                this.operatorSelect.value = this.operator;
                this.levelSelect.value = this.level;
                this.operatorSymbolEl.innerText = this.getOperatorSymbol();

                this.num1El.innerText = this.num1;
                this.num2El.innerText = this.num2;
                this.updateStatsUI();
                this.answerInput.value = '';
                this.resultMessage.innerText = '';

                const mins = Math.floor(this.timeRemaining / 60);
                const secs = this.timeRemaining % 60;
                this.timerDisplay.innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                const percent = (this.timeRemaining / this.timeLimitSeconds) * 100;
                this.timerProgress.style.width = `${percent}%`;

                return true;
            } catch (e) { return false; }
        }

        async fetchHighestScore() {
            try {
                const response = await fetch(this.getScoreUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        id_postingan: this.postinganId,
                        operator: this.operator,
                        level: this.level
                    })
                });
                if (response.ok) {
                    const data = await response.json();
                    this.highestScore = data.highest_score || 0;
                    if (this.bestScoreInfo) {
                        if (this.highestScore > 0) {
                            this.bestScoreInfo.innerHTML = `🏆 Skor tertinggi Anda: ${this.highestScore}`;
                        } else {
                            this.bestScoreInfo.innerHTML = '🎯 Mainkan dan raih skor tertinggi!';
                        }
                    }
                }
            } catch (e) { console.error(e); }
        }

        async saveScore(isFinal = false) {
            if (this.score <= this.highestScore && isFinal === false) return;
            try {
                const response = await fetch(this.saveScoreUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        id_games: this.currentGameId,
                        id_postingan: this.postinganId,
                        score: this.score,
                        playing_time: Math.floor((Date.now() - (this.gameStartTime || Date.now())) / 1000) + 's',
                        operator: this.operator,
                        level: this.level,
                        is_final: isFinal
                    })
                });
                const data = await response.json();
                if (data.id_games) this.currentGameId = data.id_games;
                if (this.score > this.highestScore) {
                    this.highestScore = this.score;
                    if (this.bestScoreInfo) {
                        this.bestScoreInfo.innerHTML = `🏆 Skor tertinggi Anda: ${this.highestScore} ✨ Rekor Baru!`;
                    }
                }
            } catch (e) { console.error(e); }
        }
    }

    // Inisialisasi game
    const game = new MathGame(container);
    window.mathGame = game;
})();

// ==========================================
//   GAME PUZZLE
