{{-- Component untuk menampilkan card postingan dalam grid --}}
<div
    class="flex flex-col rounded-lg sm:rounded-xl overflow-hidden border border-gray-100 dark:border-gray-900 bg-white dark:bg-gray-900 shadow-sm hover:shadow-md transition-all duration-300 p-2 sm:p-3 md:p-4 lg:p-6 h-full">
    @php
        // Decode JSON content untuk semua tipe
        $projectData = [];
        if (($post->type === 'project' || $post->type === 'project_user') && $post->isi_content) {
            $projectData = is_string($post->isi_content) ? json_decode($post->isi_content, true) : (is_array($post->isi_content) ? $post->isi_content : []);
        }

        // Untuk learning corner, ambil data dari tabel learning_corner
        $learningContent = [];
        $learningImages = [];
        $learningTitle = '';
        $learningText = '';
        $learningLinks = [];

        // Data user untuk learning corner
        $userPhoto = null;
        $userName = 'Pengguna';
        $userId = null;
        $userSlug = null;

        // Data untuk project terkait
        $relatedProject = null;
        $relatedProjectData = [];

        // Data untuk leader/owner project
        $leaderPhoto = null;
        $leaderName = '';
        $leaderId = null;
        $isLeaderAvailable = false;
        $ownerPhoto = null;
        $ownerName = '';
        $ownerId = null;

        if ($post->type === 'learning') {
            if (isset($post->content) && is_array($post->content)) {
                $learningContent = $post->content;
                foreach ($learningContent as $item) {
                    if ($item['type'] === 'title') {
                        $learningTitle = $item['content'] ?? '';
                    } elseif ($item['type'] === 'text') {
                        $learningText = $item['content'] ?? '';
                    } elseif ($item['type'] === 'image') {
                        $learningImages[] = $item;
                    } elseif ($item['type'] === 'link') {
                        $learningLinks[] = $item;
                    }
                }
            }

            if (isset($post->mahasiswa)) {
                $userPhoto = $post->mahasiswa->photo_profile;
                $userName = $post->mahasiswa->nama_mahasiswa ?? 'Pengguna';
                $userId = $post->mahasiswa->id;
                $userSlug  = $post->mahasiswa->slug; 
            }

            if (isset($post->project)) {
                $relatedProject = $post->project;
                if ($relatedProject && $relatedProject->isi_content) {
                    $relatedProjectData = is_string($relatedProject->isi_content) ?
                        json_decode($relatedProject->isi_content, true) :
                        (is_array($relatedProject->isi_content) ? $relatedProject->isi_content : []);
                }

                if ($relatedProject && isset($relatedProject->leader) && $relatedProject->leader) {
                    $leaderPhoto = $relatedProject->leader->photo_profile;
                    $leaderName = $relatedProject->leader->nama_mahasiswa ?? 'Leader';
                    $leaderId = $relatedProject->leader->id;
                    $isLeaderAvailable = true;
                }

                if ($relatedProject && isset($relatedProject->mahasiswa) && $relatedProject->mahasiswa) {
                    $ownerPhoto = $relatedProject->mahasiswa->photo_profile;
                    $ownerName = $relatedProject->mahasiswa->nama_mahasiswa ?? 'Owner';
                    $ownerId = $relatedProject->mahasiswa->id;
                }
            }
        } elseif ($post->type === 'project' || $post->type === 'project_user') {
            if ($post->mahasiswa) {
                $userPhoto = $post->mahasiswa->photo_profile;
                $userName = $post->mahasiswa->nama_mahasiswa ?? 'Pengguna';
                $userId = $post->mahasiswa->id;
                $userSlug  = $post->mahasiswa->slug;
            }
        } elseif ($post->type === 'sertifikat') {
            if ($post->mahasiswa) {
                $userPhoto = $post->mahasiswa->photo_profile;
                $userName = $post->mahasiswa->nama_mahasiswa ?? 'Pengguna';
                $userId = $post->mahasiswa->id;
                $userSlug  = $post->mahasiswa->slug;
            }
        }

        $nama_project = $projectData['nama_project'] ?? ($relatedProjectData['nama_project'] ?? '(Nama Project Tidak Tersedia)');
        $link_project = $projectData['link_project'] ?? $relatedProjectData['link_project'] ?? '';
        $link_github = $projectData['link_github'] ?? $relatedProjectData['link_github'] ?? '';
        $link_video = $projectData['link_video'] ?? $relatedProjectData['link_video'] ?? '';

        $youtube_id = '';
        $isValidVideo = false;

        if ($link_video && !empty(trim($link_video))) {
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $link_video, $matches)) {
                $youtube_id = $matches[1];
                $isValidVideo = true;
            } elseif (preg_match('/\.(mp4|webm|ogg)$/i', $link_video)) {
                $isValidVideo = true;
            }
        }

        // Unique ID untuk video element di card ini
        $videoCardId = 'video-card-' . ($post->id_postingan ?? $post->id ?? uniqid());

        $displayPhoto = $isLeaderAvailable ? $leaderPhoto : $ownerPhoto;
        $displayName = $isLeaderAvailable ? $leaderName : $ownerName;
        $displayId = $isLeaderAvailable ? $leaderId : $ownerId;
        // $displayRole tidak digunakan lagi, kita pakai data-translate di HTML
    @endphp

    <!-- User Info -->
    <a href="{{ $userSlug ? route('portfolio.slug', ['slug' => $userSlug]) : '#' }}"
        class="flex items-center gap-1.5 sm:gap-2 mb-2 sm:mb-3 hover:opacity-80 transition-opacity min-w-0">
        <div
            class="w-7 h-7 sm:w-8 md:w-9 rounded-full overflow-hidden border-2 border-gray-100 shadow-sm shrink-0 relative">
            @if($userPhoto)
                <img src="{{ asset('storage/' . ltrim($userPhoto, '/')) }}" alt="{{ $userName }}"
                    class="w-full h-full object-cover" loading="lazy"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div
                    class="absolute inset-0 hidden bg-gradient-to-br from-indigo-500 to-purple-600 items-center justify-center text-white font-bold text-lg">
                    {{ substr($userName, 0, 1) }}
                </div>
            @else
                <div
                    class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                    {{ substr($userName, 0, 1) }}
                </div>
            @endif
        </div>
        <div class="min-w-0 flex-1">
            <p class="font-semibold text-xs sm:text-sm md:text-base text-gray-900 dark:text-gray-300 truncate">
                {{ $userName }}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-200 truncate">
                {{ $post->created_at?->diffForHumans() ?? $post->tanggal?->diffForHumans() ?? 'Baru Saja' }}
            </p>
        </div>
    </a>

    <!-- Badge -->
    <div class="flex items-center gap-1 sm:gap-2 mb-2 sm:mb-3 flex-wrap">
        @if($post->type === 'learning')
            <span
                class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 w-fit">
                <span data-translate="learning_corner" data-translate-page="post_card">Learning Corner</span>
                @if($relatedProject)
                    <span
                        class="ml-1 text-purple-600">({{ Str::limit($relatedProjectData['nama_project'] ?? 'Project', 20) }})</span>
                @endif
            </span>
        @elseif($post->type === 'project' || $post->type === 'project_user')
            <span
                class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 w-fit">
                <span data-translate="project" data-translate-page="post_card">Project</span>
            </span>
        @elseif($post->type === 'postingan')
            <span
                class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 w-fit">
                <span data-translate="postingan" data-translate-page="post_card">Postingan</span>
            </span>
        @endif
    </div>

    <!-- Konten utama -->
    <div class="flex-1 mt-1 sm:mt-3">
        @if($post->type === 'learning')
            @if($relatedProject)
                <a href="{{ route('project.show', ['id' => $relatedProject->id]) }}"
                    class="block hover:text-indigo-700 transition-colors">
                    <h3
                        class="text-sm sm:text-base md:text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1 sm:mb-2 line-clamp-2 leading-tight">
                        {{ $learningTitle ?: ($relatedProjectData['nama_project'] ?? 'Learning Corner') }}
                    </h3>
                </a>
            @else
                <h3
                    class="text-sm sm:text-base md:text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1 sm:mb-2 line-clamp-2 leading-tight">
                    {{ $learningTitle ?: 'Learning Corner' }}
                </h3>
            @endif

            @if($learningText)
                <div class="relative">
                    <p class="text-gray-700 dark:text-gray-300 mb-1 sm:mb-2 text-xs sm:text-sm line-clamp-3">
                        {{ $learningText }}
                    </p>
                </div>
            @endif

            @if(count($learningImages) > 0)
                <div class="space-y-1.5 sm:space-y-2 mt-1.5 sm:mt-2">
                    @php $firstImage = $learningImages[0]; @endphp
                    @php $imagePath = str_replace(['\\', '/'], '/', $firstImage['content'] ?? ''); @endphp
                    <img src="{{ asset('storage/' . ltrim($imagePath, '/')) }}" alt="{{ $firstImage['alt'] ?? 'Gambar' }}"
                        class="w-full h-24 sm:h-32 md:h-40 object-cover rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm"
                        loading="lazy"
                        onerror="this.src='https://via.placeholder.com/400x200?text={{ urlencode('Gambar Tidak Ditemukan') }}';this.onerror=null;">
                </div>
            @endif

            @if(count($learningLinks) > 0)
                <div class="mt-1 sm:mt-2 space-y-0.5">
                    @foreach($learningLinks as $link)
                        <a href="{{ $link['content'] }}" target="_blank"
                            class="text-indigo-600 hover:text-indigo-800 text-xs sm:text-sm block underline line-clamp-1 break-all">
                            {{ Str::limit($link['content'], 50) }}
                        </a>
                    @endforeach
                </div>
            @endif

            @if($relatedProject)
                <div class="mt-2 sm:mt-3 pt-1.5 sm:pt-2 border-t border-gray-100 dark:border-gray-800">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-0.5 sm:mb-1">
                        <span data-translate="terkait" data-translate-page="post_card">Terkait:</span>
                    </p>
                    <a href="{{ route('project.show', ['id' => $relatedProject->id]) }}"
                        class="text-xs sm:text-sm font-semibold text-gray-800 dark:text-gray-200 hover:text-indigo-600 transition-colors line-clamp-1">
                        {{ Str::limit($relatedProjectData['nama_project'] ?? $relatedProject->nama_project ?? 'Project', 25) }}
                    </a>
                </div>
            @endif

        @elseif($post->type === 'project' || $post->type === 'project_user')
            <!-- Project Title -->
            <a href="{{ route('project.show', ['id' => $post->id]) }}"
                class="block hover:text-indigo-700 transition-colors">
                <h3
                    class="text-sm sm:text-base md:text-lg font-semibold text-gray-900 dark:text-gray-100 line-clamp-2 leading-tight">
                    {{ $nama_project }}
                </h3>
            </a>

            @if(!empty($projectData['deskripsi']))
                <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 mt-1 sm:mt-2 line-clamp-2">
                    {{ $projectData['deskripsi'] }}
                </p>
            @endif

            @if(!empty($projectData['tanggal_mulai']) || $post->tanggal_mulai)
                <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-200 mt-1 sm:mt-2">
                    <p><span class="font-medium"><span data-translate="periode" data-translate-page="post_card">Periode:</span></span>
                        @if(!empty($projectData['tanggal_mulai']))
                            {{ \Carbon\Carbon::parse($projectData['tanggal_mulai'])->translatedFormat('M Y') }}
                            @if(!empty($projectData['tanggal_akhir']))
                                → {{ \Carbon\Carbon::parse($projectData['tanggal_akhir'])->translatedFormat('M Y') }}
                            @else
                                → <span data-translate="sekarang" data-translate-page="post_card">Sekarang</span>
                            @endif
                        @elseif($post->tanggal_mulai)
                            {{ \Carbon\Carbon::parse($post->tanggal_mulai)->translatedFormat('M Y') }}
                            @if($post->tanggal_akhir)
                                → {{ \Carbon\Carbon::parse($post->tanggal_akhir)->translatedFormat('M Y') }}
                            @else
                                → <span data-translate="sekarang" data-translate-page="post_card">Sekarang</span>
                            @endif
                        @endif
                    </p>
                </div>
            @endif

            {{-- ============================================================
                 VIDEO PREVIEW SECTION — Inline Playable
                 ============================================================ --}}
            @if($isValidVideo && $youtube_id)
                {{-- YouTube: tampilkan thumbnail HQ, klik → embed iframe --}}
                <div class="relative mt-3 sm:mt-4 rounded-lg sm:rounded-xl overflow-hidden border border-gray-200 dark:border-gray-800 shadow-sm"
                     style="aspect-ratio: 16/9;">
                    <div class="video-preview-wrapper w-full h-full" id="{{ $videoCardId }}"
                         onclick="playVideoInCard('{{ $videoCardId }}', 'youtube', '{{ $youtube_id }}')">

                        {{-- Thumbnail YouTube kualitas tinggi --}}
                        <img class="yt-thumbnail w-full h-full object-cover"
                             src="https://img.youtube.com/vi/{{ $youtube_id }}/hqdefault.jpg"
                             alt="{{ 'Video ' . $nama_project }}"
                             loading="lazy"
                             onerror="this.src='https://img.youtube.com/vi/{{ $youtube_id }}/0.jpg'">

                        {{-- Play overlay --}}
                        <div class="play-overlay">
                            <div class="play-btn-circle">
                                <svg fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>

                        {{-- iframe embed (tersembunyi sampai diklik) --}}
                        <div class="video-embed-container">
                            {{-- iframe diisi via JS saat diklik agar tidak autoload --}}
                        </div>
                    </div>
                </div>

            @elseif($isValidVideo && !$youtube_id)
                {{-- Video langsung (MP4/WebM/OGG): native player dengan poster --}}
                <div class="relative mt-3 sm:mt-4 rounded-lg sm:rounded-xl overflow-hidden border border-gray-200 dark:border-gray-800 shadow-sm"
                     style="aspect-ratio: 16/9;">
                    <div class="video-preview-wrapper w-full h-full" id="{{ $videoCardId }}"
                         onclick="playVideoInCard('{{ $videoCardId }}', 'direct', '{{ $link_video }}')">

                        {{-- Poster placeholder --}}
                        <div class="yt-thumbnail w-full h-full bg-gray-900 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>

                        {{-- Play overlay --}}
                        <div class="play-overlay">
                            <div class="play-btn-circle">
                                <svg fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>

                        {{-- video element container (tersembunyi sampai diklik) --}}
                        <div class="video-embed-container">
                            {{-- video diisi via JS saat diklik --}}
                        </div>
                    </div>
                </div>

            @else
                <!-- Preview Kosong ketika tidak ada video -->
                <div class="relative mt-3 sm:mt-4 rounded-lg sm:rounded-xl overflow-hidden border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800/50">
                    <div class="flex flex-col items-center justify-center py-6 sm:py-8 px-4 text-center">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400 dark:text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                            <span data-translate="tidak_ada_video_preview" data-translate-page="post_card">Tidak ada video preview</span>
                        </p>
                        @if($link_video && !empty(trim($link_video)))
                            <a href="{{ $link_video }}" target="_blank"
                                class="mt-2 text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">
                                <span data-translate="lihat_video" data-translate-page="post_card">Lihat Video</span> →
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Links -->
            @if($link_project || $link_github || $link_video)
                <div class="flex flex-wrap gap-1 sm:gap-3 text-xs sm:text-sm mt-2 sm:mt-3">
                    @if($link_project)
                        <a href="{{ $link_project }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex hover:underline items-center text-orange-600 hover:text-orange-800 font-medium transition-colors">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            <span data-translate="demo" data-translate-page="post_card">Demo</span>
                        </a>
                    @endif

                    @if($link_github)
                        <a href="{{ $link_github }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex hover:underline items-center text-orange-600 hover:text-orange-800 font-medium transition-colors">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                            </svg>
                            <span data-translate="github" data-translate-page="post_card">GitHub</span>
                        </a>
                    @endif

                    @if($link_video)
                        <a href="{{ $link_video }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex hover:underline items-center text-orange-600 hover:text-orange-800 font-medium transition-colors">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span data-translate="buka_video" data-translate-page="post_card">Buka Video</span>
                        </a>
                    @endif
                </div>
            @endif

        @elseif($post->type === 'postingan')
            @php
                $content = $post->content ?? $post->isi_content ?? [];
                $title = '';
                $deskripsi = '';
                if (is_array($content)) {
                    foreach ($content as $item) {
                        if (isset($item['type']) && $item['type'] === 'title') $title = $item['content'] ?? '';
                        if (isset($item['type']) && $item['type'] === 'description') $deskripsi = $item['content'] ?? '';
                    }
                } else if (is_string($content)) {
                    $decoded = json_decode($content, true);
                    if (is_array($decoded)) {
                        foreach ($decoded as $item) {
                            if (isset($item['type']) && $item['type'] === 'title') $title = $item['content'] ?? '';
                            if (isset($item['type']) && $item['type'] === 'description') $deskripsi = $item['content'] ?? '';
                        }
                    }
                }
            @endphp

            @if($title)
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2 text-sm sm:text-base md:text-lg">{{ $title }}</h3>
            @endif
            @if($deskripsi)
                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">{{ Str::limit($deskripsi, 150) }}</p>
            @endif

            @php
                $game = null;
                if (method_exists($post, 'game')) {
                    $game = $post->game ?? null;
                }
                $thumbItem = null;
                if (is_array($content)) {
                    foreach ($content as $c) {
                        if (isset($c['type']) && $c['type'] === 'game_thumbnail') {
                            $thumbItem = $c['content'] ?? null;
                            break;
                        }
                    }
                }
            @endphp

            @if($game || $thumbItem)
                <div class="mt-3 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-800 shadow-sm">
                    @if($thumbItem)
                        <img src="{{ asset('storage/' . ltrim($thumbItem, '/')) }}" alt="Thumbnail Game" class="w-full h-36 object-cover">
                    @else
                        <div class="w-full h-36 bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-500">
                            <span data-translate="preview_game" data-translate-page="post_card">Preview Game</span>
                        </div>
                    @endif
                    <div class="p-3 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $game->game_name ?? 'Game' }}</div>
                            <div class="text-xs text-gray-500">
                                <span data-translate="mainkan_game" data-translate-page="post_card">Mainkan game langsung dari postingan</span>
                            </div>
                        </div>
                        <div>
                            @php $playUrl = route('game.matematika', ['locale' => app()->getLocale()]) . '?postingan=' . ($post->id_postingan ?? $post->id) . ($game ? '&game=' . $game->id_games : ''); @endphp
                            <a href="{{ $playUrl }}" class="inline-flex items-center px-3 py-1.5 bg-teal-600 text-white rounded-full hover:bg-teal-700">
                                <span data-translate="play" data-translate-page="post_card">Play</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Footer Actions -->
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex items-center justify-between -mx-4 -mb-4 mt-4 rounded-b-lg sm:rounded-b-xl">
                <div class="flex items-center gap-6">
                    <!-- Like -->
                    @auth
                        <button class="like-btn flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-red-500 transition"
                                data-postingan-id="{{ $post->id_postingan ?? $post->id }}">
                            <svg class="w-5 h-5 {{ $post->likes && $post->likes->where('id_user', auth()->id())->count() > 0 ? 'fill-current text-red-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span class="like-count text-sm">{{ $post->likes ? $post->likes->count() : 0 }}</span>
                        </button>
                    @else
                        <button onclick="window.location.href='{{ route('login') }}'" class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span class="text-sm">{{ $post->likes ? $post->likes->count() : 0 }}</span>
                        </button>
                    @endauth

                    <!-- Comment Button -->
                    <button onclick="toggleComments(this)"
                            class="comment-toggle flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-blue-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <span class="text-sm">{{ $post->komentar ? $post->komentar->count() : 0 }}</span>
                    </button>
                </div>
                <span onclick="window.location.href='{{ route('postingan.index', $post->id_postingan ?? $post->id) }}'"
                      class="text-xs text-gray-500 dark:text-gray-400 cursor-pointer hover:text-indigo-600">
                    <span data-translate="lihat_detail" data-translate-page="post_card">Lihat detail</span>
                </span>
            </div>

            <!-- Comments Section (Hidden by default) -->
            <div class="comment-section px-4 pb-4 bg-white dark:bg-gray-800" id="comments-{{ $post->id_postingan ?? $post->id }}">
                @auth
                    <form class="comment-form mb-4" data-postingan-id="{{ $post->id_postingan ?? $post->id }}">
                        @csrf
                        <div class="flex gap-3">
                            @if(auth()->user()->photo_profile && file_exists(public_path('storage/' . auth()->user()->photo_profile)))
                                <img src="{{ asset('storage/' . auth()->user()->photo_profile) }}" class="w-8 h-8 rounded-full object-cover mt-1" alt="Foto Profil">
                            @else
                                <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center mt-1">
                                    <span class="text-indigo-600 dark:text-indigo-400 text-sm font-semibold">
                                        {{ strtoupper(substr(auth()->user()->nama_mahasiswa ?? auth()->user()->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div class="flex-1">
                                <textarea name="komentar" rows="2"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none resize-y text-sm"
                                    placeholder="Tulis komentar..."></textarea>
                                <div class="flex justify-end mt-2">
                                    <button type="submit"
                                        class="px-5 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition submit-btn"
                                        data-current-user-id="{{ auth()->id() }}">
                                        <span data-translate="kirim" data-translate-page="post_card">Kirim</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-3">
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">
                            <span data-translate="login" data-translate-page="post_card">Masuk</span>
                        </a>
                        <span data-translate="untuk_berkomentar" data-translate-page="post_card">untuk berkomentar</span>
                    </p>
                @endauth

                <div class="comment-list space-y-4 max-h-96 overflow-y-auto pr-2">
                    @if($post->komentar && $post->komentar->count() > 0)
                        @foreach($post->komentar->sortByDesc('tanggal') as $komentar)
                            <div class="flex gap-3 comment-item {{ auth()->check() && auth()->id() === $komentar->id_user ? 'user-comment p-3 rounded-lg' : '' }}"
                                 data-comment-id="{{ $komentar->id_komentar }}"
                                 data-user-id="{{ $komentar->id_user }}">
                                @if($komentar->user->photo_profile && file_exists(public_path('storage/' . $komentar->user->photo_profile)))
                                    <img src="{{ asset('storage/' . $komentar->user->photo_profile) }}" class="w-8 h-8 rounded-full object-cover mt-0.5" alt="Foto Profil">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center mt-0.5">
                                        <span class="text-gray-600 dark:text-gray-400 text-sm">
                                            {{ strtoupper(substr($komentar->user->nama_mahasiswa ?? $komentar->user->name ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-sm">{{ $komentar->user->nama_mahasiswa ?? $komentar->user->name }}</span>
                                        @if(auth()->check() && auth()->id() === $komentar->id_user)
                                            <span class="user-comment-badge"><span data-translate="anda" data-translate-page="post_card">Anda</span></span>
                                        @endif
                                        <span class="text-xs text-gray-500">{{ $komentar->tanggal ? $komentar->tanggal->translatedFormat('d M Y') : 'Baru' }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-0.5">{{ $komentar->komentar }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center py-4">
                            <span data-translate="belum_ada_komentar" data-translate-page="post_card">Belum ada komentar</span>
                        </p>
                    @endif
                </div>
            </div>

        @elseif($post->type === 'sertifikat')
            <h3
                class="text-sm sm:text-base md:text-lg font-semibold text-gray-900 dark:text-gray-100 line-clamp-2 leading-tight mb-1 sm:mb-2">
                {{ $post->nama_sertifikat }}
            </h3>

            @if($post->lembaga_penerbit)
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mb-2">
                    <span class="font-medium"><span data-translate="lembaga" data-translate-page="post_card">Lembaga:</span></span> {{ $post->lembaga_penerbit }}
                </p>
            @endif

            @if($post->tanggal_terbit)
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mb-2 sm:mb-3">
                    <span class="font-medium"><span data-translate="terbit" data-translate-page="post_card">Terbit:</span></span> {{ \Carbon\Carbon::parse($post->tanggal_terbit)->translatedFormat('d M Y') }}
                </p>
            @endif

            @php
                $expiredAt = $post->expired_date ? \Carbon\Carbon::parse($post->expired_date) : null;
                $validityStatus = $expiredAt
                    ? ($expiredAt->isFuture() || $expiredAt->isToday()
                        ? 'Valid'
                        : 'Expired')
                    : 'Permanent';
                $validityClass = $expiredAt
                    ? ($expiredAt->isFuture() || $expiredAt->isToday() ? 'text-green-800' : 'text-red-800')
                    : 'text-indigo-800';
                $expiredText = $expiredAt ? $expiredAt->translatedFormat('d M Y') : null;
            @endphp

            <div class="flex items-center gap-2 mb-3">
                @if($post->status_pengajuan === 'Di Terima')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        ✓ Di Terima
                    </span>
                @elseif($post->status_pengajuan === 'Ditolak')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        ✗ Di Tolak
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        ⊙ Dalam Proses
                    </span>
                @endif
            </div>

            <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mb-3">
                <span class="font-medium"><span data-translate="status_berlaku" data-translate-page="post_card">Status Berlaku:</span></span>
                <span class="ml-1 font-medium {{ $validityClass }}">{{ $validityStatus }}</span>
                @if($expiredText)
                    | <span class="font-medium"><span data-translate="kadaluarsa" data-translate-page="post_card">Kadaluarsa:</span></span> {{ $expiredText }}
                @endif
            </div>

            @if($post->link_sertifikat)
                <div class="mt-3 sm:mt-4">
                    <a href="{{ asset('storage/' . $post->link_sertifikat) }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex hover:underline items-center text-green-600 hover:text-green-800 font-medium text-xs sm:text-sm transition-colors">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span data-translate="lihat_sertifikat" data-translate-page="post_card">Lihat Sertifikat</span>
                    </a>
                </div>
            @endif
        @endif
    </div>

    <!-- Footer -->
    <div class="mt-3 sm:mt-4 pt-2 sm:pt-4 border-t border-gray-100 dark:border-gray-900">
        @if($post->type === 'project' || $post->type === 'project_user')
            <div class="flex justify-between items-center">
                <p class="text-xs text-gray-500 dark:text-gray-50">
                    <span data-translate="diposting" data-translate-page="post_card">Diposting</span>
                    {{ $post->created_at?->translatedFormat('d M Y H:i') ?? $post->tanggal?->translatedFormat('d M Y') ?? '—' }}
                    <span data-translate="oleh" data-translate-page="post_card">oleh</span>
                    {{ $userName }}
                </p>
                <div class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>{{ $post->unique_views_count ?? 0 }} </span>
                </div>
            </div>
        @else
            <p class="text-xs text-gray-500 dark:text-gray-50 line-clamp-2">
                <span data-translate="diposting" data-translate-page="post_card">Diposting</span>
                {{ $post->created_at?->translatedFormat('d M Y H:i') ?? $post->tanggal?->translatedFormat('d M Y') ?? '—' }}
                <span data-translate="oleh" data-translate-page="post_card">oleh</span>
                {{ $userName }}
                @if($relatedProject)
                    <span data-translate="untuk_project" data-translate-page="post_card">untuk project</span>
                    <a href="{{ route('project.show', ['id' => $relatedProject->id]) }}"
                        class="text-indigo-600 hover:text-indigo-800 transition-colors font-medium">
                        {{ Str::limit($relatedProjectData['nama_project'] ?? 'Project', 30) }}
                    </a>
                @endif
            </p>
        @endif
    </div>
</div>