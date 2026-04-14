@extends('Layout.Layout')
@section('title', 'Dashboard')
@section('content')
<div class="min-h-screen dark:bg-gray-800 rounded-2xl py-4 sm:py-6 px-3 sm:px-6 lg:px-8" data-dashboard-type="me">
    <div class="max-w-7xl mx-auto space-y-6 sm:space-y-10">
        <!-- Statistic Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
            <!-- Card Learning Corner -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200">Semua Learning Corner</h3>
                    <span class="text-purple-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </span>
                </div>
                <p class="text-3xl font-bold text-purple-600 dark:text-purple-300">{{ $totalLearning ?? 0 }}</p>
                <div class="mt-4 h-20">
                    <canvas id="learningChart"></canvas>
                </div>
            </div>

            <!-- Card Total Project -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow cursor-pointer"
                onclick="window.location.href = '{{ auth()->check() ? route('project.index') : route('project.project_user') }}';">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200">Total Semua Project</h3>
                    <span class="text-orange-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="text-3xl font-bold text-orange-600">{{ $totalProject ?? 0 }}</p>
                <div class="mt-4 h-20">
                    <canvas id="projectChart"></canvas>
                </div>
            </div>

            <!-- Card Total Sertifikat -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow cursor-pointer"
                onclick="window.location.href = '{{ auth()->check() ? route('sertifikat.index') : route('sertifikat-mahasiswa') }}';">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200">Total Semua Sertifikat</h3>
                    <span class="text-amber-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </span>
                </div>
                <p class="text-3xl font-bold text-amber-600">{{ $totalSertifikat ?? 0 }}</p>
                <div class="mt-4 h-20">
                    <canvas id="sertifikatChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Postingan Terbaru -->
        <div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Postingan Terbaru</h2>
            </div>

            <!-- Learning Corner -->
            <div class="mb-10">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-5 bg-purple-600 rounded-full"></div>
                    <h3 class="text-lg font-semibold text-purple-700 dark:text-purple-300">Learning Corner</h3>
                </div>

                @if($learningCorners->isEmpty())
                    <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada postingan Learning Corner</p>
                    </div>
                @else
                    <div data-pagination-group="learning_corner">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($learningCorners as $post)
                                @include('components.card_postingan', ['post' => $post])
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $learningCorners->render('vendor.pagination.custom_ajax', ['groupName' => 'learning_corner']) }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Project -->
            <div class="mb-10">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-5 bg-orange-600 rounded-full"></div>
                    <h3 class="text-lg font-semibold text-orange-600 dark:text-orange-300">Project</h3>
                </div>

                @if($projects->isEmpty())
                    <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada postingan Project</p>
                    </div>
                @else
                    <div data-pagination-group="project">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($projects as $post)
                                @include('components.card_postingan', ['post' => $post])
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $projects->render('vendor.pagination.custom_ajax', ['groupName' => 'project']) }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sertifikat -->
            <div class="mb-10">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-5 bg-green-600 rounded-full"></div>
                    <h3 class="text-lg font-semibold text-green-700 dark:text-green-300">Sertifikat</h3>
                </div>

                @if($projectUsers->isEmpty())
                    <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada sertifikat</p>
                    </div>
                @else
                    <div data-pagination-group="sertifikat">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($projectUsers as $post)
                                @include('components.card_postingan', ['post' => $post])
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $projectUsers->render('vendor.pagination.custom_ajax', ['groupName' => 'sertifikat']) }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Update timestamp -->
            <div class="text-center text-gray-500 dark:text-gray-400 text-sm mt-10 pt-6 border-t border-gray-200 dark:border-gray-700">
                Terakhir diperbarui {{ now()->format('d F Y H:i') }} WIB
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    let charts = {};

    function createSparkline(canvasId, borderColor) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        // Destroy existing chart
        if (charts[canvasId]) {
            charts[canvasId].destroy();
        }

        const ctx = canvas.getContext('2d');
        
        const generateRandomData = () => {
            return Array.from({ length: 7 }, () => Math.floor(Math.random() * 40) + 10);
        };

        const data = generateRandomData();

        charts[canvasId] = new Chart(ctx, {
            type: 'line',
            data: {
                labels: Array(data.length).fill(''),
                datasets: [{
                    data: data,
                    borderColor: borderColor,
                    backgroundColor: borderColor + '20',
                    tension: 0.4,
                    pointRadius: 0,
                    borderWidth: 2,
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

<!-- Page Info -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        if (typeof showPageInfo === 'function') {
            showPageInfo("popup.dashboard");
        }
    });
</script>
@endsection