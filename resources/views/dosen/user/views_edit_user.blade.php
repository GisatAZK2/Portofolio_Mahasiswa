@extends('Layout.Layout')

@section('title', 'Edit User')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit User</h1>
                <a href="{{ route('dosen.users.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <!-- Form Edit User -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <form action="{{ route('dosen.users.edit', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Mahasiswa -->
                        <div class="col-span-2 md:col-span-1">
                            <label for="nama_mahasiswa"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_mahasiswa" id="nama_mahasiswa"
                                value="{{ old('nama_mahasiswa', $user->nama_mahasiswa) }}"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('nama_mahasiswa') border-red-500 @enderror"
                                placeholder="Masukkan nama lengkap" required>
                            @error('nama_mahasiswa')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div class="col-span-2 md:col-span-1">
                            <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('username') border-red-500 @enderror"
                                placeholder="Masukkan username" required>
                            @error('username')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-span-2 md:col-span-1">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('email') border-red-500 @enderror"
                                placeholder="Masukkan email">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="col-span-2 md:col-span-1">
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select name="role" id="role"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('role') border-red-500 @enderror"
                                required>
                                <option value="">Pilih Role</option>
                                <option value="mahasiswa" {{ old('role', $user->role) == 'mahasiswa' ? 'selected' : '' }}>
                                    Mahasiswa</option>
                                <option value="dosen" {{ old('role', $user->role) == 'dosen' ? 'selected' : '' }}>Dosen
                                </option>
                                <option value="dosen" {{ old('role', $user->role) == 'dosen' ? 'selected' : '' }}>dosen
                                </option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jurusan (ditampilkan jika role = mahasiswa/dosen) -->
                        <div class="col-span-2 md:col-span-1 role-dependent" data-roles="mahasiswa,dosen"
                            style="{{ !in_array(old('role', $user->role), ['mahasiswa', 'dosen']) ? 'display: none;' : '' }}">
                            <label for="id_jurusan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jurusan <span class="text-red-500">*</span>
                            </label>
                            <select name="id_jurusan" id="id_jurusan"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('id_jurusan') border-red-500 @enderror">
                                <option value="">Pilih Jurusan</option>
                                @foreach($jurusans as $jurusan)
                                    <option value="{{ $jurusan->id_jurusan }}" {{ old('id_jurusan', $user->id_jurusan) == $jurusan->id_jurusan ? 'selected' : '' }}>
                                        {{ $jurusan->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_jurusan')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keahlian (ditampilkan jika role = mahasiswa/dosen) -->
                        <div class="col-span-2 md:col-span-1 role-dependent" data-roles="mahasiswa,dosen"
                            style="{{ !in_array(old('role', $user->role), ['mahasiswa', 'dosen']) ? 'display: none;' : '' }}">
                            <label for="id_keahlian"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Keahlian <span class="text-red-500">*</span>
                            </label>
                            <select name="id_keahlian" id="id_keahlian"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('id_keahlian') border-red-500 @enderror">
                                <option value="">Pilih Keahlian</option>
                                @foreach($keahlians as $keahlian)
                                    <option value="{{ $keahlian->id_keahlian }}" {{ old('id_keahlian', $user->id_keahlian) == $keahlian->id_keahlian ? 'selected' : '' }}>
                                        {{ $keahlian->nama_keahlian }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_keahlian')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Angkatan (ditampilkan jika role = mahasiswa/dosen) -->
                        <div class="col-span-2 md:col-span-1 role-dependent" data-roles="mahasiswa,dosen"
                            style="{{ !in_array(old('role', $user->role), ['mahasiswa', 'dosen']) ? 'display: none;' : '' }}">
                            <label for="id_angkatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Angkatan <span class="text-red-500">*</span>
                            </label>
                            <select name="id_angkatan" id="id_angkatan"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('id_angkatan') border-red-500 @enderror">
                                <option value="">Pilih Angkatan</option>
                                @foreach($angkatans as $angkatan)
                                    <option value="{{ $angkatan->id }}" {{ old('id_angkatan', $user->id_angkatan) == $angkatan->id ? 'selected' : '' }}>
                                        {{ $angkatan->tahun_angkatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_angkatan')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Pengajuan -->
                        <div class="col-span-2 md:col-span-1">
                            <label for="status_pengajuan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status Pengajuan <span class="text-red-500">*</span>
                            </label>
                            <select name="status_pengajuan" id="status_pengajuan"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('status_pengajuan') border-red-500 @enderror"
                                required>
                                <option value="Di Terima" {{ old('status_pengajuan', $user->status_pengajuan) == 'Di Terima' ? 'selected' : '' }}>Di Terima</option>
                                <option value="Di Tolak" {{ old('status_pengajuan', $user->status_pengajuan) == 'Di Tolak' ? 'selected' : '' }}>Di Tolak</option>
                            </select>
                            @error('status_pengajuan')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Aktif -->
                        <div class="col-span-2 md:col-span-1">
                            <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status Akun <span class="text-red-500">*</span>
                            </label>
                            <select name="is_active" id="is_active"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('is_active') border-red-500 @enderror"
                                required>
                                <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="0" {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>Non Aktif
                                </option>
                            </select>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Baru (opsional) -->
                        <div class="col-span-2 md:col-span-1">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Password Baru (Kosongkan jika tidak diubah)
                            </label>
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('password') border-red-500 @enderror"
                                placeholder="Masukkan password baru">
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="col-span-2 md:col-span-1">
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Konfirmasi password baru">
                        </div>

                        <!-- Photo Profile -->
                        <div class="col-span-2">
                            <label for="photo_profile"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Foto Profil
                            </label>

                            <!-- Preview Foto Lama -->
                            @if($user->photo_profile)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Foto Saat Ini:</p>
                                    <img src="{{ Storage::url($user->photo_profile) }}" alt="Profile"
                                        class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300">
                                </div>
                            @endif

                            <!-- Preview Foto Baru -->
                            <div id="photoPreview" class="mb-4 hidden">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Foto Baru:</p>
                                <img id="previewImage" src="#" alt="Preview"
                                    class="w-32 h-32 object-cover rounded-lg border-2 border-blue-500">
                            </div>

                            <input type="file" name="photo_profile" id="photo_profile"
                                accept="image/jpeg,image/png,image/jpg"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('photo_profile') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Format: JPG, JPEG, PNG. Maksimal 2MB.
                            </p>
                            @error('photo_profile')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <button type="reset"
                            class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                            Reset
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const roleDependentFields = document.querySelectorAll('.role-dependent');

            function toggleRoleDependentFields() {
                const selectedRole = roleSelect.value;

                roleDependentFields.forEach(field => {
                    const allowedRoles = field.dataset.roles.split(',');

                    if (allowedRoles.includes(selectedRole)) {
                        field.style.display = 'block';
                        // Enable dan required semua input di dalam field
                        field.querySelectorAll('input, select').forEach(input => {
                            input.disabled = false;
                            if (input.tagName === 'SELECT' || input.type === 'text' || input.type === 'number') {
                                input.required = true;
                            }
                        });
                    } else {
                        field.style.display = 'none';
                        // Disable dan hapus required semua input di dalam field
                        field.querySelectorAll('input, select').forEach(input => {
                            input.disabled = true;
                            input.required = false;
                        });
                    }
                });
            }

            // Initial toggle
            toggleRoleDependentFields();

            // Toggle on change
            roleSelect.addEventListener('change', toggleRoleDependentFields);

            // Preview photo profile
            const photoInput = document.getElementById('photo_profile');
            const photoPreview = document.getElementById('photoPreview');
            const previewImage = document.getElementById('previewImage');

            photoInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                        photoPreview.classList.remove('hidden');
                    }

                    reader.readAsDataURL(this.files[0]);
                } else {
                    photoPreview.classList.add('hidden');
                    previewImage.src = '#';
                }
            });

            // Reset form confirmation
            const resetBtn = document.querySelector('button[type="reset"]');
            resetBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin mereset semua perubahan?')) {
                    document.querySelector('form').reset();
                    photoPreview.classList.add('hidden');
                    previewImage.src = '#';
                    toggleRoleDependentFields();
                }
            });
        });
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.dosen_edit_user");
         });
     </script>
@endpush