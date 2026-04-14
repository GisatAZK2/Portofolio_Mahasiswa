@extends('Layout.Layout')

@section('title', ($user->nama_mahasiswa ?? 'Mahasiswa') . ' | Portfolio')

@section('content')
    <div class="min-h-screen bg-gray-100 dark:bg-gray-800 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <div class="lg:col-span-1 lg:sticky lg:top-8 lg:self-start space-y-6">

                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-900 shadow-sm overflow-hidden">
                        <!-- Background Banner -->
                        <div class="h-32 relative
                            {{ $user->background_url
                                ? 'bg-cover bg-center'
                                : 'bg-gradient-to-r from-indigo-500 to-indigo-600' }}" 
                            @if($user->background_url)
                                style="background-image: url('{{ asset('storage/' . $user->background_url) }}');" 
                            @endif>
                            
                            <!-- Logo Overlay -->
                            <div class="absolute top-2 left-2 flex items-center gap-3">
                                @php
                                    $currentYear = date('Y');
                                    $tahunKeluar = $user->angkatan->tahun_keluar ?? null;
                                    $isAlumni = $tahunKeluar && $currentYear > $tahunKeluar;
                                @endphp
                                
 @if($isAlumni)
    <div class="flex items-center gap-1 bg-white/90 dark:bg-gray-800/90 rounded-lg px-2 py-1">
        <img 
            src="{{ asset('assets/Alumni.svg') }}" 
            alt="Alumni Logo" 
            class="w-7 h-7 md:w-8 md:h-8 lg:w-9 lg:h-9 drop-shadow-lg transition-all duration-200">
        <span class="text-[10px] font-semibold text-gray-800/30 dark:text-gray-50 whitespace-nowrap">
            Alumni Polmind
        </span>
    </div>
@else
    <div class="flex items-center gap-1 bg-white/90 dark:bg-gray-800/90 rounded-lg px-2 py-1">
        <img 
            src="{{ asset('assets/Logo.svg') }}" 
            alt="Logo Politeknik Mitra Industri" 
            class="w-5 h-5 drop-shadow-lg">
        <span class="text-[10px] font-semibold text-gray-800 dark:text-gray-50 whitespace-nowrap">
            Politeknik Mitra Industri
        </span>
    </div>
@endif
                            </div>
                        </div>

                        <!-- Avatar -->
                        <div class="px-5 pb-6 relative">
                            <div class="flex justify-between items-start gap-3">
                                <div class="-mt-12 mb-3">
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

                                <!-- Share Button -->
                                <div class="flex flex-col gap-2 pt-1">
                                    @php
                                        $shareUrl = route('portfolio.show', ['user' => $user->id]) . '?utm_source=share&utm_medium=portfolio';
                                        $shareText = 'Lihat portfolio saya di Politeknik Mitra Industri!';
                                    @endphp
                                    
                                    <!-- Share Button with Dropdown -->
                                    <div class="relative group">
                                        <button class="flex items-center justify-center gap-2 px-3 py-2 bg-indigo-600/50 hover:bg-indigo-700/50 text-white rounded-lg text-sm font-medium transition-colors"
                                            onclick="toggleShareMenu()">
                                            <img src="{{ asset('assets/share_icon.svg') }}" alt="Share" class="w-4 h-4">
                                            <span class="hidden sm:inline">Share</span>
                                        </button>

                                        <!-- Share Menu Dropdown -->
                                        <div id="shareMenu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 hidden z-50">
                                            <!-- Copy Link -->
                                            <button onclick="copyLink('{{ $shareUrl }}')" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 first:rounded-t-lg flex items-center gap-2 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.658 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                                <span>Copy Link</span>
                                            </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Email Section -->
                            <div class="mt-2 mb-1">
                                <a href="mailto:{{ $user->email ?? '#' }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline truncate block" title="{{ $user->email ?? 'Email tidak tersedia' }}">
                                    {{ $user->email ?? 'Email tidak tersedia' }}
                                </a>
                            </div>

                            <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                {{ trim($user->nama_mahasiswa ?? 'Mahasiswa') }}
                            </h1>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                               <span data-translate="prodi" data-translate-page="portofolio_user"> Prodi :</span>  {{ $user->jurusan?->nama_jurusan ?? 'Mahasiswa' }}
                            </p>

                            <div class="text-xs text-gray-600 dark:text-gray-300 mt-3 flex flex-col gap-2">
                                <!-- Jenis Kelamin dengan Icon -->
        
                                <!-- Angkatan dengan Icon -->
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                    </svg>
                                    <span data-translate="agkt" data-translate-page="portofolio_user">Cohort :</span>
                                    <span class="font-medium">{{ $user->angkatan?->nama_angkatan ?? '-' }}</span>
                                </span>
                            </div>

                            <p class="mt-4 text-sm text-gray-700 dark:text-gray-200 leading-relaxed">
                                {{ !empty(trim($user->deskripsi)) ? $user->deskripsi : 'Tidak ada deskripsi.' }}
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

                    <!-- Keahlian -->
                    <div class="bg-white rounded-2xl border border-gray-200 dark:border-gray-900 dark:bg-gray-900 shadow-sm p-5">
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-4" data-translate="khl" data-translate-page="portofolio_user">Keahlian</h3>

                        @if($user->keahlian)
                            <div class="mb-5">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" data-translate="Utama" data-translate-page="portofolio_user">Utama:</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="px-3 py-1 text-xs font-medium bg-red-50 text-red-700 rounded-full border border-red-100">
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
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tambahan:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($acceptedTambahan as $kt)
                                        <span
                                            class="px-3 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-full border border-blue-100">
                                            {{ $kt->nama_keahlian }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!$user->keahlian && $acceptedTambahan->isEmpty())
                            <p class="text-sm text-gray-500 italic text-center py-2">Belum ada keahlian ditambahkan</p>
                        @endif
                    </div>

                </div>

                <!-- ========== KONTEN UTAMA ========== -->
                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white rounded-2xl border border-gray-200 dark:bg-gray-900 dark:border-gray-900 shadow-sm p-5 lg:p-6">
                        
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
                                        <span data-translate="show_all" data-translate-page="portofolio_user"></span>Menampilkan semua proyek</span>
                                @endswitch
                            </span>
                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                {{ $projects->total() }} <span data-translate="pjt" data-translate-page="portofolio_user">proyek</span>
                            </span>
                        </div>

                        <!-- Daftar Projects -->
                        @if($projects->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($projects as $project)
                                    @php
                                        $content = $project->isi_content ?? [];
                                        $namaProject = $content['nama_project'] ?? 'Tanpa Nama';
                                        $deskripsi = $content['deskripsi'] ?? '';
                                        $linkProject = $content['link_project'] ?? null;
                                        $linkGithub = $content['link_github'] ?? null;
                                        $linkVideo = $content['link_video'] ?? null;
                                        
                                        // Ambil data leader, owner, dan cek apakah user adalah member
                                        $leader = $project->leader;
                                        $owner = $project->owner;
                                        
                                        // Cek apakah owner dan leader adalah orang yang sama
                                        $isOwnerAndLeaderSame = $owner && $leader && $owner->id === $leader->id;
                                        
                                        // Tentukan peran user dalam project 
                                        $userRole = null;
                                        $userRoleBadge = '';
                                        
                                        if ($owner && $owner->id === $user->id) {
                                            $userRole = 'owner';
                                            $userRoleBadge = '<span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">Owner</span>';
                                        } elseif ($leader && $leader->id === $user->id) {
                                            $userRole = 'leader';
                                            $userRoleBadge = '<span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Leader</span>';
                                        } elseif ($project->members->contains('id', $user->id)) {
                                            $userRole = 'member';
                                            $userRoleBadge = '<span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Member</span>';
                                        }

                                        // Tentukan status proyek
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
                                            <!-- Header: Nama Project -->
                                            <div class="flex flex-wrap items-start gap-3">
                                                <div class="flex items-center flex-wrap gap-2">
                                                    <a href="{{ route('project.show', $project->id) }}" 
                                                       class="text-base font-semibold text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                                        {{ $namaProject }}
                                                    </a>
                                                    <!-- Badge peran user (jika ada) -->
                                                    {!! $userRoleBadge !!}
                                                </div>
                                            </div>
                                            
                                            <!-- Grid: Owner/Leader -->
                                            <div class="grid grid-cols-1 gap-4">
                                                <!-- Owner/Leader Section -->
                                                <div>
                                                    <!-- Grid untuk Leader, Owner -->
                                                    <div class="grid grid-cols-1 gap-4">
                                                @if($isOwnerAndLeaderSame)
                                                    <!-- Jika Owner dan Leader sama, tampilkan satu section -->
                                                    <div class="lg:col-span-2">
                                                        <div class="flex items-center space-x-3 bg-purple-50 dark:bg-purple-900/20 p-3 rounded-lg">
                                                            <div class="flex-shrink-0">
                                                                @if($owner && $owner->photo_profile)
                                                                    <img id="logo-zoom" src="{{ asset('storage/' . ltrim($owner->photo_profile, '/')) }}"
                                                                        alt="{{ $owner->nama_mahasiswa ?? 'Owner/Leader' }}"
                                                                        class="cursor-pointer w-12 h-12 rounded-full object-cover border-2 border-purple-300">
                                                                @else
                                                                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center border-2 border-purple-300">
                                                                        <span class="text-purple-600 font-medium text-lg">
                                                                            {{ $owner ? strtoupper(mb_substr(trim($owner->nama_mahasiswa ?? 'O'), 0, 1)) : 'O' }}
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-center gap-2 mb-1">
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                                        Owner & Leader
                                                                    </span>
                                                                </div>
                                                                @if($owner)
                                                                    <a href="{{ route('portfolio.show', $owner->id) }}" 
                                                                    class="text-base font-medium text-gray-900 dark:text-gray-100 hover:text-purple-600 dark:hover:text-purple-400 truncate block">
                                                                        {{ $owner->nama_mahasiswa }}
                                                                        @if(auth()->check() && auth()->id() === $owner->id)
                                                                            <span class="ml-1 text-xs text-purple-600">(Anda)</span>
                                                                        @endif
                                                                    </a>
                                                                @else
                                                                    <p data-translate="pjt_noneownlead" data-translate-page="portofolio_user" class="text-base font-medium text-gray-900 dark:text-gray-100 truncate">
                                                                        Tidak ada owner/leader
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else

                                                    <!-- Leader Section -->
                                                    <div class="flex items-center space-x-3">
                                                        <div class="flex-shrink-0">
                                                            @if($leader && $leader->photo_profile)
                                                                <img src="{{ asset('storage/' . ltrim($leader->photo_profile, '/')) }}"
                                                                    id="logo-zoom"
                                                                    alt="{{ $leader->nama_mahasiswa ?? 'Leader' }}"
                                                                    class="cursor-pointer w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                                            @else
                                                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center border-2 border-gray-200">
                                                                    <span class="text-indigo-600 font-medium text-sm">
                                                                        {{ $leader ? strtoupper(mb_substr(trim($leader->nama_mahasiswa ?? 'L'), 0, 1)) : 'L' }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">Project Leader</p>
                                                            @if($leader)
                                                                <a href="{{ route('portfolio.show', $leader->id) }}" 
                                                                class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 truncate block">
                                                                    {{ $leader->nama_mahasiswa }}
                                                                    @if(auth()->check() && auth()->id() === $leader->id)
                                                                        <span class="ml-1 text-xs text-blue-600">(Anda)</span>
                                                                    @endif
                                                                </a>
                                                            @else
                                                                <p data-translate="pjt_nolead" data-translate-page="portofolio_user" class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                                    Tidak ada leader
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Owner Section -->
                                                    <div class="flex items-center space-x-3">
                                                        <div class="flex-shrink-0">
                                                            @if($owner && $owner->photo_profile)
                                                                <img id="logo-zoom" src="{{ asset('storage/' . ltrim($owner->photo_profile, '/')) }}"
                                                                    alt="{{ $owner->nama_mahasiswa ?? 'Owner' }}"
                                                                    class="cursor-pointer w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                                            @else
                                                                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center border-2 border-gray-200">
                                                                    <span class="text-amber-600 font-medium text-sm">
                                                                        {{ $owner ? strtoupper(mb_substr(trim($owner->nama_mahasiswa ?? 'O'), 0, 1)) : 'O' }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">Project Owner</p>
                                                            @if($owner)
                                                                <a href="{{ route('portfolio.show', $owner->id) }}" 
                                                                class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 truncate block">
                                                                    {{ $owner->nama_mahasiswa }}
                                                                    @if(auth()->check() && auth()->id() === $owner->id)
                                                                        <span class="ml-1 text-xs text-purple-600">(Anda)</span>
                                                                    @endif
                                                                </a>
                                                            @else
                                                                <p data-translate="pjt_ownnone" data-translate-page="portofolio_user" class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                                    No owner
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Preview Section -->
                                            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 space-y-3">
                                                <!-- Deskripsi -->
                                                @if(!empty($deskripsi))
                                                    <div>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-1">Deskripsi</p>
                                                        <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2">{{ $deskripsi }}</p>
                                                    </div>
                                                @endif
                                                
                                                <!-- Preview Video/Project -->
                                                @if(!empty($linkVideo))
                                                    <div class="mt-2">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-2">Pratinjau Video</p>
                                                        <a href="{{ $linkVideo }}" target="_blank" rel="noopener noreferrer" class="block group">
                                                            @php
                                                                // Extract YouTube video ID
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
                                                                
                                                                <!-- Play Button Overlay -->
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
                                            </div>                                            <!-- Members Section (Anggota Tim) -->
                                            @if($project->members->isNotEmpty())
                                                <div class="mt-2">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Anggota Tim ({{ $project->members->count() }})</p>
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
                                                                    @if(auth()->check() && auth()->id() === $member->id)
                                                                        <span class="text-green-600">(Anda)</span>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                        
                                                        @if($project->members->count() > 5)
                                                            <div class="flex items-center">
                                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                                    +{{ $project->members->count() - 5 }} lainnya
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <p data-translate="pjt_noteam" data-translate-page="portofolio_user" class="text-xs text-gray-400 dark:text-gray-500 italic mt-2">
                                                    Belum ada anggota tim
                                                </p>
                                            @endif

                                            <!-- Tanggal Project -->
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
                                                        <span data-translate="end" data-translate-page="portofolio_user">Selesai</span></span>: {{ \Carbon\Carbon::parse($project->tanggal_akhir)->format('d M Y') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
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
                                        @case('now')
                                            Belum ada proyek yang sedang dikerjakan
                                            @break
                                        @case('upcoming')
                                            Belum ada proyek yang akan datang
                                            @break
                                        @case('completed')
                                            Belum ada proyek yang selesai
                                            @break
                                    @endswitch
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Sertifikat -->
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
                                    @endphp

                                    <div class="border border-gray-100 dark:border-gray-700  rounded-xl p-5 hover:shadow-md transition">
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div class="flex flex-col justify-between">
                                                <div class="flex items-start gap-4">
                                                    <div
                                                        class="w-14 h-14 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $namaSertif }}</h4>
                                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $lembaga }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-200 mt-1">Diterbitkan {{ $tanggal }}</p>
                                                    </div>
                                                </div>

                                                @if($linkSertif)
                                                    <div class="mt-5">
                                                        <a href="{{ $linkSertif }}" target="_blank" rel="noopener noreferrer"
                                                            class="inline-flex items-center px-5 py-2.5 text-amber-600  bg-amber-50 hover:bg-amber-100 text-sm font-medium rounded-lg transition">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            Lihat Sertifikat
                                                        </a>
                                                    </div>
                                                @else
                                                    <p class="mt-5 text-sm text-gray-500 italic">File sertifikat tidak tersedia</p>
                                                @endif
                                            </div>

                                            @if($linkSertif)
                                                <div
                                                    class="relative w-full aspect-[4/3] rounded-lg overflow-hidden bg-gray-100 shadow-inner flex items-center justify-center">
                                                    @if($isImage)
                                                        <img  src="{{ $linkSertif }}" alt="Preview {{ $namaSertif }}"
                                                            class="w-full h-full object-contain" loading="lazy"
                                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        <div
                                                            class="absolute inset-0 hidden flex items-center justify-center bg-gray-200 text-gray-500 dark:text-gray-200 text-xs">
                                                            Gagal memuat preview
                                                        </div>
                                                    @else
                                                        <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-200">
                                                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            <span class="text-sm">PDF / Dokumen</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="flex items-center justify-center text-gray-400 dark:text-gray-200 text-sm italic">
                                                    Tidak ada preview tersedia
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-6">Belum ada sertifikat yang ditambahkan</p>
                        @endif

                        @if(method_exists($user->sertifikats, 'hasPages') && $user->sertifikats->hasPages())
                            <div class="mt-4">
                                {!! $user->sertifikats->render('vendor.pagination.custom_ajax', ['groupName' => 'user_sertifikats']) !!}
                            </div>
                        @endif
                    </div>

                    <!-- Learning Corners  -->
                    <div class="bg-white rounded-2xl border border-gray-200 dark:bg-gray-900 dark:border-gray-900 shadow-sm p-5 lg:p-6">
                     <div class="flex justify-between items-center mb-5">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-5">Learning Corners</h3>
                        <span class="text-sm text-indigo-600 dark:text-indigo-400 ">{{ $user->learning_corners->count() }} <span data-translate="note" data-translate-page="portofolio_user">catatan</span></span>
                     </div>

                        @if($user->learning_corners->isNotEmpty())
                            <div class="space-y-6">
                                @foreach($user->learning_corners as $entry)
                                    <div class="border border-gray-100 dark:border-gray-700 dark:text-gray-100 rounded-xl p-5 hover:shadow-md transition">
                                        <div class="space-y-4">
                                            @php
                                                // Ambil content - bisa array atau string JSON
                                                $rawContent = $entry->content ?? [];

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
                                                                onerror="this.src='https://st4.depositphotos.com/17828278/24401/v/450/depositphotos_244011872-stock-illustration-image-vector-symbol-missing-available.jpg'; this.alt='Gambar gagal dimuat';">
                                                        </div>

                                                    @elseif(($item['type'] ?? '') === 'link' && !empty($item['content']))
                                                        <a href="{{ $item['content'] }}" target="_blank" rel="noopener noreferrer"
                                                            class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                            </svg>
                                                            {{ $item['text'] ?? $item['content'] }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            @else
                                                <p data-translate="empty_content" data-translate-page="portofolio_user" class="text-sm text-gray-600 italic">
                                                    Tidak ada konten yang dapat ditampilkan
                                                </p>
                                            @endif

                                            <!-- Informasi Project terkait (jika ada) -->
                                            @if($entry->project)
                                                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        <span data-translate="lrn_origin" data-translate-page="portofolio_user">Dari project:</span> 
                                                        <a href="{{ route('project.show', $entry->project->id) }}" class="text-indigo-600 hover:underline">
                                                            {{ $entry->project->isi_content['nama_project'] ?? 'Project' }}
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
                            <p data-translate="empty_lrn" data-translate-page="portofolio_user" class="text-sm text-center text-gray-500  italic">
                                Belum ada catatan learning corner
                            </p>
                        @endif

                        @if(method_exists($user->learning_corners, 'hasPages') && $user->learning_corners->hasPages())
                            <div class="mt-4">
                                {!! $user->learning_corners->render('vendor.pagination.custom_ajax', ['groupName' => 'user_learning_corners']) !!}
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            @if(auth()->check())
                if (typeof showPageInfo === 'function') {
                    showPageInfo("popup.portofolio_saya");
                }
            @endif
        });

        // Share Menu Toggle
        function toggleShareMenu() {
            const shareMenu = document.getElementById('shareMenu');
            shareMenu.classList.toggle('hidden');
        }

        // Close share menu when clicking outside
        document.addEventListener('click', function(event) {
            const shareMenu = document.getElementById('shareMenu');
            const shareButton = event.target.closest('button[onclick*="toggleShareMenu"]');
            
            if (!shareButton && !shareMenu.contains(event.target)) {
                shareMenu.classList.add('hidden');
            }
        });

        // Copy Link to Clipboard
        function copyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                // Show notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                notification.textContent = 'Link disalin ke clipboard!';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 3000);

                // Close menu
                document.getElementById('shareMenu').classList.add('hidden');
            }).catch(err => {
                console.error('Gagal menyalin link:', err);
                alert('Gagal menyalin link');
            });
        }
    </script>
@endsection