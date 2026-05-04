@extends('Layout.Layout')

@section('title', autoTranslate('Detail Postingan'))

@section('content')
<style>
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
        max-height: 600px;
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


@php
$commentCount = \App\Models\Komentar::getCommentCount($postingan->user->id_postingan);


@endphp

<div class="bg-gradient-to-b from-gray-50 to-white dark:from-gray-950 dark:to-gray-900 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <!-- Tombol Kembali -->
        <div class="mb-4">
            <button onclick="window.location.href='{{ route('dashboard') }}'"
                class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors group">
                <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="text-sm font-medium">{{ autoTranslate('Kembali ke Beranda') }}</span>
            </button>
        </div>

        <!-- Card Utama -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300 hover:shadow-2xl">
            <!-- Header Profil -->
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 bg-gradient-to-r from-white to-gray-50 dark:from-gray-900 dark:to-gray-800/50">
                <div class="flex items-start justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <!-- Avatar -->
                        <div class="relative">
                            @if($postingan->user->photo_profile && file_exists(public_path('storage/' . $postingan->user->photo_profile)))
                                <img src="{{ asset('storage/' . $postingan->user->photo_profile) }}"
                                    class="w-14 h-14 rounded-full object-cover ring-2 ring-indigo-200 dark:ring-indigo-800 shadow-md">
                            @else
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center shadow-md ring-2 ring-indigo-200 dark:ring-indigo-800">
                                    <span class="text-white font-bold text-xl">
                                        {{ strtoupper(substr($postingan->user->nama_mahasiswa, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <div>
                                <div class="gap-2 mt-1 mb-1">
                                    <a href="{{ route('portfolio.show', ['user' => $postingan->user->username]) }}"
                                                    class="font-bold text-gray-900 dark:text-gray-100 text-lg hover:text-indigo-600 dark:hover:text-indigo-400 transition">{{ $postingan->user->nama_mahasiswa }}</a>
                                    @if(!empty($postingan->user->jurusan))
                                        <span class="text-[14px] px-2 py-1 bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300 rounded-full">{{ $postingan->user->jurusan['nama_jurusan'] ?? '-' }}</span>
                                    @endif
                                    @if(!empty($postingan->user->angkatan))
                                        <span class="text-[14px] px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded-full">{{ $postingan->user->angkatan['nama_angkatan'] ?? '-' }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($postingan->tanggal)->translatedFormat('d F Y \j\a\m H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Tombol Share -->
                        <button onclick="toggleShare(this)" 
                            class="p-2 text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 rounded-full hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all duration-200"
                            title="{{ autoTranslate('Bagikan') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                        </button>
                        
                        @if(auth()->check() && auth()->id() == $postingan->id_user)
                            <div class="relative" id="postMenuContainer">
                                <button id="postMenuButton" 
                                    class="p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition focus:outline-none">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                    </svg>
                                </button>
                                <div id="postMenuDropdown" 
                                    class="hidden absolute right-0 mt-2 w-36 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 z-20 overflow-hidden">
                                    <a href="{{ route('postingan.edit', ['id' => $postingan->id_postingan]) }}"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        {{ autoTranslate('Edit') }}
                                    </a>
                                    <form action="{{ route('postingan.destroy', ['id' => $postingan->id_postingan]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="if(confirm('{{ autoTranslate('Apakah Anda yakin ingin menghapus postingan ini?') }}')) this.form.submit();"
                                            class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            {{ autoTranslate('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Konten Postingan -->
            <div class="p-6 space-y-6">
                @php
                    $content = $postingan->content;
                    $title = '';
                    $deskripsi = '';
                    $items = [];

                    if (is_array($content)) {
                        foreach ($content as $item) {
                            if (isset($item['type'])) {
                                if ($item['type'] === 'title') {
                                    $title = $item['content'] ?? '';
                                } elseif ($item['type'] === 'description') {
                                    $deskripsi = $item['content'] ?? '';
                                } else {
                                    $items[] = $item;
                                }
                            }
                        }
                    }
                    
                    $game = $postingan->game ?? null;
                    $gameThumbnailMap = [
                        'matematika' => 'assets/game-angka.svg',
                        'math'       => 'assets/game-angka.svg',
                        'puzzle'     => 'assets/game-puzzle.svg',
                        'tts'        => 'assets/game-tts.svg',
                        'teka-teki silang' => 'assets/game-tts.svg',
                    ];

                    $gameThumbnail = '';
                    if ($game && isset($gameThumbnailMap[strtolower($game->game_name)])) {
                        $gameThumbnail = $gameThumbnailMap[strtolower($game->game_name)];
                    }
                    
                    $gameRoute = '';
                    $gameDisplayName = '';
                    if ($game) {
                        switch(strtolower(trim($game->game_name))) {
                            case 'matematika':
                            case 'math':
                                $gameRoute = route('game.matematika', ['locale' => app()->getLocale()]);
                                $gameDisplayName = 'Matematika';
                                break;
                            case 'puzzle':
                                $gameRoute = route('game.puzzle', ['locale' => app()->getLocale()]);
                                $gameDisplayName = 'Puzzle';
                                break;
                            case 'tts':
                            case 'teka-teki silang':
                                $gameRoute = route('game.tts', ['locale' => app()->getLocale()]);
                                $gameDisplayName = 'Teka-Teki Silang';
                                break;
                            default:
                                $gameRoute = route('game.matematika', ['locale' => app()->getLocale()]);
                                $gameDisplayName = $game->game_name;
                        }
                    }
                @endphp

                @if($title)
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white leading-tight">
                        {{ autoTranslate($title) }}
                    </h1>
                @endif

                @if($deskripsi)
                    <div class="prose prose-sm max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                        <p>{{ autoTranslate($deskripsi) }}</p>
                    </div>
                @endif

                @if(!empty($items))
                    <div class="space-y-5">
                        @foreach($items as $item)
                            @if(isset($item['type']))
                                @if($item['type'] === 'image' && isset($item['content']))
                                    <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-md hover:shadow-lg transition-shadow">
                                        <img src="{{ asset('storage/' . $item['content']) }}" 
                                            class="w-full h-auto object-cover" alt="Gambar postingan" id="logo-zoom">
                                    </div>
                                @elseif($item['type'] === 'text' && isset($item['content']))
                                    <div class="prose prose-sm max-w-none text-gray-700 dark:text-gray-300">
                                        <p>{{ autoTranslate($item['content']) }}</p>
                                    </div>
                                @elseif($item['type'] === 'link' && isset($item['content']))
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition group">
                                        <a href="{{ $item['content'] }}" target="_blank" rel="noopener noreferrer"
                                            class="flex items-center gap-3 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium break-all">
                                            <svg class="w-5 h-5 shrink-0 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                            </svg>
                                            <span class="truncate">{{ $item['content'] }}</span>
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                @endif

                @if($game)
                    <div class="mt-6 p-5 border border-indigo-200 dark:border-indigo-800/50 rounded-2xl bg-gradient-to-r from-indigo-50/50 to-white dark:from-indigo-950/20 dark:to-gray-900 shadow-md hover:shadow-lg transition-all">
                        <div class="flex flex-col sm:flex-row items-center gap-5">
                            <div class="shrink-0">
                                @if($gameThumbnail)
                                    <img src="{{ asset($gameThumbnail) }}" 
                                        class="w-24 h-24 object-contain rounded-xl shadow-md" 
                                        alt="Game Thumbnail">
                                @else
                                    <div class="w-24 h-24 bg-indigo-100 dark:bg-indigo-900/50 rounded-xl flex items-center justify-center">
                                        <svg class="w-12 h-12 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 text-center sm:text-left">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $gameDisplayName }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ autoTranslate('Mainkan game ini dan asah kemampuanmu!') }}</p>
                                @if($game->score > 0)
                                    <div class="inline-flex items-center gap-1 mt-2 px-2 py-1 bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300 rounded-full text-xs font-semibold">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        <span>{{ autoTranslate('Skor terbaik') }}: {{ $game->score }}</span>
                                    </div>
                                @endif
                            </div>
                            @auth
                            <a href="{{ $gameRoute }}?postingan={{ $postingan->id_postingan }}&game={{ $game->id_games }}" 
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full font-medium shadow-md hover:shadow-lg transition-all transform hover:scale-105">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm3 4v2h2V7H8zm6 0v2h2V7h-2zm-6 6v2h2v-2H8zm6 0v2h2v-2h-2z"/>
                                </svg>
                                {{ autoTranslate('Mainkan Sekarang') }}
                            </a>
                            @endauth
                        </div>
                    </div>
                @endif
            </div>

            <!-- Engagement Bar -->
            <div class="px-6 py-3 border-t border-b border-gray-100 dark:border-gray-800 flex items-center justify-between text-sm">
                <div class="flex items-center gap-6">
                    <button class="like-btn flex items-center gap-2 transition-colors hover:text-red-500 {{ $postingan->likes->contains('id_user', auth()->id()) ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}" 
                        data-postingan-id="{{ $postingan->id_postingan }}">
                        <svg class="w-5 h-5 {{ $postingan->likes->contains('id_user', auth()->id()) ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="like-count-text font-medium">{{ $postingan->likes->count() }}</span>
                        <span class="hidden sm:inline">{{ autoTranslate('Suka') }}</span>
                    </button>
                    
                    <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span class="comment-total-count font-medium">{{ $postingan->komentar->count() }}</span>
                        <span class="hidden sm:inline">{{ autoTranslate('Komentar') }}</span>
                    </div>
                </div>
            </div>

            <!-- ===================== BAGIAN KOMENTAR (AJAX - sama dengan file ke-1) ===================== -->
            <div id="comments" class="px-6 py-6 space-y-6">
                <h3 class="font-bold text-xl text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    {{ autoTranslate('Komentar') }}
                    (<span class="comment-total-count-heading">{{ $commentCount }}</span>)
                </h3>

                <!-- Form Tambah Komentar (AJAX) -->
                @auth
                    <div class="mb-4">
                        <div class="flex gap-3">
                            <div class="flex-1">
                                <textarea id="comment-input-{{ $postingan->id_postingan }}" 
                                    rows="2" 
                                    class="comment-input w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none resize-none text-sm transition text-gray-900 dark:text-white placeholder-gray-500" 
                                    placeholder="{{ autoTranslate('Tulis komentar...') }}"></textarea>
                                <div class="flex justify-end mt-2">
                                    <button onclick="window.submitComment({{ $postingan->id_postingan }})" 
                                        id="submit-comment-btn-{{ $postingan->id_postingan }}"
                                        class="submit-comment-btn px-5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                        {{ autoTranslate('Kirim') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-2xl p-4 text-center">
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            {{ autoTranslate('Silakan') }}
                            <a href="{{ route('login') }}" class="font-semibold underline hover:no-underline">{{ autoTranslate('masuk') }}</a>
                            {{ autoTranslate('untuk berkomentar.') }}
                        </p>
                    </div>
                @endauth

                <!-- Daftar Komentar (AJAX Loaded) -->
                <div id="comments-container-{{ $postingan->id_postingan }}" class="comments-container space-y-4 pr-2">
                    <div class="text-center py-6 text-gray-400 text-sm">
                        <div class="comment-loading inline-block mr-2"></div>
                        <span>{{ autoTranslate('Memuat komentar...') }}</span>
                    </div>
                </div>
            </div>
            <!-- ===================== END BAGIAN KOMENTAR ===================== -->

        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    // ===================== GLOBAL CONFIG =====================
    window.currentUserId   = {{ auth()->id() ?? 'null' }};
    window.currentUserName = "{{ auth()->user()?->nama_mahasiswa ?? '' }}";
    window.currentUserPhoto = "{{ auth()->user()?->photo_profile ?? '' }}";
    window.locale = document.querySelector('html').getAttribute('lang') || 'id';
    window.commentLastUpdated = {};

    const POSTINGAN_ID = {{ $postingan->id_postingan }};

    // ===================== HELPERS =====================
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    function getAvatarHtml(user, size = 'w-8 h-8', textSize = 'text-xs') {
        if (user && user.photo_profile && user.photo_profile !== 'null' && user.photo_profile !== '') {
            const photoPath = user.photo_profile.startsWith('http') ? user.photo_profile : `/storage/${user.photo_profile}`;
            return `<img src="${photoPath}" class="${size} rounded-full object-cover flex-shrink-0" onerror="this.src='https://ui-avatars.com/api/?background=6366f1&color=fff&size=100&name=${encodeURIComponent(user.nama_mahasiswa || 'U')}'">`;
        }
        const name = user?.nama_mahasiswa || window.currentUserName || 'User';
        const initial = name.charAt(0).toUpperCase();
        return `<div class="${size} rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                    <span class="text-indigo-600 dark:text-indigo-400 ${textSize} font-semibold">${initial}</span>
                </div>`;
    }

    function getHeaders() {
        return {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        };
    }

    // ===================== LOAD COMMENTS =====================
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

    // ===================== RENDER COMMENT =====================
    function renderCommentWithReplies(comment, level, postinganId) {
        const marginLeft = Math.min(level * 28, 56);
        const isOwnComment = window.currentUserId && comment.id_user == window.currentUserId;
        const isLoggedIn = window.currentUserId !== null && window.currentUserId !== 'null';
        const userName = escapeHtml(comment.user?.nama_mahasiswa || 'User');
        const commentText = escapeHtml(comment.komentar);
        const commentId = String(comment.id_komentar);
        postinganId = String(postinganId);

        const replyText  = (window.locale === 'id') ? 'Balas'  : 'Reply';
        const editText   = (window.locale === 'id') ? 'Edit'   : 'Edit';
        const deleteText = (window.locale === 'id') ? 'Hapus'  : 'Delete';

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

    // ===================== EVENT DELEGATION =====================
    function attachCommentEventListeners(container, postinganId) {
        if (!container.hasAttribute('data-delegated')) {
            container.setAttribute('data-delegated', 'true');
            container.addEventListener('click', function(e) {
                const btn = e.target.closest('[data-action]');
                if (!btn) return;

                const commentId  = btn.getAttribute('data-comment-id');
                const pid        = btn.getAttribute('data-postingan-id');
                const action     = btn.getAttribute('data-action');

                if (action === 'reply')       window.showReplyForm(commentId, pid);
                else if (action === 'edit')   window.showEditForm(commentId, pid);
                else if (action === 'delete') window.deleteComment(commentId, pid, btn.getAttribute('data-type') || 'full');
                else if (action === 'save-edit')   window.saveEdit(commentId, pid);
                else if (action === 'cancel-edit') window.cancelEdit(commentId);
            });
        }
    }

    // ===================== SUBMIT COMMENT =====================
    window.submitComment = async function(postinganId) {
        const textarea  = document.getElementById(`comment-input-${postinganId}`);
        const commentText = textarea.value.trim();

        if (!commentText) {
            if (window.showPageInfo) window.showPageInfo('Komentar tidak boleh kosong', 'warning', 2000);
            else alert('Komentar tidak boleh kosong');
            return;
        }

        const submitBtn = document.getElementById(`submit-comment-btn-${postinganId}`);
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
                body: JSON.stringify({ id_postingan: postinganId, komentar: commentText })
            });

            const data = await response.json();

            if (data.success) {
                textarea.value = '';
                await window.loadComments(postinganId);
                updateTotalCommentCount(1);
                if (window.showPageInfo) window.showPageInfo('Komentar berhasil ditambahkan', 'success', 2000);
            } else {
                if (window.showErrorAlert) window.showErrorAlert(data.message || 'Gagal menambahkan komentar');
                else alert(data.message || 'Gagal menambahkan komentar');
            }
        } catch (error) {
            console.error('Error:', error);
            if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan: ' + error.message);
            else alert('Terjadi kesalahan: ' + error.message);
        } finally {
            if (submitBtn) {
                submitBtn.classList.remove('btn-loading');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }
    };

    // ===================== SUBMIT REPLY =====================
    async function submitReply(form, postinganId) {
        if (form.hasAttribute('data-submitting')) return;
        form.setAttribute('data-submitting', 'true');

        const textarea   = form.querySelector('textarea[name="komentar"]');
        const commentText = textarea.value.trim();
        const parentId   = form.querySelector('input[name="parent_id"]').value;

        if (!commentText) {
            if (window.showPageInfo) window.showPageInfo('Balasan tidak boleh kosong', 'warning', 2000);
            else alert('Balasan tidak boleh kosong');
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
                updateTotalCommentCount(1);
                const replyContainer = document.getElementById(`reply-form-${parentId}`);
                if (replyContainer) {
                    replyContainer.classList.add('hidden');
                    replyContainer.innerHTML = '';
                }
                if (window.showPageInfo) window.showPageInfo('Balasan berhasil ditambahkan', 'success', 2000);
            } else {
                if (window.showErrorAlert) window.showErrorAlert(data.message || 'Gagal menambahkan balasan');
                else alert(data.message || 'Gagal menambahkan balasan');
            }
        } catch (error) {
            console.error('Error:', error);
            if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan: ' + error.message);
            else alert('Terjadi kesalahan: ' + error.message);
        } finally {
            submitBtn.disabled = false;
            if (cancelBtn) cancelBtn.disabled = false;
            form.removeAttribute('data-submitting');
        }
    }

    // ===================== SHOW REPLY FORM =====================
    window.showReplyForm = function(parentCommentId, postinganId) {
        parentCommentId = String(parentCommentId);
        const replyFormContainer = document.getElementById(`reply-form-${parentCommentId}`);
        if (!replyFormContainer) return;

        const sendText        = (window.locale === 'id') ? 'Kirim'  : 'Send';
        const cancelText      = (window.locale === 'id') ? 'Batal'  : 'Cancel';
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
                    <textarea name="komentar" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm outline-none resize-none text-gray-900 dark:text-white" placeholder="${placeholderText}"></textarea>
                    <div class="flex gap-2 justify-end">
                        <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg transition-colors">${sendText}</button>
                        <button type="button" onclick="this.closest('.reply-form-container').classList.add('hidden'); this.closest('.reply-form-container').innerHTML = '';" class="px-4 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">${cancelText}</button>
                    </div>
                </div>
            </form>
        `;
        replyFormContainer.classList.remove('hidden');

        const form = replyFormContainer.querySelector('form');
        form.onsubmit = async (e) => {
            e.preventDefault();
            await submitReply(form, postinganId);
        };
    };

    // ===================== SHOW EDIT FORM =====================
    window.showEditForm = function(commentId, postinganId) {
        commentId   = String(commentId);
        postinganId = String(postinganId);

        const commentTextEl = document.getElementById(`comment-text-${commentId}`);
        if (!commentTextEl) return;

        const originalText = commentTextEl.innerText;
        const commentItem  = commentTextEl.closest('.comment-item');

        const existingEditForm = document.getElementById(`edit-form-${commentId}`);
        if (existingEditForm) {
            existingEditForm.remove();
            commentTextEl.style.display = 'block';
            const editBtn = commentItem.querySelector('.edit-comment-btn');
            if (editBtn) editBtn.style.display = 'inline-flex';
            return;
        }

        const saveText   = (window.locale === 'id') ? 'Simpan' : 'Save';
        const cancelText = (window.locale === 'id') ? 'Batal'  : 'Cancel';

        const editForm = document.createElement('div');
        editForm.className = 'edit-form mt-2';
        editForm.id = `edit-form-${commentId}`;
        editForm.innerHTML = `
            <textarea class="edit-textarea w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none resize-none text-gray-900 dark:text-white" rows="2">${escapeHtml(originalText)}</textarea>
            <div class="flex gap-2 mt-2">
                <button class="save-edit-btn px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors"
                    data-action="save-edit" data-comment-id="${commentId}" data-postingan-id="${postinganId}">${saveText}</button>
                <button class="px-3 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-400 transition-colors"
                    data-action="cancel-edit" data-comment-id="${commentId}">${cancelText}</button>
            </div>
        `;

        commentTextEl.style.display = 'none';
        commentTextEl.parentNode.insertBefore(editForm, commentTextEl.nextSibling);

        const editBtn = commentItem.querySelector('.edit-comment-btn');
        if (editBtn) editBtn.style.display = 'none';
    };

    // ===================== SAVE EDIT =====================
    window.saveEdit = async function(commentId, postinganId) {
        commentId = String(commentId);
        const editForm = document.getElementById(`edit-form-${commentId}`);
        if (!editForm) return;

        const editTextarea = editForm.querySelector('.edit-textarea');
        if (!editTextarea) return;

        const newText = editTextarea.value.trim();
        if (!newText) {
            if (window.showPageInfo) window.showPageInfo('Komentar tidak boleh kosong', 'warning', 2000);
            else alert('Komentar tidak boleh kosong');
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
                if (window.showPageInfo) window.showPageInfo('Komentar berhasil diperbarui', 'success', 2000);
            } else if (data.code === 'STALE_DATA') {
                await window.loadComments(postinganId);
                if (window.showPageInfo) window.showPageInfo('Komentar diperbarui pengguna lain. Edit ulang jika perlu.', 'warning', 3000);
            } else {
                if (window.showErrorAlert) window.showErrorAlert(data.message || 'Gagal mengupdate komentar');
                else alert(data.message || 'Gagal mengupdate komentar');
                saveBtn.innerHTML = originalBtnHtml;
                saveBtn.disabled = false;
            }
        } catch (error) {
            console.error('Error:', error);
            if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan: ' + error.message);
            else alert('Terjadi kesalahan: ' + error.message);
            saveBtn.innerHTML = originalBtnHtml;
            saveBtn.disabled = false;
        }
    };

    // ===================== CANCEL EDIT =====================
    window.cancelEdit = function(commentId) {
        commentId = String(commentId);
        const commentTextEl = document.getElementById(`comment-text-${commentId}`);
        const editForm      = document.getElementById(`edit-form-${commentId}`);
        if (!commentTextEl) return;

        const commentItem = commentTextEl.closest('.comment-item');
        commentTextEl.style.display = 'block';
        if (editForm) editForm.remove();

        const editBtn = commentItem.querySelector('.edit-comment-btn');
        if (editBtn) editBtn.style.display = 'inline-flex';
    };

    // ===================== DELETE COMMENT =====================
    window.deleteComment = async function(commentId, postinganId, type = 'full') {
        commentId   = String(commentId);
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
            const response = await fetch(
                `/${window.locale}/komentar/destroy?id=${commentId}&id_postingan=${postinganId}&type=${type}`,
                { method: 'DELETE', headers: getHeaders() }
            );

            const data = await response.json();

            if (data.success) {
                await window.loadComments(postinganId);
                updateTotalCommentCount(-1);
                if (window.showPageInfo) window.showPageInfo('Komentar berhasil dihapus', 'success', 2000);
            } else {
                if (window.showErrorAlert) window.showErrorAlert(data.message || 'Gagal menghapus komentar');
                else alert(data.message || 'Gagal menghapus komentar');
            }
        } catch (error) {
            console.error('Error:', error);
            if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan: ' + error.message);
            else alert('Terjadi kesalahan: ' + error.message);
        } finally {
            if (window.closeLoading) window.closeLoading();
        }
    };

    // ===================== UPDATE TOTAL COUNT (heading + engagement bar) =====================
    function updateTotalCommentCount(delta) {
        document.querySelectorAll('.comment-total-count, .comment-total-count-heading').forEach(el => {
            const current = parseInt(el.textContent) || 0;
            el.textContent = Math.max(0, current + delta);
        });
    }

    // ===================== SHARE =====================
    function toggleShare(btn) {
        const url = window.location.href;
        if (navigator.share) {
            navigator.share({ url }).catch(() => {});
            return;
        }
        navigator.clipboard.writeText(url).then(() => {
            const original = btn.innerHTML;
            btn.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
            setTimeout(() => { btn.innerHTML = original; }, 2000);
            if (window.showPageInfo) window.showPageInfo('Link berhasil disalin!', 'success', 1500);
        }).catch(() => alert('Gagal menyalin link'));
    }

    // ===================== POST MENU (3-dot) =====================
    const postMenuBtn       = document.getElementById('postMenuButton');
    const postMenuDropdown  = document.getElementById('postMenuDropdown');
    const postMenuContainer = document.getElementById('postMenuContainer');

    if (postMenuBtn && postMenuDropdown) {
        postMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            postMenuDropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', function(e) {
            if (postMenuContainer && !postMenuContainer.contains(e.target)) {
                postMenuDropdown.classList.add('hidden');
            }
        });
        postMenuDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // ===================== LIKE =====================
    document.addEventListener('DOMContentLoaded', function() {
        const likeBtn = document.querySelector('.like-btn');
        if (likeBtn) {
            likeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const postinganId = this.dataset.postinganId;
                fetch(`/${window.locale}/postingan/toggle-like?id=${postinganId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const icon      = likeBtn.querySelector('svg');
                        const countSpan = likeBtn.querySelector('.like-count-text');
                        if (data.liked) {
                            likeBtn.classList.add('text-red-500', 'dark:text-red-400');
                            icon.classList.add('fill-current');
                        } else {
                            likeBtn.classList.remove('text-red-500', 'dark:text-red-400');
                            icon.classList.remove('fill-current');
                        }
                        if (countSpan) countSpan.textContent = data.like_count;
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }

        // Auto-load comments on page load
        window.loadComments(POSTINGAN_ID);
    });
</script>
@endsection