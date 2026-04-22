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
    </style>

    <div class="min-h-screen dark:bg-gray-800 rounded-2xl py-4 sm:py-6 px-3 sm:px-6 lg:px-8" data-dashboard-type="me">
        <div class="max-w-7xl mx-auto">
            <div class="dashboard-container">

                <!-- LEFT COLUMN: Posts Feed -->
                <div class="feed-column">

                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100" data-translate="perihal_terbaru"
                            data-translate-page="dashboard">{{ autoTranslate('Postingan Terbaru') }}</h2>
                    </div>

                    <!-- Postingan Mahasiswa -->
                    <div class="feed-section mb-10">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-indigo-600 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300" data-translate="ur_post"
                                data-translate-page="dashboard">
                                {{ autoTranslate('Postingan Mahasiswa') }}
                            </h3>
                        </div>

                        @if($postinganTerbaru->isEmpty())
                            <div
                                class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                <p class="text-gray-500 dark:text-gray-400" data-translate="empty_post"
                                    data-translate-page="dashboard">
                                    {{ autoTranslate('Belum ada postingan mahasiswa') }}
                                </p>
                            </div>
                        @else
                            <div id="postingan-container" class="space-y-6">
                                @foreach($postinganTerbaru as $post)
                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 post-card">

                                        <!-- Header -->
                                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center gap-3">
                                                @if($post->user->photo_profile && file_exists(public_path('storage/' . $post->user->photo_profile)))
                                                    <img src="{{ asset('storage/' . $post->user->photo_profile) }}"
                                                        class="w-10 h-10 rounded-full object-cover"
                                                        alt="{{ autoTranslate('Foto Profil') }}">
                                                @else
                                                    <div
                                                        class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                                        <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-sm">
                                                            {{ strtoupper(substr($post->user->nama_mahasiswa ?? 'U', 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100">
                                                        {{ autoTranslate($post->user->nama_mahasiswa) }}</h4>
                                                    <!-- Badge Jurusan & Angkatan -->
                                                    <div class="flex flex-wrap gap-2 mt-1">
                                                        @if(!empty($post->user->jurusan))
                                                            <span
                                                                class="text-[14px] px-2 py-1 bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300 rounded-full">
                                                                {{ $post->user->jurusan['nama_jurusan'] ?? '-' }}
                                                            </span>
                                                        @endif

                                                        @if(!empty($post->user->angkatan))
                                                            <span
                                                                class="text-[14px] px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded-full">
                                                                {{ $post->user->angkatan['nama_angkatan'] ?? '-' }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $post->tanggal?->translatedFormat('d M Y') ?? $post->created_at?->translatedFormat('d M Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Content + Gambar -->
                                        <div class="p-4 cursor-pointer"
                                            onclick="window.location.href='{{ route('postingan.show', ['locale' => app()->getLocale(), 'id' => $post->id_postingan]) }}'">
                                            @php
                                                $content = $post->content ?? [];
                                                $title = '';
                                                $deskripsi = '';
                                                $imageUrl = null;

                                                $gameThumbnail = null;
                                                if (is_array($content)) {
                                                    foreach ($content as $item) {
                                                        if (isset($item['type'])) {
                                                            if ($item['type'] === 'title') {
                                                                $title = $item['content'] ?? '';
                                                            } elseif ($item['type'] === 'description') {
                                                                $deskripsi = $item['content'] ?? '';
                                                            } elseif ($item['type'] === 'image' && !empty($item['content']) && !$imageUrl) {
                                                                $imageUrl = asset('storage/' . ltrim($item['content'], '/'));
                                                            } elseif ($item['type'] === 'game_thumbnail' && !empty($item['content']) && !$gameThumbnail) {
                                                                $gameThumbnail = ltrim($item['content'], '/');
                                                            }
                                                        }
                                                    }
                                                }
                                                $game = $post->game ?? null;
                                            @endphp

                                            @if($title)
                                                <h3
                                                    class="font-semibold text-[22px] text-gray-900 dark:text-gray-100 mb-3 line-clamp-2">
                                                    {{ autoTranslate($title) }}</h3>
                                            @endif

                                            <!-- Gambar Preview -->
                                            @if($imageUrl)
                                                <div
                                                    class="mb-4 rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 h-auto shadow-sm">
                                                    <img src="{{ $imageUrl }}" alt="{{ autoTranslate('Gambar postingan') }}"
                                                        id="logo-zoom"
                                                        class="w-full h-auto object-cover hover:scale-105 transition-transform duration-300"
                                                        loading="lazy"
                                                        onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'h-64 bg-gray-100 dark:bg-gray-800 flex items-center justify-center\'><span class=\'text-gray-400\'>{{ autoTranslate('Gambar tidak dapat dimuat') }}</span></div>'">
                                                </div>
                                            @endif

                                            @if($deskripsi)
                                                <p class="text-[20px] text-gray-600 dark:text-gray-300 line-clamp-4 leading-relaxed">
                                                    {{ autoTranslate(Str::limit(strip_tags($deskripsi), 180)) }}
                                                </p>
                                            @endif
                                         {{-- Game preview (card) --}}
@if($game)
    @php
        // Tentukan route berdasarkan nama game
        $gameRoute = '';
        $gameDisplayName = $game->game_name;
        
        switch(strtolower($game->game_name)) {
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
    @endphp
    
    <div class="mt-2 p-3 border border-gray-100 dark:border-gray-800 rounded-lg flex items-center justify-between">
        <div class="flex items-center gap-3">
            @if($gameThumbnail)
                <img src="{{ asset('storage/' . $gameThumbnail) }}" class="w-24 h-14 object-cover rounded" alt="Game Thumbnail">
            @else
                <div class="w-24 h-14 bg-gray-100 dark:bg-gray-800 rounded flex items-center justify-center text-gray-500">
                    {{ autoTranslate('Game') }}
                </div>
            @endif
            <div>
                <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $gameDisplayName }}</div>
                <div class="text-xs text-gray-500">{{ autoTranslate('Mainkan game') }}</div>
                @if($game->score > 0)
                    <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                        🏆 {{ autoTranslate('Skor terbaik') }}: {{ $game->score }}
                    </div>
                @endif
            </div>
        </div>
        <div>
            <a href="{{ $gameRoute }}?postingan={{ $post->id_postingan }}&game={{ $game->id_games }}" 
               class="inline-flex items-center px-3 py-1.5 bg-teal-600 text-white rounded-full hover:bg-teal-700 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm3 4v2h2V7H8zm6 0v2h2V7h-2zm-6 6v2h2v-2H8zm6 0v2h2v-2h-2z"/>
                </svg>
                {{ autoTranslate('Play') }}
            </a>
        </div>
    </div>
@endif
                                        </div>

                                        <!-- Footer Actions -->
                                        <div
                                            class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex items-center justify-between">
                                            <div class="flex items-center gap-6">
                                                <!-- Like -->
                                                @auth
                                                    <button
                                                        class="like-btn flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-red-500 transition"
                                                        data-postingan-id="{{ $post->id_postingan }}">
                                                        <svg class="w-5 h-5 {{ $post->likes->where('id_user', auth()->id())->count() > 0 ? 'fill-current text-red-500' : '' }}"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                        </svg>
                                                        <span class="like-count text-sm">{{ $post->likes->count() }}</span>
                                                    </button>
                                                @else
                                                    <button onclick="window.location.href='{{ route('login') }}'"
                                                        class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                        </svg>
                                                        <span class="text-sm">{{ $post->likes->count() }}</span>
                                                    </button>
                                                @endauth

                                                <!-- Comment -->
                                                <button onclick="toggleComments(this)"
                                                    class="comment-toggle flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-blue-600 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                    </svg>
                                                    <span class="text-sm">{{ $post->komentar->count() }}</span>
                                                </button>
                                            </div>

                                            <span
                                                onclick="window.location.href='{{ route('postingan.show', ['id' => $post->id_postingan]) }}'"
                                                class="text-xs text-gray-500 dark:text-gray-400 cursor-pointer hover:text-indigo-600 transition">
                                                {{ autoTranslate('Lihat detail') }} →
                                            </span>
                                        </div>

                                        <!-- Comments Section -->
                                        <div class="comment-section px-4 pb-4 bg-white dark:bg-gray-800 hidden"
                                            id="comments-{{ $post->id_postingan }}">
                                            @auth
                                                <form action="{{ route('komentar.store') }}" method="POST" class="mb-4">
                                                    @csrf
                                                    <input type="hidden" name="id_postingan" value="{{ $post->id_postingan }}">
                                                    <div class="flex gap-3">
                                                        @if(auth()->user()->photo_profile && file_exists(public_path('storage/' . auth()->user()->photo_profile)))
                                                            <img src="{{ asset('storage/' . auth()->user()->photo_profile) }}"
                                                                class="w-8 h-8 rounded-full object-cover mt-1"
                                                                alt="{{ autoTranslate('Foto Profil') }}">
                                                        @else
                                                            <div
                                                                class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center mt-1">
                                                                <span class="text-indigo-600 dark:text-indigo-400 text-sm font-semibold">
                                                                    {{ strtoupper(substr(auth()->user()->nama_mahasiswa ?? 'U', 0, 1)) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                        <div class="flex-1">
                                                            <textarea name="komentar" rows="2"
                                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none resize-y"
                                                                placeholder="{{ autoTranslate('Tulis komentar...') }}"></textarea>
                                                            <div class="flex justify-end mt-2">
                                                                <button type="submit"
                                                                    class="px-5 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                                                                    {{ autoTranslate('Kirim') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            @else
                                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-3">
                                                    <a href="{{ route('login') }}"
                                                        class="text-indigo-600 hover:underline">{{ autoTranslate('Masuk') }}</a>
                                                    {{ autoTranslate('untuk berkomentar') }}
                                                </p>
                                            @endauth

                                            <!-- List Komentar -->
                                            @if($post->komentar->count() > 0)
                                                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                                    @foreach($post->komentar->sortByDesc('tanggal') as $komentar)
                                                        <div class="flex gap-3">
                                                            @if($komentar->user->photo_profile && file_exists(public_path('storage/' . $komentar->user->photo_profile)))
                                                                <img src="{{ asset('storage/' . $komentar->user->photo_profile) }}"
                                                                    class="w-8 h-8 rounded-full object-cover mt-0.5"
                                                                    alt="{{ autoTranslate('Foto Profil') }}">
                                                            @else
                                                                <div
                                                                    class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center mt-0.5">
                                                                    <span class="text-gray-600 dark:text-gray-400 text-sm">
                                                                        {{ strtoupper(substr($komentar->user->nama_mahasiswa ?? 'U', 0, 1)) }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                            <div class="flex-1">
                                                                <div class="flex items-center gap-2">
                                                                    <span
                                                                        class="font-medium text-sm">{{ autoTranslate($komentar->user->nama_mahasiswa) }}</span>
                                                                    <span
                                                                        class="text-xs text-gray-500">{{ $komentar->tanggal?->translatedFormat('d M Y') }}</span>
                                                                </div>
                                                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-0.5">
                                                                    {{ autoTranslate($komentar->komentar) }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-xs text-gray-500 dark:text-gray-400 text-center py-4">
                                                    {{ autoTranslate('Belum ada komentar') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div id="postingan-pagination" class="mt-8">
                                {{ $postinganTerbaru->render('vendor.pagination.custom_ajax', ['groupName' => autoTranslate('postingan')]) }}
                            </div>
                        @endif
                    </div>

                    <!-- Project Section -->
                    <div class="feed-section mb-10">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-orange-600 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-orange-600 dark:text-orange-300" data-translate="ttl_pjt"
                                data-translate-page="dashboard">{{ autoTranslate('Project') }}</h3>
                        </div>
                        @if($projects->isEmpty())
                            <div
                                class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                                <p class="text-gray-500 dark:text-gray-400" data-translate="empty_pjt"
                                    data-translate-page="dashboard">{{ autoTranslate('Belum ada project') }}</p>
                            </div>
                        @else
                            <div id="project-container" class="space-y-4">
                                @foreach($projects as $post)
                                    <div class="project-card">
                                        @include('components.card_postingan', ['post' => $post])
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-6">
                                {{ $projects->render('vendor.pagination.custom_ajax', ['groupName' => autoTranslate('project')]) }}
                            </div>
                        @endif
                    </div>

                    <!-- Sertifikat Section -->
                    <div class="feed-section">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-green-600 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-green-700 dark:text-green-300" data-translate="ttl_stk"
                                data-translate-page="dashboard">{{ autoTranslate('Sertifikat') }}</h3>
                        </div>
                        @if($projectUsers->isEmpty())
                            <div
                                class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                                <p class="text-gray-500 dark:text-gray-400" data-translate="empty_stk"
                                    data-translate-page="dashboard">{{ autoTranslate('Belum ada sertifikat') }}</p>
                            </div>
                        @else
                            <div id="sertifikat-container" class="space-y-4">
                                @foreach($projectUsers as $post)
                                    <div class="sertifikat-card">
                                        @include('components.card_postingan', ['post' => $post])
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-6">
                                {{ $projectUsers->render('vendor.pagination.custom_ajax', ['groupName' => autoTranslate('sertifikat')]) }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- RIGHT COLUMN: Sidebar -->
                <div class="sidebar-column">
                    <!-- Learning Corner -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-200 dark:border-gray-700 sticky top-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-purple-600 rounded-full"></div>
                            <h3 class="text-base font-semibold text-purple-700 dark:text-purple-300"
                                data-translate="ttl_lrn" data-translate-page="dashboard">Learning Corner</h3>
                        </div>
                        @if($learningCorners->isEmpty())
                            <p class="text-gray-500 dark:text-gray-400 text-sm" data-translate="empty_lrn"
                                data-translate-page="dashboard">Belum ada Learning Corner</p>
                        @else
                            <div id="learning-corner-list" class="space-y-4 overflow-y-auto max-h-96">
                                @foreach($learningCorners->take(5) as $learning)
                                    <div onclick="window.location.href='{{ route('project.show', ['id' => $learning->project_id]) }}'"
                                        class="cursor-pointer dark:border-gray-700 border hover:bg-gray-50 dark:hover:bg-gray-700 p-3 rounded-lg transition">
                                        <h4 class="text-sm dark:text-gray-100 font-medium line-clamp-2">
                                            {{ autoTranslate($learning->content[0]['content'] ?? 'Learning Content') }}</h4>
                                        <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">
                                            {{ $learning->tanggal->translatedFormat('d M Y') }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-6">
                                {{ $learningCorners->render('vendor.pagination.custom_ajax', ['groupName' => 'learning_corner']) }}
                            </div>

                        <!-- DOSEN LIST SECTION -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-200 dark:border-gray-700 mt-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-blue-600 rounded-full"></div>
                            <h3 class="text-base font-semibold text-blue-700 dark:text-blue-300">
                                {{ $dosenList->count() }} {{ autoTranslate('Dosen') }}
                            </h3>
                        </div>

                        @if($dosenList->isEmpty())
                            <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">
                                {{ autoTranslate('Belum ada data dosen') }}
                            </p>
                        @else
                            <div class="relative w-full max-w-xl mx-auto">
                                <!-- Slider -->
                                <div class="overflow-hidden relative h-64 flex items-center justify-center">

                                    <div id="dosenCarouselTrack" class="relative w-full h-full flex items-center justify-center">

                                        @php
                                            $dosenArray = $dosenList->values()->all();
                                        @endphp

                                        @foreach($dosenArray as $index => $dosen)
                                            <div class="dosen-card absolute transition-all duration-500 ease-in-out"
                                                data-index="{{ $index }}">

                                                @if($dosen->photo_profile && file_exists(public_path('storage/' . $dosen->photo_profile)))
                                                    <img class="w-24 h-24 rounded-full mx-auto"
                                                        src="{{ asset('storage/' . $dosen->photo_profile) }}">
                                                @else
                                                    <img class="w-24 h-24 rounded-full mx-auto"
                                                        src="https://ui-avatars.com/api/?background=045be6&color=fff&size=100&name={{ urlencode($dosen->nama_mahasiswa ?? 'D') }}">
                                                @endif
                                                
                                                <p class="dosen-name text-center dark:text-gray-100 mt-4 font-semibold opacity-0 transition-all duration-300">
                                                    {{ $dosen->nama_mahasiswa ?? 'Dosen' }}
                                                </p>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>

                                <!-- Arrow -->
                                <button id="dosenPrevBtn"
                                    class="absolute left-0 top-1/2 -translate-y-1/2 bg-white p-2 rounded-full shadow z-10">
                                    ←
                                </button>

                                <button id="dosenNextBtn"
                                    class="absolute right-0 top-1/2 -translate-y-1/2 bg-white p-2 rounded-full shadow z-10">
                                    →
                                </button>

                                <!-- Dots -->
                                <div class="w-24 overflow-hidden mx-auto mt-4">
                                    <div class="flex gap-2 transition-transform duration-300" id="dosenDotsTrack">
                                        @foreach($dosenArray as $index => $dosen)
                                            <span class="dot w-3 h-3 bg-gray-300 rounded-full cursor-pointer flex-shrink-0 transition-all duration-300 opacity-40 scale-90"
                                                data-index="{{ $index }}"></span>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        @endif
                    </div>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- JavaScript -->
    <script>
        // Toggle Comments Section (LinkedIn style)
        function toggleComments(btn) {
            const postCard = btn.closest('.post-card');
            const commentSection = postCard.querySelector('.comment-section');

            if (commentSection.style.display === 'block') {
                commentSection.style.display = 'none';
            } else {
                commentSection.style.display = 'block';
            }
        }

        // Like functionality (sudah ada sebelumnya, dipertahankan)
        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('click', function (e) {
                const likeBtn = e.target.closest('.like-btn');
                if (likeBtn) {
                    e.preventDefault();
                    e.stopPropagation();
                    const postinganId = likeBtn.getAttribute('data-postingan-id');
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
                        })
                        .catch(err => console.error(err));
                }
            });
        });
    </script>
    <script>
        const cards = document.querySelectorAll('.dosen-card');
        const dots = document.querySelectorAll('.dot');
        const dotsTrack = document.getElementById('dosenDotsTrack');

        let current = 0;
        let autoSlide;
        function startAutoSlide() {
            autoSlide = setInterval(() => {
                current = (current + 1) % cards.length;
                updateCarousel();
            }, 10000);
        }

        function stopAutoSlide() {
            clearInterval(autoSlide);
        }

        const visibleDots = 4;
        const dotSize = 12 + 8; // width (w-3 = 12px) + gap (approx 8px)

        function updateCarousel() {
            cards.forEach((card, i) => {
                let offset = i - current;
                const name = card.querySelector('.dosen-name');

                if (offset === 0) {
                    card.style.transform = "translateX(0) scale(1)";
                    card.style.zIndex = "3";
                    card.style.opacity = "1";
                } else if (offset === -1) {
                    card.style.transform = "translateX(-120px) scale(0.8)";
                    card.style.zIndex = "2";
                    card.style.opacity = "0.6";
                } else if (offset === 1) {
                    card.style.transform = "translateX(120px) scale(0.8)";
                    card.style.zIndex = "2";
                    card.style.opacity = "0.6";
                } else {
                    card.style.transform = "translateX(0) scale(0.5)";
                    card.style.zIndex = "1";
                    card.style.opacity = "0";
                }

                if (offset === 0) {
                    card.style.transform = "translateX(0) scale(1)";
                    card.style.zIndex = "3";
                    card.style.opacity = "1";

                    if (name) name.style.opacity = "1"; // tampil
                } else if (offset === -1) {
                    card.style.transform = "translateX(-120px) scale(0.8)";
                    card.style.zIndex = "2";
                    card.style.opacity = "0.6";

                    if (name) name.style.opacity = "0"; // sembunyi
                } else if (offset === 1) {
                    card.style.transform = "translateX(120px) scale(0.8)";
                    card.style.zIndex = "2";
                    card.style.opacity = "0.6";

                    if (name) name.style.opacity = "0";
                } else {
                    card.style.transform = "translateX(0) scale(0.5)";
                    card.style.zIndex = "1";
                    card.style.opacity = "0";

                    if (name) name.style.opacity = "0";
                }
            });

            //  ACTIVE DOT
            dots.forEach((dot, i) => {
                if (i === current) {
                    dot.classList.add('bg-blue-500', 'opacity-100', 'scale-110');
                    dot.classList.remove('bg-gray-300', 'opacity-40', 'scale-90');
                } else {
                    dot.classList.add('bg-gray-300', 'opacity-40', 'scale-90');
                    dot.classList.remove('bg-blue-500', 'opacity-100', 'scale-110');
                }
            });

            // SCROLLING DOT EFFECT
            let offsetIndex = current - Math.floor(visibleDots / 2);

            if (offsetIndex < 0) offsetIndex = 0;
            if (offsetIndex > dots.length - visibleDots) {
                offsetIndex = dots.length - visibleDots;
            }

            let translateX = -(offsetIndex * dotSize);
            dotsTrack.style.transform = `translateX(${translateX}px)`;
        }

        // Arrow
        document.getElementById('dosenNextBtn').onclick = () => {
            current = (current + 1) % cards.length;
            updateCarousel();
        };

        document.getElementById('dosenPrevBtn').onclick = () => {
            current = (current - 1 + cards.length) % cards.length;
            updateCarousel();
        };

        // Klik dot
        dots.forEach(dot => {
            dot.onclick = () => {
                current = parseInt(dot.dataset.index);
                updateCarousel();
            };
        });
        
        updateCarousel();
        startAutoSlide();
    </script>
@endsection