@extends('Layout.Layout')
@section('title', 'Buat Postingan Baru')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900" data-page-info="popup.create_postingan">
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
                            <p class="text-sm font-medium text-red-800 dark:text-red-300 mb-2" data-translate="input_error_title" data-translate-page="add_post">Terdapat kesalahan pada input:</p>
                            <ul class="text-sm text-red-700 dark:text-red-300 space-y-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
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
                            <textarea name="judul" id="judul" rows="1" 
                                placeholder="{{ __('Judul postingan...') }}"
                                class="create-post-textarea w-full px-0 py-2 text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-700 focus:border-indigo-500 focus:ring-0 resize-none overflow-hidden transition-colors @error('judul') border-red-500 @enderror"
                                oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"
                                data-translate-placeholder="post_title_placeholder"
                                data-translate-page="add_post">{{ old('judul') }}</textarea>
                            @error('judul')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi Input -->
                        <div class="mb-2">
                            <textarea name="deskripsi" id="deskripsi" rows="3" 
                                placeholder="{{ __('Tulis sesuatu yang menarik...') }}"
                                class="create-post-textarea w-full px-0 py-2 text-gray-700 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500 bg-transparent border-0 focus:ring-0 resize-none text-base leading-relaxed @error('deskripsi') border-red-500 @enderror"
                                oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"
                                data-translate-placeholder="write_something_interesting"
                                data-translate-page="add_post">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
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
                                <span data-translate="add_media_or_link" data-translate-page="add_post">Tambah Media atau Link</span>
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
                                        <p class="text-sm font-medium text-gray-900 dark:text-white" data-translate="game_title" data-translate-page="add_post">Tambahkan Game Interaktif</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" data-translate="game_desc" data-translate-page="add_post">Buat postingan lebih menarik dengan game</p>
                                    </div>
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-3 sm:ml-auto">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="game_enabled" id="game_enabled" value="on" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300" data-translate="enable_game" data-translate-page="add_post">Aktifkan Game</span>
                                    </label>
                                    
                                    <select name="game_name" id="game_name" disabled
                                        class="px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:border-purple-500 focus:ring-purple-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <option value="Matematika">Matematika</option>
                                        <option value="TTS">Teka-Teki Silang</option>
                                        <option value="Puzzle">Puzzle</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-4" id="thumbnail_container" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" data-translate="game_thumbnail" data-translate-page="add_post">Thumbnail Game</label>
                                <div class="relative">
                                    <input type="file" name="game_thumbnail" id="game_thumbnail" accept="image/*" disabled
                                        class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-50 dark:file:bg-purple-900/30 file:text-purple-700 dark:file:text-purple-300 hover:file:bg-purple-100 dark:hover:file:bg-purple-800/50 file:transition file:cursor-pointer">
                                </div>
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400" data-translate="max_5mb_jpg_png_gif_webp" data-translate-page="add_post">Maks 5MB • jpg, jpeg, png, gif, webp</p>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="border-t border-gray-100 dark:border-gray-700 p-4 sm:p-5 flex items-center justify-end gap-3 bg-gray-50/30 dark:bg-gray-800/30">
                        <a href="{{ url()->previous() }}" 
                           class="px-5 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-gray-50 dark:hover:bg-gray-700 transition-all"
                           data-translate="cancel" data-translate-page="add_post">
                            Batal
                        </a>
                        <button id="postingan-submit-btn" type="submit"
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
@endsection