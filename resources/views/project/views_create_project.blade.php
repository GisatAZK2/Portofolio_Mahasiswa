@extends('Layout.Layout')
@section('title', 'Tambah Project Baru')
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
                    placeholder="Contoh: Website Portfolio Pribadi">
                @error('nama_project')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                    placeholder="Deskripsikan project Anda...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Project Collaborative Toggle -->
            <div class="flex items-center justify-between gap-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Projek Kolaboratif</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Aktifkan untuk menambahkan pemimpin dan anggota tim.</p>
                </div>
                <label class="inline-flex items-center cursor-pointer">
                    <span class="relative">
                        <input id="project-collaborative-toggle" type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600"></div>
                    </span>
                    <span id="toggle-label" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-200">Nonaktif</span>
                </label>
            </div>

            <!-- Collaborative Section (Hidden by default) -->
            <div id="collaborative-section" style="display: none;">
                <!-- Add Pemimpin Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="tambah_pemimpin" data-translate-page="project_create"></span>
                    </label>

                    <!-- Selected Leader Display -->
                    <div id="selected-leader-display" class="mb-3 hidden">
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2" id="selected-leader-content"></div>
                                <button type="button" onclick="clearSelectedLeader()"
                                    class="text-blue-600 dark:text-blue-400 hover:text-red-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <!-- Search Input for Leader -->
                        <div class="relative">
                            <input type="text" id="leader-search-input" placeholder="Cari pemimpin..."
                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                                focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                                text-gray-700 dark:text-gray-300
                                                placeholder-gray-500 dark:placeholder-gray-400
                                                shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm outline-none transition">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Filter Dropdowns for Leader -->
                        <div class="grid grid-cols-2 gap-2">
                            <select id="leader-filter-angkatan" class="px-3 py-2 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                                focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                                text-gray-700 dark:text-gray-300
                                                shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm">
                                <option value="">Semua Angkatan</option>
                                @foreach ($angkatanList as $ank)
                                    <option value="{{ $ank->id }}" {{ $angkatan == $ank->id ? 'selected' : '' }}>
                                        {{ $ank->tahun_masuk }}
                                    </option>
                                @endforeach
                            </select>

                            <select id="leader-filter-jurusan" class="px-3 py-2 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                                focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                                text-gray-700 dark:text-gray-300
                                                shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm">
                                <option value="">Semua Jurusan</option>
                                @foreach ($jurusanList as $jur)
                                    <option value="{{ $jur->id }}" {{ $jurusan == $jur->id ? 'selected' : '' }}>
                                        {{ $jur->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Leader Select Dropdown -->
                        <select name="leader" id="leader-select" class="w-full px-4 py-2.5 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                                focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                                text-gray-700 dark:text-gray-300
                                                shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm">
                            <option value="">-- Pilih Pemimpin Project --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" data-name="{{ $user->nama_mahasiswa }}"
                                    data-angkatan="{{ $user->id_angkatan ?? '' }}" data-jurusan="{{ $user->id_jurusan ?? '' }}">
                                    {{ $user->nama_mahasiswa }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Add Members -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="tambah_rekan" data-translate-page="project_create"></span>
                    </label>

                    <div id="members-container" class="space-y-2">
                    </div>

                    <button type="button" onclick="addMemberSelect()"
                        class="mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:cursor-pointer hover:underline flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span data-translate="tambah_rekan_btn" data-translate-page="project_create"></span>
                    </button>
                </div>
            </div>

            <!-- Hidden inputs for leader -->
            <input type="hidden" name="leader_name" id="leader-name">
            <input type="hidden" name="leader_photo" id="leader-photo">
            <input type="hidden" name="leader_email" id="leader-email">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="text-black dark:text-white" data-translate="tanggal_mulai"
                        data-translate-page="project_create"></span> <span class="text-red-500">*</span>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                        class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                            focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                            text-gray-700 dark:text-gray-300
                                            placeholder-gray-500 dark:placeholder-gray-400
                                            shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm @error('tanggal_mulai') border-red-500 @enderror">
                    @error('tanggal_mulai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <span class="text-black dark:text-white" data-translate="tanggal_selesai"
                        data-translate-page="project_create"></span>
                    <input type="date" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}"
                        class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                            focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                            text-gray-700 dark:text-gray-300
                                            placeholder-gray-500 dark:placeholder-gray-400
                                            shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm @error('tanggal_akhir') border-red-500 @enderror">
                    @error('tanggal_akhir')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Add Tasks -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    Tambahkan Tugas
                </label>

                <div id="tasks-container" class="space-y-4"></div>

                <button type="button" onclick="addTaskRow()"
                    class="mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:cursor-pointer hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Tugas
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

    <script>
        const usersData = @json($users);
        const currentUserData = @json(['id' => Auth::id(), 'nama_mahasiswa' => Auth::user()->nama_mahasiswa]);
        let taskIndex = 0;

        // Update leader display when selected
        function updateLeaderDisplay(userId) {
            if (!userId) {
                document.getElementById('selected-leader-display').classList.add('hidden');
                document.getElementById('leader-name').value = '';
                document.getElementById('leader-photo').value = '';
                document.getElementById('leader-email').value = '';
                return;
            }

            const selectedUser = usersData.find(u => u.id == userId);

            if (selectedUser) {
                document.getElementById('leader-name').value = selectedUser.nama_mahasiswa;
                document.getElementById('leader-photo').value = selectedUser.photo_profile || '';
                document.getElementById('leader-email').value = selectedUser.email || '';

                const display = document.getElementById('selected-leader-display');
                const content = document.getElementById('selected-leader-content');

                let photoHtml = selectedUser.photo_profile && selectedUser.photo_profile.includes('storage')
                    ? `<img src="{{ asset('') }}${selectedUser.photo_profile}" class="w-10 h-10 rounded-lg object-cover border border-white dark:border-gray-700 shadow" alt="${selectedUser.nama_mahasiswa}">`
                    : `<div class="w-10 h-10 rounded-lg bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                                ${selectedUser.nama_mahasiswa.charAt(0).toUpperCase()}
                                           </div>`;

                content.innerHTML = `
                                        ${photoHtml}
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">${selectedUser.nama_mahasiswa}</div>
                                        </div>
                                    `;

                display.classList.remove('hidden');
            }

            updateDisabledOptions();
            saveToLocalStorage();
        }

        function isCollaborative() {
            return document.getElementById('project-collaborative-toggle').checked;
        }

        function getAvailableTaskUsers() {
            if (!isCollaborative()) {
                return [currentUserData];
            }

            const leaderId = document.getElementById('leader-select').value;
            const memberIds = Array.from(document.querySelectorAll('.member-select'))
                .map(select => select.value)
                .filter(value => value);

            const ids = [String(currentUserData.id)];
            if (leaderId && leaderId !== String(currentUserData.id)) {
                ids.push(leaderId);
            }

            memberIds.forEach(id => {
                if (!ids.includes(id)) {
                    ids.push(id);
                }
            });

            return ids.map(id => {
                const user = usersData.find(u => String(u.id) === String(id));
                if (user) {
                    return user;
                }
                if (String(currentUserData.id) === String(id)) {
                    return currentUserData;
                }
                return null;
            }).filter(Boolean);
        }

        function renderTaskUserOptions(selectedId = null) {
            const availableUsers = getAvailableTaskUsers();
            let options = '<option value="">-- Pilih Penanggung Jawab --</option>';
            availableUsers.forEach(user => {
                const selected = selectedId && String(user.id) === String(selectedId)
                    ? 'selected'
                    : (!selectedId && String(user.id) === String(currentUserData.id) ? 'selected' : '');
                options += `<option value="${user.id}" ${selected}>${user.nama_mahasiswa}</option>`;
            });
            return options;
        }

        function updateTaskUserOptions() {
            const availableUsers = getAvailableTaskUsers();
            document.querySelectorAll('select[name="tasks[][user_id]"]').forEach(select => {
                const currentValue = select.value;
                const stillAvailable = availableUsers.some(user => String(user.id) === String(currentValue));
                select.innerHTML = renderTaskUserOptions(stillAvailable ? currentValue : null);
            });
        }

        function renderCollaborativeToggle() {
            const on = isCollaborative();
            const toggle = document.getElementById('project-collaborative-toggle');
            const label = document.getElementById('toggle-label');
            
            if (on) {
                label.textContent = 'Aktif';
                document.getElementById('collaborative-section').style.display = 'block';
            } else {
                label.textContent = 'Nonaktif';
                document.getElementById('collaborative-section').style.display = 'none';
                // Clear leader and members when collaborative is off
                clearSelectedLeader();
                document.getElementById('members-container').innerHTML = '';
            }
        }

        // Clear selected leader
        function clearSelectedLeader() {
            document.getElementById('leader-select').value = '';
            updateLeaderDisplay('');
            updateDisabledOptions();
            saveToLocalStorage();
        }

        // Filter leader options based on search and filters
        function filterLeaderDropdown(searchValue) {
            const leaderSelect = document.getElementById('leader-select');
            const filterAngkatan = document.getElementById('leader-filter-angkatan').value;
            const filterJurusan = document.getElementById('leader-filter-jurusan').value;
            const options = leaderSelect.querySelectorAll('option');
            const searchLower = searchValue.toLowerCase();

            options.forEach(option => {
                if (option.value === '') {
                    option.style.display = '';
                } else {
                    const name = option.getAttribute('data-name').toLowerCase();
                    const angkatan = option.getAttribute('data-angkatan');
                    const jurusan = option.getAttribute('data-jurusan');

                    let show = name.includes(searchLower);
                    if (filterAngkatan && show) {
                        show = angkatan === filterAngkatan;
                    }
                    if (filterJurusan && show) {
                        show = jurusan === filterJurusan;
                    }

                    option.style.display = show ? '' : 'none';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                showSuccessAlert('{{ session('success') }}');
            @endif

            @if ($errors->any())
                showErrorAlert('{{ $errors->first() }}');
            @endif

            // Set collaborative toggle to off by default
            const collaborativeToggle = document.getElementById('project-collaborative-toggle');
            collaborativeToggle.checked = false;
            renderCollaborativeToggle();
            
            loadSavedData();
            initializeMemberSelects();
            updateTaskUserOptions();

            const oldTasks = @json(old('tasks', []));
            if (Array.isArray(oldTasks) && oldTasks.length > 0) {
                oldTasks.forEach(task => {
                    if (task.user_id && task.name_task) {
                        addTaskRow(task);
                    }
                });
            }
            
            if (document.querySelectorAll('.task-item').length === 0) {
                addTaskRow();
            }

            // Leader select change event
            const leaderSelect = document.getElementById('leader-select');
            leaderSelect.addEventListener('change', function () {
                updateLeaderDisplay(this.value);
            });

            // Leader search input
            const leaderSearchInput = document.getElementById('leader-search-input');
            leaderSearchInput.addEventListener('input', function () {
                filterLeaderDropdown(this.value);
            });

            // Leader filter dropdowns
            const leaderFilterAngkatan = document.getElementById('leader-filter-angkatan');
            const leaderFilterJurusan = document.getElementById('leader-filter-jurusan');

            leaderFilterAngkatan.addEventListener('change', function () {
                filterLeaderDropdown(leaderSearchInput.value);
            });

            leaderFilterJurusan.addEventListener('change', function () {
                filterLeaderDropdown(leaderSearchInput.value);
            });

            collaborativeToggle.addEventListener('change', function () {
                updateTaskUserOptions();
                renderCollaborativeToggle();
                
                // Re-render semua task row agar user_id otomatis terisi jika non-kolaboratif
                setTimeout(() => {
                    const taskItems = document.querySelectorAll('.task-item');
                    if (!isCollaborative()) {
                        taskItems.forEach(item => {
                            const select = item.querySelector('select[name="tasks[][user_id]"]');
                            if (select) {
                                select.value = currentUserData.id;
                            }
                        });
                    } else {
                        // Re-render all task rows to update available users
                        const tasks = [];
                        taskItems.forEach(item => {
                            const select = item.querySelector('select[name="tasks[][user_id]"]');
                            const input = item.querySelector('input[name="tasks[][name_task]"]');
                            if (select && input) {
                                tasks.push({
                                    user_id: select.value,
                                    name_task: input.value
                                });
                            }
                        });
                        
                        // Clear container and re-add tasks
                        const container = document.getElementById('tasks-container');
                        container.innerHTML = '';
                        if (tasks.length === 0) {
                            addTaskRow();
                        } else {
                            tasks.forEach(task => addTaskRow(task));
                        }
                    }
                }, 100);
            });

            document.getElementById('projectForm').addEventListener('submit', function () {
                removeBlankTaskRows();
                clearLocalStorage();
            });

            // Load saved leader data
            const oldLeaderId = document.getElementById('leader-select').value;
            if (oldLeaderId) {
                updateLeaderDisplay(oldLeaderId);
            }
        });

        function saveToLocalStorage() {
            if (!isCollaborative()) return;
            
            const leaderId = document.getElementById("leader-select").value;

            // Get all member selects
            const memberSelects = document.querySelectorAll('.member-select');
            const memberIds = [];

            memberSelects.forEach(select => {
                if (select.value) {
                    memberIds.push(select.value);
                }
            });

            const projectData = {
                leader: leaderId,
                members: memberIds
            };

            localStorage.setItem('projectTeamData', JSON.stringify(projectData));
        }

        function loadSavedData() {
            const savedData = localStorage.getItem('projectTeamData');

            if (savedData && isCollaborative()) {
                try {
                    const data = JSON.parse(savedData);

                    // Set leader
                    if (data.leader) {
                        document.getElementById("leader-select").value = data.leader;
                        updateLeaderDisplay(data.leader);
                    }

                    const container = document.getElementById('members-container');
                    container.innerHTML = '';

                    if (data.members && data.members.length > 0) {
                        data.members.forEach(memberId => {
                            if (memberId) {
                                addMemberSelect(memberId);
                            }
                        });
                    } else {
                        addMemberSelect();
                    }

                    setTimeout(() => {
                        updateDisabledOptions();
                    }, 100);

                } catch (e) {
                    console.error('Error parsing saved data:', e);
                    addMemberSelect();
                }
            } else {
                addMemberSelect();
            }
        }

        function clearLocalStorage() {
            localStorage.removeItem('projectTeamData');
        }

        function updateDisabledOptions() {
            if (!isCollaborative()) return;
            
            const leaderId = document.getElementById("leader-select").value;

            // Get all selected member IDs
            const selectedMemberIds = [];
            document.querySelectorAll(".member-select").forEach(select => {
                if (select.value) {
                    selectedMemberIds.push(select.value);
                }
            });

            // Update all member selects
            document.querySelectorAll(".member-select").forEach(select => {
                select.querySelectorAll("option").forEach(option => {
                    option.disabled = false;
                });

                if (leaderId) {
                    let leaderOption = select.querySelector(`option[value="${leaderId}"]`);
                    if (leaderOption) {
                        leaderOption.disabled = true;
                    }
                }

                selectedMemberIds.forEach(selectedId => {
                    if (selectedId && select.value !== selectedId) {
                        let selectedOption = select.querySelector(`option[value="${selectedId}"]`);
                        if (selectedOption) {
                            selectedOption.disabled = true;
                        }
                    }
                });
            });
            updateTaskUserOptions();
        }

        // Function to initialize member selects
        function initializeMemberSelects() {
            document.querySelectorAll(".member-select").forEach(select => {
                select.addEventListener("change", function () {
                    updateDisabledOptions();
                    saveToLocalStorage();
                });
            });
        }

        function addMemberSelect(savedValue = null) {
            if (!isCollaborative()) return;
            
            const container = document.getElementById('members-container');

            const memberDiv = document.createElement('div');
            memberDiv.classList.add('member-item', 'mb-4');

            let optionsHtml = '<option value="">-- Pilih Mahasiswa --</option>';
            usersData.forEach(user => {
                const selected = savedValue && savedValue == user.id ? 'selected' : '';
                optionsHtml += `<option value="${user.id}" data-name="${user.nama_mahasiswa}" data-angkatan="${user.id_angkatan ?? ''}" data-jurusan="${user.id_jurusan ?? ''}" ${selected}>${user.nama_mahasiswa}</option>`;
            });

            memberDiv.innerHTML = `
                <div class="space-y-2">
                    <div class="relative">
                        <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400 pointer-events-none member-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" placeholder="Cari mahasiswa..."
                            class="member-search w-full pl-10 pr-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                            focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                            text-gray-700 dark:text-gray-300
                            placeholder-gray-500 dark:placeholder-gray-400
                            shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <select class="member-filter-angkatan px-3 py-2 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                            focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                            text-gray-700 dark:text-gray-300
                            shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm">
                            <option value="">Semua Angkatan</option>
                            @foreach ($angkatanList as $ank)
                                <option value="{{ $ank->id }}">{{ $ank->tahun_masuk }}</option>
                            @endforeach
                        </select>
                        <select class="member-filter-jurusan px-3 py-2 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                            focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                            text-gray-700 dark:text-gray-300
                            shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm">
                            <option value="">Semua Jurusan</option>
                            @foreach ($jurusanList as $jur)
                                <option value="{{ $jur->id }}">{{ $jur->nama_jurusan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <select name="members[]" 
                            class="member-select w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                            focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                            text-gray-700 dark:text-gray-300
                            placeholder-gray-500 dark:placeholder-gray-400
                            shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm rounded-lg">
                            ${optionsHtml}
                        </select>
                        <button type="button" 
                            onclick="removeMember(this)"
                            class="px-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                            ✕
                        </button>
                    </div>
                </div>
            `;

            container.appendChild(memberDiv);

            // Add change listener to new select
            const newSelect = memberDiv.querySelector('.member-select');
            const searchInput = memberDiv.querySelector('.member-search');
            const filterAngkatan = memberDiv.querySelector('.member-filter-angkatan');
            const filterJurusan = memberDiv.querySelector('.member-filter-jurusan');

            newSelect.addEventListener('change', function () {
                updateDisabledOptions();
                saveToLocalStorage();
            });

            // Add search functionality
            searchInput.addEventListener('input', function () {
                filterMemberOptions(newSelect, this.value, filterAngkatan.value, filterJurusan.value);
            });

            // Add filter change listeners
            filterAngkatan.addEventListener('change', function () {
                filterMemberOptions(newSelect, searchInput.value, this.value, filterJurusan.value);
            });

            filterJurusan.addEventListener('change', function () {
                filterMemberOptions(newSelect, searchInput.value, filterAngkatan.value, this.value);
            });

            // Update disabled options
            setTimeout(() => {
                updateDisabledOptions();
            }, 100);
        }

        // Function to remove member
        function removeMember(button) {
            const memberDiv = button.closest('.member-item');
            memberDiv.remove();

            updateDisabledOptions();
            saveToLocalStorage();
        }

function addTaskRow(taskData = null) {
    const container = document.getElementById('tasks-container');
    const index = taskIndex++; // ⬅️ WAJIB

    const taskDiv = document.createElement('div');
    taskDiv.classList.add(
        'task-item', 'p-4', 'border', 'border-gray-200',
        'dark:border-gray-700', 'rounded-xl',
        'bg-gray-50', 'dark:bg-gray-900'
    );

    const isCollaborativeMode = isCollaborative();

    const defaultUserId = !isCollaborativeMode ? currentUserData.id : null;

    const taskNameValue = taskData?.name_task
        ? taskData.name_task.replace(/"/g, '&quot;')
        : '';

    const taskUserIdValue = taskData?.user_id ?? defaultUserId ?? '';

    let userOptions = renderTaskUserOptions(taskUserIdValue);

    taskDiv.innerHTML = `
        <div class="grid gap-3 md:grid-cols-[1fr_auto] items-start">
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium">Penanggung Jawab</label>
                    <select name="tasks[${index}][user_id]"
                        class="w-full px-4 py-3 text-sm border rounded-lg">
                        ${userOptions}
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Nama Tugas</label>
                    <input type="text"
                        name="tasks[${index}][name_task]"
                        value="${taskNameValue}"
                        class="w-full px-4 py-3 text-sm border rounded-lg"
                        placeholder="Contoh: Buat desain halaman utama" />
                </div>
            </div>

            <div class="pt-6">
                <button type="button" onclick="removeTaskRow(this)"
                    class="w-10 h-10 bg-red-100 text-red-600 rounded-lg">
                    ✕
                </button>
            </div>
        </div>
    `;

    container.appendChild(taskDiv);

    // ✅ Set selected user
    const select = taskDiv.querySelector(`select[name="tasks[${index}][user_id]"]`);
    if (select && taskUserIdValue) {
        select.value = taskUserIdValue;
    }
}
        function removeTaskRow(button) {
            const taskDiv = button.closest('.task-item');
            if (taskDiv) {
                taskDiv.remove();
                // If no tasks left, add an empty one
                if (document.querySelectorAll('.task-item').length === 0) {
                    addTaskRow();
                }
            }
        }

        function removeBlankTaskRows() {
            const taskRows = document.querySelectorAll('.task-item');
            const rowsToRemove = [];
            
            taskRows.forEach(taskRow => {
                const userSelect = taskRow.querySelector('select[name="tasks[][user_id]"]');
                const taskInput = taskRow.querySelector('input[name="tasks[][name_task]"]');

                if (!userSelect || !taskInput) return;

                const userValue = userSelect.value.trim();
                const taskValue = taskInput.value.trim();

                // Hapus row jika SALAH SATU saja kosong (kecuali keduanya kosong)
                if ((!userValue && taskValue) || (userValue && !taskValue)) {
                    rowsToRemove.push(taskRow);
                } 
                else if (!userValue && !taskValue) {
                    rowsToRemove.push(taskRow);
                }
            });
            
            rowsToRemove.forEach(row => row.remove());
            
            // If all tasks were removed, add one empty task
            if (document.querySelectorAll('.task-item').length === 0) {
                addTaskRow();
            }
        }

        // Function to filter member options
        function filterMemberOptions(selectElement, searchValue, filterAngkatan = '', filterJurusan = '') {
            const options = selectElement.querySelectorAll('option');
            const searchLower = searchValue.toLowerCase();

            options.forEach(option => {
                if (option.value === '') {
                    option.style.display = '';
                } else {
                    const name = option.getAttribute('data-name').toLowerCase();
                    const angkatan = option.getAttribute('data-angkatan');
                    const jurusan = option.getAttribute('data-jurusan');

                    let show = name.includes(searchLower);
                    if (filterAngkatan && show) {
                        show = angkatan === filterAngkatan;
                    }
                    if (filterJurusan && show) {
                        show = jurusan === filterJurusan;
                    }

                    option.style.display = show ? '' : 'none';
                }
            });
        }

        window.addMemberSelect = addMemberSelect;
        window.removeMember = removeMember;
        window.addTaskRow = addTaskRow;
        window.removeTaskRow = removeTaskRow;
        window.clearSelectedLeader = clearSelectedLeader;
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("Tambahkan project baru dengan mengisi formulir. Aktifkan projek kolaboratif untuk menambahkan pemimpin dan anggota tim. Jangan lupa untuk menyimpan perubahan setelah selesai.");
        });
    </script>
@endsection