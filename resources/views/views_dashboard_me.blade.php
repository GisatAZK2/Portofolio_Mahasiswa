@extends('Layout.Layout')
@section('title', 'Dashboard')

@section('meta')
    <meta name="description" content="Manage your personal dashboard, posts, projects, certifications, and learning activities in one place.">
@endsection

@section('content')

    @php
        $dashboardProfileComplete = Auth::check() && Auth::user()->nama_mahasiswa && Auth::user()->nim && Auth::user()->id_jurusan && Auth::user()->id_angkatan && Auth::user()->tanggal_lahir && Auth::user()->photo_profile;
    @endphp

    <div class=" dark:bg-gray-800 rounded-2xl py-4 sm:py-6 px-3 sm:px-6 lg:px-8" data-dashboard-type="me" data-profile-complete="{{ $dashboardProfileComplete ? '1' : '0' }}">
        <div class="max-w-7xl mx-auto">
            <div class="dashboard-container">

                <!-- ============================================================
                     LEFT COLUMN
                ============================================================ -->
                <div class="feed-column">

                    <!-- Statistic Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

                        <!-- Learning Corner stat -->
                        <div id="total-lrn-skeleton" class="space-y-4">
                            <div class="skeleton-card skeleton-pulse h-52">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="skeleton w-1/2 h-8"></div>
                                    <div class="skeleton w-6 h-6 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                        <div id="total-lrn-wrapper" class="hidden">
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200"
                                        data-translate="all_learning_corner" data-translate-page="dashboard">Semua Learning
                                        Corner</h3>
                                    <span class="text-purple-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </span>
                                </div>
                                <p class="text-3xl font-bold text-purple-600 dark:text-purple-300">{{ $totalLearning ?? 0 }}
                                </p>
                                <div class="mt-4 h-20"><canvas id="learningChart"></canvas></div>
                            </div>
                        </div>

                        <!-- Project stat -->
                        <div id="total-pjt-skeleton" class="space-y-4">
                            <div class="skeleton-card skeleton-pulse h-52">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="skeleton w-1/2 h-8"></div>
                                    <div class="skeleton w-6 h-6 rounded-full"></div>
                                </div>
                            </div>
                        </div>

                        <div class="hidden" id="total-pjt-wrapper">
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow cursor-pointer"
                                onclick="window.location.href='{{ auth()->check() ? route('project.index') : route('project.project_user') }}';">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200"
                                        data-translate="all_projects" data-translate-page="dashboard">Total Semua Project
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
                                <div class="mt-4 h-20"><canvas id="projectChart"></canvas></div>
                            </div>
                        </div>

                        <!-- Sertifikat stat -->
                        <div id="total-stk-skeleton" class="space-y-4">
                            <div class="skeleton-card skeleton-pulse h-52">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="skeleton w-1/2 h-8"></div>
                                    <div class="skeleton w-6 h-6 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                        <div id="total-stk-wrapper" class="hidden">
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow cursor-pointer"
                                onclick="window.location.href='{{ auth()->check() ? route('sertifikat.index') : route('sertifikat-mahasiswa') }}';">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200"
                                        data-translate="all_certificates" data-translate-page="dashboard">Total Semua
                                        Sertifikat</h3>
                                    <span class="text-amber-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                    </span>
                                </div>
                                <p class="text-3xl font-bold text-amber-600">{{ $totalSertifikat ?? 0 }}</p>
                                <div class="mt-4 h-20"><canvas id="sertifikatChart"></canvas></div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================================
                         CREATE POST BOX
                    ============================================================ -->
                    <div class="create-post-box">
                        <div class="flex items-center gap-3 mb-4">
                            @if(auth()->user()->photo_profile && file_exists(public_path('storage/' . auth()->user()->photo_profile)))
                                <img src="{{ asset('storage/' . auth()->user()->photo_profile) }}"
                                    class="w-11 h-11 rounded-full object-cover flex-shrink-0" alt="">
                            @else
                                <div
                                    class="w-11 h-11 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                                    <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-base">
                                        {{ strtoupper(substr(auth()->user()->nama_mahasiswa, 0, 1)) }}
                                    </span>
                                </div>
                            @endif

                            <button class="create-post-trigger" id="openPostTrigger" onclick="cpOpenModal('cpModalPost')">
                                <span class="typed-placeholder" id="cp-typed-text"></span>
                                <span class="typing-cursor" id="cp-cursor"></span>
                            </button>
                        </div>

                        <div class="action-divider"></div>

                        <div class="post-action-row">
                            <button class="post-action-btn btn-photo" onclick="cpOpenModal('cpModalPhoto')">
                                <svg class="btn-photo-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span data-translate="photo" data-translate-page="dashboard">Foto</span>
                            </button>
                            <button class="post-action-btn btn-article" onclick="cpOpenModal('cpModalArtikel')">
                                <svg class="btn-article-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span data-translate="write_article" data-translate-page="dashboard">Tulis Artikel</span>
                            </button>
                        </div>
                    </div>

                    <!-- Konten Saya Title -->
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100" data-translate="my_content"
                            data-translate-page="dashboard_me">Konten Saya</h2>
                    </div>

                    <!-- Postingan Sendiri -->
                    <div id="postingan-section" class="feed-section mb-10" data-pagination-group="postingan">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-indigo-600 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300" data-translate="ur_post"
                                data-translate-page="dashboard_me">Postingan Anda</h3>
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
                                <div
                                    class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400" data-translate="empty_student_posts"
                                        data-translate-page="dashboard">Belum ada postingan mahasiswa</p>
                                </div>
                            @else
                                <div id="postingan-container" class="space-y-6">
                                    @foreach($postinganTerbaru as $post)
                                        @php
                                            $commentCount = \App\Models\Komentar::getCommentCount($post->id_postingan);
                                        @endphp
                                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 post-card"
                                            data-post-id="{{ $post->id_postingan }}"
                                            data-share-url="{{ route('postingan.show', ['locale' => app()->getLocale(), 'id' => $post->id_postingan]) }}">

                                            <!-- Header -->
                                            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                                <div class="flex items-center gap-3">
                                                    <a href="{{ route('portfolio.slug', ['slug' => $post->user->slug]) }}"
                                                        class="flex items-center gap-3">
                                                        <div class="relative w-10 h-10 flex-shrink-0">
                                                            <img src="{{ asset('storage/' . $post->user->photo_profile) }}"
                                                                class="w-full h-full rounded-full object-cover aspect-square"
                                                                alt="{{ $post->user->username }}"
                                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                            <div
                                                                class="w-full h-full rounded-full bg-indigo-100 dark:bg-indigo-900 items-center justify-center hidden">
                                                                <span
                                                                    class="text-indigo-600 dark:text-indigo-400 font-semibold text-sm">
                                                                    {{ strtoupper(substr($post->user->nama_mahasiswa ?? 'U', 0, 1)) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                                                {{ $post->user->nama_mahasiswa }}
                                                            </h4>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                {{ $post->tanggal->format('d M Y') }}
                                                            </p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- Content -->
                                            <div class="p-4 cursor-pointer"
                                                onclick="window.location.href='{{ route('postingan.show', ['locale' => app()->getLocale(), 'id' => $post->id_postingan]) }}'">
                                                @php
                                                    $content = $post->content_translated ?? [];
                                                    $title = '';
                                                    $deskripsi = '';
                                                    $imageUrl = null;
                                                    if (is_array($content)) {
                                                        foreach ($content as $item) {
                                                            if (isset($item['type'])) {
                                                                if ($item['type'] === 'title')
                                                                    $title = $item['content'] ?? '';
                                                                if ($item['type'] === 'description')
                                                                    $deskripsi = $item['content'] ?? '';
                                                                if ($item['type'] === 'image' && !empty($item['content']) && !$imageUrl)
                                                                    $imageUrl = asset('storage/' . ltrim($item['content'], '/'));
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
                                                    $gameThumbnail = $game && isset($gameThumbnailMap[strtolower($game->game_name)])
                                                        ? $gameThumbnailMap[strtolower($game->game_name)] : '';
                                                @endphp

                                                @if($title)
                                                    <h3
                                                        class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">
                                                        {{ $title }}</h3>
                                                @endif
                                                @if($deskripsi)
                                                    <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">
                                                        {{ Str::limit($deskripsi, 150) }}</p>
                                                @endif
                                                @if($imageUrl)
                                                    <div
                                                        class="mb-4 rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm mt-3">
                                                        <img src="{{ $imageUrl }}" alt="Gambar postingan"
                                                            class="w-full h-auto object-cover hover:scale-105 transition-transform duration-300"
                                                            loading="lazy">
                                                    </div>
                                                @endif

                                                @if($game)
                                                    @php
                                                        $gameRoute = '';
                                                        $gameDisplayName = $game->game_name;
                                                        switch (strtolower($game->game_name)) {
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
                                                        }
                                                    @endphp
                                                    <div
                                                        class="mt-2 p-3 border border-gray-100 dark:border-gray-800 rounded-lg flex items-center justify-between">
                                                        <div class="flex items-center gap-3">
                                                            <img src="{{ asset($gameThumbnail) }}"
                                                                class="w-24 h-14 object-cover rounded" alt="Game Thumbnail">
                                                            <div>
                                                                <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                                    {{ $gameDisplayName }}</div>
                                                                <div class="text-xs text-gray-500" data-translate="play_game"
                                                                    data-translate-page="dashboard">Mainkan game</div>
                                                                @if($game->score > 0)
                                                                    <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                                                                        <span data-translate="best_score" data-translate-page="dashboard">🏆
                                                                            Skor terbaik</span>: {{ $game->score }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <a href="{{ $gameRoute }}?postingan={{ $post->id_postingan }}&game={{ $game->id_games }}"
                                                            class="inline-flex items-center px-3 py-1.5 bg-teal-600 text-white rounded-full hover:bg-teal-700 transition-colors"
                                                            data-translate="play" data-translate-page="dashboard">Play</a>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Footer Actions -->
                                            <div
                                                class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex items-center justify-between">
                                                <div class="flex items-center gap-6">
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

                                                    <button
                                                        class="comment-toggle flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-blue-600 transition"
                                                        data-postingan-id="{{ $post->id_postingan }}">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                        </svg>
                                                        <span class="comment-count text-sm">{{ $commentCount }}</span>
                                                    </button>

                                                    <button
                                                        class="share-btn flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-blue-600 transition">
                                                        <svg fill="none" class="w-5 h-5" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path
                                                                d="M21.707,11.293l-8-8A.99991.99991,0,0,0,12,4V7.54492A11.01525,11.01525,0,0,0,2,18.5V20a1,1,0,0,0,1.78418.62061,11.45625,11.45625,0,0,1,7.88672-4.04932c.0498-.00635.1748-.01611.3291-.02588V20a.99991.99991,0,0,0,1.707.707l8-8A.99962.99962,0,0,0,21.707,11.293ZM14,17.58594V15.5a.99974.99974,0,0,0-1-1c-.25488,0-1.2959.04932-1.56152.085A14.00507,14.00507,0,0,0,4.05176,17.5332,9.01266,9.01266,0,0,1,13,9.5a.99974.99974,0,0,0,1-1V6.41406L19.58594,12Z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <span
                                                    onclick="window.location.href='{{ route('postingan.show', ['locale' => app()->getLocale(), 'id' => $post->id_postingan]) }}'"
                                                    class="text-xs text-gray-500 dark:text-gray-400 cursor-pointer hover:text-indigo-600 transition">
                                                    <span data-translate="lihat_detail" data-translate-page="dashboard">Lihat
                                                        detail</span> →
                                                </span>
                                            </div>

                                            <!-- Comment Section (AJAX-powered, same as file-1) -->
                                            <div class="comment-section px-4 pb-4 bg-white dark:bg-gray-800 hidden rounded-b-xl"
                                                id="comments-{{ $post->id_postingan }}">
                                                @auth
                                                    <div class="mb-4">
                                                        <div class="flex gap-3">
                                                            <div class="flex-1">
                                                                <textarea id="comment-input-{{ $post->id_postingan }}" rows="2"
                                                                    class="comment-input w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none resize-none text-sm transition"
                                                                    data-translate-placeholder="comment_placeholder"
                                                                    data-translate-page="dashboard"></textarea>
                                                                <div class="flex justify-end mt-2">
                                                                    <button onclick="window.submitComment({{ $post->id_postingan }})"
                                                                        class="submit-comment-btn px-5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                                                        <span data-translate="kirim"
                                                                            data-translate-page="dashboard">Kirim</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="text-center py-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                                            <a href="{{ route('login') }}"
                                                                class="text-indigo-600 hover:text-indigo-700 font-medium hover:underline">
                                                                <span data-translate="login"
                                                                    data-translate-page="dashboard">Masuk</span>
                                                            </a>
                                                            <span data-translate="untuk_berkomentar"
                                                                data-translate-page="dashboard">untuk berkomentar</span>
                                                        </p>
                                                    </div>
                                                @endauth

                                                <div id="comments-container-{{ $post->id_postingan }}"
                                                    class="comments-container space-y-4 pr-2">
                                                    <div class="text-center py-6 text-gray-400 text-sm">
                                                        <div class="comment-loading inline-block mr-2"></div>
                                                        <span data-translate="loading_comments"
                                                            data-translate-page="dashboard">Memuat komentar...</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>

                                <div id="postingan-pagination" class="mt-8">
                                    {{ $postinganTerbaru->render('vendor.pagination.custom_ajax', ['groupName' => 'postingan']) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Project -->
                    <div id="projects-section" class="mb-10" data-pagination-group="project">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-orange-600 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-orange-600 dark:text-orange-300" data-translate="project"
                                data-translate-page="dashboard">Project</h3>
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
                                    <p class="text-gray-500 dark:text-gray-400" data-translate="empty_project"
                                        data-translate-page="dashboard">Belum Ada Project</p>
                                </div>
                            @else
                                <div data-pagination-group="project">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
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
                    <div id="sertifikat-section" class="mb-10">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-green-600 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-green-700 dark:text-green-300" data-translate="sertifikat"
                                data-translate-page="dashboard">Sertifikat</h3>
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
                                    <p class="text-gray-500 dark:text-gray-400" data-translate="empty_certificate"
                                        data-translate-page="dashboard">Belum Ada Sertifikat</p>
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
                        <span data-translate="last_updated" data-translate-page="dashboard">Terakhir diperbarui</span>
                        {{ now()->format('d F Y H:i') }}
                    </div>
                </div>

                <!-- ============================================================
                     RIGHT COLUMN: Sidebar
                ============================================================ -->
                <div class="sidebar-column">
                    <div data-pagination-group="learning_corner"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-200 dark:border-gray-700 sticky top-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-purple-600 rounded-full"></div>
                            <h3 class="text-base font-semibold text-purple-700 dark:text-purple-300"
                                data-translate="learning_corner" data-translate-page="dashboard">Learning Corner</h3>
                        </div>
                        <div id="learning-skeleton-sidebar" class="space-y-4">
                            @for($i = 0; $i < 3; $i++)
                                <div class="skeleton-card skeleton-pulse">
                                    <div class="skeleton skeleton-title w-3/4"></div>
                                    <div class="skeleton skeleton-text w-1/2 mt-2"></div>
                                </div>
                            @endfor
                        </div>
                        <div id="learning-content-wrapper-sidebar" class="hidden">
                            @if($learningCorners->isEmpty())
                                <div class="text-center py-8">
                                    <p class="text-gray-500 dark:text-gray-400" data-translate="empty_learning_corner"
                                        data-translate-page="dashboard">Belum Ada Learning Corner</p>
                                </div>
                            @else
                                <div id="learning-corner-list" class="space-y-4">
                                    @foreach($learningCorners->take(5) as $learning)
                                        <div onclick="window.location.href='{{ route('project.show', ['id' => $learning->project_id]) }}'"
                                            class="cursor-pointer dark:border-gray-700 border hover:bg-gray-50 dark:hover:bg-gray-700 p-3 rounded-lg transition">
                                            <h4 class="text-sm dark:text-gray-100 font-medium line-clamp-2">
                                                {{ $learning->content_translated[0]['content'] ?? 'Learning Content' }}</h4>
                                            <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">
                                                {{ $learning->tanggal->translatedFormat('d M Y') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-6">
                                    {{ $learningCorners->render('vendor.pagination.custom_ajax', ['groupName' => 'learning_corner']) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ============================================================
    MODAL: Buat Postingan
    ============================================================ --}}
    <div id="cpModalPost" class="cp-modal-overlay" onclick="cpOverlayClick(event,'cpModalPost')">
        <div class="cp-modal">
            <div class="cp-modal-header">
                <div>
                    <div class="cp-type-badge badge-post">
                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        <span data-translate="new_post" data-translate-page="dashboard">Postingan Baru</span>
                    </div>
                    <h2 class="cp-modal-title" data-translate="create_new_post" data-translate-page="dashboard">Buat
                        Postingan Baru</h2>
                    <p class="cp-modal-sub" data-translate="share_thoughts" data-translate-page="dashboard">Bagikan
                        pemikiran, cerita, atau pengalaman Anda</p>
                </div>
                <button class="cp-close-btn" onclick="cpCloseModal('cpModalPost')">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="cp-modal-body">
                <form method="POST" action="{{ route('postingan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="cp-field">
                        <label class="cp-label" data-translate="post_title" data-translate-page="dashboard">Judul Postingan
                            <span class="cp-req">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}"
                            data-translate-placeholder="post_title_placeholder" data-translate-page="dashboard"
                            class="cp-input">
                        @error('judul')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="cp-field">
                        <label class="cp-label" data-translate="description" data-translate-page="dashboard">Deskripsi <span
                                class="cp-opt">(opsional)</span></label>
                        <textarea name="deskripsi" class="cp-textarea" rows="4"
                            data-translate-placeholder="description_placeholder"
                            data-translate-page="dashboard">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="cp-extra-section">
                        <div class="cp-extra-header">
                            <span class="cp-extra-title" data-translate="additional_content"
                                data-translate-page="dashboard">Konten Tambahan <span
                                    class="cp-opt">(opsional)</span></span>
                            <button type="button" id="cp-add-item-post" class="cp-add-item-btn">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                <span data-translate="add_item" data-translate-page="dashboard">Tambah Item</span>
                            </button>
                        </div>
                        <div id="cp-items-post"></div>
                    </div>
                    <div class="cp-modal-footer" style="padding-left:0;padding-right:0;border-top:none;margin-top:1rem;">
                        <button type="button" class="cp-btn-cancel" onclick="cpCloseModal('cpModalPost')"
                            data-translate="batal" data-translate-page="dashboard">Batal</button>
                        <button type="submit" class="cp-btn-submit submit-post" data-translate="create_post"
                            data-translate-page="dashboard">Buat Postingan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================================
    MODAL: Posting Foto
    ============================================================ --}}
    <div id="cpModalPhoto" class="cp-modal-overlay" onclick="cpOverlayClick(event,'cpModalPhoto')">
        <div class="cp-modal">
            <div class="cp-modal-header">
                <div>
                    <div class="cp-type-badge badge-photo">
                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span data-translate="photo_post" data-translate-page="dashboard">Postingan Foto</span>
                    </div>
                    <h2 class="cp-modal-title" data-translate="share_photo" data-translate-page="dashboard">Bagikan Foto
                    </h2>
                    <p class="cp-modal-sub" data-translate="upload_photo_desc" data-translate-page="dashboard">Unggah Gambar
                        Untuk Postingan Anda</p>
                </div>
                <button class="cp-close-btn" onclick="cpCloseModal('cpModalPhoto')">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="cp-modal-body">
                <form method="POST" action="{{ route('postingan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="post_type" value="photo">
                    <input type="hidden" name="items[0][type]" value="image">
                    <div class="cp-field">
                        <label class="cp-label" data-translate="title" data-translate-page="dashboard">Judul <span
                                class="cp-req">*</span></label>
                        <input type="text" name="judul" data-translate-placeholder="photo_title_placeholder"
                            data-translate-page="dashboard" class="cp-input">
                    </div>
                    <div class="cp-field">
                        <label class="cp-label" data-translate="description" data-translate-page="dashboard">Deskripsi <span
                                class="cp-opt">(opsional)</span></label>
                        <textarea name="deskripsi" class="cp-textarea" rows="3"
                            data-translate-placeholder="photo_description_placeholder"
                            data-translate-page="dashboard"></textarea>
                    </div>
                    <div class="cp-field">
                        <label class="cp-label" data-translate="upload_photo" data-translate-page="dashboard">Upload Foto
                            <span class="cp-req">*</span></label>
                        <label class="cp-upload-label" for="cp-file-photo">
                            <svg class="cp-upload-icon w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="cp-upload-main" data-translate="click_to_upload" data-translate-page="dashboard">Klik
                                untuk pilih foto</p>
                            <p class="cp-upload-hint" data-translate="file_hint" data-translate-page="dashboard">JPG, PNG,
                                GIF, WEBP — maks 5MB</p>
                        </label>
                        <input type="file" id="cp-file-photo" name="items[0][file]" accept="image/*"
                            onchange="cpPreviewImage(event,'cp-preview-photo-img','cp-preview-photo-wrap')">
                        <div class="cp-preview-wrap" id="cp-preview-photo-wrap">
                            <img id="cp-preview-photo-img" src="" alt="Preview foto">
                            <button type="button" class="cp-preview-remove"
                                onclick="cpRemovePreview('cp-preview-photo-img','cp-preview-photo-wrap','cp-file-photo')">✕</button>
                        </div>
                    </div>
                    <div class="cp-modal-footer" style="padding-left:0;padding-right:0;border-top:none;margin-top:1rem;">
                        <button type="button" class="cp-btn-cancel" onclick="cpCloseModal('cpModalPhoto')"
                            data-translate="batal" data-translate-page="dashboard">Batal</button>
                        <button type="submit" class="cp-btn-submit submit-photo" data-translate="post_photo"
                            data-translate-page="dashboard">Posting Foto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================================
    MODAL: Tulis Artikel
    ============================================================ --}}
    <div id="cpModalArtikel" class="cp-modal-overlay" onclick="cpOverlayClick(event,'cpModalArtikel')">
        <div class="cp-modal">
            <div class="cp-modal-header">
                <div>
                    <div class="cp-type-badge badge-article">
                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span data-translate="article" data-translate-page="dashboard">Artikel</span>
                    </div>
                    <h2 class="cp-modal-title" data-translate="write_article_title" data-translate-page="dashboard">Tulis
                        Artikel</h2>
                    <p class="cp-modal-sub" data-translate="write_article_desc" data-translate-page="dashboard">Buat konten
                        panjang yang informatif</p>
                </div>
                <button class="cp-close-btn" onclick="cpCloseModal('cpModalArtikel')">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="cp-modal-body">
                <form method="POST" action="{{ route('postingan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="post_type" value="article">
                    <div class="cp-field">
                        <label class="cp-label" data-translate="title" data-translate-page="dashboard">Judul <span
                                class="cp-req">*</span></label>
                        <input type="text" name="judul" data-translate-placeholder="article_title_placeholder"
                            data-translate-page="dashboard" class="cp-input">
                    </div>
                    <div class="cp-field">
                        <label class="cp-label" data-translate="description" data-translate-page="dashboard">Deskripsi <span
                                class="cp-opt">(opsional)</span></label>
                        <textarea name="deskripsi" class="cp-textarea" rows="4"
                            data-translate-placeholder="article_description_placeholder"
                            data-translate-page="dashboard"></textarea>
                    </div>
                    <div class="cp-field">
                        <label class="cp-label" data-translate="link_reference" data-translate-page="dashboard">Link /
                            Referensi <span class="cp-opt">(opsional)</span></label>
                        <div class="cp-link-wrap">
                            <input type="text" name="items[0][content]" class="cp-link-field"
                                data-translate-placeholder="article_link_placeholder" data-translate-page="dashboard">
                        </div>
                        <input type="hidden" name="items[0][type]" value="link">
                        <p class="cp-link-hint" data-translate="article_link_hint" data-translate-page="dashboard">Sertakan
                            sumber referensi artikel jika ada</p>
                    </div>
                    <div class="cp-modal-footer" style="padding-left:0;padding-right:0;border-top:none;margin-top:1rem;">
                        <button type="button" class="cp-btn-cancel" onclick="cpCloseModal('cpModalArtikel')"
                            data-translate="batal" data-translate-page="dashboard">Batal</button>
                        <button type="submit" class="cp-btn-submit submit-article" data-translate="create_article"
                            data-translate-page="dashboard">Buat Artikel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // =============================================
        // Global variables (from file-1)
        // =============================================
        window.currentUserId = {{ auth()->id() ?? 'null' }};
        window.currentUserPhoto = "{{ auth()->user()?->photo_profile ?? '' }}";
        window.currentUserName = "{{ auth()->user()?->nama_mahasiswa ?? '' }}";
        window.locale = document.querySelector('html').getAttribute('lang') || 'id';
    </script>
@endsection