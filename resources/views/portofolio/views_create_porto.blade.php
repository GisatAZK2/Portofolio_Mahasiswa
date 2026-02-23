@extends('Layout.Layout')

@section('title', 'Buat Portfolio Baru')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Buat Portfolio Baru</h1>
        
        <!-- Form untuk create portfolio -->
        <form action="{{ route('portofolio.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Portfolio</label>
                <input type="text" name="judul" id="judul" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Masukkan judul portfolio">
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Jelaskan tentang portfolio ini"></textarea>
            </div>

            <div>
                <label for="link_video" class="block text-sm font-medium text-gray-700 mb-2">Link Video (Optional)</label>
                <input type="url" name="link_video" id="link_video" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="https://www.youtube.com/watch?v=...">
            </div>

            <div>
                <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">Thumbnail (Optional)</label>
                <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Simpan Portfolio</button>
                <a href="{{ route('portofolio.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
