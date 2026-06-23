@extends('Layout.Layout')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-800 rounded-2xl py-4 sm:py-6 px-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            @if(session('success'))
                <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 dark:bg-green-700 border border-green-200 text-green-800 rounded-lg flex items-center gap-2 sm:gap-3 text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Header Sederhana -->
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-200"
                        data-translate="kelola_project_title" data-translate-page="kelola_project"></h1>
                    <a href="{{ route('admin.projects.create') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span data-translate="tambah_project" data-translate-page="kelola_project"></span>
                    </a>
                </div>

                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 mt-1" data-translate="kelola_project_desc"
                    data-translate-page="kelola_project"></p>
            </div>

            <!-- Search and Filter Section -->
            <div class="mb-6 bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <form method="GET" action="{{ route('admin.projects.index') }}" class="flex flex-col sm:flex-row gap-4">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        <input type="text" name="search" placeholder="Cari nama project..."
            value="{{ request('search') }}"
            class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-700 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500">
    </div>
    <select name="status" class="px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">Semua Status</option>
        <option value="Sedang Berjalan" {{ request('status') == 'Sedang Berjalan' ? 'selected' : '' }}>Sedang Berjalan</option>
        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
        <option value="Akan Datang" {{ request('status') == 'Akan Datang' ? 'selected' : '' }}>Akan Datang</option>
    </select>
    <div class="flex gap-2">
        <a href="{{ route('admin.projects.index') }}" 
            class="px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
            Reset
        </a>
        <button type="submit"
            class="px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-all shadow-sm">
            Terapkan
        </button>
    </div>
</form>
            </div>

            <!-- Results Info & Bulk Actions -->
            <div class="mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <input id="selectAllProjects" type="checkbox"
                            class="w-4 h-4 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800">
                        <span data-translate="select_all" data-translate-page="admin">Pilih Semua</span>
                    </label>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        <span data-translate="selected" data-translate-page="admin">Terpilih:</span> 
                        <strong id="selectedCount">0</strong> / 
                        <strong id="totalProjectCount">{{ $projects->count() }}</strong>
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        | <span id="filteredCount">{{ $projects->count() }}</span> <span data-translate="projects_displayed" data-translate-page="admin"></span>
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="confirmBulkDelete()"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition shadow-sm text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span data-translate="delete_select" data-translate-page="admin">Hapus Terpilih</span>
                    </button>
                </div>
            </div>

            <!-- Projects Grid -->
            <section id="projectsGrid">
                @if($projects->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6" id="projectCardsContainer">
                        @foreach($projects as $project)
                            @php
                                $content = $project->isi_content ?? [];
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

                                if ($mulai && $akhir) {
                                    if ($akhir < $today) {
                                        $statusBadgeClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                                        $statusText = 'Selesai';
                                    } elseif ($mulai <= $today && $today <= $akhir) {
                                        $statusBadgeClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                        $statusText = 'Sedang Berjalan';
                                    } elseif ($mulai > $today) {
                                        $statusBadgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                                        $statusText = 'Akan Datang';
                                    }
                                } elseif ($mulai && !$akhir) {
                                    if ($mulai <= $today) {
                                        $statusBadgeClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                        $statusText = 'Sedang Berjalan';
                                    } else {
                                        $statusBadgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                                        $statusText = 'Akan Datang';
                                    }
                                } elseif (!$mulai && $akhir) {
                                    if ($akhir < $today) {
                                        $statusBadgeClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                                        $statusText = 'Selesai';
                                    }
                                }

                                $embedVideo = null;
                                if ($linkVideo) {
                                    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([^"&?\/\s]{11})/i', $linkVideo, $matches);
                                    if (!empty($matches[1])) {
                                        $embedVideo = "https://www.youtube.com/embed/" . $matches[1];
                                    }
                                }

                                $mahasiswa = $project->mahasiswa;
                                $leader = $project->leader;
                                $isSameUser = $mahasiswa && $leader && $mahasiswa->id === $leader->id;
                            @endphp

                            <div class="project-card bg-white dark:bg-gray-900 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col h-full border border-gray-200 dark:border-gray-700"
                                 data-project-name="{{ strtolower($nama) }}"
                                 data-project-status="{{ $statusText }}">
                                <div class="relative w-full h-40 sm:h-48 bg-gray-100 dark:bg-gray-800 overflow-hidden">
                                    @if($embedVideo)
                                        <iframe class="absolute inset-0 w-full h-full"
                                            src="{{ $embedVideo }}?rel=0&modestbranding=1" 
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                    @elseif($thumbnail && Storage::disk('public')->exists($thumbnail))
                                        <img src="{{ Storage::url($thumbnail) }}" alt="{{ $nama }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 flex flex-col items-center justify-center text-center px-4">
                                            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm sm:text-base font-medium">Tidak ada thumbnail</p>
                                        </div>
                                    @endif

                                    <div class="absolute top-2 right-2">
                                        <span class="project-status inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusBadgeClass }} shadow-sm">
                                            {{ $statusText }}
                                        </span>
                                    </div>
                                </div>

                                <div class="p-4 sm:p-5 flex flex-col flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <label class="inline-flex items-center mr-2">
                                            <input type="checkbox" value="{{ $project->id }}"
                                                class="project-checkbox w-4 h-4 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800">
                                        </label>
                                        <a href="{{ route('portfolio.show', ['user' => ($leader ?? $mahasiswa)->id]) }}"
                                            class="flex-shrink-0 hover:opacity-80 transition-opacity">
                                            @php
                                                $displayUser = $leader ?? $mahasiswa;
                                                $userName = $displayUser->nama_mahasiswa ?? 'User';
                                                $userInitial = substr($userName, 0, 1);
                                            @endphp

                                            @if($displayUser && $displayUser->photo_profile && Storage::disk('public')->exists($displayUser->photo_profile))
                                                <img src="{{ Storage::url($displayUser->photo_profile) }}" alt="{{ $userName }}"
                                                    class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                                    {{ $userInitial }}
                                                </div>
                                            @endif
                                        </a>

                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <a href="{{ route('portfolio.show', ['user' => ($leader ?? $mahasiswa)->id]) }}"
                                                    class="font-semibold text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                    {{ $userName }}
                                                </a>
                                                @if($leader && $mahasiswa)
                                                    @if($isSameUser)
                                                        <span class="text-xs bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200 px-2 py-0.5 rounded-full">Owner & Leader</span>
                                                    @else
                                                        <span class="text-xs bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 px-2 py-0.5 rounded-full">Leader</span>
                                                    @endif
                                                @elseif($leader)
                                                    <span class="text-xs bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 px-2 py-0.5 rounded-full">Leader</span>
                                                @else
                                                    <span class="text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-2 py-0.5 rounded-full">Owner</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                {{ $project->created_at?->diffForHumans() ?? 'Baru saja' }}
                                            </p>
                                        </div>
                                    </div>

                                    @if($mahasiswa && $leader && !$isSameUser)
                                        <div class="flex items-center gap-2 mb-3 pl-2 border-l-2 border-gray-300 dark:border-gray-600">
                                            <a href="{{ route('portfolio.show', ['user' => $mahasiswa->id]) }}" class="flex-shrink-0 hover:opacity-80 transition-opacity">
                                                @if($mahasiswa->photo_profile && Storage::disk('public')->exists($mahasiswa->photo_profile))
                                                    <img src="{{ Storage::url($mahasiswa->photo_profile) }}" alt="{{ $mahasiswa->nama_mahasiswa }}" class="w-6 h-6 rounded-full object-cover">
                                                @else
                                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-white text-xs font-bold">
                                                        {{ substr($mahasiswa->nama_mahasiswa ?? 'O', 0, 1) }}
                                                    </div>
                                                @endif
                                            </a>
                                            <span class="text-xs text-gray-600 dark:text-gray-400">
                                                <span class="text-gray-500 dark:text-gray-500">Owner:</span>
                                                <a href="{{ route('portfolio.show', ['user' => $mahasiswa->id]) }}" class="font-medium hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                    {{ $mahasiswa->nama_mahasiswa }}
                                                </a>
                                            </span>
                                        </div>
                                    @endif

                                    <h3 class="project-name text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100 line-clamp-2 mb-2">
                                        <a href="{{ route('project.show', ['id' => $project->id]) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                            {{ $nama }}
                                        </a>
                                    </h3>

                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-2 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $mulaiFormatted }} - {{ $akhirFormatted }}</span>
                                    </div>

                                    @if($deskripsi)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2 flex-1">{{ $deskripsi }}</p>
                                    @endif

                                    <div class="flex flex-wrap items-center gap-3 mt-auto pt-3 border-t border-gray-100 dark:border-gray-800">
                                        @if($linkProject)
                                            <a href="{{ $linkProject }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                                Website
                                            </a>
                                        @endif

                                        @if($linkGithub)
                                            <a href="{{ $linkGithub }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-gray-700 hover:text-black dark:text-gray-300 font-medium">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                                                </svg>
                                                GitHub
                                            </a>
                                        @endif

                                        @if($linkVideo && !$embedVideo)
                                            <a href="{{ $linkVideo }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-red-600 hover:text-red-800 dark:text-red-400 font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Video
                                            </a>
                                        @endif
                                    </div>

                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <a href="{{ route('admin.projects.details', ['id' => $project->id]) }}"
                                            class="inline-flex items-center gap-2 px-3 py-2 bg-yellow-100 text-yellow-800 rounded-lg text-xs font-medium hover:bg-yellow-200 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <button type="button" onclick="confirmDeleteProject('{{ $project->id }}', '{{ addslashes($nama) }}')"
                                            class="inline-flex items-center gap-2 px-3 py-2 bg-red-100 text-red-800 rounded-lg text-xs font-medium hover:bg-red-200 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span data-translate="delete" data-translate-page="admin">Hapus</span>
                                        </button>
                                    </div>

                                    <div class="mt-3 text-xs text-gray-400 dark:text-gray-500">
                                        <span data-translate="posted" data-translate-page="admin">Diposting: </span> {{ $project->created_at?->format('d M Y H:i') ?? '—' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Empty State (hidden by default) -->
                    <div id="emptyState" class="hidden text-center py-12 sm:py-16 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-4 text-gray-600 dark:text-gray-400 text-base sm:text-lg">Tidak ada project ditemukan.</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Coba ubah filter pencarian Anda.</p>
                    </div>

                    @if(method_exists($projects, 'links'))
                        <div class="mt-8 flex justify-center" data-pagination-group="admin_projects" id="paginationContainer">
                            {{ $projects->render('vendor.pagination.custom_ajax', ['groupName' => 'admin_projects']) }}
                        </div>
                    @endif

                @else
                    <div class="text-center py-12 sm:py-16 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-4 text-gray-600 dark:text-gray-400 text-base sm:text-lg">Belum ada proyek yang ditampilkan.</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Silakan tambahkan proyek baru untuk memulai.</p>
                    </div>
                @endif

                <form id="deleteProjectForm" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </section>
        </div>
    </div>

    <script>
        // Filter functions
        function applyFilters() {
            const searchTerm = document.getElementById('searchProject').value;
            const statusFilter = document.getElementById('filterStatus').value;
            
            // Redirect dengan parameter filter
            const locale = document.querySelector('html').getAttribute('lang') || 'id';
            let url = `/${locale}/admin/manageProject`;
            let params = new URLSearchParams();
            
            if (searchTerm) {
                params.append('search', searchTerm);
            }
            if (statusFilter) {
                params.append('status', statusFilter);
            }
            
            if (params.toString()) {
                url += '?' + params.toString();
            }
            
            window.location.href = url;
        }

        function resetFilters() {
            // Redirect tanpa filter
            const locale = document.querySelector('html').getAttribute('lang') || 'id';
            window.location.href = `/${locale}/admin/manageProject`;
        }
        
        // Search on Enter key
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchProject');
            if (searchInput) {
                searchInput.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') {
                        applyFilters();
                    }
                });
            }
            
            // Initial count
            const cards = document.querySelectorAll('.project-card');
            document.getElementById('filteredCount').textContent = cards.length;
        });

        function updateSelectedProjects() {
            const visibleCheckboxes = Array.from(document.querySelectorAll('.project-checkbox'))
                .filter(cb => cb.closest('.project-card')?.style.display !== 'none');
            const selectedCheckboxes = visibleCheckboxes.filter(cb => cb.checked);
            const selectedCountEl = document.getElementById('selectedCount');
            const selectAllCheckbox = document.getElementById('selectAllProjects');

            if (selectedCountEl) {
                selectedCountEl.textContent = selectedCheckboxes.length;
            }

            if (selectAllCheckbox) {
                if (selectedCheckboxes.length === visibleCheckboxes.length && visibleCheckboxes.length > 0) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else if (selectedCheckboxes.length === 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = true;
                }
            }
        }

        async function confirmBulkDelete() {
            const visibleCheckboxes = Array.from(document.querySelectorAll('.project-checkbox'))
                .filter(cb => cb.closest('.project-card')?.style.display !== 'none');
            const selectedCheckboxes = visibleCheckboxes.filter(cb => cb.checked);
            const selectedIds = selectedCheckboxes.map(cb => cb.value);
            
            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Ada Data Dipilih',
                    text: 'Silakan pilih setidaknya satu project sebelum menghapus.',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            const result = await Swal.fire({
                title: 'Hapus Project Terpilih?',
                html: `${selectedIds.length} project akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });

            if (result.isConfirmed) {
                const form = document.createElement('form');
                const locale = document.querySelector('html').getAttribute('lang') || 'id';
                
                form.method = 'POST';
                form.action = `/${locale}/admin/manageProject/bulk-destroy`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfInput);
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                const idsInput = document.createElement('input');
                idsInput.type = 'hidden';
                idsInput.name = 'selected_ids';
                idsInput.value = JSON.stringify(selectedIds);
                form.appendChild(idsInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function confirmDeleteProject(projectId, projectName) {
            Swal.fire({
                title: 'Hapus Project?',
                html: `Apakah Anda yakin ingin menghapus project <strong>"${projectName}"</strong>?<br>Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const deleteForm = document.getElementById('deleteProjectForm');
                    const locale = document.querySelector('html').getAttribute('lang') || 'id';
                    
                    let url = `/${locale}/admin/manageProject/DeleteProject?id=${projectId}`;
                    deleteForm.action = url;
                    deleteForm.submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const projectCheckboxes = document.querySelectorAll('.project-checkbox');
            projectCheckboxes.forEach(cb => cb.addEventListener('change', updateSelectedProjects));

            const selectAllProjects = document.getElementById('selectAllProjects');
            if (selectAllProjects) {
                selectAllProjects.addEventListener('change', function () {
                    const visibleCheckboxes = Array.from(document.querySelectorAll('.project-checkbox'))
                        .filter(cb => cb.closest('.project-card')?.style.display !== 'none');
                    visibleCheckboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });
                    updateSelectedProjects();
                });
            }

            updateSelectedProjects();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof showPageInfo === 'function') {
                showPageInfo("popup.admin_projects");
            }
        });
    </script>
@endsection