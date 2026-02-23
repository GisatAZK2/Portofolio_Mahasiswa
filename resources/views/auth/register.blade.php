<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Pendaftaran Mahasiswa Baru</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f8f5f2] min-h-screen flex items-start justify-center pt-10 pb-12 px-5 sm:px-8">

    <div class="fixed inset-0 pointer-events-none opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\"0 0 200 200\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cfilter id=\"n\"%3E%3CfeTurbulence type=\"fractalNoise\" baseFrequency=\"0.9\" numOctaves=\"3\"/%3E%3C/filter%3E%3Ccircle cx=\"100\" cy=\"100\" r=\"200\" filter=\"url(%23n)\"/%3E%3C/svg%3E');"></div>

    <div class="relative w-full max-w-4xl">

        <div class="relative mb-8 sm:mb-12">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 tracking-tight leading-none rotate-[-2deg] inline-block">
                Daftar di Sini
            </h1>
            <p class="mt-3 text-base sm:text-lg text-gray-600 max-w-xl rotate-[-1deg]">
                Isi data kamu dulu ya.
            </p>
            <div class="absolute -top-3 -left-6 sm:-left-10 w-20 sm:w-28 h-1 bg-blue-400 rotate-[-35deg] rounded-full opacity-80"></div>
        </div>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-7 sm:space-y-8">

            @csrf

            <!-- Foto Profil -->
            <div class="flex flex-col items-center">
                <label class="block text-sm font-medium text-gray-700 mb-3">Foto Profil (opsional)</label>
                
                <div class="relative group cursor-pointer">
                    <div class="w-28 h-28 sm:w-32 sm:h-32 md:w-36 md:h-36 rounded-full overflow-hidden border-4 border-white shadow-xl transition-all duration-300 group-hover:shadow-blue-300/50 bg-gray-100 flex items-center justify-center">
                        <img id="profile-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                        <svg id="profile-placeholder" class="w-12 h-12 sm:w-14 sm:h-14 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>

                    <div class="absolute inset-0 rounded-full bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <span class="text-white text-xs sm:text-sm font-medium px-3 py-1.5 bg-blue-600/80 rounded-full">Pilih Foto</span>
                    </div>

                    <input type="file" name="photo_profile" id="photo_profile" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                </div>

                @error('photo_profile')
                    <p class="mt-2 text-xs sm:text-sm text-red-600 text-center">{{ $message }}</p>
                @enderror

                <p class="mt-2 text-xs text-gray-500 text-center">JPG/PNG • Maks. 2MB</p>
            </div>

            <script>
                document.getElementById('photo_profile').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    const preview = document.getElementById('profile-preview');
                    const placeholder = document.getElementById('profile-placeholder');
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            preview.src = ev.target.result;
                            preview.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        preview.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                        if (file) alert('Hanya gambar yang diperbolehkan!');
                    }
                });
            </script>

            <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-7 lg:gap-12">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="nama_mahasiswa" required value="{{ old('nama_mahasiswa') }}"
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition @error('nama_mahasiswa') border-red-400 @enderror"
                            placeholder="Nama lengkapmu...">
                        @error('nama_mahasiswa') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
                        <input type="text" name="username" required value="{{ old('username') }}"
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition @error('username') border-red-400 @enderror"
                            placeholder="Username untuk login">
                        @error('username') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition @error('password') border-red-400 @enderror">
                            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition">
                        </div>
                    </div>
                </div>

                <div class="space-y-6 lg:mt-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email (boleh kosong)</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition @error('email') border-red-400 @enderror"
                            placeholder="email@kampus.ac.id">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jurusan</label>
                        <select name="id_jurusan" required
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition appearance-none @error('id_jurusan') border-red-400 @enderror">
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
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Keahlian Utama</label>
                        <select name="id_keahlian" required
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition appearance-none @error('id_keahlian') border-red-400 @enderror">
                            <option value="">Pilih keahlian</option>
                            @foreach($keahlians as $k)
                                <option value="{{ $k->id_keahlian }}" {{ old('id_keahlian') == $k->id_keahlian ? 'selected' : '' }}>
                                    {{ $k->nama_keahlian ?? $k->id_keahlian }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_keahlian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-10 flex justify-center lg:justify-end">
                <button type="submit" class="px-10 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300">
                    Kirim Data →
                </button>
            </div>

            <p class="text-center mt-5 text-gray-600 text-sm sm:text-base">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium underline-offset-4 hover:underline">
                    Masuk aja
                </a>
            </p>

        </form>
    </div>

    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showSuccessAlert('{{ session('success') }}');
        });
    </script>
    @endif

</body>
</html>