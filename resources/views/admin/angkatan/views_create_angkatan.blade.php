@extends('Layout.Layout')
@section('title', 'Tambah Angkatan')
@section('content')

<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold dark:text-white">Tambah Angkatan Baru</h2>
    </div>

    <form action="{{ route('admin.angkatan.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label for="nama_angkatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Nama Angkatan <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="nama_angkatan" 
                   id="nama_angkatan" 
                   value="{{ old('nama_angkatan') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 @error('nama_angkatan') border-red-500 @enderror"
                   placeholder="Contoh: Angkatan 2020" 
                   required>
            @error('nama_angkatan')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="tahun_masuk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Tahun Masuk <span class="text-red-500">*</span>
            </label>
            <input type="date" 
                   name="tahun_masuk" 
                   id="tahun_masuk" 
                   value="{{ old('tahun_masuk') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 @error('tahun_masuk') border-red-500 @enderror"
                   required>
            @error('tahun_masuk')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="tahun_keluar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Tahun Keluar
            </label>
            <input type="date" 
                   name="tahun_keluar" 
                   id="tahun_keluar" 
                   value="{{ old('tahun_keluar') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 @error('tahun_keluar') border-red-500 @enderror">
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kosongkan jika belum lulus</p>
            @error('tahun_keluar')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex space-x-3">
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                Simpan
            </button>
            <a href="{{ route('admin.angkatan.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
// Validasi agar tahun keluar tidak lebih kecil dari tahun masuk
document.getElementById('tahun_keluar').addEventListener('change', function() {
    let tahunMasuk = document.getElementById('tahun_masuk').value;
    let tahunKeluar = this.value;
    
    if (tahunKeluar && tahunKeluar < tahunMasuk) {
        alert('Tahun keluar tidak boleh lebih kecil dari tahun masuk');
        this.value = '';
    }
});
</script>

@endsection