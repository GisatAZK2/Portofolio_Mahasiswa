@extends('Layout.Layout')

@section('title', 'Kelola Pengguna - Admin')

@section('content')
    <style>
        @media (max-width: 1919px),
        (max-height: 1079px) {
            .responsive-compact-table {
                font-size: 0.75rem !important;
            }

            .responsive-compact-table th,
            .responsive-compact-table td {
                padding: 0.5rem 0.75rem !important;
            }

            .responsive-compact-table .w-10.h-10 {
                width: 2rem !important;
                height: 2rem !important;
            }

            .responsive-compact-table svg.h-5.w-5 {
                width: 1rem !important;
                height: 1rem !important;
            }

            .responsive-compact-table .text-sm {
                font-size: 0.7rem !important;
            }
        }
                /* Style untuk tabel responsif dengan scroll horizontal */
        .table-responsive-wrapper {
           
            overflow-x: auto;
            overflow-y: visible;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
            position: relative;
            z-index: 1;
        }

        .table-responsive-wrapper::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive-wrapper::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .table-responsive-wrapper::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .table-responsive-wrapper::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark .table-responsive-wrapper::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark .table-responsive-wrapper::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        .dark .table-responsive-wrapper::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }

        /* Tabel dengan lebar setengah container */
        .half-width-table {
            table-layout: auto;
            position: relative;
            z-index: 1;
        }

        /* Container untuk membatasi lebar menjadi setengah */
        .table-half-container {
            width: 200%;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

    </style>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 data-translate="kll_pengguna" data-translate-page="admin"
                    class="text-xl sm:text-2xl font-bold dark:text-white">Kelola Pengguna</h2>
                <p data-translate="desc_kll_pengguna" data-translate-page="admin"
                    class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Kelola semua pengguna yang terdaftar dalam sistem. Anda dapat melihat detail, memperbarui status
                    pengajuan, atau menghapus pengguna sesuai kebutuhan.
                </p>
            </div>
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                    <button type="button" onclick="exportToExcel()"
        class="bg-green-500 hover:bg-green-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center text-sm sm:text-base flex-1 sm:flex-initial justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <span class="hidden sm:inline">Export Excel</span>
        <span class="sm:hidden">Excel</span>
    </button>

    <!-- Tombol Export Word -->
    <button type="button" onclick="exportToWord()"
        class="bg-blue-500 hover:bg-blue-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center text-sm sm:text-base flex-1 sm:flex-initial justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <span class="hidden sm:inline">Export Word</span>
        <span class="sm:hidden">Word</span>
    </button>


                <form id="bulkDeleteForm" action="{{ route('admin.users.bulkDestroy', ['locale' => app()->getLocale()]) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmBulkDelete()"
                        class="bg-red-500 hover:bg-red-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center text-sm sm:text-base flex-1 sm:flex-initial justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span class="hidden sm:inline" data-translate="del_user" data-translate-page="admin">Hapus
                            Terpilih</span>
                        <span class="sm:hidden">Hapus</span>
                    </button>
                </form>
                <a href="{{ route('admin.users.keahlian-tambahan.index') }}"
                    class="bg-purple-500 hover:bg-purple-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center text-sm sm:text-base flex-1 sm:flex-initial justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="hidden sm:inline">Keahlian Tambahan</span>
                    <span class="sm:hidden">Keahlian</span>
                    @if($pendingKeahlianTambahanCount > 0)
                        <span class="ml-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">
                            {{ $pendingKeahlianTambahanCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.users.ViewCreate') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center text-sm sm:text-base flex-1 sm:flex-initial justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span data-translate="add_user" data-translate-page="admin" class="hidden sm:inline">Tambah
                        Pengguna</span>
                    <span class="sm:hidden">Tambah</span>
                </a>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
            <form action="{{ route('admin.users.index') }}" method="GET" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Search Input --}}
                    <div class="lg:col-span-2">
                        <label data-translate="srch_usr" data-translate-page="admin"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pencarian
                        </label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari berdasarkan nama, username, atau email..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                            <div class="absolute left-3 top-2.5">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            @if(request('search'))
                                <a href="{{ route('admin.users.index') }}"
                                    class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                    {{-- Role Filter --}}
                    <div>
                        <label data-translate="role_usr" data-translate-page="admin"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Role
                        </label>
                        <select name="role"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                            <option data-translate="role_usr1" data-translate-page="admin" value="">Semua Role</option>
                            <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa
                            </option>
                            <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    {{-- Status Pengajuan Filter --}}
                    <div>
                        <label data-translate="stat_pengajuan" data-translate-page="admin"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status Pengajuan
                        </label>
                        <select name="status_pengajuan"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                            <option data-translate="all_stat" data-translate-page="admin" value="">Semua Status</option>
                            <option value="Di Terima" {{ request('status_pengajuan') == 'Di Terima' ? 'selected' : '' }}>
                                Diterima</option>
                            <option value="Sedang Di Ajukan" {{ request('status_pengajuan') == 'Sedang Di Ajukan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                            <option value="Di Tolak" {{ request('status_pengajuan') == 'Di Tolak' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end mt-4 space-x-3">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                        data-translate="reset_filter" data-translate-page="admin">
                        Reset Filter
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                        data-translate="trp_filter" data-translate-page="admin">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Select All Bar --}}
        @if(!$users->isEmpty())
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" id="selectAllCheckbox"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300" data-translate="plh_semua"
                                data-translate-page="admin">Pilih Semua</span>
                        </label>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        <span data-translate="total_dipilih" data-translate-page="admin">Total dipilih:</span> <span
                            id="totalSelected">0</span> / <span id="totalItems">{{ count($users) }}</span>
                    </span>
                </div>
            </div>
        @endif

        <!-- Table - Desktop View -->
               <div class="hidden w-115 md:block">
            <div class="table-half-container">
                <div class="table-responsive-wrapper">
                    <table class="half-width-table bg-white dark:bg-gray-800 text-sm responsive-compact-table">
        <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" id="tableSelectAllCheckbox"
                                class="rounded text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            data-translate="tbl_foto" data-translate-page="admin">Foto</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            data-translate="tbl_nm" data-translate-page="admin">Nama / Username</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center justify-between gap-2">
                                <span data-translate="tbl_email" data-translate-page="admin">Email</span>
                                <button type="button" id="emailHeaderToggle" class="p-1 hover:bg-gray-300 dark:hover:bg-gray-600 rounded transition" title="Toggle all emails visibility">
                                    <!-- Eye Icon (Show) -->
                                    <svg id="header-eye-icon-show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <!-- Eye Off Icon (Hide) -->
                                    <svg id="header-eye-icon-hide" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            data-translate="tbl_role" data-translate-page="admin">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            data-translate="tbl_stat" data-translate-page="admin">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aktif</th>
                        <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            data-translate="tbl_jrs" data-translate-page="admin">Prodi</th>
                        <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            data-translate="agkt" data-translate-page="admin">Angkatan</th>  
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                            data-translate="tbl_act" data-translate-page="admin">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $index => $user)
                        <tr data-item-index="{{ $index }}" class="paginated-item hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-3 py-3">
                                <input type="checkbox" name="selected[]" value="{{ $user->id }}"
                                    class="item-checkbox rounded text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-3 py-3">
                                @if($user->photo_profile && Storage::disk('public')->exists($user->photo_profile))
                                    <img src="{{ asset('storage/' . ltrim($user->photo_profile, '/')) }}"
                                        alt="{{ $user->nama_mahasiswa ?? $user->username }}"
                                        class="w-10 h-10 rounded-lg object-cover shadow-md ring-1 ring-gray-200 dark:ring-gray-700">
                                @else
                                    <div
                                        class="w-10 h-10 rounded-lg bg-gray-200 dark:bg-gray-600 flex items-center justify-center shadow-md ring-1 ring-gray-200 dark:ring-gray-700">
                                        <span class="text-sm font-bold text-gray-600 dark:text-gray-300">
                                            {{ strtoupper(substr($user->nama_mahasiswa ?? $user->username ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-3 py-3">
                                <div class="font-medium dark:text-white">{{ $user->nama_mahasiswa ?? 'Pengguna' }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ '@' . ($user->username ?? 'username') }}</div>
                            </td>
                            <td class="px-3 py-3 dark:text-white text-sm max-w-[220px] overflow-hidden whitespace-nowrap text-ellipsis truncate relative group">
                                <div class="email-cell" title="{{ $user->email ?? '-' }}" data-email="{{ $user->email ?? '-' }}">{{ $user->email ?? '-' }}</div>
                                <button type="button" class="email-toggle-btn absolute right-0 top-1/2 -translate-y-1/2 p-1 bg-gray-200 dark:bg-gray-600 rounded opacity-0 group-hover:opacity-100 transition-opacity" title="Toggle email visibility">
                                    <!-- Eye Icon (Show) -->
                                    <svg class="email-eye-show w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <!-- Eye Off Icon (Hide) -->
                                    <svg class="email-eye-hide w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </td>
                            <td class="px-3 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($user->role == 'admin') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                    @elseif($user->role == 'dosen') bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400
                                    @else bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-white
                                    @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                @if($user->status_pengajuan)
                                    @if($user->status_pengajuan == 'Di Terima')
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                                <span data-translate="stat_diterima" data-translate-page="admin">Diterima</span>
                                        </span>
                                    @elseif($user->status_pengajuan == 'Di Tolak')
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                            <span data-translate="stat_ditolak" data-translate-page="admin">Ditolak</span>
                                        </span>
                                    @elseif($user->status_pengajuan == 'Sedang Di Ajukan')
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5 animate-pulse"></span>
                                            <span data-translate="pend" data-translate-page="dosen_kll_mhs">Menunggu</span>
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-3">
                                @if($user->is_active)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="hidden md:table-cell px-3 py-3 dark:text-white text-sm">
                                {{ $user->jurusan?->nama_jurusan ?? '-' }}</td>
                            <td class="hidden md:table-cell px-3 py-3 dark:text-white text-sm">
                                {{ $user->angkatan?->nama_angkatan ?? '-' }}</td>
                            <td class="px-3 py-3">
                                <div class="flex space-x-2">
                                    @if(in_array($user->role, ['mahasiswa', 'dosen']))
                                        <a href="{{  route('portfolio.show', ['user' => $user->id]) }}"
                                            class="text-blue-500 hover:text-blue-700 transition-colors" title="Lihat Portfolio">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    @endif
                                    <!-- Edit User Button -->
                                    <a href="{{ route('admin.users.details', ['id' => $user->id]) }}"
                                        class="text-yellow-500 hover:text-yellow-700 transition-colors" title="Edit User">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @if($user->status_pengajuan == 'Sedang Di Ajukan')
                                        <button type="button"
                                            onclick="openUpdateModal({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa ?? $user->username) }}')"
                                            class="text-green-500 hover:text-green-700 transition-colors" title="Update Status">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    @endif
                                    <button type="button"
                                        onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa ?? $user->username) }}')"
                                        class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                @if(request('search') || request('role') || request('status_pengajuan'))
                                    Tidak ada hasil pencarian untuk filter yang dipilih.
                                    <div class="mt-2">
                                        <a href="{{ route('admin.users.index') }}" class="text-blue-500 hover:underline">Reset
                                            Filter</a>
                                    </div>
                                @else
                                    Belum ada pengguna terdaftar.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
        </div>

        <!-- Mobile View - Card Layout -->
        <div class="md:hidden space-y-4">
            @forelse($users as $index => $user)
                <div data-item-index="{{ $index }}"
                    class="paginated-item bg-white dark:bg-gray-700 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-600">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="selected[]" value="{{ $user->id }}"
                                class="item-checkbox rounded text-blue-600 focus:ring-blue-500">
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @if($user->role == 'admin') bg-red-100 text-red-700
                            @elseif($user->role == 'dosen') bg-purple-100 text-purple-700
                            @else bg-blue-100 text-blue-700
                            @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <div class="flex items-start space-x-3 mb-3">
                        <div>
                            @if($user->photo_profile && Storage::disk('public')->exists($user->photo_profile))
                                <img src="{{ asset('storage/' . ltrim($user->photo_profile, '/')) }}"
                                    alt="{{ $user->nama_mahasiswa ?? $user->username }}" class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                    <span class="text-lg font-bold text-gray-600 dark:text-gray-300">
                                        {{ strtoupper(substr($user->nama_mahasiswa ?? $user->username ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold dark:text-white">{{ $user->nama_mahasiswa ?? 'Pengguna' }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ '@' . ($user->username ?? 'username') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 break-words max-w-full"
                                title="{{ $user->email ?? '-' }}">{{ $user->email ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="space-y-2 mb-4">
                        @if($user->status_pengajuan)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pengajuan:</span>
                                @if($user->status_pengajuan == 'Di Terima')
                                    <span class="text-xs text-green-600">✓ Diterima</span>
                                @elseif($user->status_pengajuan == 'Di Tolak')
                                    <span class="text-xs text-red-600">✗ Ditolak</span>
                                @elseif($user->status_pengajuan == 'Sedang Di Ajukan')
                                    <span class="text-xs text-yellow-600">⏳ Menunggu</span>
                                @endif
                            </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Aktif:</span>
                            @if($user->is_active)
                                <span class="text-xs text-green-600">✓ Aktif</span>
                            @else
                                <span class="text-xs text-red-600">✗ Tidak Aktif</span>
                            @endif
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Aktif:</span>
                            @if($user->is_active)
                                <span class="text-xs text-green-600">✓ Aktif</span>
                            @else
                                <span class="text-xs text-red-600">✗ Tidak Aktif</span>
                            @endif
                        </div>

                        @if($user->jurusan?->nama_jurusan)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Jurusan:</span>
                                <span class="text-sm dark:text-white">{{ $user->jurusan->nama_jurusan }}</span>
                            </div>
                        @endif

                        @if($user->angkatan?->tahun_angkatan)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Angkatan:</span>
                                <span class="text-sm dark:text-white">{{ $user->angkatan->tahun_angkatan }}</span>
                            </div>
                        @endif

                        @if($user->keahlian?->nama_keahlian)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Keahlian:</span>
                                <span class="text-sm dark:text-white">{{ $user->keahlian->nama_keahlian }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Stats:</span>
                            <span class="text-xs dark:text-white">{{ $user->projects_count ?? 0 }} Project •
                                {{ $user->learning_corners_count ?? 0 }} Learning • {{ $user->sertifikats_count ?? 0 }}
                                Sertifikat</span>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                        @if(in_array($user->role, ['mahasiswa', 'dosen']))
                            <a href="{{  route('portfolio.show', ['user' => $user->id]) }}"
                                class="text-blue-500 hover:text-blue-700 p-2 transition-colors" title="Lihat Portfolio">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        @endif
                        <!-- Edit User Button Mobile -->
                        <a href="{{ route('admin.users.details', ['id' => $user->id]) }}"
                            class="text-yellow-500 hover:text-yellow-700 p-2 transition-colors" title="Edit User">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        @if($user->status_pengajuan == 'Sedang Di Ajukan')
                            <button type="button"
                                onclick="openUpdateModal({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa ?? $user->username) }}')"
                                class="text-green-500 hover:text-green-700 p-2 transition-colors" title="Update Status">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        @endif
                        <button type="button"
                            onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa ?? $user->username) }}')"
                            class="text-red-500 hover:text-red-700 p-2 transition-colors" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    @if(request('search') || request('role') || request('status_pengajuan'))
                        Tidak ada hasil pencarian untuk filter yang dipilih.
                        <div class="mt-2">
                            <a href="{{ route('admin.users.index') }}" class="text-blue-500 hover:underline">Reset Filter</a>
                        </div>
                    @else
                        Belum ada pengguna terdaftar.
                    @endif
                </div>
            @endforelse
        </div>

        <div id="clientPaginationControls" class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                <span data-translate="show" data-translate-page="admin"Menampilkan></span> <span id="paginationVisibleCount">0</span> <span data-translate="from" data-translate-page="admin"dari></span> <span
                    id="paginationTotalCount">{{ $users->count() }}</span> <span data-translate="user" data-translate-page="admin">pengguna</span>.
            </p>
            <nav id="paginationNumberButtons" class="flex flex-wrap items-center gap-2"></nav>
        </div>

        
        <!-- Hidden Delete Form -->
        <form id="hiddenDeleteForm" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        <!-- Hidden Update Form -->
        <form id="hiddenUpdateForm" method="POST" style="display: none;">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status_pengajuan" id="hidden_status_pengajuan">
            <input type="hidden" name="keterangan_tolak" id="hidden_keterangan_tolak">
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        let allEmailsVisible = true;
    // ============ EXPORT FUNCTIONS - SIMPLE VERSION ============
    
    // Fungsi untuk mengumpulkan data pengguna
    function getExportData() {
        const users = [];
        const userRows = document.querySelectorAll('.paginated-item:not(.hidden), tbody .paginated-item');
        
        userRows.forEach(row => {
            if (row.offsetParent !== null || row.style.display !== 'none') {
                let userData = {};
                
                // Desktop view
                const desktopCells = row.querySelectorAll('td');
                if (desktopCells.length > 0) {
                    userData = {
                        nama: row.querySelector('td:nth-child(3) .font-medium')?.innerText || '-',
                        username: row.querySelector('td:nth-child(3) .text-sm')?.innerText?.replace('@', '') || '-',
                        email: getEmailText(row),
                        role: row.querySelector('td:nth-child(5) span')?.innerText || '-',
                        status_pengajuan: getStatusPengajuanText(row),
                        status_aktif: getStatusAktifText(row),
                        jurusan: row.querySelector('td:nth-child(8)')?.innerText?.trim() || '-',
                        angkatan: row.querySelector('td:nth-child(9)')?.innerText?.trim() || '-'
                    };
                } 
                // Mobile view
                else {
                    userData = {
                        nama: row.querySelector('h3')?.innerText || '-',
                        username: row.querySelector('.text-gray-500')?.innerText?.replace('@', '') || '-',
                        email: getMobileEmailText(row),
                        role: row.querySelector('.rounded-full')?.innerText || '-',
                        status_pengajuan: getMobileStatusPengajuanText(row),
                        status_aktif: getMobileStatusAktifText(row),
                        jurusan: getMobileJurusanText(row),
                        angkatan: getMobileAngkatanText(row)
                    };
                }
                
                users.push(userData);
            }
        });
        
        return users;
    }
    
    function getEmailText(row) {
        const emailCell = row.querySelector('td:nth-child(4) .email-cell');
        if (emailCell && emailCell.textContent !== '...') {
            return emailCell.textContent;
        }
        const emailData = row.querySelector('td:nth-child(4) [data-email]')?.getAttribute('data-email');
        return emailData || '-';
    }
    
    function getStatusPengajuanText(row) {
        const statusSpan = row.querySelector('td:nth-child(6) span');
        if (statusSpan) {
            let text = statusSpan.innerText.replace('✓', '').replace('✗', '').replace('⏳', '').trim();
            if (text === 'Diterima') return 'Di Terima';
            if (text === 'Ditolak') return 'Di Tolak';
            if (text === 'Menunggu') return 'Sedang Di Ajukan';
            return text;
        }
        return '-';
    }
    
    function getStatusAktifText(row) {
        const activeSpan = row.querySelector('td:nth-child(7) span');
        if (activeSpan) {
            return activeSpan.innerText.includes('Aktif') ? 'Aktif' : 'Tidak Aktif';
        }
        return '-';
    }
    
    function getMobileEmailText(row) {
        const emailEl = row.querySelector('.break-words');
        return emailEl?.innerText || '-';
    }
    
    function getMobileStatusPengajuanText(row) {
        const statusText = row.querySelector('.text-green-600, .text-red-600, .text-yellow-600');
        if (statusText) {
            let text = statusText.innerText.replace('✓', '').replace('✗', '').replace('⏳', '').trim();
            if (text === 'Diterima') return 'Di Terima';
            if (text === 'Ditolak') return 'Di Tolak';
            if (text === 'Menunggu') return 'Sedang Di Ajukan';
            return text;
        }
        return '-';
    }
    
    function getMobileStatusAktifText(row) {
        const activeText = Array.from(row.querySelectorAll('.text-green-600, .text-red-600'))
            .find(el => el.innerText.includes('Aktif') || el.innerText.includes('Tidak'));
        if (activeText) {
            return activeText.innerText.replace('✓', '').replace('✗', '').trim();
        }
        return '-';
    }
    
    function getMobileJurusanText(row) {
        const jurusanDiv = Array.from(row.querySelectorAll('.flex.justify-between'))
            .find(div => div.innerText.includes('Jurusan:'));
        return jurusanDiv ? jurusanDiv.querySelector('span:last-child')?.innerText || '-' : '-';
    }
    
    function getMobileAngkatanText(row) {
        const angkatanDiv = Array.from(row.querySelectorAll('.flex.justify-between'))
            .find(div => div.innerText.includes('Angkatan:'));
        return angkatanDiv ? angkatanDiv.querySelector('span:last-child')?.innerText || '-' : '-';
    }
    
    // Export ke Excel (CSV)
   function exportToExcel() {
    const users = getExportData();

    if (users.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Tidak Ada Data',
            text: 'Tidak ada data pengguna yang dapat diexport.',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }

    const columns = [
        'NO',
        'NAMA LENGKAP',
        'USERNAME',
        'EMAIL',
        'ROLE',
        'STATUS PENGAJUAN',
        'STATUS AKTIF',
        'JURUSAN/PRODI',
        'ANGKATAN'
    ];

    let csvContent = '\uFEFF';

    csvContent += columns.map(col => `"${col}"`).join(';') + '\r\n';

    users.forEach((user, index) => {
        const row = [
            index + 1,
            user.nama,
            user.username,
            user.email,
            user.role,
            user.status_pengajuan,
            user.status_aktif,
            user.jurusan,
            user.angkatan
        ];

        csvContent += row.map(item =>
            `"${String(item).replace(/"/g, '""')}"`
        ).join(';') + '\r\n';
    });

    const blob = new Blob([csvContent], {
        type: 'text/csv;charset=utf-8;'
    });

    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);

    link.href = url;
    link.download = `data_pengguna_${formatDate(new Date())}.csv`;

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    URL.revokeObjectURL(url);

    Swal.fire({
        icon: 'success',
        title: 'Export Berhasil!',
        text: `${users.length} data berhasil diexport.`,
        timer: 1500,
        showConfirmButton: false
    });
}

    
    // Export ke Word (Simple)
    function exportToWord() {
        const users = getExportData();
        
        if (users.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak Ada Data',
                text: 'Tidak ada data pengguna yang dapat diexport.',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }
        
        const date = new Date();
        
        let html = `<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Data Pengguna</title>
            <style>
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background: #f0f0f0; }
            </style>
        </head>
        <body>
            <h3>Data Pengguna - ${formatDate(date)}</h3>
            <p>Total: ${users.length} pengguna</p>
            <table>
                <thead>
                    <tr>
                        <th>NO</th><th>NAMA LENGKAP</th><th>USERNAME</th><th>EMAIL</th>
                        <th>ROLE</th><th>STATUS PENGAJUAN</th><th>STATUS AKTIF</th>
                        <th>JURUSAN/PRODI</th><th>ANGKATAN</th>
                    </tr>
                </thead>
                <tbody>`;
        
        users.forEach((user, i) => {
            html += `<tr>
                <td>${i+1}</td>
                <td>${escapeHtml(user.nama)}</td>
                <td>${escapeHtml(user.username)}</td>
                <td>${escapeHtml(user.email)}</td>
                <td>${escapeHtml(user.role)}</td>
                <td>${escapeHtml(user.status_pengajuan)}</td>
                <td>${escapeHtml(user.status_aktif)}</td>
                <td>${escapeHtml(user.jurusan)}</td>
                <td>${escapeHtml(user.angkatan)}</td>
            </tr>`;
        });
        
        html += `</tbody></table></body></html>`;
        
        const blob = new Blob([html], { type: 'application/msword' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.href = url;
        link.setAttribute('download', `laporan_pengguna_${formatDate(date)}.doc`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        
        Swal.fire({
            icon: 'success',
            title: 'Export Berhasil!',
            text: `${users.length} data berhasil diexport.`,
            timer: 1500,
            showConfirmButton: false
        });
    }
    
    function formatDate(date) {
        return `${date.getFullYear()}-${String(date.getMonth()+1).padStart(2,'0')}-${String(date.getDate()).padStart(2,'0')}`;
    }
    
    function escapeHtml(str) {
        if (!str || str === '-') return '-';
        return String(str).replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

        function toggleIndividualEmailVisibility(button) {
            const emailCell = button.previousElementSibling;
            const eyeShowIcon = button.querySelector('.email-eye-show');
            const eyeHideIcon = button.querySelector('.email-eye-hide');
            const isHidden = emailCell.textContent === '...';

            if (isHidden) {
                emailCell.textContent = emailCell.dataset.email;
                emailCell.title = emailCell.dataset.email;
            } else {
                emailCell.textContent = '...';
                emailCell.title = 'Email tersembunyi';
            }

            // Toggle icon - hanya di row ini, tidak update header
            eyeShowIcon.classList.toggle('hidden', isHidden);
            eyeHideIcon.classList.toggle('hidden', !isHidden);
        }

        function toggleAllEmailsVisibility() {
            allEmailsVisible = !allEmailsVisible;
            const headerEyeShow = document.getElementById('header-eye-icon-show');
            const headerEyeHide = document.getElementById('header-eye-icon-hide');
            const allEmailToggleButtons = document.querySelectorAll('.email-toggle-btn');

            allEmailToggleButtons.forEach(button => {
                const emailCell = button.previousElementSibling;
                const eyeShowIcon = button.querySelector('.email-eye-show');
                const eyeHideIcon = button.querySelector('.email-eye-hide');

                if (allEmailsVisible) {
                    emailCell.textContent = emailCell.dataset.email;
                    emailCell.title = emailCell.dataset.email;
                    eyeShowIcon.classList.remove('hidden');
                    eyeHideIcon.classList.add('hidden');
                } else {
                    emailCell.textContent = '...';
                    emailCell.title = 'Email tersembunyi';
                    eyeShowIcon.classList.add('hidden');
                    eyeHideIcon.classList.remove('hidden');
                }
            });

            // Toggle header icon saat button diklik
            headerEyeShow.classList.toggle('hidden', !allEmailsVisible);
            headerEyeHide.classList.toggle('hidden', allEmailsVisible);
        }

        let selectedIds = [];

        function getAllCheckboxes() {
            return Array.from(document.querySelectorAll('.item-checkbox'));
        }

        function getVisibleCheckboxes() {
            return getAllCheckboxes().filter(checkbox => checkbox.offsetParent !== null);
        }

        function setCheckboxStateByValue(value, checked) {
            getAllCheckboxes().forEach(checkbox => {
                if (checkbox.value === value) {
                    checkbox.checked = checked;
                }
            });
        }

        function updateSelectedIds() {
            const selectedSet = new Set();
            getAllCheckboxes().forEach(checkbox => {
                if (checkbox.checked) {
                    selectedSet.add(checkbox.value);
                }
            });
            selectedIds = Array.from(selectedSet);

            const totalSelectedEl = document.getElementById('totalSelected');
            if (totalSelectedEl) {
                totalSelectedEl.textContent = selectedIds.length;
            }

            // Reset input hidden
            const bulkForm = document.getElementById('bulkDeleteForm');
            bulkForm.querySelectorAll('input[name="selected_ids[]"]').forEach(input => input.remove());

            // Tambah input baru
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;
                bulkForm.appendChild(input);
            });

            const allCheckboxes = getAllCheckboxes();
            const allUniqueIds = Array.from(new Set(allCheckboxes.map(cb => cb.value)));
            const visibleCheckboxes = getVisibleCheckboxes();
            const visibleUniqueIds = Array.from(new Set(visibleCheckboxes.map(cb => cb.value)));

            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const tableSelectAllCheckbox = document.getElementById('tableSelectAllCheckbox');

            if (selectAllCheckbox) {
                if (selectedIds.length === allUniqueIds.length && allUniqueIds.length > 0) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else if (selectedIds.length === 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = true;
                }
            }

            if (tableSelectAllCheckbox) {
                const visibleSelectedCount = visibleUniqueIds.filter(id => selectedIds.includes(id)).length;
                if (visibleSelectedCount === visibleUniqueIds.length && visibleUniqueIds.length > 0) {
                    tableSelectAllCheckbox.checked = true;
                    tableSelectAllCheckbox.indeterminate = false;
                } else if (visibleSelectedCount === 0) {
                    tableSelectAllCheckbox.checked = false;
                    tableSelectAllCheckbox.indeterminate = false;
                } else {
                    tableSelectAllCheckbox.checked = false;
                    tableSelectAllCheckbox.indeterminate = true;
                }
            }
        }

        // Toggle All Checkboxes (fungsi utama)
        function toggleAll(source, onlyVisible = false) {
            const targetCheckboxes = onlyVisible ? getVisibleCheckboxes() : getAllCheckboxes();
            const targetValues = Array.from(new Set(targetCheckboxes.map(checkbox => checkbox.value)));

            targetValues.forEach(value => {
                setCheckboxStateByValue(value, source.checked);
            });
            updateSelectedIds();
        }

        // Confirm Bulk Delete
        function confirmBulkDelete() {
            updateSelectedIds(); // Pastikan data ter-update sebelum konfirmasi

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Ada Data Dipilih',
                    text: 'Silakan pilih minimal satu pengguna.',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            Swal.fire({
                title: 'Hapus Pengguna Terpilih?',
                html: `Anda akan menghapus <strong>${selectedIds.length}</strong> pengguna secara permanen.<br><br><small class="text-red-600">Tindakan ini tidak dapat dibatalkan.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus Permanen',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('bulkDeleteForm').submit();
                }
            });
        }

        // Open Delete Modal (Single Delete)
        function openDeleteModal(id, name) {
            Swal.fire({
                title: 'Hapus Pengguna',
                html: `Apakah Anda yakin ingin menghapus <strong>${name}</strong>?<br><br><small class="text-red-600">Tindakan ini tidak dapat dibatalkan.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('hiddenDeleteForm');
                    const locale = document.querySelector('html').getAttribute('lang') || 'id';
                    let url = `/${locale}/admin/manageUser/DeleteUser?id=${id}`;
                    url = url.replace(':id', id);
                    form.action = url;
                    form.submit();
                }
            });
        }

        function openUpdateModal(userId, userName) {
            Swal.fire({
                title: 'Update Status Pengajuan',
                html: `
                <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-left">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Mahasiswa: <span class="font-medium text-gray-900 dark:text-white">${userName}</span>
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Status Pengajuan <span class="text-red-500">*</span>
                    </label>
                    <select id="swal-status_pengajuan" 
                            onchange="toggleKeteranganField(this)"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Pilih Status</option>
                        <option value="Di Terima">✅ Terima Pengajuan</option>
                        <option value="Di Tolak">❌ Tolak Pengajuan</option>
                    </select>
                </div>

                <div class="mb-4 hidden" id="swal-keteranganTolakField">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Keterangan / Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="swal-keterangan_tolak" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Masukkan alasan penolakan secara jelas..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">Alasan ini akan dikirimkan ke email mahasiswa</p>
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: 'Simpan Perubahan',
                cancelButtonText: 'Batal',
                width: '520px',
                preConfirm: () => {
                    const status = document.getElementById('swal-status_pengajuan').value;
                    const keterangan = document.getElementById('swal-keterangan_tolak') ?
                        document.getElementById('swal-keterangan_tolak').value.trim() : '';

                    if (!status) {
                        Swal.showValidationMessage('Silakan pilih status pengajuan');
                        return false;
                    }

                    if (status === 'Di Tolak' && !keterangan) {
                        Swal.showValidationMessage('Alasan penolakan wajib diisi!');
                        return false;
                    }

                    const form = document.getElementById('hiddenUpdateForm');
                    form.action = `/user/${userId}/update-status`;
                    document.getElementById('hidden_status_pengajuan').value = status;
                    document.getElementById('hidden_keterangan_tolak').value = keterangan;

                    form.submit();
                    return false;
                }
            });
        }

        // Toggle keterangan field
        function toggleKeteranganField(selectElement) {
            const keteranganField = document.getElementById('swal-keteranganTolakField');
            if (selectElement.value === 'Di Tolak') {
                keteranganField.classList.remove('hidden');
                setTimeout(() => {
                    document.getElementById('swal-keterangan_tolak').focus();
                }, 300);
            } else {
                keteranganField.classList.add('hidden');
            }
        }

        const clientPageSize = 20;
        let clientCurrentPage = 1;

        function getClientPaginationItems() {
            return Array.from(document.querySelectorAll('.paginated-item'))
                .sort((a, b) => parseInt(a.dataset.itemIndex, 10) - parseInt(b.dataset.itemIndex, 10));
        }

        function renderPaginationButtons() {
            const totalCount = parseInt(document.getElementById('paginationTotalCount').textContent, 10);
            const pageCount = Math.max(1, Math.ceil(totalCount / clientPageSize));
            const paginationNav = document.getElementById('paginationNumberButtons');

            if (!paginationNav) return;
            paginationNav.innerHTML = '';

            const visiblePages = 10;
            let startPage = Math.max(1, clientCurrentPage - Math.floor(visiblePages / 2));
            let endPage = startPage + visiblePages - 1;

            if (endPage > pageCount) {
                endPage = pageCount;
                startPage = Math.max(1, endPage - visiblePages + 1);
            }

            const addNavButton = (label, page, isActive, extraClasses = []) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = label;
                button.className = ['px-3 py-2 min-w-[40px] rounded-full text-sm font-medium transition', ...extraClasses].join(' ');
                if (isActive) {
                    button.classList.add('bg-indigo-600', 'text-white');
                } else {
                    button.classList.add('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300', 'border', 'border-gray-200', 'dark:border-gray-700', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
                    button.addEventListener('click', () => {
                        clientCurrentPage = page;
                        updateClientPagination();
                    });
                }
                paginationNav.appendChild(button);
            };

            if (clientCurrentPage > 1) {
                addNavButton('«', clientCurrentPage - 1, false, ['px-3', 'py-2']);
            }

            if (startPage > 1) {
                addNavButton('1', 1, false);
                if (startPage > 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.className = 'px-3 py-2 text-sm text-gray-500 dark:text-gray-400';
                    paginationNav.appendChild(ellipsis);
                }
            }

            for (let page = startPage; page <= endPage; page++) {
                addNavButton(page, page, page === clientCurrentPage);
            }

            if (endPage < pageCount) {
                if (endPage < pageCount - 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.className = 'px-3 py-2 text-sm text-gray-500 dark:text-gray-400';
                    paginationNav.appendChild(ellipsis);
                }
                addNavButton(pageCount, pageCount, false);
            }

            if (clientCurrentPage < pageCount) {
                addNavButton('»', clientCurrentPage + 1, false, ['px-3', 'py-2']);
            }
        }

        function updateClientPagination() {
            const items = getClientPaginationItems();
            const totalCount = items.length;
            const startIndex = (clientCurrentPage - 1) * clientPageSize;
            const endIndex = clientCurrentPage * clientPageSize;

            items.forEach((item, index) => {
                item.classList.toggle('hidden', index < startIndex || index >= endIndex);
            });

            const visibleText = Math.min(endIndex, totalCount);
            document.getElementById('paginationVisibleCount').textContent = visibleText;
            document.getElementById('paginationTotalCount').textContent = totalCount;

            renderPaginationButtons();
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function () {
            // Header email toggle button
            const emailHeaderToggle = document.getElementById('emailHeaderToggle');
            if (emailHeaderToggle) {
                emailHeaderToggle.addEventListener('click', toggleAllEmailsVisibility);
            }

            // Email toggle buttons (individual)
            document.querySelectorAll('.email-toggle-btn').forEach(button => {
                button.addEventListener('click', function () {
                    toggleIndividualEmailVisibility(this);
                });
            });

            // Inisialisasi awal
            updateSelectedIds();
            updateClientPagination();

            // Event listener untuk setiap checkbox item
            getAllCheckboxes().forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    setCheckboxStateByValue(checkbox.value, checkbox.checked);
                    updateSelectedIds();
                });
            });

            // Select All di bar atas (full range select)
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function () {
                    toggleAll(this, false);
                });
            }

            // Select All di header tabel (current page only)
            const tableSelectAllCheckbox = document.getElementById('tableSelectAllCheckbox');
            if (tableSelectAllCheckbox) {
                tableSelectAllCheckbox.addEventListener('change', function () {
                    toggleAll(this, true);
                });
            }

            // Session messages
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#dc2626'
                });
            @endif

            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: '{{ session('info') }}',
                    confirmButtonColor: '#3b82f6'
                });
            @endif
    });
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.manage_users");
        });
    </script>
@endpush