@extends('Layout.Layout')
@section('title', 'Dashboard')
@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-700 rounded-2xl py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-10">
            <!-- Statistic Cards with Mini Charts -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Learning Corner -->
                <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-600 dark:bg-gray-900 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                          <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200"
                                data-translate="learning_corner" data-translate-page="dashboard_me">Learning Corner</h3>
                        <span class="text-purple-500">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </span>
                    </div>
                    <p class="text-4xl font-extrabold text-purple-600">{{ $totalLearning ?? 0 }}</p>
                    <div class="mt-4 h-20">
                        <canvas id="learningChart"></canvas>
                    </div>
                </div>

                <!-- Total Project Dikerjakan -->
                <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-600 dark:bg-gray-900 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-semibold text-gray-700 dark:text-gray-300" data-translate="total_project" data-translate-page="dashboard_me">Total Project Dikerjakan</h3>
                        <span class="text-orange-500">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                    <p class="text-4xl font-extrabold text-orange-600">{{ $totalProject ?? 0 }}</p>
                    <div class="mt-4 h-20">
                        <canvas id="projectChart"></canvas>
                    </div>
                </div>

                <!-- Total Sertifikat Didapat -->
                <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-600 dark:bg-gray-900 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-semibold text-gray-700" data-translate="total_sertifikat"
                                data-translate-page="dashboard_me">Total Sertifikat Didapat</h3>
                        <span class="text-amber-500">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </span>
                    </div>
                    <p class="text-4xl font-extrabold text-amber-600">{{ $totalSertifikat ?? 0 }}</p>
                    <div class="mt-4 h-20">
                        <canvas id="sertifikatChart"></canvas>
                    </div>
                </div>
            </div>
        
            <!-- Random Posts - Masonry Layout -->
            <div>
                <div class="flex justify-between items-center mb-6">
                     <h2 class="text-2xl font-bold text-gray-900" data-translate="perihal_terbaru"
                        data-translate-page="dashboard_me">Perihal Terbaru</h2>
                    <a href="{{ route('search') ?? '#' }}"
                        class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center gap-1">
                        Lihat Semua →
                    </a>
                </div>

                @if($randomPosts->isEmpty())
                    <div class="text-center py-12 bg-white rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 shadow-sm">
                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-4 text-gray-600 dark:text-gray-200">Belum ada postingan acak untuk ditampilkan saat ini.</p>
                    </div>
                @else
                    <!-- Masonry Grid with columns -->
                    <div class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6">
                        @foreach($randomPosts as $post)
                            @php
                                // Decode JSON content untuk semua tipe
                                $projectData = [];
                                if ($post->type === 'project' && $post->isi_content) {
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
                                } elseif ($post->type === 'project') {
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
                                
                                // Cek status sertifikat (untuk tipe sertifikat)
                                $isSertifikatValid = false;
                                $statusBadge = '';
                                $statusColor = '';
                                if ($post->type === 'sertifikat') {
                                    $isSertifikatValid = ($post->status_pengajuan === 'Di Terima' && $post->is_active == 1);
                                    
                                    if ($post->status_pengajuan === 'Di Terima' && $post->is_active == 1) {
                                        $statusBadge = 'Aktif';
                                        $statusColor = 'green';
                                    } elseif ($post->status_pengajuan === 'Sedang Di Ajukan') {
                                        $statusBadge = 'Sedang Diajukan';
                                        $statusColor = 'yellow';
                                    } elseif ($post->status_pengajuan === 'Di Tolak') {
                                        $statusBadge = 'Ditolak';
                                        $statusColor = 'red';
                                    } elseif ($post->is_active == 0) {
                                        $statusBadge = 'Tidak Aktif';
                                        $statusColor = 'gray';
                                    }
                                }
                            @endphp
                            
                            <!-- Card wrapper with independent expansion -->
                            <div 
                                class="flex flex-col h-fit rounded-xl overflow-hidden border border-gray-100 dark:border-gray-900 bg-white dark:bg-gray-900 shadow-md hover:shadow-xl transition-all duration-300 {{ $post->type !== 'sertifikat' ? 'group' : '' }} 
                                @if($post->type === 'sertifikat' && !$isSertifikatValid)
                                    opacity-70
                                @endif"
                                x-data="{ expanded: false }"
                            >
                                <!-- Card body -->
                                <div class="flex flex-col p-5 lg:p-6">
                                    <!-- User Info - Clickable ke Portfolio -->
                                    <a href="{{ $userId ? route('portfolio.show', ['user' => $userId]) : '#' }}" 
                                       class="flex items-center space-x-3 mb-4 hover:opacity-80 transition-opacity">
                                        <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-gray-100 shadow-sm flex-shrink-0 relative">
                                            @if($userPhoto)
                                                <img src="{{ asset('storage/' . ltrim($userPhoto, '/')) }}"
                                                    alt="{{ $userName }}"
                                                    class="w-full h-full object-cover" loading="lazy"
                                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="absolute inset-0 hidden bg-gradient-to-br from-indigo-500 to-purple-600 items-center justify-center text-white font-bold text-lg">
                                                    {{ substr($userName, 0, 1) }}
                                                </div>
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                                    {{ substr($userName, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-gray-300 group-hover:text-indigo-700 transition-colors">
                                                {{ $userName }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-200">
                                                {{ $post->created_at?->diffForHumans() ?? $post->tanggal?->diffForHumans() ?? 'Baru saja' }}
                                            </p>
                                        </div>
                                    </a>

                                    <!-- Badge -->
                                    @if($post->type === 'learning')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mb-3 w-fit">
                                            Learning Corner
                                            @if($relatedProject)
                                                <span class="ml-1 text-purple-600">({{ $relatedProjectData['nama_project'] ?? 'Project' }})</span>
                                            @endif
                                        </span>
                                    @elseif($post->type === 'project')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mb-3 w-fit">Project</span>
                                    @elseif($post->type === 'sertifikat')
                                        <div class="flex flex-wrap items-center gap-2 mb-3">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 w-fit">
                                                Sertifikat
                                            </span>
                                            
                                            <!-- Status Badge -->
                                            @if($statusBadge)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                    @if($statusColor == 'green') bg-green-100 text-green-800
                                                    @elseif($statusColor == 'yellow') bg-yellow-100 text-yellow-800
                                                    @elseif($statusColor == 'red') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $statusBadge }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Konten utama -->
                                    <div class="flex-1 mt-3">
                                        @if($post->type === 'sertifikat')
                                            <div class="flex flex-col space-y-3 
                                                @if(!$isSertifikatValid)
                                                    grayscale
                                                @endif">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 line-clamp-2">
                                                    {{ $post->nama_sertifikat ?? '(Tanpa Judul Sertifikat)' }}
                                                </h3>
                                                <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                                    </svg>
                                                    <span class="font-medium">Penerbit:</span>
                                                    <span class="ml-2">{{ $post->lembaga_penerbit ?? 'Tidak diketahui' }}</span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="font-medium">Terbit:</span>
                                                    <span class="ml-2">{{ $post->tanggal_terbit ? \Carbon\Carbon::parse($post->tanggal_terbit)->format('d M Y') : '—' }}</span>
                                                </div>
                                                
                                                <!-- Keterangan (untuk status non-aktif/ditolak) -->
                                                @if(!$isSertifikatValid && $post->keterangan)
                                                    <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                                            <span class="font-semibold">Keterangan:</span> {{ $post->keterangan }}
                                                        </p>
                                                    </div>
                                                @endif
                                                
                                                @if($post->link_sertifikat && $isSertifikatValid)
                                                    <a href="{{ asset('storage/' . $post->link_sertifikat) }}" target="_blank" rel="noopener noreferrer"
                                                       class="inline-flex hover:underline items-center gap-2 text-amber-600 hover:text-amber-800 font-medium text-sm bg-amber-50 hover:bg-amber-100 px-4 py-2 rounded-lg transition-colors self-start mt-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Lihat Sertifikat
                                                    </a>
                                                @elseif($post->link_sertifikat && !$isSertifikatValid)
                                                    <div class="mt-2 inline-flex items-center gap-2 text-gray-500 font-medium text-sm bg-gray-100 px-4 py-2 rounded-lg cursor-not-allowed">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                        </svg>
                                                        Sertifikat Tidak Tersedia
                                                    </div>
                                                @else
                                                    <p class="text-sm text-gray-500 dark:text-gray-50 italic">Tidak ada link sertifikat</p>
                                                @endif
                                                
                                                <!-- Informasi Status Tambahan -->
                                                @if(!$isSertifikatValid)
                                                    <div class="mt-3 text-xs text-gray-500 dark:text-gray-400 italic">
                                                        @if($post->status_pengajuan === 'Sedang Di Ajukan')
                                                            Sertifikat sedang dalam proses pengajuan
                                                        @elseif($post->status_pengajuan === 'Di Tolak')
                                                            Pengajuan sertifikat ditolak
                                                        @elseif($post->is_active == 0)
                                                            Sertifikat tidak aktif
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>

                                        @elseif($post->type === 'learning')
                                            @if($relatedProject)
                                                <a href="{{ route('project.show', ['id' => $relatedProject->id]) }}" 
                                                   class="block group-hover:text-indigo-700 transition-colors">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                                        {{ $learningTitle ?: ($relatedProjectData['nama_project'] ?? 'Learning Corner') }}
                                                    </h3>
                                                </a>
                                            @else
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                                    {{ $learningTitle ?: 'Learning Corner' }}
                                                </h3>
                                            @endif
                                            
                                            <!-- Text with expand/collapse -->
                                            @if($learningText)
                                                <div class="relative">
                                                    <p class="text-gray-700 dark:text-gray-300 mb-3 text-sm" 
                                                       :class="{ 'line-clamp-3': !expanded }"
                                                       x-show="!expanded || $el.scrollHeight <= $el.clientHeight">
                                                        {{ $learningText }}
                                                    </p>
                                                    <div x-show="expanded" x-collapse>
                                                        <p class="text-gray-700 dark:text-gray-300 mb-3 text-sm">
                                                            {{ $learningText }}
                                                        </p>
                                                    </div>
                                                    
                                                    @if(strlen($learningText) > 150)
                                                        <button @click="expanded = !expanded" 
                                                                class="text-indigo-600 hover:text-indigo-800 text-xs font-medium mt-1 flex items-center gap-1">
                                                            <span x-text="expanded ? 'Tampilkan lebih sedikit' : 'Baca selengkapnya'"></span>
                                                            <svg class="w-3 h-3" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            <!-- Images - Collapsible -->
                                            @if(count($learningImages) > 0)
                                                <div class="space-y-3 mt-2">
                                                    <!-- First image always visible -->
                                                    <div class="relative">
                                                        @php $firstImage = $learningImages[0]; @endphp
                                                        @php $imagePath = str_replace(['\\', '/'], '/', $firstImage['content'] ?? ''); @endphp
                                                        <img src="{{ asset('storage/' . ltrim($imagePath, '/')) }}"
                                                             alt="{{ $firstImage['alt'] ?? 'Gambar' }}"
                                                             class="w-full h-40 object-cover rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm"
                                                             loading="lazy"
                                                             onerror="this.src='https://via.placeholder.com/400x200?text=Gambar+Tidak+Ditemukan';this.onerror=null;">
                                                        
                                                        @if(count($learningImages) > 1)
                                                            <button @click="expanded = !expanded" 
                                                                    class="absolute bottom-2 right-2 bg-black/50 hover:bg-black/70 text-white text-xs px-2 py-1 rounded-full backdrop-blur-sm flex items-center gap-1 transition-colors">
                                                                <span x-text="expanded ? 'Sembunyikan' : '+{{ count($learningImages)-1 }} lagi'"></span>
                                                                <svg class="w-3 h-3" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Additional images (collapsed) -->
                                                    <div x-show="expanded" 
                                                         x-collapse
                                                         class="space-y-3 mt-3">
                                                        @foreach(array_slice($learningImages, 1) as $image)
                                                            @php $imagePath = str_replace(['\\', '/'], '/', $image['content'] ?? ''); @endphp
                                                            <img src="{{ asset('storage/' . ltrim($imagePath, '/')) }}"
                                                                 alt="{{ $image['alt'] ?? 'Gambar' }}"
                                                                 class="w-full h-40 object-cover rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm"
                                                                 loading="lazy"
                                                                 onerror="this.src='https://via.placeholder.com/400x200?text=Gambar+Tidak+Ditemukan';this.onerror=null;">
                                                        @endforeach
                                                    </div>
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
                                            
                                            <!-- Related Project Info with Leader/Owner Profile -->
                                            @if($relatedProject)
                                                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800">
                                                    <!-- Label Project Terkait -->
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Project Terkait:</p>
                                                    
                                                    <!-- Nama Project - Ditampilkan Lebih Menonjol -->
                                                    <div class="mb-3">
                                                        <a href="{{ route('project.show', ['id' => $relatedProject->id]) }}" 
                                                        class="text-base font-semibold text-gray-800 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors line-clamp-2">
                                                            {{ $relatedProjectData['nama_project'] ?? $relatedProject->nama_project ?? 'Project' }}
                                                        </a>
                                                    </div>
                                                    
                                                    <!-- Leader/Owner Profile dengan Highlight -->
                                                    @if($displayName)
                                                    <div class="flex items-center space-x-2 {{ $isLeaderAvailable ? 'bg-orange-50 dark:bg-orange-900/20' : 'bg-blue-50 dark:bg-blue-900/20' }} p-2 rounded-lg">
                                                        <!-- Icon/Label -->
                                                        <span class="text-xs font-medium {{ $isLeaderAvailable ? 'text-orange-600 dark:text-orange-400' : 'text-blue-600 dark:text-blue-400' }}">
                                                            {{ $displayRole }}:
                                                        </span>
                                                        
                                                        <!-- Profile - Clickable ke Portfolio -->
                                                        <a href="{{ $displayId ? route('portfolio.show', ['user' => $displayId]) : '#' }}" 
                                                        class="flex items-center space-x-2 hover:opacity-80 transition-opacity flex-1">
                                                            <div class="w-6 h-6 rounded-full overflow-hidden bg-gray-200 flex-shrink-0 relative">
                                                                @if($displayPhoto)
                                                                    <img src="{{ asset('storage/' . ltrim($displayPhoto, '/')) }}" 
                                                                        alt="{{ $displayName }}"
                                                                        class="w-full h-full object-cover"
                                                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                                    <div class="absolute inset-0 hidden {{ $isLeaderAvailable ? 'bg-gradient-to-br from-orange-500 to-red-500' : 'bg-gradient-to-br from-blue-500 to-indigo-500' }} items-center justify-center text-white text-xs font-bold">
                                                                        {{ substr($displayName, 0, 1) }}
                                                                    </div>
                                                                @else
                                                                    <div class="w-full h-full {{ $isLeaderAvailable ? 'bg-gradient-to-br from-orange-500 to-red-500' : 'bg-gradient-to-br from-blue-500 to-indigo-500' }} flex items-center justify-center text-white text-xs font-bold">
                                                                        {{ substr($displayName, 0, 1) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $displayName }}</span>
                                                        </a>
                                                    </div>
                                                    @endif
                                                    
                                                    <!-- Arrow Icon dan Project Link -->
                                                    <div class="flex items-center space-x-2 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                        </svg>
                                                        <a href="{{ route('project.show', ['id' => $relatedProject->id]) }}" 
                                                        class="hover:text-indigo-600 transition-colors">
                                                            Lihat Detail Project
                                                        </a>
                                                    </div>
                                                    
                                                    <!-- Periode Project -->
                                                    @if(!empty($relatedProjectData['tanggal_mulai']))
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                                            {{ \Carbon\Carbon::parse($relatedProjectData['tanggal_mulai'])->format('M Y') }}
                                                            @if(!empty($relatedProjectData['tanggal_akhir']))
                                                                → {{ \Carbon\Carbon::parse($relatedProjectData['tanggal_akhir'])->format('M Y') }}
                                                            @else
                                                                → Sekarang
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @elseif($post->type === 'project')
                                            <!-- Project Title - Clickable ke Project -->
                                            <a href="{{ route('project.show', ['id' => $post->id]) }}" 
                                               class="block group-hover:text-indigo-700 transition-colors">
                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 line-clamp-2 leading-tight">
                                                    {{ $nama_project }}
                                                </h3>
                                            </a>

                                            <!-- Periode -->
                                            @if(!empty($projectData['tanggal_mulai']))
                                                <div class="text-sm text-gray-600 dark:text-gray-200 mt-2">
                                                    <p><span class="font-medium">Periode:</span>
                                                        {{ \Carbon\Carbon::parse($projectData['tanggal_mulai'])->format('M Y') }}
                                                        @if(!empty($projectData['tanggal_akhir']))
                                                            → {{ \Carbon\Carbon::parse($projectData['tanggal_akhir'])->format('M Y') }}
                                                        @else
                                                            → Sekarang
                                                        @endif
                                                    </p>
                                                </div>
                                            @endif

                                            <!-- YouTube Video - Collapsible -->
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
                                                            Lihat Project
                                                        </a>
                                                    @endif

                                                    @if($link_github)
                                                        <a href="{{ $link_github }}" target="_blank" rel="noopener noreferrer"
                                                           class="inline-flex hover:underline items-center text-orange-600 hover:text-orange-800 font-medium transition-colors">
                                                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
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
                                        @endif
                                    </div>

                                    <!-- Footer -->
                                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-900">
                                        <p class="text-xs text-gray-500 dark:text-gray-50">
                                            Diposting
                                            {{ $post->created_at?->format('d M Y H:i') ?? $post->tanggal?->format('d M Y') ?? '—' }}
                                            WIB
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Update timestamp -->
                <div class="text-center text-gray-500 dark:text-gray-50 text-sm mt-10">
                    Data terakhir diperbarui: {{ now()->format('d F Y H:i') }} WIB
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js and Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function createSparkline(canvasId, borderColor) {
            const ctx = document.getElementById(canvasId)?.getContext('2d');
            if (!ctx) return;
            
            // Generate random data for sparkline
            const generateRandomData = () => {
                return Array.from({ length: 7 }, () => Math.floor(Math.random() * 40));
            };
            
            const data = generateRandomData();
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Array(data.length).fill(''),
                    datasets: [{
                        data: data,
                        borderColor: borderColor,
                        backgroundColor: borderColor + '20',
                        tension: 0.4,
                        pointRadius: 0,
                        borderWidth: 2.5,
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
                    },
                    elements: { 
                        point: { radius: 0 } 
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            createSparkline('learningChart', '#8b5cf6');
            createSparkline('projectChart', '#f97316');
            createSparkline('sertifikatChart', '#f59e0b');
        });
    </script>
@endsection