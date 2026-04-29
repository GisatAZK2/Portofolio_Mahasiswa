@extends('Layout.Layout')
@section('title', 'Tambah User Baru')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white" data-translate="tambah_user_form"
                data-translate-page="admin">
                Tambah User Baru
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2" data-translate="pilih_role" data-translate-page="admin">
                Pilih role user yang akan ditambahkan
            </p>
        </div>

        <!-- Card Selection -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <!-- Card Admin -->
            <div class="cursor-pointer group" onclick="selectRole('admin')">
                <div id="card-admin"
                    class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="relative h-24 bg-gradient-to-r from-red-500 to-rose-600 dark:from-red-600 dark:to-rose-700 px-6 pt-6">
                        <div
                            class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto -mt-2">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Admin</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4" data-translate="desc_admin"
                            data-translate-page="admin">
                            Hak akses penuh untuk mengelola sistem
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card Mahasiswa -->
            <div class="cursor-pointer group" onclick="selectRole('mahasiswa')">
                <div id="card-mahasiswa"
                    class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="relative h-24 bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-600 dark:to-indigo-700 px-6 pt-6">
                        <div
                            class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto -mt-2">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path
                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Mahasiswa</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4" data-translate="desc_mhs"
                            data-translate-page="admin">
                            Akses untuk mengikuti kegiatan akademik
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card Dosen -->
            <div class="cursor-pointer group" onclick="selectRole('dosen')">
                <div id="card-dosen"
                    class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="relative h-24 bg-gradient-to-r from-green-500 to-emerald-600 dark:from-green-600 dark:to-emerald-700 px-6 pt-6">
                        <div
                            class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto -mt-2">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Dosen</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4" data-translate="desc_dosen"
                            data-translate-page="admin">
                            Akses untuk mengelola pembelajaran
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Type Selection (Only for Mahasiswa) -->
        <div id="registrationTypeContainer" class="mb-6 hidden">
            <div class="bg-blue-50 dark:bg-blue-900/30 rounded-2xl p-4 border border-blue-200 dark:border-blue-800">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    Tipe Registrasi
                </label>
                <div class="flex gap-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="registration_type" value="simple" onchange="toggleRegistrationType('simple')" class="w-4 h-4 text-blue-600">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Registrasi Sederhana</span>
                        <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">(Hanya NIM, Nama, Tanggal Lahir)</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="registration_type" value="full" onchange="toggleRegistrationType('full')" class="w-4 h-4 text-blue-600">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Registrasi Lengkap</span>
                        <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">(Semua data termasuk Jurusan, Keahlian, Angkatan)</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 p-6 md:p-10">
            <form method="POST" action="{{ route('admin.users.StoreUser') }}" enctype="multipart/form-data" id="userForm">
                @csrf

                <!-- Hidden Role -->
                <input type="hidden" name="role" id="selectedRole" value="">
                <input type="hidden" name="registration_type" id="registrationType" value="">

                <!-- Informasi Dasar -->
                <div class="mb-10">
                    <h2 data-translate="info_adduser" data-translate-page="admin"
                        class="text-2xl font-semibold text-gray-900 dark:text-white mb-6 pb-3 border-b border-gray-200 dark:border-gray-700">
                        Informasi Dasar
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- NIM -->
                        <div id="nimField">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                NIM <span class="text-red-500" id="nimRequired">*</span>
                            </label>
                            <input type="text" name="nim" id="nimInput" value="{{ old('nim') }}"
                                class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            @error('nim')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span data-translate="nm_lgkp" data-translate-page="admin">Nama Lengkap</span> <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_mahasiswa" value="{{ old('nama_mahasiswa') }}"
                                class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                required>
                            @error('nama_mahasiswa')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                      <!-- Tanggal Lahir -->
<div id="tanggalLahirField" class="mb-6">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Tanggal Lahir <span class="text-red-500" id="tanggalLahirRequired">*</span>
    </label>

    <input type="date" name="tanggal_lahir" id="tanggalLahirInput"
        value="{{ old('tanggal_lahir') }}"
        class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">

    @error('tanggal_lahir')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
                        <!-- Username -->
<div id="usernameField">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Username <span class="text-red-500">*</span>
    </label>
    <input type="text" name="username" value="{{ old('username') }}"
        class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
        required>
    @error('username')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" data-translate="usr_req"
        data-translate-page="admin">Hanya huruf, angka, dan underscore (_)</p>
</div>

<!-- Email -->
<div id="emailField">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
    <input type="email" name="email" value="{{ old('email') }}"
        class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
    @error('email')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>

<!-- Password -->
<div id="passwordField">
    <label data-translate="pw_adduser" data-translate-page="admin"
        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Password <span class="text-red-500">*</span>
    </label>
    <input type="password" name="password"
        class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
        required>
    <div class="mt-2 p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-100 dark:border-blue-800">
        <p class="text-xs font-semibold text-blue-900 dark:text-blue-100 mb-1">Syarat Password:</p>
        <ul class="text-xs text-blue-800 dark:text-blue-200 space-y-0.5">
            <li class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-200 dark:bg-blue-600 flex items-center justify-center text-xs">✓</span>
                Minimal 8 karakter
            </li>
            <li class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-200 dark:bg-blue-600 flex items-center justify-center text-xs">✓</span>
                Harus ada huruf besar (A-Z)
            </li>
            <li class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-200 dark:bg-blue-600 flex items-center justify-center text-xs">✓</span>
                Tidak boleh ada spasi
            </li>
        </ul>
    </div>
    @error('password')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>

<!-- Konfirmasi Password -->
<div id="passwordConfirmationField">
    <label data-translate="pw_confirm_adduser" data-translate-page="admin"
        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Konfirmasi Password <span class="text-red-500">*</span>
    </label>
    <input type="password" name="password_confirmation"
        class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
        required>
</div>

<!-- Photo Profile -->
<div id="photoField">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Photo Profile</label>
    <div class="flex items-center gap-5">
        <img id="preview" src="https://via.placeholder.com/150"
            class="w-20 h-20 rounded-2xl object-cover border-2 border-gray-300 dark:border-gray-600">
        <input type="file" name="photo_profile" id="photo_profile"
            accept="image/jpeg,image/png,image/jpg"
            class="text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-sm file:font-medium file:bg-blue-50 dark:file:bg-blue-900/50 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100">
    </div>
    @error('photo_profile')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
                </div>

                <!-- Informasi Tambahan (for full registration) -->
                <div id="additionalFields" class="mb-10 hidden">
                    <h2 data-translate="more_info_addusr" data-translate-page="admin"
                        class="text-2xl font-semibold text-gray-900 dark:text-white mb-6 pb-3 border-b border-gray-200 dark:border-gray-700">
                        Informasi Tambahan
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Jurusan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span data-translate="jrs_addusr" data-translate-page="admin">Jurusan</span> <span
                                    class="text-red-500" id="jurusanRequired">*</span>
                            </label>
                            <select name="id_jurusan" id="jurusanSelect"
                                class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500">
                                <option data-translate="chs_jrs_addusr" data-translate-page="admin" value="">Pilih Jurusan
                                </option>
                                @foreach($jurusan as $j)
                                    <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                                        {{ $j->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Keahlian -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span data-translate="exp_addusr" data-translate-page="admin">Bidang Keahlian</span> <span
                                    class="text-red-500" id="keahlianRequired">*</span>
                            </label>
                            <select name="id_keahlian" id="keahlianSelect"
                                class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500">
                                <option data-translate="chs_exp_addusr" data-translate-page="admin" value="">Pilih Keahlian
                                </option>
                                @foreach($keahlian as $k)
                                    <option value="{{ $k->id_keahlian }}" {{ old('id_keahlian') == $k->id_keahlian ? 'selected' : '' }}>
                                        {{ $k->nama_keahlian }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Angkatan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span data-translate="agkt_addusr" data-translate-page="admin">Angkatan</span> <span
                                    class="text-red-500" id="angkatanRequired">*</span>
                            </label>
                            <select name="id_angkatan" id="angkatanSelect"
                                class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:text-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500">
                                <option data-translate="chs_agkt_addusr" data-translate-page="admin" value="">Pilih Angkatan
                                </option>
                                @foreach($angkatan as $a)
                                    <option value="{{ $a->id }}" {{ old('id_angkatan') == $a->id ? 'selected' : '' }}>
                                        {{ $a->nama_angkatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Excel Import Section (for simple registration) -->
                <div id="excelImportSection" class="mb-10 hidden">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl p-6 border border-green-200 dark:border-green-800">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-file-excel mr-2"></i> Import Data Mahasiswa dari Excel
                            </h3>
                            <button type="button" onclick="downloadTemplate()" class="text-sm bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl transition-colors">
                                Download Template Excel
                            </button>
                        </div>
                        
                        <div class="border-2 border-dashed border-green-300 dark:border-green-700 rounded-xl p-6 text-center">
                            <input type="file" id="excelFile" accept=".xlsx, .xls" class="hidden" onchange="handleExcelUpload(this)">
                            <div onclick="document.getElementById('excelFile').click()" class="cursor-pointer">
                                <svg class="w-12 h-12 mx-auto text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-600 dark:text-gray-400">Klik atau drag file Excel disini</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Support .xlsx, .xls</p>
                            </div>
                        </div>

                        <!-- Preview Table -->
                        <div id="excelPreview" class="mt-6 hidden">
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-3">Preview Data:</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white dark:bg-gray-800 rounded-xl overflow-hidden">
                                    <thead class="bg-gray-100 dark:bg-gray-700">
                                        <tr id="previewHeader"></tr>
                                    </thead>
                                    <tbody id="previewBody"></tbody>
                                </table>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button type="button" onclick="applyExcelData()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl transition-colors">
                                    Terapkan Data ke Form
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-8 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        data-translate="cancel_addusr" data-translate-page="admin">
                        Batal
                    </a>
                    <button type="submit" id="submitBtn" disabled
                        class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl transition-all disabled:opacity-50 disabled:cursor-not-allowed font-medium"
                        data-translate-page="admin" data-translate="add_addusr">
                        Tambah User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Include SheetJS for Excel parsing -->
    <script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

    <!-- JavaScript -->
    <script>
        let selectedRole = '';
        let currentRegistrationType = 'simple';
        let excelData = [];

        function selectRole(role) {
    selectedRole = role;
    document.getElementById('selectedRole').value = role;

    // Reset semua card
    document.querySelectorAll('[id^="card-"]').forEach(card => {
        card.classList.remove('ring-2', 'ring-offset-4', 'ring-blue-500', 'ring-red-500', 'ring-green-500', 'border-red-500', 'border-blue-500', 'border-green-500');
    });

    // Highlight card terpilih
    const card = document.getElementById(`card-${role}`);
    if (role === 'admin') card.classList.add('ring-2', 'ring-offset-4', 'ring-red-500', 'border-red-500');
    else if (role === 'mahasiswa') card.classList.add('ring-2', 'ring-offset-4', 'ring-blue-500', 'border-blue-500');
    else if (role === 'dosen') card.classList.add('ring-2', 'ring-offset-4', 'ring-green-500', 'border-green-500');

    // Handle registration type container and form fields
    const regTypeContainer = document.getElementById('registrationTypeContainer');
    const additionalFields = document.getElementById('additionalFields');
    const excelImportSection = document.getElementById('excelImportSection');
    
    // Get all form fields that might be hidden
    const usernameField = document.getElementById('usernameField');
    const emailField = document.getElementById('emailField');
    const passwordField = document.getElementById('passwordField');
    const passwordConfirmationField = document.getElementById('passwordConfirmationField');
    const photoField = document.getElementById('photoField');
    const nimField = document.getElementById('nimField');
    const tanggalLahirField = document.getElementById('tanggalLahirField');
    
    if (role === 'mahasiswa') {
        regTypeContainer.classList.remove('hidden');
        // Show NIM and Tanggal Lahir for mahasiswa
        if (nimField) nimField.style.display = 'block';
        if (tanggalLahirField) tanggalLahirField.style.display = 'block';
        toggleRegistrationType('simple'); // Default to simple registration
    } else {
        regTypeContainer.classList.add('hidden');
        additionalFields.classList.add('hidden');
        excelImportSection.classList.add('hidden');
        
        // For non-mahasiswa roles, show all fields except NIM and Tanggal Lahir for admin
        if (usernameField) usernameField.style.display = 'block';
        if (emailField) emailField.style.display = 'block';
        if (passwordField) passwordField.style.display = 'block';
        if (passwordConfirmationField) passwordConfirmationField.style.display = 'block';
        if (photoField) photoField.style.display = 'block';
        
        // Reset required attributes for selects
        document.querySelectorAll('#additionalFields select').forEach(sel => sel.required = false);
        
        // Set field requirements based on role
        if (role === 'admin') {
            // Hide NIM and Tanggal Lahir for admin
            if (nimField) nimField.style.display = 'none';
            if (tanggalLahirField) tanggalLahirField.style.display = 'none';
            
            // Remove required attributes
            const nimInput = document.getElementById('nimInput');
            const tanggalLahirInput = document.getElementById('tanggalLahirInput');
            if (nimInput) nimInput.required = false;
            if (tanggalLahirInput) tanggalLahirInput.required = false;
            
            // Make username and password required for admin
            if (usernameField) {
                const usernameInput = usernameField.querySelector('input');
                if (usernameInput) usernameInput.required = true;
            }
            if (passwordField) {
                const passwordInput = passwordField.querySelector('input');
                if (passwordInput) passwordInput.required = true;
            }
            if (passwordConfirmationField) {
                const confirmInput = passwordConfirmationField.querySelector('input');
                if (confirmInput) confirmInput.required = true;
            }
            
        } else if (role === 'dosen') {
            // Show NIM (optional) and Tanggal Lahir (required) for dosen
            if (nimField) nimField.style.display = 'none';
            if (tanggalLahirField) tanggalLahirField.style.display = 'block';
            
            const nimRequired = document.getElementById('nimRequired');
            const tanggalLahirRequired = document.getElementById('tanggalLahirRequired');
            const nimInput = document.getElementById('nimInput');
            const tanggalLahirInput = document.getElementById('tanggalLahirInput');
            
            if (nimRequired) nimRequired.style.display = 'none';
            if (tanggalLahirRequired) tanggalLahirRequired.style.display = 'inline';
            if (nimInput) nimInput.required = false;
            if (tanggalLahirInput) tanggalLahirInput.required = true;
            
            // Make username and password required for dosen
            if (usernameField) {
                const usernameInput = usernameField.querySelector('input');
                if (usernameInput) usernameInput.required = true;
            }
            if (passwordField) {
                const passwordInput = passwordField.querySelector('input');
                if (passwordInput) passwordInput.required = true;
            }
            if (passwordConfirmationField) {
                const confirmInput = passwordConfirmationField.querySelector('input');
                if (confirmInput) confirmInput.required = true;
            }
            
            // Dosen needs additional fields
            additionalFields.classList.remove('hidden');
            document.querySelectorAll('#additionalFields select').forEach(sel => sel.required = true);
        }
    }

    document.getElementById('submitBtn').disabled = false;
}

 function toggleRegistrationType(type) {
    currentRegistrationType = type;
    document.getElementById('registrationType').value = type;
    
    const additionalFields = document.getElementById('additionalFields');
    const excelImportSection = document.getElementById('excelImportSection');
    const nimRequired = document.getElementById('nimRequired');
    const tanggalLahirRequired = document.getElementById('tanggalLahirRequired');
    const nimInput = document.getElementById('nimInput');
    const tanggalLahirInput = document.getElementById('tanggalLahirInput');
    
    // Fields to hide/show in simple registration
    const usernameField = document.getElementById('usernameField');
    const emailField = document.getElementById('emailField');
    const passwordField = document.getElementById('passwordField');
    const passwordConfirmationField = document.getElementById('passwordConfirmationField');
    const photoField = document.getElementById('photoField');
    const nimField = document.getElementById('nimField');
    const tanggalLahirField = document.getElementById('tanggalLahirField');
    
    // Set radio button checked state
    document.querySelectorAll('input[name="registration_type"]').forEach(radio => {
        if (radio.value === type) {
            radio.checked = true;
        }
    });
    
    if (type === 'full') {
        // Full registration: show additional fields, hide excel import
        additionalFields.classList.remove('hidden');
        excelImportSection.classList.add('hidden');
        
        // Show all form fields for mahasiswa
        if (usernameField) usernameField.style.display = 'block';
        if (emailField) emailField.style.display = 'block';
        if (passwordField) passwordField.style.display = 'block';
        if (passwordConfirmationField) passwordConfirmationField.style.display = 'block';
        if (photoField) photoField.style.display = 'block';
        if (nimField) nimField.style.display = 'block';
        if (tanggalLahirField) tanggalLahirField.style.display = 'block';
        
        // Make all select fields required
        document.querySelectorAll('#additionalFields select').forEach(sel => sel.required = true);
        
        // Make username and password required
        if (usernameField) {
            const usernameInput = usernameField.querySelector('input');
            if (usernameInput) usernameInput.required = true;
        }
        if (passwordField) {
            const passwordInput = passwordField.querySelector('input');
            if (passwordInput) passwordInput.required = true;
        }
        if (passwordConfirmationField) {
            const confirmInput = passwordConfirmationField.querySelector('input');
            if (confirmInput) confirmInput.required = true;
        }
        
        // NIM and Tanggal Lahir required
        if (nimRequired) nimRequired.style.display = 'inline';
        if (tanggalLahirRequired) tanggalLahirRequired.style.display = 'inline';
        if (nimInput) nimInput.required = true;
        if (tanggalLahirInput) tanggalLahirInput.required = true;
        
        // Remove required from hidden fields (just in case)
        if (emailField) {
            const emailInput = emailField.querySelector('input');
            if (emailInput) emailInput.required = false;
        }
        
    } else {
        // Simple registration: show additional fields, hide excel import
        additionalFields.classList.remove('hidden'); // TETAP TAMPILKAN
        excelImportSection.classList.remove('hidden'); // SEMBUNYIKAN EXCEL IMPORT
        
        // Hide username, email, password, confirm password, and photo fields for simple registration
        if (usernameField) usernameField.style.display = 'none';
        if (emailField) emailField.style.display = 'none';
        if (passwordField) passwordField.style.display = 'none';
        if (passwordConfirmationField) passwordConfirmationField.style.display = 'none';
        if (photoField) photoField.style.display = 'none';
        
        // Show NIM and Tanggal Lahir for simple registration
        if (nimField) nimField.style.display = 'block';
        if (tanggalLahirField) tanggalLahirField.style.display = 'block';
        
        // Make all select fields OPTIONAL (required = false) untuk simple registration
        document.querySelectorAll('#additionalFields select').forEach(sel => sel.required = false);
        
        // Remove required from hidden fields
        if (usernameField) {
            const usernameInput = usernameField.querySelector('input');
            if (usernameInput) usernameInput.required = false;
        }
        if (passwordField) {
            const passwordInput = passwordField.querySelector('input');
            if (passwordInput) passwordInput.required = false;
        }
        if (passwordConfirmationField) {
            const confirmInput = passwordConfirmationField.querySelector('input');
            if (confirmInput) confirmInput.required = false;
        }
        if (emailField) {
            const emailInput = emailField.querySelector('input');
            if (emailInput) emailInput.required = false;
        }
        
        // NIM required, Tanggal Lahir optional for simple registration
        if (nimRequired) nimRequired.style.display = 'inline';
        if (tanggalLahirRequired) tanggalLahirRequired.style.display = 'none';
        if (nimInput) nimInput.required = true;
        if (tanggalLahirInput) tanggalLahirInput.required = false;
    }
}

        // Image Preview
        document.getElementById('photo_profile').addEventListener('change', function (e) {
            if (e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    document.getElementById('preview').src = ev.target.result;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Handle Excel file upload
        function handleExcelUpload(input) {
            const file = input.files[0];
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {type: 'array'});
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                const jsonData = XLSX.utils.sheet_to_json(firstSheet);
                
                if (jsonData.length > 0) {
                    excelData = jsonData;
                    displayExcelPreview(jsonData);
                } else {
                    alert('File Excel kosong!');
                }
            };
            reader.readAsArrayBuffer(file);
        }

        function displayExcelPreview(data) {
            const previewDiv = document.getElementById('excelPreview');
            const headerRow = document.getElementById('previewHeader');
            const bodyTable = document.getElementById('previewBody');
            
            if (data.length === 0) return;
            
            // Get headers
            const headers = Object.keys(data[0]);
            
            // Create header
            headerRow.innerHTML = '';
            headers.forEach(header => {
                const th = document.createElement('th');
                th.className = 'px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300';
                th.textContent = header;
                headerRow.appendChild(th);
            });
            
            // Create body (max 5 rows for preview)
            bodyTable.innerHTML = '';
            const previewData = data.slice(0, 5);
            previewData.forEach(row => {
                const tr = document.createElement('tr');
                headers.forEach(header => {
                    const td = document.createElement('td');
                    td.className = 'px-4 py-2 text-sm text-gray-600 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700';
                    td.textContent = row[header] || '-';
                    tr.appendChild(td);
                });
                bodyTable.appendChild(tr);
            });
            
            previewDiv.classList.remove('hidden');
        }

        function applyExcelData() {
            if (excelData.length === 0) {
                alert('Tidak ada data Excel untuk diimpor!');
                return;
            }
            
            // Apply first row data to form
            const firstRow = excelData[0];
            
            // Map Excel columns to form fields
            if (firstRow['NIM'] || firstRow['nim']) {
                document.querySelector('input[name="nim"]').value = firstRow['NIM'] || firstRow['nim'] || '';
            }
            
            if (firstRow['Nama Lengkap'] || firstRow['nama'] || firstRow['Nama']) {
                document.querySelector('input[name="nama_mahasiswa"]').value = firstRow['Nama Lengkap'] || firstRow['nama'] || firstRow['Nama'] || '';
            }
            
            if (firstRow['Tanggal Lahir'] || firstRow['tanggal_lahir']) {
                let tgl = firstRow['Tanggal Lahir'] || firstRow['tanggal_lahir'];
                if (tgl) {
                    // Handle various date formats
                    if (typeof tgl === 'number') {
                        // Excel serial date
                        const date = XLSX.SSF.parse_date_code(tgl);
                        tgl = `${date.y}-${String(date.m).padStart(2,'0')}-${String(date.d).padStart(2,'0')}`;
                    } else if (typeof tgl === 'string') {
                        // Try to parse string date
                        const parsed = new Date(tgl);
                        if (!isNaN(parsed)) {
                            tgl = parsed.toISOString().split('T')[0];
                        }
                    }
                    document.querySelector('input[name="tanggal_lahir"]').value = tgl;
                }
            }
            
            if (firstRow['Email'] || firstRow['email']) {
                document.querySelector('input[name="email"]').value = firstRow['Email'] || firstRow['email'] || '';
            }
            
            if (firstRow['Username'] || firstRow['username']) {
                document.querySelector('input[name="username"]').value = firstRow['Username'] || firstRow['username'] || '';
            }
            
            // For full registration mode, also apply additional fields
            if (currentRegistrationType === 'full') {
                if (firstRow['Jurusan'] || firstRow['jurusan']) {
                    const jurusanValue = firstRow['Jurusan'] || firstRow['jurusan'];
                    const jurusanSelect = document.querySelector('select[name="id_jurusan"]');
                    for (let i = 0; i < jurusanSelect.options.length; i++) {
                        if (jurusanSelect.options[i].text === jurusanValue) {
                            jurusanSelect.value = jurusanSelect.options[i].value;
                            break;
                        }
                    }
                }
                
                if (firstRow['Keahlian'] || firstRow['keahlian']) {
                    const keahlianValue = firstRow['Keahlian'] || firstRow['keahlian'];
                    const keahlianSelect = document.querySelector('select[name="id_keahlian"]');
                    for (let i = 0; i < keahlianSelect.options.length; i++) {
                        if (keahlianSelect.options[i].text === keahlianValue) {
                            keahlianSelect.value = keahlianSelect.options[i].value;
                            break;
                        }
                    }
                }
                
                if (firstRow['Angkatan'] || firstRow['angkatan']) {
                    const angkatanValue = firstRow['Angkatan'] || firstRow['angkatan'];
                    const angkatanSelect = document.querySelector('select[name="id_angkatan"]');
                    for (let i = 0; i < angkatanSelect.options.length; i++) {
                        if (angkatanSelect.options[i].text === angkatanValue) {
                            angkatanSelect.value = angkatanSelect.options[i].value;
                            break;
                        }
                    }
                }
            }
            
            // Show success message
            showNotification('Data Excel berhasil diterapkan ke form!', 'success');
        }

        function downloadTemplate() {
            // Create template data
            const templateData = [
                {
                    'NIM': '20230010001',
                    'Nama Lengkap': 'Ahmad Budi Santoso',
                    'Tanggal Lahir': '2000-01-15',
                    'Email': 'ahmad@example.com',
                    'Username': 'ahmad.budi',
                    'Jurusan': 'Teknik Informatika',
                    'Keahlian': 'Web Development',
                    'Angkatan': '2023'
                },
                {
                    'NIM': '20230010002',
                    'Nama Lengkap': 'Siti Nurhaliza',
                    'Tanggal Lahir': '2000-02-20',
                    'Email': 'siti@example.com',
                    'Username': 'siti.nur',
                    'Jurusan': 'Sistem Informasi',
                    'Keahlian': 'Mobile Development',
                    'Angkatan': '2023'
                }
            ];
            
            const ws = XLSX.utils.json_to_sheet(templateData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Template Mahasiswa');
            XLSX.writeFile(wb, 'template_import_mahasiswa.xlsx');
        }

        function showNotification(message, type = 'info') {
            // Simple alert for now, you can replace with better notification system
            alert(message);
        }

        // Auto select saat ada error / old input
        document.addEventListener("DOMContentLoaded", function () {
            @if(old('role'))
                selectRole('{{ old('role') }}');
            @elseif(auth()->check() && auth()->user()->role === 'admin')
                selectRole('admin');
            @endif
        });
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.add_user");
        });
    </script>
@endsection
