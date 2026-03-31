@extends('Layout.Layout')
@section('title', 'Tambah Project Mahasiswa Baru')
@section('content')
    <div class="min-h-screen">
        <div class="p-8">
            <!-- Header -->
            <div class="mb-8 text-center md:text-left">
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100" data-translate="ttl_form" data-translate-page="dosen_add_pjt">Tambah Project Baru</h1>
                </div>
                <p class="mt-2 text-gray-600 dark:text-gray-200">
                    <span data-translate="desc_form" data-translate-page="dosen_add_pjt">Tambah Projek Yang Pernah Kamu Buat.</span>
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
            <form method="POST" action="{{ route('dosen.projects.store') }}" class="space-y-7" id="projectForm">
                @csrf

                <!-- Search and Filter Section -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4" data-translate="filter" data-translate-page="dosen_add_pjt">Filter Mahasiswa</h3>
                    
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
                            <label data-translate="agkt" data-translate-page="dosen_add_pjt" for="angkatan-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Angkatan
                            </label>
                            <select id="angkatan-filter"
                                class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition">
                                <option data-translate="all_agkt" data-translate-page="dosen_add_pjt" value="">Semua Angkatan</option>
                                @foreach($angkatans as $angk)
                                    <option value="{{ $angk->id }}" {{ ($angkatan ?? '') == $angk->id ? 'selected' : '' }}>
                                        {{ $angk->nama_angkatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Jurusan -->
                        <div>
                            <label data-translate="jrs" data-translate-page="dosen_add_pjt" for="jurusan-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jurusan
                            </label>
                            <select id="jurusan-filter"
                                class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition">
                                <option data-translate="all_jrs" data-translate-page="dosen_add_pjt" value="">Semua Jurusan</option>
                                @foreach($jurusans as $jrs)
                                    <option value="{{ $jrs->id }}" {{ ($jurusan ?? '') == $jrs->id ? 'selected' : '' }}>
                                        {{ $jrs->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Keahlian -->
                        <div>
                            <label data-translate="khl" data-translate-page="dosen_add_pjt" for="keahlian-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Keahlian
                            </label>
                            <select id="keahlian-filter"
                                class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition">
                                <option data-translate="all_khl" data-translate-page="dosen_add_pjt" value="">Semua Keahlian</option>
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
                        <a href="{{ route('dosen.projects.create') }}" 
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                           Reset Filter
                        </a>
                        <button type="button" onclick="applyFilters()"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <span data-translate="trp_filter" data-translate-page="dosen_add_pjt">Terapkan Filter</span>
                        </button>
                    </div>
                </div>

                <!-- Nama Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="nm_pjt" data-translate-page="dosen_add_pjt">Nama Project</span> <span class="text-red-500">*</span>
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
                        <span data-translate="desc_pjt" data-translate-page="dosen_add_pjt">Deskripsi (opsional)</span>
                    </label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('deskripsi') border-red-500 @enderror"
                        placeholder="Deskripsikan project Anda...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Owner Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Pilih Owner Project <span class="text-red-500">*</span>
                    </label>
                    <div id="selected-owner-display" class="mb-4 hidden">
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center justify-between gap-3">
                                <div class="font-medium text-blue-800" id="selected-owner-name"></div>
                                <button type="button" onclick="clearSelectedOwner()" class="text-blue-600 hover:text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="owner" id="selected-owner-id" value="{{ old('owner') }}">
                    @error('owner')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pemimpin Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="select_leader" data-translate-page="dosen_add_pjt">Pilih Mahasiswa (Pemimpin Project)</span> <span class="text-red-500">*</span>
                    </label>
                    
                    <div id="selected-leader-display" class="mb-4 hidden">
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
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

                    <!-- Search untuk Pemimpin -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="leader-search" placeholder="Cari nama pemimpin project..."
                                   class="w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 outline-none transition text-sm">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Table of Users for Leader -->
                    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Owner
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Leader
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <span data-translate="tbl_nm" data-translate-page="dosen_add_pjt">Nama Mahasiswa</span> 
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <span data-translate="tbl_agkt" data-translate-page="dosen_add_pjt">Angkatan</span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <span data-translate="tbl_jrs" data-translate-page="dosen_add_pjt">Jurusan</span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <span data-translate="tbl_skill" data-translate-page="dosen_add_pjt">Keahlian</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="leader-table-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($users as $user)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-6 py-4">
                                            <input type="radio" 
                                                   name="owner_radio" 
                                                   value="{{ $user->id }}"
                                                   class="owner-radio w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                                   {{ old('owner') == $user->id ? 'checked' : '' }}
                                                   onchange="selectOwner({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa) }}')">
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="radio" 
                                                   name="leader_radio" 
                                                   value="{{ $user->id }}"
                                                   class="leader-radio w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                                   {{ old('leader') == $user->id ? 'checked' : '' }}
                                                   onchange="selectLeader({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa) }}')">
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">
                                            {{ $user->nama_mahasiswa }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $user->angkatan->nama_angkatan ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $user->jurusan->nama_jurusan ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $user->keahlian->nama_keahlian ?? '-'}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>
                                                <p class="text-lg font-medium"><span data-translate="empty_filter" data-translate-page="dosen_add_pjt"></span>Tidak ada mahasiswa ditemukan</p>
                                                <p class="text-sm"><span data-translate="empty_filter_desc" data-translate-page="dosen_add_pjt"></span>Coba ubah filter pencarian Anda</p>
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
                    <label data-translate="add_partner" data-translate-page="dosen_add_pjt" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Tambah Rekan (opsional)
                    </label>

                    <div id="members-container">
                        <!-- Member items will be dynamically added here -->
                    </div>

                    <button type="button"
                        onclick="addMemberSelect()"
                        class="mt-3 text-sm text-indigo-600 dark:text-indigo-400 hover:cursor-pointer hover:underline">
                        <span data-translate="add_partner_btn" data-translate-page="dosen_add_pjt">+ Tambah Rekan</span>
                    </button>
                </div>

                <!-- Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            <span data-translate="date_start" data-translate-page="dosen_add_pjt">Tanggal Mulai</span> <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                            class="w-full px-4 py-3 border dark:text-white dark:bg-gray-700 dark:border-gray-600 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_mulai') border-red-500 @enderror">
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
<<<<<<< HEAD
                            <span data-translate="date_end" data-translate-page="dosen_add_pjt"></span>
=======
                            <span data-translate="date_end" data-translate-page="dosen_add_pjt">Tanggal Akhir</span> (opsional)
>>>>>>> 7d6373dded44c5fd933cf8d7c3dba60e33b32deb
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
                        <span data-translate="link_pjt" data-translate-page="dosen_add_pjt">Link Project</span> (opsional)
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
                    <label data-translate="link_github" data-translate-page="dosen_add_pjt" for="link_github" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Link GitHub
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
                    <label data-translate="link_vid" data-translate-page="dosen_add_pjt" for="link_video" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Link Video
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
                    <a href="{{ route('dosen.projects.index') }}"
                        class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        <span data-translate="cancel" data-translate-page="dosen_add_pjt"></span>
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md">
                        <span data-translate="save_pjt" data-translate-page="dosen_add_pjt"></span>
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
            const url = new URL('{{ route("dosen.projects.create") }}');
            url.searchParams.set('search', currentFilters.search);
            url.searchParams.set('angkatan', currentFilters.angkatan);
            url.searchParams.set('jurusan', currentFilters.jurusan);
            url.searchParams.set('keahlian', currentFilters.keahlian);
            url.searchParams.set('page', page);
            
            // Use fetch to get filtered results
            fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Parse the HTML response
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Update leader table
                const newLeaderBody = doc.querySelector('#leader-table-body');
                if (newLeaderBody) {
                    document.getElementById('leader-table-body').innerHTML = newLeaderBody.innerHTML;
                }
                
                // Update pagination
                const newPagination = doc.querySelector('#pagination-links');
                if (newPagination) {
                    document.getElementById('pagination-links').innerHTML = newPagination.innerHTML;
                }

                // Restore selected owner if any
                const selectedOwnerId = document.getElementById('selected-owner-id').value;
                if (selectedOwnerId) {
                    const selectedOwnerRadio = document.querySelector(`.owner-radio[value="${selectedOwnerId}"]`);
                    if (selectedOwnerRadio) {
                        const row = selectedOwnerRadio.closest('tr');
                        const ownerName = row.querySelector('td:nth-child(3)').textContent.trim();
                        selectOwner(selectedOwnerId, ownerName);
                    }
                }

                // Restore selected leader if any
                const selectedLeaderId = document.getElementById('selected-leader-id').value;
                if (selectedLeaderId) {
                    const selectedRadio = document.querySelector(`.leader-radio[value="${selectedLeaderId}"]`);
                    if (selectedRadio) {
                        const row = selectedRadio.closest('tr');
                        const leaderName = row.querySelector('td:nth-child(3)').textContent.trim();
                        selectLeader(selectedLeaderId, leaderName);
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
                    row.onclick = () => selectLeader(radio.value, row.querySelector('td:nth-child(3)').textContent.trim());
                }
            });
        }

        function filterLeaderTable() {
            const keyword = document.getElementById('leader-search').value.toLowerCase().trim();
            document.querySelectorAll('#leader-table-body tr').forEach(row => {
                const nameCell = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                row.style.display = nameCell.includes(keyword) ? '' : 'none';
            });
        }

        // Function to select leader
        function selectLeader(userId, userName) {
            // Clear owner selection first to prevent conflict
            if (document.getElementById('selected-owner-id').value && document.getElementById('selected-owner-id').value === userId) {
                clearSelectedOwner();
            }
            
            // Update hidden input
            document.getElementById('selected-leader-id').value = userId;
            
            // Update radio buttons
            document.querySelectorAll('.leader-radio').forEach(radio => {
                radio.checked = (radio.value == userId);
            });
            
            // Update display
            const display = document.getElementById('selected-leader-display');
            const nameSpan = document.getElementById('selected-leader-name');
            
            if (userId) {
                nameSpan.textContent = 'Pemimpin: ' + userName;
                display.classList.remove('hidden');
            } else {
                display.classList.add('hidden');
            }

            // Update disabled options
            updateDisabledOptions();
            
            // Save to localStorage
            saveToLocalStorage();
        }

        // Function to clear selected leader
        function clearSelectedLeader() {
            document.getElementById('selected-leader-id').value = '';
            document.querySelectorAll('.leader-radio').forEach(radio => {
                radio.checked = false;
            });
            document.getElementById('selected-leader-display').classList.add('hidden');
            
            // Update disabled options
            updateDisabledOptions();
            
            // Save to localStorage
            saveToLocalStorage();
        }

        // Function to select owner
        function selectOwner(userId, userName) {
            // Clear leader selection first to prevent conflict
            if (document.getElementById('selected-leader-id').value && document.getElementById('selected-leader-id').value === userId) {
                clearSelectedLeader();
            }
            
            document.getElementById('selected-owner-id').value = userId;
            document.querySelectorAll('.owner-radio').forEach(radio => {
                radio.checked = (radio.value == userId);
            });

            const display = document.getElementById('selected-owner-display');
            const nameSpan = document.getElementById('selected-owner-name');
            if (userId) {
                nameSpan.textContent = 'Owner: ' + userName;
                display.classList.remove('hidden');
            } else {
                display.classList.add('hidden');
            }

            updateDisabledOptions();
            saveToLocalStorage();
        }

        // Function to clear selected owner
        function clearSelectedOwner() {
            document.getElementById('selected-owner-id').value = '';
            document.querySelectorAll('.owner-radio').forEach(radio => {
                radio.checked = false;
            });
            document.getElementById('selected-owner-display').classList.add('hidden');
            
            updateDisabledOptions();
            saveToLocalStorage();
        }

        // Function to add new member select
        function addMemberSelect(savedValue = null) {
            const container = document.getElementById('members-container');

            const memberDiv = document.createElement('div');
            memberDiv.classList.add('member-item', 'mb-3');

            // Get all users from the current table
            const users = [];
            document.querySelectorAll('#leader-table-body tr').forEach(row => {
                const radio = row.querySelector('.leader-radio');
                if (radio) {
                    const name = row.querySelector('td:nth-child(3)').textContent.trim();
                    users.push({
                        id: radio.value,
                        name: name
                    });
                }
            });

            // If no users in table, use a default message
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
                    optionsHtml += `<option value="${user.id}" ${selected}>${user.name}</option>`;
                });

                memberDiv.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <div class="relative mb-3">
                            <input type="text" class="member-search w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm" placeholder="Cari nama rekan...">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <select name="members[]" 
                                class="member-select w-full p-3 border border-gray-300 dark:text-white dark:bg-gray-700 dark:border-gray-700 rounded-lg">
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
            }

            container.appendChild(memberDiv);

            // Add change listener to new select
            const newSelect = memberDiv.querySelector('.member-select');
            if (newSelect && !newSelect.disabled) {
                newSelect.addEventListener('change', function() {
                    updateDisabledOptions();
                    saveToLocalStorage();
                });
            }
            // Add search listener to new member row
            const newSearchInput = memberDiv.querySelector('.member-search');
            if (newSearchInput) {
                newSearchInput.addEventListener('input', function() {
                    const keyword = this.value.toLowerCase().trim();
                    const select = memberDiv.querySelector('.member-select');
                    Array.from(select.options).forEach(option => {
                        if (option.value === '') return;
                        option.style.display = option.textContent.toLowerCase().includes(keyword) ? '' : 'none';
                    });
                });
            }
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

        // Function to update disabled options based on selected leader, owner and members
        function updateDisabledOptions() {
            const leaderId = document.getElementById('selected-leader-id').value;
            const ownerId = document.getElementById('selected-owner-id').value;
            
            // Get all selected member IDs
            const selectedMemberIds = [];
            document.querySelectorAll('.member-select').forEach(select => {
                if (select.value && !select.disabled) {
                    selectedMemberIds.push(select.value);
                }
            });

            // Update all member selects
            document.querySelectorAll('.member-select').forEach(select => {
                if (select.disabled) return;
                
                // Enable all options first
                select.querySelectorAll('option').forEach(option => {
                    option.disabled = false;
                });

                // Disable leader option if leader is selected
                if (leaderId) {
                    let leaderOption = select.querySelector(`option[value="${leaderId}"]`);
                    if (leaderOption) {
                        leaderOption.disabled = true;
                    }
                }

                // Disable owner option if owner is selected
                if (ownerId) {
                    let ownerOption = select.querySelector(`option[value="${ownerId}"]`);
                    if (ownerOption) {
                        ownerOption.disabled = true;
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

        // Function to save form data to localStorage
        function saveToLocalStorage() {
            const ownerId = document.getElementById('selected-owner-id').value;
            const leaderId = document.getElementById('selected-leader-id').value;
            
            // Get all member selects
            const memberSelects = document.querySelectorAll('.member-select');
            const memberIds = [];
            
            memberSelects.forEach(select => {
                if (select.value && !select.disabled) {
                    memberIds.push(select.value);
                }
            });

            const projectData = {
                owner: ownerId,
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
                    
                    // Set owner
                    if (data.owner) {
                        const ownerRadio = document.querySelector(`.owner-radio[value="${data.owner}"]`);
                        if (ownerRadio) {
                            const ownerRow = ownerRadio.closest('tr');
                            const ownerName = ownerRow.querySelector('td:nth-child(3)').textContent.trim();
                            selectOwner(data.owner, ownerName);
                        }
                    }

                    // Set leader
                    if (data.leader) {
                        const leaderRadio = document.querySelector(`.leader-radio[value="${data.leader}"]`);
                        if (leaderRadio) {
                            const leaderRow = leaderRadio.closest('tr');
                            const leaderName = leaderRow.querySelector('td:nth-child(3)').textContent.trim();
                            selectLeader(data.leader, leaderName);
                        }
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

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there's an old selected owner
            const oldOwnerId = document.getElementById('selected-owner-id').value;
            if (oldOwnerId) {
                const selectedRadio = document.querySelector(`.owner-radio[value="${oldOwnerId}"]`);
                if (selectedRadio) {
                    const row = selectedRadio.closest('tr');
                    const userName = row.querySelector('td:nth-child(3)').textContent.trim();
                    selectOwner(oldOwnerId, userName);
                }
            }

            // Check if there's an old selected leader
            const oldLeaderId = document.getElementById('selected-leader-id').value;
            if (oldLeaderId) {
                const selectedRadio = document.querySelector(`.leader-radio[value="${oldLeaderId}"]`);
                if (selectedRadio) {
                    const row = selectedRadio.closest('tr');
                    const userName = row.querySelector('td:nth-child(3)').textContent.trim();
                    selectLeader(oldLeaderId, userName);
                }
            }

            // Load saved data from localStorage
            loadSavedData();

            // Add submit event listener to form
            document.getElementById('projectForm').addEventListener('submit', function() {
                clearLocalStorage();
            });

            // Enter key for search
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        applyFilters();
                    }
                });
            }

            const leaderSearch = document.getElementById('leader-search');
            if (leaderSearch) {
                leaderSearch.addEventListener('input', filterLeaderTable);
            }

            // Attach table row listeners
            attachTableRowListeners();
        });
    </script>

    <style>
        /* Table row hover effect */
        tbody tr {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        tbody tr:hover {
            background-color: rgba(99, 102, 241, 0.05);
        }

        .dark tbody tr:hover {
            background-color: rgba(99, 102, 241, 0.1);
        }

        /* Select styling */
        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        select:focus {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%234F46E5' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
        }

        /* Dark mode select */
        .dark select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%23E5E7EB' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
        }

        .dark select:focus {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%238181F2' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
        }

        /* Pagination styling */
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
        }

        .pagination li {
            margin: 0 2px;
        }

        .pagination li a,
        .pagination li span {
            display: inline-block;
            padding: 0.5rem 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .dark .pagination li a,
        .dark .pagination li span {
            border-color: #4b5563;
            color: #e5e7eb;
            background-color: #374151;
        }

        .pagination li.active span {
            background-color: #4f46e5;
            border-color: #4f46e5;
            color: white;
        }

        .pagination li a:hover {
            background-color: #f3f4f6;
        }

        .dark .pagination li a:hover {
            background-color: #4b5563;
        }
    </style>
@endsection