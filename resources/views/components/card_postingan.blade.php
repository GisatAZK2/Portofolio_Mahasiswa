{{-- Component untuk menampilkan card postingan dalam grid --}}
<div
    class="flex flex-col rounded-xl overflow-hidden border border-gray-100 dark:border-gray-900 bg-white dark:bg-gray-900 shadow-md hover:shadow-xl transition-all duration-300 p-5 lg:p-6 h-full">
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
                $userName = $post->mahasiswa->nama_mahasiswa ?? 'Pengguna';
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
                    $leaderName = $relatedProject->leader->nama_mahasiswa ?? 'Leader';
                    $leaderId = $relatedProject->leader->id;
                    $isLeaderAvailable = true;
                }

                // Ambil data owner (pembuat project) dari id_mahasiswa
                if ($relatedProject && isset($relatedProject->mahasiswa) && $relatedProject->mahasiswa) {
                    $ownerPhoto = $relatedProject->mahasiswa->photo_profile;
                    $ownerName = $relatedProject->mahasiswa->nama_mahasiswa ?? 'Owner';
                    $ownerId = $relatedProject->mahasiswa->id;
                }
            }
        } elseif ($post->type === 'project' || $post->type === 'project_user') {
            // Data untuk project
            if ($post->mahasiswa) {
                $userPhoto = $post->mahasiswa->photo_profile;
                $userName = $post->mahasiswa->nama_mahasiswa ?? 'Pengguna';
                $userId = $post->mahasiswa->id;
            }
        } elseif ($post->type === 'sertifikat') {
            // Data untuk sertifikat
            if ($post->mahasiswa) {
                $userPhoto = $post->mahasiswa->photo_profile;
                $userName = $post->mahasiswa->nama_mahasiswa ?? 'Pengguna';
                $userId = $post->mahasiswa->id;
            }
        }

        // Data untuk project card
        $nama_project = $projectData['nama_project'] ?? ($relatedProjectData['nama_project'] ?? '(Nama Project Tidak Tersedia)');
        $link_project = $projectData['link_project'] ?? $relatedProjectData['link_project'] ?? '';
        $link_github = $projectData['link_github'] ?? $relatedProjectData['link_github'] ?? '';
        $link_video = $projectData['link_video'] ?? $relatedProjectData['link_video'] ?? '';

        // Ekstrak youtube_id jika link_video adalah YouTube
        $youtube_id = '';
        if ($link_video) {
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $link_video, $matches)) {
                $youtube_id = $matches[1];
            }
        }

        // Tentukan data yang akan ditampilkan untuk leader/owner
        $displayPhoto = $isLeaderAvailable ? $leaderPhoto : $ownerPhoto;
        $displayName = $isLeaderAvailable ? $leaderName : $ownerName;
        $displayId = $isLeaderAvailable ? $leaderId : $ownerId;
        $displayRole = $isLeaderAvailable ? 'Leader' : 'Owner';
    @endphp

    <!-- User Info -->
    <a href="{{ $userId ? route('portfolio.show', ['user' => $userId]) : '#' }}"
        class="flex items-center space-x-3 mb-4 hover:opacity-80 transition-opacity">
        <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-gray-100 shadow-sm shrink-0 relative">
            @if($userPhoto)
                <img src="{{ asset('storage/' . ltrim($userPhoto, '/')) }}" alt="{{ $userName }}"
                    class="w-full h-full object-cover" loading="lazy"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div
                    class="absolute inset-0 hidden bg-linear-to-br from-indigo-500 to-purple-600 items-center justify-center text-white font-bold text-lg">
                    {{ substr($userName, 0, 1) }}
                </div>
            @else
                <div
                    class="w-full h-full bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                    {{ substr($userName, 0, 1) }}
                </div>
            @endif
        </div>
        <div>
            <p class="font-semibold text-gray-900 dark:text-gray-300">
                {{ $userName }}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-200">
                {{ $post->created_at?->diffForHumans() ?? $post->tanggal?->diffForHumans() ?? 'Baru saja' }}
            </p>
        </div>
    </a>

    <!-- Badge -->
    <div class="flex items-center gap-2 mb-3 flex-wrap">
        @if($post->type === 'learning')
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 w-fit">
                Learning Corner
                @if($relatedProject)
                    <span
                        class="ml-1 text-purple-600">({{ Str::limit($relatedProjectData['nama_project'] ?? 'Project', 30) }})</span>
                @endif
            </span>
        @elseif($post->type === 'project' || $post->type === 'project_user')
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 w-fit">Project</span>
        @endif
    </div>

    <!-- Konten utama -->
    <div class="flex-1 mt-3">
        @if($post->type === 'learning')
            <!-- Title - Clickable ke Project -->
            @if($relatedProject)
                <a href="{{ route('project.show', ['id' => $relatedProject->id]) }}"
                    class="block hover:text-indigo-700 transition-colors">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">
                        {{ $learningTitle ?: ($relatedProjectData['nama_project'] ?? 'Learning Corner') }}
                    </h3>
                </a>
            @else
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">
                    {{ $learningTitle ?: 'Learning Corner' }}
                </h3>
            @endif

            <!-- Text with expand/collapse -->
            @if($learningText)
                <div class="relative">
                    <p class="text-gray-700 dark:text-gray-300 mb-3 text-sm line-clamp-3">
                        {{ $learningText }}
                    </p>
                </div>
            @endif

            <!-- Images -->
            @if(count($learningImages) > 0)
                <div class="space-y-3 mt-2">
                    @php $firstImage = $learningImages[0]; @endphp
                    @php $imagePath = str_replace(['\\', '/'], '/', $firstImage['content'] ?? ''); @endphp
                    <img src="{{ asset('storage/' . ltrim($imagePath, '/')) }}" alt="{{ $firstImage['alt'] ?? 'Gambar' }}"
                        class="w-full h-40 object-cover rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm"
                        loading="lazy"
                        onerror="this.src='https://via.placeholder.com/400x200?text=Gambar+Tidak+Ditemukan';this.onerror=null;">
                </div>
            @endif

            <!-- Links -->
            @if(count($learningLinks) > 0)
                <div class="mt-3 space-y-1">
                    @foreach($learningLinks as $link)
                        <a href="{{ $link['content'] }}" target="_blank"
                            class="text-indigo-600 hover:text-indigo-800 text-sm block underline line-clamp-1 break-all">
                            {{ Str::limit($link['content'], 60) }}
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Related Project Info -->
            @if($relatedProject)
                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Project Terkait:</p>
                    <a href="{{ route('project.show', ['id' => $relatedProject->id]) }}"
                        class="text-sm font-semibold text-gray-800 dark:text-gray-200 hover:text-indigo-600 transition-colors line-clamp-1">
                        {{ $relatedProjectData['nama_project'] ?? $relatedProject->nama_project ?? 'Project' }}
                    </a>
                </div>
            @endif

        @elseif($post->type === 'project' || $post->type === 'project_user')
            <!-- Project Title -->
            <a href="{{ route('project.show', ['id' => $post->id]) }}"
                class="block hover:text-indigo-700 transition-colors">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 line-clamp-2 leading-tight">
                    {{ $nama_project }}
                </h3>
            </a>

            <!-- Deskripsi Project -->
            @if(!empty($projectData['deskripsi']))
                <p class="text-sm text-gray-700 dark:text-gray-300 mt-2 line-clamp-2">
                    {{ $projectData['deskripsi'] }}
                </p>
            @endif

            <!-- Periode -->
            @if(!empty($projectData['tanggal_mulai']) || $post->tanggal_mulai)
                <div class="text-sm text-gray-600 dark:text-gray-200 mt-2">
                    <p><span class="font-medium">Periode:</span>
                        @if(!empty($projectData['tanggal_mulai']))
                            {{ \Carbon\Carbon::parse($projectData['tanggal_mulai'])->format('M Y') }}
                            @if(!empty($projectData['tanggal_akhir']))
                                → {{ \Carbon\Carbon::parse($projectData['tanggal_akhir'])->format('M Y') }}
                            @else
                                → Sekarang
                            @endif
                        @elseif($post->tanggal_mulai)
                            {{ \Carbon\Carbon::parse($post->tanggal_mulai)->format('M Y') }}
                            @if($post->tanggal_akhir)
                                → {{ \Carbon\Carbon::parse($post->tanggal_akhir)->format('M Y') }}
                            @else
                                → Sekarang
                            @endif
                        @endif
                    </p>
                </div>
            @endif

            <!-- YouTube Video -->
            @if($youtube_id)
                <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-800 shadow-sm mt-4">
                    <div class="aspect-video">
                        <iframe class="w-full h-full"
                            src="https://www.youtube.com/embed/{{ $youtube_id }}?rel=0&modestbranding=1"
                            title="Video {{ $nama_project }}" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    </div>
                </div>
            @endif

            <!-- Links -->
            @if($link_project || $link_github || $link_video)
                <div class="flex flex-wrap gap-4 text-sm mt-4">
                    @if($link_project)
                        <a href="{{ $link_project }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex hover:underline items-center text-orange-600 hover:text-orange-800 font-medium transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            Demo
                        </a>
                    @endif

                    @if($link_github)
                        <a href="{{ $link_github }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex hover:underline items-center text-orange-600 hover:text-orange-800 font-medium transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
                            </svg>
                            GitHub
                        </a>
                    @endif

                    @if($link_video && !$youtube_id)
                        <a href="{{ $link_video }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex hover:underline items-center text-orange-600 hover:text-orange-800 font-medium transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Video
                        </a>
                    @endif
                </div>
            @endif

        @elseif($post->type === 'sertifikat')
            <!-- Sertifikat Title -->
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 line-clamp-2 leading-tight mb-2">
                {{ $post->nama_sertifikat }}
            </h3>

            <!-- Lembaga Penerbit -->
            @if($post->lembaga_penerbit)
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                    <span class="font-medium">Lembaga:</span> {{ $post->lembaga_penerbit }}
                </p>
            @endif

            <!-- Tanggal Terbit -->
            @if($post->tanggal_terbit)
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                    <span class="font-medium">Terbit:</span> {{ \Carbon\Carbon::parse($post->tanggal_terbit)->format('d M Y') }}
                </p>
            @endif

            <!-- Status Badge -->
            <div class="flex items-center gap-2 mb-3">
                @if($post->status_pengajuan === 'Di Terima')
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        ✓ Diterima
                    </span>
                @elseif($post->status_pengajuan === 'Ditolak')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        ✗ Ditolak
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        ⊙ Pengajuan
                    </span>
                @endif
            </div>

            <!-- Link Sertifikat -->
            @if($post->link_sertifikat)
                <div class="mt-4">
                    <a href="{{ asset('storage/' . $post->link_sertifikat) }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex hover:underline items-center text-green-600 hover:text-green-800 font-medium transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Lihat Sertifikat
                    </a>
                </div>
            @endif
        @endif
    </div>

    <!-- Footer -->
    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-900">
        <p class="text-xs text-gray-500 dark:text-gray-50 line-clamp-2">
            <span>Diposting</span>
            {{ $post->created_at?->format('d M Y H:i') ?? $post->tanggal?->format('d M Y') ?? '—' }}
            <span>oleh</span>
            {{ $userName }}
            @if($relatedProject)
                <span>untuk project</span>
                <a href="{{ route('project.show', ['id' => $relatedProject->id]) }}"
                    class="text-indigo-600 hover:text-indigo-800 transition-colors font-medium">
                    {{ Str::limit($relatedProjectData['nama_project'] ?? 'Project', 30) }}
                </a>
            @endif
        </p>
    </div>
</div>