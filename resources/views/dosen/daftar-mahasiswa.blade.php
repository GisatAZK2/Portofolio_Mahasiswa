@extends('Layout.Layout')
@section('title', 'Kelola Pengguna - Dosen')
@section('content')
@php
    function isUserDataComplete($user) {
        return !empty($user->nama_mahasiswa) && !empty($user->username);
    }
@endphp
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8 transition-colors duration-200">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white" data-translate="kll_pengguna" data-translate-page="admin"></h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        <span data-translate="desc_kll_pengguna" data-translate-page="admin"></span>
                    </p>
                </div>
                <a href="{{ route('dosen.users.ViewCreate') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl shadow-sm hover:shadow-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span data-translate="add_user" data-translate-page="admin"></span>
                </a>
            </div>

            {{-- Filter Section --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-6 mb-8 border border-gray-100 dark:border-gray-800">
                <form action="{{ route('dosen.users.index') }}" method="GET" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
                        <!-- Search -->
                        <div class="lg:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pencarian</label>
                           <div class="relative">
                            <input type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari data..."
                                data-translate-placeholder="search_placeholder"
                                data-translate-page="admin"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-100">

                            <div class="absolute left-3 top-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        </div>
                       <!-- Angkatan -->
<div>
    <label
        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
        data-translate="angkatan"
        data-translate-page="admin">
        Angkatan
    </label>

    <select name="angkatan"
        class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-100">

        <option value=""
            data-translate="all_angkatan"
            data-translate-page="admin">
            Semua Angkatan
        </option>

        @foreach($angkatans as $angkatanItem)
            <option value="{{ $angkatanItem->id }}"
                {{ request('angkatan') == $angkatanItem->id ? 'selected' : '' }}>
                {{ $angkatanItem->nama_angkatan }}
            </option>
        @endforeach
    </select>
</div>

<!-- Jurusan -->
<div>
    <label
        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
        data-translate="jurusan"
        data-translate-page="admin">
        Jurusan
    </label>

    <select name="jurusan"
        class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-100">

        <option value=""
            data-translate="all_jurusan"
            data-translate-page="admin">
            Semua Jurusan
        </option>

        @foreach($jurusans as $jurusanItem)
            <option value="{{ $jurusanItem->id_jurusan }}"
                {{ request('jurusan') == $jurusanItem->id_jurusan ? 'selected' : '' }}>
                {{ $jurusanItem->nama_jurusan }}
            </option>
        @endforeach
    </select>
</div>

<!-- Keahlian -->
<div>
    <label
        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
        data-translate="keahlian"
        data-translate-page="admin">
        Keahlian
    </label>

    <select name="keahlian"
        class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-100">

        <option value=""
            data-translate="all_keahlian"
            data-translate-page="admin">
            Semua Keahlian
        </option>

        @foreach($keahlians as $keahlianItem)
            <option value="{{ $keahlianItem->id_keahlian }}"
                {{ request('keahlian') == $keahlianItem->id_keahlian ? 'selected' : '' }}>
                {{ $keahlianItem->nama_keahlian }}
            </option>
        @endforeach
    </select>
</div>
                    </div>
                </form>
            </div>

            <!-- Tabel Users - Responsive -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700">
                
                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"><span data-translate="semua_user" data-translate-page="admin"></span></th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"><span data-translate="tbl_jrs" data-translate-page="admin"></span></th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"><span data-translate="tbl_agkt" data-translate-page="admin"></span></th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"><span data-translate="keahlian" data-translate-page="admin"></span></th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"><span data-translate="stat_pengajuan" data-translate-page="admin"></span></th>
                                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"><span data-translate="aksi" data-translate-page="admin"></span>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0">
                                                @if($user->photo_profile && Storage::disk('public')->exists($user->photo_profile))
                                                    <img src="{{ asset('storage/' . ltrim($user->photo_profile, '/')) }}"
                                                         alt="{{ $user->nama_mahasiswa }}"
                                                         class="w-10 h-10 rounded-xl object-cover border border-gray-200 dark:border-gray-600">
                                                @else
                                                    <div class="w-10 h-10 rounded-xl bg-gray-200 dark:bg-gray-600 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                        <span class="text-lg font-bold text-gray-600 dark:text-gray-300">
                                                            {{ strtoupper(substr($user->nama_mahasiswa ?? $user->username ?? 'U', 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('dosen.users.details', ['id' => $user->id]) }}"
                                                   class="font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $user->nama_mahasiswa ?? 'Pengguna' }}
                                                </a>
                                                @if($user->email)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px]">
                                                        {{ $user->email }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $user->jurusan?->nama_jurusan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $user->angkatan?->nama_angkatan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-5 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $user->keahlian?->nama_keahlian ?? '-' }}
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($user->status_pengajuan)
                                            @if($user->status_pengajuan == 'Di Terima')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                                    <span class="w-2 h-2 rounded-full bg-green-500 mr-1.5"></span>
                                                    Diterima
                                                </span>
                                            @elseif($user->status_pengajuan == 'Di Tolak')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                                    <span class="w-2 h-2 rounded-full bg-red-500 mr-1.5"></span>
                                                    Ditolak
                                                </span>
                                            @elseif($user->status_pengajuan == 'Sedang Di Ajukan')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                    <span class="w-2 h-2 rounded-full bg-yellow-500 mr-1.5 animate-pulse"></span>
                                                    Pending
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5">
    <div class="flex items-center justify-center gap-2">
        @if(isUserDataComplete($user))
            <a href="{{ route('portfolio.slug', ['user' => $user->slug]) }}"
               class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
               title="Lihat Portfolio">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </a>
            
            @if($user->status_pengajuan == 'Sedang Di Ajukan')
                <button onclick="openUpdateModal({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa ?? $user->username) }}')"
                    class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors"
                    title="Update Status Pengajuan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2v-5m10-10v5a2 2 0 01-2 2v.341c0 .895-.447 1.74-1.17 2.234l-4.83 2.89A2 2 0 0110 18.382V12" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            @endif
        @else
            <div class="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700/50 px-3 py-1.5 rounded-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Data Belum Lengkap</span>
            </div>
        @endif
        
        <form action="{{ route('dosen.users.destroy', ['id' => $user->id]) }}" method="POST" class="inline delete-form">
            @csrf
            @method('DELETE')
            <button type="button"
                class="delete-btn p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                title="Hapus Pengguna"
                data-name="{{ addslashes($user->nama_mahasiswa ?? $user->username) }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </form>
    </div>
</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tidak ada data pengguna</h3>
                                        <p class="text-gray-500 dark:text-gray-400 mt-1">Silakan tambahkan pengguna baru atau ubah filter pencarian.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card Layout -->
                <div class="md:hidden space-y-4 p-4">
                    @forelse($users as $user)
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
                            <div class="p-5">
                                <!-- User Info -->
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="flex-shrink-0">
                                        @if($user->photo_profile && Storage::disk('public')->exists($user->photo_profile))
                                            <img src="{{ asset('storage/' . ltrim($user->photo_profile, '/')) }}"
                                                 alt="{{ $user->nama_mahasiswa }}"
                                                 class="w-14 h-14 rounded-2xl object-cover border border-gray-200 dark:border-gray-600">
                                        @else
                                            <div class="w-14 h-14 rounded-2xl bg-gray-200 dark:bg-gray-600 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                <span class="text-2xl font-bold text-gray-600 dark:text-gray-300">
                                                    {{ strtoupper(substr($user->nama_mahasiswa ?? $user->username ?? 'U', 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('portfolio.slug', ['user' => $user->slug]) }}"
                                           class="font-semibold text-lg text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 block">
                                            {{ $user->nama_mahasiswa ?? 'Pengguna' }}
                                        </a>
                                        @if($user->email)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                {{ $user->email }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 text-xs" data-translate="tbl_jrs" data-translate-page="admin"></p>
                                        <p class="font-medium text-gray-700 dark:text-gray-300">
                                            {{ $user->jurusan?->nama_jurusan ?? '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 text-xs" data-translate="tbl_agkt" data-translate-page="admin"></p>
                                        <p class="font-medium text-gray-700 dark:text-gray-300">
                                            {{ $user->angkatan?->nama_angkatan ?? '-' }}
                                        </p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-gray-500 dark:text-gray-400 text-xs" data-translate="keahlian" data-translate-page="admin"></p>
                                        <p class="font-medium text-gray-700 dark:text-gray-300">
                                            {{ $user->keahlian?->nama_keahlian ?? '-' }}
                                        </p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-gray-500 dark:text-gray-400 text-xs" data-translate="stat_pengajuan" data-translate-page="admin"></p>
                                        <div>
                                            @if($user->status_pengajuan)
                                                @if($user->status_pengajuan == 'Di Terima')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                                        <span class="w-2 h-2 rounded-full bg-green-500 mr-1.5"></span>
                                                        Diterima
                                                    </span>
                                                @elseif($user->status_pengajuan == 'Di Tolak')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                                        <span class="w-2 h-2 rounded-full bg-red-500 mr-1.5"></span>
                                                        Ditolak
                                                    </span>
                                                @elseif($user->status_pengajuan == 'Sedang Di Ajukan')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                        <span class="w-2 h-2 rounded-full bg-yellow-500 mr-1.5 animate-pulse"></span>
                                                        Pending
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-xs text-gray-400">—</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
<div class="border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-5 py-4 flex gap-3">
    @if(isUserDataComplete($user))
        <a href="{{ route('portfolio.slug', ['user' => $user->slug]) }}"
           class="flex-1 flex items-center justify-center gap-2 py-3 text-sm font-medium text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950 rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <span data-translate="see_portofolio" data-translate-page="admin"></span>
        </a>

        @if($user->status_pengajuan == 'Sedang Di Ajukan')
            <button onclick="openUpdateModal({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa ?? $user->username) }}')"
                class="flex-1 flex items-center justify-center gap-2 py-3 text-sm font-medium text-green-600 hover:bg-green-50 dark:hover:bg-green-950 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2v-5m10-10v5a2 2 0 01-2 2v.341c0 .895-.447 1.74-1.17 2.234l-4.83 2.89A2 2 0 0110 18.382V12" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Update Status
            </button>
        @endif
    @else
        <div class="w-full flex items-center justify-center gap-2 py-3 text-sm text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-800 rounded-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Data User Belum Lengkap
        </div>
    @endif

    <form action="{{ route('dosen.users.destroy', ['id' => $user->id]) }}" method="POST" class="inline delete-form">
        @csrf
        @method('DELETE')
        <button type="button"
            class="delete-btn px-5 py-3 text-red-600 hover:bg-red-50 dark:hover:bg-red-950 rounded-xl transition-colors"
            data-name="{{ addslashes($user->nama_mahasiswa ?? $user->username) }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    </form>
</div>
                        </div>
                    @empty
                        <div class="text-center py-16">
                            <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tidak ada data pengguna</h3>
                            <p class="text-gray-500 dark:text-gray-400 mt-1">Silakan tambahkan pengguna baru atau ubah filter pencarian.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Update Status (tetap sama) --}}
    <div id="updateModal" class="fixed inset-0 bg-gray-900/70 hidden flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Update Status Pengajuan</h3>
                <form id="updateForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Mahasiswa:
                            <span id="modalNama" class="font-medium text-gray-900 dark:text-white"></span>
                        </p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status_pengajuan" id="status_pengajuan" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Pilih Status</option>
                            <option value="Di Terima">Terima Pengajuan</option>
                            <option value="Di Tolak">Tolak Pengajuan</option>
                        </select>
                    </div>
                    <div class="mb-6 hidden" id="keteranganTolakField">
                        <label class="block text-sm font-medium mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="keterangan_tolak" id="keterangan_tolak" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl"
                            placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeUpdateModal()"
                            class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

   

    <script>
        let currentDeleteForm = null;

        function openUpdateModal(userId, userName) {
            const modal = document.getElementById('updateModal');
            const form = document.getElementById('updateForm');
            document.getElementById('modalNama').textContent = userName;
            form.action = `/user/${userId}/update-status`;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeUpdateModal() {
            const modal = document.getElementById('updateModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentDeleteForm = null;
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Toggle keterangan tolak
            const statusSelect = document.getElementById('status_pengajuan');
            if (statusSelect) {
                statusSelect.addEventListener('change', function () {
                    const field = document.getElementById('keteranganTolakField');
                    if (this.value === 'Di Tolak') {
                        field.classList.remove('hidden');
                        document.getElementById('keterangan_tolak').required = true;
                    } else {
                        field.classList.add('hidden');
                        document.getElementById('keterangan_tolak').required = false;
                    }
                });
            }

            // Delete buttons
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    currentDeleteForm = this.closest('form');
                    const userName = this.getAttribute('data-name');
                    document.getElementById('deleteUserName').textContent = userName;
                    document.getElementById('deleteModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            });

            // Confirm delete
            document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
                if (currentDeleteForm) {
                    currentDeleteForm.submit();
                }
            });

            // Close modals on outside click
            document.querySelectorAll('#updateModal, #deleteModal').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        if (this.id === 'updateModal') closeUpdateModal();
                        else closeDeleteModal();
                    }
                });
            });

            // Escape key
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') {
                    closeUpdateModal();
                    closeDeleteModal();
                }
            });
        });
    </script>
@endsection