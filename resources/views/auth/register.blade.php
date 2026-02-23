<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Mahasiswa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">

<div class="w-full max-w-2xl bg-white shadow-lg rounded-lg p-8">
    
    <h2 class="text-2xl font-bold mb-6 text-center">Registrasi Mahasiswa</h2>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- Nama -->
        <div class="mb-4">
            <label class="block mb-1 font-medium">Nama Lengkap</label>
            <input type="text" 
                   name="nama_mahasiswa"
                   value="{{ old('nama_mahasiswa') }}"
                   class="w-full border rounded px-3 py-2 @error('nama_mahasiswa') border-red-500 @enderror"
                   required>
            @error('nama_mahasiswa')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label class="block mb-1 font-medium">Email (Opsional)</label>
            <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Username -->
        <div class="mb-4">
            <label class="block mb-1 font-medium">Username</label>
            <input type="text"
                   name="username"
                   value="{{ old('username') }}"
                   class="w-full border rounded px-3 py-2 @error('username') border-red-500 @enderror"
                   required>
            @error('username')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label class="block mb-1 font-medium">Password</label>
            <input type="password"
                   name="password"
                   class="w-full border rounded px-3 py-2 @error('password') border-red-500 @enderror"
                   required>
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Konfirmasi Password -->
        <div class="mb-4">
            <label class="block mb-1 font-medium">Konfirmasi Password</label>
            <input type="password"
                   name="password_confirmation"
                   class="w-full border rounded px-3 py-2"
                   required>
        </div>

        <!-- Jurusan -->
        <div class="mb-4">
            <label class="block mb-1 font-medium">Jurusan</label>
            <select name="id_jurusan"
                    class="w-full border rounded px-3 py-2 @error('id_jurusan') border-red-500 @enderror"
                    required>
                <option value="">-- Pilih Jurusan --</option>
                @foreach($jurusans as $j)
                    <option value="{{ $j->id_jurusan }}"
                        {{ old('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                        {{ $j->nama_jurusan ?? $j->id_jurusan }}
                    </option>
                @endforeach
            </select>
            @error('id_jurusan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Keahlian -->
        <div class="mb-4">
            <label class="block mb-1 font-medium">Keahlian Utama</label>
            <select name="id_keahlian"
                    class="w-full border rounded px-3 py-2 @error('id_keahlian') border-red-500 @enderror"
                    required>
                <option value="">-- Pilih Keahlian --</option>
                @foreach($keahlians as $k)
                    <option value="{{ $k->id_keahlian }}"
                        {{ old('id_keahlian') == $k->id_keahlian ? 'selected' : '' }}>
                        {{ $k->nama_keahlian ?? $k->id_keahlian }}
                    </option>
                @endforeach
            </select>
            @error('id_keahlian')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Foto -->
        <div class="mb-6">
            <label class="block mb-1 font-medium">Foto Profil (Opsional)</label>
            <input type="file"
                   name="photo_profile"
                   accept="image/*"
                   class="w-full border rounded px-3 py-2 @error('photo_profile') border-red-500 @enderror">
            @error('photo_profile')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            Daftar
        </button>

        <p class="mt-4 text-center">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
                Login di sini
            </a>
        </p>

    </form>
</div>

</body>
</html>