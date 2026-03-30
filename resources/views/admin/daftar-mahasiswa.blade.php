@extends('Layout.Layout')

@section('title', 'Kelola Pengguna - Admin')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold dark:text-white">Kelola Pengguna</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Kelola semua pengguna yang terdaftar dalam sistem. Anda dapat melihat detail, memperbarui status pengajuan, atau menghapus pengguna sesuai kebutuhan.
            </p>
        </div>
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            <form id="bulkDeleteForm" action="{{ route('admin.users.bulkDestroy') }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmBulkDelete()" class="bg-red-500 hover:bg-red-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center text-sm sm:text-base flex-1 sm:flex-initial justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span class="hidden sm:inline">Hapus Terpilih</span>
                    <span class="sm:hidden">Hapus</span>
                </button>
            </form>
            <a href="{{ route('admin.users.ViewCreate') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center text-sm sm:text-base flex-1 sm:flex-initial justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">Tambah Pengguna</span>
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
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pencarian
                    </label>
                    <div class="relative">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari berdasarkan nama, username, atau email..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                        <div class="absolute left-3 top-2.5">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        @if(request('search'))
                            <a href="{{ route('admin.users.index') }}" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
                {{-- Role Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Role
                    </label>
                    <select name="role"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">Semua Role</option>
                        <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                {{-- Status Pengajuan Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status Pengajuan
                    </label>
                    <select name="status_pengajuan"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">Semua Status</option>
                        <option value="Di Terima" {{ request('status_pengajuan') == 'Di Terima' ? 'selected' : '' }}>Diterima</option>
                        <option value="Sedang Di Ajukan" {{ request('status_pengajuan') == 'Sedang Di Ajukan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="Di Tolak" {{ request('status_pengajuan') == 'Di Tolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end mt-4 space-x-3">
                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Reset Filter
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
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
                    <input type="checkbox"
                           id="selectAllCheckbox"
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Semua</span>
                </label>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400">
                Total dipilih: <span id="totalSelected">0</span> / <span id="totalItems">{{ count($users) }}</span>
            </span>
        </div>
    </div>
    @endif

    <!-- Table - Desktop View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-gray-800">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700">
                    <th class="px-4 py-3 text-left">
                        <input type="checkbox" id="tableSelectAllCheckbox" class="rounded text-blue-600 focus:ring-blue-500">
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Foto</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama / Username</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jurusan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Angkatan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($users as $index => $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-4">
                        <input type="checkbox" name="selected[]" value="{{ $user->id }}" class="item-checkbox rounded text-blue-600 focus:ring-blue-500">
                    </td>
                    <td class="px-4 py-4">
                        @if($user->photo_profile && Storage::disk('public')->exists($user->photo_profile))
                            <img src="{{ asset('storage/' . ltrim($user->photo_profile, '/')) }}"
                                 alt="{{ $user->nama_mahasiswa ?? $user->username }}"
                                 class="w-10 h-10 rounded-lg object-cover">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                <span class="text-sm font-bold text-gray-600 dark:text-gray-300">
                                    {{ strtoupper(substr($user->nama_mahasiswa ?? $user->username ?? 'U', 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        <div class="font-medium dark:text-white">{{ $user->nama_mahasiswa ?? 'Pengguna' }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ '@' . ($user->username ?? 'username') }}</div>
                    </td>
                    <td class="px-4 py-4 dark:text-white text-sm">{{ $user->email ?? '-' }}</td>
                    <td class="px-4 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @if($user->role == 'admin') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                            @elseif($user->role == 'dosen') bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400
                            @else bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-white
                            @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        @if($user->status_pengajuan)
                            @if($user->status_pengajuan == 'Di Terima')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                    Diterima
                                </span>
                            @elseif($user->status_pengajuan == 'Di Tolak')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                    Ditolak
                                </span>
                            @elseif($user->status_pengajuan == 'Sedang Di Ajukan')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5 animate-pulse"></span>
                                    Menunggu
                                </span>
                            @endif
                        @else
                            <span class="text-xs text-gray-500">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 dark:text-white text-sm">{{ $user->jurusan?->nama_jurusan ?? '-' }}</td>
                    <td class="px-4 py-4 dark:text-white text-sm">{{ $user->angkatan?->nama_angkatan ?? '-' }}</td>
                    <td class="px-4 py-4">
                        <div class="flex space-x-2">
                            @if(in_array($user->role, ['mahasiswa', 'dosen']))
                            <a href="{{ route('portfolio.show', $user->id) }}"
                               class="text-blue-500 hover:text-blue-700 transition-colors" title="Lihat Portfolio">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            @endif
                            <!-- Edit User Button -->
                            <a href="{{ route('admin.users.details', $user->id) }}"
                               class="text-yellow-500 hover:text-yellow-700 transition-colors" title="Edit User">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            @if($user->status_pengajuan == 'Sedang Di Ajukan')
                            <button type="button"
                                    onclick="openUpdateModal({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa ?? $user->username) }}')"
                                    class="text-green-500 hover:text-green-700 transition-colors" title="Update Status">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            @endif
                            <button type="button" 
                                    onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa ?? $user->username) }}')"
                                    class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                                <a href="{{ route('admin.users.index') }}" class="text-blue-500 hover:underline">Reset Filter</a>
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

    <!-- Mobile View - Card Layout -->
    <div class="md:hidden space-y-4">
        @forelse($users as $index => $user)
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-600">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <input type="checkbox" name="selected[]" value="{{ $user->id }}" class="item-checkbox rounded text-blue-600 focus:ring-blue-500">
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
                             alt="{{ $user->nama_mahasiswa ?? $user->username }}"
                             class="w-12 h-12 rounded-lg object-cover">
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
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $user->email ?? '-' }}</p>
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
                    <span class="text-xs dark:text-white">{{ $user->projects_count ?? 0 }} Project • {{ $user->learning_corners_count ?? 0 }} Learning • {{ $user->sertifikats_count ?? 0 }} Sertifikat</span>
                </div>
            </div>
           
            <div class="flex justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                @if(in_array($user->role, ['mahasiswa', 'dosen']))
                <a href="{{ route('portfolio.show', $user->id) }}"
                   class="text-blue-500 hover:text-blue-700 p-2 transition-colors" title="Lihat Portfolio">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>
                @endif
                <!-- Edit User Button Mobile -->
                <a href="{{ route('admin.users.details', $user->id) }}"
                   class="text-yellow-500 hover:text-yellow-700 p-2 transition-colors" title="Edit User">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                @if($user->status_pengajuan == 'Sedang Di Ajukan')
                <button type="button"
                        onclick="openUpdateModal({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa ?? $user->username) }}')"
                        class="text-green-500 hover:text-green-700 p-2 transition-colors" title="Update Status">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
                @endif
                <button type="button" 
                        onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa ?? $user->username) }}')"
                        class="text-red-500 hover:text-red-700 p-2 transition-colors" title="Hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
let selectedIds = [];

function updateSelectedIds() {
    selectedIds = [];

    // Ambil checkbox yang terlihat saja (tidak display:none)
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        if (checkbox.offsetParent !== null && checkbox.checked) {
            selectedIds.push(checkbox.value);
        }
    });

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

    // FIX: hanya hitung checkbox visible
    const allCheckboxes = Array.from(document.querySelectorAll('.item-checkbox'))
        .filter(cb => cb.offsetParent !== null);

    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const tableSelectAllCheckbox = document.getElementById('tableSelectAllCheckbox');

    if (allCheckboxes.length > 0) {
        if (selectedIds.length === allCheckboxes.length) {
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            }
            if (tableSelectAllCheckbox) {
                tableSelectAllCheckbox.checked = true;
                tableSelectAllCheckbox.indeterminate = false;
            }
        } else if (selectedIds.length === 0) {
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
            if (tableSelectAllCheckbox) {
                tableSelectAllCheckbox.checked = false;
                tableSelectAllCheckbox.indeterminate = false;
            }
        } else {
            if (selectAllCheckbox) selectAllCheckbox.indeterminate = true;
            if (tableSelectAllCheckbox) tableSelectAllCheckbox.indeterminate = true;
        }
    }
}

// Toggle All Checkboxes (fungsi utama)
function toggleAll(source) {
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.checked = source.checked;
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
            let url = `{{ route('admin.users.destroy', ':id') }}`;
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

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi awal
    updateSelectedIds();

    // Event listener untuk setiap checkbox item
   document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        if (checkbox.offsetParent !== null) {
            checkbox.checked = this.checked;
        }
    });

    // Select All di bar atas (mobile & desktop)
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            toggleAll(this);   
        });
    }

    // Select All di header tabel (desktop)
    const tableSelectAllCheckbox = document.getElementById('tableSelectAllCheckbox');
    if (tableSelectAllCheckbox) {
        tableSelectAllCheckbox.addEventListener('change', function() {
            toggleAll(this);
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
@endpush