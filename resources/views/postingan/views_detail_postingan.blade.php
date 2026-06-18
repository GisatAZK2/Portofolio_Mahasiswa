@extends('Layout.Layout')

@section('title', 'Detail Postingan')

@section('content')
<div id="postingan-detail-container"
     data-user-id="{{ auth()->id() ?? 'null' }}"
     data-user-name="{{ auth()->user()?->nama_mahasiswa ?? '' }}"
     data-user-photo="{{ auth()->user()?->photo_profile ?? '' }}"
     data-locale="{{ app()->getLocale() }}"
     data-postingan-id="{{ $postingan->id_postingan }}"
     data-post-url="{{ url()->current() }}"
     data-post-user-name="{{ $postingan->user->nama_mahasiswa }}">

    @php
        $commentCount = \App\Models\Komentar::getCommentCount($postingan->id_postingan);
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
                    <span class="text-sm font-medium" data-translate="back_to_home" data-translate-page="post">Kembali ke Beranda</span>
                </button>
            </div>

            <!-- Card Utama -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300 hover:shadow-2xl">
                <!-- Header Profil -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-800 bg-gradient-to-r from-white to-gray-50 dark:from-gray-900 dark:to-gray-800/50">
                    <div class="flex items-start justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <img src="{{ asset('storage/' . $postingan->user->photo_profile) }}"
                                     class="w-14 h-14 rounded-full object-cover ring-2 ring-indigo-200 dark:ring-indigo-800 shadow-md"
                                     alt="{{ $postingan->user->nama_mahasiswa }}"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 items-center justify-center shadow-md ring-2 ring-indigo-200 dark:ring-indigo-800 hidden">
                                    <span class="text-white font-bold text-xl">
                                        {{ strtoupper(substr($postingan->user->nama_mahasiswa ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    <a href="{{ route('portfolio.show', ['user' => $postingan->user->username]) }}"
                                       class="font-bold text-gray-900 dark:text-gray-100 text-lg hover:text-indigo-600 dark:hover:text-indigo-400 transition">{{ $postingan->user->nama_mahasiswa }}</a>
                                    @if(!empty($postingan->user->jurusan))
                                        <span class="text-[14px] px-2 py-1 bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300 rounded-full">{{ $postingan->user->jurusan['nama_jurusan'] ?? '-' }}</span>
                                    @endif
                                    @if(!empty($postingan->user->angkatan))
                                        <span class="text-[14px] px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded-full">{{ $postingan->user->angkatan['nama_angkatan'] ?? '-' }}</span>
                                    @endif
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
                            <button onclick="window.toggleShare(this)"
                                    class="p-2 text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 rounded-full hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all duration-200"
                                    title="{{ __('Bagikan') }}" data-translate="share" data-translate-page="post">
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
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                                           data-translate="edit_btn" data-translate-page="post">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('postingan.destroy', ['id' => $postingan->id_postingan]) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="if(confirm('Apakah Anda yakin ingin menghapus postingan ini?')) this.form.submit();"
                                                    class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                                                    data-translate="del" data-translate-page="post">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
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
                        $content = $postingan->translated('content');
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
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white leading-tight">{{ $title }}</h1>
                    @endif

                    @if($deskripsi)
                        <div class="prose prose-sm max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                            <p>{{ $deskripsi }}</p>
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
                                            <p>{{ $item['content'] }}</p>
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
                                        <img src="{{ asset($gameThumbnail) }}" class="w-24 h-24 object-contain rounded-xl shadow-md" alt="Game Thumbnail">
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
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1" data-translate="play_this_game" data-translate-page="post">Mainkan game ini dan asah kemampuanmu!</p>
                                    @if($game->score > 0)
                                        <div class="inline-flex items-center gap-1 mt-2 px-2 py-1 bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300 rounded-full text-xs font-semibold">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            <span data-translate="best_score" data-translate-page="post">Skor terbaik:</span> {{ $game->score }}
                                        </div>
                                    @endif
                                </div>
                                @auth
                                <a href="{{ $gameRoute }}?postingan={{ $postingan->id_postingan }}&game={{ $game->id_games }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full font-medium shadow-md hover:shadow-lg transition-all transform hover:scale-105"
                                   data-translate="play_now" data-translate-page="post">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm3 4v2h2V7H8zm6 0v2h2V7h-2zm-6 6v2h2v-2H8zm6 0v2h2v-2h-2z"/>
                                    </svg>
                                    Mainkan Sekarang
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
                            <span class="hidden sm:inline" data-translate="like" data-translate-page="post">Suka</span>
                        </button>

                        <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span class="comment-total-count font-medium">{{ $commentCount }}</span>
                            <span class="hidden sm:inline" data-translate="comments" data-translate-page="post">Komentar</span>
                        </div>
                    </div>
                </div>

                <!-- Komentar -->
                <div id="comments" class="px-6 py-6 space-y-6">
                    <h3 class="font-bold text-xl text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span data-translate="comments" data-translate-page="post">Komentar</span>
                        (<span class="comment-total-count-heading">{{ $commentCount }}</span>)
                    </h3>

                    @auth
                        <div class="mb-4">
                            <div class="flex gap-3">
                                <div class="flex-1">
                                    <textarea id="comment-input-{{ $postingan->id_postingan }}"
                                              rows="2"
                                              class="comment-input w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none resize-none text-sm transition text-gray-900 dark:text-white placeholder-gray-500"
                                              placeholder="{{ __('Tulis komentar...') }}"
                                              data-translate-placeholder="write_a_comment"
                                              data-translate-page="post"></textarea>
                                    <div class="flex justify-end mt-2">
                                        <button onclick="window.submitComment({{ $postingan->id_postingan }})"
                                                id="submit-comment-btn-{{ $postingan->id_postingan }}"
                                                class="submit-comment-btn px-5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow"
                                                data-translate="send" data-translate-page="post">
                                            Kirim
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-2xl p-4 text-center">
                            <p class="text-sm text-blue-800 dark:text-blue-300" data-translate="login_to_comment" data-translate-page="post">
                                Silakan <a href="{{ route('login') }}" class="font-semibold underline hover:no-underline">masuk</a> untuk berkomentar.
                            </p>
                        </div>
                    @endauth

                    <div id="comments-container-{{ $postingan->id_postingan }}" class="comments-container space-y-4 pr-2">
                        <div class="text-center py-6 text-gray-400 text-sm">
                            <div class="comment-loading inline-block mr-2"></div>
                            <span data-translate="loading_comments" data-translate-page="post">Memuat komentar...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection