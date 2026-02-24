@extends('Layout.Layout')

@section('title', 'Tambah Catatan Learning Corner')

@section('content')
<div class="min-h-screen ">
    <div class=" mx-auto">

        <!-- Judul Halaman -->
        <div class="mb-10 text-center md:text-left">
            <h1 class="text-3xl font-bold text-gray-800">Tambah Catatan Baru</h1>
            <p class="mt-2 text-gray-600">Tulis apa yang kamu pelajari hari ini atau bagikan ilmu yang ingin kamu simpan.</p>
        </div>

        <!-- Notifikasi Error -->
        @if ($errors->any())
            <div class="mb-8 p-5 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('learning-corner.store') }}" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 space-y-8">
            @csrf

            <!-- Judul -->
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Catatan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition @error('judul') border-red-500 @enderror">
                @error('judul')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal -->
            <div>
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition @error('tanggal') border-red-500 @enderror">
                @error('tanggal')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konten Utama (Textarea) -->
            <div>
                <label for="isi_learning_corner" class="block text-sm font-medium text-gray-700 mb-2">
                    Isi Catatan <span class="text-red-500">*</span>
                </label>
                <textarea name="isi_learning_corner" id="isi_learning_corner" rows="10" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition resize-y @error('isi_learning_corner') border-red-500 @enderror"
                          placeholder="Tulis apa saja yang ingin kamu ingat... bisa teks panjang, kode, ide, atau ringkasan materi.">{{ old('isi_learning_corner') }}</textarea>
                @error('isi_learning_corner')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Dynamic Items (Teks / Gambar / Link Tambahan) -->
            <div class="pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Tambahan (opsional)</h3>
                    <button type="button" id="add-item"
                            class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Item
                    </button>
                </div>

                <div id="items-container" class="space-y-5">
                    <!-- Item awal (default teks) -->
                    <div class="item bg-gray-50 border border-gray-200 rounded-xl p-5" data-index="0">
                        <div class="flex justify-between items-center mb-3">
                            <select name="items[0][type]" class="border border-gray-300 rounded px-3 py-2 text-sm focus:border-indigo-500 outline-none">
                                <option value="text">Teks tambahan</option>
                                <option value="image">Gambar (URL)</option>
                                <option value="link">Link / Referensi</option>
                            </select>
                            <button type="button" class="text-red-500 hover:text-red-700 text-sm font-medium remove-item">Hapus</button>
                        </div>
                        <input type="text" name="items[0][content]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 outline-none transition"
                               placeholder="Masukkan teks / URL gambar / URL link">
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 border-t border-gray-200">
                <button type="submit"
                        class="px-10 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">
                    Simpan Catatan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript untuk dynamic items -->
<script>
    let itemIndex = 1;

    document.getElementById('add-item').addEventListener('click', () => {
        const container = document.getElementById('items-container');
        const newItem = document.createElement('div');
        newItem.className = 'item bg-gray-50 border border-gray-200 rounded-xl p-5';
        newItem.dataset.index = itemIndex;
        newItem.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <select name="items[${itemIndex}][type]" class="border border-gray-300 rounded px-3 py-2 text-sm focus:border-indigo-500 outline-none">
                    <option value="text">Teks tambahan</option>
                    <option value="image">Gambar (URL)</option>
                    <option value="link">Link / Referensi</option>
                </select>
                <button type="button" class="text-red-500 hover:text-red-700 text-sm font-medium remove-item">Hapus</button>
            </div>
            <input type="text" name="items[${itemIndex}][content]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 outline-none transition"
                   placeholder="Masukkan teks / URL gambar / URL link">
        `;
        container.appendChild(newItem);
        itemIndex++;
    });

    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.item').remove();
        }
    });
</script>
@endsection