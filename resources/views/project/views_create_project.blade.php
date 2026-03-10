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
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 dark:bg-gray-500 dark:placeholder:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('nama_project') border-red-500 @enderror"
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
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 dark:bg-gray-500 dark:placeholder:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('deskripsi') border-red-500 @enderror"
                    placeholder="Deskripsikan project Anda...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-white">
                    <span data-translate="tambah_pemimpin" data-translate-page="project_create"></span>
                </label>
            </div>

            <div>
                <select name="leader" id="leader-select"
                    class="min-w-full border border-gray-300 dark:text-white dark:bg-gray-500 dark:border-gray-700 rounded-lg p-3">
                    <option class="dark:text-white" value="">-- Pilih Pemimpin Project --</option>
                    @foreach ($users as $user)
                        <option class="dark:text-white" value="{{ $user->id }}" data-name="{{ $user->nama_mahasiswa }}">
                            {{$user->nama_mahasiswa}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="member-wrapper">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="tambah_rekan" data-translate-page="project_create"></span>
                </label>

                <div id="members-container">
                    <!-- Member items will be dynamically added here -->
                </div>
            </div>

            <button type="button" onclick="addMemberSelect()"
                class="text-sm text-indigo-600 dark:text-indigo-400 hover:cursor-pointer hover:underline">
                <span data-translate="tambah_rekan_btn" data-translate-page="project_create"></span>
            </button>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span data-translate="tanggal_mulai" data-translate-page="project_create"></span> <span
                        class="text-red-500">*</span>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                        class="w-full px-4 py-3 border dark:text-white dark:bg-gray-500 dark:border-gray-700 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_mulai') border-red-500 @enderror">
                    @error('tanggal_mulai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <span data-translate="tanggal_selesai" data-translate-page="project_create"></span>
                    <input type="date" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}"
                        class="w-full px-4 py-3 border dark:text-white dark:bg-gray-500 dark:border-gray-700 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_akhir') border-red-500 @enderror">
                    @error('tanggal_akhir')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <span data-translate="link_project_opsional" data-translate-page="project_create"></span>
                <input type="url" name="link_project" value="{{ old('link_project') }}"
                    class="w-full px-4 py-3 border dark:text-white dark:bg-gray-500 dark:border-gray-700 border-gray-300 dark:placeholder:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_project') border-red-500 @enderror"
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
                    class="w-full px-4 py-3 border border-gray-300 dark:text-white dark:bg-gray-500 dark:border-gray-700 dark:placeholder:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_github') border-red-500 @enderror"
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
                    class="w-full px-4 py-3 border dark:text-white dark:bg-gray-500 dark:border-gray-700 dark:placeholder:text-white border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_video') border-red-500 @enderror"
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
        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                showSuccessAlert('{{ session('success') }}');
            @endif

            @if ($errors->any())
                showErrorAlert('{{ $errors->first() }}');
                {{ implode("\n", $errors->all()) }}
            @endif

            // Load saved data from localStorage
            loadSavedData();

            // Initialize member selects
            initializeMemberSelects();

            // Add change listener to leader select
            const leaderSelect = document.getElementById("leader-select");
            leaderSelect.addEventListener("change", function () {
                updateDisabledOptions();
                saveToLocalStorage();
            });

            // Add submit event listener to form
            document.getElementById('projectForm').addEventListener('submit', function () {
                clearLocalStorage();
            });
        });

        // Function to save form data to localStorage
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

        // Function to load saved data from localStorage
        function loadSavedData() {
            const savedData = localStorage.getItem('projectTeamData');

            if (savedData) {
                try {
                    const data = JSON.parse(savedData);

                    // Set leader
                    if (data.leader) {
                        document.getElementById("leader-select").value = data.leader;
                    }

                    // Clear existing members
                    const container = document.getElementById('members-container');
                    container.innerHTML = '';

                    // Add saved members
                    if (data.members && data.members.length > 0) {
                        data.members.forEach(memberId => {
                            if (memberId) {
                                addMemberSelect(memberId);
                            }
                        });
                    } else {
                        // Add one empty member select if no saved members
                        addMemberSelect();
                    }

                    // Update disabled options
                    setTimeout(() => {
                        updateDisabledOptions();
                    }, 100);

                } catch (e) {
                    console.error('Error parsing saved data:', e);
                    // Add one empty member select if error
                    addMemberSelect();
                }
            } else {
                // Add one empty member select if no saved data
                addMemberSelect();
            }
        }

        // Function to clear localStorage after submit
        function clearLocalStorage() {
            localStorage.removeItem('projectTeamData');
        }

        // Function to update disabled options based on selected leader and members
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
                // Enable all options first
                select.querySelectorAll("option").forEach(option => {
                    option.disabled = false;
                });

                // Disable leader option if leader is selected
                if (leaderId) {
                    let leaderOption = select.querySelector(`option[value="${leaderId}"]`);
                    if (leaderOption) {
                        leaderOption.disabled = true;
                    }
                }

                // Disable options that are selected in other member selects
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
            // Add change listeners to all member selects
            document.querySelectorAll(".member-select").forEach(select => {
                select.addEventListener("change", function () {
                    updateDisabledOptions();
                    saveToLocalStorage();
                });
            });
        }

        // Function to add new member select
        function addMemberSelect(savedValue = null) {
            const container = document.getElementById('members-container');

            const memberDiv = document.createElement('div');
            memberDiv.classList.add('member-item', 'mb-3');

            const usersData = @json($users);

            let optionsHtml = '<option value="">-- Pilih Mahasiswa --</option>';
            usersData.forEach(user => {
                const selected = savedValue && savedValue == user.id ? 'selected' : '';
                optionsHtml += `<option value="${user.id}" data-name="${user.nama_mahasiswa}" ${selected}>${user.nama_mahasiswa}</option>`;
            });

            memberDiv.innerHTML = `
                        <div class="flex gap-2">
                            <select name="members[]" 
                                class="member-select w-full p-3 border border-gray-300 dark:text-white dark:bg-gray-500 dark:border-gray-700 rounded-lg">
                                ${optionsHtml}
                            </select>
                            <button type="button" 
                                onclick="removeMember(this)"
                                class="px-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                                ✕
                            </button>
                        </div>
                    `;

            container.appendChild(memberDiv);

            // Add change listener to new select
            const newSelect = memberDiv.querySelector('.member-select');
            newSelect.addEventListener('change', function () {
                updateDisabledOptions();
                saveToLocalStorage();
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

            // Update disabled options after removal
            updateDisabledOptions();

            // Save to localStorage
            saveToLocalStorage();
        }

        // Export functions to global scope
        window.addMemberSelect = addMemberSelect;
        window.removeMember = removeMember;
    </script>
@endsection