@extends('Layout.Layout')
@section('title', 'Dashboard Admin')


@section('content')
    <style>
        .skeleton {
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200px 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 0.5rem;
        }

        .dark .skeleton {
            background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
            background-size: 200px 100%;
        }

        .skeleton-circle {
            border-radius: 50%;
        }

        .skeleton-text {
            height: 14px;
            margin-bottom: 8px;
        }

        .skeleton-title {
            height: 20px;
            width: 60%;
            margin-bottom: 12px;
        }

        .skeleton-image {
            width: 100%;
            height: 200px;
            border-radius: 12px;
        }

        .skeleton-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #e5e7eb;
        }

        .dark .skeleton-card {
            background: #1f2937;
            border-color: #374151;
        }

        .skeleton-pulse {
            animation: skeletonPulse 2s ease-in-out infinite;
        }

        @keyframes skeletonPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
    </style>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div id="header-skeleton" class="space-y-4 mb-10">
                <div class="skeleton h-8 w-1/3"></div>
                <div class="skeleton h-4 w-1/2"></div>
                <div class="skeleton h-4 w-1/4"></div>
            </div>
            <div class="hidden" id="header-wrapper"> 
                <!-- Header -->
                <div class="mb-10">
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
                        Dashboard Admin
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Selamat datang kembali, <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ Auth::user()->nama_mahasiswa ?? Auth::user()->name ?? 'Admin' }}</span>
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" data-translate="admin_dashboard_desc" data-translate-page="admin">
                        Kelola dashboard admin Anda di sini.
                    </p>
                </div>
            </div>
            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap gap-3 mb-8">
                <a href="{{ route('admin.users.ViewCreate') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition-all shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span data-translate="button_add_user" data-translate-page="admin">Tambah User</span>
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-gray-100 dark:bg-gray-800 px-5 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all shadow-md hover:shadow-lg border border-gray-200 dark:border-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 01-5.356-1.857M17 20H7m5-2v-2c0-.656-.126-1.284-.356-1.852M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.284.356-1.852m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span data-translate="button_user_list" data-translate-page="admin">Daftar User</span>
                </a>
                <a href="{{ route('admin.projects.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white hover:bg-green-700 transition-all shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2" />
                    </svg>
                    <span data-translate="button_all_projects" data-translate-page="admin">Semua Project</span>
                </a>
                <a href="{{ route('admin.sertifikat.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-white hover:bg-amber-600 transition-all shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <span data-translate="button_certificates" data-translate-page="admin">Sertifikat</span>
                </a>
                
                <!-- Global Email Toggle -->
                <button id="globalEmailToggle" type="button"
                    class="inline-flex items-center gap-2 rounded-xl bg-gray-100 dark:bg-gray-800 px-5 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all shadow-md hover:shadow-lg border border-gray-200 dark:border-gray-700 ml-auto">
                    <svg id="global-eye-show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="global-eye-hide" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                    <span>Toggle Email</span>
                </button>
            </div>

            <div id="statistics-skeleton">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                    @for($i = 0; $i < 4; $i++)
                        <div class="skeleton skeleton-card p-6">
                            <div class="flex justify-between mb-4">
                                <div class="skeleton h-4 w-24"></div>
                                <div class="skeleton h-6 w-6 rounded-full"></div>
                            </div>
                            <div class="skeleton h-8 w-20 mb-4"></div>
                            <div class="skeleton h-12 w-full"></div>
                        </div>
                    @endfor
                </div>
            </div>
            <div class="hidden" id="statistics-wrapper">
                <!-- Statistics Cards with Chart -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                    <!-- Total Users -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200" data-translate="total_users_title" data-translate-page="admin">
                                Total Pengguna
                            </h3>
                            <span class="text-blue-500">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 01-5.356-1.857M17 20H7m5-2v-2c0-.656-.126-1.284-.356-1.852M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.284.356-1.852m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">
                            {{ number_format($totalUsers) }}
                        </p>
                        <div class="mt-6 h-16">
                            <canvas id="chart-users"></canvas>
                        </div>
                    </div>

                    <!-- Total Mahasiswa -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200" data-translate="total_students_title" data-translate-page="admin">
                                Total Mahasiswa
                            </h3>
                            <span class="text-emerald-500">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-4xl font-bold text-emerald-600 dark:text-emerald-400">
                            {{ number_format($totalMahasiswa) }}
                        </p>
                        <div class="mt-6 h-16">
                            <canvas id="chart-students"></canvas>
                        </div>
                    </div>

                    <!-- Total Admin -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200" data-translate="total_admin_title" data-translate-page="admin">
                                Total Admin
                            </h3>
                            <span class="text-amber-500">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-4xl font-bold text-amber-600 dark:text-amber-400">
                            {{ number_format($totalAdmin) }}
                        </p>
                        <div class="mt-6 h-16">
                            <canvas id="chart-admin"></canvas>
                        </div>
                    </div>

                    <!-- Total Dosen -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200" data-translate="total_lecturers_title" data-translate-page="admin">
                                Total Dosen
                            </h3>
                            <span class="text-purple-500">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-4xl font-bold text-purple-600 dark:text-purple-400">
                            {{ number_format($totalDosen) }}
                        </p>
                        <div class="mt-6 h-16">
                            <canvas id="chart-lecturers"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div id="summary-skeleton">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                    @for($i = 0; $i < 4; $i++)
                        <div class="skeleton skeleton-card p-6">
                            <div class="skeleton h-4 w-32 mb-3"></div>
                            <div class="skeleton h-8 w-20 mb-3"></div>
                            <div class="skeleton h-4 w-40"></div>
                        </div>
                    @endfor
                </div>
            </div>

            <div id="summary-wrapper" class="hidden">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                    <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white">
                        <h3 class="text-sm font-semibold opacity-90" data-translate="highlight_title" data-translate-page="admin">Mahasiswa Terdaftar</h3>
                        <p class="mt-3 text-4xl font-bold">{{ number_format($totalMahasiswa) }}</p>
                        <div class="mt-4 flex items-center text-indigo-100 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Total mahasiswa aktif</span>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200" data-translate="total_projects_title" data-translate-page="admin">Total Project</h3>
                            <span class="text-green-500">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-4xl font-bold text-green-600 dark:text-green-400">{{ number_format($totalProjects) }}</p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200" data-translate="total_learning_corners_title" data-translate-page="admin">Learning Corner</h3>
                            <span class="text-blue-500">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalLearningCorners) }}</p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200" data-translate="total_certificates_title" data-translate-page="admin">Sertifikat</h3>
                            <span class="text-purple-500">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-4xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($totalSertifikats) }}</p>
                    </div>
                </div>
            </div>
            <div id="activity-skeleton" class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-pulse">

                <!-- LEFT: Activity Skeleton -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                    
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-5">
                        <div class="h-5 w-40 bg-gray-300 dark:bg-gray-700 rounded"></div>
                        <div class="h-4 w-20 bg-gray-300 dark:bg-gray-700 rounded"></div>
                    </div>

                    <!-- List -->
                    <div class="space-y-4">
                        @for ($i = 0; $i < 3; $i++)
                            <div class="flex gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                
                                <!-- Icon -->
                                <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-lg"></div>

                                <!-- Text -->
                                <div class="flex-1 space-y-2">
                                    <div class="h-4 w-3/4 bg-gray-300 dark:bg-gray-600 rounded"></div>
                                    <div class="h-3 w-1/2 bg-gray-200 dark:bg-gray-500 rounded"></div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <!-- Button -->
                    <div class="mt-5 h-10 bg-gray-300 dark:bg-gray-700 rounded-lg"></div>
                </div>


                <!-- RIGHT SIDE -->
                <div class="space-y-6">

                    <!-- Pending Skeleton -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                        
                        <!-- Header -->
                        <div class="flex justify-between items-center mb-5">
                            <div class="h-5 w-40 bg-gray-300 dark:bg-gray-700 rounded"></div>
                            <div class="h-4 w-20 bg-gray-300 dark:bg-gray-700 rounded"></div>
                        </div>

                        <!-- List -->
                        <div class="space-y-3">
                            @for ($i = 0; $i < 3; $i++)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    
                                    <!-- Avatar -->
                                    <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full"></div>

                                    <!-- Text -->
                                    <div class="flex-1 space-y-2">
                                        <div class="h-4 w-2/3 bg-gray-300 dark:bg-gray-600 rounded"></div>
                                        <div class="h-3 w-1/3 bg-gray-200 dark:bg-gray-500 rounded"></div>
                                    </div>

                                    <!-- Badge -->
                                    <div class="h-5 w-16 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                                </div>
                            @endfor
                        </div>
                    </div>


                    <!-- Rejected Skeleton -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                        
                        <!-- Header -->
                        <div class="flex justify-between items-center mb-5">
                            <div class="h-5 w-40 bg-gray-300 dark:bg-gray-700 rounded"></div>
                            <div class="h-4 w-20 bg-gray-300 dark:bg-gray-700 rounded"></div>
                        </div>

                        <!-- List -->
                        <div class="space-y-3">
                            @for ($i = 0; $i < 3; $i++)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    
                                    <!-- Avatar -->
                                    <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full"></div>

                                    <!-- Text -->
                                    <div class="flex-1 space-y-2">
                                        <div class="h-4 w-2/3 bg-gray-300 dark:bg-gray-600 rounded"></div>
                                        <div class="h-3 w-1/3 bg-gray-200 dark:bg-gray-500 rounded"></div>
                                    </div>

                                    <!-- Badge -->
                                    <div class="h-5 w-16 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                                </div>
                            @endfor
                        </div>
                    </div>

                </div>
            </div>
            <div class="hidden" id="activity-wrapper">
                <!-- Activity and User Sections -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Latest Activity -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200" data-translate="latest_activity_title" data-translate-page="admin">
                                Aktivitas Terbaru
                            </h2>
                            <a href="{{ route('admin.projects.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition">
                                <span data-translate="see_all" data-translate-page="admin">Lihat Semua</span> →
                            </a>
                        </div>
                        <div class="space-y-4">
                            @forelse($latestActivities as $activity)
                                <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition {{ $loop->index >= 3 ? 'hidden extra-activity' : '' }}">
                                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg">
                                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            @if($activity->isi_content['nama_project'] ?? false)
                                                {{ $activity->isi_content['nama_project'] }}
                                            @else
                                                <span data-translate="project_no_title" data-translate-page="admin">Proyek Tanpa Judul</span>
                                            @endif
                                        </p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                <span data-translate="posted_by" data-translate-page="admin">Oleh</span>
                                                @if($activity->mahasiswa->nama_mahasiswa ?? false)
                                                    {{ $activity->mahasiswa->nama_mahasiswa }}
                                                @else
                                                    <span data-translate="student_label" data-translate-page="admin">Mahasiswa</span>
                                                @endif
                                            </p>
                                            @if($activity->mahasiswa->email ?? false)
                                                <div class="relative group">
                                                    <button type="button" class="email-toggle-btn p-1 hover:bg-gray-200 dark:hover:bg-gray-600 rounded transition" title="Toggle email">
                                                        <svg class="email-eye-show w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        <svg class="email-eye-hide w-3.5 h-3.5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                        </svg>
                                                    </button>
                                                    <span class="email-display text-xs text-gray-400 ml-1" data-email="{{ $activity->mahasiswa->email }}">••••••••</span>
                                                </div>
                                            @endif
                                            <span class="text-xs text-gray-400">• {{ $activity->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400" data-translate="no_recent_activity" data-translate-page="admin">
                                        Tidak ada aktivitas terbaru
                                    </p>
                                </div>
                            @endforelse
                        </div>
                        @if($latestActivities->count() > 3)
                            <button id="toggleActivity" type="button"
                                class="mt-5 w-full inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 px-4 py-2.5 text-sm font-medium text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition border border-indigo-200 dark:border-indigo-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                                <span data-translate="show_more" data-translate-page="admin">Tampilkan Lebih Banyak</span>
                            </button>
                        @endif
                    </div>
                
                <!-- Pending & Rejected Students -->
                <div class="space-y-6">
                    <!-- Pending Students -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg">
                                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200" data-translate="pending_students_title" data-translate-page="admin">
                                    Mahasiswa Menunggu
                                </h2>
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition">
                                <span data-translate="see_all" data-translate-page="admin">Lihat Semua</span> →
                            </a>
                        </div>
                        <div class="space-y-3">
                            @forelse($pendingMahasiswa as $mahasiswa)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg {{ $loop->index >= 3 ? 'hidden extra-pending' : '' }}">
                                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-yellow-700 dark:text-yellow-300 font-medium text-sm">
                                            {{ strtoupper(substr($mahasiswa->nama_mahasiswa ?? 'M', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white truncate">{{ $mahasiswa->nama_mahasiswa }}</p>
                                        <div class="flex items-center gap-2">
                                            <div class="relative group flex items-center">
                                                <button type="button" class="email-toggle-btn p-0.5 hover:bg-gray-200 dark:hover:bg-gray-600 rounded transition" title="Toggle email">
                                                    <svg class="email-eye-show w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <svg class="email-eye-hide w-3.5 h-3.5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                    </svg>
                                                </button>
                                                <span class="email-display text-xs text-gray-500 dark:text-gray-400 ml-1 truncate" data-email="{{ $mahasiswa->email ?? $mahasiswa->username }}">••••••••</span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-xs px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 rounded-full flex-shrink-0">Menunggu</span>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg {{ $loop->index >= 3 ? 'hidden extra-pending' : '' }}">
                                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-yellow-700 dark:text-yellow-300 font-medium text-sm">
                                            {{ strtoupper(substr($mahasiswa->nama_mahasiswa ?? 'M', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white truncate">{{ $mahasiswa->nama_mahasiswa }}</p>
                                        <div class="flex items-center gap-2">
                                            <div class="relative group flex items-center">
                                                <button type="button" class="email-toggle-btn p-0.5 hover:bg-gray-200 dark:hover:bg-gray-600 rounded transition" title="Toggle email">
                                                    <svg class="email-eye-show w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <svg class="email-eye-hide w-3.5 h-3.5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                    </svg>
                                                </button>
                                                <span class="email-display text-xs text-gray-500 dark:text-gray-400 ml-1 truncate" data-email="{{ $mahasiswa->email ?? $mahasiswa->username }}">••••••••</span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-xs px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 rounded-full flex-shrink-0">Menunggu</span>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <p class="text-gray-500 dark:text-gray-400" data-translate="pending_students_empty" data-translate-page="admin">
                                        Tidak ada mahasiswa menunggu
                                    </p>
                                </div>
                            @endforelse
                        </div>
                        @if($pendingMahasiswa->count() > 3)
                            <button id="togglePending" type="button"
                                class="mt-5 w-full inline-flex items-center justify-center gap-2 rounded-lg bg-yellow-50 dark:bg-yellow-900/30 px-4 py-2.5 text-sm font-medium text-yellow-700 dark:text-yellow-300 hover:bg-yellow-100 dark:hover:bg-yellow-900/50 transition border border-yellow-200 dark:border-yellow-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                                <span data-translate="show_more" data-translate-page="admin">Tampilkan Lebih Banyak</span>
                            </button> <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                                <span data-translate="show_more" data-translate-page="admin">Tampilkan Lebih Banyak</span>
                            </button>
                        @endif
                    </div>

                    <!-- Rejected Students -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-red-100 dark:bg-red-900/50 rounded-lg">
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200" data-translate="rejected_students_title" data-translate-page="admin">
                                    Mahasiswa Ditolak
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-red-100 dark:bg-red-900/50 rounded-lg">
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200" data-translate="rejected_students_title" data-translate-page="admin">
                                    Mahasiswa Ditolak
                                </h2>
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition">
                                <span data-translate="see_all" data-translate-page="admin">Lihat Semua</span> →
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition">
                                <span data-translate="see_all" data-translate-page="admin">Lihat Semua</span> →
                            </a>
                        </div>
                        <div class="space-y-3">
                            @forelse($rejectedMahasiswa as $mahasiswa)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg {{ $loop->index >= 3 ? 'hidden extra-rejected' : '' }}">
                                    <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-red-700 dark:text-red-300 font-medium text-sm">
                                            {{ strtoupper(substr($mahasiswa->nama_mahasiswa ?? 'M', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white truncate">{{ $mahasiswa->nama_mahasiswa }}</p>
                                        <div class="flex items-center gap-2">
                                            <div class="relative group flex items-center">
                                                <button type="button" class="email-toggle-btn p-0.5 hover:bg-gray-200 dark:hover:bg-gray-600 rounded transition" title="Toggle email">
                                                    <svg class="email-eye-show w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <svg class="email-eye-hide w-3.5 h-3.5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                    </svg>
                                                </button>
                                                <span class="email-display text-xs text-gray-500 dark:text-gray-400 ml-1 truncate" data-email="{{ $mahasiswa->email ?? $mahasiswa->username }}">••••••••</span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-xs px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-full flex-shrink-0">Ditolak</span>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg {{ $loop->index >= 3 ? 'hidden extra-rejected' : '' }}">
                                    <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-red-700 dark:text-red-300 font-medium text-sm">
                                            {{ strtoupper(substr($mahasiswa->nama_mahasiswa ?? 'M', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white truncate">{{ $mahasiswa->nama_mahasiswa }}</p>
                                        <div class="flex items-center gap-2">
                                            <div class="relative group flex items-center">
                                                <button type="button" class="email-toggle-btn p-0.5 hover:bg-gray-200 dark:hover:bg-gray-600 rounded transition" title="Toggle email">
                                                    <svg class="email-eye-show w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <svg class="email-eye-hide w-3.5 h-3.5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                    </svg>
                                                </button>
                                                <span class="email-display text-xs text-gray-500 dark:text-gray-400 ml-1 truncate" data-email="{{ $mahasiswa->email ?? $mahasiswa->username }}">••••••••</span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-xs px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-full flex-shrink-0">Ditolak</span>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <p class="text-gray-500 dark:text-gray-400" data-translate="rejected_students_empty" data-translate-page="admin">
                                        Tidak ada mahasiswa ditolak
                                    </p>
                                </div>
                            @endforelse
                        </div>
                        @if($rejectedMahasiswa->count() > 3)
                            <button id="toggleRejected" type="button"
                                class="mt-5 w-full inline-flex items-center justify-center gap-2 rounded-lg bg-red-50 dark:bg-red-900/30 px-4 py-2.5 text-sm font-medium text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/50 transition border border-red-200 dark:border-red-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                                <span data-translate="show_more" data-translate-page="admin">Tampilkan Lebih Banyak</span>
                            </button>
                                class="mt-5 w-full inline-flex items-center justify-center gap-2 rounded-lg bg-red-50 dark:bg-red-900/30 px-4 py-2.5 text-sm font-medium text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/50 transition border border-red-200 dark:border-red-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                                <span data-translate="show_more" data-translate-page="admin">Tampilkan Lebih Banyak</span>
                            </button>
                        @endif
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
    <!-- Chart.js & Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Global email visibility state
            let allEmailsVisible = false;
            
            // Toggle individual email
            function toggleIndividualEmail(button) {
                const container = button.closest('.relative');
                const emailDisplay = container.querySelector('.email-display');
                const eyeShow = button.querySelector('.email-eye-show');
                const eyeHide = button.querySelector('.email-eye-hide');
                
                if (emailDisplay.textContent === '••••••••') {
                    emailDisplay.textContent = emailDisplay.dataset.email;
                    eyeShow.classList.add('hidden');
                    eyeHide.classList.remove('hidden');
                } else {
                    emailDisplay.textContent = '••••••••';
                    eyeShow.classList.remove('hidden');
                    eyeHide.classList.add('hidden');
                }
            }
            
            // Toggle all emails
            function toggleAllEmails(show) {
                document.querySelectorAll('.email-display').forEach(display => {
                    const container = display.closest('.relative');
                    const button = container.querySelector('.email-toggle-btn');
                    const eyeShow = button.querySelector('.email-eye-show');
                    const eyeHide = button.querySelector('.email-eye-hide');
                    
                    if (show) {
                        display.textContent = display.dataset.email;
                        eyeShow.classList.add('hidden');
                        eyeHide.classList.remove('hidden');
                    } else {
                        display.textContent = '••••••••';
                        eyeShow.classList.remove('hidden');
                        eyeHide.classList.add('hidden');
                    }
                });
                
                // Update global button icons
                const globalEyeShow = document.getElementById('global-eye-show');
                const globalEyeHide = document.getElementById('global-eye-hide');
                if (show) {
                    globalEyeShow.classList.add('hidden');
                    globalEyeHide.classList.remove('hidden');
                } else {
                    globalEyeShow.classList.remove('hidden');
                    globalEyeHide.classList.add('hidden');
                }
                allEmailsVisible = show;
            }
            
            // Attach event listeners to all email toggle buttons
            document.querySelectorAll('.email-toggle-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleIndividualEmail(this);
                });
            });
            
            // Global email toggle button
            const globalToggle = document.getElementById('globalEmailToggle');
            if (globalToggle) {
                globalToggle.addEventListener('click', function() {
                    toggleAllEmails(!allEmailsVisible);
                });
            }

            // Toggle Functions for sections
            const toggleActivity = document.getElementById('toggleActivity');
            const togglePending = document.getElementById('togglePending');
            const toggleRejected = document.getElementById('toggleRejected');

            const toggleSection = (button, selector) => {
                if (!button) return;

                const icon = button.querySelector('svg');

                button.addEventListener('click', () => {
                    const items = document.querySelectorAll(selector);
                    const isHidden = items.length && items[0].classList.contains('hidden');

                    items.forEach(item => item.classList.toggle('hidden', !isHidden));

                    // Rotate icon and change text
                    if (icon) {
                        icon.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
                        icon.style.transition = 'transform 0.3s ease';
                    }

                    const span = button.querySelector('span');
                    if (span) {
                        span.textContent = isHidden ? 'Sembunyikan' : 'Tampilkan Lebih Banyak';
                    }
                });
            };

            toggleSection(toggleActivity, '.extra-activity');
            toggleSection(togglePending, '.extra-pending');
            toggleSection(toggleRejected, '.extra-rejected');

            // Chart.js Sparklines
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? '#374151' : '#E5E7EB';

            function createSparkline(canvasId, borderColor) {
                const canvas = document.getElementById(canvasId);
                if (!canvas) return;

                const ctx = canvas.getContext('2d');
                const data = Array.from({ length: 10 }, () => Math.floor(Math.random() * 40) + 15);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: Array(data.length).fill(''),
                        datasets: [{
                            data: data,
                            borderColor: borderColor,
                            backgroundColor: borderColor + '15',
                            tension: 0.35,
                            borderWidth: 2.5,
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

            // Create all sparkline charts
            createSparkline('chart-users', '#3B82F6');
            createSparkline('chart-students', '#10B981');
            createSparkline('chart-admin', '#F59E0B');
            createSparkline('chart-lecturers', '#8B5CF6');
        });
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.admin_dashboard");
        });
    </script>
@endsection