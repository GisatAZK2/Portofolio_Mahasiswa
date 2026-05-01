@extends('Layout.Layout')
@section('title', 'Tambah User Baru')

@section('content')
<div class="add-user-wrapper">

    {{-- ===== HEADER ===== --}}
    <div class="auw-header">
        <div>
            <h1 class="auw-title" data-translate="tambah_user_form" data-translate-page="admin">
                Tambah User Baru
            </h1>
            <p class="auw-subtitle" data-translate="pilih_role" data-translate-page="admin">
                Pilih role user yang akan ditambahkan
            </p>
        </div>
    </div>

    {{-- ===== ROLE CARDS ===== --}}
    <div class="auw-role-grid">

        {{-- Admin --}}
        <div class="auw-role-card" id="card-admin" onclick="selectRole('admin')" data-role="admin">
            <div class="auw-role-banner auw-banner-admin">
                <div class="auw-role-icon-wrap">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                </div>
            </div>
            <div class="auw-role-body">
                <div class="auw-role-check">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="auw-role-name">Admin</h3>
                <p class="auw-role-desc" data-translate="desc_admin" data-translate-page="admin">
                    Hak akses penuh untuk mengelola sistem
                </p>
            </div>
        </div>

        {{-- Mahasiswa --}}
        <div class="auw-role-card" id="card-mahasiswa" onclick="selectRole('mahasiswa')" data-role="mahasiswa">
            <div class="auw-role-banner auw-banner-mahasiswa">
                <div class="auw-role-icon-wrap">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                    </svg>
                </div>
            </div>
            <div class="auw-role-body">
                <div class="auw-role-check">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="auw-role-name">Mahasiswa</h3>
                <p class="auw-role-desc" data-translate="desc_mhs" data-translate-page="admin">
                    Akses untuk mengikuti kegiatan akademik
                </p>
            </div>
        </div>

        {{-- Dosen --}}
        <div class="auw-role-card" id="card-dosen" onclick="selectRole('dosen')" data-role="dosen">
            <div class="auw-role-banner auw-banner-dosen">
                <div class="auw-role-icon-wrap">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
            <div class="auw-role-body">
                <div class="auw-role-check">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="auw-role-name">Dosen</h3>
                <p class="auw-role-desc" data-translate="desc_dosen" data-translate-page="admin">
                    Akses untuk mengelola pembelajaran
                </p>
            </div>
        </div>

    </div>

    {{-- ===== REGISTRATION TYPE (Mahasiswa only) ===== --}}
    <div id="registrationTypeContainer" class="auw-reg-type hidden">
        <span class="auw-reg-type-label">Tipe Registrasi</span>
        <div class="auw-reg-type-options">
            <label class="auw-reg-option" id="regSimpleLabel">
                <input type="radio" name="registration_type" value="simple" onchange="toggleRegistrationType('simple')">
                <div class="auw-reg-option-inner">
                    <div class="auw-reg-option-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="auw-reg-option-title">Registrasi Sederhana</p>
                        <p class="auw-reg-option-sub">NIM, Nama, Tanggal Lahir</p>
                    </div>
                </div>
            </label>
            <label class="auw-reg-option" id="regFullLabel">
                <input type="radio" name="registration_type" value="full" onchange="toggleRegistrationType('full')">
                <div class="auw-reg-option-inner">
                    <div class="auw-reg-option-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="auw-reg-option-title">Registrasi Lengkap</p>
                        <p class="auw-reg-option-sub">Semua data termasuk Jurusan, Keahlian, Angkatan</p>
                    </div>
                </div>
            </label>
        </div>
    </div>

    {{-- ===== MAIN FORM ===== --}}
    <div class="auw-form-card">
        <form method="POST" action="{{ route('admin.users.StoreUser') }}" enctype="multipart/form-data" id="userForm">
            @csrf
            <input type="hidden" name="role" id="selectedRole" value="">
            <input type="hidden" name="registration_type" id="registrationType" value="">
            <input type="hidden" name="avatar_default" id="avatarDefault" value="">

            {{-- ─── SECTION: Informasi Dasar ─── --}}
            <div class="auw-section">
                <div class="auw-section-header">
                    <div class="auw-section-dot"></div>
                    <h2 class="auw-section-title" data-translate="info_adduser" data-translate-page="admin">
                        Informasi Dasar
                    </h2>
                </div>

                <div class="auw-fields-grid">

                    {{-- NIM --}}
                    <div class="auw-field" id="nimField">
                        <label class="auw-label">
                            NIM <span class="auw-required" id="nimRequired">*</span>
                        </label>
                        <input type="text" name="nim" id="nimInput" value="{{ old('nim') }}"
                            placeholder="Contoh: 20230010001"
                            class="auw-input @error('nim') auw-input-error @enderror">
                        @error('nim')
                            <p class="auw-error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="auw-field">
                        <label class="auw-label">
                            <span data-translate="nm_lgkp" data-translate-page="admin">Nama Lengkap</span>
                            <span class="auw-required">*</span>
                        </label>
                        <input type="text" name="nama_mahasiswa" value="{{ old('nama_mahasiswa') }}"
                            placeholder="Nama Lengkap Anda"
                            class="auw-input @error('nama_mahasiswa') auw-input-error @enderror"
                            required>
                        @error('nama_mahasiswa')
                            <p class="auw-error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="auw-field" id="tanggalLahirField">
                        <label class="auw-label">
                            Tanggal Lahir <span class="auw-required" id="tanggalLahirRequired">*</span>
                        </label>
                        <input type="date" name="tanggal_lahir" id="tanggalLahirInput"
                            value="{{ old('tanggal_lahir') }}"
                            max="{{ date('Y-m-d') }}"
                            class="auw-input @error('tanggal_lahir') auw-input-error @enderror">
                        <p class="auw-hint">Tanggal lahir tidak boleh melebihi hari ini</p>
                        @error('tanggal_lahir')
                            <p class="auw-error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div class="auw-field" id="usernameField">
                        <label class="auw-label">
                            Username <span class="auw-required">*</span>
                        </label>
                        <input type="text" name="username" value="{{ old('username') }}"
                            placeholder="Contoh: john_doe"
                            class="auw-input @error('username') auw-input-error @enderror"
                            required>
                        @error('username')
                            <p class="auw-error-msg">{{ $message }}</p>
                        @enderror
                        <p class="auw-hint" data-translate="usr_req" data-translate-page="admin">
                            Hanya huruf, angka, dan underscore (_)
                        </p>
                    </div>

                    {{-- Email --}}
                    <div class="auw-field" id="emailField">
                        <label class="auw-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="contoh@email.com"
                            class="auw-input @error('email') auw-input-error @enderror">
                        @error('email')
                            <p class="auw-error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="auw-field" id="passwordField">
                        <label class="auw-label" data-translate="pw_adduser" data-translate-page="admin">
                            Password <span class="auw-required">*</span>
                        </label>
                        <input type="password" name="password"
                            placeholder="Minimal 8 karakter"
                            class="auw-input @error('password') auw-input-error @enderror"
                            required>
                        <div class="auw-pw-rules">
                            <p class="auw-pw-rules-title">Syarat Password:</p>
                            <ul class="auw-pw-rules-list">
                                <li><span class="auw-pw-dot"></span> Minimal 8 karakter</li>
                                <li><span class="auw-pw-dot"></span> Harus ada huruf besar (A–Z)</li>
                                <li><span class="auw-pw-dot"></span> Tidak boleh ada spasi</li>
                            </ul>
                        </div>
                        @error('password')
                            <p class="auw-error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="auw-field" id="passwordConfirmationField">
                        <label class="auw-label" data-translate="pw_confirm_adduser" data-translate-page="admin">
                            Konfirmasi Password <span class="auw-required">*</span>
                        </label>
                        <input type="password" name="password_confirmation"
                            placeholder="Ulangi password"
                            class="auw-input"
                            required>
                    </div>

                    {{-- Photo Profile --}}
                    <div class="auw-field auw-field-full" id="photoField">
                        <label class="auw-label">Photo Profile</label>

                        <div class="auw-photo-wrap">
                            {{-- Kiri: Avatar pilihan + Upload --}}
                            <div class="auw-photo-left">

                                {{-- Avatar Otomatis --}}
                                <p class="auw-avatar-heading">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Pilih Avatar Otomatis
                                </p>

                                <div class="auw-avatar-options">
                                    {{-- Avatar Perempuan --}}
                                    <label class="auw-avatar-label" id="avatarFemaleLabel">
                                        <input type="radio" name="avatar_choice" value="WanitaAVA"
                                            class="auw-avatar-radio"
                                            onchange="applyAvatar('WanitaAVA', 'Perempuan')">
                                        <div class="auw-avatar-circle" id="avatarFemaleCircle">
                                            <img src="{{ asset('assets/WanitaAVA.png') }}" alt="Avatar Perempuan"
                                                class="auw-avatar-img"
                                                onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                            <span class="auw-avatar-fallback">♀</span>
                                            <div class="auw-avatar-tick">
                                                <svg width="9" height="9" fill="none" stroke="white" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <span class="auw-avatar-name">Perempuan</span>
                                    </label>

                                    {{-- Avatar Laki-laki --}}
                                    <label class="auw-avatar-label" id="avatarMaleLabel">
                                        <input type="radio" name="avatar_choice" value="LakiAVA"
                                            class="auw-avatar-radio"
                                            onchange="applyAvatar('LakiAVA', 'Laki-laki')">
                                        <div class="auw-avatar-circle" id="avatarMaleCircle">
                                            <img src="{{ asset('assets/LakiAVA.png') }}" alt="Avatar Laki-laki"
                                                class="auw-avatar-img"
                                                onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                            <span class="auw-avatar-fallback">♂</span>
                                            <div class="auw-avatar-tick">
                                                <svg width="9" height="9" fill="none" stroke="white" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <span class="auw-avatar-name">Laki-laki</span>
                                    </label>
                                </div>

                                {{-- Divider --}}
                                <div class="auw-photo-divider">
                                    <div class="auw-photo-divider-line"></div>
                                    <span class="auw-photo-divider-text">atau upload sendiri</span>
                                    <div class="auw-photo-divider-line"></div>
                                </div>

                                {{-- Upload file --}}
                                <input type="file" name="photo_profile" id="photo_profile"
                                    accept="image/jpeg,image/png,image/jpg"
                                    class="auw-file-input"
                                    onchange="handlePhotoChange(this)">
                                <p class="auw-hint" style="margin-top:6px;">JPG, PNG. Maksimal 2MB</p>
                            </div>

                            {{-- Kanan: Preview --}}
                            <div class="auw-photo-right">
                                <p class="auw-preview-label">Preview</p>
                                <div class="auw-preview-frame" id="previewFrame">
                                    <img id="preview" src="https://via.placeholder.com/150"
                                        class="auw-preview-img" alt="Preview foto"
                                        onerror="this.src='https://via.placeholder.com/150?text=Error';">
                                    <div class="auw-preview-overlay" id="previewOverlay">
                                        <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>Belum dipilih</span>
                                    </div>
                                </div>
                                <p class="auw-avatar-selected-msg" id="avatarSelectedMsg"></p>
                            </div>
                        </div>

                        @error('photo_profile')
                            <p class="auw-error-msg" style="margin-top:8px;">{{ $message }}</p>
                        @enderror
                    </div>

                </div>{{-- /auw-fields-grid --}}
            </div>{{-- /Informasi Dasar --}}

            {{-- ─── SECTION: Informasi Tambahan (dosen / mahasiswa full) ─── --}}
            <div id="additionalFields" class="auw-section hidden">
                <div class="auw-section-header">
                    <div class="auw-section-dot auw-section-dot-green"></div>
                    <h2 class="auw-section-title" data-translate="more_info_addusr" data-translate-page="admin">
                        Informasi Tambahan
                    </h2>
                </div>
                <div class="auw-fields-grid auw-fields-grid-3">

                    {{-- Jurusan --}}
                    <div class="auw-field">
                        <label class="auw-label">
                            <span data-translate="jrs_addusr" data-translate-page="admin">Jurusan</span>
                            <span class="auw-required" id="jurusanRequired">*</span>
                        </label>
                        <select name="id_jurusan" id="jurusanSelect" class="auw-select">
                            <option value="" data-translate="chs_jrs_addusr" data-translate-page="admin">
                                Pilih Jurusan
                            </option>
                            @foreach($jurusan as $j)
                                <option value="{{ $j->id_jurusan }}"
                                    {{ old('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                                    {{ $j->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Keahlian --}}
                    <div class="auw-field">
                        <label class="auw-label">
                            <span data-translate="exp_addusr" data-translate-page="admin">Bidang Keahlian</span>
                            <span class="auw-required" id="keahlianRequired">*</span>
                        </label>
                        <select name="id_keahlian" id="keahlianSelect" class="auw-select">
                            <option value="" data-translate="chs_exp_addusr" data-translate-page="admin">
                                Pilih Keahlian
                            </option>
                            @foreach($keahlian as $k)
                                <option value="{{ $k->id_keahlian }}"
                                    {{ old('id_keahlian') == $k->id_keahlian ? 'selected' : '' }}>
                                    {{ $k->nama_keahlian }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Angkatan --}}
                    <div class="auw-field">
                        <label class="auw-label">
                            <span data-translate="agkt_addusr" data-translate-page="admin">Angkatan</span>
                            <span class="auw-required" id="angkatanRequired">*</span>
                        </label>
                        <select name="id_angkatan" id="angkatanSelect" class="auw-select">
                            <option value="" data-translate="chs_agkt_addusr" data-translate-page="admin">
                                Pilih Angkatan
                            </option>
                            @foreach($angkatan as $a)
                                <option value="{{ $a->id }}"
                                    {{ old('id_angkatan') == $a->id ? 'selected' : '' }}>
                                    {{ $a->nama_angkatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>{{-- /Informasi Tambahan --}}

            {{-- ─── SECTION: Excel Import (mahasiswa simple) ─── --}}
            <div id="excelImportSection" class="auw-section hidden">
                <div class="auw-section-header">
                    <div class="auw-section-dot auw-section-dot-emerald"></div>
                    <h2 class="auw-section-title">Import dari Excel</h2>
                </div>

                <div class="auw-excel-box">
                    <div class="auw-excel-topbar">
                        <div class="auw-excel-topbar-left">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Import Data Mahasiswa dari Excel
                        </div>
                        <button type="button" onclick="downloadTemplate()" class="auw-btn-dl">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download Template
                        </button>
                    </div>

                    <div class="auw-excel-dropzone" onclick="document.getElementById('excelFile').click()" id="excelDropzone">
                        <input type="file" id="excelFile" accept=".xlsx,.xls" class="hidden" onchange="handleExcelUpload(this)">
                        <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="auw-excel-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="auw-excel-drop-title">Klik atau seret file Excel ke sini</p>
                        <p class="auw-excel-drop-sub">Mendukung format .xlsx dan .xls</p>
                    </div>

                    {{-- Preview Table --}}
                    <div id="excelPreview" class="hidden">
                        <p class="auw-excel-preview-title">Preview Data (maks. 5 baris):</p>
                        <div class="auw-excel-table-wrap">
                            <table class="auw-excel-table">
                                <thead><tr id="previewHeader"></tr></thead>
                                <tbody id="previewBody"></tbody>
                            </table>
                        </div>
                        <div class="auw-excel-apply-row">
                            <button type="button" onclick="applyExcelData()" class="auw-btn-apply">
                                Terapkan Data ke Form
                            </button>
                        </div>
                    </div>
                </div>
            </div>{{-- /Excel Import --}}

            {{-- ─── BUTTONS ─── --}}
            <div class="auw-form-actions">
                <a href="{{ route('admin.users.index') }}" class="auw-btn-cancel"
                    data-translate="cancel_addusr" data-translate-page="admin">
                    Batal
                </a>
                <button type="submit" id="submitBtn" disabled class="auw-btn-submit"
                    data-translate="add_addusr" data-translate-page="admin">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Tambah User
                </button>
            </div>

        </form>
    </div>{{-- /auw-form-card --}}
</div>

{{-- ===== STYLES ===== --}}
<style>
/* ── Root ───────────────────────────────── */
.add-user-wrapper {
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 1.25rem 4rem;
    font-family: inherit;
}

/* ── Header ─────────────────────────────── */
.auw-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 2rem;
}
.auw-header-icon {
    width: 48px; height: 48px;
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    color: white;
    flex-shrink: 0;
}
.auw-title {
    font-size: 1.6rem; font-weight: 700;
    color: #111827;
    margin: 0;
    line-height: 1.2;
}
.dark .auw-title { color: #f9fafb; }
.auw-subtitle {
    font-size: 0.875rem; color: #6b7280;
    margin: 4px 0 0;
}
.dark .auw-subtitle { color: #9ca3af; }

/* ── Role Grid ───────────────────────────── */
.auw-role-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 640px) {
    .auw-role-grid { grid-template-columns: 1fr; }
}

.auw-role-card {
    border-radius: 18px;
    border: 2px solid #e5e7eb;
    background: #fff;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    position: relative;
}
.dark .auw-role-card {
    background: #1f2937;
    border-color: #374151;
}
.auw-role-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 28px -4px rgba(0,0,0,0.1);
}
.auw-role-card.selected-admin   { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.15); }
.auw-role-card.selected-mahasiswa { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
.auw-role-card.selected-dosen   { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,.15); }

.auw-role-banner {
    height: 72px;
    display: flex; align-items: center; justify-content: center;
}
.auw-banner-admin     { background: linear-gradient(135deg, #ef4444, #e11d48); }
.auw-banner-mahasiswa { background: linear-gradient(135deg, #3b82f6, #4f46e5); }
.auw-banner-dosen     { background: linear-gradient(135deg, #10b981, #059669); }

.auw-role-icon-wrap {
    width: 52px; height: 52px;
    background: rgba(255,255,255,.22);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    color: white;
    backdrop-filter: blur(4px);
}

.auw-role-body {
    padding: 1rem 1rem 1.125rem;
    text-align: center;
    position: relative;
}

.auw-role-check {
    position: absolute;
    top: -10px; right: 12px;
    width: 22px; height: 22px;
    background: #3b82f6;
    border-radius: 50%;
    display: none;
    align-items: center; justify-content: center;
    color: white;
    box-shadow: 0 2px 8px rgba(59,130,246,.4);
}
.auw-role-card.selected-admin   .auw-role-check { display: flex; background: #ef4444; }
.auw-role-card.selected-mahasiswa .auw-role-check { display: flex; background: #3b82f6; }
.auw-role-card.selected-dosen   .auw-role-check { display: flex; background: #10b981; }

.auw-role-name {
    font-size: 1rem; font-weight: 600;
    color: #111827; margin: 0 0 6px;
}
.dark .auw-role-name { color: #f9fafb; }
.auw-role-desc {
    font-size: 0.78rem; color: #6b7280; margin: 0;
    line-height: 1.4;
}
.dark .auw-role-desc { color: #9ca3af; }

/* ── Registration Type ───────────────────── */
.auw-reg-type {
    background: #eff6ff;
    border: 1.5px solid #bfdbfe;
    border-radius: 14px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
}
.dark .auw-reg-type {
    background: rgba(59,130,246,.1);
    border-color: rgba(59,130,246,.3);
}
.auw-reg-type.hidden { display: none; }
.auw-reg-type-label {
    display: block;
    font-size: 0.8rem; font-weight: 600;
    color: #1d4ed8; text-transform: uppercase; letter-spacing: .05em;
    margin-bottom: 10px;
}
.dark .auw-reg-type-label { color: #93c5fd; }

.auw-reg-type-options {
    display: flex; gap: 12px; flex-wrap: wrap;
}
.auw-reg-option {
    flex: 1; min-width: 200px;
    cursor: pointer;
    position: relative;
}
.auw-reg-option input { position: absolute; opacity: 0; width: 0; }
.auw-reg-option-inner {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 14px;
    border-radius: 10px;
    border: 1.5px solid #bfdbfe;
    background: white;
    transition: border-color .18s, background .18s, box-shadow .18s;
}
.dark .auw-reg-option-inner {
    background: #1f2937; border-color: #374151;
}
.auw-reg-option input:checked ~ .auw-reg-option-inner {
    border-color: #3b82f6;
    background: #dbeafe;
    box-shadow: 0 0 0 3px rgba(59,130,246,.12);
}
.dark .auw-reg-option input:checked ~ .auw-reg-option-inner {
    background: rgba(59,130,246,.18);
}
.auw-reg-option-icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    background: #dbeafe;
    display: flex; align-items: center; justify-content: center;
    color: #2563eb; flex-shrink: 0;
}
.dark .auw-reg-option-icon { background: rgba(59,130,246,.2); }
.auw-reg-option input:checked ~ .auw-reg-option-inner .auw-reg-option-icon {
    background: #2563eb; color: white;
}
.auw-reg-option-title {
    font-size: 0.875rem; font-weight: 600;
    color: #1e3a8a; margin: 0;
}
.dark .auw-reg-option-title { color: #bfdbfe; }
.auw-reg-option-sub {
    font-size: 0.75rem; color: #6b7280; margin: 2px 0 0;
}
.dark .auw-reg-option-sub { color: #9ca3af; }

/* ── Form Card ───────────────────────────── */
.auw-form-card {
    background: white;
    border: 1.5px solid #e5e7eb;
    border-radius: 24px;
    padding: 2rem 2rem 1.5rem;
    box-shadow: 0 4px 24px -4px rgba(0,0,0,.07);
}
.dark .auw-form-card {
    background: #1f2937;
    border-color: #374151;
}

/* ── Sections ────────────────────────────── */
.auw-section { margin-bottom: 2rem; }
.auw-section.hidden { display: none; }
.auw-section-header {
    display: flex; align-items: center; gap: 10px;
    padding-bottom: 12px;
    border-bottom: 1.5px solid #f3f4f6;
    margin-bottom: 1.25rem;
}
.dark .auw-section-header { border-color: #374151; }

.auw-section-dot {
    width: 10px; height: 10px; border-radius: 50%;
    background: #3b82f6; flex-shrink: 0;
}
.auw-section-dot-green   { background: #10b981; }
.auw-section-dot-emerald { background: #059669; }

.auw-section-title {
    font-size: 1.1rem; font-weight: 600;
    color: #111827; margin: 0;
}
.dark .auw-section-title { color: #f9fafb; }

/* ── Fields Grid ─────────────────────────── */
.auw-fields-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem 1.5rem;
}
.auw-fields-grid-3 { grid-template-columns: repeat(3, 1fr); }
@media (max-width: 640px) {
    .auw-fields-grid,
    .auw-fields-grid-3 { grid-template-columns: 1fr; }
}
.auw-field-full { grid-column: 1 / -1; }

/* ── Label / Input ───────────────────────── */
.auw-label {
    display: block;
    font-size: 0.8125rem; font-weight: 500;
    color: #374151; margin-bottom: 6px;
}
.dark .auw-label { color: #d1d5db; }
.auw-required { color: #ef4444; }

.auw-input,
.auw-select {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #d1d5db;
    border-radius: 12px;
    font-size: 0.875rem;
    background: #f9fafb;
    color: #111827;
    outline: none;
    transition: border-color .18s, box-shadow .18s, background .18s;
    appearance: none;
    -webkit-appearance: none;
}
.dark .auw-input,
.dark .auw-select {
    background: #111827;
    border-color: #4b5563;
    color: #f3f4f6;
}
.auw-input:focus,
.auw-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    background: white;
}
.dark .auw-input:focus,
.dark .auw-select:focus { background: #1f2937; }

.auw-input-error { border-color: #ef4444 !important; }
.auw-error-msg { font-size: 0.78rem; color: #ef4444; margin-top: 4px; }
.auw-hint { font-size: 0.75rem; color: #9ca3af; margin-top: 5px; }

/* ── Password Rules ──────────────────────── */
.auw-pw-rules {
    margin-top: 8px;
    padding: 10px 12px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 10px;
}
.dark .auw-pw-rules {
    background: rgba(59,130,246,.08);
    border-color: rgba(59,130,246,.2);
}
.auw-pw-rules-title {
    font-size: 0.75rem; font-weight: 600;
    color: #1e40af; margin: 0 0 5px;
}
.dark .auw-pw-rules-title { color: #93c5fd; }
.auw-pw-rules-list {
    list-style: none; margin: 0; padding: 0;
    display: flex; flex-direction: column; gap: 3px;
}
.auw-pw-rules-list li {
    font-size: 0.75rem; color: #1d4ed8;
    display: flex; align-items: center; gap: 7px;
}
.dark .auw-pw-rules-list li { color: #bfdbfe; }
.auw-pw-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: #60a5fa; flex-shrink: 0;
}

/* ── Photo / Avatar ──────────────────────── */
.auw-photo-wrap {
    display: flex;
    gap: 1.5rem;
    align-items: flex-start;
    flex-wrap: wrap;
}
.auw-photo-left { flex: 1; min-width: 240px; }
.auw-photo-right { display: flex; flex-direction: column; align-items: center; gap: 8px; }

.auw-avatar-heading {
    font-size: 0.8125rem; font-weight: 500;
    color: #374151; margin: 0 0 10px;
}
.dark .auw-avatar-heading { color: #d1d5db; }

.auw-avatar-options { display: flex; gap: 16px; margin-bottom: 14px; }

.auw-avatar-label { display: flex; flex-direction: column; align-items: center; gap: 6px; cursor: pointer; }
.auw-avatar-radio { display: none; }

.auw-avatar-circle {
    width: 68px; height: 68px;
    border-radius: 50%;
    border: 2.5px solid #d1d5db;
    background: #f3f4f6;
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
    transition: border-color .2s, box-shadow .2s, transform .2s;
}
.dark .auw-avatar-circle { background: #374151; border-color: #4b5563; }
.auw-avatar-label:hover .auw-avatar-circle {
    border-color: #93c5fd;
    transform: scale(1.04);
}
.auw-avatar-radio:checked ~ .auw-avatar-circle {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.2);
}
.auw-avatar-img {
    width: 100%; height: 100%; object-fit: cover;
}
.auw-avatar-fallback {
    font-size: 26px; color: #9ca3af;
    display: none;
    align-items: center; justify-content: center;
}
.auw-avatar-tick {
    position: absolute; bottom: 1px; right: 1px;
    width: 20px; height: 20px;
    background: #3b82f6; border-radius: 50%;
    display: none; align-items: center; justify-content: center;
    border: 2px solid white;
}
.auw-avatar-radio:checked ~ .auw-avatar-circle .auw-avatar-tick { display: flex; }

.auw-avatar-name {
    font-size: 0.75rem; color: #6b7280; font-weight: 500;
    transition: color .18s;
}
.dark .auw-avatar-name { color: #9ca3af; }
.auw-avatar-radio:checked ~ .auw-avatar-circle + .auw-avatar-name,
.auw-avatar-label:has(input:checked) .auw-avatar-name {
    color: #2563eb; font-weight: 600;
}
.dark .auw-avatar-label:has(input:checked) .auw-avatar-name { color: #93c5fd; }

.auw-photo-divider {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 12px;
}
.auw-photo-divider-line { flex: 1; height: 1px; background: #e5e7eb; }
.dark .auw-photo-divider-line { background: #374151; }
.auw-photo-divider-text { font-size: 0.72rem; color: #9ca3af; white-space: nowrap; }

.auw-file-input {
    font-size: 0.8125rem; color: #6b7280;
    cursor: pointer;
    width: 100%;
}
.auw-file-input::file-selector-button {
    margin-right: 10px;
    padding: 7px 14px;
    border: none; border-radius: 9px;
    background: #dbeafe;
    color: #1d4ed8;
    font-size: 0.8rem; font-weight: 500;
    cursor: pointer;
    transition: background .15s;
}
.auw-file-input::file-selector-button:hover { background: #bfdbfe; }
.dark .auw-file-input::file-selector-button {
    background: rgba(59,130,246,.18); color: #93c5fd;
}

/* Preview */
.auw-preview-label {
    font-size: 0.72rem; font-weight: 500; text-transform: uppercase;
    letter-spacing: .06em; color: #9ca3af; margin: 0;
}
.auw-preview-frame {
    width: 96px; height: 96px;
    border-radius: 18px;
    border: 2px dashed #d1d5db;
    overflow: hidden; position: relative;
    background: #f9fafb;
    transition: border-color .2s;
}
.dark .auw-preview-frame { background: #111827; border-color: #374151; }
.auw-preview-frame.has-image { border-style: solid; border-color: #3b82f6; }

.auw-preview-img {
    width: 100%; height: 100%; object-fit: cover;
    display: block;
}
.auw-preview-overlay {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 4px;
    background: rgba(17,24,39,.45);
    color: white; font-size: 0.65rem;
    text-align: center; padding: 6px;
}
.auw-preview-overlay.hidden-overlay { display: none; }

.auw-avatar-selected-msg {
    font-size: 0.72rem; color: #2563eb; font-weight: 500;
    text-align: center; min-height: 18px;
    margin: 0;
}
.dark .auw-avatar-selected-msg { color: #93c5fd; }

/* ── Excel Import ────────────────────────── */
.auw-excel-box {
    background: #f0fdf4;
    border: 1.5px solid #bbf7d0;
    border-radius: 16px;
    padding: 1.25rem;
}
.dark .auw-excel-box {
    background: rgba(16,185,129,.08);
    border-color: rgba(16,185,129,.25);
}
.auw-excel-topbar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
    margin-bottom: 14px;
}
.auw-excel-topbar-left {
    display: flex; align-items: center; gap: 8px;
    font-size: 0.9rem; font-weight: 600; color: #065f46;
}
.dark .auw-excel-topbar-left { color: #6ee7b7; }
.auw-btn-dl {
    display: flex; align-items: center; gap: 6px;
    padding: 7px 14px;
    background: #059669; color: white;
    border: none; border-radius: 9px;
    font-size: 0.8rem; font-weight: 500;
    cursor: pointer; transition: background .15s;
}
.auw-btn-dl:hover { background: #047857; }

.auw-excel-dropzone {
    border: 2px dashed #6ee7b7;
    border-radius: 12px;
    padding: 2rem 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: background .15s, border-color .15s;
}
.dark .auw-excel-dropzone { border-color: rgba(16,185,129,.4); }
.auw-excel-dropzone:hover { background: rgba(16,185,129,.08); border-color: #10b981; }
.auw-excel-icon { color: #10b981; margin: 0 auto 10px; display: block; }
.auw-excel-drop-title { font-size: 0.875rem; color: #065f46; font-weight: 500; margin: 0 0 4px; }
.dark .auw-excel-drop-title { color: #6ee7b7; }
.auw-excel-drop-sub { font-size: 0.75rem; color: #6b7280; margin: 0; }

.auw-excel-preview-title { font-size: 0.875rem; font-weight: 600; color: #065f46; margin: 1rem 0 8px; }
.dark .auw-excel-preview-title { color: #6ee7b7; }
.auw-excel-table-wrap { overflow-x: auto; border-radius: 10px; }
.auw-excel-table {
    width: 100%; border-collapse: collapse;
    background: white; border-radius: 10px; overflow: hidden;
    font-size: 0.8rem;
}
.dark .auw-excel-table { background: #1f2937; }
.auw-excel-table thead { background: #f0fdf4; }
.dark .auw-excel-table thead { background: rgba(16,185,129,.1); }
.auw-excel-table th {
    padding: 9px 12px; text-align: left;
    font-weight: 600; color: #065f46; font-size: 0.78rem;
}
.dark .auw-excel-table th { color: #6ee7b7; }
.auw-excel-table td {
    padding: 8px 12px; color: #374151;
    border-top: 1px solid #f3f4f6;
}
.dark .auw-excel-table td { color: #d1d5db; border-color: #374151; }
.auw-excel-apply-row { display: flex; justify-content: flex-end; margin-top: 12px; }
.auw-btn-apply {
    padding: 8px 20px;
    background: #2563eb; color: white;
    border: none; border-radius: 9px;
    font-size: 0.85rem; font-weight: 500;
    cursor: pointer; transition: background .15s;
}
.auw-btn-apply:hover { background: #1d4ed8; }

/* ── Form Actions ────────────────────────── */
.auw-form-actions {
    display: flex; justify-content: flex-end; align-items: center;
    gap: 12px;
    padding-top: 1.5rem;
    border-top: 1.5px solid #f3f4f6;
}
.dark .auw-form-actions { border-color: #374151; }

.auw-btn-cancel {
    padding: 10px 24px;
    border: 1.5px solid #d1d5db;
    border-radius: 12px;
    color: #374151; font-size: 0.875rem; font-weight: 500;
    text-decoration: none;
    transition: background .15s, border-color .15s;
}
.dark .auw-btn-cancel { border-color: #4b5563; color: #d1d5db; }
.auw-btn-cancel:hover { background: #f3f4f6; }
.dark .auw-btn-cancel:hover { background: #374151; }

.auw-btn-submit {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 24px;
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: white; border: none; border-radius: 12px;
    font-size: 0.875rem; font-weight: 600;
    cursor: pointer;
    transition: opacity .15s, transform .15s;
    box-shadow: 0 4px 14px -2px rgba(59,130,246,.35);
}
.auw-btn-submit:hover:not(:disabled) { opacity: .9; transform: translateY(-1px); }
.auw-btn-submit:disabled {
    background: #9ca3af;
    box-shadow: none; cursor: not-allowed;
    transform: none; opacity: 1;
}

/* ── Utility ─────────────────────────────── */
.hidden { display: none !important; }
</style>

{{-- ===== SCRIPTS ===== --}}
<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
<script>
let selectedRole = '';
let currentRegistrationType = 'simple';
let excelData = [];
let importResult = null;

// Modifikasi handleExcelUpload
function handleExcelUpload(input) {
    const file = input.files[0];
    if (!file) return;
    
    // Cek ukuran file (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file terlalu besar! Maksimal 5MB.');
        input.value = '';
        return;
    }
    
    const reader = new FileReader();
    reader.onload = e => {
        try {
            const wb = XLSX.read(new Uint8Array(e.target.result), { type: 'array' });
            const json = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]]);
            
            if (!json.length) { 
                alert('File Excel kosong!'); 
                return; 
            }
            
            excelData = json;
            displayExcelPreview(json);
        } catch (error) {
            console.error('Error parsing Excel:', error);
            alert('Gagal membaca file Excel. Pastikan format file benar.');
        }
    };
    reader.readAsArrayBuffer(file);
}

function validateExcelData() {
    if (!excelData.length) return false;
    
    const errors = [];
    const nims = new Set();
    
    excelData.forEach((row, index) => {
        const nim = row.NIM || row.nim;
        const nama = row['Nama Lengkap'] || row.nama || row.Nama;
        
        if (!nim) errors.push(`Baris ${index + 2}: NIM tidak boleh kosong`);
        if (!nama) errors.push(`Baris ${index + 2}: Nama tidak boleh kosong`);
        if (nims.has(nim)) errors.push(`Baris ${index + 2}: NIM duplikat dalam file`);
        nims.add(nim);
    });
    
    if (errors.length > 0) {
        alert('Validasi gagal:\n' + errors.join('\n'));
        return false;
    }
    
    return true;
}

// Fungsi baru untuk import massal
async function importExcelData() {
    if (!excelData.length) {
        alert('Tidak ada data Excel untuk diimport!');
        return;
    }

     if (!validateExcelData()) return;
    
    // Konfirmasi import
    const confirmed = confirm(`Anda akan mengimport ${excelData.length} data mahasiswa.\n\nPastikan data sudah benar.\n\nLanjutkan?`);
    if (!confirmed) return;
    
    // Buat FormData
    const formData = new FormData();
    const excelFile = document.getElementById('excelFile').files[0];
    if (!excelFile) {
        alert('File Excel tidak ditemukan!');
        return;
    }
    formData.append('excel_file', excelFile);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value);
    
    // Tampilkan loading
    showImportLoading();
    
    try {
        const response = await fetch('{{ route("admin.users.importExcel") }}', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showImportResult(result);
        } else {
            alert('Gagal import: ' + result.message);
        }
    } catch (error) {
        console.error('Error import:', error);
        alert('Terjadi kesalahan saat mengimport data.');
    } finally {
        hideImportLoading();
    }
}

// Tampilkan loading indicator
function showImportLoading() {
    const dropzone = document.getElementById('excelDropzone');
    const originalContent = dropzone.innerHTML;
    
    dropzone.innerHTML = `
        <div style="text-align: center; padding: 2rem;">
            <div class="spinner" style="display: inline-block; width: 40px; height: 40px; border: 3px solid #f3f3f3; border-top: 3px solid #10b981; border-radius: 50%; animation: spin 1s linear infinite;"></div>
            <p style="margin-top: 1rem; color: #065f46;">Sedang mengimport data...</p>
        </div>
    `;
    dropzone.style.pointerEvents = 'none';
    
    // Tambahkan style spinner jika belum ada
    if (!document.querySelector('#spinner-style')) {
        const style = document.createElement('style');
        style.id = 'spinner-style';
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Simpan original content untuk restore
    dropzone.dataset.originalContent = originalContent;
}

// Hide loading indicator
function hideImportLoading() {
    const dropzone = document.getElementById('excelDropzone');
    if (dropzone.dataset.originalContent) {
        dropzone.innerHTML = dropzone.dataset.originalContent;
        delete dropzone.dataset.originalContent;
    }
    dropzone.style.pointerEvents = 'auto';
}

// Tampilkan hasil import
function showImportResult(result) {
    let message = result.message;
    
    if (result.warnings && result.warnings.length > 0) {
        message += '\n\n⚠️ Peringatan:\n' + result.warnings.join('\n');
    }
    
    if (result.failedRows && result.failedRows.length > 0) {
        message += '\n\n❌ Gagal di baris:\n';
        result.failedRows.slice(0, 10).forEach(failed => {
            message += `Baris ${failed.row}: ${failed.reason}\n`;
        });
        if (result.failedRows.length > 10) {
            message += `\n... dan ${result.failedRows.length - 10} baris lainnya`;
        }
    }
    
    message += `\n\n📊 Statistik:\n- Berhasil: ${result.stats.success}\n- Gagal: ${result.stats.failed}\n- Total: ${result.stats.total}`;
    
    alert(message);
    
    // Refresh halaman jika ada yang berhasil
    if (result.stats.success > 0) {
        if (confirm('Import selesai! Apakah Anda ingin me-refresh halaman untuk melihat data terbaru?')) {
            window.location.reload();
        }
    }
}

// Tambahkan tombol import massal di UI
function addImportButton() {
    const excelSection = document.getElementById('excelImportSection');
    if (!excelSection) return;
    
    const excelBox = excelSection.querySelector('.auw-excel-box');
    if (!excelBox) return;
    
    // Cek apakah tombol sudah ada
    if (excelBox.querySelector('.auw-btn-mass-import')) return;
    
    // Buat container untuk actions
    const actionsDiv = document.createElement('div');
    actionsDiv.className = 'auw-excel-actions';
    actionsDiv.style.marginTop = '15px';
    actionsDiv.style.display = 'flex';
    actionsDiv.style.gap = '10px';
    actionsDiv.style.justifyContent = 'flex-end';
    
    // Tombol Import Massal
    const importBtn = document.createElement('button');
    importBtn.type = 'button';
    importBtn.className = 'auw-btn-mass-import';
    importBtn.style.cssText = `
        padding: 10px 24px;
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.15s;
    `;
    importBtn.innerHTML = `
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
        </svg>
        Import Massal ke Database
    `;
    importBtn.onclick = importExcelData;
    
    // Tombol Reset Preview
    const resetBtn = document.createElement('button');
    resetBtn.type = 'button';
    resetBtn.className = 'auw-btn-reset';
    resetBtn.style.cssText = `
        padding: 10px 20px;
        background: #6b7280;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s;
    `;
    resetBtn.innerHTML = 'Reset Preview';
    resetBtn.onclick = () => {
        excelData = [];
        document.getElementById('excelPreview').classList.add('hidden');
        document.getElementById('excelFile').value = '';
        const dropzoneText = document.querySelector('#excelDropzone p:first-of-type');
        if (dropzoneText) dropzoneText.textContent = 'Klik atau seret file Excel ke sini';
    };
    
    actionsDiv.appendChild(resetBtn);
    actionsDiv.appendChild(importBtn);
    excelBox.appendChild(actionsDiv);
}

/* ─── tanggal lahir validation (global, run once) ─── */
document.getElementById('tanggalLahirInput').addEventListener('change', function () {
    const today = new Date().toISOString().split('T')[0];
    const existingErr = document.getElementById('tanggalLahirClientError');
    if (existingErr) existingErr.remove();

    if (this.value > today) {
        this.classList.add('auw-input-error');
        const err = document.createElement('p');
        err.id = 'tanggalLahirClientError';
        err.className = 'auw-error-msg';
        err.textContent = 'Tanggal lahir tidak boleh melebihi tanggal hari ini';
        this.parentNode.appendChild(err);
        this.value = '';
    } else {
        this.classList.remove('auw-input-error');
    }
});

/* ─── selectRole ─── */
function selectRole(role) {
    selectedRole = role;
    document.getElementById('selectedRole').value = role;

    /* reset semua card */
    document.querySelectorAll('.auw-role-card').forEach(c => {
        c.classList.remove('selected-admin', 'selected-mahasiswa', 'selected-dosen');
    });
    document.getElementById(`card-${role}`).classList.add(`selected-${role}`);

    const regTypeContainer = document.getElementById('registrationTypeContainer');
    const additionalFields = document.getElementById('additionalFields');
    const excelImportSection = document.getElementById('excelImportSection');

    /* show/hide field references */
    const f = {
        nim          : document.getElementById('nimField'),
        tgl          : document.getElementById('tanggalLahirField'),
        username     : document.getElementById('usernameField'),
        email        : document.getElementById('emailField'),
        password     : document.getElementById('passwordField'),
        confirm      : document.getElementById('passwordConfirmationField'),
        photo        : document.getElementById('photoField'),
        nimInput     : document.getElementById('nimInput'),
        tglInput     : document.getElementById('tanggalLahirInput'),
        nimReq       : document.getElementById('nimRequired'),
        tglReq       : document.getElementById('tanggalLahirRequired'),
    };

    /* reset all field visibility */
    ['nim','tgl','username','email','password','confirm','photo'].forEach(k => {
        if (f[k]) f[k].style.display = 'block';
    });
    additionalFields.classList.add('hidden');
    excelImportSection.classList.add('hidden');
    document.querySelectorAll('#additionalFields select').forEach(s => s.required = false);

    if (role === 'mahasiswa') {
        regTypeContainer.classList.remove('hidden');
        toggleRegistrationType('simple');

    } else {
        regTypeContainer.classList.add('hidden');

        if (role === 'admin') {
            // Admin: sembunyikan NIM dan Tanggal Lahir
            f.nim.style.display  = 'none';
            f.tgl.style.display  = 'none';
            if (f.nimInput) f.nimInput.required = false;
            if (f.tglInput) f.tglInput.required = false;
            
            // Pastikan username dan password required
            setRequired(f.username, 'input', true);
            setRequired(f.password, 'input', true);
            setRequired(f.confirm, 'input', true);
            
            // Email opsional untuk admin
            if (f.email) {
                const emailInput = f.email.querySelector('input');
                if (emailInput) emailInput.required = false;
            }

        } else if (role === 'dosen') {
            // Dosen: sembunyikan NIM, TAMPILKAN Tanggal Lahir (required)
            f.nim.style.display  = 'none';
            f.tgl.style.display  = 'block';
            if (f.nimInput) f.nimInput.required = false;
            if (f.tglInput) {
                f.tglInput.required = true;
                f.tglInput.disabled = false;
            }
            if (f.tglReq)   f.tglReq.style.display = 'inline';
            
            // Username dan password required
            setRequired(f.username, 'input', true);
            setRequired(f.password, 'input', true);
            setRequired(f.confirm, 'input', true);

            additionalFields.classList.remove('hidden');
            document.querySelectorAll('#additionalFields select').forEach(s => s.required = true);
        }
    }

    document.getElementById('submitBtn').disabled = false;
}

function toggleRegistrationType(type) {
    currentRegistrationType = type;
    document.getElementById('registrationType').value = type;

    document.querySelectorAll('input[name="registration_type"]').forEach(r => r.checked = r.value === type);

    const f = {
        nim     : document.getElementById('nimField'),
        tgl     : document.getElementById('tanggalLahirField'),
        username: document.getElementById('usernameField'),
        email   : document.getElementById('emailField'),
        password: document.getElementById('passwordField'),
        confirm : document.getElementById('passwordConfirmationField'),
        photo   : document.getElementById('photoField'),
        nimInput: document.getElementById('nimInput'),
        tglInput: document.getElementById('tanggalLahirInput'),
        nimReq  : document.getElementById('nimRequired'),
        tglReq  : document.getElementById('tanggalLahirRequired'),
    };
    const additionalFields     = document.getElementById('additionalFields');
    const excelImportSection   = document.getElementById('excelImportSection');

    if (type === 'full') {
        /* Show all fields */
        ['nim','tgl','username','email','password','confirm','photo'].forEach(k => {
            if (f[k]) f[k].style.display = 'block';
        });
        additionalFields.classList.remove('hidden');
        excelImportSection.classList.add('hidden');
        document.querySelectorAll('#additionalFields select').forEach(s => s.required = true);
        setRequired(f.username, 'input', true);
        setRequired(f.password, 'input', true);
        setRequired(f.confirm, 'input', true);
        if (f.nimInput) f.nimInput.required = true;
        if (f.tglInput) f.tglInput.required = true;
        if (f.nimReq)   f.nimReq.style.display   = 'inline';
        if (f.tglReq)   f.tglReq.style.display   = 'inline';

    } else {
        /* Simple registration */
        ['username','email','password','confirm','photo'].forEach(k => {
            if (f[k]) f[k].style.display = 'none';
        });
        if (f.nim) f.nim.style.display = 'block';
        if (f.tgl) f.tgl.style.display = 'block';
        additionalFields.classList.remove('hidden');
        excelImportSection.classList.remove('hidden');
        document.querySelectorAll('#additionalFields select').forEach(s => s.required = false);
        setRequired(f.username, 'input', false);
        setRequired(f.password, 'input', false);
        setRequired(f.confirm, 'input', false);
        if (f.nimInput) f.nimInput.required = true;
        if (f.tglInput) f.tglInput.required = false;
        if (f.nimReq)   f.nimReq.style.display   = 'inline';
        if (f.tglReq)   f.tglReq.style.display   = 'none';
    }
}

/* ─── helper ─── */
function setRequired(fieldEl, selector, val) {
    if (!fieldEl) return;
    const el = fieldEl.querySelector(selector);
    if (el) el.required = val;
}

/* ─── PERBAIKAN UTAMA : Avatar & Preview ─── */
function applyAvatar(filename, label) {
    // 1. Set value hidden input untuk dikirim ke server
    document.getElementById('avatarDefault').value = filename;

    // 2. Tentukan path gambar (sesuaikan dengan ekstensi file asli, misal .png)
    const imgSrc = `/assets/${filename}.png`;

    // 3. Update preview image
    const previewImg = document.getElementById('preview');
    previewImg.src = imgSrc;

    // 4. Fallback jika gambar gagal dimuat (misal karena path salah)
    previewImg.onerror = function() {
        this.src = 'https://via.placeholder.com/150?text=Avatar+Error';
        console.warn('Gambar avatar gagal dimuat:', imgSrc);
    };

    // 5. Tampilkan frame preview, sembunyikan overlay
    const frame = document.getElementById('previewFrame');
    frame.classList.add('has-image');
    document.getElementById('previewOverlay').classList.add('hidden-overlay');

    // 6. Tampilkan pesan avatar terpilih
    document.getElementById('avatarSelectedMsg').textContent = `Avatar: ${label}`;

    // 7. Bersihkan input file upload (agar tidak bentrok, prioritas avatar)
    const fileInput = document.getElementById('photo_profile');
    if (fileInput) {
        fileInput.value = '';
    }

    // 8. Pastikan radio button yang sesuai tetap ter-check (opsional)
    document.querySelectorAll('input[name="avatar_choice"]').forEach(radio => {
        if (radio.value !== filename) radio.checked = false;
    });
}

/* ─── Photo upload (override avatar) ─── */
function handlePhotoChange(input) {
    if (!input.files[0]) return;
    // Reset avatar pilihan (karena user upload file sendiri)
    document.querySelectorAll('input[name="avatar_choice"]').forEach(r => r.checked = false);
    document.getElementById('avatarDefault').value = '';
    document.getElementById('avatarSelectedMsg').textContent = '';

    const reader = new FileReader();
    reader.onload = e => {
        const previewImg = document.getElementById('preview');
        previewImg.src = e.target.result;
        // Hapus onerror supaya tidak override preview upload
        previewImg.onerror = null;
        document.getElementById('previewFrame').classList.add('has-image');
        document.getElementById('previewOverlay').classList.add('hidden-overlay');
    };
    reader.readAsDataURL(input.files[0]);
}

/* ─── Excel handlers (tidak berubah) ─── */
function handleExcelUpload(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const wb = XLSX.read(new Uint8Array(e.target.result), { type: 'array' });
        const json = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]]);
        if (!json.length) { alert('File Excel kosong!'); return; }
        excelData = json;
        displayExcelPreview(json);
    };
    reader.readAsArrayBuffer(file);
}

function displayExcelPreview(data) {
    const headers = Object.keys(data[0]);
    const headerRow = document.getElementById('previewHeader');
    const body      = document.getElementById('previewBody');

    headerRow.innerHTML = '';
    headers.forEach(h => {
        const th = document.createElement('th');
        th.textContent = h;
        headerRow.appendChild(th);
    });
    body.innerHTML = '';
    data.slice(0, 5).forEach(row => {
        const tr = document.createElement('tr');
        headers.forEach(h => {
            const td = document.createElement('td');
            td.textContent = row[h] ?? '-';
            tr.appendChild(td);
        });
        body.appendChild(tr);
    });
    document.getElementById('excelPreview').classList.remove('hidden');
}

function applyExcelData() {
    if (!excelData.length) { alert('Tidak ada data Excel!'); return; }
    const r = excelData[0];
    const get = (...keys) => keys.reduce((v, k) => v || r[k], '');

    const setVal = (name, val) => {
        const el = document.querySelector(`[name="${name}"]`);
        if (el && val) el.value = val;
    };

    setVal('nim', get('NIM', 'nim'));
    setVal('nama_mahasiswa', get('Nama Lengkap', 'nama', 'Nama'));
    setVal('email', get('Email', 'email'));
    setVal('username', get('Username', 'username'));

    let tgl = get('Tanggal Lahir', 'tanggal_lahir');
    if (tgl) {
        if (typeof tgl === 'number') {
            const d = XLSX.SSF.parse_date_code(tgl);
            tgl = `${d.y}-${String(d.m).padStart(2,'0')}-${String(d.d).padStart(2,'0')}`;
        } else {
            const parsed = new Date(tgl);
            if (!isNaN(parsed)) tgl = parsed.toISOString().split('T')[0];
        }
        setVal('tanggal_lahir', tgl);
    }

    if (currentRegistrationType === 'full') {
        matchSelectByText('id_jurusan', get('Jurusan', 'jurusan'));
        matchSelectByText('id_keahlian', get('Keahlian', 'keahlian'));
        matchSelectByText('id_angkatan', get('Angkatan', 'angkatan'));
    }

    alert('Data Excel berhasil diterapkan ke form!');
}

function matchSelectByText(name, text) {
    if (!text) return;
    const sel = document.querySelector(`select[name="${name}"]`);
    if (!sel) return;
    for (const opt of sel.options) {
        if (opt.text.trim() === String(text).trim()) { sel.value = opt.value; break; }
    }
}

function downloadTemplate() {
    const data = [
        { 'NIM': '20250010001', 'Nama Lengkap': 'Ahmad Budi Santoso', 'Jurusan': 'Bisnis Digital', 'Keahlian': 'Web Development', 'Angkatan': '2025' },
        { 'NIM': '20250010002', 'Nama Lengkap': 'Siti Nurhaliza', 'Jurusan': 'Teknologi Rekayasa Perangkat Lunak', 'Keahlian': 'Mobile Development', 'Angkatan': '2025' }
    ];
    const ws = XLSX.utils.json_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Template Mahasiswa');
    XLSX.writeFile(wb, 'template_import_mahasiswa.xlsx');
}

/* ─── Auto select on page load ─── */
document.addEventListener('DOMContentLoaded', () => {
    @if(old('role'))
        selectRole('{{ old('role') }}');
    @elseif(auth()->check() && auth()->user()->role === 'admin')
        selectRole('admin');
    @endif

    addImportButton();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof showPageInfo === 'function') showPageInfo('popup.add_user');
});
</script>
@endsection