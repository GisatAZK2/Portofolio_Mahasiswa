@extends('Layout.Layout')
@section('title', 'Sertifikat Saya')
@section('content')
<div class="p-6 lg:p-8 dark:bg-gray-700 rounded-2xl">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-50">Kelola Semua Sertifikat</h1>
            <p class="text-gray-600 dark:text-gray-200">
               Kelola Semua Sertifikat Milik Mahasiswa
            </p>
        </div>
        <div class="flex gap-3">
            <button type="button" 
                    id="bulkDeleteBtn"
                    class="inline-flex items-center px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus Terpilih (<span id="selectedCount">0</span>)
            </button>
            <a href="{{ route('admin.sertifikat.create') }}"
               class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Sertifikat
            </a>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-6 mb-8 border border-gray-100 dark:border-gray-800">
        <form action="{{ route('admin.sertifikat.index') }}" method="GET" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                {{-- Search Input --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pencarian
                    </label>
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ $search ?? '' }}"
                               placeholder="Cari mahasiswa atau nama sertifikat..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-100">
                        <div class="absolute left-3 top-2.5">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Angkatan Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Angkatan
                    </label>
                    <select name="angkatan" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">Semua Angkatan</option>
                        @foreach($angkatans as $angkatanItem)
                            <option value="{{ $angkatanItem->id }}" {{ $angkatan == $angkatanItem->id ? 'selected' : '' }}>
                                {{ $angkatanItem->nama_angkatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Jurusan Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Jurusan
                    </label>
                    <select name="jurusan" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusans as $jurusanItem)
                            <option value="{{ $jurusanItem->id_jurusan }}" {{ $jurusan == $jurusanItem->id_jurusan ? 'selected' : '' }}>
                                {{ $jurusanItem->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Keahlian Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Keahlian
                    </label>
                    <select name="keahlian" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">Semua Keahlian</option>
                        @foreach($keahlians as $keahlianItem)
                            <option value="{{ $keahlianItem->id_keahlian }}" {{ $keahlian == $keahlianItem->id_keahlian ? 'selected' : '' }}>
                                {{ $keahlianItem->nama_keahlian }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Pengajuan Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>
                    <select name="status_pengajuan" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">Semua Status</option>
                        @foreach($statusOptions as $status)
                            <option value="{{ $status }}" {{ $status_pengajuan == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end mt-4 space-x-3">
                <a href="{{ route('admin.sertifikat.index') }}" 
                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Reset Filter
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Select All Bar --}}
    @if(!$sertifikat->isEmpty())
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 mb-6 border border-gray-100 dark:border-gray-800">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" 
                           id="selectAllCheckbox"
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Semua</span>
                </label>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Menampilkan {{ $sertifikat->firstItem() }} - {{ $sertifikat->lastItem() }} dari {{ $sertifikat->total() }} sertifikat
                </span>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400">
                Total dipilih: <span id="totalSelected">0</span>
            </span>
        </div>
    </div>
    @endif

    {{-- Data Sertifikat --}}
    @if ($sertifikat->isEmpty())
        <div class="text-center py-12 bg-gray-50 dark:bg-gray-900 dark:border-gray-900 rounded-xl border border-gray-200">
            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="mt-4 text-gray-600 dark:text-gray-200">Tidak ada sertifikat yang ditemukan.</p>
            @if($search || $angkatan || $jurusan || $keahlian || $status_pengajuan)
                <p class="text-gray-500 dark:text-gray-50 text-sm mt-2">Coba atur ulang filter pencarian Anda.</p>
                <a href="{{ route('admin.sertifikat.index') }}" 
                   class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Reset Semua Filter
                </a>
            @else
                <p class="text-gray-500 dark:text-gray-50 text-sm mt-2">Mulai tambahkan sertifikat pertama!</p>
            @endif
        </div>
    @else
        <form id="bulkDeleteForm" action="{{ route('admin.sertifikat.bulk-destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="selected_ids" id="selectedIdsInput" value="">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($sertifikat as $entry)
                    <div class="certificate-card bg-white dark:border-gray-900 dark:bg-gray-900 dark:text-gray-200 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all border-2 relative flex flex-col h-full"
                         data-id="{{ $entry->id }}">
                        <!-- Selection Checkbox -->
                        <div class="absolute top-4 left-4 z-20">
                            <input type="checkbox" 
                                   name="certificate_ids[]" 
                                   value="{{ $entry->id }}"
                                   class="certificate-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800">
                        </div>

                        <!-- Header dengan ikon sertifikat dan status badge -->
                        <div class="bg-gradient-to-r from-blue-700 to-blue-500 p-4">
                            <div class="flex items-center justify-between">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                
                                {{-- Status Badge --}}
                                @php
                                    $statusClass = '';
                                    $statusText = '';
                                    
                                    if ($entry->status_pengajuan == 'Di Terima' && $entry->is_active == 1) {
                                        $statusClass = 'bg-green-100 text-green-800';
                                        $statusText = 'Diterima & Aktif';
                                    } elseif ($entry->status_pengajuan == 'Di Terima' && $entry->is_active == 0) {
                                        $statusClass = 'bg-gray-100 text-gray-800';
                                        $statusText = 'Diterima (Tidak Aktif)';
                                    } elseif ($entry->status_pengajuan == 'Sedang Di Ajukan') {
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                        $statusText = 'Sedang Diajukan';
                                    } elseif ($entry->status_pengajuan == 'Di Tolak') {
                                        $statusClass = 'bg-red-100 text-red-800';
                                        $statusText = 'Ditolak';
                                    } else {
                                        $statusClass = 'bg-gray-100 text-gray-800';
                                        $statusText = $entry->status_pengajuan ?? 'Unknown';
                                    }
                                @endphp
                                
                                <span class="text-xs font-medium px-2 py-1 rounded-full {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col">
                            <!-- Nama Sertifikat -->
                            <div class="mb-3">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Nama Sertifikat:</span>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 line-clamp-2">
                                    {{ $entry->nama_sertifikat }}
                                </h3>
                            </div>
                         
                            <!-- Nama Mahasiswa -->
                            <div class="mb-3">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Nama Mahasiswa:</span>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ $entry->mahasiswa->nama_mahasiswa }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $entry->mahasiswa->angkatan->nama_angkatan ?? '-' }} | 
                                    {{ $entry->mahasiswa->jurusan->nama_jurusan ?? '-' }}
                                </p>
                            </div>
                        
                            <!-- Lembaga Penerbit -->
                            <div class="flex items-center text-gray-600 dark:text-gray-200 mb-3">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l14-7 3.5 1.5L21 21z"></path>
                                </svg>
                                <span class="text-sm">{{ $entry->lembaga_penerbit }}</span>
                            </div>

                            <!-- Tanggal Terbit -->
                            <div class="flex items-center text-gray-600 dark:text-gray-300 mb-4">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm">{{ \Carbon\Carbon::parse($entry->tanggal_terbit)->format('d F Y') }}</span>
                            </div>

                            <!-- Link Sertifikat -->
                            @if($entry->link_sertifikat && $entry->status_pengajuan == 'Di Terima' && $entry->is_active == 1)
                                <div class="mb-4">
                                      <a href="{{ asset('storage/' . $entry->link_sertifikat) }}" 
                                target="_blank"
                                class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 hover:underline">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    <span data-translate="sertifikat_link" data-translate-page="sertifikat_user"></span>
                                </a>
                                </div>
                            @endif

                            <!-- Keterangan (Jika ada) -->
                            @if($entry->keterangan)
                                <div class="mb-3 p-2 bg-gray-50 dark:bg-gray-800 rounded-lg border-l-4 border-gray-400">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 font-semibold">Keterangan:</p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $entry->keterangan }}</p>
                                </div>
                            @endif

                            <!-- Informasi Status Detail -->
                            <div class="mb-3 space-y-1">
                                <p class="text-xs">
                                    <span class="font-semibold">Status Pengajuan:</span> 
                                    <span class="
                                        @if($entry->status_pengajuan == 'Di Terima') text-green-600
                                        @elseif($entry->status_pengajuan == 'Sedang Di Ajukan') text-yellow-600
                                        @elseif($entry->status_pengajuan == 'Di Tolak') text-red-600
                                        @endif
                                    ">
                                        {{ $entry->status_pengajuan }}
                                    </span>
                                </p>
                                <p class="text-xs">
                                    <span class="font-semibold">Status Aktif:</span> 
                                    <span class="{{ $entry->is_active ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $entry->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </p>
                            </div>

                            <!-- Tanggal dibuat/diupdate -->
                            <p class="text-xs text-gray-400 mt-auto pt-4 border-t border-gray-100 dark:border-gray-800">
                                Ditambahkan: {{ $entry->created_at ? $entry->created_at->format('d M Y') : '-' }}
                                @if($entry->created_at != $entry->updated_at)
                                    <br>Diupdate: {{ $entry->updated_at->format('d M Y') }}
                                @endif
                            </p>

                             <!-- Tombol Aksi - DESAIN BARU -->
                            <div class="mt-auto pt-6 border-t border-gray-100 dark:border-gray-700">
                                <div class="grid grid-cols-2 gap-3">
                                    @php
                                        $canEdit = in_array($entry->status_pengajuan, ['Di Terima', 'Sedang Di Ajukan']);
                                    @endphp

                                    @if($canEdit)
                                        <a href="{{ route('admin.sertifikat.details', $entry->id) }}"
                                           class="flex items-center justify-center gap-2 py-3 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:hover:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-2xl font-medium transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Edit
                                        </a>
                                    @endif

                                    <form action="{{ route('admin.sertifikat.destroy', $entry->id) }}" method="POST" class="delete-form">
                                        @csrf @method('DELETE')
                                        <button type="button" class="delete-btn w-full flex items-center justify-center gap-2 py-3 bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900 text-red-700 dark:text-red-300 rounded-2xl font-medium transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                                {{-- Aksi Approve/Reject untuk status "Sedang Di Ajukan" --}}
                                @if($entry->status_pengajuan == 'Sedang Di Ajukan')
                                    <div class="flex space-x-3 mt-2">
                                        <form action="{{ route('admin.sertifikat.approve', $entry->id) }}" 
                                              method="POST" 
                                              class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button"
                                                    class="approve-btn w-full py-2.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition font-medium border border-green-200 dark:bg-green-900 dark:text-green-200 dark:border-green-800">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Terima
                                            </button>
                                        </form>

                                        <button type="button"
                                                class="reject-btn flex-1 py-2.5 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 transition font-medium border border-orange-200 dark:bg-orange-900 dark:text-orange-200 dark:border-orange-800"
                                                data-id="{{ $entry->id }}">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Tolak
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $sertifikat->links() }}
        </div>
        
    @endif
</div>

{{-- Reject Modals --}}
@foreach($sertifikat as $entry)
    @if($entry->status_pengajuan == 'Sedang Di Ajukan')
    <div id="rejectModal-{{ $entry->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 dark:border-gray-700">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Tolak Sertifikat</h3>
                <form action="{{ route('admin.sertifikat.reject', $entry->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="keterangan-{{ $entry->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Keterangan Penolakan
                        </label>
                        <textarea 
                            name="keterangan" 
                            id="keterangan-{{ $entry->id }}" 
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                            required
                        ></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                class="cancel-reject px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition">
                            Konfirmasi Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

<script>
document.addEventListener('DOMContentLoaded', () => {
    // ============== BULK DELETE FUNCTIONALITY ==============
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const certificateCheckboxes = document.querySelectorAll('.certificate-checkbox');
    const certificateCards = document.querySelectorAll('.certificate-card');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCount = document.getElementById('selectedCount');
    const totalSelected = document.getElementById('totalSelected');
    const selectedIdsInput = document.getElementById('selectedIdsInput');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');

    // Function to update card border based on checkbox state
    function updateCardBorder(checkbox) {
        const card = checkbox.closest('.certificate-card');
        if (checkbox.checked) {
            card.classList.add('border-red-500', 'border-4');
            card.classList.remove('border-2');
        } else {
            card.classList.remove('border-red-500', 'border-4');
            card.classList.add('border-2');
        }
    }
    
    function updateSelectedCount() {
        const checked = document.querySelectorAll('.certificate-checkbox:checked');
        const count = checked.length;
        
        if (selectedCount) selectedCount.textContent = count;
        if (totalSelected) totalSelected.textContent = count;
        
        if (bulkDeleteBtn) {
            bulkDeleteBtn.disabled = count === 0;
        }
        
        // Update selected IDs input
        if (selectedIdsInput) {
            const ids = Array.from(checked).map(cb => cb.value);
            selectedIdsInput.value = JSON.stringify(ids);
        }
        
        // Update select all checkbox state
        if (selectAllCheckbox) {
            if (count === certificateCheckboxes.length) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else if (count === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.indeterminate = true;
            }
        }
    }

    // Select all functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            certificateCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                updateCardBorder(checkbox);
            });
            updateSelectedCount();
        });
    }

    // Individual checkbox change
    certificateCheckboxes.forEach(checkbox => {
        // Set initial border
        updateCardBorder(checkbox);
        
        checkbox.addEventListener('change', function() {
            updateCardBorder(this);
            updateSelectedCount();
        });
    });

    // Bulk delete button click
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const checkedCount = document.querySelectorAll('.certificate-checkbox:checked').length;
            
            if (checkedCount === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Ada Data Dipilih',
                    text: 'Silakan pilih sertifikat yang ingin dihapus.',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            const confirmed = await Swal.fire({
                title: 'Hapus Sertifikat Terpilih?',
                text: `${checkedCount} sertifikat akan dihapus permanen dan tidak bisa dikembalikan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus Semua',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });

            if (confirmed.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const formData = new FormData();
                    const ids = Array.from(
                        document.querySelectorAll('.certificate-checkbox:checked')
                    ).map(cb => cb.value);

                    formData.append('_method', 'DELETE');
                    formData.append('_token', '{{ csrf_token() }}');

                    ids.forEach(id => {
                        formData.append('selected_ids[]', id);
                    });

                    const response = await fetch('{{ route("admin.sertifikat.bulk-destroy") }}', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        await Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: result.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        window.location.reload();
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: error.message || 'Terjadi kesalahan saat menghapus data.',
                        confirmButtonColor: '#dc2626'
                    });
                }
            }
        });
    }

    // ============== SINGLE DELETE FUNCTIONALITY ==============
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();

            const confirmed = await Swal.fire({
                title: 'Hapus Sertifikat?',
                text: 'Sertifikat ini akan dihapus permanen dan tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });

            if (confirmed.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                this.closest('form').submit();
            }
        });
    });

    // ============== APPROVE FUNCTIONALITY ==============
    document.querySelectorAll('.approve-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();

            const confirmed = await Swal.fire({
                title: 'Terima Sertifikat?',
                text: 'Sertifikat akan diterima dan status akan menjadi "Di Terima".',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Terima',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });

            if (confirmed.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                this.closest('form').submit();
            }
        });
    });

    // ============== REJECT MODAL FUNCTIONALITY ==============
    document.querySelectorAll('.reject-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            document.getElementById(`rejectModal-${id}`).style.display = 'block';
        });
    });

    document.querySelectorAll('.cancel-reject').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.fixed').style.display = 'none';
        });
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('fixed')) {
            e.target.style.display = 'none';
        }
    });

    // ============== SWEET ALERT MESSAGES ==============
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

    // Initial count update
    updateSelectedCount();
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

#selectAllCheckbox:indeterminate {
    background-color: #4f46e5;
    border-color: #4f46e5;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 4'%3E%3Cpath stroke='white' d='M0 2h4'/%3E%3C/svg%3E");
}

/* Transisi untuk border card */
.certificate-card {
    transition: all 0.2s ease-in-out;
}

.certificate-card.border-red-500 {
    box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.1);
}

</style>

@endsection