@extends('Layout.Layout')

@section('title', 'Tambah User Baru')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Tambah User Baru</h1>
        <p class="text-gray-600 mt-2">Pilih role user yang akan ditambahkan</p>
    </div>

    <!-- Card Selection -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card Admin -->
        <div class="cursor-pointer" onclick="selectRole('admin')">
            <div id="card-admin" class="bg-white rounded-xl shadow-lg p-6 border-2 transition-all duration-300 hover:shadow-xl hover:scale-105 border-gray-200">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Admin</h3>
                    <p class="text-gray-600 text-sm">Hak akses penuh untuk mengelola sistem</p>
                    <div class="mt-4 bg-red-50 text-red-700 px-3 py-1 rounded-full text-xs font-medium">
                        Form Sederhana
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Mahasiswa -->
        <div class="cursor-pointer" onclick="selectRole('mahasiswa')">
            <div id="card-mahasiswa" class="bg-white rounded-xl shadow-lg p-6 border-2 transition-all duration-300 hover:shadow-xl hover:scale-105 border-gray-200">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Mahasiswa</h3>
                    <p class="text-gray-600 text-sm">Akses untuk mengikuti kegiatan akademik</p>
                    <div class="mt-4 bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                        Form Lengkap
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Dosen -->
        <div class="cursor-pointer" onclick="selectRole('dosen')">
            <div id="card-dosen" class="bg-white rounded-xl shadow-lg p-6 border-2 transition-all duration-300 hover:shadow-xl hover:scale-105 border-gray-200">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Dosen</h3>
                    <p class="text-gray-600 text-sm">Akses untuk mengelola pembelajaran</p>
                    <div class="mt-4 bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                        Form Lengkap
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
        <form method="POST" action="{{ route('admin.users.StoreUser') }}" enctype="multipart/form-data" id="userForm">
            @csrf
            
            <!-- Hidden Role Input -->
            <input type="hidden" name="role" id="selectedRole" value="">

            <!-- Basic Information (Semua Role) -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Informasi Dasar</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nama_mahasiswa" 
                               value="{{ old('nama_mahasiswa') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nama_mahasiswa') border-red-500 @enderror"
                               required>
                        @error('nama_mahasiswa')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="username" 
                               value="{{ old('username') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('username') border-red-500 @enderror"
                               required>
                        @error('username')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Hanya huruf, angka, dan underscore (_)</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                               required>
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               required>
                    </div>

                    <!-- Photo Profile -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Photo Profile
                        </label>
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img id="preview" src="https://via.placeholder.com/150" class="w-16 h-16 rounded-full object-cover border-2 border-gray-300">
                            </div>
                            <input type="file" 
                                   name="photo_profile" 
                                   id="photo_profile"
                                   accept="image/jpeg,image/png,image/jpg"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                        @error('photo_profile')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Format: JPEG, PNG, JPG. Maks: 2MB</p>
                    </div>
                </div>
            </div>

            <!-- Additional Information for Mahasiswa/Dosen -->
            <div id="additionalFields" class="mb-8 hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Informasi Tambahan</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Jurusan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jurusan <span class="text-red-500" id="jurusanRequired">*</span>
                        </label>
                        <select name="id_jurusan" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('id_jurusan') border-red-500 @enderror">
                            <option value="">Pilih Jurusan</option>
                            @foreach($jurusan as $j)
                                <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                                    {{ $j->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_jurusan')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keahlian -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Bidang Keahlian <span class="text-red-500" id="keahlianRequired">*</span>
                        </label>
                        <select name="id_keahlian" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('id_keahlian') border-red-500 @enderror">
                            <option value="">Pilih Keahlian</option>
                            @foreach($keahlian as $k)
                                <option value="{{ $k->id_keahlian }}" {{ old('id_keahlian') == $k->id_keahlian ? 'selected' : '' }}>
                                    {{ $k->nama_keahlian }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_keahlian')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Angkatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Angkatan <span class="text-red-500" id="angkatanRequired">*</span>
                        </label>
                        <select name="id_angkatan" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('id_angkatan') border-red-500 @enderror">
                            <option value="">Pilih Angkatan</option>
                            @foreach($angkatan as $a)
                                <option value="{{ $a->id }}" {{ old('id_angkatan') == $a->id ? 'selected' : '' }}>
                                    {{ $a->tahun }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_angkatan')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        id="submitBtn"
                        disabled
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
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
    
    // Update hidden input
    document.getElementById('selectedRole').value = role;
    
    // Reset all cards
    document.querySelectorAll('[id^="card-"]').forEach(card => {
        card.classList.remove('border-blue-500', 'border-red-500', 'border-green-500', 'ring-2', 'ring-offset-2');
    });
    
    // Highlight selected card
    const selectedCard = document.getElementById(`card-${role}`);
    if (role === 'admin') {
        selectedCard.classList.add('border-red-500', 'ring-2', 'ring-red-500', 'ring-offset-2');
    } else if (role === 'mahasiswa') {
        selectedCard.classList.add('border-blue-500', 'ring-2', 'ring-blue-500', 'ring-offset-2');
    } else if (role === 'dosen') {
        selectedCard.classList.add('border-green-500', 'ring-2', 'ring-green-500', 'ring-offset-2');
    }
    
    // Show/hide additional fields
    const additionalFields = document.getElementById('additionalFields');
    const jurusanRequired = document.getElementById('jurusanRequired');
    const keahlianRequired = document.getElementById('keahlianRequired');
    const angkatanRequired = document.getElementById('angkatanRequired');
    
    if (role === 'admin') {
        additionalFields.classList.add('hidden');
        // Remove required attributes
        document.querySelector('select[name="id_jurusan"]').required = false;
        document.querySelector('select[name="id_keahlian"]').required = false;
        document.querySelector('select[name="id_angkatan"]').required = false;
    } else {
        additionalFields.classList.remove('hidden');
        // Add required attributes
        document.querySelector('select[name="id_jurusan"]').required = true;
        document.querySelector('select[name="id_keahlian"]').required = true;
        document.querySelector('select[name="id_angkatan"]').required = true;
    }
    
    // Enable submit button
    document.getElementById('submitBtn').disabled = false;
}

// Preview image before upload
document.getElementById('photo_profile').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
// Check if there's old input for role
@if(old('role'))
    selectRole('{{ old('role') }}');
@endif

document.addEventListener("DOMContentLoaded", function () {

    // Jika ada old input (validasi gagal)
    @if(old('role'))
        selectRole('{{ old('role') }}');

    // Jika tidak ada old input dan user login adalah admin
    @elseif(auth()->check() && auth()->user()->role === 'admin')
        selectRole('admin');
    @endif

});
</script>

@endsection