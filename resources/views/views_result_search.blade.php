@extends('Layout.Layout')
@section('title', 'Hasil Pencarian')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Filter Aktif -->
        @if($keyword || request()->jurusan || request()->keahlian || request()->angkatan || request()->type)
            <div class="mb-8 bg-white dark:bg-gray-800 dark:border-gray-900 rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-50" data-translate="filter_aktif"
                        data-translate-page="result_search">Filter aktif:</span>

                    @if($keyword)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Pencarian: "{{ $keyword }}"
                        </span>
                    @endif

                    @if(request()->jurusan && $results->isNotEmpty())
                        @php
                            $jurusanItem = $results->first(fn($item) => isset($item->jurusan) && $item->jurusan->id_jurusan == request()->jurusan);
                        @endphp
                        @if($jurusanItem)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                                </svg>
                                Jurusan: {{ $jurusanItem->jurusan->nama_jurusan }}
                            </span>
                        @endif
                    @endif

                    @if(request()->keahlian && $results->isNotEmpty())
                        @php
                            $keahlianItem = $results->first(fn($item) => isset($item->keahlian) && $item->keahlian->id_keahlian == request()->keahlian);
                        @endphp
                        @if($keahlianItem)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                                Keahlian: {{ $keahlianItem->keahlian->nama_keahlian }}
                            </span>
                        @endif
                    @endif

                    <!-- 3 Pilihan Tipe -->
                    <div class="flex gap-2">
                        <a href="{{ route('search', array_merge(request()->query(), ['type' => 'mahasiswa'])) }}"
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ request()->type === 'mahasiswa' ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-800 hover:bg-blue-200' }}">
                            Mahasiswa
                        </a>
                        <a href="{{ route('search', array_merge(request()->query(), ['type' => 'project'])) }}"
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ request()->type === 'project' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
                            Project
                        </a>
                        <a href="{{ route('search', array_merge(request()->query(), ['type' => 'sertifikat'])) }}"
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ request()->type === 'sertifikat' ? 'bg-amber-600 text-white' : 'bg-amber-100 text-amber-800 hover:bg-amber-200' }}">
                            Sertifikat
                        </a>
                    </div>

                    <a href="{{ route('search') }}"
                        class="ml-auto inline-flex items-center px-3 py-1 text-sm text-gray-600 dark:text-gray-300 dark:hover:text-gray-400 hover:text-gray-900">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span data-translate="clr_filter" data-translate-page="result_search">Hapus semua filter</span>
                    </a>
                </div>
            </div>
        @endif

        @if($hasResults)
            <!-- Mahasiswa -->
            @if($mahasiswa->count() > 0)
                <div class="mb-12" id="mahasiswa-section" data-pagination-group="mahasiswa">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <span class="inline-flex px-4 py-2 rounded-full bg-blue-100 text-blue-800 font-medium text-base">
                                <span data-translate="mhs" data-translate-page="result_search">Mahasiswa</span>
                                <span>({{ $mahasiswa->total() }})</span>
                            </span>
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($mahasiswa as $item)
                            <a href="{{ route('portfolio.show', $item) }}"
                                class="block h-full group focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-xl">
                                <div
                                    class="bg-white dark:bg-gray-900 dark:border-gray-900 rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full group-hover:border-indigo-300 group-hover:ring-1 group-hover:ring-indigo-200">
                                    <div class="p-6 flex flex-col flex-1">
                                        <!-- Foto + Nama + Badge -->
                                        <div class="flex items-start gap-4 mb-4">
                                            <div class="flex-shrink-0">
                                                @if($item->photo_profile)
                                                    <img src="{{ asset('storage/' . $item->photo_profile) }}"
                                                        class="w-14 h-14 rounded-full object-cover border-2 border-gray-200 shadow-sm transition-transform group-hover:scale-105"
                                                        alt="{{ $item->nama_mahasiswa ?? 'Profil' }}">
                                                @else
                                                    <div
                                                        class="w-14 h-14 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-sm transition-transform group-hover:scale-105">
                                                        {{ strtoupper(substr($item->nama_mahasiswa ?? 'U', 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex justify-between items-start gap-2">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-50 group-hover:text-indigo-700 transition-colors truncate max-w-[150px]"
                                                        title="{{ $item->nama_mahasiswa ?? 'Nama tidak tersedia' }}">
                                                        {{ $item->nama_mahasiswa ?? 'Nama tidak tersedia' }}
                                                    </h3>
                                                    <span
                                                        class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 flex-shrink-0">
                                                        Mahasiswa
                                                    </span>
                                                </div>
                                                <p
                                                    class="mt-1 text-sm text-gray-600 dark:text-gray-100 flex items-center gap-1 truncate">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-200 flex-shrink-0" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="truncate"
                                                        title="{{ $item->email ?? 'Email tidak tersedia' }}">{{ $item->email ?? 'Email tidak tersedia' }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <!-- Jurusan, Keahlian & Angkatan -->
                                        <div class="flex flex-wrap gap-2 mb-5 min-h-[60px]">
                                            @if($item->angkatan)
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                                    <svg class="w-3.5 h-3.5 mr-1 flex-shrink-0" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="truncate max-w-[100px]"
                                                        title="Angkatan {{ $item->angkatan->tahun_angkatan ?? $item->angkatan->nama_angkatan ?? $item->angkatan }}">
                                                        Angkatan
                                                        {{ $item->angkatan->tahun_angkatan ?? $item->angkatan->nama_angkatan ?? $item->angkatan }}
                                                    </span>
                                                </span>
                                            @endif
                                            @if($item->jurusan)
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                                    <svg class="w-3.5 h-3.5 mr-1 flex-shrink-0" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                                                    </svg>
                                                    <span class="truncate max-w-[120px]" title="{{ $item->jurusan->nama_jurusan }}">
                                                        {{ $item->jurusan->nama_jurusan }}
                                                    </span>
                                                </span>
                                            @endif
                                            @if($item->keahlian)
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700">
                                                    <svg class="w-3.5 h-3.5 mr-1 flex-shrink-0" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                    </svg>
                                                    <span class="truncate max-w-[120px]" title="{{ $item->keahlian->nama_keahlian }}">
                                                        {{ $item->keahlian->nama_keahlian }}
                                                    </span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-auto">
                                            <div class="text-sm text-gray-600 flex justify-between border-t pt-4">
                                                <span class="dark:text-gray-50"><strong
                                                        class="text-gray-900 dark:text-gray-50">{{ $item->projects_count ?? 0 }}</strong>
                                                    Project</span>
                                                <span class="dark:text-gray-50"><strong
                                                        class="text-gray-900 dark:text-gray-50">{{ $item->sertifikats_count ?? 0 }}</strong>
                                                    <span data-translate="sertifikat"
                                                        data-translate-page="result_search">Sertifikat</span></span>
                                                <span class="dark:text-gray-50"><strong
                                                        class="text-gray-900 dark:text-gray-50">{{ $item->learning_count ?? 0 }}</strong>
                                                    Learning</span>
                                            </div>
                                            <div class="mt-3 text-right">
                                                <span data-translate="prtfl_lengkap" data-translate-page="result_search"
                                                    class="text-sm font-medium text-indigo-600 group-hover:text-indigo-800 transition-colors flex items-center justify-end gap-1">
                                                    Lihat portfolio lengkap →
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination Mahasiswa -->
                    <div class="mt-8">
                        {{ $mahasiswa->render('vendor.pagination.custom_ajax', ['groupName' => 'mahasiswa']) }}
                    </div>
                </div>
            @endif

            <!-- Separator Project -->
            @if($projects->count() > 0)
                <hr class="my-12 border-gray-200">
            @endif

            <!-- Project -->
            @if($projects->count() > 0)
                <div class="mb-12" id="project-section" data-pagination-group="project">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <span class="inline-flex px-4 py-2 rounded-full bg-green-100 text-green-800 font-medium text-base">
                                Project ({{ $projects->total() }})
                            </span>
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($projects as $item)
                            <div
                                class="bg-white dark:bg-gray-900 rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full border-t-4 border-green-500 project-item">
                                <div class="p-6 flex flex-col flex-1">
                                    <a href="{{ route('project.show', $item->id) }}" class="block flex-1">
                                        <span
                                            class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-3 w-fit">
                                            Project
                                        </span>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2 min-h-[56px] hover:text-green-600 transition"
                                            title="{{ $item->nama_project ?? 'Project Tanpa Judul' }}">
                                            {{ $item->nama_project ?? 'Project Tanpa Judul' }}
                                        </h3>
                                    </a>

                                    @if($item->mahasiswa)
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                                            <span data-translate="oleh" data-translate-page="result_search">Oleh</span>
                                            <a href="{{ route('portfolio.show', $item->mahasiswa->id) }}"
                                                class="font-semibold hover:text-green-600 transition truncate inline-block max-w-[150px]"
                                                title="{{ $item->mahasiswa->nama_mahasiswa ?? '—' }}">
                                                {{ $item->mahasiswa->nama_mahasiswa ?? '—' }}
                                            </a>
                                            @if($item->mahasiswa->angkatan)
                                                <span
                                                    class="inline-flex ml-2 px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-700 whitespace-nowrap">
                                                    Angkatan
                                                    {{ $item->mahasiswa->angkatan->tahun_angkatan ?? $item->mahasiswa->angkatan->nama_angkatan ?? $item->mahasiswa->angkatan }}
                                                </span>
                                            @endif
                                        </p>
                                    @endif

                                    <p class="text-sm text-gray-500 dark:text-gray-50 mb-4 flex items-center gap-2 min-h-[40px]">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="truncate"
                                            title="{{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') : '—' }} @if($item->tanggal_akhir) - {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }} @else - Sekarang @endif">
                                            {{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') : '—' }}
                                            @if($item->tanggal_akhir)
                                                - {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }}
                                            @else
                                                - Sekarang
                                            @endif
                                        </span>
                                    </p>

                                    <div class="mt-auto">
                                        @if($item->link_project)
                                            <a href="{{ $item->link_project }}" target="_blank" rel="noopener noreferrer"
                                                class="inline-flex items-center justify-center w-full px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                                                <span data-translate="see_project" data-translate-page="result_search">Lihat Project
                                                    →</span>
                                            </a>
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-50 italic text-center py-2.5"
                                                data-translate="empty_link" data-translate-page="result_search">Tidak ada link project</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination Project -->
                    <div class="mt-8">
                        {{ $projects->render('vendor.pagination.custom_ajax', ['groupName' => 'project']) }}
                    </div>
                </div>
            @endif

            <!-- Separator Sertifikat -->
            @if($sertifikats->count() > 0)
                <hr class="my-12 border-gray-200">
            @endif

            <!-- Sertifikat -->
            @if($sertifikats->count() > 0)
                <div class="mb-12" id="sertifikat-section" data-pagination-group="sertifikat">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-3">
                            <span class="inline-flex px-4 py-2 rounded-full bg-amber-100 text-amber-800 font-medium text-base">
                                Sertifikat ({{ $sertifikats->total() }})
                            </span>
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($sertifikats as $item)
                            <div
                                class="bg-white dark:bg-gray-900 dark:border-gray-900 rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full border-t-4 border-amber-500 sertifikat-item">
                                <div class="p-6 flex flex-col flex-1">
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 mb-3 w-fit">
                                        Sertifikat
                                    </span>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2 min-h-[56px]"
                                        title="{{ $item->nama_sertifikat ?? 'Sertifikat Tanpa Judul' }}">
                                        {{ $item->nama_sertifikat ?? 'Sertifikat Tanpa Judul' }}
                                    </h3>
                                    @if($item->mahasiswa)
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                                            <span data-translate="oleh" data-translate-page="result_search"></span> <strong
                                                class="truncate inline-block max-w-[150px]"
                                                title="{{ $item->mahasiswa->nama_mahasiswa ?? '—' }}">{{ $item->mahasiswa->nama_mahasiswa ?? '—' }}</strong>
                                            @if($item->mahasiswa->angkatan)
                                                <span
                                                    class="inline-flex ml-2 px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-700 whitespace-nowrap">
                                                    Angkatan
                                                    {{ $item->mahasiswa->angkatan->tahun_angkatan ?? $item->mahasiswa->angkatan->nama_angkatan ?? $item->mahasiswa->angkatan }}
                                                </span>
                                            @endif
                                        </p>
                                    @endif
                                    <div class="space-y-2 mb-4 min-h-[80px]">
                                        <div class="flex items-center text-sm text-gray-700 dark:text-gray-200">
                                            <svg class="w-4 h-4 mr-2 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                            </svg>
                                            <span class="font-medium dark:text-gray-100 flex-shrink-0">Penerbit: </span>
                                            <span class="truncate"
                                                title="{{ $item->lembaga_penerbit ?? 'Tidak diketahui' }}">{{ $item->lembaga_penerbit ?? 'Tidak diketahui' }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-700 dark:text-gray-200">
                                            <svg class="w-4 h-4 mr-2 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="font-medium dark:text-gray-100 flex-shrink-0">Terbit: </span>
                                            <span class="truncate"
                                                title="{{ $item->tanggal_terbit ? \Carbon\Carbon::parse($item->tanggal_terbit)->format('d M Y') : '—' }}">{{ $item->tanggal_terbit ? \Carbon\Carbon::parse($item->tanggal_terbit)->format('d M Y') : '—' }}</span>
                                        </div>
                                    </div>

                                    <div class="mt-auto">
                                        @if($item->link_sertifikat)
                                            <a href="{{ asset('storage/' . $item->link_sertifikat) }}" target="_blank"
                                                rel="noopener noreferrer"
                                                class="inline-flex items-center justify-center w-full px-5 py-2.5 bg-amber-600 text-white font-medium rounded-lg hover:bg-amber-700 transition">
                                                Lihat Sertifikat →
                                            </a>
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-100 italic text-center py-2.5">Tidak ada link
                                                sertifikat</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination Sertifikat -->
                    <div class="mt-8">
                        {{ $sertifikats->render('vendor.pagination.custom_ajax', ['groupName' => 'sertifikat']) }}
                    </div>
                </div>
            @endif

        @else
            <!-- Tidak ada hasil -->
            <div
                class="text-center py-20 bg-white rounded-xl shadow-sm border border-gray-200 dark:border-gray-900 dark:bg-gray-900">
                <svg class="mx-auto h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <h3 class="mt-6 text-2xl font-medium text-gray-900 dark:text-gray-100" data-translate="none"
                    data-translate-page="result_search">Tidak ada hasil ditemukan</h3>
                <p class="mt-3 text-gray-600 dark:text-gray-300 max-w-md mx-auto" data-translate="none_desc"
                    data-translate-page="result_search">

                </p>
                <div class="mt-6 flex justify-center gap-3">
                    <a href="{{ route('search') }}"
                        class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </a>
                </div>
            </div>
        @endif

        @push('scripts')
            <script>
                function toggleSeeMore(gridClass, button, totalCount) {
                    const grid = document.querySelector(`.${gridClass}`);
                    const items = grid.querySelectorAll(`.${gridClass.replace('-grid', '-item')}`);
                    const hidden = Array.from(items).filter(item => item.classList.contains('hidden'));
                    const svg = button.querySelector('svg');

                    if (hidden.length > 0) {
                        hidden.forEach(item => item.classList.remove('hidden'));
                        button.innerHTML = `
                        <span data-translate="sembunyi" data-translate-page="result_search">Sembunyikan</span>
                        <svg class="w-4 h-4 ml-1 transition-transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    `;
                    } else {
                        items.forEach((item, index) => {
                            if (index >= 3) item.classList.add('hidden');
                        });
                        button.innerHTML = `
                        <span data-translate="tampil" data-translate-page="result_search">Lihat semua</span>
                        <svg class="w-4 h-4 ml-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    `;
                    }
                }
            </script>
        @endpush
@endsection