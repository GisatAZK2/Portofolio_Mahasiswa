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
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Postingan Acak</h2>
            
            @if($randomPosts->isEmpty())
                <p class="text-gray-500 text-center py-10">Belum ada postingan acak saat ini.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($randomPosts as $post)
                        <div class="bg-white rounded-xl shadow-md p-6 space-y-4 border border-gray-100 hover:shadow-lg transition-shadow">
                            <!-- User Info -->
                            <div class="flex items-center space-x-3">
                                <img 
                                    src="{{ $post->mahasiswa->photo_profile ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->mahasiswa->nama_mahasiswa ?? 'User') . '&background=random' }}" 
                                    alt="Profile" 
                                    class="w-10 h-10 rounded-full object-cover border border-gray-200"
                                >
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $post->mahasiswa->nama_mahasiswa ?? 'User' }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $post->created_at?->format('d M Y H:i') ?? $post->tanggal ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="text-gray-700">
                                @if($post->type === 'portofolio')
                                    <p class="font-semibold text-green-600 mb-1">Portfolio</p>
                                    <p class="line-clamp-2">{{ $post->isi_content['judul'] ?? 'Portfolio Content' }}</p>
                                @elseif($post->type === 'learning')
                                    <p class="font-semibold text-purple-600 mb-1">Learning Corner</p>
                                    <p class="line-clamp-3 text-sm">{{ \Illuminate\Support\Str::limit($post->isi_learning_corner ?? '', 90) }}</p>
                                @elseif($post->type === 'project')
                                    <p class="font-semibold text-orange-600 mb-1">Project</p>
                                    <p class="line-clamp-2">{{ $post->nama_project ?? 'Project Tanpa Judul' }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Update Info -->
            <div class="text-center text-gray-500 text-sm mt-10">
                Data terakhir diperbarui: {{ now()->format('d F Y H:i') }} WIB
            </div>
        </div>

    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Mini Charts Script -->
<script>
    function createSparkline(canvasId, borderColor) {
        const ctx = document.getElementById(canvasId)?.getContext('2d');
        if (!ctx) return;

        // Data dummy - nanti ganti dengan data real dari controller kalau ada histori
        const data = [0, 10, 5, 25, 15, 40, 30]; // contoh tren naik

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