@extends('Layout.Layout')
@section('title', 'Tambah Project Mahasiswa Baru')
@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
        <div class="p-4 md:p-8 max-w-7xl mx-auto">

            <!-- Header -->
            <div class="mb-6 md:mb-8 text-center md:text-left">
                <div class="flex items-center gap-3 mb-2 justify-center md:justify-start">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">
                        <span data-translate="ttl_form" data-translate-page="dosen_add_pjt">Tambah Project Mahasiswa
                            Baru</span>
                    </h1>
                </div>
                <p data-translate="desc_form" data-translate-page="dosen_add_pjt"
                    class="mt-2 text-gray-600 dark:text-gray-400 text-sm md:text-base max-w-md mx-auto md:mx-0">
                    Admin dapat membantu membuat dan mengisi portofolio project mahasiswa
                </p>
            </div>

            <!-- Error Global -->
            @if ($errors->any())
                <div class="mb-6 md:mb-8 p-4 md:p-5 bg-red-50 border border-red-200 text-red-700 rounded-2xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
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
            <form method="POST" action="{{ route('admin.projects.store') }}" class="space-y-6 md:space-y-7"
                id="projectForm">
                @csrf
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
                <input type="hidden" name="owner" id="selected-owner-id" value="{{ old('owner') }}">
                <input type="hidden" name="leader" id="selected-leader-id" value="{{ old('leader') }}">
                <input type="hidden" name="members" id="selected-members-ids" value="{{ old('members') ? implode(',', old('members')) : '' }}">

                <!-- Nama Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="nm_pjt" data-translate-page="dosen_add_pjt">Nama Project</span> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_project" value="{{ old('nama_project') }}" required
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
                        placeholder="Deskripsikan project Anda...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tambah Tugas -->
                <div id="task-section" class="hidden">
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
                            <span data-translate="date_start" data-translate-page="dosen_add_pjt">Tanggal Mulai</span> <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
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
                        <input type="date" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}"
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
                    <input type="url" name="link_project" value="{{ old('link_project') }}"
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
                        <input type="url" name="link_github" maxlength="500" value="{{ old('link_github') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_github') border-red-500 @enderror"
                            placeholder="https://github.com/username/repo">
                    </div>
                    <div>
                        <label data-translate="link_vid" data-translate-page="dosen_add_pjt"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Link Video (YouTube, opsional)
                        </label>
                        <input type="url" name="link_video" maxlength="500" value="{{ old('link_video') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_video') border-red-500 @enderror"
                            placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex-1"></div>

                    <a href="{{ route('admin.projects.index') }}" data-translate="cancel"
                        data-translate-page="dosen_add_pjt"
                        class="px-6 py-3.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition text-center w-full sm:w-auto">
                        Batal
                    </a>

                    <button type="submit" data-translate="save_pjt" data-translate-page="dosen_add_pjt"
                        class="px-8 py-3.5 bg-indigo-600 text-white font-medium rounded-2xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-md w-full sm:w-auto">
                        Simpan Project
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
                            <div>
                                <input type="text" id="modal-search" placeholder="Cari nama..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:bg-gray-600 dark:text-white dark:border-gray-500 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
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

            if (role === 'owner' && selectedUsers.owner && selectedUsers.owner.id != userId) {
                alert('Owner sudah dipilih. Hapus owner yang ada terlebih dahulu.');
                selectElement.value = '';
                return;
            }

            if (role === 'leader' && selectedUsers.leader && selectedUsers.leader.id != userId) {
                alert('Leader sudah dipilih. Hapus leader yang ada terlebih dahulu.');
                selectElement.value = '';
                return;
            }

            if (selectedUsers.owner?.id == userId) selectedUsers.owner = null;
            if (selectedUsers.leader?.id == userId) selectedUsers.leader = null;
            selectedUsers.members = selectedUsers.members.filter(m => m.id != userId);

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
            // Refresh task UI
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
            document.getElementById('selected-members-ids').value = selectedUsers.members.map(m => m.id).join(',');
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
            const memberIds = document.getElementById('selected-members-ids')?.value.split(',').filter(id => id) || [];

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
            if (!selectedUsers.leader) {
                alert('Leader harus dipilih.');
                event.preventDefault();
                return;
            }
            updateFormInputs();
            cleanupInvalidTaskRows();
            updateTaskUserOptions();
        }

        document.addEventListener('DOMContentLoaded', function () {
            loadSelectedUsersFromForm();
            renderSelectedUsers();
            setupModalFilters();
            updateTaskSectionVisibility();
            updateSelectedUsersBadge();
            initializeTaskRows(@json(old('tasks', [])));
            document.getElementById('projectForm')?.addEventListener('submit', onSubmitProjectForm);
        });
    </script>

@endsection