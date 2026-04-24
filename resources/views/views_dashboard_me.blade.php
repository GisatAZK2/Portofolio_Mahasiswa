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

        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }

        .dark .modal-content {
            background-color: rgb(31, 41, 55);
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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        @keyframes skeletonPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
    </style>

    <div class="min-h-screen dark:bg-gray-800 rounded-2xl py-4 sm:py-6 px-3 sm:px-6 lg:px-8" data-dashboard-type="me">
        <div class=" mx-auto">
            <div class="dashboard-container">

                <!-- LEFT COLUMN: Posts Feed -->
                <div class="feed-column">

                    <!-- Statistic Cards with Chart -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                        <div id="total-lrn-skeleton" class="space-y-4">
                            @for($i = 0; $i < 1; $i++)
                            <div class="skeleton-card skeleton-pulse h-52">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="skeleton w-1/2 h-8"></div>
                                    <div class="skeleton w-6 h-6 rounded-full"></div>
                                </div>
                            </div>
                            @endfor
                        </div>
                    
                        <div id="total-lrn-wrapper" class="hidden">
                            <!-- Card Learning Corner -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200" data-translate="all_lrn" data-translate-page="dashboard_me">Semua Learning Corner
                                    </h3>
                                    <span class="text-purple-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </span>
                                </div>
                                <p class="text-3xl font-bold text-purple-600 dark:text-purple-300">{{ $totalLearning ?? 0 }}</p>
                                <div class="mt-4 h-20">
                                    <canvas id="learningChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div id="total-pjt-skeleton" class="space-y-4">
                            @for($i = 0; $i < 1; $i++)
                            <div class="skeleton-card skeleton-pulse h-52">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="skeleton w-1/2 h-8"></div>
                                    <div class="skeleton w-6 h-6 rounded-full"></div>
                                </div>
                            </div>
                            @endfor
                        </div>
                        <div class="hidden" id="total-pjt-wrapper">
                            <!-- Card Total Project -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow cursor-pointer"
                                onclick="window.location.href = '{{ auth()->check() ? route('project.index') : route('project.project_user') }}';">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200">Total Semua Project
                                    </h3>
                                    <span class="text-orange-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                </div>
                                <p class="text-3xl font-bold text-orange-600">{{ $totalProject ?? 0 }}</p>
                                <div class="mt-4 h-20">
                                    <canvas id="projectChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div id="total-stk-skeleton" class="space-y-4">
                            @for($i = 0; $i < 1; $i++)
                            <div class="skeleton-card skeleton-pulse h-52">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="skeleton w-1/2 h-8"></div>
                                    <div class="skeleton w-6 h-6 rounded-full"></div>
                                </div>
                            </div>
                            @endfor
                        </div>
                        <div id="total-stk-wrapper" class="hidden">
                            <!-- Card Total Sertifikat -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow cursor-pointer"
                                onclick="window.location.href = '{{ auth()->check() ? route('sertifikat.index') : route('sertifikat-mahasiswa') }}';">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200" data-translate="all_stk" data-translate-page="dashboard_me">Total Semua Sertifikat</h3>
                                    <span class="text-amber-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                    </span>
                                </div>
                                <p class="text-3xl font-bold text-amber-600">{{ $totalSertifikat ?? 0 }}</p>
                                <div class="mt-4 h-20">
                                    <canvas id="sertifikatChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Create Posting Section -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700 mb-8">
                        <div class="flex items-center gap-4">
                            @if(auth()->user()->photo_profile && file_exists(public_path('storage/' . auth()->user()->photo_profile)))
                                <img src="{{ asset('storage/' . auth()->user()->photo_profile) }}"
                                    class="w-12 h-12 rounded-full object-cover" alt="">
                            @else
                                <div
                                    class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-lg">
                                        {{ strtoupper(substr(auth()->user()->nama_mahasiswa, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div class="flex-1">
                                <input type="text" id="openCreatePostBtn" placeholder="Mulai buat postingan" readonly
                                    class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full border border-gray-300 dark:border-gray-600 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            </div>
                        </div>
                        <div class="flex gap-4 mt-4 justify-around">
                            <button onclick="openCreatePostModal()"
                                class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm font-medium" data-translate="foto" data-translate-page="dashboard_me">Foto</span>
                            </button>
                            <button onclick="openCreatePostModal()"
                                class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-red-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm font-medium" data-translate="write" data-translate-page="dashboard_me">Tulis artikel</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Konten Saya Title -->
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100" data-translate="my_content" data-translate-page="dashboard_me">Konten Saya</h2>
                    </div>

                    
                    <!-- Postingan Sendiri -->
                    <div class="feed-section mb-10">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-indigo-600 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300" data-translate="ur_post" data-translate-page="dashboard_me">Postingan Anda</h3>
                        </div>

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
                        <div class="hidden" id="postingan-content-wrapper">
                        @if($postinganTerbaru->isEmpty())
                            <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                <p class="text-gray-500 dark:text-gray-400" data-translate="empty_post" data-translate-page="dashboard_me">Belum ada postingan mahasiswa</p>
                            </div>
                        @else
                            <div id="postingan-container" class="space-y-6">
                                @foreach($postinganTerbaru as $post)
                                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 post-card">
                                        
                                        <!-- Header -->
                                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('portfolio.show', ['user' => $post->user->username]) }}" class="flex items-center gap-3">
                                                <a href="{{ route('portfolio.show', ['user' => $post->user->username]) }}" class="flex items-center gap-3">
                                                @if($post->user->photo_profile && file_exists(public_path('storage/' . $post->user->photo_profile)))
                                                    <img src="{{ asset('storage/' . $post->user->photo_profile) }}" 
                                                         class="w-10 h-10 rounded-full object-cover" alt="">
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                                        <span class="text-indigo-600 dark:text-indigo-400 font-semibold">
                                                            {{ strtoupper(substr($post->user->nama_mahasiswa, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $post->user->nama_mahasiswa }}</h4>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $post->tanggal->format('d M Y') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        </a>
                                        </a>

                                        <!-- Content -->
                                        <div class="p-4" onclick="window.location.href='{{ route('postingan.index', ['id' => $post->id_postingan]) }}'" style="cursor: pointer;">
                                        <div class="p-4" onclick="window.location.href='{{ route('postingan.index', ['id' => $post->id_postingan]) }}'" style="cursor: pointer;">
                                            @php
                                                $content = $post->content;
                                                $title = '';
                                                $deskripsi = '';
                                                $imageUrl = null;
                                                $gameThumbnail = null;
                                                if (is_array($content)) {
                                                    foreach ($content as $item) {
                                                        if (isset($item['type'])) {
                                                            if ($item['type'] === 'title') $title = $item['content'] ?? '';
                                                            if ($item['type'] === 'description') $deskripsi = $item['content'] ?? '';
                                                            if ($item['type'] === 'image' && !empty($item['content']) && !$imageUrl) {
                                                                $imageUrl = asset('storage/' . ltrim($item['content'], '/'));
                                                            }
                                                        }
                                                    }
                                                }
                                                $game = $post->game ?? null;
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
                                            @endphp

                                            @if($title)
                                                <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">{{ $title }}</h3>
                                            @endif
                                            @if($deskripsi)
                                                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">{{ Str::limit($deskripsi, 150) }}</p>
                                            @endif
                                            @if($imageUrl)
                                                <div class="mb-4 rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 w-auto h-auto shadow-sm">
                                                    <img src="{{ $imageUrl }}" 
                                                         alt="{{ autoTranslate('Gambar postingan') }}"
                                                         id="logo-zoom"
                                                         class="w-full h-auto object-cover hover:scale-105 transition-transform duration-300"
                                                         loading="lazy"
                                                         onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'h-64 bg-gray-100 dark:bg-gray-800 flex items-center justify-center\'><span class=\'text-gray-400\'>{{ autoTranslate('Gambar tidak dapat dimuat') }}</span></div>'">
                                                </div>
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
                                                        <img src="{{ asset($gameThumbnail) }}" class="w-24 h-14 object-cover rounded" alt="Game Thumbnail">
                                                    
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
                                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex items-center justify-between">
                                            <div class="flex items-center gap-6">
                                                <!-- Like -->
                                                @auth
                                                    <button class="like-btn flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-red-500 transition"
                                                            data-postingan-id="{{ $post->id_postingan }}">
                                                        <svg class="w-5 h-5 {{ $post->likes->where('id_user', auth()->id())->count() > 0 ? 'fill-current text-red-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                        </svg>
                                                        <span class="like-count text-sm">{{ $post->likes->count() }}</span>
                                                    </button>
                                                @else
                                                    <button onclick="window.location.href='{{ route('login') }}'" class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                        </svg>
                                                        <span class="text-sm">{{ $post->likes->count() }}</span>
                                                    </button>
                                                @endauth

                                                <!-- Comment Button -->
                                                <button onclick="toggleComments(this)" 
                                                        class="comment-toggle flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-blue-600 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                    </svg>
                                                    <span class="text-sm">{{ $post->komentar->count() }}</span>
                                                </button>
                                            </div>
                                            <span onclick="window.location.href='{{ route('postingan.show', ['locale' => app()->getLocale(), 'id' => $post->id_postingan]) }}'"  data-translate="see_dtl" data-translate-page="dashboard_me"
                                                  class="text-xs text-gray-500 dark:text-gray-400 cursor-pointer hover:text-indigo-600">
                                                Lihat detail
                                            </span>
                                        </div>

                                        <!-- Comments Section (Hidden by default) -->
                                        <div class="comment-section px-4 pb-4 bg-white dark:bg-gray-800" id="comments-{{ $post->id_postingan }}">
                                            @auth
                                                <form action="{{ route('komentar.store') }}" method="POST" class="mb-4">
                                                    @csrf
                                                    <input type="hidden" name="id_postingan" value="{{ $post->id_postingan }}">
                                                    <div class="flex gap-3">
                                                        @if(auth()->user()->photo_profile && file_exists(public_path('storage/' . auth()->user()->photo_profile)))
                                                            <img src="{{ asset('storage/' . auth()->user()->photo_profile) }}" class="w-8 h-8 rounded-full object-cover mt-1">
                                                        @else
                                                            <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center mt-1">
                                                                <span class="text-indigo-600 dark:text-indigo-400 text-sm font-semibold">
                                                                    {{ strtoupper(substr(auth()->user()->nama_mahasiswa, 0, 1)) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                        <div class="flex-1">
                                                            <textarea name="komentar" rows="2" 
                                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none resize-y"
                                                                placeholder="Tulis komentar..."></textarea>
                                                            <div class="flex justify-end mt-2">
                                                                <button type="submit" data-translate="send" data-translate-page="dashboard_me"
                                                                    class="px-5 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                                                                    Kirim
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            @else
                                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-3">
                                                    <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Masuk</a> untuk berkomentar
                                                </p>
                                            @endauth

                                            <!-- List Komentar -->
                                            @if($post->komentar->count() > 0)
                                                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                                    @foreach($post->komentar->sortByDesc('tanggal') as $komentar)
                                                        <div class="flex gap-3">
                                                            @if($komentar->user->photo_profile && file_exists(public_path('storage/' . $komentar->user->photo_profile)))
                                                                <img src="{{ asset('storage/' . $komentar->user->photo_profile) }}" class="w-8 h-8 rounded-full object-cover mt-0.5">
                                                            @else
                                                                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center mt-0.5">
                                                                    <span class="text-gray-600 dark:text-gray-400 text-sm">
                                                                        {{ strtoupper(substr($komentar->user->nama_mahasiswa, 0, 1)) }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                            <div class="flex-1">
                                                                <div class="flex items-center gap-2">
                                                                    <span class="font-medium text-sm">{{ $komentar->user->nama_mahasiswa }}</span>
                                                                    <span class="text-xs text-gray-500">{{ $komentar->tanggal->format('d M Y') }}</span>
                                                                </div>
                                                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-0.5">{{ $komentar->komentar }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-xs text-gray-500 dark:text-gray-400 text-center py-4">Belum ada komentar</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div id="postingan-pagination" class="mt-8">
                                {{ $postinganTerbaru->render('vendor.pagination.custom_ajax', ['groupName' => 'postingan']) }}
                            </div>
                        @endif
                    </div>

                    <!-- Project -->
                    <div class="mb-10">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-orange-600 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-orange-600 dark:text-orange-300" data-translate="pjt" data-translate-page="dashboard_me">Project</h3>
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
                        
                        <div class="hidden" id="project-content-wrapper">
                            @if($projects->isEmpty())
                                <div
                                    class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400" data-translate="empty_pjt" data-translate-page="dashboard_me">Belum ada Project</p>
                                </div>
                            @else
                                <div data-pagination-group="project">
                                    <div class="lg:grid-cols-3 gap-4">
                                        @foreach($projects as $post)
                                            @include('components.card_postingan', ['post' => $post])
                                        @endforeach
                                    </div>
                                    <div class="mt-4">
                                        {{ $projects->render('vendor.pagination.custom_ajax', ['groupName' => 'project']) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sertifikat -->
                    <div class="mb-10">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-green-600 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-green-700 dark:text-green-300" data-translate="stk" data-translate-page="dashboard_me">Sertifikat</h3>
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
                                <div
                                    class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada Sertifikat</p>
                                </div>
                            @else
                                <div data-pagination-group="sertifikat">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($projectUsers as $post)
                                            @include('components.card_postingan', ['post' => $post])
                                        @endforeach
                                    </div>
                                    <div class="mt-4">
                                        {{ $projectUsers->render('vendor.pagination.custom_ajax', ['groupName' => 'sertifikat']) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Timestamp -->
                    <div
                        class="text-center text-gray-500 dark:text-gray-400 text-sm mt-10 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <span data-translate="terakhir_diperbarui" data-translate-page="dashboard_me">Terakhir diperbarui</span> {{ now()->format('d F Y H:i') }}
                    </div>

                </div>
                <div class="sidebar-column">
                    <!-- Learning Corner -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-200 dark:border-gray-700 sticky top-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-purple-600 rounded-full"></div>
                            <h3 class="text-base font-semibold text-purple-700 dark:text-purple-300" data-translate="ttl_lrn" data-translate-page="dashboard">{{ autoTranslate('Learning Corner') }}</h3>
                        </div>
                        
                        <div class="py-8" id="learning-skeleton">
                            @for($i = 0; $i < 3; $i++)
                                <div class="skeleton-card skeleton-pulse">
                                    <div class="skeleton skeleton-title w-3/4"></div>
                                    <div class="skeleton skeleton-text w-1/2 mt-2"></div>
                                </div>
                            @endfor

                        </div>
                        <div id="learning-content-wrapper" class="hidden">
                            @if($learningCorners->isEmpty())
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M3 9h.06M3 12h.06M3 15h.06M6 9h.06M6 12h.06M6 15h.06M9 9h.06M9 18h.06M12 9h.06M12 18h.06M15 9h.06M15 12h.06M15 15h.06M18 9h.06M18 12h.06M18 15h.06M21 9h.06M21 12h.06M21 15h.06" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400" data-translate="empty_lrn" data-translate-page="dashboard_me">Belum ada Learning Corner</p>
                                </div>
                            @else
                                <div id="learning-corner-list" class="space-y-4">
                                    @foreach($learningCorners->take(5) as $learning)
                                        <div onclick="window.location.href='{{ route('project.show', ['id' => $learning->project_id]) }}'" 
                                            class="cursor-pointer dark:border-gray-700 border hover:bg-gray-50 dark:hover:bg-gray-700 p-3 rounded-lg transition">
                                            <h4 class="text-sm dark:text-gray-100 font-medium line-clamp-2">{{ autoTranslate($learning->content[0]['content'] ?? 'Learning Content') }}</h4>
                                            <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">{{ $learning->tanggal->translatedFormat('d M Y') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-6">
                                    {{ $learningCorners->render('vendor.pagination.custom_ajax', ['groupName' => autoTranslate('learning_corner')]) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <!-- Modal Create Postingan -->
    <div id="createPostModal" class="modal">
        <div class="modal-content">
            <!-- Modal Header -->
            <div
                class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between sticky top-0 bg-white dark:bg-gray-800 z-10">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Buat Postingan Baru</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Bagikan pemikiran, cerita, atau pengalaman Anda
                    </p>
                </div>
                <button type="button" onclick="closeCreatePostModal()"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 140px);">
                <form method="POST" action="{{ route('postingan.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Judul -->
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Judul Postingan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul') }}"
                            placeholder="Judul postingan Anda..."
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition">
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Deskripsi (Opsional)
                        </label>
                        <textarea name="deskripsi" rows="4" class="w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:placeholder-gray-400 rounded-lg
                                        focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                        placeholder-gray-500 shadow-sm bg-white">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dynamic Items -->
                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">Konten Tambahan (opsional)</h3>
                            <button type="button" id="add-item"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Item
                            </button>
                        </div>

                        <div id="items-container" class="space-y-6">
                            <!-- Item template akan ditambahkan via JS -->
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700 gap-3">
                        <button type="button" onclick="closeCreatePostModal()"
                            class="px-6 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-8 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                            Buat Postingan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
                
                document.getElementById('total-lrn-skeleton').classList.add("hidden");
                document.getElementById('total-lrn-wrapper').classList.remove('hidden');

                document.getElementById('total-pjt-skeleton').classList.add("hidden");
                document.getElementById('total-pjt-wrapper').classList.remove('hidden');

                document.getElementById('total-stk-skeleton').classList.add("hidden");
                document.getElementById('total-stk-wrapper').classList.remove('hidden');
                sortPostinganByGame();
            }, 800);
        });
            
        // Modal Functions
        function openCreatePostModal() {
            document.getElementById('createPostModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeCreatePostModal() {
            document.getElementById('createPostModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('createPostModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeCreatePostModal();
            }
        });

        // Open modal on button click
        document.getElementById('openCreatePostBtn').addEventListener('click', openCreatePostModal);

        // Dynamic Items Management
        let itemIndex = 0;

        function addItem() {
            const container = document.getElementById('items-container');
            const newItem = document.createElement('div');
            newItem.className = 'item bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 relative';
            newItem.dataset.index = itemIndex;

            newItem.innerHTML = `
                    <div class="flex justify-between items-start mb-4">
                        <select name="items[${itemIndex}][type]" class="type-select border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2 text-sm focus:border-indigo-500 outline-none w-44">
                            <option value="image">Gambar</option>
                            <option value="link">Link / Referensi</option>
                        </select>
                        <button type="button" class="remove-item text-red-500 hover:text-red-700 text-sm font-medium">
                            Hapus
                        </button>
                    </div>

                    <div class="content-area">
                        <!-- Teks default -->
                        <textarea name="items[${itemIndex}][content]" rows="3" class="text-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:border-indigo-500 outline-none transition"
                               placeholder="Masukkan teks di sini..."></textarea>

                        <!-- File upload (hidden awal) -->
                        <div class="file-input hidden mt-2">
                            <input type="file" name="items[${itemIndex}][file]" accept="image/*"
                                   class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 dark:file:bg-indigo-900 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Maks 5MB • jpg, png, gif, webp</p>
                        </div>

                        <!-- Link (hidden awal) -->
                        <input type="url" name="items[${itemIndex}][content]" class="link-input hidden w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:border-indigo-500 outline-none transition"
                               placeholder="https://example.com">
                    </div>
                `;

            container.appendChild(newItem);
            attachTypeListener(newItem);
            itemIndex++;
        }

        function attachTypeListener(itemElement) {
            const select = itemElement.querySelector('.type-select');
            const textInput = itemElement.querySelector('.text-input');
            const fileDiv = itemElement.querySelector('.file-input');
            const linkInput = itemElement.querySelector('.link-input');

            function toggleFields() {
                const type = select.value;
                textInput.classList.toggle('hidden', type !== 'text');
                fileDiv.classList.toggle('hidden', type !== 'image');
                linkInput.classList.toggle('hidden', type !== 'link');

                textInput.disabled = type !== 'text';
                linkInput.disabled = type !== 'link';
                if (type === 'image') {
                    textInput.name = `items[${itemElement.dataset.index}][dummy]`;
                } else {
                    textInput.name = `items[${itemElement.dataset.index}][content]`;
                }
            }

            select.addEventListener('change', toggleFields);
            toggleFields();
        }

        document.getElementById('add-item').addEventListener('click', addItem);

        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.item').remove();
            }
        });

        // Chart.js Configuration
        let charts = {};

        function createSparkline(canvasId, borderColor) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;

            if (charts[canvasId]) {
                charts[canvasId].destroy();
            }

            const ctx = canvas.getContext('2d');

            const generateRandomData = () => {
                return Array.from({ length: 7 }, () => Math.floor(Math.random() * 40) + 10);
            };

            const data = generateRandomData();

            charts[canvasId] = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Array(data.length).fill(''),
                    datasets: [{
                        data: data,
                        borderColor: borderColor,
                        backgroundColor: borderColor + '20',
                        tension: 0.4,
                        pointRadius: 0,
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    },
                    scales: {
                        x: { display: false },
                        y: { display: false }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            createSparkline('learningChart', '#8b5cf6');
            createSparkline('projectChart', '#f97316');
            createSparkline('sertifikatChart', '#f59e0b');
        });

        // Toggle Comments Section
        function toggleComments(btn) {
            const postCard = btn.closest('.post-card');
            const commentSection = postCard.querySelector('.comment-section');

            if (commentSection.style.display === 'block') {
                commentSection.style.display = 'none';
            } else {
                commentSection.style.display = 'block';
            }
        }

        // Like functionality
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

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof showPageInfo === 'function') {
                showPageInfo("popup.dashboard");
            }
        });
    </script>
@endsection
