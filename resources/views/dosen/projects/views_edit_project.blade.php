@extends('Layout.Layout')
@section('title', 'Edit Project Mahasiswa')
@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
        <div class="p-4 md:p-8 max-w-7xl mx-auto">
           
            <!-- Header -->
            <div class="mb-6 md:mb-8 text-center md:text-left">
                <div class="flex items-center gap-3 mb-2 justify-center md:justify-start">
                    <h1 data-translate="title_edit" data-translate-page="admin" class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">
                        Edit Project
                    </h1>
                </div>
                <p data-translate="desc_edit" data-translate-page="admin" class="mt-2 text-gray-600 dark:text-gray-400 text-sm md:text-base max-w-md mx-auto md:mx-0">
                    Edit informasi project atau ganti file jika diperlukan. Pastikan untuk menyimpan perubahan setelah selesai.
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
            <!-- Form -->
            <form method="POST" action="{{ route('dosen.projects.update', $project->id) }}" class="space-y-6 md:space-y-7" id="projectForm">
                @csrf
                @method('PATCH')
                
                <!-- User Selection Section -->
                <div class="bg-white dark:bg-gray-800 p-5 md:p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Pemilihan User Project</h3>
                        <button type="button" onclick="openUserModal()"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah User
                            </span>
                        </button>
                    </div>

                    <!-- Selected Users Display -->
                    <div id="selected-users-container" class="space-y-3">
                        <!-- Users will be displayed here -->
                    </div>

                    <div id="no-users-message" class="text-center py-8 text-gray-500 dark:text-gray-400">
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
                
                <!-- Nama Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="nm_project" data-translate-page="admin">Nama Project</span> <span class="text-red-500">*</span>
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
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="desc_project" data-translate-page="admin">Deskripsi (opsional)</span>
                    </label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('deskripsi') border-red-500 @enderror"
                        placeholder="Deskripsikan project Anda...">{{ old('deskripsi', $project->isi_content['deskripsi'] ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Tambah Tugas -->
                <div>
                    <label data-translate="add_task_opt" data-translate-page="dosen_add_pjt" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">
                        Tambah Tugas (opsional)
                    </label>
                    <div id="tasks-container" class="space-y-4"></div>
                    <button type="button" onclick="addTaskRow()"
                        class="mt-3 text-sm text-indigo-600 dark:text-indigo-400 hover:cursor-pointer hover:underline flex items-center gap-1">
                        <span class="text-xl">+</span> <span data-translate="add_task" data-translate-page="dosen_add_pjt">Tambah Tugas</span>
                    </button>
                    @error('tasks')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('tasks.*.user_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('tasks.*.name_task')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            <span data-translate="date_start" data-translate-page="admin">Tanggal Mulai</span> <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $project->tanggal_mulai->format('Y-m-d')) }}" required
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_mulai') border-red-500 @enderror">
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            <span data-translate="date_end" data-translate-page="admin">Tanggal Selesai</span> (opsional)
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
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2" data-translate="link_project_opsional" data-translate-page="project_create">Link Project (opsional)</label>
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2" data-translate="link_github_opsional" data-translate-page="project_create">Link GitHub (opsional)</label>
                        <input type="url" name="link_github" maxlength="500" value="{{ old('link_github', $project->isi_content['link_github'] ?? '') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_github') border-red-500 @enderror"
                            placeholder="https://github.com/username/repo">
                        @error('link_github')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2" data-translate="link_video_opsional" data-translate-page="project_create">Link Video (YouTube, opsional)</label>
                        <input type="url" name="link_video" maxlength="500" value="{{ old('link_video', $project->isi_content['link_video'] ?? '') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_video') border-red-500 @enderror"
                            placeholder="https://www.youtube.com/watch?v=...">
                        @error('link_video')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex-1"></div>
                   
                    <a href="{{ route('dosen.projects.index') }}" data-translate="cancel" data-translate-page="dosen_add_pjt"
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
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Pilih User untuk Project</h3>
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
                                <input type="text" id="modal-search" placeholder="Cari nama..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 
                                    dark:bg-gray-600 dark:text-white dark:border-gray-500 
                                    rounded-lg focus:ring-indigo-500 focus:border-indigo-500">

                                <!-- SVG icon -->
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <!-- contoh icon search -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M21 21l-4.35-4.35m1.6-5.4a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <select id="modal-angkatan" class="w-full px-3 py-2 border border-gray-300 dark:bg-gray-600 dark:text-white dark:border-gray-500 rounded-lg">
                                <option value="">Semua Angkatan</option>
                                @foreach($angkatans as $angk)
                                    <option value="{{ $angk->id }}">{{ $angk->nama_angkatan }}</option>
                                @endforeach
                            </select>
                            <select id="modal-jurusan" class="w-full px-3 py-2 border border-gray-300 dark:bg-gray-600 dark:text-white dark:border-gray-500 rounded-lg">
                                <option value="">Semua Jurusan</option>
                                @foreach($jurusans as $jrs)
                                    <option value="{{ $jrs->id_jurusan }}">{{ $jrs->nama_jurusan }}</option>
                                @endforeach
                            </select>
                            <select id="modal-keahlian" class="w-full px-3 py-2 border border-gray-300 dark:bg-gray-600 dark:text-white dark:border-gray-500 rounded-lg">
                                <option value="">Semua Keahlian</option>
                                @foreach($keahlians as $keahlianItem)
                                    <option value="{{ $keahlianItem->id_keahlian }}">{{ $keahlianItem->nama_keahlian }}</option>
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
                    <button onclick="closeUserModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                    <button onclick="confirmUserSelection()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
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
            // Use old input if available (after validation error)
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
            // Otherwise load from database
            $existingTasks = $project->tasks->map(function($task) {
                return [
                    'id' => $task->id,
                    'user_id' => $task->user_id,
                    'name_task' => $task->name_task ?? '',
                ];
            })->toArray();
        }
    @endphp
    <script>
        const allUsers = @json($users->items());
        let currentModalFilters = { search: '', angkatan: '', jurusan: '', keahlian: '' };
        let selectedUsers = { owner: null, leader: null, members: [] };
        let taskIndex = 0;

        function updateSelectedUsersBadge() {
            const badge = document.getElementById('selected-users-badge');
            if (!badge) return;

            let count = 0;
            if (selectedUsers.owner) count++;
            if (selectedUsers.leader) count++;
            count += selectedUsers.members.length;

            if (count === 0) {
                badge.innerHTML = '';
            } else {
                badge.innerHTML = `<span class="inline-block px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full text-xs font-semibold">${count} user(s) terpilih</span>`;
            }
        }

        function updateTaskSectionVisibility() {
            const taskSection = document.getElementById('task-section');
            if (!taskSection) return;

            let hasUsers = selectedUsers.owner || selectedUsers.leader || selectedUsers.members.length > 0;

            if (hasUsers) {
                taskSection.classList.remove('hidden');
            } else {
                taskSection.classList.add('hidden');
                // Clear tasks saat tidak ada users
                const container = document.getElementById('tasks-container');
                if (container) {
                    container.innerHTML = '';
                    taskIndex = 0;
                }
            }
        }

        function openUserModal() {
            document.getElementById('userModal').classList.remove('hidden');
            loadUsersToModal();
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
        }
        

        function filterUsersForModal() {
            console.log('Filtering with:', currentModalFilters);
console.log('Sample user jurusan:', allUsers[0]?.jurusan, 'id_jurusan:', allUsers[0]?.id_jurusan);
    return allUsers.filter(user => {
        const keyword = currentModalFilters.search.toLowerCase().trim();

        // Search (nama atau email)
        const matchesSearch = !keyword || 
            user.nama_mahasiswa.toLowerCase().includes(keyword) || 
            (user.email && user.email.toLowerCase().includes(keyword));

        // Angkatan
        const matchesAngkatan = !currentModalFilters.angkatan || 
            String(user.id_angkatan) === String(currentModalFilters.angkatan);

        // Jurusan - lebih aman (bisa dari relation atau field langsung)
        const userJurusanId = user.jurusan?.id_jurusan ?? user.id_jurusan;
        const matchesJurusan = !currentModalFilters.jurusan || 
            String(userJurusanId) === String(currentModalFilters.jurusan);

        // Keahlian - lebih aman
        const userKeahlianId = user.keahlian?.id_keahlian ?? user.id_keahlian;
        const matchesKeahlian = !currentModalFilters.keahlian || 
            String(userKeahlianId) === String(currentModalFilters.keahlian);

        return matchesSearch && matchesAngkatan && matchesJurusan && matchesKeahlian;
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

                return `
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center gap-3">
                            ${user.photo_profile ?
                                `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover">` :
                                `<div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <span class="text-indigo-600 dark:text-indigo-400 font-semibold">${user.nama_mahasiswa.charAt(0).toUpperCase()}</span>
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
                                <option value="owner" ${isOwner ? 'selected' : ''} ${selectedUsers.owner && !isOwner ? 'disabled' : ''}>Owner</option>
                                <option value="leader" ${isLeader ? 'selected' : ''} ${selectedUsers.leader && !isLeader ? 'disabled' : ''}>Leader</option>
                                <option value="member" ${isMember ? 'selected' : ''} ${memberDisabled ? 'disabled' : ''}>Member</option>
                            </select>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function updateUserRole(selectElement, userId, role) {
            const user = allUsers.find(u => u.id == userId);
            if (!user) return;

            // Hanya Owner yang tidak boleh duplikat
            if (role === 'owner' && selectedUsers.owner && selectedUsers.owner.id != userId) {
                alert('Owner sudah dipilih. Hapus owner yang ada terlebih dahulu jika ingin mengganti.');
                selectElement.value = '';
                return;
            }

            // Leader boleh diganti (tidak wajib)
            if (role === 'leader' && selectedUsers.leader && selectedUsers.leader.id != userId) {
                if (!confirm('Leader sudah ada. Ganti leader?')) {
                    selectElement.value = '';
                    return;
                }
            }

            // Reset dulu user ini dari semua role
            if (selectedUsers.owner?.id == userId) selectedUsers.owner = null;
            if (selectedUsers.leader?.id == userId) selectedUsers.leader = null;
            selectedUsers.members = selectedUsers.members.filter(m => m.id != userId);

            // Assign role baru
            if (role === 'owner') {
                selectedUsers.owner = user;
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
            // Refresh task UI
            updateTaskSectionVisibility();
            updateTaskUserOptions();
            updateSelectedUsersBadge();
            closeUserModal();
        }

        function updateFormInputs() {
            document.getElementById('selected-owner-id').value = selectedUsers.owner?.id || '';
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
            const noUsersMsg = document.getElementById('no-users-message');
            if (!container || !noUsersMsg) return;

            const selected = [];
            if (selectedUsers.owner) selected.push({ ...selectedUsers.owner, role: 'Owner' });
            if (selectedUsers.leader) selected.push({ ...selectedUsers.leader, role: 'Leader' });
            selectedUsers.members.forEach(member => selected.push({ ...member, role: 'Member' }));

            if (!selected.length) {
                container.innerHTML = '';
                noUsersMsg.classList.remove('hidden');
                return;
            }

            noUsersMsg.classList.add('hidden');
            container.innerHTML = selected.map(user => {
                const styles = {
                    Owner: 'bg-blue-50 dark:bg-blue-950 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
                    Leader: 'bg-green-50 dark:bg-green-950 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
                    Member: 'bg-purple-50 dark:bg-purple-950 border-purple-200 dark:border-purple-800 text-purple-800 dark:text-purple-200'
                }[user.role];

                return `
                    <div class="flex items-center justify-between p-4 border rounded-2xl ${styles}">
                        <div class="flex items-center gap-3">
                            ${user.photo_profile ?
                                `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800">` :
                                `<div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center ring-2 ring-white dark:ring-gray-800">
                                    <span class="font-semibold text-current">${user.nama_mahasiswa.charAt(0).toUpperCase()}</span>
                                </div>`
                            }
                            <div>
                                <div class="font-medium">${user.role}: ${user.nama_mahasiswa}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">${user.email}</div>
                            </div>
                        </div>
                        <button type="button" onclick="removeUser(${user.id})" class="text-current p-1 rounded-lg hover:opacity-80">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;
            }).join('');
        }

        function removeUser(userId) {
            if (selectedUsers.owner?.id == userId) selectedUsers.owner = null;
            if (selectedUsers.leader?.id == userId) selectedUsers.leader = null;
            selectedUsers.members = selectedUsers.members.filter(m => m.id != userId);
            updateFormInputs();
            renderSelectedUsers();
            // Refresh task UI
            updateTaskSectionVisibility();
            updateTaskUserOptions();
            updateSelectedUsersBadge();
        }

        function loadSelectedUsersFromForm() {
            const ownerId = document.getElementById('selected-owner-id')?.value;
            const leaderId = document.getElementById('selected-leader-id')?.value;
            const memberInputs = document.querySelectorAll('input[name="members[]"]');
            const memberIds = Array.from(memberInputs).map(input => input.value).filter(id => id);

            if (ownerId) selectedUsers.owner = getUserById(ownerId);
            if (leaderId) selectedUsers.leader = getUserById(leaderId);
            selectedUsers.members = memberIds.map(id => getUserById(id)).filter(Boolean);
        }

        function getUserById(id) {
            return allUsers.find(u => String(u.id) === String(id)) || null;
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
            let html = '<option value="">-- Pilih Penanggung Jawab --</option>';
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Penanggung Jawab</label>
                        <select name="tasks[${index}][user_id]" class="task-user-select w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                            ${renderTaskUserOptions(userId)}
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Nama Tugas</label>
                        <input type="text" name="tasks[${index}][name_task]" value="${taskName}" class="task-name-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white" placeholder="Deskripsikan tugas...">
                    </div>
                    <button type="button" onclick="removeTaskRow(this)" class="self-start mt-6 px-4 py-3 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl">Hapus</button>
                </div>
            `;

            container.appendChild(taskItem);
            const select = taskItem.querySelector('.task-user-select');
            if (select) select.addEventListener('change', updateTaskUserOptions);
        }

        function removeTaskRow(button) {
            const taskItem = button.closest('.task-item');
            if (taskItem) taskItem.remove();
            if (!document.querySelectorAll('.task-item').length) addTaskRow();
        }

        function cleanupInvalidTaskRows() {
            const allowedIds = getAllowedTaskUsers().map(user => String(user.id));
            document.querySelectorAll('.task-item').forEach(taskItem => {
                const select = taskItem.querySelector('.task-user-select');
                if (!select || !select.value || !allowedIds.includes(select.value)) {
                    taskItem.remove();
                }
            });
            if (!document.querySelectorAll('.task-item').length) addTaskRow();
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
                existingTasks.forEach(task => {
                    if (task.user_id || task.name_task) addTaskRow(task);
                });
            } else {
                addTaskRow();
            }
            updateTaskUserOptions();
        }

        function setupModalFilters() {
            document.getElementById('modal-search')?.addEventListener('input', function() {
                currentModalFilters.search = this.value;
                loadUsersToModal();
            });
            document.getElementById('modal-angkatan')?.addEventListener('change', function() {
                currentModalFilters.angkatan = this.value;
                loadUsersToModal();
            });
            document.getElementById('modal-jurusan')?.addEventListener('change', function() {
                currentModalFilters.jurusan = this.value;
                loadUsersToModal();
            });
            document.getElementById('modal-keahlian')?.addEventListener('change', function() {
                currentModalFilters.keahlian = this.value;
                loadUsersToModal();
            });
        }

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

        function setupDateConstraints() {
            const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]');
            const tanggalAkhir = document.querySelector('input[name="tanggal_akhir"]');
            if (!tanggalMulai || !tanggalAkhir) return;

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

        // Initialize modal functions
        document.addEventListener('DOMContentLoaded', function () {
            loadSelectedUsersFromForm();
            renderSelectedUsers();
            setupModalFilters();
            updateTaskSectionVisibility();
            updateSelectedUsersBadge();
            initializeTaskRows(@json($existingTasks));
            document.getElementById('projectForm')?.addEventListener('submit', onSubmitProjectForm);
            setupDateConstraints();
        });
    </script>
    <script>
        let currentFilters = { search: '{{ $search ?? '' }}', angkatan: '{{ $angkatan ?? '' }}', jurusan: '{{ $jurusan ?? '' }}', keahlian: '{{ $keahlian ?? '' }}' };
        let isCollaborative = {{ old('is_collaborative', $project->is_collaborative ?? 1) ? 'true' : 'false' }};
        function applyFilters() {
            currentFilters.search = document.getElementById('search-input').value.trim();
            currentFilters.angkatan = document.getElementById('angkatan-filter').value;
            currentFilters.jurusan = document.getElementById('jurusan-filter').value;
            currentFilters.keahlian = document.getElementById('keahlian-filter').value;
            fetchFilteredUsers();
        }
        function fetchFilteredUsers(page = 1) {
            const url = new URL('{{ route("project.edit", $project->id) }}');
            url.searchParams.set('search', currentFilters.search);
            url.searchParams.set('angkatan', currentFilters.angkatan);
            url.searchParams.set('jurusan', currentFilters.jurusan);
            url.searchParams.set('keahlian', currentFilters.keahlian);
            url.searchParams.set('page', page);
            fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                   
                    const newBody = doc.querySelector('#leader-table-body');
                    if (newBody) document.getElementById('leader-table-body').innerHTML = newBody.innerHTML;
                    const newPagination = doc.querySelector('#pagination-links');
                    if (newPagination) document.getElementById('pagination-links').innerHTML = newPagination.innerHTML;
                    attachTableRowListeners();
                    const selectedId = document.getElementById('selected-leader-id').value;
                    if (selectedId) {
                        const radio = document.querySelector(`.leader-radio[value="${selectedId}"]`);
                        if (radio) radio.checked = true;
                    }
                })
                .catch(err => console.error('Error:', err));
        }
        function attachTableRowListeners() {
            document.querySelectorAll('#leader-table-body tr, #owner-table-body tr').forEach(row => {
                const leaderRadio = row.querySelector('.leader-radio');
                const ownerRadio = row.querySelector('.owner-radio');
                const radio = leaderRadio || ownerRadio;
                if (radio) {
                    row.addEventListener('click', function(e) {
                        if (e.target.type !== 'radio') {
                            const nameEl = row.querySelector('td:nth-child(2) .font-medium');
                            const name = nameEl ? nameEl.textContent.trim() : '';
                            const img = row.querySelector('img');
                            const photoSrc = img ? img.src : '';
                            if (leaderRadio) {
                                selectLeader(radio.value, name, photoSrc);
                            } else if (ownerRadio) {
                                selectOwner(radio.value, name, photoSrc);
                            }
                        }
                    });
                }
            });
        }
        // Search untuk Leader Table
        function filterLeaderTable() {
            const keyword = document.getElementById('leader-search').value.toLowerCase().trim();
            document.querySelectorAll('#leader-table-body tr').forEach(row => {
                if (!row.querySelector('.leader-radio')) return;
                const nameText = row.textContent.toLowerCase();
                row.style.display = nameText.includes(keyword) ? '' : 'none';
            });
        }
        function selectLeader(userId, userName, photoProfile) {
            document.getElementById('selected-leader-id').value = userId;
            document.querySelectorAll('.leader-radio').forEach(radio => radio.checked = (radio.value == userId));
            const display = document.getElementById('selected-leader-display');
            const content = document.getElementById('selected-leader-content');
            let photoHtml = photoProfile
                ? `<img class="w-9 h-9 rounded-full object-cover ring-2 ring-green-200" src="${photoProfile}" alt="${userName}">`
                : `<div class="w-9 h-9 rounded-full bg-green-100 dark:bg-green-800 flex items-center justify-center">
                     <span class="text-green-700 dark:text-green-300 font-semibold">${userName.charAt(0).toUpperCase()}</span>
                   </div>`;
            content.innerHTML = `${photoHtml}<div class="font-medium text-green-800 dark:text-green-200">Pemimpin: ${userName}</div>`;
            display.classList.remove('hidden');
            updateDisabledOptions();
            saveToLocalStorage();
            syncTaskRows();
        }
        function clearSelectedLeader() {
            document.getElementById('selected-leader-id').value = '';
            document.querySelectorAll('.leader-radio').forEach(radio => radio.checked = false);
            document.getElementById('selected-leader-display').classList.add('hidden');
            updateDisabledOptions();
            saveToLocalStorage();
                syncTaskRows();
        }
        // ==================== ADD MEMBER SELECT (Dropdown Full) ====================
        function addMemberSelect(savedValue = null) {
            const container = document.getElementById('members-container');
            const memberDiv = document.createElement('div');
            memberDiv.classList.add('member-item', 'mb-4');
            const users = [];
            document.querySelectorAll('#leader-table-body tr').forEach(row => {
                const radio = row.querySelector('.leader-radio');
                if (radio) {
                    const nameElement = row.querySelector('td:nth-child(2) .font-medium');
                    const fullName = nameElement ? nameElement.textContent.trim() : 'Nama Tidak Diketahui';
                    const img = row.querySelector('img');
                    const photoProfile = img ? img.src : '';
                    const initial = fullName.charAt(0).toUpperCase();
                    users.push({ id: radio.value, name: fullName, photoProfile: photoProfile, initial: initial });
                }
            });
            if (users.length === 0) {
                memberDiv.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl border border-gray-200 dark:border-gray-700">
                        <select class="member-select w-full p-4 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-2xl" disabled>
                            <option value="">Tidak ada mahasiswa tersedia</option>
                        </select>
                    </div>
                `;
            } else {
                let optionsHtml = '<option value="">-- Pilih Rekan Project --</option>';
                users.forEach(user => {
                    const selected = savedValue && savedValue == user.id ? 'selected' : '';
                    optionsHtml += `<option value="${user.id}" data-photo="${user.photoProfile}" data-initial="${user.initial}" ${selected}>${user.name}</option>`;
                });
                memberDiv.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <div class="relative mb-4">
                            <input type="text" class="member-search w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm" placeholder="Cari nama rekan...">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex gap-3 items-center">
                            <div class="relative flex-1 w-full">
                                <select name="members[]" class="member-select w-full p-4 pl-14 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition text-base">
                                    ${optionsHtml}
                                </select>
                                <div class="member-photo absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <div class="w-9 h-9 rounded-2xl bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center overflow-hidden ring-2 ring-white dark:ring-gray-700">
                                        <span class="member-initial text-indigo-600 dark:text-indigo-400 font-semibold text-base"></span>
                                        <img class="member-img hidden w-full h-full object-cover rounded-2xl" src="" alt="">
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="removeMember(this)" class="px-6 py-4 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900 rounded-2xl transition font-medium flex-shrink-0">✕</button>
                        </div>
                    </div>
                `;
                container.appendChild(memberDiv);
                const select = memberDiv.querySelector('.member-select');
                const searchInput = memberDiv.querySelector('.member-search');
                const initialSpan = memberDiv.querySelector('.member-initial');
                const img = memberDiv.querySelector('.member-img');
                searchInput.addEventListener('input', function() {
                    const keyword = this.value.toLowerCase().trim();
                    Array.from(select.options).forEach(option => {
                        if (option.value === '') return;
                        option.style.display = option.textContent.toLowerCase().includes(keyword) ? '' : 'none';
                    });
                });
                select.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (this.value) {
                        const photo = selectedOption.dataset.photo;
                        const initial = selectedOption.dataset.initial;
                        if (photo && photo !== '') {
                            img.src = photo; img.classList.remove('hidden'); initialSpan.classList.add('hidden');
                        } else {
                            img.classList.add('hidden'); initialSpan.classList.remove('hidden'); initialSpan.textContent = initial;
                        }
                    } else {
                        img.classList.add('hidden'); initialSpan.classList.remove('hidden'); initialSpan.textContent = '?';
                    }
                    updateDisabledOptions();
                    saveToLocalStorage();
                });
                if (savedValue) {
                    select.value = savedValue;
                    select.dispatchEvent(new Event('change'));
                }
            }
            updateDisabledOptions();
            syncTaskRows();
        }
        function removeMember(button) {
            const memberDiv = button.closest('.member-item');
            if (memberDiv) memberDiv.remove();
            updateDisabledOptions();
            cleanupInvalidTaskRows();
            saveToLocalStorage();
                syncTaskRows();
        }
        function updateDisabledOptions() {
            const ownerId = document.getElementById('selected-owner-id').value || '';
            const leaderId = document.getElementById('selected-leader-id').value || '';
            const selectedMemberIds = [];
            document.querySelectorAll('.member-select').forEach(select => {
                if (select.value && !select.disabled) selectedMemberIds.push(select.value);
            });
            document.querySelectorAll('.member-select').forEach(select => {
                if (select.disabled) return;
                select.querySelectorAll('option').forEach(option => option.disabled = false);
                if (ownerId) {
                    const ownerOption = select.querySelector(`option[value="${ownerId}"]`);
                    if (ownerOption) ownerOption.disabled = true;
                }
                if (leaderId) {
                    const leaderOption = select.querySelector(`option[value="${leaderId}"]`);
                    if (leaderOption) leaderOption.disabled = true;
                }
                selectedMemberIds.forEach(selectedId => {
                    if (selectedId && select.value !== selectedId) {
                        const selectedOption = select.querySelector(`option[value="${selectedId}"]`);
                        if (selectedOption) selectedOption.disabled = true;
                    }
                });
            });
        }
        function saveToLocalStorage() {
            const ownerId = document.getElementById('selected-owner-id').value || '';
            const leaderId = document.getElementById('selected-leader-id').value || '';
            const memberIds = Array.from(document.querySelectorAll('.member-select'))
                .filter(s => s.value).map(s => s.value);
            localStorage.setItem('projectTeamData', JSON.stringify({ owner: ownerId, leader: leaderId, members: memberIds }));
        }
        function loadSavedData() {
            const savedData = localStorage.getItem('projectTeamData');
            if (!savedData) {
                // Load existing members from database
                const existingMembers = @json($project->members->pluck('id')->toArray());
                if (existingMembers.length > 0) {
                    existingMembers.forEach(id => addMemberSelect(id));
                } else {
                    addMemberSelect();
                }
                return;
            }
            try {
                const data = JSON.parse(savedData);
                if (data.owner) {
                    const radio = document.querySelector(`.owner-radio[value="${data.owner}"]`);
                    if (radio) {
                        const row = radio.closest('tr');
                        const nameEl = row.querySelector('td:nth-child(2) .font-medium');
                        const name = nameEl ? nameEl.textContent.trim() : '';
                        const img = row.querySelector('img');
                        const photo = img ? img.src : '';
                        selectOwner(data.owner, name, photo);
                    }
                }
                if (data.leader) {
                    const radio = document.querySelector(`.leader-radio[value="${data.leader}"]`);
                    if (radio) {
                        const row = radio.closest('tr');
                        const nameEl = row.querySelector('td:nth-child(2) .font-medium');
                        const name = nameEl ? nameEl.textContent.trim() : '';
                        const img = row.querySelector('img');
                        const photo = img ? img.src : '';
                        selectLeader(data.leader, name, photo);
                    }
                }
                const container = document.getElementById('members-container');
                container.innerHTML = '';
                if (data.members && data.members.length > 0) {
                    data.members.forEach(id => addMemberSelect(id));
                } else {
                    // Load existing members from database if no saved data
                    const existingMembers = @json($project->members->pluck('id')->toArray());
                    if (existingMembers.length > 0) {
                        existingMembers.forEach(id => addMemberSelect(id));
                    } else {
                        addMemberSelect();
                    }
                }
                setTimeout(updateDisabledOptions, 150);
            } catch (e) {
                console.error('Error loading saved data:', e);
                // Load existing members from database
                const existingMembers = @json($project->members->pluck('id')->toArray());
                if (existingMembers.length > 0) {
                    existingMembers.forEach(id => addMemberSelect(id));
                } else {
                    addMemberSelect();
                }
            }
        }
        // Task UI helpers
        let taskIndex = 0;
        let removedTaskIds = new Set();
        function getRemovedTasksFromStorage() {
    try {
        const data = localStorage.getItem('projectRemovedTasks');
        return data ? JSON.parse(data) : [];
    } catch (e) {
        return [];
    }
}

       function saveRemovedTasksToStorage() {
    localStorage.setItem('projectRemovedTasks', JSON.stringify(Array.from(removedTaskIds)));
}

        function markTaskAsRemoved(taskId) {
    if (!taskId) return;
    removedTaskIds.add(String(taskId));
    saveRemovedTasksToStorage();
}
        function toggleCollaborativeMode() {
            setCollaborativeMode(!isCollaborative);
        }
      
function syncTaskRows() {
    const allowedUsers = getAllowedTaskUsers();
    const currentTaskItems = document.querySelectorAll('.task-item');

    // 1. Hapus tugas yang usernya sudah tidak ada di team
    currentTaskItems.forEach(item => {
        const select = item.querySelector('.task-user-select');
        if (select && select.value) {
            const isStillAllowed = allowedUsers.some(u => String(u.id) === select.value);
            if (!isStillAllowed) {
                const hiddenId = item.querySelector('input[name$="[id]"]');
                if (hiddenId && hiddenId.value) {
                    markTaskAsRemoved(hiddenId.value);
                }
                item.remove();
            }
        }
    });

    // 2. Tambahkan tugas untuk user yang belum punya tugas
    allowedUsers.forEach(user => {
        const userIdStr = String(user.id);
        
        const alreadyHasTask = Array.from(document.querySelectorAll('.task-item')).some(item => {
            const sel = item.querySelector('.task-user-select');
            return sel && sel.value === userIdStr;
        });

        if (!alreadyHasTask) {
            addTaskRow({ user_id: user.id, name_task: '' });
        }
    });

    // Jika setelah semua tidak ada task, tambahkan 1 baris kosong
    if (document.querySelectorAll('.task-item').length === 0) {
        addTaskRow();
    }

    updateTaskUserOptions();
}


        function setCollaborativeMode(enabled) {
            isCollaborative = enabled;
            document.getElementById('collaborative-input').value = enabled ? 1 : 0;
            document.getElementById('collaborative-status').textContent = enabled ? 'On' : 'Off';
            const leaderSection = document.getElementById('leader-section');
            const memberWrapper = document.getElementById('member-wrapper');
            const addMemberBtn = document.getElementById('add-member-btn');
            if (!enabled) {
                if (leaderSection) leaderSection.classList.add('hidden');
                if (memberWrapper) memberWrapper.classList.add('hidden');
                if (addMemberBtn) addMemberBtn.setAttribute('disabled', 'disabled');
                // Set leader to owner and clear members
                const ownerId = document.getElementById('selected-owner-id').value;
                if (ownerId) {
                    selectLeader(ownerId, getUserNameById(ownerId) || 'Owner', '');
                }
                document.querySelectorAll('.member-item').forEach(item => item.remove());
                updateTaskUserOptions();
            } else {
                if (leaderSection) leaderSection.classList.remove('hidden');
                if (memberWrapper) memberWrapper.classList.remove('hidden');
                if (addMemberBtn) addMemberBtn.removeAttribute('disabled');
            }
            updateDisabledOptions();
        }
        function selectOwner(userId, userName, photoProfile) {
            document.getElementById('selected-owner-id').value = userId;
            document.querySelectorAll('.owner-radio').forEach(radio => radio.checked = (radio.value == userId));
            const display = document.getElementById('selected-owner-display');
            const content = document.getElementById('selected-owner-content');
            let photoHtml = photoProfile
                ? `<img class="w-9 h-9 rounded-full object-cover ring-2 ring-blue-200" src="${photoProfile}" alt="${userName}">`
                : `<div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center"><span class="text-blue-700 dark:text-blue-300 font-semibold">${userName.charAt(0).toUpperCase()}</span></div>`;
            content.innerHTML = `${photoHtml}<div class="font-medium text-blue-800 dark:text-blue-200">Owner: ${userName}</div>`;
            display.classList.remove('hidden');
            // Jika collaborative dimatikan maka leader harus mengikuti owner
            if (!isCollaborative) {
                selectLeader(userId, userName, photoProfile);
            }
            updateDisabledOptions();
            updateTaskUserOptions();
            syncTaskRows();
        }
        function clearSelectedOwner() {
            document.getElementById('selected-owner-id').value = '';
            document.querySelectorAll('.owner-radio').forEach(radio => radio.checked = false);
            document.getElementById('selected-owner-display').classList.add('hidden');
            updateDisabledOptions();
            updateTaskUserOptions();
            syncTaskRows();
        }
        function getUserNameById(userId) {
            const radio = document.querySelector(`.leader-radio[value="${userId}"], .owner-radio[value="${userId}"]`);
            if (radio) {
                const row = radio.closest('tr');
                const nameEl = row ? row.querySelector('.font-medium') : null;
                if (nameEl) return nameEl.textContent.trim();
            }
            const option = document.querySelector(`select[name="members[]"] option[value="${userId}"]`);
            if (option) {
                return option.textContent.trim();
            }
            return null;
        }
        function getAllowedTaskUsers() {
            const users = [];
            const added = new Set();
            const add = (id) => {
                if (!id || added.has(id)) return;
                added.add(id);
                const name = getUserNameById(id) || `User ${id}`;
                users.push({ id, name });
            };
            const ownerRadio = document.querySelector('.owner-radio:checked');
            if (ownerRadio) add(ownerRadio.value);
            const leaderRadio = document.querySelector('.leader-radio:checked');
            if (leaderRadio) add(leaderRadio.value);
            document.querySelectorAll('select[name="members[]"]').forEach(select => {
                if (select.value) add(select.value);
            });
            return users;
        }
        function renderTaskUserOptions(selectedId = '') {
            const users = getAllowedTaskUsers();
            let html = '<option data-translate="pick_rsp" data-translate-page="dosen_add_pjt" value="">-- Pilih Penanggung Jawab --</option>';
            users.forEach(user => {
                const selected = String(user.id) === String(selectedId) ? ' selected' : '';
                html += `<option value="${user.id}"${selected}>${user.name}</option>`;
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
                        <input type="text" name="tasks[${index}][name_task]" value="${taskName}" class="task-name-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white" placeholder="Deskripsikan tugas...">
                    </div>
                    <button type="button" onclick="removeTaskRow(this)" class="self-start mt-6 px-4 py-3 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl">Hapus</button>
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
    
    // Jika task ini punya ID (dari database), tandai sebagai removed
    if (hiddenIdInput && hiddenIdInput.value) {
        markTaskAsRemoved(hiddenIdInput.value);
    }

    // Hapus elemen dari DOM
    taskItem.remove();

    // Update options di semua select task
    updateTaskUserOptions();

    // Jika tidak ada task sama sekali, tambahkan satu baris kosong
    if (document.querySelectorAll('.task-item').length === 0) {
        addTaskRow();
    }

    // Sinkronisasi lagi dengan team
    setTimeout(syncTaskRows, 50);
}
        function cleanupInvalidTaskRows() {
          syncTaskRows();
        }
        function updateTaskUserOptions() {
            document.querySelectorAll('.task-user-select').forEach(select => {
                const currentValue = select.value;
                select.innerHTML = renderTaskUserOptions(currentValue);
                if (currentValue) {
                    select.value = currentValue;
                }
            });
        }
       function initializeTaskRows(existingTasks = []) {
    taskIndex = 0;
    const container = document.getElementById('tasks-container');
    if (!container) return;
    
    container.innerHTML = ''; // Kosongkan dulu

    // Ambil daftar task yang sudah di-remove dari localStorage
    const savedRemoved = getRemovedTasksFromStorage();
    removedTaskIds = new Set(savedRemoved.map(String));

    // Filter task: hanya tampilkan yang TIDAK ada di removedTaskIds
    const filteredTasks = Array.isArray(existingTasks)
        ? existingTasks.filter(task => {
            if (!task || !task.user_id || !task.name_task) return false;
            
            // Jika task punya ID dan sudah masuk removed → jangan tampilkan
            if (task.id && removedTaskIds.has(String(task.id))) {
                return false;
            }
            
            return true;
        })
        : [];

    if (filteredTasks.length > 0) {
        filteredTasks.forEach(task => {
            addTaskRow(task);
        });
    } else {
        // Jika setelah difilter tidak ada task sama sekali, tambahkan 1 baris kosong
        addTaskRow();
    }

    // Sinkronisasi dengan team saat ini (owner + leader + members)
    setTimeout(() => {
        syncTaskRows();
    }, 100);
}
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            const oldOwnerId = document.getElementById('selected-owner-id').value;
            if (oldOwnerId) {
                const radio = document.querySelector(`.owner-radio[value="${oldOwnerId}"]`);
                if (radio) {
                    const row = radio.closest('tr');
                    const nameEl = row.querySelector('td:nth-child(2) .font-medium');
                    const name = nameEl ? nameEl.textContent.trim() : '';
                    const img = row.querySelector('img');
                    const photo = img ? img.src : '';
                    selectOwner(oldOwnerId, name, photo);
                }
            }
            const oldLeaderId = document.getElementById('selected-leader-id').value;
            if (oldLeaderId) {
                const radio = document.querySelector(`.leader-radio[value="${oldLeaderId}"]`);
                if (radio) {
                    const row = radio.closest('tr');
                    const nameEl = row.querySelector('td:nth-child(2) .font-medium');
                    const name = nameEl ? nameEl.textContent.trim() : '';
                    const img = row.querySelector('img');
                    const photo = img ? img.src : '';
                    selectLeader(oldLeaderId, name, photo);
                }
            }
            setCollaborativeMode(isCollaborative);
            attachTableRowListeners();
            loadSavedData();
            // Initialize tasks with the data from the PHP variable (which includes old input if any)
            initializeTaskRows(@json($existingTasks));
            document.getElementById('projectForm').addEventListener('submit', () => {
                localStorage.removeItem('projectTeamData');
                localStorage.removeItem('projectRemovedTasks');
                localStorage.removeItem('projectRemovedTasksByUser');
            });
            // Global Filter Search + Enter
            document.getElementById('search-input').addEventListener('keypress', e => {
                if (e.key === 'Enter') { e.preventDefault(); applyFilters(); }
            });
            // Leader Search
            const leaderSearch = document.getElementById('leader-search');
            if (leaderSearch) leaderSearch.addEventListener('input', filterLeaderTable);
            const ownerSearch = document.getElementById('owner-search');
            if (ownerSearch) ownerSearch.addEventListener('input', filterLeaderTable);
            // Pagination AJAX
            document.addEventListener('click', e => {
                const link = e.target.closest('.pagination a');
                if (link) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    const page = url.searchParams.get('page') || 1;
                    fetchFilteredUsers(page);
                }
            });
        });
        // Filter Leader dan Owner Table Function
        function filterLeaderTable() {
            const leaderKeyword = document.getElementById('leader-search') ? document.getElementById('leader-search').value.toLowerCase().trim() : '';
            const ownerKeyword = document.getElementById('owner-search') ? document.getElementById('owner-search').value.toLowerCase().trim() : '';
            document.querySelectorAll('#leader-table-body tr').forEach(row => {
                if (!row.querySelector('.leader-radio')) return;
                row.style.display = row.textContent.toLowerCase().includes(leaderKeyword) ? '' : 'none';
            });
            document.querySelectorAll('#owner-table-body tr').forEach(row => {
                if (!row.querySelector('.owner-radio')) return;
                row.style.display = row.textContent.toLowerCase().includes(ownerKeyword) ? '' : 'none';
            });
        }
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.edit_project");
        });
    </script>
@endsection