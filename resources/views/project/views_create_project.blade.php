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
                    <div id="selected-users-container" class="space-y-3"></div>

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
            <div id="userModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 overflow-y-auto">

                <!-- Overlay: klik overlay TIDAK close modal, supaya user bisa pilih role dulu -->
                <div class="fixed inset-0 bg-black/40"></div>

                <!-- Modal Box -->
                <div class="relative w-full max-w-xl md:max-w-4xl lg:max-w-5xl xl:max-w-6xl h-[90vh] bg-white dark:bg-gray-900 rounded-3xl shadow-2xl flex flex-col overflow-hidden">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700 shrink-0 bg-white dark:bg-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100" data-translate="add_user" data-translate-page="dosen_add_pjt"></h2>
                        <button type="button" onclick="closeUserModal()"
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-300 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M6 18L18 6" />
                            </svg>
                        </button>
                    </div>

                    <!-- Main Content -->
                    <div class="flex-1 overflow-y-auto">
                        <div class="p-5 space-y-5">

                            <!-- Filter -->
                            <div class="grid gap-4 sm:grid-cols-2">
                                <input id="modal-search" type="text"
                                    placeholder="{{ autoTranslate('Cari nama atau email...') }}"
                                    data-translate-placeholder="search_name_placeholder"
                                    data-translate-page="dosen_add_pjt"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">

                                <select id="modal-angkatan"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">
                                    <option value="" data-translate="all_angkatan" data-translate-page="dosen_add_pjt"></option>
                                    @foreach($angkatanList as $angkatanItem)
                                        <option value="{{ $angkatanItem->id }}">{{ $angkatanItem->nama_angkatan }}</option>
                                    @endforeach
                                </select>

                                <select id="modal-jurusan"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">
                                    <option value="" data-translate="all_jurusan" data-translate-page="dosen_add_pjt"></option>
                                    @foreach($jurusanList as $jurusanItem)
                                        <option value="{{ $jurusanItem->id_jurusan }}">{{ autoTranslate($jurusanItem->nama_jurusan) }}</option>
                                    @endforeach
                                </select>

                                <select id="modal-keahlian"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-white">
                                    <option value="" data-translate="all_keahlian" data-translate-page="dosen_add_pjt"></option>
                                    @foreach($keahlianList as $keahlianItem)
                                        <option value="{{ $keahlianItem->id_keahlian }}">{{ autoTranslate($keahlianItem->nama_keahlian) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- User List Area -->
                            <div class="border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                                    <h3 data-translate="daftar_user" data-translate-page="project_create" class="font-medium text-gray-700 dark:text-white"></h3>
                                </div>
                                <div id="modal-user-list" class="max-h-[320px] overflow-y-auto p-4 space-y-3 bg-white dark:bg-gray-900"></div>
                            </div>

                            <!-- Pagination -->
                            <div id="modal-pagination" class="pt-2"></div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700 shrink-0 bg-white dark:bg-gray-900">
                        <button type="button" onclick="cancelUserModal()"
                            data-translate="cancel" data-translate-page="dosen_add_pjt"
                            class="px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-xl">
                            Batal
                        </button>
                        <button type="button" onclick="confirmUserSelection()"
                            data-translate="confirm" data-translate-page="dosen_add_pjt"
                            class="px-4 py-3 bg-indigo-600 text-white rounded-xl">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="text-black dark:text-white" data-translate="tanggal_mulai" data-translate-page="project_create"></span> <span class="text-red-500">*</span>
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
                    <span class="text-black dark:text-white" data-translate="tanggal_selesai" data-translate-page="project_create"></span>
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
                <span class="text-black dark:text-white" data-translate="link_project_opsional" data-translate-page="project_create"></span>
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
                    <span class="text-xl">+</span> <span data-translate="add_task_opt" data-translate-page="dosen_add_pjt">Tambah Tugas</span>
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
    let allUsers = []; // Akan diisi dari berbagai halaman
    let allUsersMap = new Map(); // Untuk menyimpan user dari semua halaman
    const currentUser = @json($currentUserData);
    const userSelectionStorageKey = 'project_selected_users';

    let currentModalFilters = { search: '', angkatan: '', jurusan: '', keahlian: '' };
    let currentPage = 1;
    let totalUsersLoaded = false;

    // State permanen (sudah di-confirm)
    let selectedUsers = { owner: null, leader: null, members: [] };

    // State sementara di dalam modal (sebelum confirm)
    let pendingUsers = { owner: null, leader: null, members: [] };

    let taskIndex = 0;

    // ─── Storage helpers ────────────────────────────────────────────────────────

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
            if (parsed.owner)  selectedUsers.owner   = parsed.owner;
            if (parsed.leader) selectedUsers.leader  = parsed.leader;
            if (Array.isArray(parsed.members)) selectedUsers.members = parsed.members;
            return true;
        } catch (e) {
            console.warn('Unable to restore selected users:', e);
            return false;
        }
    }

    // ─── Update allUsers dari berbagai halaman ─────────────────────────────────

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

    // ─── pendingUsers helpers ────────────────────────────────────────────────────

    function syncPendingFromSelected() {
        pendingUsers = {
            owner:   selectedUsers.owner   ? { ...selectedUsers.owner }   : null,
            leader:  selectedUsers.leader  ? { ...selectedUsers.leader }  : null,
            members: selectedUsers.members.map(m => ({ ...m }))
        };
    }

    function applyPendingToSelected() {
        selectedUsers.owner   = pendingUsers.owner   ? { ...pendingUsers.owner }   : null;
        selectedUsers.leader  = pendingUsers.leader  ? { ...pendingUsers.leader }  : null;
        selectedUsers.members = pendingUsers.members.map(m => ({ ...m }));
    }

    function discardPending() {
        pendingUsers = { owner: null, leader: null, members: [] };
    }

    // ─── Modal open/close ────────────────────────────────────────────────────────

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
        discardPending();
    }

    function cancelUserModal() {
        discardPending();
        document.getElementById('userModal').classList.add('hidden');
    }

    function confirmUserSelection() {
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

    // ─── Fetch users (AJAX) ──────────────────────────────────────────────────────

    function fetchUsers(page = 1) {
        currentPage = page;
        const params = new URLSearchParams({
            page: page,
            search:   currentModalFilters.search,
            angkatan: currentModalFilters.angkatan,
            jurusan:  currentModalFilters.jurusan,
            keahlian: currentModalFilters.keahlian
        });

        fetch(`{{ route('project.create') }}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            // Parse HTML untuk mendapatkan data user
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
            
            // Tambahkan ke allUsers
            addUsersToAllUsers(usersInPage);
            
            // Render ulang dengan user yang sudah lengkap
            renderUserListWithRoles(data.userListHtml);
            
            document.getElementById('modal-pagination').innerHTML = data.paginationHtml;
            
            // Refresh role selections berdasarkan pendingUsers
            refreshRoleSelections();
            
            if (typeof window.refreshTranslations === 'function') {
                window.refreshTranslations();
            }
        });
    }
    
    function renderUserListWithRoles(html) {
        const container = document.getElementById('modal-user-list');
        container.innerHTML = html;
        
        // Set nilai dropdown berdasarkan pendingUsers
        document.querySelectorAll('.user-role-select').forEach(select => {
            const userId = String(select.dataset.userId);
            const user = getUserById(userId);
            
            if (user) {
                // Ganti data jika perlu
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

    // ─── Role assignment ────────────────────────────────────────────────────────

    function updateUserRole(selectElement, userId, role) {
        const user = getUserById(userId);
        if (!user) {
            console.error('User not found:', userId);
            return;
        }

        const isOwner         = pendingUsers.owner  && String(pendingUsers.owner.id)  === String(userId);
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
            // Cek duplikasi
            if (!pendingUsers.members.some(m => String(m.id) === String(userId))) {
                pendingUsers.members.push(user);
            }
        }

        refreshRoleSelections();
    }

    function refreshRoleSelections() {
        const leaderId = pendingUsers.leader ? String(pendingUsers.leader.id) : null;
        const ownerId  = pendingUsers.owner  ? String(pendingUsers.owner.id)  : null;

        document.querySelectorAll('.user-role-select').forEach(select => {
            const userId   = String(select.dataset.userId);
            const isOwner  = ownerId  === userId;
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
                leaderOption.title    = leaderOption.disabled ? 'Leader sudah dipilih' : '';
            }

            if (memberOption) {
                memberOption.disabled = false;
            }

            select.disabled = false;
        });
    }

    // ─── Form inputs & rendering ─────────────────────────────────────────────────

    function updateSelectedUsersBadge() {
        const badge = document.getElementById('selected-users-badge');
        if (!badge) return;
        let count = 0;
        if (selectedUsers.owner)  count++;
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
        document.getElementById('selected-owner-id').value   = selectedUsers.owner?.id  || '';
        document.getElementById('selected-leader-id').value  = selectedUsers.leader?.id || '';
        document.getElementById('selected-members-ids').value = selectedUsers.members.map(m => m.id).join(',');

        const memberInputs = document.getElementById('selected-members-inputs');
        if (memberInputs) {
            memberInputs.innerHTML = selectedUsers.members
                .map(member => `<input type="hidden" name="members[]" value="${member.id}">`)
                .join('');
        }
    }

    function renderSelectedUsers() {
        const container    = document.getElementById('selected-users-container');
        const noUsersMsg   = document.getElementById('no-users-message');
        if (!container || !noUsersMsg) return;

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
            container.innerHTML = '';
            noUsersMsg.classList.remove('hidden');
            return;
        }

        noUsersMsg.classList.add('hidden');
        container.innerHTML = selected.map(user => {
            const styles = {
                'Owner':           'bg-blue-50 dark:bg-blue-950 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
                'Leader':          'bg-green-50 dark:bg-green-950 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
                'Owner & Leader':  'bg-teal-50 dark:bg-teal-950 border-teal-200 dark:border-teal-800 text-teal-800 dark:text-teal-200',
                'Member':          'bg-purple-50 dark:bg-purple-950 border-purple-200 dark:border-purple-800 text-purple-800 dark:text-purple-200'
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

    function removeUser(userId) {
        deleteTasksForUser(userId);
        if (selectedUsers.leader?.id == userId)  selectedUsers.leader  = null;
        selectedUsers.members = selectedUsers.members.filter(m => String(m.id) !== String(userId));
        updateFormInputs();
        renderSelectedUsers();
        updateTaskUserOptions();
        updateSelectedUsersBadge();
        saveSelectedUsersToStorage();
    }

    function editUser(userId) {
        openUserModal();
        setTimeout(() => {
            const userElement = document.querySelector(`.user-role-select[data-user-id="${userId}"]`)?.closest('[data-user-id]');
            if (userElement) {
                userElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                userElement.style.backgroundColor = '#fef3c7';
                setTimeout(() => userElement.style.backgroundColor = '', 2000);
            }
        }, 500);
    }

    // ─── Task helpers ────────────────────────────────────────────────────────────

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
        const index    = taskIndex++;
        const userId   = taskData?.user_id ?? '';
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
                    <input type="text" name="tasks[${index}][name_task]" value="${taskName}"
                        class="task-name-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                        placeholder="{{ autoTranslate('Deskripsikan tugas...') }}">
                </div>
                <button type="button" onclick="removeTaskRow(this)"
                    class="self-start mt-6 px-4 py-3 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-300 rounded-xl">Hapus</button>
            </div>
        `;
        container.appendChild(taskItem);
        taskItem.querySelector('.task-user-select')?.addEventListener('change', updateTaskUserOptions);
    }

    function removeTaskRow(button) {
        button.closest('.task-item')?.remove();
        if (!document.querySelectorAll('.task-item').length) addTaskRow();
    }

    function cleanupInvalidTaskRows() {
        const allowedIds = getAllowedTaskUsers().map(u => String(u.id));
        document.querySelectorAll('.task-item').forEach(taskItem => {
            const select = taskItem.querySelector('.task-user-select');
            if (!select || !select.value || !allowedIds.includes(select.value)) taskItem.remove();
        });
        if (!document.querySelectorAll('.task-item').length) addTaskRow();
    }

    function updateTaskUserOptions() {
        document.querySelectorAll('.task-user-select').forEach(select => {
            const currentValue = select.value;
            select.innerHTML   = renderTaskUserOptions(currentValue);
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
            existingTasks.forEach(task => { if (task.user_id || task.name_task) addTaskRow(task); });
        } else {
            addTaskRow();
        }
        updateTaskUserOptions();
    }

    // ─── Modal filters ───────────────────────────────────────────────────────────

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

    // ─── Pagination click ────────────────────────────────────────────────────────

    document.addEventListener('click', function (e) {
        const link = e.target.closest('#modal-pagination a');
        if (link) {
            e.preventDefault();
            const url  = link.getAttribute('href');
            if (!url) return;
            const page = new URL(url).searchParams.get('page') || 1;
            fetchUsers(page);
        }
    });

    // ─── Collaborative toggle ────────────────────────────────────────────────────

    function toggleUserSelectionSection() {
        const toggle             = document.getElementById('project-collaborative-toggle');
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
            selectedUsers.owner   = currentUser;
            selectedUsers.leader  = currentUser;
            selectedUsers.members = [];
            updateFormInputs();
            renderSelectedUsers();
            updateTaskUserOptions();
            updateSelectedUsersBadge();
            saveSelectedUsersToStorage();
        }
    }

    // ─── Date validation ─────────────────────────────────────────────────────────

    function setupDateValidation() {
        const tanggalMulaiInput  = document.getElementById('tanggal_mulai');
        const tanggalAkhirInput  = document.getElementById('tanggal_akhir');
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

    // ─── Form submit ─────────────────────────────────────────────────────────────

    function onSubmitProjectForm() {
        if (currentUser) selectedUsers.owner = currentUser;
        if (selectedUsers.owner && !selectedUsers.leader && selectedUsers.members.length > 0) {
            selectedUsers.leader = selectedUsers.owner;
        }
        updateFormInputs();
        cleanupInvalidTaskRows();
        updateTaskUserOptions();
    }

    // ─── Init ────────────────────────────────────────────────────────────────────

    function loadSelectedUsersFromForm() {
        if (restoreSelectedUsersFromStorage()) {
            updateFormInputs();
            return;
        }
        const leaderId  = document.getElementById('selected-leader-id')?.value;
        const memberIds = document.getElementById('selected-members-ids')?.value.split(',').filter(id => id) || [];
        if (leaderId) selectedUsers.leader = getUserById(leaderId);
        selectedUsers.members = memberIds.map(id => getUserById(id)).filter(Boolean);
    }

    // Tambahkan current user ke allUsers
    if (currentUser) {
        addUsersToAllUsers([currentUser]);
    }

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
        const toggleLabel         = document.getElementById('toggle-label');
        if (collaborativeToggle && toggleLabel) {
            toggleLabel.textContent = collaborativeToggle.checked ? 'Aktif' : 'Nonaktif';
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