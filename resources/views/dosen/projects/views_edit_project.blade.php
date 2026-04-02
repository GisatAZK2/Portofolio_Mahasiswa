@extends('Layout.Layout')
@section('title', 'Edit Project Mahasiswa')
@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
        <div class="p-4 md:p-8 max-w-7xl mx-auto">
           
            <!-- Header -->
            <div class="mb-6 md:mb-8 text-center md:text-left">
                <div class="flex items-center gap-3 mb-2 justify-center md:justify-start">
                    <h1 data-translate="title_edit" data-translate-page="admin" class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">
                        Edit Project
                    </h1>
                </div>
                <p data-translate="desc_edit" data-translate-page="admin" class="mt-2 text-gray-600 dark:text-gray-400 text-sm md:text-base max-w-md mx-auto md:mx-0">
                    Edit informasi project atau ganti file jika diperlukan. Pastikan untuk menyimpan perubahan setelah selesai.
                </p>
            </div>
            
            <!-- Error Global -->
            @if ($errors->any())
                <div class="mb-6 md:mb-8 p-4 md:p-5 bg-red-50 border border-red-200 text-red-700 rounded-2xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
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
            <form method="POST" action="{{ route('dosen.projects.update', $project->id) }}" class="space-y-6 md:space-y-7" id="projectForm">
                @csrf
                @method('PATCH')
                
                <!-- Filter Section -->
                <div class="bg-white dark:bg-gray-800 p-5 md:p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4" data-translate="filter_mhs" data-translate-page="admin">Filter Mahasiswa</h3>
                   
                    <div class="mb-5">
                        <div class="relative">
                            <input type="text" id="search-input" placeholder="Cari nama mahasiswa..." value="{{ $search ?? '' }}"
                                   class="w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 outline-none transition text-sm md:text-base">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label for="angkatan-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" data-translate="agkt_addusr" data-translate-page="admin">Angkatan</label>
                            <select id="angkatan-filter" class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 outline-none transition text-sm">
                                <option data-translate="all_cohorts" data-translate-page="admin" value="">Semua Angkatan</option>
                                @foreach($angkatans as $angk)
                                    <option value="{{ $angk->id }}" {{ ($angkatan ?? '') == $angk->id ? 'selected' : '' }}>{{ $angk->nama_angkatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="jurusan-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" data-translate="jrs_addusr" data-translate-page="admin">Jurusan</label>
                            <select id="jurusan-filter" class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 outline-none transition text-sm">
                                <option data-translate="all_jrs" data-translate-page="admin" value="">Semua Jurusan</option>
                                @foreach($jurusans as $jrs)
                                    <option value="{{ $jrs->id }}" {{ ($jurusan ?? '') == $jrs->id ? 'selected' : '' }}>{{ $jrs->nama_jurusan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="keahlian-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" data-translate="all_skill" data-translate-page="admin">Keahlian</label>
                            <select id="keahlian-filter" class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 outline-none transition text-sm">
                                <option data-translate="all_skill" data-translate-page="admin" value="">Semua Keahlian</option>
                                @foreach($keahlians as $keahlianItem)
                                    <option value="{{ $keahlianItem->id }}" {{ ($keahlian ?? '') == $keahlianItem->id ? 'selected' : '' }}>{{ $keahlianItem->nama_keahlian }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 justify-end mt-6">
                        <a href="{{ route('dosen.projects.details', $project->id) }}" class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition text-center">
                            Reset Filter
                        </a>
                        <button data-translate="trp_filter" data-translate-page="admin" type="button" onclick="applyFilters()" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
                
                <!-- Nama Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="nm_project" data-translate-page="admin">Nama Project</span> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_project" value="{{ old('nama_project', $project->isi_content['nama_project'] ?? '') }}" required
                        class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('nama_project') border-red-500 @enderror"
                        placeholder="Contoh: Website Portfolio Pribadi">
                    @error('nama_project')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="desc_project" data-translate-page="admin">Deskripsi (opsional)</span>
                    </label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('deskripsi') border-red-500 @enderror"
                        placeholder="Deskripsikan project Anda...">{{ old('deskripsi', $project->isi_content['deskripsi'] ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Pemilik Project dengan Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">
                        Pemilik Project (Owner) <span class="text-red-500">*</span>
                    </label>
                    <!-- Selected Owner Display -->
                    <div id="selected-owner-display" class="mb-4 {{ old('owner', $project->id_mahasiswa) ? '' : 'hidden' }}">
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-2xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3" id="selected-owner-content"></div>
                                <button type="button" onclick="clearSelectedOwner()" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 p-1 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="owner" id="selected-owner-id" value="{{ old('owner', $project->id_mahasiswa) }}">
                    
                    <!-- Search untuk Owner -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="owner-search" placeholder="Cari nama owner project..."
                                   class="w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 outline-none transition text-sm">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-10">Pilih</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Mahasiswa</th>
                                        <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Angkatan</th>
                                        <th class="hidden lg:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jurusan</th>
                                        <th class="hidden xl:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Keahlian</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider text-center w-20">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="owner-table-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($users as $user)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer group"
                                            onclick="selectOwner({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa) }}', '{{ $user->photo_profile ?? '' }}')">
                                            <td class="px-4 py-4">
                                                <input type="radio" name="owner_radio" value="{{ $user->id }}"
                                                       class="owner-radio w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                                       {{ old('owner', $project->id_mahasiswa) == $user->id ? 'checked' : '' }}
                                                       onchange="event.stopImmediatePropagation(); selectOwner({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa) }}', '{{ $user->photo_profile ?? '' }}')">
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-shrink-0">
                                                        @if($user->photo_profile && file_exists(public_path('storage/' . $user->photo_profile)))
                                                            <img src="{{ asset('storage/' . $user->photo_profile) }}" class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-700" alt="{{ $user->nama_mahasiswa }}">
                                                        @else
                                                            <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center ring-2 ring-white dark:ring-gray-700">
                                                                <span class="text-indigo-600 dark:text-indigo-300 font-medium text-sm">{{ strtoupper(substr($user->nama_mahasiswa, 0, 2)) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ $user->nama_mahasiswa }}</div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="hidden md:table-cell px-4 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $user->angkatan->nama_angkatan ?? '-' }}</td>
                                            <td class="hidden lg:table-cell px-4 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $user->jurusan->nama_jurusan ?? '-' }}</td>
                                            <td class="hidden xl:table-cell px-4 py-4">
                                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">{{ $user->keahlian->nama_keahlian ?? '-' }}</span>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <a href="{{ route('portfolio.show', $user->id) }}" onclick="event.stopImmediatePropagation()" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium inline-block">Lihat</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-14 h-14 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                    </svg>
                                                    <p class="font-medium">Tidak ada mahasiswa ditemukan</p>
                                                    <p class="text-sm mt-1">Coba ubah filter pencarian Anda</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-5" id="pagination-links-owner">
                        {{ $users->links() }}
                    </div>
                    @error('owner')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Pemimpin Project dengan Search -->
                <div id="leader-section">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">
                        <span data-translate="leader_project" data-translate-page="admin">Pilih Mahasiswa (Pemimpin Project)</span> <span class="text-red-500">*</span>
                    </label>
                    <!-- Selected Leader Display -->
                    <div id="selected-leader-display" class="mb-4 {{ old('leader', $project->leader_id) ? '' : 'hidden' }}">
                        <div class="p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-2xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3" id="selected-leader-content"></div>
                                <button type="button" onclick="clearSelectedLeader()" class="text-green-600 dark:text-green-400 hover:text-green-800 p-1 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="leader" id="selected-leader-id" value="{{ old('leader', $project->leader_id) }}">
                    
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
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-10">Pilih</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Mahasiswa</th>
                                        <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Angkatan</th>
                                        <th class="hidden lg:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jurusan</th>
                                        <th class="hidden xl:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Keahlian</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider text-center w-20">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="leader-table-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($users as $user)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer group"
                                            onclick="selectLeader({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa) }}', '{{ $user->photo_profile ?? '' }}')">
                                            <td class="px-4 py-4">
                                                <input type="radio" name="leader_radio" value="{{ $user->id }}"
                                                       class="leader-radio w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                                       {{ old('leader', $project->leader_id) == $user->id ? 'checked' : '' }}
                                                       onchange="event.stopImmediatePropagation(); selectLeader({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa) }}', '{{ $user->photo_profile ?? '' }}')">
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-shrink-0">
                                                        @if($user->photo_profile && file_exists(public_path('storage/' . $user->photo_profile)))
                                                            <img src="{{ asset('storage/' . $user->photo_profile) }}" class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-700" alt="{{ $user->nama_mahasiswa }}">
                                                        @else
                                                            <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center ring-2 ring-white dark:ring-gray-700">
                                                                <span class="text-indigo-600 dark:text-indigo-300 font-medium text-sm">{{ strtoupper(substr($user->nama_mahasiswa, 0, 2)) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ $user->nama_mahasiswa }}</div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="hidden md:table-cell px-4 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $user->angkatan->nama_angkatan ?? '-' }}</td>
                                            <td class="hidden lg:table-cell px-4 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $user->jurusan->nama_jurusan ?? '-' }}</td>
                                            <td class="hidden xl:table-cell px-4 py-4">
                                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">{{ $user->keahlian->nama_keahlian ?? '-' }}</span>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <a href="{{ route('portfolio.show', $user->id) }}" onclick="event.stopImmediatePropagation()" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium inline-block">Lihat</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-14 h-14 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                    </svg>
                                                    <p class="font-medium">Tidak ada mahasiswa ditemukan</p>
                                                    <p class="text-sm mt-1">Coba ubah filter pencarian Anda</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-5" id="pagination-links-leader">
                        {{ $users->links() }}
                    </div>
                    @error('leader')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Collaborative Project Toggle -->
                <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-4 md:p-5 rounded-2xl border border-gray-200 dark:border-gray-700 mb-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-800 dark:text-gray-200">Mode Kolaboratif</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Off = project owner only, leader + member akan dihapus dan tugas otomatis disesuaikan.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="collaborative-status" class="text-sm font-semibold text-green-600 dark:text-green-300">{{ old('is_collaborative', $project->is_collaborative ?? 1) ? 'On' : 'Off' }}</span>
                        <button type="button" onclick="toggleCollaborativeMode()" id="collaborative-toggle" class="px-3 py-1 rounded-lg bg-indigo-600 text-white text-sm">Switch</button>
                    </div>
                </div>
                <input type="hidden" name="is_collaborative" id="collaborative-input" value="{{ old('is_collaborative', $project->is_collaborative ?? 1) ? 1 : 0 }}">
                
                <!-- Rekan Project -->
                <div id="member-wrapper">
                    <label data-translate="partner_project" data-translate-page="admin" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">
                        Tambah Rekan (opsional)
                    </label>
                    <div id="members-container" class="space-y-3"></div>
                    <button type="button" onclick="addMemberSelect()" id="add-member-btn"
                            class="mt-4 text-indigo-600 dark:text-indigo-400 hover:underline text-sm font-medium flex items-center gap-1">
                        <span class="text-xl">+</span> <span data-translate="add_partner" data-translate-page="admin">Tambah Rekan</span>
                    </button>
                </div>
                
                <!-- Tambah Tugas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">
                        Tambah Tugas (opsional)
                    </label>
                    <div id="tasks-container" class="space-y-4"></div>
                    <button type="button" onclick="addTaskRow()"
                        class="mt-3 text-sm text-indigo-600 dark:text-indigo-400 hover:cursor-pointer hover:underline flex items-center gap-1">
                        <span class="text-xl">+</span> Tambah Tugas
                    </button>
                    @error('tasks')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('tasks.*.user_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('tasks.*.name_task')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            <span data-translate="date_start" data-translate-page="admin">Tanggal Mulai</span> <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $project->tanggal_mulai->format('Y-m-d')) }}" required
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_mulai') border-red-500 @enderror">
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            <span data-translate="date_end" data-translate-page="admin">Tanggal Selesai</span> (opsional)
                        </label>
                        <input type="date" name="tanggal_akhir" value="{{ old('tanggal_akhir', $project->tanggal_akhir?->format('Y-m-d') ?? '') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_akhir') border-red-500 @enderror">
                        @error('tanggal_akhir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Link Project -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2" data-translate="link_project_opsional" data-translate-page="project_create">Link Project (opsional)</label>
                    <input type="url" name="link_project" value="{{ old('link_project', $project->isi_content['link_project'] ?? '') }}"
                        class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_project') border-red-500 @enderror"
                        placeholder="https://example.com/project">
                    @error('link_project')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Link GitHub & Video -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2" data-translate="link_github_opsional" data-translate-page="project_create">Link GitHub (opsional)</label>
                        <input type="url" name="link_github" maxlength="500" value="{{ old('link_github', $project->isi_content['link_github'] ?? '') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_github') border-red-500 @enderror"
                            placeholder="https://github.com/username/repo">
                        @error('link_github')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2" data-translate="link_video_opsional" data-translate-page="project_create">Link Video (YouTube, opsional)</label>
                        <input type="url" name="link_video" maxlength="500" value="{{ old('link_video', $project->isi_content['link_video'] ?? '') }}"
                            class="w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_video') border-red-500 @enderror"
                            placeholder="https://www.youtube.com/watch?v=...">
                        @error('link_video')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex-1"></div>
                   
                    <a href="{{ route('dosen.projects.index') }}"
                       class="px-6 py-3.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition text-center w-full sm:w-auto">
                        Batal
                    </a>
                   
                    <button type="submit"
                        class="px-8 py-3.5 bg-indigo-600 text-white font-medium rounded-2xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-md w-full sm:w-auto">
                        Update Project
                    </button>
                </div>
            </form>
        </div>
    </div>

    @php
        // Prepare existing tasks from old input or from the database
        $existingTasks = [];
        if (old('tasks')) {
            // Use old input if available (after validation error)
            $oldTasks = old('tasks');
            foreach ($oldTasks as $index => $task) {
                if (isset($task['user_id']) && isset($task['name_task']) && !empty(trim($task['name_task']))) {
                    $existingTasks[] = [
                        'id' => $task['id'] ?? null,
                        'user_id' => $task['user_id'],
                        'name_task' => trim($task['name_task']),
                    ];
                }
            }
        } else {
            // Otherwise load from database - hanya task yang masih valid
            $existingTasks = $project->tasks->filter(function($task) {
                return !empty($task->user_id) && !empty(trim($task->name_task ?? ''));
            })->map(function($task) {
                return [
                    'id' => $task->id,
                    'user_id' => $task->user_id,
                    'name_task' => trim($task->name_task ?? ''),
                ];
            })->toArray();
        }
    @endphp

    <script>
        let currentFilters = { search: '{{ $search ?? '' }}', angkatan: '{{ $angkatan ?? '' }}', jurusan: '{{ $jurusan ?? '' }}', keahlian: '{{ $keahlian ?? '' }}' };
        let isCollaborative = {{ old('is_collaborative', $project->is_collaborative ?? 1) ? 'true' : 'false' }};
        let taskIndex = 0;
        let removedTaskIds = new Set();

        // Utility functions
        function getRemovedTasksFromStorage() {
            try {
                const data = localStorage.getItem('projectRemovedTasks');
                return data ? JSON.parse(data) : [];
            } catch (e) {
                return [];
            }
        }

        function saveRemovedTasksToStorage() {
            localStorage.setItem('projectRemovedTasks', JSON.stringify(Array.from(removedTaskIds)));
        }

        function markTaskAsRemoved(taskId) {
            if (!taskId) return;
            removedTaskIds.add(String(taskId));
            saveRemovedTasksToStorage();
        }

        function applyFilters() {
            currentFilters.search = document.getElementById('search-input').value.trim();
            currentFilters.angkatan = document.getElementById('angkatan-filter').value;
            currentFilters.jurusan = document.getElementById('jurusan-filter').value;
            currentFilters.keahlian = document.getElementById('keahlian-filter').value;
            fetchFilteredUsers();
        }

        function fetchFilteredUsers(page = 1) {
            const url = new URL('{{ route("dosen.projects.details", $project->id) }}');
            url.searchParams.set('search', currentFilters.search);
            url.searchParams.set('angkatan', currentFilters.angkatan);
            url.searchParams.set('jurusan', currentFilters.jurusan);
            url.searchParams.set('keahlian', currentFilters.keahlian);
            url.searchParams.set('page', page);
            
            fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                   
                    // Update leader table
                    const newLeaderBody = doc.querySelector('#leader-table-body');
                    if (newLeaderBody) document.getElementById('leader-table-body').innerHTML = newLeaderBody.innerHTML;
                    
                    // Update owner table
                    const newOwnerBody = doc.querySelector('#owner-table-body');
                    if (newOwnerBody) document.getElementById('owner-table-body').innerHTML = newOwnerBody.innerHTML;
                    
                    // Update pagination
                    const newLeaderPagination = doc.querySelector('#pagination-links-leader');
                    if (newLeaderPagination) document.getElementById('pagination-links-leader').innerHTML = newLeaderPagination.innerHTML;
                    
                    const newOwnerPagination = doc.querySelector('#pagination-links-owner');
                    if (newOwnerPagination) document.getElementById('pagination-links-owner').innerHTML = newOwnerPagination.innerHTML;
                    
                    attachTableRowListeners();
                    
                    // Restore selected values
                    const selectedOwnerId = document.getElementById('selected-owner-id').value;
                    if (selectedOwnerId) {
                        const radio = document.querySelector(`.owner-radio[value="${selectedOwnerId}"]`);
                        if (radio) radio.checked = true;
                    }
                    
                    const selectedLeaderId = document.getElementById('selected-leader-id').value;
                    if (selectedLeaderId) {
                        const radio = document.querySelector(`.leader-radio[value="${selectedLeaderId}"]`);
                        if (radio) radio.checked = true;
                    }
                })
                .catch(err => console.error('Error:', err));
        }

        function attachTableRowListeners() {
            document.querySelectorAll('#leader-table-body tr, #owner-table-body tr').forEach(row => {
                const leaderRadio = row.querySelector('.leader-radio');
                const ownerRadio = row.querySelector('.owner-radio');
                const radio = leaderRadio || ownerRadio;
                if (radio) {
                    row.addEventListener('click', function(e) {
                        if (e.target.type !== 'radio') {
                            const nameEl = row.querySelector('td:nth-child(2) .font-medium');
                            const name = nameEl ? nameEl.textContent.trim() : '';
                            const img = row.querySelector('img');
                            const photoSrc = img ? img.src : '';
                            if (leaderRadio) {
                                selectLeader(radio.value, name, photoSrc);
                            } else if (ownerRadio) {
                                selectOwner(radio.value, name, photoSrc);
                            }
                        }
                    });
                }
            });
        }

        function filterLeaderTable() {
            const leaderKeyword = document.getElementById('leader-search') ? document.getElementById('leader-search').value.toLowerCase().trim() : '';
            document.querySelectorAll('#leader-table-body tr').forEach(row => {
                if (!row.querySelector('.leader-radio')) return;
                row.style.display = row.textContent.toLowerCase().includes(leaderKeyword) ? '' : 'none';
            });
            
            const ownerKeyword = document.getElementById('owner-search') ? document.getElementById('owner-search').value.toLowerCase().trim() : '';
            document.querySelectorAll('#owner-table-body tr').forEach(row => {
                if (!row.querySelector('.owner-radio')) return;
                row.style.display = row.textContent.toLowerCase().includes(ownerKeyword) ? '' : 'none';
            });
        }

        function selectLeader(userId, userName, photoProfile) {
            document.getElementById('selected-leader-id').value = userId;
            document.querySelectorAll('.leader-radio').forEach(radio => radio.checked = (radio.value == userId));
            const display = document.getElementById('selected-leader-display');
            const content = document.getElementById('selected-leader-content');
            let photoHtml = photoProfile && photoProfile !== 'null'
                ? `<img class="w-9 h-9 rounded-full object-cover ring-2 ring-green-200" src="${photoProfile}" alt="${userName}">`
                : `<div class="w-9 h-9 rounded-full bg-green-100 dark:bg-green-800 flex items-center justify-center">
                     <span class="text-green-700 dark:text-green-300 font-semibold">${userName.charAt(0).toUpperCase()}</span>
                   </div>`;
            content.innerHTML = `${photoHtml}<div class="font-medium text-green-800 dark:text-green-200">Pemimpin: ${userName}</div>`;
            display.classList.remove('hidden');
            updateDisabledOptions();
            saveToLocalStorage();
            syncTaskRows();
        }

        function clearSelectedLeader() {
            document.getElementById('selected-leader-id').value = '';
            document.querySelectorAll('.leader-radio').forEach(radio => radio.checked = false);
            document.getElementById('selected-leader-display').classList.add('hidden');
            updateDisabledOptions();
            saveToLocalStorage();
            syncTaskRows();
        }

        function selectOwner(userId, userName, photoProfile) {
            document.getElementById('selected-owner-id').value = userId;
            document.querySelectorAll('.owner-radio').forEach(radio => radio.checked = (radio.value == userId));
            const display = document.getElementById('selected-owner-display');
            const content = document.getElementById('selected-owner-content');
            let photoHtml = photoProfile && photoProfile !== 'null'
                ? `<img class="w-9 h-9 rounded-full object-cover ring-2 ring-blue-200" src="${photoProfile}" alt="${userName}">`
                : `<div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                     <span class="text-blue-700 dark:text-blue-300 font-semibold">${userName.charAt(0).toUpperCase()}</span>
                   </div>`;
            content.innerHTML = `${photoHtml}<div class="font-medium text-blue-800 dark:text-blue-200">Owner: ${userName}</div>`;
            display.classList.remove('hidden');
            
            if (!isCollaborative) {
                selectLeader(userId, userName, photoProfile);
            }
            
            updateDisabledOptions();
            updateTaskUserOptions();
            syncTaskRows();
        }

        function clearSelectedOwner() {
            document.getElementById('selected-owner-id').value = '';
            document.querySelectorAll('.owner-radio').forEach(radio => radio.checked = false);
            document.getElementById('selected-owner-display').classList.add('hidden');
            updateDisabledOptions();
            updateTaskUserOptions();
            syncTaskRows();
        }

        function addMemberSelect(savedValue = null) {
            const container = document.getElementById('members-container');
            const memberDiv = document.createElement('div');
            memberDiv.classList.add('member-item', 'mb-4');
            
            const users = [];
            document.querySelectorAll('#leader-table-body tr').forEach(row => {
                const radio = row.querySelector('.leader-radio');
                if (radio) {
                    const nameElement = row.querySelector('td:nth-child(2) .font-medium');
                    const fullName = nameElement ? nameElement.textContent.trim() : 'Nama Tidak Diketahui';
                    const img = row.querySelector('img');
                    const photoProfile = img ? img.src : '';
                    const initial = fullName.charAt(0).toUpperCase();
                    users.push({ id: radio.value, name: fullName, photoProfile: photoProfile, initial: initial });
                }
            });
            
            if (users.length === 0) {
                memberDiv.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl border border-gray-200 dark:border-gray-700">
                        <select class="member-select w-full p-4 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-2xl" disabled>
                            <option value="">Tidak ada mahasiswa tersedia</option>
                        </select>
                    </div>
                `;
            } else {
                let optionsHtml = '<option value="">-- Pilih Rekan Project --</option>';
                users.forEach(user => {
                    const selected = savedValue && savedValue == user.id ? 'selected' : '';
                    optionsHtml += `<option value="${user.id}" data-photo="${user.photoProfile || ''}" data-initial="${user.initial}" ${selected}>${user.name}</option>`;
                });
                memberDiv.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <div class="relative mb-4">
                            <input type="text" class="member-search w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm" placeholder="Cari nama rekan...">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex gap-3 items-center">
                            <div class="relative flex-1 w-full">
                                <select name="members[]" class="member-select w-full p-4 pl-14 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition text-base">
                                    ${optionsHtml}
                                </select>
                                <div class="member-photo absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <div class="w-9 h-9 rounded-2xl bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center overflow-hidden ring-2 ring-white dark:ring-gray-700">
                                        <span class="member-initial text-indigo-600 dark:text-indigo-400 font-semibold text-base"></span>
                                        <img class="member-img hidden w-full h-full object-cover rounded-2xl" src="" alt="">
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="removeMember(this)" class="px-6 py-4 bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900 rounded-2xl transition font-medium flex-shrink-0">✕</button>
                        </div>
                    </div>
                `;
                container.appendChild(memberDiv);
                
                const select = memberDiv.querySelector('.member-select');
                const searchInput = memberDiv.querySelector('.member-search');
                const initialSpan = memberDiv.querySelector('.member-initial');
                const img = memberDiv.querySelector('.member-img');
                
                searchInput.addEventListener('input', function() {
                    const keyword = this.value.toLowerCase().trim();
                    Array.from(select.options).forEach(option => {
                        if (option.value === '') return;
                        option.style.display = option.textContent.toLowerCase().includes(keyword) ? '' : 'none';
                    });
                });
                
                select.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (this.value) {
                        const photo = selectedOption.dataset.photo;
                        const initial = selectedOption.dataset.initial;
                        if (photo && photo !== '') {
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
                    syncTaskRows();
                });
                
                if (savedValue) {
                    select.value = savedValue;
                    select.dispatchEvent(new Event('change'));
                }
            }
            updateDisabledOptions();
            syncTaskRows();
        }

        function removeMember(button) {
            const memberDiv = button.closest('.member-item');
            if (memberDiv) memberDiv.remove();
            updateDisabledOptions();
            cleanupInvalidTaskRows();
            saveToLocalStorage();
            syncTaskRows();
        }

        function updateDisabledOptions() {
            const ownerId = document.getElementById('selected-owner-id').value || '';
            const leaderId = document.getElementById('selected-leader-id').value || '';
            const selectedMemberIds = [];
            
            document.querySelectorAll('.member-select').forEach(select => {
                if (select.value && !select.disabled) selectedMemberIds.push(select.value);
            });
            
            document.querySelectorAll('.member-select').forEach(select => {
                if (select.disabled) return;
                select.querySelectorAll('option').forEach(option => option.disabled = false);
                
                if (ownerId) {
                    const ownerOption = select.querySelector(`option[value="${ownerId}"]`);
                    if (ownerOption) ownerOption.disabled = true;
                }
                if (leaderId) {
                    const leaderOption = select.querySelector(`option[value="${leaderId}"]`);
                    if (leaderOption) leaderOption.disabled = true;
                }
                selectedMemberIds.forEach(selectedId => {
                    if (selectedId && select.value !== selectedId) {
                        const selectedOption = select.querySelector(`option[value="${selectedId}"]`);
                        if (selectedOption) selectedOption.disabled = true;
                    }
                });
            });
        }

        function saveToLocalStorage() {
            const ownerId = document.getElementById('selected-owner-id').value || '';
            const leaderId = document.getElementById('selected-leader-id').value || '';
            const memberIds = Array.from(document.querySelectorAll('.member-select'))
                .filter(s => s.value).map(s => s.value);
            localStorage.setItem('projectTeamData', JSON.stringify({ owner: ownerId, leader: leaderId, members: memberIds }));
        }

        function loadSavedData() {
            const savedData = localStorage.getItem('projectTeamData');
            const container = document.getElementById('members-container');
            container.innerHTML = '';
            
            if (savedData) {
                try {
                    const data = JSON.parse(savedData);
                    if (data.members && data.members.length > 0) {
                        data.members.forEach(id => addMemberSelect(id));
                    } else {
                        // Load existing members from database
                        const existingMembers = @json($project->members->pluck('id')->toArray());
                        if (existingMembers.length > 0) {
                            existingMembers.forEach(id => addMemberSelect(id));
                        } else {
                            addMemberSelect();
                        }
                    }
                } catch (e) {
                    console.error('Error loading saved data:', e);
                    loadDatabaseMembers();
                }
            } else {
                loadDatabaseMembers();
            }
            setTimeout(updateDisabledOptions, 150);
        }
        
        function loadDatabaseMembers() {
            const existingMembers = @json($project->members->pluck('id')->toArray());
            if (existingMembers.length > 0) {
                existingMembers.forEach(id => addMemberSelect(id));
            } else {
                addMemberSelect();
            }
        }

        function getAllowedTaskUsers() {
            const users = [];
            const added = new Set();
            
            const add = (id) => {
                if (!id || added.has(id)) return;
                added.add(id);
                const name = getUserNameById(id) || `User ${id}`;
                users.push({ id: id, name: name });
            };
            
            const ownerId = document.getElementById('selected-owner-id').value;
            if (ownerId) add(ownerId);
            
            if (isCollaborative) {
                const leaderId = document.getElementById('selected-leader-id').value;
                if (leaderId) add(leaderId);
                
                document.querySelectorAll('select[name="members[]"]').forEach(select => {
                    if (select.value) add(select.value);
                });
            }
            
            return users;
        }

        function getUserNameById(userId) {
            const radio = document.querySelector(`.leader-radio[value="${userId}"], .owner-radio[value="${userId}"]`);
            if (radio) {
                const row = radio.closest('tr');
                const nameEl = row ? row.querySelector('.font-medium') : null;
                if (nameEl) return nameEl.textContent.trim();
            }
            const option = document.querySelector(`select[name="members[]"] option[value="${userId}"]`);
            if (option) {
                return option.textContent.trim();
            }
            return null;
        }

        function renderTaskUserOptions(selectedId = '') {
            const users = getAllowedTaskUsers();
            let html = '<option value="">-- Pilih Penanggung Jawab --</option>';
            users.forEach(user => {
                const selected = String(user.id) === String(selectedId) ? ' selected' : '';
                html += `<option value="${user.id}"${selected}>${escapeHtml(user.name)}</option>`;
            });
            return html;
        }

        function escapeHtml(text) {
            if (!text) return '';
            return text.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            }).replace(/[\uD800-\uDBFF][\uDC00-\uDFFF]/g, function(c) {
                return c;
            });
        }

        function addTaskRow(taskData = null) {
            const container = document.getElementById('tasks-container');
            if (!container) return;
            
            const index = taskIndex++;
            const userId = taskData?.user_id ?? '';
            const taskName = taskData?.name_task ? escapeHtml(taskData.name_task) : '';
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
            if (select) {
                select.addEventListener('change', updateTaskUserOptions);
            }
            
            const nameInput = taskItem.querySelector('.task-name-input');
            if (nameInput) {
                nameInput.addEventListener('input', function() {
                    if (this.value.trim() === '') {
                        this.classList.add('border-red-500');
                    } else {
                        this.classList.remove('border-red-500');
                    }
                });
            }
        }

        function removeTaskRow(button) {
            const taskItem = button.closest('.task-item');
            if (!taskItem) return;

            const hiddenIdInput = taskItem.querySelector('input[name^="tasks"][name$="[id]"]');
            
            if (hiddenIdInput && hiddenIdInput.value) {
                markTaskAsRemoved(hiddenIdInput.value);
            }

            taskItem.remove();
            updateTaskUserOptions();

            if (document.querySelectorAll('.task-item').length === 0) {
                addTaskRow();
            }

            setTimeout(syncTaskRows, 50);
        }

        function syncTaskRows() {
            const allowedUsers = getAllowedTaskUsers();
            const currentTaskItems = document.querySelectorAll('.task-item');

            // Hapus tugas yang usernya sudah tidak ada di team
            currentTaskItems.forEach(item => {
                const select = item.querySelector('.task-user-select');
                if (select && select.value) {
                    const isStillAllowed = allowedUsers.some(u => String(u.id) === select.value);
                    if (!isStillAllowed) {
                        const hiddenId = item.querySelector('input[name$="[id]"]');
                        if (hiddenId && hiddenId.value) {
                            markTaskAsRemoved(hiddenId.value);
                        }
                        item.remove();
                    }
                }
            });

            // Tambahkan tugas untuk user yang belum punya tugas
            allowedUsers.forEach(user => {
                const userIdStr = String(user.id);
                
                const alreadyHasTask = Array.from(document.querySelectorAll('.task-item')).some(item => {
                    const sel = item.querySelector('.task-user-select');
                    return sel && sel.value === userIdStr;
                });

                if (!alreadyHasTask) {
                    addTaskRow({ user_id: user.id, name_task: '' });
                }
            });

            if (document.querySelectorAll('.task-item').length === 0) {
                addTaskRow();
            }

            updateTaskUserOptions();
        }

        function cleanupInvalidTaskRows() {
            syncTaskRows();
        }

        function updateTaskUserOptions() {
            document.querySelectorAll('.task-user-select').forEach(select => {
                const currentValue = select.value;
                select.innerHTML = renderTaskUserOptions(currentValue);
                if (currentValue && Array.from(select.options).some(opt => opt.value === currentValue)) {
                    select.value = currentValue;
                }
            });
        }

        function toggleCollaborativeMode() {
            setCollaborativeMode(!isCollaborative);
        }

        function setCollaborativeMode(enabled) {
            isCollaborative = enabled;
            document.getElementById('collaborative-input').value = enabled ? 1 : 0;
            document.getElementById('collaborative-status').textContent = enabled ? 'On' : 'Off';
            const leaderSection = document.getElementById('leader-section');
            const memberWrapper = document.getElementById('member-wrapper');
            const addMemberBtn = document.getElementById('add-member-btn');
            
            if (!enabled) {
                if (leaderSection) leaderSection.style.display = 'none';
                if (memberWrapper) memberWrapper.style.display = 'none';
                if (addMemberBtn) addMemberBtn.style.display = 'none';
                
                const ownerId = document.getElementById('selected-owner-id').value;
                if (ownerId) {
                    const ownerName = getUserNameById(ownerId);
                    if (ownerName) {
                        selectLeader(ownerId, ownerName, '');
                    }
                }
                document.querySelectorAll('.member-item').forEach(item => item.remove());
            } else {
                if (leaderSection) leaderSection.style.display = 'block';
                if (memberWrapper) memberWrapper.style.display = 'block';
                if (addMemberBtn) addMemberBtn.style.display = 'flex';
            }
            updateDisabledOptions();
            syncTaskRows();
        }

        function initializeTaskRows(existingTasks = []) {
            taskIndex = 0;
            const container = document.getElementById('tasks-container');
            if (!container) return;
            
            container.innerHTML = '';
            
            const savedRemoved = getRemovedTasksFromStorage();
            removedTaskIds = new Set(savedRemoved.map(String));
            
            const filteredTasks = Array.isArray(existingTasks)
                ? existingTasks.filter(task => {
                    if (!task || !task.user_id || !task.name_task) return false;
                    if (task.id && removedTaskIds.has(String(task.id))) {
                        return false;
                    }
                    return true;
                })
                : [];
            
            if (filteredTasks.length > 0) {
                filteredTasks.forEach(task => {
                    addTaskRow(task);
                });
            } else {
                addTaskRow();
            }
            
            setTimeout(() => {
                syncTaskRows();
            }, 100);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const oldOwnerId = document.getElementById('selected-owner-id').value;
            if (oldOwnerId) {
                const radio = document.querySelector(`.owner-radio[value="${oldOwnerId}"]`);
                if (radio) {
                    const row = radio.closest('tr');
                    const nameEl = row.querySelector('td:nth-child(2) .font-medium');
                    const name = nameEl ? nameEl.textContent.trim() : '';
                    const img = row.querySelector('img');
                    const photo = img ? img.src : '';
                    selectOwner(oldOwnerId, name, photo);
                }
            }
            
            const oldLeaderId = document.getElementById('selected-leader-id').value;
            if (oldLeaderId) {
                const radio = document.querySelector(`.leader-radio[value="${oldLeaderId}"]`);
                if (radio) {
                    const row = radio.closest('tr');
                    const nameEl = row.querySelector('td:nth-child(2) .font-medium');
                    const name = nameEl ? nameEl.textContent.trim() : '';
                    const img = row.querySelector('img');
                    const photo = img ? img.src : '';
                    selectLeader(oldLeaderId, name, photo);
                }
            }
            
            setCollaborativeMode(isCollaborative);
            attachTableRowListeners();
            loadSavedData();
            initializeTaskRows(@json($existingTasks));
            
            // Add form submit validation
           // GANTI BAGIAN INI di dalam document.addEventListener('DOMContentLoaded'...
document.getElementById('projectForm').addEventListener('submit', function(e) {
    let hasError = false;
    const taskItems = document.querySelectorAll('.task-item');

    taskItems.forEach(item => {
        const nameInput = item.querySelector('.task-name-input');
        const userSelect = item.querySelector('.task-user-select');

        const nameValue = nameInput ? nameInput.value.trim() : '';
        const userValue = userSelect ? userSelect.value : '';

        // Jika salah satu kosong → anggap tugas ini "kosong" dan harus dihapus
        if (!nameValue || !userValue) {
            // Hapus otomatis tugas yang kosong sebelum submit
            item.remove();
        } else {
            // Tugas valid → pastikan tidak ada class error
            if (nameInput) nameInput.classList.remove('border-red-500');
            if (userSelect) userSelect.classList.remove('border-red-500');
        }
    });

    // Setelah membersihkan tugas kosong, cek lagi apakah masih ada tugas
    const remainingTasks = document.querySelectorAll('.task-item');
    if (remainingTasks.length === 0) {
        // Opsional: tambahkan satu task kosong jika ingin selalu ada minimal 1
        // addTaskRow();
    }

    // Bersihkan localStorage
    localStorage.removeItem('projectTeamData');
    localStorage.removeItem('projectRemovedTasks');
});

            // Search filters
            document.getElementById('search-input').addEventListener('keypress', e => {
                if (e.key === 'Enter') { e.preventDefault(); applyFilters(); }
            });
            
            const leaderSearch = document.getElementById('leader-search');
            if (leaderSearch) leaderSearch.addEventListener('input', filterLeaderTable);
            
            const ownerSearch = document.getElementById('owner-search');
            if (ownerSearch) ownerSearch.addEventListener('input', filterLeaderTable);
            
            // Pagination AJAX
            document.addEventListener('click', e => {
                const link = e.target.closest('#pagination-links-leader a, #pagination-links-owner a');
                if (link) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    const page = url.searchParams.get('page') || 1;
                    fetchFilteredUsers(page);
                }
            });
        });
    </script>
@endsection