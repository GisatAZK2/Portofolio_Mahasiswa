@extends('Layout.Layout')
@section('title', 'Detail Angkatan')

@section('content')
    <div class="max-w-7xl mx-auto bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-6 md:p-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <h2 class="text-2xl md:text-3xl font-bold dark:text-white" data-translate="detail_agkt" data-translate-page="admin">
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
                    {{ $angkatan->tahun_keluar ? date('d/m/Y', strtotime($angkatan->tahun_keluar)) : 'Belum ditentukan' }}
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
                    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-6 rounded-3xl text-center">
                        <i class="fas fa-users text-4xl text-blue-600 dark:text-blue-400 mb-4"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Mahasiswa</p>
                        <p class="text-4xl font-bold dark:text-white mt-2">{{ $angkatan->mahasiswa_count }}</p>
                    </div>

                    <!-- Aktif -->
                    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-6 rounded-3xl text-center">
                        <i class="fas fa-user-check text-4xl text-green-600 dark:text-green-400 mb-4"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Aktif</p>
                        <p class="text-4xl font-bold text-green-600 dark:text-green-400 mt-2">
                            {{ $angkatan->mahasiswa->where('is_active', true)->count() }}
                        </p>
                    </div>

                    <!-- Tidak Aktif -->
                    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-6 rounded-3xl text-center">
                        <i class="fas fa-user-times text-4xl text-red-600 dark:text-red-400 mb-4"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak Aktif</p>
                        <p class="text-4xl font-bold text-red-600 dark:text-red-400 mt-2">
                            {{ $angkatan->mahasiswa->where('is_active', false)->count() }}
                        </p>
                    </div>

                </div>
            </div>

            <!-- Grafik -->
            <div class="xl:col-span-7 bg-white dark:bg-gray-700 p-6 md:p-8 rounded-3xl border border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold dark:text-white mb-6">Grafik Status Mahasiswa</h3>
                <div class="relative" style="height: 320px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Daftar Mahasiswa -->
        <div>
            <h3 class="text-xl font-semibold dark:text-white mb-6">Daftar Mahasiswa</h3>

            @if($angkatan->mahasiswa->count() > 0)
                <div class="overflow-x-auto rounded-3xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase w-12">No</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama Mahasiswa</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase hidden md:table-cell">Jurusan</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase w-28">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @foreach($angkatan->mahasiswa as $index => $mahasiswa)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-6 py-5 text-sm dark:text-white">{{ $index + 1 }}</td>
                                    <td class="px-6 py-5 text-sm font-medium dark:text-white">{{ $mahasiswa->nama_mahasiswa }}</td>
                                    <td class="px-6 py-5 text-sm dark:text-white hidden md:table-cell">
                                        {{ $mahasiswa->jurusan->nama_jurusan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-5 text-sm dark:text-white">{{ $mahasiswa->email ?? '-' }}</td>
                                    <td class="px-6 py-5">
                                        @if($mahasiswa->is_active)
                                            <span class="px-4 py-1.5 text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300 rounded-2xl">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="px-4 py-1.5 text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 rounded-2xl">
                                                Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-16 bg-gray-50 dark:bg-gray-700 rounded-3xl">
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada mahasiswa dalam angkatan ini.</p>
                </div>
            @endif
        </div>

    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
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
        });
    </script>
@endsection