@extends('Layout.Layout')
@section('title', 'Kelola Mahasiswa - Admin')
@section('content')

<div class="dark:text-white mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="font-medium text-2xl">Kelola Pengajuan Mahasiswa</h3>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Daftar pengajuan mahasiswa yang perlu diverifikasi</p>
        </div>
        
        <!-- Search and Filter -->
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative">
                <input type="text" 
                       id="searchInput" 
                       placeholder="Cari mahasiswa..." 
                       class="pl-10 pr-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white w-full sm:w-64">
                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            
            <select id="filterStatus" class="px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Semua Status</option>
                <option value="Sedang Di Ajukan">Menunggu</option>
                <option value="Di Terima">Diterima</option>
                <option value="Di Tolak">Ditolak</option>
            </select>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4 flex items-center justify-between" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
@endif

<!-- Mobile View (Card Layout) -->
<div class="block lg:hidden space-y-4" id="mobileView">
    @forelse($mahasiswa as $index => $mhs)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border dark:border-gray-700 mobile-card" data-status="{{ $mhs->status_pengajuan }}">
        <div class="flex items-start space-x-4">
            <!-- Profile Photo -->
            <div class="flex-shrink-0">
                @if($mhs->profile_photo)
                    <img src="{{ asset('storage/' . $mhs->profile_photo) }}" 
                         alt="{{ $mhs->nama_mahasiswa }}"
                         class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                @else
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold">
                        {{ strtoupper(substr($mhs->nama_mahasiswa, 0, 1)) }}
                    </div>
                @endif
            </div>
            
            <!-- Content -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-lg font-semibold dark:text-white truncate">{{ $mhs->nama_mahasiswa }}</h4>
                    @if($mhs->status_pengajuan == 'Di Terima')
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full">Diterima</span>
                    @elseif($mhs->status_pengajuan == 'Di Tolak')
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-1 rounded-full">Ditolak</span>
                    @elseif($mhs->status_pengajuan == 'Sedang Di Ajukan')
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-1 rounded-full">Menunggu</span>
                    @endif
                </div>
                
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">@ {{ $mhs->username }}</p>
                
                <!-- Detail Kecil: Jurusan, Angkatan, Keahlian -->
                <div class="flex flex-wrap gap-2 mb-3">
                    @if($mhs->jurusan && $mhs->jurusan->nama_jurusan)
                    <span class="inline-flex items-center px-2 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 text-xs rounded-full">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                        </svg>
                        {{ $mhs->jurusan->nama_jurusan }}
                    </span>
                    @endif
                    
                    @if($mhs->angkatan && $mhs->angkatan->tahun)
                    <span class="inline-flex items-center px-2 py-1 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 text-xs rounded-full">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Angkatan {{ $mhs->angkatan->tahun }}
                    </span>
                    @elseif($mhs->id_angkatan)
                    <span class="inline-flex items-center px-2 py-1 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 text-xs rounded-full">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Angkatan {{ $mhs->id_angkatan }}
                    </span>
                    @endif
                    
                    @if($mhs->keahlian && $mhs->keahlian->nama_keahlian)
                    <span class="inline-flex items-center px-2 py-1 bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 text-xs rounded-full">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ $mhs->keahlian->nama_keahlian }}
                    </span>
                    @endif
                </div>
                
                <!-- Stats -->
                <div class="flex items-center space-x-4 mb-3">
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="dark:text-white">{{ $mhs->projects_count ?? 0 }} Project</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="dark:text-white">{{ $mhs->learning_corners_count ?? 0 }} Learning</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span class="dark:text-white">{{ $mhs->sertifikats_count ?? 0 }} Sertifikat</span>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex items-center justify-end space-x-2 pt-2 border-t dark:border-gray-700">
                    <a href="{{ route('portfolio.show', $mhs->id) }}" 
                       target="_blank"
                       class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                       title="Lihat Portfolio">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </a>
                    
                    @if($mhs->status_pengajuan == 'Sedang Di Ajukan')
                    <button onclick="openUpdateModal({{ $mhs->id }}, '{{ $mhs->nama_mahasiswa }}')"
                            class="p-2 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors"
                            title="Update Status">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        <p class="text-gray-500 dark:text-gray-400 text-lg">Tidak ada data mahasiswa.</p>
    </div>
    @endforelse
</div>

<!-- Desktop View (Table Layout) -->
<div class="hidden lg:block overflow-x-auto">
    <table class="w-full border-collapse">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="border p-3 text-left">No</th>
                <th class="border p-3 text-left">Foto</th>
                <th class="border p-3 text-left">Nama</th>
                <th class="border p-3 text-left">Username</th>
                <th class="border p-3 text-left">Status</th>
                <th class="border p-3 text-center">Projects</th>
                <th class="border p-3 text-center">Learning</th>
                <th class="border p-3 text-center">Sertifikat</th>
                <th class="border p-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="dark:text-white" id="tableView">
            @forelse($mahasiswa as $index => $mhs)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 table-row group" data-status="{{ $mhs->status_pengajuan }}">
                <td class="border p-3">{{ $index + 1 }}</td>
                <td class="border p-3">
                    @if($mhs->profile_photo)
                        <img src="{{ asset('storage/' . $mhs->profile_photo) }}" 
                             alt="{{ $mhs->nama_mahasiswa }}"
                             class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr($mhs->nama_mahasiswa, 0, 1)) }}
                        </div>
                    @endif
                </td>
                <td class="border p-3 font-medium">
                    {{ $mhs->nama_mahasiswa }}
                    <!-- Detail Kecil: Jurusan, Angkatan, Keahlian (muncul saat hover) -->
                    <div class="hidden group-hover:flex absolute mt-1 gap-2 flex-wrap z-10 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-2 border dark:border-gray-700">
                        @if($mhs->jurusan && $mhs->jurusan->nama_jurusan)
                        <span class="inline-flex items-center px-2 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 text-xs rounded-full">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                            </svg>
                            {{ $mhs->jurusan->nama_jurusan }}
                        </span>
                        @endif
                        
                        @if($mhs->angkatan && $mhs->angkatan->tahun)
                        <span class="inline-flex items-center px-2 py-1 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 text-xs rounded-full">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Angkatan {{ $mhs->angkatan->tahun }}
                        </span>
                        @elseif($mhs->id_angkatan)
                        <span class="inline-flex items-center px-2 py-1 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 text-xs rounded-full">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Angkatan {{ $mhs->id_angkatan }}
                        </span>
                        @endif
                        
                        @if($mhs->keahlian && $mhs->keahlian->nama_keahlian)
                        <span class="inline-flex items-center px-2 py-1 bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 text-xs rounded-full">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ $mhs->keahlian->nama_keahlian }}
                        </span>
                        @endif
                    </div>
                </td>
                <td class="border p-3">{{ $mhs->username }}</td>
                <td class="border p-3">
                    @if($mhs->status_pengajuan == 'Di Terima')
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full">Diterima</span>
                    @elseif($mhs->status_pengajuan == 'Di Tolak')
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-1 rounded-full">Ditolak</span>
                    @elseif($mhs->status_pengajuan == 'Sedang Di Ajukan')
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-1 rounded-full">Menunggu</span>
                    @else
                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-1 rounded-full">{{ $mhs->status_pengajuan ?? 'Tidak ada' }}</span>
                    @endif
                </td>
                <td class="border p-3 text-center">{{ $mhs->projects_count ?? 0 }}</td>
                <td class="border p-3 text-center">{{ $mhs->learning_corners_count ?? 0 }}</td>
                <td class="border p-3 text-center">{{ $mhs->sertifikats_count ?? 0 }}</td>
                <td class="border p-3 text-center">
                    <div class="flex justify-center space-x-2">
                        <a href="{{ route('portfolio.show', $mhs->id) }}" 
                           target="_blank"
                           class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                           title="Lihat Portfolio">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        
                        @if($mhs->status_pengajuan == 'Sedang Di Ajukan')
                        <button onclick="openUpdateModal({{ $mhs->id }}, '{{ $mhs->nama_mahasiswa }}')"
                                class="p-1.5 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors"
                                title="Update Status">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="border p-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <p class="text-lg">Tidak ada data mahasiswa.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Update Status -->
<div id="updateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white dark:bg-gray-800">
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                Update Status Pengajuan
            </h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Content -->
        <form id="updateForm" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Mahasiswa: <span id="modalNama" class="font-semibold dark:text-white"></span>
                </p>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status_pengajuan" id="status_pengajuan" 
                        class="w-full border rounded-lg px-3 py-2.5 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
                          class="w-full border rounded-lg px-3 py-2.5 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Masukkan alasan penolakan..."></textarea>
                <p class="text-xs text-gray-500 mt-1">Alasan akan dikirimkan ke email mahasiswa</p>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="closeModal()"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Search and Filter Function
function filterData() {
    const searchInput = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const filterStatus = document.getElementById('filterStatus')?.value || '';
    
    // Filter mobile cards
    document.querySelectorAll('.mobile-card').forEach(card => {
        const name = card.querySelector('h4')?.textContent.toLowerCase() || '';
        const status = card.dataset.status || '';
        
        const matchesSearch = name.includes(searchInput);
        const matchesStatus = !filterStatus || status === filterStatus;
        
        card.style.display = matchesSearch && matchesStatus ? 'block' : 'none';
    });
    
    // Filter table rows
    document.querySelectorAll('#tableView tr:not(.no-data)').forEach(row => {
        const name = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
        const status = row.dataset.status || '';
        
        const matchesSearch = name.includes(searchInput);
        const matchesStatus = !filterStatus || status === filterStatus;
        
        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
    });
}

// Event listeners for search and filter
document.getElementById('searchInput')?.addEventListener('input', filterData);
document.getElementById('filterStatus')?.addEventListener('change', filterData);

// Modal functions
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

function closeModal() {
    document.getElementById('updateModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Show/hide keterangan tolak field based on status selection
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

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('updateModal');
    if (event.target == modal) {
        closeModal();
    }
}

// Handle form submission with AJAX
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

// Keyboard shortcut: ESC to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Initialize filter on page load
document.addEventListener('DOMContentLoaded', function() {
    filterData();
});
</script>

<style>
/* Smooth transitions */
.mobile-card {
    transition: all 0.3s ease;
}

.mobile-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
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

/* Tooltip positioning */
.group:hover .group-hover\:flex {
    display: flex;
    position: absolute;
    margin-top: 0.25rem;
}
</style>

@endsection