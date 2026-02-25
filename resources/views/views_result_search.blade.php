@extends('Layout.Layout')
@section('title', 'Hasil Pencarian')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Filter Info -->
    @if($keyword || request()->jurusan || request()->keahlian || request()->angkatan)
    <div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-sm font-medium text-gray-700">Filter aktif:</span>
            
            @if($keyword)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Pencarian: "{{ $keyword }}"
            </span>
            @endif

            @if(request()->jurusan && $results->isNotEmpty())
                @php
                    $jurusanItem = $results->first(function($item) {
                        return isset($item->jurusan) && $item->jurusan->id_jurusan == request()->jurusan;
                    });
                @endphp
                @if($jurusanItem)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                    </svg>
                    Jurusan: {{ $jurusanItem->jurusan->nama_jurusan }}
                </span>
                @endif
            @endif

            @if(request()->keahlian && $results->isNotEmpty())
                @php
                    $keahlianItem = $results->first(function($item) {
                        return isset($item->keahlian) && $item->keahlian->id_keahlian == request()->keahlian;
                    });
                @endphp
                @if($keahlianItem)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    Keahlian: {{ $keahlianItem->keahlian->nama_keahlian }}
                </span>
                @endif
            @endif

            @if(request()->angkatan)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-amber-100 text-amber-800">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Angkatan {{ request()->angkatan }}
            </span>
            @endif

            @if(request()->type)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-emerald-100 text-emerald-800">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                Tipe: {{ ucfirst(request()->type) }}
            </span>
            @endif

            <a href="{{ route('search') }}" class="ml-auto inline-flex items-center px-3 py-1 text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Hapus semua filter
            </a>
        </div>
    </div>
    @endif

    <!-- Ringkasan Hasil Total -->
    @if($results->count() > 0)
        <div class="mb-10 flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-2xl font-bold text-gray-900">Hasil Pencarian</h2>
            <div class="flex items-center gap-3">
                <span class="text-gray-700 font-medium text-lg">
                    Total: {{ $results->count() }} entri ditemukan
                </span>
            </div>
        </div>
    @endif

    @if($results->count() > 0)

        <!-- 1. Bagian Mahasiswa -->
        @php $mahasiswa = $results->where('type', 'mahasiswa'); @endphp
        @if($mahasiswa->count() > 0)
        <div class="mb-12" id="mahasiswa-section">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="inline-flex px-4 py-2 rounded-full bg-blue-100 text-blue-800 font-medium text-base">
                        Mahasiswa ({{ $mahasiswa->count() }})
                    </span>
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mahasiswa-grid">
                @foreach($mahasiswa->take(3) as $item)
                    <a href="{{ route('portfolio.show', $item) }}"
                       class="block h-full group focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-xl mahasiswa-item">
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full group-hover:border-indigo-300 group-hover:ring-1 group-hover:ring-indigo-200">
                            <div class="p-6 flex flex-col flex-1">
                                <!-- Foto + Nama + Badge -->
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="flex-shrink-0">
                                        @if($item->photo_profile)
                                            <img src="{{ asset('storage/' . $item->photo_profile) }}"
                                                 class="w-14 h-14 rounded-full object-cover border-2 border-gray-200 shadow-sm transition-transform group-hover:scale-105"
                                                 alt="{{ $item->nama_mahasiswa ?? 'Profil' }}">
                                        @else
                                            <div class="w-14 h-14 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xl shadow-sm transition-transform group-hover:scale-105">
                                                {{ strtoupper(substr($item->nama_mahasiswa ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-700 transition-colors">
                                                {{ $item->nama_mahasiswa ?? 'Nama tidak tersedia' }}
                                            </h3>
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Mahasiswa
                                            </span>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-600 flex items-center gap-1">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $item->email ?? 'Email tidak tersedia' }}
                                        </p>
                                    </div>
                                </div>
                                <!-- Jurusan, Keahlian & Angkatan -->
                                <div class="flex flex-wrap gap-2 mb-5">
                                    @if($item->angkatan)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Angkatan {{ $item->angkatan->tahun_angkatan ?? $item->angkatan->nama_angkatan ?? $item->angkatan }}
                                        </span>
                                    @endif
                                    @if($item->jurusan)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5"/>
                                            </svg>
                                            {{ $item->jurusan->nama_jurusan }}
                                        </span>
                                    @endif
                                    @if($item->keahlian)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            </svg>
                                            {{ $item->keahlian->nama_keahlian }}
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-auto">
                                    <div class="text-sm text-gray-600 flex justify-between border-t pt-4">
                                        <span><strong class="text-gray-900">{{ $item->projects_count ?? 0 }}</strong> Project</span>
                                        <span><strong class="text-gray-900">{{ $item->portofolios_count ?? 0 }}</strong> Portofolio</span>
                                        <span><strong class="text-gray-900">{{ $item->learning_count ?? 0 }}</strong> Learning</span>
                                    </div>
                                    <div class="mt-3 text-right">
                                        <span class="text-sm font-medium text-indigo-600 group-hover:text-indigo-800 transition-colors flex items-center justify-end gap-1">
                                            Lihat portfolio lengkap →
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach

                @foreach($mahasiswa->skip(3) as $item)
                    <a href="{{ route('portfolio.show', $item) }}"
                       class="block h-full group focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-xl mahasiswa-item hidden">
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full group-hover:border-indigo-300 group-hover:ring-1 group-hover:ring-indigo-200">
                            <div class="p-6 flex flex-col flex-1">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="flex-shrink-0">
                                        @if($item->photo_profile)
                                            <img src="{{ asset('storage/' . $item->photo_profile) }}"
                                                 class="w-14 h-14 rounded-full object-cover border-2 border-gray-200 shadow-sm transition-transform group-hover:scale-105"
                                                 alt="{{ $item->nama_mahasiswa ?? 'Profil' }}">
                                        @else
                                            <div class="w-14 h-14 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xl shadow-sm transition-transform group-hover:scale-105">
                                                {{ strtoupper(substr($item->nama_mahasiswa ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-700 transition-colors">
                                                {{ $item->nama_mahasiswa ?? 'Nama tidak tersedia' }}
                                            </h3>
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Mahasiswa
                                            </span>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-600 flex items-center gap-1">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $item->email ?? 'Email tidak tersedia' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-2 mb-5">
                                    @if($item->angkatan)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Angkatan {{ $item->angkatan->tahun_angkatan ?? $item->angkatan->nama_angkatan ?? $item->angkatan }}
                                        </span>
                                    @endif
                                    @if($item->jurusan)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5"/>
                                            </svg>
                                            {{ $item->jurusan->nama_jurusan }}
                                        </span>
                                    @endif
                                    @if($item->keahlian)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            </svg>
                                            {{ $item->keahlian->nama_keahlian }}
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-auto">
                                    <div class="text-sm text-gray-600 flex justify-between border-t pt-4">
                                        <span><strong class="text-gray-900">{{ $item->projects_count ?? 0 }}</strong> Project</span>
                                        <span><strong class="text-gray-900">{{ $item->portofolios_count ?? 0 }}</strong> Portofolio</span>
                                        <span><strong class="text-gray-900">{{ $item->learning_count ?? 0 }}</strong> Learning</span>
                                    </div>
                                    <div class="mt-3 text-right">
                                        <span class="text-sm font-medium text-indigo-600 group-hover:text-indigo-800 transition-colors flex items-center justify-end gap-1">
                                            Lihat portfolio lengkap →
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($mahasiswa->count() > 3)
                <div class="mt-8 text-center">
                    <button 
                        onclick="toggleSeeMore('mahasiswa-grid', this, {{ $mahasiswa->count() }})"
                        class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-md">
                        Lihat semua {{ $mahasiswa->count() }} mahasiswa
                        <svg class="w-5 h-5 ml-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>
            @endif
        </div>
        @endif

        <!-- Separator -->
        @if($results->where('type', 'portofolio')->count() > 0 || $results->where('type', 'project')->count() > 0)
            <hr class="my-12 border-gray-200">
        @endif

        <!-- 2. Bagian Portofolio -->
        @php $portofolios = $results->where('type', 'portofolio'); @endphp
        @if($portofolios->count() > 0)
        <div class="mb-12" id="portofolio-section">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="inline-flex px-4 py-2 rounded-full bg-indigo-100 text-indigo-800 font-medium text-base">
                        Portofolio ({{ $portofolios->count() }})
                    </span>
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 portofolio-grid">
                @foreach($portofolios->take(3) as $item)
                    @php
                        $content = is_string($item->isi_content) ? json_decode($item->isi_content, true) : (array) $item->isi_content;
                        $judul = $content['judul'] ?? 'Portofolio Tanpa Judul';
                        $deskripsi = $content['deskripsi'] ?? 'Tidak ada deskripsi tersedia';
                        $link_project = $content['link_project'] ?? null;
                        $link_github = $content['link_github'] ?? null;
                        $link_video = $content['link_video'] ?? null;
                    @endphp
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full border-t-4 border-indigo-500 portofolio-item">
                        <div class="p-6 flex flex-col flex-1">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mb-3 w-fit">
                                Portofolio
                            </span>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $judul }}
                            </h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                {{ Str::limit($deskripsi, 130) }}
                            </p>
                            @if($item->mahasiswa)
                                <p class="text-sm text-gray-600 mb-4">
                                    Oleh <strong>{{ $item->mahasiswa->nama_mahasiswa ?? '—' }}</strong>
                                    @if($item->mahasiswa->angkatan)
                                        <span class="inline-flex ml-2 px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-700">
                                            Angkatan {{ $item->mahasiswa->angkatan->tahun_angkatan ?? $item->mahasiswa->angkatan->nama_angkatan ?? $item->mahasiswa->angkatan }}
                                        </span>
                                    @endif
                                </p>
                            @endif
                            <div class="flex flex-wrap gap-3 mb-5">
                                @if($link_project)
                                    <a href="{{ $link_project }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-900 transition">
                                        Lihat Project →
                                    </a>
                                @elseif($link_github)
                                    <a href="{{ $link_github }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-900 transition">
                                        GitHub →
                                    </a>
                                @endif
                                @if($link_video)
                                    <a href="{{ $link_video }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition">
                                        Video Demo →
                                    </a>
                                @endif
                            </div>
                            <div class="mt-auto flex items-center justify-between text-xs text-gray-500 pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Diperbarui {{ \Carbon\Carbon::parse($item->updated_at ?? $item->tanggal ?? now())->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @foreach($portofolios->skip(3) as $item)
                    @php
                        $content = is_string($item->isi_content) ? json_decode($item->isi_content, true) : (array) $item->isi_content;
                        $judul = $content['judul'] ?? 'Portofolio Tanpa Judul';
                        $deskripsi = $content['deskripsi'] ?? 'Tidak ada deskripsi tersedia';
                        $link_project = $content['link_project'] ?? null;
                        $link_github = $content['link_github'] ?? null;
                        $link_video = $content['link_video'] ?? null;
                    @endphp
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full border-t-4 border-indigo-500 portofolio-item hidden">
                        <div class="p-6 flex flex-col flex-1">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mb-3 w-fit">
                                Portofolio
                            </span>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $judul }}
                            </h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                {{ Str::limit($deskripsi, 130) }}
                            </p>
                            @if($item->mahasiswa)
                                <p class="text-sm text-gray-600 mb-4">
                                    Oleh <strong>{{ $item->mahasiswa->nama_mahasiswa ?? '—' }}</strong>
                                    @if($item->mahasiswa->angkatan)
                                        <span class="inline-flex ml-2 px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-700">
                                            Angkatan {{ $item->mahasiswa->angkatan->tahun_angkatan ?? $item->mahasiswa->angkatan->nama_angkatan ?? $item->mahasiswa->angkatan }}
                                        </span>
                                    @endif
                                </p>
                            @endif
                            <div class="flex flex-wrap gap-3 mb-5">
                                @if($link_project)
                                    <a href="{{ $link_project }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-900 transition">
                                        Lihat Project →
                                    </a>
                                @elseif($link_github)
                                    <a href="{{ $link_github }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-900 transition">
                                        GitHub →
                                    </a>
                                @endif
                                @if($link_video)
                                    <a href="{{ $link_video }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition">
                                        Video Demo →
                                    </a>
                                @endif
                            </div>
                            <div class="mt-auto flex items-center justify-between text-xs text-gray-500 pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Diperbarui {{ \Carbon\Carbon::parse($item->updated_at ?? $item->tanggal ?? now())->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($portofolios->count() > 3)
                <div class="mt-8 text-center">
                    <button 
                        onclick="toggleSeeMore('portofolio-grid', this, {{ $portofolios->count() }})"
                        class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-md">
                        Lihat semua {{ $portofolios->count() }} portofolio
                        <svg class="w-5 h-5 ml-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>
            @endif
        </div>
        @endif

        <!-- Separator -->
        @if($results->where('type', 'project')->count() > 0)
            <hr class="my-12 border-gray-200">
        @endif

        <!-- 3. Bagian Project -->
        @php $projects = $results->where('type', 'project'); @endphp
        @if($projects->count() > 0)
        <div class="mb-12" id="project-section">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="inline-flex px-4 py-2 rounded-full bg-green-100 text-green-800 font-medium text-base">
                        Project ({{ $projects->count() }})
                    </span>
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 project-grid">
                @foreach($projects->take(3) as $item)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full border-t-4 border-green-500 project-item">
                        <div class="p-6 flex flex-col flex-1">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-3 w-fit">
                                Project
                            </span>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $item->nama_project ?? 'Project Tanpa Judul' }}
                            </h3>
                            @if($item->mahasiswa)
                                <p class="text-sm text-gray-600 mb-3">
                                    Oleh <strong>{{ $item->mahasiswa->nama_mahasiswa ?? '—' }}</strong>
                                    @if($item->mahasiswa->angkatan)
                                        <span class="inline-flex ml-2 px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-700">
                                            Angkatan {{ $item->mahasiswa->angkatan->tahun_angkatan ?? $item->mahasiswa->angkatan->nama_angkatan ?? $item->mahasiswa->angkatan }}
                                        </span>
                                    @endif
                                </p>
                            @endif
                            <p class="text-sm text-gray-500 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') : '—' }}
                                @if($item->tanggal_akhir)
                                    - {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }}
                                @else
                                    - Sekarang
                                @endif
                            </p>
                            @if($item->link_project)
                                <a href="{{ $item->link_project }}" target="_blank" rel="noopener noreferrer"
                                   class="mt-auto inline-flex items-center justify-center px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                                    Lihat Project →
                                </a>
                            @else
                                <p class="mt-auto text-sm text-gray-500 italic">Tidak ada link project</p>
                            @endif
                        </div>
                    </div>
                @endforeach

                @foreach($projects->skip(3) as $item)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full border-t-4 border-green-500 project-item hidden">
                        <div class="p-6 flex flex-col flex-1">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-3 w-fit">
                                Project
                            </span>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $item->nama_project ?? 'Project Tanpa Judul' }}
                            </h3>
                            @if($item->mahasiswa)
                                <p class="text-sm text-gray-600 mb-3">
                                    Oleh <strong>{{ $item->mahasiswa->nama_mahasiswa ?? '—' }}</strong>
                                    @if($item->mahasiswa->angkatan)
                                        <span class="inline-flex ml-2 px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-700">
                                            Angkatan {{ $item->mahasiswa->angkatan->tahun_angkatan ?? $item->mahasiswa->angkatan->nama_angkatan ?? $item->mahasiswa->angkatan }}
                                        </span>
                                    @endif
                                </p>
                            @endif
                            <p class="text-sm text-gray-500 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') : '—' }}
                                @if($item->tanggal_akhir)
                                    - {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }}
                                @else
                                    - Sekarang
                                @endif
                            </p>
                            @if($item->link_project)
                                <a href="{{ $item->link_project }}" target="_blank" rel="noopener noreferrer"
                                   class="mt-auto inline-flex items-center justify-center px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                                    Lihat Project →
                                </a>
                            @else
                                <p class="mt-auto text-sm text-gray-500 italic">Tidak ada link project</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($projects->count() > 3)
                <div class="mt-8 text-center">
                    <button 
                        onclick="toggleSeeMore('project-grid', this, {{ $projects->count() }})"
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition shadow-md">
                        Lihat semua {{ $projects->count() }} project
                        <svg class="w-5 h-5 ml-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>
            @endif
        </div>
        @endif

    @else
        <!-- Tidak ada hasil -->
        <div class="text-center py-20 bg-white rounded-xl shadow-sm border border-gray-200">
            <svg class="mx-auto h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <h3 class="mt-6 text-2xl font-medium text-gray-900">Tidak ada hasil ditemukan</h3>
            <p class="mt-3 text-gray-600 max-w-md mx-auto">
                Coba ubah kata kunci, pilih jurusan/keahlian/angkatan lain, atau hapus filter di atas.
            </p>
            <div class="mt-6 flex justify-center gap-3">
                <a href="{{ route('search') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset Filter
                </a>
            </div>
        </div>
    @endif

    @if(method_exists($results, 'links'))
        <div class="mt-10 flex justify-center">
            {{ $results->links() }}
        </div>
    @endif

</div>

@push('scripts')
<script>
function toggleSeeMore(gridClass, button, totalCount) {
    const grid = document.querySelector(`.${gridClass}`);
    const items = grid.querySelectorAll(`.${gridClass.replace('-grid', '-item')}`);
    const hidden = Array.from(items).filter(item => item.classList.contains('hidden'));

    if (hidden.length > 0) {
        // Tampilkan semua
        hidden.forEach(item => item.classList.remove('hidden'));
        button.innerHTML = `
            Sembunyikan 
            <svg class="w-5 h-5 ml-2 inline transition-transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        `;
    } else {
        // Sembunyikan kembali ke 3 item
        items.forEach((item, index) => {
            if (index >= 3) item.classList.add('hidden');
        });
        button.innerHTML = `
            Lihat semua ${totalCount} 
            <svg class="w-5 h-5 ml-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        `;
    }
}

// Animasi smooth scroll saat klik lihat semua
document.querySelectorAll('[onclick^="toggleSeeMore"]').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const gridClass = this.getAttribute('onclick').match(/'([^']+)'/)[1];
        setTimeout(() => {
            document.querySelector(`.${gridClass}`).scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }, 100);
    });
});
</script>
@endpush

@endsection