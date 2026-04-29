@extends('Layout.Layout')
@section('title', autoTranslate('Buat Postingan Baru'))

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            
            <!-- Header Modern -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-3">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white" data-translate="ttl" data-translate-page="add_post">
                            Buat Postingan Baru
                        </h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5" data-translate="desc" data-translate-page="add_post">
                            Bagikan pemikiran, cerita, atau pengalaman Anda dengan komunitas.
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
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-red-800 dark:text-red-300 mb-2">{{ autoTranslate('Terdapat kesalahan pada input:') }}</p>
                            <ul class="text-sm text-red-700 dark:text-red-300 space-y-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ autoTranslate($error) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-r-xl">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-5 h-5 text-red-500 mt-0.5">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-red-800 dark:text-red-300 mb-2">{{ autoTranslate('Terdapat kesalahan pada input:') }}</p>
                            <ul class="text-sm text-red-700 dark:text-red-300 space-y-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ autoTranslate($error) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('postingan.store') }}" enctype="multipart/form-data">
                @csrf
                
                <!-- Main Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    
                    <!-- Content Area -->
                    <div class="p-5 sm:p-6">
                        <!-- Judul Input -->
                        <div class="mb-5">
                            <textarea name="judul" id="judul" rows="1" placeholder="{{ autoTranslate('Judul postingan...') }}"
                                class="w-full px-0 py-2 text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-700 focus:border-indigo-500 focus:ring-0 resize-none overflow-hidden transition-colors @error('judul') border-red-500 @enderror"
                                oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">{{ old('judul') }}</textarea>
                            @error('judul')
                                <p class="mt-1 text-xs text-red-500">{{ autoTranslate($message) }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi Input -->
                        <div class="mb-2">
                            <textarea name="deskripsi" id="deskripsi" rows="3" placeholder="{{ autoTranslate('Tulis sesuatu yang menarik...') }}"
                                class="w-full px-0 py-2 text-gray-700 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500 bg-transparent border-0 focus:ring-0 resize-none text-base leading-relaxed @error('deskripsi') border-red-500 @enderror"
                                oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-xs text-red-500">{{ autoTranslate($message) }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dynamic Items Section -->
                    <div class="border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        <div id="items-container" class="divide-y divide-gray-100 dark:divide-gray-700">
                            <!-- Items will be added here -->
                        </div>
                        
                        <!-- Add Item Button -->
                        <div class="p-4 flex justify-center border-t border-gray-100 dark:border-gray-700">
                            <button type="button" id="add-item"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:border-indigo-200 dark:hover:border-indigo-700 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span data-translate="add" data-translate-page="add_post">Tambah Media atau Link</span>
                            </button>
                        </div>
                    </div>

                    <!-- Game Option Section -->
                    @if (Auth::user()->role !== 'mahasiswa')
                        <div class="border-t border-gray-100 dark:border-gray-700 p-5 sm:p-6 bg-gray-50/30 dark:bg-gray-800/30">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ autoTranslate('Tambahkan Game Interaktif') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ autoTranslate('Buat postingan lebih menarik dengan game') }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-3 sm:ml-auto">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="game_enabled" id="game_enabled" value="on" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ autoTranslate('Aktifkan Game') }}</span>
                                    </label>
                                    
                                    <select name="game_name" id="game_name" disabled
                                        class="px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:border-purple-500 focus:ring-purple-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <option value="Matematika">{{ autoTranslate('Matematika') }}</option>
                                        <option value="TTS">{{ autoTranslate('Teka-Teki Silang') }}</option>
                                        <option value="Puzzle">{{ autoTranslate('Puzzle') }}</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-4" id="thumbnail_container" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ autoTranslate('Thumbnail Game') }}</label>
                                <div class="relative">
                                    <input type="file" name="game_thumbnail" id="game_thumbnail" accept="image/*" disabled
                                        class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-50 dark:file:bg-purple-900/30 file:text-purple-700 dark:file:text-purple-300 hover:file:bg-purple-100 dark:hover:file:bg-purple-800/50 file:transition file:cursor-pointer">
                                </div>
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">{{ autoTranslate('Maks 5MB • jpg, jpeg, png, gif, webp') }}</p>
                            </div>
                        </div>
                    @endif
                    <!-- Game Option Section -->
                    @if (Auth::user()->role !== 'mahasiswa')
                        <div class="border-t border-gray-100 dark:border-gray-700 p-5 sm:p-6 bg-gray-50/30 dark:bg-gray-800/30">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ autoTranslate('Tambahkan Game Interaktif') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ autoTranslate('Buat postingan lebih menarik dengan game') }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-3 sm:ml-auto">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="game_enabled" id="game_enabled" value="on" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ autoTranslate('Aktifkan Game') }}</span>
                                    </label>
                                    
                                    <select name="game_name" id="game_name" disabled
                                        class="px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:border-purple-500 focus:ring-purple-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <option value="Matematika">{{ autoTranslate('Matematika') }}</option>
                                        <option value="TTS">{{ autoTranslate('Teka-Teki Silang') }}</option>
                                        <option value="Puzzle">{{ autoTranslate('Puzzle') }}</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-4" id="thumbnail_container" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ autoTranslate('Thumbnail Game') }}</label>
                                <div class="relative">
                                    <input type="file" name="game_thumbnail" id="game_thumbnail" accept="image/*" disabled
                                        class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-50 dark:file:bg-purple-900/30 file:text-purple-700 dark:file:text-purple-300 hover:file:bg-purple-100 dark:hover:file:bg-purple-800/50 file:transition file:cursor-pointer">
                                </div>
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">{{ autoTranslate('Maks 5MB • jpg, jpeg, png, gif, webp') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="border-t border-gray-100 dark:border-gray-700 p-4 sm:p-5 flex items-center justify-end gap-3 bg-gray-50/30 dark:bg-gray-800/30">
                        <a href="{{ url()->previous() }}" 
                           class="px-5 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                            {{ autoTranslate('Batal') }}
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-full shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span data-translate="make" data-translate-page="add_post">Publikasikan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        textarea {
            overflow-y: hidden;
        }
        textarea:focus {
            outline: none;
        }
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
        .image-preview-container {
            position: relative;
            display: inline-block;
        }
        .image-preview-container img {
            max-height: 200px;
            border-radius: 8px;
            object-fit: cover;
        }
        .remove-preview {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
    </style>

    <script>
        let itemIndex = 0;

        function addItem() {
            const container = document.getElementById('items-container');
            const newItem = document.createElement('div');
            newItem.className = 'item p-5 bg-white dark:bg-gray-800 relative group';
            newItem.dataset.index = itemIndex;

            newItem.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center" id="icon-container-${itemIndex}">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <select name="items[${itemIndex}][type]" class="type-select px-3 py-1.5 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="image">{{ autoTranslate('Gambar') }}</option>
                            <option value="link">{{ autoTranslate('Link') }}</option>
                        </select>
                    </div>
                    <button type="button" class="remove-item w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 dark:hover:text-red-400 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 transition-all opacity-0 group-hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                    </div>
                    <button type="button" class="remove-item w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 dark:hover:text-red-400 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 transition-all opacity-0 group-hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="content-area pl-11">
                    <!-- Teks default -->
                    <textarea name="items[${itemIndex}][content]" rows="2" class="text-input w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500 resize-none"
                        placeholder="{{ autoTranslate('Tulis keterangan...') }}"></textarea>

                    <!-- File upload dengan preview -->
                    <div class="file-input hidden mt-3">
                        <div class="relative border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:border-indigo-300 dark:hover:border-indigo-500 transition-colors" id="drop-area-${itemIndex}">
                            <input type="file" name="items[${itemIndex}][file]" accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 file-input-trigger"
                                data-index="${itemIndex}"
                                onchange="previewImage(this)">
                            <div class="text-center" id="upload-placeholder-${itemIndex}">
                                <svg class="mx-auto w-8 h-8 text-gray-400 dark:text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ autoTranslate('Klik atau drag & drop gambar') }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ autoTranslate('Maks 5MB • jpg, png, gif, webp') }}</p>
                            </div>
                            <!-- Preview container -->
                            <div id="image-preview-${itemIndex}" class="hidden mt-2 flex justify-center"></div>
                        </div>
                    </div>

                    <!-- Link (hidden awal) -->
                    <div class="link-input hidden mt-3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 000-5.656l-4-4a4 4 0 00-5.656 5.656L6.343 9.17"></path>
                                </svg>
                            </div>
                            <input type="url" name="items[${itemIndex}][content]" 
                                class="w-full pl-9 pr-3 py-2 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="https://example.com">
                        </div>
                    </div>
                </div>
            `;

            container.appendChild(newItem);
            attachTypeListener(newItem);
            
            const textarea = newItem.querySelector('.text-input');
            textarea.addEventListener('input', function() {
                this.style.height = '';
                this.style.height = this.scrollHeight + 'px';
            });
            
            itemIndex++;
        }

        // Fungsi preview image
        function previewImage(input) {
            const index = input.dataset.index;
            const previewContainer = document.getElementById(`image-preview-${index}`);
            const uploadPlaceholder = document.getElementById(`upload-placeholder-${index}`);
            
            if (!previewContainer) return;
            
            const file = input.files[0];
            
            if (file) {
                // Validasi ukuran (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file maksimal 5MB');
                    input.value = '';
                    return;
                }
                
                // Validasi tipe file
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan jpg, jpeg, png, gif, atau webp');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewContainer.innerHTML = `
                        <div class="image-preview-container">
                            <img src="${e.target.result}" alt="Preview" class="max-h-48 rounded-lg shadow-md">
                            <span class="remove-preview" onclick="removePreview(this, ${index})" title="Hapus gambar">×</span>
                        </div>
                    `;
                    previewContainer.classList.remove('hidden');
                    if (uploadPlaceholder) uploadPlaceholder.classList.add('hidden');
                };
                
                reader.readAsDataURL(file);
            } else {
                previewContainer.innerHTML = '';
                previewContainer.classList.add('hidden');
                if (uploadPlaceholder) uploadPlaceholder.classList.remove('hidden');
            }
        }

        // Fungsi hapus preview
        function removePreview(btn, index) {
            const previewContainer = document.getElementById(`image-preview-${index}`);
            const uploadPlaceholder = document.getElementById(`upload-placeholder-${index}`);
            const fileInput = document.querySelector(`input[data-index="${index}"]`);
            
            if (fileInput) fileInput.value = '';
            if (previewContainer) {
                previewContainer.innerHTML = '';
                previewContainer.classList.add('hidden');
            }
            if (uploadPlaceholder) uploadPlaceholder.classList.remove('hidden');
        }

        function attachTypeListener(itemElement) {
            const select = itemElement.querySelector('.type-select');
            const textInput = itemElement.querySelector('.text-input');
            const fileDiv = itemElement.querySelector('.file-input');
            const linkInput = itemElement.querySelector('.link-input');
            const iconContainer = itemElement.querySelector('[id^="icon-container"]');

            function toggleFields() {
                const type = select.value;
                
                if (type === 'image') {
                    iconContainer.innerHTML = `
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    `;
                } else {
                    iconContainer.innerHTML = `
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 000-5.656l-4-4a4 4 0 00-5.656 5.656L6.343 9.17"></path>
                        </svg>
                    `;
                }
                
                textInput.classList.toggle('hidden', type !== 'image');
                fileDiv.classList.toggle('hidden', type !== 'image');
                linkInput.classList.toggle('hidden', type !== 'link');

                textInput.disabled = type !== 'image';
                linkInput.disabled = type !== 'link';
                
                
                if (type === 'image') {
                    textInput.name = `items[${itemElement.dataset.index}][content]`;
                    linkInput.name = `items[${itemElement.dataset.index}][dummy]`;
                } else if (type === 'link') {
                    textInput.name = `items[${itemElement.dataset.index}][dummy]`;
                    linkInput.name = `items[${itemElement.dataset.index}][content]`;
                }
            }

            select.addEventListener('change', toggleFields);
            toggleFields();
        }

        document.addEventListener('DOMContentLoaded', function() {
            addItem();
            
            const judul = document.getElementById('judul');
            const deskripsi = document.getElementById('deskripsi');
            
            if (judul) {
                judul.style.height = '';
                judul.style.height = judul.scrollHeight + 'px';
                judul.addEventListener('input', function() {
                    this.style.height = '';
                    this.style.height = this.scrollHeight + 'px';
                });
            }
            
            if (deskripsi) {
                deskripsi.style.height = '';
                deskripsi.style.height = deskripsi.scrollHeight + 'px';
                deskripsi.addEventListener('input', function() {
                    this.style.height = '';
                    this.style.height = this.scrollHeight + 'px';
                });
            }
            
            const gameEnabled = document.getElementById('game_enabled');
            const gameName = document.getElementById('game_name');
            const gameThumbnail = document.getElementById('game_thumbnail');
            const thumbnailContainer = document.getElementById('thumbnail_container');
            
            if (gameEnabled) {
                gameEnabled.addEventListener('change', function() {
                    gameName.disabled = !this.checked;
                    gameThumbnail.disabled = !this.checked;
                    thumbnailContainer.style.display = this.checked ? 'block' : 'none';
                });
            }
        });

        document.getElementById('add-item').addEventListener('click', addItem);

        document.addEventListener('click', (e) => {
            if (e.target.closest('.remove-item')) {
                const item = e.target.closest('.item');
                item.style.opacity = '0';
                item.style.transform = 'translateY(-10px)';
                setTimeout(() => item.remove(), 150);
            }
        });
    </script>
@endsection