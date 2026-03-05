@extends('Layout.Layout')
@section('title', 'Tambah Catatan Learning Corner')

@section('content')
    <div class="min-h-screen">
        <div class=" p-8">
            <!-- Header -->
            <div class="mb-10 text-center md:text-left">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Tambah Catatan Baru</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300 ">Tulis apa yang kamu pelajari hari ini atau bagikan ilmu yang ingin kamu
                    simpan.</p>
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
            <form method="POST" action="{{ route('learning-corner.store', $project) }}" enctype="multipart/form-data"
                class="space-y-8">
                @csrf

                <!-- Judul -->
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Judul Catatan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul') }}" placeholder="Judul Catatan disini.." required
                        class="w-full px-4 py-3 border border-gray-300 dark:placeholder:text-white dark:bg-gray-400 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition @error('judul') border-red-500 @enderror">
                    @error('judul')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dynamic Items -->
                <div class="pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">Konten Tambahan (opsional)</h3>
                        <button type="button" id="add-item" name="project_id" value="$project->project_id"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Item
                        </button>
                    </div>

                    <div id="items-container" class="space-y-6">
                        <!-- Item template akan ditambahkan via JS -->
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex justify-end pt-8 border-t border-gray-200">
                    <button type="submit" name="project_id" value="{{$project->id}}"
                        class="px-10 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md">
                        Simpan Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript Dynamic Items -->
    <script>
        let itemIndex = 0;

        function addItem() {
            const container = document.getElementById('items-container');
            const newItem = document.createElement('div');
            newItem.className = 'item bg-gray-50 border border-gray-200 dark:bg-gray-900 rounded-xl p-6 relative';
            newItem.dataset.index = itemIndex;

            newItem.innerHTML = `
                <div class="flex justify-between items-start mb-4 ">
                    <select name="items[${itemIndex}][type]" class="type-select border dark:text-white border-gray-300 dark:bg-gray-400 rounded px-3 py-2 text-sm focus:border-indigo-500 outline-none w-44">
                        <option class="dark:text-white" value="text">Teks tambahan</option>
                        <option class="dark:text-white" value="image">Gambar</option>
                        <option class="dark:text-white" value="link">Link / Referensi</option>
                    </select>
                    <button type="button" class="remove-item text-red-500 hover:text-red-700 text-sm font-medium">
                        Hapus
                    </button>
                </div>

                <div class="content-area">
                    <!-- Teks default -->
                    <input type="text" name="items[${itemIndex}][content]" class="text-input dark:placeholder:text-white w-full px-4 py-3 border border-gray-300 dark:bg-gray-400 rounded-lg focus:border-indigo-500 outline-none transition"
                           placeholder="Masukkan teks di sini...">

                    <!-- File upload (hidden awal) -->
                    <div class="file-input hidden mt-2">
                        <input type="file" name="items[${itemIndex}][file]" accept="image/*"
                               class="block w-full text-sm text-gray-500 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-xs text-gray-500">Maks 5MB • jpg, png, gif</p>
                    </div>

                    <!-- Link (hidden awal) -->
                    <input type="url" name="items[${itemIndex}][content]" class="link-input hidden w-full px-4 py-3 dark:placeholder:text-white dark:bg-gray-400 border border-gray-300 rounded-lg focus:border-indigo-500 outline-none transition"
                           placeholder="https://example.com">
                </div>
            `;

            container.appendChild(newItem);
            attachTypeListener(newItem);
            itemIndex++;
        }

        function attachTypeListener(itemElement) {
            const select = itemElement.querySelector('.type-select');
            const textInput = itemElement.querySelector('.text-input');
            const fileDiv = itemElement.querySelector('.file-input');
            const linkInput = itemElement.querySelector('.link-input');

            function toggleFields() {
                const type = select.value;
                textInput.classList.toggle('hidden', type !== 'text');
                fileDiv.classList.toggle('hidden', type !== 'image');
                linkInput.classList.toggle('hidden', type !== 'link');

                // Pastikan hanya satu input content yang aktif (untuk validasi)
                textInput.disabled = type !== 'text';
                linkInput.disabled = type !== 'link';
                if (type === 'image') {
                    textInput.name = `items[${itemElement.dataset.index}][dummy]`; // hindari kirim kosong
                } else {
                    textInput.name = `items[${itemElement.dataset.index}][content]`;
                }
            }

            select.addEventListener('change', toggleFields);
            toggleFields(); // init
        }

        // Add first item by default
        addItem();

        // Event listeners
        document.getElementById('add-item').addEventListener('click', addItem);

        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.item').remove();
            }
        });
    </script>
@endsection