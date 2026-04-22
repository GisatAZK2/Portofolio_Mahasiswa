@extends('Layout.Layout')
@section('title', autoTranslate('Edit Postingan'))

@section('content')
    <div class="min-h-screen">
        <div class="p-8">
            <!-- Header -->
            <div class="mb-10 text-center md:text-left">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200" data-translate="ttl"
                    data-translate-page="edit_post">Edit Postingan</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300" data-translate="desc" data-translate-page="edit_post">
                    Perbarui konten postingan Anda.</p>
            </div>

            <!-- Error Global -->
            @if ($errors->any())
                <div
                    class="mb-8 p-5 bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">{{ autoTranslate('Terdapat kesalahan pada input:') }}</span>
                    </div>
                    <ul class="list-disc pl-10 space-y-1.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ autoTranslate($error) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('postingan.update', ['id' => $postingan->id_postingan]) }}"
                enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Judul -->
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <span data-translate="jdl" data-translate-page="add_post">Judul Postingan</span> <span
                            class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" id="judul"
                        value="{{ old('judul', $postingan->content[0]['content'] ?? '') }}"
                        placeholder="{{ autoTranslate('Judul postingan Anda...') }}" required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition @error('judul') border-red-500 @enderror">
                    @error('judul')
                        <p class="mt-1 text-sm text-red-600">{{ autoTranslate($message) }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <span data-translate="deskripsi_opsional" data-translate-page="project_create">Deskripsi
                            (opsional)</span>
                    </label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full pl-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg
                                                        focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                                        text-gray-700 dark:text-gray-300
                                                        placeholder-gray-500 dark:placeholder-gray-400
                                                        shadow-sm bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm @error('deskripsi') border-red-500 @enderror"
                        placeholder="{{ autoTranslate('Deskripsikan postingan Anda...') }}">{{ old('deskripsi', $postingan->content[1]['content'] ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ autoTranslate($message) }}</p>
                    @enderror
                </div>

                <!-- Dynamic Items -->
                <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200" data-translate="addition"
                            data-translate-page="add_post">Konten Tambahan (opsional)</h3>
                        <button type="button" id="add-item"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span data-translate="add" data-translate-page="add_post">Tambah Item</span>
                        </button>
                    </div>

                    <div id="items-container" class="space-y-6">
                        <!-- Existing items -->
                        @php
                            $existingItems = [];
                            if (is_array($postingan->content)) {
                                foreach ($postingan->content as $item) {
                                    if (isset($item['type']) && !in_array($item['type'], ['title', 'description', 'game_thumbnail'])) {
                                        $existingItems[] = $item;
                                    }
                                }
                            }
                        @endphp
                        @foreach($existingItems as $index => $item)
                            <div class="item bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 relative"
                                data-index="{{ $index }}">
                                <div class="flex justify-between items-start mb-4">
                                    <select name="items[{{ $index }}][type]"
                                        class="type-select border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2 text-sm focus:border-indigo-500 outline-none w-44">
                                        <option value="image" {{ (isset($item['type']) && $item['type'] === 'image') ? 'selected' : '' }}>
                                            {{ autoTranslate('Gambar') }}
                                        </option>
                                        <option value="link" {{ (isset($item['type']) && $item['type'] === 'link') ? 'selected' : '' }}>
                                            {{ autoTranslate('Link / Referensi') }}
                                        </option>
                                    </select>
                                    <button type="button"
                                        class="remove-item text-red-500 hover:text-red-700 text-sm font-medium">
                                        {{ autoTranslate('Hapus') }}
                                    </button>
                                </div>

                                <div class="content-area">
                                    <!-- Hidden text input for storing content value -->
                                    <input type="hidden" name="items[{{ $index }}][content]" class="content-value" value="{{ $item['content'] ?? '' }}">
                                    
                                    <!-- File upload area -->
                                    <div class="file-input {{ (isset($item['type']) && $item['type'] !== 'image') ? 'hidden' : '' }}">
                                        @if(isset($item['type']) && $item['type'] === 'image' && isset($item['content']))
                                            <div class="mb-3">
                                                <img src="{{ asset('storage/' . $item['content']) }}"
                                                    class="max-w-xs h-auto rounded-lg border border-gray-200 dark:border-gray-700" alt="Current image">
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ autoTranslate('Gambar saat ini') }}
                                                </p>
                                                <button type="button" class="remove-image-btn text-xs text-red-600 hover:text-red-800 mt-1">
                                                    {{ autoTranslate('Hapus gambar') }}
                                                </button>
                                            </div>
                                        @endif
                                        <input type="file" name="items[{{ $index }}][file]" accept="image/*"
                                            class="image-file-input block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 dark:file:bg-indigo-900 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800">
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ autoTranslate('Maks 5MB • jpg, png, gif, webp') }}</p>
                                    </div>

                                    <!-- Link input -->
                                    <input type="url" name="items[{{ $index }}][content]"
                                        class="link-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:border-indigo-500 outline-none transition {{ (isset($item['type']) && $item['type'] !== 'link') ? 'hidden' : '' }}"
                                        placeholder="https://example.com"
                                        value="{{ isset($item['type']) && $item['type'] === 'link' ? ($item['content'] ?? '') : '' }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Game Option -->
                @php
                    $gameThumbnail = null;
                    if (is_array($postingan->content)) {
                        foreach ($postingan->content as $c) {
                            if (isset($c['type']) && $c['type'] === 'game_thumbnail') {
                                $gameThumbnail = ltrim($c['content'], '/');
                                break;
                            }
                        }
                    }
                    $existingGame = $postingan->game ?? null;
                    $hasGame = $existingGame ? true : false;
                @endphp

        
                @if (Auth::user()->role !== 'mahasiswa') 
                <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-3">
                        {{ autoTranslate('Tambahkan Game (opsional)') }}
                    </h3>

                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="game_enabled" id="game_enabled" value="on"
                                class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300"
                                {{ old('game_enabled', $hasGame) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ autoTranslate('Sertakan game pada postingan') }}
                            </span>
                        </label>

                        <select name="game_name" id="game_name"
                            class="ml-2 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-sm"
                            {{ (old('game_enabled', $hasGame)) ? '' : 'disabled' }}>
                            <option value="Matematika" {{ old('game_name', $existingGame->game_name ?? '') === 'Matematika' ? 'selected' : '' }}>
                                Matematika
                            </option>
                            <option value="Puzzle" {{ old('game_name', $existingGame->game_name ?? '') === 'Puzzle' ? 'selected' : '' }}>
                                Puzzle
                            </option>
                            <option value="TTS" {{ old('game_name', $existingGame->game_name ?? '') === 'TTS' ? 'selected' : '' }}>
                                Teka-Teki Silang (TTS)
                            </option>
                        </select>
                    </div>

                </div>
                @endif

                <!-- Submit -->
                <div class="flex justify-end pt-8 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('postingan.index') }}"
                        class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition mr-4">
                        {{ autoTranslate('Batal') }}
                    </a>
                    <button type="submit"
                        class="px-10 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-md">
                        {{ autoTranslate('Update Postingan') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript Dynamic Items -->
    <script>
        let itemIndex = {{ count($existingItems) }};

        function addItem() {
            const container = document.getElementById('items-container');
            const newItem = document.createElement('div');
            newItem.className = 'item bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 relative';
            newItem.dataset.index = itemIndex;

            newItem.innerHTML = `
                <div class="flex justify-between items-start mb-4">
                    <select name="items[${itemIndex}][type]" class="type-select border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2 text-sm focus:border-indigo-500 outline-none w-44">
                        <option value="image">{{ autoTranslate('Gambar') }}</option>
                        <option value="link">{{ autoTranslate('Link / Referensi') }}</option>
                    </select>
                    <button type="button" class="remove-item text-red-500 hover:text-red-700 text-sm font-medium">
                        {{ autoTranslate('Hapus') }}
                    </button>
                </div>

                <div class="content-area">
                    <div class="file-input hidden">
                        <input type="file" name="items[${itemIndex}][file]" accept="image/*"
                            class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 dark:file:bg-indigo-900 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ autoTranslate('Maks 5MB • jpg, png, gif, webp') }}</p>
                    </div>
                    <input type="url" name="items[${itemIndex}][content]" class="link-input hidden w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:border-indigo-500 outline-none transition"
                        placeholder="https://example.com">
                </div>
            `;

            container.appendChild(newItem);
            attachTypeListener(newItem);
            itemIndex++;
        }

        function attachTypeListener(itemElement) {
            const select = itemElement.querySelector('.type-select');
            const fileDiv = itemElement.querySelector('.file-input');
            const linkInput = itemElement.querySelector('.link-input');

            function toggleFields() {
                const type = select.value;
                fileDiv.classList.toggle('hidden', type !== 'image');
                linkInput.classList.toggle('hidden', type !== 'link');
                linkInput.disabled = type !== 'link';
            }

            select.addEventListener('change', toggleFields);
            toggleFields();
        }

        // Attach listeners to existing items
        document.querySelectorAll('.item').forEach(item => {
            attachTypeListener(item);
        });

        // Event listeners
        document.getElementById('add-item').addEventListener('click', addItem);

        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.item').remove();
            }
        });

        // Handle remove image button for existing items
        document.querySelectorAll('.remove-image-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const fileInput = this.closest('.file-input').querySelector('.image-file-input');
                const hiddenInput = this.closest('.item').querySelector('.content-value');
                const imgContainer = this.parentElement;
                
                if (imgContainer) imgContainer.remove();
                if (fileInput) fileInput.value = '';
                if (hiddenInput) hiddenInput.value = '';
            });
        });

        // Game toggle logic
        (function() {
            const gameEnabledEl = document.getElementById('game_enabled');
            if (!gameEnabledEl) return;
            
            const gameNameEl = document.getElementById('game_name');
            const gameThumbEl = document.getElementById('game_thumbnail');
            const thumbnailContainer = document.getElementById('thumbnail_container');
            const removeThumbBtn = document.getElementById('remove_thumbnail_btn');
            const removeThumbInput = document.getElementById('remove_thumbnail');

            // Initial state
            const isChecked = gameEnabledEl.checked;
            if (gameNameEl) gameNameEl.disabled = !isChecked;
            if (gameThumbEl) gameThumbEl.disabled = !isChecked;
            if (thumbnailContainer) thumbnailContainer.style.display = isChecked ? 'block' : 'none';

            // Handle checkbox change
            gameEnabledEl.addEventListener('change', function() {
                const checked = this.checked;
                if (gameNameEl) gameNameEl.disabled = !checked;
                if (gameThumbEl) gameThumbEl.disabled = !checked;
                if (thumbnailContainer) thumbnailContainer.style.display = checked ? 'block' : 'none';
                
                // If unchecked, remove thumbnail requirement
                if (!checked && removeThumbInput) {
                    removeThumbInput.value = '1';
                }
            });

            // Handle remove thumbnail button
            if (removeThumbBtn) {
                removeThumbBtn.addEventListener('click', function() {
                    const container = document.getElementById('current_thumbnail_container');
                    if (container) container.remove();
                    if (removeThumbInput) removeThumbInput.value = '1';
                    if (gameThumbEl) gameThumbEl.value = '';
                });
            }
        })();
    </script>
@endsection