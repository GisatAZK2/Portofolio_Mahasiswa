@extends('Layout.Layout')

@section('title', 'Edit Catatan Learning Corner')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        
        <!-- Header -->
        <div class="mb-10 text-center md:text-left">
            <h1 class="text-3xl font-bold text-gray-800">Edit Catatan</h1>
            <p class="mt-2 text-gray-600">Ubah judul atau tambah/ubah/hapus konten yang sudah ada.</p>
        </div>

        <!-- Error Global -->
        @if ($errors->any())
            <div class="mb-8 p-5 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                <ul class="list-disc pl-6 space-y-1.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('learning-corner.update', $learningCorner) }}" 
              class="space-y-8" enctype="multipart/form-data">
            
            @csrf
            @method('PUT')

            <!-- Judul -->
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Catatan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="judul" id="judul"
                       value="{{ old('judul', $learningCorner->judul) }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition @error('judul') border-red-500 @enderror">
                @error('judul')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Dynamic Items -->
            <div class="pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-medium text-gray-800">Konten Tambahan</h3>
                    <button type="button" id="add-item"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Item
                    </button>
                </div>

                <div id="items-container" class="space-y-6">
                    @foreach ($items as $idx => $item)
                        <div class="item bg-gray-50 border border-gray-200 rounded-xl p-6 relative" data-index="{{ $idx }}">
                            
                            <div class="flex justify-between items-start mb-4">
                                <select name="items[{{ $idx }}][type]" class="type-select border border-gray-300 rounded px-3 py-2 text-sm focus:border-indigo-500 outline-none w-44">
                                    <option value="text"   {{ $item['type'] === 'text'   ? 'selected' : '' }}>Teks tambahan</option>
                                    <option value="image"  {{ $item['type'] === 'image'  ? 'selected' : '' }}>Gambar</option>
                                    <option value="link"   {{ $item['type'] === 'link'   ? 'selected' : '' }}>Link / Referensi</option>
                                </select>
                                <button type="button" class="remove-item text-red-500 hover:text-red-700 text-sm font-medium">
                                    Hapus
                                </button>
                            </div>

                            <div class="content-area mt-3">
                                @if ($item['type'] === 'image')
                                    <div class="mb-4">
                                        @if ($item['content'] && Storage::disk('public')->exists($item['content']))
                                            <img src="{{ Storage::url($item['content']) }}" 
                                                 alt="Preview gambar lama" 
                                                 class="max-h-64 object-contain rounded border border-gray-300 bg-white">
                                            <p class="text-xs text-gray-500 mt-1">Gambar saat ini</p>
                                        @else
                                            <p class="text-sm text-gray-500 italic">Gambar tidak ditemukan</p>
                                        @endif
                                    </div>
                                    <label class="block text-sm text-gray-600 mb-1">Ganti gambar (opsional):</label>
                                    <input type="file" name="items[{{ $idx }}][image_file]" accept="image/*"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    <!-- Kirim content lama agar bisa dipertahankan jika tidak upload baru -->
                                    <input type="hidden" name="items[{{ $idx }}][content]" value="{{ $item['content'] ?? '' }}">
                                @else
                                    <input type="text" name="items[{{ $idx }}][content]"
                                           value="{{ old("items.$idx.content", $item['content'] ?? '') }}"
                                           class="text-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 outline-none transition @error("items.$idx.content") border-red-500 @enderror"
                                           placeholder="{{ $item['type'] === 'link' ? 'https://...' : 'Masukkan teks di sini...' }}">
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end pt-8 border-t border-gray-200 space-x-4">
                <a href="{{ route('learning-corner.index') }}"
                   class="px-8 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-10 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript Dynamic Items -->
<script>
    let itemIndex = {{ count($items) }};

    function addItem() {
        const container = document.getElementById('items-container');
        const newItem = document.createElement('div');
        newItem.className = 'item bg-gray-50 border border-gray-200 rounded-xl p-6 relative';
        newItem.dataset.index = itemIndex;

        newItem.innerHTML = `
            <div class="flex justify-between items-start mb-4">
                <select name="items[${itemIndex}][type]" class="type-select border border-gray-300 rounded px-3 py-2 text-sm focus:border-indigo-500 outline-none w-44">
                    <option value="text">Teks tambahan</option>
                    <option value="image">Gambar</option>
                    <option value="link">Link / Referensi</option>
                </select>
                <button type="button" class="remove-item text-red-500 hover:text-red-700 text-sm font-medium">
                    Hapus
                </button>
            </div>
            <div class="content-area mt-3">
                <input type="text" name="items[${itemIndex}][content]" 
                       class="text-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 outline-none transition"
                       placeholder="Masukkan teks di sini...">
            </div>
        `;

        container.appendChild(newItem);
        itemIndex++;

        // Optional: auto fokus ke input baru
        newItem.querySelector('input').focus();
    }

    document.getElementById('add-item').addEventListener('click', addItem);

    // Remove item
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.item').remove();
        }
    });

    // Optional: ketika pilih tipe image → ganti input jadi file
    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('type-select')) {
            const itemDiv = e.target.closest('.item');
            const contentArea = itemDiv.querySelector('.content-area');
            const currentType = e.target.value;
            
            if (currentType === 'image') {
                contentArea.innerHTML = `
                    <label class="block text-sm text-gray-600 mb-1">Upload gambar:</label>
                    <input type="file" name="items[${itemDiv.dataset.index}][image_file]" accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                `;
            } else {
                contentArea.innerHTML = `
                    <input type="text" name="items[${itemDiv.dataset.index}][content]" 
                           class="text-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 outline-none transition"
                           placeholder="${currentType === 'link' ? 'https://...' : 'Masukkan teks di sini...'}">
                `;
            }
        }
    });
</script>
@endsection