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
            <form method="POST" action="{{ route('dosen.projects.store') }}" class="space-y-6 md:space-y-7"
                id="projectForm">
                @csrf
                <!-- User Selection Section -->
                <div class="bg-white dark:bg-gray-800 p-5 md:p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200" data-translate="user_selection" data-translate-page="dosen_add_pjt"></h3>
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

                    <a href="{{ route('dosen.projects.index') }}" data-translate="cancel"
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

                                <!-- SVG icon -->
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
                    <div class="mt-4" data-pagination-group="admin_project_user_selection">
                        <div class="max-h-96 overflow-y-auto">
                            <div id="modal-user-list" class="space-y-2">
                                <!-- Users will be loaded here -->
                            </div>
                        </div>
                        <div class="mt-4" id="modal-pagination-container">
                            {{ $users->render('vendor.pagination.custom_ajax', ['groupName' => 'admin_project_user_selection']) }}
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

    <script>
        const allUsers = @json($allUsers);
        const userSelectionStorageKey = 'admin_project_selected_users';
        let selectedUsers = { owner: null, leader: null, members: [] };
        let taskIndex = 0;
        let filterTimer = null;

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
            document.getElementById('userModal').classList.remove('hidden');
            sessionStorage.setItem('admin_project_modal_open', '1');
            // Reset pagination ke halaman 1
            sessionStorage.removeItem('admin_project_user_page');
            // Reset filter ke nilai default
            document.getElementById('modal-search').value = '';
            document.getElementById('modal-angkatan').value = '';
            document.getElementById('modal-jurusan').value = '';
            document.getElementById('modal-keahlian').value = '';
            // Refetch data dengan halaman 1
            applyUserFilters();
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
            sessionStorage.removeItem('admin_project_modal_open');
            sessionStorage.removeItem('admin_project_user_page');
        }
        
        function applyUserFilters() {
            // Reset ke halaman 1 saat apply filter
            sessionStorage.removeItem('admin_project_user_page');
            const search = document.getElementById('modal-search')?.value.trim();
            const angkatan = document.getElementById('modal-angkatan')?.value;
            const jurusan = document.getElementById('modal-jurusan')?.value;
            const keahlian = document.getElementById('modal-keahlian')?.value;

            const params = new URLSearchParams();
            if (search) params.set('search', search);
            if (angkatan) params.set('angkatan', angkatan);
            if (jurusan) params.set('jurusan', jurusan);
            if (keahlian) params.set('keahlian', keahlian);

            const url = '{{ route("dosen.projects.create") }}?' + params.toString();

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('modal-user-list').innerHTML = data.userListHtml;
                document.getElementById('modal-pagination-container').innerHTML = data.paginationHtml;
                // Re-set selected roles after updating HTML
                setSelectedRolesInModal();
                // Refresh translations for newly rendered elements
                if (typeof window.refreshTranslations === 'function') {
                    window.refreshTranslations();
                }
                attachPaginationListeners();
            })
            .catch(error => {
                console.error('Error loading users:', error);
            });
        }

        function attachPaginationListeners() {
            document.querySelectorAll('[data-pagination-group="admin_project_user_selection"] .pagination-link').forEach(link => {
                // Remove existing listeners to avoid duplicates
                const newLink = link.cloneNode(true);
                link.parentNode.replaceChild(newLink, link);
                
                newLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.href;
                    fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('modal-user-list').innerHTML = data.userListHtml;
                        document.getElementById('modal-pagination-container').innerHTML = data.paginationHtml;
                        setSelectedRolesInModal();
                        // Refresh translations for newly rendered elements
                        if (typeof window.refreshTranslations === 'function') {
                            window.refreshTranslations();
                        }
                        attachPaginationListeners();
                    })
                    .catch(error => {
                        console.error('Error loading page:', error);
                    });
                });
            });
        }

        // Fungsi utama untuk mengatur disabled options pada select roles
        function setSelectedRolesInModal() {
            // Dapatkan semua select element di dalam modal
            const selects = document.querySelectorAll('#modal-user-list .user-role-select');
            
            selects.forEach(select => {
                // Ekstrak userId dari onchange attribute atau data attribute
                let userId = null;
                const onchangeAttr = select.getAttribute('onchange');
                if (onchangeAttr) {
                    const match = onchangeAttr.match(/updateUserRole\(this, (\d+),/);
                    if (match) userId = parseInt(match[1]);
                }
                
                if (!userId && select.hasAttribute('data-user-id')) {
                    userId = parseInt(select.getAttribute('data-user-id'));
                }
                
                if (!userId) return;
                
                // Cek status user saat ini
                const isOwner = selectedUsers.owner?.id === userId;
                const isLeader = selectedUsers.leader?.id === userId;
                const isMember = selectedUsers.members.some(m => m.id === userId);
                
                // Dapatkan option elements
                const ownerOption = select.querySelector('option[value="owner"]');
                const leaderOption = select.querySelector('option[value="leader"]');
                const memberOption = select.querySelector('option[value="member"]');
                
                // Set disabled attributes
                if (ownerOption) {
                    // Owner option disabled jika sudah ada owner lain
                    ownerOption.disabled = selectedUsers.owner !== null && !isOwner;
                }
                
                if (leaderOption) {
                    // Leader option disabled jika sudah ada leader lain
                    leaderOption.disabled = selectedUsers.leader !== null && !isLeader;
                }
                
                if (memberOption) {
                    // Member option disabled jika user adalah owner atau leader
                    memberOption.disabled = isOwner || isLeader;
                }
                
                // Set value sesuai role yang sudah dipilih
                if (isOwner) {
                    select.value = 'owner';
                } else if (isLeader) {
                    select.value = 'leader';
                } else if (isMember) {
                    select.value = 'member';
                } else {
                    select.value = '';
                }
            });
        }

        function updateUserRole(selectElement, userId, role) {
            // Get user data from DOM
            const userDiv = selectElement.closest('.flex.items-center.justify-between');
            if (!userDiv) return;
            const img = userDiv.querySelector('img');
            const photo_profile = img ? img.src.replace('/storage/', '') : null;
            const nameDiv = userDiv.querySelector('.font-medium');
            const nama_mahasiswa = nameDiv ? nameDiv.textContent : 'Unknown';
            const emailDiv = userDiv.querySelector('.text-sm');
            const email = emailDiv ? emailDiv.textContent : '';
            const user = { id: userId, nama_mahasiswa, email, photo_profile };

            // Validasi Owner tidak boleh duplikat
            if (role === 'owner' && selectedUsers.owner && selectedUsers.owner.id !== userId) {
                alert('Owner sudah dipilih. Hapus owner yang ada terlebih dahulu jika ingin mengganti.');
                selectElement.value = '';
                // Refresh disabled states
                setSelectedRolesInModal();
                return;
            }

            // Validasi Leader tidak boleh duplikat
            if (role === 'leader' && selectedUsers.leader && selectedUsers.leader.id !== userId) {
                if (!confirm('Leader sudah ada. Ganti leader?')) {
                    selectElement.value = '';
                    setSelectedRolesInModal();
                    return;
                }
                // Hapus leader lama
                selectedUsers.leader = null;
            }

            // Reset user ini dari semua role
            if (selectedUsers.owner?.id === userId) selectedUsers.owner = null;
            if (selectedUsers.leader?.id === userId) selectedUsers.leader = null;
            selectedUsers.members = selectedUsers.members.filter(m => m.id !== userId);

            // Assign role baru
            if (role === 'owner') {
                selectedUsers.owner = user;
            } else if (role === 'leader') {
                selectedUsers.leader = user;
            } else if (role === 'member') {
                selectedUsers.members.push(user);
            }

            // Update form inputs
            updateFormInputs();
            // Render selected users di main form
            renderSelectedUsers();
            // Update task section visibility
            updateTaskSectionVisibility();
            updateTaskUserOptions();
            updateSelectedUsersBadge();
            saveSelectedUsersToStorage();
            
            // Refresh disabled states di modal
            setSelectedRolesInModal();
        }
        
        function confirmUserSelection() {
            updateFormInputs();
            renderSelectedUsers();
            // Refresh task UI
            updateTaskSectionVisibility();
            updateTaskUserOptions();
            updateSelectedUsersBadge();
            saveSelectedUsersToStorage();
            sessionStorage.removeItem('admin_project_modal_open');
            closeUserModal();
        }

        function updateFormInputs() {
            document.getElementById('selected-owner-id').value = selectedUsers.owner?.id || '';
            const effectiveLeaderId = selectedUsers.leader?.id || (selectedUsers.owner && selectedUsers.members.length > 0 ? selectedUsers.owner.id : null);
            document.getElementById('selected-leader-id').value = effectiveLeaderId || '';
            document.getElementById('selected-members-ids').value = selectedUsers.members.map(m => m.id).join(',');
        }

       function renderSelectedUsers() {
    const container = document.getElementById('selected-users-container');
    const noUsersMsg = document.getElementById('no-users-message');
    if (!container || !noUsersMsg) return;

    const selected = [];
    if (selectedUsers.owner) {
        if (!selectedUsers.leader && selectedUsers.members.length > 0) {
            selected.push({ ...selectedUsers.owner, role: 'owner_leader_role' }); // Use the translation key
        } else if (selectedUsers.leader && selectedUsers.owner.id === selectedUsers.leader.id) {
            selected.push({ ...selectedUsers.owner, role: 'owner_leader_role' });
        } else {
            selected.push({ ...selectedUsers.owner, role: 'owner_role' });
        }
    }
    if (selectedUsers.leader && (!selectedUsers.owner || selectedUsers.owner.id !== selectedUsers.leader.id)) {
        selected.push({ ...selectedUsers.leader, role: 'leader_role' });
    }
    selectedUsers.members.forEach(member => selected.push({ ...member, role: 'member_role' }));

    if (!selected.length) {
        container.innerHTML = '';
        noUsersMsg.classList.remove('hidden');
        return;
    }

    noUsersMsg.classList.add('hidden');
    container.innerHTML = selected.map(user => {
        const roleKey = user.role;
        let roleDisplay = '';
        
        // Get the translated role text
        if (typeof window.translations !== 'undefined' && window.currentLang) {
            const pageData = window.translations[window.currentLang]?.dosen_add_pjt || 
                           window.translations.id?.dosen_add_pjt;
            if (pageData && pageData[roleKey]) {
                roleDisplay = pageData[roleKey];
            } else {
                // Fallback mapping
                const fallback = {
                    'owner_role': 'Owner',
                    'leader_role': 'Leader', 
                    'member_role': 'Member',
                    'owner_leader_role': 'Owner & Leader'
                };
                roleDisplay = fallback[roleKey] || user.role;
            }
        } else {
            const fallback = {
                'owner_role': 'Owner',
                'leader_role': 'Leader',
                'member_role': 'Member', 
                'owner_leader_role': 'Owner & Leader'
            };
            roleDisplay = fallback[roleKey] || user.role;
        }

        return `
            <div class="flex items-center justify-between p-4 border rounded-2xl bg-blue-50 dark:bg-blue-950 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200">
                <div class="flex items-center gap-3">
                    ${user.photo_profile ?
                        `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-800">` :
                        `<div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center ring-2 ring-white dark:ring-gray-800">
                            <span class="font-semibold text-current">${user.nama_mahasiswa.charAt(0).toUpperCase()}</span>
                        </div>`
                    }
                    <div>
                        <div class="font-medium">${roleDisplay}: ${user.nama_mahasiswa}</div>
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
            if (selectedUsers.owner?.id === userId) selectedUsers.owner = null;
            if (selectedUsers.leader?.id === userId) selectedUsers.leader = null;
            selectedUsers.members = selectedUsers.members.filter(m => m.id !== userId);
            updateFormInputs();
            renderSelectedUsers();
            // Refresh task UI
            updateTaskSectionVisibility();
            updateTaskUserOptions();
            updateSelectedUsersBadge();
            saveSelectedUsersToStorage();
        }

        function loadSelectedUsersFromForm() {
            if (restoreSelectedUsersFromStorage()) {
                updateFormInputs();
                return;
            }

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
    if (select) select.addEventListener('change', updateTaskUserOptions);
    
    // CRITICAL: Refresh translations for the newly added task row
    if (typeof window.refreshTranslations === 'function') {
        window.refreshTranslations();
    }
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
            // Refresh translations for updated task user options
            if (typeof window.refreshTranslations === 'function') {
                window.refreshTranslations();
            }
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
            const searchInput = document.getElementById('modal-search');
            const angkatanSelect = document.getElementById('modal-angkatan');
            const jurusanSelect = document.getElementById('modal-jurusan');
            const keahlianSelect = document.getElementById('modal-keahlian');
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(filterTimer);
                    filterTimer = setTimeout(applyUserFilters, 500);
                });
            }
            
            if (angkatanSelect) angkatanSelect.addEventListener('change', applyUserFilters);
            if (jurusanSelect) jurusanSelect.addEventListener('change', applyUserFilters);
            if (keahlianSelect) keahlianSelect.addEventListener('change', applyUserFilters);
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

        document.addEventListener('DOMContentLoaded', function () {
            const hasOldData = '{{ old('members') || old('owner') || old('leader') ? 'true' : 'false' }}' === 'true';
            if (!hasOldData) {
                localStorage.removeItem(userSelectionStorageKey);
            }
            
            loadSelectedUsersFromForm();
            renderSelectedUsers();
            setupModalFilters();
            updateTaskSectionVisibility();
            updateSelectedUsersBadge();
            initializeTaskRows(@json(old('tasks', [])));
            document.getElementById('projectForm')?.addEventListener('submit', onSubmitProjectForm);

            if (sessionStorage.getItem('admin_project_modal_open') === '1') {
                openUserModal();
            }
            
            // Setup date validation
            const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]');
            const tanggalAkhir = document.querySelector('input[name="tanggal_akhir"]');
            
            if (tanggalMulai && tanggalAkhir) {
                tanggalMulai.addEventListener('change', function() {
                    if (this.value) {
                        // Set min date for tanggal_akhir to the day after tanggal_mulai
                        const startDate = new Date(this.value);
                        const minEndDate = new Date(startDate);
                        minEndDate.setDate(startDate.getDate() + 1);
                        const minEndDateStr = minEndDate.toISOString().split('T')[0];
                        tanggalAkhir.min = minEndDateStr;
                        
                        // If current tanggal_akhir is before the new min, clear it
                        if (tanggalAkhir.value && tanggalAkhir.value < minEndDateStr) {
                            tanggalAkhir.value = '';
                        }
                    } else {
                        // If tanggal_mulai is cleared, remove min constraint
                        tanggalAkhir.min = '';
                    }
                });
            }
        });
    </script>

@endsection