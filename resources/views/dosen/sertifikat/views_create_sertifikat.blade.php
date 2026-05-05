@extends('Layout.Layout')
@section('title', 'Tambah Sertifikat Baru')

@section('content')
    <div class="min-h-screen">
        <div class="p-8">
            <!-- Header -->
            <div class="mb-8 text-center md:text-left">
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100" data-translate="ttl_stk" data-translate-page="dosen_stk_add">Tambah Sertifikat Baru</h1>
                </div>
                <p class="mt-2 text-gray-600 dark:text-gray-200" data-translate="desc_stk" data-translate-page="dosen_stk_add">Tambahkan sertifikat yang kamu peroleh untuk melengkapi
                    portofoliomu.</p>
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
            <form method="POST" action="{{ route('dosen.sertifikat.store') }}" enctype="multipart/form-data"
                class="space-y-7">
                @csrf

                <!-- Search and Filter Section -->
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4" data-translate="filter" data-translate-page="dosen_stk_add">Filter Mahasiswa</h3>

                    <!-- Search Bar -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" name="search" id="search-input" placeholder="Cari nama mahasiswa..."
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
                        <!-- Filter Angkatan -->
                        <div>
                            <label data-translate="agkt" data-translate-page="dosen_stk_add" for="angkatan-filter"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Angkatan
                            </label>
                            <select name="angkatan" id="angkatan-filter"
                                class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition">
                                <option data-translate="all_agkt" data-translate-page="dosen_stk_add" value="">Semua Angkatan</option>
                                @foreach($angkatans as $angk)
                                    <option value="{{ $angk->id }}" {{ ($angkatan ?? '') == $angk->id ? 'selected' : '' }}>
                                        {{ $angk->nama_angkatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Jurusan -->
                        <div>
                            <label data-translate="jrs" data-translate-page="dosen_stk_add" for="jurusan-filter"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jurusan
                            </label>
                            <select name="jurusan" id="jurusan-filter"
                                class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition">
                                <option data-translate="all_jrs" data-translate-page="dosen_stk_add" value="">Semua Jurusan</option>
                                @foreach($jurusans as $jrs)
                                    <option value="{{ $jrs->id }}" {{ ($jurusan ?? '') == $jrs->id ? 'selected' : '' }}>
                                        {{ $jrs->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Keahlian -->
                        <div>
                            <label data-translate="skill" data-translate-page="dosen_stk_add" for="keahlian-filter"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Keahlian
                            </label>
                            <select name="keahlian" id="keahlian-filter"
                                class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition">
                                <option data-translate="all_skill" data-translate-page="dosen_stk_add" value="">Semua Keahlian</option>
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
                        <a href="{{ route('dosen.sertifikat.create') }}"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                            Reset Filter
                        </a>
                        <button data-translate="trp_filter" data-translate-page="dosen_stk_add" type="button" onclick="applyFilters()"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            Terapkan Filter
                        </button>
                    </div>
                </div>

                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="pick_mhs" data-translate-page="dosen_stk_add">Pilih Mahasiswa</span> <span class="text-red-500">*</span>
                    </label>

                    <div id="selected-user-display" class="mb-4 hidden">
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-green-800 font-medium" id="selected-user-name"></span>
                                </div>
                                <button type="button" onclick="clearSelectedUser()"
                                    class="text-green-600 hover:text-green-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Input for Selected User ID -->
                    <input type="hidden" name="user_id" id="selected-user-id" value="{{ old('user_id') }}">

                    <!-- Table of Users -->
                    <div
                        class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th data-translate="pick" data-translate-page="dosen_stk_add"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Pilih
                                    </th>
                                    <th data-translate="nm_mhs" data-translate-page="dosen_stk_add"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nama Mahasiswa
                                    </th>
                                    <th data-translate="agkt" data-translate-page="dosen_stk_add"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Angkatan
                                    </th>
                                    <th data-translate="jrs" data-translate-page="dosen_stk_add"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Jurusan
                                    </th>
                                    <th data-translate="skill" data-translate-page="dosen_stk_add"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Keahlian
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($users as $user)

                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer"
                                        onclick="selectUser({{ $user->id }}, '{{ $user->nama_mahasiswa }}')">
                                        <td class="px-6 py-4">
                                            <input type="radio" name="user_radio" value="{{ $user->id }}"
                                                class="user-radio w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                                {{ old('user_id') == $user->id ? 'checked' : '' }}
                                                onchange="selectUser({{ $user->id }}, '{{ $user->nama_mahasiswa }}')">
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
                                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                    </path>
                                                </svg>
                                                <p class="text-lg font-medium" data-translate="empty_filter" data-translate-page="dosen_stk_add">Tidak ada mahasiswa ditemukan</p>
                                                <p class="text-sm" data-translate="empty_filter_desc" data-translate-page="dosen_stk_add">Coba ubah filter pencarian Anda</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                    @error('user_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Sertifikat -->
                <div>
                    <label for="nama_sertifikat" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="nm_stk" data-translate-page="dosen_stk_add">Nama Sertifikat</span> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_sertifikat" id="nama_sertifikat" value="{{ old('nama_sertifikat') }}"
                        required placeholder="Contoh: Sertifikat Kompetensi Programming"
                        class="w-full px-4 py-3 border dark:text-gray-200 border-gray-300 dark:bg-gray-700 dark:placeholder:text-gray-400 dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition @error('nama_sertifikat') border-red-500 @enderror">
                    @error('nama_sertifikat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lembaga Penerbit -->
                <div>
                    <label for="lembaga_penerbit" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="lembaga" data-translate-page="dosen_stk_add">Lembaga Penerbit</span> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="lembaga_penerbit" id="lembaga_penerbit" value="{{ old('lembaga_penerbit') }}"
                        required placeholder="Contoh: Dicoding, Coursera, Kampus Merdeka"
                        class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:placeholder:text-gray-400 dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition @error('lembaga_penerbit') border-red-500 @enderror">
                    @error('lembaga_penerbit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Terbit -->
                <div>
                    <label for="tanggal_terbit" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Tanggal Terbit <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_terbit" id="tanggal_terbit" value="{{ old('tanggal_terbit') }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition @error('tanggal_terbit') border-red-500 @enderror">
                    @error('tanggal_terbit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sertifikat Berlaku Permanen -->
                <div class="flex items-center gap-3 mb-4">
                    <input type="checkbox" id="permanent" name="permanent" value="1"
                        {{ old('permanent', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="permanent" class="text-sm font-medium text-gray-900 dark:text-gray-300">
                        <span data-translate="permanent_cert" data-translate-page="dosen_stk_add">Sertifikat Berlaku Permanen</span>
                    </label>
                </div>

                <!-- Tanggal Expired -->
                <div id="expired-date-container">
                    <label for="expired_date"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="exp_date" data-translate-page="dosen_stk_add">Tanggal Expired</span>
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="expired_date" id="expired_date" value="{{ old('expired_date') }}"
                        class="w-full px-4 py-3 border border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition @error('expired_date') border-red-500 @enderror">
                    @error('expired_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Upload File Sertifikat -->
                <div>
                    <label for="link_sertifikat" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        <span data-translate="ttl_file" data-translate-page="dosen_stk_add">Upload File Sertifikat</span> <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition cursor-pointer"
                        onclick="document.getElementById('link_sertifikat').click()">
                        <div class="space-y-2 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H8a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label
                                    class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                    <span data-translate="up_file" data-translate-page="dosen_stk_add">Upload file</span>
                                    <input id="link_sertifikat" name="link_sertifikat" type="file"
                                        accept="image/jpeg,image/png,image/gif,image/jpg" class="sr-only"
                                        onchange="updateFileLabel(this)">
                                </label>
                                <p class="pl-1" data-translate="or_drag" data-translate-page="dosen_stk_add">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400" id="file-name">PNG, JPG, GIF up to 5MB</p>
                        </div>
                    </div>
                    @error('link_sertifikat')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span data-translate="format_file" data-translate-page="dosen_stk_add">Format yang diperbolehkan: JPG, JPEG, PNG, GIF. Maksimal ukuran: 5MB</span>
                    </p>
                </div>

                <!-- Preview Gambar -->
                <div id="image-preview-container"
                    class="hidden mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview:</p>
                    <img id="image-preview" src="#" alt="Preview Sertifikat" class="max-h-48 rounded-lg shadow-sm">
                </div>

                <!-- Informasi Tambahan (optional) -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mt-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400 dark:text-blue-300" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1 md:flex md:justify-between">
                            <p data-translate="uped_file" data-translate-page="dosen_stk_add" class="text-sm text-blue-700 dark:text-blue-300">
                                File yang diupload akan tersimpan dan dapat diakses melalui link publik.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-4 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('dosen.sertifikat.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-500 hover:bg-gray-600 active:bg-gray-700 text-white rounded-lg transition duration-200 font-medium shadow-sm hover:shadow-md dark:bg-gray-600 dark:hover:bg-gray-700 dark:active:bg-gray-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span data-translate="cancel" data-translate-page="dosen_stk_add">Batal</span>
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-lg transition duration-200 font-medium shadow-md hover:shadow-lg dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:active:bg-indigo-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span data-translate="sv_stk" data-translate-page="dosen_stk_add">Simpan Sertifikat</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // ── Permanent toggle ────────────────────────────────────────────────
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

        // Function to apply filters
        function applyFilters() {
            const search = document.getElementById('search-input').value;
            const angkatan = document.getElementById('angkatan-filter').value;
            const jurusan = document.getElementById('jurusan-filter').value;
            const keahlian = document.getElementById('keahlian-filter').value;

            const url = new URL(window.location.href);
            url.searchParams.set('search', search);
            url.searchParams.set('angkatan', angkatan);
            url.searchParams.set('jurusan', jurusan);
            url.searchParams.set('keahlian', keahlian);

            window.location.href = url.toString();
        }

        // Function to select user
        function selectUser(userId, userName) {
            // Update hidden input
            document.getElementById('selected-user-id').value = userId;

            // Update radio buttons
            document.querySelectorAll('.user-radio').forEach(radio => {
                radio.checked = (radio.value == userId);
            });

            // Update display
            const display = document.getElementById('selected-user-display');
            const nameSpan = document.getElementById('selected-user-name');

            if (userId) {
                nameSpan.textContent = 'Dipilih: ' + userName;
                display.classList.remove('hidden');
            } else {
                display.classList.add('hidden');
            }
        }

        // Function to clear selected user
        function clearSelectedUser() {
            document.getElementById('selected-user-id').value = '';
            document.querySelectorAll('.user-radio').forEach(radio => {
                radio.checked = false;
            });
            document.getElementById('selected-user-display').classList.add('hidden');
        }

        // Function to update file label and preview
        function updateFileLabel(input) {
            const fileName = input.files[0]?.name;
            const fileNameElement = document.getElementById('file-name');
            const previewContainer = document.getElementById('image-preview-container');
            const previewImage = document.getElementById('image-preview');

            if (fileName) {
                fileNameElement.textContent = fileName;

                // Preview image
                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            } else {
                fileNameElement.textContent = 'PNG, JPG, GIF up to 5MB';
                previewContainer.classList.add('hidden');
                previewImage.src = '#';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            // Check if there's an old selected user
            const oldUserId = document.getElementById('selected-user-id').value;
            if (oldUserId) {
                const selectedRadio = document.querySelector(`.user-radio[value="${oldUserId}"]`);
                if (selectedRadio) {
                    const row = selectedRadio.closest('tr');
                    const userName = row.querySelector('td:nth-child(2)').textContent.trim();
                    selectUser(oldUserId, userName);
                }
            }

            // Setup drag and drop
            const dropZone = document.querySelector('.border-dashed');
            const fileInput = document.getElementById('link_sertifikat');

            if (dropZone && fileInput) {
                // Prevent default drag behaviors
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, preventDefaults, false);
                    document.body.addEventListener(eventName, preventDefaults, false);
                });

                // Highlight drop zone when dragging over it
                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, unhighlight, false);
                });

                // Handle dropped files
                dropZone.addEventListener('drop', handleDrop, false);
            }

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight() {
                dropZone.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
            }

            function unhighlight() {
                dropZone.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files && files.length > 0) {
                    fileInput.files = files;
                    updateFileLabel(fileInput);

                    // Trigger change event
                    const event = new Event('change', { bubbles: true });
                    fileInput.dispatchEvent(event);
                }
            }

            // Enter key for search
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        applyFilters();
                    }
                });
            }
        });
    </script>

    <!-- CSS Tambahan -->
    <style>
        .border-dashed {
            transition: all 0.2s ease;
        }

        .border-dashed:hover {
            border-color: #6366f1;
            background-color: #f9fafb;
        }

        .dark .border-dashed:hover {
            background-color: rgba(99, 102, 241, 0.1);
        }

        /* Hide spinner on number input */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Custom file input */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
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

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.dosen_add_sertifikat");
        });
    </script>
@endsection