@extends('Layout.Layout')

@section('content')
<div class="p-6 lg:p-8 max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Tambah Entri Learning Corner</h1>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('learning-corner.store') }}" class="space-y-6 bg-white p-8 rounded-xl shadow-md border border-gray-100">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Judul <span class="text-red-500">*</span></label>
            <input type="text" name="judul" value="{{ old('judul') }}" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('judul') border-red-500 @enderror">
            @error('judul') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('tanggal') border-red-500 @enderror">
            @error('tanggal') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Dynamic items (text, image, link) -->
        <div id="items-container" class="space-y-4">
            <h3 class="text-lg font-medium text-gray-800 mt-6">Konten Tambahan (opsional)</h3>

            <!-- Contoh satu item awal (bisa kosong) -->
            <div class="item border p-4 rounded-lg bg-gray-50" data-index="0">
                <div class="flex justify-between mb-2">
                    <select name="items[0][type]" class="border rounded px-3 py-2">
                        <option value="text">Teks</option>
                        <option value="image">Gambar (URL)</option>
                        <option value="link">Link</option>
                    </select>
                    <button type="button" class="text-red-600 hover:text-red-800 remove-item">Hapus</button>
                </div>
                <input type="text" name="items[0][content]" class="w-full px-3 py-2 border rounded"
                       placeholder="Masukkan isi teks / url gambar / url link">
            </div>
        </div>

        <div class="mt-4">
            <button type="button" id="add-item"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                + Tambah Item (teks/gambar/link)
            </button>
        </div>

        <div class="flex justify-end space-x-4 pt-6">
            <a href="{{ route('learning-corner.index') }}"
               class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300">
                Batal
            </a>
            <button type="submit"
                    class="px-8 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 shadow-md">
                Simpan
            </button>
        </div>
    </form>
</div>

<script>
    let itemIndex = 1;

    document.getElementById('add-item').addEventListener('click', () => {
        const container = document.getElementById('items-container');
        const newItem = document.createElement('div');
        newItem.className = 'item border p-4 rounded-lg bg-gray-50';
        newItem.dataset.index = itemIndex;
        newItem.innerHTML = `
            <div class="flex justify-between mb-2">
                <select name="items[${itemIndex}][type]" class="border rounded px-3 py-2">
                    <option value="text">Teks</option>
                    <option value="image">Gambar (URL)</option>
                    <option value="link">Link</option>
                </select>
                <button type="button" class="text-red-600 hover:text-red-800 remove-item">Hapus</button>
            </div>
            <input type="text" name="items[${itemIndex}][content]" class="w-full px-3 py-2 border rounded"
                   placeholder="Masukkan isi teks / url gambar / url link">
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