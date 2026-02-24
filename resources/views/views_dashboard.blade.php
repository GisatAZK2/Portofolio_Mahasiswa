@extends('Layout.Layout')
@section('title', 'Dashboard')
@section('content')
<div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-10">

        <!-- Statistic Cards with Mini Charts -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">
            
            <!-- Total Mahasiswa -->
            <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-700">Total Mahasiswa</h3>
                    <span class="text-blue-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                </div>
                <p class="text-4xl font-extrabold text-blue-600">{{ $totalMahasiswa ?? 0 }}</p>
                <div class="mt-4 h-20">
                    <canvas id="mahasiswaChart"></canvas>
                </div>
            </div>

            <!-- Total Portfolio -->
            <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-700">Total Portfolio</h3>
                    <span class="text-green-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </span>
                </div>
                <p class="text-4xl font-extrabold text-green-600">{{ $totalPortofolio ?? 0 }}</p>
                <div class="mt-4 h-20">
                    <canvas id="portfolioChart"></canvas>
                </div>
            </div>

            <!-- Learning Corner -->
            <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-700">Learning Corner</h3>
                    <span class="text-purple-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </span>
                </div>
                <p class="text-4xl font-extrabold text-purple-600">{{ $totalLearning ?? 0 }}</p>
                <div class="mt-4 h-20">
                    <canvas id="learningChart"></canvas>
                </div>
            </div>

            <!-- Total Project -->
            <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-700">Total Project</h3>
                    <span class="text-orange-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="text-4xl font-extrabold text-orange-600">{{ $totalProject ?? 0 }}</p>
                <div class="mt-4 h-20">
                    <canvas id="projectChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Random Posts -->
        <div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Postingan Acak Terbaru</h2>
                @auth
                <a href="{{ route('search') ?? '#' }}"
                   class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center gap-1">
                    Lihat Semua →
                </a>
                @endauth
            </div>

            @if($randomPosts->isEmpty())
                <div class="text-center py-12 bg-white rounded-xl border border-gray-200 shadow-sm">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-4 text-gray-600">Belum ada postingan acak untuk ditampilkan saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($randomPosts as $post)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full">
                            <div class="p-5 lg:p-6 flex-1 flex flex-col">

                                <!-- User Info - FOTO PROFILE SUDAH DITANGANI DENGAN FALLBACK -->
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-gray-100 shadow-sm flex-shrink-0 relative">
                                        @if($post->mahasiswa?->photo_profile)
                                            <img
                                                src="{{ asset('storage/' . ltrim($post->mahasiswa->photo_profile, '/')) }}"
                                                alt="{{ $post->mahasiswa->nama_mahasiswa ?? 'Profile' }}"
                                                class="w-full h-full object-cover"
                                                loading="lazy"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                            >
                                            <div class="absolute inset-0 hidden bg-gradient-to-br from-indigo-500 to-purple-600 items-center justify-center text-white font-bold text-lg">
                                                {{ substr($post->mahasiswa->nama_mahasiswa ?? 'U', 0, 1) }}
                                            </div>
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                                {{ substr($post->mahasiswa->nama_mahasiswa ?? 'U', 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $post->mahasiswa->nama_mahasiswa ?? 'Pengguna' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $post->created_at?->diffForHumans() ?? $post->tanggal?->diffForHumans() ?? 'Baru saja' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Badge Tipe -->
                                @if($post->type === 'learning')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mb-3">
                                        Learning Corner
                                    </span>
                                @elseif($post->type === 'portofolio')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-3">
                                        Portfolio
                                    </span>
                                @elseif($post->type === 'project')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mb-3">
                                        Project
                                    </span>
                                @endif

                                <!-- Konten berdasarkan tipe -->
                                @if($post->type === 'portofolio')
                                    @php
                                        $content     = $post->isi_content ?? [];
                                        $judul       = $content['judul']       ?? '(Tanpa Judul)';
                                        $deskripsi   = $content['deskripsi']   ?? 'Tidak ada deskripsi';
                                        $link_project = $content['link_project'] ?? null;
                                        $link_github  = $content['link_github']  ?? null;
                                        $link_video   = $content['link_video']   ?? null;

                                        $embed_video = null;
                                        if ($link_video) {
                                            if (str_contains($link_video, 'watch?v=')) {
                                                $embed_video = str_replace('watch?v=', 'embed/', $link_video);
                                                $embed_video = explode('&', $embed_video)[0];
                                            } elseif (str_contains($link_video, 'youtu.be/')) {
                                                $embed_video = str_replace('youtu.be/', 'www.youtube.com/embed/', $link_video);
                                            }
                                        }
                                    @endphp

                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                        {{ $judul }}
                                    </h3>

                                    <p class="text-gray-700 mb-3 line-clamp-3 text-sm">
                                        {{ Str::limit($deskripsi, 120) }}
                                    </p>

                                    @if($embed_video)
                                        <div class="aspect-video rounded-lg overflow-hidden mb-4 border border-gray-200 shadow-sm">
                                            <iframe class="w-full h-full" src="{{ $embed_video }}" frameborder="0" allowfullscreen></iframe>
                                        </div>
                                    @endif

                                    <div class="flex flex-wrap gap-3 mt-auto pt-3">
                                        @if($link_project)
                                            <a href="{{ $link_project }}" target="_blank" rel="noopener noreferrer"
                                               class="text-green-600 hover:text-green-800 text-sm flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 002.22 2.883" />
                                                </svg>
                                                Project
                                            </a>
                                        @endif
                                        @if($link_github)
                                            <a href="{{ $link_github }}" target="_blank" rel="noopener noreferrer"
                                               class="text-gray-800 hover:text-gray-900 text-sm flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12c0 4.42 2.87 8.17 6.84 9.49.5.09.68-.22.68-.48v-1.69c-2.78.61-3.37-1.34-3.37-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1.01.07 1.54 1.03 1.54 1.03.89 1.52 2.34 1.08 2.91.83.09-.65.35-1.09.63-1.34-2.22-.25-4.56-1.11-4.56-4.94 0-1.09.39-1.98 1.03-2.68-.1-.25-.45-1.27.1-2.65 0 0 .84-.27 2.75 1.03A9.56 9.56 0 0112 6.8c.85.004 1.71.11 2.52.33 1.91-1.3 2.75-1.03 2.75-1.03.55 1.38.2 2.4.1 2.65.64.7 1.03 1.59 1.03 2.68 0 3.84-2.34 4.69-4.57 4.94.36.31.68.92.68 1.85v2.74c0 .26.18.57.69.49C19.13 20.17 22 16.42 22 12c0-5.52-4.48-10-10-10z"/>
                                                </svg>
                                                GitHub
                                            </a>
                                        @endif
                                        @if($link_video)
                                            <a href="{{ $link_video }}" target="_blank" rel="noopener noreferrer"
                                               class="text-red-600 hover:text-red-800 text-sm flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M21.5 6.5c-.3-.3-.8-.5-1.3-.5H4.8c-.5 0-1 .2-1.3.5-.3.3-.8.5-1.3.5v8.4c0 .5.2 1 .5 1.3.3.3.8.5 1.3.5h15.4c.5 0 1-.2 1.3-.5.3-.3.5-.8.5-1.3V7.8c0-.5-.2-1-.5-1.3zM10 16.5v-9l6 4.5-6 4.5z"/>
                                                </svg>
                                                Video
                                            </a>
                                        @endif
                                    </div>

                                @elseif($post->type === 'learning' && !empty($post->content) && is_array($post->content))
                                    @foreach($post->content as $item)
                                        @if($item['type'] === 'title')
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                                {{ $item['content'] ?? '(Tanpa Judul)' }}
                                            </h3>
                                        @elseif($item['type'] === 'text')
                                            <p class="text-gray-700 mb-3 line-clamp-3 text-sm">
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
                                            <a href="{{ $item['content'] }}" target="_blank" rel="noopener noreferrer"
                                               class="text-indigo-600 hover:text-indigo-800 text-sm block mb-3 underline line-clamp-1 break-all">
                                                {{ Str::limit($item['content'], 60) }}
                                            </a>
                                        @endif
                                    @endforeach

                                @elseif($post->type === 'project')
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                        {{ $post->nama_project ?? '(Tanpa Judul)' }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-3">
                                        {{ $post->tanggal_mulai ? \Carbon\Carbon::parse($post->tanggal_mulai)->format('M Y') : '—' }}
                                        @if($post->tanggal_akhir)
                                            → {{ \Carbon\Carbon::parse($post->tanggal_akhir)->format('M Y') }}
                                        @else
                                            → Sekarang
                                        @endif
                                    </p>
                                    @if($post->link_project)
                                        <a href="{{ $post->link_project }}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center gap-2 text-orange-600 hover:text-orange-800 font-medium text-sm mb-4">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                            Lihat Project
                                        </a>
                                    @else
                                        <p class="text-sm text-gray-500 italic mb-4">Tidak ada link project</p>
                                    @endif
                                @endif

                                <!-- Footer tanggal -->
                                <p class="text-xs text-gray-500 mt-auto pt-4 border-t border-gray-100">
                                    Diposting {{ $post->created_at?->format('d M Y H:i') ?? $post->tanggal?->format('d M Y') ?? '—' }} WIB
                                </p>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Update timestamp -->
            <div class="text-center text-gray-500 text-sm mt-10">
                Data terakhir diperbarui: {{ now()->format('d F Y H:i') }} WIB
            </div>
        </div>

    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        createSparkline('portfolioChart', '#10b981');
        createSparkline('learningChart', '#8b5cf6');
        createSparkline('projectChart', '#f97316');
    });
</script>
@endsection