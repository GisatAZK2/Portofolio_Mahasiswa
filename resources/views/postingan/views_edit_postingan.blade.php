@extends('Layout.Layout')
@section('title', 'Edit Postingan')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900" data-page-info="popup.edit_postingan">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">

            <!-- Header Modern -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-3">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white" data-translate="ttl"
                            data-translate-page="edit_post">
                            Edit Postingan
                        </h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5" data-translate="desc"
                            data-translate-page="edit_post">
                            Perbarui konten postingan Anda.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Error Global -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-r-xl">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-5 h-5 text-red-500 mt-0.5">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-red-800 dark:text-red-300 mb-2">
                                Terdapat kesalahan pada input:</p>
                            <ul class="text-sm text-red-700 dark:text-red-300 space-y-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error  }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('postingan.update', ['id' => $postingan->id_postingan]) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Main Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                    <!-- Content Area -->
                    <div class="p-5 sm:p-6">
                        <!-- Judul Input -->
                        <div class="mb-5">
                            <textarea name="judul" id="judul" rows="1"
                                placeholder="Judul postingan...}"
                                class="w-full px-0 py-2 text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-700 focus:border-indigo-500 focus:ring-0 resize-none overflow-hidden transition-colors @error('judul') border-red-500 @enderror"
                                oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">{{ old('judul', $postingan->content[0]['content'] ?? '') }}</textarea>
                            @error('judul')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi Input -->
                        <div class="mb-2">
                            <textarea name="deskripsi" id="deskripsi" rows="3"
                                placeholder="Tulis sesuatu yang menarik..."
                                class="w-full px-0 py-2 text-gray-700 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500 bg-transparent border-0 focus:ring-0 resize-none text-base leading-relaxed @error('deskripsi') border-red-500 @enderror"
                                oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">{{ old('deskripsi', $postingan->content[1]['content'] ?? '') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dynamic Items Section -->
                    <div class="border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        <div id="items-container" class="divide-y divide-gray-100 dark:divide-gray-700">

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

                            <div id="items-container" class="divide-y divide-gray-100 dark:divide-gray-700" data-item-count="{{ count($existingItems) }}">
                                @foreach ($existingItems as $index => $item)
                                    <div class="item p-5 bg-white dark:bg-gray-800 relative group" data-index="{{ $index }}">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center"
                                                id="icon-container-{{ $index }}">
                                                @if (isset($item['type']) && $item['type'] === 'link')
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M14.828 14.828a4 4 0 000-5.656l-4-4a4 4 0 00-5.656 5.656L6.343 9.17">
                                                        </path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <select name="items[{{ $index }}][type]"
                                                class="type-select px-3 py-1.5 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="image"
                                                    {{ isset($item['type']) && $item['type'] === 'image' ? 'selected' : '' }}>
                                                    Gambar
                                                </option>
                                                <option value="link"
                                                    {{ isset($item['type']) && $item['type'] === 'link' ? 'selected' : '' }}>
                                                    Link
                                                </option>
                                            </select>
                                        </div>
                                        <button type="button"
                                            class="remove-item w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 dark:hover:text-red-400 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 transition-all opacity-0 group-hover:opacity-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="content-area pl-11">
                                        <!-- Hidden input untuk menyimpan path gambar existing -->
                                        <input type="hidden" name="items[{{ $index }}][existing_content]"
                                            class="existing-content-value"
                                            value="{{ isset($item['type']) && $item['type'] === 'image' ? ($item['content'] ?? '') : '' }}">

                                        <!-- File upload area -->
                                        <div
                                            class="file-input {{ isset($item['type']) && $item['type'] !== 'image' ? 'hidden' : '' }}">
                                            <div class="relative border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:border-indigo-300 dark:hover:border-indigo-500 transition-colors"
                                                id="drop-area-{{ $index }}">
                                                <input type="file" name="items[{{ $index }}][file]"
                                                    accept="image/*"
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 file-input-trigger"
                                                    data-index="{{ $index }}"
                                                    onchange="previewImage(this)">

                                                <div class="text-center" id="upload-placeholder-{{ $index }}"
                                                    {{ isset($item['type']) && $item['type'] === 'image' && isset($item['content']) ? 'style=display:none' : '' }}>
                                                    <svg class="mx-auto w-8 h-8 text-gray-400 dark:text-gray-500 mb-2"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                       Klik atau drag & drop gambar </p>
                                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                        Maks 5MB • jpg, png, gif, webp</p>
                                                </div>

                                                <!-- Preview container (existing image or new upload) -->
                                                <div id="image-preview-{{ $index }}"
                                                    class="{{ isset($item['type']) && $item['type'] === 'image' && isset($item['content']) ? '' : 'hidden' }} mt-2 flex justify-center">
                                                    @if (isset($item['type']) && $item['type'] === 'image' && isset($item['content']))
                                                        <div class="image-preview-container">
                                                            <img src="{{ asset('storage/' . $item['content']) }}"
                                                                alt="Current image"
                                                                class="max-h-48 rounded-lg shadow-md">
                                                            <span class="remove-preview"
                                                                onclick="removePreview(this, {{ $index }})"
                                                                title="Hapus gambar">×</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Link input -->
                                        <div
                                            class="link-input {{ isset($item['type']) && $item['type'] !== 'link' ? 'hidden' : '' }} mt-3">
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M14.828 14.828a4 4 0 000-5.656l-4-4a4 4 0 00-5.656 5.656L6.343 9.17">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <input type="url" name="items[{{ $index }}][content]"
                                                    class="w-full pl-9 pr-3 py-2 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500"
                                                    placeholder="https://example.com"
                                                    value="{{ isset($item['type']) && $item['type'] === 'link' ? ($item['content'] ?? '') : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                        <!-- Add Item Button -->
                        <div class="p-4 flex justify-center border-t border-gray-100 dark:border-gray-700">
                            <button type="button" id="add-item"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:border-indigo-200 dark:hover:border-indigo-700 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span data-translate="add" data-translate-page="add_post">Tambah Media atau Link</span>
                            </button>
                        </div>
                    </div>

                    <!-- Game Option Section -->
                    @php
                        $existingGame = $postingan->game ?? null;
                        $hasGame = $existingGame ? true : false;
                    @endphp

                    @if (Auth::user()->role !== 'mahasiswa')
                        <div
                            class="border-t border-gray-100 dark:border-gray-700 p-5 sm:p-6 bg-gray-50/30 dark:bg-gray-800/30">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white"
                                            data-translate="game_title" data-translate-page="edit_post">
                                           Tambahkan Game Interaktif</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" data-translate="game_desc"
                                            data-translate-page="edit_post">
                                            Buat postingan lebih menarik dengan game</p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-3 sm:ml-auto">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="game_enabled" id="game_enabled" value="on"
                                            class="sr-only peer"
                                            {{ old('game_enabled', $hasGame) ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600">
                                        </div>
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300"
                                            data-translate="game_enable_label"
                                            data-translate-page="edit_post">Aktifkan Game</span>
                                    </label>

                                    <select name="game_name" id="game_name"
                                        class="px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:border-purple-500 focus:ring-purple-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ old('game_enabled', $hasGame) ? '' : 'disabled' }}>
                                        <option value="Matematika"
                                            {{ old('game_name', $existingGame->game_name ?? '') === 'Matematika' ? 'selected' : '' }}>
                                            Matematika
                                        </option>
                                        <option value="TTS"
                                            {{ old('game_name', $existingGame->game_name ?? '') === 'TTS' ? 'selected' : '' }}>
                                            Teka-Teki Silang
                                        </option>
                                        <option value="Puzzle"
                                            {{ old('game_name', $existingGame->game_name ?? '') === 'Puzzle' ? 'selected' : '' }}>
                                            Puzzle
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4" id="thumbnail_container"
                                style="display: {{ old('game_enabled', $hasGame) ? 'block' : 'none' }};">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    data-translate="game_thumbnail_label"
                                    data-translate-page="edit_post">Thumbnail Game</label>

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
                                @endphp

                                @if ($gameThumbnail)
                                    <div id="current_thumbnail_container" class="mb-3 flex items-center gap-3">
                                        <img src="{{ asset('storage/' . $gameThumbnail) }}"
                                            class="h-16 w-16 object-cover rounded-lg border border-gray-200 dark:border-gray-700"
                                            alt="Current thumbnail">
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400"
                                                data-translate="current_thumbnail"
                                                data-translate-page="edit_post">Thumbnail saat ini</p>
                                            <button type="button" id="remove_thumbnail_btn"
                                                class="text-xs text-red-500 hover:text-red-700 mt-1"
                                                data-translate="remove_thumbnail"
                                                data-translate-page="edit_post">Hapus thumbnail</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="remove_thumbnail" id="remove_thumbnail" value="0">
                                @endif

                                <div class="relative">
                                    <input type="file" name="game_thumbnail" id="game_thumbnail" accept="image/*"
                                        {{ old('game_enabled', $hasGame) ? '' : 'disabled' }}
                                        class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-50 dark:file:bg-purple-900/30 file:text-purple-700 dark:file:text-purple-300 hover:file:bg-purple-100 dark:hover:file:bg-purple-800/50 file:transition file:cursor-pointer">
                                </div>
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                                    Maks 5MB • jpg, jpeg, png, gif, webp</p>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div
                        class="border-t border-gray-100 dark:border-gray-700 p-4 sm:p-5 flex items-center justify-end gap-3 bg-gray-50/30 dark:bg-gray-800/30">
                        <a href="{{ route('postingan.index') }}"
                            class="px-5 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-gray-50 dark:hover:bg-gray-700 transition-all"
                            data-translate="cancel" data-translate-page="edit_post">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-full shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span data-translate="update" data-translate-page="edit_post">Update Postingan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection