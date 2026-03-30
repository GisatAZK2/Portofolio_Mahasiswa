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
                <span data-translate="nm_agkt_kcl" data-translate-page="admin">Nama Angkatan</span> <span class="text-red-500">*</span>
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
                <span data-translate="thn_agkt_kcl" data-translate-page="admin">Tahun Masuk</span> <span class="text-red-500">*</span>
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
                <span data-translate="exit_agkt_kcl" data-translate-page="admin">Tahun Keluar</span>
            </label>
            <input type="date" 
                   name="tahun_keluar" 
                   id="tahun_keluar" 
                   value="{{ old('tahun_keluar') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 @error('tahun_keluar') border-red-500 @enderror">
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                <span data-translate="desc_agkt_exit" data-translate-page="admin">Kosongkan jika belum lulus</span>
                </p>
            @error('tahun_keluar')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

       <!-- Action Buttons - Versi Terbaik -->
<div class="pt-8 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
    <a href="{{ route('admin.projects.index') }}"
       class="px-7 py-3.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-2xl transition focus:outline-none focus:ring-2 focus:ring-gray-500">
        <span data-translate="cncl" data-translate-page="admin">Batal</span>
    </a>
    
    <button type="submit"
        class="px-9 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-2xl shadow-md transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
        <span data-translate="addadd_agkt" data-translate-page="admin">Tambahkan Angkatan</span>
    </button>
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