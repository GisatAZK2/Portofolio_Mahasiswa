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
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-200">Project Mahasiswa</h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 mt-1">Beberapa Pameran Project Mahasiswa</p>
            </div>

            <!-- Projects Grid -->
            <section>
                @if($projects->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        @foreach($projects as $project)
                            @php
                                $content = $project->isi_content ?? [];
                                $nama = $content['nama_project'] ?? 'Tanpa Nama Project';
                                $deskripsi = $content['deskripsi'] ?? null;
                                $linkProject = $content['link_project'] ?? null;
                                $linkGithub = $content['link_github'] ?? null;
                                $linkVideo = $content['link_video'] ?? null;
                                $thumbnail = $content['thumbnail'] ?? null;

                                // Tanggal dari field model
                                $mulaiRaw = $project->tanggal_mulai ?? null;
                                $akhirRaw = $project->tanggal_akhir ?? null;

                                $mulai = $mulaiRaw ? \Carbon\Carbon::parse($mulaiRaw) : null;
                                $akhir = $akhirRaw ? \Carbon\Carbon::parse($akhirRaw) : null;
                                $today = \Carbon\Carbon::today();

                                $mulaiFormatted = $mulai ? $mulai->translatedFormat('d M Y') : '—';
                                $akhirFormatted = $akhir ? $akhir->translatedFormat('d M Y') : 'Sekarang';

                                // Status logic
                                $status = '—';
                                $statusBadgeClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                $statusText = 'Tidak diketahui';

                                if ($mulai && $akhir) {
                                    if ($akhir < $today) {
                                        $status = 'Past';
                                        $statusBadgeClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                                        $statusText = 'Selesai';
                                    } elseif ($mulai <= $today && $today <= $akhir) {
                                        $status = 'Now';
                                        $statusBadgeClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                        $statusText = 'Sedang Berjalan';
                                    } elseif ($mulai > $today) {
                                        $status = 'Coming';
                                        $statusBadgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                                        $statusText = 'Akan Datang';
                                    }
                                } elseif ($mulai && !$akhir) {
                                    if ($mulai <= $today) {
                                        $status = 'Now';
                                        $statusBadgeClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                        $statusText = 'Sedang Berjalan';
                                    } else {
                                        $status = 'Coming';
                                        $statusBadgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                                        $statusText = 'Akan Datang';
                                    }
                                } elseif (!$mulai && $akhir) {
                                    if ($akhir < $today) {
                                        $status = 'Past';
                                        $statusBadgeClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                                        $statusText = 'Selesai';
                                    }
                                }

                                // YouTube embed
                                $embedVideo = null;
                                $youtube_id = null;
                                if ($linkVideo) {
                                    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([^"&?\/\s]{11})/i', $linkVideo, $matches);
                                    if (!empty($matches[1])) {
                                        $youtube_id = $matches[1];
                                        $embedVideo = "https://www.youtube.com/embed/" . $youtube_id;
                                    }
                                }

                                // Get user information
                                $mahasiswa = $project->mahasiswa;
                                $leader = $project->leader;
                                $isSameUser = $mahasiswa && $leader && $mahasiswa->id === $leader->id;
                            @endphp

                            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col h-full border border-gray-200 dark:border-gray-700">
                                
                                <!-- Media Header -->
                                <div class="relative w-full h-40 sm:h-48 bg-gray-100 dark:bg-gray-800 overflow-hidden">
                                    @if($embedVideo)
                                        <div class="relative w-full h-full">
                                            <iframe class="absolute inset-0 w-full h-full" src="{{ $embedVideo }}?rel=0&modestbranding=1" 
                                                title="Video: {{ $nama }}" frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen></iframe>
                                        </div>
                                    @elseif($thumbnail && Storage::disk('public')->exists($thumbnail))
                                        <img src="{{ Storage::url($thumbnail) }}" alt="{{ $nama }}"
                                            class="w-full h-full object-cover">
                                    @elseif($linkProject)
                                        <div class="w-full h-full bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/50 dark:to-purple-900/50 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Status Badge -->
                                    <div class="absolute top-2 right-2">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusBadgeClass }} shadow-sm">
                                            {{ $statusText }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-4 sm:p-5 flex flex-col flex-1">
                                    
                                    <!-- User Info - SEPERTI CONTOH SERTIFIKAT dengan LINK -->
                                    <div class="flex items-center gap-3 mb-3">
                                        <!-- Foto Profile Leader/Owner dengan LINK -->
                                        <a href="{{ route('portfolio.show', ($leader ?? $mahasiswa)->id) }}" class="flex-shrink-0 hover:opacity-80 transition-opacity">
                                            @php
                                                $displayUser = $leader ?? $mahasiswa;
                                                $userName = $displayUser->nama_mahasiswa ?? 'User';
                                                $userInitial = substr($userName, 0, 1);
                                            @endphp
                                            
                                            @if($displayUser && $displayUser->photo_profile && Storage::disk('public')->exists($displayUser->photo_profile))
                                                <img src="{{ Storage::url($displayUser->photo_profile) }}" 
                                                     alt="{{ $userName }}"
                                                     class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                                    {{ $userInitial }}
                                                </div>
                                            @endif
                                        </a>
                                        
                                        <!-- Nama dan Role dengan LINK -->
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <a href="{{ route('portfolio.show', ($leader ?? $mahasiswa)->id) }}" 
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

                                    <!-- Jika Owner berbeda dengan Leader, tampilkan owner tambahan dengan LINK -->
                                    @if($mahasiswa && $leader && !$isSameUser)
                                        <div class="flex items-center gap-2 mb-3 pl-2 border-l-2 border-gray-300 dark:border-gray-600">
                                            <a href="{{ route('portfolio.show', $mahasiswa->id) }}" class="flex-shrink-0 hover:opacity-80 transition-opacity">
                                                @if($mahasiswa->photo_profile && Storage::disk('public')->exists($mahasiswa->photo_profile))
                                                    <img src="{{ Storage::url($mahasiswa->photo_profile) }}" 
                                                         alt="{{ $mahasiswa->nama_mahasiswa }}"
                                                         class="w-6 h-6 rounded-full object-cover">
                                                @else
                                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-white text-xs font-bold">
                                                        {{ substr($mahasiswa->nama_mahasiswa ?? 'O', 0, 1) }}
                                                    </div>
                                                @endif
                                            </a>
                                            <span class="text-xs text-gray-600 dark:text-gray-400">
                                                <span class="text-gray-500 dark:text-gray-500">Owner:</span> 
                                                <a href="{{ route('portfolio.show', $mahasiswa->id) }}" 
                                                   class="font-medium hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                    {{ $mahasiswa->nama_mahasiswa }}
                                                </a>
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Project Title -->
                                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100 line-clamp-2 mb-2">
                                        <a href="{{ route('project.show', $project->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                            {{ $nama }}
                                        </a>
                                    </h3>

                                    <!-- Periode -->
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-2 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $mulaiFormatted }} - {{ $akhirFormatted }}</span>
                                    </div>

                                    <!-- Description -->
                                    @if($deskripsi)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2 flex-1">
                                            {{ $deskripsi }}
                                        </p>
                                    @endif

                                    <!-- Tech Stack (optional) -->
                                    @if(!empty($content['tech_stack']) && is_array($content['tech_stack']))
                                        <div class="flex flex-wrap gap-1 mb-3">
                                            @foreach(array_slice($content['tech_stack'], 0, 3) as $tech)
                                                <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-800 text-xs rounded text-gray-700 dark:text-gray-300">{{ $tech }}</span>
                                            @endforeach
                                            @if(count($content['tech_stack']) > 3)
                                                <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-800 text-xs rounded text-gray-700 dark:text-gray-300">+{{ count($content['tech_stack']) - 3 }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Links - SEPERTI CONTOH SERTIFIKAT -->
                                    <div class="flex flex-wrap items-center gap-3 mt-auto pt-3 border-t border-gray-100 dark:border-gray-800">
                                        @if($linkProject)
                                            <a href="{{ $linkProject }}" target="_blank" rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                                Website
                                            </a>
                                        @endif

                                        @if($linkGithub)
                                            <a href="{{ $linkGithub }}" target="_blank" rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1 text-sm text-gray-700 hover:text-black dark:text-gray-300 dark:hover:text-white font-medium">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                                                </svg>
                                                GitHub
                                            </a>
                                        @endif

                                        @if($linkVideo && !$embedVideo)
                                            <a href="{{ $linkVideo }}" target="_blank" rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1 text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Video
                                            </a>
                                        @endif
                                    </div>

                                    <!-- Footer Date - SEPERTI CONTOH SERTIFIKAT -->
                                    <div class="mt-3 text-xs text-gray-400 dark:text-gray-500">
                                        Diposting {{ $project->created_at?->format('d M Y H:i') ?? '—' }} WIB
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($projects, 'links'))
                        <div class="mt-8 flex justify-center">
                            {{ $projects->links() }}
                        </div>
                    @endif

                @else
                    <div class="text-center py-12 sm:py-16 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-4 text-gray-600 dark:text-gray-400 text-base sm:text-lg">Belum ada proyek yang ditampilkan.</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Silakan tambahkan proyek baru untuk memulai.</p>
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection