@extends('Layout.Layout')
@section('title', 'Dashboard Dosen')
@section('content')

    <div class="dark:text-white">
        <h1 class="text-3xl font-bold ml-6 mb-6" data-translate="dashboard_dosen" data-translate-page="dosen_dashboard">
            Dashboard Dosen</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Card Total Mahasiswa Bimbingan -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 dark:text-gray-400" data-translate="ttl_mhs_bbg"
                            data-translate-page="dosen_dashboard">Total Mahasiswa Bimbingan</p>
                        <p class="text-2xl font-semibold">{{ App\Models\User::where('role', 'mahasiswa')
        ->where('id_jurusan', auth()->user()->id_jurusan)
        ->where('id_angkatan', auth()->user()->id_angkatan)
        ->where('id_keahlian', auth()->user()->id_keahlian)
        ->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Card Menunggu Persetujuan -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 dark:text-gray-400" data-translate="approval_pending"
                            data-translate-page="dosen_dashboard">Menunggu Persetujuan</p>
                        <p class="text-2xl font-semibold">{{ App\Models\User::where('role', 'mahasiswa')
        ->where('id_jurusan', auth()->user()->id_jurusan)
        ->where('id_angkatan', auth()->user()->id_angkatan)
        ->where('id_keahlian', auth()->user()->id_keahlian)
        ->where('status_pengajuan', 'Sedang Di Ajukan')
        ->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Card Total Projects -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 dark:text-gray-400" data-translate-page="dosen_dashboard"
                            data-translate="ttl_pjt">Total Projects</p>
                        <p class="text-2xl font-semibold">{{ App\Models\Project::whereHas('mahasiswa', function ($q) {
        $q->where('id_jurusan', auth()->user()->id_jurusan)
            ->where('id_angkatan', auth()->user()->id_angkatan)
            ->where('id_keahlian', auth()->user()->id_keahlian);
    })->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Menu Cepat -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4" data-translate="quick_mn" data-translate-page="dosen_dashboard">Menu
                    Cepat</h2>
                <div class="space-y-3">
                    <a href="{{ route('dosen.users.index') }}"
                        class="block p-3 bg-blue-50 dark:bg-gray-700 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-600">
                        <span class="font-medium" data-translate="kll_mhs" data-translate-page="dosen_dashboard">Kelola
                            Mahasiswa</span>
                        <p class="text-sm text-gray-600 dark:text-gray-400" data-translate="kll_desc_mhs"
                            data-translate-page="dosen_dashboard">Setujui atau tolak pengajuan mahasiswa</p>
                    </a>
                    <a href="{{ route('dosen.projects.index') }}"
                        class="block p-3 bg-green-50 dark:bg-gray-700 rounded-lg hover:bg-green-100 dark:hover:bg-gray-600">
                        <span class="font-medium" data-translate="kll_pjt" data-translate-page="dosen_dashboard">Kelola
                            Projects</span>
                        <p class="text-sm text-gray-600 dark:text-gray-400" data-translate="kll_desc_pjt"
                            data-translate-page="dosen_dashboard">Lihat dan validasi project mahasiswa</p>
                    </a>
                    <a href="{{ route('dosen.sertifikat.index') }}"
                        class="block p-3 bg-purple-50 dark:bg-gray-700 rounded-lg hover:bg-purple-100 dark:hover:bg-gray-600">
                        <span class="font-medium" data-translate="kll_srtfkt" data-translate-page="dosen_dashboard">Kelola
                            Sertifikat</span>
                        <p class="text-sm text-gray-600 dark:text-gray-400" data-translate="kll_desc_srtfkt"
                            data-translate-page="dosen_dashboard">Lihat dan validasi sertifikat mahasiswa</p>
                    </a>
                </div>
            </div>

            <!-- Informasi -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4" data-translate="info" data-translate-page="dosen_dashboard">Informasi
                </h2>
                <div class="space-y-3">
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-sm">
                            <span class="font-medium" data-translate="info_jrs"
                                data-translate-page="dosen_dashboard">Jurusan:</span>
                            {{ auth()->user()->jurusan->nama_jurusan ?? '-' }}
                        </p>
                        <p class="text-sm">
                            <span class="font-medium" data-translate="info_agkt"
                                data-translate-page="dosen_dashboard">Angkatan:</span>
                            {{ auth()->user()->angkatan->nama_angkatan ?? '-' }}
                        </p>
                        <p class="text-sm">
                            <span class="font-medium" data-translate="info_khl"
                                data-translate-page="dosen_dashboard">Keahlian:</span>
                            {{ auth()->user()->keahlian->nama_keahlian ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

@endsection