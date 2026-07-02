{{--
    Partial: postingan/partials/post_card.blade.php
    Variables:
        $post   — Postingan model
        $isOwn  — bool, true = milik auth user sendiri
--}}
@php
    $content = $post->translated('content');
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

    $game = $post->game ?? null;
    $gameThumbnailMap = [
        'matematika'       => 'assets/game-angka.svg',
        'math'             => 'assets/game-angka.svg',
        'puzzle'           => 'assets/game-puzzle.svg',
        'tts'              => 'assets/game-tts.svg',
        'teka-teki silang' => 'assets/game-tts.svg',
    ];

    $gameThumbnail = '';
    if ($game && isset($gameThumbnailMap[strtolower($game->game_name)])) {
        $gameThumbnail = $gameThumbnailMap[strtolower($game->game_name)];
    }

    $authUser = Auth::user();
    $canEdit  = in_array($authUser->role, ['admin', 'dosen']) || $post->id_user === $authUser->id;

    // Warna aksen border berdasarkan kepemilikan
    $borderAccent = $isOwn
        ? 'border-l-4 border-l-blue-400 dark:border-l-blue-500'
        : 'border-l-4 border-l-emerald-400 dark:border-l-emerald-500';
@endphp

<div class="post-card bg-white dark:bg-gray-900 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300
            border border-gray-100 dark:border-gray-800 {{ $borderAccent }} flex flex-col h-full">

    {{-- Badge kepemilikan (hanya tampil untuk admin/dosen) --}}
    @if (in_array($authUser->role, ['admin', 'dosen']))
        <div class="px-4 pt-3 pb-0">
            @if ($isOwn)
                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full
                             bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    <span data-translate="badge_my_post" data-translate-page="post">
                        Postingan Saya
                    </span>
                </span>
            @else
                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full
                             bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v1h-3zM4.75 12.094A5.973 5.973 0 004 15v1H1v-1a3 3 0 013.75-2.906z"/>
                    </svg>
                    <span data-translate="badge_student_post" data-translate-page="post">
                        Postingan Mahasiswa
                    </span>
                </span>
            @endif
        </div>
    @endif

    {{-- Content Area --}}
    <div class="p-6 flex-1 flex flex-col cursor-pointer group"
         onclick="window.location='{{ route('postingan.show', ['id' => $post->id_postingan]) }}'">

        {{-- Author Info --}}
        <div class="flex items-center gap-3 mb-4">
            @if ($post->user->photo_profile && file_exists(public_path('storage/' . $post->user->photo_profile)))
                <img src="{{ asset('storage/' . $post->user->photo_profile) }}"
                     class="w-10 h-10 rounded-full object-cover" alt="">
            @else
                <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                    <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-sm">
                        {{ strtoupper(substr($post->user->nama_mahasiswa, 0, 1)) }}
                    </span>
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm truncate">
                    {{ $post->user->nama_mahasiswa }}
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $post->tanggal->format('d M Y') }}</p>
            </div>
        </div>

        {{-- Title --}}
        @if ($title)
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2
                       group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                {{ $title }}
            </h2>
        @endif

        {{-- Deskripsi --}}
        @if ($deskripsi)
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">
                {{ Str::limit($deskripsi, 100) }}
            </p>
        @endif

        {{-- Preview Image --}}
        @foreach ($items as $item)
            @if (isset($item['type']) && $item['type'] === 'image' && isset($item['content']))
                <div class="mb-4 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <img src="{{ asset('storage/' . $item['content']) }}"
                         class="w-full h-48 object-cover" alt="">
                </div>
                @break
            @endif
        @endforeach

        {{-- Game Card --}}
        @if ($game)
            @php
                switch (strtolower($game->game_name)) {
                    case 'matematika':
                    case 'math':
                        $gameRoute       = route('game.matematika', ['locale' => app()->getLocale()]);
                        $gameDisplayName = 'Matematika';
                        break;
                    case 'puzzle':
                        $gameRoute       = route('game.puzzle', ['locale' => app()->getLocale()]);
                        $gameDisplayName = 'Puzzle';
                        break;
                    case 'tts':
                    case 'teka-teki silang':
                        $gameRoute       = route('game.tts', ['locale' => app()->getLocale()]);
                        $gameDisplayName = 'Teka-Teki Silang';
                        break;
                    default:
                        $gameRoute       = route('game.matematika', ['locale' => app()->getLocale()]);
                        $gameDisplayName = $game->game_name;
                }
            @endphp
            <div class="mt-2 p-3 border border-gray-100 dark:border-gray-800 rounded-lg flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if ($gameThumbnail)
                        <img src="{{ asset($gameThumbnail) }}" class="w-24 h-14 object-cover rounded" alt="Game Thumbnail">
                    @else
                        <div class="w-24 h-14 bg-gray-100 dark:bg-gray-800 rounded flex items-center justify-center text-gray-500 text-xs">
                            <span data-translate="game_label" data-translate-page="post">Game</span>
                        </div>
                    @endif
                    <div>
                        <div class="font-semibold text-gray-900 dark:text-gray-100 text-sm">{{ $gameDisplayName }}</div>
                        <div class="text-xs text-gray-500" data-translate="play_game" data-translate-page="post">
                            Mainkan game
                        </div>
                        @if ($game->score > 0)
                            <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                                🏆
                                <span data-translate="best_score" data-translate-page="post">Skor terbaik</span>:
                                {{ $game->score }}
                            </div>
                        @endif
                    </div>
                </div>
                <a href="{{ $gameRoute }}?postingan={{ $post->id_postingan }}&game={{ $game->id_games }}"
                   onclick="event.stopPropagation();"
                   class="inline-flex items-center px-3 py-1.5 bg-teal-600 text-white rounded-full hover:bg-teal-700 transition-colors text-sm">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm3 4v2h2V7H8zm6 0v2h2V7h-2zm-6 6v2h2v-2H8zm6 0v2h2v-2h-2z"/>
                    </svg>
                    <span data-translate="play_btn" data-translate-page="post">Play</span>
                </a>
            </div>
        @endif
    </div>

    {{-- Stats & Action Footer --}}
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 space-y-4">
        {{-- Like & Comment Stats --}}
        <div class="flex items-center gap-4 text-sm">
            <span class="inline-flex items-center gap-1 text-gray-500 dark:text-gray-400">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                {{ $post->likes->count() }}
            </span>
            <span class="inline-flex items-center gap-1 text-gray-500 dark:text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                {{ $post->komentar->count() }}
            </span>
        </div>

        {{-- Action Buttons --}}
        @if ($canEdit)
            <div class="post-card-actions flex gap-3">
                <a href="{{ route('postingan.edit', ['id' => $post->id_postingan]) }}"
                   onclick="event.stopPropagation();"
                   class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 px-4 bg-blue-50 text-blue-700 rounded-lg
                          hover:bg-blue-100 active:bg-blue-200 transition font-medium
                          dark:bg-blue-900/30 dark:text-blue-500 dark:hover:bg-blue-900/50 dark:active:bg-blue-900/70
                          shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-7-10l3 3m0 0l-3 3m3-3H9"/>
                    </svg>
                    <span data-translate="edit_btn" data-translate-page="post">Edit</span>
                </a>
                <form action="{{ route('postingan.destroy', ['id' => $post->id_postingan]) }}"
                      method="POST"
                      class="delete-form flex-1"
                      onclick="event.stopPropagation();">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        class="delete-btn w-full inline-flex items-center justify-center gap-2 py-2.5 px-4
                               bg-red-50 text-red-700 rounded-lg hover:bg-red-100 active:bg-red-200 transition font-medium
                               dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 dark:active:bg-red-900/70
                               shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span data-translate="del" data-translate-page="post">Hapus</span>
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>