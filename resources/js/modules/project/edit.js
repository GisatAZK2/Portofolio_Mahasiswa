/**
 * modules/project/edit.js
 * Project Edit Page IIFE — exposes window.initProjectEditPage.
 */
(function () {
    // ----- state -----
    let allUsersMap = new Map();
    let allUsersArray = [];
    let pendingUsers = { owner: null, leader: null, members: [] };
    let selectedUsers = { owner: null, leader: null, members: [] };
    let taskIndex = 0;
    let currentModalFilters = { search: '', angkatan: '', jurusan: '', keahlian: '' };
    let tempSelectedRoles = {};
    let isCollaborativeMode = false;
    let projectData = null;

    // ----- helpers -----
    function addUsersToMap(users) {
        if (!Array.isArray(users)) return;
        users.forEach(user => {
            if (user && user.id && !allUsersMap.has(String(user.id))) {
                allUsersMap.set(String(user.id), user);
                allUsersArray.push(user);
            }
        });
    }

    function getUserById(id) {
        const userId = String(id);
        if (allUsersMap.has(userId)) {
            return allUsersMap.get(userId);
        }
        return null;
    }

    // ----- pending / selected sync -----
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

    function saveCurrentModalRoles() {
        tempSelectedRoles = {};
        document.querySelectorAll('.user-role-select').forEach(select => {
            const userId = select.dataset.userId;
            if (userId && select.value) {
                tempSelectedRoles[userId] = select.value;
            }
        });
    }

    function restoreModalRoles() {
        document.querySelectorAll('.user-role-select').forEach(select => {
            const userId = select.dataset.userId;
            if (userId && tempSelectedRoles[userId]) {
                select.value = tempSelectedRoles[userId];
            }
        });
        refreshLeaderOptions();
    }

    // ----- task functions -----
    function getAllowedTaskUsers(additionalUsers = []) {
        const users = [];
        const added = new Set();
        const add = user => {
            if (!user || added.has(String(user.id))) return;
            added.add(String(user.id));
            users.push({ id: user.id, name: user.nama_mahasiswa });
        };

        add(selectedUsers.owner);
        add(selectedUsers.leader);
        selectedUsers.members.forEach(add);
        additionalUsers.forEach(add);
        return users;
    }

    function renderTaskUserOptions(selectedId = '') {
        const additionalUsers = [];
        let fallbackName = null;

        if (selectedId) {
            const selectedTaskUser = getUserById(selectedId);
            if (selectedTaskUser) {
                additionalUsers.push(selectedTaskUser);
            } else {
                const taskData = (projectData?.existingTasks || []).find(t => String(t.user_id) === String(selectedId));
                if (taskData && taskData.user_name) {
                    fallbackName = taskData.user_name;
                }
            }
        }

        const users = getAllowedTaskUsers(additionalUsers);
        let html = '<option value="">-- Pilih Penanggung Jawab --</option>';
        users.forEach(user => {
            html += `<option value="${user.id}" ${String(user.id) === String(selectedId) ? 'selected' : ''}>${user.name}</option>`;
        });

        if (selectedId && fallbackName && !users.some(u => String(u.id) === String(selectedId))) {
            html += `<option value="${selectedId}" selected>${fallbackName}</option>`;
        }

        return html;
    }

    function addTaskRow(taskData = null) {
        const container = document.getElementById('tasks-container');
        if (!container) return;

        const index = taskIndex++;
        const userId = taskData?.user_id ?? '';
        const taskName = taskData?.name_task ? taskData.name_task.replace(/"/g, '&quot;') : '';
        const hiddenId = taskData?.id ? `<input type="hidden" name="tasks[${index}][id]" value="${taskData.id}">` : '';
        const isDone = taskData?.is_done ? true : false;

        const taskItem = document.createElement('div');
        taskItem.className = 'task-item p-4 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900';

        if (!isCollaborativeMode) {
            const currentUser = projectData?.currentUser || { id: '', nama_mahasiswa: 'Unknown' };
            taskItem.innerHTML = `
            ${hiddenId}
            <input type="hidden" name="tasks[${index}][user_id]" value="${currentUser.id}">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <!-- Kolom 1: Penanggung Jawab (readonly) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Penanggung Jawab</label>
                    <input type="text" value="${currentUser.nama_mahasiswa} (Owner)"
                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-sm"
                           readonly disabled>
                </div>

                <!-- Kolom 2: Nama Tugas + Checkbox -->
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
                    <button type="button" onclick="window.removeTaskRow(this)"
                            class="px-4 py-2.5 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl text-sm font-medium hover:bg-red-200 dark:hover:bg-red-900 transition">
                        Hapus
                    </button>
                </div>
            </div>
        `;
        } else {
            taskItem.innerHTML = `
            ${hiddenId}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <!-- Kolom 1: Penanggung Jawab (select) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Penanggung Jawab</label>
                    <select name="tasks[${index}][user_id]" class="task-user-select w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm">
                        ${renderTaskUserOptions(userId)}
                    </select>
                </div>

                <!-- Kolom 2: Nama Tugas + Checkbox -->
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
                    <button type="button" onclick="window.removeTaskRow(this)"
                            class="px-4 py-2.5 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl text-sm font-medium hover:bg-red-200 dark:hover:bg-red-900 transition">
                        Hapus
                    </button>
                </div>
            </div>
        `;
        }

        container.appendChild(taskItem);

        if (isCollaborativeMode) {
            const select = taskItem.querySelector('.task-user-select');
            if (select) select.addEventListener('change', () => updateTaskUserOptions());
        }
    }

    function removeTaskRow(button) {
        const taskItem = button.closest('.task-item');
        if (taskItem) taskItem.remove();
        if (!document.querySelectorAll('.task-item').length) addTaskRow();
    }

    function updateTaskUserOptions() {
        if (!isCollaborativeMode) return;
        setTimeout(() => {
            document.querySelectorAll('.task-user-select').forEach(select => {
                const currentValue = select.value;
                const newOptions = renderTaskUserOptions(currentValue);
                select.innerHTML = newOptions;
                if (currentValue) select.value = currentValue;
            });
        }, 50);
    }

    function saveCurrentTasks() {
        const tasks = [];
        document.querySelectorAll('.task-item').forEach(taskItem => {
            const userIdInput = taskItem.querySelector('input[name$="[user_id]"], select[name$="[user_id]"]');
            const taskNameInput = taskItem.querySelector('input[name$="[name_task]"]');
            const taskIdInput = taskItem.querySelector('input[name$="[id]"]');
            const isDoneInput = taskItem.querySelector('input.task-is-done[type="checkbox"]'); // ← TAMBAHKAN

            let userId = null;
            if (userIdInput) { userId = userIdInput.value; }

            const taskName = taskNameInput ? taskNameInput.value : '';
            const taskId = taskIdInput ? taskIdInput.value : null;
            const isDone = isDoneInput ? isDoneInput.checked : false; // ← TAMBAHKAN

            if (taskName) {
                tasks.push({
                    id: taskId,
                    user_id: userId || currentUser.id,
                    name_task: taskName,
                    is_done: isDone, // ← TAMBAHKAN
                });
            }
        });
        return tasks;
    }

    function restoreTasks(tasks) {
        const container = document.getElementById('tasks-container');
        if (!container) return;
        container.innerHTML = '';
        taskIndex = 0;
        if (tasks && tasks.length > 0) {
            tasks.forEach(task => {
                if (task.name_task) addTaskRow(task);
            });
        }
        if (container.children.length === 0) addTaskRow();
        if (isCollaborativeMode) setTimeout(() => updateTaskUserOptions(), 100);
    }

    // ----- modal / ajax -----
    function fetchUsers(page = 1) {
        if (!projectData) return;
        const params = new URLSearchParams({
            id: projectData.projectId,
            page: page,
            search: currentModalFilters.search,
            angkatan: currentModalFilters.angkatan,
            jurusan: currentModalFilters.jurusan,
            keahlian: currentModalFilters.keahlian
        });

        const url = `/${locale}/projectUser/edit?${params}`;
        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(response => response.json())
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
                        const user = {
                            id: parseInt(userId),
                            nama_mahasiswa: nameEl.textContent.trim(),
                            email: emailEl ? emailEl.textContent.trim() : '',
                            photo_profile: imgEl ? imgEl.getAttribute('src')?.replace('/storage/', '') : null
                        };
                        usersInPage.push(user);
                    }
                });

                addUsersToMap(usersInPage);

                document.getElementById('modal-user-list').innerHTML = data.userListHtml;
                document.getElementById('modal-pagination').innerHTML = data.paginationHtml;

                attachRoleSelectEvents();
                restoreModalRoles();

                document.querySelectorAll('.user-role-select').forEach(select => {
                    const userId = select.dataset.userId;
                    if (pendingUsers.leader && String(pendingUsers.leader.id) === String(userId)) {
                        select.value = 'leader';
                    } else if (pendingUsers.members.some(m => String(m.id) === String(userId))) {
                        select.value = 'member';
                    }
                });

                refreshLeaderOptions();
                if (typeof window.refreshTranslations === 'function') window.refreshTranslations();
            })
            .catch(error => console.error('Error fetching users:', error));
    }

    function attachRoleSelectEvents() {
        document.querySelectorAll('.user-role-select').forEach(select => {
            const userId = select.getAttribute('data-user-id');
            if (userId) {
                select.removeEventListener('change', select._handler);
                const handler = function () {
                    updateUserRole(this, userId, this.value);
                    saveCurrentModalRoles();
                };
                select.addEventListener('change', handler);
                select._handler = handler;
            }
        });
    }

    function updateUserRole(selectElement, userId, role) {
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
            refreshLeaderOptions();
            return;
        }

        if (role === 'leader' && pendingUsers.leader && String(pendingUsers.leader.id) !== String(userId)) {
            const confirmChange = confirm(`Anda yakin ingin mengganti leader dari "${pendingUsers.leader.nama_mahasiswa}" menjadi "${user.nama_mahasiswa}"?`);
            if (!confirmChange) {
                selectElement.value = isCurrentLeader ? 'leader' : '';
                refreshLeaderOptions();
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

        refreshLeaderOptions();
    }

    function refreshLeaderOptions() {
        const leaderId = pendingUsers.leader ? String(pendingUsers.leader.id) : null;
        document.querySelectorAll('.user-role-select').forEach(select => {
            const userId = select.dataset.userId;
            const isOwner = pendingUsers.owner && String(pendingUsers.owner.id) === String(userId);
            const leaderOption = select.querySelector('option[value="leader"]');
            if (!leaderOption) return;
            if (isOwner) {
                select.disabled = true;
                select.value = 'leader';
                return;
            }
            select.disabled = false;
            if (leaderId && String(userId) !== String(leaderId)) {
                leaderOption.disabled = true;
                leaderOption.textContent = 'Leader (Sudah Dipilih)';
                if (select.value === 'leader') {
                    select.value = '';
                }
            } else {
                leaderOption.disabled = false;
                leaderOption.textContent = 'Leader';
            }
        });
    }

    // ----- modal open/close -----
    function openUserModal() {
        const toggle = document.getElementById('project-collaborative-toggle');
        if (!toggle || !toggle.checked) {
            alert('Harap aktifkan mode kolaboratif terlebih dahulu untuk menambah user.');
            return;
        }
        syncPendingFromSelected();
        document.getElementById('userModal').classList.remove('hidden');
        fetchUsers(1);
    }

    function closeUserModal() {
        document.getElementById('userModal').classList.add('hidden');
    }

    function confirmUserSelection() {
        applyPendingToSelected();
        updateFormInputs();
        renderSelectedUsers();
        // Update tasks without losing data
        const currentTasks = saveCurrentTasks();
        isCollaborativeMode = true;
        restoreTasks(currentTasks);
        closeUserModal();
    }

    // ----- form / ui -----
    function updateFormInputs() {
        const ownerId = selectedUsers.owner?.id || (projectData?.currentUser?.id || '');
        document.getElementById('selected-owner-id').value = ownerId;
        document.getElementById('selected-leader-id').value = selectedUsers.leader?.id || '';

        const container = document.getElementById('members-hidden-container');
        if (!container) return;
        container.innerHTML = '';
        selectedUsers.members.forEach(member => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'members[]';
            input.value = member.id;
            container.appendChild(input);
        });
    }

    function renderSelectedUsers() {
        const container = document.getElementById('selected-users-container');
        const noMsg = document.getElementById('no-users-message');
        if (!container || !noMsg) return;

        const selected = [];
        if (selectedUsers.owner) {
            const isOwnerAsLeader = selectedUsers.leader && selectedUsers.leader.id === selectedUsers.owner.id;
            const role = isOwnerAsLeader ? 'Owner & Leader' : 'Owner';
            selected.push({ ...selectedUsers.owner, role, isOwner: true });
        } else if (projectData?.currentUser) {
            selected.push({ ...projectData.currentUser, role: 'Owner', isOwner: true });
        }

        if (selectedUsers.leader && (!selectedUsers.owner || selectedUsers.owner.id !== selectedUsers.leader.id)) {
            selected.push({ ...selectedUsers.leader, role: 'Leader', isOwner: false });
        }

        selectedUsers.members.forEach(member => {
            selected.push({ ...member, role: 'Member', isOwner: false });
        });

        if (selected.length === 0) {
            container.innerHTML = '';
            noMsg.classList.remove('hidden');
            return;
        }

        noMsg.classList.add('hidden');
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

    function removeUser(userId) {
        if (selectedUsers.owner && String(selectedUsers.owner.id) === String(userId)) {
            alert('Owner tidak dapat dihapus dari project.');
            return;
        }

        const affectedTaskItems = Array.from(document.querySelectorAll('.task-item')).filter(taskItem => {
            const input = taskItem.querySelector('input[name$="[user_id]"], select[name$="[user_id]"]');
            return input && input.value && String(input.value) === String(userId);
        });

        if (selectedUsers.leader && String(selectedUsers.leader.id) === String(userId)) {
            selectedUsers.leader = null;
        }
        selectedUsers.members = selectedUsers.members.filter(m => String(m.id) !== String(userId));

        
        affectedTaskItems.forEach(taskItem => taskItem.remove());
        if (!document.querySelectorAll('.task-item').length) addTaskRow();

        updateFormInputs();
        renderSelectedUsers();
        updateTaskUserOptions();
    }

    function loadSelectedUsersFromForm() {
        if (!projectData) return;
        selectedUsers.owner = projectData.currentUser || null;

        const leaderId = projectData.leaderId;
        if (leaderId && String(leaderId) !== String(projectData.currentUser?.id)) {
            let leader = getUserById(leaderId);
            if (!leader && projectData.existingTasks) {
                const taskUser = projectData.existingTasks.find(t => String(t.user_id) === String(leaderId));
                if (taskUser && taskUser.user_name) {
                    leader = { id: leaderId, nama_mahasiswa: taskUser.user_name };
                }
            }
            selectedUsers.leader = leader || null;
        } else {
            selectedUsers.leader = null;
        }

        selectedUsers.members = [];
        const memberIds = projectData.memberIds || [];
        memberIds.forEach(memberId => {
            if (String(memberId) === String(projectData.currentUser?.id)) return;
            if (selectedUsers.leader && String(memberId) === String(selectedUsers.leader.id)) return;
            let member = getUserById(memberId);
            if (!member && projectData.existingTasks) {
                const taskUser = projectData.existingTasks.find(t => String(t.user_id) === String(memberId));
                if (taskUser && taskUser.user_name) {
                    member = { id: memberId, nama_mahasiswa: taskUser.user_name };
                }
            }
            if (member) selectedUsers.members.push(member);
        });

        syncPendingFromSelected();
    }

    // ----- toggle collaborative mode -----
    function toggleUserSelectionSection() {
        const toggle = document.getElementById('project-collaborative-toggle');
        const userSelectionSection = document.getElementById('user-selection-section');
        const taskSection = document.getElementById('task-section');

        if (toggle && userSelectionSection) {
            if (toggle.checked) {
                if (!isCollaborativeMode) {
                    const currentTasks = saveCurrentTasks();
                    isCollaborativeMode = true;
                    userSelectionSection.style.display = 'block';
                    if (taskSection) taskSection.classList.remove('hidden');
                    restoreTasks(currentTasks);
                } else {
                    userSelectionSection.style.display = 'block';
                    if (taskSection) taskSection.classList.remove('hidden');
                }
            } else {
                if (isCollaborativeMode) {
                    const currentTasks = saveCurrentTasks();
                    isCollaborativeMode = false;
                    userSelectionSection.style.display = 'none';
                    if (taskSection) taskSection.classList.remove('hidden');

                    selectedUsers.owner = projectData?.currentUser || null;
                    selectedUsers.leader = null;
                    selectedUsers.members = [];
                    updateFormInputs();
                    renderSelectedUsers();
                    restoreTasks(currentTasks);
                } else {
                    userSelectionSection.style.display = 'none';
                    if (taskSection) taskSection.classList.remove('hidden');
                }
            }
        }
    }

    // ----- date validation -----
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

    // ----- modal filters -----
    function setupModalFilters() {
        const searchInput = document.getElementById('modal-search');
        const angkatanSelect = document.getElementById('modal-angkatan');
        const jurusanSelect = document.getElementById('modal-jurusan');
        const keahlianSelect = document.getElementById('modal-keahlian');

        const fetchWithSave = (page) => {
            saveCurrentModalRoles();
            fetchUsers(page);
        };

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                currentModalFilters.search = this.value;
                fetchWithSave(1);
            });
        }
        if (angkatanSelect) {
            angkatanSelect.addEventListener('change', function () {
                currentModalFilters.angkatan = this.value;
                fetchWithSave(1);
            });
        }
        if (jurusanSelect) {
            jurusanSelect.addEventListener('change', function () {
                currentModalFilters.jurusan = this.value;
                fetchWithSave(1);
            });
        }
        if (keahlianSelect) {
            keahlianSelect.addEventListener('change', function () {
                currentModalFilters.keahlian = this.value;
                fetchWithSave(1);
            });
        }
    }

    // ----- pagination (delegated) -----
    document.addEventListener('click', function (e) {
        const link = e.target.closest('#modal-pagination a');
        if (link) {
            e.preventDefault();
            const url = new URL(link.href);
            const page = url.searchParams.get('page') || 1;
            saveCurrentModalRoles();
            fetchUsers(page);
        }
    });

    // ----- initialize tasks -----
    function initializeTaskRows() {
        const container = document.getElementById('tasks-container');
        if (!container) return;
        container.innerHTML = '';
        taskIndex = 0;

        const tasks = projectData?.existingTasks || [];
        if (tasks.length) {
            tasks.forEach(task => addTaskRow(task));
        }
        if (container.children.length === 0) addTaskRow();

        if (isCollaborativeMode) setTimeout(() => updateTaskUserOptions(), 100);
    }

    // ----- expose to window (for onclick) -----
    window.openUserModal = openUserModal;
    window.closeUserModal = closeUserModal;
    window.confirmUserSelection = confirmUserSelection;
    window.addTaskRow = addTaskRow;
    window.removeTaskRow = removeTaskRow;
    window.updateUserRole = updateUserRole;
    window.fetchUsers = fetchUsers;
    window.refreshLeaderOptions = refreshLeaderOptions;
    window.applyPendingToSelected = applyPendingToSelected;
    window.syncPendingFromSelected = syncPendingFromSelected;
    window.saveCurrentModalRoles = saveCurrentModalRoles;
    window.restoreModalRoles = restoreModalRoles;
    window.getUserById = getUserById;
    window.addUsersToMap = addUsersToMap;
    window.renderSelectedUsers = renderSelectedUsers;
    window.updateFormInputs = updateFormInputs;
    window.removeUser = removeUser;
    window.loadSelectedUsersFromForm = loadSelectedUsersFromForm;
    window.toggleUserSelectionSection = toggleUserSelectionSection;
    window.setupDateValidation = setupDateValidation;
    window.setupModalFilters = setupModalFilters;
    window.initializeTaskRows = initializeTaskRows;
    window.saveCurrentTasks = saveCurrentTasks;
    window.restoreTasks = restoreTasks;
    window.updateTaskUserOptions = updateTaskUserOptions;
    window.renderTaskUserOptions = renderTaskUserOptions;
    window.getAllowedTaskUsers = getAllowedTaskUsers;
    window.attachRoleSelectEvents = attachRoleSelectEvents;

    // ----- auto-init when DOM ready -----
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('project-edit-data');
        if (!container) return;

        projectData = {
            users: JSON.parse(container.dataset.users || '[]'),
            currentUser: JSON.parse(container.dataset.currentUser || 'null'),
            existingTasks: JSON.parse(container.dataset.existingTasks || '[]'),
            projectId: container.dataset.projectId,
            leaderId: container.dataset.leaderId || null,
            memberIds: JSON.parse(container.dataset.memberIds || '[]')
        };

        addUsersToMap(projectData.users);
        if (projectData.currentUser) addUsersToMap([projectData.currentUser]);

        loadSelectedUsersFromForm();
        renderSelectedUsers();

        const toggle = document.getElementById('project-collaborative-toggle');
        const label = document.getElementById('toggle-label');
        if (toggle && label) {
            const hasMembers = selectedUsers.members.length > 0 || selectedUsers.leader;
            toggle.checked = hasMembers;
            label.textContent = hasMembers ? 'Aktif' : 'Nonaktif';
            isCollaborativeMode = hasMembers;
            toggleUserSelectionSection();
            initializeTaskRows();
            toggle.addEventListener('change', function () {
                label.textContent = this.checked ? 'Aktif' : 'Nonaktif';
                toggleUserSelectionSection();
            });
        } else {
            initializeTaskRows();
        }

        setupDateValidation();
        setupModalFilters();
    });
})();

