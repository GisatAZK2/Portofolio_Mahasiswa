@extends('Layout.Layout')
@section('title', 'Kelola Pengguna - Admin')
@section('content')

<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Kelola Pengguna</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar seluruh mahasiswa dan dosen</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- Search -->
            <div class="relative">
                <input type="text"
                       id="searchInput"
                       placeholder="Cari nama atau username..."
                       class="pl-10 pr-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 w-full sm:w-72">
                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            
            <!-- Filter Role -->
            <select id="filterRole" class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                <option value="">Semua Role</option>
                <option value="mahasiswa">Mahasiswa</option>
                <option value="dosen">Dosen</option>
                <option value="admin">Admin</option>
            </select>
            
            <!-- Filter Status -->
            <select id="filterStatus" class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                <option value="">Semua Status</option>
                <option value="Sedang Di Ajukan">Menunggu</option>
                <option value="Di Terima">Diterima</option>
                <option value="Di Tolak">Ditolak</option>
            </select>
            
            <!-- Add User Button -->
            <a href="{{ route('admin.users.ViewCreate') }}" 
               class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah User
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-5 h-5 text-green-600 dark:text-green-400">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-5 h-5 text-red-600 dark:text-red-400">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Pengguna</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">{{ $users->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Menunggu Verifikasi</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">{{ $users->where('status_pengajuan', 'Sedang Di Ajukan')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Mahasiswa</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">{{ $users->where('role', 'mahasiswa')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Dosen</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">{{ $users->where('role', 'dosen')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="usersGrid">
        @forelse($users as $user)
        <div class="user-card bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow cursor-pointer"
             data-user-id="{{ $user->id }}"
             data-role="{{ $user->role }}"
             data-status="{{ $user->status_pengajuan }}">
            <!-- Card Header -->
            <div class="p-5">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-3">
                        <!-- Avatar -->
                        <div class="relative">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                     alt="{{ $user->nama_mahasiswa }}"
                                     class="w-12 h-12 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                            @else
                                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center">
                                    <span class="text-lg font-medium text-gray-600 dark:text-gray-300">
                                        {{ strtoupper(substr($user->nama_mahasiswa, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            
                            <!-- Role Badge -->
                            <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white dark:border-gray-800
                                @if($user->role == 'admin') bg-red-500
                                @elseif($user->role == 'dosen') bg-purple-500
                                @else bg-blue-500
                                @endif">
                            </span>
                        </div>
                        
                        <!-- Name & Username -->
                        <div>
                            <h3 class="font-medium text-gray-900 dark:text-white">{{ $user->nama_mahasiswa }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">@ {{ $user->username }}</p>
                        </div>
                    </div>
                    
                    <!-- Status Badge -->
                    @if($user->status_pengajuan == 'Di Terima')
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                            Diterima
                        </span>
                    @elseif($user->status_pengajuan == 'Di Tolak')
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                            Ditolak
                        </span>
                    @elseif($user->status_pengajuan == 'Sedang Di Ajukan')
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                            Menunggu
                        </span>
                    @endif
                </div>
                
                <!-- Info Tags -->
                <div class="mt-4 flex flex-wrap gap-2">
                    @if($user->jurusan && $user->jurusan->nama_jurusan)
                    <span class="inline-flex items-center px-2 py-1 bg-gray-100 dark:bg-gray-700 text-xs text-gray-700 dark:text-gray-300 rounded-lg">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                        </svg>
                        {{ $user->jurusan->nama_jurusan }}
                    </span>
                    @endif
                    
                    @if($user->angkatan && $user->angkatan->tahun)
                    <span class="inline-flex items-center px-2 py-1 bg-gray-100 dark:bg-gray-700 text-xs text-gray-700 dark:text-gray-300 rounded-lg">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Angkatan {{ $user->angkatan->tahun }}
                    </span>
                    @endif
                    
                    @if($user->keahlian && $user->keahlian->nama_keahlian)
                    <span class="inline-flex items-center px-2 py-1 bg-gray-100 dark:bg-gray-700 text-xs text-gray-700 dark:text-gray-300 rounded-lg">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ $user->keahlian->nama_keahlian }}
                    </span>
                    @endif
                </div>
                
                <!-- Stats -->
                <div class="mt-4 flex items-center space-x-4">
                    <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span>{{ $user->projects_count ?? 0 }} Project</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span>{{ $user->learning_corners_count ?? 0 }} Learning</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span>{{ $user->sertifikats_count ?? 0 }} Sertifikat</span>
                    </div>
                </div>
            </div>
            
            <!-- Card Footer -->
            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-end space-x-2">
                    @if($user->role == 'mahasiswa' || $user->role == 'dosen')
                    <a href="{{ route('portfolio.show', $user->id) }}" 
                       target="_blank"
                       class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 dark:text-gray-400 dark:hover:text-blue-400 dark:hover:bg-blue-900/30 rounded-lg transition-colors"
                       title="Lihat Portfolio"
                       onclick="event.stopPropagation()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </a>
                    @endif
                    
                    @if($user->status_pengajuan == 'Sedang Di Ajukan')
                    <button onclick="event.stopPropagation(); openUpdateModal({{ $user->id }}, '{{ $user->nama_mahasiswa }}')"
                            class="p-2 text-gray-600 hover:text-green-600 hover:bg-green-50 dark:text-gray-400 dark:hover:text-green-400 dark:hover:bg-green-900/30 rounded-lg transition-colors"
                            title="Update Status">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </button>
                    @endif
                    
                    <button onclick="event.stopPropagation(); openDeleteModal({{ $user->id }}, '{{ $user->nama_mahasiswa }}')"
                            class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                            title="Hapus User">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-12 text-center">
                <div class="w-20 h-20 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak Ada Data</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada pengguna yang terdaftar</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Detail User -->
<div id="detailModal" class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 w-full max-w-2xl">
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl">
            <!-- Close Button -->
            <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <!-- Modal Content -->
            <div class="p-6">
                <div id="modalContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div id="updateModal" class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 w-full max-w-md">
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Update Status Pengajuan</h3>
                
                <form id="updateForm" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Mahasiswa: <span id="modalNama" class="font-medium text-gray-900 dark:text-white"></span>
                        </p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status_pengajuan" id="status_pengajuan"
                                class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                                required>
                            <option value="">Pilih Status</option>
                            <option value="Di Terima">Terima Pengajuan</option>
                            <option value="Di Tolak">Tolak Pengajuan</option>
                        </select>
                    </div>
                    
                    <div class="mb-6 hidden" id="keteranganTolakField">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="keterangan_tolak"
                                  rows="4"
                                  class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                                  placeholder="Masukkan alasan penolakan..."></textarea>
                        <p class="text-xs text-gray-500 mt-1">Alasan akan dikirimkan ke email mahasiswa</p>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                onclick="closeUpdateModal()"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete Confirmation -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 w-full max-w-md">
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full mb-4">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-2">Hapus User?</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6">
                    User <span id="deleteUserName" class="font-medium text-gray-900 dark:text-white"></span> akan dihapus permanen dan tidak bisa dikembalikan.
                </p>
                
                <form id="deleteForm" method="POST" class="flex justify-center space-x-3">
                    @csrf
                    @method('DELETE')
                    
                    <button type="button"
                            onclick="closeDeleteModal()"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Filter functionality
function filterUsers() {
    const searchInput = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const filterRole = document.getElementById('filterRole')?.value || '';
    const filterStatus = document.getElementById('filterStatus')?.value || '';
    
    document.querySelectorAll('.user-card').forEach(card => {
        const name = card.querySelector('h3')?.textContent.toLowerCase() || '';
        const username = card.querySelector('p')?.textContent.toLowerCase().replace('@', '') || '';
        const role = card.dataset.role || '';
        const status = card.dataset.status || '';
        
        const matchesSearch = name.includes(searchInput) || username.includes(searchInput);
        const matchesRole = !filterRole || role === filterRole;
        const matchesStatus = !filterStatus || status === filterStatus;
        
        card.style.display = matchesSearch && matchesRole && matchesStatus ? '' : 'none';
    });
}

document.getElementById('searchInput')?.addEventListener('input', filterUsers);
document.getElementById('filterRole')?.addEventListener('change', filterUsers);
document.getElementById('filterStatus')?.addEventListener('change', filterUsers);

// Detail Modal Functions
function openDetailModal(userId) {
    // Fetch user details via AJAX
    fetch(`/admin/users/${userId}/details`)
        .then(response => response.json())
        .then(user => {
            const modalContent = document.getElementById('modalContent');
            
            // Determine if portfolio button should be shown
            const showPortfolio = user.role === 'mahasiswa' || user.role === 'dosen';
            
            modalContent.innerHTML = `
                <div class="space-y-6">
                    <!-- Header -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            ${user.profile_photo ? 
                                `<img src="/storage/${user.profile_photo}" alt="${user.nama_mahasiswa}" class="w-20 h-20 rounded-full object-cover border border-gray-200 dark:border-gray-600">` :
                                `<div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center">
                                    <span class="text-2xl font-medium text-gray-600 dark:text-gray-300">${user.nama_mahasiswa.charAt(0).toUpperCase()}</span>
                                </div>`
                            }
                            <span class="absolute bottom-0 right-0 w-4 h-4 rounded-full border-2 border-white dark:border-gray-800
                                ${user.role === 'admin' ? 'bg-red-500' : user.role === 'dosen' ? 'bg-purple-500' : 'bg-blue-500'}">
                            </span>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">${user.nama_mahasiswa}</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">@${user.username}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">${user.email || 'Email tidak tersedia'}</p>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Status:</span>
                        ${user.status_pengajuan === 'Di Terima' ? 
                            '<span class="px-2 py-1 bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs rounded-full">Diterima</span>' :
                            user.status_pengajuan === 'Di Tolak' ?
                            '<span class="px-2 py-1 bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-xs rounded-full">Ditolak</span>' :
                            user.status_pengajuan === 'Sedang Di Ajukan' ?
                            '<span class="px-2 py-1 bg-yellow-50 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 text-xs rounded-full">Menunggu</span>' :
                            '<span class="px-2 py-1 bg-gray-50 text-gray-700 dark:bg-gray-700 dark:text-gray-400 text-xs rounded-full">Tidak ada</span>'
                        }
                    </div>
                    
                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Role</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white capitalize">${user.role}</p>
                        </div>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Jenis Kelamin</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">${user.jenis_kelamin || '-'}</p>
                        </div>
                        ${user.jurusan ? `
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Jurusan</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">${user.jurusan.nama_jurusan}</p>
                        </div>
                        ` : ''}
                        ${user.angkatan ? `
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Angkatan</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">${user.angkatan.tahun}</p>
                        </div>
                        ` : ''}
                        ${user.keahlian ? `
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Keahlian Utama</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">${user.keahlian.nama_keahlian}</p>
                        </div>
                        ` : ''}
                    </div>
                    
                    <!-- Keahlian Tambahan -->
                    ${user.keahlian_tambahan ? `
                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keahlian Tambahan</p>
                        <div class="flex flex-wrap gap-2">
                            ${JSON.parse(user.keahlian_tambahan).map(skill => 
                                `<span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-xs text-gray-700 dark:text-gray-300 rounded-lg">${skill}</span>`
                            ).join('')}
                        </div>
                    </div>
                    ` : ''}
                    
                    <!-- Deskripsi -->
                    ${user.deskripsi ? `
                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">${user.deskripsi}</p>
                    </div>
                    ` : ''}
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-3">
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">${user.projects_count || 0}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Projects</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">${user.learning_corners_count || 0}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Learning</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">${user.sertifikats_count || 0}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Sertifikat</p>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        ${showPortfolio ? `
                        <a href="/portfolio/${user.id}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat Portfolio
                        </a>
                        ` : ''}
                        
                        <a href="/admin/users/${user.id}/edit" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                        
                        ${user.status_pengajuan === 'Sedang Di Ajukan' ? `
                        <button onclick="openUpdateModal(${user.id}, '${user.nama_mahasiswa}')"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-xl transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Status
                        </button>
                        ` : ''}
                    </div>
                </div>
            `;
            
            document.getElementById('detailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat detail user');
        });
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Update Modal Functions
function openUpdateModal(userId, userName) {
    const modal = document.getElementById('updateModal');
    const form = document.getElementById('updateForm');
    const namaSpan = document.getElementById('modalNama');
    
    form.action = `/user/${userId}/update-status`;
    namaSpan.textContent = userName;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Reset form
    document.getElementById('status_pengajuan').value = '';
    document.getElementById('keteranganTolakField').classList.add('hidden');
}

function closeUpdateModal() {
    document.getElementById('updateModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Delete Modal Functions
function openDeleteModal(userId, userName) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    const nameSpan = document.getElementById('deleteUserName');
    
    form.action = `/admin/users/${userId}`;
    nameSpan.textContent = userName;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Add click event to user cards
document.querySelectorAll('.user-card').forEach(card => {
    card.addEventListener('click', function(e) {
        // Don't open modal if clicking on action buttons
        if (e.target.closest('a') || e.target.closest('button')) {
            return;
        }
        openDetailModal(this.dataset.userId);
    });
});

// Show/hide keterangan tolak field
document.getElementById('status_pengajuan')?.addEventListener('change', function() {
    const keteranganField = document.getElementById('keteranganTolakField');
    if (this.value === 'Di Tolak') {
        keteranganField.classList.remove('hidden');
        document.querySelector('[name="keterangan_tolak"]').required = true;
    } else {
        keteranganField.classList.add('hidden');
        document.querySelector('[name="keterangan_tolak"]').required = false;
    }
});

// Close modals when clicking outside
window.onclick = function(event) {
    const detailModal = document.getElementById('detailModal');
    const updateModal = document.getElementById('updateModal');
    const deleteModal = document.getElementById('deleteModal');
    
    if (event.target == detailModal) {
        closeDetailModal();
    }
    if (event.target == updateModal) {
        closeUpdateModal();
    }
    if (event.target == deleteModal) {
        closeDeleteModal();
    }
}

// Keyboard shortcut: ESC to close modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetailModal();
        closeUpdateModal();
        closeDeleteModal();
    }
});

// Handle update form submission
document.getElementById('updateForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Menyimpan...';
    submitBtn.disabled = true;
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeUpdateModal();
            // Show success message and reload
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses request');
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});

// Initialize filter on page load
document.addEventListener('DOMContentLoaded', function() {
    filterUsers();
});
</script>

<style>
/* Smooth transitions */
.user-card {
    transition: all 0.2s ease;
}

.user-card:hover {
    transform: translateY(-2px);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Dark mode scrollbar */
.dark ::-webkit-scrollbar-track {
    background: #374151;
}

.dark ::-webkit-scrollbar-thumb {
    background: #4b5563;
}

.dark ::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}

/* Modal animations */
#detailModal,
#updateModal,
#deleteModal {
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>
@endsection