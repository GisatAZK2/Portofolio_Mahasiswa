@extends('Layout.Layout')

@section('title', Game Matematika')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-400 to-teal-600 dark:from-gray-900 dark:to-gray-800 py-8">
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
                    <select id="operatorSelect" class="px-3 py-1.5 rounded-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm font-medium"></select>
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

@push('scripts')
<script>
// ======================== MATH GAME CLASS ========================
class MathGame {
    constructor() {
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
        this.maxQuestions = 10;      // total soal dalam satu sesi
        this.timeLimitSeconds = 120; // 2 menit
        this.timeRemaining = this.timeLimitSeconds;
        this.timerInterval = null;
        this.gameActive = true;
        this.waitingForSubmit = false;
        this.startTime = null;
        this.gameStartTime = null;
        
        // Skor tertinggi (dari server)
        this.highestScore = 0;
        
        // Data dari server
        this.postinganId = @json($postingan->id_postingan ?? null);
        this.currentGameId = @json($game->id_games ?? null);
        
        // Storage key buat reload
        this.storageKey = `math_game_${this.postinganId}`;
        
        // Score multiplier based on level
        this.pointMultiplier = { easy: 5, medium: 10, hard: 20 };
        
        this.init();
    }
    
    async init() {
        // Cek apakah ada state tersimpan (karena reload)
        const navType = this.getNavigationType();
        if (navType === 'reload') {
            this.restoreState();
        } else {
            // Mulai baru
            this.resetGame(false); // false = jangan simpan ke server dulu
        }
        
        // Ambil skor tertinggi dari server
        await this.fetchHighestScore();
        
        // Generate soal pertama
        this.generateQuestion();
        
        // Mulai timer
        this.startTimer();
        
        // Event listeners
        this.attachEvents();
    }
    
    getNavigationType() {
        try {
            const navEntries = performance.getEntriesByType && performance.getEntriesByType('navigation');
            if (navEntries && navEntries[0]) return navEntries[0].type;
            if (performance.navigation && performance.navigation.type === 1) return 'reload';
        } catch(e) {}
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
                this.resetGame(true); // ganti operasi -> reset permainan
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
        
        // Tentukan range angka berdasarkan level dan operator
        let maxNum = 10, maxNum2 = 10;
        if (this.level === 'easy') {
            maxNum = 10;
            maxNum2 = 10;
            if (this.operator === '*') maxNum = 5;
            if (this.operator === '/') maxNum = 10;
        } else if (this.level === 'medium') {
            maxNum = 20;
            maxNum2 = 20;
            if (this.operator === '*') maxNum = 12;
            if (this.operator === '/') maxNum = 20;
        } else { // hard
            maxNum = 50;
            maxNum2 = 50;
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
                valid = (this.correctAnswer >= 0); // hindari negatif
            } else if (this.operator === '*') {
                this.correctAnswer = this.num1 * this.num2;
                valid = (this.correctAnswer <= 200); // batasi hasil terlalu besar
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
        // Fallback jika tidak valid
        if (!valid) {
            this.num1 = 5; this.num2 = 3; this.correctAnswer = 8;
            if (this.operator === '-') { this.num1 = 10; this.num2 = 4; this.correctAnswer = 6; }
            if (this.operator === '*') { this.num1 = 4; this.num2 = 3; this.correctAnswer = 12; }
            if (this.operator === '/') { this.num1 = 12; this.num2 = 3; this.correctAnswer = 4; }
        }
        
        // Update DOM
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
            this.resultMessage.innerText = '⚠️ ' + 'Masukkan angka!';
            return;
        }
        
        const isCorrect = (userAnswer === this.correctAnswer);
        const points = this.pointMultiplier[this.level];
        
        if (isCorrect) {
            this.score += points;
            this.correct++;
            this.resultMessage.innerHTML = `✅ ${'Benar!'} +${points} ${'poin'}`;
            this.resultMessage.className = 'text-lg font-medium text-green-600';
        } else {
            this.wrong++;
            this.resultMessage.innerHTML = `❌ ${'Salah! Jawaban:'} ${this.correctAnswer}`;
            this.resultMessage.className = 'text-lg font-medium text-red-600';
        }
        
        this.questionsAnswered++;
        this.updateStatsUI();
        
        // Kirim skor ke server jika mencetak rekor baru
        if (this.score > this.highestScore) {
            await this.saveScore(false);
        }
        
        // Cek apakah game selesai
        if (this.questionsAnswered >= this.maxQuestions || this.timeRemaining <= 0) {
            this.endGame();
            return;
        }
        
        // Siapkan soal berikutnya
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
            
            // Update tampilan timer
            const mins = Math.floor(this.timeRemaining / 60);
            const secs = this.timeRemaining % 60;
            this.timerDisplay.innerText = `${mins.toString().padStart(2,'0')}:${secs.toString().padStart(2,'0')}`;
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
        
        // Simpan skor akhir jika lebih tinggi dari rekor
        const isNewRecord = this.score > this.highestScore;
        if (isNewRecord) {
            await this.saveScore(true);
        }
        
        // Tampilkan pesan akhir
        const message = isNewRecord 
            ? `🎉 ${'Selesai! Skor akhir:'} ${this.score} 🎉\n🏆 ${'Rekor Baru!'} 🏆`
            : `${'Selesai! Skor akhir:'} ${this.score}\n📊 ${'Skor tertinggi Anda:'} ${this.highestScore}`;
        
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
        
        // Hapus state & redirect ke leaderboard
        sessionStorage.removeItem(this.storageKey);
        window.location.href = '{{ route("game.leaderboard", ["locale" => app()->getLocale()]) }}';
    }
    
    async resetGame(confirmReset = true) {
        if (confirmReset) {
            const ok = confirm('Mulai permainan baru? Skor saat ini akan hilang.');
            if (!ok) return;
        }
        // Hentikan timer lama
        if (this.timerInterval) clearInterval(this.timerInterval);
        
        // Reset state
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
        
        // Mulai timer baru
        this.startTimer();
        // Generate soal baru
        this.generateQuestion();
        // Hapus state lama
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
        } catch(e) {}
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
            
            // Sinkronkan dropdown
            this.operatorSelect.value = this.operator;
            this.levelSelect.value = this.level;
            this.operatorSymbolEl.innerText = this.getOperatorSymbol();
            
            // Update UI
            this.num1El.innerText = this.num1;
            this.num2El.innerText = this.num2;
            this.updateStatsUI();
            this.answerInput.value = '';
            this.resultMessage.innerText = '';
            
            // Set timer display from remaining time
            const mins = Math.floor(this.timeRemaining / 60);
            const secs = this.timeRemaining % 60;
            this.timerDisplay.innerText = `${mins.toString().padStart(2,'0')}:${secs.toString().padStart(2,'0')}`;
            const percent = (this.timeRemaining / this.timeLimitSeconds) * 100;
            this.timerProgress.style.width = `${percent}%`;
            
            return true;
        } catch(e) { return false; }
    }
    
    async fetchHighestScore() {
        try {
            const response = await fetch('{{ route("game.getHighestScore") }}', {
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
                        this.bestScoreInfo.innerHTML = `🏆 ${'Skor tertinggi Anda:'} ${this.highestScore}`;
                    } else {
                        this.bestScoreInfo.innerHTML = `🎯 ${'Mainkan dan raih skor tertinggi!'}`;
                    }
                }
            }
        } catch(e) { console.error(e); }
    }
    
    async saveScore(isFinal = false) {
        // Hanya simpan jika melebihi rekor
        if (this.score <= this.highestScore && isFinal === false) return;
        try {
            const response = await fetch('{{ route("game.saveScore") }}', {
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
                    this.bestScoreInfo.innerHTML = `🏆 ${'Skor tertinggi Anda:'} ${this.highestScore} ✨ ${'Rekor Baru!'}`;
                }
            }
        } catch(e) { console.error(e); }
    }
}

// Inisialisasi game saat DOM ready
document.addEventListener('DOMContentLoaded', () => {
    window.mathGame = new MathGame();
});
</script>
@endpush