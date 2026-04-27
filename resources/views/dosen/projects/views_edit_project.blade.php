@extends('Layout.Layout')
@section('title', 'Edit Project Mahasiswa')
@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
        <div class="p-4 md:p-8 max-w-7xl mx-auto">
           
            <!-- Header -->
            <div class="mb-6 md:mb-8 text-center md:text-left">
                <div class="flex items-center gap-3 mb-2 justify-center md:justify-start">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">
                        <span data-translate="title_edit" data-translate-page="admin">Edit Project Mahasiswa</span>
                    </h1>
                </div>
                <p data-translate="desc_edit" data-translate-page="admin" class="mt-2 text-gray-600 dark:text-gray-400 text-sm md:text-base max-w-md mx-auto md:mx-0">
                    Admin dapat mengedit data portofolio project mahasiswa
                </p>
            </div>
            
            <!-- Error Global -->
            @if ($errors->any())
                <div class="mb-6 md:mb-8 p-4 md:p-5 bg-red-50 border border-red-200 text-red-700 rounded-2xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">Terdapat kesalahan pada input:</span>
                    </div>
                    <ul class="list-disc pl-5 md:pl-10 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form - Gunakan PUT untuk update -->
            <form method="POST" action="{{ route('dosen.projects.update', ['id' => $project->id]) }}" class="space-y-6 md:space-y-7" id="projectForm">
                @csrf
                @method('PUT')
                
                <!-- User Selection Section -->
                <div class="bg-white dark:bg-gray-800 p-5 md:p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200" data-translate="user_selection" data-translate-page="dosen_add_pjt">Pemilihan User Project</h3>
                        <button type="button" onclick="openUserModal()" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span data-translate="add_user" data-translate-page="dosen_add_pjt">Tambah User</span>
                            </span>
                        </button>
                    </div>

                    <!-- Selected Users Display -->
                    <div id="selected-users-container" class="space-y-3">
                        <!-- Users will be displayed here -->
                    </div>

                    <div id="no-users-message" data-translate="no_users_selected" data-translate-page="dosen_add_pjt" class="text-center py-8 text-gray-500 dark:text-gray-400">
                        Belum ada user yang dipilih. Klik "Tambah User" untuk memulai.
                    </div>
                </div>

                <!-- Hidden inputs for selected users -->
                <input type="hidden" name="owner" id="selected-owner-id" value="{{ old('owner', $project->id_mahasiswa) }}">
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
                <input type="hidden" name="is_collaborative" id="is_collaborative" value="1">
                
                <!-- Nama Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="nm_pjt" data-translate-page="dosen_add_pjt">Nama Project</span> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_project" value="{{ old('nama_project', $project->isi_content['nama_project'] ?? '') }}" required
                        class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('nama_project') border-red-500 @enderror"
                        placeholder="Contoh: Website Portfolio Pribadi">
                    @error('nama_project')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label data-translate="desc_pjt" data-translate-page="dosen_add_pjt" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Deskripsi (opsional)
                    </label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('deskripsi') border-red-500 @enderror"
                        placeholder="Deskripsikan project Anda...">{{ old('deskripsi', $project->isi_content['deskripsi'] ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tambah Tugas -->
                <div id="task-section">
                    <label data-translate="add_task_opt" data-translate-page="dosen_add_pjt"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            <span data-translate="date_start" data-translate-page="dosen_add_pjt">Tanggal Mulai</span> <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $project->tanggal_mulai->format('Y-m-d')) }}" required
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_mulai') border-red-500 @enderror">
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label data-translate="date_end" data-translate-page="dosen_add_pjt"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Tanggal Selesai (opsional)
                        </label>
                        <input type="date" name="tanggal_akhir" value="{{ old('tanggal_akhir', $project->tanggal_akhir?->format('Y-m-d') ?? '') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_akhir') border-red-500 @enderror">
                        @error('tanggal_akhir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Link Project -->
                <div>
                    <label data-translate="link_pjt" data-translate-page="dosen_add_pjt"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Link Project (opsional)
                    </label>
                    <input type="url" name="link_project" value="{{ old('link_project', $project->isi_content['link_project'] ?? '') }}"
                        class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_project') border-red-500 @enderror"
                        placeholder="https://example.com/project">
                    @error('link_project')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link GitHub & Video -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label data-translate="link_github" data-translate-page="dosen_add_pjt"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Link GitHub (opsional)
                        </label>
                        <input type="url" name="link_github" maxlength="500" value="{{ old('link_github', $project->isi_content['link_github'] ?? '') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_github') border-red-500 @enderror"
                            placeholder="https://github.com/username/repo">
                    </div>
                    <div>
                        <label data-translate="link_vid" data-translate-page="dosen_add_pjt"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Link Video (YouTube, opsional)
                        </label>
                        <input type="url" name="link_video" maxlength="500" value="{{ old('link_video', $project->isi_content['link_video'] ?? '') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_video') border-red-500 @enderror"
                            placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex-1"></div>

                    <a href="{{ route('dosen.projects.index') }}" data-translate="cancel"
                        data-translate-page="dosen_add_pjt"
                        class="px-6 py-3.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition text-center w-full sm:w-auto">
                        Batal
                    </a>

                    <button type="submit" data-translate="upd_pjt" data-translate-page="dosen_add_pjt"
                        class="px-8 py-3.5 bg-indigo-600 text-white font-medium rounded-2xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-md w-full sm:w-auto">
                        Update Project
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- User Selection Modal -->
    <div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-2xl bg-white dark:bg-gray-800">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100" data-translate="select_user_title" data-translate-page="dosen_add_pjt">Pilih User untuk Project</h3>
                    <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="space-y-4">
                    <!-- Search and Filters -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="relative">
                                <input type="text" id="modal-search" value="{{ $search ?? '' }}" data-translate-placeholder="search_name_placeholder" data-translate-page="dosen_add_pjt" placeholder="Cari nama mahasiswa..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 
                                    dark:bg-gray-600 dark:text-white dark:border-gray-500 
                                    rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M21 21l-4.35-4.35m1.6-5.4a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <select id="modal-angkatan" class="w-full px-3 py-2 border border-gray-300 dark:bg-gray-600 dark:text-white dark:border-gray-500 rounded-lg">
                                <option value="" data-translate="all_angkatan" data-translate-page="dosen_add_pjt">Semua Angkatan</option>
                                @foreach($angkatans as $angk)
                                    <option value="{{ $angk->id }}" {{ $angkatan == $angk->id ? 'selected' : '' }}>{{ $angk->nama_angkatan }}</option>
                                @endforeach
                            </select>
                            <select id="modal-jurusan" class="w-full px-3 py-2 border border-gray-300 dark:bg-gray-600 dark:text-white dark:border-gray-500 rounded-lg">
                                <option value="" data-translate="all_jurusan" data-translate-page="dosen_add_pjt">Semua Jurusan</option>
                                @foreach($jurusans as $jrs)
                                    <option value="{{ $jrs->id_jurusan }}" {{ $jurusan == $jrs->id_jurusan ? 'selected' : '' }}>{{ $jrs->nama_jurusan }}</option>
                                @endforeach
                            </select>
                            <select id="modal-keahlian" class="w-full px-3 py-2 border border-gray-300 dark:bg-gray-600 dark:text-white dark:border-gray-500 rounded-lg">
                                <option value="" data-translate="all_keahlian" data-translate-page="dosen_add_pjt">Semua Keahlian</option>
                                @foreach($keahlians as $keahlianItem)
                                    <option value="{{ $keahlianItem->id_keahlian }}" {{ $keahlian == $keahlianItem->id_keahlian ? 'selected' : '' }}>{{ $keahlianItem->nama_keahlian }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- User List -->
                    <div class="max-h-96 overflow-y-auto">
                        <div id="modal-user-list" class="space-y-2">
                            <!-- Users will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button onclick="closeUserModal()" data-translate="cancel" data-translate-page="dosen_add_pjt" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                    <button onclick="confirmUserSelection()" data-translate="confirm" data-translate-page="dosen_add_pjt" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Konfirmasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    @php
        // Prepare existing tasks from old input or from the database
        $existingTasks = [];
        if (old('tasks')) {
            $oldTasks = old('tasks');
            foreach ($oldTasks as $index => $task) {
                if (isset($task['user_id']) && isset($task['name_task'])) {
                    $existingTasks[] = [
                        'id' => $task['id'] ?? null,
                        'user_id' => $task['user_id'],
                        'name_task' => $task['name_task'],
                    ];
                }
            }
        } else {
            $existingTasks = $project->tasks->map(function($task) {
                return [
                    'id' => $task->id,
                    'user_id' => $task->user_id,
                    'name_task' => $task->name_task ?? '',
                ];
            })->toArray();
        }
        
        // Get dosen attributes from authenticated user
        $dosenAttrs = Auth::user()->only(['id_jurusan', 'id_angkatan', 'id_keahlian']);
    @endphp

    <script>
        // Data from server
        const allUsers = @json($users->items());
        const dosenAttrs = @json($dosenAttrs);
        const projectId = @json($project->id);
        let selectedUsers = { owner: null, leader: null, members: [] };
        let taskIndex = 0;
        let removedTaskIds = new Set();
        let currentModalFilters = { search: '', angkatan: '', jurusan: '', keahlian: '' };

        // Helper functions
        function getUserById(id) {
            if (!id) return null;
            return allUsers.find(u => String(u.id) === String(id)) || null;
        }

        function isSimilarToDosen(user) {
            if (!user) return false;
            return String(user.id_jurusan) === String(dosenAttrs.id_jurusan)
                && String(user.id_angkatan) === String(dosenAttrs.id_angkatan)
                && String(user.id_keahlian) === String(dosenAttrs.id_keahlian);
        }

        function findBestReplacementForRole(role = 'owner') {
            // Priority 1: Same dosen attributes
            const sameDosenUsers = [];
            if (selectedUsers.leader && isSimilarToDosen(selectedUsers.leader)) sameDosenUsers.push(selectedUsers.leader);
            selectedUsers.members.forEach(m => { if (isSimilarToDosen(m)) sameDosenUsers.push(m); });
            
            if (sameDosenUsers.length > 0) return sameDosenUsers[0];
            
            // Priority 2: Any leader or member
            if (selectedUsers.leader) return selectedUsers.leader;
            if (selectedUsers.members.length > 0) return selectedUsers.members[0];
            
            return null;
        }

        // Update form inputs
        function updateFormInputs() {
            document.getElementById('selected-owner-id').value = selectedUsers.owner?.id || '';
            
            let effectiveLeaderId = '';
            if (selectedUsers.leader && selectedUsers.leader.id !== selectedUsers.owner?.id) {
                effectiveLeaderId = selectedUsers.leader.id;
            } else if (selectedUsers.leader && selectedUsers.leader.id === selectedUsers.owner?.id) {
                effectiveLeaderId = selectedUsers.leader.id;
            } else if (selectedUsers.owner && !selectedUsers.leader) {
                effectiveLeaderId = '';
            }
            document.getElementById('selected-leader-id').value = effectiveLeaderId;
            
            const container = document.getElementById('members-hidden-container');
            container.innerHTML = '';
            selectedUsers.members.forEach(member => {
                if (member.id !== selectedUsers.owner?.id && member.id !== selectedUsers.leader?.id) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'members[]';
                    input.value = member.id;
                    container.appendChild(input);
                }
            });
        }

        // Render selected users display
        function renderSelectedUsers() {
            const container = document.getElementById('selected-users-container');
            const noUsersMsg = document.getElementById('no-users-message');
            if (!container || !noUsersMsg) return;

            const selected = [];
            
            if (selectedUsers.owner) {
                const isAlsoLeader = (selectedUsers.leader && selectedUsers.leader.id === selectedUsers.owner.id);
                const hasMembers = selectedUsers.members.length > 0;
                if (isAlsoLeader && hasMembers) {
                    selected.push({ ...selectedUsers.owner, role: 'Owner & Leader' });
                } else if (hasMembers) {
                    selected.push({ ...selectedUsers.owner, role: 'Owner' });
                } else {
                    selected.push({ ...selectedUsers.owner, role: 'Owner' });
                }
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
                container.innerHTML = '';
                noUsersMsg.classList.remove('hidden');
                return;
            }

            noUsersMsg.classList.add('hidden');
            
            const styles = {
                'Owner': 'bg-blue-50 dark:bg-blue-950 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
                'Leader': 'bg-green-50 dark:bg-green-950 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
                'Member': 'bg-purple-50 dark:bg-purple-950 border-purple-200 dark:border-purple-800 text-purple-800 dark:text-purple-200',
                'Owner & Leader': 'bg-indigo-50 dark:bg-indigo-950 border-indigo-200 dark:border-indigo-800 text-indigo-800 dark:text-indigo-200'
            };

            container.innerHTML = selected.map(user => {
                const userStyle = styles[user.role] || styles.Member;
                const isSameDosen = isSimilarToDosen(user);
                const showRemoveButton = isSameDosen;

                return `
                    <div class="flex items-center justify-between p-4 border rounded-2xl ${userStyle} ${!isSameDosen ? 'opacity-60' : ''}">
                        <div class="flex items-center gap-3">
                            ${user.photo_profile ?
                                `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800">` :
                                `<div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center ring-2 ring-white dark:ring-gray-800">
                                    <span class="font-semibold text-current">${(user.nama_mahasiswa || '?').charAt(0).toUpperCase()}</span>
                                </div>`
                            }
                            <div>
                                <div class="font-medium">${user.role}: ${user.nama_mahasiswa}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">${user.email || ''}</div>
                            </div>
                        </div>
                        ${showRemoveButton ? `
                            <button type="button" onclick="removeUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        ` : ''}
                    </div>
                `;
            }).join('');
        }

        // Remove user function with auto-replacement logic
        function removeUser(userId) {
            const wasOwner = selectedUsers.owner?.id == userId;
            const wasLeader = selectedUsers.leader?.id == userId;
            
            // Remove from all roles
            if (selectedUsers.owner?.id == userId) selectedUsers.owner = null;
            if (selectedUsers.leader?.id == userId) selectedUsers.leader = null;
            selectedUsers.members = selectedUsers.members.filter(m => m.id != userId);
            
            // If owner was removed, find replacement
            if (wasOwner) {
                const replacement = findBestReplacementForRole('owner');
                if (replacement) {
                    selectedUsers.owner = replacement;
                    if (selectedUsers.leader?.id == replacement.id) {
                        selectedUsers.leader = null;
                    }
                    selectedUsers.members = selectedUsers.members.filter(m => m.id != replacement.id);
                }
            }
            
            // If leader was removed and we have no leader, set leader to owner if members exist
            if (wasLeader && !selectedUsers.leader && selectedUsers.owner && selectedUsers.members.length > 0) {
                selectedUsers.leader = selectedUsers.owner;
            }
            
            updateFormInputs();
            renderSelectedUsers();
            updateTaskSectionVisibility();
            updateTaskUserOptions();
            updateSelectedUsersBadge();
        }

        // Modal functions
        function openUserModal() {
            document.getElementById('userModal').classList.remove('hidden');
            loadUsersToModal();
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        function filterUsersForModal() {
            return allUsers.filter(user => {
                const keyword = currentModalFilters.search.toLowerCase().trim();
                const matchesSearch = !keyword || 
                    (user.nama_mahasiswa && user.nama_mahasiswa.toLowerCase().includes(keyword)) || 
                    (user.email && user.email.toLowerCase().includes(keyword));
                
                const matchesAngkatan = !currentModalFilters.angkatan || 
                    String(user.id_angkatan) === String(currentModalFilters.angkatan);
                
                const userJurusanId = user.jurusan?.id_jurusan ?? user.id_jurusan;
                const matchesJurusan = !currentModalFilters.jurusan || 
                    String(userJurusanId) === String(currentModalFilters.jurusan);
                
                const userKeahlianId = user.keahlian?.id_keahlian ?? user.id_keahlian;
                const matchesKeahlian = !currentModalFilters.keahlian || 
                    String(userKeahlianId) === String(currentModalFilters.keahlian);
                
                // Only show users that match dosen attributes
                const matchesDosen = isSimilarToDosen(user);
                
                return matchesSearch && matchesAngkatan && matchesJurusan && matchesKeahlian && matchesDosen;
            });
        }

        function loadUsersToModal() {
            const userList = document.getElementById('modal-user-list');
            const filteredUsers = filterUsersForModal();
            if (!userList) return;

            if (filteredUsers.length === 0) {
                userList.innerHTML = `
                    <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                        Tidak ada mahasiswa yang sesuai filter.
                    </div>
                `;
                return;
            }

            userList.innerHTML = filteredUsers.map(user => {
                const isOwner = selectedUsers.owner?.id == user.id;
                const isLeader = selectedUsers.leader?.id == user.id;
                const isMember = selectedUsers.members.some(m => m.id == user.id);
                const memberDisabled = isOwner || isLeader;
                const leaderDisabled = selectedUsers.leader && selectedUsers.leader.id != user.id && selectedUsers.leader.id != selectedUsers.owner?.id;
                const selectedRole = isOwner ? 'owner' : isLeader ? 'leader' : isMember ? 'member' : '';

                return `
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center gap-3">
                            ${user.photo_profile ?
                                `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover">` :
                                `<div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <span class="text-indigo-600 dark:text-indigo-400 font-semibold">${(user.nama_mahasiswa || '?').charAt(0).toUpperCase()}</span>
                                </div>`
                            }
                            <div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">${user.nama_mahasiswa}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">${user.email}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <select class="user-role-select px-3 py-1 border border-gray-300 dark:border-gray-500 rounded-lg text-sm" onchange="updateUserRole(this, ${user.id}, this.value)">
                                <option value="">-- Pilih Role --</option>
                                <option value="owner" ${selectedRole === 'owner' ? 'selected' : ''} ${selectedUsers.owner && !isOwner ? 'disabled' : ''}>Owner</option>
                                <option value="leader" ${selectedRole === 'leader' ? 'selected' : ''} ${leaderDisabled ? 'disabled' : ''}>Leader</option>
                                <option value="member" ${selectedRole === 'member' ? 'selected' : ''} ${memberDisabled ? 'disabled' : ''}>Member</option>
                            </select>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function updateUserRole(selectElement, userId, role) {
            const user = getUserById(userId);
            if (!user) return;

            // Check if user belongs to the same dosen
            if (!isSimilarToDosen(user)) {
                alert('Mahasiswa ini tidak sesuai dengan data dosen Anda.');
                selectElement.value = '';
                return;
            }

            // Owner cannot be duplicated
            if (role === 'owner' && selectedUsers.owner && selectedUsers.owner.id != userId) {
                if (confirm('Owner sudah dipilih. Apakah Anda ingin mengganti owner yang ada?')) {
                    // Remove old owner
                    const oldOwnerId = selectedUsers.owner.id;
                    if (selectedUsers.leader?.id == oldOwnerId) selectedUsers.leader = null;
                    selectedUsers.members = selectedUsers.members.filter(m => m.id != oldOwnerId);
                    selectedUsers.owner = null;
                } else {
                    selectElement.value = '';
                    return;
                }
            }

            // Remove user from all roles first
            if (selectedUsers.owner?.id == userId) selectedUsers.owner = null;
            if (selectedUsers.leader?.id == userId) selectedUsers.leader = null;
            selectedUsers.members = selectedUsers.members.filter(m => m.id != userId);

            // Assign new role
            if (role === 'owner') {
                selectedUsers.owner = user;
                // If no leader exists and we have members, set leader as owner
                if (!selectedUsers.leader && selectedUsers.members.length > 0) {
                    selectedUsers.leader = user;
                }
            } else if (role === 'leader') {
                selectedUsers.leader = user;
            } else if (role === 'member') {
                selectedUsers.members.push(user);
            }

            updateFormInputs();
            renderSelectedUsers();
            loadUsersToModal();
            updateTaskSectionVisibility();
            updateTaskUserOptions();
            updateSelectedUsersBadge();
        }

        function confirmUserSelection() {
            updateFormInputs();
            renderSelectedUsers();
            updateTaskSectionVisibility();
            updateTaskUserOptions();
            updateSelectedUsersBadge();
            closeUserModal();
        }

        // Task functions
        function getAllowedTaskUsers() {
            const users = [];
            const added = new Set();
            const add = user => {
                if (!user || added.has(user.id) || !isSimilarToDosen(user)) return;
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
            let html = '<option value="" data-translate="pick_rsp" data-translate-page="dosen_add_pjt">-- Pilih Penanggung Jawab --</option>';
            users.forEach(user => {
                html += `<option value="${user.id}" ${String(user.id) === String(selectedId) ? 'selected' : ''}>${user.name}</option>`;
            });
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2" data-translate="rsp_task" data-translate-page="dosen_add_pjt">Penanggung Jawab</label>
                        <select name="tasks[${index}][user_id]" class="task-user-select w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                            ${renderTaskUserOptions(userId)}
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2" data-translate="nm_task" data-translate-page="dosen_add_pjt">Nama Tugas</label>
                        <input type="text" name="tasks[${index}][name_task]" value="${taskName}" class="task-name-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white" placeholder="Nama tugas..." data-translate-placeholder="task_placeholder" data-translate-page="dosen_add_pjt">
                    </div>
                    <button type="button" onclick="removeTaskRow(this)" class="self-start mt-6 px-4 py-3 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl" data-translate="del" data-translate-page="dosen_add_pjt">Hapus</button>
                </div>
            `;

            container.appendChild(taskItem);
            const select = taskItem.querySelector('.task-user-select');
            if (select) {
                select.addEventListener('change', updateTaskUserOptions);
            }
        }

        function removeTaskRow(button) {
            const taskItem = button.closest('.task-item');
            if (!taskItem) return;

            const hiddenIdInput = taskItem.querySelector('input[name^="tasks"][name$="[id]"]');
            if (hiddenIdInput && hiddenIdInput.value) {
                removedTaskIds.add(String(hiddenIdInput.value));
            }

            taskItem.remove();

            if (!document.querySelectorAll('.task-item').length) {
                addTaskRow();
            }

            updateTaskUserOptions();
        }

        function cleanupInvalidTaskRows() {
            const allowedIds = getAllowedTaskUsers().map(user => String(user.id));
            document.querySelectorAll('.task-item').forEach(taskItem => {
                const select = taskItem.querySelector('.task-user-select');
                if (!select || !select.value || !allowedIds.includes(select.value)) {
                    const hiddenIdInput = taskItem.querySelector('input[name^="tasks"][name$="[id]"]');
                    if (hiddenIdInput && hiddenIdInput.value) {
                        removedTaskIds.add(String(hiddenIdInput.value));
                    }
                    taskItem.remove();
                }
            });

            const hasAllowedUsers = allowedIds.length > 0;
            if (!document.querySelectorAll('.task-item').length && hasAllowedUsers) {
                addTaskRow();
            }
        }

        function updateTaskUserOptions() {
            document.querySelectorAll('.task-user-select').forEach(select => {
                const currentValue = select.value;
                select.innerHTML = renderTaskUserOptions(currentValue);
                if (currentValue) {
                    select.value = currentValue;
                }
            });
            cleanupInvalidTaskRows();
        }

        function initializeTaskRows() {
            const container = document.getElementById('tasks-container');
            if (!container) return;
            container.innerHTML = '';
            taskIndex = 0;

            const existingTasks = @json($existingTasks);
            
            if (existingTasks && Array.isArray(existingTasks) && existingTasks.length) {
                existingTasks.forEach(task => {
                    if (task.user_id || task.name_task) {
                        // Check if task user is still valid (matches dosen filters)
                        const taskUser = getUserById(task.user_id);
                        if (taskUser && isSimilarToDosen(taskUser)) {
                            addTaskRow(task);
                        }
                    }
                });
            }
            
            if (!document.querySelectorAll('.task-item').length) {
                addTaskRow();
            }
            
            updateTaskUserOptions();
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
                if (container) {
                    container.innerHTML = '';
                    taskIndex = 0;
                }
            }
        }

        function updateSelectedUsersBadge() {
            const badge = document.getElementById('selected-users-badge');
            if (!badge) return;

            let count = 0;
            if (selectedUsers.owner) count++;
            if (selectedUsers.leader && selectedUsers.leader.id !== selectedUsers.owner?.id) count++;
            count += selectedUsers.members.length;

            if (count === 0) {
                if (badge) badge.innerHTML = '';
            } else {
                if (badge) badge.innerHTML = `<span class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full text-xs font-semibold">${count} user(s) terpilih</span>`;
            }
        }

        function loadSelectedUsersFromForm() {
            const ownerId = document.getElementById('selected-owner-id')?.value;
            const leaderId = document.getElementById('selected-leader-id')?.value;
            const memberInputs = document.querySelectorAll('#members-hidden-container input[name="members[]"]');
            const memberIds = Array.from(memberInputs).map(input => input.value).filter(id => id);

            if (ownerId) {
                const owner = getUserById(ownerId);
                if (owner) selectedUsers.owner = owner;
            }

            if (leaderId && leaderId !== ownerId) {
                const leader = getUserById(leaderId);
                if (leader) selectedUsers.leader = leader;
            }

            memberIds.forEach(id => {
                const member = getUserById(id);
                if (member && member.id !== selectedUsers.owner?.id && member.id !== selectedUsers.leader?.id) {
                    selectedUsers.members.push(member);
                }
            });

            // If no leader but has members, set leader to owner when possible
            if (!selectedUsers.leader && selectedUsers.owner && selectedUsers.members.length > 0) {
                selectedUsers.leader = selectedUsers.owner;
            }
        }

        function setupModalFilters() {
            const searchInput = document.getElementById('modal-search');
            const angkatanSelect = document.getElementById('modal-angkatan');
            const jurusanSelect = document.getElementById('modal-jurusan');
            const keahlianSelect = document.getElementById('modal-keahlian');
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    currentModalFilters.search = this.value;
                    loadUsersToModal();
                });
            }
            
            if (angkatanSelect) {
                angkatanSelect.addEventListener('change', function() {
                    currentModalFilters.angkatan = this.value;
                    loadUsersToModal();
                });
            }
            
            if (jurusanSelect) {
                jurusanSelect.addEventListener('change', function() {
                    currentModalFilters.jurusan = this.value;
                    loadUsersToModal();
                });
            }
            
            if (keahlianSelect) {
                keahlianSelect.addEventListener('change', function() {
                    currentModalFilters.keahlian = this.value;
                    loadUsersToModal();
                });
            }
        }

        function onSubmitProjectForm(event) {
            function getFirstOutsideUser() {
                if (selectedUsers.leader && !isSimilarToDosen(selectedUsers.leader)) {
                    return selectedUsers.leader;
                }
                for (let member of selectedUsers.members) {
                    if (!isSimilarToDosen(member)) {
                        return member;
                    }
                }
                return null;
            }

            if (!selectedUsers.owner) {
                const outsideUser = getFirstOutsideUser();
                if (outsideUser) {
                    selectedUsers.owner = outsideUser;
                    if (selectedUsers.leader?.id === outsideUser.id) {
                        selectedUsers.leader = null;
                    }
                    selectedUsers.members = selectedUsers.members.filter(m => m.id !== outsideUser.id);
                    renderSelectedUsers();
                }
            }

            if (!selectedUsers.owner) {
                 event.preventDefault();
                alert('Owner harus dipilih.');
                return;
            }

            updateFormInputs();
            cleanupInvalidTaskRows();
            updateTaskUserOptions();
        }

        function setupDateConstraints() {
            const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]');
            const tanggalAkhir = document.querySelector('input[name="tanggal_akhir"]');
            
            if (tanggalMulai && tanggalAkhir) {
                const updateMinAkhir = () => {
                    if (tanggalMulai.value) {
                        const startDate = new Date(tanggalMulai.value);
                        const minDate = new Date(startDate);
                        minDate.setDate(startDate.getDate() + 1);
                        const minDateStr = minDate.toISOString().split('T')[0];
                        tanggalAkhir.min = minDateStr;
                        
                        if (tanggalAkhir.value && tanggalAkhir.value < minDateStr) {
                            tanggalAkhir.value = '';
                        }
                    } else {
                        tanggalAkhir.min = '';
                    }
                };
                
                tanggalMulai.addEventListener('change', updateMinAkhir);
                updateMinAkhir();
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadSelectedUsersFromForm();
            renderSelectedUsers();
            setupModalFilters();
            updateTaskSectionVisibility();
            updateSelectedUsersBadge();
            initializeTaskRows();
            setupDateConstraints();
            
            document.getElementById('projectForm')?.addEventListener('submit', onSubmitProjectForm);
            
            if (typeof window.refreshTranslations === 'function') {
                window.refreshTranslations();
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.edit_project");
        });
    </script>
@endsection