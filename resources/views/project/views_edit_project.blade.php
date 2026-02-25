@extends('Layout.Layout') 

@section('content')
<div class="p-6 lg:p-8 max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Edit Project</h1>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('project.update', $project->id) }}" class="space-y-6 bg-white p-8 rounded-xl shadow-md border border-gray-100">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Project <span class="text-red-500">*</span></label>
            <input type="text" name="nama_project" value="{{ old('nama_project', $project->isi_content['nama_project'] ?? '') }}"  required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('nama_project') border-red-500 @enderror">
            @error('nama_project') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $project->tanggal_mulai->format('Y-m-d')) }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('tanggal_mulai') border-red-500 @enderror">
                @error('tanggal_mulai') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai (opsional)</label>
                <input type="date" name="tanggal_akhir" value="{{ old('tanggal_akhir', $project->tanggal_akhir ? $project->tanggal_akhir->format('Y-m-d') : '') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('tanggal_akhir') border-red-500 @enderror">
                @error('tanggal_akhir') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Link Project (opsional)</label>
            <input type="url" name="link_project" value="{{ old('link_project', $project->isi_content['link_project'] ?? '') }}" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('link_project') border-red-500 @enderror"
                   placeholder="https://github.com/username/project">
            @error('link_project') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
       
        <div>
                <label for="link_github" class="block text-sm font-medium text-gray-700 mb-2">Link GitHub (opsional)</label>
                <input type="url" name="link_github" id="link_github" maxlength="500"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_github') border-red-500 @enderror"
                       placeholder="https://github.com/username/repo" value="{{ old('link_github', $project->isi_content['link_github'] ?? '') }}" >
                @error('link_github')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
        </div>

            <!-- Link Video -->
        <div>
            <label for="link_video" class="block text-sm font-medium text-gray-700 mb-2">Link Video (YouTube, opsional)</label>
            <input type="url" name="link_video" id="link_video" maxlength="500"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('link_video') border-red-500 @enderror"
                       placeholder="https://www.youtube.com/watch?v=..." value="{{ old('link_video', $project->isi_content['link_video'] ?? '') }}" >
            @error('link_video')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="flex justify-end space-x-4 pt-4">
            <a href="{{ route('project.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition">
                Batal
            </a>
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition shadow-md">
                Update Project
            </button>
        </div>
    </form>
</div>
@endsection