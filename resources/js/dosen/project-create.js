function initDosenProjectCreatePage() {
    const dataEl = document.getElementById('dosen-project-create-data');
    if (!dataEl) return;

    const allUsers = JSON.parse(dataEl.dataset.users || '[]');
    const oldTasks = JSON.parse(dataEl.dataset.oldTasks || '[]');
    const checkDuplicateNameUrl = dataEl.dataset.checkDuplicateNameUrl || '';

    let selectedUsers = { owner: null, leader: null, members: [] };
    let taskIndex = 0;

    const getUserById = (id) => allUsers.find((user) => String(user.id) === String(id)) || null;

        const loadSelectedUsersFromForm = () => {
        const ownerId = document.getElementById('selected-owner-id')?.value;
        const leaderId = document.getElementById('selected-leader-id')?.value;
        const memberInputs = document.querySelectorAll('#members-hidden-container input[name="members[]"]');
        const memberIds = Array.from(memberInputs).map((input) => input.value).filter(Boolean);
        if (ownerId) selectedUsers.owner = getUserById(ownerId);
        if (leaderId && leaderId !== ownerId) selectedUsers.leader = getUserById(leaderId);
        memberIds.forEach((id) => {
            const member = getUserById(id);
            if (member && member.id !== selectedUsers.owner?.id && member.id !== selectedUsers.leader?.id) selectedUsers.members.push(member);
        });
    };

    const updateSelectedUsersBadge = () => {
        const badge = document.getElementById('selected-users-badge');
        if (!badge) return;
        let count = 0;
        if (selectedUsers.owner) count++;
        if (selectedUsers.leader) count++;
        count += selectedUsers.members.length;
        badge.innerHTML = count ? `<span class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full text-xs font-semibold">${count} user(s) terpilih</span>` : '';
    };

    const updateTaskSectionVisibility = () => {
        const taskSection = document.getElementById('task-section');
        const container = document.getElementById('tasks-container');
        if (!taskSection) return;
        const hasUsers = !!(selectedUsers.owner || selectedUsers.leader || selectedUsers.members.length > 0);
        taskSection.classList.toggle('hidden', !hasUsers);
        if (!hasUsers && container) {
            container.innerHTML = '';
            taskIndex = 0;
        }
    };

    const updateFormInputs = () => {
        const ownerInput = document.getElementById('selected-owner-id');
        const leaderInput = document.getElementById('selected-leader-id');
        const container = document.getElementById('members-hidden-container');
        if (ownerInput) ownerInput.value = selectedUsers.owner?.id || '';
        if (leaderInput) leaderInput.value = selectedUsers.leader?.id || '';
        if (container) {
            container.innerHTML = '';
            selectedUsers.members.forEach((member) => {
                if (member.id !== selectedUsers.owner?.id && member.id !== selectedUsers.leader?.id) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'members[]';
                    input.value = member.id;
                    container.appendChild(input);
                }
            });
        }
    };

    const renderSelectedUsers = () => {
        const container = document.getElementById('selected-users-container');
        const noUsersMsg = document.getElementById('no-users-message');
        if (!container || !noUsersMsg) return;
        const selected = [];
        if (selectedUsers.owner) selected.push({ ...selectedUsers.owner, role: 'Owner' });
        if (selectedUsers.leader && (!selectedUsers.owner || selectedUsers.leader.id !== selectedUsers.owner.id)) selected.push({ ...selectedUsers.leader, role: 'Leader' });
        selectedUsers.members.forEach((member) => {
            if (member.id !== selectedUsers.owner?.id && member.id !== selectedUsers.leader?.id) selected.push({ ...member, role: 'Member' });
        });

        if (!selected.length) {
            container.innerHTML = '';
            noUsersMsg.classList.remove('hidden');
            return;
        }

        noUsersMsg.classList.add('hidden');
        container.innerHTML = selected.map((user) => {
            const photo = user.photo_profile 
                ? `/storage/${user.photo_profile}` 
                : null;
            const photoHtml = photo 
                ? `<img src="${photo}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800">`
                : `<span class="font-semibold text-current">${(user.nama_mahasiswa || '?').charAt(0).toUpperCase()}</span>`;

            return `
                <div class="flex items-center justify-between p-4 border rounded-2xl bg-indigo-50 dark:bg-indigo-950 border-indigo-200 dark:border-indigo-800 text-indigo-800 dark:text-indigo-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center ring-2 ring-white dark:ring-gray-800">
                            ${photoHtml}
                        </div>
                        <div>
                            <div class="font-medium">${user.role}: ${user.nama_mahasiswa}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">${user.email || ''}</div>
                        </div>
                    </div>
                    <button type="button" onclick="window.removeSelectedUser(${user.id}, '${user.role.toLowerCase()}')" 
                        class="p-2 bg-red-100 hover:bg-red-200 dark:bg-red-950 dark:hover:bg-red-900 text-red-600 dark:text-red-300 rounded-xl transition duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
        }).join('');
    };

    window.removeSelectedUser = function (userId, role) {
        if (role === 'owner') selectedUsers.owner = null;
        else if (role === 'leader') selectedUsers.leader = null;
        else if (role === 'member') {
            selectedUsers.members = selectedUsers.members.filter((member) => member.id !== userId);
        }

        // Hapus juga task-task yang penanggung jawabnya adalah user yang baru saja dihapus,
        // supaya tidak ada task "nyangkut" tanpa PJ yang valid di form.
        document.querySelectorAll('.task-user-select').forEach((select) => {
            if (select.value && String(select.value) === String(userId)) {
                select.closest('.task-item')?.remove();
            }
        });

        updateFormInputs();
        renderSelectedUsers();
        updateTaskSectionVisibility();
        updateSelectedUsersBadge();
        updateTaskUserOptions();
        
        // Re-render modal dropdown states
        filterAndRenderUsers();
    };

    // ----- Local Filter and Render Users -----
    const filterAndRenderUsers = () => {
        const searchVal = (document.getElementById('modal-search')?.value || '').toLowerCase().trim();
        const angkatanVal = document.getElementById('modal-angkatan')?.value || '';
        const jurusanVal = document.getElementById('modal-jurusan')?.value || '';
        const keahlianVal = document.getElementById('modal-keahlian')?.value || '';

        const filtered = allUsers.filter((user) => {
            if (searchVal) {
                const name = (user.nama_mahasiswa || '').toLowerCase();
                const username = (user.username || '').toLowerCase();
                const email = (user.email || '').toLowerCase();
                if (!name.includes(searchVal) && !username.includes(searchVal) && !email.includes(searchVal)) {
                    return false;
                }
            }
            if (angkatanVal && String(user.id_angkatan) !== String(angkatanVal)) return false;
            if (jurusanVal && String(user.id_jurusan) !== String(jurusanVal)) return false;
            if (keahlianVal && String(user.id_keahlian) !== String(keahlianVal)) return false;
            return true;
        });

        const userList = document.getElementById('modal-user-list');
        if (!userList) return;

        if (filtered.length === 0) {
            userList.innerHTML = `
                <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                    Tidak ada mahasiswa yang sesuai filter.
                </div>
            `;
            return;
        }

        const isOwnerSelected = !!selectedUsers.owner;
        const isLeaderSelected = !!selectedUsers.leader;

        userList.innerHTML = filtered.map((user) => {
            let roleVal = '';
            if (selectedUsers.owner?.id === user.id) roleVal = 'owner';
            else if (selectedUsers.leader?.id === user.id) roleVal = 'leader';
            else if (selectedUsers.members.some(m => m.id === user.id)) roleVal = 'member';

            const photoHtml = user.photo_profile 
                ? `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover">`
                : `<div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                       <span class="text-indigo-600 dark:text-indigo-400 font-semibold">${(user.nama_mahasiswa || '?').charAt(0).toUpperCase()}</span>
                   </div>`;

            const ownerDisabled = isOwnerSelected && roleVal !== 'owner' ? 'disabled style="color: #9ca3af; background-color: #f3f4f6;"' : '';
            const leaderDisabled = isLeaderSelected && roleVal !== 'leader' ? 'disabled style="color: #9ca3af; background-color: #f3f4f6;"' : '';

            return `
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center gap-3">
                        ${photoHtml}
                        <div>
                            <div class="font-medium text-gray-900 dark:text-gray-100">${user.nama_mahasiswa}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">${user.email || ''}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <select class="user-role-select px-3 py-1 border border-gray-300 dark:border-gray-500 rounded-lg text-sm" onchange="window.updateDosenProjectUserRole(this, ${user.id}, this.value)">
                            <option value="">-- Pilih Role --</option>
                            <option value="owner" ${roleVal === 'owner' ? 'selected' : ''} ${ownerDisabled}>Owner</option>
                            <option value="leader" ${roleVal === 'leader' ? 'selected' : ''} ${leaderDisabled}>Leader</option>
                            <option value="member" ${roleVal === 'member' ? 'selected' : ''}>Member</option>
                        </select>
                    </div>
                </div>
            `;
        }).join('');
    };

    window.updateDosenProjectUserRole = function (selectElement, userId, role) {
        const user = allUsers.find((entry) => String(entry.id) === String(userId));
        if (!user) return;
        
        if (selectedUsers.owner?.id === userId) selectedUsers.owner = null;
        if (selectedUsers.leader?.id === userId) selectedUsers.leader = null;
        selectedUsers.members = selectedUsers.members.filter((member) => member.id !== userId);
        
        if (role === 'owner') selectedUsers.owner = user;
        else if (role === 'leader') selectedUsers.leader = user;
        else if (role === 'member') selectedUsers.members.push(user);
        
        updateFormInputs();
        renderSelectedUsers();
        updateTaskSectionVisibility();
        updateSelectedUsersBadge();
        updateTaskUserOptions();
        
        // Re-render modal dropdown states
        filterAndRenderUsers();
    };

    // Fallback for dynamically matched role select name from admin template compatibility
    window.updateUserRole = function (selectElement, userId, role) {
        window.updateDosenProjectUserRole(selectElement, userId, role);
    };

    window.openUserModal = function () {
        document.getElementById('userModal')?.classList.remove('hidden');
        filterAndRenderUsers();
    };

    window.closeUserModal = function () {
        document.getElementById('userModal')?.classList.add('hidden');
    };

    window.confirmUserSelection = function () {
        updateFormInputs();
        renderSelectedUsers();
        updateTaskSectionVisibility();
        updateSelectedUsersBadge();
        updateTaskUserOptions();
        window.closeUserModal();
    };

    // ----- Task rows management -----
    const getAllowedTaskUsers = () => {
        const users = [];
        const added = new Set();
        const add = (user) => {
            if (!user || added.has(user.id)) return;
            added.add(user.id);
            users.push({ id: user.id, name: user.nama_mahasiswa });
        };
        add(selectedUsers.owner);
        add(selectedUsers.leader);
        selectedUsers.members.forEach(add);
        return users;
    };

    const renderTaskUserOptions = (selectedId = '') => {
        const users = getAllowedTaskUsers();
        let html = '<option value="">-- Pilih Penanggung Jawab --</option>';
        users.forEach((user) => {
            html += `<option value="${user.id}" ${String(user.id) === String(selectedId) ? 'selected' : ''}>${user.name}</option>`;
        });
        return html;
    };

    const updateTaskUserOptions = () => {
        document.querySelectorAll('.task-user-select').forEach((select) => {
            const currentVal = select.value;
            select.innerHTML = renderTaskUserOptions(currentVal);
        });
    };

    window.addTaskRow = function (taskData = null) {
        const container = document.getElementById('tasks-container');
        if (!container) return;
        const index = taskIndex++;
        const userId = taskData?.user_id ?? '';
        const taskName = taskData?.name_task ? taskData.name_task.replace(/"/g, '&quot;') : '';
        const hiddenId = taskData?.id ? `<input type="hidden" name="tasks[${index}][id]" value="${taskData.id}">` : '';

        const taskItem = document.createElement('div');
        taskItem.className = 'task-item p-4 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900';
        taskItem.innerHTML = `
            ${hiddenId}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Penanggung Jawab</label>
                    <select name="tasks[${index}][user_id]" class="task-user-select w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm" required>
                        ${renderTaskUserOptions(userId)}
                    </select>
                </div>
                <div class="md:col-span-2 space-y-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nama Tugas</label>
                        <input type="text" name="tasks[${index}][name_task]" value="${taskName}" required
                            class="task-name-input w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm"
                            placeholder="Deskripsikan tugas...">
                    </div>
                </div>
                <div class="flex items-end justify-end md:justify-start">
                    <button type="button" onclick="window.removeTaskRow(this)"
                        class="px-4 py-2.5 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl text-sm font-medium hover:bg-red-200 dark:hover:bg-red-900 transition">
                        Hapus
                    </button>
                </div>
            </div>
        `;
        container.appendChild(taskItem);
    };

    window.removeTaskRow = function (button) {
        button.closest('.task-item')?.remove();
    };

    const setupDateConstraints = () => {
        const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]');
        const tanggalAkhir = document.querySelector('input[name="tanggal_akhir"]');
        if (tanggalMulai && tanggalAkhir) {
            const updateMinAkhir = () => {
                if (tanggalMulai.value) {
                    const startDate = new Date(tanggalMulai.value);
                    const minDate = new Date(startDate);
                    minDate.setDate(startDate.getDate() + 1);
                    tanggalAkhir.min = minDate.toISOString().split('T')[0];
                    if (tanggalAkhir.value && tanggalAkhir.value < tanggalAkhir.min) {
                        tanggalAkhir.value = '';
                    }
                }
            };
            tanggalMulai.addEventListener('change', updateMinAkhir);
            updateMinAkhir();
        }
    };

    // ----- Init filters listeners -----
    const searchInput = document.getElementById('modal-search');
    const angkatanSelect = document.getElementById('modal-angkatan');
    const jurusanSelect = document.getElementById('modal-jurusan');
    const keahlianSelect = document.getElementById('modal-keahlian');

    if (searchInput) searchInput.addEventListener('input', filterAndRenderUsers);
    if (angkatanSelect) angkatanSelect.addEventListener('change', filterAndRenderUsers);
    if (jurusanSelect) jurusanSelect.addEventListener('change', filterAndRenderUsers);
    if (keahlianSelect) keahlianSelect.addEventListener('change', filterAndRenderUsers);

    // ----- Load Init data -----
    loadSelectedUsersFromForm();
    renderSelectedUsers();
    updateTaskSectionVisibility();
    updateSelectedUsersBadge();
    updateFormInputs();
    setupDateConstraints();

    if (oldTasks && oldTasks.length > 0) {
        oldTasks.forEach(task => {
            if (task.user_id || task.name_task) window.addTaskRow(task);
        });
    }

    const form = document.getElementById('projectForm');
    if (form) {
        form.addEventListener('submit', () => {
            updateFormInputs();
        });
    }

    if (typeof window.setupProjectDuplicateNameCheck === 'function') {
        window.setupProjectDuplicateNameCheck({ checkUrl: checkDuplicateNameUrl });
    }

    if (typeof window.showPageInfo === 'function') {
        window.showPageInfo('popup.dosen_add_pjt');
    }
}

document.addEventListener('DOMContentLoaded', initDosenProjectCreatePage);
document.addEventListener('turbo:load', initDosenProjectCreatePage);