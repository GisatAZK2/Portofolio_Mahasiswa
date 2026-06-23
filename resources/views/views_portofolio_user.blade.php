{{-- ini Portofolio Page --}}
@extends('Layout.Layout')
@section('show_footer', true)
@section('show_up_page', true)

@section('title', ($user->nama_mahasiswa ?? 'Mahasiswa') . ' | Portfolio')

@section('meta')
    @php
        $portfolioTitle = trim($user->nama_mahasiswa ?? 'Mahasiswa');
        $portfolioProdi = $user->jurusan?->nama_jurusan ?? 'Mahasiswa';
        $portfolioAngkatan = $user->angkatan?->nama_angkatan ?? '-';
        $acceptedTambahan = $user->keahlianTambahan->filter(function ($item) {
            return optional($item->pivot)->status_pengajuan === 'Di Terima';
        });
        $portfolioKeahlian = collect([$user->keahlian?->nama_keahlian])
            ->merge($acceptedTambahan->pluck('nama_keahlian')->toArray())
            ->filter()
            ->unique()
            ->values()
            ->all();
        $portfolioKeahlianText = !empty($portfolioKeahlian)
            ? implode(', ', $portfolioKeahlian)
            : 'Belum ada keahlian terdaftar';
        $portfolioDescription = "Portfolio {$portfolioTitle} — {$portfolioProdi}, Angkatan {$portfolioAngkatan}. Keahlian: {$portfolioKeahlianText}.";
        $portfolioImage = $user->photo_profile
            ? asset('storage/' . ltrim($user->photo_profile, '/'))
            : asset('assets/Logo.svg');
        $portfolioUrl = route('portfolio.show', ['user' => $user->username]);
    @endphp

    <meta name="description" content="{{ $portfolioDescription }}">
    <meta property="og:title" content="{{ $portfolioTitle }} | Portfolio">
    <meta property="og:description" content="{{ $portfolioDescription }}">
    <meta property="og:image" content="{{ $portfolioImage }}">
    <meta property="og:image:alt" content="Foto profil {{ $portfolioTitle }} - {{ $portfolioProdi }}">
    <meta property="og:url" content="{{ $portfolioUrl }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $portfolioTitle }} | Portfolio">
    <meta name="twitter:description" content="{{ $portfolioDescription }}">
    <meta name="twitter:image" content="{{ $portfolioImage }}">
@endsection

@section('content')
    <div class="min-h-screen bg-gray-100 dark:bg-gray-800 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                {{-- ===== SIDEBAR KIRI ===== --}}
                <div class="space-y-6 lg:sticky lg:top-2 lg:self-start lg:z-10">

                    {{-- Kartu Profil --}}
                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-900 shadow-sm overflow-hidden">
                        {{-- Background Banner --}}
                        <div class="h-32 relative
                            {{ $user->background_url
                                ? 'bg-cover bg-center'
                                : 'bg-gradient-to-r from-indigo-500 to-indigo-600' }}"
                            @if($user->background_url)
                                style="background-image: url('{{ asset('storage/' . $user->background_url) }}');"
                            @endif>

                            {{-- Logo Overlay --}}
                            <div class="absolute top-2 left-2 flex items-center gap-3">
                                @php
                                    $currentYear = date('Y');
                                    $tahunKeluar = $user->angkatan->tahun_keluar ?? null;
                                    $isAlumni = $tahunKeluar && $currentYear > $tahunKeluar;
                                @endphp

                                @if($isAlumni)
                                    <div class="flex items-center gap-1 bg-white/90 dark:bg-gray-800/90 rounded-lg px-2 py-1">
                                        <img src="{{ asset('assets/Alumni.svg') }}" alt="Alumni Logo"
                                            class="w-7 h-7 md:w-8 md:h-8 lg:w-9 lg:h-9 drop-shadow-lg transition-all duration-200">
                                        <span class="text-[10px] font-semibold text-gray-800/30 dark:text-gray-50 whitespace-nowrap">
                                            Alumni Polmind
                                        </span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1 bg-white/90 dark:bg-gray-800/90 rounded-lg px-2 py-1">
                                        <img src="{{ asset('assets/Logo.svg') }}" alt="Logo Politeknik Mitra Industri"
                                            class="w-5 h-5 drop-shadow-lg">
                                        <span class="text-[10px] font-semibold text-gray-800 dark:text-gray-50 whitespace-nowrap">
                                            Politeknik Mitra Industri
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Avatar --}}
                        <div class="px-5 pb-6 relative">
                            <div class="flex justify-between items-start gap-3">
                                <div class="-mt-12 mb-3 relative">
                                    <div class="w-24 h-24 rounded-full border-4 border-white bg-white dark:border-gray-900 dark:bg-gray-900 shadow-lg overflow-hidden">
                                        @if($user->photo_profile)
                                            <img id="logo-zoom"
                                                src="{{ asset('storage/' . ltrim($user->photo_profile, '/')) }}"
                                                alt="{{ $user->nama_mahasiswa ?? 'Mahasiswa' }}"
                                                class="cursor-pointer w-full h-full object-cover"
                                                onerror="this.onerror=null; this.parentNode.innerHTML='<div class=\'w-full h-full bg-indigo-600 flex items-center justify-center text-white text-3xl font-bold\'>{{ strtoupper(mb_substr(trim($user->nama_mahasiswa ?? 'M'), 0, 1)) }}</div>'">
                                        @else
                                            <div class="w-full h-full bg-indigo-600 flex items-center justify-center text-white text-3xl font-bold">
                                                {{ strtoupper(mb_substr(trim($user->nama_mahasiswa ?? 'M'), 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Share Button --}}
                                <div class="flex flex-col gap-2 pt-1">
                                    @php
                                        $shareUrl = route('portfolio.show', ['user' => $user->username]);
                                    @endphp
                                    <div class="relative group">
                                        <button class="flex items-center justify-center gap-2 px-3 py-2 bg-indigo-600/50 hover:bg-indigo-700/50 text-white rounded-lg text-sm font-medium transition-colors"
                                            onclick="toggleShareMenu()">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                            </svg>
                                            <span class="hidden sm:inline" data-translate="share" data-translate-page="portofolio_user">Share</span>
                                        </button>
                                        <div id="shareMenu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 hidden z-50">
                                            <button onclick="copyLink('{{ $shareUrl }}')" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 first:rounded-t-lg flex items-center gap-2 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.658 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                                <span data-translate="copy_link" data-translate-page="portofolio_user">Copy Link</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="mt-2 mb-1">
                                <a href="mailto:{{ $user->email ?? '#' }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline truncate block" title="{{ $user->email ?? 'Email tidak tersedia' }}">
                                    {{ $user->email ?? 'Email tidak tersedia' }}
                                </a>
                            </div>

                            <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                {{ trim($user->nama_mahasiswa ?? 'Mahasiswa') }}
                            </h1>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                <span data-translate="prodi" data-translate-page="portofolio_user"> Prodi :</span> {{ $user->jurusan?->nama_jurusan ?? 'Mahasiswa' }}
                            </p>

                            <div class="text-xs text-gray-600 dark:text-gray-300 mt-3 flex flex-col gap-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                    </svg>
                                    <span data-translate="agkt" data-translate-page="portofolio_user">Cohort :</span>
                                    <span class="font-medium">{{ $user->angkatan?->nama_angkatan ?? '-' }}</span>
                                </span>
                            </div>

                            <p class="mt-4 text-sm text-gray-700 dark:text-gray-200 leading-relaxed">
                                @php $desc = $user->translated('deskripsi') @endphp
                                {{ !empty(trim($desc)) ? $desc : 'Tidak ada deskripsi.' }}
                            </p>

                            @if($user->video_url)
                                <div class="mt-4">
                                    <div class="relative w-full h-full rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                                        <iframe
                                            class="w-full h-full"
                                            src="{{ str_replace('watch?v=', 'embed/', str_replace('youtu.be/', 'youtube.com/embed/', $user->video_url)) }}"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- END SIDEBAR --}}

                {{-- ===== KONTEN UTAMA ===== --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- ==================== POSTINGAN ==================== --}}
                    <div id="postingan-section" class="bg-white rounded-2xl border border-gray-200 dark:bg-gray-900 dark:border-gray-900 shadow-sm p-5 lg:p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2" />
                                </svg>
                                <span data-translate="postings" data-translate-page="portofolio_user">Postingannya</span>
                            </h3>
                            <span class="text-sm text-purple-600 dark:text-purple-400 font-medium">
                                {{ $postingans->total() ?? 0 }} <span data-translate="postings_count" data-translate-page="portofolio_user">postingan</span>
                            </span>
                        </div>

                        @if($postingans->isEmpty())
                            <div class="text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-xl">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-3 text-gray-500 dark:text-gray-400" data-translate="post_empty" data-translate-page="portofolio_user">
                                    Belum ada postingan yang dibuat.
                                </p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 gap-6">
                                @foreach($postingans as $post)
                                    @php
                                        $content = $post->translated('content') ?? [];
                                        $title = '';
                                        $deskripsi = '';
                                        $items = [];

                                        if (is_array($content)) {
                                            foreach ($content as $item) {
                                                if (isset($item['type']) && $item['type'] === 'title') {
                                                    $title = $item['content'] ?? '';
                                                } elseif (isset($item['type']) && $item['type'] === 'description') {
                                                    $deskripsi = $item['content'] ?? '';
                                                } else {
                                                    $items[] = $item;
                                                }
                                            }
                                        }

                                        $firstImage = null;
                                        foreach ($items as $item) {
                                            if (isset($item['type']) && $item['type'] === 'image' && !empty($item['content'])) {
                                                $firstImage = $item['content'];
                                                break;
                                            }
                                        }
                                    @endphp

                                    <div class="border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden hover:shadow-md transition-all duration-200 group flex flex-col">

                                        {{-- Gambar hanya ditampilkan jika ada --}}
                                        @if($firstImage)
                                            <div class="h-48 bg-gray-100 dark:bg-gray-800 relative overflow-hidden flex-shrink-0">
                                                <img src="{{ asset('storage/' . $firstImage) }}"
                                                     alt="Preview postingan"
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                     onerror="this.parentElement.remove()">
                                            </div>
                                        @endif

                                        <div class="p-5 flex flex-col flex-1">
                                            @if($title)
                                                <h4 class="font-semibold text-gray-900 dark:text-gray-100 line-clamp-2 mb-2 group-hover:text-purple-600 transition-colors">
                                                    {{ $title }}
                                                </h4>
                                            @endif

                                            @if($deskripsi)
                                                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 mb-4 flex-1">
                                                    {{ Str::limit($deskripsi, 130) }}
                                                </p>
                                            @endif

                                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mt-auto">
                                                <span>{{ $post->created_at?->format('d M Y') }}</span>
                                                <a href="{{ route('postingan.show', ['id' => $post->id_postingan]) }}"
                                                   class="text-purple-600 hover:text-purple-700 dark:text-purple-400 font-medium flex items-center gap-1">
                                                    <span data-translate="read_more" data-translate-page="portofolio_user">Baca selengkapnya</span>
                                                    <span class="text-lg leading-none">→</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-8 flex justify-center">
                                {{ $postingans->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>

                    {{-- ==================== PENDIDIKAN ==================== --}}
                    @php
                        $pendidikanList = $user->pendidikan ?? [];
                        $jenjangIcons = [
                            'S1' => '🎓', 'S2' => '🎓', 'S3' => '🎓',
                            'D1' => '📚', 'D2' => '📚', 'D3' => '📚', 'D4' => '📚',
                            'SMA/SMK' => '🏫', 'SMP' => '🏫', 'SD' => '🏫',
                            'Kursus/Pelatihan' => '📖',
                        ];
                    @endphp

                    <div class="bg-white rounded-2xl border border-gray-200 dark:bg-gray-900 dark:border-gray-900 shadow-sm p-5 lg:p-6">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.083 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                </svg>
                                <span data-translate="edu_title" data-translate-page="portofolio_user">Pendidikan</span>
                            </h3>
                            @if(!empty($pendidikanList))
                                <span class="text-sm text-blue-600 dark:text-blue-400 font-medium">
                                    {{ count($pendidikanList) }} <span data-translate="edu_count" data-translate-page="portofolio_user">riwayat</span>
                                </span>
                            @endif
                        </div>

                        @if(!empty($pendidikanList))
                            <div class="relative">

                                <div class="space-y-0">
                                    @foreach($pendidikanList as $index => $pend)
                                        @php
                                            $isLast = $index === count($pendidikanList) - 1;
                                            $masihKuliah = !empty($pend['masih_kuliah']) && $pend['masih_kuliah'];
                                            $icon = $jenjangIcons[$pend['jenjang'] ?? ''] ?? '🏛️';
                                        @endphp

                                        <div class="relative flex gap-4 sm:gap-5 pb-{{ $isLast ? '0' : '6' }}">
                                            {{-- Timeline dot --}}
                                            <div class="flex-shrink-0 relative z-10">
                                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-blue-50 dark:bg-blue-900/20 border-2 {{ $masihKuliah ? 'border-blue-500 dark:border-blue-400' : 'border-gray-300 dark:border-gray-600' }} flex items-center justify-center shadow-sm text-xl">
                                                    {{ $icon }}
                                                </div>
                                            </div>

                                            {{-- Content --}}
                                            <div class="flex-1 min-w-0 pb-{{ $isLast ? '2' : '6' }}">
                                                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-colors duration-200">
                                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                                                        <div class="flex-1 min-w-0">
                                                            {{-- Nama sekolah + badge --}}
                                                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                                                <h4 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-gray-100 truncate">
                                                                    {{ $pend['nama_sekolah'] ?? '-' }}
                                                                </h4>
                                                                @if($masihKuliah)
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 whitespace-nowrap"
                                                                        data-translate="edu_active" data-translate-page="portofolio_user">
                                                                        Aktif
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            {{-- Jenjang + Jurusan --}}
                                                            <div class="flex flex-wrap items-center gap-1.5 mb-1.5">
                                                                @if(!empty($pend['jenjang']))
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300">
                                                                        {{ $pend['jenjang'] }}
                                                                    </span>
                                                                @endif
                                                                @if(!empty($pend['jurusan_sek']))
                                                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                                                        {{ $pend['jurusan_sek'] }}
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            {{-- Periode --}}
                                                            <div class="flex flex-wrap items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                                <span data-translate="edu_enter" data-translate-page="portofolio_user">Masuk</span>:
                                                                <span>{{ !empty($pend['tahun_masuk']) ? \Carbon\Carbon::parse($pend['tahun_masuk'])->translatedFormat('F Y') : '-' }}</span>
                                                                <span>—</span>
                                                                @if($masihKuliah)
                                                                    <span class="text-blue-600 dark:text-blue-400 font-medium"
                                                                        data-translate="edu_now" data-translate-page="portofolio_user">
                                                                        Sekarang
                                                                    </span>
                                                                @else
                                                                    <span>{{ !empty($pend['tahun_lulus']) ? \Carbon\Carbon::parse($pend['tahun_lulus'])->translatedFormat('F Y') : '-' }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-10 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.083 0 01.665-6.479L12 14z" />
                                </svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic"
                                    data-translate="edu_empty" data-translate-page="portofolio_user">
                                    Belum ada riwayat pendidikan
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- ==================== PENGALAMAN KERJA ==================== --}}
                    @php
                        $pengalamanList = $user->pengalaman_kerja ?? [];
                    @endphp

                    <div class="bg-white rounded-2xl border border-gray-200 dark:bg-gray-900 dark:border-gray-900 shadow-sm p-5 lg:p-6">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span data-translate="exp_title" data-translate-page="portofolio_user">Pengalaman</span>
                            </h3>
                            @if(!empty($pengalamanList))
                                <span class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                                    {{ count($pengalamanList) }} <span data-translate="exp_count" data-translate-page="portofolio_user">pengalaman</span>
                                </span>
                            @endif
                        </div>

                        @if(!empty($pengalamanList))
                            <div class="relative">

                                <div class="space-y-0">
                                    @foreach($pengalamanList as $index => $pkj)
                                        @php
                                            $isLast = $index === count($pengalamanList) - 1;
                                            $masihBekerja = !empty($pkj['masih_bekerja']) && $pkj['masih_bekerja'];

                                            $tahunMulai = $pkj['tahun_mulai'] ?? null;
                                            $tahunAkhir = $masihBekerja ? date('Y') : ($pkj['tahun_akhir'] ?? null);

                                            $selisihTahun = null;
                                            if ($tahunMulai && $tahunAkhir) {
                                                $selisihTahun = (int)$tahunAkhir - (int)$tahunMulai;
                                            }
                                        @endphp

                                        <div class="relative flex gap-4 sm:gap-5 pb-{{ $isLast ? '0' : '6' }}">
                                            {{-- Timeline dot --}}
                                            <div class="flex-shrink-0 relative z-10">
                                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-emerald-50 dark:bg-emerald-900/20 border-2 {{ $masihBekerja ? 'border-emerald-500 dark:border-emerald-400' : 'border-gray-300 dark:border-gray-600' }} flex items-center justify-center shadow-sm">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $masihBekerja ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            </div>

                                            {{-- Content --}}
                                            <div class="flex-1 min-w-0 pb-{{ $isLast ? '2' : '6' }}">
                                                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 transition-colors duration-200">
                                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                                                        <div class="flex-1 min-w-0">
                                                            {{-- Nama Perusahaan + badge --}}
                                                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                                                <h4 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-gray-100 truncate">
                                                                    {{ $pkj['nama_pt'] ?? '-' }}
                                                                </h4>
                                                                @if(!empty($pkj['jenis_pekerjaan']))
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 whitespace-nowrap">
                                                                        {{ $pkj['jenis_pekerjaan'] }}
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            {{-- Posisi --}}
                                                            <p class="text-sm font-medium text-emerald-700 dark:text-emerald-400 mb-1.5">
                                                                {{ $pkj['bagian_kerja'] ?? '-' }}
                                                            </p>

                                                            {{-- Deskripsi --}}
                                                            @if(!empty($pkj['deskripsi']))
                                                                <p class="text-xs text-gray-600 dark:text-gray-300 mb-2">
                                                                    {{ $pkj['deskripsi'] }}
                                                                </p>
                                                            @else
                                                                <p class="text-xs text-gray-400 dark:text-gray-500 mb-2">-</p>
                                                            @endif

                                                            {{-- Periode & durasi --}}
                                                            <div class="flex flex-wrap items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                                <span>{{ $pkj['tahun_mulai'] ?? '-' }}</span>
                                                                <span>—</span>
                                                                @if($masihBekerja)
                                                                    <span class="text-emerald-600 dark:text-emerald-400 font-medium"
                                                                        data-translate="exp_now" data-translate-page="portofolio_user">
                                                                        Saat ini
                                                                    </span>
                                                                @else
                                                                    <span>{{ $pkj['tahun_akhir'] ?? '-' }}</span>
                                                                @endif

                                                            </div>
                                                        </div>

                                                        {{-- Sertifikat link --}}
                                                        @if(!empty($pkj['sertifikat_pendukung']))
                                                            <div class="flex-shrink-0">
                                                                <a href="{{ asset('storage/' . $pkj['sertifikat_pendukung']) }}" target="_blank" rel="noopener noreferrer"
                                                                    class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                                    </svg>
                                                                    <span data-translate="exp_sertif" data-translate-page="portofolio_user">Sertifikat</span>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-10 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic"
                                    data-translate="exp_empty" data-translate-page="portofolio_user">
                                    Belum ada pengalaman kerja
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- Keahlian --}}
                    <div class="bg-white rounded-2xl border border-gray-200 dark:border-gray-900 dark:bg-gray-900 shadow-sm p-5">
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-4" data-translate="khl" data-translate-page="portofolio_user">Keahlian</h3>

                        @if($user->keahlian)
                            <div class="mb-5">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" data-translate="Utama" data-translate-page="portofolio_user">Utama:</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 text-xs font-medium bg-red-50 text-red-700 rounded-full border border-red-100">
                                        {{ $user->keahlian->nama_keahlian }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        @php
                            $acceptedTambahan = $user->keahlianTambahan->filter(function ($item) {
                                return optional($item->pivot)->status_pengajuan === 'Di Terima';
                            });
                        @endphp

                        @if($acceptedTambahan->isNotEmpty())
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" data-translate="additional_skills_label" data-translate-page="portofolio_user">Tambahan:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($acceptedTambahan as $kt)
                                        <span class="px-3 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-full border border-blue-100">
                                            {{ $kt->nama_keahlian }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!$user->keahlian && $acceptedTambahan->isEmpty())
                            <p class="text-sm text-gray-500 italic text-center py-2" data-translate="empty_skills" data-translate-page="portofolio_user">Belum ada keahlian ditambahkan</p>
                        @endif
                    </div>

                    {{-- ==================== PROJECTS ==================== --}}
                    <div id="project-section" class="bg-white rounded-2xl border border-gray-200 dark:bg-gray-900 dark:border-gray-900 shadow-sm p-5 lg:p-6">

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-5">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-3 sm:mb-0" data-translate="pjt" data-translate-page="portofolio_user">Projects</h3>

                            <div class="flex space-x-1 bg-gray-100 dark:bg-gray-800 p-1 rounded-lg">
                                <a href="{{ request()->fullUrlWithQuery(['project_tab' => 'completed']) }}"
                                   class="px-4 py-2 text-sm font-medium rounded-md transition-all
                                          {{ $projectTab == 'completed'
                                             ? 'bg-white dark:bg-gray-900 text-indigo-600 shadow-sm'
                                             : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span data-translate="done" data-translate-page="portofolio_user">Selesai</span>
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['project_tab' => 'now']) }}"
                                   class="px-4 py-2 text-sm font-medium rounded-md transition-all
                                          {{ $projectTab == 'now'
                                             ? 'bg-white dark:bg-gray-900 text-indigo-600 shadow-sm'
                                             : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span data-translate="ongoing" data-translate-page="portofolio_user">Sedang Dikerjakan</span>
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['project_tab' => 'upcoming']) }}"
                                   class="px-4 py-2 text-sm font-medium rounded-md transition-all
                                          {{ $projectTab == 'upcoming'
                                             ? 'bg-white dark:bg-gray-900 text-indigo-600 shadow-sm'
                                             : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span data-translate="soon" data-translate-page="portofolio_user">Akan Datang</span>
                                </a>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                @switch($projectTab)
                                    @case('completed')
                                        <span data-translate="show_done" data-translate-page="portofolio_user">Menampilkan proyek yang telah selesai</span>
                                        @break
                                    @case('now')
                                        <span data-translate="show_ongoing" data-translate-page="portofolio_user">Menampilkan proyek yang sedang dikerjakan</span>
                                        @break
                                    @case('upcoming')
                                        <span data-translate="show_soon" data-translate-page="portofolio_user">Menampilkan proyek yang akan datang</span>
                                        @break
                                    @default
                                        <span data-translate="show_all" data-translate-page="portofolio_user">Menampilkan semua proyek</span>
                                @endswitch
                            </span>
                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                {{ $projects->total() }} <span data-translate="pjt" data-translate-page="portofolio_user">proyek</span>
                            </span>
                        </div>

                        @if($projects->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($projects as $project)
                                    @php
                                        $content = $project->translated('isi_content') ?? [];
                                        $namaProject = $content['nama_project'] ?? 'Tanpa Nama';
                                        $deskripsi = $content['deskripsi'] ?? '';
                                        $linkProject = $content['link_project'] ?? null;
                                        $linkGithub = $content['link_github'] ?? null;
                                        $linkVideo = $content['link_video'] ?? null;

                                        $leader = $project->leader ?? $owner;
                                        $owner = $project->owner;
                                        $isOwnerAndLeaderSame = $owner && $leader && $owner->id === $leader->id;

                                        $userRole = null;
                                        $userRoleBadge = '';

                                        // Badge owner dihapus sesuai permintaan
                                        if ($owner && $owner->id === $user->id) {
                                            $userRole = 'owner';
                                            // tidak ada badge untuk owner
                                        } elseif ($leader && $leader->id === $user->id) {
                                            $userRole = 'leader';
                                            $userRoleBadge = '<span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800" data-translate="leader_badge" data-translate-page="portofolio_user">Leader</span>';
                                        } elseif ($project->members->contains('id', $user->id)) {
                                            $userRole = 'member';
                                            $userRoleBadge = '<span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800" data-translate="member_badge" data-translate-page="portofolio_user">Member</span>';
                                        }

                                        $today = now()->startOfDay();
                                        $mulai = \Carbon\Carbon::parse($project->tanggal_mulai)->startOfDay();
                                        $akhir = $project->tanggal_akhir
                                            ? \Carbon\Carbon::parse($project->tanggal_akhir)->startOfDay()
                                            : null;

                                        if ($akhir && $today->gt($akhir)) {
                                            $projectStatus = 'completed';
                                        } elseif ($today->between($mulai, $akhir ?? $mulai)) {
                                            $projectStatus = 'now';
                                        } elseif ($today->lt($mulai)) {
                                            $projectStatus = 'upcoming';
                                        } else {
                                            $projectStatus = 'unknown';
                                        }
                                    @endphp

                                    <div class="border border-gray-100 dark:border-gray-700 rounded-xl p-5 hover:shadow-md transition">
                                        <div class="flex flex-col space-y-4">

                                            {{-- Nama Project --}}
                                            <div class="flex flex-wrap items-start gap-3">
                                                <div class="flex items-center flex-wrap gap-2">
                                                    <a href="{{ route('project.show',['id' => $project->id]) }}"
                                                       class="text-base font-semibold text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                                        {{ $namaProject }}
                                                    </a>
                                                    {!! $userRoleBadge !!}
                                                </div>
                                            </div>

                                            {{-- Leader --}}
                                            <div class="flex items-center space-x-3 bg-indigo-50 dark:bg-indigo-900/20 p-3 rounded-lg">
                                                <div class="flex-shrink-0">
                                                    @if($leader && $leader->photo_profile)
                                                        <img id="logo-zoom"
                                                            src="{{ asset('storage/' . ltrim($leader->photo_profile, '/')) }}"
                                                            alt="{{ $leader->nama_mahasiswa ?? 'Leader' }}"
                                                            class="cursor-pointer w-12 h-12 rounded-full object-cover border-2 border-indigo-300">
                                                    @else
                                                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center border-2 border-indigo-300">
                                                            <span class="text-indigo-600 font-medium text-lg">
                                                                {{ $leader ? strtoupper(mb_substr(trim($leader->nama_mahasiswa ?? 'L'), 0, 1)) : 'L' }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-0.5">
                                                        <span data-translate="pjt_leader" data-translate-page="portofolio_user">Project Leader</span>
                                                    </p>
                                                    @if($leader)
                                                        <a href="{{ route('portfolio.show', ['user' => $leader->username]) }}"
                                                            class="text-base font-medium text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 truncate block">
                                                            {{ $leader->nama_mahasiswa }}
                                                        </a>
                                                    @else
                                                        <p class="text-base font-medium text-gray-900 dark:text-gray-100 truncate">
                                                            <span data-translate="pjt_noleader" data-translate-page="portofolio_user">Tidak ada leader</span>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Preview Section --}}
                                            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 space-y-3">
                                                @if(!empty($deskripsi))
                                                    <div>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-1" data-translate="pjt_desc" data-translate-page="portofolio_user">Deskripsi</p>
                                                        <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2">{{ $deskripsi }}</p>
                                                    </div>
                                                @endif

                                                @if(!empty($linkVideo))
                                                    <div class="mt-2">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-2" data-translate="pjt_vidprev" data-translate-page="portofolio_user">Pratinjau Video</p>
                                                        <a href="{{ $linkVideo }}" target="_blank" rel="noopener noreferrer" class="block group">
                                                            @php
                                                                $youtubeId = null;
                                                                if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $linkVideo, $matches)) {
                                                                    $youtubeId = $matches[1];
                                                                }
                                                            @endphp
                                                            <div class="relative w-full rounded overflow-hidden bg-gray-900 aspect-video flex items-center justify-center hover:opacity-90 transition">
                                                                @if($youtubeId)
                                                                    <img src="https://img.youtube.com/vi/{{ $youtubeId }}/mqdefault.jpg"
                                                                         alt="Video thumbnail"
                                                                         class="w-full h-full object-cover">
                                                                @else
                                                                    <div class="w-full h-full bg-gray-800"></div>
                                                                @endif
                                                                <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/40 transition">
                                                                    <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center">
                                                                        <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                                            <path d="M8 5v14l11-7z" />
                                                                        </svg>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Anggota Tim --}}
                                            @if($project->members->isNotEmpty())
                                                <a href="{{ route('portfolio.show', ['user' => $project->members->first()->username]) }}">
                                                    <div class="mt-2">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                                            <span data-translate="team" data-translate-page="portofolio_user">Anggota Tim</span> ({{ $project->members->count() }})
                                                        </p>
                                                        <div class="flex flex-wrap gap-3">
                                                            @foreach($project->members->take(5) as $member)
                                                                <div class="flex items-center space-x-2 group relative" title="{{ $member->nama_mahasiswa }}">
                                                                    @if($member->photo_profile)
                                                                        <img id="logo-zoom" src="{{ asset('storage/' . ltrim($member->photo_profile, '/')) }}"
                                                                            alt="{{ $member->nama_mahasiswa }}"
                                                                            class="cursor-pointer w-8 h-8 rounded-full object-cover border-2 border-gray-200 hover:border-indigo-400 transition">
                                                                    @else
                                                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center border-2 border-gray-200 hover:border-indigo-400 transition cursor-pointer">
                                                                            <span class="text-gray-600 font-medium text-xs">
                                                                                {{ strtoupper(mb_substr(trim($member->nama_mahasiswa ?? 'M'), 0, 1)) }}
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                    <span class="text-xs text-gray-700 dark:text-gray-300 max-w-[100px] truncate">
                                                                        {{ $member->nama_mahasiswa }}
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                            @if($project->members->count() > 5)
                                                                <div class="flex items-center">
                                                                    <span class="text-xs text-gray-500 dark:text-gray-400">+{{ $project->members->count() - 5 }} <span data-translate="others" data-translate-page="portofolio_user">lainnya</span></span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </a>
                                            @else
                                                <p class="text-xs text-gray-400 dark:text-gray-500 italic mt-2" data-translate="no_team" data-translate-page="portofolio_user">Belum ada anggota tim</p>
                                            @endif

                                            {{-- Tanggal --}}
                                            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 dark:text-gray-400 mt-3">
                                                <span class="flex items-center">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span data-translate="start" data-translate-page="portofolio_user">Mulai</span>: {{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d M Y') }}
                                                </span>
                                                @if($project->tanggal_akhir)
                                                    <span class="flex items-center">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        <span data-translate="end" data-translate-page="portofolio_user">Selesai</span>: {{ \Carbon\Carbon::parse($project->tanggal_akhir)->format('d M Y') }}
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Created By --}}
                                            @if($owner)
                                                <div class="flex items-center space-x-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                                    <div class="flex-shrink-0">
                                                        @if($owner->photo_profile)
                                                            <img id="logo-zoom"
                                                                src="{{ asset('storage/' . ltrim($owner->photo_profile, '/')) }}"
                                                                alt="{{ $owner->nama_mahasiswa ?? 'Created By' }}"
                                                                class="cursor-pointer w-8 h-8 rounded-full object-cover border-2 border-gray-200">
                                                        @else
                                                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center border-2 border-gray-200">
                                                                <span class="text-amber-600 font-medium text-xs">
                                                                    {{ strtoupper(mb_substr(trim($owner->nama_mahasiswa ?? 'C'), 0, 1)) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            <span data-translate="pjt_created_by" data-translate-page="portofolio_user">Created By</span>
                                                        </p>
                                                        <a href="{{ route('portfolio.show', ['user' => $owner->username]) }}"
                                                            class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 truncate block">
                                                            {{ $owner->nama_mahasiswa }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                {{ $projects->appends(['project_tab' => $projectTab])->render('vendor.pagination.custom_ajax', ['groupName' => 'portfolio_projects']) }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    @switch($projectTab)
                                        @case('now') <span data-translate="empty_now" data-translate-page="portofolio_user">Belum ada proyek yang sedang dikerjakan</span> @break
                                        @case('upcoming') <span data-translate="empty_upcoming" data-translate-page="portofolio_user">Belum ada proyek yang akan datang</span> @break
                                        @case('completed') <span data-translate="empty_completed" data-translate-page="portofolio_user">Belum ada proyek yang selesai</span> @break
                                        @default <span data-translate="empty_projects" data-translate-page="portofolio_user">Belum ada proyek</span>
                                    @endswitch
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- ==================== LEARNING CORNERS ==================== --}}
                    <div class="bg-white rounded-2xl border border-gray-200 dark:bg-gray-900 dark:border-gray-900 shadow-sm p-5 lg:p-6">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100" data-translate="lrn" data-translate-page="portofolio_user">Learning Corners</h3>
                            <span class="text-sm text-indigo-600 dark:text-indigo-400">{{ $user->learning_corners->count() }} <span data-translate="note" data-translate-page="portofolio_user">catatan</span></span>
                        </div>

                        @if($user->learning_corners->isNotEmpty())
                            <div class="space-y-6">
                                @foreach($user->learning_corners as $entry)
                                    <div class="border border-gray-100 dark:border-gray-700 dark:text-gray-100 rounded-xl p-5 hover:shadow-md transition">
                                        <div class="space-y-4">
                                            @php
                                                $rawContent = $entry->translated('content') ?? [];
                                                if (is_string($rawContent) && !empty($rawContent)) {
                                                    $items = json_decode($rawContent, true) ?? [];
                                                } else {
                                                    $items = is_array($rawContent) ? $rawContent : [];
                                                }
                                            @endphp

                                            @if(!empty($items) && is_array($items))
                                                @foreach($items as $item)
                                                    @if(($item['type'] ?? '') === 'title')
                                                        <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                                                            {{ $item['content'] ?? 'Judul tidak tersedia' }}
                                                        </h4>
                                                    @elseif(($item['type'] ?? '') === 'text')
                                                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                                            {{ $item['content'] ?? '' }}
                                                        </p>
                                                    @elseif(($item['type'] ?? '') === 'image' && !empty($item['content']))
                                                        <div class="my-3">
                                                            <img id="logo-zoom" src="{{ asset('storage/' . $item['content']) }}" alt="Gambar learning corner"
                                                                class="cursor-pointer w-30 rounded-lg shadow-sm object-cover max-h-[500px]" loading="lazy"
                                                                onerror="this.src='https://st4.depositphotos.com/17828278/24401/v/450/depositphotos_244011872-stock-illustration-image-vector-symbol-missing-available.jpg'">
                                                        </div>
                                                    @elseif(($item['type'] ?? '') === 'link' && !empty($item['content']))
                                                        <a href="{{ $item['content'] }}" target="_blank" rel="noopener noreferrer"
                                                            class="inline-flex items-start text-sm text-blue-600 hover:text-blue-800 hover:underline break-all">
                                                            <svg class="w-4 h-4 mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                            </svg>
                                                            <span class="break-all">{{ $item['text'] ?? $item['content'] }}</span>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            @else
                                                <p class="text-sm text-gray-600 italic" data-translate="empty_content" data-translate-page="portofolio_user">
                                                    Tidak ada konten yang dapat ditampilkan
                                                </p>
                                            @endif

                                            @if($entry->project)
                                                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        <span data-translate="lrn_origin" data-translate-page="portofolio_user">Dari project:</span>
                                                        <a href="{{ route('project.show', ['id' => $entry->project->id]) }}" class="text-indigo-600 hover:underline">
                                                            {{ $entry->project->translated('isi_content')['nama_project'] ?? 'Project' }}
                                                        </a>
                                                    </p>
                                                </div>
                                            @endif

                                            <p class="text-xs dark:text-gray-200 text-gray-500 mt-3">
                                                {{ $entry->created_at?->format('d M Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-center text-gray-500 italic" data-translate="empty_lrn" data-translate-page="portofolio_user">
                                Belum ada catatan learning corner
                            </p>
                        @endif

                        @if(method_exists($user->learning_corners, 'hasPages') && $user->learning_corners->hasPages())
                            <div class="mt-4">
                                {!! $user->learning_corners->render('vendor.pagination.custom_ajax', ['groupName' => 'user_learning_corners']) !!}
                            </div>
                        @endif
                    </div>

                    {{-- ==================== SERTIFIKAT ==================== --}}
                    <div class="bg-white rounded-2xl border border-gray-200 dark:border-gray-900 dark:bg-gray-900 shadow-sm p-5 lg:p-6">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100" data-translate="stk" data-translate-page="portofolio_user">Sertifikat</h3>
                            <span class="text-sm text-indigo-600 dark:text-indigo-400">{{ $user->sertifikats->count() }} <span data-translate="stk" data-translate-page="portofolio_user">sertifikat</span></span>
                        </div>

                        @if($user->sertifikats->isNotEmpty())
                            <div class="space-y-6">
                                @foreach($user->sertifikats as $sertifikat)
                                    @php
                                        $namaSertif = $sertifikat->nama_sertifikat ?? 'Sertifikat Tanpa Nama';
                                        $lembaga = $sertifikat->lembaga_penerbit ?? 'Lembaga Tidak Diketahui';
                                        $tanggal = $sertifikat->tanggal_terbit
                                            ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('F Y')
                                            : 'Tanggal Tidak Tersedia';
                                        $linkSertif = $sertifikat->link_sertifikat
                                            ? asset('storage/' . ltrim($sertifikat->link_sertifikat, '/'))
                                            : null;
                                        $isImage = $linkSertif && in_array(
                                            strtolower(pathinfo($linkSertif, PATHINFO_EXTENSION)),
                                            ['jpg', 'jpeg', 'png', 'gif', 'webp']
                                        );
                                        $expiredAt = $sertifikat->expired_date ? \Carbon\Carbon::parse($sertifikat->expired_date) : null;
                                        $statusBerlakuKey = $expiredAt
                                            ? ($expiredAt->isFuture() || $expiredAt->isToday() ? 'still_valid' : 'expired')
                                            : 'permanent';
                                        $expiredText = $expiredAt ? $expiredAt->format('d F Y') : null;
                                    @endphp

                                    <div class="border border-gray-100 dark:border-gray-700 rounded-xl p-5 hover:shadow-md transition">
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div class="flex flex-col justify-between">
                                                <div class="flex items-start gap-4">
                                                    <div class="w-14 h-14 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                             <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                      class="w-8 h-8 text-black" fill="currentColor"  viewBox="0 0 459 459" style="enable-background:new 0 0 459 459;" xml:space="preserve">
                                    <g>
                                        <g>
                                            <rect x="286.875" y="239.062" width="114.75" height="19.125"/>
                                            <rect x="229.5" y="181.688" width="172.125" height="19.125"/>
                                            <path d="M420.75,28.688H38.25C17.212,28.688,0,45.9,0,66.938v248.625c0,21.037,17.212,38.25,38.25,38.25H76.5v76.5l47.812-47.812
                                                l47.812,47.812v-76.5H420.75c21.037,0,38.25-17.213,38.25-38.25V66.938C459,45.9,441.787,28.688,420.75,28.688z M153,384.412
                                                l-28.688-28.688l-28.688,28.688v-74.587c9.562,3.825,19.125,5.737,28.688,5.737s19.125-1.912,28.688-5.737V384.412z
                                                M124.312,296.438c-26.775,0-47.812-21.037-47.812-47.812s21.038-47.812,47.812-47.812s47.812,21.037,47.812,47.812
                                                S151.087,296.438,124.312,296.438z M439.875,315.562c0,11.475-7.65,19.125-19.125,19.125H172.125v-40.162
                                                c11.475-11.476,19.125-28.688,19.125-45.9c0-36.337-30.6-66.938-66.938-66.938s-66.938,30.6-66.938,66.938
                                                c0,19.125,7.65,34.425,19.125,45.9v40.162H38.25c-11.475,0-19.125-9.562-19.125-19.125V66.938c0-11.475,7.65-19.125,19.125-19.125
                                                h382.5c11.475,0,19.125,9.562,19.125,19.125V315.562z"/>
                                            <rect x="57.375" y="124.312" width="344.25" height="19.125"/>
                                        </g>
                                    </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $namaSertif }}</h4>
                                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $lembaga }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-200 mt-1">
                                                            <span data-translate="issued" data-translate-page="portofolio_user">Diterbitkan</span> {{ $tanggal }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-200 mt-1">
                                                            <span class="font-semibold" data-translate="status_valid" data-translate-page="portofolio_user">Status Berlaku</span>:
                                                            <span class="ml-1 text-sm text-gray-700 dark:text-gray-300" data-translate="{{ $statusBerlakuKey }}" data-translate-page="portofolio_user">
                                                                {{ $statusBerlakuKey }}
                                                            </span>
                                                            @if($expiredText)
                                                                | <span data-translate="expiry_date" data-translate-page="portofolio_user">Tanggal Kadaluarsa</span> {{ $expiredText }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>

                                                @if($linkSertif)
                                                    <div class="mt-5">
                                                        <a href="{{ $linkSertif }}" target="_blank" rel="noopener noreferrer"
                                                            class="inline-flex items-center px-5 py-2.5 text-amber-600 bg-amber-50 hover:bg-amber-100 text-sm font-medium rounded-lg transition">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            <span data-translate="view_certificate" data-translate-page="portofolio_user">Lihat Sertifikat</span>
                                                        </a>
                                                    </div>
                                                @else
                                                    <p class="mt-5 text-sm text-gray-500 italic" data-translate="no_cert_file" data-translate-page="portofolio_user">File sertifikat tidak tersedia</p>
                                                @endif
                                            </div>

                                            @if($linkSertif)
                                                <div class="relative w-full aspect-[4/3] rounded-lg overflow-hidden bg-gray-100 shadow-inner flex items-center justify-center">
                                                    @if($isImage)
                                                        <img src="{{ $linkSertif }}" alt="Preview {{ $namaSertif }}"
                                                            class="w-full h-full object-contain" loading="lazy"
                                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        <div class="absolute inset-0 hidden flex items-center justify-center bg-gray-200 text-gray-500 dark:text-gray-200 text-xs">
                                                            Gagal memuat preview
                                                        </div>
                                                    @else
                                                        <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-200">
                                                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            <span class="text-sm">PDF / Dokumen</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="flex items-center justify-center text-gray-400 dark:text-gray-200 text-sm italic">
                                                    <span data-translate="not_yet" data-translate-page="portofolio_user">Tidak ada preview Tersedia</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-6" data-translate="empty_sertifikat" data-translate-page="portofolio_user">Belum ada sertifikat yang ditambahkan</p>
                        @endif

                        @if(method_exists($user->sertifikats, 'hasPages') && $user->sertifikats->hasPages())
                            <div class="mt-4">
                                {!! $user->sertifikats->render('vendor.pagination.custom_ajax', ['groupName' => 'user_sertifikats']) !!}
                            </div>
                        @endif
                    </div>

                </div>
                {{-- END KONTEN UTAMA --}}

            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            @if(auth()->check())
                if (typeof showPageInfo === 'function') {
                    showPageInfo("popup.portofolio_saya");
                }
            @endif
        });

        function toggleShareMenu() {
            const shareMenu = document.getElementById('shareMenu');
            shareMenu.classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            const shareMenu = document.getElementById('shareMenu');
            const shareButton = event.target.closest('button[onclick*="toggleShareMenu"]');
            if (!shareButton && shareMenu && !shareMenu.contains(event.target)) {
                shareMenu.classList.add('hidden');
            }
        });

        function copyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                notification.textContent = 'Link disalin ke clipboard!';
                document.body.appendChild(notification);
                setTimeout(() => { notification.remove(); }, 3000);
                document.getElementById('shareMenu').classList.add('hidden');
            }).catch(err => {
                console.error('Gagal menyalin link:', err);
                alert('Gagal menyalin link');
            });
        }
    </script>
@endsection