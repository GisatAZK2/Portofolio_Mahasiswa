@extends('Layout.Layout')
@section('show_footer', true)
@section('show_up_page', true)
@section('title', 'Student Projects')

@section('meta')
    <meta name="description" content="Browse student projects, timelines, links, and media from the portfolio community.">
@endsection

@section('content')

<!-- Project User Page Styles migrated to app.css -->

    <div id="project-user-container" data-page-info="project-user" class="min-h-screen bg-gray-50 dark:bg-gray-800 rounded-2xl py-4 sm:py-6 px-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            @if(session('success'))
                <div
                    class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 dark:bg-green-700 border border-green-200 text-green-800 rounded-lg flex items-center gap-2 sm:gap-3 text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Header -->
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-200"
                    data-translate="project_user_title" data-translate-page="project_user"></h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 mt-1" data-translate="project_user_desc"
                    data-translate-page="project_user"></p>
            </div>

            <!-- Projects Grid -->
            <section>
                @if($projects->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6"
                        data-pagination-group="project_user">
                        @foreach($projects as $project)
                            @php
                                $content = $project->translated('isi_content') ?? [];
                                $nama = $content['nama_project'] ?? 'Tanpa Nama Project';
                                $deskripsi = $content['deskripsi'] ?? null;
                                $linkProject = $content['link_project'] ?? null;
                                $linkGithub = $content['link_github'] ?? null;
                                $linkVideo = $content['link_video'] ?? null;
                                $thumbnail = $content['thumbnail'] ?? null;

                                $mulaiRaw = $project->tanggal_mulai ?? null;
                                $akhirRaw = $project->tanggal_akhir ?? null;
                                $mulai = $mulaiRaw ? \Carbon\Carbon::parse($mulaiRaw) : null;
                                $akhir = $akhirRaw ? \Carbon\Carbon::parse($akhirRaw) : null;
                                $today = \Carbon\Carbon::today();

                                $mulaiFormatted = $mulai ? $mulai->translatedFormat('d M Y') : '—';
                                $akhirFormatted = $akhir ? $akhir->translatedFormat('d M Y') : 'Sekarang';

                                $statusBadgeClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                $statusText = 'Tidak diketahui';
                                $statusTranslateKey = 'tidak_diketahui';

                                if ($mulai && $akhir) {
                                    if ($akhir < $today) {
                                        $statusBadgeClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                                        $statusText = 'Selesai';
                                        $statusTranslateKey = 'selesai';
                                    } elseif ($mulai <= $today && $today <= $akhir) {
                                        $statusBadgeClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                        $statusText = 'Sedang Berjalan';
                                        $statusTranslateKey = 'sedang_berjalan';
                                    } elseif ($mulai > $today) {
                                        $statusBadgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                                        $statusText = 'Akan Datang';
                                        $statusTranslateKey = 'akan_datang';
                                    }
                                } elseif ($mulai && !$akhir) {
                                    if ($mulai <= $today) {
                                        $statusBadgeClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                        $statusText = 'Sedang Berjalan';
                                        $statusTranslateKey = 'sedang_berjalan';
                                    } else {
                                        $statusBadgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                                        $statusText = 'Akan Datang';
                                        $statusTranslateKey = 'akan_datang';
                                    }
                                } elseif (!$mulai && $akhir) {
                                    if ($akhir < $today) {
                                        $statusBadgeClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                                        $statusText = 'Selesai';
                                        $statusTranslateKey = 'selesai';
                                    }
                                }

                                // Parse YouTube ID
                                $youtube_id = null;
                                if ($linkVideo) {
                                    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([^"&?\/\s]{11})/i', $linkVideo, $matches);
                                    if (!empty($matches[1])) {
                                        $youtube_id = $matches[1];
                                    }
                                }
                                $isValidVideo = ($youtube_id || ($linkVideo && preg_match('/\.(mp4|webm|ogg)$/i', $linkVideo)));

                                $mahasiswa = $project->mahasiswa;
                                $leader = $project->leader;
                                $displayUser = $leader ?? $mahasiswa;
                                $userName = $displayUser->nama_mahasiswa ?? 'Pengguna';
                                $userId = $displayUser->id ?? null;
                                $userPhoto = $displayUser->photo_profile ?? null;
                                $userSlug    = $displayUser->slug ?? null;

                                $viewCount = $project->views ?? $project->unique_views_count ?? 0;

                                // Unique ID untuk video wrapper tiap card
                                $puVideoId = 'pu-video-' . $project->id;
                            @endphp

                            <div class="flex flex-col rounded-lg sm:rounded-xl border border-gray-100 dark:border-gray-900 bg-white dark:bg-gray-900 shadow-sm hover:shadow-md transition-all duration-300 p-3 sm:p-4 lg:p-5">

                                {{-- [A] User info --}}
                                <a href="{{ $userSlug ? route('portfolio.slug', ['slug' => $userSlug]) : '#' }}"
                                    class="flex items-center gap-2 mb-2 hover:opacity-80 transition-opacity h-10 overflow-hidden shrink-0">
                                    <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-gray-100 shadow-sm shrink-0 relative">
                                        @if($userPhoto && Storage::disk('public')->exists($userPhoto))
                                            <img src="{{ Storage::url($userPhoto) }}" alt="{{ $userName }}"
                                                class="w-full h-full object-cover" loading="lazy"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="absolute inset-0 hidden bg-gradient-to-br from-indigo-500 to-purple-600 items-center justify-center text-white font-bold text-sm">
                                                {{ substr($userName, 0, 1) }}
                                            </div>
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                                {{ substr($userName, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1 overflow-hidden">
                                        <p class="font-semibold text-xs sm:text-sm text-gray-900 dark:text-gray-300 truncate leading-tight">
                                            {{ $userName }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate leading-tight">
                                            {{ $project->created_at?->diffForHumans() ?? 'Baru saja' }}
                                        </p>
                                    </div>
                                </a>

                                {{-- [B] Badge --}}
                                <div class="flex items-center gap-1.5 mb-2 h-7 overflow-hidden shrink-0">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 shrink-0" data-translate="project_label" data-translate-page="project_user">Project</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusBadgeClass }} shrink-0"
                                        data-translate="{{ $statusTranslateKey }}" data-translate-page="project_user">
                                        {{ $statusText }}
                                    </span>
                                </div>

                                {{-- [C] Judul --}}
                                <div class="h-12 overflow-hidden mb-1 shrink-0">
                                    <a href="{{ route('project.show', ['id' => $project->id]) }}"
                                        class="block hover:text-indigo-700 transition-colors">
                                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-gray-100 line-clamp-2 leading-6">
                                            {{ $nama }}
                                        </h3>
                                    </a>
                                </div>

                                {{-- [D] Deskripsi --}}
                                <div class="h-10 overflow-hidden mb-1 shrink-0">
                                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 line-clamp-2 leading-5">
                                        {{ $deskripsi ? $deskripsi : '' }}
                                    </p>
                                </div>

                                {{-- [E] Periode --}}
                                <div class="h-8 overflow-hidden mb-3 flex items-center shrink-0">
                                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 truncate">
                                        <span class="font-medium" data-translate="periode_label" data-translate-page="project_user">Periode:</span>
                                        {{ $mulaiFormatted }} → {{ $akhirFormatted }}
                                    </p>
                                </div>

                                {{-- [F] Preview video --}}
                                <div class="h-44 w-full rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 shrink-0 mb-2">

                                    @if($isValidVideo && $youtube_id)
                                        <div class="pu-video-wrapper" id="{{ $puVideoId }}"
                                             onclick="puPlayVideo('{{ $puVideoId }}', 'youtube', '{{ $youtube_id }}')">

                                            <img class="pu-thumbnail"
                                                 src="https://img.youtube.com/vi/{{ $youtube_id }}/hqdefault.jpg"
                                                 alt="Video {{ $nama }}"
                                                 loading="lazy"
                                                 onerror="this.src='/assets/LogoFooter.webp'">

                                            <div class="pu-play-overlay">
                                                <div class="pu-play-btn">
                                                    <svg fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z"/>
                                                    </svg>
                                                </div>
                                            </div>

                                            <div class="pu-embed-container"></div>
                                        </div>

                                    @elseif($isValidVideo)
                                        <div class="pu-video-wrapper" id="{{ $puVideoId }}"
                                             onclick="puPlayVideo('{{ $puVideoId }}', 'direct', '{{ $linkVideo }}')">

                                            <div class="pu-thumbnail w-full h-full bg-gray-900 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </div>

                                            <div class="pu-play-overlay">
                                                <div class="pu-play-btn">
                                                    <svg fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z"/>
                                                    </svg>
                                                </div>
                                            </div>

                                            <div class="pu-embed-container"></div>
                                        </div>

                                    @elseif($thumbnail && Storage::disk('public')->exists($thumbnail))
                                        <img src="{{ Storage::url($thumbnail) }}" alt="{{ $nama }}"
                                            class="w-full h-full object-cover">

                                    @else
                                        <div class="pu-no-preview">
                                            <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-xs text-gray-400 dark:text-gray-500" data-translate="no_preview" data-translate-page="project_user">Tidak ada preview</p>
                                        </div>
                                    @endif

                                </div>

                                {{-- [G] Links --}}
                                <div class="h-8 flex items-center gap-3 overflow-hidden shrink-0">
                                    @if($linkProject)
                                        <a href="{{ $linkProject }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center text-xs sm:text-sm text-orange-600 hover:text-orange-800 font-medium transition-colors hover:underline shrink-0">
                                            <svg class="w-3.5 h-3.5 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                            <span data-translate="demo_link" data-translate-page="project_user">Demo</span>
                                        </a>
                                    @endif
                                    @if($linkGithub)
                                        <a href="{{ $linkGithub }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center text-xs sm:text-sm text-orange-600 hover:text-orange-800 font-medium transition-colors hover:underline shrink-0">
                                            <svg class="w-3.5 h-3.5 mr-1 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                                            </svg>
                                            <span data-translate="github" data-translate-page="project_user">GitHub</span>
                                        </a>
                                    @endif
                                    @if($linkVideo && !$youtube_id)
                                        <a href="{{ $linkVideo }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center text-xs sm:text-sm text-orange-600 hover:text-orange-800 font-medium transition-colors hover:underline shrink-0">
                                            <svg class="w-3.5 h-3.5 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span data-translate="video" data-translate-page="project_user">Video</span>
                                        </a>
                                    @endif
                                </div>

                                {{-- [H] Footer --}}
                                <div class="pt-3 mt-2 border-t border-gray-100 dark:border-gray-800 shrink-0">
                                    <div class="flex justify-between items-center gap-2">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            <span data-translate="diposting" data-translate-page="project_user">Diposting</span> {{ $project->created_at?->translatedFormat('d M Y H:i') ?? '—' }} <span data-translate="oleh" data-translate-page="project_user">Oleh</span> {{ $userName }}
                                        </p>
                                        <div class="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500 shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span>{{ $viewCount }}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($projects, 'links'))
                        <div class="mt-8 justify-center">
                            {{ $projects->render('vendor.pagination.custom_ajax', ['groupName' => 'project_user']) }}
                        </div>
                    @endif

                @else
                    <div class="text-center py-12 sm:py-16 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto text-gray-400 dark:text-gray-600" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-4 text-gray-600 dark:text-gray-400 text-base sm:text-lg" data-translate="no_projects_displayed" data-translate-page="project_user">Belum ada proyek yang ditampilkan.</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-2" data-translate="add_project_to_start" data-translate-page="project_user">Silakan tambahkan proyek baru untuk memulai.</p>
                    </div>
                @endif
            </section>
        </div>
    </div>

@endsection