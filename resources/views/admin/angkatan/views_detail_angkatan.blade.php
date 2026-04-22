@extends('Layout.Layout')
@section('title', 'Detail Angkatan')

@section('content')
    <div class="max-w-7xl mx-auto bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-6 md:p-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <h2 class="text-2xl md:text-3xl font-bold dark:text-white" data-translate="detail_agkt"
                data-translate-page="admin">
                Detail Angkatan
            </h2>
            <a href="{{ route('admin.angkatan.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-2xl flex items-center gap-2 transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span data-translate="back" data-translate-page="admin">Kembali</span>
            </a>
        </div>

        <!-- Info Angkatan -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-3xl">
                <p class="text-sm text-gray-500 dark:text-gray-400">Nama Angkatan</p>
                <p class="text-xl font-semibold dark:text-white mt-2">{{ $angkatan->nama_angkatan }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-3xl">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tahun Masuk</p>
                <p class="text-xl font-semibold dark:text-white mt-2">
                    {{ date('d/m/Y', strtotime($angkatan->tahun_masuk)) }}
                </p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-3xl">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tahun Keluar</p>
                <p class="text-xl font-semibold dark:text-white mt-2">
                    @if($angkatan->tahun_keluar)
                        {{ date('d/m/Y', strtotime($angkatan->tahun_keluar)) }}
                    @else
                        Belum ditentukan
                    @endif
                </p>
            </div>
        </div>

        <!-- Statistik Mahasiswa -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 mb-12">

            <!-- Statistik Cards -->
            <div class="xl:col-span-5">
                <h3 class="text-xl font-semibold dark:text-white mb-6">Statistik Mahasiswa</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

                    <!-- Total -->
                    <div
                        class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-6 rounded-3xl text-center">
                        <i class="fas fa-users text-4xl text-blue-600 dark:text-blue-400 mb-4"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Mahasiswa</p>
                        <p class="text-4xl font-bold dark:text-white mt-2">{{ $angkatan->mahasiswa_count }}</p>
                    </div>

                    <!-- Aktif -->
                    <div
                        class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-6 rounded-3xl text-center">
                        <i class="fas fa-user-check text-4xl text-green-600 dark:text-green-400 mb-4"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Aktif</p>
                        <p class="text-4xl font-bold text-green-600 dark:text-green-400 mt-2">
                            {{ $angkatan->mahasiswa->where('is_active', true)->count() }}
                        </p>
                    </div>

                    <!-- Tidak Aktif -->
                    <div
                        class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-6 rounded-3xl text-center">
                        <i class="fas fa-user-times text-4xl text-red-600 dark:text-red-400 mb-4"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak Aktif</p>
                        <p class="text-4xl font-bold text-red-600 dark:text-red-400 mt-2">
                            {{ $angkatan->mahasiswa->where('is_active', false)->count() }}
                        </p>
                    </div>

                </div>
            </div>

            <!-- Grafik -->
            <div
                class="xl:col-span-7 bg-white dark:bg-gray-700 p-6 md:p-8 rounded-3xl border border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold dark:text-white mb-6">Grafik Status Mahasiswa</h3>
                <div class="relative" style="height: 320px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Daftar Mahasiswa -->
        <div>
            <h3 class="text-xl font-semibold dark:text-white mb-6" data-translate="list_mhs" data-translate-page="admin">
                Daftar Mahasiswa</h3>

            <!-- Filter Section -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 md:p-6 mb-6 border border-black dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filter & Pencarian</h4>

                <!-- Search Bar -->
                <div class="mb-4">
                    <div class="relative">
                        <input type="text" id="search-mahasiswa" placeholder="Cari nama mahasiswa..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Filter Jurusan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="filter-jurusan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Filter Prodi
                        </label>
                        <select id="filter-jurusan"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Semua Prodi</option>
                            @foreach($jurusans as $jurusan)
                                <option value="{{ $jurusan->id_jurusan }}">{{ $jurusan->nama_jurusan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="filter-status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Filter Status
                        </label>
                        <select id="filter-status"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="tidak-aktif">Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <!-- Filter Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
                    <button type="button" onclick="resetFiltersMahasiswa()"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors font-medium">
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

            @if($angkatan->mahasiswa->count() > 0)
                <!-- Table View -->
                <div class="overflow-x-auto rounded-xl border border-black dark:border-gray-700">
                    <table id="mahasiswa-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-blue-600 dark:bg-blue-700">
                            <tr>
                                <th class="px-3 py-2 text-left text-[10px] sm:text-xs font-medium text-white uppercase w-10">No</th>
                                <th class="px-3 py-2 text-left text-[10px] sm:text-xs font-medium text-white uppercase">Nama Mahasiswa</th>
                                <th class="px-3 py-2 text-left text-[10px] sm:text-xs font-medium text-white uppercase  whitespace-nowrap">
                                    Jurusan</th>
                                <th class="px-3 py-2 text-left text-[10px] sm:text-xs font-medium text-white uppercase">Email</th>
                                <th class="px-3 py-2 text-left text-[10px] sm:text-xs font-medium text-white uppercase w-24 whitespace-nowrap">Status</th>
                                <th class="px-3 py-2 text-center text-[10px] sm:text-xs font-medium text-white uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="mahasiswa-tbody"
                            class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            <!-- Table rows will be loaded here -->
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div id="empty-state-mahasiswa" class="hidden">
                    <div class="flex flex-col items-center justify-center py-16 px-4">
                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0v2h-2v-2a7 7 0 00-14 0v2H6v-2z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tidak ada mahasiswa ditemukan</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-center max-w-md">
                            Coba ubah filter pencarian Anda atau cari dengan kata kunci lain
                        </p>
                    </div>
                </div>
            @else
                <div class="text-center py-16 bg-gray-50 dark:bg-gray-700 rounded-3xl">
                    <p class="text-gray-500 dark:text-gray-400">
                        Tidak ada mahasiswa dalam angkatan ini.</p>
                </div>
            @endif
        </div>

    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        // Data mahasiswa
       // Data mahasiswa - PERBAIKAN: gunakan id_jurusan
const allMahasiswa = {!! json_encode($angkatan->mahasiswa->map(function ($m) {
    return [
        'id' => $m->id,
        'nama_mahasiswa' => $m->nama_mahasiswa,
        'id_jurusan' => $m->id_jurusan, // PERBAIKAN: pakai id_jurusan, bukan jurusan_id
        'jurusan_nama' => $m->jurusan?->nama_jurusan ?? '-',
        'email' => $m->email,
        'is_active' => $m->is_active,
    ];
})) !!};



        let filteredMahasiswa = [];

        document.addEventListener("DOMContentLoaded", () => {
            const aktif = {{ $angkatan->mahasiswa->where('is_active', true)->count() }};
            const tidakAktif = {{ $angkatan->mahasiswa->where('is_active', false)->count() }};

            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Aktif', 'Tidak Aktif'],
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
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { size: 14 },
                                padding: 25,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });

            // Initial load
            applyFiltersMahasiswa();

            // Event listeners
            document.getElementById('search-mahasiswa').addEventListener('keyup', function (e) {
                if (e.key === 'Enter') {
                    applyFiltersMahasiswa();
                }
            });

            document.getElementById('filter-jurusan').addEventListener('change', applyFiltersMahasiswa);
            document.getElementById('filter-status').addEventListener('change', applyFiltersMahasiswa);
        });

        function applyFiltersMahasiswa() {
    const search = document.getElementById('search-mahasiswa').value.toLowerCase().trim();
    const jurusanFilter = document.getElementById('filter-jurusan').value;
    const statusFilter = document.getElementById('filter-status').value;

    filteredMahasiswa = allMahasiswa.filter(m => {
        const matchesSearch = m.nama_mahasiswa.toLowerCase().includes(search);
        // PERBAIKAN: gunakan id_jurusan, bukan jurusan_id
        const matchesJurusan = !jurusanFilter || m.id_jurusan == jurusanFilter;
        const matchesStatus = !statusFilter ||
            (statusFilter === 'aktif' ? m.is_active : !m.is_active);

        return matchesSearch && matchesJurusan && matchesStatus;
    });

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
            const locale = document.querySelector('html').getAttribute('lang') || 'id';

            filteredMahasiswa.forEach((m, index) => {
                const statusColor = m.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300';
                const statusText = m.is_active ? 'Aktif' : 'Tidak Aktif';
                const portfolioUrl = `/${locale}/portofolio?user=${m.id}`;

                html += `
                                                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                                                                <td class="px-3 py-2 text-[11px] text-gray-900 dark:text-white">${index + 1}</td>
                                                                                <td class="px-3 py-2 text-[11px] font-medium text-gray-900 dark:text-white">${m.nama_mahasiswa}</td>
                                                                                <td class="px-3 py-2 text-[11px] text-gray-700 dark:text-gray-300 ">${m.jurusan_nama}</td>
                                                                                <td class="px-3 py-2 text-[11px] text-gray-700 dark:text-gray-300 max-w-[150px] sm:max-w-[200px] truncate" title="${m.email || '-'}">
                                                                                    ${m.email || '-'}
                                                                                </td>
                                                                                <td class="px-3 py-2">
                                                                                    <span class="px-2 py-1 text-[10px] font-medium ${statusColor} rounded-full">
                                                                                        ${statusText}
                                                                                    </span>
                                                                                </td>
                                                                                <td class="px-3 py-2 text-center">
                                                                                    <a href="${portfolioUrl}" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-[10px] sm:text-xs font-medium rounded-lg transition-colors">
                                                                                        Lihat
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        `;
            });

            tbody.innerHTML = html;
        } function resetFiltersMahasiswa() {
            document.getElementById('search-mahasiswa').value = '';
            document.getElementById('filter-jurusan').value = '';
            document.getElementById('filter-status').value = '';
            applyFiltersMahasiswa();
        }
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.detail_angkatan");
        });
    </script>

    <style>
        /* Custom scrollbar for table container */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .dark .overflow-x-auto::-webkit-scrollbar-track {
            background: #374151;
        }
        .dark .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #6b7280;
        }
        .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
@endsection
