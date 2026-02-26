@extends('Layout.Layout')
@section('title', 'Tambah Project Baru')
@section('content')
    <div class="p-6 lg:p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Tambah Project Baru</h1>
        <p class="text-gray-600">
            Tambah Projek Yang Pernah Kamu Buat.
        </p>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('project.store') }}" class="space-y-6 bg-white p-8 rounded-xl shadow-md border border-gray-100">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Project <span class="text-red-500">*</span></label>
                <input type="text" name="nama_project" value="{{ old('nama_project') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('nama_project') border-red-500 @enderror"
                       placeholder="Contoh: Website Portfolio Pribadi">
                @error('nama_project')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi (opsional)</label>
                <textarea name="deskripsi" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('deskripsi') border-red-500 @enderror"
                          placeholder="Deskripsikan project Anda...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_mulai') border-red-500 @enderror">
                    @error('tanggal_mulai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai (opsional)</label>
                    <input type="date" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('tanggal_akhir') border-red-500 @enderror">
                    @error('tanggal_akhir')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Link Project (opsional)</label>
                <input type="url" name="link_project" value="{{ old('link_project') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_project') border-red-500 @enderror"
                       placeholder="https://github.com/username/project">
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

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow-md">
                    Simpan Project
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
             {{ implode("\n", $errors->all()) }}
        @endif
    });
    </script>
@endsection