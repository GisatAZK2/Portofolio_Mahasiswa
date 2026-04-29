﻿@extends('Layout.Layout')
@section('title', autoTranslate('Edit Project Mahasiswa'))
@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
        <div class="p-4 md:p-8 max-w-7xl mx-auto">
            
            <!-- Header -->
            <div class="mb-6 md:mb-8 text-center md:text-left">
                <div class="flex items-center gap-3 mb-2 justify-center md:justify-start">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100" data-translate="title" data-translate-page="project_edit"></h1>
                </div>
                <p class="mt-2 text-gray-600 dark:text-gray-400 text-sm md:text-base max-w-md mx-auto md:mx-0" data-translate="desc" data-translate-page="project_edit"></p>
            </div>

            <div id="translation-templates" class="hidden">
                <span id="selected-leader-prefix" data-translate="selected_leader_prefix" data-translate-page="project_edit"></span>
                <span id="select-member-option" data-translate="select_member_option" data-translate-page="project_edit"></span>
                <span id="member-search-placeholder" data-translate="member_search_placeholder" data-translate-page="project_edit"></span>
                <span id="no-students-available" data-translate="no_students_available" data-translate-page="project_edit"></span>
                <span id="name-unknown-text" data-translate="name_unknown" data-translate-page="project_edit"></span>
                <span id="leader-suffix" data-translate="leader_suffix" data-translate-page="project_edit"></span>
                <span id="already-selected-suffix" data-translate="already_selected_suffix" data-translate-page="project_edit"></span>
            </div>

            <!-- Error Global -->
            @if ($errors->any())
                <div class="mb-6 md:mb-8 p-4 md:p-5 bg-red-50 border border-red-200 text-red-700 rounded-2xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">{{ autoTranslate('Terdapat kesalahan pada input:')}}</span>
                    </div>
                    <ul class="list-disc pl-5 md:pl-10 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ autoTranslate($error) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('project.update', ['id' => $project->id]) }}" class="space-y-6 md:space-y-7" id="projectForm">
                @csrf
                @method('PUT')

                <div class="flex items-center justify-between gap-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{autoTranslate('Projek Kolaboratif')}}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{autoTranslate('Aktifkan untuk menambahkan pemimpin dan anggota tim.')}}</p>
                    </div>
                    <label class="inline-flex items-center cursor-pointer">
                        <span class="relative">
                            <input id="project-collaborative-toggle" type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600"></div>
                        </span>
                        <span id="toggle-label" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-200">{{autoTranslate('Nonaktif')}}</span>
                    </label>
                </div>

                <!-- User Selection Section -->
                <div id="user-selection-section" style="display: none;">
                    <div class="bg-white dark:bg-gray-800 p-5 md:p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ autoTranslate('Pemilihan User Project') }}</h3>
                            <button type="button" onclick="openUserModal()" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    {{ autoTranslate('Tambah User') }}
                                </span>
                            </button>
                        </div>

                        <!-- Selected Users Display -->
                        <div id="selected-users-container" class="space-y-3"></div>

                        <div id="no-users-message" class="text-center py-8 text-gray-500 dark:text-gray-400">
                            {{ autoTranslate('Belum ada leader atau member yang dipilih. Klik "Tambah User" untuk memulai.') }}
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs for selected users -->
                <input type="hidden" name="owner" id="selected-owner-id" value="{{ Auth::id() }}">
                <input type="hidden" name="leader" id="selected-leader-id" value="{{ old('leader', $project->leader_id) }}">
                <div id="members-hidden-container">
                    @php
                        $memberIds = old('members', $project->members->pluck('id')->toArray());
                        if (!is_array($memberIds)) {
                            $memberIds = explode(',', $memberIds);
                        }
                    @endphp
                    @foreach($memberIds as $id)
                        <input type="hidden" name="members[]" value="{{ $id }}">
                    @endforeach
                </div>

                <!-- Nama Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="nama_project" data-translate-page="project_edit"></span> <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_project" value="{{ old('nama_project', $project->isi_content['nama_project'] ?? '') }}" required
                        class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('nama_project') border-red-500 @enderror"
                        placeholder="{{ autoTranslate('Contoh: Website Portfolio Pribadi') }}">
                    @error('nama_project')
                        <p class="mt-1 text-sm text-red-600">{{ autoTranslate($message) }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="deskripsi_opsional" data-translate-page="project_edit"></span></label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('deskripsi') border-red-500 @enderror"
                        placeholder="{{ autoTranslate('Deskripsikan project Anda...') }}">{{ old('deskripsi', $project->isi_content['deskripsi'] ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ autoTranslate($message) }}</p>
                    @enderror
                </div>

                <!-- Tambah Tugas -->
                <div id="task-section" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3" data-translate="add_task_opt" data-translate-page="dosen_add_pjt">
                        Tambah Tugas (opsional)
                    </label>
                    <div id="tasks-container" class="space-y-4"></div>
                    <button type="button" onclick="addTaskRow()"
                        class="mt-3 text-sm text-indigo-600 dark:text-indigo-400 hover:cursor-pointer hover:underline flex items-center gap-1">
                        <span class="text-xl">+</span> <span data-translate="add_task_opt"
                            data-translate-page="dosen_add_pjt">Tambah Tugas</span>
                    </button>
                </div>

                <!-- Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="tanggal_mulai" data-translate-page="project_edit"></span> <span class="text-red-500">*</span></label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $project->tanggal_mulai->format('Y-m-d')) }}" required
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_mulai') border-red-500 @enderror">
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ autoTranslate($message) }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="tanggal_selesai" data-translate-page="project_edit"></span></label>
                        <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="{{ old('tanggal_akhir', $project->tanggal_akhir?->format('Y-m-d') ?? '') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_akhir') border-red-500 @enderror">
                        @error('tanggal_akhir')
                            <p class="mt-1 text-sm text-red-600">{{ autoTranslate($message) }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Link Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="link_project_opsional" data-translate-page="project_edit"></span></label>
                    <input type="url" name="link_project" value="{{ old('link_project', $project->isi_content['link_project'] ?? '') }}"
                        class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_project') border-red-500 @enderror"
                        placeholder="https://example.com/project">
                    @error('link_project')
                        <p class="mt-1 text-sm text-red-600">{{ autoTranslate($message) }}</p>
                    @enderror
                </div>

                <!-- Link GitHub & Video -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="link_github_opsional" data-translate-page="project_edit"></span></label>
                        <input type="url" name="link_github" maxlength="500" value="{{ old('link_github', $project->isi_content['link_github'] ?? '') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_github') border-red-500 @enderror"
                            placeholder="https://github.com/username/repo">
                        @error('link_github')
                            <p class="mt-1 text-sm text-red-600">{{ autoTranslate($message) }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"><span data-translate="link_video_opsional" data-translate-page="project_edit"></span></label>
                        <input type="url" name="link_video" maxlength="500" value="{{ old('link_video', $project->isi_content['link_video'] ?? '') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_video') border-red-500 @enderror"
                            placeholder="https://www.youtube.com/watch?v=...">
                        @error('link_video')
                            <p class="mt-1 text-sm text-red-600">{{ autoTranslate($message) }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex-1"></div> 
                    
                    <a href="{{ route('project.index') }}"
                       class="px-6 py-3.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition text-center w-full sm:w-auto" data-translate="cancel" data-translate-page="project_edit"></a>
                    
                    <button type="submit"
                        class="px-8 py-3.5 bg-indigo-600 text-white font-medium rounded-2xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-md w-full sm:w-auto" data-translate="update_project" data-translate-page="project_edit"></button>
                </div>
            </form>
        </div>
    </div>

    @php
    $existingTasks = $project->tasks->map(function ($task) {
        return [
            'id'        => $task->id,
            'user_id'   => $task->user_id,
            'user_name' => $task->user?->nama_mahasiswa,
            'name_task' => $task->name_task,
        ];
    })->toArray();
    @endphp

    <script>
    // ====================== GLOBAL VARIABLES ======================
    let allUsersMap = new Map();
    let allUsersArray = [];
    let pendingUsers = { owner: null, leader: null, members: [] };
    let selectedUsers = { owner: null, leader: null, members: [] };
    let taskIndex = 0;
    let currentModalFilters = { search: '', angkatan: '', jurusan: '', keahlian: '' };
    let tempSelectedRoles = {};

    // Data dari Laravel
    const initialUsers = @json($users->items());
    const currentUser = @json(['id' => Auth::id(), 'nama_mahasiswa' => Auth::user()->nama_mahasiswa]);
    const existingTasksData = @json($existingTasks);
    const projectLeaderId = @json(optional($project->leader)->id);
    const projectMemberIds = @json($project->members->pluck('id')->toArray());

    // ====================== USER STORAGE HELPERS ======================
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

    // Add initial users
    addUsersToMap(initialUsers);
    if (currentUser) addUsersToMap([currentUser]);

    // ====================== PENDING/SELECTED STATE ======================
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

    // ====================== TASK FUNCTIONS ======================
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

    function addTaskRow(taskData = null) {
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
                <button type="button" onclick="removeTaskRow(this)" 
                        class="self-start mt-6 px-4 py-3 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl">Hapus</button>
            </div>
        `;

        container.appendChild(taskItem);
        const select = taskItem.querySelector('.task-user-select');
        if (select) select.addEventListener('change', () => updateTaskUserOptions());
    }

    function removeTaskRow(button) {
        const taskItem = button.closest('.task-item');
        if (taskItem) taskItem.remove();
        if (!document.querySelectorAll('.task-item').length) addTaskRow();
    }

    function updateTaskUserOptions() {
        setTimeout(() => {
            document.querySelectorAll('.task-user-select').forEach(select => {
                const currentValue = select.value;
                const newOptions = renderTaskUserOptions(currentValue);
                select.innerHTML = newOptions;
                if (currentValue) select.value = currentValue;
            });
        }, 50);
    }

    function initializeTaskRows() {
        const container = document.getElementById('tasks-container');
        if (!container) return;
        container.innerHTML = '';
        taskIndex = 0;

        if (Array.isArray(existingTasksData) && existingTasksData.length) {
            existingTasksData.forEach(task => addTaskRow(task));
        }
        
        if (container.children.length === 0) addTaskRow();
        setTimeout(() => updateTaskUserOptions(), 100);
    }

    // ====================== AJAX FUNCTIONS ======================
    function fetchUsers(page = 1) {
        const params = new URLSearchParams({
            id: {{ $project->id }},
            page: page,
            search: currentModalFilters.search,
            angkatan: currentModalFilters.angkatan,
            jurusan: currentModalFilters.jurusan,
            keahlian: currentModalFilters.keahlian
        });

        fetch(`{{ route('project.edit', ['id' => $project->id]) }}?${params}`, {
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
                const handler = function() { 
                    updateUserRole(this, userId, this.value);
                    saveCurrentModalRoles();
                };
                select.addEventListener('change', handler);
                select._handler = handler;
            }
        });
    }

    // ====================== USER ROLE FUNCTIONS ======================
    function updateUserRole(selectElement, userId, role) {
        const user = getUserById(userId);
        if (!user) {
            console.error('User not found:', userId);
            return;
        }

        const isOwner = pendingUsers.owner && String(pendingUsers.owner.id) === String(userId);
        const isCurrentLeader = pendingUsers.leader && String(pendingUsers.leader.id) === String(userId);

        // Owner tidak bisa diubah jadi member
        if (isOwner && role === 'member') {
            alert('Owner tidak bisa menjadi member.');
            selectElement.value = isCurrentLeader ? 'leader' : '';
            refreshLeaderOptions();
            return;
        }

        // Jika memilih leader baru
        if (role === 'leader' && pendingUsers.leader && String(pendingUsers.leader.id) !== String(userId)) {
            const confirmChange = confirm(`Anda yakin ingin mengganti leader dari "${pendingUsers.leader.nama_mahasiswa}" menjadi "${user.nama_mahasiswa}"?`);
            if (!confirmChange) {
                selectElement.value = isCurrentLeader ? 'leader' : '';
                refreshLeaderOptions();
                return;
            }
            pendingUsers.leader = null;
        }

        // Hapus dari role sebelumnya
        if (pendingUsers.leader && String(pendingUsers.leader.id) === String(userId)) {
            pendingUsers.leader = null;
        }
        pendingUsers.members = pendingUsers.members.filter(m => String(m.id) !== String(userId));

        // Assign role baru
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
            const memberOption = select.querySelector('option[value="member"]');
            
            if (!leaderOption) return;
            
            // Owner tidak boleh diubah role-nya (tetap leader/owner)
            if (isOwner) {
                select.disabled = true;
                select.value = 'leader';
                return;
            }
            
            select.disabled = false;
            
            // Jika sudah ada leader (dan user ini bukan leader yang terpilih)
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

    // ====================== MODAL FUNCTIONS ======================
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
        updateTaskUserOptions();
        closeUserModal();
    }

    // ====================== FORM FUNCTIONS ======================
    function updateFormInputs() {
        document.getElementById('selected-owner-id').value = selectedUsers.owner?.id || currentUser.id;
        document.getElementById('selected-leader-id').value = selectedUsers.leader?.id || '';
        
        const container = document.getElementById('members-hidden-container');
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
        
        // Owner selalu ada (current user)
        if (selectedUsers.owner) {
            // Cek apakah owner juga sebagai leader
            const isOwnerAsLeader = selectedUsers.leader && selectedUsers.leader.id === selectedUsers.owner.id;
            const role = isOwnerAsLeader ? 'Owner & Leader' : 'Owner';
            selected.push({ ...selectedUsers.owner, role, isOwner: true });
        } else if (currentUser) {
            selected.push({ ...currentUser, role: 'Owner', isOwner: true });
        }
        
        // Leader (jika berbeda dengan owner)
        if (selectedUsers.leader && (!selectedUsers.owner || selectedUsers.owner.id !== selectedUsers.leader.id)) {
            selected.push({ ...selectedUsers.leader, role: 'Leader', isOwner: false });
        }
        
        // Members
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
            
            // Hanya tampilkan tombol hapus jika bukan Owner
            const showRemoveButton = user.role !== 'Owner' && user.role !== 'Owner & Leader';
            
            return `
                <div class="flex items-center justify-between p-4 border rounded-2xl ${styles}">
                    <div class="flex items-center gap-3">
                        ${user.photo_profile ?
                            `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800">` :
                            `<div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center ring-2 ring-white dark:ring-gray-800">
                                <span class="font-semibold text-current">${(user.nama_mahasiswa || '?').charAt(0).toUpperCase()}</span>
                            </div>`
                        }
                        <div>
                            <div class="font-medium">${user.role}: ${user.nama_mahasiswa || 'Unknown'}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">${user.email || ''}</div>
                        </div>
                    </div>
                    ${showRemoveButton ? `
                    <button type="button" onclick="removeUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80" title="Remove">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    ` : ''}
                </div>
            `;
        }).join('');
    }

    function removeUser(userId) {
        // Cek apakah yang dihapus adalah owner (tidak boleh)
        if (selectedUsers.owner && String(selectedUsers.owner.id) === String(userId)) {
            alert('Owner tidak dapat dihapus dari project.');
            return;
        }
        
        // Hapus dari leader jika yang dihapus adalah leader
        if (selectedUsers.leader && String(selectedUsers.leader.id) === String(userId)) {
            selectedUsers.leader = null;
        }
        
        // Hapus dari members
        selectedUsers.members = selectedUsers.members.filter(m => String(m.id) !== String(userId));
        
        updateFormInputs();
        renderSelectedUsers();
        updateTaskUserOptions();
    }

    function loadSelectedUsersFromForm() {
        selectedUsers.owner = currentUser;
        
        // Load leader (jika ada dan berbeda dengan owner)
        if (projectLeaderId && String(projectLeaderId) !== String(currentUser.id)) {
            let leader = getUserById(projectLeaderId);
            if (!leader && existingTasksData) {
                const taskUser = existingTasksData.find(t => String(t.user_id) === String(projectLeaderId));
                if (taskUser && taskUser.user_name) {
                    leader = { id: projectLeaderId, nama_mahasiswa: taskUser.user_name };
                }
            }
            selectedUsers.leader = leader || null;
        } else {
            selectedUsers.leader = null;
        }

        // Load members (filter owner dan leader)
        selectedUsers.members = [];
        projectMemberIds.forEach(memberId => {
            // Skip jika memberId adalah owner atau leader
            if (String(memberId) === String(currentUser.id)) return;
            if (selectedUsers.leader && String(memberId) === String(selectedUsers.leader.id)) return;
            
            let member = getUserById(memberId);
            if (!member && existingTasksData) {
                const taskUser = existingTasksData.find(t => String(t.user_id) === String(memberId));
                if (taskUser && taskUser.user_name) {
                    member = { id: memberId, nama_mahasiswa: taskUser.user_name };
                }
            }
            if (member) selectedUsers.members.push(member);
        });
        
        // Initialize pendingUsers
        syncPendingFromSelected();
    }

    // ====================== TOGGLE FUNCTIONS ======================
    function toggleUserSelectionSection() {
        const toggle = document.getElementById('project-collaborative-toggle');
        const userSelectionSection = document.getElementById('user-selection-section');
        const taskSection = document.getElementById('task-section');
        
        if (toggle && userSelectionSection) {
            if (toggle.checked) {
                userSelectionSection.style.display = 'block';
                if (selectedUsers.owner || selectedUsers.leader || selectedUsers.members.length > 0) {
                    taskSection.classList.remove('hidden');
                }
            } else {
                userSelectionSection.style.display = 'none';
                taskSection.classList.add('hidden');
                selectedUsers.owner = currentUser;
                selectedUsers.leader = null;
                selectedUsers.members = [];
                updateFormInputs();
                renderSelectedUsers();
                updateTaskUserOptions();
            }
        }
    }

    // ====================== DATE VALIDATION ======================
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

    // ====================== MODAL FILTERS ======================
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
            searchInput.addEventListener('input', function() {
                currentModalFilters.search = this.value;
                fetchWithSave(1);
            });
        }
        
        if (angkatanSelect) {
            angkatanSelect.addEventListener('change', function() {
                currentModalFilters.angkatan = this.value;
                fetchWithSave(1);
            });
        }
        
        if (jurusanSelect) {
            jurusanSelect.addEventListener('change', function() {
                currentModalFilters.jurusan = this.value;
                fetchWithSave(1);
            });
        }
        
        if (keahlianSelect) {
            keahlianSelect.addEventListener('change', function() {
                currentModalFilters.keahlian = this.value;
                fetchWithSave(1);
            });
        }
    }

    // ====================== PAGINATION ======================
    document.addEventListener('click', function(e) {
        const link = e.target.closest('#modal-pagination a');
        if (link) {
            e.preventDefault();
            const url = new URL(link.href);
            const page = url.searchParams.get('page') || 1;
            saveCurrentModalRoles();
            fetchUsers(page);
        }
    });

    // ====================== INIT ======================
    document.addEventListener('DOMContentLoaded', function() {
        loadSelectedUsersFromForm();
        renderSelectedUsers();
        initializeTaskRows();
        setupDateValidation();
        setupModalFilters();

        const collaborativeToggle = document.getElementById('project-collaborative-toggle');
        const toggleLabel = document.getElementById('toggle-label');
        
        if (collaborativeToggle && toggleLabel) {
            const hasMembers = selectedUsers.members.length > 0 || selectedUsers.leader;
            collaborativeToggle.checked = hasMembers;
            toggleLabel.textContent = hasMembers ? 'Aktif' : 'Nonaktif';
            toggleUserSelectionSection();
            
            collaborativeToggle.addEventListener('change', function() {
                toggleLabel.textContent = this.checked ? 'Aktif' : 'Nonaktif';
                toggleUserSelectionSection();
            });
        }
    });
</script>
 <!-- User selection modal -->
<div id="userModal"
    class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 overflow-y-auto">

    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/40" onclick="closeUserModal()"></div>

    <!-- Modal Box -->
    <div
        class="relative w-full max-w-xl md:max-w-4xl lg:max-w-5xl xl:max-w-6xl h-[90vh] bg-white dark:bg-gray-900 rounded-3xl shadow-2xl flex flex-col overflow-hidden">

        <!-- Header -->
        <div
            class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700 shrink-0 bg-white dark:bg-gray-900">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                data-translate="add_user"
                data-translate-page="dosen_add_pjt"></h2>

            <button type="button"
                onclick="closeUserModal()"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-300 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6 6l12 12M6 18L18 6" />
                </svg>

            </button>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto p-5">

            <div class="space-y-5">

                <!-- Filter -->
                <div class="grid gap-4 sm:grid-cols-2">

                    <input id="modal-search"
                        type="text"
                        placeholder="Cari nama atau email..."
                        data-translate-placeholder="search_name_placeholder"
                        data-translate-page="dosen_add_pjt"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">

                    <select id="modal-angkatan"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">
                        <option value=""
                            data-translate="all_angkatan"
                            data-translate-page="dosen_add_pjt">
                            Semua Angkatan
                        </option>
                        @foreach($angkatans as $angkatanItem)
                            <option value="{{ $angkatanItem->id }}">
                                {{ $angkatanItem->nama_angkatan }}
                            </option>
                        @endforeach
                    </select>

                    <select id="modal-jurusan"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">
                        <option value=""
                            data-translate="all_jurusan"
                            data-translate-page="dosen_add_pjt">
                            Semua Prodi
                        </option>
                        @foreach($jurusans as $jurusanItem)
                            <option value="{{ $jurusanItem->id_jurusan }}">
                                {{ $jurusanItem->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>

                    <select id="modal-keahlian"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">
                        <option value=""
                            data-translate="all_keahlian"
                            data-translate-page="dosen_add_pjt">
                            Semua Keahlian
                        </option>
                        @foreach($keahlians as $keahlianItem)
                            <option value="{{ $keahlianItem->id_keahlian }}">
                                {{ $keahlianItem->nama_keahlian }}
                            </option>
                        @endforeach
                    </select>

                </div>

                <!-- User List -->
                <div id="modal-user-list" class="space-y-3"></div>

                <!-- Pagination -->
                <div id="modal-pagination" class="mt-4 flex justify-center"></div>

            </div>

        </div>

        <!-- Footer -->
        <div
            class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700 shrink-0 bg-white dark:bg-gray-900">

            <button type="button"
                onclick="closeUserModal()"
                data-translate="cancel"
                data-translate-page="dosen_add_pjt"
                class="px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-200">
                Batal
            </button>

            <button type="button"
                onclick="confirmUserSelection()"
                data-translate="confirm"
                data-translate-page="dosen_add_pjt"
                class="px-4 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">
                Simpan
            </button>

        </div>

    </div>
</div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof showPageInfo === 'function') {
                showPageInfo("popup.user_edit_project");
            }
        });
    </script>
@endsection