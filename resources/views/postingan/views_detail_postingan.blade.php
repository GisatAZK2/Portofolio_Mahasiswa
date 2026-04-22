@extends('Layout.Layout')
@section('title', autoTranslate('Detail Postingan'))

@section('content')
    <div class="bg-gray-50 dark:bg-gray-950 py-8">
        <div class="mx-auto px-4">

            <!-- Main Post Card -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm overflow-hidden">
                <!-- Post Header -->
                <div class="p-5 border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            @if($postingan->user->photo_profile && file_exists(public_path('storage/' . $postingan->user->photo_profile)))
                                <img src="{{ asset('storage/' . $postingan->user->photo_profile) }}"
                                    class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-linear-to-br from-indigo-400 to-indigo-600 flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">
                                        {{ strtoupper(substr($postingan->user->nama_mahasiswa, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $postingan->user->nama_mahasiswa }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $postingan->tanggal->format('d M Y') }}</p>
                            </div>
                        </div>

                         <!-- Tombol Back -->
                        <button onclick="window.location.href='{{ route('dashboard') }}'"
                            class="inline-flex items-center gap-2 px-3 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="text-sm font-medium">Back</span>
                        </button>

                        <!-- Action Dropdown -->
                        @if(auth()->check() && auth()->id() == $postingan->id_user)
                            <div class="relative group">
                                <button class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                    </svg>
                                </button>
                                <div class="hidden group-hover:block absolute right-0 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-10 min-w-[120px]">
                                    <a href="{{ route('postingan.edit', ['id' => $postingan->id_postingan]) }}"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        {{ autoTranslate('Edit') }}
                                    </a>
                                    <form action="{{ route('postingan.destroy', ['id' => $postingan->id_postingan]) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="if(confirm('{{ autoTranslate('Apakah Anda yakin?') }}')) this.form.submit();"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            {{ autoTranslate('Hapus') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Post Content -->
                <div class="p-5">
                    @php
                        $content = $postingan->content;
                        $title = '';
                        $deskripsi = '';
                        $items = [];
                        $gameThumbnail = null;

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
                            'math' => 'assets/game-angka.svg',
                            'puzzle' => 'assets/game-puzzle.svg',
                            'tts' => 'assets/game-tts.svg',
                            'teka-teki silang' => 'assets/game-tts.svg',
                        ];

                        // Ambil thumbnail berdasarkan nama game
                        $gameThumbnail = '';
                        if ($game && isset($gameThumbnailMap[strtolower($game->game_name)])) {
                            $gameThumbnail = $gameThumbnailMap[strtolower($game->game_name)];
                        }
                        
                        // Tentukan route berdasarkan nama game
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

                    <!-- Title -->
                    @if($title)
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">{{ autoTranslate($title) }}</h1>
                    @endif

                    <!-- Description -->
                    @if($deskripsi)
                        <p class="text-gray-700 dark:text-gray-300 text-base leading-relaxed mb-4 whitespace-pre-wrap">{{ autoTranslate($deskripsi) }}</p>
                    @endif

                    <!-- Items (Images, Links, etc) -->
                    @if(!empty($items))
                        <div class="space-y-4 mt-4">
                            @foreach($items as $item)
                                @if(isset($item['type']))
                                    @if($item['type'] === 'image' && isset($item['content']))
                                        <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                                            <img src="{{ asset('storage/' . $item['content']) }}"
                                                class="w-full h-auto" alt="Postingan image">
                                        </div>
                                    @elseif($item['type'] === 'text' && isset($item['content']))
                                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ autoTranslate($item['content']) }}</p>
                                    @elseif($item['type'] === 'link' && isset($item['content']))
                                        <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                            <a href="{{ $item['content'] }}" target="_blank" rel="noopener noreferrer"
                                                class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium break-all">
                                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                </svg>
                                                {{ $item['content'] }}
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    @endif

                    {{-- Game Play Block (if post has associated game) --}}
                    @if($game)
                        <div class="mt-6 p-4 border border-gray-200 dark:border-gray-700 rounded-xl bg-gradient-to-r from-gray-50 to-white dark:from-gray-800/50 dark:to-gray-900/50">
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <div class="flex items-center gap-4">
                                        <img src="{{ asset($gameThumbnail) }}" 
                                            class="w-16 h-16 object-cover rounded-lg shadow-md" 
                                            alt="Game Thumbnail">
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-gray-100 text-lg">{{ $gameDisplayName }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ autoTranslate('Mainkan game dan raih skor tertinggi!') }}</div>
                                        @if($game->score > 0)
                                            <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                                                🏆 {{ autoTranslate('Skor terbaik Anda') }}: {{ $game->score }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ $gameRoute }}?postingan={{ $postingan->id_postingan }}&game={{ $game->id_games }}" 
                                    class="inline-flex items-center px-5 py-2.5 bg-teal-600 text-white rounded-full hover:bg-teal-700 transition-all transform hover:scale-105 shadow-md">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm3 4v2h2V7H8zm6 0v2h2V7h-2zm-6 6v2h2v-2H8zm6 0v2h2v-2h-2z"/>
                                    </svg>
                                    {{ autoTranslate('Mainkan Sekarang') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Engagement Stats -->
                <div class="px-5 py-3 border-t border-b border-gray-100 dark:border-gray-800 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-4">
                        <button class="like-btn flex items-center gap-1 transition-colors hover:text-red-500 {{ $postingan->likes->contains('id_user', auth()->id()) ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}" 
                            data-postingan-id="{{ $postingan->id_postingan }}">
                            <svg class="w-5 h-5 {{ $postingan->likes->contains('id_user', auth()->id()) ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span class="like-count-text">{{ $postingan->likes->count() }}</span>
                        </button>
                        <span class="flex items-center gap-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            {{ $postingan->komentar->count() }}
                        </span>
                    </div>
                </div>

                <!-- Comments Section -->
                <div id="comments" class="px-5 py-5 space-y-5">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-lg mb-5">
                        <span data-translate="comment" data-translate-page="post">Komentar</span> ({{ $postingan->komentar->count() }})
                    </h3>

                    <!-- Add Comment Form -->
                    @auth
                        <form action="{{ route('komentar.store') }}" method="POST" class="mb-6">
                            @csrf
                            <input type="hidden" name="id_postingan" value="{{ $postingan->id_postingan }}">

                            <div class="flex items-start gap-3">
                                @if(auth()->user()->photo_profile && file_exists(public_path('storage/' . auth()->user()->photo_profile)))
                                    <img src="{{ asset('storage/' . auth()->user()->photo_profile) }}"
                                        class="w-8 h-8 rounded-full object-cover shrink-0">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-linear-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shrink-0">
                                        <span class="text-white font-semibold text-xs">
                                            {{ strtoupper(substr(auth()->user()->nama_mahasiswa, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <textarea name="komentar" rows="2"
                                        class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-800 border border-transparent dark:border-gray-700 text-gray-900 dark:text-white rounded-full focus:bg-white dark:focus:bg-gray-900 focus:border-gray-300 dark:focus:border-gray-600 outline-none transition placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="{{ autoTranslate('Tulis komentar...') }}">{{ old('komentar') }}</textarea>
                                    @error('komentar')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                    <div class="flex justify-end gap-2 mt-2">
                                        <button type="reset" data-translate="cancel" data-translate-page="post"
                                            class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition">
                                            {{ autoTranslate('Batal') }}
                                        </button>
                                        <button type="submit" data-translate="send" data-translate-page="post"
                                            class="px-4 py-1.5 text-sm bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition font-medium">
                                            {{ autoTranslate('Kirim') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/30 rounded-lg p-4 text-center">
                            <p class="text-sm text-blue-900 dark:text-blue-300">
                                <a href="{{ route('login') }}" class="font-semibold hover:underline" data-translate="signin" data-translate-page="post">{{ autoTranslate('Masuk') }}</a>
                                <span data-translate="for" data-translate-page="post">{{ autoTranslate('untuk menambahkan komentar.') }}</span>
                            </p>
                        </div>
                    @endauth

                    <!-- Comments List -->
                    @if($postingan->komentar->count() > 0)
                        <div class="space-y-3 mt-6">
                            @foreach($postingan->komentar->sortByDesc('tanggal') as $komentar)
                                <div class="group flex items-start gap-2.5 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                    <!-- Avatar -->
                                    @if($komentar->user->photo_profile && file_exists(public_path('storage/' . $komentar->user->photo_profile)))
                                        <img src="{{ asset('storage/' . $komentar->user->photo_profile) }}"
                                            class="w-8 h-8 rounded-full object-cover shrink-0">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-linear-to-br from-blue-400 to-blue-600 flex items-center justify-center shrink-0">
                                            <span class="text-white font-semibold text-xs">
                                                {{ strtoupper(substr($komentar->user->nama_mahasiswa, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <!-- Comment Bubble -->
                                        <div class="bg-gray-100 dark:bg-gray-800 rounded-xl px-4 py-2.5">
                                            <div class="flex items-baseline gap-2 mb-1">
                                                <h4 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">{{ $komentar->user->nama_mahasiswa }}</h4>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $komentar->tanggal->format('d M Y') }}</span>
                                            </div>
                                            <div id="comment-content-{{ $komentar->id_komentar }}" class="text-sm text-gray-700 dark:text-gray-300 break-words">
                                                {{ autoTranslate($komentar->komentar) }}
                                            </div>

                                            <!-- Edit Form (hidden by default) -->
                                            <form id="edit-form-{{ $komentar->id_komentar }}" action="{{ route('komentar.update', ['id' => $komentar->id_komentar]) }}" method="POST" class="hidden mt-2">
                                                @csrf 
                                                @method('PUT')
                                                <textarea name="komentar" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm outline-none focus:border-indigo-500">{{ $komentar->komentar }}</textarea>
                                                <div class="flex justify-end gap-2 mt-2">
                                                    <button type="button" onclick="cancelEdit({{ $komentar->id_komentar }})" 
                                                        class="px-3 py-1 text-xs text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">
                                                        {{ autoTranslate('Batal') }}
                                                    </button>
                                                    <button type="submit" 
                                                        class="px-3 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                                        {{ autoTranslate('Simpan') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Comment Actions -->
                                        @auth
                                            @if(auth()->id() == $komentar->id_user)
                                                <div class="flex items-center gap-3 mt-1 opacity-0 group-hover:opacity-100 transition">
                                                    <button onclick="editComment({{ $komentar->id_komentar }})" 
                                                        class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                                                        {{ autoTranslate('Edit') }}
                                                    </button>
                                                    <span class="text-gray-300 dark:text-gray-600">•</span>
                                                    <form action="{{ route('komentar.destroy', ['id' => $komentar->id_komentar]) }}" method="POST" class="inline">
                                                        @csrf 
                                                        @method('DELETE')
                                                        <button type="button" onclick="if(confirm('{{ autoTranslate('Hapus komentar ini?') }}')) this.form.submit();" 
                                                            class="text-xs text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium">
                                                            {{ autoTranslate('Hapus') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">{{ autoTranslate('Belum ada komentar') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- JavaScript -->
    <script>
        function editComment(commentId) {
            document.getElementById('comment-content-' + commentId).classList.add('hidden');
            document.getElementById('edit-form-' + commentId).classList.remove('hidden');
        }

        function cancelEdit(commentId) {
            document.getElementById('comment-content-' + commentId).classList.remove('hidden');
            document.getElementById('edit-form-' + commentId).classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Handle like button
            const likeBtn = document.querySelector('.like-btn');
            if (likeBtn) {
                likeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const postinganId = this.dataset.postinganId;
                    const likeButton = this;
                    const locale = document.querySelector('html').getAttribute('lang') || 'id';

                    fetch(`/${locale}/postingan/toggle-like?id=${postinganId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const heartIcon = likeButton.querySelector('svg');
                            const countSpan = likeButton.querySelector('.like-count-text');
                            
                            if (data.liked) {
                                likeButton.classList.add('text-red-500', 'dark:text-red-400');
                                heartIcon.classList.add('fill-current');
                            } else {
                                likeButton.classList.remove('text-red-500', 'dark:text-red-400');
                                heartIcon.classList.remove('fill-current');
                            }
                            
                            if (countSpan) {
                                countSpan.textContent = data.like_count;
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            }
        });
    </script>
@endsection