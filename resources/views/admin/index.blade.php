@extends('Layout.Layout')
@section('title', 'Dashboard Admin')
@section('content')

    <div class="min-h-screen dark:bg-gray-800 rounded-2xl py-4 sm:py-6 px-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-6 sm:space-y-10">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">Halo,
                            {{ Auth::user()->nama_mahasiswa ?? Auth::user()->name ?? 'Admin' }}
                        </h1>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400" data-translate="admin_dashboard_desc"
                            data-translate-page="admin">Kelola dashboard admin Anda di sini.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.users.ViewCreate') }}"
                            class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition"
                            data-translate="button_add_user" data-translate-page="admin">Tambah User</a>
                        <a href="{{ route('admin.users.index') }}"
                            class="inline-flex items-center justify-center rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                            data-translate="button_user_list" data-translate-page="admin">Daftar User</a>
                        <a href="{{ route('admin.projects.index') }}"
                            class="inline-flex items-center justify-center rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 transition"
                            data-translate="button_all_projects" data-translate-page="admin">Semua Project</a>
                        <a href="{{ route('admin.sertifikat.index') }}"
                            class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600 transition"
                            data-translate="button_certificates" data-translate-page="admin">Sertifikat</a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="total_users_title"
                                data-translate-page="admin">Total Pengguna</p>
                            <p class="mt-4 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalUsers) }}
                            </p>
                        </div>
                        <div class="w-12 h-12">
                            <canvas id="chart-users" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="total_students_title"
                                data-translate-page="admin">Total Mahasiswa</p>
                            <p class="mt-4 text-3xl font-semibold text-gray-900 dark:text-white">
                                {{ number_format($totalMahasiswa) }}
                            </p>
                        </div>
                        <div class="w-12 h-12">
                            <canvas id="chart-students" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="total_admin_title"
                                data-translate-page="admin">Total Admin</p>
                            <p class="mt-4 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalAdmin) }}
                            </p>
                        </div>
                        <div class="w-12 h-12">
                            <canvas id="chart-admin" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="total_lecturers_title"
                                data-translate-page="admin">Total Dosen</p>
                            <p class="mt-4 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($totalDosen) }}
                            </p>
                        </div>
                        <div class="w-12 h-12">
                            <canvas id="chart-lecturers" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid gap-4 xl:grid-cols-4">
                <div class="rounded-xl bg-indigo-600 p-6 text-white">
                    <h3 class="text-sm font-semibold" data-translate="highlight_title" data-translate-page="admin">Mahasiswa
                        Terdaftar</h3>
                    <p class="mt-2 text-2xl font-bold">{{ number_format($totalMahasiswa) }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="total_projects_title"
                        data-translate-page="admin">Total Project</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalProjects) }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="total_learning_corners_title"
                        data-translate-page="admin">Learning Corner</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($totalLearningCorners) }}
                    </p>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="total_certificates_title"
                        data-translate-page="admin">Sertifikat</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalSertifikats) }}
                    </p>
                </div>
            </div>

            <!-- Activity and User Sections -->
            <div class="grid gap-4 xl:grid-cols-2">
                <!-- Latest Activity -->
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6">
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white"
                                data-translate="latest_activity_title" data-translate-page="admin">Aktivitas Terbaru</h2>
                        </div>
                        <a href="{{ route('admin.projects.index') }}"
                            class="text-sm font-semibold text-indigo-600 hover:text-indigo-800" data-translate="see_all"
                            data-translate-page="admin">Lihat Semua</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($latestActivities as $activity)
                            <div
                                class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-3 {{ $loop->index >= 3 ? 'hidden extra-activity' : '' }}">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    @if($activity->isi_content['nama_project'] ?? false)
                                        {{ $activity->isi_content['nama_project'] }}
                                    @else
                                        <span data-translate="project_no_title" data-translate-page="admin">Proyek Tanpa
                                            Judul</span>
                                    @endif
                                </p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    <span data-translate="posted_by" data-translate-page="admin">Oleh</span>
                                    @if($activity->mahasiswa->nama_mahasiswa ?? false)
                                        {{ $activity->mahasiswa->nama_mahasiswa }}
                                    @else
                                        <span data-translate="student_label" data-translate-page="admin">Mahasiswa</span>
                                    @endif
                                </p>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                    {{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="no_recent_activity"
                                data-translate-page="admin">Tidak ada aktivitas terbaru</p>
                        @endforelse
                    </div>
                    @if($latestActivities->count() > 3)
                        <button id="toggleActivity" type="button"
                            class="mt-4 inline-flex items-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition"
                            data-translate="show_more" data-translate-page="admin">Tampilkan Lebih Banyak</button>
                    @endif
                </div>

                <div class="space-y-4">
                    <!-- Pending Students -->
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white"
                                    data-translate="pending_students_title" data-translate-page="admin">Mahasiswa Menunggu
                                </h2>
                            </div>
                            <a href="{{ route('admin.users.index') }}"
                                class="text-sm font-semibold text-indigo-600 hover:text-indigo-800" data-translate="see_all"
                                data-translate-page="admin">Lihat Semua</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($pendingMahasiswa as $mahasiswa)
                                <div
                                    class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-3 {{ $loop->index >= 3 ? 'hidden extra-pending' : '' }}">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $mahasiswa->nama_mahasiswa }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $mahasiswa->email ?? $mahasiswa->username }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="pending_students_empty"
                                    data-translate-page="admin">Tidak ada mahasiswa menunggu</p>
                            @endforelse
                        </div>
                        @if($pendingMahasiswa->count() > 3)
                            <button id="togglePending" type="button"
                                class="mt-4 inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition"
                                data-translate="show_more" data-translate-page="admin">Tampilkan Lebih Banyak</button>
                        @endif
                    </div>

                    <!-- Rejected Students -->
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white"
                                    data-translate="rejected_students_title" data-translate-page="admin">Mahasiswa Ditolak
                                </h2>
                            </div>
                            <a href="{{ route('admin.users.index') }}"
                                class="text-sm font-semibold text-indigo-600 hover:text-indigo-800" data-translate="see_all"
                                data-translate-page="admin">Lihat Semua</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($rejectedMahasiswa as $mahasiswa)
                                <div
                                    class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-3 {{ $loop->index >= 3 ? 'hidden extra-rejected' : '' }}">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $mahasiswa->nama_mahasiswa }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $mahasiswa->email ?? $mahasiswa->username }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="rejected_students_empty"
                                    data-translate-page="admin">Tidak ada mahasiswa ditolak</p>
                            @endforelse
                        </div>
                        @if($rejectedMahasiswa->count() > 3)
                            <button id="toggleRejected" type="button"
                                class="mt-4 inline-flex items-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-700 transition"
                                data-translate="show_more" data-translate-page="admin">Tampilkan Lebih Banyak</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle Activity
            const toggleActivity = document.getElementById('toggleActivity');
            const togglePending = document.getElementById('togglePending');
            const toggleRejected = document.getElementById('toggleRejected');

            const toggleSection = (button, selector) => {
                if (!button) return;
                button.addEventListener('click', () => {
                    const items = document.querySelectorAll(selector);
                    const isHidden = items.length && items[0].classList.contains('hidden');
                    items.forEach(item => item.classList.toggle('hidden', !isHidden));
                });
            };

            toggleSection(toggleActivity, '.extra-activity');
            toggleSection(togglePending, '.extra-pending');
            toggleSection(toggleRejected, '.extra-rejected');

            // Chart.js Sparklines
            const chartOptions = {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                },
                scales: {
                    x: { display: false },
                    y: { display: false }
                }
            };

            const sparklineData = [12, 19, 8, 14, 22, 18, 25];
            
            // Total Users Chart
            new Chart(document.getElementById('chart-users'), {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        data: sparklineData,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: chartOptions
            });

            // Total Students Chart
            new Chart(document.getElementById('chart-students'), {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        data: [10, 15, 12, 18, 20, 16, 22],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: chartOptions
            });

            // Total Admin Chart
            new Chart(document.getElementById('chart-admin'), {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        data: [3, 4, 3, 5, 4, 6, 5],
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: chartOptions
            });

            // Total Lecturers Chart
            new Chart(document.getElementById('chart-lecturers'), {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        data: [8, 9, 8, 10, 11, 9, 12],
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: chartOptions
            });
        });
    </script>

@endsection