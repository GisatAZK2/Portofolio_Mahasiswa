@extends('Layout.Layout')
@section('title', 'Tambah Jurusan')

@section('content')

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold dark:text-white" data-translate="tambah_jurusan_title" data-translate-page="admin">Tambah Jurusan Baru</h2>
        </div>

        <form action="{{ route('admin.prodi.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="nama_prodi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" data-translate="nama_jurusan_label" data-translate-page="admin">
                    Nama Jurusan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_prodi" id="nama_prodi" value="{{ old('nama_prodi') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 @error('nama_jurusan') border-red-500 @enderror"
                    placeholder="Contoh: Teknik Informatika" data-translate-placeholder="nama_jurusan_placeholder" data-translate-page="admin" required>
                @error('nama_prodi')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="pt-8 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                <a href="{{ route('admin.prodi.index') }}"
                    class="px-7 py-3.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-2xl transition focus:outline-none focus:ring-2 focus:ring-gray-500"
                    data-translate="cancel" data-translate-page="admin">
                    Batal
                </a>

                <button type="submit"
                    class="px-9 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-2xl shadow-md transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    data-translate="tambah_jurusan" data-translate-page="admin">
                    Tambahkan Jurusan
                </button>
            </div>
        </form>
    </div>

@endsection