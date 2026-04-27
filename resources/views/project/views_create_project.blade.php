@extends('Layout.Layout')
@section('title', autoTranslate('Tambah Project Baru'))
@section('content')
    <div class="p-6 lg:p-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2" data-translate="tambah_project"
            data-translate-page="project_create"></h1>
        <p class="text-gray-600 dark:text-gray-200" data-translate="desc_create" data-translate-page="project_create"></p>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('project.store') }}" class="space-y-6 mt-4" id="projectForm">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="nama_project" data-translate-page="project_create"></span> <span
                        class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_project" value="{{ old('nama_project') }}" required
                    class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                            focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                            text-gray-700 dark:text-gray-300
                                            placeholder-gray-500 dark:placeholder-gray-400
                                            shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm @error('nama_project') border-red-500 @enderror"
                    placeholder="{{ autoTranslate('Contoh: Website Portfolio Pribadi') }}">
                @error(autoTranslate('nama_project'))
                    <p class="mt-1 text-sm text-red-600">{{ autoTranslate($message) }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <span data-translate="deskripsi_opsional" data-translate-page="project_create"></span>
                </label>
                <textarea name="deskripsi" rows="4"
                    class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                            focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                            text-gray-700 dark:text-gray-300
                                            placeholder-gray-500 dark:placeholder-gray-400
                                            shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm @error('deskripsi') border-red-500 @enderror"
                    placeholder="{{ autoTranslate('Deskripsikan project Anda...') }}">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ autoTranslate($message) }}</p>
                @enderror
            </div>

            <!-- Project Collaborative Toggle -->
            <div class="flex items-center justify-between gap-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ autoTranslate('Projek Kolaboratif') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ autoTranslate('Aktifkan untuk menambahkan pemimpin dan anggota tim.') }}</p>
                </div>
                <label class="inline-flex items-center cursor-pointer">
                    <span class="relative">
                        <input id="project-collaborative-toggle" type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600"></div>
                    </span>
                    <span id="toggle-label" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-200">{{ autoTranslate('Nonaktif') }}</span>
                </label>
            </div>

            <!-- User Selection Section -->
            <div id="user-selection-section" style="display: none;">
                <div class="bg-white dark:bg-gray-800 p-5 md:p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ autoTranslate('Pemilihan User Project') }}</h3>
                        <div class="flex gap-2">
                            <button type="button" onclick="openUserModal()" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    {{ autoTranslate('Tambah User') }}
                                </span>
                            </button>
                            <button type="button" onclick="openUserModal()" 
                                class="px-4 py-2 bg-gray-600 text-white rounded-xl hover:bg-gray-700 transition">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    {{ autoTranslate('Edit') }}
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Selected Users Display -->
                    <div id="selected-users-container" class="space-y-3">
                        <!-- Users will be displayed here -->
                    </div>

                    <div id="no-users-message" class="text-center py-8 text-gray-500 dark:text-gray-400">
                        {{ autoTranslate('Belum ada leader atau member yang dipilih. Klik "Tambah User" untuk memulai.') }}
                    </div>
                </div>
            </div>

            <input type="hidden" name="owner" id="selected-owner-id" value="{{ Auth::id() }}">
            <input type="hidden" name="leader" id="selected-leader-id" value="{{ old('leader') }}">
            <input type="hidden" id="selected-members-ids" value="{{ old('members') ? implode(',', old('members')) : '' }}">
            <div id="selected-members-inputs" class="hidden"></div>

            <!-- User selection modal -->
            <div id="userModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/40" onclick="closeUserModal()"></div>
                <div class="relative w-full max-w-2xl bg-white dark:bg-gray-900 rounded-3xl shadow-2xl overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Pilih Leader / Member</h2>
                        <button type="button" onclick="closeUserModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-300">Tutup</button>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <input id="modal-search" type="text" placeholder="{{autoTranslate('Cari nama atau email...')}}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">
                            <select id="modal-angkatan" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">
                                <option value="">Semua Angkatan</option>
                                @foreach($angkatanList as $angkatanItem)
                                    <option value="{{ $angkatanItem->id }}">{{ $angkatanItem->nama_angkatan }}</option>
                                @endforeach
                            </select>
                            <select id="modal-jurusan" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">
                                <option value="">Semua Prodi</option>
                                @foreach($jurusanList as $jurusanItem)
                                    <option value="{{ $jurusanItem->id_jurusan }}">{{ autoTranslate($jurusanItem->nama_jurusan) }}</option>
                                @endforeach
                            </select>
                            <select id="modal-keahlian" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">
                                <option value="">Semua Keahlian</option>
                                @foreach($keahlianList as $keahlianItem)
                                    <option value="{{ $keahlianItem->id_keahlian }}">{{ autoTranslate($keahlianItem->nama_keahlian) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="modal-user-list" class="space-y-3 max-h-96 overflow-y-auto"></div>
                        <div id="modal-pagination" class="mt-4"></div>

                        <div class="flex justify-end gap-3 pt-4">
                            <button type="button" onclick="closeUserModal()" class="px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-xl">Batal</button>
                            <button type="button" onclick="confirmUserSelection()" class="px-4 py-3 bg-indigo-600 text-white rounded-xl">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="text-black dark:text-white" data-translate="tanggal_mulai"
                        data-translate-page="project_create"></span> <span class="text-red-500">*</span>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                        class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                            focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                            text-gray-700 dark:text-gray-300
                                            placeholder-gray-500 dark:placeholder-gray-400
                                            shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm @error('tanggal_mulai') border-red-500 @enderror">
                    @error('tanggal_mulai')
                        <p class="mt-1 text-sm text-red-600">{{ autoTranslate($message) }}</p>
                    @enderror
                </div>
                <div>
                    <span class="text-black dark:text-white" data-translate="tanggal_selesai"
                        data-translate-page="project_create"></span>
                    <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}"
                        class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                            focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                            text-gray-700 dark:text-gray-300
                                            placeholder-gray-500 dark:placeholder-gray-400
                                            shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm @error('tanggal_akhir') border-red-500 @enderror">
                    @error('tanggal_akhir')
                        <p class="mt-1 text-sm text-red-600">{{ autoTranslate($message) }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <span class="text-black dark:text-white" data-translate="link_project_opsional"
                    data-translate-page="project_create"></span>
                <input type="url" name="link_project" value="{{ old('link_project') }}"
                    class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                            focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                            text-gray-700 dark:text-gray-300
                                            placeholder-gray-500 dark:placeholder-gray-400
                                            shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm @error('link_project') border-red-500 @enderror"
                    placeholder="https://github.com/username/project">
                @error('link_project')
                    <p class="mt-1 text-sm text-red-600">{{ autoTranslate($message) }}</p>
                @enderror
            </div>

            <!-- Link GitHub -->
            <div>
                <label for="link_github" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="link_github_opsional" data-translate-page="project_create"></span>
                </label>
                <input type="url" name="link_github" id="link_github" maxlength="500"
                    class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                            focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                            text-gray-700 dark:text-gray-300
                                            placeholder-gray-500 dark:placeholder-gray-400
                                            shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition @error('link_github') border-red-500 @enderror"
                    placeholder="https://github.com/username/repo" value="{{ old('link_github') }}">
                @error('link_github')
                    <p class="mt-1 text-sm text-red-600">{{ autoTranslate($message) }}</p>
                @enderror
            </div>

            <!-- Link Video -->
            <div>
                <label for="link_video" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="link_video_opsional" data-translate-page="project_create"></span>
                </label>
                <input type="url" name="link_video" id="link_video" maxlength="500"
                    class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                            focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                            text-gray-700 dark:text-gray-300
                                            placeholder-gray-500 dark:placeholder-gray-400
                                            shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition @error('link_video') border-red-500 @enderror"
                    placeholder="https://www.youtube.com/watch?v=..." value="{{ old('link_video') }}">
                @error('link_video')
                    <p class="mt-1 text-sm text-red-600">{{ autoTranslate($message) }}</p>
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

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="px-8 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow-md">
                    <span data-translate="simpan_project" data-translate-page="project_create"></span>
                </button>
            </div>
        </form>
    </div>

    @php
        $currentUserData = Auth::check()
            ? Auth::user()->only(['id', 'nama_mahasiswa', 'photo_profile', 'email'])
            : null;
    @endphp
    <script>
        let allUsers = @json($users->items());
        const currentUser = @json($currentUserData);
        const userSelectionStorageKey = 'project_selected_users';
        let currentModalFilters = { search: '', angkatan: '', jurusan: '', keahlian: '' };
        let selectedUsers = { owner: null, leader: null, members: [] };
        let taskIndex = 0;

        function saveSelectedUsersToStorage() {
            const payload = {
                owner: selectedUsers.owner,
                leader: selectedUsers.leader,
                members: selectedUsers.members
            };
            localStorage.setItem(userSelectionStorageKey, JSON.stringify(payload));
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
            } catch (error) {
                console.warn('Unable to restore selected users from storage:', error);
                return false;
            }
        }

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
            // Cek apakah toggle aktif sebelum membuka modal
            const toggle = document.getElementById('project-collaborative-toggle');
            if (!toggle || !toggle.checked) {
                alert('Harap aktifkan mode kolaboratif terlebih dahulu untuk menambah user.');
                return;
            }
            document.getElementById('userModal').classList.remove('hidden');
            fetchUsers();
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
        }
        
        function filterUsersForModal() {
            return allUsers.filter(user => {
                // Exclude current user only if not already selected as owner
                if (currentUser && String(user.id) === String(currentUser.id) && !selectedUsers.owner) {
                    return false;
                }
                const keyword = currentModalFilters.search.toLowerCase().trim();

                // Search (nama atau email)
                const matchesSearch = !keyword || 
                    user.nama_mahasiswa.toLowerCase().includes(keyword) || 
                    (user.email && user.email.toLowerCase().includes(keyword));

                // Angkatan
                const matchesAngkatan = !currentModalFilters.angkatan || 
                    String(user.id_angkatan) === String(currentModalFilters.angkatan);

                // Jurusan - lebih aman (bisa dari relation atau field langsung)
                const userJurusanId = (user.jurusan && user.jurusan.id_jurusan) 
                              ? user.jurusan.id_jurusan 
                              : user.id_jurusan;
                const matchesJurusan = !currentModalFilters.jurusan || 
                    String(userJurusanId) === String(currentModalFilters.jurusan);

                // Keahlian - lebih aman
                const userKeahlianId = (user.keahlian && user.keahlian.id_keahlian) 
                               ? user.keahlian.id_keahlian 
                               : user.id_keahlian;
                const matchesKeahlian = !currentModalFilters.keahlian || 
                    String(userKeahlianId) === String(currentModalFilters.keahlian);

                return matchesSearch && matchesAngkatan && matchesJurusan && matchesKeahlian;
            });
        }

        // GANTI updateUserRole()
       function updateUserRole(selectElement, userId, role) {
    const user = allUsers.find(u => u.id == userId);
    if (!user) return;

    // Cek apakah user adalah owner (tidak boleh diubah role-nya)
    if (selectedUsers.owner && String(selectedUsers.owner.id) === String(userId)) {
        alert('Owner tidak dapat diubah role-nya!');
        selectElement.value = ''; // Reset select
        refreshRoleSelections();
        return;
    }

    // Cek jika mencoba memilih leader tapi sudah ada leader lain
    if (role === 'leader' && selectedUsers.leader && String(selectedUsers.leader.id) !== String(userId)) {
        const confirmChange = confirm(`Anda yakin ingin mengganti leader dari "${selectedUsers.leader.nama_mahasiswa}" menjadi "${user.nama_mahasiswa}"?`);
        if (!confirmChange) {
            selectElement.value = '';
            refreshRoleSelections();
            return;
        }
        // Hapus leader lama
        selectedUsers.leader = null;
    }

    // Hapus dari manapun dia berada dulu
    if (selectedUsers.leader && String(selectedUsers.leader.id) === String(userId)) {
        selectedUsers.leader = null;
    }
    selectedUsers.members = selectedUsers.members.filter(
        m => String(m.id) !== String(userId)
    );

    // Assign role baru
    if (role === 'leader') {
        selectedUsers.leader = user;
    } else if (role === 'member') {
        selectedUsers.members.push(user);
    }
    // role === '' => hapus saja (sudah di atas)

    updateFormInputs();
    renderSelectedUsers();
    updateTaskSectionVisibility();
    updateTaskUserOptions();
    updateSelectedUsersBadge();
    saveSelectedUsersToStorage();
    refreshRoleSelections();
    
    // Tutup modal jika masih terbuka
    const modal = document.getElementById('userModal');
    if (modal && !modal.classList.contains('hidden')) {
        closeUserModal();
    }
}
        
        function confirmUserSelection() {
            updateFormInputs();
            renderSelectedUsers();
            // Refresh task UI
            updateTaskSectionVisibility();
            updateTaskUserOptions();
            updateSelectedUsersBadge();
            saveSelectedUsersToStorage();
            closeUserModal();
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
                selected.push({ ...selectedUsers.owner, role: 'Owner' });
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
            container.innerHTML = selected.map(user => {
                const styles = {
                'Owner': 'bg-blue-50 dark:bg-blue-950 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
                'Leader': 'bg-green-50 dark:bg-green-950 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
                'Owner & Leader': 'bg-teal-50 dark:bg-teal-950 border-teal-200 dark:border-teal-800 text-teal-800 dark:text-teal-200',
                'Member': 'bg-purple-50 dark:bg-purple-950 border-purple-200 dark:border-purple-800 text-purple-800 dark:text-purple-200'
            }[user.role] || 'bg-gray-50 dark:bg-gray-950 border-gray-200 dark:border-gray-800 text-gray-800 dark:text-gray-200';
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
                            </button>
                            ` : ''}
                        </div>
                    </div>
                `;
            }).join('');
        }

        function editUser(userId) {
            // Open modal and focus on the specific user
            openUserModal();
            // Scroll to the user in modal after a short delay
            setTimeout(() => {
                const userElement = document.querySelector(`[data-user-id="${userId}"]`) || 
                                   document.querySelector(`.user-role-select[onchange*="${userId}"]`)?.closest('.flex.items-center.justify-between');
                if (userElement) {
                    userElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // Highlight temporarily
                    userElement.style.backgroundColor = '#fef3c7';
                    setTimeout(() => userElement.style.backgroundColor = '', 2000);
                }
            }, 500);
        }
        function fetchUsers(page = 1) {
    const params = new URLSearchParams({
        page: page,
        search: currentModalFilters.search,
        angkatan: currentModalFilters.angkatan,
        jurusan: currentModalFilters.jurusan,
        keahlian: currentModalFilters.keahlian
    });

    fetch(`{{ route('project.create') }}?${params}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('modal-user-list').innerHTML = data.userListHtml;
        document.getElementById('modal-pagination').innerHTML = data.paginationHtml;
        refreshRoleSelections();
    });
}

        function loadSelectedUsersFromForm() {
            if (restoreSelectedUsersFromStorage()) {
                updateFormInputs();
                return;
            }

            selectedUsers.owner = currentUser || null;
            const leaderId = document.getElementById('selected-leader-id')?.value;
            const memberIds = document.getElementById('selected-members-ids')?.value.split(',').filter(id => id) || [];

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
            let html = '<option value="">{{ autoTranslate("Pilih Penanggung Jawab") }}</option>';
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">{{ autoTranslate('Penanggung Jawab') }}</label>
                        <select name="tasks[${index}][user_id]" class="task-user-select w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                            ${renderTaskUserOptions(userId)}
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">{{ autoTranslate('Nama Tugas') }}</label>
                        <input type="text" name="tasks[${index}][name_task]" value="${taskName}" class="task-name-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white" placeholder="{{ autoTranslate('Deskripsikan tugas...') }}">
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
                fetchUsers();
            });
            document.getElementById('modal-angkatan')?.addEventListener('change', function() {
                currentModalFilters.angkatan = this.value;
                fetchUsers();
            });
            document.getElementById('modal-jurusan')?.addEventListener('change', function() {
                currentModalFilters.jurusan = this.value;
                fetchUsers();
            });
            document.getElementById('modal-keahlian')?.addEventListener('change', function() {
                currentModalFilters.keahlian = this.value;
                fetchUsers();
            });
        }

        function onSubmitProjectForm(event) {
    // Pastikan owner diisi dengan current user saat submit
    if (currentUser) {
        selectedUsers.owner = currentUser;
    }
    updateFormInputs();
    cleanupInvalidTaskRows();
    updateTaskUserOptions();
}

        // Fungsi untuk mengontrol visibilitas section user selection
       function toggleUserSelectionSection() {
    const toggle = document.getElementById('project-collaborative-toggle');
    const userSelectionSection = document.getElementById('user-selection-section');
    const leaderInput = document.getElementById('selected-leader-id');
    
    if (toggle && userSelectionSection) {
        if (toggle.checked) {
            userSelectionSection.style.display = 'block';
            // Jika toggle diaktifkan, pastikan leader bisa dipilih dari modal
            if (leaderInput) {
                leaderInput.removeAttribute('required');
                // Jangan otomatis set leader = currentUser
                // Biarkan user memilih leader dari modal
            }
        } else {
            userSelectionSection.style.display = 'none';
            // Ketika toggle dimatikan, user auth menjadi owner + leader
            selectedUsers.owner = currentUser;
            selectedUsers.leader = currentUser; // Hanya ini yang otomatis
            selectedUsers.members = [];
            updateFormInputs();
            renderSelectedUsers();
            updateTaskUserOptions();
            updateSelectedUsersBadge();
            saveSelectedUsersToStorage();
            if (leaderInput) leaderInput.removeAttribute('required');
        }
    }
}
function refreshRoleSelections() {
    const leaderId = selectedUsers.leader 
        ? String(selectedUsers.leader.id) 
        : null;
    const ownerId = selectedUsers.owner 
        ? String(selectedUsers.owner.id) 
        : null;

    document.querySelectorAll('.user-role-select').forEach(select => {
        const userId = String(select.dataset.userId);
        
        // Reset value
        select.value = '';
        
        // Set value berdasarkan selectedUsers
        if (leaderId === userId) {
            select.value = 'leader';
        } else if (selectedUsers.members.some(m => String(m.id) === userId)) {
            select.value = 'member';
        }
        
        // Disable options yang tidak tersedia
        const leaderOption = select.querySelector('option[value="leader"]');
        const memberOption = select.querySelector('option[value="member"]');
        
        if (leaderOption) {
            // Hanya disable leader jika:
            // 1. Sudah ada leader lain DAN user ini BUKAN leader yang terpilih
            // 2. User ini BUKAN owner (owner tetap bisa di select tapi akan ditolak di updateUserRole)
            if (leaderId && leaderId !== userId && ownerId !== userId) {
                leaderOption.disabled = true;
                leaderOption.title = 'Leader sudah dipilih';
            } else {
                leaderOption.disabled = false;
                leaderOption.title = '';
            }
        }
        
        if (memberOption) {
            memberOption.disabled = false;
        }
        
        // Disable select untuk owner (owner tidak boleh diganti role-nya)
        if (ownerId === userId) {
            select.disabled = true;
            select.title = 'Owner tidak dapat diubah role-nya';
        } else {
            select.disabled = false;
        }
    });
}

function loadSelectedUsersFromStorage() {
    if (restoreSelectedUsersFromStorage()) {
        updateFormInputs();
        return;
    }

    // Jangan set owner default di sini, biarkan user memilih saat menyimpan
    // selectedUsers.owner = currentUser || null; // HAPUS BARIS INI
    
    const leaderId = document.getElementById('selected-leader-id')?.value;
    const memberIds = document.getElementById('selected-members-ids')?.value.split(',').filter(id => id) || [];

    if (leaderId) selectedUsers.leader = getUserById(leaderId);
    selectedUsers.members = memberIds.map(id => getUserById(id)).filter(Boolean);
}
        // Fungsi untuk update min date pada tanggal_akhir berdasarkan tanggal_mulai
        function setupDateValidation() {
            const tanggalMulaiInput = document.getElementById('tanggal_mulai');
            const tanggalAkhirInput = document.getElementById('tanggal_akhir');

            if (!tanggalMulaiInput || !tanggalAkhirInput) return;

            // Set minimum date pada tanggal_akhir saat halaman dimuat (jika tanggal_mulai sudah ada)
            if (tanggalMulaiInput.value) {
                tanggalAkhirInput.min = tanggalMulaiInput.value;
            }

            // Update minimum date ketika tanggal_mulai berubah
            tanggalMulaiInput.addEventListener('change', function () {
                if (this.value) {
                    tanggalAkhirInput.min = this.value;
                    // Reset tanggal_akhir jika lebih kecil dari tanggal_mulai
                    if (tanggalAkhirInput.value && tanggalAkhirInput.value < this.value) {
                        tanggalAkhirInput.value = '';
                    }
                } else {
                    tanggalAkhirInput.min = '';
                }
            });

            // Optional: Validasi saat tanggal_akhir berubah
            tanggalAkhirInput.addEventListener('change', function () {
                if (this.value && tanggalMulaiInput.value && this.value < tanggalMulaiInput.value) {
                    this.value = '';
                    alert('Tanggal selesai harus setelah atau sama dengan tanggal mulai.');
                }
            });
        }

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


        document.addEventListener('DOMContentLoaded', function () {
            loadSelectedUsersFromForm();
            renderSelectedUsers();
            setupModalFilters();
            setupDateValidation();
            updateTaskSectionVisibility();
            updateSelectedUsersBadge();
            initializeTaskRows(@json(old('tasks', [])));
            document.getElementById('projectForm')?.addEventListener('submit', onSubmitProjectForm);

            const collaborativeToggle = document.getElementById('project-collaborative-toggle');
            const toggleLabel = document.getElementById('toggle-label');
            if (collaborativeToggle && toggleLabel) {
                toggleLabel.textContent = collaborativeToggle.checked ? 'Aktif' : 'Nonaktif';
                
                // Panggil fungsi toggle saat halaman dimuat untuk menyembunyikan section jika perlu
                toggleUserSelectionSection();
                
                collaborativeToggle.addEventListener('change', function () {
                    toggleLabel.textContent = this.checked ? 'Aktif' : 'Nonaktif';
                    toggleUserSelectionSection();
                });
            }
        });
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.user_create_project");
        });
    </script>

@endsection