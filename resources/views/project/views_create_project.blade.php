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
                    class="w-full pl-4  py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                        focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                        text-gray-700 dark:text-gray-300
                                        placeholder-gray-500 dark:placeholder-gray-400
                                        shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm @error('nama_project') border-red-500 @enderror" placeholder="Contoh: Website Portfolio Pribadi">
                @error('nama_project')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <span data-translate="deskripsi_opsional" data-translate-page="project_create"></span>
                </label>
                <textarea name="deskripsi" rows="4"
                    class="w-full pl-4  py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                        focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                        text-gray-700 dark:text-gray-300
                                        placeholder-gray-500 dark:placeholder-gray-400
                                        shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm @error('deskripsi') border-red-500 @enderror"
                    placeholder="Deskripsikan project Anda...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Add Pemimpin Project (Optional) -->
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

            <!-- Add Members (Optional) -->
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

            <!-- Hidden inputs for leader -->
            <input type="hidden" name="leader_name" id="leader-name">
            <input type="hidden" name="leader_photo" id="leader-photo">
            <input type="hidden" name="leader_email" id="leader-email">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="text-black dark:text-white" data-translate="tanggal_mulai"
                        data-translate-page="project_create"></span> <span class="text-red-500">*</span>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                        class="w-full pl-4  py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
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
                        class="w-full pl-4  py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
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
                    class="w-full pl-4  py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
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
                    class="w-full pl-4  py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
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
                    class="w-full pl-4  py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                        focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                        text-gray-700 dark:text-gray-300
                                        placeholder-gray-500 dark:placeholder-gray-400
                                        shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm transition @error('link_video') border-red-500 @enderror"
                    placeholder="https://www.youtube.com/watch?v=..." value="{{ old('link_video') }}">
                @error('link_video')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
        // Update leader display when selected
        function updateLeaderDisplay(userId) {
            if (!userId) {
                document.getElementById('selected-leader-display').classList.add('hidden');
                document.getElementById('leader-name').value = '';
                document.getElementById('leader-photo').value = '';
                document.getElementById('leader-email').value = '';
                return;
            }

            const usersData = @json($users);
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
                {{ implode("\n", $errors->all()) }}
            @endif

            loadSavedData();
            initializeMemberSelects();

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

            document.getElementById('projectForm').addEventListener('submit', function () {
                clearLocalStorage();
            });

            // Load saved leader data
            const oldLeaderId = document.getElementById('leader-select').value;
            if (oldLeaderId) {
                updateLeaderDisplay(oldLeaderId);
            }
        });

        function saveToLocalStorage() {
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

            if (savedData) {
                try {
                    const data = JSON.parse(savedData);

                    // Set leader
                    if (data.leader) {
                        document.getElementById("leader-select").value = data.leader;
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
            const container = document.getElementById('members-container');

            const memberDiv = document.createElement('div');
            memberDiv.classList.add('member-item', 'mb-4');

            const usersData = @json($users);

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
                                                        class="member-select w-full pl-4  py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
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

        // Function to add member select with direct value
        function addMemberSelectWithValue(userId) {
            const container = document.getElementById('members-container');
            const memberDiv = document.createElement('div');
            memberDiv.classList.add('member-item', 'mb-4');

            const usersData = @json($users);

            let optionsHtml = '<option value="">-- Pilih Mahasiswa --</option>';
            usersData.forEach(user => {
                const selected = userId == user.id ? 'selected' : '';
                optionsHtml += `<option value="${user.id}" data-name="${user.nama_mahasiswa}" ${selected}>${user.nama_mahasiswa}</option>`;
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
                                                <div class="flex gap-2">
                                                    <select name="members[]" 
                                                        class="member-select w-full pl-4  py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
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

            newSelect.addEventListener('change', function () {
                updateDisabledOptions();
                saveToLocalStorage();
            });

            // Add search functionality
            searchInput.addEventListener('input', function () {
                filterMemberOptions(newSelect, this.value);
            });

            // Update disabled options
            setTimeout(() => {
                updateDisabledOptions();
            }, 100);
        }

        // Function to filter leader options
        function filterLeaderOptions(selectElement, searchValue) {
            const options = selectElement.querySelectorAll('option');
            const searchLower = searchValue.toLowerCase();

            options.forEach(option => {
                if (option.value === '') {
                    option.style.display = '';
                } else {
                    const name = option.getAttribute('data-name').toLowerCase();
                    option.style.display = name.includes(searchLower) ? '' : 'none';
                }
            });
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
        window.addMemberSelectWithValue = addMemberSelectWithValue;
        window.removeMember = removeMember;
    </script>


    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("Tambahkan project baru dengan mengisi formulir. Pastikan untuk memilih pemimpin project dan menambahkan anggota tim jika diperlukan. Jangan lupa untuk menyimpan perubahan setelah selesai.");
        });
    </script>
@endsection