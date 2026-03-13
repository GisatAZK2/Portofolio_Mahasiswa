@extends('Layout.Layout')
@section('title', 'Tambah Project Mahasiswa Baru')
@section('content')
    <div class="min-h-screen">
        <div class="p-8">
            <!-- Header -->
            <div class="mb-8 text-center md:text-left">
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">    
                        <span data-translate="kelola_create_project_title" data-translate-page="kelola_create_project"></span>
                </div>
                <p class="mt-2 text-gray-600 dark:text-gray-200">
                    <span data-translate="kelola_create_project_desc" data-translate-page="kelola_create_project"></span>
                </p>
            </div>

            <!-- Error Global -->
            @if ($errors->any())
                <div class="mb-8 p-5 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">Terdapat kesalahan pada input:</span>
                    </div>
                    <ul class="list-disc pl-10 space-y-1.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('admin.projects.store') }}" class="space-y-7" id="projectForm">
                @csrf

                <!-- Search and Filter Section -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Filter Mahasiswa</h3>
                    
                    <!-- Search Bar -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" 
                                   id="search-input"
                                   placeholder="Cari nama mahasiswa..." 
                                   value="{{ $search ?? '' }}"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition">
                            <div class="absolute left-3 top-3.5 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Dropdowns -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Filter Angkatan -->
                        <div>
                            <label for="angkatan-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Angkatan
                            </label>
                            <select id="angkatan-filter"
                                class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition">
                                <option value="">Semua Angkatan</option>
                                @foreach($angkatans as $angk)
                                    <option value="{{ $angk->id }}" {{ ($angkatan ?? '') == $angk->id ? 'selected' : '' }}>
                                        {{ $angk->tahun_angkatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Jurusan -->
                        <div>
                            <label for="jurusan-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jurusan
                            </label>
                            <select id="jurusan-filter"
                                class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition">
                                <option value="">Semua Jurusan</option>
                                @foreach($jurusans as $jrs)
                                    <option value="{{ $jrs->id }}" {{ ($jurusan ?? '') == $jrs->id ? 'selected' : '' }}>
                                        {{ $jrs->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Keahlian -->
                        <div>
                            <label for="keahlian-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Keahlian
                            </label>
                            <select id="keahlian-filter"
                                class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition">
                                <option value="">Semua Keahlian</option>
                                @foreach($keahlians as $keahlianItem)
                                    <option value="{{ $keahlianItem->id }}" {{ ($keahlian ?? '') == $keahlianItem->id ? 'selected' : '' }}>
                                        {{ $keahlianItem->nama_keahlian }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex justify-end space-x-3 mt-4">
                        <a href="{{ route('admin.projects.create') }}" 
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                            Reset Filter
                        </a>
                        <button type="button" onclick="applyFilters()"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            Terapkan Filter
                        </button>
                    </div>
                </div>

                <!-- Nama Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Nama Project <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_project" value="{{ old('nama_project') }}" required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('nama_project') border-red-500 @enderror"
                        placeholder="Contoh: Website Portfolio Pribadi">
                    @error('nama_project')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Deskripsi (opsional)
                    </label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('deskripsi') border-red-500 @enderror"
                        placeholder="Deskripsikan project Anda...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pemimpin Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Pilih Mahasiswa (Pemimpin Project) <span class="text-red-500">*</span>
                    </label>
                    
                    <div id="selected-leader-display" class="mb-4 hidden">
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="text-green-800 font-medium" id="selected-leader-name"></span>
                                </div>
                                <button type="button" onclick="clearSelectedLeader()" class="text-green-600 hover:text-green-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Input for Selected Leader ID -->
                    <input type="hidden" name="leader" id="selected-leader-id" value="{{ old('leader') }}">

                    <!-- Table of Users for Leader -->
                    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Pilih
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Photo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nama Mahasiswa
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Angkatan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Jurusan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Keahlian
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"                                  style="width: 150px;">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="leader-table-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($users as $user)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer" 
                                        onclick="selectLeader({{ $user->id }}, '{{ $user->nama_mahasiswa }}', '{{ $user->photo_profile }}')">
                                        <td class="px-6 py-4">
                                            <input type="radio" 
                                                   name="leader_radio" 
                                                   value="{{ $user->id }}"
                                                   class="leader-radio w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                                   {{ old('leader') == $user->id ? 'checked' : '' }}
                                                   onchange="selectLeader({{ $user->id }}, '{{ $user->nama_mahasiswa }}', '{{ $user->photo_profile }}')">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex-shrink-0">
                                                @if($user->photo_profile && file_exists(public_path('storage/' . $user->photo_profile)))
                                                    <img class="h-10 w-10 rounded-full object-cover" 
                                                         src="{{ asset('storage/' . $user->photo_profile) }}"
                                                         id="logo-zoom" 
                                                         alt="{{ $user->nama_mahasiswa }}"
                                                         onerror="this.onerror=null; this.src='{{ asset('default-avatar.png') }}'">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                                        <span class="text-indigo-600 dark:text-indigo-300 font-medium text-sm">
                                                            {{ strtoupper(substr($user->nama_mahasiswa, 0, 2)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                                {{ $user->nama_mahasiswa }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $user->email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $user->angkatan->nama_angkatan ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $user->jurusan->nama_jurusan ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                {{ $user->keahlian->nama_keahlian ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium">
                                            <a href="{{ $user->id ? route('portfolio.show', ['user' => $user->id]) : '#' }}" 
                                                class="text-indigo-600 hover:text-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded transition">
                                                Lihat Profil
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>
                                                <p class="text-lg font-medium">Tidak ada mahasiswa ditemukan</p>
                                                <p class="text-sm">Coba ubah filter pencarian Anda</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4" id="pagination-links">
                        {{ $users->links() }}
                    </div>

                    @error('leader')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rekan Project -->
                <div id="member-wrapper">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Tambah Rekan (opsional)
                    </label>

                    <div id="members-container">
                        <!-- Member items will be dynamically added here -->
                    </div>

                    <button type="button"
                        onclick="addMemberSelect()"
                        class="mt-3 text-sm text-indigo-600 dark:text-indigo-400 hover:cursor-pointer hover:underline">
                        + Tambah Rekan
                    </button>
                </div>

                <!-- Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                            class="w-full px-4 py-3 border dark:text-white dark:bg-gray-700 dark:border-gray-600 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_mulai') border-red-500 @enderror">
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Tanggal Selesai (opsional)
                        </label>
                        <input type="date" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}"
                            class="w-full px-4 py-3 border dark:text-white dark:bg-gray-700 dark:border-gray-600 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_akhir') border-red-500 @enderror">
                        @error('tanggal_akhir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Link Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Link Project (opsional)
                    </label>
                    <input type="url" name="link_project" value="{{ old('link_project') }}"
                        class="w-full px-4 py-3 border dark:text-white dark:bg-gray-700 dark:border-gray-600 border-gray-300 dark:placeholder:text-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_project') border-red-500 @enderror"
                        placeholder="https://github.com/username/project">
                    @error('link_project')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link GitHub -->
                <div>
                    <label for="link_github" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Link GitHub (opsional)
                    </label>
                    <input type="url" name="link_github" id="link_github" maxlength="500"
                        class="w-full px-4 py-3 border border-gray-300 dark:text-white dark:bg-gray-700 dark:border-gray-600 dark:placeholder:text-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_github') border-red-500 @enderror"
                        placeholder="https://github.com/username/repo" value="{{ old('link_github') }}">
                    @error('link_github')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link Video -->
                <div>
                    <label for="link_video" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Link Video (YouTube, opsional)
                    </label>
                    <input type="url" name="link_video" id="link_video" maxlength="500"
                        class="w-full px-4 py-3 border dark:text-white dark:bg-gray-700 dark:border-gray-600 dark:placeholder:text-gray-400 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_video') border-red-500 @enderror"
                        placeholder="https://www.youtube.com/watch?v=..." value="{{ old('link_video') }}">
                    @error('link_video')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.projects.index') }}"
                        class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md">
                        Simpan Project
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentFilters = {
            search: '',
            angkatan: '',
            jurusan: '',
            keahlian: ''
        };

        // Function to apply filters
        function applyFilters() {
            currentFilters.search = document.getElementById('search-input').value;
            currentFilters.angkatan = document.getElementById('angkatan-filter').value;
            currentFilters.jurusan = document.getElementById('jurusan-filter').value;
            currentFilters.keahlian = document.getElementById('keahlian-filter').value;
            
            fetchFilteredUsers();
        }

        // Function to fetch filtered users
        function fetchFilteredUsers(page = 1) {
            const url = new URL('{{ route("admin.projects.create") }}');
            url.searchParams.set('search', currentFilters.search);
            url.searchParams.set('angkatan', currentFilters.angkatan);
            url.searchParams.set('jurusan', currentFilters.jurusan);
            url.searchParams.set('keahlian', currentFilters.keahlian);
            url.searchParams.set('page', page);
            
            fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                const newLeaderBody = doc.querySelector('#leader-table-body');
                if (newLeaderBody) {
                    document.getElementById('leader-table-body').innerHTML = newLeaderBody.innerHTML;
                }
                
                const newPagination = doc.querySelector('#pagination-links');
                if (newPagination) {
                    document.getElementById('pagination-links').innerHTML = newPagination.innerHTML;
                }

                attachTableRowListeners();
                
                const selectedLeaderId = document.getElementById('selected-leader-id').value;
                if (selectedLeaderId) {
                    const selectedRadio = document.querySelector(`.leader-radio[value="${selectedLeaderId}"]`);
                    if (selectedRadio) {
                        selectedRadio.checked = true;
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Function to attach click listeners to table rows
        function attachTableRowListeners() {
            document.querySelectorAll('#leader-table-body tr').forEach(row => {
                const radio = row.querySelector('.leader-radio');
                if (radio) {
                    const nameCell = row.querySelector('td:nth-child(3) .font-medium');
                    const name = nameCell ? nameCell.textContent.trim() : '';
                    const photoProfile = ''; // We don't have photo profile in the row click
                    
                    row.onclick = () => {
                        const img = row.querySelector('img');
                        const photoSrc = img ? img.src : '';
                        selectLeader(radio.value, name, photoSrc);
                    };
                }
            });
        }

        // Function to select leader
        function selectLeader(userId, userName, photoProfile) {
            document.getElementById('selected-leader-id').value = userId;
            
            document.querySelectorAll('.leader-radio').forEach(radio => {
                radio.checked = (radio.value == userId);
            });
            
            const display = document.getElementById('selected-leader-display');
            const nameSpan = document.getElementById('selected-leader-name');
            
            if (userId) {
                // Create leader display with photo if available
                const photoHtml = photoProfile ? 
                    `<img class="w-8 h-8 rounded-full object-cover" src="${photoProfile}" alt="${userName}">` :
                    `<div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                        <span class="text-green-600 font-medium text-sm">${userName.charAt(0).toUpperCase()}</span>
                    </div>`;
                
                nameSpan.innerHTML = `
                    <div class="flex items-center space-x-3">
                        ${photoHtml}
                        <span>Pemimpin: ${userName}</span>
                    </div>
                `;
                display.classList.remove('hidden');
            } else {
                display.classList.add('hidden');
            }

            updateDisabledOptions();
            saveToLocalStorage();
        }

        // Function to clear selected leader
        function clearSelectedLeader() {
            document.getElementById('selected-leader-id').value = '';
            document.querySelectorAll('.leader-radio').forEach(radio => {
                radio.checked = false;
            });
            document.getElementById('selected-leader-display').classList.add('hidden');
            
            updateDisabledOptions();
            saveToLocalStorage();
        }

        // Function to add new member select
        function addMemberSelect(savedValue = null) {
            const container = document.getElementById('members-container');

            const memberDiv = document.createElement('div');
            memberDiv.classList.add('member-item', 'mb-3');

            const users = [];
            document.querySelectorAll('#leader-table-body tr').forEach(row => {
                const radio = row.querySelector('.leader-radio');
                if (radio) {
                    const nameElement = row.querySelector('td:nth-child(3) .font-medium');
                    const name = nameElement ? nameElement.textContent.trim() : '';
                    
                    // Get photo
                    const img = row.querySelector('img');
                    const photoProfile = img ? img.src : '';
                    
                    // Get initial if no photo
                    const initial = name.charAt(0).toUpperCase();
                    
                    users.push({
                        id: radio.value,
                        name: name,
                        photoProfile: photoProfile,
                        initial: initial
                    });
                }
            });

            if (users.length === 0) {
                memberDiv.innerHTML = `
                    <div class="flex gap-2">
                        <select class="member-select w-full p-3 border border-gray-300 dark:text-white dark:bg-gray-500 dark:border-gray-700 rounded-lg" disabled>
                            <option value="">Tidak ada mahasiswa tersedia</option>
                        </select>
                        <button type="button" 
                            onclick="removeMember(this)"
                            class="px-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                            ✕
                        </button>
                    </div>
                `;
            } else {
                let optionsHtml = '<option value="">-- Pilih Mahasiswa --</option>';
                users.forEach(user => {
                    const selected = savedValue && savedValue == user.id ? 'selected' : '';
                    optionsHtml += `<option value="${user.id}" data-photo="${user.photoProfile}" data-initial="${user.initial}" ${selected}>${user.name}</option>`;
                });

                memberDiv.innerHTML = `
                    <div class="flex gap-2 items-center">
                        <div class="relative flex-1">
                            <select name="members[]" 
                                class="member-select w-full p-3 pl-12 border border-gray-300 dark:text-white dark:bg-gray-500 dark:border-gray-700 rounded-lg appearance-none">
                                ${optionsHtml}
                            </select>
                            <div class="member-photo absolute left-2 top-1/2 transform -translate-y-1/2">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center overflow-hidden">
                                    <span class="member-initial text-indigo-600 font-medium text-sm"></span>
                                    <img class="member-img hidden w-full h-full object-cover" src="" alt="">
                                </div>
                            </div>
                        </div>
                        <button type="button" 
                            onclick="removeMember(this)"
                            class="px-3 py-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                            ✕
                        </button>
                    </div>
                `;

                container.appendChild(memberDiv);

                const select = memberDiv.querySelector('.member-select');
                const photoDiv = memberDiv.querySelector('.member-photo');
                const initialSpan = memberDiv.querySelector('.member-initial');
                const img = memberDiv.querySelector('.member-img');

                // Update photo when selection changes
                select.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (this.value) {
                        const photo = selectedOption.dataset.photo;
                        const initial = selectedOption.dataset.initial;
                        
                        if (photo) {
                            img.src = photo;
                            img.classList.remove('hidden');
                            initialSpan.classList.add('hidden');
                        } else {
                            img.classList.add('hidden');
                            initialSpan.classList.remove('hidden');
                            initialSpan.textContent = initial;
                        }
                    } else {
                        img.classList.add('hidden');
                        initialSpan.classList.remove('hidden');
                        initialSpan.textContent = '?';
                    }
                    updateDisabledOptions();
                    saveToLocalStorage();
                });

                // Trigger initial change if there's a saved value
                if (savedValue) {
                    select.value = savedValue;
                    select.dispatchEvent(new Event('change'));
                }
            }

            updateDisabledOptions();
        }

        // Function to remove member
        function removeMember(button) {
            const memberDiv = button.closest('.member-item');
            memberDiv.remove();
            
            updateDisabledOptions();
            saveToLocalStorage();
        }

        // Function to update disabled options based on selected leader and members
        function updateDisabledOptions() {
            const leaderId = document.getElementById('selected-leader-id').value;
            
            const selectedMemberIds = [];
            document.querySelectorAll('.member-select').forEach(select => {
                if (select.value && !select.disabled) {
                    selectedMemberIds.push(select.value);
                }
            });

            document.querySelectorAll('.member-select').forEach(select => {
                if (select.disabled) return;
                
                select.querySelectorAll('option').forEach(option => {
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

        // Function to save form data to localStorage
        function saveToLocalStorage() {
            const leaderId = document.getElementById('selected-leader-id').value;
            
            const memberSelects = document.querySelectorAll('.member-select');
            const memberIds = [];
            
            memberSelects.forEach(select => {
                if (select.value && !select.disabled) {
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
                    
                    if (data.leader) {
                        const userRow = document.querySelector(`.leader-radio[value="${data.leader}"]`);
                        if (userRow) {
                            const row = userRow.closest('tr');
                            const nameElement = row.querySelector('td:nth-child(3) .font-medium');
                            const name = nameElement ? nameElement.textContent.trim() : '';
                            const img = row.querySelector('img');
                            const photoSrc = img ? img.src : '';
                            selectLeader(data.leader, name, photoSrc);
                        }
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

        // Function to clear localStorage after submit
        function clearLocalStorage() {
            localStorage.removeItem('projectTeamData');
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const oldLeaderId = document.getElementById('selected-leader-id').value;
            if (oldLeaderId) {
                const selectedRadio = document.querySelector(`.leader-radio[value="${oldLeaderId}"]`);
                if (selectedRadio) {
                    const row = selectedRadio.closest('tr');
                    const nameElement = row.querySelector('td:nth-child(3) .font-medium');
                    const name = nameElement ? nameElement.textContent.trim() : '';
                    const img = row.querySelector('img');
                    const photoSrc = img ? img.src : '';
                    selectLeader(oldLeaderId, name, photoSrc);
                }
            }

            attachTableRowListeners();
            loadSavedData();

            document.getElementById('projectForm').addEventListener('submit', function() {
                clearLocalStorage();
            });

            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        applyFilters();
                    }
                });
            }

            document.addEventListener('click', function(e) {
                if (e.target.matches('.pagination a')) {
                    e.preventDefault();
                    const page = new URL(e.target.href).searchParams.get('page');
                    fetchFilteredUsers(page);
                }
            });
        });
    </script>

@endsection