@extends('Layout.Layout')
@section('show_footer', true)
@section('show_up_page', true)
@section('title', 'Dashboard')

@section('meta')
    <meta name="description" content="Explore the latest student posts, projects, certifications, and learning corners on the platform.">
@endsection

@section('content')


    <div class=" dark:bg-gray-800 rounded-2xl py-4 sm:py-6 px-3 sm:px-6 lg:px-8" data-dashboard-type="me">
        <div class="max-w-7xl mx-auto">
            <div class="dashboard-container">

                <!-- ════════════════════════════════════════
                     LEFT COLUMN: Posts Feed
                ════════════════════════════════════════ -->
                <div class="feed-column">

                    <div class="mb-6 flex justify-between items-center flex-wrap gap-3">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100"
                            data-translate="perihal_terbaru"
                            data-translate-page="dashboard">
                            Postingan Terbaru
                        </h2>

                        @php
                            $hasAnyGame = $postinganTerbaru->contains(fn($p) => $p->game !== null);
                        @endphp

                        @if($hasAnyGame)
                            <a href="{{ route('game.leaderboard', ['locale' => app()->getLocale()]) }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-400 to-blue-600 hover:from-blue-500 hover:to-blue-700 text-white font-medium rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L15 8.5L22 9.5L17 14L18.5 21L12 17.5L5.5 21L7 14L2 9.5L9 8.5L12 2Z"
                                        stroke="currentColor" stroke-width="1.5" fill="none" />
                                    <path
                                        d="M12 6L13.5 9.5L17.5 10L14.5 12.5L15.5 16.5L12 14.5L8.5 16.5L9.5 12.5L6.5 10L10.5 9.5L12 6Z"
                                        fill="currentColor" />
                                </svg>
                                <span data-translate="game_leaderboard" data-translate-page="dashboard">Peringkat Permainan</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @endif
                    </div>

                    <!-- ══ Postingan Section ══ -->
                    <div id="postingan-section" class="feed-section mb-10" data-pagination-group="postingan">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-1 h-5 bg-indigo-600 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300"
                                data-translate="ur_post"
                                data-translate-page="dashboard">
                                Postingan Mahasiswa
                            </h3>
                        </div>

                        <!-- Search result info — shown when header search is active -->
                        <div id="dashboard-search-result-info"
                            class="mb-4 items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>
                                <span id="dashboard-search-count"
                                    class="font-semibold text-indigo-600 dark:text-indigo-400">0</span>
                                <span data-translate="posts_found" data-translate-page="dashboard">postingan ditemukan</span>
                            </span>
                        </div>

                        <!-- Skeleton -->
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

                        <!-- Content -->
                        <div id="postingan-content-wrapper" class="hidden">
                            @if($postinganTerbaru->isEmpty())
                                <div
                                    class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                    <p class="text-gray-500 dark:text-gray-400"
                                        data-translate="empty_student_posts"
                                        data-translate-page="dashboard">
                                        Belum ada postingan mahasiswa
                                    </p>
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
                                                        if ($item['type'] === 'title')
                                                            $postTitle = strip_tags($item['content'] ?? '');
                                                        elseif ($item['type'] === 'description')
                                                            $postDescription = strip_tags($item['content'] ?? '');
                                                    }
                                                }
                                            }
                                            $commentCount = \App\Models\Komentar::getCommentCount($post->id_postingan);
                                        @endphp
                                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 post-card relative"
                                            data-post-title="{{ strtolower($postTitle) }}"
                                            data-post-description="{{ strtolower($postDescription) }}"
                                            data-post-author="{{ strtolower($post->user->nama_mahasiswa ?? '') }}"
                                            data-post-id="{{ $post->id_postingan }}"
                                            data-share-url="{{ route('postingan.show', ['locale' => app()->getLocale(), 'id' => $post->id_postingan]) }}">

                                            <!-- Header -->
                                            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                                <div class="flex items-center gap-3">
                                                    <a href="{{ route('portfolio.slug', ['slug' => $post->user->slug]) }}"
                                                        class="flex items-center gap-3">
                                                        <img src="{{ asset('storage/' . $post->user->photo_profile) }}"
                                                            class="w-10 h-10 rounded-full object-cover"
                                                            alt="{{ $post->user->username }}"
                                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        <div
                                                            class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 items-center justify-center hidden">
                                                            <span
                                                                class="text-indigo-600 dark:text-indigo-400 font-semibold text-sm">
                                                                {{ strtoupper(substr($post->user->username ?? 'U', 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100">
                                                                {{ $post->user->nama_mahasiswa }}</h4>
                                                            <div class="flex flex-wrap gap-2 mt-1">
                                                                @if(!empty($post->user->jurusan))
                                                                    <span
                                                                        class="text-[14px] px-2 py-1 bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300 rounded-full">{{ $post->user->jurusan['nama_jurusan'] ?? '-' }}</span>
                                                                @endif
                                                                @if(!empty($post->user->angkatan))
                                                                    <span
                                                                        class="text-[14px] px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded-full">{{ $post->user->angkatan['nama_angkatan'] ?? '-' }}</span>
                                                                @endif
                                                            </div>
                                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                                {{ $post->tanggal?->translatedFormat('d M Y') ?? $post->created_at?->translatedFormat('d M Y') }}
                                                            </p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- Body -->
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
                                                                elseif ($item['type'] === 'description')
                                                                    $deskripsi = $item['content'] ?? '';
                                                                elseif ($item['type'] === 'image' && !empty($item['content']) && !$imageUrl)
                                                                    $imageUrl = asset('storage/' . ltrim($item['content'], '/'));
                                                            }
                                                        }
                                                    }

                                                    $game = $post->game ?? null;
                                                    $gameThumbnailMap = ['matematika' => 'assets/game-angka.svg', 'math' => 'assets/game-angka.svg', 'puzzle' => 'assets/game-puzzle.svg', 'tts' => 'assets/game-tts.svg', 'teka-teki silang' => 'assets/game-tts.svg'];
                                                    $gameThumbnail = ($game && isset($gameThumbnailMap[strtolower($game->game_name)])) ? $gameThumbnailMap[strtolower($game->game_name)] : '';
                                                @endphp

                                                @if($title)
                                                    <h3
                                                        class="font-semibold text-[22px] text-gray-900 dark:text-gray-100 mb-3 line-clamp-2">
                                                            {{ $title }}
                                                    </h3>
                                                @endif

                                                @if($imageUrl)
                                                    <div class="mb-4 rounded-xl overflow-hidden">
                                                        <img src="{{ $imageUrl }}"
                                                            class="w-full h-auto object-cover hover:scale-105 transition-transform duration-300"
                                                            loading="lazy">
                                                    </div>
                                                @endif

                                                @if($deskripsi)
                                                    <p class="text-[20px] text-gray-600 dark:text-gray-300 line-clamp-4">
                                                        {{ Str::limit(strip_tags($deskripsi), 180) }}
                                                    </p>
                                                @endif
                                            </div>

                                            <!-- Footer -->
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
                                                        <span class="comment-count text-sm">
                                                            <a href="{{ route('portfolio.slug', ['slug' => $post->user->slug]) }}">{{ $commentCount }}</a>
                                                        </span>
                                                       
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
                                                    onclick="window.location.href='{{ route('postingan.show', ['id' => $post->id_postingan]) }}'"
                                                    class="text-xs text-gray-500 dark:text-gray-400 cursor-pointer hover:text-indigo-600 transition"
                                                    data-translate="lihat_detail"
                                                    data-translate-page="dashboard">
                                                    Lihat detail →
                                                </span>
                                            </div>

                                            <!-- Comment Section -->
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

                    <!-- ══ Project Section ══ -->
                    <div id="projects-section" class="feed-section mb-10" data-pagination-group="project">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-orange-600 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-orange-600 dark:text-orange-300"
                                data-translate="project"
                                data-translate-page="dashboard">
                                Project
                            </h3>
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
                                <div
                                    class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                                    <p class="text-gray-500 dark:text-gray-400"
                                        data-translate="empty_project"
                                        data-translate-page="dashboard">
                                        Belum ada project
                                    </p>
                                </div>
                            @else
                                <div id="project-container" class="space-y-4">
                                    @foreach($projects as $post)
                                        <div class="project-card">@include('components.card_postingan', ['post' => $post])</div>
                                    @endforeach
                                </div>
                                <div class="mt-6">
                                    {{ $projects->render('vendor.pagination.custom_ajax', ['groupName' => 'project']) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- ══ Sertifikat Section ══ -->
                    <div id="sertifikat-section" class="feed-section"  data-pagination-group="sertifikat">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-green-600 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-green-700 dark:text-green-300"
                                data-translate="sertifikat"
                                data-translate-page="dashboard">
                                Sertifikat
                            </h3>
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
                                    class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                                    <p class="text-gray-500 dark:text-gray-400"
                                        data-translate="empty_certificate"
                                        data-translate-page="dashboard">
                                        Belum ada sertifikat
                                    </p>
                                </div>
                            @else
                                <div id="sertifikat-container" class="space-y-4">
                                    @foreach($projectUsers as $post)
                                        <div class="sertifikat-card">@include('components.card_postingan', ['post' => $post])</div>
                                    @endforeach
                                </div>
                                <div class="mt-6">
                                    {{ $projectUsers->render('vendor.pagination.custom_ajax', ['groupName' => 'sertifikat']) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- ════════════════════════════════════════
                     RIGHT COLUMN: Sidebar
                ════════════════════════════════════════ -->
                <div class="sidebar-column">
                    <div 
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-200 dark:border-gray-700 sticky top-6" data-pagination-group="learning_corner">
                        <div id="learning-corner-sidebar" class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-purple-600 rounded-full"></div>
                            <h3 class="text-base font-semibold text-purple-700 dark:text-purple-300"
                                data-translate="learning_corner"
                                data-translate-page="dashboard">
                                Learning Corner
                            </h3>
                        </div>

                        <div id="learning-skeleton" class="space-y-3">
                            @for($i = 0; $i < 4; $i++)
                                <div class="skeleton skeleton-text w-full"></div>
                            @endfor
                        </div>

                        <div id="learning-content-wrapper" class="hidden">
                            @if($learningCorners->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400 text-sm"
                                    data-translate="empty_learning_corner"
                                    data-translate-page="dashboard">
                                    Belum ada Learning Corner
                                </p>
                            @else
                                <div id="learning-corner-list" class="space-y-4 overflow-y-auto max-h-96">
                                    @foreach($learningCorners->take(5) as $learning)
                                        @php
                                            $contentArray = $learning->content_translated ?? [];
                                            $firstTitle = 'Learning Content';

                                            if (is_array($contentArray)) {
                                                foreach ($contentArray as $item) {
                                                    if (($item['type'] ?? '') === 'title') {
                                                        $firstTitle = $item['content'] ?? 'Learning Content';
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp

                                        <div onclick="window.location.href='{{ route('project.show', ['id' => $learning->project_id]) }}'"
                                            class="cursor-pointer dark:border-gray-700 border hover:bg-gray-50 dark:hover:bg-gray-700 p-3 rounded-lg transition">
                                            <h4 class="text-sm dark:text-gray-100 font-medium line-clamp-2">
                                                {{ $firstTitle }}
                                            </h4>
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

                        <!-- Dosen Carousel -->
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-1 h-5 bg-blue-600 rounded-full"></div>
                                <h3 class="text-base font-semibold text-blue-700 dark:text-blue-300">
                                    {{ $dosenList->count() }}
                                    <span data-translate="dosen" data-translate-page="dashboard">Dosen</span>
                                </h3>
                            </div>

                            @if($dosenList->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4"
                                    data-translate="empty_dosen"
                                    data-translate-page="dashboard">
                                    Belum ada data dosen
                                </p>
                            @else
                                <div class="relative w-full max-w-xl mx-auto">
                                    <div class="overflow-hidden relative h-64 flex items-center justify-center">
                                        <div id="dosenCarouselTrack"
                                            class="relative w-full h-full flex items-center justify-center">
                                            @php $dosenArray = $dosenList->values()->all(); @endphp
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
                                                    <p
                                                        class="dosen-name text-center dark:text-gray-100 mt-4 font-semibold opacity-0 transition-all duration-300">
                                                        {{ $dosen->nama_mahasiswa ?? 'Dosen' }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <button id="dosenPrevBtn"
                                        class="absolute left-0 top-1/2 -translate-y-1/2 bg-white dark:bg-gray-700 p-2 rounded-full shadow z-10 text-gray-700 dark:text-gray-300">←</button>
                                    <button id="dosenNextBtn"
                                        class="absolute right-0 top-1/2 -translate-y-1/2 bg-white dark:bg-gray-700 p-2 rounded-full shadow z-10 text-gray-700 dark:text-gray-300">→</button>
                                    <div class="w-24 overflow-hidden mx-auto mt-4">
                                        <div class="flex gap-2 transition-transform duration-300" id="dosenDotsTrack">
                                            @foreach($dosenArray as $index => $dosen)
                                                <span
                                                    class="dot w-3 h-3 bg-gray-300 dark:bg-gray-600 rounded-full cursor-pointer flex-shrink-0 transition-all duration-300 opacity-40 scale-90"
                                                    data-index="{{ $index }}"></span>
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
        // ── Global vars ──
        window.currentUserId = {{ auth()->id() ?? 'null' }};
        window.currentUserPhoto = "{{ auth()->user()?->photo_profile ?? '' }}";
        window.currentUserName = "{{ auth()->user()?->nama_mahasiswa ?? '' }}";
        window.locale = document.querySelector('html').getAttribute('lang') || 'id';
    </script>
@endsection