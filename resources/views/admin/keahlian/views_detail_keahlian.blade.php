@extends('Layout.Layout')
@section('title', 'Detail Keahlian - ' . ($keahlian->nama_keahlian ?? ''))

@section('content')
    <div class="max-w-7xl mx-auto bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-6 md:p-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <h2 class="text-2xl md:text-3xl font-bold dark:text-white" data-translate="detail_keahlian_title" data-translate-page="admin">
                Detail Keahlian
            </h2>
            <a href="{{ route('admin.keahlian.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-2xl flex items-center gap-2 transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span data-translate="back" data-translate-page="admin">Kembali</span>
            </a>
        </div>

        <!-- Info Keahlian -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-3xl">
                <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="nama_keahlian_label" data-translate-page="admin">Nama Keahlian</p>
                <p class="text-xl font-semibold dark:text-white mt-2">{{ $keahlian->nama_keahlian }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-3xl">
                <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="total_mahasiswa" data-translate-page="admin">Total Mahasiswa</p>
                <p class="text-4xl font-bold dark:text-white mt-2">{{ $keahlian->users_count ?? 0 }}</p>
            </div>
        </div>

        <!-- Statistik Mahasiswa -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 mb-12">

            <!-- Statistik Cards -->
            <div class="xl:col-span-5">
                <h3 class="text-xl font-semibold dark:text-white mb-6" data-translate="statistik_mahasiswa" data-translate-page="admin">Statistik Mahasiswa</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

                    <!-- Total -->
                    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-6 rounded-3xl text-center">
                        <i class="fas fa-users text-4xl text-blue-600 dark:text-blue-400 mb-4"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="ttl_mhs" data-translate-page="project_create">Total Mahasiswa</p>
                        <p class="text-4xl font-bold dark:text-white mt-2">{{ $keahlian->users_count ?? 0 }}</p>
                    </div>

                    <!-- Aktif -->
                    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-6 rounded-3xl text-center">
                        <i class="fas fa-user-check text-4xl text-green-600 dark:text-green-400 mb-4"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="aktf" data-translate-page="project_create">Aktif</p>
                        <p class="text-4xl font-bold text-green-600 dark:text-green-400 mt-2">
                            {{ $keahlian->users->where('is_active', true)->count() }}
                        </p>
                    </div>

                    <!-- Tidak Aktif -->
                    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-6 rounded-3xl text-center">
                        <i class="fas fa-user-times text-4xl text-red-600 dark:text-red-400 mb-4"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="tdk_aktf" data-translate-page="project_create">Tidak Aktif</p>
                        <p class="text-4xl font-bold text-red-600 dark:text-red-400 mt-2">
                            {{ $keahlian->users->where('is_active', false)->count() }}
                        </p>
                    </div>

                </div>
            </div>

            <!-- Grafik -->
            <div class="xl:col-span-7 bg-white dark:bg-gray-700 p-6 md:p-8 rounded-3xl border border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold dark:text-white mb-6" data-translate="grafik_status_mahasiswa" data-translate-page="admin">Grafik Status Mahasiswa</h3>
                <div class="relative" style="height: 320px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Daftar Mahasiswa -->
        <div>
            <h3 class="text-xl font-semibold dark:text-white mb-6" data-translate="daftar_mahasiswa" data-translate-page="admin">Daftar Mahasiswa</h3>

            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 md:p-6 mb-6 border border-black dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4" data-translate="flt_pencarian" data-translate-page="project_create">Filter & Pencarian</h4>

                <!-- Search Bar -->
                <div class="mb-4">
                    <div class="relative">
                        <input type="text" id="search-mahasiswa" placeholder="Cari nama mahasiswa..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" data-translate-placeholder="cari_nama_mahasiswa" data-translate-page="admin">
                        <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Filter Status -->
                <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
                    <button type="button" onclick="resetFiltersMahasiswa()"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors font-medium" data-translate="rst_flt" data-translate-page="project_create">
                        Reset Filter
                    </button>
                    <button type="button" onclick="applyFiltersMahasiswa()"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                        Terapkan Filter
                    </button>
                </div>
            </div>

            <!-- Results Info -->
            <div class="mb-4 text-gray-600 dark:text-gray-400 text-sm">
                <span>Menampilkan <strong id="results-count">0</strong> mahasiswa</span>
            </div>

            @if($keahlian->users->count() > 0)
                <div class="overflow-x-auto rounded-xl border border-black dark:border-gray-700">
                    <table id="mahasiswa-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-blue-600 dark:bg-blue-700">
                            <tr>
                                <th class="px-3 py-2 text-left text-[10px] sm:text-xs font-medium text-white uppercase w-10">No</th>
                                <th class="px-3 py-2 text-left text-[10px] sm:text-xs font-medium text-white uppercase">Nama Mahasiswa</th>
                                <th class="px-3 py-2 text-left text-[10px] sm:text-xs font-medium text-white uppercase">Email</th>
                                <th class="px-3 py-2 text-left text-[10px] sm:text-xs font-medium text-white uppercase w-24 whitespace-nowrap">Status</th>
                                <th class="px-3 py-2 text-center text-[10px] sm:text-xs font-medium text-white uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="mahasiswa-tbody" class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            <!-- Diisi via JS -->
                        </tbody>
                    </table>
                </div>

                <div id="empty-state-mahasiswa" class="hidden text-center py-16">
                    <div class="flex flex-col items-center justify-center py-16 px-4">
                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0v2h-2v-2a7 7 0 00-14 0v2H6v-2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tidak ada mahasiswa ditemukan</h3>
                    </div>
                </div>
            @else
                <div class="text-center py-16 bg-gray-50 dark:bg-gray-700 rounded-3xl">
                    <p class="text-gray-500 dark:text-gray-400" data-translate="tidak_ada_mahasiswa_keahlian" data-translate-page="admin">Tidak ada mahasiswa dalam keahlian ini.</p>
                </div>
            @endif
        </div>

    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const allMahasiswa = {!! json_encode($keahlian->users->map(function ($m) {
            return [
                'id' => $m->id,
                'nama_mahasiswa' => $m->nama_mahasiswa,
                'email' => $m->email,
                'is_active' => $m->is_active,
            ];
        })) !!};

        let filteredMahasiswa = [];

        document.addEventListener("DOMContentLoaded", () => {
            const aktif = {{ $keahlian->users->where('is_active', true)->count() }};
            const tidakAktif = {{ $keahlian->users->where('is_active', false)->count() }};

            // Get translated labels
            const aktifLabel = translations[currentLang]?.project_create?.aktf || 'Aktif';
            const tidakAktifLabel = translations[currentLang]?.project_create?.tdk_aktf || 'Tidak Aktif';

            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: [aktifLabel, tidakAktifLabel],
                    datasets: [{
                        data: [aktif, tidakAktif],
                        backgroundColor: ['#10b981', '#ef4444'],
                        borderWidth: 3,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { size: 14 }, padding: 25, usePointStyle: true }}
                    }
                }
            });

            applyFiltersMahasiswa();

            document.getElementById('search-mahasiswa').addEventListener('keyup', function (e) {
                if (e.key === 'Enter') applyFiltersMahasiswa();
            });
        });

        function applyFiltersMahasiswa() {
            const search = document.getElementById('search-mahasiswa').value.toLowerCase().trim();

            filteredMahasiswa = allMahasiswa.filter(m => 
                m.nama_mahasiswa.toLowerCase().includes(search)
            );

            displayMahasiswa();
        }

        function displayMahasiswa() {
            const tbody = document.getElementById('mahasiswa-tbody');
            const emptyState = document.getElementById('empty-state-mahasiswa');
            const table = document.getElementById('mahasiswa-table');

            document.getElementById('results-count').textContent = filteredMahasiswa.length;

            if (filteredMahasiswa.length === 0) {
                tbody.innerHTML = '';
                table.classList.add('hidden');
                emptyState.classList.remove('hidden');
                return;
            }

            table.classList.remove('hidden');
            emptyState.classList.add('hidden');

            let html = '';
            filteredMahasiswa.forEach((m, index) => {
                const statusColor = m.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300';
                const statusText = m.is_active ? (translations[currentLang]?.project_create?.aktf || 'Aktif') : (translations[currentLang]?.project_create?.tdk_aktf || 'Tidak Aktif');
                const portfolioUrl = '{{ route('portfolio.show', ':id') }}'.replace(':id', m.id);

                html += `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-3 py-2 text-[11px] text-gray-900 dark:text-white">${index + 1}</td>
                        <td class="px-3 py-2 text-[11px] font-medium text-gray-900 dark:text-white">${m.nama_mahasiswa}</td>
                        <td class="px-3 py-2 text-[11px] text-gray-700 dark:text-gray-300 max-w-[200px] truncate" title="${m.email || '-'}">${m.email || '-'}</td>
                        <td class="px-3 py-2">
                            <span class="px-2 py-1 text-[10px] font-medium ${statusColor} rounded-full">${statusText}</span>
                        </td>
                        <td class="px-3 py-2 text-center">
                            <a href="${portfolioUrl}" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-[10px] sm:text-xs font-medium rounded-lg transition-colors">Lihat</a>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        function resetFiltersMahasiswa() {
            document.getElementById('search-mahasiswa').value = '';
            applyFiltersMahasiswa();
        }
    </script>

@endsection