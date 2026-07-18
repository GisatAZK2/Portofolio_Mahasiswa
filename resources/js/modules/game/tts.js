/**
 * modules/game/tts.js
 * Game TTS (Teka-Teki Silang) — crossword IIFE.
 */
(function () {
    const container = document.getElementById('tts-game-container');
    if (!container) return;

    const postinganId = container.dataset.postinganId || null;
    const getScoreUrl = container.dataset.getScoreUrl || '';
    const saveScoreUrl = container.dataset.saveScoreUrl || '';
    const leaderboardUrl = container.dataset.leaderboardUrl || '';
    const storageKey = `tts_state_postingan_${postinganId}`;

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
    let remainingTime = 300;

    const containerEl = document.getElementById('crossword-container');
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

    // Load saved state
    function loadSavedState() {
        const savedState = localStorage.getItem(storageKey);
        if (savedState) {
            try {
                const state = JSON.parse(savedState);
                if (state.solved && Array.isArray(state.solved)) {
                    crosswordData.solved = state.solved;
                    questionsSolved = state.solved.length;
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
                    remainingTime = 300;
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
                updateQuestionsLeft();
                return true;
            } catch (e) {
                console.error('Error loading saved state:', e);
            }
        }
        return false;
    }

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

    function initGrid() {
        containerEl.innerHTML = '';
        for (let i = 0; i < crosswordData.size; i++) {
            for (let j = 0; j < crosswordData.size; j++) {
                const cell = document.createElement('div');
                cell.className = 'flex items-center justify-center bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 font-bold text-lg text-gray-900 dark:text-white';
                cell.style.width = '60px';
                cell.style.height = '60px';

                let isActive = false;
                let cellQuestion = null;
                let cellDirection = null;

                for (let q of crosswordData.answers.h) {
                    if (i === q.row && j >= q.col && j < q.col + q.length) {
                        isActive = true;
                        cellQuestion = q;
                        cellDirection = 'h';
                        break;
                    }
                }
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
                containerEl.appendChild(cell);
            }
        }
    }

    function loadQuestions() {
        horizontalQuestionsEl.innerHTML = '';
        crosswordData.answers.h.forEach((q, idx) => {
            const solved = crosswordData.solved.find(s => s.questionId === q.id);
            const div = document.createElement('div');
            div.className = `p-2 rounded cursor-pointer ${solved ? 'bg-green-100 dark:bg-green-900' : 'bg-gray-100 dark:bg-gray-800'} hover:bg-green-200 dark:hover:bg-green-800 text-gray-900 dark:text-gray-100`;
            div.innerHTML = `<strong>${idx + 1}.</strong> ${q.clue} ${solved ? '✓' : ''}`;
            div.addEventListener('click', () => selectQuestion(q, 'h'));
            horizontalQuestionsEl.appendChild(div);
        });

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
                saveCurrentState();
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
        if (questionsLeftEl) questionsLeftEl.innerText = remaining;
    }

    function updateScoreDisplay() {
        const timeElapsed = Math.floor((Date.now() - startTime) / 1000);
        const timeBonus = Math.max(0, 300 - timeElapsed) * 2;
        const questionBonus = questionsSolved * 50;
        score = Math.max(0, questionBonus + timeBonus);
        scoreEl.innerText = score;
        saveCurrentState();
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
                    game_name: 'TTS'
                }),
            });
            if (response.ok) {
                const data = await response.json();
                highestScore = data.highest_score || 0;
                if (bestScoreInfoEl && highestScore > 0) {
                    bestScoreInfoEl.innerHTML = `🏆 <span class="text-gray-900 dark:text-white">Skor tertinggi Anda: ${highestScore}</span>`;
                } else if (bestScoreInfoEl) {
                    bestScoreInfoEl.innerHTML = `🎯 <span class="text-gray-700 dark:text-gray-300">Selesaikan semua teka-teki!</span>`;
                }
            }
        } catch (err) {
            console.error('Error fetching highest score:', err);
        }
    }

    async function sendScore(isFinal = false) {
        if (score <= highestScore && isFinal) return false;
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
        return Math.max(0, 300 - elapsed);
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
            updateScoreDisplay();
            await sendScore(true);
            resultEl.innerHTML = '<span class="text-green-600 dark:text-green-400">🎉 Selamat! Anda menyelesaikan semua teka-teki! 🎉</span>';
        } else {
            resultEl.innerHTML = '<span class="text-red-600 dark:text-red-400">⏰ Waktu habis!</span>';
        }
        setTimeout(() => {
            window.location.href = leaderboardUrl;
        }, 2000);
    }

    function resetGame() {
        if (!gameActive) return;
        if (confirm('Mulai ulang permainan? Semua progres akan hilang.')) {
            localStorage.removeItem(storageKey);
            crosswordData.solved = [];
            questionsSolved = 0;
            score = 0;
            gameActive = true;
            remainingTime = 300;
            startTime = Date.now();
            gameStart = Date.now();
            currentGameId = null;
            scoreEl.innerText = score;
            resultEl.innerHTML = '';
            selectedQuestionEl.innerHTML = '';
            currentQuestion = null;
            currentDirection = null;
            initGrid();
            loadQuestions();
            updateQuestionsLeft();
            updateTimerDisplay();
            if (timerInterval) clearInterval(timerInterval);
            startTimer();
        }
    }

    function startTimer() {
        if (timerInterval) clearInterval(timerInterval);
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
    if (resetBtn) resetBtn.addEventListener('click', resetGame);
    answerEl.addEventListener('keyup', (e) => {
        if (e.key === 'Enter' && gameActive) checkAnswer();
    });

    // Load saved state or initialize new game
    const hasSavedState = loadSavedState();
    initGrid();
    loadQuestions();
    fetchHighestScore();
    updateQuestionsLeft();
    updateTimerDisplay();

    if (!hasSavedState) {
        startTime = Date.now();
        gameStart = Date.now();
        remainingTime = 300;
    } else {
        if (remainingTime <= 0) {
            finishGame(false);
            return;
        }
        startTime = Date.now() - (300 - remainingTime) * 1000;
    }

    startTimer();

    // Save state periodically and on beforeunload
    setInterval(() => {
        if (gameActive) saveCurrentState();
    }, 5000);

    window.addEventListener('beforeunload', () => {
        if (gameActive) saveCurrentState();
    });
})();

window.initAdminProjectCreate = function () {
    const container = document.getElementById('admin-project-create-data');
    if (!container) return;

    const allUsers    = JSON.parse(container.dataset.users    || '[]');
    const oldTasks    = JSON.parse(container.dataset.oldTasks || '[]');
    const fetchUrl    = container.dataset.fetchUrl   || '';
    const hasOldData  = container.dataset.hasOldData === 'true';
    const storageKey  = 'admin_project_selected_users';

    let selectedUsers = { owner: null, leader: null, members: [] };
    let taskIndex     = 0;
    let filterTimer   = null;

    // ─── Storage helpers ─────────────────────────────────────────────────────

    function saveSelectedUsersToStorage() {
        localStorage.setItem(storageKey, JSON.stringify({
            owner:   selectedUsers.owner,
            leader:  selectedUsers.leader,
            members: selectedUsers.members,
        }));
    }

    function restoreSelectedUsersFromStorage() {
        const stored = localStorage.getItem(storageKey);
        if (!stored) return false;
        try {
            const parsed = JSON.parse(stored);
            if (parsed.owner)                      selectedUsers.owner   = parsed.owner;
            if (parsed.leader)                     selectedUsers.leader  = parsed.leader;
            if (Array.isArray(parsed.members))     selectedUsers.members = parsed.members;
            return true;
        } catch (err) {
            console.warn('Unable to restore selected users from storage:', err);
            return false;
        }
    }

    // ─── Badge ───────────────────────────────────────────────────────────────

    function updateSelectedUsersBadge() {
        const badge = document.getElementById('selected-users-badge');
        if (!badge) return;

        const count = (selectedUsers.owner ? 1 : 0)
                    + (selectedUsers.leader ? 1 : 0)
                    + selectedUsers.members.length;

        badge.innerHTML = count
            ? `<span class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full text-xs font-semibold">${count} user(s) terpilih</span>`
            : '';
    }

    // ─── Task-section visibility ──────────────────────────────────────────────

    function updateTaskSectionVisibility() {
        const taskSection = document.getElementById('task-section');
        if (!taskSection) return;

        const hasUsers = selectedUsers.owner
                      || selectedUsers.leader
                      || selectedUsers.members.length > 0;

        if (hasUsers) {
            taskSection.classList.remove('hidden');
        } else {
            taskSection.classList.add('hidden');
            const tasksContainer = document.getElementById('tasks-container');
            if (tasksContainer) {
                tasksContainer.innerHTML = '';
                taskIndex = 0;
            }
        }
    }

    // ─── Modal open / close ───────────────────────────────────────────────────

    function openUserModal() {
        document.getElementById('userModal').classList.remove('hidden');
        sessionStorage.setItem('admin_project_modal_open', '1');
        sessionStorage.removeItem('admin_project_user_page');

        document.getElementById('modal-search').value    = '';
        document.getElementById('modal-angkatan').value  = '';
        document.getElementById('modal-jurusan').value   = '';
        document.getElementById('modal-keahlian').value  = '';

        applyUserFilters();
    }

    function closeUserModal() {
        document.getElementById('userModal').classList.add('hidden');
        sessionStorage.removeItem('admin_project_modal_open');
        sessionStorage.removeItem('admin_project_user_page');
    }

    // ─── AJAX: filter + pagination ────────────────────────────────────────────

    function applyUserFilters() {
        sessionStorage.removeItem('admin_project_user_page');

        const search   = document.getElementById('modal-search')?.value.trim();
        const angkatan = document.getElementById('modal-angkatan')?.value;
        const jurusan  = document.getElementById('modal-jurusan')?.value;
        const keahlian = document.getElementById('modal-keahlian')?.value;

        const params = new URLSearchParams();
        if (search)   params.set('search',   search);
        if (angkatan) params.set('angkatan', angkatan);
        if (jurusan)  params.set('jurusan',  jurusan);
        if (keahlian) params.set('keahlian', keahlian);

        fetchUserList(fetchUrl + '?' + params.toString());
    }

    function fetchUserList(url) {
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('modal-user-list').innerHTML            = data.userListHtml;
            document.getElementById('modal-pagination-container').innerHTML = data.paginationHtml;
            setSelectedRolesInModal();
            if (typeof window.refreshTranslations === 'function') window.refreshTranslations();
            attachPaginationListeners();
        })
        .catch(err => console.error('Error loading users:', err));
    }

    function attachPaginationListeners() {
        document.querySelectorAll(
            '[data-pagination-group="admin_project_user_selection"] .pagination-link'
        ).forEach(link => {
            // Clone to wipe any previous listener
            const newLink = link.cloneNode(true);
            link.parentNode.replaceChild(newLink, link);

            newLink.addEventListener('click', function (e) {
                e.preventDefault();
                fetchUserList(this.href);
            });
        });
    }

    // ─── Role selects inside modal ────────────────────────────────────────────

    function setSelectedRolesInModal() {
        document.querySelectorAll('#modal-user-list .user-role-select').forEach(select => {
            // Resolve userId from onchange attribute or data-user-id
            let userId = null;
            const onchangeAttr = select.getAttribute('onchange');
            if (onchangeAttr) {
                const match = onchangeAttr.match(/updateUserRole\(this,\s*(\d+),/);
                if (match) userId = parseInt(match[1]);
            }
            if (!userId && select.hasAttribute('data-user-id')) {
                userId = parseInt(select.getAttribute('data-user-id'));
            }
            if (!userId) return;

            const isOwner  = selectedUsers.owner?.id  === userId;
            const isLeader = selectedUsers.leader?.id === userId;
            const isMember = selectedUsers.members.some(m => m.id === userId);

            const ownerOpt  = select.querySelector('option[value="owner"]');
            const leaderOpt = select.querySelector('option[value="leader"]');
            const memberOpt = select.querySelector('option[value="member"]');

            if (ownerOpt)  ownerOpt.disabled  = selectedUsers.owner  !== null && !isOwner;
            if (leaderOpt) leaderOpt.disabled = selectedUsers.leader !== null && !isLeader;
            if (memberOpt) memberOpt.disabled = isOwner || isLeader;

            if      (isOwner)  select.value = 'owner';
            else if (isLeader) select.value = 'leader';
            else if (isMember) select.value = 'member';
            else               select.value = '';
        });
    }

    // ─── Update role from modal select ────────────────────────────────────────

    function updateUserRole(selectElement, userId, role) {
        const userDiv = selectElement.closest('.flex.items-center.justify-between');
        if (!userDiv) return;

        const img            = userDiv.querySelector('img');
        const photo_profile  = img?.src ? img.src.split('/storage/')[1] : null;
        const nama_mahasiswa = userDiv.querySelector('.font-medium')?.textContent ?? 'Unknown';
        const email          = userDiv.querySelector('.text-sm')?.textContent ?? '';
        const user           = { id: userId, nama_mahasiswa, email, photo_profile };

        // Guard: duplicate owner
        if (role === 'owner' && selectedUsers.owner && selectedUsers.owner.id !== userId) {
            alert('Owner sudah dipilih. Hapus owner yang ada terlebih dahulu jika ingin mengganti.');
            selectElement.value = '';
            setSelectedRolesInModal();
            return;
        }

        // Guard: replace leader with confirmation
        if (role === 'leader' && selectedUsers.leader && selectedUsers.leader.id !== userId) {
            if (!confirm('Leader sudah ada. Ganti leader?')) {
                selectElement.value = '';
                setSelectedRolesInModal();
                return;
            }
            selectedUsers.leader = null;
        }

        // Remove this user from any existing role
        if (selectedUsers.owner?.id  === userId) selectedUsers.owner  = null;
        if (selectedUsers.leader?.id === userId) selectedUsers.leader = null;
        selectedUsers.members = selectedUsers.members.filter(m => m.id !== userId);

        // Assign new role
        if      (role === 'owner')  selectedUsers.owner  = user;
        else if (role === 'leader') selectedUsers.leader = user;
        else if (role === 'member') selectedUsers.members.push(user);

        _syncAfterUserChange();
        setSelectedRolesInModal();
    }

    function confirmUserSelection() {
        _syncAfterUserChange();
        sessionStorage.removeItem('admin_project_modal_open');
        closeUserModal();
    }

    // ─── Form inputs ──────────────────────────────────────────────────────────

    function updateFormInputs() {
        document.getElementById('selected-owner-id').value = selectedUsers.owner?.id ?? '';

        // Effective leader: explicit leader, OR owner acts as leader when members exist
        const effectiveLeaderId = selectedUsers.leader?.id
            ?? (selectedUsers.owner && selectedUsers.members.length > 0 ? selectedUsers.owner.id : null);
        document.getElementById('selected-leader-id').value  = effectiveLeaderId ?? '';
        document.getElementById('selected-members-ids').value = selectedUsers.members.map(m => m.id).join(',');
    }

    // ─── Render selected users (main form) ───────────────────────────────────

    function getRoleDisplay(roleKey) {
        if (typeof window.translations !== 'undefined' && window.currentLang) {
            const pageData = window.translations[window.currentLang]?.dosen_add_pjt
                          ?? window.translations.id?.dosen_add_pjt;
            if (pageData?.[roleKey]) return pageData[roleKey];
        }
        return { owner_role: 'Owner', leader_role: 'Leader', member_role: 'Member', owner_leader_role: 'Owner & Leader' }[roleKey] ?? roleKey;
    }

    function renderSelectedUsers() {
        const container   = document.getElementById('selected-users-container');
        const noUsersMsg  = document.getElementById('no-users-message');
        if (!container || !noUsersMsg) return;

        const selected = [];

        if (selectedUsers.owner) {
            const ownerIsAlsoLeader = selectedUsers.leader?.id === selectedUsers.owner.id;
            const ownerActsAsLeader = !selectedUsers.leader && selectedUsers.members.length > 0;
            const roleKey = (ownerIsAlsoLeader || ownerActsAsLeader) ? 'owner_leader_role' : 'owner_role';
            selected.push({ ...selectedUsers.owner, role: roleKey });
        }

        if (selectedUsers.leader && selectedUsers.leader.id !== selectedUsers.owner?.id) {
            selected.push({ ...selectedUsers.leader, role: 'leader_role' });
        }

        selectedUsers.members.forEach(m => selected.push({ ...m, role: 'member_role' }));

        if (!selected.length) {
            container.innerHTML = '';
            noUsersMsg.classList.remove('hidden');
            return;
        }

        noUsersMsg.classList.add('hidden');
        container.innerHTML = selected.map(user => {
            const roleDisplay   = getRoleDisplay(user.role);
            const avatarHtml    = user.photo_profile
                ? `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800">`
                : `<div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center ring-2 ring-white dark:ring-gray-800">
                       <span class="font-semibold text-current">${user.nama_mahasiswa.charAt(0).toUpperCase()}</span>
                   </div>`;
            return `
                <div class="flex items-center justify-between p-4 border rounded-2xl bg-blue-50 dark:bg-blue-950 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200">
                    <div class="flex items-center gap-3">
                        ${avatarHtml}
                        <div>
                            <div class="font-medium">${roleDisplay}: ${user.nama_mahasiswa}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">${user.email}</div>
                        </div>
                    </div>
                    <button type="button" onclick="window.removeUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>`;
        }).join('');
    }

    function removeUser(userId) {
        if (selectedUsers.owner?.id  === userId) selectedUsers.owner  = null;
        if (selectedUsers.leader?.id === userId) selectedUsers.leader = null;
        selectedUsers.members = selectedUsers.members.filter(m => m.id !== userId);
        _syncAfterUserChange();
    }

    // ─── Internal sync helper (DRY) ───────────────────────────────────────────

    function _syncAfterUserChange() {
        updateFormInputs();
        renderSelectedUsers();
        updateTaskSectionVisibility();
        updateTaskUserOptions();
        updateSelectedUsersBadge();
        saveSelectedUsersToStorage();
    }

    // ─── Load initial user state ──────────────────────────────────────────────

    function loadSelectedUsersFromForm() {
        if (restoreSelectedUsersFromStorage()) {
            updateFormInputs();
            return;
        }
        const ownerId   = document.getElementById('selected-owner-id')?.value;
        const leaderId  = document.getElementById('selected-leader-id')?.value;
        const memberIds = (document.getElementById('selected-members-ids')?.value || '')
                            .split(',').filter(Boolean);

        if (ownerId)  selectedUsers.owner   = getUserById(ownerId);
        if (leaderId) selectedUsers.leader  = getUserById(leaderId);
        selectedUsers.members = memberIds.map(getUserById).filter(Boolean);
    }

    function getUserById(id) {
        return allUsers.find(u => String(u.id) === String(id)) ?? null;
    }

    // ─── Task helpers ─────────────────────────────────────────────────────────

    function getAllowedTaskUsers() {
        const users = [];
        const seen  = new Set();
        const push  = user => {
            if (!user || seen.has(user.id)) return;
            seen.add(user.id);
            users.push({ id: user.id, name: user.nama_mahasiswa });
        };
        push(selectedUsers.owner);
        push(selectedUsers.leader);
        selectedUsers.members.forEach(push);
        return users;
    }

    function renderTaskUserOptions(selectedId = '') {
        const users = getAllowedTaskUsers();
        return '<option value="" data-translate="pick_rsp" data-translate-page="dosen_add_pjt">-- Pilih Penanggung Jawab --</option>'
            + users.map(u => `<option value="${u.id}" ${String(u.id) === String(selectedId) ? 'selected' : ''}>${u.name}</option>`).join('');
    }

    function addTaskRow(taskData = null) {
        const tasksContainer = document.getElementById('tasks-container');
        if (!tasksContainer) return;

        const index    = taskIndex++;
        const userId   = taskData?.user_id ?? '';
        const taskName = taskData?.name_task ? taskData.name_task.replace(/"/g, '&quot;') : '';
        const hiddenId = taskData?.id
            ? `<input type="hidden" name="tasks[${index}][id]" value="${taskData.id}">`
            : '';

        const taskItem = document.createElement('div');
        taskItem.className = 'task-item p-4 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900';
        taskItem.innerHTML = `
            ${hiddenId}
            <div class="grid gap-4 md:grid-cols-3 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"
                           data-translate="rsp_task" data-translate-page="dosen_add_pjt">Penanggung Jawab</label>
                    <select name="tasks[${index}][user_id]"
                            class="task-user-select w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                        ${renderTaskUserOptions(userId)}
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"
                           data-translate="nm_task" data-translate-page="dosen_add_pjt">Nama Tugas</label>
                    <input type="text" name="tasks[${index}][name_task]" value="${taskName}"
                           class="task-name-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                           placeholder="Nama tugas..."
                           data-translate-placeholder="task_placeholder" data-translate-page="dosen_add_pjt">
                </div>
                <button type="button" onclick="window.removeTaskRow(this)"
                        class="self-start mt-6 px-4 py-3 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl"
                        data-translate="del" data-translate-page="dosen_add_pjt">Hapus</button>
            </div>`;

        tasksContainer.appendChild(taskItem);
        taskItem.querySelector('.task-user-select')
                ?.addEventListener('change', updateTaskUserOptions);

        if (typeof window.refreshTranslations === 'function') window.refreshTranslations();
    }

    function removeTaskRow(button) {
        button.closest('.task-item')?.remove();
        if (!document.querySelectorAll('.task-item').length) addTaskRow();
    }

    function cleanupInvalidTaskRows() {
        const allowed = new Set(getAllowedTaskUsers().map(u => String(u.id)));
        document.querySelectorAll('.task-item').forEach(item => {
            const sel = item.querySelector('.task-user-select');
            if (!sel || !sel.value || !allowed.has(sel.value)) item.remove();
        });
        if (!document.querySelectorAll('.task-item').length) addTaskRow();
    }

    function updateTaskUserOptions() {
        document.querySelectorAll('.task-user-select').forEach(select => {
            const current = select.value;
            select.innerHTML = renderTaskUserOptions(current);
            if (current) select.value = current;
        });
        cleanupInvalidTaskRows();
        if (typeof window.refreshTranslations === 'function') window.refreshTranslations();
    }

    function initializeTaskRows(existingTasks = []) {
        const tasksContainer = document.getElementById('tasks-container');
        if (!tasksContainer) return;
        tasksContainer.innerHTML = '';
        taskIndex = 0;

        const validTasks = Array.isArray(existingTasks)
            ? existingTasks.filter(t => t.user_id || t.name_task)
            : [];

        if (validTasks.length) {
            validTasks.forEach(addTaskRow);
        } else {
            addTaskRow();
        }
        updateTaskUserOptions();
    }

    // ─── Modal filters setup ──────────────────────────────────────────────────

    function setupModalFilters() {
        const searchInput = document.getElementById('modal-search');
        if (searchInput) {
            searchInput.addEventListener('input', () => {
                clearTimeout(filterTimer);
                filterTimer = setTimeout(applyUserFilters, 500);
            });
        }
        ['modal-angkatan', 'modal-jurusan', 'modal-keahlian'].forEach(id => {
            document.getElementById(id)?.addEventListener('change', applyUserFilters);
        });
    }

    // ─── Form submit guard ────────────────────────────────────────────────────

    function onSubmitProjectForm(event) {
        if (!selectedUsers.owner) {
            alert('Owner harus dipilih.');
            event.preventDefault();
            return;
        }
        updateFormInputs();
        cleanupInvalidTaskRows();
        updateTaskUserOptions();
    }

    // ─── Date validation ──────────────────────────────────────────────────────

    function setupDateValidation() {
        const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]');
        const tanggalAkhir = document.querySelector('input[name="tanggal_akhir"]');
        if (!tanggalMulai || !tanggalAkhir) return;

        tanggalMulai.addEventListener('change', function () {
            if (this.value) {
                const minEnd = new Date(this.value);
                minEnd.setDate(minEnd.getDate() + 1);
                const minEndStr = minEnd.toISOString().split('T')[0];
                tanggalAkhir.min = minEndStr;
                if (tanggalAkhir.value && tanggalAkhir.value < minEndStr) {
                    tanggalAkhir.value = '';
                }
            } else {
                tanggalAkhir.min = '';
            }
        });
    }

    // ─── Expose functions called from inline HTML ─────────────────────────────

    window.updateUserRole       = updateUserRole;
    window.removeUser           = removeUser;
    window.removeTaskRow        = removeTaskRow;
    window.addTaskRow           = addTaskRow;
    window.openUserModal        = openUserModal;
    window.closeUserModal       = closeUserModal;
    window.confirmUserSelection = confirmUserSelection;

    // ─── Boot ─────────────────────────────────────────────────────────────────

    document.addEventListener('DOMContentLoaded', function () {
        if (!hasOldData) localStorage.removeItem(storageKey);

        loadSelectedUsersFromForm();
        renderSelectedUsers();
        setupModalFilters();
        setupDateValidation();
        updateTaskSectionVisibility();
        updateSelectedUsersBadge();
        initializeTaskRows(oldTasks);

        document.getElementById('projectForm')
                ?.addEventListener('submit', onSubmitProjectForm);

        if (sessionStorage.getItem('admin_project_modal_open') === '1') {
            openUserModal();
        }
    });
};

/* ==========================================
   ADMIN VIEWS_EDIT_PROJECT.BLADE.PHP SCRIPTS
   ========================================== */
window.initAdminProjectEditPage = function (container) {
    const allUsers       = JSON.parse(container.dataset.users        || '[]');
    const existingTasks  = JSON.parse(container.dataset.existingTasks|| '[]');
    const initialOwner   = container.dataset.ownerId   || '';
    const initialLeader  = container.dataset.leaderId  || '';
    const initialMembers = container.dataset.memberIds || '';   // comma-separated
    const fetchUrl       = container.dataset.fetchUrl;          // route admin.projects.details

    const userSelectionStorageKey = 'admin_project_edit_selected_users';

    let selectedUsers = { owner: null, leader: null, members: [] };
    let taskIndex     = 0;
    let filterTimer   = null;

    // ── Helpers ──────────────────────────────────────────────────────
    function getUserById(id) {
        return allUsers.find(u => String(u.id) === String(id)) || null;
    }

    // ── Storage ───────────────────────────────────────────────────────
    function saveSelectedUsersToStorage() {
        localStorage.setItem(userSelectionStorageKey, JSON.stringify({
            owner:   selectedUsers.owner,
            leader:  selectedUsers.leader,
            members: selectedUsers.members,
        }));
    }

    function restoreSelectedUsersFromStorage() {
        const stored = localStorage.getItem(userSelectionStorageKey);
        if (!stored) return false;
        try {
            const parsed = JSON.parse(stored);
            if (parsed.owner)                      selectedUsers.owner   = parsed.owner;
            if (parsed.leader)                     selectedUsers.leader  = parsed.leader;
            if (Array.isArray(parsed.members))     selectedUsers.members = parsed.members;
            return true;
        } catch (e) {
            console.warn('Unable to restore selected users from storage:', e);
            return false;
        }
    }

    // ── Initialize selection from project data ────────────────────────
    function initializeSelectedUsersFromProject() {
        if (initialOwner) {
            const owner = getUserById(initialOwner);
            if (owner) selectedUsers.owner = owner;
        }
        if (initialLeader && initialLeader !== initialOwner) {
            const leader = getUserById(initialLeader);
            if (leader) selectedUsers.leader = leader;
        }
        if (initialMembers) {
            initialMembers.split(',').forEach(id => {
                if (id && id !== initialOwner && id !== initialLeader) {
                    const member = getUserById(id);
                    if (member && !selectedUsers.members.some(m => m.id == member.id)) {
                        selectedUsers.members.push(member);
                    }
                }
            });
        }
    }

    function loadSelectedUsersFromForm() {
        const hasOldData = container.dataset.hasOldData === 'true';
        if (!hasOldData) {
            localStorage.removeItem(userSelectionStorageKey);
        }
        const restored = restoreSelectedUsersFromStorage();
        if (!restored) {
            initializeSelectedUsersFromProject();
        }
        updateFormInputs();
    }

    // ── Badge ─────────────────────────────────────────────────────────
    function updateSelectedUsersBadge() {
        const badge = document.getElementById('selected-users-badge');
        if (!badge) return;
        let count = 0;
        if (selectedUsers.owner) count++;
        if (selectedUsers.leader && selectedUsers.leader.id !== selectedUsers.owner?.id) count++;
        count += selectedUsers.members.length;
        badge.innerHTML = count === 0
            ? ''
            : `<span class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full text-xs font-semibold">${count} user(s) terpilih</span>`;
    }

    // ── Task section visibility ───────────────────────────────────────
    function updateTaskSectionVisibility() {
        const taskSection = document.getElementById('task-section');
        if (!taskSection) return;
        const hasUsers = selectedUsers.owner || selectedUsers.leader || selectedUsers.members.length > 0;
        if (hasUsers) {
            taskSection.classList.remove('hidden');
        } else {
            taskSection.classList.add('hidden');
            const tContainer = document.getElementById('tasks-container');
            if (tContainer) { tContainer.innerHTML = ''; taskIndex = 0; }
        }
    }

    // ── Modal ─────────────────────────────────────────────────────────
    window.openUserModal = function () {
        document.getElementById('userModal').classList.remove('hidden');
        sessionStorage.setItem('admin_project_edit_modal_open', '1');
        document.getElementById('modal-search').value     = '';
        document.getElementById('modal-angkatan').value   = '';
        document.getElementById('modal-jurusan').value    = '';
        document.getElementById('modal-keahlian').value   = '';
        applyUserFilters();
    };

    window.closeUserModal = function () {
        document.getElementById('userModal').classList.add('hidden');
        sessionStorage.removeItem('admin_project_edit_modal_open');
    };

    // ── AJAX filter / pagination ──────────────────────────────────────
    function applyUserFilters() {
        const search    = document.getElementById('modal-search')?.value.trim()    || '';
        const angkatan  = document.getElementById('modal-angkatan')?.value         || '';
        const jurusan   = document.getElementById('modal-jurusan')?.value          || '';
        const keahlian  = document.getElementById('modal-keahlian')?.value         || '';

        const params = new URLSearchParams();
        if (search)   params.set('search',   search);
        if (angkatan) params.set('angkatan', angkatan);
        if (jurusan)  params.set('jurusan',  jurusan);
        if (keahlian) params.set('keahlian', keahlian);

        fetch(`${fetchUrl}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        })
            .then(r => r.json())
            .then(data => {
                document.getElementById('modal-user-list').innerHTML          = data.userListHtml;
                document.getElementById('modal-pagination-container').innerHTML = data.paginationHtml;
                setSelectedRolesInModal();
                if (typeof window.refreshTranslations === 'function') window.refreshTranslations();
                attachPaginationListeners();
            })
            .catch(err => console.error('Error loading users:', err));
    }

    function attachPaginationListeners() {
        document.querySelectorAll('[data-pagination-group="admin_project_user_selection"] .pagination-link').forEach(link => {
            const newLink = link.cloneNode(true);
            link.parentNode.replaceChild(newLink, link);
            newLink.addEventListener('click', function (e) {
                e.preventDefault();
                fetch(this.href, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                })
                    .then(r => r.json())
                    .then(data => {
                        document.getElementById('modal-user-list').innerHTML             = data.userListHtml;
                        document.getElementById('modal-pagination-container').innerHTML  = data.paginationHtml;
                        setSelectedRolesInModal();
                        if (typeof window.refreshTranslations === 'function') window.refreshTranslations();
                        attachPaginationListeners();
                    })
                    .catch(err => console.error('Error loading page:', err));
            });
        });
    }

    function setupModalFilters() {
        const searchInput   = document.getElementById('modal-search');
        const angkatanSel   = document.getElementById('modal-angkatan');
        const jurusanSel    = document.getElementById('modal-jurusan');
        const keahlianSel   = document.getElementById('modal-keahlian');

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(filterTimer);
                filterTimer = setTimeout(applyUserFilters, 500);
            });
        }
        if (angkatanSel) angkatanSel.addEventListener('change', applyUserFilters);
        if (jurusanSel)  jurusanSel.addEventListener('change',  applyUserFilters);
        if (keahlianSel) keahlianSel.addEventListener('change', applyUserFilters);
    }

    // ── Modal role selects ────────────────────────────────────────────
    function setSelectedRolesInModal() {
        document.querySelectorAll('#modal-user-list .user-role-select').forEach(select => {
            let userId = null;
            const onchangeAttr = select.getAttribute('onchange');
            if (onchangeAttr) {
                const match = onchangeAttr.match(/updateUserRole\(this,\s*(\d+),/);
                if (match) userId = parseInt(match[1]);
            }
            if (!userId && select.hasAttribute('data-user-id')) {
                userId = parseInt(select.getAttribute('data-user-id'));
            }
            if (!userId) return;

            const isOwner  = selectedUsers.owner?.id  === userId;
            const isLeader = selectedUsers.leader?.id === userId && !isOwner;
            const isMember = selectedUsers.members.some(m => m.id === userId);

            const ownerOpt  = select.querySelector('option[value="owner"]');
            const leaderOpt = select.querySelector('option[value="leader"]');
            const memberOpt = select.querySelector('option[value="member"]');

            if (ownerOpt)  ownerOpt.disabled  = selectedUsers.owner  !== null && !isOwner;
            if (leaderOpt) leaderOpt.disabled  = selectedUsers.leader !== null && !isLeader && (!selectedUsers.owner || selectedUsers.owner.id !== userId);
            if (memberOpt) memberOpt.disabled  = isOwner || isLeader;

            select.value = isOwner ? 'owner' : isLeader ? 'leader' : isMember ? 'member' : '';
        });
    }

    // ── Role update (called by onchange in modal list HTML) ───────────
    window.updateUserRole = function (selectElement, userId, role) {
        const userDiv      = selectElement.closest('.flex.items-center.justify-between');
        if (!userDiv) return;
        const img          = userDiv.querySelector('img');
        const photo_profile = img?.src ? img.src.split('/storage/')[1] : null;
        const nameDiv      = userDiv.querySelector('.font-medium');
        const nama_mahasiswa = nameDiv ? nameDiv.textContent.trim() : 'Unknown';
        const emailDiv     = userDiv.querySelector('.text-sm');
        const email        = emailDiv ? emailDiv.textContent.trim() : '';
        const user         = { id: userId, nama_mahasiswa, email, photo_profile };

        if (role === 'owner') {
            if (selectedUsers.owner && selectedUsers.owner.id !== userId) {
                if (!confirm('Owner sudah dipilih. Ganti owner yang ada?')) {
                    selectElement.value = '';
                    setSelectedRolesInModal();
                    return;
                }
                if (selectedUsers.leader?.id === selectedUsers.owner.id) {
                    selectedUsers.leader = null;
                }
                selectedUsers.owner = null;
            }
            selectedUsers.owner   = user;
            selectedUsers.members = selectedUsers.members.filter(m => m.id !== userId);

        } else if (role === 'leader') {
            if (selectedUsers.leader && selectedUsers.leader.id !== userId && selectedUsers.leader.id !== selectedUsers.owner?.id) {
                if (!confirm('Leader sudah dipilih. Ganti leader yang ada?')) {
                    selectElement.value = '';
                    setSelectedRolesInModal();
                    return;
                }
                if (selectedUsers.leader?.id !== selectedUsers.owner?.id) {
                    selectedUsers.leader = null;
                }
            }
            if (selectedUsers.owner?.id === userId) {
                selectedUsers.leader = user;
                // Jangan ubah select ke 'owner', biarkan owner tetap
            } else {
                selectedUsers.leader  = user;
                selectedUsers.members = selectedUsers.members.filter(m => m.id !== userId);
            }

        } else if (role === 'member') {
            if (selectedUsers.owner?.id === userId) {
                alert('User ini adalah Owner, tidak bisa dijadikan Member.');
                selectElement.value = 'owner';
                setSelectedRolesInModal();
                return;
            }
            if (selectedUsers.leader?.id === userId) {
                alert('User ini adalah Leader, tidak bisa dijadikan Member.');
                selectElement.value = 'leader';
                setSelectedRolesInModal();
                return;
            }
            if (!selectedUsers.members.some(m => m.id === userId)) {
                selectedUsers.members.push(user);
            }

        } else { // '' → hapus dari semua role
            if (selectedUsers.owner?.id === userId) {
                selectedUsers.owner = null;
                if (selectedUsers.leader?.id === userId) selectedUsers.leader = null;
            }
            if (selectedUsers.leader?.id === userId && selectedUsers.leader?.id !== selectedUsers.owner?.id) {
                selectedUsers.leader = null;
            }
            selectedUsers.members = selectedUsers.members.filter(m => m.id !== userId);
        }

        updateFormInputs();
        renderSelectedUsers();
        updateTaskSectionVisibility();
        updateTaskUserOptions();
        updateSelectedUsersBadge();
        saveSelectedUsersToStorage();
        setSelectedRolesInModal();
    };

    window.confirmUserSelection = function () {
        updateFormInputs();
        renderSelectedUsers();
        updateTaskSectionVisibility();
        updateTaskUserOptions();
        updateSelectedUsersBadge();
        saveSelectedUsersToStorage();
        sessionStorage.removeItem('admin_project_edit_modal_open');
        window.closeUserModal();
    };

    // ── Form inputs sync ──────────────────────────────────────────────
    function updateFormInputs() {
        const ownerEl   = document.getElementById('selected-owner-id');
        const leaderEl  = document.getElementById('selected-leader-id');
        const membersEl = document.getElementById('selected-members-ids');

        if (ownerEl) ownerEl.value = selectedUsers.owner?.id || '';

        let leaderId = '';
        if (selectedUsers.leader) {
            leaderId = selectedUsers.leader.id;
        }
        if (leaderEl) leaderEl.value = leaderId;

        const memberIds = selectedUsers.members
            .filter(m => m.id !== selectedUsers.owner?.id && m.id !== selectedUsers.leader?.id)
            .map(m => m.id)
            .join(',');
        if (membersEl) membersEl.value = memberIds;

        // Array hidden inputs
        document.querySelectorAll('input[name="members[]"]').forEach(i => i.remove());
        if (memberIds) {
            memberIds.split(',').forEach(id => {
                const input    = document.createElement('input');
                input.type     = 'hidden';
                input.name     = 'members[]';
                input.value    = id;
                document.getElementById('projectForm').appendChild(input);
            });
        }
    }

    // ── Render selected users list ────────────────────────────────────
    function renderSelectedUsers() {
        const containerEl  = document.getElementById('selected-users-container');
        const noUsersMsg   = document.getElementById('no-users-message');
        if (!containerEl || !noUsersMsg) return;

        const selected = [];

        if (selectedUsers.owner) {
            const isAlsoLeader = !selectedUsers.leader || selectedUsers.leader.id === selectedUsers.owner.id;
            selected.push({
                ...selectedUsers.owner,
                role: (isAlsoLeader && selectedUsers.members.length > 0) ? 'Owner & Leader' : 'Owner',
            });
        }
        if (selectedUsers.leader && (!selectedUsers.owner || selectedUsers.leader.id !== selectedUsers.owner.id)) {
            selected.push({ ...selectedUsers.leader, role: 'Leader' });
        }
        selectedUsers.members.forEach(member => {
            if (member.id !== selectedUsers.owner?.id && member.id !== selectedUsers.leader?.id) {
                selected.push({ ...member, role: 'Member' });
            }
        });

        if (!selected.length) {
            containerEl.innerHTML = '';
            noUsersMsg.classList.remove('hidden');
            return;
        }

        noUsersMsg.classList.add('hidden');

        const styleMap = {
            'Owner':          'bg-blue-50  dark:bg-blue-950  border-blue-200  dark:border-blue-800  text-blue-800  dark:text-blue-200',
            'Leader':         'bg-green-50 dark:bg-green-950 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
            'Owner & Leader': 'bg-teal-50  dark:bg-teal-950  border-teal-200  dark:border-teal-800  text-teal-800  dark:text-teal-200',
            'Member':         'bg-purple-50 dark:bg-purple-950 border-purple-200 dark:border-purple-800 text-purple-800 dark:text-purple-200',
        };

        containerEl.innerHTML = selected.map(user => {
            const style  = styleMap[user.role] || 'bg-gray-50 dark:bg-gray-950 border-gray-200 text-gray-800';
            const avatar = user.photo_profile
                ? `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800">`
                : `<div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center ring-2 ring-white dark:ring-gray-800">
                       <span class="font-semibold text-current">${user.nama_mahasiswa.charAt(0).toUpperCase()}</span>
                   </div>`;

            const removeBtn = user.role !== 'Owner'
                ? `<button type="button" onclick="removeUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80">
                       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                       </svg>
                   </button>`
                : '';

            return `
                <div class="flex items-center justify-between p-4 border rounded-2xl ${style}">
                    <div class="flex items-center gap-3">
                        ${avatar}
                        <div>
                            <div class="font-medium">${user.role}: ${user.nama_mahasiswa}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">${user.email || ''}</div>
                        </div>
                    </div>
                    ${removeBtn}
                </div>`;
        }).join('');
    }

    window.removeUser = function (userId) {
        if (selectedUsers.owner?.id === userId) {
            selectedUsers.owner = null;
            if (selectedUsers.leader?.id === userId) selectedUsers.leader = null;
        }
        if (selectedUsers.leader?.id === userId && selectedUsers.leader?.id !== selectedUsers.owner?.id) {
            selectedUsers.leader = null;
        }
        selectedUsers.members = selectedUsers.members.filter(m => m.id !== userId);

        updateFormInputs();
        renderSelectedUsers();
        updateTaskSectionVisibility();
        updateTaskUserOptions();
        updateSelectedUsersBadge();
        saveSelectedUsersToStorage();
    };

    // ── Task rows ─────────────────────────────────────────────────────
    function getAllowedTaskUsers() {
        const users = [];
        const added = new Set();
        const add = user => {
            if (!user || added.has(user.id)) return;
            added.add(user.id);
            users.push({ id: user.id, name: user.nama_mahasiswa });
        };
        add(selectedUsers.owner);
        if (selectedUsers.leader && selectedUsers.leader.id !== selectedUsers.owner?.id) add(selectedUsers.leader);
        selectedUsers.members.forEach(add);
        return users;
    }

    function renderTaskUserOptions(selectedId = '') {
        const users = getAllowedTaskUsers();
        let html = '<option value="" data-translate="pick_rsp" data-translate-page="dosen_add_pjt">-- Pilih Penanggung Jawab --</option>';
        users.forEach(user => {
            html += `<option value="${user.id}" ${String(user.id) === String(selectedId) ? 'selected' : ''}>${user.name}</option>`;
        });
        return html;
    }

    window.addTaskRow = function (taskData = null) {
        const tContainer = document.getElementById('tasks-container');
        if (!tContainer) return;

        const index    = taskIndex++;
        const userId   = taskData?.user_id   ?? '';
        const taskName = taskData?.name_task  ? taskData.name_task.replace(/"/g, '&quot;') : '';
        const hiddenId = taskData?.id
            ? `<input type="hidden" name="tasks[${index}][id]" value="${taskData.id}">`
            : '';

        const taskItem = document.createElement('div');
        taskItem.className = 'task-item p-4 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900';
        taskItem.innerHTML = `
            ${hiddenId}
            <div class="grid gap-4 md:grid-cols-3 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2" data-translate="rsp_task" data-translate-page="dosen_add_pjt">Penanggung Jawab</label>
                    <select name="tasks[${index}][user_id]" class="task-user-select w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                        ${renderTaskUserOptions(userId)}
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2" data-translate="nm_task" data-translate-page="dosen_add_pjt">Nama Tugas</label>
                    <input type="text" name="tasks[${index}][name_task]" value="${taskName}"
                        class="task-name-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                        placeholder="Nama tugas..."
                        data-translate-placeholder="task_placeholder" data-translate-page="dosen_add_pjt">
                </div>
                <button type="button" onclick="removeTaskRow(this)"
                    class="self-start mt-6 px-4 py-3 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl"
                    data-translate="del" data-translate-page="dosen_add_pjt">Hapus</button>
            </div>`;

        tContainer.appendChild(taskItem);
        const select = taskItem.querySelector('.task-user-select');
        if (select) select.addEventListener('change', updateTaskUserOptions);

        if (typeof window.refreshTranslations === 'function') window.refreshTranslations();
    };

    window.removeTaskRow = function (button) {
        const taskItem = button.closest('.task-item');
        if (taskItem) taskItem.remove();
        if (!document.querySelectorAll('.task-item').length) window.addTaskRow();
    };

    function cleanupInvalidTaskRows() {
        const allowedIds = getAllowedTaskUsers().map(u => String(u.id));
        document.querySelectorAll('.task-item').forEach(taskItem => {
            const select = taskItem.querySelector('.task-user-select');
            if (!select || !select.value || !allowedIds.includes(select.value)) taskItem.remove();
        });
        if (!document.querySelectorAll('.task-item').length) window.addTaskRow();
    }

    function updateTaskUserOptions() {
        document.querySelectorAll('.task-user-select').forEach(select => {
            const currentValue = select.value;
            select.innerHTML = renderTaskUserOptions(currentValue);
            if (currentValue) select.value = currentValue;
        });
        cleanupInvalidTaskRows();
        if (typeof window.refreshTranslations === 'function') window.refreshTranslations();
    }

    function initializeTaskRows() {
        const tContainer = document.getElementById('tasks-container');
        if (!tContainer) return;
        tContainer.innerHTML = '';
        taskIndex = 0;

        if (Array.isArray(existingTasks) && existingTasks.length) {
            existingTasks.forEach(task => {
                if (task.user_id || task.name_task) window.addTaskRow(task);
            });
        } else {
            window.addTaskRow();
        }
        updateTaskUserOptions();
    }

    // ── Date validation ───────────────────────────────────────────────
    function setupDateValidation() {
        const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]');
        const tanggalAkhir = document.querySelector('input[name="tanggal_akhir"]');
        if (!tanggalMulai || !tanggalAkhir) return;

        tanggalMulai.addEventListener('change', function () {
            if (this.value) {
                const startDate   = new Date(this.value);
                const minEndDate  = new Date(startDate);
                minEndDate.setDate(startDate.getDate() + 1);
                const minStr      = minEndDate.toISOString().split('T')[0];
                tanggalAkhir.min  = minStr;
                if (tanggalAkhir.value && tanggalAkhir.value < minStr) tanggalAkhir.value = '';
            } else {
                tanggalAkhir.min = '';
            }
        });
    }

    // ── Form submit ───────────────────────────────────────────────────
    function onSubmitProjectForm(event) {
        if (!selectedUsers.owner) {
            alert('Owner harus dipilih.');
            event.preventDefault();
            return;
        }
        updateFormInputs();
        cleanupInvalidTaskRows();
        updateTaskUserOptions();
    }

    // ── Boot ──────────────────────────────────────────────────────────
    loadSelectedUsersFromForm();
    renderSelectedUsers();
    setupModalFilters();
    updateTaskSectionVisibility();
    updateSelectedUsersBadge();
    initializeTaskRows();

    document.getElementById('projectForm')?.addEventListener('submit', onSubmitProjectForm);
    setupDateValidation();

    if (sessionStorage.getItem('admin_project_edit_modal_open') === '1') {
        window.openUserModal();
    }

    if (typeof window.showPageInfo === 'function') {
        window.showPageInfo('popup.edit_project');
    }
};

if (typeof window.Alpine?.start === 'function' && !window.__alpineStarted) {
    window.Alpine.start();
    window.__alpineStarted = true;
}

/* ==========================================
   MOBILE BOTTOM NAVIGATION JS
   ========================================== */
window.fabOpen = false;
window.profileOpen = false;
window.guestOpen = false;

function showOverlay(el) {
    if (!el) return;
    el.classList.remove('hidden');
    el.style.pointerEvents = 'auto';
    // Trigger reflow
    void el.offsetWidth;
    el.classList.remove('opacity-0');
}

function hideOverlay(el) {
    if (!el) return;
    el.style.pointerEvents = 'none';
    el.classList.add('opacity-0');
    setTimeout(() => { if (el.classList.contains('opacity-0')) el.classList.add('hidden'); }, 300);
}

function openSheet(sheet, overlay) {
    if (!sheet) return;
    sheet.classList.remove('hidden');
    void sheet.offsetWidth;
    sheet.classList.remove('translate-y-full');
    if (overlay) showOverlay(overlay);
}

function closeSheet(sheet, overlay) {
    if (!sheet) return;
    sheet.classList.add('translate-y-full');
    if (overlay) hideOverlay(overlay);
}

window.toggleMobileFab = function () {
    window.fabOpen ? window.closeMobileFab() : window.openMobileFab();
}
window.openMobileFab = function () {
    window.fabOpen = true;
    openSheet(document.getElementById('mobile-fab-sheet'), document.getElementById('mobile-fab-overlay'));
    const icon = document.getElementById('mobile-fab-icon');
    if (icon) icon.style.transform = 'rotate(45deg)';
}
window.closeMobileFab = function () {
    window.fabOpen = false;
    closeSheet(document.getElementById('mobile-fab-sheet'), document.getElementById('mobile-fab-overlay'));
    const icon = document.getElementById('mobile-fab-icon');
    if (icon) icon.style.transform = 'rotate(0deg)';
}

window.toggleMobileProfile = function () {
    window.profileOpen ? window.closeMobileProfile() : window.openMobileProfile();
}
window.openMobileProfile = function () {
    window.profileOpen = true;
    openSheet(document.getElementById('mobile-profile-sheet'), document.getElementById('mobile-profile-overlay'));
}
window.closeMobileProfile = function () {
    window.profileOpen = false;
    closeSheet(document.getElementById('mobile-profile-sheet'), document.getElementById('mobile-profile-overlay'));
}

window.toggleGuestSheet = function () {
    window.guestOpen ? window.closeGuestSheet() : window.openGuestSheet();
}
window.openGuestSheet = function () {
    window.guestOpen = true;
    openSheet(document.getElementById('guest-sheet'), document.getElementById('guest-sheet-overlay'));
}
window.closeGuestSheet = function () {
    window.guestOpen = false;
    closeSheet(document.getElementById('guest-sheet'), document.getElementById('guest-sheet-overlay'));
}

window.toggleMobileDarkMode = function () {
    window.toggleDarkMode();
}
window.toggleGuestDarkMode = function () {
    window.toggleDarkMode();
}

// NOTE: window.changeLanguageMobile & window.changeGuestLanguage sudah
// didefinisikan di modules/core/helpers.js (dimuat lebih awal di app.js).
// Jangan didefinisikan ulang di sini — definisi lama di file ini tidak
// menghapus query string `locale` dan tidak menyimpan pilihan bahasa ke
// cookie/localStorage sebelum redirect, sehingga selector bahasa di mobile
// navigation kadang balik lagi ke /id.

/* Swipe to close */
document.addEventListener('DOMContentLoaded', () => {
    const sheets = [
        { id: 'mobile-fab-sheet', close: window.closeMobileFab },
        { id: 'mobile-profile-sheet', close: window.closeMobileProfile },
        { id: 'guest-sheet', close: window.closeGuestSheet }
    ];
    sheets.forEach(({ id, close }) => {
        const el = document.getElementById(id);
        if (!el) return;
        let startY = 0, currentY = 0, dragging = false;
        el.addEventListener('touchstart', e => {
            startY = e.touches[0].clientY;
            dragging = true;
            el.style.transition = 'none';
        }, { passive: true });
        el.addEventListener('touchmove', e => {
            if (!dragging) return;
            currentY = e.touches[0].clientY;
            const delta = Math.max(0, currentY - startY);
            el.style.transform = 'translateY(' + delta + 'px)';
        }, { passive: true });
        el.addEventListener('touchend', () => {
            dragging = false;
            el.style.transition = '';
            if (currentY - startY > 80) close();
            else el.style.transform = '';
        });
    });
});
