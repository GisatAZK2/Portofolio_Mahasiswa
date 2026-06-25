@extends('Layout.Layout')

@section('title', 'Edit User')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white"
                    data-translate="edit_user_title" data-translate-page="dosen_edit_user">Edit User</h1>
                <a href="{{ route('dosen.users.index') }}"
                    class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg transition duration-200 text-sm font-medium">
                    <i class="fas fa-arrow-left text-xs"></i>
                    <span data-translate="back" data-translate-page="admin">Kembali</span>
                </a>
            </div>

            {{-- Page Info Banner --}}
            <div class="mb-5 px-4 py-3 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 flex items-start gap-3">
                <i class="fas fa-circle-info text-blue-500 mt-0.5 text-sm"></i>
                <p class="text-sm text-blue-700 dark:text-blue-300"
                   data-translate="dosen_edit_user" data-translate-page="popup">
                    Edit informasi user yang sudah ada. Perbarui nama, username, email, atau role sesuai kebutuhan.
                </p>
            </div>

            {{-- Form Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

                {{-- Card Header --}}
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80">
                    <div class="flex items-center gap-3">
                        @if($user->photo_profile)
                            <img src="{{ Storage::url($user->photo_profile) }}" alt="Avatar"
                                 class="w-10 h-10 rounded-full object-cover ring-2 ring-blue-500/30">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr($user->nama_mahasiswa, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white text-sm">{{ $user->nama_mahasiswa }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">@{{ $user->username }}</p>
                        </div>
                        <span class="ml-auto px-2.5 py-1 rounded-full text-xs font-medium
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300' :
                               ($user->role === 'dosen' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' :
                               'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('dosen.users.edit', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="p-6">

                        {{-- ───── Section: Informasi Dasar ───── --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-1 h-5 bg-blue-500 rounded-full"></div>
                                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
                                    data-translate="info" data-translate-page="admin">Informasi Dasar</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                {{-- Nama Lengkap --}}
                                <div>
                                    <label for="nama_mahasiswa"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        <span data-translate="nm_lgkp" data-translate-page="admin">Nama Lengkap</span>
                                        <span class="text-red-500 ml-0.5">*</span>
                                    </label>
                                    <input type="text" name="nama_mahasiswa" id="nama_mahasiswa"
                                        value="{{ old('nama_mahasiswa', $user->nama_mahasiswa) }}"
                                        class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition @error('nama_mahasiswa') border-red-400 bg-red-50 dark:bg-red-900/20 @enderror"
                                        data-translate-placeholder="nm_lgkp_placeholder" data-translate-page="dosen_edit_user"
                                        placeholder="Masukkan nama lengkap" required>
                                    @error('nama_mahasiswa')
                                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Username --}}
                                <div>
                                    <label for="username"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        <span data-translate="usrnm" data-translate-page="admin">Username</span>
                                        <span class="text-red-500 ml-0.5">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">@</span>
                                        <input type="text" name="username" id="username"
                                            value="{{ old('username', $user->username) }}"
                                            class="w-full pl-8 pr-4 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition @error('username') border-red-400 bg-red-50 dark:bg-red-900/20 @enderror"
                                            placeholder="username" required>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-400" data-translate="usrnm_error" data-translate-page="admin">
                                        Hanya huruf, angka, dan underscore (_)
                                    </p>
                                    @error('username')
                                        <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        <span data-translate="email" data-translate-page="admin">Email</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email" name="email" id="email"
                                            value="{{ old('email', $user->email) }}"
                                            class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition @error('email') border-red-400 bg-red-50 dark:bg-red-900/20 @enderror"
                                            placeholder="email@contoh.com">
                                    </div>
                                    @error('email')
                                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ───── Section: Informasi Tambahan (role-dependent) ───── --}}
                        @php
                            $showMahasiswaFields = $user->role === 'mahasiswa';
                            $showDosenFields = in_array($user->role, ['mahasiswa', 'dosen']);
                        @endphp
                        
                        <div class="mb-8">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-1 h-5 bg-indigo-500 rounded-full"></div>
                                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
                                    data-translate="info_lebih" data-translate-page="admin">Informasi Tambahan</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                {{-- NIM (mahasiswa only) --}}
                                @if($showMahasiswaFields)
                                <div>
                                    <label for="nim"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        <span data-translate="nim" data-translate-page="dosen_edit_user">NIM</span>
                                        <span class="text-red-500 ml-0.5">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                                            <i class="fas fa-id-card"></i>
                                        </span>
                                        <input type="text" name="nim" id="nim"
                                            value="{{ old('nim', $user->nim) }}"
                                            class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition @error('nim') border-red-400 bg-red-50 dark:bg-red-900/20 @enderror"
                                            data-translate-placeholder="nim_placeholder" data-translate-page="dosen_edit_user"
                                            placeholder="Masukkan NIM mahasiswa">
                                    </div>
                                    @error('nim')
                                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                @endif

                                {{-- Tanggal Lahir (mahasiswa & dosen) --}}
                                @if($showDosenFields)
                                <div>
                                    <label for="tanggal_lahir"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        <span data-translate="tanggal_lahir" data-translate-page="dosen_edit_user">Tanggal Lahir</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                        <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                            value="{{ old('tanggal_lahir', $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : '') }}"
                                            max="{{ date('Y-m-d') }}"
                                            class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition @error('tanggal_lahir') border-red-400 bg-red-50 dark:bg-red-900/20 @enderror">
                                    </div>
                                    @error('tanggal_lahir')
                                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                @endif

                            </div>
                        </div>

                        {{-- ───── Section: Status ───── --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-1 h-5 bg-emerald-500 rounded-full"></div>
                                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
                                    data-translate="stat_acc" data-translate-page="admin">Status</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                {{-- Status Pengajuan --}}
                                <div>
                                    <label for="status_pengajuan"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        <span data-translate="stat_pengajuan" data-translate-page="admin">Status Pengajuan</span>
                                        <span class="text-red-500 ml-0.5">*</span>
                                    </label>
                                    <select name="status_pengajuan" id="status_pengajuan"
                                        class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition @error('status_pengajuan') border-red-400 @enderror"
                                        required>
                                        <option value="Di Terima"
                                            {{ old('status_pengajuan', $user->status_pengajuan) == 'Di Terima' ? 'selected' : '' }}
                                            data-translate="stat_diterima" data-translate-page="admin">Di Terima</option>
                                        <option value="Di Tolak"
                                            {{ old('status_pengajuan', $user->status_pengajuan) == 'Di Tolak' ? 'selected' : '' }}
                                            data-translate="stat_ditolak" data-translate-page="admin">Di Tolak</option>
                                    </select>
                                    @error('status_pengajuan')
                                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Status Aktif --}}
                                <div>
                                    <label for="is_active"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        <span data-translate="stat_acc" data-translate-page="admin">Status Akun</span>
                                        <span class="text-red-500 ml-0.5">*</span>
                                    </label>
                                    <select name="is_active" id="is_active"
                                        class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition @error('is_active') border-red-400 @enderror"
                                        required>
                                        <option value="1"
                                            {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}
                                            data-translate="stat_aktif" data-translate-page="admin">Aktif</option>
                                        <option value="0"
                                            {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}
                                            data-translate="stat_non_aktif" data-translate-page="admin">Non Aktif</option>
                                    </select>
                                    @error('is_active')
                                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        {{-- ───── Section: Password ───── --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-1 h-5 bg-amber-500 rounded-full"></div>
                                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
                                    data-translate="pw_section" data-translate-page="dosen_edit_user">Password</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                {{-- Password Baru --}}
                                <div>
                                    <label for="password"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        <span data-translate="pw_new" data-translate-page="admin">Password Baru</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" name="password" id="password"
                                            class="w-full pl-9 pr-10 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition @error('password') border-red-400 bg-red-50 dark:bg-red-900/20 @enderror"
                                            data-translate-placeholder="pw_placeholder" data-translate-page="dosen_edit_user"
                                            placeholder="Kosongkan jika tidak diubah">
                                        <button type="button" onclick="togglePassword('password', 'eyeIcon1')"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                                            <i id="eyeIcon1" class="fas fa-eye text-sm"></i>
                                        </button>
                                    </div>

                                    {{-- Password Hints --}}
                                    <div class="mt-2 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-100 dark:border-amber-800/50">
                                        <p class="text-xs font-semibold text-amber-800 dark:text-amber-300 mb-1.5"
                                           data-translate="pw_hint_title" data-translate-page="dosen_edit_user">Syarat Password:</p>
                                        <ul class="space-y-1">
                                            <li class="flex items-center gap-2 text-xs text-amber-700 dark:text-amber-400" id="hint-length">
                                                <span class="w-3.5 h-3.5 rounded-full border border-amber-300 dark:border-amber-600 flex items-center justify-center flex-shrink-0" id="icon-length">
                                                    <i class="fas fa-check text-[9px] hidden text-green-500"></i>
                                                </span>
                                                <span data-translate="pw_hint_min" data-translate-page="dosen_edit_user">Minimal 8 karakter</span>
                                            </li>
                                            <li class="flex items-center gap-2 text-xs text-amber-700 dark:text-amber-400" id="hint-upper">
                                                <span class="w-3.5 h-3.5 rounded-full border border-amber-300 dark:border-amber-600 flex items-center justify-center flex-shrink-0" id="icon-upper">
                                                    <i class="fas fa-check text-[9px] hidden text-green-500"></i>
                                                </span>
                                                <span data-translate="pw_hint_upper" data-translate-page="dosen_edit_user">Harus ada huruf besar (A-Z)</span>
                                            </li>
                                            <li class="flex items-center gap-2 text-xs text-amber-700 dark:text-amber-400" id="hint-space">
                                                <span class="w-3.5 h-3.5 rounded-full border border-amber-300 dark:border-amber-600 flex items-center justify-center flex-shrink-0" id="icon-space">
                                                    <i class="fas fa-check text-[9px] hidden text-green-500"></i>
                                                </span>
                                                <span data-translate="pw_hint_nospace" data-translate-page="dosen_edit_user">Tidak boleh ada spasi</span>
                                            </li>
                                        </ul>
                                    </div>

                                    @error('password')
                                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Konfirmasi Password --}}
                                <div>
                                    <label for="password_confirmation"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        <span data-translate="pw_new_confirm" data-translate-page="admin">Konfirmasi Password Baru</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="w-full pl-9 pr-10 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition"
                                            data-translate-placeholder="pw_confirm_placeholder" data-translate-page="dosen_edit_user"
                                            placeholder="Konfirmasi password baru">
                                        <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                                            <i id="eyeIcon2" class="fas fa-eye text-sm"></i>
                                        </button>
                                    </div>
                                    <div id="confirmMatchHint" class="hidden mt-1.5 text-xs flex items-center gap-1"></div>
                                </div>

                            </div>
                        </div>

                        {{-- ───── Section: Foto Profil ───── --}}
                        <div class="mb-2">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-1 h-5 bg-rose-500 rounded-full"></div>
                                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
                                    data-translate="pfp_new" data-translate-page="admin">Foto Profil</h2>
                            </div>

                            <div class="flex flex-col sm:flex-row items-start gap-5">

                                {{-- Current / Preview Avatar --}}
                                <div class="flex-shrink-0">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2"
                                       data-translate="curr_photo" data-translate-page="dosen_edit_user">Foto Saat Ini</p>
                                    <div class="relative group">
                                        <img id="previewImage"
                                            src="{{ $user->photo_profile ? Storage::url($user->photo_profile) : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama_mahasiswa) . '&background=3b82f6&color=fff&size=128' }}"
                                            alt="Profile"
                                            class="w-24 h-24 object-cover rounded-2xl border-2 border-gray-200 dark:border-gray-600 transition group-hover:border-blue-400">
                                        <label for="photo_profile"
                                            class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-2xl opacity-0 group-hover:opacity-100 transition cursor-pointer">
                                            <i class="fas fa-camera text-white text-lg"></i>
                                        </label>
                                    </div>
                                </div>

                                {{-- Upload Area --}}
                                <div class="flex-1 w-full">
                                    <label for="photo_profile"
                                        class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition group">
                                        <div class="flex flex-col items-center gap-1.5 text-center px-4">
                                            <i class="fas fa-cloud-arrow-up text-2xl text-gray-300 group-hover:text-blue-400 transition"></i>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                <span class="font-medium text-blue-500"
                                                      data-translate="up_file" data-translate-page="admin">Upload File</span>
                                                <span data-translate="or_drag" data-translate-page="admin"> atau drag & drop</span>
                                            </p>
                                            <p class="text-[11px] text-gray-400"
                                               data-translate="pfp_frmt" data-translate-page="admin">Format: JPEG, PNG, JPG. Maks: 2MB</p>
                                        </div>
                                        <input type="file" name="photo_profile" id="photo_profile"
                                            accept="image/jpeg,image/png,image/jpg" class="hidden">
                                    </label>
                                    <p id="fileNameLabel" class="hidden mt-2 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                                        <i class="fas fa-file-image text-blue-400"></i>
                                        <span id="fileNameText"></span>
                                    </p>
                                    @error('photo_profile')
                                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                            </div>
                        </div>

                    </div>{{-- /p-6 --}}

                    {{-- ───── Footer Actions ───── --}}
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80 flex flex-col sm:flex-row justify-end gap-3">
                        <button type="button" id="resetBtn"
                            class="px-5 py-2.5 text-sm font-medium bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <i class="fas fa-rotate-left mr-1.5"></i>
                            <span data-translate="cancel" data-translate-page="admin">Reset</span>
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition shadow-sm shadow-blue-500/30 flex items-center gap-2">
                            <i class="fas fa-floppy-disk"></i>
                            <span data-translate="save_new" data-translate-page="admin">Simpan Perubahan</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection