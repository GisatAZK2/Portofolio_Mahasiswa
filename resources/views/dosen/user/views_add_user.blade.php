@extends('Layout.Layout')

@section('title', 'Tambah Mahasiswa Baru')

@section('content')
<div class="daw-wrapper">

    {{-- ===== HEADER ===== --}}
    <div class="daw-header">
        <div>
            <h1 class="daw-title" data-translate="add_mhs" data-translate-page="dosen_add_mhs">
                Tambah Mahasiswa Baru
            </h1>
            <p class="daw-subtitle" data-translate="add_mhs_desc" data-translate-page="dosen_add_mhs">
                Form tambah mahasiswa untuk dosen
            </p>
        </div>
    </div>

    {{-- ===== INFO AKADEMIK DOSEN (otomatis) ===== --}}
    @if($dosen->id_jurusan || $dosen->id_keahlian || $dosen->id_angkatan)
    <div class="daw-info-banner">
        <div class="daw-info-banner-icon">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="daw-info-banner-text">
            <span class="daw-info-banner-label">Data akademik mahasiswa akan otomatis diisi berdasarkan data Anda:</span>
            <div class="daw-info-tags">
                @foreach($jurusan as $j)
                    <span class="daw-info-tag daw-tag-blue">
                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                        {{ $j->nama_jurusan }}
                    </span>
                @endforeach
                @foreach($keahlian as $k)
                    <span class="daw-info-tag daw-tag-purple">
                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        {{ $k->nama_keahlian }}
                    </span>
                @endforeach
                @foreach($angkatan as $a)
                    <span class="daw-info-tag daw-tag-green">
                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Angkatan {{ $a->nama_angkatan }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ===== REGISTRATION TYPE ===== --}}
    <div class="daw-reg-type">
        <span class="daw-reg-type-label">Tipe Registrasi</span>
        <div class="daw-reg-type-options">
            <label class="daw-reg-option" id="regSimpleLabel">
                <input type="radio" name="registration_type_ui" value="simple" onchange="toggleRegistrationType('simple')" checked>
                <div class="daw-reg-option-inner">
                    <div class="daw-reg-option-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="daw-reg-option-title">Registrasi Sederhana</p>
                        <p class="daw-reg-option-sub">NIM, Nama, Tanggal Lahir — Password = NIM</p>
                    </div>
                </div>
            </label>
            <label class="daw-reg-option" id="regFullLabel">
                <input type="radio" name="registration_type_ui" value="full" onchange="toggleRegistrationType('full')">
                <div class="daw-reg-option-inner">
                    <div class="daw-reg-option-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="daw-reg-option-title">Registrasi Lengkap</p>
                        <p class="daw-reg-option-sub">Semua data termasuk Username & Password sendiri</p>
                    </div>
                </div>
            </label>
        </div>
    </div>

    {{-- ===== MAIN FORM ===== --}}
    <div class="daw-form-card">
        <form method="POST" action="{{ route('dosen.users.StoreUser') }}" enctype="multipart/form-data" id="dosenUserForm">
            @csrf
            <input type="hidden" name="registration_type" id="registrationType" value="simple">

            {{-- ─── SECTION: Informasi Mahasiswa ─── --}}
            <div class="daw-section">
                <div class="daw-section-header">
                    <div class="daw-section-dot"></div>
                    <h2 class="daw-section-title" data-translate="info_mhs" data-translate-page="dosen_add_mhs">
                        Informasi Mahasiswa
                    </h2>
                </div>

                <div class="daw-fields-grid">

                    {{-- NIM --}}
                    <div class="daw-field" id="nimField">
                        <label class="daw-label">
                            NIM <span class="daw-required">*</span>
                        </label>
                        <input type="text" name="nim" id="nimInput" value="{{ old('nim') }}"
                            placeholder="Contoh: 20230010001"
                            class="daw-input @error('nim') daw-input-error @enderror">
                        @error('nim')
                            <p class="daw-error-msg">{{ $message }}</p>
                        @enderror
                        <p class="daw-hint" id="nimHint">Password default mahasiswa = NIM (bisa diubah setelah login)</p>
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="daw-field">
                        <label class="daw-label">
                            <span data-translate="nm_lgkp" data-translate-page="dosen_add_mhs">Nama Lengkap</span>
                            <span class="daw-required">*</span>
                        </label>
                        <input type="text" name="nama_mahasiswa" value="{{ old('nama_mahasiswa') }}"
                            placeholder="Masukkan nama lengkap"
                            class="daw-input @error('nama_mahasiswa') daw-input-error @enderror"
                            required>
                        @error('nama_mahasiswa')
                            <p class="daw-error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="daw-field" id="tanggalLahirField">
                        <label class="daw-label">
                            Tanggal Lahir
                            <span class="daw-required" id="tanggalLahirRequired" style="display:none">*</span>
                        </label>
                        <input type="date" name="tanggal_lahir" id="tanggalLahirInput"
                            value="{{ old('tanggal_lahir') }}"
                            max="{{ date('Y-m-d') }}"
                            class="daw-input @error('tanggal_lahir') daw-input-error @enderror">
                        @error('tanggal_lahir')
                            <p class="daw-error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="daw-field" id="jenisKelaminField" style="display:none">
                        <label class="daw-label" >
                            <span data-translate="klmn" data-translate-page="dosen_add_mhs">Jenis Kelamin</span>
                        </label>
                        <select name="jenis_kelamin"
                            class="daw-select @error('jenis_kelamin') daw-input-error @enderror">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            <option value="Tidak ingin memberi tahu" {{ old('jenis_kelamin') == 'Tidak ingin memberi tahu' ? 'selected' : '' }}>Tidak ingin memberi tahu</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="daw-error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Username (full only) --}}
                    <div class="daw-field" id="usernameField" style="display:none">
                        <label class="daw-label">
                            <span data-translate="usn" data-translate-page="dosen_add_mhs">Username</span>
                            <span class="daw-required">*</span>
                        </label>
                        <input type="text" name="username" id="usernameInput" value="{{ old('username') }}"
                            placeholder="Contoh: john_doe"
                            class="daw-input @error('username') daw-input-error @enderror">
                        @error('username')
                            <p class="daw-error-msg">{{ $message }}</p>
                        @enderror
                        <p class="daw-hint">Hanya huruf, angka, dan underscore (_)</p>
                    </div>

                    {{-- Email (full only) --}}
                    <div class="daw-field" id="emailField" style="display:none">
                        <label class="daw-label">
                            <span data-translate="email" data-translate-page="dosen_add_mhs">Email</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="contoh@email.com"
                            class="daw-input @error('email') daw-input-error @enderror">
                        @error('email')
                            <p class="daw-error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password (full only) --}}
                    <div class="daw-field" id="passwordField" style="display:none">
                        <label class="daw-label">
                            <span data-translate="pw" data-translate-page="dosen_add_mhs">Password</span>
                            <span class="daw-required">*</span>
                        </label>
                        <input type="password" name="password" id="passwordInput"
                            minlength="8"
                            pattern="^(?=.*[A-Z])(?!.*\s).{8,}$"
                            title="Password harus minimal 8 karakter, mengandung minimal 1 huruf besar, dan tidak boleh ada spasi"
                            placeholder="Minimal 8 karakter dengan huruf besar"
                            class="daw-input @error('password') daw-input-error @enderror">
                        <div class="daw-pw-rules">
                            <p class="daw-pw-rules-title">Syarat Password:</p>
                            <ul class="daw-pw-rules-list">
                                <li><span class="daw-pw-dot"></span> Minimal 8 karakter</li>
                                <li><span class="daw-pw-dot"></span> Harus ada huruf besar (A–Z)</li>
                                <li><span class="daw-pw-dot"></span> Tidak boleh ada spasi</li>
                            </ul>
                        </div>
                        @error('password')
                            <p class="daw-error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password (full only) --}}
                    <div class="daw-field" id="passwordConfirmField" style="display:none">
                        <label class="daw-label">
                            <span data-translate="pw_conf" data-translate-page="dosen_add_mhs">Konfirmasi Password</span>
                            <span class="daw-required">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="passwordConfirmInput"
                            placeholder="Ulangi password"
                            class="daw-input">
                    </div>

                    {{-- Foto Profil --}}
                    <div class="daw-field daw-field-full" id="photoField" style="display:none">
                        <label class="daw-label">
                            <span data-translate="pfp" data-translate-page="dosen_add_mhs">Foto Profil</span>
                        </label>
                        <div class="daw-photo-wrap">
                            <div class="daw-photo-left">
                                <input type="file" name="photo_profile" id="photo_profile"
                                    accept="image/jpeg,image/png,image/jpg"
                                    class="daw-file-input"
                                    onchange="handlePhotoChange(this)">
                                <p class="daw-hint" style="margin-top:6px;">Format: JPEG, PNG, JPG. Maks: 2MB</p>
                            </div>
                            <div class="daw-photo-right">
                                <p class="daw-preview-label">Preview</p>
                                <div class="daw-preview-frame" id="previewFrame">
                                    <img id="preview" src="https://via.placeholder.com/150"
                                        class="daw-preview-img" alt="Preview foto">
                                    <div class="daw-preview-overlay" id="previewOverlay">
                                        <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>Belum dipilih</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('photo_profile')
                            <p class="daw-error-msg" style="margin-top:8px;">{{ $message }}</p>
                        @enderror
                    </div>

                </div>{{-- /daw-fields-grid --}}
            </div>{{-- /Informasi Mahasiswa --}}

            {{-- ─── SECTION: Excel Import (simple only) ─── --}}
            <div id="excelImportSection" class="daw-section">
                <div class="daw-section-header">
                    <div class="daw-section-dot daw-section-dot-emerald"></div>
                    <h2 class="daw-section-title">Import Massal dari Excel</h2>
                </div>

                <div class="daw-excel-box">
                    <div class="daw-excel-topbar">
                        <div class="daw-excel-topbar-left">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Import Data Mahasiswa dari Excel
                        </div>
                        <button type="button" onclick="downloadTemplate()" class="daw-btn-dl">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download Template
                        </button>
                    </div>

                    <div class="daw-excel-info">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Kolom Excel: <strong>NIM</strong>, <strong>Nama Lengkap</strong>, <strong>Tanggal Lahir</strong> (opsional). 
                        Jurusan, Keahlian, dan Angkatan otomatis dari data Anda.
                    </div>

                    <div class="daw-excel-dropzone" onclick="document.getElementById('excelFile').click()" id="excelDropzone">
                        <input type="file" id="excelFile" accept=".xlsx,.xls" class="hidden" onchange="handleExcelUpload(this)">
                        <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="daw-excel-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="daw-excel-drop-title">Klik atau seret file Excel ke sini</p>
                        <p class="daw-excel-drop-sub">Mendukung format .xlsx dan .xls • Maks 5MB</p>
                    </div>

                    {{-- Preview Table --}}
                    <div id="excelPreview" class="hidden">
                        <p class="daw-excel-preview-title">Preview Data (maks. 5 baris):</p>
                        <div class="daw-excel-table-wrap">
                            <table class="daw-excel-table">
                                <thead><tr id="previewHeader"></tr></thead>
                                <tbody id="previewBody"></tbody>
                            </table>
                        </div>
                        <div class="daw-excel-apply-row">
                            <button type="button" onclick="resetExcel()" class="daw-btn-reset">
                                Reset
                            </button>
                            <button type="button" onclick="importExcelData()" class="daw-btn-mass-import">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Import Massal ke Database
                            </button>
                        </div>
                    </div>
                </div>
            </div>{{-- /Excel Import --}}

            {{-- ─── BUTTONS ─── --}}
            <div class="daw-form-actions">
                <a href="{{ route('dosen.users.index') }}" class="daw-btn-cancel"
                    data-translate="cancel" data-translate-page="dosen_add_mhs">
                    Batal
                </a>
                <button type="submit" id="submitBtn" class="daw-btn-submit">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span data-translate="add" data-translate-page="dosen_add_mhs">Tambah Mahasiswa</span>
                </button>
            </div>

        </form>
    </div>{{-- /daw-form-card --}}
</div>

{{-- ===== STYLES ===== --}}
<style>
/* ── Root ─────────────────────────────── */
.daw-wrapper {
    max-width: 860px;
    margin: 0 auto;
    padding: 2rem 1.25rem 4rem;
}

/* ── Header ───────────────────────────── */
.daw-header { margin-bottom: 1.5rem; }
.daw-title {
    font-size: 1.6rem; font-weight: 700;
    color: #111827; margin: 0; line-height: 1.2;
}
.dark .daw-title { color: #f9fafb; }
.daw-subtitle { font-size: 0.875rem; color: #6b7280; margin: 4px 0 0; }
.dark .daw-subtitle { color: #9ca3af; }

/* ── Info Banner (akademik otomatis) ───── */
.daw-info-banner {
    display: flex; align-items: flex-start; gap: 12px;
    background: #eff6ff;
    border: 1.5px solid #bfdbfe;
    border-radius: 14px;
    padding: 14px 16px;
    margin-bottom: 1.5rem;
}
.dark .daw-info-banner {
    background: rgba(59,130,246,.08);
    border-color: rgba(59,130,246,.3);
}
.daw-info-banner-icon {
    width: 32px; height: 32px; flex-shrink: 0;
    background: #dbeafe; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: #2563eb;
}
.dark .daw-info-banner-icon { background: rgba(59,130,246,.2); }
.daw-info-banner-label {
    font-size: 0.8rem; font-weight: 500; color: #1d4ed8;
    display: block; margin-bottom: 8px;
}
.dark .daw-info-banner-label { color: #93c5fd; }
.daw-info-tags { display: flex; flex-wrap: wrap; gap: 6px; }
.daw-info-tag {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem; font-weight: 500;
}
.daw-tag-blue   { background: #dbeafe; color: #1e40af; }
.daw-tag-purple { background: #ede9fe; color: #5b21b6; }
.daw-tag-green  { background: #d1fae5; color: #065f46; }
.dark .daw-tag-blue   { background: rgba(59,130,246,.2); color: #93c5fd; }
.dark .daw-tag-purple { background: rgba(139,92,246,.2); color: #c4b5fd; }
.dark .daw-tag-green  { background: rgba(16,185,129,.2); color: #6ee7b7; }

/* ── Registration Type ────────────────── */
.daw-reg-type {
    background: #eff6ff;
    border: 1.5px solid #bfdbfe;
    border-radius: 14px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
}
.dark .daw-reg-type {
    background: rgba(59,130,246,.08);
    border-color: rgba(59,130,246,.3);
}
.daw-reg-type-label {
    display: block;
    font-size: 0.78rem; font-weight: 600;
    color: #1d4ed8; text-transform: uppercase; letter-spacing: .05em;
    margin-bottom: 10px;
}
.dark .daw-reg-type-label { color: #93c5fd; }
.daw-reg-type-options { display: flex; gap: 12px; flex-wrap: wrap; }
.daw-reg-option { flex: 1; min-width: 220px; cursor: pointer; position: relative; }
.daw-reg-option input { position: absolute; opacity: 0; width: 0; }
.daw-reg-option-inner {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 14px;
    border-radius: 10px;
    border: 1.5px solid #bfdbfe;
    background: white;
    transition: border-color .18s, background .18s, box-shadow .18s;
}
.dark .daw-reg-option-inner { background: #1f2937; border-color: #374151; }
.daw-reg-option input:checked ~ .daw-reg-option-inner {
    border-color: #3b82f6;
    background: #dbeafe;
    box-shadow: 0 0 0 3px rgba(59,130,246,.12);
}
.dark .daw-reg-option input:checked ~ .daw-reg-option-inner { background: rgba(59,130,246,.18); }
.daw-reg-option-icon {
    width: 36px; height: 36px;
    border-radius: 9px; background: #dbeafe;
    display: flex; align-items: center; justify-content: center;
    color: #2563eb; flex-shrink: 0;
}
.dark .daw-reg-option-icon { background: rgba(59,130,246,.2); }
.daw-reg-option input:checked ~ .daw-reg-option-inner .daw-reg-option-icon { background: #2563eb; color: white; }
.daw-reg-option-title { font-size: 0.875rem; font-weight: 600; color: #1e3a8a; margin: 0; }
.dark .daw-reg-option-title { color: #bfdbfe; }
.daw-reg-option-sub { font-size: 0.75rem; color: #6b7280; margin: 2px 0 0; }
.dark .daw-reg-option-sub { color: #9ca3af; }

/* ── Form Card ────────────────────────── */
.daw-form-card {
    background: white;
    border: 1.5px solid #e5e7eb;
    border-radius: 24px;
    padding: 2rem 2rem 1.5rem;
    box-shadow: 0 4px 24px -4px rgba(0,0,0,.07);
}
.dark .daw-form-card { background: #1f2937; border-color: #374151; }

/* ── Sections ─────────────────────────── */
.daw-section { margin-bottom: 2rem; }
.daw-section.hidden { display: none; }
.daw-section-header {
    display: flex; align-items: center; gap: 10px;
    padding-bottom: 12px;
    border-bottom: 1.5px solid #f3f4f6;
    margin-bottom: 1.25rem;
}
.dark .daw-section-header { border-color: #374151; }
.daw-section-dot {
    width: 10px; height: 10px; border-radius: 50%;
    background: #3b82f6; flex-shrink: 0;
}
.daw-section-dot-emerald { background: #059669; }
.daw-section-title { font-size: 1.1rem; font-weight: 600; color: #111827; margin: 0; }
.dark .daw-section-title { color: #f9fafb; }

/* ── Fields Grid ──────────────────────── */
.daw-fields-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem 1.5rem;
}
@media (max-width: 640px) { .daw-fields-grid { grid-template-columns: 1fr; } }
.daw-field-full { grid-column: 1 / -1; }

/* ── Label / Input ────────────────────── */
.daw-label {
    display: block;
    font-size: 0.8125rem; font-weight: 500;
    color: #374151; margin-bottom: 6px;
}
.dark .daw-label { color: #d1d5db; }
.daw-required { color: #ef4444; }
.daw-input, .daw-select {
    width: 100%; padding: 10px 14px;
    border: 1.5px solid #d1d5db;
    border-radius: 12px;
    font-size: 0.875rem;
    background: #f9fafb; color: #111827;
    outline: none;
    transition: border-color .18s, box-shadow .18s, background .18s;
    appearance: none; -webkit-appearance: none;
    box-sizing: border-box;
}
.dark .daw-input, .dark .daw-select { background: #111827; border-color: #4b5563; color: #f3f4f6; }
.daw-input:focus, .daw-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    background: white;
}
.dark .daw-input:focus, .dark .daw-select:focus { background: #1f2937; }
.daw-input-error { border-color: #ef4444 !important; }
.daw-error-msg { font-size: 0.78rem; color: #ef4444; margin-top: 4px; }
.daw-hint { font-size: 0.75rem; color: #9ca3af; margin-top: 5px; }

/* ── Password Rules ───────────────────── */
.daw-pw-rules {
    margin-top: 8px; padding: 10px 12px;
    background: #eff6ff;
    border: 1px solid #bfdbfe; border-radius: 10px;
}
.dark .daw-pw-rules { background: rgba(59,130,246,.08); border-color: rgba(59,130,246,.2); }
.daw-pw-rules-title { font-size: 0.75rem; font-weight: 600; color: #1e40af; margin: 0 0 5px; }
.dark .daw-pw-rules-title { color: #93c5fd; }
.daw-pw-rules-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 3px; }
.daw-pw-rules-list li { font-size: 0.75rem; color: #1d4ed8; display: flex; align-items: center; gap: 7px; }
.dark .daw-pw-rules-list li { color: #bfdbfe; }
.daw-pw-dot { width: 6px; height: 6px; border-radius: 50%; background: #60a5fa; flex-shrink: 0; }

/* ── Photo ────────────────────────────── */
.daw-photo-wrap { display: flex; gap: 1.5rem; align-items: flex-start; flex-wrap: wrap; }
.daw-photo-left { flex: 1; min-width: 200px; }
.daw-photo-right { display: flex; flex-direction: column; align-items: center; gap: 8px; }
.daw-preview-label { font-size: 0.72rem; font-weight: 500; text-transform: uppercase; letter-spacing: .06em; color: #9ca3af; margin: 0; }
.daw-preview-frame {
    width: 88px; height: 88px;
    border-radius: 16px;
    border: 2px dashed #d1d5db;
    overflow: hidden; position: relative;
    background: #f9fafb;
}
.dark .daw-preview-frame { background: #111827; border-color: #374151; }
.daw-preview-frame.has-image { border-style: solid; border-color: #3b82f6; }
.daw-preview-img { width: 100%; height: 100%; object-fit: cover; display: block; }
.daw-preview-overlay {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 4px; background: rgba(17,24,39,.45);
    color: white; font-size: 0.65rem; text-align: center; padding: 6px;
}
.daw-preview-overlay.hidden-overlay { display: none; }
.daw-file-input { font-size: 0.8125rem; color: #6b7280; cursor: pointer; width: 100%; }
.daw-file-input::file-selector-button {
    margin-right: 10px; padding: 7px 14px;
    border: none; border-radius: 9px;
    background: #dbeafe; color: #1d4ed8;
    font-size: 0.8rem; font-weight: 500;
    cursor: pointer; transition: background .15s;
}
.daw-file-input::file-selector-button:hover { background: #bfdbfe; }
.dark .daw-file-input::file-selector-button { background: rgba(59,130,246,.18); color: #93c5fd; }

/* ── Excel Import ─────────────────────── */
.daw-excel-box {
    background: #f0fdf4;
    border: 1.5px solid #bbf7d0;
    border-radius: 16px; padding: 1.25rem;
}
.dark .daw-excel-box { background: rgba(16,185,129,.06); border-color: rgba(16,185,129,.25); }
.daw-excel-topbar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px; margin-bottom: 12px;
}
.daw-excel-topbar-left {
    display: flex; align-items: center; gap: 8px;
    font-size: 0.9rem; font-weight: 600; color: #065f46;
}
.dark .daw-excel-topbar-left { color: #6ee7b7; }
.daw-btn-dl {
    display: flex; align-items: center; gap: 6px;
    padding: 7px 14px; background: #059669; color: white;
    border: none; border-radius: 9px;
    font-size: 0.8rem; font-weight: 500;
    cursor: pointer; transition: background .15s;
}
.daw-btn-dl:hover { background: #047857; }
.daw-excel-info {
    display: flex; align-items: flex-start; gap: 7px;
    font-size: 0.78rem; color: #065f46;
    background: rgba(16,185,129,.1);
    border-radius: 8px; padding: 8px 12px;
    margin-bottom: 12px; line-height: 1.5;
}
.dark .daw-excel-info { color: #6ee7b7; background: rgba(16,185,129,.12); }
.daw-excel-dropzone {
    border: 2px dashed #6ee7b7; border-radius: 12px;
    padding: 2rem 1.5rem; text-align: center;
    cursor: pointer;
    transition: background .15s, border-color .15s;
}
.dark .daw-excel-dropzone { border-color: rgba(16,185,129,.4); }
.daw-excel-dropzone:hover { background: rgba(16,185,129,.08); border-color: #10b981; }
.daw-excel-icon { color: #10b981; margin: 0 auto 10px; display: block; }
.daw-excel-drop-title { font-size: 0.875rem; color: #065f46; font-weight: 500; margin: 0 0 4px; }
.dark .daw-excel-drop-title { color: #6ee7b7; }
.daw-excel-drop-sub { font-size: 0.75rem; color: #6b7280; margin: 0; }
.daw-excel-preview-title { font-size: 0.875rem; font-weight: 600; color: #065f46; margin: 1rem 0 8px; }
.dark .daw-excel-preview-title { color: #6ee7b7; }
.daw-excel-table-wrap { overflow-x: auto; border-radius: 10px; }
.daw-excel-table {
    width: 100%; border-collapse: collapse;
    background: white; font-size: 0.8rem;
}
.dark .daw-excel-table { background: #1f2937; }
.daw-excel-table thead { background: #f0fdf4; }
.dark .daw-excel-table thead { background: rgba(16,185,129,.1); }
.daw-excel-table th { padding: 9px 12px; text-align: left; font-weight: 600; color: #065f46; font-size: 0.78rem; }
.dark .daw-excel-table th { color: #6ee7b7; }
.daw-excel-table td { padding: 8px 12px; color: #374151; border-top: 1px solid #f3f4f6; }
.dark .daw-excel-table td { color: #d1d5db; border-color: #374151; }
.daw-excel-apply-row { display: flex; justify-content: flex-end; gap: 10px; margin-top: 12px; }
.daw-btn-reset {
    padding: 8px 18px; background: #6b7280; color: white;
    border: none; border-radius: 9px;
    font-size: 0.85rem; font-weight: 500; cursor: pointer;
    transition: background .15s;
}
.daw-btn-reset:hover { background: #4b5563; }
.daw-btn-mass-import {
    display: flex; align-items: center; gap: 7px;
    padding: 8px 20px;
    background: linear-gradient(135deg, #059669, #047857);
    color: white; border: none; border-radius: 9px;
    font-size: 0.85rem; font-weight: 600; cursor: pointer;
    transition: opacity .15s, transform .15s;
}
.daw-btn-mass-import:hover { opacity: .9; transform: translateY(-1px); }

/* ── Form Actions ─────────────────────── */
.daw-form-actions {
    display: flex; justify-content: flex-end; align-items: center;
    gap: 12px; padding-top: 1.5rem;
    border-top: 1.5px solid #f3f4f6;
}
.dark .daw-form-actions { border-color: #374151; }
.daw-btn-cancel {
    padding: 10px 24px;
    border: 1.5px solid #d1d5db; border-radius: 12px;
    color: #374151; font-size: 0.875rem; font-weight: 500;
    text-decoration: none; transition: background .15s;
}
.dark .daw-btn-cancel { border-color: #4b5563; color: #d1d5db; }
.daw-btn-cancel:hover { background: #f3f4f6; }
.dark .daw-btn-cancel:hover { background: #374151; }
.daw-btn-submit {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 24px;
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: white; border: none; border-radius: 12px;
    font-size: 0.875rem; font-weight: 600; cursor: pointer;
    transition: opacity .15s, transform .15s;
    box-shadow: 0 4px 14px -2px rgba(59,130,246,.35);
}
.daw-btn-submit:hover { opacity: .9; transform: translateY(-1px); }

/* ── Utility ──────────────────────────── */
.hidden { display: none !important; }
</style>

{{-- ===== SCRIPTS ===== --}}
<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
<script>
let excelData = [];
let currentType = 'simple';

/* ─── Toggle Registration Type ─── */
function toggleRegistrationType(type) {
    currentType = type;
    document.getElementById('registrationType').value = type;

    const fields = {
        nim           : document.getElementById('nimField'),
        tgl           : document.getElementById('tanggalLahirField'),
        username      : document.getElementById('usernameField'),
        email         : document.getElementById('emailField'),
        password      : document.getElementById('passwordField'),
        jenisKelamin  : document.getElementById('jenisKelaminField'),  // Tambahkan ini
        confirm       : document.getElementById('passwordConfirmField'),
        photo         : document.getElementById('photoField'),         // Tambahkan ini
        nimInput      : document.getElementById('nimInput'),
        tglInput      : document.getElementById('tanggalLahirInput'),
        usernameInput : document.getElementById('usernameInput'),
        passwordInput : document.getElementById('passwordInput'),
        confirmInput  : document.getElementById('passwordConfirmInput'),
        tglReq        : document.getElementById('tanggalLahirRequired'),
        nimHint       : document.getElementById('nimHint'),
        excel         : document.getElementById('excelImportSection'),
    };

    if (type === 'simple') {
        // Tampilkan hanya NIM, Tanggal Lahir, dan Excel
        show(fields.nim); 
        show(fields.tgl); 
        show(fields.excel);
        
        // Sembunyikan semua field yang tidak diperlukan di mode simple
        hide(fields.jenisKelamin);
        hide(fields.username);
        hide(fields.email);
        hide(fields.password);
        hide(fields.confirm);
        hide(fields.photo);      // Sembunyikan foto profil di mode simple

        // Required settings
        setRequired(fields.nimInput, true);
        setRequired(fields.tglInput, false);
        setRequired(fields.usernameInput, false);
        setRequired(fields.passwordInput, false);
        setRequired(fields.confirmInput, false);

        fields.tglReq.style.display = 'none';
        fields.nimHint.style.display = 'block';

    } else { // full
        // Tampilkan semua field termasuk jenis kelamin dan foto
        show(fields.nim);
        show(fields.tgl);
        show(fields.jenisKelamin);  // Tampilkan jenis kelamin
        show(fields.username);
        show(fields.email);
        show(fields.password);
        show(fields.confirm);
        show(fields.photo);         // Tampilkan foto profil

        // Required settings
        setRequired(fields.nimInput, true);
        setRequired(fields.tglInput, true);
        setRequired(fields.usernameInput, true);
        setRequired(fields.passwordInput, true);
        setRequired(fields.confirmInput, true);
        // Jenis kelamin dan foto tidak wajib diisi (tidak pakai required)
        setRequired(document.querySelector('select[name="jenis_kelamin"]'), false);
        setRequired(document.getElementById('photo_profile'), false);

        fields.tglReq.style.display = 'inline';
        fields.nimHint.style.display = 'none';

        // Sembunyikan Excel import
        hide(fields.excel);
        resetExcel();
    }
}

function show(el) { if (el) el.style.display = 'block'; }
function hide(el) { if (el) el.style.display = 'none'; }
function setRequired(el, val) { if (el) el.required = val; }

/* ─── Photo Preview ─── */
function handlePhotoChange(input) {
    if (!input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById('preview');
        img.src = e.target.result;
        document.getElementById('previewFrame').classList.add('has-image');
        document.getElementById('previewOverlay').classList.add('hidden-overlay');
    };
    reader.readAsDataURL(input.files[0]);
}

/* ─── Tanggal Lahir Validation ─── */
document.getElementById('tanggalLahirInput').addEventListener('change', function () {
    const today = new Date().toISOString().split('T')[0];
    const existing = document.getElementById('tanggalLahirClientError');
    if (existing) existing.remove();
    if (this.value > today) {
        this.classList.add('daw-input-error');
        const err = document.createElement('p');
        err.id = 'tanggalLahirClientError';
        err.className = 'daw-error-msg';
        err.textContent = 'Tanggal lahir tidak boleh melebihi tanggal hari ini';
        this.parentNode.appendChild(err);
        this.value = '';
    } else {
        this.classList.remove('daw-input-error');
    }
});

/* ─── Excel: Upload & Preview ─── */
function handleExcelUpload(input) {
    const file = input.files[0];
    if (!file) return;
    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file terlalu besar! Maksimal 5MB.');
        input.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = e => {
        try {
            const wb   = XLSX.read(new Uint8Array(e.target.result), { type: 'array' });
            const json = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]]);
            if (!json.length) { alert('File Excel kosong!'); return; }
            excelData = json;
            displayExcelPreview(json);
        } catch (err) {
            console.error(err);
            alert('Gagal membaca file Excel. Pastikan format file benar.');
        }
    };
    reader.readAsArrayBuffer(file);
}

function displayExcelPreview(data) {
    const headers   = Object.keys(data[0]);
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

function resetExcel() {
    excelData = [];
    document.getElementById('excelPreview').classList.add('hidden');
    const excelFile = document.getElementById('excelFile');
    if (excelFile) excelFile.value = '';
}

/* ─── Excel: Validate ─── */
function validateExcelData() {
    const errors = [];
    const nims   = new Set();
    excelData.forEach((row, index) => {
        const nim  = row.NIM || row.nim || row['Nama Lengkap'] ? (row.NIM || row.nim) : '';
        const nama = row['Nama Lengkap'] || row.nama || row.Nama;
        if (!nim)           errors.push(`Baris ${index + 2}: NIM tidak boleh kosong`);
        if (!nama)          errors.push(`Baris ${index + 2}: Nama tidak boleh kosong`);
        if (nims.has(nim))  errors.push(`Baris ${index + 2}: NIM duplikat dalam file`);
        if (nim) nims.add(nim);
    });
    if (errors.length) { alert('Validasi gagal:\n' + errors.join('\n')); return false; }
    return true;
}

/* ─── Excel: Import Massal ─── */
async function importExcelData() {
    if (!excelData.length) { alert('Tidak ada data Excel untuk diimport!'); return; }
    if (!validateExcelData()) return;

    const confirmed = confirm(
        `Anda akan mengimport ${excelData.length} data mahasiswa.\n` +
        `Jurusan, Keahlian, dan Angkatan akan otomatis diisi dari data Anda.\n\n` +
        `Password default = NIM masing-masing mahasiswa.\n\nLanjutkan?`
    );
    if (!confirmed) return;

    const excelFile = document.getElementById('excelFile').files[0];
    if (!excelFile) { alert('File Excel tidak ditemukan!'); return; }

    const formData = new FormData();
    formData.append('excel_file', excelFile);
    formData.append('_token', document.querySelector('input[name="_token"]').value);

    // Loading state
    const dropzone = document.getElementById('excelDropzone');
    const originalHTML = dropzone.innerHTML;
    dropzone.innerHTML = `
        <div style="text-align:center;padding:2rem;">
            <div style="display:inline-block;width:40px;height:40px;border:3px solid #bbf7d0;border-top:3px solid #059669;border-radius:50%;animation:dawSpin 1s linear infinite;"></div>
            <p style="margin-top:1rem;color:#065f46;font-weight:500;">Sedang mengimport data...</p>
        </div>`;
    dropzone.style.pointerEvents = 'none';

    if (!document.querySelector('#daw-spin-style')) {
        const style = document.createElement('style');
        style.id = 'daw-spin-style';
        style.textContent = `@keyframes dawSpin{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}`;
        document.head.appendChild(style);
    }

    try {
        const response = await fetch('{{ route("dosen.users.importExcel") }}', {
            method: 'POST',
            body: formData,
        });
        const result = await response.json();

        // Restore dropzone
        dropzone.innerHTML = originalHTML;
        dropzone.style.pointerEvents = 'auto';

        if (result.success) {
            let msg = result.message;
            if (result.failedRows && result.failedRows.length) {
                msg += '\n\n❌ Gagal di baris:\n';
                result.failedRows.slice(0, 10).forEach(f => { msg += `Baris ${f.row}: ${f.reason}\n`; });
                if (result.failedRows.length > 10) msg += `... dan ${result.failedRows.length - 10} baris lainnya`;
            }
            msg += `\n\n📊 Statistik:\n- Berhasil: ${result.stats.success}\n- Gagal: ${result.stats.failed}\n- Total: ${result.stats.total}`;
            alert(msg);
            if (result.stats.success > 0) {
                if (confirm('Import selesai! Me-refresh halaman untuk melihat data terbaru?')) {
                    window.location.reload();
                }
            }
        } else {
            alert('Gagal import: ' + result.message);
        }
    } catch (err) {
        dropzone.innerHTML = originalHTML;
        dropzone.style.pointerEvents = 'auto';
        console.error(err);
        alert('Terjadi kesalahan saat mengimport data.');
    }
}

/* ─── Download Template ─── */
function downloadTemplate() {
    const data = [
        { 'NIM': '20250010001', 'Nama Lengkap': 'Ahmad Budi Santoso', 'Tanggal Lahir': '2005-03-15' },
        { 'NIM': '20250010002', 'Nama Lengkap': 'Siti Nurhaliza',     'Tanggal Lahir': '2004-07-22' },
    ];
    const ws = XLSX.utils.json_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Template Mahasiswa');
    XLSX.writeFile(wb, 'template_import_mahasiswa_dosen.xlsx');
}

/* ─── Init ─── */
document.addEventListener('DOMContentLoaded', () => {
    // Default: simple already selected by HTML checked attr
    toggleRegistrationType('simple');

    @if(old('registration_type'))
        toggleRegistrationType('{{ old('registration_type') }}');
        document.querySelectorAll('input[name="registration_type_ui"]').forEach(r => {
            r.checked = r.value === '{{ old('registration_type') }}';
        });
    @endif
});
</script>

<!-- Page Info -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        if (typeof showPageInfo === 'function') showPageInfo("popup.dosen_add_user");
    });
</script>

@endsection