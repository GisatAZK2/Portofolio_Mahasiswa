@extends('Layout.Layout')
@section('title', 'Dashboard')
@section('content')
    <div class="min-h-screen dark:bg-gray-800 rounded-2xl py-4 sm:py-6 px-3 sm:px-6 lg:px-8" data-dashboard-type="me">
        <div class="max-w-7xl mx-auto space-y-6 sm:space-y-10">
            <!-- Statistic Cards with Mini Charts -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200"
                            data-translate="semua_learning_corner" data-translate-page="dashboard">Semua Learning Corner
                        </h3>
                        <span class="text-purple-500">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </span>
                    </div>
                    <p class="text-4xl font-extrabold text-purple-600 dark:text-purple-300">{{ $totalLearning ?? 0 }}</p>
                    <div class="mt-4 h-20">
                        <canvas id="learningChart"></canvas>
                    </div>
                </div>

                <!-- Total Semua Project -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow cursor-pointer"
                    onclick="window.location.href = '{{ auth()->check() ? route('project.index') : route('project.project_user') }}';">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200" data-translate="total_project"
                            data-translate-page="dashboard">Total Semua Project</h3>
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

                <!-- Total Semua Sertifikat -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow cursor-pointer"
                    onclick="window.location.href = '{{ auth()->check() ? route('sertifikat.index') : route('sertifikat-mahasiswa') }}';">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200"
                            data-translate="total_sertifikat" data-translate-page="dashboard">Total Semua Sertifikat</h3>
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

            <!-- Postingan Terbaru: Dikelompokkan dan Pagination per Kelompok -->
            <div>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100" data-translate="perihal_terbaru"
                        data-translate-page="dashboard">Postingan Terbaru</h2>
                </div>

                {{-- LEARNING CORNER --}}
                <div class="mb-10">
                    <h3 class="text-lg font-bold text-purple-700 dark:text-purple-300 mb-3" data-translate="ttl_lrn" data-translate-page="dashboard">Learning Corner</h3>
                    @if($learningCorners->isEmpty())
                        <div
                            class="text-center py-8 bg-white rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 shadow-sm">
                            <p class="text-gray-600 dark:text-gray-200" data-translate="empty_lrn" data-translate-page="dashboard">Belum ada postingan Learning Corner</p>
                        </div>
                    @else
                        <div data-pagination-group="learning_corner">
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-3 sm:gap-6">
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

                {{-- PROJECT --}}
                <div class="mb-10">
                    <h3 class="text-lg font-bold text-orange-600 dark:text-orange-300 mb-3" data-translate="ttl_pjt" data-translate-page="dashboard">Project</h3>
                    @if($projects->isEmpty())
                        <div
                            class="text-center py-8 bg-white rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 shadow-sm">
                            <p class="text-gray-600 dark:text-gray-200" data-translate="empty_pjt" data-translate-page="dashboard">Belum ada postingan Project</p>
                        </div>
                    @else
                        <div data-pagination-group="project">
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-3 sm:gap-6">
                                @foreach($projects as $post)
                                    @include('components.card_postingan', ['post' => $post])
                                @endforeach
                            </div>
                            <div class="mt-4">
                                {{ $projects->render('vendor.pagination.custom_ajax', ['groupName' => 'project']) }}</div>
                        </div>
                    @endif
                </div>

                {{-- SERTIFIKAT --}}
                <div class="mb-10">
                    <h3 class="text-lg font-bold text-green-700 dark:text-green-300 mb-3" data-translate="ttl_stk" data-translate-page="dashboard" data-translate="ttl_stk" data-translate-page="dashboard">Sertifikat</h3>
                    @if($projectUsers->isEmpty())
                        <div
                            class="text-center py-8 bg-white rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 shadow-sm">
                            <p class="text-gray-600 dark:text-gray-200" data-translate="empty_stk" data-translate-page="dashboard">Belum ada sertifikat</p>
                        </div>
                    @else
                        <div data-pagination-group="sertifikat">
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-3 sm:gap-6">
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
                <div class="text-center text-gray-500 dark:text-gray-50 text-sm mt-10">
                    <span data-translate="terakhir_diperbarui" data-translate-page="dashboard">Terakhir diperbarui</span>
                    {{ now()->format('d F Y H:i') }} WIB
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function createSparkline(canvasId, borderColor) {
            const ctx = document.getElementById(canvasId)?.getContext('2d');
            if (!ctx) return;

            // Generate random data for sparkline
            const generateRandomData = () => {
                return Array.from({ length: 7 }, () => Math.floor(Math.random() * 40) + 10);
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

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("Kelola dashboard Anda di sini. Pantau aktivitas dan proyek terbaru.");
        });
    </script>
@endsection