@extends('Layout.Layout')

@section('title', 'Edit User')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit User</h1>
                <a href="{{ route('admin.users.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-arrow-left mr-2" data-translate="back" data-translate-page="admin"></i>
                </a>
            </div>

            <!-- Form Edit User -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <form action="{{ route('admin.users.edit', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <!-- Role (Readonly) -->
                    <div class="mb-6">
                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="role" value="{{ ucfirst($user->role) }}" readonly
                            class="w-full px-4 py-2 border rounded-lg bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 cursor-not-allowed">
                        <input type="hidden" name="role" value="{{ $user->role }}">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- NIM (Hanya untuk Mahasiswa) -->
                        <div id="nimField" class="col-span-2 md:col-span-1" style="{{ $user->role != 'mahasiswa' ? 'display: none;' : '' }}">
                            <label for="nim" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                NIM <span class="text-red-500" id="nimRequired">*</span>
                            </label>
                            <input type="text" name="nim" id="nim" value="{{ old('nim', $user->nim) }}"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('nim') border-red-500 @enderror"
                                placeholder="Masukkan NIM" {{ $user->role == 'mahasiswa' ? '' : 'disabled' }}>
                            @error('nim')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

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

                        <!-- Tanggal Lahir (Untuk Mahasiswa dan Dosen) -->
                        <div id="tanggalLahirField" class="col-span-2 md:col-span-1" style="{{ !in_array($user->role, ['mahasiswa', 'dosen']) ? 'display: none;' : '' }}">
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Lahir <span class="text-red-500" id="tanggalLahirRequired">
                                    {{ $user->role == 'admin' ? '' : '*' }}
                                </span>
                            </label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" 
                                value="{{ old('tanggal_lahir', $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : '') }}"
                                max="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('tanggal_lahir') border-red-500 @enderror"
                                {{ in_array($user->role, ['mahasiswa', 'dosen']) ? 'required' : '' }}>
                            @error('tanggal_lahir')
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

                        <!-- Jurusan (Hanya untuk Mahasiswa dan Dosen) -->
                        <div id="jurusanField" class="col-span-2 md:col-span-1" style="{{ !in_array($user->role, ['mahasiswa', 'dosen']) ? 'display: none;' : '' }}">
                            <label for="id_jurusan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jurusan <span class="text-red-500" id="jurusanRequired">*</span>
                            </label>
                            <select name="id_jurusan" id="id_jurusan"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('id_jurusan') border-red-500 @enderror"
                                {{ in_array($user->role, ['mahasiswa', 'dosen']) ? 'required' : 'disabled' }}>
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

                        <!-- Keahlian (Hanya untuk Mahasiswa dan Dosen) -->
                        <div id="keahlianField" class="col-span-2 md:col-span-1" style="{{ !in_array($user->role, ['mahasiswa', 'dosen']) ? 'display: none;' : '' }}">
                            <label for="id_keahlian"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Bidang Keahlian <span class="text-red-500" id="keahlianRequired">*</span>
                            </label>
                            <select name="id_keahlian" id="id_keahlian"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('id_keahlian') border-red-500 @enderror"
                                {{ in_array($user->role, ['mahasiswa', 'dosen']) ? 'required' : 'disabled' }}>
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

                        <!-- Angkatan (Hanya untuk Mahasiswa dan Dosen) -->
                        <div id="angkatanField" class="col-span-2 md:col-span-1" style="{{ !in_array($user->role, ['mahasiswa', 'dosen']) ? 'display: none;' : '' }}">
                            <label for="id_angkatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Angkatan <span class="text-red-500" id="angkatanRequired">*</span>
                            </label>
                            <select name="id_angkatan" id="id_angkatan"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('id_angkatan') border-red-500 @enderror"
                                {{ in_array($user->role, ['mahasiswa', 'dosen']) ? 'required' : 'disabled' }}>
                                <option value="">Pilih Angkatan</option>
                                @foreach($angkatans as $angkatan)
                                    <option value="{{ $angkatan->id }}" {{ old('id_angkatan', $user->id_angkatan) == $angkatan->id ? 'selected' : '' }}>
                                        {{ $angkatan->nama_angkatan }}
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
                                <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>Non Aktif</option>
                            </select>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Baru (opsional) -->
                        <div class="col-span-2 md:col-span-1">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Password Baru
                            </label>
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('password') border-red-500 @enderror"
                                placeholder="Masukkan password baru">
                            <div class="mt-2 p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-100 dark:border-blue-800">
                                <p class="text-xs font-semibold text-blue-900 dark:text-blue-100 mb-1">Syarat Password:</p>
                                <ul class="text-xs text-blue-800 dark:text-blue-200 space-y-0.5">
                                    <li class="flex items-center gap-2">✓ Minimal 8 karakter</li>
                                    <li class="flex items-center gap-2">✓ Harus ada huruf besar (A-Z)</li>
                                    <li class="flex items-center gap-2">✓ Tidak boleh ada spasi</li>
                                </ul>
                            </div>
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
                                Photo Profile
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
                                Format yang didukung: JPG, JPEG, PNG. Maksimal 2MB.
                            </p>
                            @error('photo_profile')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('admin.users.index') }}"
                            class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
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
            // Preview photo profile
            const photoInput = document.getElementById('photo_profile');
            const photoPreview = document.getElementById('photoPreview');
            const previewImage = document.getElementById('previewImage');

            if (photoInput) {
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
            }

            

            // Validasi tanggal lahir tidak boleh melebihi hari ini
           const tanggalLahirInput = document.getElementById('tanggal_lahir');

if (tanggalLahirInput) {
    tanggalLahirInput.addEventListener('change', function () {
        const selectedDate = this.value; // format YYYY-MM-DD

        const today = new Date();
        const todayString = today.toISOString().split('T')[0];

        if (selectedDate > todayString) {
            alert('Tanggal lahir tidak boleh melebihi tanggal hari ini!');
            this.value = '';
        }
    });
}
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof showPageInfo === 'function') {
                showPageInfo("popup.edit_user");
            }
        });
    </script>
@endpush