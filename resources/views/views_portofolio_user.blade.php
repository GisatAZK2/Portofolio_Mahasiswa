@extends('Layout.Layout')

@section('title', ($user->nama_mahasiswa ?? 'Mahasiswa') . ' | Portfolio')

@section('content')
    <div class="min-h-screen bg-gray-100 dark:bg-gray-700 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Grid utama ala LinkedIn -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                <!-- ========== SIDEBAR KIRI (Profil + Keahlian) - Sticky hanya di lg+ ========== -->
                <div class="lg:col-span-1 lg:sticky lg:top-8 lg:self-start space-y-6">

                    <!-- Kartu Profil Ringkas -->
                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-900 shadow-sm overflow-hidden">
                        <!-- Background Banner -->
                        <div class="h-32
                            {{ $user->background_url
                                ? 'bg-cover bg-center'
                                : 'bg-gradient-to-r from-indigo-500 to-indigo-600' }}" 
                            @if($user->background_url)
                                style="background-image: url('{{ asset('storage/' . $user->background_url) }}');" 
                            @endif>
                        </div>

                        <!-- Avatar dan Info Utama -->
                        <div class="px-5 pb-6 relative">
                            <div class="flex justify-between items-start">
                                <div class="-mt-12 mb-3">
                                    <div
                                        class="w-24 h-24 rounded-full border-4 border-white bg-white dark:border-gray-900 dark:bg-gray-900 shadow-lg overflow-hidden">
                                        @if($user->photo_profile)
                                            <img src="{{ asset('storage/' . ltrim($user->photo_profile, '/')) }}"
                                                alt="{{ $user->nama_mahasiswa ?? 'Mahasiswa' }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div
                                                class="w-full h-full bg-indigo-600 flex items-center justify-center text-white text-3xl font-bold">
                                                {{ strtoupper(mb_substr(trim($user->nama_mahasiswa ?? 'M'), 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-xs text-gray-600 mt-2">
                                    <span class="inline-flex items-center gap-1">
                                        <span>Jenis kelamin :</span>
                                        {{ $user->jenis_kelamin ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                                {{ trim($user->nama_mahasiswa ?? 'Mahasiswa') }}
                            </h1>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $user->jurusan?->nama_jurusan ?? 'Mahasiswa' }}
                            </p>

                            <p class="mt-4 text-sm text-gray-700 dark:text-gray-200 leading-relaxed">
                                {{ !empty(trim($user->deskripsi)) ? $user->deskripsi : 'Tidak ada deskripsi.' }}
                            </p>

                            <p class="mt-4 text-xs text-blue-600 font-medium cursor-pointer hover:underline">
                                Politeknik Mitra Industri
                            </p>
                        </div>
                    </div>

                    <!-- Keahlian -->
                    <div class="bg-white rounded-2xl border border-gray-200 dark:border-gray-900 dark:bg-gray-900 shadow-sm p-5">
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 mb-4">Keahlian</h3>

                        @if($user->keahlian)
                            <div class="mb-5">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Utama:</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="px-3 py-1 text-xs font-medium bg-red-50 text-red-700 rounded-full border border-red-100">
                                        {{ $user->keahlian->nama_keahlian }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if(!empty($user->keahlian_tambahan))
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:gray-300 mb-2">Tambahan:</p>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $keahlianTambahan = \App\Models\Keahlian::whereIn(
                                            'id_keahlian',
                                            $user->keahlian_tambahan
                                        )->pluck('nama_keahlian');
                                    @endphp
                                    @foreach($keahlianTambahan as $nama)
                                        <span
                                            class="px-3 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-full border border-blue-100">
                                            {{ $nama }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!$user->keahlian && empty($user->keahlian_tambahan))
                            <p class="text-sm text-gray-500 italic text-center py-2">Belum ada keahlian ditambahkan</p>
                        @endif
                    </div>

                </div>

                <!-- ========== KONTEN UTAMA ========== -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Projects dengan Tabs dan Pagination -->
                    <div class="bg-white rounded-2xl border border-gray-200 dark:bg-gray-900 dark:border-gray-900 shadow-sm p-5 lg:p-6">
                        <!-- Header dengan Tabs -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-5">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-3 sm:mb-0">Projects</h3>
                            
                            <!-- Tab Navigation -->
                            <div class="flex space-x-1 bg-gray-100 dark:bg-gray-800 p-1 rounded-lg">
                                <a href="{{ request()->fullUrlWithQuery(['project_tab' => 'now']) }}" 
                                   class="px-4 py-2 text-sm font-medium rounded-md transition-all
                                          {{ $projectTab == 'now' 
                                             ? 'bg-white dark:bg-gray-900 text-indigo-600 shadow-sm' 
                                             : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                                    Sedang Berjalan
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['project_tab' => 'upcoming']) }}" 
                                   class="px-4 py-2 text-sm font-medium rounded-md transition-all
                                          {{ $projectTab == 'upcoming' 
                                             ? 'bg-white dark:bg-gray-900 text-indigo-600 shadow-sm' 
                                             : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                                    Akan Datang
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['project_tab' => 'completed']) }}" 
                                   class="px-4 py-2 text-sm font-medium rounded-md transition-all
                                          {{ $projectTab == 'completed' 
                                             ? 'bg-white dark:bg-gray-900 text-indigo-600 shadow-sm' 
                                             : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                                    Selesai
                                </a>
                            </div>
                        </div>

                        <!-- Info Tab dan Count -->
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                @switch($projectTab)
                                    @case('now')
                                        Proyek yang sedang berjalan
                                        @break
                                    @case('upcoming')
                                        Proyek yang akan datang
                                        @break
                                    @case('completed')
                                        Proyek yang sudah selesai
                                        @break
                                @endswitch
                            </span>
                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                {{ $projects->total() }} proyek
                            </span>
                        </div>

                        <!-- Daftar Projects -->
                        @if($projects->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($projects as $project)
                                    @php
                                        $content = $project->isi_content ?? [];
                                        $namaProject = $content['nama_project'] ?? 'Tanpa Nama';
                                        
                                        // Ambil data leader, owner, dan cek apakah user adalah member
                                        $leader = $project->leader;
                                        $owner = $project->owner;
                                        
                                        // Cek apakah owner dan leader adalah orang yang sama
                                        $isOwnerAndLeaderSame = $owner && $leader && $owner->id === $leader->id;
                                        
                                        // Tentukan peran user dalam project ini
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

                                        if ($akhir && $akhir < $today) {
                                            $status = 'completed';
                                            $statusText = 'Selesai';
                                            $statusColor = 'green';
                                        } elseif ($mulai > $today) {
                                            $status = 'upcoming';
                                            $statusText = 'Akan Datang';
                                            $statusColor = 'yellow';
                                        } else {
                                            $status = 'now';
                                            $statusText = 'Sedang Berjalan';
                                            $statusColor = 'blue';
                                        }
                                    @endphp

                                    <div class="border border-gray-100 dark:border-gray-700 rounded-xl p-5 hover:shadow-md transition">
                                        <div class="flex flex-col space-y-4">
                                            <!-- Header: Nama Project dan Status -->
                                            <div class="flex flex-wrap items-start justify-between gap-3">
                                                <div class="flex items-center flex-wrap gap-2">
                                                    <a href="{{ route('project.show', $project->id) }}" 
                                                       class="text-base font-semibold text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                                        {{ $namaProject }}
                                                    </a>
                                                    <!-- Badge peran user (jika ada) -->
                                                    {!! $userRoleBadge !!}
                                                </div>
                                                
                                                <!-- Status Badge -->
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($statusColor == 'green') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($statusColor == 'yellow') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif">
                                                    {{ $statusText }}
                                                </span>
                                            </div>
                                            
                                            <!-- Grid untuk Leader, Owner, dan Members -->
                                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                                @if($isOwnerAndLeaderSame)
                                                    <!-- Jika Owner dan Leader sama, tampilkan satu section -->
                                                    <div class="lg:col-span-2">
                                                        <div class="flex items-center space-x-3 bg-purple-50 dark:bg-purple-900/20 p-3 rounded-lg">
                                                            <div class="flex-shrink-0">
                                                                @if($owner && $owner->photo_profile)
                                                                    <img src="{{ asset('storage/' . ltrim($owner->photo_profile, '/')) }}"
                                                                        alt="{{ $owner->nama_mahasiswa ?? 'Owner/Leader' }}"
                                                                        class="w-12 h-12 rounded-full object-cover border-2 border-purple-300">
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
                                                                        @if($owner->id === $user->id)
                                                                            <span class="ml-1 text-xs text-purple-600">(Anda)</span>
                                                                        @endif
                                                                    </a>
                                                                @else
                                                                    <p class="text-base font-medium text-gray-900 dark:text-gray-100 truncate">
                                                                        Tidak ada owner/leader
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- Tampilkan terpisah jika berbeda -->
                                                    <!-- Leader Section -->
                                                    <div class="flex items-center space-x-3">
                                                        <div class="flex-shrink-0">
                                                            @if($leader && $leader->photo_profile)
                                                                <img src="{{ asset('storage/' . ltrim($leader->photo_profile, '/')) }}"
                                                                    alt="{{ $leader->nama_mahasiswa ?? 'Leader' }}"
                                                                    class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
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
                                                                    @if($leader->id === $user->id)
                                                                        <span class="ml-1 text-xs text-blue-600">(Anda)</span>
                                                                    @endif
                                                                </a>
                                                            @else
                                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                                    Tidak ada leader
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Owner Section -->
                                                    <div class="flex items-center space-x-3">
                                                        <div class="flex-shrink-0">
                                                            @if($owner && $owner->photo_profile)
                                                                <img src="{{ asset('storage/' . ltrim($owner->photo_profile, '/')) }}"
                                                                    alt="{{ $owner->nama_mahasiswa ?? 'Owner' }}"
                                                                    class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
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
                                                                    @if($owner->id === $user->id)
                                                                        <span class="ml-1 text-xs text-purple-600">(Anda)</span>
                                                                    @endif
                                                                </a>
                                                            @else
                                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                                    Tidak ada owner
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Members Section (Anggota Tim) -->
                                            @if($project->members->isNotEmpty())
                                                <div class="mt-2">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Anggota Tim ({{ $project->members->count() }})</p>
                                                    <div class="flex flex-wrap gap-3">
                                                        @foreach($project->members->take(5) as $member)
                                                            <div class="flex items-center space-x-2 group relative" title="{{ $member->nama_mahasiswa }}">
                                                                @if($member->photo_profile)
                                                                    <img src="{{ asset('storage/' . ltrim($member->photo_profile, '/')) }}"
                                                                        alt="{{ $member->nama_mahasiswa }}"
                                                                        class="w-8 h-8 rounded-full object-cover border-2 border-gray-200 hover:border-indigo-400 transition cursor-pointer">
                                                                @else
                                                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center border-2 border-gray-200 hover:border-indigo-400 transition cursor-pointer">
                                                                        <span class="text-gray-600 font-medium text-xs">
                                                                            {{ strtoupper(mb_substr(trim($member->nama_mahasiswa ?? 'M'), 0, 1)) }}
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                                <span class="text-xs text-gray-700 dark:text-gray-300 max-w-[100px] truncate">
                                                                    {{ $member->nama_mahasiswa }}
                                                                    @if($member->id === $user->id)
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
                                                <p class="text-xs text-gray-400 dark:text-gray-500 italic mt-2">
                                                    Belum ada anggota tim
                                                </p>
                                            @endif

                                            <!-- Tanggal Project -->
                                            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 dark:text-gray-400 mt-3">
                                                <span class="flex items-center">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    Mulai: {{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d M Y') }}
                                                </span>
                                                @if($project->tanggal_akhir)
                                                    <span class="flex items-center">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        Selesai: {{ \Carbon\Carbon::parse($project->tanggal_akhir)->format('d M Y') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $projects->appends(['project_tab' => $projectTab])->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    @switch($projectTab)
                                        @case('now')
                                            Belum ada proyek yang sedang berjalan
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
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Sertifikat</h3>
                            <span class="text-sm text-gray-500 dark:text-gray-200">{{ $user->sertifikats->count() }} sertifikat</span>
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
                                                        <img src="{{ $linkSertif }}" alt="Preview {{ $namaSertif }}"
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
                    </div>

                    <!-- Learning Corners - FIXED VERSION -->
                    <div class="bg-white rounded-2xl border border-gray-200 dark:bg-gray-900 dark:border-gray-900 shadow-sm p-5 lg:p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-5">Learning Corners</h3>

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
                                                            <img src="{{ asset('storage/' . $item['content']) }}" alt="Gambar learning corner"
                                                                class="w-30 rounded-lg shadow-sm object-cover max-h-[500px]" loading="lazy"
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
                                                <p class="text-sm text-gray-600 dark:text-gray-300 italic">
                                                    Tidak ada konten yang dapat ditampilkan
                                                </p>
                                            @endif

                                            <!-- Informasi Project terkait (jika ada) -->
                                            @if($entry->project)
                                                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        Dari project: 
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
                            <p class="text-sm text-gray-500 dark:text-gray-200 text-center py-8 italic">
                                Belum ada catatan learning corner
                            </p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection