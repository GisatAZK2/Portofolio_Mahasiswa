@extends('Layout.Layout')

@section('title', 'Tambah Mahasiswa Baru')

@section('content')
    <div class="container mx-auto px-4 py-8 dark:bg-gray-900 min-h-screen">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white" data-translate-page="dosen_add_mhs"
                data-translate="add_mhs">Tambah Mahasiswa Baru</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2" data-translate-page="dosen_add_mhs"
                data-translate="add_mhs_desc">Form tambah mahasiswa untuk dosen</p>
        </div>

        <!-- Form Container -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 md:p-8 mx-auto border border-gray-200 dark:border-gray-700">
            <form method="POST" action="{{ route('dosen.users.StoreUser') }}" enctype="multipart/form-data">
                @csrf

                <!-- Informasi Dasar -->
                <div class="mb-8">
                    <h2 data-translate="info_mhs" data-translate-page="dosen_add_mhs"
                        class="text-xl font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        Informasi Mahasiswa
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span data-translate="nm_lgkp" data-translate-page="dosen_add_mhs">Nama Lengkap</span> <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_mahasiswa" value="{{ old('nama_mahasiswa') }}" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg 
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors
                                          text-gray-900 dark:text-white
                                          @error('nama_mahasiswa') border-red-500 @enderror"
                                placeholder="Masukkan nama lengkap" required>
                            @error('nama_mahasiswa')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span data-translate="usn" data-translate-page="dosen_add_mhs">Username</span> <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="text" name="username" value="{{ old('username') }}" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg 
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors
                                          text-gray-900 dark:text-white
                                          @error('username') border-red-500 @enderror" placeholder="Contoh: john_doe"
                                required>
                            @error('username')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hanya huruf, angka, dan underscore (_)
                            </p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span data-translate="email" data-translate-page="dosen_add_mhs">Email</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg 
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors
                                          text-gray-900 dark:text-white
                                          @error('email') border-red-500 @enderror" placeholder="contoh@email.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span data-translate="klmn" data-translate-page="dosen_add_mhs">Jenis Kelamin</span>
                            </label>
                            <select name="jenis_kelamin" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg 
                                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors
                                           text-gray-900 dark:text-white
                                           @error('jenis_kelamin') border-red-500 @enderror">
                                <option data-translate="klmn_choose" data-translate-page="dosen_add_mhs" value="">Pilih
                                    Jenis Kelamin</option>
                                <option data-translate="lk" data-translate-page="dosen_add_mhs" value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option data-translate="pr" data-translate-page="dosen_add_mhs" value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                <option data-translate="secret" data-translate-page="dosen_add_mhs"
                                    value="Tidak ingin memberi tahu" {{ old('jenis_kelamin') == 'Tidak ingin memberi tahu' ? 'selected' : '' }}>Tidak ingin memberi tahu</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span data-translate="pw" data-translate-page="dosen_add_mhs">Password</span> <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg 
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors
                                          text-gray-900 dark:text-white
                                          @error('password') border-red-500 @enderror" placeholder="Minimal 8 karakter"
                                required>
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span data-translate="pw_conf" data-translate-page="dosen_add_mhs">Konfirmasi
                                    Password</span> <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg 
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors
                                          text-gray-900 dark:text-white" placeholder="Ulangi password" required>
                        </div>

                        <!-- Photo Profile -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span data-translate="pfp" data-translate-page="dosen_add_mhs">Foto Profil</span>
                            </label>
                            <div class="flex items-center space-x-6">
                                <div class="flex-shrink-0">
                                    <img id="preview" src="https://via.placeholder.com/150"
                                        class="w-20 h-20 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="photo_profile" id="photo_profile"
                                        accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-gray-500 dark:text-gray-400 
                                                  file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 
                                                  file:text-sm file:font-semibold 
                                                  file:bg-blue-50 file:text-blue-700 
                                                  dark:file:bg-blue-900 dark:file:text-blue-300
                                                  hover:file:bg-blue-100 dark:hover:file:bg-blue-800">
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Format: JPEG, PNG, JPG. Maks:
                                        2MB</p>
                                </div>
                            </div>
                            @error('photo_profile')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('dosen.users.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                              text-gray-700 dark:text-gray-300 
                              hover:bg-gray-50 dark:hover:bg-gray-700 
                              focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                        <span data-translate="cancel" data-translate-page="dosen_add_mhs">Batal</span>
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 
                                   text-white rounded-lg 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 
                                   transition-colors">
                        <span data-translate="add" data-translate-page="dosen_add_mhs">Tambah Mahasiswa</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript untuk Preview Image -->
    <script>
        document.getElementById('photo_profile').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.dosen_add_user");
         });
     </script>

@endsection