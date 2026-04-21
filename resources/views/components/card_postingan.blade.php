{{-- Component untuk menampilkan card postingan dalam grid --}}
<style>
    .comment-section {
        display: none;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid rgb(229 231 235);
    }
    .dark .comment-section {
        border-top-color: rgb(55 65 81);
    }

    .comment-section.active {
        display: block;
    }

    .user-comment {
        background-color: rgb(229 231 235);
    }
    .dark .user-comment {
        background-color: rgb(75 85 99);
    }

    .user-comment-badge {
        display: inline-block;
        background-color: rgb(99 102 241);
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 600;
        margin-left: 8px;
    }
</style>
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
            // Ambil data dari learning_corner
            if (isset($post->content) && is_array($post->content)) {
                $learningContent = $post->content;
                // Pisahkan konten berdasarkan tipe
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

            // Data user pembuat learning corner
            if (isset($post->mahasiswa)) {
                $userPhoto = $post->mahasiswa->photo_profile;
                $userName = $post->mahasiswa->nama_mahasiswa ?? autoTranslate('Pengguna');
                $userId = $post->mahasiswa->id;
            }

            // Data project terkait
            if (isset($post->project)) {
                $relatedProject = $post->project;
                if ($relatedProject && $relatedProject->isi_content) {
                    $relatedProjectData = is_string($relatedProject->isi_content) ?
                        json_decode($relatedProject->isi_content, true) :
                        (is_array($relatedProject->isi_content) ? $relatedProject->isi_content : []);
                }

                // Ambil data leader dari project jika ada
                if ($relatedProject && isset($relatedProject->leader) && $relatedProject->leader) {
                    $leaderPhoto = $relatedProject->leader->photo_profile;
                    $leaderName = $relatedProject->leader->nama_mahasiswa ?? autoTranslate('Leader');
                    $leaderId = $relatedProject->leader->id;
                    $isLeaderAvailable = true;
                }

                // Ambil data owner (pembuat project) dari id_mahasiswa
                if ($relatedProject && isset($relatedProject->mahasiswa) && $relatedProject->mahasiswa) {
                    $ownerPhoto = $relatedProject->mahasiswa->photo_profile;
                    $ownerName = $relatedProject->mahasiswa->nama_mahasiswa ?? autoTranslate('Owner');
                    $ownerId = $relatedProject->mahasiswa->id;
                }
            }
        } elseif ($post->type === 'project' || $post->type === 'project_user') {
            // Data untuk project
            if ($post->mahasiswa) {
                $userPhoto = $post->mahasiswa->photo_profile;
                $userName = $post->mahasiswa->nama_mahasiswa ?? autoTranslate('Pengguna');
                $userId = $post->mahasiswa->id;
            }
        } elseif ($post->type === 'sertifikat') {
            // Data untuk sertifikat
            if ($post->mahasiswa) {
                $userPhoto = $post->mahasiswa->photo_profile;
                $userName = $post->mahasiswa->nama_mahasiswa ?? autoTranslate('Pengguna');
                $userId = $post->mahasiswa->id;
            }
        }

        // Data untuk project card
        $nama_project = $projectData['nama_project'] ?? ($relatedProjectData['nama_project'] ?? autoTranslate('(Nama Project Tidak Tersedia)'));
        $link_project = $projectData['link_project'] ?? $relatedProjectData['link_project'] ?? '';
        $link_github = $projectData['link_github'] ?? $relatedProjectData['link_github'] ?? '';
        $link_video = $projectData['link_video'] ?? $relatedProjectData['link_video'] ?? '';

        // Ekstrak youtube_id jika link_video adalah YouTube
        $youtube_id = '';
        $isValidVideo = false;
        
        if ($link_video && !empty(trim($link_video))) {
            // Cek apakah link adalah YouTube
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $link_video, $matches)) {
                $youtube_id = $matches[1];
                $isValidVideo = true;
            } 
            // Cek apakah link adalah video langsung (mp4, etc)
            elseif (preg_match('/\.(mp4|webm|ogg)$/i', $link_video)) {
                $isValidVideo = true;
            }
        }

        // Tentukan data yang akan ditampilkan untuk leader/owner
        $displayPhoto = $isLeaderAvailable ? $leaderPhoto : $ownerPhoto;
        $displayName = $isLeaderAvailable ? $leaderName : $ownerName;
        $displayId = $isLeaderAvailable ? $leaderId : $ownerId;
        $displayRole = $isLeaderAvailable ? autoTranslate('Leader') : autoTranslate('Owner');
    @endphp

    <!-- User Info -->
    <a href="{{ $userId ? route('portfolio.show', ['user' => $userId]) : '#' }}"
        class="flex items-center gap-1.5 sm:gap-2 mb-2 sm:mb-3 hover:opacity-80 transition-opacity min-w-0">
        <div
            class="w-7 h-7 sm:w-8 md:w-9 rounded-full overflow-hidden border-2 border-gray-100 shadow-sm shrink-0 relative">
            @if($userPhoto)
                <img src="{{ asset('storage/' . ltrim($userPhoto, '/')) }}" alt="{{ autoTranslate($userName) }}"
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
                {{ autoTranslate($userName) }}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-200 truncate">
                {{ autoTranslate($post->created_at?->diffForHumans() ?? $post->tanggal?->diffForHumans() ?? 'Baru saja') }}
            </p>
        </div>
    </a>

    <!-- Badge -->
    <div class="flex items-center gap-1 sm:gap-2 mb-2 sm:mb-3 flex-wrap">
        @if($post->type === 'learning')
            <span
                class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 w-fit">
                {{ autoTranslate('Learning Corner') }}
                @if($relatedProject)
                    <span
                        class="ml-1 text-purple-600">({{ autoTranslate(Str::limit($relatedProjectData['nama_project'] ?? 'Project', 20)) }})</span>
                @endif
            </span>
        @elseif($post->type === 'project' || $post->type === 'project_user')
            <span
                class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 w-fit">{{ autoTranslate('Project') }}</span>
        @elseif($post->type === 'postingan')
            <span
                class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 w-fit">{{ autoTranslate('Postingan') }}</span>
        @endif
    </div>

    <!-- Konten utama -->
    <div class="flex-1 mt-1 sm:mt-3">
        @if($post->type === 'learning')
            <!-- Title - Clickable ke Project -->
            @if($relatedProject)
                <a href="{{ route('project.show', ['id' => $relatedProject->id]) }}"
                    class="block hover:text-indigo-700 transition-colors">
                    <h3
                        class="text-sm sm:text-base md:text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1 sm:mb-2 line-clamp-2 leading-tight">
                        {{ autoTranslate($learningTitle ?: ($relatedProjectData['nama_project'] ?? 'Learning Corner')) }}
                    </h3>
                </a>
            @else
                <h3
                    class="text-sm sm:text-base md:text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1 sm:mb-2 line-clamp-2 leading-tight">
                    {{ autoTranslate($learningTitle ?: 'Learning Corner') }}
                </h3>
            @endif

            <!-- Text with expand/collapse -->
            @if($learningText)
                <div class="relative">
                    <p class="text-gray-700 dark:text-gray-300 mb-1 sm:mb-2 text-xs sm:text-sm line-clamp-3">
                        {{ autoTranslate($learningText) }}
                    </p>
                </div>
            @endif

            <!-- Images -->
            @if(count($learningImages) > 0)
                <div class="space-y-1.5 sm:space-y-2 mt-1.5 sm:mt-2">
                    @php $firstImage = $learningImages[0]; @endphp
                    @php $imagePath = str_replace(['\\', '/'], '/', $firstImage['content'] ?? ''); @endphp
                    <img src="{{ asset('storage/' . ltrim($imagePath, '/')) }}" alt="{{ autoTranslate($firstImage['alt'] ?? 'Gambar') }}"
                        class="w-full h-24 sm:h-32 md:h-40 object-cover rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm"
                        loading="lazy"
                        onerror="this.src='https://via.placeholder.com/400x200?text={{ urlencode(autoTranslate('Gambar Tidak Ditemukan')) }}';this.onerror=null;">
                </div>
            @endif

            <!-- Links -->
            @if(count($learningLinks) > 0)
                <div class="mt-1 sm:mt-2 space-y-0.5">
                    @foreach($learningLinks as $link)
                        <a href="{{ $link['content'] }}" target="_blank"
                            class="text-indigo-600 hover:text-indigo-800 text-xs sm:text-sm block underline line-clamp-1 break-all">
                            {{ autoTranslate(Str::limit($link['content'], 50)) }}
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Related Project Info -->
            @if($relatedProject)
                <div class="mt-2 sm:mt-3 pt-1.5 sm:pt-2 border-t border-gray-100 dark:border-gray-800">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-0.5 sm:mb-1">{{ autoTranslate('Terkait:') }}</p>
                    <a href="{{ route('project.show', ['id' => $relatedProject->id]) }}"
                        class="text-xs sm:text-sm font-semibold text-gray-800 dark:text-gray-200 hover:text-indigo-600 transition-colors line-clamp-1">
                        {{ autoTranslate(Str::limit($relatedProjectData['nama_project'] ?? $relatedProject->nama_project ?? 'Project', 25)) }}
                    </a>
                </div>
            @endif

        @elseif($post->type === 'project' || $post->type === 'project_user')
            <!-- Project Title -->
            <a href="{{ route('project.show', ['id' => $post->id]) }}"
                class="block hover:text-indigo-700 transition-colors">
                <h3
                    class="text-sm sm:text-base md:text-lg font-semibold text-gray-900 dark:text-gray-100 line-clamp-2 leading-tight">
                    {{ autoTranslate($nama_project) }}
                </h3>
            </a>

            <!-- Deskripsi Project -->
            @if(!empty($projectData['deskripsi']))
                <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 mt-1 sm:mt-2 line-clamp-2">
                    {{ autoTranslate($projectData['deskripsi']) }}
                </p>
            @endif

            <!-- Periode -->
            @if(!empty($projectData['tanggal_mulai']) || $post->tanggal_mulai)
                <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-200 mt-1 sm:mt-2">
                    <p><span class="font-medium">{{ autoTranslate('Periode:') }}</span>
                        @if(!empty($projectData['tanggal_mulai']))
                            {{ autoTranslate(\Carbon\Carbon::parse($projectData['tanggal_mulai'])->translatedFormat('M Y')) }}
                            @if(!empty($projectData['tanggal_akhir']))
                                → {{ autoTranslate(\Carbon\Carbon::parse($projectData['tanggal_akhir'])->translatedFormat('M Y')) }}
                            @else
                                → {{ autoTranslate('Sekarang') }}
                            @endif
                        @elseif($post->tanggal_mulai)
                            {{ autoTranslate(\Carbon\Carbon::parse($post->tanggal_mulai)->translatedFormat('M Y')) }}
                            @if($post->tanggal_akhir)
                                → {{ autoTranslate(\Carbon\Carbon::parse($post->tanggal_akhir)->translatedFormat('M Y')) }}
                            @else
                                → {{ autoTranslate('Sekarang') }}
                            @endif
                        @endif
                    </p>
                </div>
            @endif

            <!-- YouTube Video atau Preview Kosong -->
            @if($isValidVideo && $youtube_id)
                <!-- Video Preview untuk YouTube -->
                @include('components.video_preview', [
                    'link_video' => $link_video,
                    'alt' => autoTranslate('Video ') . autoTranslate($nama_project),
                    'class' => 'rounded-lg sm:rounded-xl overflow-hidden border border-gray-200 dark:border-gray-800 shadow-sm mt-3 sm:mt-4'
                ])
            @elseif($isValidVideo && !$youtube_id)
                <!-- Video Preview untuk video langsung (mp4, etc) -->
                <div class="relative mt-3 sm:mt-4 rounded-lg sm:rounded-xl overflow-hidden border border-gray-200 dark:border-gray-800 shadow-sm bg-gray-100 dark:bg-gray-800">
                    <video class="w-full h-32 sm:h-40 md:h-48 object-cover" controls>
                        <source src="{{ $link_video }}" type="video/mp4">
                        {{ autoTranslate('Browser Anda tidak mendukung video.') }}
                    </video>
                </div>
            @else
                <!-- Preview Kosong ketika tidak ada video -->
                <div class="relative mt-3 sm:mt-4 rounded-lg sm:rounded-xl overflow-hidden border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800/50">
                    <div class="flex flex-col items-center justify-center py-6 sm:py-8 px-4 text-center">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400 dark:text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ autoTranslate('Tidak ada video preview') }}</p>
                        @if($link_video && !empty(trim($link_video)))
                            <a href="{{ $link_video }}" target="_blank" 
                                class="mt-2 text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">
                                {{ autoTranslate('Lihat Video') }} →
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
                            {{ autoTranslate('Demo') }}
                        </a>
                    @endif

                    @if($link_github)
                        <a href="{{ $link_github }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex hover:underline items-center text-orange-600 hover:text-orange-800 font-medium transition-colors">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
                            </svg>
                            GitHub
                        </a>
                    @endif

                    @if($link_video && !$youtube_id)
                        <a href="{{ $link_video }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex hover:underline items-center text-orange-600 hover:text-orange-800 font-medium transition-colors">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ autoTranslate('Video') }}
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
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2 text-sm sm:text-base md:text-lg">{{ autoTranslate($title) }}</h3>
            @endif
            @if($deskripsi)
                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">{{ autoTranslate(Str::limit($deskripsi, 150)) }}</p>
            @endif

            {{-- Game preview (if posting contains game) --}}
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
                        <img src="{{ asset('storage/' . ltrim($thumbItem, '/')) }}" alt="{{ autoTranslate('Thumbnail Game') }}" class="w-full h-36 object-cover">
                    @else
                        <div class="w-full h-36 bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-500">
                            {{ autoTranslate('Preview Game') }}
                        </div>
                    @endif
                    <div class="p-3 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $game->game_name ?? autoTranslate('Game') }}</div>
                            <div class="text-xs text-gray-500">{{ autoTranslate('Mainkan game langsung dari postingan') }}</div>
                        </div>
                        <div>
                            @php $playUrl = route('game.matematika', ['locale' => app()->getLocale()]) . '?postingan=' . ($post->id_postingan ?? $post->id) . ($game ? '&game=' . $game->id_games : ''); @endphp
                            <a href="{{ $playUrl }}" class="inline-flex items-center px-3 py-1.5 bg-teal-600 text-white rounded-full hover:bg-teal-700">{{ autoTranslate('Play') }}</a>
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
                    {{ autoTranslate('Lihat detail') }}
                </span>
            </div>

            <!-- Comments Section (Hidden by default) -->
            <div class="comment-section px-4 pb-4 bg-white dark:bg-gray-800" id="comments-{{ $post->id_postingan ?? $post->id }}">
                @auth
                    <form class="comment-form mb-4" data-postingan-id="{{ $post->id_postingan ?? $post->id }}">
                        @csrf
                        <div class="flex gap-3">
                            @if(auth()->user()->photo_profile && file_exists(public_path('storage/' . auth()->user()->photo_profile)))
                                <img src="{{ asset('storage/' . auth()->user()->photo_profile) }}" class="w-8 h-8 rounded-full object-cover mt-1" alt="{{ autoTranslate('Foto Profil') }}">
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
                                    placeholder="{{ autoTranslate('Tulis komentar...') }}"></textarea>
                                <div class="flex justify-end mt-2">
                                    <button type="submit" 
                                        class="px-5 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition submit-btn"
                                        data-current-user-id="{{ auth()->id() }}">
                                        {{ autoTranslate('Kirim') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-3">
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">{{ autoTranslate('Masuk') }}</a> {{ autoTranslate('untuk berkomentar') }}
                    </p>
                @endauth

                <!-- List Komentar -->
                <div class="comment-list space-y-4 max-h-96 overflow-y-auto pr-2">
                    @if($post->komentar && $post->komentar->count() > 0)
                        @foreach($post->komentar->sortByDesc('tanggal') as $komentar)
                            <div class="flex gap-3 comment-item {{ auth()->check() && auth()->id() === $komentar->id_user ? 'user-comment p-3 rounded-lg' : '' }}"
                                 data-comment-id="{{ $komentar->id_komentar }}"
                                 data-user-id="{{ $komentar->id_user }}">
                                @if($komentar->user->photo_profile && file_exists(public_path('storage/' . $komentar->user->photo_profile)))
                                    <img src="{{ asset('storage/' . $komentar->user->photo_profile) }}" class="w-8 h-8 rounded-full object-cover mt-0.5" alt="{{ autoTranslate('Foto Profil') }}">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center mt-0.5">
                                        <span class="text-gray-600 dark:text-gray-400 text-sm">
                                            {{ strtoupper(substr($komentar->user->nama_mahasiswa ?? $komentar->user->name ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-sm">{{ autoTranslate($komentar->user->nama_mahasiswa ?? $komentar->user->name) }}</span>
                                        @if(auth()->check() && auth()->id() === $komentar->id_user)
                                            <span class="user-comment-badge">{{ autoTranslate('Anda') }}</span>
                                        @endif
                                        <span class="text-xs text-gray-500">{{ autoTranslate($komentar->tanggal ? $komentar->tanggal->translatedFormat('d M Y') : 'Baru') }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-0.5">{{ autoTranslate($komentar->komentar) }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center py-4">{{ autoTranslate('Belum ada komentar') }}</p>
                    @endif
                </div>
            </div>

        @elseif($post->type === 'sertifikat')
            <!-- Sertifikat Title -->
            <h3
                class="text-sm sm:text-base md:text-lg font-semibold text-gray-900 dark:text-gray-100 line-clamp-2 leading-tight mb-1 sm:mb-2">
                {{ autoTranslate($post->nama_sertifikat) }}
            </h3>

            <!-- Lembaga Penerbit -->
            @if($post->lembaga_penerbit)
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mb-2">
                    <span class="font-medium">{{ autoTranslate('Lembaga:') }}</span> {{ autoTranslate($post->lembaga_penerbit) }}
                </p>
            @endif

            <!-- Tanggal Terbit -->
            @if($post->tanggal_terbit)
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mb-2 sm:mb-3">
                    <span class="font-medium">{{ autoTranslate('Terbit:') }}</span> {{ autoTranslate(\Carbon\Carbon::parse($post->tanggal_terbit)->translatedFormat('d M Y')) }}
                </p>
            @endif

            <!-- Status Badge -->
            <div class="flex items-center gap-2 mb-3">
                @if($post->status_pengajuan === 'Di Terima')
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        ✓ {{ autoTranslate('Diterima') }}
                    </span>
                @elseif($post->status_pengajuan === 'Ditolak')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        ✗ {{ autoTranslate('Ditolak') }}
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        ⊙ {{ autoTranslate('Pengajuan') }}
                    </span>
                @endif
            </div>

            <!-- Link Sertifikat -->
            @if($post->link_sertifikat)
                <div class="mt-3 sm:mt-4">
                    <a href="{{ asset('storage/' . $post->link_sertifikat) }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex hover:underline items-center text-green-600 hover:text-green-800 font-medium text-xs sm:text-sm transition-colors">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        {{ autoTranslate('Lihat Sertifikat') }}
                    </a>
                </div>
            @endif
        @endif
    </div>

    <!-- Footer -->
    <div class="mt-3 sm:mt-4 pt-2 sm:pt-4 border-t border-gray-100 dark:border-gray-900">
        <p class="text-xs text-gray-500 dark:text-gray-50 line-clamp-2">
            <span>{{ autoTranslate('Diposting') }}</span>
            {{ autoTranslate($post->created_at?->translatedFormat('d M Y H:i') ?? $post->tanggal?->translatedFormat('d M Y') ?? '—') }}
            <span>{{ autoTranslate('oleh') }}</span>
            {{ autoTranslate($userName) }}
            @if($relatedProject)
                <span>{{ autoTranslate('untuk project') }}</span>
                <a href="{{ route('project.show', ['id' => $relatedProject->id]) }}"
                    class="text-indigo-600 hover:text-indigo-800 transition-colors font-medium">
                    {{ autoTranslate(Str::limit($relatedProjectData['nama_project'] ?? 'Project', 30)) }}
                </a>
            @endif
        </p>
    </div>
</div>