@extends('Layout.Layout')
@section('title', 'Dashboard')
@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-700 py-6 px-4 rounded-2xl sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-10">
            <!-- Statistic Cards -->
            @auth
            @if (auth()->user()->role === 'dosen')
            
            <div class="grid grid-cols-1 sm:grid-cols-4 sm:gap-2 lg:grid-cols-4 lg:gap-6">
            
                <!-- Mahasiswa  -->
                <div class="bg-white  rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-600 dark:bg-gray-900 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200">Total Mahasiswa</h3>
                        <span class="text-blue-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5z"/>
                                <path d="M12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="text-4xl font-extrabold text-blue-600">{{ $totalMahasiswa ?? 0 }}</p>
                    <div class="mt-4 h-20">
                        <canvas id="mahasiswaChart"></canvas>
                    </div>
                    <a href="{{route('admin.mahasiswa')}}">
                    <div class="mt-5 text-center bg-blue-600 rounded-lg text-white w-full p-3 hover:cursor-pointer">
                        Pantau
                    </div>
                    </a>
                </div>
                <!-- Learning Chart -->
                <div class="bg-white  rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-600 dark:bg-gray-900 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200">Learning Corner</h3>
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
                    <a href="{{route('admin.learning-corner')}}">
                    <div class="mt-5 text-center bg-purple-600 rounded-lg text-white w-full p-3 hover:cursor-pointer">
                        Pantau
                    </div>
                    </a>
                </div>
            
            <!-- Total Project -->
            <div class="bg-white rounded-xl shadow-md p-5 border overflow-hidden border-gray-100 dark:border-gray-600 dark:bg-gray-900 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold dark:text-gray-200 text-gray-700">Total Project</h3>
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
                <a href="{{route('admin.project')}}">
                <div class="mt-5 text-center bg-orange-600 rounded-lg text-white w-full p-3 hover:cursor-pointer">
                Pantau
                </div>
                </a>
            </div>

            <!-- Total Sertifikat -->
            <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-600 dark:bg-gray-900 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200">Total Sertifikat</h3>
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
                <a href='{{route('admin.sertifikat')}}'>
                <div class="mt-5 text-center bg-amber-600 rounded-lg text-white w-full p-3 hover:cursor-pointer">
                Pantau
                </div>
                </a>
            </div>
            </div>
            @else
            
            <div class="grid grid-cols-1 sm:grid-cols-3 sm:gap-2 lg:grid-cols-3 lg:gap-6">

                <!-- Learning Corner -->
                <div class="bg-white  rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-600 dark:bg-gray-900 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200">Learning Corner</h3>
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

                <!-- Total Project -->
                <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-600 dark:bg-gray-900 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-semibold dark:text-gray-200 text-gray-700">Total Project Dikerjakan</h3>
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

                <!-- Total Sertifikat -->
                <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-600 dark:bg-gray-900 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200">Total Sertifikat Didapat</h3>
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
            @endif
            @endauth

            <!-- Random Posts -->
            <div>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Perihal Terbaru</h2>
                </div>

                @if($randomPosts->isEmpty())
                    <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-4 text-gray-600 dark:text-gray-200">Belum ada postingan acak untuk ditampilkan saat ini.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($randomPosts as $post)
                            <!-- Card wrapper -->
                            <div
                                class="min-h-[320px] flex flex-col h-full rounded-xl overflow-hidden border border-gray-100 dark:border-gray-900 dark:bg-gray-900 shadow-md hover:shadow-xl transition-all duration-300 {{ $post->type !== 'sertifikat' ? 'group-hover:border-indigo-300 group-hover:ring-1 group-hover:ring-indigo-200' : '' }}">
                                @php
                                    $cardHref = '#'; // default
                                    $isExternal = false;
                                    if ($post->type === 'project' && $post->link_project) {
                                        $cardHref = $post->link_project;
                                        $isExternal = true;
                                    } elseif ($post->mahasiswa) {
                                        $cardHref = route('portfolio.show', $post->mahasiswa);
                                        $isExternal = false;
                                    }

                                    $projectData = $post->isi_content;

                                    if (is_string($projectData)) {
                                        $projectData = json_decode($projectData, true) ?? [];
                                    }

                                    if (!is_array($projectData)) {
                                        $projectData = [];
                                    }

                                    $nama_project = $projectData['nama_project'] ?? '(Nama Project Tidak Tersedia)';
                                    $link_project = $projectData['link_project'] ?? '';
                                    $link_github = $projectData['link_github'] ?? '';
                                    $link_video = $projectData['link_video'] ?? '';

                                    $youtube_id = '';
                                    if ($link_video) {
                                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $link_video, $matches)) {
                                            $youtube_id = $matches[1];
                                        }
                                    }
                                @endphp

                                @if($cardHref !== '#')
                                    <a href="{{ $cardHref }}" {{ $isExternal ? 'target="_blank" rel="noopener noreferrer"' : '' }}>
                                @endif

                                    <!-- Card body -->
                                    <div class="flex flex-col h-full dark:border-gray-900 bg-white dark:bg-gray-900 p-5 lg:p-6">
                                        <!-- User Info -->
                                        <div class="flex items-center space-x-3 mb-4">
                                            <div
                                                class="w-10 h-10 rounded-full overflow-hidden border-2 border-gray-100 dark:border-gray-900 shadow-sm flex-shrink-0 relative transition-transform {{ $post->type !== 'sertifikat' ? 'group-hover:scale-105' : '' }}">
                                                @if($post->mahasiswa?->photo_profile)
                                                    <img src="{{ asset('storage/' . ltrim($post->mahasiswa->photo_profile, '/')) }}"
                                                        alt="{{ $post->mahasiswa->nama_mahasiswa ?? 'Profile' }}"
                                                        class="w-full h-full object-cover" loading="lazy"
                                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div
                                                        class="absolute inset-0 hidden bg-gradient-to-br from-indigo-500 to-purple-600 items-center justify-center text-white font-bold text-lg">
                                                        {{ substr($post->mahasiswa->nama_mahasiswa ?? 'U', 0, 1) }}
                                                    </div>
                                                @else
                                                    <div
                                                        class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                                        {{ substr($post->mahasiswa->nama_mahasiswa ?? 'U', 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <p
                                                    class="font-semibold text-gray-900 dark:text-gray-300 {{ $post->type !== 'sertifikat' ? 'group-hover:text-indigo-700' : '' }} transition-colors">
                                                    {{ $post->mahasiswa->nama_mahasiswa ?? 'Pengguna' }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-100">
                                                    {{ $post->created_at?->diffForHumans() ?? $post->tanggal?->diffForHumans() ?? 'Baru saja' }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Badge -->
                                        @if($post->type === 'learning')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mb-3">Learning
                                                Corner</span>
                                        @elseif($post->type === 'project')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mb-3">Project</span>
                                        @elseif($post->type === 'sertifikat')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 mb-3">Sertifikat</span>
                                        @endif

                                        <!-- Konten utama -->
                                        <div class="flex-1 mt-3">
                                            @if($post->type === 'sertifikat')
                                                <div class="flex flex-col space-y-3">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 line-clamp-2">
                                                        {{ $post->nama_sertifikat ?? '(Tanpa Judul Sertifikat)' }}
                                                    </h3>
                                                    <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                                        </svg>
                                                        <span class="font-medium">Penerbit:</span>
                                                        <span class="ml-2">{{ $post->lembaga_penerbit ?? 'Tidak diketahui' }}</span>
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        <span class="font-medium">Terbit:</span>
                                                        <span
                                                            class="ml-2">{{ $post->tanggal_terbit ? \Carbon\Carbon::parse($post->tanggal_terbit)->format('d M Y') : '—' }}</span>
                                                    </div>
                                                    @if($post->link_sertifikat)
                                                        <a href="{{ asset('storage/' . $post->link_sertifikat) }}" target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-800 font-medium text-sm bg-amber-50 hover:bg-amber-100 px-4 py-2 rounded-lg transition-colors self-start mt-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            Lihat Sertifikat
                                                        </a>
                                                    @else
                                                        <p class="text-sm text-gray-500 italic">Tidak ada link sertifikat</p>
                                                    @endif
                                                </div>

                                            @elseif($post->type === 'learning' && !empty($post->content) && is_array($post->content))
                                                @foreach($post->content as $item)
                                                    @if($item['type'] === 'title')
                                                        <h3
                                                            class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2 {{ $post->type !== 'sertifikat' ? 'group-hover:text-indigo-700' : '' }} transition-colors">
                                                            {{ $item['content'] ?? '(Tanpa Judul)' }}
                                                        </h3>
                                                    @elseif($item['type'] === 'text')
                                                        <p class="text-gray-700 dark:text-gray-200 mb-3 line-clamp-3 text-sm">
                                                            {{ $item['content'] ?? '' }}
                                                        </p>
                                                    @elseif($item['type'] === 'image')
                                                        @php $imagePath = str_replace(['\\', '/'], '/', $item['content'] ?? ''); @endphp
                                                        <div class="mb-4">
                                                            <img src="{{ asset('storage/' . ltrim($imagePath, '/')) }}"
                                                                alt="{{ $item['alt'] ?? 'Gambar' }}"
                                                                class="w-full h-40 object-cover rounded-lg border border-gray-200 shadow-sm"
                                                                loading="lazy"
                                                                onerror="this.src='https://via.placeholder.com/400x200?text=Gambar+Tidak+Ditemukan';this.onerror=null;">
                                                        </div>
                                                    @elseif($item['type'] === 'link')
                                                        <a href="{{ $item['content'] }}" target="_blank"
                                                            class="text-indigo-600 hover:text-indigo-800 text-sm block mb-3 underline line-clamp-1 break-all">
                                                            {{ Str::limit($item['content'], 60) }}
                                                        </a>
                                                    @endif
                                                @endforeach

                                            @elseif($post->type === 'project')

                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 line-clamp-2 leading-tight">
                                                    {{ $nama_project }}
                                                </h3>

                                                <div class="text-sm text-gray-600 space-y-1 mt-2">
                                                    @if(!empty($projectData['tanggal_mulai']))
                                                        <p><span class="font-medium">Periode:</span>
                                                            {{ \Carbon\Carbon::parse($projectData['tanggal_mulai'])->format('M Y') }}
                                                            @if(!empty($projectData['tanggal_akhir']))
                                                                → {{ \Carbon\Carbon::parse($projectData['tanggal_akhir'])->format('M Y') }}
                                                            @else
                                                                → Sekarang
                                                            @endif
                                                        </p>
                                                    @endif
                                                </div>

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

                                                @if($link_project || $link_github || $link_video)
                                                    <div class="flex flex-wrap gap-4 text-sm mt-4">
                                                        @if($link_project)
                                                            <a href="{{ $link_project }}" target="_blank" rel="noopener noreferrer"
                                                                class="inline-flex hover:underline items-center text-orange-600 hover:text-orange-800 font-medium transition-colors">
                                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
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
                                                                    <path
                                                                        d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
                                                                </svg>
                                                                GitHub
                                                            </a>
                                                        @endif

                                                        @if($link_video && !$youtube_id)
                                                            <a href="{{ $link_video }}" target="_blank" rel="noopener noreferrer"
                                                                class="inline-flex hover:underline items-center text-orange-600 hover:text-orange-800 font-medium transition-colors">
                                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
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
                                        <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-900">
                                            <p class="text-xs text-gray-500 dark:text-gray-50">
                                                Diposting
                                                {{ $post->created_at?->format('d M Y H:i') ?? $post->tanggal?->format('d M Y') ?? '—' }}
                                                WIB
                                            </p>
                                        </div>
                                    </div>

                                    @if($cardHref !== '#' && $post->mahasiswa && $post->type !== 'sertifikat')
                                        </a>
                                    @endif
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

    <script>
        function createSparkline(canvasId, borderColor) {
            const ctx = document.getElementById(canvasId)?.getContext('2d');
            if (!ctx) return;
            const data = [0, 8, 4, 20, 12, 35, 28];
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
                    plugins: { legend: { display: false }, tooltip: { enabled: false } },
                    scales: { x: { display: false }, y: { display: false } },
                    elements: { point: { radius: 0 } }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            createSparkline('mahasiswaChart', '#3b82f6');
            createSparkline('learningChart', '#8b5cf6');
            createSparkline('projectChart', '#f97316');
            createSparkline('sertifikatChart', '#f59e0b');
        });
    </script>
@endsection