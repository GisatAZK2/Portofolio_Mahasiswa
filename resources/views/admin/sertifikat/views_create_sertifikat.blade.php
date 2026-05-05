@extends('Layout.Layout')
@section('title', 'Tambah Sertifikat Baru')

@section('content')
    <div class="min-h-screen">
        <div class="p-8">
            <!-- Header -->
            <div class="mb-8 text-center md:text-left">
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100" data-translate="title_srtfkt_create"
                        data-translate-page="admin">
                        Tambah Sertifikat Baru
                    </h1>
                </div>
                <p class="mt-2 text-gray-600 dark:text-gray-400" data-translate="desc_srtfkt_create"
                    data-translate-page="admin">
                    Tambahkan sertifikat yang diperoleh mahasiswa untuk melengkapi portofolio mereka.
                </p>
            </div>

            <!-- Error Global -->
            @if ($errors->any())
                <div
                    class="mb-8 p-5 bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300 rounded-xl">
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

            <form method="POST" action="{{ route('admin.sertifikat.store') }}" enctype="multipart/form-data"
                class="space-y-7" id="sertifikatForm">
                @csrf

                <!-- Filter Mahasiswa -->
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4" data-translate="filter_mhs"
                        data-translate-page="admin">
                        Filter Mahasiswa
                    </h3>

                    <!-- Search Bar -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="search-input" name="search" placeholder="Cari nama mahasiswa..."
                                value="{{ $search ?? '' }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition">
                            <div class="absolute left-3 top-3.5 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Dropdowns -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                data-translate="angkatan" data-translate-page="admin">
                                Angkatan
                            </label>
                            <select name="angkatan" id="angkatan-filter"
                                class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                                <option data-translate="all_cohorts" data-translate-page="admin" value="">Semua Angkatan
                                </option>
                                @foreach($angkatans as $angk)
                                    <option value="{{ $angk->id }}" {{ ($angkatan ?? '') == $angk->id ? 'selected' : '' }}>
                                        {{ $angk->nama_angkatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                data-translate="jrs_addusr" data-translate-page="admin">Jurusan</label>
                            <select name="jurusan" id="jurusan-filter"
                                class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                                <option data-translate="all_jrs" data-translate-page="admin" value="">Semua Jurusan</option>
                                @foreach($jurusans as $jrs)
                                    <option value="{{ $jrs->id }}" {{ ($jurusan ?? '') == $jrs->id ? 'selected' : '' }}>
                                        {{ $jrs->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                data-translate="keahlian" data-translate-page="admin">Keahlian</label>
                            <select name="keahlian" id="keahlian-filter"
                                class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                                <option data-translate="all_skill" data-translate-page="admin" value="">Semua Keahlian
                                </option>
                                @foreach($keahlians as $keahlianItem)
                                    <option value="{{ $keahlianItem->id }}" {{ ($keahlian ?? '') == $keahlianItem->id ? 'selected' : '' }}>
                                        {{ $keahlianItem->nama_keahlian }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-4">
                        <a href="{{ route('admin.sertifikat.create') }}"
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            Reset Filter
                        </a>
                        <button type="button" onclick="applyFilters()"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            <span data-translate="trp_filter" data-translate-page="admin">Terapkan Filter</span>
                        </button>
                    </div>
                </div>

                <!-- Pilih Mahasiswa -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">
                        <span data-translate="pilih_mhs" data-translate-page="admin">Pilih Mahasiswa</span> <span
                            class="text-red-500">*</span>
                    </label>

                    <!-- Selected User Display -->
                    <div id="selected-user-display" class="mb-6 hidden">
                        <div
                            class="p-5 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-2xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4" id="selected-user-content">
                                    <!-- Diisi oleh JavaScript -->
                                </div>
                                <button type="button" onclick="clearSelectedUser()"
                                    class="text-green-600 dark:text-green-400 hover:text-red-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="user_id" id="selected-user-id" value="{{ old('user_id') }}">

                    <!-- Search untuk Mahasiswa -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="user-search" placeholder="Cari nama mahasiswa..."
                                class="w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 outline-none transition text-sm">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Table Container untuk Mahasiswa -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-10" data-translate="pick" data-translate-page="admin">Pilih</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" data-translate="mhs" data-translate-page="admin">Mahasiswa</th>
                                        <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" data-translate="agkt" data-translate-page="admin">Angkatan</th>
                                        <th class="hidden lg:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" data-translate="jrs" data-translate-page="admin">Jurusan</th>
                                        <th class="hidden xl:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" data-translate="khl" data-translate-page="admin">Keahlian</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider text-center w-20" data-translate="act" data-translate-page="admin">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="user-table-body"
                                    class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($users as $user)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer group"
                                            onclick="selectUser({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa) }}', '{{ $user->photo_profile ?? '' }}', '{{ $user->email ?? '' }}')">
                                            <td class="px-4 py-4">
                                                <input type="radio" name="user_radio" value="{{ $user->id }}"
                                                    class="user-radio w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                                    {{ old('user_id') == $user->id ? 'checked' : '' }}
                                                    onchange="event.stopImmediatePropagation(); selectUser({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa) }}', '{{ $user->photo_profile ?? '' }}', '{{ $user->email ?? '' }}')">
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-shrink-0">
                                                        @if($user->photo_profile && file_exists(public_path('storage/' . $user->photo_profile)))
                                                            <img src="{{ asset('storage/' . $user->photo_profile) }}"
                                                                class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-700"
                                                                alt="{{ $user->nama_mahasiswa }}">
                                                        @else
                                                            <div
                                                                class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center ring-2 ring-white dark:ring-gray-700">
                                                                <span
                                                                    class="text-indigo-600 dark:text-indigo-300 font-medium text-sm">
                                                                    {{ strtoupper(substr($user->nama_mahasiswa, 0, 2)) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="font-medium text-gray-900 dark:text-gray-100 text-sm">
                                                            {{ $user->nama_mahasiswa }}
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="hidden md:table-cell px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $user->angkatan->nama_angkatan ?? $user->angkatan->tahun_angkatan ?? '-' }}
                                            </td>
                                            <td class="hidden lg:table-cell px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $user->jurusan->nama_jurusan ?? '-' }}
                                            </td>
                                            <td class="hidden xl:table-cell px-4 py-4">
                                                <span
                                                    class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    {{ $user->keahlian->nama_keahlian ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <a href="{{ route('portfolio.show', $user->id) }}"
                                                    onclick="event.stopImmediatePropagation()"
                                                    class="text-indigo-600 hover:text-indigo-700 text-sm font-medium inline-block">
                                                    <span data-translate="see" data-translate-page="admin">Lihat</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-14 h-14 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                        </path>
                                                    </svg>
                                                    <p class="font-medium">Tidak ada mahasiswa ditemukan</p>
                                                    <p class="text-sm mt-1">Coba ubah filter pencarian anda</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-5" id="pagination-links">
                        {{ $users->links() }}
                    </div>

                    @error('user_id')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Sertifikat -->
                <div>
                    <label for="nama_sertifikat" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="nm_srtfkt_create" data-translate-page="admin">Nama Sertifikat</span> <span
                            class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_sertifikat" id="nama_sertifikat" value="{{ old('nama_sertifikat') }}"
                        required placeholder="Contoh: Sertifikat Kompetensi Web Developer"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition @error('nama_sertifikat') border-red-500 @enderror">
                    @error('nama_sertifikat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lembaga Penerbit -->
                <div>
                    <label for="lembaga_penerbit" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="lembaga_penerbit" data-translate-page="admin">Lembaga Penerbit</span> <span
                            class="text-red-500">*</span>
                    </label>
                    <input type="text" name="lembaga_penerbit" id="lembaga_penerbit" value="{{ old('lembaga_penerbit') }}"
                        required placeholder="Contoh: Dicoding, Coursera, Google, Kampus Merdeka"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition @error('lembaga_penerbit') border-red-500 @enderror">
                    @error('lembaga_penerbit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Terbit -->
                <div>
                    <label for="tanggal_terbit" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="tggl_terbit" data-translate-page="admin">Tanggal Terbit</span> <span
                            class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_terbit" id="tanggal_terbit" value="{{ old('tanggal_terbit') }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition @error('tanggal_terbit') border-red-500 @enderror">
                    @error('tanggal_terbit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sertifikat Berlaku Permanen -->
                <div class="flex items-center gap-3 mb-4">
                    <input type="checkbox" id="permanent" name="permanent" value="1"
                        {{ old('permanent') ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="permanent" class="text-sm font-medium text-gray-900 dark:text-gray-300">
                        <span data-translate="permanent_cert" data-translate-page="admin">Sertifikat Berlaku Permanen</span>
                    </label>
                </div>

                <!-- Tanggal Expired -->
                <div id="expired-date-container">
                    <label for="expired_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="exp_date" data-translate-page="admin">Tanggal Expired</span> <span
                            class="text-red-500">*</span>
                    </label>
                    <input type="date" name="expired_date" id="expired_date" value="{{ old('expired_date') }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition @error('expired_date') border-red-500 @enderror">
                    @error('expired_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Upload File Sertifikat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="upload_srtfkt" data-translate-page="admin">Upload File Sertifikat</span> <span
                            class="text-red-500">*</span>
                    </label>
                    <div id="drop-zone"
                        class="mt-1 flex justify-center px-6 pt-8 pb-8 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl hover:border-indigo-500 dark:hover:border-indigo-400 transition cursor-pointer"
                        onclick="document.getElementById('link_sertifikat').click()">
                        <div class="space-y-3 text-center">
                            <svg class="mx-auto h-14 w-14 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H8a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                <label
                                    class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                    <span data-translate="up_file" data-translate-page="admin">Upload file</span>
                                    <input id="link_sertifikat" name="link_sertifikat" type="file"
                                        accept="image/jpeg,image/png,image/gif,image/jpg" class="sr-only"
                                        onchange="updateFileLabel(this)">
                                </label>
                                <p class="pl-1" data-translate="or_drag" data-translate-page="admin">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400" id="file-name"
                                data-translate="format_srtfkt" data-translate-page="admin">PNG, JPG, GIF maksimal 5MB</p>
                        </div>
                    </div>
                    @error('link_sertifikat')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview -->
                <div id="image-preview-container"
                    class="hidden mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Preview Sertifikat:</p>
                    <img id="image-preview" src="#" alt="Preview" class="max-h-64 w-full object-contain rounded-xl shadow">
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-4 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.sertifikat.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-500 hover:bg-gray-600 active:bg-gray-700 text-white rounded-lg transition duration-200 font-medium shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span data-translate="cncl" data-translate-page="admin">Batal</span>
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-lg transition duration-200 font-medium shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span data-translate="save_srtfkt" data-translate-page="admin">Simpan Sertifikat</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Apply Filter
        function applyFilters() {
            const url = new URL(window.location.href);
            url.searchParams.set('search', document.getElementById('search-input').value);
            url.searchParams.set('angkatan', document.getElementById('angkatan-filter').value);
            url.searchParams.set('jurusan', document.getElementById('jurusan-filter').value);
            url.searchParams.set('keahlian', document.getElementById('keahlian-filter').value);
            window.location.href = url.toString();
        }

        // Select User
        function selectUser(userId, userName, photoProfile, email) {
            document.getElementById('selected-user-id').value = userId;

            document.querySelectorAll('.user-radio').forEach(radio => {
                radio.checked = (radio.value == userId);
            });

            const display = document.getElementById('selected-user-display');
            const content = document.getElementById('selected-user-content');

            let photoHtml = photoProfile
                ? `<img src="${photoProfile}" class="w-12 h-12 rounded-2xl object-cover border border-white dark:border-gray-700 shadow" alt="${userName}">`
                : `<div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-2xl shadow">
                            ${userName.charAt(0).toUpperCase()}
                       </div>`;

            content.innerHTML = `
                    <div class="flex items-center gap-4">
                        ${photoHtml}
                        <div>
                            <div class="font-semibold text-lg text-green-800 dark:text-green-200">${userName}</div>
                            <div class="text-sm text-green-700 dark:text-green-300">${email}</div>
                        </div>
                    </div>
                `;

            display.classList.remove('hidden');
        }

        // Clear Selected User
        function clearSelectedUser() {
            document.getElementById('selected-user-id').value = '';
            document.querySelectorAll('.user-radio').forEach(radio => radio.checked = false);
            document.getElementById('selected-user-display').classList.add('hidden');
        }

        // File Upload & Preview
        function updateFileLabel(input) {
            const fileNameEl       = document.getElementById('file-name');
            const previewContainer = document.getElementById('image-preview-container');
            const previewImg       = document.getElementById('image-preview');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                fileNameEl.textContent = file.name;

                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // ── Permanent Certificate Toggle ────────────────────────────────────
        const permanentCheckbox    = document.getElementById('permanent');
        const expiredDateContainer = document.getElementById('expired-date-container');
        const expiredDateInput     = document.getElementById('expired_date');

        function updateExpiredDateState() {
            if (permanentCheckbox.checked) {
                expiredDateContainer.style.display = 'none';
                expiredDateInput.required           = false;
                expiredDateInput.value              = '';
            } else {
                expiredDateContainer.style.display = 'block';
                expiredDateInput.required           = true;
            }
        }

        if (permanentCheckbox) {
            permanentCheckbox.addEventListener('change', updateExpiredDateState);
            updateExpiredDateState(); // run on page load
        }

        // ── Date validation (expired_date must be after tanggal_terbit) ───────
        const tanggalTerbitInput = document.getElementById('tanggal_terbit');

        function updateMinExpiredDate() {
            if (tanggalTerbitInput.value) {
                // Set minimum date untuk expired_date ke hari setelah tanggal_terbit
                const terbitDate = new Date(tanggalTerbitInput.value + 'T00:00:00');
                const minDate = new Date(terbitDate);
                minDate.setDate(minDate.getDate() + 1);
                
                expiredDateInput.min = minDate.toISOString().split('T')[0];
                
                // Jika expired_date sudah dipilih dan lebih awal dari tanggal_terbit, kosongkan
                if (expiredDateInput.value) {
                    const expiredDate = new Date(expiredDateInput.value + 'T00:00:00');
                    if (expiredDate <= terbitDate) {
                        expiredDateInput.value = '';
                    }
                }
            }
        }

        function validateExpiredDate() {
            if (!expiredDateInput.value || !tanggalTerbitInput.value) return;
            
            const terbitDate = new Date(tanggalTerbitInput.value + 'T00:00:00');
            const expiredDate = new Date(expiredDateInput.value + 'T00:00:00');
            
            if (expiredDate <= terbitDate) {
                expiredDateInput.value = '';
                alert('Tanggal expired harus setelah tanggal terbit');
            }
        }

        if (tanggalTerbitInput) {
            tanggalTerbitInput.addEventListener('change', updateMinExpiredDate);
            // Validasi saat halaman dimuat
            updateMinExpiredDate();
        }

        if (expiredDateInput) {
            expiredDateInput.addEventListener('change', validateExpiredDate);
        }

        // ── Drag and Drop ───────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            const dropZone  = document.getElementById('drop-zone');
            const fileInput = document.getElementById('link_sertifikat');

            if (dropZone && fileInput) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, e => {
                        e.preventDefault();
                        e.stopPropagation();
                    });
                });

                dropZone.addEventListener('dragenter', () => dropZone.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20'));
                dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20'));
                dropZone.addEventListener('drop', (e) => {
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        fileInput.files = files;
                        updateFileLabel(fileInput);
                    }
                    dropZone.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                });
            }

            // Load old selection
            const oldUserId = document.getElementById('selected-user-id').value;
            if (oldUserId) {
                const radio = document.querySelector(`.user-radio[value="${oldUserId}"]`);
                if (radio) {
                    const row   = radio.closest('tr');
                    if (row) {
                        const name  = row.cells[1].querySelector('.font-medium').textContent.trim();
                        const email = row.cells[1].querySelector('.text-xs').textContent.trim();
                        const img   = row.querySelector('img');
                        const photo = img ? img.src : '';
                        selectUser(oldUserId, name, photo, email);
                    }
                }
            }

            // Enter key on filter search
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        applyFilters();
                    }
                });
            }

            // User search (client-side filter on table rows)
            const userSearchInput = document.getElementById('user-search');
            if (userSearchInput) {
                userSearchInput.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase().trim();
                    const rows       = document.querySelectorAll('#user-table-body tr');

                    rows.forEach(row => {
                        if (!row.cells || row.cells.length < 2) return;

                        const nameEl  = row.cells[1].querySelector('.font-medium');
                        const emailEl = row.cells[1].querySelector('.text-xs');
                        if (!nameEl) return;

                        const name  = nameEl.textContent.toLowerCase();
                        const email = emailEl ? emailEl.textContent.toLowerCase() : '';

                        row.style.display = (name.includes(searchTerm) || email.includes(searchTerm)) ? '' : 'none';
                    });
                });
            }
        });
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.add_sertifikat");
        });
    </script>
@endsection