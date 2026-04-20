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

        <!-- Form Container -->
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 p-6 md:p-10">
            <form method="POST" action="{{ route('admin.users.StoreUser') }}" enctype="multipart/form-data" id="userForm">
                @csrf

                <!-- Hidden Role -->
                <input type="hidden" name="role" id="selectedRole" value="">

                <!-- Informasi Dasar -->
                <div class="mb-10">
                    <h2 data-translate="info_adduser" data-translate-page="admin"
                        class="text-2xl font-semibold text-gray-900 dark:text-white mb-6 pb-3 border-b border-gray-200 dark:border-gray-700">
                        Informasi Dasar
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                        <!-- Username -->
                        <div>
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
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
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
                        <div>
                            <label data-translate="pw_confirm_adduser" data-translate-page="admin"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                required>
                        </div>

                        <!-- Photo Profile -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Photo
                                Profile</label>
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
                </div>

                <!-- Informasi Tambahan -->
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
                            <select name="id_jurusan"
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
                            <select name="id_keahlian"
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
                            <select name="id_angkatan"
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

    <!-- JavaScript -->
    <script>
        let selectedRole = '';

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

            // Toggle additional fields
            const additional = document.getElementById('additionalFields');
            if (role === 'admin') {
                additional.classList.add('hidden');
                document.querySelectorAll('#additionalFields select').forEach(sel => sel.required = false);
            } else {
                additional.classList.remove('hidden');
                document.querySelectorAll('#additionalFields select').forEach(sel => sel.required = true);
            }

            document.getElementById('submitBtn').disabled = false;
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