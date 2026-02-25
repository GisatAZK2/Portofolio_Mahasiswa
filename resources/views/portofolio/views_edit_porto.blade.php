@extends('Layout.Layout')
@section('title', 'Edit Portfolio')
@section('content')
    <div class="min-h-screen bg-gray-100 py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Portfolio</h1>
                <p class="text-gray-600 mb-8">Mengedit karya dari {{ $portfolio->mahasiswa->nama_mahasiswa ?? 'Mahasiswa' }}</p>

                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('portofolio.update', $portfolio->id_portfolio) }}" method="POST" class="space-y-7">
                    @csrf
                    @method('PUT')

                    <!-- Judul -->
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Portfolio</label>
                        <input type="text" name="judul" id="judul" maxlength="255" value="{{ old('judul', $portfolio->isi_content['judul'] ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="5"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">{{ old('deskripsi', $portfolio->isi_content['deskripsi'] ?? '') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link Project -->
                    <div>
                        <label for="link_project" class="block text-sm font-medium text-gray-700 mb-2">Link Project</label>
                        <input type="url" name="link_project" id="link_project" maxlength="500" value="{{ old('link_project', $portfolio->isi_content['link_project'] ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                               placeholder="https://example.com/my-project">
                        @error('link_project')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link GitHub -->
                    <div>
                        <label for="link_github" class="block text-sm font-medium text-gray-700 mb-2">Link GitHub</label>
                        <input type="url" name="link_github" id="link_github" maxlength="500" value="{{ old('link_github', $portfolio->isi_content['link_github'] ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                               placeholder="https://github.com/username/repo">
                        @error('link_github')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link Video -->
                    <div>
                        <label for="link_video" class="block text-sm font-medium text-gray-700 mb-2">Link Video (YouTube)</label>
                        <input type="url" name="link_video" id="link_video" maxlength="500" value="{{ old('link_video', $portfolio->isi_content['link_video'] ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                               placeholder="https://www.youtube.com/watch?v=...">
                        @error('link_video')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                        <button type="submit"
                                class="flex-1 px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                            Update Portfolio
                        </button>
                        <a href="{{ route('portofolio.index') }}"
                           class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 text-center transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        @if (session('success'))
            showSuccessAlert('{{ session('success') }}');
        @endif

        @if ($errors->any())
            showErrorAlert('{{ $errors->first() }}'); 
             {{ implode('\n', $errors->all()) }}
        @endif
    });
    </script>
@endsection