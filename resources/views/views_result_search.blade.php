@extends('Layout.Layout')
@section('show_footer', true)
@section('show_up_page', true)
@section('title', 'Hasil Pencarian')

@section('content')
    <style>
        .comments-container {
            max-height: 400px;
            overflow-y: auto;
            scrollbar-width: thin;
        }
        .comments-container::-webkit-scrollbar {
            width: 4px;
        }
        .comments-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .comments-container::-webkit-scrollbar-thumb {
            background: #c7d2fe;
            border-radius: 10px;
        }
        .dark .comments-container::-webkit-scrollbar-track {
            background: #374151;
        }
        .dark .comments-container::-webkit-scrollbar-thumb {
            background: #6366f1;
        }
    </style>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Filter Aktif -->
        @if($keyword || request()->jurusan || request()->keahlian || request()->angkatan || request()->type)
            <div class="mb-8 bg-white dark:bg-gray-800 dark:border-gray-900 rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-50"
                          data-translate="filter_aktif"
                          data-translate-page="result_search">Filter aktif:</span>

                    @if($keyword)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span data-translate="search_label" data-translate-page="result_search">Pencarian:</span> "{{ $keyword }}"
                        </span>
                    @endif

                    @if(request()->jurusan && $results->isNotEmpty())
                        @php
                            $jurusanItem = $results->first(fn($item) => isset($item->jurusan) && $item->jurusan->id_jurusan == request()->jurusan);
                        @endphp
                        @if($jurusanItem)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                                </svg>
                                {{ $jurusanItem->jurusan->nama_jurusan }}
                            </span>
                        @endif
                    @endif

                    @if(request()->keahlian && $results->isNotEmpty())
                        @php
                            $keahlianItem = $results->first(fn($item) => isset($item->keahlian) && $item->keahlian->id_keahlian == request()->keahlian);
                        @endphp
                        @if($keahlianItem)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                                {{ $keahlianItem->keahlian->nama_keahlian }}
                            </span>
                        @endif
                    @endif

                    <!-- 3 Pilihan Tipe -->
                    <div class="flex gap-2">
                        <a href="{{ route('search', array_merge(request()->query(), ['type' => 'mahasiswa'])) }}"
                           class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ request()->type === 'mahasiswa' ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-800 hover:bg-blue-200' }}">
                            <span data-translate="student_label" data-translate-page="result_search">Mahasiswa</span>
                        </a>
                        <a href="{{ route('search', array_merge(request()->query(), ['type' => 'project'])) }}"
                           class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ request()->type === 'project' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
                            <span data-translate="project_label" data-translate-page="result_search">Project</span>
                        </a>
                        <a href="{{ route('search', array_merge(request()->query(), ['type' => 'sertifikat'])) }}"
                           class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ request()->type === 'sertifikat' ? 'bg-amber-600 text-white' : 'bg-amber-100 text-amber-800 hover:bg-amber-200' }}">
                            <span data-translate="certificate_label" data-translate-page="result_search">Sertifikat</span>
                        </a>
                    </div>

                    <a href="{{ route('search') }}"
                       class="ml-auto inline-flex items-center px-3 py-1 text-sm text-gray-600 dark:text-gray-300 dark:hover:text-gray-400 hover:text-gray-900">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span data-translate="clr_filter" data-translate-page="result_search">Hapus semua filter</span>
                    </a>
                </div>
            </div>
        @endif

        @if($hasResults)
            <!-- Mahasiswa -->
            @if($mahasiswa->count() > 0)
                <div id="mahasiswa-section" class="mb-12" data-pagination-group="mahasiswa">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-50 flex items-center gap-3">
                            <span class="inline-flex px-4 py-2 rounded-full bg-blue-100 text-blue-800 font-medium text-base">
                                <span data-translate="student_label" data-translate-page="result_search">Mahasiswa</span>
                                <span>({{ $mahasiswa->total() }})</span>
                            </span>
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($mahasiswa as $item)
                            <a href="{{ route('portfolio.show', ['user' => $item->id]) }}"
                               class="block h-full group focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-xl">
                                <div class="bg-white dark:bg-gray-900 dark:border-gray-900 rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full group-hover:border-indigo-300 group-hover:ring-1 group-hover:ring-indigo-200">
                                    <div class="p-6 flex flex-col flex-1">
                                        <!-- Foto + Nama + Badge -->
                                        <div class="flex items-start gap-4 mb-4">
                                            <div class="flex-shrink-0">
                                                @if($item->photo_profile)
                                                    <img src="{{ asset('storage/' . $item->photo_profile) }}"
                                                         class="w-14 h-14 rounded-full object-cover border-2 border-gray-200 shadow-sm transition-transform group-hover:scale-105"
                                                         alt="{{ $item->nama_mahasiswa ?? 'Profil' }}">
                                                @else
                                                    <div class="w-14 h-14 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-sm transition-transform group-hover:scale-105">
                                                        {{ strtoupper(substr($item->nama_mahasiswa ?? 'U', 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex justify-between items-start gap-2">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-50 group-hover:text-indigo-700 transition-colors truncate max-w-[150px]"
                                                        title="{{ $item->nama_mahasiswa ?? 'Nama tidak tersedia' }}">
                                                        {{ $item->nama_mahasiswa ?? 'Nama tidak tersedia' }}
                                                    </h3>
                                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 flex-shrink-0">
                                                        <span data-translate="student_label" data-translate-page="result_search">Mahasiswa</span>
                                                    </span>
                                                </div>
                                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-100 flex items-center gap-1 truncate">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="truncate" title="{{ $item->email ?? 'Email tidak tersedia' }}">{{ $item->email ?? 'Email tidak tersedia' }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <!-- Jurusan, Keahlian & Angkatan -->
                                        <div class="flex flex-wrap gap-2 mb-5 min-h-[60px]">
                                            @if($item->angkatan)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                                    <svg class="w-6 h-3.5 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="truncate max-w-[100px]" title="{{ $item->angkatan->tahun_angkatan ?? $item->angkatan->nama_angkatan ?? $item->angkatan }}">
                                                        {{ $item->angkatan->tahun_angkatan ?? $item->angkatan->nama_angkatan ?? $item->angkatan }}
                                                    </span>
                                                </span>
                                            @endif
                                            @if($item->jurusan)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                                    <svg class="w-3.5 h-3.5 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                                                    </svg>
                                                    <span class="truncate max-w-[120px]" title="{{ $item->jurusan->nama_jurusan }}">
                                                        {{ $item->jurusan->nama_jurusan }}
                                                    </span>
                                                </span>
                                            @endif
                                            @if($item->keahlian)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700">
                                                    <svg class="w-3.5 h-3.5 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                    </svg>
                                                    <span class="truncate max-w-[120px]" title="{{ $item->keahlian->nama_keahlian }}">
                                                        {{ $item->keahlian->nama_keahlian }}
                                                    </span>
                                                </span>
                                            @endif
                                            @if($item->keahlianTambahan)
                                                @foreach($item->keahlianTambahan as $keahlianTambahan)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700">
                                                        <svg class="w-3.5 h-3.5 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                        </svg>
                                                        <span class="truncate max-w-[120px]" title="{{ $keahlianTambahan->nama_keahlian }}">
                                                            {{ $keahlianTambahan->nama_keahlian }}
                                                        </span>
                                                    </span>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="mt-auto">
                                            <div class="text-sm text-gray-600 flex justify-between border-t pt-4">
                                                <span class="dark:text-gray-50"><strong class="text-gray-900 dark:text-gray-50">{{ $item->project_total_count ?? (($item->projects_count ?? 0) + ($item->leading_projects_count ?? 0) + ($item->member_projects_count ?? 0)) }}</strong> <span data-translate="project_label" data-translate-page="result_search">Project</span></span>
                                                <span class="dark:text-gray-50"><strong class="text-gray-900 dark:text-gray-50">{{ $item->sertifikats_count ?? 0 }}</strong> <span data-translate="certificate_label" data-translate-page="result_search">Sertifikat</span></span>
                                                <span class="dark:text-gray-50"><strong class="text-gray-900 dark:text-gray-50">{{ $item->learning_count ?? 0 }}</strong> Learning</span>
                                            </div>
                                            <div class="mt-3 text-right">
                                                <span data-translate="prtfl_lengkap" data-translate-page="result_search"
                                                      class="text-sm font-medium text-indigo-600 group-hover:text-indigo-800 transition-colors flex items-center justify-end gap-1">
                                                    Lihat portfolio lengkap →
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination Mahasiswa -->
                    <div class="mt-8">
                        {{ $mahasiswa->render('vendor.pagination.custom_ajax', ['groupName' => 'mahasiswa']) }}
                    </div>
                </div>
            @endif

            <!-- Postingan -->
            @if($postingan->count() > 0)
                <hr class="my-12 border-gray-200">
                <div id="postingan-section" class="mb-12" data-pagination-group="postingan">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-1 h-5 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">
                            <span data-translate="post_label" data-translate-page="result_search">Postingan</span> ({{ $postingan->total() }})
                        </h3>
                    </div>

                    <div id="postingan-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($postingan as $post)
                            @php
                                $postTitle       = '';
                                $postDescription = '';
                                $contentArray = is_array($post->content_translated) ? $post->content_translated : json_decode($post->content_translated, true);
                                if (is_array($contentArray)) {
                                    foreach ($contentArray as $item) {
                                        if (isset($item['type'])) {
                                            if ($item['type'] === 'title')       $postTitle       = strip_tags($item['content'] ?? '');
                                            elseif ($item['type'] === 'description') $postDescription = strip_tags($item['content'] ?? '');
                                        }
                                    }
                                }
                                $commentCount = \App\Models\Komentar::getCommentCount($post->id_postingan);
                            @endphp
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 post-card relative h-full flex flex-col"
                                 data-post-title="{{ strtolower($postTitle) }}"
                                 data-post-description="{{ strtolower($postDescription) }}"
                                 data-post-author="{{ strtolower($post->user->nama_mahasiswa ?? '') }}"
                                 data-post-id="{{ $post->id_postingan }}"
                                 data-share-url="{{ route('postingan.show', ['locale' => app()->getLocale(), 'id' => $post->id_postingan]) }}">

                                <!-- Header -->
                                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('portfolio.show', ['user' => $post->user->username]) }}" class="flex items-center gap-3">
                                            <img src="{{ asset('storage/' . $post->user->photo_profile) }}"
                                                 class="w-10 h-10 rounded-full object-cover"
                                                 alt="{{ $post->user->username }}"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 items-center justify-center hidden">
                                                <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-sm">
                                                    {{ strtoupper(substr($post->user->username ?? 'U', 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100">{{ $post->user->nama_mahasiswa }}</h4>
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

                                <!-- Body -->
                                <div class="p-4 cursor-pointer flex-1 overflow-hidden" onclick="window.location.href='{{ route('postingan.show', ['locale' => app()->getLocale(), 'id' => $post->id_postingan]) }}'">
                                    @php
                                        $content  = $post->content_translated ?? [];
                                        $title    = ''; $deskripsi = ''; $imageUrl = null;
                                        if (is_array($content)) {
                                            foreach ($content as $item) {
                                                if (isset($item['type'])) {
                                                    if ($item['type'] === 'title')       $title    = $item['content'] ?? '';
                                                    elseif ($item['type'] === 'description') $deskripsi = $item['content'] ?? '';
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
                                        <h3 class="font-semibold text-[22px] text-gray-900 dark:text-gray-100 mb-3 line-clamp-2">{{ $title }}</h3>
                                    @endif
                                    @if($imageUrl)
                                        <div class="mb-4 rounded-xl overflow-hidden">
                                            <img src="{{ $imageUrl }}" class="w-full h-auto object-cover hover:scale-105 transition-transform duration-300" loading="lazy">
                                        </div>
                                    @endif
                                    @if($deskripsi)
                                        <p class="text-[20px] text-gray-600 dark:text-gray-300 line-clamp-4">{{ Str::limit(strip_tags($deskripsi), 180) }}</p>
                                    @endif

                                    @if($game)
                                        @php
                                            $gameRoute       = '';
                                            $gameDisplayName = $game->game_name;
                                            switch(strtolower($game->game_name)) {
                                                case 'matematika': case 'math':
                                                    $gameRoute = route('game.matematika', ['locale' => app()->getLocale()]); $gameDisplayName = 'Matematika'; break;
                                                case 'puzzle':
                                                    $gameRoute = route('game.puzzle', ['locale' => app()->getLocale()]); $gameDisplayName = 'Puzzle'; break;
                                                case 'tts': case 'teka-teki silang':
                                                    $gameRoute = route('game.tts', ['locale' => app()->getLocale()]); $gameDisplayName = 'Teka-Teki Silang'; break;
                                                default:
                                                    $gameRoute = route('game.matematika', ['locale' => app()->getLocale()]);
                                            }
                                        @endphp
                                        <div class="mt-2 p-3 border border-gray-100 dark:border-gray-800 rounded-lg flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ asset($gameThumbnail) }}" class="w-24 h-14 object-cover rounded">
                                                <div>
                                                    <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $gameDisplayName }}</div>
                                                    <div class="text-xs text-gray-500"><span data-translate="play_game" data-translate-page="result_search">Mainkan game</span></div>
                                                    @if($game->score > 0)
                                                        <div class="text-xs text-green-600 dark:text-green-400 mt-1">🏆 <span data-translate="best_score" data-translate-page="result_search">Skor terbaik</span>: {{ $game->score }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            @auth
                                                <a href="{{ $gameRoute }}?postingan={{ $post->id_postingan }}&game={{ $game->id_games }}"
                                                   class="inline-flex items-center px-3 py-1.5 bg-teal-600 text-white rounded-full hover:bg-teal-700 transition-colors">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm3 4v2h2V7H8zm6 0v2h2V7h-2zm-6 6v2h2v-2H8zm6 0v2h2v-2h-2z"/>
                                                    </svg>
                                                    <span data-translate="play_btn" data-translate-page="result_search">Play</span>
                                                </a>
                                            @endauth
                                        </div>
                                    @endif
                                </div>

                                <!-- Footer -->
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex items-center justify-between">
                                    <div class="flex items-center gap-6">
                                        @auth
                                            <button class="like-btn flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-red-500 transition"
                                                    data-postingan-id="{{ $post->id_postingan }}">
                                                <svg class="w-5 h-5 {{ $post->likes->where('id_user', auth()->id())->count() > 0 ? 'fill-current text-red-500' : '' }}"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                                        <button class="comment-toggle flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-blue-600 transition"
                                                data-postingan-id="{{ $post->id_postingan }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                            <span class="comment-count text-sm">{{ $commentCount }}</span>
                                        </button>

                                        <button class="share-btn flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-blue-600 transition">
                                            <svg fill="none" class="w-5 h-5" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M21.707,11.293l-8-8A.99991.99991,0,0,0,12,4V7.54492A11.01525,11.01525,0,0,0,2,18.5V20a1,1,0,0,0,1.78418.62061,11.45625,11.45625,0,0,1,7.88672-4.04932c.0498-.00635.1748-.01611.3291-.02588V20a.99991.99991,0,0,0,1.707.707l8-8A.99962.99962,0,0,0,21.707,11.293ZM14,17.58594V15.5a.99974.99974,0,0,0-1-1c-.25488,0-1.2959.04932-1.56152.085A14.00507,14.00507,0,0,0,4.05176,17.5332,9.01266,9.01266,0,0,1,13,9.5a.99974.99974,0,0,0,1-1V6.41406L19.58594,12Z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <span onclick="window.location.href='{{ route('postingan.show', ['locale' => app()->getLocale(), 'id' => $post->id_postingan]) }}'"
                                          class="text-xs text-gray-500 dark:text-gray-400 cursor-pointer hover:text-indigo-600 transition">
                                        <span data-translate="lihat_detail" data-translate-page="result_search">Lihat detail</span> →
                                    </span>
                                </div>

                                <!-- Comment Section -->
                                <div class="comment-section px-4 pb-4 bg-white dark:bg-gray-800 hidden rounded-b-xl" id="comments-{{ $post->id_postingan }}">
                                    @auth
                                        <div class="mb-4">
                                            <div class="flex gap-3">
                                                <div class="flex-1">
                                                    <textarea id="comment-input-{{ $post->id_postingan }}"
                                                              rows="2"
                                                              class="comment-input w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none resize-none text-sm transition"
                                                              placeholder="Tulis komentar..."
                                                              data-translate-placeholder="Tulis komentar..."
                                                              data-translate-page="result_search"></textarea>
                                                    <div class="flex justify-end mt-2">
                                                        <button onclick="window.submitComment({{ $post->id_postingan }})"
                                                                class="submit-comment-btn px-5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                                            <span data-translate="kirim" data-translate-page="result_search">Kirim</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium hover:underline">
                                                    <span data-translate="login" data-translate-page="result_search">Masuk</span>
                                                </a>
                                                <span data-translate="untuk_berkomentar" data-translate-page="result_search">untuk berkomentar</span>
                                            </p>
                                        </div>
                                    @endauth

                                    <div id="comments-container-{{ $post->id_postingan }}" class="comments-container space-y-4 pr-2">
                                        <div class="text-center py-6 text-gray-400 text-sm">
                                            <div class="comment-loading inline-block mr-2"></div>
                                            <span data-translate="loading_comments" data-translate-page="result_search">Memuat komentar...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $postingan->render('vendor.pagination.custom_ajax', ['groupName' => 'postingan']) }}
                    </div>
                </div>
            @endif

            <!-- Separator Project -->
            @if($projects->count() > 0)
                <hr class="my-12 border-gray-200">
            @endif

            <!-- Project -->
            @if($projects->count() > 0)
                <div id="projects-section" class="mb-12" data-pagination-group="project">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <span class="inline-flex px-4 py-2 rounded-full bg-green-100 text-green-800 font-medium text-base">
                                <span data-translate="project_label" data-translate-page="result_search">Project</span> ({{ $projects->total() }})
                            </span>
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($projects as $item)
                            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full border-t-4 border-green-500 project-item">
                                <div class="p-6 flex flex-col flex-1">
                                    <a href="{{ route('project.show', ['locale' => app()->getLocale(), 'id' => $item->id]) }}" class="block flex-1">
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-3 w-fit">
                                            <span data-translate="project_label" data-translate-page="result_search">Project</span>
                                        </span>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2 min-h-[56px] hover:text-green-600 transition"
                                            title="{{ $item->nama_project ?? 'Project Tanpa Judul' }}">
                                            {{ $item->nama_project ?? 'Project Tanpa Judul' }}
                                        </h3>
                                    </a>

                                    @if($item->mahasiswa)
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                                            <span data-translate="oleh" data-translate-page="result_search">Oleh</span>
                                            <a href="{{ route('portfolio.show', $item->mahasiswa->id) }}"
                                               class="font-semibold hover:text-green-600 transition truncate inline-block max-w-[150px]"
                                               title="{{ $item->mahasiswa->nama_mahasiswa ?? '—' }}">
                                                {{ $item->mahasiswa->nama_mahasiswa ?? '—' }}
                                            </a>
                                            @if($item->mahasiswa->angkatan)
                                                <span class="inline-flex ml-2 px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-700 whitespace-nowrap">
                                                    {{ $item->mahasiswa->angkatan->tahun_angkatan ?? $item->mahasiswa->angkatan->nama_angkatan ?? $item->mahasiswa->angkatan }}
                                                </span>
                                            @endif
                                        </p>
                                    @endif

                                    <p class="text-sm text-gray-500 dark:text-gray-50 mb-4 flex items-center gap-2 min-h-[40px]">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="truncate" title="{{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') : '—' }} @if($item->tanggal_akhir) - {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }} @else - Sekarang @endif">
                                            {{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') : '—' }}
                                            @if($item->tanggal_akhir)
                                                - {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }}
                                            @else
                                                - Sekarang
                                            @endif
                                        </span>
                                    </p>

                                    <div class="mt-auto">
                                        @if($item->link_project)
                                            <a href="{{ $item->link_project }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center justify-center w-full px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                                                <span data-translate="see_project" data-translate-page="result_search">Lihat Project →</span>
                                            </a>
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-50 italic text-center py-2.5" data-translate="empty_link" data-translate-page="result_search">Tidak ada link project</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination Project -->
                    <div class="mt-8">
                        {{ $projects->render('vendor.pagination.custom_ajax', ['groupName' => 'project']) }}
                    </div>
                </div>
            @endif

            <!-- Separator Sertifikat -->
            @if($sertifikats->count() > 0)
                <hr class="my-12 border-gray-200">
            @endif

            <!-- Sertifikat -->
            @if($sertifikats->count() > 0)
                <div id="sertifikat-section" class="mb-12" data-pagination-group="sertifikat">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-3">
                            <span class="inline-flex px-4 py-2 rounded-full bg-amber-100 text-amber-800 font-medium text-base">
                                <span data-translate="certificate_label" data-translate-page="result_search">Sertifikat</span> ({{ $sertifikats->total() }})
                            </span>
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($sertifikats as $item)
                            <div class="bg-white dark:bg-gray-900 dark:border-gray-900 rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full border-t-4 border-amber-500 sertifikat-item">
                                <div class="p-6 flex flex-col flex-1">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 mb-3 w-fit">
                                        <span data-translate="certificate_label" data-translate-page="result_search">Sertifikat</span>
                                    </span>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2 min-h-[56px]"
                                        title="{{ $item->nama_sertifikat ?? 'Sertifikat Tanpa Judul' }}">
                                        {{ $item->nama_sertifikat ?? 'Sertifikat Tanpa Judul' }}
                                    </h3>
                                    @if($item->mahasiswa)
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                                            <span data-translate="oleh" data-translate-page="result_search">Oleh</span>
                                            <strong class="truncate inline-block max-w-[150px]" title="{{ $item->mahasiswa->nama_mahasiswa ?? '—' }}">{{ $item->mahasiswa->nama_mahasiswa ?? '—' }}</strong>
                                            @if($item->mahasiswa->angkatan)
                                                <span class="inline-flex ml-2 px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-700 whitespace-nowrap">
                                                    {{ $item->mahasiswa->angkatan->tahun_angkatan ?? $item->mahasiswa->angkatan->nama_angkatan ?? $item->mahasiswa->angkatan }}
                                                </span>
                                            @endif
                                        </p>
                                    @endif
                                    <div class="space-y-2 mb-4 min-h-[80px]">
                                        <div class="flex items-center text-sm text-gray-700 dark:text-gray-200">
                                            <svg class="w-4 h-4 mr-2 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                            </svg>
                                            <span class="font-medium dark:text-gray-100 flex-shrink-0" data-translate="penerbit" data-translate-page="result_search">Penerbit:</span>
                                            <span class="truncate" title="{{ $item->lembaga_penerbit ?? 'Tidak diketahui' }}">{{ $item->lembaga_penerbit ?? 'Tidak diketahui' }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-700 dark:text-gray-200">
                                            <svg class="w-4 h-4 mr-2 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="font-medium dark:text-gray-100 flex-shrink-0" data-translate="terbit" data-translate-page="result_search">Terbit:</span>
                                            <span class="truncate" title="{{ $item->tanggal_terbit ? \Carbon\Carbon::parse($item->tanggal_terbit)->format('d M Y') : '—' }}">{{ $item->tanggal_terbit ? \Carbon\Carbon::parse($item->tanggal_terbit)->format('d M Y') : '—' }}</span>
                                        </div>
                                    </div>

                                    <div class="mt-auto">
                                        @if($item->link_sertifikat)
                                            <a href="{{ asset('storage/' . $item->link_sertifikat) }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center justify-center w-full px-5 py-2.5 bg-amber-600 text-white font-medium rounded-lg hover:bg-amber-700 transition">
                                                <span data-translate="lihat_sertifikat" data-translate-page="result_search">Lihat Sertifikat →</span>
                                            </a>
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-100 italic text-center py-2.5" data-translate="empty_link" data-translate-page="result_search">Tidak ada link sertifikat</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination Sertifikat -->
                    <div class="mt-8">
                        {{ $sertifikats->render('vendor.pagination.custom_ajax', ['groupName' => 'sertifikat']) }}
                    </div>
                </div>
            @endif

        @else
            <!-- Tidak ada hasil -->
            <div class="text-center py-20 bg-white rounded-xl shadow-sm border border-gray-200 dark:border-gray-900 dark:bg-gray-900">
                <svg class="mx-auto h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <h3 class="mt-6 text-2xl font-medium text-gray-900 dark:text-gray-100" data-translate="none" data-translate-page="result_search">Tidak ada hasil ditemukan</h3>
                <p class="mt-3 text-gray-600 dark:text-gray-300 max-w-md mx-auto" data-translate="none_desc" data-translate-page="result_search">
                    Coba ubah kata kunci, pilih jurusan/keahlian/angkatan lain, atau hapus filter di atas.
                </p>
                <div class="mt-6 flex justify-center gap-3">
                    <a href="{{ route('search') }}"
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition"
                       data-translate="clr_filter" data-translate-page="result_search">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span data-translate="reset_filter" data-translate-page="result_search">Reset Filter</span>
                    </a>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            function toggleSeeMore(gridClass, button, totalCount) {
                const grid = document.querySelector(`.${gridClass}`);
                const items = grid.querySelectorAll(`.${gridClass.replace('-grid', '-item')}`);
                const hidden = Array.from(items).filter(item => item.classList.contains('hidden'));
                const svg = button.querySelector('svg');

                if (hidden.length > 0) {
                    hidden.forEach(item => item.classList.remove('hidden'));
                    button.innerHTML = `
                        <span data-translate="sembunyi" data-translate-page="result_search">Sembunyikan</span>
                        <svg class="w-4 h-4 ml-1 transition-transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    `;
                } else {
                    items.forEach((item, index) => {
                        if (index >= 3) item.classList.add('hidden');
                    });
                    button.innerHTML = `
                        <span data-translate="tampil" data-translate-page="result_search">Lihat semua</span>
                        <svg class="w-4 h-4 ml-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    `;
                }
            }
        </script>
    @endpush

    <!-- Page Info & Komentar Script (sama seperti sebelumnya, tidak diubah) -->
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                showPageInfo("popup.semua_portofolio");
            });

            // ==================== GLOBAL VARS ====================
            window.currentUserId   = {{ auth()->id() ?? 'null' }};
            window.currentUserPhoto = "{{ auth()->user()?->photo_profile ?? '' }}";
            window.currentUserName  = "{{ auth()->user()?->nama_mahasiswa ?? '' }}";
            window.locale = document.querySelector('html').getAttribute('lang') || 'id';
            const isLoggedIn = window.currentUserId !== null && window.currentUserId !== 'null';

            // ==================== HELPERS ====================
            function escapeHtml(text) {
                if (!text) return '';
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function formatDate(dateString) {
                if (!dateString) return '';
                return new Date(dateString).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            }

            function getAvatarHtml(user, size = 'w-8 h-8', textSize = 'text-sm') {
                if (user && user.photo_profile && user.photo_profile !== 'null' && user.photo_profile !== '') {
                    const photoPath = user.photo_profile.startsWith('http') ? user.photo_profile : `/storage/${user.photo_profile}`;
                    return `<img src="${photoPath}" class="${size} rounded-full object-cover" onerror="this.src='https://ui-avatars.com/api/?background=6366f1&color=fff&size=100&name=${encodeURIComponent(user.nama_mahasiswa || 'U')}'">`;
                }
                const name = user?.nama_mahasiswa || window.currentUserName || 'User';
                const initial = name.charAt(0).toUpperCase();
                return `<div class="${size} rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                            <span class="text-indigo-600 dark:text-indigo-400 ${textSize} font-semibold">${initial}</span>
                        </div>`;
            }

            function getHeaders() {
                return {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                };
            }

            window.commentLastUpdated = {};

            // ==================== LOAD COMMENTS ====================
            window.loadComments = async function (postinganId) {
                const container = document.getElementById(`comments-container-${postinganId}`);
                if (!container) return;
                container.innerHTML = '<div class="text-center py-6 text-gray-400 text-sm"><div class="comment-loading inline-block mr-2"></div> Memuat komentar...</div>';
                try {
                    const response = await fetch(`/${window.locale}/komentar?id_postingan=${postinganId}`);
                    const data = await response.json();
                    if (data && data.success === true) {
                        window.commentLastUpdated[postinganId] = data.last_updated ?? null;
                        let commentsArray = [];
                        if (data.comments && Array.isArray(data.comments)) commentsArray = data.comments;
                        else if (data.comments?.comments && Array.isArray(data.comments.comments)) commentsArray = data.comments.comments;
                        if (commentsArray.length === 0) {
                            container.innerHTML = `<p class="text-sm text-gray-500 dark:text-gray-400 text-center py-5">Belum ada komentar. Jadilah yang pertama!</p>`;
                            return;
                        }
                        let html = '<div class="space-y-4">';
                        commentsArray.forEach(c => { html += renderCommentWithReplies(c, 0, postinganId); });
                        html += '</div>';
                        container.innerHTML = html;
                        attachCommentEventListeners(container, postinganId);
                    } else {
                        container.innerHTML = '<p class="text-center text-red-500 text-sm py-4">⚠️ Gagal memuat komentar</p>';
                    }
                } catch (error) {
                    container.innerHTML = '<p class="text-center text-red-500 text-sm py-4">⚠️ Gagal memuat komentar: ' + error.message + '</p>';
                }
            };

            function renderCommentWithReplies(comment, level, postinganId) {
                const marginLeft = Math.min(level * 28, 56);
                const isOwnComment = window.currentUserId && comment.id_user == window.currentUserId;
                const userName = escapeHtml(comment.user?.nama_mahasiswa || 'User');
                const commentText = escapeHtml(comment.komentar);
                const commentId = String(comment.id_komentar);
                const replyText = window.locale === 'id' ? 'Balas' : 'Reply';
                const editText = window.locale === 'id' ? 'Edit' : 'Edit';
                const deleteText = window.locale === 'id' ? 'Hapus' : 'Delete';
                let html = `
                    <div class="comment-item transition-all duration-200 py-2" data-comment-id="${commentId}" data-postingan-id="${postinganId}" style="margin-left:${marginLeft}px;">
                        <div class="flex gap-3">
                            ${getAvatarHtml(comment.user, 'w-8 h-8', 'text-xs')}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-semibold text-sm text-gray-900 dark:text-gray-100">${userName}</span>
                                    <span class="text-xs text-gray-500">${formatDate(comment.tanggal || comment.created_at)}</span>
                                </div>
                                <p class="comment-text text-sm text-gray-700 dark:text-gray-300 mt-1.5 leading-relaxed" id="comment-text-${commentId}">${commentText}</p>
                                <div class="flex flex-wrap gap-3 mt-2">`;
                if (isLoggedIn) {
                    html += `<button class="reply-btn text-xs text-indigo-500 hover:text-indigo-700 font-medium transition-colors inline-flex items-center gap-1"
                                data-action="reply" data-comment-id="${commentId}" data-postingan-id="${postinganId}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                ${replyText}</button>`;
                }
                if (isOwnComment) {
                    html += `<button class="edit-comment-btn text-xs text-blue-500 hover:text-blue-700 transition-colors inline-flex items-center gap-1"
                                data-action="edit" data-comment-id="${commentId}" data-postingan-id="${postinganId}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                ${editText}</button>
                             <button class="delete-comment-btn text-xs text-red-500 hover:text-red-700 transition-colors inline-flex items-center gap-1"
                                data-action="delete" data-comment-id="${commentId}" data-postingan-id="${postinganId}" data-type="full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                ${deleteText}</button>`;
                }
                html += `</div></div></div>
                        <div id="reply-form-${commentId}" class="reply-form-container hidden mt-3 ml-11"></div>
                    </div>`;
                if (comment.balasan?.length) {
                    html += `<div class="replies-container ml-4 mt-1">`;
                    comment.balasan.forEach(r => { html += renderCommentWithReplies(r, level + 1, postinganId); });
                    html += `</div>`;
                }
                return html;
            }

            function attachCommentEventListeners(container, postinganId) {
                if (container.hasAttribute('data-delegated')) return;
                container.setAttribute('data-delegated', 'true');
                container.addEventListener('click', function (e) {
                    const btn = e.target.closest('[data-action]');
                    if (!btn) return;
                    const commentId = btn.getAttribute('data-comment-id');
                    const pid = btn.getAttribute('data-postingan-id');
                    const action = btn.getAttribute('data-action');
                    if (action === 'reply') window.showReplyForm(commentId, pid);
                    else if (action === 'edit') window.showEditForm(commentId, pid);
                    else if (action === 'delete') window.deleteComment(commentId, pid, btn.getAttribute('data-type') || 'full');
                    else if (action === 'save-edit') window.saveEdit(commentId, pid);
                    else if (action === 'cancel-edit') window.cancelEdit(commentId);
                });
            }

            // ==================== SUBMIT NEW COMMENT ====================
            window.submitComment = async function (postinganId) {
                const textarea = document.getElementById(`comment-input-${postinganId}`);
                const commentText = textarea.value.trim();
                if (!commentText) {
                    if (typeof showWarningAlert === 'function') showWarningAlert('Komentar tidak boleh kosong');
                    else alert('Komentar tidak boleh kosong');
                    return;
                }
                const submitBtn = textarea.closest('.flex-1')?.querySelector('.submit-comment-btn');
                const origHtml = submitBtn ? submitBtn.innerHTML : '';
                if (submitBtn) {
                    submitBtn.classList.add('btn-loading');
                    submitBtn.innerHTML = 'Mengirim...';
                    submitBtn.disabled = true;
                }
                try {
                    const response = await fetch(`/${window.locale}/komentar`, {
                        method: 'POST',
                        headers: getHeaders(),
                        body: JSON.stringify({ id_postingan: postinganId, komentar: commentText })
                    });
                    const data = await response.json();
                    if (data.success) {
                        textarea.value = '';
                        await window.loadComments(postinganId);
                        await updateCommentCount(postinganId, 1);
                        if (typeof showSuccessAlert === 'function') showSuccessAlert('Komentar berhasil ditambahkan');
                        else alert('Komentar berhasil ditambahkan');
                    } else {
                        if (typeof showErrorAlert === 'function') showErrorAlert(data.message || 'Gagal menambah komentar');
                        else alert(data.message || 'Gagal menambah komentar');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    if (typeof showErrorAlert === 'function') showErrorAlert('Terjadi kesalahan: ' + error.message);
                    else alert('Terjadi kesalahan: ' + error.message);
                } finally {
                    if (submitBtn) {
                        submitBtn.classList.remove('btn-loading');
                        submitBtn.innerHTML = origHtml;
                        submitBtn.disabled = false;
                    }
                }
            };

            // ==================== SUBMIT REPLY ====================
            async function submitReply(form, postinganId) {
                if (form.hasAttribute('data-submitting')) return;
                form.setAttribute('data-submitting', 'true');
                const textarea = form.querySelector('textarea[name="komentar"]');
                const commentText = textarea.value.trim();
                const parentId = form.querySelector('input[name="parent_id"]').value;
                if (!commentText) {
                    if (typeof showWarningAlert === 'function') showWarningAlert('Balasan tidak boleh kosong');
                    else alert('Balasan tidak boleh kosong');
                    form.removeAttribute('data-submitting');
                    return;
                }
                const submitBtn = form.querySelector('button[type="submit"]');
                const cancelBtn = form.querySelector('button[type="button"]');
                if (submitBtn) submitBtn.disabled = true;
                if (cancelBtn) cancelBtn.disabled = true;
                try {
                    const response = await fetch(`/${window.locale}/komentar`, {
                        method: 'POST',
                        headers: getHeaders(),
                        body: JSON.stringify({ id_postingan: postinganId, komentar: commentText, parent_id: parentId, reply_to_id: parentId })
                    });
                    const data = await response.json();
                    if (data.success) {
                        await window.loadComments(postinganId);
                        await updateCommentCount(postinganId, 1);
                        const replyContainer = document.getElementById(`reply-form-${parentId}`);
                        if (replyContainer) {
                            replyContainer.classList.add('hidden');
                            replyContainer.innerHTML = '';
                        }
                        if (typeof showSuccessAlert === 'function') showSuccessAlert('Balasan berhasil ditambahkan');
                        else alert('Balasan berhasil ditambahkan');
                    } else {
                        if (typeof showErrorAlert === 'function') showErrorAlert(data.message || 'Gagal menambah balasan');
                        else alert(data.message || 'Gagal menambah balasan');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    if (typeof showErrorAlert === 'function') showErrorAlert('Terjadi kesalahan: ' + error.message);
                    else alert('Terjadi kesalahan: ' + error.message);
                } finally {
                    if (submitBtn) submitBtn.disabled = false;
                    if (cancelBtn) cancelBtn.disabled = false;
                    form.removeAttribute('data-submitting');
                }
            }

            window.showReplyForm = function (parentCommentId, postinganId) {
                parentCommentId = String(parentCommentId);
                const replyContainer = document.getElementById(`reply-form-${parentCommentId}`);
                if (!replyContainer) return;
                const sendText = window.locale === 'id' ? 'Kirim' : 'Send';
                const cancelText = window.locale === 'id' ? 'Batal' : 'Cancel';
                const ph = window.locale === 'id' ? 'Tulis balasan...' : 'Write a reply...';
                if (replyContainer.innerHTML.trim() !== '' && !replyContainer.classList.contains('hidden')) {
                    replyContainer.classList.add('hidden');
                    replyContainer.innerHTML = '';
                    return;
                }
                replyContainer.innerHTML = `<form class="reply-submit-form mt-3">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                    <input type="hidden" name="parent_id" value="${parentCommentId}">
                    <div class="flex flex-col gap-2">
                        <textarea name="komentar" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm outline-none resize-none" placeholder="${ph}"></textarea>
                        <div class="flex gap-2 justify-end">
                            <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg">${sendText}</button>
                            <button type="button" onclick="this.closest('.reply-form-container').classList.add('hidden');this.closest('.reply-form-container').innerHTML='';" class="px-4 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg">${cancelText}</button>
                        </div>
                    </div>
                </form>`;
                replyContainer.classList.remove('hidden');
                replyContainer.querySelector('form').onsubmit = async (e) => {
                    e.preventDefault();
                    await submitReply(replyContainer.querySelector('form'), postinganId);
                };
            };

            // ==================== EDIT COMMENT ====================
            window.showEditForm = function (commentId, postinganId) {
                commentId = String(commentId);
                postinganId = String(postinganId);
                const commentTextEl = document.getElementById(`comment-text-${commentId}`);
                if (!commentTextEl) return;
                const originalText = commentTextEl.innerText;
                const commentItem = commentTextEl.closest('.comment-item');
                const existing = document.getElementById(`edit-form-${commentId}`);
                if (existing) {
                    existing.remove();
                    commentTextEl.style.display = 'block';
                    const editBtn = commentItem.querySelector('.edit-comment-btn');
                    if (editBtn) editBtn.style.display = 'inline-flex';
                    return;
                }
                const saveText = window.locale === 'id' ? 'Simpan' : 'Save';
                const cancelText = window.locale === 'id' ? 'Batal' : 'Cancel';
                const editForm = document.createElement('div');
                editForm.className = 'edit-form mt-2';
                editForm.id = `edit-form-${commentId}`;
                editForm.innerHTML = `<textarea class="edit-textarea w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none resize-none" rows="2">${escapeHtml(originalText)}</textarea>
                    <div class="flex gap-2 mt-2">
                        <button class="save-edit-btn px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg" data-action="save-edit" data-comment-id="${commentId}" data-postingan-id="${postinganId}">${saveText}</button>
                        <button class="px-3 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg" data-action="cancel-edit" data-comment-id="${commentId}">${cancelText}</button>
                    </div>`;
                commentTextEl.style.display = 'none';
                commentTextEl.parentNode.insertBefore(editForm, commentTextEl.nextSibling);
                const editBtn = commentItem.querySelector('.edit-comment-btn');
                if (editBtn) editBtn.style.display = 'none';
            };

            window.saveEdit = async function (commentId, postinganId) {
                commentId = String(commentId);
                const editForm = document.getElementById(`edit-form-${commentId}`);
                if (!editForm) return;
                const textarea = editForm.querySelector('.edit-textarea');
                if (!textarea) return;
                const newText = textarea.value.trim();
                if (!newText) {
                    if (typeof showWarningAlert === 'function') showWarningAlert('Komentar tidak boleh kosong');
                    else alert('Komentar tidak boleh kosong');
                    return;
                }
                const saveBtn = editForm.querySelector('.save-edit-btn');
                if (!saveBtn) return;
                const origHtml = saveBtn.innerHTML;
                saveBtn.innerHTML = '<div class="comment-loading" style="width:14px;height:14px;"></div>';
                saveBtn.disabled = true;
                try {
                    const lastUpdated = window.commentLastUpdated?.[postinganId] ?? '';
                    const response = await fetch(`/${window.locale}/komentar/update?id=${commentId}&id_postingan=${postinganId}&last_updated=${encodeURIComponent(lastUpdated)}`, {
                        method: 'PUT',
                        headers: getHeaders(),
                        body: JSON.stringify({ komentar: newText })
                    });
                    const data = await response.json();
                    if (data.success) {
                        await window.loadComments(postinganId);
                        if (typeof showSuccessAlert === 'function') showSuccessAlert('Komentar berhasil diperbarui');
                        else alert('Komentar berhasil diperbarui');
                    } else if (data.code === 'STALE_DATA') {
                        await window.loadComments(postinganId);
                        if (typeof showWarningAlert === 'function') showWarningAlert('Komentar telah diperbarui oleh pengguna lain. Edit ulang jika diperlukan.');
                        else alert('Komentar telah diperbarui oleh pengguna lain. Edit ulang jika diperlukan.');
                    } else {
                        if (typeof showErrorAlert === 'function') showErrorAlert(data.message || 'Gagal memperbarui komentar');
                        else alert(data.message || 'Gagal memperbarui komentar');
                        saveBtn.innerHTML = origHtml;
                        saveBtn.disabled = false;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    if (typeof showErrorAlert === 'function') showErrorAlert('Terjadi kesalahan: ' + error.message);
                    else alert('Terjadi kesalahan: ' + error.message);
                    saveBtn.innerHTML = origHtml;
                    saveBtn.disabled = false;
                }
            };

            window.cancelEdit = function (commentId) {
                commentId = String(commentId);
                const commentTextEl = document.getElementById(`comment-text-${commentId}`);
                const editForm = document.getElementById(`edit-form-${commentId}`);
                if (commentTextEl) commentTextEl.style.display = 'block';
                if (editForm) editForm.remove();
                const editBtn = document.querySelector(`.edit-comment-btn[data-comment-id="${commentId}"]`);
                if (editBtn) editBtn.style.display = 'inline-flex';
            };

            // ==================== DELETE COMMENT ====================
            window.deleteComment = async function (commentId, postinganId, type = 'full') {
                commentId = String(commentId);
                postinganId = String(postinganId);
                const msg = type === 'single'
                    ? 'Apakah Anda yakin ingin menghapus balasan ini saja?'
                    : 'Apakah Anda yakin ingin menghapus komentar ini beserta semua balasannya?';
                
                const confirmDelete = async () => {
                    try {
                        const response = await fetch(`/${window.locale}/komentar/destroy?id=${commentId}&id_postingan=${postinganId}&type=${type}`, {
                            method: 'DELETE',
                            headers: getHeaders()
                        });
                        const data = await response.json();
                        if (data.success) {
                            await window.loadComments(postinganId);
                            await updateCommentCount(postinganId, -1);
                            if (typeof showSuccessAlert === 'function') showSuccessAlert('Komentar berhasil dihapus');
                            else alert('Komentar berhasil dihapus');
                        } else {
                            if (typeof showErrorAlert === 'function') showErrorAlert(data.message || 'Gagal menghapus komentar');
                            else alert(data.message || 'Gagal menghapus komentar');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        if (typeof showErrorAlert === 'function') showErrorAlert('Terjadi kesalahan: ' + error.message);
                        else alert('Terjadi kesalahan: ' + error.message);
                    }
                };

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: msg,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) confirmDelete();
                    });
                } else {
                    if (confirm(msg)) confirmDelete();
                }
            };

            async function updateCommentCount(postinganId, delta) {
                const countSpan = document.querySelector(`.comment-toggle[data-postingan-id="${postinganId}"] .comment-count`);
                if (countSpan) {
                    const current = parseInt(countSpan.innerText) || 0;
                    countSpan.innerText = Math.max(0, current + delta);
                }
            }

            // ==================== INITIALIZE EVENT LISTENERS ====================
            function setupCommentToggleListeners() {
                document.querySelectorAll('.comment-toggle').forEach(btn => {
                    btn.removeEventListener('click', window.handleCommentToggle);
                    btn.addEventListener('click', window.handleCommentToggle);
                });
            }

            window.handleCommentToggle = async function (e) {
                const btn = e.currentTarget;
                const postCard = btn.closest('.post-card');
                const commentSection = postCard.querySelector('.comment-section');
                const isHidden = commentSection.style.display !== 'block';
                commentSection.style.display = isHidden ? 'block' : 'none';
                if (isHidden && btn.dataset.postinganId) {
                    await window.loadComments(btn.dataset.postinganId);
                }
            };

            function setupShareListeners() {
                document.querySelectorAll('.share-btn').forEach(btn => {
                    btn.removeEventListener('click', window.handleShare);
                    btn.addEventListener('click', window.handleShare);
                });
            }

            function fallbackCopyToClipboard(text, btn) {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);

                try {
                    textarea.select();
                    const successful = document.execCommand('copy');
                    if (successful) {
                        const origHtml = btn.innerHTML;
                        btn.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
                        setTimeout(() => { btn.innerHTML = origHtml; }, 2000);
                        if (typeof showSuccessAlert === 'function') showSuccessAlert('Link berhasil disalin!');
                        else alert('Link berhasil disalin!');
                    } else {
                        if (typeof showErrorAlert === 'function') showErrorAlert('Gagal menyalin URL');
                        else alert('Gagal menyalin URL');
                    }
                } catch (err) {
                    console.error('Fallback copy error:', err);
                    if (typeof showErrorAlert === 'function') showErrorAlert('Gagal menyalin URL');
                    else alert('Gagal menyalin URL');
                } finally {
                    document.body.removeChild(textarea);
                }
            }

            window.handleShare = function (e) {
                e.preventDefault();
                e.stopPropagation();

                const btn = e.currentTarget;
                const postCard = btn.closest('.post-card');
                if (!postCard) return;
                const url = postCard.dataset.shareUrl || window.location.href;
                const title = postCard.querySelector('h3')?.innerText || 'Postingan Menarik';

                if (navigator.share) {
                    navigator.share({ title, url }).catch(() => {});
                } else {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(url)
                            .then(() => {
                                const origHtml = btn.innerHTML;
                                btn.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
                                setTimeout(() => { btn.innerHTML = origHtml; }, 2000);
                                if (typeof showSuccessAlert === 'function') showSuccessAlert('Link berhasil disalin!');
                                else alert('Link berhasil disalin!');
                            })
                            .catch(err => {
                                console.error('Clipboard error:', err);
                                fallbackCopyToClipboard(url, btn);
                            });
                    } else {
                        fallbackCopyToClipboard(url, btn);
                    }
                }
            };

            function setupLikeListeners() {
                document.querySelectorAll('.like-btn').forEach(btn => {
                    btn.removeEventListener('click', window.handleLike);
                    btn.addEventListener('click', window.handleLike);
                });
            }

            window.handleLike = async function (e) {
                e.preventDefault();
                e.stopPropagation();
                const btn = e.currentTarget;
                const postinganId = btn.getAttribute('data-postingan-id');
                try {
                    const response = await fetch(`/${window.locale}/postingan/toggle-like?id=${postinganId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    const data = await response.json();
                    if (data.success) {
                        btn.querySelector('.like-count').textContent = data.like_count;
                        const svg = btn.querySelector('svg');
                        if (data.liked) {
                            btn.classList.add('text-red-500');
                            svg.classList.add('fill-current', 'text-red-500');
                        } else {
                            btn.classList.remove('text-red-500');
                            svg.classList.remove('fill-current', 'text-red-500');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            };

            document.addEventListener('DOMContentLoaded', function() {
                setupCommentToggleListeners();
                setupShareListeners();
                setupLikeListeners();
            });
        </script>
    @endpush
@endsection