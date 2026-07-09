@extends('Layout.Layout')
@section('title', 'Sertifikat Saya')
@section('content')
    <div id="sertifikat-page-container" class="p-3 sm:p-6 lg:p-8 dark:bg-gray-700 rounded-2xl" data-page-info="popup.admin_mange_sertifikat">

        {{-- ===== HEADER ===== --}}
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-50"
                data-translate="title_srtfkt" data-translate-page="admin">
                Kelola Semua Sertifikat
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-200 mt-1">
                <span data-translate="desc_srtfkt" data-translate-page="admin">
                    Kelola Semua Sertifikat Milik Mahasiswa
                </span>
            </p>
        </div>

        {{-- ===== ACTION BUTTONS ===== --}}
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end mb-6">
            {{-- Bulk Delete --}}
            <button type="button" id="bulkDeleteBtnSertifikat"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition shadow-md disabled:opacity-50 disabled:cursor-not-allowed text-sm font-medium w-full sm:w-auto">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span data-translate="delete_selected" data-translate-page="project_detail">Hapus Terpilih</span>
                (<span id="selectedCountSertifikat">0</span>)
            </button>

            {{-- Bulk Approve --}}
            <button type="button" id="bulkApproveBtnSertifikat"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-green-600 text-white rounded-xl hover:bg-green-700 transition shadow-md disabled:opacity-50 disabled:cursor-not-allowed text-sm font-medium w-full sm:w-auto">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span data-translate="approve_selected" data-translate-page="admin">Setujui Terpilih</span>
                (<span id="selectedCountApproveSertifikat">0</span>)
            </button>

            {{-- Add Certificate --}}
            <a href="{{ route('admin.sertifikat.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition shadow-md text-sm font-medium w-full sm:w-auto">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span data-translate="add_srtfkt" data-translate-page="admin">Tambah Sertifikat</span>
            </a>
        </div>

        {{-- ===== FILTER SECTION ===== --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md mb-6 border border-gray-100 dark:border-gray-800">
            {{-- Filter Toggle Button (Mobile) --}}
            <button type="button" id="filterToggleBtnSertifikat"
                class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 sm:hidden">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Filter & Pencarian
                </span>
                <svg id="filterChevronSertifikat" class="w-4 h-4 transition-transform duration-200" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div id="filterContentSertifikat" class="hidden sm:block p-4 sm:p-6">
                <form action="{{ route('admin.sertifikat.index') }}" method="GET" id="filterForm">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3">
                        {{-- Search --}}
                        <div class="sm:col-span-2 xl:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                <span data-translate="srch_usr" data-translate-page="admin">Pencarian</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ $search ?? '' }}"
                                    placeholder="Cari mahasiswa atau sertifikat..."
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-100">
                                <div class="absolute left-3 top-2.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Angkatan --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                <span data-translate="agkt_addusr" data-translate-page="admin">Angkatan</span>
                            </label>
                            <select name="angkatan"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-100">
                                <option data-translate="all_cohorts" data-translate-page="admin" value="">Semua</option>
                                @foreach($angkatans as $angkatanItem)
                                    <option value="{{ $angkatanItem->id }}" {{ $angkatan == $angkatanItem->id ? 'selected' : '' }}>
                                        {{ $angkatanItem->nama_angkatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Jurusan --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                <span data-translate="jrs_addusr" data-translate-page="admin">Jurusan</span>
                            </label>
                            <select name="jurusan"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-100">
                                <option data-translate="all_jrs" data-translate-page="admin" value="">Semua</option>
                                @foreach($jurusans as $jurusanItem)
                                    <option value="{{ $jurusanItem->id_jurusan }}" {{ $jurusan == $jurusanItem->id_jurusan ? 'selected' : '' }}>
                                        {{ $jurusanItem->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Keahlian --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                <span data-translate="exp_addusr" data-translate-page="admin">Keahlian</span>
                            </label>
                            <select name="keahlian"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-100">
                                <option data-translate="all_skill" data-translate-page="admin" value="">Semua</option>
                                @foreach($keahlians as $keahlianItem)
                                    <option value="{{ $keahlianItem->id_keahlian }}" {{ $keahlian == $keahlianItem->id_keahlian ? 'selected' : '' }}>
                                        {{ $keahlianItem->nama_keahlian }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                <span data-translate="stat_filter" data-translate-page="admin">Status</span>
                            </label>
                            <select name="status_pengajuan"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-100">
                                <option data-translate="stat_filter_all" data-translate-page="admin" value="">Semua</option>
                                @foreach($statusOptions as $status)
                                    <option value="{{ $status }}" {{ $status_pengajuan == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4 gap-2">
                        <a href="{{ route('admin.sertifikat.index') }}"
                            class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            Reset
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            <span data-translate="trp_filter" data-translate-page="admin">Terapkan Filter</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ===== SELECT ALL BAR ===== --}}
        @if(!$sertifikat->isEmpty())
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-3 mb-4 border border-gray-100 dark:border-gray-800">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="selectAllCheckboxSertifikat"
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            data-translate="plh_semua" data-translate-page="admin">Pilih Semua</span>
                    </label>
                    <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                        <span>
                            {{ $sertifikat->firstItem() }}–{{ $sertifikat->lastItem() }}
                            <span data-translate="dr" data-translate-page="admin">dari</span>
                            {{ $sertifikat->total() }}
                        </span>
                        <span class="text-indigo-600 font-semibold">
                            Dipilih: <span id="totalSelectedSertifikat">0</span>
                        </span>
                    </div>
                </div>
            </div>
        @endif

        {{-- ===== DATA SERTIFIKAT ===== --}}
        @if ($sertifikat->isEmpty())
            <div class="text-center py-16 bg-gray-50 dark:bg-gray-900 dark:border-gray-900 rounded-xl border border-gray-200">
                <svg class="w-14 h-14 mx-auto text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-4 text-gray-600 dark:text-gray-200 font-medium"
                    data-translate="empty_srtfkt" data-translate-page="admin">
                    Tidak ada sertifikat yang ditemukan.
                </p>
                @if($search || $angkatan || $jurusan || $keahlian || $status_pengajuan)
                    <p class="text-gray-500 dark:text-gray-50 text-sm mt-2">Coba atur ulang filter pencarian Anda.</p>
                    <a href="{{ route('admin.sertifikat.index') }}"
                        class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm">
                        Reset Semua Filter
                    </a>
                @else
                    <p class="text-gray-500 dark:text-gray-50 text-sm mt-2"
                        data-translate="empty_desc_srtfkt" data-translate-page="admin">
                        Mulai tambahkan sertifikat pertama!
                    </p>
                @endif
            </div>
        @else
            <form id="bulkDeleteForm" action="{{ route('admin.sertifikat.bulk-destroy') }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
                <input type="hidden" name="selected_ids" id="selectedIdsInputSertifikat" value="">
            </form>

            {{-- Card Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach ($sertifikat as $entry)
                    @php
                        $expiredAt = $entry->expired_date ? \Carbon\Carbon::parse($entry->expired_date) : null;
                        $validityStatus = $expiredAt
                            ? ($expiredAt->isFuture() || $expiredAt->isToday() ? 'Masih Berlaku' : 'Kadaluarsa')
                            : 'Permanen';
                        $validityClass = $expiredAt
                            ? ($expiredAt->isFuture() || $expiredAt->isToday() ? 'text-green-600' : 'text-red-600')
                            : 'text-indigo-600';
                        $expiredLabel = $expiredAt ? $expiredAt->format('d F Y') : null;

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

                        $canEdit = in_array($entry->status_pengajuan, ['Di Terima', 'Sedang Di Ajukan']);
                    @endphp

                    <div class="certificate-card bg-white dark:border-gray-900 dark:bg-gray-900 dark:text-gray-200 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all border-2 relative flex flex-col"
                        data-id="{{ $entry->id }}">

                        {{-- Checkbox --}}
                        <div class="absolute top-3 left-3 z-20">
                            <input type="checkbox" name="certificate_ids[]" value="{{ $entry->id }}"
                                class="certificate-checkbox w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 cursor-pointer">
                        </div>

                        {{-- Card Header --}}
                        <div class="bg-gradient-to-r from-blue-700 to-blue-500 px-4 py-3 pl-11">
                            <div class="flex items-center justify-between">
                                <svg class="w-7 h-7 text-white flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusClass }} whitespace-nowrap ml-2">
                                    {{ $statusText }}
                                </span>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-4 flex-1 flex flex-col gap-3">

                            {{-- Nama Sertifikat & Mahasiswa --}}
                            <div>
                                <p class="text-xs text-gray-400 dark:text-gray-400 mb-0.5"
                                    data-translate="nm_srtfkt" data-translate-page="admin">Nama Sertifikat</p>
                                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 line-clamp-2 leading-snug">
                                    {{ $entry->nama_sertifikat }}
                                </h3>
                            </div>

                            <div class="border-t border-gray-100 dark:border-gray-800 pt-3">
                                <p class="text-xs text-gray-400 dark:text-gray-400 mb-0.5"
                                    data-translate="nm_mhs_srtfkt" data-translate-page="admin">Mahasiswa</p>
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                                    {{ $entry->mahasiswa->nama_mahasiswa }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ $entry->mahasiswa->angkatan->nama_angkatan ?? '-' }} &bull;
                                    {{ $entry->mahasiswa->jurusan->nama_jurusan ?? '-' }}
                                </p>
                            </div>

                            {{-- Info Grid --}}
                            <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs border-t border-gray-100 dark:border-gray-800 pt-3">
                                <div>
                                    <p class="text-gray-400 mb-0.5">Lembaga</p>
                                    <p class="text-gray-700 dark:text-gray-300 font-medium truncate">{{ $entry->lembaga_penerbit }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 mb-0.5">Terbit</p>
                                    <p class="text-gray-700 dark:text-gray-300 font-medium">
                                        {{ \Carbon\Carbon::parse($entry->tanggal_terbit)->format('d M Y') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-400 mb-0.5">Berlaku</p>
                                    <p class="font-semibold {{ $validityClass }}">{{ $validityStatus }}</p>
                                </div>
                                @if($expiredLabel)
                                    <div>
                                        <p class="text-gray-400 mb-0.5">Kadaluarsa</p>
                                        <p class="text-gray-700 dark:text-gray-300 font-medium">{{ $expiredLabel }}</p>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-gray-400 mb-0.5">Pengajuan</p>
                                    <p class="font-semibold
                                        @if($entry->status_pengajuan == 'Di Terima') text-green-600
                                        @elseif($entry->status_pengajuan == 'Sedang Di Ajukan') text-yellow-600
                                        @elseif($entry->status_pengajuan == 'Di Tolak') text-red-600
                                        @endif">
                                        {{ $entry->status_pengajuan }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-400 mb-0.5">Status Aktif</p>
                                    <p class="font-semibold {{ $entry->is_active ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $entry->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Link Sertifikat --}}
                            @if($entry->link_sertifikat && $entry->status_pengajuan == 'Di Terima' && $entry->is_active == 1)
                                <a href="{{ asset('storage/' . $entry->link_sertifikat) }}" target="_blank"
                                    class="inline-flex items-center text-xs text-indigo-600 hover:text-indigo-800 hover:underline">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    <span data-translate="sertifikat_link" data-translate-page="sertifikat_user">Lihat Sertifikat</span>
                                </a>
                            @endif

                            {{-- Keterangan --}}
                            @if($entry->keterangan)
                                <div class="p-2.5 bg-gray-50 dark:bg-gray-800 rounded-lg border-l-4 border-gray-400">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Keterangan:</p>
                                    <p class="text-xs text-gray-700 dark:text-gray-300">{{ $entry->keterangan }}</p>
                                </div>
                            @endif

                            {{-- Timestamps --}}
                            <p class="text-xs text-gray-400 mt-auto pt-3 border-t border-gray-100 dark:border-gray-800">
                                Ditambahkan: {{ $entry->created_at ? $entry->created_at->format('d M Y') : '-' }}
                                @if($entry->created_at != $entry->updated_at)
                                    &bull; Diupdate: {{ $entry->updated_at->format('d M Y') }}
                                @endif
                            </p>

                            {{-- ===== ACTION BUTTONS ===== --}}
                            <div class="pt-3 border-t border-gray-100 dark:border-gray-700 space-y-2">

                                {{-- Edit + Delete --}}
                                <div class="grid gap-2" style="{{ $canEdit ? 'grid-template-columns: 1fr 1fr' : 'grid-template-columns: 1fr' }}">
                                    @if($canEdit)
                                        <a href="{{ route('admin.sertifikat.details', ['id' => $entry->id]) }}"
                                            class="flex items-center justify-center gap-1.5 py-2.5 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-all shadow-sm">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-7-10l3 3m0 0l-3 3m3-3H9" />
                                            </svg>
                                            <span data-translate="edit_srtfkt" data-translate-page="admin">Edit</span>
                                        </a>
                                    @endif

                                    <form action="{{ route('admin.sertifikat.destroy', ['id' => $entry->id]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Hapus Sertifikat? Sertifikat ini akan dihapus permanen dan tidak bisa dikembalikan.')"
                                            class="w-full flex items-center justify-center gap-1.5 py-2.5 px-3 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-medium transition-all shadow-sm">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span data-translate="hapus_srtfkt" data-translate-page="admin">Hapus</span>
                                        </button>
                                    </form>
                                </div>

                                {{-- Approve / Reject (hanya untuk "Sedang Di Ajukan") --}}
                                @if($entry->status_pengajuan == 'Sedang Di Ajukan')
                                    <div class="grid grid-cols-2 gap-2">
                                        <form action="{{ route('admin.sertifikat.approve', ['id' => $entry->id]) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="button"
                                                class="approve-btn w-full py-2.5 bg-green-50 text-green-700 rounded-xl hover:bg-green-100 transition text-sm font-medium border border-green-200 dark:bg-green-900 dark:text-green-200 dark:border-green-800">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Terima
                                            </button>
                                        </form>
                                        <button type="button"
                                            class="reject-btn w-full py-2.5 bg-orange-50 text-orange-700 rounded-xl hover:bg-orange-100 transition text-sm font-medium border border-orange-200 dark:bg-orange-900 dark:text-orange-200 dark:border-orange-800"
                                            data-id="{{ $entry->id }}">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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

            {{-- Pagination --}}
            <div class="mt-6" data-pagination-group="admin_sertifikats">
                {{ $sertifikat->render('vendor.pagination.custom_ajax', ['groupName' => 'admin_sertifikats']) }}
            </div>
        @endif
    </div>

    {{-- ===== REJECT MODALS ===== --}}
    @foreach($sertifikat as $entry)
        @if($entry->status_pengajuan == 'Sedang Di Ajukan')
            <div id="rejectModal-{{ $entry->id }}"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 px-4">
                <div class="relative top-16 mx-auto p-5 border max-w-sm w-full shadow-xl rounded-xl bg-white dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">Tolak Sertifikat</h3>
                    <form action="{{ route('admin.sertifikat.reject', ['id' => $entry->id]) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="mb-4">
                            <label for="keterangan-{{ $entry->id }}"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Keterangan Penolakan
                            </label>
                            <textarea name="keterangan" id="keterangan-{{ $entry->id }}" rows="3"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                                required></textarea>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button"
                                class="cancel-reject px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-medium">
                                Konfirmasi Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach


    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        #selectAllCheckboxSertifikat:indeterminate {
            background-color: #4f46e5;
            border-color: #4f46e5;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 4'%3E%3Cpath stroke='white' d='M0 2h4'/%3E%3C/svg%3E");
        }

        .certificate-card {
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .certificate-card.border-indigo-500 {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        /* Filter chevron transition */
        #filterChevronSertifikat {
            transition: transform 0.2s ease;
        }
    </style>

@endsection