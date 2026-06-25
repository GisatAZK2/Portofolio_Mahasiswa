@extends('Layout.Layout')

@section('title', 'Kelola Pengguna - Admin')

@section('content')
    <style>
        /* Responsive table wrapper */
        .table-responsive-wrapper {
            overflow-x: auto;
            overflow-y: visible;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
            border-radius: 0.5rem;
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

        /* Compact table for high-res screens */
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

        /* Mobile card enhancements */
        @media (max-width: 767px) {
            .mobile-card-buttons {
                gap: 0.5rem;
            }

            .mobile-card-buttons button,
            .mobile-card-buttons a {
                padding: 0.5rem;
            }
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="hidden sm:inline">Export Excel</span>
                    <span class="sm:hidden">Excel</span>
                </button>

                <button type="button" onclick="exportToWord()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center text-sm sm:text-base flex-1 sm:flex-initial justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="hidden sm:inline">Export Word</span>
                    <span class="sm:hidden">Word</span>
                </button>

                    <form id="bulkDeleteForm" action="{{ route('admin.users.bulkDestroy', ['locale' => app()->getLocale()]) }}"
                    method="POST" class="inline md:hidden">
                    @csrf
                    @method('DELETE')
                    <button id="mobileBulkDeleteBtn" type="button" onclick="confirmBulkDelete()"
                        class="bg-red-500 hover:bg-red-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center text-sm sm:text-base flex-1 sm:flex-initial justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span class="hidden sm:inline" data-translate="del_user" data-translate-page="admin">Hapus
                            Terpilih</span>
                        <span class="sm:hidden" data-translate="del_user" data-translate-page="admin">Hapus</span>
                    </button>
                </form>

                <form id="bulkApproveForm" action="{{ route('admin.users.bulkApprove', ['locale' => app()->getLocale()]) }}" method="POST" class="inline md:hidden">
                    @csrf
                    @method('PATCH')
                    <button id="mobileBulkApproveBtn" type="button" onclick="confirmBulkApprove()"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center text-sm sm:text-base flex-1 sm:flex-initial justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="hidden sm:inline" data-translate="approve_selected" data-translate-page="admin">Setujui Terpilih</span>
                        <span class="sm:hidden" data-translate="approve_selected" data-translate-page="admin">Setujui</span>
                    </button>
                </form>

                <a href="{{ route('admin.users.keahlian-tambahan.index') }}"
                    class="bg-purple-500 hover:bg-purple-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center text-sm sm:text-base flex-1 sm:flex-initial justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="hidden sm:inline" data-translate="keahlian_tambahan" data-translate-page="admin">Keahlian Tambahan</span>
                    <span class="sm:hidden" data-translate="keahlian_tambahan" data-translate-page="admin">Keahlian</span>
                    @if ($pendingKeahlianTambahanCount > 0)
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
                    <span class="sm:hidden" data-translate="add_user" data-translate-page="admin">Tambah</span>
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
            <form action="{{ route('admin.users.index') }}" method="GET" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
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
                            @if (request('search'))
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
                            <option value="Sedang Di Ajukan"
                                {{ request('status_pengajuan') == 'Sedang Di Ajukan' ? 'selected' : '' }}>Menunggu Persetujuan
                            </option>
                            <option value="Di Tolak" {{ request('status_pengajuan') == 'Di Tolak' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Prodi
                        </label>
                        <select name="jurusan"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Semua Prodi</option>
                            @foreach($jurusan as $j)
                                <option value="{{ $j->id_jurusan }}" {{ request('jurusan') == $j->id_jurusan ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                            @endforeach
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

        <!-- Select All Bar -->
        @if ($users->count() > 0)
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" id="selectAllCheckbox"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300" data-translate="plh_semua"
                                data-translate-page="admin">Pilih Semua</span>
                        </label>
                        
                        <!-- Desktop bulk action buttons placed next to select-all -->
                        <div class="hidden md:flex items-center space-x-2 ml-4">
                            <button id="desktopBulkApproveBtn" type="button" onclick="confirmBulkApprove()"
                                class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-sm flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span data-translate="approve_selected" data-translate-page="admin">Setujui Terpilih</span>
                            </button>

                            <button id="desktopBulkDeleteBtn" type="button" onclick="confirmBulkDelete()"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-sm flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6" /></svg>
                                <span data-translate="del_user" data-translate-page="admin">Hapus Terpilih</span>
                            </button>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        <span data-translate="total_dipilih" data-translate-page="admin">Total dipilih:</span> <span
                            id="totalSelected">0</span> / <span id="totalItems">{{ $users->count() }}</span>
                    </span>
                </div>
            </div>
        @endif

        <!-- Desktop Table View -->
        <div class="hidden md:block">
            <div class="table-responsive-wrapper">
                <table class="min-w-full bg-white dark:bg-gray-800 text-sm responsive-compact-table">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left w-10">
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
                                    <button type="button" id="emailHeaderToggle"
                                        class="p-1 hover:bg-gray-300 dark:hover:bg-gray-600 rounded transition"
                                        title="Toggle all emails visibility">
                                        <svg id="header-eye-icon-show" class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg id="header-eye-icon-hide" class="w-4 h-4 hidden" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
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
                            <th class="hidden lg:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                                data-translate="tbl_jrs" data-translate-page="admin">Prodi</th>
                            <th class="hidden lg:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
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
                                    @if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile))
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
                                    <div class="email-cell" title="{{ $user->email ?? '-' }}"
                                        data-email="{{ $user->email ?? '-' }}">{{ $user->email ?? '-' }}</div>
                                    <button type="button"
                                        class="email-toggle-btn absolute right-0 top-1/2 -translate-y-1/2 p-1 bg-gray-200 dark:bg-gray-600 rounded opacity-0 group-hover:opacity-100 transition-opacity"
                                        title="Toggle email visibility">
                                        <svg class="email-eye-show w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg class="email-eye-hide w-4 h-4 hidden" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-3 py-3">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium
                                        @if ($user->role == 'admin') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                        @elseif($user->role == 'dosen') bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400
                                        @else bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-white @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3">
                                    @if ($user->status_pengajuan)
                                        @if ($user->status_pengajuan == 'Di Terima')
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
                                    @if ($user->is_active)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="hidden lg:table-cell px-3 py-3 dark:text-white text-sm">
                                    {{ $user->jurusan?->nama_jurusan ?? '-' }}
                                </td>
                                <td class="hidden lg:table-cell px-3 py-3 dark:text-white text-sm">
                                    {{ $user->angkatan?->nama_angkatan ?? '-' }}
                                </td>
                                <td class="px-3 py-3">
                                    <div class="flex space-x-2">
                                        @if (in_array($user->role, ['mahasiswa', 'dosen']))
                                            <a href="{{ route('portfolio.slug', ['user' => $user->slug]) }}"
                                                class="text-blue-500 hover:text-blue-700 transition-colors" title="Lihat Portfolio">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.users.details', ['id' => $user->id]) }}"
                                            class="text-yellow-500 hover:text-yellow-700 transition-colors" title="Edit User">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        @if ($user->status_pengajuan == 'Sedang Di Ajukan')
                                            <button type="button"
                                                onclick="openUpdateModal({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa ?? $user->username) }}')"
                                                class="text-green-500 hover:text-green-700 transition-colors" title="Update Status">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        @endif
                                        <button type="button"
                                            onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa ?? $user->username) }}')"
                                            class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
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
                                    @if (request('search') || request('role') || request('status_pengajuan'))
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

                <div class="mt-6">
                    {{ $users->links('vendor.pagination.custom_ajax', ['groupName' => 'users']) }}
                </div>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @forelse($users as $index => $user)
                <div data-item-index="{{ $index }}" class="paginated-item bg-white dark:bg-gray-700 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-600">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="selected[]" value="{{ $user->id }}"
                                class="item-checkbox rounded text-blue-600 focus:ring-blue-500">
                        </div>
                        <span
                            class="px-2 py-1 rounded-full text-xs font-medium
                            @if ($user->role == 'admin') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                            @elseif($user->role == 'dosen') bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400
                            @else bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-white @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <div class="flex items-start space-x-3 mb-3">
                        <div>
                            @if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile))
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
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400 break-words max-w-[70%] email-cell-mobile"
                                    title="{{ $user->email ?? '-' }}" data-email="{{ $user->email ?? '-' }}">
                                    {{ $user->email ?? '-' }}
                                </p>
                                <button type="button"
                                    class="email-toggle-btn-mobile p-1 bg-gray-200 dark:bg-gray-600 rounded"
                                    title="Toggle email visibility">
                                    <svg class="email-eye-show-mobile w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg class="email-eye-hide-mobile w-4 h-4 hidden" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 mb-4">
                        @if ($user->status_pengajuan)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pengajuan:</span>
                                @if ($user->status_pengajuan == 'Di Terima')
                                    <span class="text-xs text-green-600 dark:text-green-400">✓ Diterima</span>
                                @elseif($user->status_pengajuan == 'Di Tolak')
                                    <span class="text-xs text-red-600 dark:text-red-400">✗ Ditolak</span>
                                @elseif($user->status_pengajuan == 'Sedang Di Ajukan')
                                    <span class="text-xs text-yellow-600 dark:text-yellow-400">⏳ Menunggu</span>
                                @endif
                            </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Aktif:</span>
                            @if ($user->is_active)
                                <span class="text-xs text-green-600 dark:text-green-400">✓ Aktif</span>
                            @else
                                <span class="text-xs text-red-600 dark:text-red-400">✗ Tidak Aktif</span>
                            @endif
                        </div>

                        @if ($user->jurusan?->nama_jurusan)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Jurusan:</span>
                                <span class="text-sm dark:text-white">{{ $user->jurusan->nama_jurusan }}</span>
                            </div>
                        @endif

                        @if ($user->angkatan?->tahun_angkatan)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Angkatan:</span>
                                <span class="text-sm dark:text-white">{{ $user->angkatan->tahun_angkatan }}</span>
                            </div>
                        @endif

                        @if ($user->keahlian?->nama_keahlian)
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

                    <div class="flex justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-600 mobile-card-buttons">
                        @if (in_array($user->role, ['mahasiswa', 'dosen']))
                            <a href="{{ route('portfolio.slug', ['user' => $user->slug]) }}" class="text-blue-500 hover:text-blue-700 p-2 transition-colors"
                                title="Lihat Portfolio">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        @endif
                        <a href="{{ route('admin.users.details', ['id' => $user->id]) }}" class="text-yellow-500 hover:text-yellow-700 p-2 transition-colors"
                            title="Edit User">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        @if ($user->status_pengajuan == 'Sedang Di Ajukan')
                            <button type="button"
                                onclick="openUpdateModal({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa ?? $user->username) }}')"
                                class="text-green-500 hover:text-green-700 p-2 transition-colors" title="Update Status">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
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
                    @if (request('search') || request('role') || request('status_pengajuan'))
                        Tidak ada hasil pencarian untuk filter yang dipilih.
                        <div class="mt-2">
                            <a href="{{ route('admin.users.index') }}" class="text-blue-500 hover:underline">Reset Filter</a>
                        </div>
                    @else
                        Belum ada pengguna terdaftar.
                    @endif
                </div>
            @endforelse
            <div class="mt-6">
                {{ $users->links('vendor.pagination.custom_ajax', ['groupName' => 'users']) }}
            </div>
        </div>

        <!-- Hidden Forms -->
        <form id="hiddenDeleteForm" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        <form id="hiddenUpdateForm" method="POST" style="display: none;">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status_pengajuan" id="hidden_status_pengajuan">
            <input type="hidden" name="keterangan_tolak" id="hidden_keterangan_tolak">
        </form>
    </div>

    {{-- Flash message data for JS (dibaca oleh app.js) --}}
    <div id="manage-users-data"
         data-flash-success="{{ session('success') ?? '' }}"
         data-flash-error="{{ session('error') ?? '' }}"
         data-flash-info="{{ session('info') ?? '' }}"
         class="hidden"></div>

@endsection