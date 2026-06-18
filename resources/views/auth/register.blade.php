@extends('auth.layout')

@section('title', 'Register')

@section('content')

    <main class="flex-grow flex items-start justify-center pt-12 pb-12 px-5 sm:px-8">
    <div class="relative max-w-2xl w-full mx-auto justify-center">

        <div class=" mb-8 sm:mb-12">
            <h1
                class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 tracking-tight leading-none rotate-[-2deg] inline-block">
                Daftar di Sini
            </h1>
            <p class="mt-3 text-base sm:text-lg text-gray-600 max-w-xl rotate-[-1deg]">
                Isi data kamu dulu ya.
            </p>
            <div
                class="absolute -top-3 -left-6 sm:-left-10 w-20 sm:w-28 h-1 bg-blue-400 rotate-[-35deg] rounded-full opacity-80">
            </div>
        </div>

        @php
            $attemptsLeft = 3 - (session('registration_attempts', 0));
            $isBlocked = $attemptsLeft <= 0;

            // Cek apakah sudah bisa registrasi lagi (keesokan hari)
            $lastAttemptDate = session('last_attempt_date');
            $canRegisterAgain = false;

            if ($isBlocked && $lastAttemptDate) {
                $today = now()->format('Y-m-d');
                if ($lastAttemptDate !== $today) {
                    // Reset attempts jika sudah berganti hari
                    session(['registration_attempts' => 0, 'last_attempt_date' => $today]);
                    $isBlocked = false;
                    $attemptsLeft = 3;
                }
            }
        @endphp

        <!-- Info Batas Registrasi -->
        @if($attemptsLeft > 0 && $attemptsLeft < 3)
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                <p class="text-yellow-800 text-sm">
                    ⚠️ Anda memiliki <strong>{{ $attemptsLeft }} kali</strong> kesempatan pengajuan tersisa (maksimal 3
                    kali).
                </p>
            </div>
        @endif

        <!-- Pesan Blokir -->
        @if($isBlocked)
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-red-800 text-sm font-semibold">
                    🚫 Anda telah melebihi batas percobaan pendaftaran (3 kali). Silakan coba lagi besok.
                </p>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data"
            class="space-y-7 sm:space-y-8">
            @csrf

            <!-- Foto Profil -->
            <div class="flex flex-col items-center">
                <label class="block text-sm font-medium text-gray-700 mb-3">Foto Profil (opsional)</label>

                <div class="relative group cursor-pointer {{ $isBlocked ? 'opacity-60 cursor-not-allowed' : '' }}">
                    <div
                        class="w-28 h-28 sm:w-32 sm:h-32 md:w-36 md:h-36 rounded-full overflow-hidden border-4 border-white shadow-xl transition-all duration-300 group-hover:shadow-blue-300/50 bg-gray-100 flex items-center justify-center">
                        <img id="profile-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                        <svg id="profile-placeholder" class="w-12 h-12 sm:w-14 sm:h-14 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>

                    @if(!$isBlocked)
                        <div
                            class="absolute inset-0 rounded-full bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span
                                class="text-white text-xs sm:text-sm font-medium px-3 py-1.5 bg-blue-600/80 rounded-full">Pilih
                                Foto</span>
                        </div>
                    @endif

                    <input type="file" name="photo_profile" id="photo_profile" accept="image/*"
                        class="absolute inset-0 opacity-0 cursor-pointer {{ $isBlocked ? 'pointer-events-none' : '' }}"
                        {{ $isBlocked ? 'disabled' : '' }}>
                </div>

                @error('photo_profile')
                    <p class="mt-2 text-xs sm:text-sm text-red-600 text-center">{{ $message }}</p>
                @enderror

                <p class="mt-2 text-xs text-gray-500 text-center">JPG/PNG • Maks. 2MB</p>
            </div>

            @if(!$isBlocked)
                <!-- CropperJS assets -->
                <link rel="stylesheet" href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css">
                <script src="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.js"></script>

                <!-- Cropper Modal -->
                <div id="cropper-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
                    <div class="absolute inset-0 bg-black/60" onclick="closeCropperModal()"></div>
                    <div class="relative flex items-center justify-center min-h-screen p-4">
                        <div id="cropper-modal-content" class="w-full max-w-3xl bg-white dark:bg-gray-800 rounded-xl overflow-hidden transform transition-all scale-95 opacity-0">
                            <div class="p-4">
                                <div class="w-full h-80 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <img id="cropper-image" src="" alt="Cropper" class="max-h-full max-w-full object-contain">
                                </div>
                                <div class="mt-3 flex items-center gap-3">
                                    <input id="cropper-zoom-range" type="range" min="0" max="3" step="0.01" value="1" class="w-full">
                                    <button type="button" onclick="cancelCropper()" class="px-4 py-2 bg-gray-100 rounded">Batal</button>
                                    <button type="button" onclick="confirmCrop()" class="px-4 py-2 bg-emerald-600 text-white rounded">Pilih & Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    let cropperInstance = null;
                    let cropperObjectUrl = null;

                    function openCropperModal(file) {
                        const modal = document.getElementById('cropper-modal');
                        const img = document.getElementById('cropper-image');
                        if (cropperObjectUrl) URL.revokeObjectURL(cropperObjectUrl);
                        cropperObjectUrl = URL.createObjectURL(file);
                        img.src = cropperObjectUrl;
                        modal.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';

                        // wait image load before creating cropper
                        img.onload = function () {
                            if (cropperInstance) cropperInstance.destroy();
                            cropperInstance = new Cropper(img, {
                                aspectRatio: 1,
                                viewMode: 1,
                                autoCropArea: 1,
                                responsive: true,
                                background: false,
                            });
                            document.getElementById('cropper-modal-content').classList.add('scale-100', 'opacity-100');
                        };

                        // zoom control
                        const zoomRange = document.getElementById('cropper-zoom-range');
                        zoomRange.value = 1;
                        zoomRange.oninput = function (e) {
                            if (cropperInstance) {
                                const val = parseFloat(e.target.value);
                                cropperInstance.zoomTo(val);
                            }
                        };
                    }

                    function closeCropperModal() {
                        const modal = document.getElementById('cropper-modal');
                        modal.classList.add('hidden');
                        document.body.style.overflow = '';
                        if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }
                        if (cropperObjectUrl) { URL.revokeObjectURL(cropperObjectUrl); cropperObjectUrl = null; }
                    }

                    function cancelCropper() { closeCropperModal(); }

                    function confirmCrop() {
                        if (!cropperInstance) return closeCropperModal();
                        cropperInstance.getCroppedCanvas({ width: 800, height: 800, imageSmoothingQuality: 'high' }).toBlob(function (blob) {
                            if (!blob) return alert('Gagal memproses gambar');

                            // Update preview
                            const preview = document.getElementById('profile-preview');
                            const placeholder = document.getElementById('profile-placeholder');
                            const url = URL.createObjectURL(blob);
                            preview.src = url;
                            preview.classList.remove('hidden');
                            placeholder.classList.add('hidden');

                            // Replace file input's files with the cropped blob so server receives cropped image
                            const croppedFile = new File([blob], 'photo_profile.jpg', { type: blob.type });
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(croppedFile);
                            const input = document.getElementById('photo_profile');
                            input.files = dataTransfer.files;

                            closeCropperModal();
                        }, 'image/jpeg', 0.9);
                    }

                    // Bind file input to open cropper
                    document.getElementById('photo_profile').addEventListener('change', function (e) {
                        const file = e.target.files[0];
                        if (file && file.type.startsWith('image/')) {
                            openCropperModal(file);
                        } else {
                            const preview = document.getElementById('profile-preview');
                            const placeholder = document.getElementById('profile-placeholder');
                            preview.classList.add('hidden');
                            placeholder.classList.remove('hidden');
                            if (file) alert('Hanya gambar yang diperbolehkan!');
                        }
                    });
                </script>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-7 lg:gap-12">
                <div class="space-y-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">NIM </label>
                        <input type="text" name="nim" required value="{{ old('nim') }}"
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition @error('nim') border-red-400 @enderror {{ $isBlocked ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                            placeholder="NIM" {{ $isBlocked ? 'disabled' : '' }}>
                        @error('nim') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="nama_mahasiswa" required value="{{ old('nama_mahasiswa') }}"
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition @error('nama_mahasiswa') border-red-400 @enderror {{ $isBlocked ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                            placeholder="Nama lengkapmu..." {{ $isBlocked ? 'disabled' : '' }}>
                        @error('nama_mahasiswa') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                     <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" required value="{{ old('tanggal_lahir') }}"
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition @error('tanggal_lahir') border-red-400 @enderror {{ $isBlocked ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                            placeholder="Tanggal Lahir" {{ $isBlocked ? 'disabled' : '' }}>
                        @error('tanggal_lahir') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
                        <input type="text" name="username" required value="{{ old('username') }}"
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition @error('username') border-red-400 @enderror {{ $isBlocked ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                            placeholder="Username untuk login" {{ $isBlocked ? 'disabled' : '' }}>
                        @error('username') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="password-reg" required
                                    minlength="8"
                                    pattern="^(?=.*[A-Z])(?!.*\s).{8,}$"
                                    title="Password harus minimal 8 karakter, mengandung minimal 1 huruf besar, dan tidak boleh ada spasi"
                                    class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition @error('password') border-red-400 @enderror {{ $isBlocked ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                    {{ $isBlocked ? 'disabled' : '' }}>
                                @if(!$isBlocked)
                                    <button type="button"
                                        class="toggle-password-btn absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-500 hover:text-gray-700 transition"
                                        onclick="togglePasswordVisibility('password-reg')">
                                        <!-- Eye Icon (Show) -->
                                        <svg class="eye-icon-show w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <!-- Eye Off Icon (Hide) -->
                                        <svg class="eye-icon-hide w-5 h-5 hidden" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                            </path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            <div class="mt-2 p-3 bg-blue-50 rounded-lg border border-blue-100">
                                <p class="text-xs font-semibold text-blue-900 mb-2">Syarat Password:</p>
                                <ul class="text-xs text-blue-800 space-y-1">
                                    <li class="flex items-center gap-2">
                                        <span class="w-4 h-4 rounded-full bg-blue-200 flex items-center justify-center text-xs">✓</span>
                                        Minimal 8 karakter
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="w-4 h-4 rounded-full bg-blue-200 flex items-center justify-center text-xs">✓</span>
                                        Harus mengandung minimal 1 huruf besar (A-Z)
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="w-4 h-4 rounded-full bg-blue-200 flex items-center justify-center text-xs">✓</span>
                                        Tidak boleh ada spasi
                                    </li>
                                </ul>
                            </div>
                            @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="relative w-full">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password-confirm-reg" required
                                    class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition {{ $isBlocked ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                    {{ $isBlocked ? 'disabled' : '' }}>
                                @if(!$isBlocked)
                                    <button type="button"
                                        class="toggle-password-btn absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-500 hover:text-gray-700 transition"
                                        onclick="togglePasswordVisibility('password-confirm-reg')">
                                        <!-- Eye Icon (Show) -->
                                        <svg class="eye-icon-show w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <!-- Eye Off Icon (Hide) -->
                                        <svg class="eye-icon-hide w-5 h-5 hidden" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                            </path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 lg:mt-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email (boleh kosong)</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition @error('email') border-red-400 @enderror {{ $isBlocked ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                            placeholder="email@kampus.ac.id" {{ $isBlocked ? 'disabled' : '' }}>
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jurusan</label>
                        <select name="id_jurusan" required
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition appearance-none @error('id_jurusan') border-red-400 @enderror {{ $isBlocked ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                            {{ $isBlocked ? 'disabled' : '' }}>
                            <option value="">Pilih jurusan</option>
                            @foreach($jurusans as $j)
                                <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                                    {{ $j->nama_jurusan ?? $j->id_jurusan }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_jurusan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Angkatan</label>
                        <select name="id_angkatan" required
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition appearance-none @error('id_angkatan') border-red-400 @enderror {{ $isBlocked ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                            {{ $isBlocked ? 'disabled' : '' }}>
                            <option value="">Pilih Angkatan</option>
                            @foreach($angkatans as $k)
                                <option value="{{ $k->id }}" {{ old('id_angkatan') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_angkatan ?? $k->id }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_angkatan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Keahlian Utama</label>
                        <select name="id_keahlian" {{ $isBlocked ? 'disabled' : '' }}
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition appearance-none @error('id_keahlian') border-red-400 @enderror {{ $isBlocked ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                            <option value="">Pilih keahlian</option>
                            @foreach($keahlians as $k)
                                <option value="{{ $k->id_keahlian }}" {{ old('id_keahlian') == $k->id_keahlian ? 'selected' : '' }}>
                                    {{ $k->nama_keahlian ?? $k->id_keahlian }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_keahlian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Atau masukkan skill sendiri</label>
                            <input type="text" name="custom_keahlian" value="{{ old('custom_keahlian') }}"
                                class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition @error('custom_keahlian') border-red-400 @enderror {{ $isBlocked ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                placeholder="Skill baru / khusus" {{ $isBlocked ? 'disabled' : '' }}>
                            @error('custom_keahlian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex justify-center lg:justify-end">
                <button type="submit"
                    class="w-full sm:w-auto px-6 sm:px-10 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-full shadow-lg transition-all duration-300 {{ $isBlocked ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-xl hover:scale-105 active:scale-95' }}"
                    {{ $isBlocked ? 'disabled' : '' }}>
                    Kirim Data →
                </button>
            </div>

            <p class="text-center mt-5 text-gray-600 text-sm sm:text-base">
                Sudah punya akun?
                <a href="{{ route('login') }}"
                    class="text-blue-600 hover:text-blue-800 font-medium underline-offset-4 hover:underline">
                    Masuk aja
                </a>
            </p>

        </form>
    </div>
    </main>

    @if(!$isBlocked)
        <script>
            function togglePasswordVisibility(inputId) {
                const input = document.getElementById(inputId);
                const button = input.parentElement.querySelector('.toggle-password-btn');
                const eyeShowIcon = button.querySelector('.eye-icon-show');
                const eyeHideIcon = button.querySelector('.eye-icon-hide');

                if (input.type === 'password') {
                    input.type = 'text';
                    eyeShowIcon.classList.add('hidden');
                    eyeHideIcon.classList.remove('hidden');
                } else {
                    input.type = 'password';
                    eyeShowIcon.classList.remove('hidden');
                    eyeHideIcon.classList.add('hidden');
                }
            }
        </script>
    @endif

    <script>
        // Sweet Alert for success/error messages
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'OK'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: '{!! implode("<br>", $errors->all()) !!}',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'OK'
            });
        @endif

        @if($isBlocked)
            Swal.fire({
                icon: 'warning',
                title: 'Pendaftaran Dibatasi',
                text: 'Anda telah melebihi batas percobaan pendaftaran. Silakan coba lagi besok.',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Mengerti'
            });
        @endif
    </script>
@endsection