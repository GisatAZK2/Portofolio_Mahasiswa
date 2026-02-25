@extends('Layout.Layout')
@section('title', 'Buat Portfolio Baru')
@section('content')
    <div class="p-6 lg:p-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Buat Portfolio Baru</h1>
            <p class="text-gray-600">
                Buat portfolio baru untuk menampilkan karya kamu.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Optional: Tampilkan success jika redirect back ke create (jarang dipakai) -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('portofolio.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-8 rounded-xl shadow-md border border-gray-100">
            @csrf

            <!-- Judul -->
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Portfolio</label>
                <input type="text" name="judul" id="judul" maxlength="255"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('judul') border-red-500 @enderror"
                       placeholder="Contoh: Aplikasi Manajemen Keuangan Mahasiswa" value="{{ old('judul') }}">
                @error('judul')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="5"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('deskripsi') border-red-500 @enderror"
                          placeholder="Jelaskan secara singkat tentang portfolio/karya ini...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Link Project -->
            <div>
                <label for="link_project" class="block text-sm font-medium text-gray-700 mb-2">Link Project (opsional)</label>
                <input type="url" name="link_project" id="link_project" maxlength="500"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_project') border-red-500 @enderror"
                       placeholder="https://example.com/my-project" value="{{ old('link_project') }}">
                @error('link_project')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Link GitHub -->
            <div>
                <label for="link_github" class="block text-sm font-medium text-gray-700 mb-2">Link GitHub (opsional)</label>
                <input type="url" name="link_github" id="link_github" maxlength="500"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_github') border-red-500 @enderror"
                       placeholder="https://github.com/username/repo" value="{{ old('link_github') }}">
                @error('link_github')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Link Video -->
            <div>
                <label for="link_video" class="block text-sm font-medium text-gray-700 mb-2">Link Video (YouTube, opsional)</label>
                <input type="url" name="link_video" id="link_video" maxlength="500"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_video') border-red-500 @enderror"
                       placeholder="https://www.youtube.com/watch?v=..." value="{{ old('link_video') }}">
                @error('link_video')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol -->
            <div class="flex justify-end pt-4">
                <button type="submit"
                        class="px-8 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow-md">
                    Simpan Portfolio
                </button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        @if (session('success'))
            showSuccessAlert('{{ session('success') }}');
        @endif

        @if ($errors->any())
            showErrorAlert('{{ $errors->first() }}');
        @endif
    });
    </script>
@endsection