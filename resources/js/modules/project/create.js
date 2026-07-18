/**
 * modules/project/create.js
 * Project Create Page IIFE — exposes window.initProjectCreatePage.
 * Termasuk duplicate project checker.
 */
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const dataEl = document.getElementById('project-create-data');
        if (!dataEl) return;

        // Ambil data dari dataset
        const currentUser = JSON.parse(dataEl.dataset.currentUser || 'null');
        const oldTasks = JSON.parse(dataEl.dataset.oldTasks || '[]');
        const routeCreate = dataEl.dataset.routeCreate || '';
        const isRetry = dataEl.dataset.isRetry === 'true';
        const checkDuplicateNameUrl = dataEl.dataset.checkDuplicateNameUrl || '';

        // ------------------------------------------------------------------
        // Semua fungsi dan state di-bungkus dalam scope agar tidak global
        // ------------------------------------------------------------------
        let allUsers = [];
        let allUsersMap = new Map();
        let currentModalFilters = { search: '', angkatan: '', jurusan: '', keahlian: '' };
        let currentPage = 1;
        let selectedUsers = { owner: null, leader: null, members: [] };
        let pendingUsers = { owner: null, leader: null, members: [] };
        let taskIndex = 0;
        const userSelectionStorageKey = 'project_selected_users';

        // ----- Storage helpers -----
        function saveSelectedUsersToStorage() {
            localStorage.setItem(userSelectionStorageKey, JSON.stringify({
                owner: selectedUsers.owner,
                leader: selectedUsers.leader,
                members: selectedUsers.members
            }));
        }

        function restoreSelectedUsersFromStorage() {
            const stored = localStorage.getItem(userSelectionStorageKey);
            if (!stored) return false;
            try {
                const parsed = JSON.parse(stored);
                if (parsed.owner) selectedUsers.owner = parsed.owner;
                if (parsed.leader) selectedUsers.leader = parsed.leader;
                if (Array.isArray(parsed.members)) selectedUsers.members = parsed.members;
                return true;
            } catch (e) {
                console.warn('Unable to restore selected users:', e);
                return false;
            }
        }

        // ----- allUsers helpers -----
        function addUsersToAllUsers(usersArray) {
            if (!Array.isArray(usersArray)) return;
            usersArray.forEach(user => {
                if (!allUsersMap.has(String(user.id))) {
                    allUsersMap.set(String(user.id), user);
                    allUsers.push(user);
                }
            });
        }

        function getUserById(id) {
            const userId = String(id);
            return allUsersMap.has(userId) ? allUsersMap.get(userId) : null;
        }

        // ----- pendingUsers helpers -----
        function syncPendingFromSelected() {
            pendingUsers = {
                owner: selectedUsers.owner ? { ...selectedUsers.owner } : null,
                leader: selectedUsers.leader ? { ...selectedUsers.leader } : null,
                members: selectedUsers.members.map(m => ({ ...m }))
            };
        }

        function applyPendingToSelected() {
            selectedUsers.owner = pendingUsers.owner ? { ...pendingUsers.owner } : null;
            selectedUsers.leader = pendingUsers.leader ? { ...pendingUsers.leader } : null;
            selectedUsers.members = pendingUsers.members.map(m => ({ ...m }));
        }

        function discardPending() {
            pendingUsers = { owner: null, leader: null, members: [] };
        }

        // ----- Modal open/close -----
        window.openUserModal = function () {
            const toggle = document.getElementById('project-collaborative-toggle');
            if (!toggle || !toggle.checked) {
                alert('Harap aktifkan mode kolaboratif terlebih dahulu untuk menambah user.');
                return;
            }
            syncPendingFromSelected();
            document.getElementById('userModal').classList.remove('hidden');
            fetchUsers(1);
        };

        window.closeUserModal = function () {
            document.getElementById('userModal').classList.add('hidden');
            discardPending();
        };

        window.cancelUserModal = function () {
            discardPending();
            document.getElementById('userModal').classList.add('hidden');
        };

        window.confirmUserSelection = function () {
            applyPendingToSelected();
            updateFormInputs();
            renderSelectedUsers();
            updateTaskSectionVisibility();
            updateTaskUserOptions();
            updateSelectedUsersBadge();
            saveSelectedUsersToStorage();
            document.getElementById('userModal').classList.add('hidden');
            discardPending();
        };

        // ----- Fetch users (AJAX) -----
        function fetchUsers(page = 1) {
            currentPage = page;
            const params = new URLSearchParams({
                page: page,
                search: currentModalFilters.search,
                angkatan: currentModalFilters.angkatan,
                jurusan: currentModalFilters.jurusan,
                keahlian: currentModalFilters.keahlian
            });

            fetch(`${routeCreate}?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(data => {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.userListHtml;
                    const userElements = tempDiv.querySelectorAll('[data-user-id]');
                    const usersInPage = [];
                    userElements.forEach(el => {
                        const userId = el.getAttribute('data-user-id');
                        const nameEl = el.querySelector('.font-medium');
                        const emailEl = el.querySelector('.text-sm.text-gray-500');
                        const imgEl = el.querySelector('img');
                        if (userId && nameEl) {
                            usersInPage.push({
                                id: parseInt(userId),
                                nama_mahasiswa: nameEl.textContent.trim(),
                                email: emailEl ? emailEl.textContent.trim() : '',
                                photo_profile: imgEl ? imgEl.getAttribute('src')?.replace('/storage/', '') : null
                            });
                        }
                    });
                    addUsersToAllUsers(usersInPage);
                    renderUserListWithRoles(data.userListHtml);
                    document.getElementById('modal-pagination').innerHTML = data.paginationHtml;
                    refreshRoleSelections();
                    if (typeof window.refreshTranslations === 'function') window.refreshTranslations();
                });
        }

        function renderUserListWithRoles(html) {
            const container = document.getElementById('modal-user-list');
            container.innerHTML = html;
            document.querySelectorAll('.user-role-select').forEach(select => {
                const userId = String(select.dataset.userId);
                const user = getUserById(userId);
                if (user) {
                    const userDiv = select.closest('[data-user-id]');
                    if (userDiv) {
                        const nameDiv = userDiv.querySelector('.font-medium');
                        if (nameDiv && nameDiv.textContent !== user.nama_mahasiswa) {
                            nameDiv.textContent = user.nama_mahasiswa;
                        }
                    }
                }
            });
        }

        // ----- Role assignment -----
        window.updateUserRole = function (selectElement, userId, role) {
            const user = getUserById(userId);
            if (!user) {
                console.error('User not found:', userId);
                return;
            }
            const isOwner = pendingUsers.owner && String(pendingUsers.owner.id) === String(userId);
            const isCurrentLeader = pendingUsers.leader && String(pendingUsers.leader.id) === String(userId);

            if (isOwner && role === 'member') {
                alert('Owner tidak bisa menjadi member.');
                selectElement.value = isCurrentLeader ? 'leader' : '';
                refreshRoleSelections();
                return;
            }

            if (role === 'leader' && pendingUsers.leader && String(pendingUsers.leader.id) !== String(userId)) {
                if (!confirm(`Anda yakin ingin mengganti leader dari "${pendingUsers.leader.nama_mahasiswa}" menjadi "${user.nama_mahasiswa}"?`)) {
                    selectElement.value = isCurrentLeader ? 'leader' : '';
                    refreshRoleSelections();
                    return;
                }
                pendingUsers.leader = null;
            }

            if (pendingUsers.leader && String(pendingUsers.leader.id) === String(userId)) {
                pendingUsers.leader = null;
            }
            pendingUsers.members = pendingUsers.members.filter(m => String(m.id) !== String(userId));

            if (role === 'leader') {
                pendingUsers.leader = user;
            } else if (role === 'member') {
                if (!pendingUsers.members.some(m => String(m.id) === String(userId))) {
                    pendingUsers.members.push(user);
                }
            }
            refreshRoleSelections();
        };

        function refreshRoleSelections() {
            const leaderId = pendingUsers.leader ? String(pendingUsers.leader.id) : null;
            const ownerId = pendingUsers.owner ? String(pendingUsers.owner.id) : null;

            document.querySelectorAll('.user-role-select').forEach(select => {
                const userId = String(select.dataset.userId);
                const isOwner = ownerId === userId;
                const isLeader = leaderId === userId;
                const isMember = pendingUsers.members.some(m => String(m.id) === userId);

                if (isLeader) {
                    select.value = 'leader';
                } else if (isMember) {
                    select.value = 'member';
                } else {
                    select.value = '';
                }

                const leaderOption = select.querySelector('option[value="leader"]');
                const memberOption = select.querySelector('option[value="member"]');

                if (leaderOption) {
                    leaderOption.disabled = !!(leaderId && leaderId !== userId && !isLeader);
                    leaderOption.title = leaderOption.disabled ? 'Leader sudah dipilih' : '';
                }
                if (memberOption) {
                    memberOption.disabled = false;
                }
                select.disabled = false;
            });
        }

        // ----- Form inputs & rendering -----
        function updateSelectedUsersBadge() {
            const badge = document.getElementById('selected-users-badge');
            if (!badge) return;
            let count = 0;
            if (selectedUsers.owner) count++;
            if (selectedUsers.leader) count++;
            count += selectedUsers.members.length;
            badge.innerHTML = count === 0
                ? ''
                : `<span class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full text-xs font-semibold">${count} user(s) terpilih</span>`;
        }

        function updateTaskSectionVisibility() {
            const taskSection = document.getElementById('task-section');
            if (!taskSection) return;
            const hasUsers = selectedUsers.owner || selectedUsers.leader || selectedUsers.members.length > 0;
            if (hasUsers) {
                taskSection.classList.remove('hidden');
            } else {
                taskSection.classList.add('hidden');
                const container = document.getElementById('tasks-container');
                if (container) { container.innerHTML = ''; taskIndex = 0; }
            }
        }

        function updateFormInputs() {
            document.getElementById('selected-owner-id').value = selectedUsers.owner?.id || '';
            document.getElementById('selected-leader-id').value = selectedUsers.leader?.id || '';
            document.getElementById('selected-members-ids').value = selectedUsers.members.map(m => m.id).join(',');

            const memberInputs = document.getElementById('selected-members-inputs');
            if (memberInputs) {
                memberInputs.innerHTML = selectedUsers.members
                    .map(member => `<input type="hidden" name="members[]" value="${member.id}">`)
                    .join('');
            }
        }

        function renderSelectedUsers() {
            const container = document.getElementById('selected-users-container');
            const noUsersMsg = document.getElementById('no-users-message');
            if (!container || !noUsersMsg) return;

            const selected = [];
            if (selectedUsers.owner) {
                const role = selectedUsers.leader && selectedUsers.leader.id === selectedUsers.owner.id ? 'Owner & Leader' : 'Owner';
                selected.push({ ...selectedUsers.owner, role });
            }
            if (selectedUsers.leader && (!selectedUsers.owner || selectedUsers.owner.id !== selectedUsers.leader.id)) {
                selected.push({ ...selectedUsers.leader, role: 'Leader' });
            }
            selectedUsers.members.forEach(member => selected.push({ ...member, role: 'Member' }));

            if (!selected.length) {
                container.innerHTML = '';
                noUsersMsg.classList.remove('hidden');
                return;
            }

            noUsersMsg.classList.add('hidden');
            // Di dalam fungsi renderSelectedUsers()
            container.innerHTML = selected.map(user => {
                const styles = {
                    'Owner': 'bg-blue-50 dark:bg-blue-950 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
                    'Leader': 'bg-green-50 dark:bg-green-950 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
                    'Owner & Leader': 'bg-teal-50 dark:bg-teal-950 border-teal-200 dark:border-teal-800 text-teal-800 dark:text-teal-200',
                    'Member': 'bg-purple-50 dark:bg-purple-950 border-purple-200 dark:border-purple-800 text-purple-800 dark:text-purple-200'
                }[user.role] || 'bg-gray-50 dark:bg-gray-950 border-gray-200 dark:border-gray-800 text-gray-800 dark:text-gray-200';

                return `
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 border rounded-2xl ${styles}">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            ${user.photo_profile
                        ? `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800 flex-shrink-0">`
                        : `<div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center ring-2 ring-white dark:ring-gray-800 flex-shrink-0">
                                    <span class="font-semibold text-current">${user.nama_mahasiswa.charAt(0).toUpperCase()}</span>
                                </div>`
                    }
                            <div class="min-w-0 flex-1">
                                <div class="font-medium break-words">${user.role}: ${user.nama_mahasiswa}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 break-all">${user.email || ''}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 mt-2 sm:mt-0 flex-shrink-0">
                            <button type="button" onclick="editUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80" title="Edit Role">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            ${user.role !== 'Owner' ? `
                            <button type="button" onclick="removeUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80" title="Remove">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>` : ''}
                        </div>
                    </div>
                `;
            }).join('');
        }

        function deleteTasksForUser(userId) {
            const userIdStr = String(userId);
            document.querySelectorAll('.task-item').forEach(taskItem => {
                const select = taskItem.querySelector('.task-user-select');
                if (select && String(select.value) === userIdStr) taskItem.remove();
            });
            if (!document.querySelectorAll('.task-item').length) addTaskRow();
        }

        window.removeUser = function (userId) {
            deleteTasksForUser(userId);
            if (selectedUsers.leader?.id == userId) selectedUsers.leader = null;
            selectedUsers.members = selectedUsers.members.filter(m => String(m.id) !== String(userId));
            updateFormInputs();
            renderSelectedUsers();
            updateTaskUserOptions();
            updateSelectedUsersBadge();
            saveSelectedUsersToStorage();
        };

        window.editUser = function (userId) {
            window.openUserModal();
            setTimeout(() => {
                const userElement = document.querySelector(`.user-role-select[data-user-id="${userId}"]`)?.closest('[data-user-id]');
                if (userElement) {
                    userElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    userElement.style.backgroundColor = '#fef3c7';
                    setTimeout(() => userElement.style.backgroundColor = '', 2000);
                }
            }, 500);
        };

        // ----- Task helpers -----
        function getAllowedTaskUsers() {
            const users = [];
            const added = new Set();
            const add = user => {
                if (!user || added.has(user.id)) return;
                added.add(user.id);
                users.push({ id: user.id, name: user.nama_mahasiswa });
            };
            add(selectedUsers.owner);
            add(selectedUsers.leader);
            selectedUsers.members.forEach(add);
            return users;
        }

        function renderTaskUserOptions(selectedId = '') {
            const users = getAllowedTaskUsers();
            let html = '<option value=""> Pilih Penanggung Jawab </option>';
            users.forEach(user => {
                html += `<option value="${user.id}" ${String(user.id) === String(selectedId) ? 'selected' : ''}>${user.name}</option>`;
            });
            return html;
        }

        window.addTaskRow = function (taskData = null) {
            const container = document.getElementById('tasks-container');
            if (!container) return;
            const index = taskIndex++;
            const userId = taskData?.user_id ?? '';
            const taskName = taskData?.name_task ? taskData.name_task.replace(/"/g, '&quot;') : '';
            const hiddenId = taskData?.id ? `<input type="hidden" name="tasks[${index}][id]" value="${taskData.id}">` : '';
            const isDone = taskData?.is_done ? true : false;

            const taskItem = document.createElement('div');
            taskItem.className = 'task-item p-4 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900';
            taskItem.innerHTML = `
                ${hiddenId}
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <!-- Kolom 1: Penanggung Jawab -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Penanggung Jawab</label>
                        <select name="tasks[${index}][user_id]" class="task-user-select w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm" required>
                            ${renderTaskUserOptions(userId)}
                        </select>
                    </div>

                    <!-- Kolom 2: Nama Tugas + Checkbox (di bawah input) -->
                    <div class="md:col-span-2 space-y-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nama Tugas</label>
                            <input type="text" name="tasks[${index}][name_task]" value="${taskName}"
                                class="task-name-input w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm"
                                placeholder="Deskripsikan tugas...">
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="tasks[${index}][is_done]" value="0">
                            <input type="checkbox" name="tasks[${index}][is_done]" value="1"
                                id="is_done_${index}"
                                class="task-is-done w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                ${isDone ? 'checked' : ''}>
                            <label for="is_done_${index}" class="text-sm text-gray-700 dark:text-gray-300">Tugas Selesai</label>
                        </div>
                    </div>

                    <!-- Kolom 3: Tombol Hapus -->
                    <div class="flex items-end justify-end md:justify-start">
                        <button type="button" onclick="removeTaskRow(this)"
                            class="px-4 py-2.5 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl text-sm font-medium hover:bg-red-200 dark:hover:bg-red-900 transition">
                            Hapus
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(taskItem);
            taskItem.querySelector('.task-user-select')?.addEventListener('change', updateTaskUserOptions);
        };

        window.removeTaskRow = function (button) {
            button.closest('.task-item')?.remove();
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
        }

        function initializeTaskRows(existingTasks = []) {
            const container = document.getElementById('tasks-container');
            if (!container) return;
            container.innerHTML = '';
            taskIndex = 0;
            if (Array.isArray(existingTasks) && existingTasks.length) {
                existingTasks.forEach(task => { if (task.user_id || task.name_task) window.addTaskRow(task); });
            } else {
                window.addTaskRow();
            }
            updateTaskUserOptions();
        }

        // ----- Modal filters -----
        function setupModalFilters() {
            document.getElementById('modal-search')?.addEventListener('input', function () {
                currentModalFilters.search = this.value;
                fetchUsers(1);
            });
            document.getElementById('modal-angkatan')?.addEventListener('change', function () {
                currentModalFilters.angkatan = this.value;
                fetchUsers(1);
            });
            document.getElementById('modal-jurusan')?.addEventListener('change', function () {
                currentModalFilters.jurusan = this.value;
                fetchUsers(1);
            });
            document.getElementById('modal-keahlian')?.addEventListener('change', function () {
                currentModalFilters.keahlian = this.value;
                fetchUsers(1);
            });
        }

        // ----- Pagination click (delegated) -----
        document.addEventListener('click', function (e) {
            const link = e.target.closest('#modal-pagination a');
            if (link) {
                e.preventDefault();
                const url = link.getAttribute('href');
                if (!url) return;
                const page = new URL(url).searchParams.get('page') || 1;
                fetchUsers(page);
            }
        });

        // ----- Collaborative toggle -----
        function toggleUserSelectionSection() {
            const toggle = document.getElementById('project-collaborative-toggle');
            const userSelectionSection = document.getElementById('user-selection-section');

            if (!toggle || !userSelectionSection) return;

            if (toggle.checked) {
                userSelectionSection.style.display = 'block';
                if (!selectedUsers.owner && currentUser) {
                    selectedUsers.owner = currentUser;
                    updateFormInputs();
                }
                if (
                    selectedUsers.leader &&
                    selectedUsers.owner &&
                    String(selectedUsers.leader.id) === String(selectedUsers.owner.id) &&
                    selectedUsers.members.length === 0
                ) {
                    selectedUsers.leader = null;
                    updateFormInputs();
                    renderSelectedUsers();
                    updateSelectedUsersBadge();
                    saveSelectedUsersToStorage();
                }
            } else {
                userSelectionSection.style.display = 'none';
                selectedUsers.owner = currentUser;
                selectedUsers.leader = currentUser;
                selectedUsers.members = [];
                updateFormInputs();
                renderSelectedUsers();
                updateTaskUserOptions();
                updateSelectedUsersBadge();
                saveSelectedUsersToStorage();
            }
            updateTaskSectionVisibility();
        }

        // ----- Date validation -----
        function setupDateValidation() {
            const tanggalMulaiInput = document.getElementById('tanggal_mulai');
            const tanggalAkhirInput = document.getElementById('tanggal_akhir');
            if (!tanggalMulaiInput || !tanggalAkhirInput) return;

            if (tanggalMulaiInput.value) tanggalAkhirInput.min = tanggalMulaiInput.value;

            tanggalMulaiInput.addEventListener('change', function () {
                if (this.value) {
                    tanggalAkhirInput.min = this.value;
                    if (tanggalAkhirInput.value && tanggalAkhirInput.value < this.value) {
                        tanggalAkhirInput.value = '';
                    }
                } else {
                    tanggalAkhirInput.min = '';
                }
            });

            tanggalAkhirInput.addEventListener('change', function () {
                if (this.value && tanggalMulaiInput.value && this.value < tanggalMulaiInput.value) {
                    this.value = '';
                    alert('Tanggal selesai harus setelah atau sama dengan tanggal mulai.');
                }
            });
        }

        // ----- Duplicate project name checker -----
        let duplicateNameCheckTimer = null;
        let isDuplicateProjectName = false;

        function setSubmitDisabled(disabled) {
            const submitBtn = document.getElementById('submit-btn');
            if (submitBtn) submitBtn.disabled = disabled;
        }

        function showDuplicateNameWarning(message) {
            const warning = document.getElementById('duplicate-warning');
            const warningText = document.getElementById('duplicate-warning-text');
            if (warningText) warningText.textContent = message;
            if (warning) warning.classList.remove('hidden');
            const nameInput = document.getElementById('nama_project');
            if (nameInput) nameInput.classList.add('border-red-500');
        }

        function hideDuplicateNameWarning() {
            const warning = document.getElementById('duplicate-warning');
            if (warning) warning.classList.add('hidden');
            const nameInput = document.getElementById('nama_project');
            if (nameInput) nameInput.classList.remove('border-red-500');
        }

        function checkDuplicateProjectName(name) {
            if (!checkDuplicateNameUrl || !name.trim()) {
                isDuplicateProjectName = false;
                hideDuplicateNameWarning();
                setSubmitDisabled(false);
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            fetch(checkDuplicateNameUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ nama_project: name })
            })
                .then(res => res.json())
                .then(data => {
                    isDuplicateProjectName = !!data.is_duplicate;
                    if (isDuplicateProjectName) {
                        showDuplicateNameWarning(data.message || 'Nama project ini sudah digunakan.');
                    } else {
                        hideDuplicateNameWarning();
                    }
                    setSubmitDisabled(isDuplicateProjectName);
                })
                .catch(() => {
                    // Kalau gagal cek (jaringan dsb), jangan blok user - biarkan validasi server yang final.
                    isDuplicateProjectName = false;
                    hideDuplicateNameWarning();
                    setSubmitDisabled(false);
                });
        }

        function setupDuplicateNameCheck() {
            const nameInput = document.getElementById('nama_project');
            if (!nameInput) return;

            nameInput.addEventListener('input', function () {
                clearTimeout(duplicateNameCheckTimer);
                const value = this.value;
                duplicateNameCheckTimer = setTimeout(() => checkDuplicateProjectName(value), 400);
            });

            // Cek nilai awal (misal hasil restore dari validasi gagal sebelumnya)
            if (nameInput.value.trim()) {
                checkDuplicateProjectName(nameInput.value);
            }
        }

        // ----- Form submit -----
        function onSubmitProjectForm(e) {
            if (isDuplicateProjectName) {
                e.preventDefault();
                return;
            }
            if (currentUser) selectedUsers.owner = currentUser;
            if (selectedUsers.owner && !selectedUsers.leader && selectedUsers.members.length > 0) {
                selectedUsers.leader = selectedUsers.owner;
            }
            updateFormInputs();
            cleanupInvalidTaskRows();
            updateTaskUserOptions();
        }

        // ----- Load from storage / form -----
        function buildUserFallback(id) {
            return {
                id: id,
                nama_mahasiswa: `Anggota #${id}`,
                email: '',
                photo_profile: null
            };
        }

        function loadSelectedUsersFromForm() {
            selectedUsers.owner = currentUser || null;

            if (isRetry && restoreSelectedUsersFromStorage()) {
                updateFormInputs();
                return;
            }

            const leaderId = document.getElementById('selected-leader-id')?.value;
            const memberIds = (document.getElementById('selected-members-ids')?.value || '')
                .split(',')
                .map(id => String(id).trim())
                .filter(id => id);

            if (leaderId) {
                selectedUsers.leader = getUserById(leaderId) || buildUserFallback(leaderId);
            } else {
                selectedUsers.leader = null;
            }

            selectedUsers.members = memberIds
                .filter(id => String(id) !== String(leaderId))
                .filter(id => !currentUser || String(id) !== String(currentUser.id))
                .map(id => getUserById(id) || buildUserFallback(id));

            updateFormInputs();
        }

        // ----- Initialization -----
        if (currentUser) {
            addUsersToAllUsers([currentUser]);
        }

        loadSelectedUsersFromForm();
        renderSelectedUsers();
        setupModalFilters();
        setupDateValidation();
        setupDuplicateNameCheck();
        updateTaskSectionVisibility();
        updateSelectedUsersBadge();
        initializeTaskRows(oldTasks);

        document.getElementById('projectForm')?.addEventListener('submit', onSubmitProjectForm);

        const collaborativeToggle = document.getElementById('project-collaborative-toggle');
        const toggleLabel = document.getElementById('toggle-label');
        if (collaborativeToggle && toggleLabel) {
            const hasLeader = selectedUsers.leader && String(selectedUsers.leader.id) !== String(currentUser?.id);
            const hasMembers = selectedUsers.members.length > 0;
            collaborativeToggle.checked = hasLeader || hasMembers;
            toggleLabel.textContent = collaborativeToggle.checked ? 'Aktif' : 'Nonaktif';
            toggleUserSelectionSection();
            collaborativeToggle.addEventListener('change', function () {
                toggleLabel.textContent = this.checked ? 'Aktif' : 'Nonaktif';
                toggleUserSelectionSection();
            });
        }


    });
})();


// admin script
