@extends('Layout.Layout')

@section('title', 'Dashboard Dosen')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div id="header-skeleton" class="mb-10 animate-pulse">
                <div class="h-8 w-64 bg-gray-300 dark:bg-gray-700 rounded mb-3"></div>
                <div class="h-4 w-80 bg-gray-200 dark:bg-gray-600 rounded"></div>
            </div>
            <div id="header-wrapper" class="hidden">
                <!-- Header -->
                <div class="mb-10">
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white" 
                        data-translate="dashboard_dosen" 
                        data-translate-page="dosen_dashboard">
                        Dashboard Dosen
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Selamat datang kembali, <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ auth()->user()->nama_mahasiswa ?? auth()->user()->username }}</span>
                    </p>
                </div>
            </div>

            <div id="stats-skeleton" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10 animate-pulse">
    
                <!-- Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl">
                    <div class="h-4 w-40 bg-gray-300 dark:bg-gray-700 rounded mb-4"></div>
                    <div class="h-10 w-20 bg-gray-400 dark:bg-gray-600 rounded mb-6"></div>
                    <div class="h-20 bg-gray-200 dark:bg-gray-700 rounded"></div>
                </div>

                <!-- Copy 2x -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl">
                    <div class="h-4 w-40 bg-gray-300 dark:bg-gray-700 rounded mb-4"></div>
                    <div class="h-10 w-20 bg-gray-400 dark:bg-gray-600 rounded mb-6"></div>
                    <div class="h-20 bg-gray-200 dark:bg-gray-700 rounded"></div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl">
                    <div class="h-4 w-40 bg-gray-300 dark:bg-gray-700 rounded mb-4"></div>
                    <div class="h-10 w-20 bg-gray-400 dark:bg-gray-600 rounded mb-6"></div>
                    <div class="h-20 bg-gray-200 dark:bg-gray-700 rounded"></div>
                </div>
            </div>
            <div id="stats-wrapper" class="hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

                    <!-- Card 1: Mahasiswa Bimbingan -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200" 
                                data-translate="ttl_mhs_bbg" 
                                data-translate-page="dosen_dashboard">
                                Mahasiswa Bimbingan
                            </h3>
                            <span class="text-blue-500">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">
                            {{ App\Models\User::where('role', 'mahasiswa')
                                ->where('id_jurusan', auth()->user()->id_jurusan)
                                ->where('id_angkatan', auth()->user()->id_angkatan)
                                ->where('id_keahlian', auth()->user()->id_keahlian)
                                ->count() }}
                        </p>
                        <div class="mt-6 h-20">
                            <canvas id="bimbinganChart"></canvas>
                        </div>
                    </div>
                

                    <!-- Card 2: Menunggu Persetujuan -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200" 
                                data-translate="approval_pending" 
                                data-translate-page="dosen_dashboard">
                                Menunggu Persetujuan
                            </h3>
                            <span class="text-yellow-500">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-4xl font-bold text-yellow-600 dark:text-yellow-400">
                            {{ App\Models\User::where('role', 'mahasiswa')
                                ->where('id_jurusan', auth()->user()->id_jurusan)
                                ->where('id_angkatan', auth()->user()->id_angkatan)
                                ->where('id_keahlian', auth()->user()->id_keahlian)
                                ->where('status_pengajuan', 'Sedang Di Ajukan')
                                ->count() }}
                        </p>
                        <div class="mt-6 h-20">
                            <canvas id="pendingChart"></canvas>
                        </div>
                    </div>

                    <!-- Card 3: Total Projects -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200" 
                                data-translate="ttl_pjt" 
                                data-translate-page="dosen_dashboard">
                                Total Projects
                            </h3>
                            <span class="text-emerald-500">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-4xl font-bold text-emerald-600 dark:text-emerald-400">
                            {{ App\Models\Project::whereHas('mahasiswa', function ($q) {
                                $q->where('id_jurusan', auth()->user()->id_jurusan)
                                ->where('id_angkatan', auth()->user()->id_angkatan)
                                ->where('id_keahlian', auth()->user()->id_keahlian);
                            })->count() }}
                        </p>
                        <div class="mt-6 h-20">
                            <canvas id="projectChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Cepat & Informasi -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Menu Cepat -->
                <div id="menu-skeleton" class="lg:col-span-7 bg-white dark:bg-gray-800 p-6 rounded-xl animate-pulse">
                    <div class="h-5 w-40 bg-gray-300 dark:bg-gray-700 rounded mb-6"></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-5 bg-gray-200 dark:bg-gray-700 rounded-xl h-24"></div>
                        <div class="p-5 bg-gray-200 dark:bg-gray-700 rounded-xl h-24"></div>
                        <div class="p-5 bg-gray-200 dark:bg-gray-700 rounded-xl h-24 md:col-span-2"></div>
                    </div>
                </div>
                <div id="menu-wrapper" class="hidden lg:col-span-7">
                    <!-- Menu Cepat -->
                    <div class="lg:col-span-7 bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                        <h2 class="text-xl font-semibold mb-5 text-gray-800 dark:text-gray-200" 
                            data-translate="quick_mn" 
                            data-translate-page="dosen_dashboard">Menu Cepat</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="{{ route('dosen.users.index') }}" 
                            class="p-5 bg-blue-50 dark:bg-gray-700 hover:bg-blue-100 dark:hover:bg-gray-600 rounded-xl transition-all flex items-center gap-4">
                                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 01-5.356-1.857M17 20H7m5-2v-2c0-.656-.126-1.284-.356-1.852M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.284.356-1.852m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white" data-translate="kll_mhs" data-translate-page="dosen_dashboard">Kelola Mahasiswa</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400" data-translate="kll_desc_mhs" data-translate-page="dosen_dashboard">Setujui atau tolak pengajuan mahasiswa</p>
                                </div>
                            </a>

                            <a href="{{ route('dosen.projects.index') }}" 
                            class="p-5 bg-green-50 dark:bg-gray-700 hover:bg-green-100 dark:hover:bg-gray-600 rounded-xl transition-all flex items-center gap-4">
                                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white" data-translate="kll_pjt" data-translate-page="dosen_dashboard">Kelola Projects</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400" data-translate="kll_desc_pjt" data-translate-page="dosen_dashboard">Lihat dan validasi project mahasiswa</p>
                                </div>
                            </a>

                            <a href="{{ route('dosen.sertifikat.index') }}" 
                            class="p-5 bg-purple-50 dark:bg-gray-700 hover:bg-purple-100 dark:hover:bg-gray-600 rounded-xl transition-all flex items-center gap-4 md:col-span-2">
                                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white" data-translate="kll_srtfkt" data-translate-page="dosen_dashboard">Kelola Sertifikat</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400" data-translate="kll_desc_srtfkt" data-translate-page="dosen_dashboard">Lihat dan validasi sertifikat mahasiswa</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div id="info-skeleton" class="lg:col-span-5 bg-white dark:bg-gray-800 p-6 rounded-xl animate-pulse">
                    <div class="h-5 w-40 bg-gray-300 dark:bg-gray-700 rounded mb-6"></div>

                    <div class="space-y-4">
                        <div class="h-16 bg-gray-200 dark:bg-gray-700 rounded"></div>
                        <div class="h-16 bg-gray-200 dark:bg-gray-700 rounded"></div>
                        <div class="h-16 bg-gray-200 dark:bg-gray-700 rounded"></div>
                    </div>
                </div>
                <div class="hidden lg:col-span-5" id="info-wrapper">
                    <!-- Informasi -->
                    <div class=" bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                        <h2 class="text-xl font-semibold mb-5 text-gray-800 dark:text-gray-200" 
                            data-translate="info" 
                            data-translate-page="dosen_dashboard">Informasi Anda</h2>
                        
                        <div class="space-y-5">
                            <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <svg class="w-6 h-6 text-gray-400 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 01-2-2H7a2 2 0 01-2 2v16m14 0h-4m-6 0H5" />
                                </svg>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" data-translate="info_jrs" data-translate-page="dosen_dashboard">Jurusan</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ auth()->user()->jurusan->nama_jurusan ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <svg class="w-6 h-6 text-gray-400 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" data-translate="info_agkt" data-translate-page="dosen_dashboard">Angkatan</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ auth()->user()->angkatan->nama_angkatan ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <svg class="w-6 h-6 text-gray-400 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2" />
                                </svg>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" data-translate="info_khl" data-translate-page="dosen_dashboard">Keahlian</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ auth()->user()->keahlian->nama_keahlian ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <!-- Load Script -->
    <script>
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.querySelectorAll('[id$="-skeleton"]').forEach(skeleton => {
                    skeleton.classList.add('hidden');

                    const wrapperId = skeleton.id.replace('-skeleton', '-wrapper');
                    const wrapper = document.getElementById(wrapperId);

                    if (wrapper) {
                        wrapper.classList.remove('hidden');
                    }
                });

                sortPostinganByGame?.();
            }, 800);
        });
    </script>
    <!-- Chart.js - Hanya Grafik Random TANPA LABEL -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? '#374151' : '#E5E7EB';

            function createSparkline(canvasId, borderColor) {
                const canvas = document.getElementById(canvasId);
                if (!canvas) return;

                const ctx = canvas.getContext('2d');

                // Generate random data
                const data = Array.from({ length: 8 }, () => Math.floor(Math.random() * 45) + 10);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: Array(data.length).fill(''),   // Tidak ada label
                        datasets: [{
                            data: data,
                            borderColor: borderColor,
                            backgroundColor: borderColor + '15',  // Transparan ringan
                            tension: 0.35,
                            borderWidth: 3,
                            pointRadius: 0,
                            pointHoverRadius: 0,
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
                            line: { borderJoinStyle: 'round' }
                        }
                    }
                });
            }

            // Buat ketiga chart
            createSparkline('bimbinganChart', '#3B82F6');
            createSparkline('pendingChart',   '#EAB308');
            createSparkline('projectChart',   '#10B981');
        });
    </script>
@endsection