@extends('Layout.Layout')
@section('title', 'Detail Angkatan')

@section('content')
    <style>
        @media (max-width: 1919px),
        (max-height: 1079px) {
            .responsive-compact-table {
                font-size: 0.75rem !important;
            }

            .responsive-compact-table th,
            .responsive-compact-table td {
                padding: 0.5rem 0.75rem !important;
            }
        }

        /* Style untuk tabel responsif dengan scroll horizontal */
        .table-responsive-wrapper {
            width: 100%;
            overflow-x: auto;
            overflow-y: visible;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
            position: relative;
            z-index: 1;
        }

        .table-responsive-wrapper::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive-wrapper::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .table-responsive-wrapper::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .table-responsive-wrapper::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark .table-responsive-wrapper::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark .table-responsive-wrapper::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        .dark .table-responsive-wrapper::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }

        /* Tabel dengan lebar setengah container */
        .half-width-table {
            min-width: 100%;
            width: max-content;
            table-layout: auto;
            position: relative;
            z-index: 1;
        }

        /* Container untuk membatasi lebar */
        .table-half-container {
            width: 100%;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        @media (min-width: 768px) {
            .table-half-container {
                width: 95%;
            }
        }

        @media (min-width: 1024px) {
            .table-half-container {
                width: 90%;
            }
        }
    </style>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
                            Detail Angkatan
                        </h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            {{ $angkatan->nama_angkatan }}
                        </p>
                    </div>
                    <a href="{{ route('admin.angkatan.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-gray-100 dark:bg-gray-800 px-5 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all shadow-md hover:shadow-lg border border-gray-200 dark:border-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span data-translate="back" data-translate-page="admin">Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Info Angkatan Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Angkatan</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $angkatan->nama_angkatan }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tahun Masuk</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ date('d/m/Y', strtotime($angkatan->tahun_masuk)) }}
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tahun Keluar</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        @if($angkatan->tahun_keluar)
                            {{ date('d/m/Y', strtotime($angkatan->tahun_keluar)) }}
                        @else
                            <span class="text-gray-400 dark:text-gray-500 text-lg">Belum ditentukan</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Statistik Mahasiswa -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Mahasiswa</p>
                            <p class="text-4xl font-bold mt-2">{{ $angkatan->mahasiswa_count }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m5-2v-2c0-.656-.126-1.284-.356-1.852M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.284.356-1.852m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Mahasiswa Aktif</p>
                            <p class="text-4xl font-bold mt-2">{{ $angkatan->mahasiswa->where('is_active', true)->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Mahasiswa Tidak Aktif</p>
                            <p class="text-4xl font-bold mt-2">{{ $angkatan->mahasiswa->where('is_active', false)->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Mahasiswa -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white" data-translate="list_mhs" data-translate-page="admin">
                        Daftar Mahasiswa
                    </h2>
                </div>

                <!-- Filter Section -->
                <div class="p-6 bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="relative">
                            <input type="text" id="search-mahasiswa" placeholder="Cari nama mahasiswa..."
                                class="w-full pl-10 pr-4 py-2.5 text-sm bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500">
                            <svg class="absolute left-3 top-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <select id="filter-jurusan"
                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Prodi</option>
                            @foreach($jurusans as $jurusan)
                                <option value="{{ $jurusan->id_jurusan }}">{{ $jurusan->nama_jurusan }}</option>
                            @endforeach
                        </select>

                        <select id="filter-status"
                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="tidak-aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 mt-4">
                        <button type="button" onclick="resetFiltersMahasiswa()"
                            class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                            Reset
                        </button>
                        <button type="button" onclick="applyFiltersMahasiswa()"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all shadow-sm">
                            Terapkan Filter
                        </button>
                    </div>
                </div>

                <!-- Table - Desktop View -->
                <div class="hidden md:block">
                    <div class="table-half-container">
                        <div class="table-responsive-wrapper">
                            <table id="mahasiswa-table-desktop" class="half-width-table divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 responsive-compact-table">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-12">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[180px]">Nama Mahasiswa</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[150px]">Prodi</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[220px]">
                                            <div class="flex items-center gap-2">
                                                Email
                                                <button type="button" id="emailHeaderToggle" class="p-1 hover:bg-gray-200 dark:hover:bg-gray-600 rounded transition" title="Toggle email visibility">
                                                    <svg id="header-eye-show" class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <svg id="header-eye-hide" class="w-3.5 h-3.5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[100px]">Status</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[100px]">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="mahasiswa-tbody-desktop" class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <!-- Desktop rows loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Mobile View - Card Layout -->
                <div id="mobile-cards-container" class="md:hidden p-4 space-y-3">
                    <!-- Mobile cards loaded here -->
                </div>

                <!-- Empty State -->
                <div id="empty-state-mahasiswa" class="hidden py-16">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0v2h-2v-2a7 7 0 00-14 0v2H6v-2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tidak ada mahasiswa ditemukan</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-center max-w-md">
                            Coba ubah filter pencarian Anda atau cari dengan kata kunci lain
                        </p>
                    </div>
                </div>

                <!-- Pagination Controls -->
                <div id="clientPaginationControls" class="p-6 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span>Menampilkan</span> 
                        <span id="paginationVisibleCount">0</span> 
                        <span>dari</span> 
                        <span id="paginationTotalCount">0</span> 
                        <span>mahasiswa</span>
                    </p>
                    <nav id="paginationNumberButtons" class="flex flex-wrap items-center gap-2"></nav>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.__PAGE_DATA__ = {
            allMahasiswa: {!! json_encode($angkatan->mahasiswa->map(function ($m) {
                return [
                    'id' => $m->id,
                    'nama_mahasiswa' => $m->nama_mahasiswa,
                    'id_jurusan' => $m->id_jurusan,
                    'jurusan_nama' => $m->jurusan?->nama_jurusan ?? '-',
                    'email' => $m->email,
                    'is_active' => $m->is_active,
                ];
            })) !!}
        };
    </script>
@endsection
