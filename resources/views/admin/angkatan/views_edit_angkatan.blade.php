@extends('Layout.Layout')
@section('title', 'Detail Angkatan')
@section('content')

<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold dark:text-white" data-translate="detail_agkt" data-translate-page="admin">Detail Angkatan</h2>
        <a href="{{ route('admin.angkatan.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> <span data-translate="back" data-translate-page="admin">Kembali</span>
        </a>
    </div>

    <!-- Info Angkatan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-50 dark:bg-gray-700 p-4 rounded-lg">
            <p class="text-sm text-gray-600 dark:text-gray-300" data-translate="nm_agkt_kcl" data-translate-page="admin">Nama Angkatan</p>
            <p class="text-lg font-semibold dark:text-white">{{ $angkatan->nama_angkatan }}</p>
        </div>
        <div class="bg-green-50 dark:bg-gray-700 p-4 rounded-lg">
            <p class="text-sm text-gray-600 dark:text-gray-300" data-translate="thn_agkt_kcl" data-translate-page="admin">Tahun Masuk</p>
            <p class="text-lg font-semibold dark:text-white">{{ date('d/m/Y', strtotime($angkatan->tahun_masuk)) }}</p>
        </div>
        <div class="bg-purple-50 dark:bg-gray-700 p-4 rounded-lg">
            <p class="text-sm text-gray-600 dark:text-gray-300" data-translate="exit_agkt_kcl" data-translate-page="admin">Tahun Keluar</p>
            <p class="text-lg font-semibold dark:text-white">
                {{ $angkatan->tahun_keluar ? date('d/m/Y', strtotime($angkatan->tahun_keluar)) : '-' }}
            </p>
        </div>
    </div>

    <!-- Statistik -->
    <div class="mb-8">
        <h3 class="text-xl font-semibold dark:text-white mb-4" data-translate="mhs_stats" data-translate-page="admin">Statistik Mahasiswa</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow border">
                <p class="text-sm text-gray-600 dark:text-gray-300" data-translate="total_mhs_agkt" data-translate-page="admin">Total Mahasiswa</p>
                <p class="text-3xl font-bold text-blue-600">{{ $angkatan->mahasiswa_count }}</p>
            </div>
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow border">
                <p class="text-sm text-gray-600 dark:text-gray-300" data-translate="stat_aktif" data-translate-page="admin">Aktif</p>
                <p class="text-3xl font-bold text-green-600">
                    {{ $angkatan->mahasiswa->where('is_active', true)->count() }}
                </p>
            </div>
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow border">
                <p class="text-sm text-gray-600 dark:text-gray-300" data-translate="stat_non_aktif" data-translate-page="admin">Tidak Aktif</p>
                <p class="text-3xl font-bold text-red-600">
                    {{ $angkatan->mahasiswa->where('is_active', false)->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Daftar Mahasiswa -->
    <div>
        <h3 class="text-xl font-semibold dark:text-white mb-4" data-translate="list_mhs" data-translate-page="admin">Daftar Mahasiswa</h3>
        
        @if($angkatan->mahasiswa->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" data-translate="no_agkt_mhs" data-translate-page="admin">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" data-translate="nm_mhs_agkt" data-translate-page="admin">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" data-translate="jrs_agkt" data-translate-page="admin">Jurusan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" data-translate="email" data-translate-page="admin">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" data-translate="stat_agkt" data-translate-page="admin">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($angkatan->mahasiswa as $index => $mahasiswa)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 dark:text-white">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 dark:text-white">{{ $mahasiswa->nama_mahasiswa }}</td>
                            <td class="px-6 py-4 dark:text-white">{{ $mahasiswa->jurusan->nama_jurusan ?? '-' }}</td>
                            <td class="px-6 py-4 dark:text-white">{{ $mahasiswa->email ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($mahasiswa->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Tidak Aktif</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-500 dark:text-gray-400 py-8">
                Tidak ada mahasiswa dalam angkatan ini
            </p>
        @endif
    </div>
</div>

@endsection