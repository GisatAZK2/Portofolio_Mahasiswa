/**
 * modules/project/pages.js
 * Project user page (puPlayVideo), project detail (openImageModal/closeImageModal),
 * initProjectDetailPage (learning corner mass delete).
 */

// Close modal with ESC key
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        window.closeImageModal();
    }
});

window.initProjectDetailPage = function (container) {
    // Individual delete buttons
    container.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

            const form = this.closest('.delete-form');
            if (!form) return;

            const confirmed = await window.showConfirmAlert({
                title: 'Hapus Catatan?',
                text: 'Catatan ini akan dihapus permanen dan tidak bisa dikembalikan.',
                icon: 'warning',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
            });

            if (confirmed) {
                if (window.showLoading) window.showLoading('Menghapus catatan...');
                form.submit();
            }
        });
    });

    // Mass delete functionality
    const checkboxes = container.querySelectorAll('.entry-checkbox');
    const massDeleteBtn = document.getElementById('massDeleteBtn');
    const massDeleteIds = document.getElementById('massDeleteIds');
    const massDeleteForm = document.getElementById('massDeleteForm');
    const selectedCount = document.getElementById('selectedCount');

    if (checkboxes.length > 0 && massDeleteBtn && massDeleteForm && selectedCount) {
        const updateMassDeleteButton = () => {
            const checkedBoxes = container.querySelectorAll('.entry-checkbox:checked');
            const checkedCount = checkedBoxes.length;

            selectedCount.textContent = checkedCount;

            // Update hidden input with selected IDs
            const selectedIds = Array.from(checkedBoxes).map(cb => cb.dataset.id);
            massDeleteIds.value = JSON.stringify(selectedIds);

            if (checkedCount > 0) {
                massDeleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                massDeleteBtn.disabled = false;
            } else {
                massDeleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
                massDeleteBtn.disabled = true;
            }
        };

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateMassDeleteButton);
        });

        massDeleteBtn.addEventListener('click', async function () {
            const checkedCount = container.querySelectorAll('.entry-checkbox:checked').length;

            if (checkedCount === 0) return;

            const confirmed = await window.showConfirmAlert({
                title: 'Hapus Banyak Catatan?',
                text: `Anda akan menghapus ${checkedCount} catatan. Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                confirmButtonText: 'Ya, Hapus Semua',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
            });

            if (confirmed) {
                if (window.showLoading) window.showLoading('Menghapus catatan terpilih...');

                // Parse IDs from hidden input
                const ids = JSON.parse(massDeleteIds.value);

                // Create a new form with the IDs as array
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = massDeleteForm.action;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfInput);

                ids.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    if (typeof window.showPageInfo === 'function') {
        window.showPageInfo("popup.user_detail_project");
    }
};

/* ==========================================
   VIEWS_CREATE_PROJECT.BLADE.PHP SCRIPTS
   ========================================== */
window.initProjectCreatePage = function (container) {
    let allUsers = [];
    let allUsersMap = new Map();
    const currentUser = JSON.parse(container.dataset.currentUser || 'null');
    const userSelectionStorageKey = 'project_selected_users';

    let currentModalFilters = { search: '', angkatan: '', jurusan: '', keahlian: '' };
    let currentPage = 1;

    // State permanen (sudah di-confirm)
    let selectedUsers = { owner: null, leader: null, members: [] };

    // State sementara di dalam modal (sebelum confirm)
    let pendingUsers = { owner: null, leader: null, members: [] };

    let taskIndex = 0;

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
        if (allUsersMap.has(userId)) {
            return allUsersMap.get(userId);
        }
        return null;
    }

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

    window.openUserModal = function () {
        const toggle = document.getElementById('project-collaborative-toggle');
        if (!toggle || !toggle.checked) {
            alert('Harap aktifkan mode kolaboratif terlebih dahulu untuk menambah user.');
            return;
        }
        syncPendingFromSelected();
        document.getElementById('userModal').classList.remove('hidden');
        fetchUsers(1);
    }

    window.closeUserModal = function () {
        document.getElementById('userModal').classList.add('hidden');
        discardPending();
    }

    window.cancelUserModal = function () {
        discardPending();
        document.getElementById('userModal').classList.add('hidden');
    }

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
    }

    function fetchUsers(page = 1) {
        currentPage = page;
        const params = new URLSearchParams({
            page: page,
            search: currentModalFilters.search,
            angkatan: currentModalFilters.angkatan,
            jurusan: currentModalFilters.jurusan,
            keahlian: currentModalFilters.keahlian
        });

        const fetchUrl = container.dataset.fetchUrl;

        fetch(`${fetchUrl}?${params}`, {
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
                        const user = {
                            id: parseInt(userId),
                            nama_mahasiswa: nameEl.textContent.trim(),
                            email: emailEl ? emailEl.textContent.trim() : '',
                            photo_profile: imgEl ? imgEl.getAttribute('src')?.replace('/storage/', '') : null
                        };
                        usersInPage.push(user);
                    }
                });

                addUsersToAllUsers(usersInPage);
                renderUserListWithRoles(data.userListHtml);
                document.getElementById('modal-pagination').innerHTML = data.paginationHtml;
                refreshRoleSelections();

                if (typeof window.refreshTranslations === 'function') {
                    window.refreshTranslations();
                }
            });
    }

    function renderUserListWithRoles(html) {
        const uListContainer = document.getElementById('modal-user-list');
        uListContainer.innerHTML = html;

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
            const confirmChange = confirm(
                `Anda yakin ingin mengganti leader dari "${pendingUsers.leader.nama_mahasiswa}" menjadi "${user.nama_mahasiswa}"?`
            );
            if (!confirmChange) {
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
    }

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
            const tContainer = document.getElementById('tasks-container');
            if (tContainer) { tContainer.innerHTML = ''; taskIndex = 0; }
        }
    }

    function updateFormInputs() {
        const ownerEl = document.getElementById('selected-owner-id');
        const leaderEl = document.getElementById('selected-leader-id');
        const membersEl = document.getElementById('selected-members-ids');
        if (ownerEl) ownerEl.value = selectedUsers.owner?.id || '';
        if (leaderEl) leaderEl.value = selectedUsers.leader?.id || '';
        if (membersEl) membersEl.value = selectedUsers.members.map(m => m.id).join(',');

        const memberInputs = document.getElementById('selected-members-inputs');
        if (memberInputs) {
            memberInputs.innerHTML = selectedUsers.members
                .map(member => `<input type="hidden" name="members[]" value="${member.id}">`)
                .join('');
        }
    }

    function renderSelectedUsers() {
        const containerSelected = document.getElementById('selected-users-container');
        const noUsersMsg = document.getElementById('no-users-message');
        if (!containerSelected || !noUsersMsg) return;

        const selected = [];
        if (selectedUsers.owner) {
            const role = selectedUsers.leader && selectedUsers.leader.id === selectedUsers.owner.id
                ? 'Owner & Leader' : 'Owner';
            selected.push({ ...selectedUsers.owner, role });
        }
        if (selectedUsers.leader && (!selectedUsers.owner || selectedUsers.owner.id !== selectedUsers.leader.id)) {
            selected.push({ ...selectedUsers.leader, role: 'Leader' });
        }
        selectedUsers.members.forEach(member => selected.push({ ...member, role: 'Member' }));

        if (!selected.length) {
            containerSelected.innerHTML = '';
            noUsersMsg.classList.remove('hidden');
            return;
        }

        noUsersMsg.classList.add('hidden');
        containerSelected.innerHTML = selected.map(user => {
            const styles = {
                'Owner': 'bg-blue-50 dark:bg-blue-950 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
                'Leader': 'bg-green-50 dark:bg-green-950 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
                'Owner & Leader': 'bg-teal-50 dark:bg-teal-950 border-teal-200 dark:border-teal-800 text-teal-800 dark:text-teal-200',
                'Member': 'bg-purple-50 dark:bg-purple-950 border-purple-200 dark:border-purple-800 text-purple-800 dark:text-purple-200'
            }[user.role] || 'bg-gray-50 dark:bg-gray-950 border-gray-200 dark:border-gray-800 text-gray-800 dark:text-gray-200';

            return `
                <div class="flex items-center justify-between p-4 border rounded-2xl ${styles}">
                     <div class="flex items-center gap-3">
                         ${user.photo_profile
                    ? `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800">`
                    : `<div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center ring-2 ring-white dark:ring-gray-800">
                                    <span class="font-semibold text-current">${user.nama_mahasiswa.charAt(0).toUpperCase()}</span>
                                </div>`
                }
                         <div>
                             <div class="font-medium">${user.role}: ${user.nama_mahasiswa}</div>
                             <div class="text-sm text-gray-500 dark:text-gray-400">${user.email || ''}</div>
                         </div>
                     </div>
                     <div class="flex items-center gap-2">
                         <button type="button" onclick="editUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80" title="Edit Role">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                             </svg>
                         </button>
                         ${user.role !== 'Owner' ? `
                         <button type="button" onclick="removeUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80" title="Remove">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
        if (!document.querySelectorAll('.task-item').length) window.addTaskRow();
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
    }

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
    }

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
        const tContainer = document.getElementById('tasks-container');
        if (!tContainer) return;
        const index = taskIndex++;
        const userId = taskData?.user_id ?? '';
        const taskName = taskData?.name_task ? taskData.name_task.replace(/"/g, '&quot;') : '';
        const hiddenId = taskData?.id ? `<input type="hidden" name="tasks[${index}][id]" value="${taskData.id}">` : '';
        const isDone = taskData?.is_done ? true : false; // ← TAMBAHKAN

        const taskItem = document.createElement('div');
        taskItem.className = 'task-item p-4 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900';
        taskItem.innerHTML = `
            ${hiddenId}
            <div class="grid gap-4 md:grid-cols-3 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Penanggung Jawab</label>
                    <select name="tasks[${index}][user_id]" class="task-user-select w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                        ${renderTaskUserOptions(userId)}
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Nama Tugas</label>
                    <input type="text" name="tasks[${index}][name_task]" value="${taskName}"
                        class="task-name-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                        placeholder="Deskripsikan tugas...">
                </div>

                {{-- ↓ TAMBAHKAN BLOK INI ↓ --}}
                <div class="flex items-center gap-2 mt-1">
                    <input type="hidden" name="tasks[${index}][is_done]" value="0">
                    <input type="checkbox" name="tasks[${index}][is_done]" value="1"
                        id="is_done_${index}"
                        class="task-is-done w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                        ${isDone ? 'checked' : ''}>
                    <label for="is_done_${index}" class="text-sm text-gray-700 dark:text-gray-300">
                        Tugas Selesai
                    </label>
                </div>
                {{-- ↑ SAMPAI SINI ↑ --}}

                <button type="button" onclick="removeTaskRow(this)"
                    class="self-start mt-6 px-4 py-3 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl">Hapus</button>
            </div>
        `;
        tContainer.appendChild(taskItem);
        taskItem.querySelector('.task-user-select')?.addEventListener('change', updateTaskUserOptions);
    }

    window.removeTaskRow = function (button) {
        button.closest('.task-item')?.remove();
        if (!document.querySelectorAll('.task-item').length) window.addTaskRow();
    }

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
        const tContainer = document.getElementById('tasks-container');
        if (!tContainer) return;
        tContainer.innerHTML = '';
        taskIndex = 0;
        if (Array.isArray(existingTasks) && existingTasks.length) {
            existingTasks.forEach(task => { if (task.user_id || task.name_task) window.addTaskRow(task); });
        } else {
            window.addTaskRow();
        }
        updateTaskUserOptions();
    }

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

    // Modal pagination click
    const pagListener = function (e) {
        const link = e.target.closest('#modal-pagination a');
        if (link) {
            e.preventDefault();
            const url = link.getAttribute('href');
            if (!url) return;
            const page = new URL(url).searchParams.get('page') || 1;
            fetchUsers(page);
        }
    };
    document.addEventListener('click', pagListener);

    function toggleUserSelectionSection() {
        const toggle = document.getElementById('project-collaborative-toggle');
        const userSelectionSection = document.getElementById('user-selection-section');

        if (!toggle || !userSelectionSection) return;

        if (toggle.checked) {
            userSelectionSection.style.display = 'block';

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
    }

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

    function onSubmitProjectForm() {
        if (currentUser) selectedUsers.owner = currentUser;
        if (selectedUsers.owner && !selectedUsers.leader && selectedUsers.members.length > 0) {
            selectedUsers.leader = selectedUsers.owner;
        }
        updateFormInputs();
        cleanupInvalidTaskRows();
        updateTaskUserOptions();
    }

    function loadSelectedUsersFromForm() {
        if (restoreSelectedUsersFromStorage()) {
            updateFormInputs();
            return;
        }
        const leaderId = document.getElementById('selected-leader-id')?.value;
        const memberIds = document.getElementById('selected-members-ids')?.value.split(',').filter(id => id) || [];
        if (leaderId) selectedUsers.leader = getUserById(leaderId);
        selectedUsers.members = memberIds.map(id => getUserById(id)).filter(Boolean);
    }

    if (currentUser) {
        addUsersToAllUsers([currentUser]);
    }

    loadSelectedUsersFromForm();
    renderSelectedUsers();
    setupModalFilters();
    setupDateValidation();
    updateTaskSectionVisibility();
    updateSelectedUsersBadge();

    const oldTasks = JSON.parse(container.dataset.oldTasks || '[]');
    initializeTaskRows(oldTasks);

    const pForm = document.getElementById('projectForm');
    if (pForm) pForm.addEventListener('submit', onSubmitProjectForm);

    const collaborativeToggle = document.getElementById('project-collaborative-toggle');
    const toggleLabel = document.getElementById('toggle-label');
    if (collaborativeToggle && toggleLabel) {
        toggleLabel.textContent = collaborativeToggle.checked ? 'Aktif' : 'Nonaktif';
        toggleUserSelectionSection();
        collaborativeToggle.addEventListener('change', function () {
            toggleLabel.textContent = this.checked ? 'Aktif' : 'Nonaktif';
            toggleUserSelectionSection();
        });
    }

    if (window.showPageInfo) {
        window.showPageInfo("popup.user_create_project");
    }
};

/* ==========================================
   VIEWS_EDIT_PROJECT.BLADE.PHP SCRIPTS
   ========================================== */
window.initProjectEditPage = function (container) {
    let allUsersMap = new Map();
    let allUsersArray = [];
    let pendingUsers = { owner: null, leader: null, members: [] };
    let selectedUsers = { owner: null, leader: null, members: [] };
    let taskIndex = 0;
    let currentModalFilters = { search: '', angkatan: '', jurusan: '', keahlian: '' };
    let tempSelectedRoles = {};
    let isCollaborativeMode = false;

    const initialUsers = JSON.parse(container.dataset.users || '[]');
    const currentUser = JSON.parse(container.dataset.currentUser || 'null');
    const existingTasksData = JSON.parse(container.dataset.existingTasks || '[]');
    const projectLeaderId = container.dataset.leaderId ? parseInt(container.dataset.leaderId) : null;
    const projectMemberIds = JSON.parse(container.dataset.memberIds || '[]');

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

    addUsersToMap(initialUsers);
    if (currentUser) addUsersToMap([currentUser]);

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
                const taskData = existingTasksData.find(t => String(t.user_id) === String(selectedId));
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

    window.addTaskRow = function (taskData = null) {
        const tContainer = document.getElementById('tasks-container');
        if (!tContainer) return;

        const index = taskIndex++;
        const userId = taskData?.user_id ?? '';
        const taskName = taskData?.name_task ? taskData.name_task.replace(/"/g, '&quot;') : '';
        const isDone = taskData?.is_done ? true : false;
        const hiddenId = taskData?.id ? `<input type="hidden" name="tasks[${index}][id]" value="${taskData.id}">` : '';

        const taskItem = document.createElement('div');
        taskItem.className = 'task-item p-4 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900';

        if (!isCollaborativeMode) {
            taskItem.innerHTML = `
                ${hiddenId}
                <input type="hidden" name="tasks[${index}][user_id]" value="${currentUser.id}">
                <div class="grid gap-4 md:grid-cols-3 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Penanggung Jawab</label>
                        <input type="text" value="${currentUser.nama_mahasiswa} (Owner)" 
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400" 
                               readonly disabled>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Nama Tugas</label>
                        <input type="text" name="tasks[${index}][name_task]" value="${taskName}" 
                               class="task-name-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white" 
                               placeholder="Deskripsikan tugas...">
                            <div class="flex items-center gap-2 mt-1">
                                <input type="hidden" name="tasks[${index}][is_done]" value="0">
                                <input type="checkbox" name="tasks[${index}][is_done]" value="1"
                                    id="is_done_${index}"
                                    class="task-is-done w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                    ${isDone ? 'checked' : ''}>
                                <label for="is_done_${index}" class="text-sm text-gray-700 dark:text-gray-300">
                                    Tugas Selesai
                                </label>
                            </div>
                    </div>
                    <button type="button" onclick="removeTaskRow(this)" 
                            class="self-start mt-6 px-4 py-3 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl">Hapus</button>
                </div>
            `;
        } else {
            taskItem.innerHTML = `
                ${hiddenId}
                <div class="grid gap-4 md:grid-cols-3 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Penanggung Jawab</label>
                        <select name="tasks[${index}][user_id]" class="task-user-select w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                            ${renderTaskUserOptions(userId)}
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Nama Tugas</label>
                        <input type="text" name="tasks[${index}][name_task]" value="${taskName}" 
                               class="task-name-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white" 
                               placeholder="Deskripsikan tugas...">
                        <div class="flex items-center gap-2 mt-1">
                                <input type="hidden" name="tasks[${index}][is_done]" value="0">
                                <input type="checkbox" name="tasks[${index}][is_done]" value="1"
                                    id="is_done_${index}"
                                    class="task-is-done w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                    ${isDone ? 'checked' : ''}>
                                <label for="is_done_${index}" class="text-sm text-gray-700 dark:text-gray-300">
                                    Tugas Selesai
                                </label>
                        </div>
                    </div>
                    <button type="button" onclick="removeTaskRow(this)" 
                            class="self-start mt-6 px-4 py-3 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl">Hapus</button>
                </div>
            `;
        }

        tContainer.appendChild(taskItem);

        if (isCollaborativeMode) {
            const select = taskItem.querySelector('.task-user-select');
            if (select) select.addEventListener('change', () => updateTaskUserOptions());
        }
    }

    window.removeTaskRow = function (button) {
        const taskItem = button.closest('.task-item');
        if (taskItem) taskItem.remove();
        if (!document.querySelectorAll('.task-item').length) window.addTaskRow();
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
        const tContainer = document.getElementById('tasks-container');
        if (!tContainer) return;

        tContainer.innerHTML = '';
        taskIndex = 0;

        if (tasks && tasks.length > 0) {
            tasks.forEach(task => {
                if (task.name_task) {
                    window.addTaskRow(task);
                }
            });
        }

        if (tContainer.children.length === 0) {
            window.addTaskRow();
        }

        if (isCollaborativeMode) {
            setTimeout(() => updateTaskUserOptions(), 100);
        }
    }

    function initializeTaskRows() {
        const tContainer = document.getElementById('tasks-container');
        if (!tContainer) return;
        tContainer.innerHTML = '';
        taskIndex = 0;

        if (Array.isArray(existingTasksData) && existingTasksData.length) {
            existingTasksData.forEach(task => window.addTaskRow(task));
        }

        if (tContainer.children.length === 0) window.addTaskRow();

        if (isCollaborativeMode) {
            setTimeout(() => updateTaskUserOptions(), 100);
        }
    }

    function fetchUsers(page = 1) {
        const fetchUrl = container.dataset.fetchUrl;
        const params = new URLSearchParams({
            id: container.dataset.projectId,
            page: page,
            search: currentModalFilters.search,
            angkatan: currentModalFilters.angkatan,
            jurusan: currentModalFilters.jurusan,
            keahlian: currentModalFilters.keahlian
        });

        fetch(`${fetchUrl}?${params}`, {
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
            select.addEventListener('change', function () {
                const userId = this.dataset.userId;
                const role = this.value;
                const user = getUserById(userId);

                if (!user) return;

                if (role === 'leader' && pendingUsers.leader && String(pendingUsers.leader.id) !== String(userId)) {
                    const confirmChange = confirm(`Anda yakin ingin mengganti leader dari "${pendingUsers.leader.nama_mahasiswa}" menjadi "${user.nama_mahasiswa}"?`);
                    if (!confirmChange) {
                        this.value = '';
                        return;
                    }

                    const oldLeaderSelect = document.querySelector(`.user-role-select[data-user-id="${pendingUsers.leader.id}"]`);
                    if (oldLeaderSelect) oldLeaderSelect.value = '';
                }

                if (role === 'leader') {
                    pendingUsers.leader = user;
                    pendingUsers.members = pendingUsers.members.filter(m => String(m.id) !== String(userId));
                } else if (role === 'member') {
                    if (pendingUsers.leader && String(pendingUsers.leader.id) === String(userId)) {
                        pendingUsers.leader = null;
                    }
                    if (!pendingUsers.members.some(m => String(m.id) === String(userId))) {
                        pendingUsers.members.push(user);
                    }
                } else {
                    if (pendingUsers.leader && String(pendingUsers.leader.id) === String(userId)) {
                        pendingUsers.leader = null;
                    }
                    pendingUsers.members = pendingUsers.members.filter(m => String(m.id) !== String(userId));
                }

                refreshLeaderOptions();
            });
        });
    }

    function refreshLeaderOptions() {
        const hasLeader = !!pendingUsers.leader;
        document.querySelectorAll('.user-role-select').forEach(select => {
            const userId = select.dataset.userId;
            const optionLeader = select.querySelector('option[value="leader"]');

            if (optionLeader) {
                if (hasLeader && (!pendingUsers.leader || String(pendingUsers.leader.id) !== String(userId))) {
                    optionLeader.disabled = true;
                } else {
                    optionLeader.disabled = false;
                }
            }
        });
    }

    window.openUserModal = function () {
        if (!isCollaborativeMode) {
            alert('Harap aktifkan mode kolaboratif terlebih dahulu untuk menambah user.');
            return;
        }
        syncPendingFromSelected();
        document.getElementById('userModal').classList.remove('hidden');
        fetchUsers(1);
    }

    window.closeUserModal = function () {
        document.getElementById('userModal').classList.add('hidden');
    }

    window.cancelUserModal = function () {
        document.getElementById('userModal').classList.add('hidden');
    }

    window.confirmUserSelection = function () {
        applyPendingToSelected();
        updateFormInputs();
        renderSelectedUsers();
        updateTaskUserOptions();
        updateSelectedUsersBadge();
        document.getElementById('userModal').classList.add('hidden');
    }

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

    function updateFormInputs() {
        const ownerEl = document.getElementById('selected-owner-id');
        const leaderEl = document.getElementById('selected-leader-id');
        const membersEl = document.getElementById('selected-members-ids');
        if (ownerEl) ownerEl.value = selectedUsers.owner?.id || '';
        if (leaderEl) leaderEl.value = selectedUsers.leader?.id || '';
        if (membersEl) membersEl.value = selectedUsers.members.map(m => m.id).join(',');

        const memberInputs = document.getElementById('selected-members-inputs');
        if (memberInputs) {
            memberInputs.innerHTML = selectedUsers.members
                .map(member => `<input type="hidden" name="members[]" value="${member.id}">`)
                .join('');
        }
    }

    function renderSelectedUsers() {
        const containerSelected = document.getElementById('selected-users-container');
        const noUsersMsg = document.getElementById('no-users-message');
        if (!containerSelected || !noUsersMsg) return;

        const selected = [];
        if (selectedUsers.owner) {
            const role = selectedUsers.leader && selectedUsers.leader.id === selectedUsers.owner.id
                ? 'Owner & Leader' : 'Owner';
            selected.push({ ...selectedUsers.owner, role });
        }
        if (selectedUsers.leader && (!selectedUsers.owner || selectedUsers.owner.id !== selectedUsers.leader.id)) {
            selected.push({ ...selectedUsers.leader, role: 'Leader' });
        }
        selectedUsers.members.forEach(member => selected.push({ ...member, role: 'Member' }));

        if (!selected.length) {
            containerSelected.innerHTML = '';
            noUsersMsg.classList.remove('hidden');
            return;
        }

        noUsersMsg.classList.add('hidden');
        containerSelected.innerHTML = selected.map(user => {
            const styles = {
                'Owner': 'bg-blue-50 dark:bg-blue-950 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
                'Leader': 'bg-green-50 dark:bg-green-950 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
                'Owner & Leader': 'bg-teal-50 dark:bg-teal-950 border-teal-200 dark:border-teal-800 text-teal-800 dark:text-teal-200',
                'Member': 'bg-purple-50 dark:bg-purple-950 border-purple-200 dark:border-purple-800 text-purple-800 dark:text-purple-200'
            }[user.role] || 'bg-gray-50 dark:bg-gray-950 border-gray-200 dark:border-gray-800 text-gray-800 dark:text-gray-200';

            return `
                <div class="flex items-center justify-between p-4 border rounded-2xl ${styles}">
                    <div class="flex items-center gap-3">
                        ${user.photo_profile
                    ? `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800">`
                    : `<div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center ring-2 ring-white dark:ring-gray-800">
                                   <span class="font-semibold text-current">${user.nama_mahasiswa.charAt(0).toUpperCase()}</span>
                               </div>`
                }
                        <div>
                            <div class="font-medium">${user.role}: ${user.nama_mahasiswa}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">${user.email || ''}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="editUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80" title="Edit Role">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        ${user.role !== 'Owner' ? `
                        <button type="button" onclick="removeUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80" title="Remove">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>` : ''}
                    </div>
                </div>
            `;
        }).join('');
    }

    window.removeUser = function (userId) {
        if (selectedUsers.leader?.id == userId) selectedUsers.leader = null;
        selectedUsers.members = selectedUsers.members.filter(m => String(m.id) !== String(userId));
        updateFormInputs();
        renderSelectedUsers();
        updateTaskUserOptions();
        updateSelectedUsersBadge();
    }

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
    }

    function toggleUserSelectionSection() {
        const toggle = document.getElementById('project-collaborative-toggle');
        const userSelectionSection = document.getElementById('user-selection-section');

        if (!toggle || !userSelectionSection) return;

        if (toggle.checked) {
            userSelectionSection.style.display = 'block';
            isCollaborativeMode = true;

            if (selectedUsers.leader && selectedUsers.owner && String(selectedUsers.leader.id) === String(selectedUsers.owner.id) && selectedUsers.members.length === 0) {
                selectedUsers.leader = null;
                updateFormInputs();
                renderSelectedUsers();
                updateSelectedUsersBadge();
            }

            const currentTasks = saveCurrentTasks();
            restoreTasks(currentTasks);
        } else {
            userSelectionSection.style.display = 'none';
            isCollaborativeMode = false;

            selectedUsers.leader = null;
            selectedUsers.members = [];

            updateFormInputs();
            renderSelectedUsers();
            updateSelectedUsersBadge();

            const currentTasks = saveCurrentTasks();
            restoreTasks(currentTasks);
        }
    }

    function setupDateValidation() {
        const tanggalMulaiInput = document.getElementById('tanggal_mulai');
        const tanggalAkhirInput = document.getElementById('tanggal_akhir');

        if (!tanggalMulaiInput || !tanggalAkhirInput) return;

        if (tanggalMulaiInput.value) {
            tanggalAkhirInput.min = tanggalMulaiInput.value;
        }

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

    function loadSelectedUsersFromForm() {
        selectedUsers.owner = currentUser;

        if (projectLeaderId) {
            const lead = getUserById(projectLeaderId);
            if (lead) selectedUsers.leader = lead;
        }

        if (projectMemberIds && projectMemberIds.length > 0) {
            selectedUsers.members = projectMemberIds.map(id => getUserById(id)).filter(Boolean);
        }

        updateFormInputs();
    }

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

    const pagListener = function (e) {
        const link = e.target.closest('#modal-pagination a');
        if (link) {
            e.preventDefault();
            const url = new URL(link.href);
            const page = url.searchParams.get('page') || 1;
            saveCurrentModalRoles();
            fetchUsers(page);
        }
    };
    document.addEventListener('click', pagListener);

    loadSelectedUsersFromForm();
    renderSelectedUsers();

    const collaborativeToggle = document.getElementById('project-collaborative-toggle');
    const toggleLabel = document.getElementById('toggle-label');

    if (collaborativeToggle && toggleLabel) {
        const hasMembers = selectedUsers.members.length > 0 || selectedUsers.leader;
        collaborativeToggle.checked = hasMembers;
        toggleLabel.textContent = hasMembers ? 'Aktif' : 'Nonaktif';
        isCollaborativeMode = hasMembers;

        toggleUserSelectionSection();
        initializeTaskRows();

        collaborativeToggle.addEventListener('change', function () {
            toggleLabel.textContent = this.checked ? 'Aktif' : 'Nonaktif';
            toggleUserSelectionSection();
        });
    } else {
        initializeTaskRows();
    }

    setupDateValidation();
    setupModalFilters();

    if (window.showPageInfo) {
        window.showPageInfo("popup.user_edit_project");
    }
};

/* ==========================================
   VIEWS_SERTIFIKAT.BLADE.PHP SCRIPTS
   ========================================== */
window.initSertifikatListPage = function (container = document) {
    const root = container && typeof container.querySelectorAll === 'function' ? container : document;

    window.filterStatus = function (status) {
        root.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('bg-indigo-600', 'text-white', 'hover:bg-indigo-700');
            if (btn.dataset.filter === status) {
                btn.classList.add('bg-indigo-600', 'text-white', 'hover:bg-indigo-700');
            } else {
                const filterValue = btn.dataset.filter;
                btn.classList.remove('bg-indigo-600', 'text-white', 'hover:bg-indigo-700');

                if (filterValue === 'Sedang Di Ajukan') {
                    btn.classList.add('bg-yellow-100', 'text-yellow-800', 'hover:bg-yellow-200', 'dark:bg-yellow-900/30', 'dark:text-yellow-300');
                } else if (filterValue === 'Di Terima') {
                    btn.classList.add('bg-green-100', 'text-green-800', 'hover:bg-green-200', 'dark:bg-green-900/30', 'dark:text-green-300');
                } else if (filterValue === 'Di Tolak') {
                    btn.classList.add('bg-red-100', 'text-red-800', 'hover:bg-red-200', 'dark:bg-red-900/30', 'dark:text-red-300');
                } else {
                    btn.classList.add('bg-gray-100', 'text-gray-800', 'hover:bg-gray-200', 'dark:bg-gray-700', 'dark:text-gray-300');
                }
            }
        });

        const cards = root.querySelectorAll('.sertifikat-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardStatus = card.dataset.status;
            if (status === 'all' || cardStatus === status) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const noDataMessage = root.querySelector('.no-data-message');
        if (visibleCount === 0) {
            if (!noDataMessage) {
                const gridContainer = root.querySelector('.grid');
                if (gridContainer) {
                    const message = document.createElement('div');
                    message.className = 'no-data-message col-span-full text-center py-12 bg-gray-50 dark:bg-gray-900 dark:border-gray-900 rounded-xl border border-gray-200';
                    message.innerHTML = `
                            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-4 text-gray-600 dark:text-gray-200" data-translate="no_data_filtered" data-translate-page="sertifikat"></p>
                        `;
                    gridContainer.parentNode.insertBefore(message, gridContainer.nextSibling);
                }
            }
        } else {
            const existingMessage = root.querySelector('.no-data-message');
            if (existingMessage) {
                existingMessage.remove();
            }
        }

        localStorage.setItem('sertifikatFilter', status);
    };

    root.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data sertifikat akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                if (confirm("Apakah Anda yakin ingin menghapus data sertifikat ini?")) {
                    form.submit();
                }
            }
        });
    });

    if (window.showPageInfo) {
        window.showPageInfo("popup.semua_sertifikat");
    }
};

/* ==========================================
   VIEWS_SERTIFIKAT_USER.BLADE.PHP SCRIPTS
   ========================================== */
window.initSertifikatUserPage = function (container) {
    container.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();
            const form = this.closest('form');

            if (typeof Swal !== 'undefined') {
                const confirmed = await Swal.fire({
                    title: 'Hapus Sertifikat?',
                    text: 'Sertifikat ini akan dihapus permanen dan tidak bisa dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                });

                if (confirmed.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    form.submit();
                }
            } else {
                if (confirm('Sertifikat ini akan dihapus permanen dan tidak bisa dikembalikan.')) {
                    form.submit();
                }
            }
        });
    });

    if (window.showPageInfo) {
        window.showPageInfo("popup.sertifikat_saya");
    }
};

/* ==========================================
   VIEWS_CREATE_SERTIFIKAT.BLADE.PHP SCRIPTS
   ========================================== */
window.initSertifikatCreatePage = function (container) {
    const fileInput = document.getElementById('link_sertifikat');
    const fileNameElement = document.getElementById('file-name');
    const previewContainer = document.getElementById('image-preview-container');
    const previewImage = document.getElementById('image-preview');

    window.updateFileLabel = function (input) {
        const fileName = input.files[0]?.name;

        if (fileName) {
            if (fileNameElement) fileNameElement.textContent = fileName;

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    if (previewImage) previewImage.src = e.target.result;
                    if (previewContainer) previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        } else {
            const defaultText = fileNameElement ? (fileNameElement.getAttribute('data-translate') || 'PNG, JPG, GIF up to 5MB') : 'PNG, JPG, GIF up to 5MB';
            if (fileNameElement) fileNameElement.textContent = defaultText;
            if (previewContainer) previewContainer.classList.add('hidden');
            if (previewImage) previewImage.src = '#';
        }
    };

    const dropZone = container.querySelector('.border-dashed');
    if (dropZone && fileInput) {
        const preventDefaults = (e) => {
            e.preventDefault();
            e.stopPropagation();
        };

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('border-indigo-500', 'bg-indigo-50'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('border-indigo-500', 'bg-indigo-50'), false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                fileInput.files = files;
                window.updateFileLabel(fileInput);
                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
        }, false);
    }

    const tanggalTerbitInput = document.getElementById('tanggal_terbit');
    const expiredDateInput = document.getElementById('expired_date');
    const permanentCheckbox = document.getElementById('permanent');
    const expiredDateBlock = document.getElementById('expired_date_block');

    function updateExpiredDateState() {
        if (!expiredDateInput) return;
        const isPermanent = permanentCheckbox?.checked;

        if (isPermanent) {
            expiredDateInput.value = '';
            expiredDateInput.disabled = true;
            expiredDateInput.required = false;
            expiredDateInput.classList.add('opacity-60');
            if (expiredDateBlock) expiredDateBlock.classList.add('opacity-60');
        } else {
            expiredDateInput.disabled = false;
            expiredDateInput.required = true;
            expiredDateInput.classList.remove('opacity-60');
            if (expiredDateBlock) expiredDateBlock.classList.remove('opacity-60');
        }
    }

    function validateExpiredDate() {
        if (permanentCheckbox?.checked) {
            if (expiredDateInput) expiredDateInput.setCustomValidity('');
            return true;
        }

        if (tanggalTerbitInput && expiredDateInput && tanggalTerbitInput.value && expiredDateInput.value) {
            const tanggalTerbit = new Date(tanggalTerbitInput.value);
            const expiredDate = new Date(expiredDateInput.value);

            if (expiredDate <= tanggalTerbit) {
                expiredDateInput.setCustomValidity('Tanggal expired harus setelah tanggal terbit');
                expiredDateInput.reportValidity();
                return false;
            } else {
                expiredDateInput.setCustomValidity('');
                return true;
            }
        }
        return true;
    }

    if (tanggalTerbitInput && expiredDateInput) {
        tanggalTerbitInput.addEventListener('change', function () {
            if (this.value) {
                const nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                expiredDateInput.min = nextDay.toISOString().split('T')[0];
            }
            validateExpiredDate();
        });
        expiredDateInput.addEventListener('change', validateExpiredDate);
    }

    if (permanentCheckbox) {
        permanentCheckbox.addEventListener('change', updateExpiredDateState);
    }

    updateExpiredDateState();

    const form = container.querySelector('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            updateExpiredDateState();
            if (!validateExpiredDate()) {
                e.preventDefault();
            }
        });
    }

    if (window.showPageInfo) {
        window.showPageInfo("popup.user_create_sertifikat");
    }
};

/* ==========================================
   VIEWS_EDIT_SERTIFIKAT.BLADE.PHP SCRIPTS
   ========================================== */
window.initSertifikatEditPage = function (container) {
    const fileInput = document.getElementById('link_sertifikat');
    const fileNameElement = document.getElementById('file-name');
    const previewContainer = document.getElementById('image-preview-container');
    const previewImage = document.getElementById('image-preview');
    const currentPreview = document.getElementById('current-image-preview');
    const originalFileName = container.dataset.originalFileName || '';

    window.updateFileLabel = function (input) {
        const fileName = input.files[0]?.name;

        if (fileName) {
            if (fileNameElement) fileNameElement.textContent = fileName;

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    if (previewImage) previewImage.src = e.target.result;
                    if (previewContainer) previewContainer.classList.remove('hidden');
                    if (currentPreview) currentPreview.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        } else {
            if (fileNameElement) {
                if (originalFileName) {
                    fileNameElement.textContent = originalFileName;
                } else {
                    fileNameElement.textContent = fileNameElement.getAttribute('data-translate') || 'PNG, JPG, GIF up to 5MB';
                }
            }
            if (previewContainer) previewContainer.classList.add('hidden');
            if (previewImage) previewImage.src = '#';
            if (currentPreview) currentPreview.classList.remove('hidden');
        }
    };

    window.toggleFileUpload = function (checkbox) {
        const fileUploadSection = document.getElementById('file-upload-section');

        if (checkbox.checked) {
            if (fileUploadSection) fileUploadSection.classList.remove('hidden');
            if (currentPreview) currentPreview.classList.add('hidden');

            if (fileInput) {
                fileInput.value = '';
                window.updateFileLabel(fileInput);
            }
        } else {
            if (fileUploadSection) fileUploadSection.classList.add('hidden');
            if (currentPreview) currentPreview.classList.remove('hidden');

            if (previewContainer) previewContainer.classList.add('hidden');
        }
    };

    const dropZone = container.querySelector('.border-dashed');
    if (dropZone && fileInput) {
        const preventDefaults = (e) => {
            e.preventDefault();
            e.stopPropagation();
        };

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('border-indigo-500', 'bg-indigo-50'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('border-indigo-500', 'bg-indigo-50'), false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files && files.length > 0) {
                const replaceCheckbox = document.getElementById('replace-file-checkbox');
                if (replaceCheckbox && !replaceCheckbox.checked) {
                    replaceCheckbox.checked = true;
                    window.toggleFileUpload(replaceCheckbox);
                }

                fileInput.files = files;
                window.updateFileLabel(fileInput);

                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
        }, false);
    }

    const tanggalTerbitInput = document.getElementById('tanggal_terbit');
    const expiredDateInput = document.getElementById('expired_date');
    const permanentCheckbox = document.getElementById('permanent');
    const expiredDateBlock = document.getElementById('expired_date_block');

    function updateExpiredDateState() {
        if (!expiredDateInput) return;
        const isPermanent = permanentCheckbox?.checked;

        if (isPermanent) {
            expiredDateInput.value = '';
            expiredDateInput.disabled = true;
            expiredDateInput.required = false;
            expiredDateInput.classList.add('opacity-60');
            if (expiredDateBlock) expiredDateBlock.classList.add('opacity-60');
        } else {
            expiredDateInput.disabled = false;
            expiredDateInput.required = true;
            expiredDateInput.classList.remove('opacity-60');
            if (expiredDateBlock) expiredDateBlock.classList.remove('opacity-60');
        }
    }

    function validateExpiredDate() {
        if (permanentCheckbox?.checked) {
            if (expiredDateInput) expiredDateInput.setCustomValidity('');
            return true;
        }

        if (tanggalTerbitInput && expiredDateInput && tanggalTerbitInput.value && expiredDateInput.value) {
            const tanggalTerbit = new Date(tanggalTerbitInput.value);
            const expiredDate = new Date(expiredDateInput.value);

            if (expiredDate <= tanggalTerbit) {
                expiredDateInput.setCustomValidity('Tanggal expired harus setelah tanggal terbit');
                expiredDateInput.reportValidity();
                return false;
            } else {
                expiredDateInput.setCustomValidity('');
                return true;
            }
        }
        return true;
    }

    if (tanggalTerbitInput && expiredDateInput) {
        tanggalTerbitInput.addEventListener('change', function () {
            if (this.value) {
                const nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                expiredDateInput.min = nextDay.toISOString().split('T')[0];
            }
            validateExpiredDate();
        });

        expiredDateInput.addEventListener('change', validateExpiredDate);
        validateExpiredDate();
    }

    if (permanentCheckbox) {
        permanentCheckbox.addEventListener('change', updateExpiredDateState);
    }

    updateExpiredDateState();

    const form = container.querySelector('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (!validateExpiredDate()) {
                e.preventDefault();
                return false;
            }

            const replaceCheckbox = document.getElementById('replace-file-checkbox');
            if (fileInput && fileInput.files.length > 0 && replaceCheckbox && !replaceCheckbox.checked) {
                replaceCheckbox.checked = true;
                window.toggleFileUpload(replaceCheckbox);
            }
        });
    }

    if (window.showPageInfo) {
        window.showPageInfo("popup.user_edit_sertifikat");
    }
};

/* ==========================================
   PAGINATION AJAX & SCROLL RESTORATION HELPERS
   ========================================== */
document.addEventListener('click', function (e) {
    const link = e.target.closest('.pagination-link');
    if (link) {
        const groupName = link.getAttribute('data-group');
        sessionStorage.setItem('scrollToGroup', groupName);
    }
});

function handlePaginationScroll() {
    const groupName = sessionStorage.getItem('scrollToGroup');
    if (groupName) {
        const element = document.querySelector(`[data-pagination-group="${groupName}"]`);
        if (element) {
            setTimeout(function () {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 1200);
        }
        sessionStorage.removeItem('scrollToGroup');
    }
}
document.addEventListener('DOMContentLoaded', handlePaginationScroll);
document.addEventListener('turbo:load', handlePaginationScroll);

