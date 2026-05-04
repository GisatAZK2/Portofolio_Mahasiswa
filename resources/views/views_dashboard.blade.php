@extends('Layout.Layout')
@section('title', autoTranslate('Dashboard'))

@section('content')
    <style>
        .dashboard-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width: 1024px) {
            .dashboard-container {
                grid-template-columns: 2fr 1fr;
                gap: 1.5rem;
            }
        }

        .comment-section {
            display: none;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid rgb(229 231 235);
        }

        .dark .comment-section {
            border-top-color: rgb(55 65 81);
        }

        .post-card:hover {
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: calc(200px + 100%) 0; }
        }

        .skeleton {
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200px 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 0.5rem;
        }

        .skeleton-circle {
            border-radius: 50%;
        }

        .skeleton-text {
            height: 14px;
            margin-bottom: 8px;
        }

        .skeleton-title {
            height: 20px;
            width: 60%;
            margin-bottom: 12px;
        }

        .skeleton-image {
            width: 100%;
            height: 200px;
            border-radius: 12px;
        }

        .skeleton-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #e5e7eb;
        }

        .dark .skeleton-card {
            background: #1f2937;
            border-color: #374151;
        }

        .skeleton-pulse {
            animation: skeletonPulse 2s ease-in-out infinite;
        }

        @keyframes skeletonPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        .dark .skeleton {
            background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
            background-size: 200px 100%;
        }

        /* Comment Section Enhanced Styles */
        .comment-item {
            transition: all 0.2s ease;
            background: transparent;
        }
        
        .comment-item:hover {
            background: rgba(0, 0, 0, 0.02);
        }
        
        .dark .comment-item:hover {
            background: rgba(255, 255, 255, 0.03);
        }
        
        .replies-container {
            border-left: 2px solid #e5e7eb;
            margin-left: 28px;
            padding-left: 16px;
        }
        
        .dark .replies-container {
            border-left-color: #374151;
        }
        
        .comments-container {
            max-height: 400px;
            overflow-y: auto;
            scrollbar-width: thin;
        }
        
        .comments-container::-webkit-scrollbar {
            width: 4px;
        }
        
        .comments-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .comments-container::-webkit-scrollbar-thumb {
            background: #c7d2fe;
            border-radius: 10px;
        }
        
        .dark .comments-container::-webkit-scrollbar-track {
            background: #374151;
        }
        
        .dark .comments-container::-webkit-scrollbar-thumb {
            background: #6366f1;
        }
        
        .edit-form textarea, .reply-form-container textarea {
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }
        
        .comment-text {
            word-break: break-word;
            white-space: pre-wrap;
            line-height: 1.5;
        }
        
        /* Loading spinner */
        .comment-loading {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid #e5e7eb;
            border-top: 2px solid #6366f1;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .btn-loading {
            opacity: 0.7;
            pointer-events: none;
            position: relative;
        }
        
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 14px;
            height: 14px;
            top: 50%;
            left: 50%;
            margin-left: -7px;
            margin-top: -7px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        
        /* Comment action buttons */
        .comment-action-btn {
            transition: all 0.2s ease;
            opacity: 0.7;
        }
        
        .comment-action-btn:hover {
            opacity: 1;
            transform: translateY(-1px);
        }
        
        /* Smooth transitions */
        .reply-form-container {
            transition: all 0.2s ease;
        }
        
        .reply-form-container.hidden {
            display: none;
        }
        
        /* Comment input focus */
        .comment-input:focus, .reply-form-container textarea:focus, .edit-textarea:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        }
        
        /* Delete button hover effect */
        .delete-comment-btn:hover {
            color: #ef4444;
        }
        
        .edit-comment-btn:hover {
            color: #3b82f6;
        }
        
        .reply-btn:hover {
            color: #6366f1;
        }
    </style>

    <div class="min-h-screen dark:bg-gray-800 rounded-2xl py-4 sm:py-6 px-3 sm:px-6 lg:px-8" data-dashboard-type="me">
        <div class="max-w-7xl mx-auto">
            <div class="dashboard-container">

                <!-- LEFT COLUMN: Posts Feed -->
                <div class="feed-column">

                    <div class="mb-6 flex justify-between items-center flex-wrap gap-3">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100" data-translate="perihal_terbaru"
                            data-translate-page="dashboard">{{ autoTranslate('Postingan Terbaru') }}</h2>

                        @php
                            $hasAnyGame = $postinganTerbaru->contains(function($post) {
                                return $post->game !== null;
                            });
                        @endphp
                        
                        @if($hasAnyGame)
                            <a href="{{ route('game.leaderboard', ['locale' => app()->getLocale()]) }}" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-400 to-blue-600 hover:from-blue-500 hover:to-blue-700 text-white font-medium rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L15 8.5L22 9.5L17 14L18.5 21L12 17.5L5.5 21L7 14L2 9.5L9 8.5L12 2Z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                    <path d="M12 6L13.5 9.5L17.5 10L14.5 12.5L15.5 16.5L12 14.5L8.5 16.5L9.5 12.5L6.5 10L10.5 9.5L12 6Z" fill="currentColor"/>
                                </svg>
                                <span>{{ autoTranslate('Peringkat Permainan') }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endif
                    </div>

                    <!-- SEARCH BAR -->
                    <div class="mb-6 search-container">
                        <div class="relative">
                            <input type="text" 
                                id="searchPostinganInput" 
                                placeholder="{{ autoTranslate('Cari postingan berdasarkan judul, deskripsi, atau penulis...') }}"
                                class="search-input w-full px-4 py-3 pl-11 pr-10 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <button id="clearSearchBtn" class="search-clear-btn absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 hidden">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div id="searchResultInfo" class="mt-2 text-sm text-gray-500 dark:text-gray-400 hidden">
                            <span id="searchResultCount"></span> {{ autoTranslate('postingan ditemukan') }}
                        </div>
                    </div>

                    <!-- Postingan Mahasiswa -->
                    <div class="feed-section mb-10">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-indigo-600 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300" data-translate="ur_post"
                                data-translate-page="dashboard">{{ autoTranslate('Postingan Mahasiswa') }}</h3>
                        </div>

                        <!-- Postingan Skeleton -->
                        <div id="postingan-skeleton" class="space-y-6">
                            @for($i = 0; $i < 2; $i++)
                            <div class="skeleton-card skeleton-pulse">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="skeleton skeleton-circle w-10 h-10"></div>
                                    <div>
                                        <div class="skeleton skeleton-text w-32"></div>
                                        <div class="skeleton skeleton-text w-20"></div>
                                    </div>
                                </div>
                                <div class="skeleton skeleton-title"></div>
                                <div class="skeleton skeleton-image"></div>
                                <div class="skeleton skeleton-text w-full mt-3"></div>
                                <div class="skeleton skeleton-text w-3/4"></div>
                                <div class="flex gap-4 mt-4">
                                    <div class="skeleton skeleton-text w-16"></div>
                                    <div class="skeleton skeleton-text w-16"></div>
                                </div>
                            </div>
                            @endfor
                        </div>

                        <!-- Postingan Content -->
                        <div id="postingan-content-wrapper" class="hidden">
                            @if($postinganTerbaru->isEmpty())
                                <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                    <p class="text-gray-500 dark:text-gray-400">{{ autoTranslate('Belum ada postingan mahasiswa') }}</p>
                                </div>
                            @else
                                <div id="postingan-container" class="space-y-6">
                                    @foreach($postinganTerbaru as $post)
                                        @php
                                            $postTitle = '';
                                            $postDescription = '';
                                            $contentArray = is_array($post->content) ? $post->content : json_decode($post->content, true);
                                            if (is_array($contentArray)) {
                                                foreach ($contentArray as $item) {
                                                    if (isset($item['type'])) {
                                                        if ($item['type'] === 'title') {
                                                            $postTitle = strip_tags($item['content'] ?? '');
                                                        } elseif ($item['type'] === 'description') {
                                                            $postDescription = strip_tags($item['content'] ?? '');
                                                        }
                                                    }
                                                }
                                            }
                                            $commentCount = \App\Models\Komentar::getCommentCount($post->id_postingan);
                                        @endphp
                                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 post-card relative" 
                                            data-post-title="{{ strtolower($postTitle) }}" 
                                            data-post-description="{{ strtolower($postDescription) }}" 
                                            data-post-author="{{ strtolower($post->user->nama_mahasiswa ?? '') }} " 
                                            data-post-id="{{ $post->id_postingan }}"
                                            data-share-url="{{ route('postingan.show', ['locale' => app()->getLocale(), 'id' => $post->id_postingan]) }}">
                                            
                                            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                                <div class="flex items-center gap-3">
                                                    <a href="{{ route('portfolio.show', ['user' => $post->user->username]) }}" class="flex items-center gap-3">
                                                        @if($post->user->photo_profile && file_exists(public_path('storage/' . $post->user->photo_profile)))
                                                            <img src="{{ asset('storage/' . $post->user->photo_profile) }}" class="w-10 h-10 rounded-full object-cover">
                                                        @else
                                                            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                                                <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-sm">{{ strtoupper(substr($post->user->nama_mahasiswa ?? 'U', 0, 1)) }}</span>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100">{{ $post->user->nama_mahasiswa }}</h4>
                                                            <div class="flex flex-wrap gap-2 mt-1">
                                                                @if(!empty($post->user->jurusan))
                                                                    <span class="text-[14px] px-2 py-1 bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300 rounded-full">{{ $post->user->jurusan['nama_jurusan'] ?? '-' }}</span>
                                                                @endif
                                                                @if(!empty($post->user->angkatan))
                                                                    <span class="text-[14px] px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded-full">{{ $post->user->angkatan['nama_angkatan'] ?? '-' }}</span>
                                                                @endif
                                                            </div>
                                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $post->tanggal?->translatedFormat('d M Y') ?? $post->created_at?->translatedFormat('d M Y') }}</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="p-4 cursor-pointer" onclick="window.location.href='{{ route('postingan.show', ['locale' => app()->getLocale(), 'id' => $post->id_postingan]) }}'">
                                                @php
                                                    $content = $post->content ?? [];
                                                    $title = ''; $deskripsi = ''; $imageUrl = null;
                                                    if (is_array($content)) {
                                                        foreach ($content as $item) {
                                                            if (isset($item['type'])) {
                                                                if ($item['type'] === 'title') $title = $item['content'] ?? '';
                                                                elseif ($item['type'] === 'description') $deskripsi = $item['content'] ?? '';
                                                                elseif ($item['type'] === 'image' && !empty($item['content']) && !$imageUrl) $imageUrl = asset('storage/' . ltrim($item['content'], '/'));
                                                            }
                                                        }
                                                    }
                                                    $game = $post->game ?? null;
                                                    $gameThumbnailMap = ['matematika' => 'assets/game-angka.svg', 'math' => 'assets/game-angka.svg', 'puzzle' => 'assets/game-puzzle.svg', 'tts' => 'assets/game-tts.svg', 'teka-teki silang' => 'assets/game-tts.svg'];
                                                    $gameThumbnail = ($game && isset($gameThumbnailMap[strtolower($game->game_name)])) ? $gameThumbnailMap[strtolower($game->game_name)] : '';
                                                @endphp
                                                @if($title)<h3 class="font-semibold text-[22px] text-gray-900 dark:text-gray-100 mb-3 line-clamp-2">{{ autoTranslate($title) }}</h3>@endif
                                                @if($imageUrl)<div class="mb-4 rounded-xl overflow-hidden"><img src="{{ $imageUrl }}" class="w-full h-auto object-cover hover:scale-105 transition-transform duration-300" loading="lazy"></div>@endif
                                                @if($deskripsi)<p class="text-[20px] text-gray-600 dark:text-gray-300 line-clamp-4">{{ autoTranslate(Str::limit(strip_tags($deskripsi), 180)) }}</p>@endif

                                                @if($game)
                                                    @php
                                                        $gameRoute = ''; $gameDisplayName = $game->game_name;
                                                        switch(strtolower($game->game_name)) {
                                                            case 'matematika': case 'math': $gameRoute = route('game.matematika', ['locale' => app()->getLocale()]); $gameDisplayName = 'Matematika'; break;
                                                            case 'puzzle': $gameRoute = route('game.puzzle', ['locale' => app()->getLocale()]); $gameDisplayName = 'Puzzle'; break;
                                                            case 'tts': case 'teka-teki silang': $gameRoute = route('game.tts', ['locale' => app()->getLocale()]); $gameDisplayName = 'Teka-Teki Silang'; break;
                                                            default: $gameRoute = route('game.matematika', ['locale' => app()->getLocale()]);
                                                        }
                                                    @endphp
                                                    <div class="mt-2 p-3 border border-gray-100 dark:border-gray-800 rounded-lg flex items-center justify-between">
                                                        <div class="flex items-center gap-3">
                                                            <img src="{{ asset($gameThumbnail) }}" class="w-24 h-14 object-cover rounded">
                                                            <div>
                                                                <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $gameDisplayName }}</div>
                                                                <div class="text-xs text-gray-500">{{ autoTranslate('Mainkan game') }}</div>
                                                                @if($game->score > 0)<div class="text-xs text-green-600 dark:text-green-400 mt-1">🏆 {{ autoTranslate('Skor terbaik') }}: {{ $game->score }}</div>@endif
                                                            </div>
                                                        </div>
                                                        @auth
                                                        <a href="{{ $gameRoute }}?postingan={{ $post->id_postingan }}&game={{ $game->id_games }}" class="inline-flex items-center px-3 py-1.5 bg-teal-600 text-white rounded-full hover:bg-teal-700 transition-colors">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm3 4v2h2V7H8zm6 0v2h2V7h-2zm-6 6v2h2v-2H8zm6 0v2h2v-2h-2z"/></svg>
                                                            {{ autoTranslate('Play') }}
                                                        </a>
                                                        @endauth
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex items-center justify-between">
                                                <div class="flex items-center gap-6">
                                                    @auth
                                                        <button class="like-btn flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-red-500 transition" data-postingan-id="{{ $post->id_postingan }}">
                                                            <svg class="w-5 h-5 {{ $post->likes->where('id_user', auth()->id())->count() > 0 ? 'fill-current text-red-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                                            <span class="like-count text-sm">{{ $post->likes->count() }}</span>
                                                        </button>
                                                    @else
                                                        <button onclick="window.location.href='{{ route('login') }}'" class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                                            <span class="text-sm">{{ $post->likes->count() }}</span>
                                                        </button>
                                                    @endauth
                                                    <button class="comment-toggle flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-blue-600 transition" data-postingan-id="{{ $post->id_postingan }}">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                                        <span class="comment-count text-sm">{{ $commentCount }}</span>
                                                    </button>
                                                    <button class="share-btn flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-blue-600 transition">
                                                        <svg fill="none" class="w-5 h-5" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path d="M21.707,11.293l-8-8A.99991.99991,0,0,0,12,4V7.54492A11.01525,11.01525,0,0,0,2,18.5V20a1,1,0,0,0,1.78418.62061,11.45625,11.45625,0,0,1,7.88672-4.04932c.0498-.00635.1748-.01611.3291-.02588V20a.99991.99991,0,0,0,1.707.707l8-8A.99962.99962,0,0,0,21.707,11.293ZM14,17.58594V15.5a.99974.99974,0,0,0-1-1c-.25488,0-1.2959.04932-1.56152.085A14.00507,14.00507,0,0,0,4.05176,17.5332,9.01266,9.01266,0,0,1,13,9.5a.99974.99974,0,0,0,1-1V6.41406L19.58594,12Z"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <span onclick="window.location.href='{{ route('postingan.show', ['id' => $post->id_postingan]) }}'" class="text-xs text-gray-500 dark:text-gray-400 cursor-pointer hover:text-indigo-600 transition">{{ autoTranslate('Lihat detail') }} →</span>
                                            </div>

                                            <!-- Comment Section - Enhanced -->
                                            <div class="comment-section px-4 pb-4 bg-white dark:bg-gray-800 hidden rounded-b-xl" id="comments-{{ $post->id_postingan }}">
                                                @auth
                                                    <div class="mb-4">
                                                        <div class="flex gap-3">
                                                            <div class="flex-1">
                                                                <textarea id="comment-input-{{ $post->id_postingan }}" 
                                                                    rows="2" 
                                                                    class="comment-input w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none resize-none text-sm transition" 
                                                                    placeholder="{{ autoTranslate('Tulis komentar...') }}"
                                                                    data-translate-placeholder="Tulis komentar..."
                                                                    data-translate-page="dashboard"></textarea>
                                                                <div class="flex justify-end mt-2">
                                                                    <button onclick="window.submitComment({{ $post->id_postingan }})" 
                                                                        class="submit-comment-btn px-5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                                                        <span data-translate="kirim" data-translate-page="dashboard">{{ autoTranslate('Kirim') }}</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="text-center py-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                                            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium hover:underline">
                                                                <span data-translate="login" data-translate-page="dashboard">{{ autoTranslate('Masuk') }}</span>
                                                            </a> 
                                                            <span data-translate="untuk_berkomentar" data-translate-page="dashboard">{{ autoTranslate('untuk berkomentar') }}</span>
                                                        </p>
                                                    </div>
                                                @endauth
                                                
                                                <div id="comments-container-{{ $post->id_postingan }}" class="comments-container space-y-4 pr-2">
                                                    <div class="text-center py-6 text-gray-400 text-sm">
                                                        <div class="comment-loading inline-block mr-2"></div>
                                                        <span data-translate="memuat_komentar" data-translate-page="dashboard">{{ autoTranslate('Memuat komentar...') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="postingan-pagination" class="mt-8">
                                    {{ $postinganTerbaru->render('vendor.pagination.custom_ajax', ['groupName' => autoTranslate('postingan')]) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Project Section -->
                    <div class="feed-section mb-10">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-orange-600 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-orange-600 dark:text-orange-300">{{ autoTranslate('Project') }}</h3>
                        </div>

                        <div id="project-skeleton" class="space-y-4">
                            @for($i = 0; $i < 2; $i++)
                            <div class="skeleton-card skeleton-pulse flex gap-4">
                                <div class="skeleton w-20 h-20 rounded-lg"></div>
                                <div class="flex-1">
                                    <div class="skeleton skeleton-title w-3/4"></div>
                                    <div class="skeleton skeleton-text w-full"></div>
                                    <div class="skeleton skeleton-text w-1/2"></div>
                                </div>
                            </div>
                            @endfor
                        </div>

                        <div id="project-content-wrapper" class="hidden">
                            @if($projects->isEmpty())
                                <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                                    <p class="text-gray-500 dark:text-gray-400">{{ autoTranslate('Belum ada project') }}</p>
                                </div>
                            @else
                                <div id="project-container" class="space-y-4">
                                    @foreach($projects as $post)
                                        <div class="project-card">@include('components.card_postingan', ['post' => $post])</div>
                                    @endforeach
                                </div>
                                <div class="mt-6">
                                    {{ $projects->render('vendor.pagination.custom_ajax', ['groupName' => autoTranslate('project')]) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sertifikat Section -->
                    <div class="feed-section">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-green-600 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-green-700 dark:text-green-300">{{ autoTranslate('Sertifikat') }}</h3>
                        </div>

                        <div id="sertifikat-skeleton" class="space-y-4">
                            @for($i = 0; $i < 2; $i++)
                            <div class="skeleton-card skeleton-pulse flex gap-4">
                                <div class="skeleton w-16 h-20 rounded-lg"></div>
                                <div class="flex-1">
                                    <div class="skeleton skeleton-title w-1/2"></div>
                                    <div class="skeleton skeleton-text w-3/4"></div>
                                </div>
                            </div>
                            @endfor
                        </div>

                        <div id="sertifikat-content-wrapper" class="hidden">
                            @if($projectUsers->isEmpty())
                                <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                                    <p class="text-gray-500 dark:text-gray-400">{{ autoTranslate('Belum ada sertifikat') }}</p>
                                </div>
                            @else
                                <div id="sertifikat-container" class="space-y-4">
                                    @foreach($projectUsers as $post)
                                        <div class="sertifikat-card">@include('components.card_postingan', ['post' => $post])</div>
                                    @endforeach
                                </div>
                                <div class="mt-6">
                                    {{ $projectUsers->render('vendor.pagination.custom_ajax', ['groupName' => autoTranslate('sertifikat')]) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Sidebar -->
                <div class="sidebar-column">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-200 dark:border-gray-700 sticky top-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-purple-600 rounded-full"></div>
                            <h3 class="text-base font-semibold text-purple-700 dark:text-purple-300">Learning Corner</h3>
                        </div>

                        <div id="learning-skeleton" class="space-y-3">
                            @for($i = 0; $i < 4; $i++)
                                <div class="skeleton skeleton-text w-full"></div>
                            @endfor
                        </div>

                        <div id="learning-content-wrapper" class="hidden">
                            @if($learningCorners->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada Learning Corner</p>
                            @else
                                <div id="learning-corner-list" class="space-y-4 overflow-y-auto max-h-96">
                                    @foreach($learningCorners->take(5) as $learning)
                                        <div onclick="window.location.href='{{ route('project.show', ['id' => $learning->project_id]) }}'" class="cursor-pointer dark:border-gray-700 border hover:bg-gray-50 dark:hover:bg-gray-700 p-3 rounded-lg transition">
                                            <h4 class="text-sm dark:text-gray-100 font-medium line-clamp-2">{{ autoTranslate($learning->content[0]['content'] ?? 'Learning Content') }}</h4>
                                            <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">{{ $learning->tanggal->translatedFormat('d M Y') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-6">
                                    {{ $learningCorners->render('vendor.pagination.custom_ajax', ['groupName' => 'learning_corner']) }}
                                </div>
                            @endif
                        </div>

                        <!-- DOSEN LIST SECTION -->
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-1 h-5 bg-blue-600 rounded-full"></div>
                                <h3 class="text-base font-semibold text-blue-700 dark:text-blue-300">{{ $dosenList->count() }} {{ autoTranslate('Dosen') }}</h3>
                            </div>

                            @if($dosenList->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">{{ autoTranslate('Belum ada data dosen') }}</p>
                            @else
                                <div class="relative w-full max-w-xl mx-auto">
                                    <div class="overflow-hidden relative h-64 flex items-center justify-center">
                                        <div id="dosenCarouselTrack" class="relative w-full h-full flex items-center justify-center">
                                            @php $dosenArray = $dosenList->values()->all(); @endphp
                                            @foreach($dosenArray as $index => $dosen)
                                                <div class="dosen-card absolute transition-all duration-500 ease-in-out" data-index="{{ $index }}">
                                                    @if($dosen->photo_profile && file_exists(public_path('storage/' . $dosen->photo_profile)))
                                                        <img class="w-24 h-24 rounded-full mx-auto" src="{{ asset('storage/' . $dosen->photo_profile) }}">
                                                    @else
                                                        <img class="w-24 h-24 rounded-full mx-auto" src="https://ui-avatars.com/api/?background=045be6&color=fff&size=100&name={{ urlencode($dosen->nama_mahasiswa ?? 'D') }}">
                                                    @endif
                                                    <p class="dosen-name text-center dark:text-gray-100 mt-4 font-semibold opacity-0 transition-all duration-300">{{ $dosen->nama_mahasiswa ?? 'Dosen' }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <button id="dosenPrevBtn" class="absolute left-0 top-1/2 -translate-y-1/2 bg-white dark:bg-gray-700 p-2 rounded-full shadow z-10 text-gray-700 dark:text-gray-300">←</button>
                                    <button id="dosenNextBtn" class="absolute right-0 top-1/2 -translate-y-1/2 bg-white dark:bg-gray-700 p-2 rounded-full shadow z-10 text-gray-700 dark:text-gray-300">→</button>
                                    <div class="w-24 overflow-hidden mx-auto mt-4">
                                        <div class="flex gap-2 transition-transform duration-300" id="dosenDotsTrack">
                                            @foreach($dosenArray as $index => $dosen)
                                                <span class="dot w-3 h-3 bg-gray-300 dark:bg-gray-600 rounded-full cursor-pointer flex-shrink-0 transition-all duration-300 opacity-40 scale-90" data-index="{{ $index }}"></span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        window.currentUserId = {{ auth()->id() ?? 'null' }};
        window.currentUserPhoto = "{{ auth()->user()?->photo_profile ?? '' }}";
        window.currentUserName = "{{ auth()->user()?->nama_mahasiswa ?? '' }}";
        window.locale = document.querySelector('html').getAttribute('lang') || 'id';

        const isLoggedIn = window.currentUserId !== null && window.currentUserId !== 'null';
        
        // Helper functions
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }
        
        function getAvatarHtml(user, size = 'w-8 h-8', textSize = 'text-sm') {
            if (user && user.photo_profile && user.photo_profile !== 'null' && user.photo_profile !== '') {
                const photoPath = user.photo_profile.startsWith('http') ? user.photo_profile : `/storage/${user.photo_profile}`;
                return `<img src="${photoPath}" class="${size} rounded-full object-cover" onerror="this.src='https://ui-avatars.com/api/?background=6366f1&color=fff&size=100&name=${encodeURIComponent(user.nama_mahasiswa || 'U')}'">`;
            }
            const name = user?.nama_mahasiswa || window.currentUserName || 'User';
            const initial = name.charAt(0).toUpperCase();
            return `<div class="${size} rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                        <span class="text-indigo-600 dark:text-indigo-400 ${textSize} font-semibold">${initial}</span>
                    </div>`;
        }

        window.commentLastUpdated = {};

        window.loadComments = async function(postinganId) {
            const container = document.getElementById(`comments-container-${postinganId}`);
            if (!container) return;
            
            container.innerHTML = '<div class="text-center py-6 text-gray-400 text-sm"><div class="comment-loading inline-block mr-2"></div> Memuat komentar...</div>';
            
            try {
                const response = await fetch(`/${window.locale}/komentar?id_postingan=${postinganId}`);
                const data = await response.json();
                
                if (data && data.success === true) {
                    window.commentLastUpdated[postinganId] = data.last_updated ?? null;

                    let commentsArray = [];
                    if (data.comments && Array.isArray(data.comments)) {
                        commentsArray = data.comments;
                    } else if (data.comments && data.comments.comments && Array.isArray(data.comments.comments)) {
                        commentsArray = data.comments.comments;
                    }
                    
                    if (commentsArray.length === 0) {
                        const noCommentsText = (window.locale === 'id') 
                            ? '✨ Belum ada komentar. Jadilah yang pertama!'
                            : '✨ No comments yet. Be the first!';
                        container.innerHTML = `<p class="text-sm text-gray-500 dark:text-gray-400 text-center py-5">${noCommentsText}</p>`;
                        return;
                    }
                    
                    let html = '<div class="space-y-4">';
                    commentsArray.forEach(comment => {
                        html += renderCommentWithReplies(comment, 0, postinganId);
                    });
                    html += '</div>';
                    container.innerHTML = html;
                    
                    attachCommentEventListeners(container, postinganId);
                } else {
                    container.innerHTML = '<p class="text-center text-red-500 text-sm py-4">⚠️ Gagal memuat komentar</p>';
                }
            } catch (error) {
                console.error('Error loading comments:', error);
                container.innerHTML = '<p class="text-center text-red-500 text-sm py-4">⚠️ Gagal memuat komentar: ' + error.message + '</p>';
            }
        };
        
        // Render a single comment thread
        function renderCommentWithReplies(comment, level, postinganId) {
            const marginLeft = Math.min(level * 28, 56);
            const isOwnComment = window.currentUserId && comment.id_user == window.currentUserId;
            const isLoggedIn = window.currentUserId !== null && window.currentUserId !== 'null';
            const userName = escapeHtml(comment.user?.nama_mahasiswa || 'User');
            const commentText = escapeHtml(comment.komentar);

            const commentId = String(comment.id_komentar);
            
            const replyText = (window.locale === 'id') ? 'Balas' : 'Reply';
            const editText = (window.locale === 'id') ? 'Edit' : 'Edit';
            const deleteText = (window.locale === 'id') ? 'Hapus' : 'Delete';
            
            let html = `
                <div class="comment-item transition-all duration-200 py-2" data-comment-id="${commentId}" data-postingan-id="${postinganId}" style="margin-left: ${marginLeft}px;">
                    <div class="flex gap-3">
                        ${getAvatarHtml(comment.user, 'w-8 h-8', 'text-xs')}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-semibold text-sm text-gray-900 dark:text-gray-100">${userName}</span>
                                <span class="text-xs text-gray-500">${formatDate(comment.tanggal || comment.created_at)}</span>
                            </div>
                            <p class="comment-text text-sm text-gray-700 dark:text-gray-300 mt-1.5 leading-relaxed" id="comment-text-${commentId}">${commentText}</p>
                            <div class="flex flex-wrap gap-3 mt-2">
            `;
            
            if (isLoggedIn) {
                html += `
                                <button class="reply-btn text-xs text-indigo-500 hover:text-indigo-700 font-medium transition-colors inline-flex items-center gap-1"
                                    data-action="reply" data-comment-id="${commentId}" data-postingan-id="${postinganId}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                    ${replyText}
                                </button>
                `;
            }
            
            if (isOwnComment) {
                html += `
                                <button class="edit-comment-btn text-xs text-blue-500 hover:text-blue-700 transition-colors inline-flex items-center gap-1"
                                    data-action="edit" data-comment-id="${commentId}" data-postingan-id="${postinganId}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    ${editText}
                                </button>
                                <button class="delete-comment-btn text-xs text-red-500 hover:text-red-700 transition-colors inline-flex items-center gap-1"
                                    data-action="delete" data-comment-id="${commentId}" data-postingan-id="${postinganId}" data-type="full">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    ${deleteText}
                                </button>
                `;
            }
            
            html += `
                            </div>
                        </div>
                    </div>
                    <div id="reply-form-${commentId}" class="reply-form-container hidden mt-3 ml-11"></div>
                </div>
            `;
            
            if (comment.balasan && comment.balasan.length > 0) {
                html += `<div class="replies-container ml-4 mt-1">`;
                comment.balasan.forEach(reply => {
                    html += renderCommentWithReplies(reply, level + 1, postinganId);
                });
                html += `</div>`;
            }
            
            return html;
        }
        
        // Attach event listeners for dynamic elements
        function attachCommentEventListeners(container, postinganId) {
            if (!container.hasAttribute('data-delegated')) {
                container.setAttribute('data-delegated', 'true');
                container.addEventListener('click', function(e) {
                    const btn = e.target.closest('[data-action]');
                    if (!btn) return;

                    const commentId = btn.getAttribute('data-comment-id');
                    const pid = btn.getAttribute('data-postingan-id');
                    const action = btn.getAttribute('data-action');

                    if (action === 'reply') {
                        window.showReplyForm(commentId, pid);
                    } else if (action === 'edit') {
                        window.showEditForm(commentId, pid);
                    } else if (action === 'delete') {
                        const type = btn.getAttribute('data-type') || 'full';
                        window.deleteComment(commentId, pid, type);
                    } else if (action === 'save-edit') {
                        window.saveEdit(commentId, pid);
                    } else if (action === 'cancel-edit') {
                        window.cancelEdit(commentId);
                    }
                });
            }
        }
        
        // Helper: common fetch headers
        function getHeaders() {
            return {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            };
        }
        
        // Submit a new comment (top-level)
        window.submitComment = async function(postinganId) {
            const textarea = document.getElementById(`comment-input-${postinganId}`);
            const commentText = textarea.value.trim();
        
            if (!commentText) {
                if (window.showPageInfo) {
                    window.showPageInfo('Komentar tidak boleh kosong', 'warning', 2000);
                } else {
                    alert('Komentar tidak boleh kosong');
                }
                return;
            }
        
            const submitBtn = textarea.closest('.flex-1')?.querySelector('.submit-comment-btn');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.classList.add('btn-loading');
                submitBtn.innerHTML = 'Mengirim...';
                submitBtn.disabled = true;
            }
        
            try {
                const response = await fetch(`/${window.locale}/komentar`, {
                    method: 'POST',
                    headers: getHeaders(),
                    body: JSON.stringify({
                        id_postingan: postinganId,
                        komentar: commentText
                    })
                });
        
                const data = await response.json();
        
                if (data.success) {
                    textarea.value = '';
                    await window.loadComments(postinganId);
                    await updateCommentCount(postinganId, 1);
                    if (window.showPageInfo) {
                        window.showPageInfo('Komentar berhasil ditambahkan', 'success', 2000);
                    }
                } else {
                    if (window.showErrorAlert) {
                        window.showErrorAlert(data.message || 'Gagal menambahkan komentar');
                    } else {
                        alert(data.message || 'Gagal menambahkan komentar');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                if (window.showErrorAlert) {
                    window.showErrorAlert('Terjadi kesalahan: ' + error.message);
                } else {
                    alert('Terjadi kesalahan: ' + error.message);
                }
            } finally {
                if (submitBtn) {
                    submitBtn.classList.remove('btn-loading');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            }
        };
        
        // Submit a reply
        async function submitReply(form, postinganId) {
            if (form.hasAttribute('data-submitting')) return;
            form.setAttribute('data-submitting', 'true');

            const textarea = form.querySelector('textarea[name="komentar"]');
            const commentText = textarea.value.trim();
            const parentId = form.querySelector('input[name="parent_id"]').value;

            if (!commentText) {
                if (window.showPageInfo) {
                    window.showPageInfo('Balasan tidak boleh kosong', 'warning', 2000);
                } else {
                    alert('Balasan tidak boleh kosong');
                }
                form.removeAttribute('data-submitting');
                return;
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            const cancelBtn = form.querySelector('button[type="button"]');
            
            submitBtn.disabled = true;
            if (cancelBtn) cancelBtn.disabled = true;

            try {
                const response = await fetch(`/${window.locale}/komentar`, {
                    method: 'POST',
                    headers: getHeaders(),
                    body: JSON.stringify({
                        id_postingan: postinganId,
                        komentar: commentText,
                        parent_id: parentId,
                        reply_to_id: parentId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    await window.loadComments(postinganId);
                    await updateCommentCount(postinganId, 1);
                    const replyContainer = document.getElementById(`reply-form-${parentId}`);
                    if (replyContainer) {
                        replyContainer.classList.add('hidden');
                        replyContainer.innerHTML = '';
                    }
                    if (window.showPageInfo) {
                        window.showPageInfo('Balasan berhasil ditambahkan', 'success', 2000);
                    }
                } else {
                    if (window.showErrorAlert) {
                        window.showErrorAlert(data.message || 'Gagal menambahkan balasan');
                    } else {
                        alert(data.message || 'Gagal menambahkan balasan');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                if (window.showErrorAlert) {
                    window.showErrorAlert('Terjadi kesalahan: ' + error.message);
                } else {
                    alert('Terjadi kesalahan: ' + error.message);
                }
            } finally {
                submitBtn.disabled = false;
                if (cancelBtn) cancelBtn.disabled = false;
                form.removeAttribute('data-submitting');
            }
        }
        
        // SHOW REPLY FORM
        window.showReplyForm = function(parentCommentId, postinganId) {
            parentCommentId = String(parentCommentId);
            const replyFormContainer = document.getElementById(`reply-form-${parentCommentId}`);
            if (!replyFormContainer) return;
            
            const sendText = (window.locale === 'id') ? 'Kirim' : 'Send';
            const cancelText = (window.locale === 'id') ? 'Batal' : 'Cancel';
            const placeholderText = (window.locale === 'id') ? 'Tulis balasan...' : 'Write a reply...';
            
            if (replyFormContainer.innerHTML.trim() !== '' && !replyFormContainer.classList.contains('hidden')) {
                replyFormContainer.classList.add('hidden');
                replyFormContainer.innerHTML = '';
                return;
            }
            
            replyFormContainer.innerHTML = `
                <form class="reply-submit-form mt-3">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                    <input type="hidden" name="parent_id" value="${parentCommentId}">
                    <div class="flex flex-col gap-2">
                        <textarea name="komentar" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm outline-none resize-none" placeholder="${placeholderText}"></textarea>
                        <div class="flex gap-2 justify-end">
                            <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg transition-colors">${sendText}</button>
                            <button type="button" onclick="this.closest('.reply-form-container').classList.add('hidden'); this.closest('.reply-form-container').innerHTML = '';" class="px-4 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">${cancelText}</button>
                        </div>
                    </div>
                </form>
            `;
            replyFormContainer.classList.remove('hidden');
            
            const form = replyFormContainer.querySelector('form');
            form.onsubmit = null;
            form.onsubmit = async (e) => {
                e.preventDefault();
                await submitReply(form, postinganId);
            };
        };
        
        // Show edit form
        window.showEditForm = function(commentId, postinganId) {
            commentId = String(commentId);
            postinganId = String(postinganId);
            
            const commentTextEl = document.getElementById(`comment-text-${commentId}`);
            if (!commentTextEl) return;
            
            const originalText = commentTextEl.innerText;
            const commentItem = commentTextEl.closest('.comment-item');
            
            const existingEditForm = document.getElementById(`edit-form-${commentId}`);
            if (existingEditForm) {
                existingEditForm.remove();
                commentTextEl.style.display = 'block';
                const editBtn = commentItem.querySelector('.edit-comment-btn');
                if (editBtn) editBtn.style.display = 'inline-flex';
                return;
            }
            
            const saveText = (window.locale === 'id') ? 'Simpan' : 'Save';
            const cancelText = (window.locale === 'id') ? 'Batal' : 'Cancel';
            
            const editForm = document.createElement('div');
            editForm.className = 'edit-form mt-2';
            editForm.id = `edit-form-${commentId}`;
            
            editForm.innerHTML = `
                <textarea class="edit-textarea w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none resize-none" rows="2">${escapeHtml(originalText)}</textarea>
                <div class="flex gap-2 mt-2">
                    <button class="save-edit-btn px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors"
                        data-action="save-edit"
                        data-comment-id="${commentId}"
                        data-postingan-id="${postinganId}">${saveText}</button>
                    <button class="px-3 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-400 transition-colors"
                        data-action="cancel-edit"
                        data-comment-id="${commentId}">${cancelText}</button>
                </div>
            `;
            
            commentTextEl.style.display = 'none';
            commentTextEl.parentNode.insertBefore(editForm, commentTextEl.nextSibling);
            
            const editBtn = commentItem.querySelector('.edit-comment-btn');
            if (editBtn) editBtn.style.display = 'none';
        };
        
        // Save edit
        window.saveEdit = async function(commentId, postinganId) {
            commentId = String(commentId);
            const editForm = document.getElementById(`edit-form-${commentId}`);
            if (!editForm) return;

            const editTextarea = editForm.querySelector('.edit-textarea');
            if (!editTextarea) return;

            const newText = editTextarea.value.trim();
            if (!newText) {
                if (window.showPageInfo) {
                    window.showPageInfo('Komentar tidak boleh kosong', 'warning', 2000);
                } else {
                    alert('Komentar tidak boleh kosong');
                }
                return;
            }

            const saveBtn = editForm.querySelector('.save-edit-btn');
            if (!saveBtn) return;

            const originalBtnHtml = saveBtn.innerHTML;
            saveBtn.innerHTML = '<div class="comment-loading" style="width:14px;height:14px;"></div>';
            saveBtn.disabled = true;

            try {
                const lastUpdated = window.commentLastUpdated?.[postinganId] ?? '';
                const response = await fetch(
                    `/${window.locale}/komentar/update?id=${commentId}&id_postingan=${postinganId}&last_updated=${encodeURIComponent(lastUpdated)}`,
                    {
                        method: 'PUT',
                        headers: getHeaders(),
                        body: JSON.stringify({ komentar: newText })
                    }
                );

                const data = await response.json();

                if (data.success) {
                    await window.loadComments(postinganId);
                    if (window.showPageInfo) {
                        window.showPageInfo('Komentar berhasil diperbarui', 'success', 2000);
                    }
                } else if (data.code === 'STALE_DATA') {
                    await window.loadComments(postinganId);
                    if (window.showPageInfo) {
                        window.showPageInfo('Komentar diperbarui pengguna lain. Edit ulang jika perlu.', 'warning', 3000);
                    }
                } else {
                    if (window.showErrorAlert) {
                        window.showErrorAlert(data.message || 'Gagal mengupdate komentar');
                    } else {
                        alert(data.message || 'Gagal mengupdate komentar');
                    }
                    saveBtn.innerHTML = originalBtnHtml;
                    saveBtn.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                if (window.showErrorAlert) {
                    window.showErrorAlert('Terjadi kesalahan: ' + error.message);
                } else {
                    alert('Terjadi kesalahan: ' + error.message);
                }
                saveBtn.innerHTML = originalBtnHtml;
                saveBtn.disabled = false;
            }
        };
        
        // Cancel edit
        window.cancelEdit = function(commentId) {
            commentId = String(commentId);
            const commentTextEl = document.getElementById(`comment-text-${commentId}`);
            const editForm = document.getElementById(`edit-form-${commentId}`);
            const commentItem = commentTextEl.closest('.comment-item');
            
            commentTextEl.style.display = 'block';
            if (editForm) editForm.remove();
            
            const editBtn = commentItem.querySelector('.edit-comment-btn');
            if (editBtn) editBtn.style.display = 'inline-flex';
        };
        
        // Delete comment
        window.deleteComment = async function(commentId, postinganId, type = 'full') {
            commentId = String(commentId);
            postinganId = String(postinganId);
            
            const confirmMessageSingle = (window.locale === 'id') 
                ? 'Apakah Anda yakin ingin menghapus balasan ini saja?'
                : 'Are you sure you want to delete this reply only?';
            const confirmMessageFull = (window.locale === 'id')
                ? 'Apakah Anda yakin ingin menghapus komentar ini beserta semua balasannya?'
                : 'Are you sure you want to delete this comment and all its replies?';
            const confirmMessage = type === 'single' ? confirmMessageSingle : confirmMessageFull;
            
            if (window.showConfirm) {
                const confirmed = await window.showConfirm(confirmMessage);
                if (!confirmed) return;
            } else {
                if (!confirm(confirmMessage)) return;
            }
            
            if (window.showLoading) window.showLoading('Menghapus...');
        
            try {
                const lastUpdated = window.commentLastUpdated[postinganId] ?? '';
                const response = await fetch(
                    `/${window.locale}/komentar/destroy?id=${commentId}&id_postingan=${postinganId}&type=${type}`, {
                        method: 'DELETE',
                        headers: getHeaders(),
                    });
        
                const data = await response.json();
        
                if (data.success) {
                    await window.loadComments(postinganId);
                    await updateCommentCount(postinganId, -1);
                    if (window.showPageInfo) {
                        window.showPageInfo('Komentar berhasil dihapus', 'success', 2000);
                    }
                } else {
                    if (window.showErrorAlert) {
                        window.showErrorAlert(data.message || 'Gagal menghapus komentar');
                    } else {
                        alert(data.message || 'Gagal menghapus komentar');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                if (window.showErrorAlert) {
                    window.showErrorAlert('Terjadi kesalahan: ' + error.message);
                } else {
                    alert('Terjadi kesalahan: ' + error.message);
                }
            } finally {
                if (window.closeLoading) window.closeLoading();
            }
        };
        
        // Update comment count
        async function updateCommentCount(postinganId, delta) {
            const commentCountSpan = document.querySelector(`.comment-toggle[data-postingan-id="${postinganId}"] .comment-count`);
            if (commentCountSpan) {
                const currentCount = parseInt(commentCountSpan.innerText) || 0;
                commentCountSpan.innerText = Math.max(0, currentCount + delta);
            }
        }
        
        // Toggle comment section
        function setupCommentToggleListeners() {
            document.querySelectorAll('.comment-toggle').forEach(btn => {
                btn.removeEventListener('click', window.handleCommentToggle);
                btn.addEventListener('click', window.handleCommentToggle);
            });
        }
        
        window.handleCommentToggle = async function(event) {
            const btn = event.currentTarget;
            const postCard = btn.closest('.post-card');
            const commentSection = postCard.querySelector('.comment-section');
            const isHidden = commentSection.style.display !== 'block';
            
            commentSection.style.display = isHidden ? 'block' : 'none';
            
            if (isHidden) {
                const postinganId = btn.dataset.postinganId;
                if (postinganId) {
                    await window.loadComments(postinganId);
                }
            }
        };
        
        // Share functionality
        function setupShareListeners() {
            document.querySelectorAll('.share-btn').forEach(btn => {
                btn.removeEventListener('click', window.handleShare);
                btn.addEventListener('click', window.handleShare);
            });
        }
        
        window.handleShare = function(event) {
            const btn = event.currentTarget;
            const postCard = btn.closest('.post-card');
            if (!postCard) return;
            
            let url = postCard.dataset.shareUrl || window.location.href;
            const title = postCard.querySelector('h3')?.innerText || 'Postingan Menarik';
            
            if (navigator.share) {
                navigator.share({ title: title, url: url }).catch(() => {});
            } else {
                navigator.clipboard.writeText(url).then(() => {
                    const originalHtml = btn.innerHTML;
                    btn.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
                    setTimeout(() => { btn.innerHTML = originalHtml; }, 2000);
                    if (window.showPageInfo) {
                        window.showPageInfo('Link berhasil disalin!', 'success', 1500);
                    }
                }).catch(() => {
                    if (window.showErrorAlert) {
                        window.showErrorAlert('Gagal menyalin URL');
                    } else {
                        alert('Gagal menyalin URL');
                    }
                });
            }
        };
        
        // Like functionality
        function setupLikeListeners() {
            document.querySelectorAll('.like-btn').forEach(btn => {
                btn.removeEventListener('click', window.handleLike);
                btn.addEventListener('click', window.handleLike);
            });
        }
        
        window.handleLike = async function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            const likeBtn = event.currentTarget;
            const postinganId = likeBtn.getAttribute('data-postingan-id');
            
            try {
                const response = await fetch(`/${window.locale}/postingan/toggle-like?id=${postinganId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const countEl = likeBtn.querySelector('.like-count');
                    countEl.textContent = data.like_count;
                    const svg = likeBtn.querySelector('svg');
                    if (data.liked) {
                        likeBtn.classList.add('text-red-500');
                        svg.classList.add('fill-current', 'text-red-500');
                    } else {
                        likeBtn.classList.remove('text-red-500');
                        svg.classList.remove('fill-current', 'text-red-500');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
            }
        };
        
        // Search functionality
        function setupSearch() {
            const searchInput = document.getElementById('searchPostinganInput');
            const clearBtn = document.getElementById('clearSearchBtn');
            const searchResultInfo = document.getElementById('searchResultInfo');
            const searchResultCount = document.getElementById('searchResultCount');
            
            if (!searchInput) return;
            
            let debounceTimer;
            
            function performSearch() {
                const searchTerm = searchInput.value.trim().toLowerCase();
                const allPosts = document.querySelectorAll('#postingan-container .post-card');
                const paginationDiv = document.getElementById('postingan-pagination');
                
                if (searchTerm.length < 2) {
                    allPosts.forEach(post => post.style.display = '');
                    if (paginationDiv) paginationDiv.style.display = '';
                    searchResultInfo?.classList.add('hidden');
                    clearBtn?.classList.add('hidden');
                    const noResultsMsg = document.getElementById('noResultsMessage');
                    if (noResultsMsg) noResultsMsg.remove();
                    return;
                }
                
                clearBtn?.classList.remove('hidden');
                if (paginationDiv) paginationDiv.style.display = 'none';
                
                let visibleCount = 0;
                
                allPosts.forEach(post => {
                    const title = post.getAttribute('data-post-title') || '';
                    const description = post.getAttribute('data-post-description') || '';
                    const author = post.getAttribute('data-post-author') || '';
                    
                    const hasMatch = title.includes(searchTerm) || description.includes(searchTerm) || author.includes(searchTerm);
                    
                    if (hasMatch) {
                        post.style.display = '';
                        visibleCount++;
                        
                        if (title.includes(searchTerm) && searchTerm.length >= 2) {
                            const titleElement = post.querySelector('h3');
                            if (titleElement && titleElement.innerText) {
                                const originalTitle = titleElement.innerText;
                                const regex = new RegExp(`(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                                titleElement.innerHTML = originalTitle.replace(regex, '<mark class="search-highlight">$1</mark>');
                            }
                        }
                    } else {
                        post.style.display = 'none';
                    }
                });
                
                if (searchResultCount) searchResultCount.textContent = visibleCount;
                searchResultInfo?.classList.remove('hidden');
                
                let noResultsMsg = document.getElementById('noResultsMessage');
                if (visibleCount === 0) {
                    const container = document.getElementById('postingan-container');
                    if (container && !noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.id = 'noResultsMessage';
                        noResultsMsg.className = 'text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm no-results-animation';
                        noResultsMsg.innerHTML = `
                            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-lg font-medium mb-2">Tidak ada postingan ditemukan</p>
                            <p class="text-gray-400 dark:text-gray-500 text-sm">Coba dengan kata kunci lain</p>
                        `;
                        container.appendChild(noResultsMsg);
                    } else if (noResultsMsg) noResultsMsg.style.display = 'block';
                } else if (noResultsMsg) noResultsMsg.style.display = 'none';
            }
            
            function resetSearch() {
                searchInput.value = '';
                clearBtn?.classList.add('hidden');
                searchResultInfo?.classList.add('hidden');
                
                document.querySelectorAll('#postingan-container .post-card').forEach(post => {
                    post.style.display = '';
                    const titleElement = post.querySelector('h3');
                    if (titleElement && titleElement.innerHTML.includes('search-highlight')) {
                        titleElement.innerHTML = titleElement.innerText;
                    }
                });
                
                const paginationDiv = document.getElementById('postingan-pagination');
                if (paginationDiv) paginationDiv.style.display = '';
                
                const noResultsMsg = document.getElementById('noResultsMessage');
                if (noResultsMsg) noResultsMsg.remove();
            }
            
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(performSearch, 300);
            });
            
            if (clearBtn) clearBtn.addEventListener('click', resetSearch);
        }
        
        // Sort posts by game
        function sortPostinganByGame() {
            const container = document.getElementById('postingan-container');
            if (!container) return;
            const posts = Array.from(container.children);
            posts.sort((a, b) => {
                const aHasGame = a.querySelector('.bg-teal-600') !== null;
                const bHasGame = b.querySelector('.bg-teal-600') !== null;
                if (aHasGame && !bHasGame) return -1;
                if (!aHasGame && bHasGame) return 1;
                return 0;
            });
            container.innerHTML = '';
            posts.forEach(post => container.appendChild(post));
        }
        
        // Dosen Carousel
        function initDosenCarousel() {
            const dosenCards = document.querySelectorAll('.dosen-card');
            const dots = document.querySelectorAll('.dot');
            if (!dosenCards.length) return;
            
            let current = 0;
            const visibleDots = 4;
            const dotSize = 20;
            
            function updateCarousel() {
                dosenCards.forEach((card, i) => {
                    let offset = i - current;
                    const name = card.querySelector('.dosen-name');
                    card.style.zIndex = offset === 0 ? '3' : (Math.abs(offset) === 1 ? '2' : '1');
                    card.style.opacity = offset === 0 ? '1' : (Math.abs(offset) === 1 ? '0.6' : '0');
                    card.style.transform = offset === 0 ? 'translateX(0) scale(1)' : (offset === -1 ? 'translateX(-120px) scale(0.8)' : (offset === 1 ? 'translateX(120px) scale(0.8)' : 'translateX(0) scale(0.5)'));
                    if (name) name.style.opacity = offset === 0 ? '1' : '0';
                });
                dots.forEach((dot, i) => {
                    dot.classList.toggle('bg-blue-500', i === current);
                    dot.classList.toggle('opacity-100', i === current);
                    dot.classList.toggle('bg-gray-300', i !== current);
                    dot.classList.toggle('opacity-40', i !== current);
                });
                let offsetIndex = Math.max(0, Math.min(current - Math.floor(visibleDots / 2), dots.length - visibleDots));
                const dotsTrack = document.getElementById('dosenDotsTrack');
                if (dotsTrack) {
                    dotsTrack.style.transform = `translateX(${-(offsetIndex * dotSize)}px)`;
                }
            }
            
            const nextBtn = document.getElementById('dosenNextBtn');
            const prevBtn = document.getElementById('dosenPrevBtn');
            if (nextBtn) nextBtn.onclick = () => { current = (current + 1) % dosenCards.length; updateCarousel(); };
            if (prevBtn) prevBtn.onclick = () => { current = (current - 1 + dosenCards.length) % dosenCards.length; updateCarousel(); };
            dots.forEach(dot => { dot.onclick = () => { current = parseInt(dot.dataset.index); updateCarousel(); }; });
            
            updateCarousel();
            setInterval(() => { current = (current + 1) % dosenCards.length; updateCarousel(); }, 5000);
        }
        
        // Initialize everything when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const skeletonElements = ['postingan-skeleton', 'project-skeleton', 'sertifikat-skeleton', 'learning-skeleton'];
                skeletonElements.forEach(id => {
                    const skeleton = document.getElementById(id);
                    const wrapper = document.getElementById(id.replace('-skeleton', '-content-wrapper'));
                    if (skeleton && wrapper) {
                        skeleton.classList.add('hidden');
                        wrapper.classList.remove('hidden');
                    }
                });
                sortPostinganByGame();
                setupCommentToggleListeners();
                setupShareListeners();
                setupLikeListeners();
                setupSearch();
                initDosenCarousel();
            }, 800);
        });
    </script>
@endsection