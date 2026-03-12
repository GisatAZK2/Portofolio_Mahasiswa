@extends('Layout.Layout')

@section('content')
<div class="p-6 lg:p-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2" data-translate="edit_project"
        data-translate-page="project_create">Edit Project</h1>
    <p class="text-gray-600 dark:text-gray-200" data-translate="desc_create" data-translate-page="project_create">
        Edit informasi project Anda di bawah ini
    </p>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('project.update', $project->id) }}"
        class="space-y-6 bg-white p-8 rounded-xl shadow-md border dark:bg-gray-800 dark:border-gray-800 border-gray-100">
        @csrf
        @method('PUT')

        <!-- Nama Project -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                <span data-translate="nama_project" data-translate-page="project_create">Nama Project</span> <span
                    class="text-red-500">*</span>
            </label>
            <input type="text" name="nama_project"
                value="{{ old('nama_project', $project->isi_content['nama_project'] ?? '') }}" required
                class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('nama_project') border-red-500 @enderror">
            @error('nama_project')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                <span data-translate="deskripsi_opsional" data-translate-page="project_create">Deskripsi
                    (Opsional)</span>
            </label>
            <textarea name="deskripsi" rows="4"
                class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('deskripsi') border-red-500 @enderror"
                placeholder="Deskripsikan project Anda...">{{ old('deskripsi', $project->isi_content['deskripsi'] ?? '') }}</textarea>
            @error('deskripsi')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Leader -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                <span data-translate="tambah_pemimpin" data-translate-page="project_create">Pemimpin
                    Project</span> <span class="text-red-500">*</span>
            </label>
            <select name="leader" id="leader-select"
                class="dark:bg-gray-700 dark:text-white w-full border border-gray-300 rounded-lg p-3"
                onchange="updateAllSelects()">
                <option class="dark:bg-gray-700 dark:text-white" value="">-- Pilih Pemimpin Project --</option>
                @foreach ($users as $user)
                    <option class="dark:bg-gray-700 dark:text-white" value="{{ $user->id }}"
                        data-user-id="{{ $user->id }}"
                        {{ old('leader', $project->leader_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->nama_mahasiswa }}
                    </option>
                @endforeach
            </select>
            @error('leader')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Members Section -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                <span data-translate="tambah_rekan" data-translate-page="project_create">Anggota Project</span>
            </label>

            <div id="member-wrapper" class="space-y-3">
                @php
                    $oldMembers = old('members', $project->members->pluck('id')->toArray());
                @endphp

                @forelse($oldMembers as $index => $memberId)
                    <div class="member-item">
                        <div class="flex gap-2">
                            <select name="members[]"
                                class="w-full p-3 border border-gray-300 dark:bg-gray-700 dark:text-white rounded-lg member-select"
                                onchange="updateAllSelects()">
                                <option class="dark:bg-gray-700 dark:text-white" value="">-- Pilih Mahasiswa --
                                </option>
                                @foreach ($users as $user)
                                    <option class="dark:bg-gray-700 dark:text-white" value="{{ $user->id }}"
                                        data-user-id="{{ $user->id }}"
                                        {{ $memberId == $user->id ? 'selected' : '' }}>
                                        {{ $user->nama_mahasiswa }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="button" onclick="removeMember(this)"
                                class="px-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                                ✕
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Jika tidak ada member, tampilkan 1 field kosong -->
                    <div class="member-item">
                        <div class="flex gap-2">
                            <select name="members[]"
                                class="w-full p-3 border border-gray-300 dark:bg-gray-700 dark:text-white rounded-lg member-select"
                                onchange="updateAllSelects()">
                                <option class="dark:bg-gray-700 dark:text-white" value="">-- Pilih Mahasiswa --
                                </option>
                                @foreach ($users as $user)
                                    <option class="dark:bg-gray-700 dark:text-white" value="{{ $user->id }}"
                                        data-user-id="{{ $user->id }}">
                                        {{ $user->nama_mahasiswa }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="button" onclick="removeMember(this)"
                                class="px-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                                ✕
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>

            <button type="button" onclick="addMemberSelect()"
                class="mt-3 text-sm text-indigo-600 dark:text-indigo-400 hover:cursor-pointer hover:underline">
                + Tambah Anggota Lain
            </button>
        </div>

        <!-- Tanggal Mulai dan Selesai -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="tanggal_mulai" data-translate-page="project_create">Tanggal Mulai</span>
                    <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal_mulai"
                    value="{{ old('tanggal_mulai', $project->tanggal_mulai->format('Y-m-d')) }}" required
                    class="w-full px-4 py-3 border border-gray-300 dark:text-white dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('tanggal_mulai') border-red-500 @enderror">
                @error('tanggal_mulai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    <span data-translate="tanggal_selesai" data-translate-page="project_create">Tanggal
                        Selesai</span>
                </label>
                <input type="date" name="tanggal_akhir"
                    value="{{ old('tanggal_akhir', $project->tanggal_akhir ? $project->tanggal_akhir->format('Y-m-d') : '') }}"
                    class="w-full px-4 py-3 border border-gray-300 dark:text-white dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('tanggal_akhir') border-red-500 @enderror">
                @error('tanggal_akhir')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Link Project -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                <span data-translate="link_project_opsional" data-translate-page="project_create">Link Project
                    (Opsional)</span>
            </label>
            <input type="url" name="link_project"
                value="{{ old('link_project', $project->isi_content['link_project'] ?? '') }}"
                class="w-full px-4 py-3 border border-gray-300 dark:text-white dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('link_project') border-red-500 @enderror"
                placeholder="https://github.com/username/project">
            @error('link_project')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Link GitHub -->
        <div>
            <label for="link_github" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                <span data-translate="link_github_opsional" data-translate-page="project_create">Link GitHub
                    (Opsional)</span>
            </label>
            <input type="url" name="link_github" id="link_github" maxlength="500"
                class="w-full px-4 py-3 border border-gray-300 dark:text-white dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_github') border-red-500 @enderror"
                placeholder="https://github.com/username/repo"
                value="{{ old('link_github', $project->isi_content['link_github'] ?? '') }}">
            @error('link_github')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Link Video -->
        <div>
            <label for="link_video" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                <span data-translate="link_video_opsional" data-translate-page="project_create">Link Video
                    (Opsional)</span>
            </label>
            <input type="url" name="link_video" id="link_video" maxlength="500"
                class="w-full px-4 py-3 border border-gray-300 dark:text-white dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_video') border-red-500 @enderror"
                placeholder="https://www.youtube.com/watch?v=..."
                value="{{ old('link_video', $project->isi_content['link_video'] ?? '') }}">
            @error('link_video')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-4 pt-4">
            <a href="{{ route('project.index') }}"
                class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition">
                Batal
            </a>
            <button type="submit"
                class="px-8 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow-md">
                Simpan Project
            </button>
        </div>
    </form>
</div>

<script>
    // Fungsi utama untuk mengupdate semua select options
    function updateAllSelects() {
        // Kumpulkan semua ID yang sudah dipilih
        const selectedIds = new Set();
        
        // Ambil ID leader
        const leaderSelect = document.getElementById('leader-select');
        if (leaderSelect && leaderSelect.value) {
            selectedIds.add(leaderSelect.value);
        }
        
        // Ambil ID dari semua member select
        const memberSelects = document.querySelectorAll('select[name="members[]"]');
        memberSelects.forEach(select => {
            if (select.value) {
                selectedIds.add(select.value);
            }
        });

        // Update leader select options
        updateSelectOptions(leaderSelect, selectedIds, leaderSelect.value);

        // Update setiap member select options
        memberSelects.forEach(select => {
            updateSelectOptions(select, selectedIds, select.value);
        });
    }

    // Fungsi untuk mengupdate options dalam satu select
    function updateSelectOptions(selectElement, selectedIds, currentValue) {
        if (!selectElement) return;

        // Simpan value yang sedang dipilih
        const options = selectElement.querySelectorAll('option[data-user-id]');
        
        options.forEach(option => {
            const userId = option.getAttribute('data-user-id');
            
            // Sembunyikan option jika ID sudah dipilih di select lain dan bukan option yang sedang dipilih di select ini
            if (selectedIds.has(userId) && userId !== currentValue) {
                option.style.display = 'none';
                option.disabled = true;
            } else {
                option.style.display = '';
                option.disabled = false;
            }
        });
    }

    // Fungsi untuk menambah field member baru
    function addMemberSelect() {
        let wrapper = document.getElementById('member-wrapper');

        let newMemberDiv = document.createElement('div');
        newMemberDiv.classList.add('member-item');

        // Get users data from PHP
        let usersOptions = '';
        @foreach ($users as $user)
            usersOptions += `<option class="dark:bg-gray-700 dark:text-white" value="{{ $user->id }}" data-user-id="{{ $user->id }}">
                {{ $user->nama_mahasiswa }}
            </option>`;
        @endforeach

        newMemberDiv.innerHTML = `
            <div class="flex gap-2">
                <select name="members[]" 
                    class="w-full p-3 border border-gray-300 dark:bg-gray-700 dark:text-white rounded-lg member-select"
                    onchange="updateAllSelects()">
                    <option class="dark:bg-gray-700 dark:text-white" value="">-- Pilih Mahasiswa --</option>
                    ${usersOptions}
                </select>

                <button type="button" 
                    onclick="removeMember(this)"
                    class="px-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                    ✕
                </button>
            </div>
        `;

        wrapper.appendChild(newMemberDiv);

        // Update semua select setelah menambah
        updateAllSelects();
        
        // Simpan ke localStorage
        saveMembersToLocalStorage();
    }

    // Fungsi untuk menghapus field member
    function removeMember(button) {
        // Hapus elemen member-item
        let memberItem = button.closest('.member-item');
        if (memberItem) {
            memberItem.remove();
        }

        // Update semua select setelah menghapus
        updateAllSelects();
        
        // Simpan perubahan ke localStorage
        saveMembersToLocalStorage();
    }

    // Fungsi untuk menyimpan data member ke localStorage
    function saveMembersToLocalStorage() {
        let members = [];
        let selects = document.querySelectorAll('select[name="members[]"]');

        selects.forEach(select => {
            if (select.value) {
                members.push(select.value);
            }
        });

        localStorage.setItem('project_members', JSON.stringify(members));
    }

    // Fungsi untuk memuat data member dari localStorage
    function loadMembersFromLocalStorage() {
        let savedMembers = localStorage.getItem('project_members');

        if (savedMembers) {
            try {
                let members = JSON.parse(savedMembers);
                let selects = document.querySelectorAll('select[name="members[]"]');

                selects.forEach((select, index) => {
                    if (index < members.length && members[index]) {
                        select.value = members[index];
                    }
                });
                
                // Update select options setelah memuat data
                updateAllSelects();
            } catch (e) {
                console.error('Error loading members from localStorage', e);
            }
        }
    }

    // Event listener untuk menyimpan saat select berubah
    document.addEventListener('change', function(e) {
        if (e.target && (e.target.name === 'members[]' || e.target.id === 'leader-select')) {
            saveMembersToLocalStorage();
        }
    });

    // Hapus data localStorage saat form disubmit
    document.querySelector('form').addEventListener('submit', function() {
        localStorage.removeItem('project_members');
    });

    // Load data dari localStorage dan inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        loadMembersFromLocalStorage();
        
        // Tambahkan event listener untuk semua select yang sudah ada
        updateAllSelects();
        
        // Tambahkan observer untuk mendeteksi perubahan dinamis
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length) {
                    // Jika ada elemen baru ditambahkan, pastikan select di dalamnya memiliki event listener
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            const selects = node.querySelectorAll('select.member-select');
                            selects.forEach(select => {
                                select.setAttribute('onchange', 'updateAllSelects()');
                            });
                        }
                    });
                }
            });
        });

        // Observasi perubahan pada member-wrapper
        const memberWrapper = document.getElementById('member-wrapper');
        if (memberWrapper) {
            observer.observe(memberWrapper, { childList: true, subtree: true });
        }
    });

    // Info page
    document.addEventListener("DOMContentLoaded", () => {
        showPageInfo("Edit informasi project atau ganti file jika diperlukan. Pastikan untuk menyimpan perubahan setelah selesai.");
    });
</script>

<style>
  
    select option:disabled {
        color: #999;
        background-color: #f5f5f5;
    }
    

    .dark select option:disabled {
        color: #666;
        background-color: #2d2d2d;
    }
</style>

@endsection