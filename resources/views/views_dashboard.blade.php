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

        .dark .skeleton {
            background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
            background-size: 200px 100%;
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
    </style>

    <div class="min-h-screen dark:bg-gray-800 rounded-2xl py-4 sm:py-6 px-3 sm:px-6 lg:px-8" data-dashboard-type="me">
        <div class="max-w-7xl mx-auto">
            <div class="dashboard-container">

                <!-- LEFT COLUMN: Posts Feed -->
                <div class="feed-column">

                    <div class="mb-6 flex justify-between items-center">
                    <div class="mb-6 flex justify-between items-center">
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

                    <!-- Postingan Mahasiswa -->
                    <div class="feed-section mb-10">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-indigo-600 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300" data-translate="ur_post"
                                data-translate-page="dashboard">{{ autoTranslate('Postingan Mahasiswa') }}</h3>
                        </div>

                        <!-- Postingan Skeleton (DI LUAR loop) -->
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
                                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 post-card">
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
                                                            <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100">{{ autoTranslate($post->user->nama_mahasiswa) }}</h4>
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
                                                        <a href="{{ $gameRoute }}?postingan={{ $post->id_postingan }}&game={{ $game->id_games }}" class="inline-flex items-center px-3 py-1.5 bg-teal-600 text-white rounded-full hover:bg-teal-700 transition-colors">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm3 4v2h2V7H8zm6 0v2h2V7h-2zm-6 6v2h2v-2H8zm6 0v2h2v-2h-2z"/></svg>
                                                            {{ autoTranslate('Play') }}
                                                        </a>
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
                                                    <button onclick="toggleComments(this)" class="comment-toggle flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-blue-600 transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                                        <span class="text-sm">{{ $post->komentar->count() }}</span>
                                                    </button>
                                                </div>
                                                <span onclick="window.location.href='{{ route('postingan.show', ['id' => $post->id_postingan]) }}'" class="text-xs text-gray-500 dark:text-gray-400 cursor-pointer hover:text-indigo-600 transition">{{ autoTranslate('Lihat detail') }} →</span>
                                            </div>

                                            <div class="comment-section px-4 pb-4 bg-white dark:bg-gray-800 hidden" id="comments-{{ $post->id_postingan }}">
                                                @auth
                                                    <form action="{{ route('komentar.store') }}" method="POST" class="mb-4">
                                                        @csrf
                                                        <input type="hidden" name="id_postingan" value="{{ $post->id_postingan }}">
                                                        <div class="flex gap-3">
                                                            @if(auth()->user()->photo_profile && file_exists(public_path('storage/' . auth()->user()->photo_profile)))
                                                                <img src="{{ asset('storage/' . auth()->user()->photo_profile) }}" class="w-8 h-8 rounded-full object-cover mt-1">
                                                            @else
                                                                <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center mt-1"><span class="text-indigo-600 dark:text-indigo-400 text-sm">{{ strtoupper(substr(auth()->user()->nama_mahasiswa ?? 'U', 0, 1)) }}</span></div>
                                                            @endif
                                                            <div class="flex-1">
                                                                <textarea name="komentar" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none resize-y" placeholder="{{ autoTranslate('Tulis komentar...') }}"></textarea>
                                                                <div class="flex justify-end mt-2"><button type="submit" class="px-5 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">{{ autoTranslate('Kirim') }}</button></div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                @else
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-3"><a href="{{ route('login') }}" class="text-indigo-600 hover:underline">{{ autoTranslate('Masuk') }}</a> {{ autoTranslate('untuk berkomentar') }}</p>
                                                @endauth
                                                @if($post->komentar->count() > 0)
                                                    <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                                        @foreach($post->komentar->sortByDesc('tanggal') as $komentar)
                                                            <div class="flex gap-3">
                                                                @if($komentar->user->photo_profile && file_exists(public_path('storage/' . $komentar->user->photo_profile)))
                                                                    <img src="{{ asset('storage/' . $komentar->user->photo_profile) }}" class="w-8 h-8 rounded-full object-cover mt-0.5">
                                                                @else
                                                                    <div class="w-8 h-8 rounded-full text-gray-600 dark:text-gray-200 bg-gray-200 dark:bg-gray-700 flex items-center justify-center mt-0.5"><span class="text-gray-600 dark:text-gray-200 text-sm">{{ strtoupper(substr($komentar->user->nama_mahasiswa ?? 'U', 0, 1)) }}</span></div>
                                                                @endif
                                                                <div class="flex-1"><div class="flex items-center text-gray-600 dark:text-gray-200 gap-2"><span class="font-medium text-sm">{{ autoTranslate($komentar->user->nama_mahasiswa) }}</span><span class="text-xs text-gray-500">{{ $komentar->tanggal?->translatedFormat('d M Y') }}</span></div><p class="text-sm text-gray-700 dark:text-gray-300 mt-0.5">{{ autoTranslate($komentar->komentar) }}</p></div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center py-4">{{ autoTranslate('Belum ada komentar') }}</p>
                                                @endif
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
                        <div class="rounded-xl shadow-md p-5 mt-6">
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
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('postingan-skeleton').classList.add('hidden');
                document.getElementById('postingan-content-wrapper').classList.remove('hidden');
                
                document.getElementById('project-skeleton').classList.add('hidden');
                document.getElementById('project-content-wrapper').classList.remove('hidden');
                
                document.getElementById('sertifikat-skeleton').classList.add('hidden');
                document.getElementById('sertifikat-content-wrapper').classList.remove('hidden');
                
                document.getElementById('learning-skeleton').classList.add('hidden');
                document.getElementById('learning-content-wrapper').classList.remove('hidden');
                
                sortPostinganByGame();
            }, 800);
        });

        function toggleComments(btn) {
            const postCard = btn.closest('.post-card');
            const commentSection = postCard.querySelector('.comment-section');
            commentSection.style.display = commentSection.style.display === 'block' ? 'none' : 'block';
        }

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

        document.addEventListener('click', function(e) {
            const likeBtn = e.target.closest('.like-btn');
            if (likeBtn) {
                e.preventDefault();
                e.stopPropagation();
                const postinganId = likeBtn.getAttribute('data-postingan-id');
                const locale = document.querySelector('html').getAttribute('lang') || 'id';
                fetch(`/${locale}/postingan/toggle-like?id=${postinganId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const countEl = likeBtn.querySelector('.like-count');
                        countEl.textContent = data.like_count;
                        const svg = likeBtn.querySelector('svg');
                        if (data.liked) { likeBtn.classList.add('text-red-500'); svg.classList.add('fill-current', 'text-red-500'); }
                        else { likeBtn.classList.remove('text-red-500'); svg.classList.remove('fill-current', 'text-red-500'); }
                    }
                });
            }
        });

        // Dosen Carousel
        const dosenCards = document.querySelectorAll('.dosen-card');
        const dots = document.querySelectorAll('.dot');
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
            document.getElementById('dosenDotsTrack').style.transform = `translateX(${-(offsetIndex * dotSize)}px)`;
        }

        document.getElementById('dosenNextBtn').onclick = () => { current = (current + 1) % dosenCards.length; updateCarousel(); };
        document.getElementById('dosenPrevBtn').onclick = () => { current = (current - 1 + dosenCards.length) % dosenCards.length; updateCarousel(); };
        dots.forEach(dot => { dot.onclick = () => { current = parseInt(dot.dataset.index); updateCarousel(); }; });
        updateCarousel();
        setInterval(() => { current = (current + 1) % dosenCards.length; updateCarousel(); }, 5000);
    </script>
@endsection