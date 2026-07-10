@extends('Layout.Layout')
@section('title', 'Detail Proyek')
@section('content')
    <!-- CONTENT -->
    <div id="project-detail-container" data-page-info="popup.project_detail" class="min-h-screen bg-gray-50 dark:bg-gray-800 transition-colors duration-200">
        <div class="p-4 sm:p-6 lg:p-10 space-y-6 sm:space-y-8 lg:space-y-10 max-w-7xl mx-auto">

            <!-- PROJECT CARD -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 lg:p-8 hover:shadow-md transition-shadow duration-300">

                <!-- Project Status Bar -->
                @php
                    $currentUserId = auth()->id();
                    $canEdit = $currentUserId && ($project->id_mahasiswa == $currentUserId || $project->leader_id == $currentUserId);

                    // === OVERDUE PROGRESS BAR LOGIC ===
                    $today = \Carbon\Carbon::today();
                    $endDate = $project->tanggal_akhir ? \Carbon\Carbon::parse($project->tanggal_akhir) : null;
                    $isOverdue = $endDate && $today->gt($endDate) && $projectProgress < 100;

                    // Cek apakah user adalah member proyek (owner / leader / anggota)
                    $isProjectMemberForBar = auth()->check() && (
                        auth()->id() === $project->id_mahasiswa ||
                        auth()->id() === $project->leader_id ||
                        $project->members->contains('id', auth()->id())
                    );

                    // Cek apakah user adalah admin atau dosen
                    $isAdminOrDosen = auth()->check() && in_array(auth()->user()->role, ['admin', 'dosen']);

                    // Tentukan warna dan nilai progress bar
                    if ($isOverdue) {
                        $displayProgress = 100;
                        if ($isProjectMemberForBar) {
                            // Member melihat: 100% merah dengan peringatan
                            $barColor = 'bg-red-600 dark:bg-red-500';
                            $showOverdueWarning = true;
                        } else {
                            // Publik / user lain melihat: 100% hijau
                            $barColor = 'bg-green-600 dark:bg-green-500';
                            $showOverdueWarning = false;
                        }
                    } else {
                        $displayProgress = $projectProgress;
                        $barColor = 'bg-indigo-600 dark:bg-indigo-500';
                        $showOverdueWarning = false;
                    }

                    // Hitung task-based progress untuk task section
                    $taskProgress = $taskTotalCount > 0 ? round(($taskDoneCount / $taskTotalCount) * 100) : 0;
                    if ($isOverdue && $taskProgress < 100) {
                        $taskBarColor = 'bg-red-600 dark:bg-red-500';
                    } else {
                        $taskBarColor = 'bg-indigo-600 dark:bg-indigo-500';
                    }
                @endphp

                <div id="project-detail-status" class="mb-6">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}"
                                data-translate="status_{{ $status }}" data-translate-page="pjt_detail">
                                {{ $statusText }}
                            </span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $displayProgress }}% <span data-translate="progress_done" data-translate-page="pjt_detail">Selesai</span>
                            </span>
                        </div>

                        <!-- Project Title -->
                        <h1
                            class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white order-first sm:order-none w-full sm:w-auto">
                            {{ $project->translated('isi_content')['nama_project'] ?? 'Tanpa Judul' }}
                        </h1>

                        @if ($canEdit)
                            <a href="{{ route('project.edit', ['locale' => app()->getLocale(), 'id' => $project->id]) }}"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-7-10l3 3m0 0l-3 3m3-3H9"/>
                                </svg>
                                <span data-translate="edit_project" data-translate-page="pjt_detail">Edit</span>
                            </a>
                        @endif
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-500"
                            style="width: {{ $displayProgress }}%"></div>
                    </div>

                    <!-- Overdue Warning (untuk member proyek, admin, dan dosen) -->
                    @if($showOverdueWarning || ($isOverdue && $isAdminOrDosen))
                        <div class="mt-3 flex items-start gap-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl px-4 py-3">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-red-700 dark:text-red-400"
                                    data-translate="overdue_warning_title"
                                    data-translate-page="pjt_detail">
                                    Proyek Melewati Batas Waktu!
                                </p>
                                <p class="text-xs text-red-600 dark:text-red-300 mt-0.5"
                                    data-translate="overdue_warning_desc"
                                    data-translate-page="pjt_detail">
                                    Harap selesaikan semua tugas yang masih dalam proses secepatnya.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                    <!-- LEFT SIDE -->
                    <div class="space-y-6">
                        <!-- Deskripsi -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5">
                            <h3
                                class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                                <span data-translate="desc_pjt" data-translate-page="pjt_detail">Deskripsi Proyek</span>
                            </h3>
                            <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                                @if(isset($project->translated('isi_content')['deskripsi']) && $project->translated('isi_content')['deskripsi'])
                                    {{ $project->translated('isi_content')['deskripsi'] }}
                                @else
                                    <span data-translate="empty_desc" data-translate-page="pjt_detail">Tidak ada deskripsi untuk proyek ini.</span>
                                @endif
                            </p>
                        </div>

                        <!-- Team Section -->
                        @php
                            $owner = $project->mahasiswa;
                            $leader = $project->leader;
                            $sameOwnerLeader = $owner && $leader && $owner->id === $leader->id;
                        @endphp
                        <div id="project-detail-team" class="space-y-4">
                            <!-- Project Owner -->
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5">
                                <h3
                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    {{ $sameOwnerLeader ? 'Owner Project' : 'Owner' }}
                                </h3>

                                @if($owner)
                                    <div
                                        class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 max-w-max">
                                        @if($owner->photo_profile)
                                            <img src="{{ asset('storage/' . ltrim($owner->photo_profile, '/')) }}"
                                                alt="{{ $owner->nama_mahasiswa ?? 'Mahasiswa' }}"
                                                class="w-10 h-10 rounded-full object-cover border-2 border-indigo-200 dark:border-indigo-900">
                                        @else
                                            <div
                                                class="w-10 h-10 bg-indigo-600 flex items-center justify-center text-white text-sm font-bold rounded-full border-2 border-indigo-200 dark:border-indigo-900">
                                                {{ strtoupper(mb_substr(trim($owner->nama_mahasiswa ?? 'O'), 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('portfolio.show', ['user' => $owner->username]) }}"
                                                class="text-sm font-medium text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                                {{ $owner->nama_mahasiswa }}
                                            </a>
                                            @if($owner->jurusan)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $owner->jurusan->nama_jurusan }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-500 dark:text-gray-400 text-sm italic">Belum ada owner project</p>
                                @endif
                            </div>

                            @if($leader && !$sameOwnerLeader)
                                <!-- Project Leader -->
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5">
                                    <h3
                                        class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <span data-translate="lead_pjt" data-translate-page="pjt_detail">Project Leader</span>
                                    </h3>

                                    <div
                                        class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 max-w-max">
                                        @if($leader->photo_profile)
                                            <img src="{{ asset('storage/' . ltrim($leader->photo_profile, '/')) }}"
                                                alt="{{ $leader->nama_mahasiswa ?? 'Mahasiswa' }}"
                                                class="w-10 h-10 rounded-full object-cover border-2 border-indigo-200 dark:border-indigo-900">
                                        @else
                                            <div
                                                class="w-10 h-10 bg-indigo-600 flex items-center justify-center text-white text-sm font-bold rounded-full border-2 border-indigo-200 dark:border-indigo-900">
                                                {{ strtoupper(mb_substr(trim($leader->nama_mahasiswa ?? 'L'), 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('portfolio.show', ['user' => $leader->username]) }}"
                                                class="text-sm font-medium text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                                {{ $leader->nama_mahasiswa }}
                                            </a>
                                            @if($leader->jurusan)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $leader->jurusan->nama_jurusan }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Team Members -->
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5">
                                <h3
                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span data-translate="tim_pjt" data-translate-page="pjt_detail">Anggota Tim</span> ({{ $project->members->where('id', '!=', $project->leader_id)->count() }})
                                </h3>

                                <div class="space-y-2">
                                    @forelse($project->members as $member)
                                    <a href="{{ route('portfolio.show', ['user' => $member->username]) }}">
                                        @if($member->id !== $project->leader_id)
                                            <div
                                                class="flex items-center gap-3 p-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 max-w-max">
                                                @if($member->photo_profile)
                                                    <img src="{{ asset('storage/' . ltrim($member->photo_profile, '/')) }}"
                                                        alt="{{ $member->nama_mahasiswa ?? 'Mahasiswa' }}"
                                                        class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                                @else
                                                    <div
                                                        class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-300 text-xs font-bold rounded-full border border-gray-200 dark:border-gray-600">
                                                        {{ strtoupper(mb_substr(trim($member->nama_mahasiswa ?? 'M'), 0, 1)) }}
                                                    </div>
                                                @endif
                                                <a href="{{ route('portfolio.show', ['user' => $member->username]) }}"
                                                    class="text-sm text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                                    {{ $member->nama_mahasiswa }}
                                                </a>
                                            </div>
                                        @endif
                                    </a>
                                    @empty
                                        <p class="text-gray-500 dark:text-gray-400 text-sm italic" data-translate="empty_tim" data-translate-page="pjt_detail">Belum ada anggota tim</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Links -->
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5">
                                <h3
                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                    <span data-translate="link_pjt" data-translate-page="pjt_detail">Link Terkait</span>
                                </h3>

                                <div class="space-y-3">
                                    @if(!empty($project->isi_content['link_project']))
                                        <a href="{{ $project->isi_content['link_project'] }}" target="_blank"
                                            class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-600 transition group">
                                            <div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                                    Website Proyek
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 break-all whitespace-normal">
                                                    {{ $project->isi_content['link_project'] }}
                                                </p>
                                            </div>
                                        </a>
                                    @endif

                                    @if(!empty($project->isi_content['link_github']))
                                        <a href="{{ $project->isi_content['link_github'] }}" target="_blank"
                                            class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 transition group">
                                            <div class="p-2 bg-gray-800 dark:bg-gray-700 rounded-lg">
                                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                                    <path
                                                        d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-gray-700 dark:group-hover:text-gray-300">
                                                    GitHub Repository</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 break-all whitespace-normal">
                                                    {{ $project->isi_content['link_github'] }}
                                                </p>
                                            </div>
                                        </a>
                                    @endif

                                    @if(!empty($project->isi_content['link_video']))
                                        <a href="{{ $project->isi_content['link_video'] }}" target="_blank"
                                            class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-red-300 dark:hover:border-red-600 transition group">
                                            <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-red-600 dark:group-hover:text-red-400">
                                                    Video Demo</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 break-all whitespace-normal">
                                                    {{ $project->isi_content['link_video'] }}
                                                </p>
                                            </div>
                                        </a>
                                    @endif

                                    @if(empty($project->isi_content['link_project']) && empty($project->isi_content['link_github']) && empty($project->isi_content['link_video']))
                                        <p class="text-gray-500 dark:text-gray-400 text-sm italic text-center py-4" data-translate="empty_link" data-translate-page="pjt_detail">Belum ada link
                                            terkait</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT SIDE -->
                    <div class="space-y-6">
                        <!-- Timeline -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5">
                            <h3
                                class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span data-translate="timeline_pjt" data-translate-page="pjt_detail">Timeline Proyek</span>
                            </h3>

                            <div class="flex items-center gap-4 text-sm">
                                <div
                                    class="flex-1 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Mulai</p>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d M Y') }}
                                    </p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                                <div
                                class="flex-1 p-3 bg-white dark:bg-gray-800 rounded-lg border {{ ($isOverdue && $isProjectMemberForBar) ? 'border-red-300 dark:border-red-700 bg-red-50 dark:bg-red-900/10' : 'border-gray-200 dark:border-gray-600' }}">
                                    <p class="text-xs {{ ($isOverdue && $isProjectMemberForBar) ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }} mb-1">
                                        <span data-translate="tgl_selesai" data-translate-page="pjt_detail">Selesai</span>
                                        @if($isOverdue && $isProjectMemberForBar)
                                            &mdash; <span class="font-semibold" data-translate="overdue_label" data-translate-page="pjt_detail">Terlambat</span>
                                        @endif
                                    </p>
                                    <p class="font-medium {{ ($isOverdue && $isProjectMemberForBar) ? 'text-red-700 dark:text-red-300' : 'text-gray-900 dark:text-white' }}">
                                        {{ $project->tanggal_akhir
                                            ? \Carbon\Carbon::parse($project->tanggal_akhir)->format('d M Y')
                                            : 'Belum ditentukan' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($showTaskSection)
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5">
                                <div class="flex items-center justify-between gap-4 mb-4">
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m2 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span data-translate="task_progress" data-translate-page="pjt_detail">Progress Task</span>
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $taskDoneCount }} dari {{ $taskTotalCount }} task selesai
                                        </p>
                                    </div>
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full
                                        {{ $isOverdue && $taskProgress < 100
                                            ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200'
                                            : 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-200'
                                        }}">
                                        {{ $taskProgress }}% Progress
                                    </span>
                                </div>

                                <!-- Task Section Progress Bar -->
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-4">
                                    <div class="{{ $taskBarColor }} h-2.5 rounded-full transition-all duration-500"
                                        style="width: {{ $taskProgress }}%"></div>
                                </div>

                                @if($visibleTasks->isEmpty())
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada task yang dapat ditampilkan.</p>
                                @else
                                    <div class="space-y-3">
                                        @foreach($visibleTasks as $task)
                                            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-600
                                                {{ $isOverdue && !$task->is_done ? 'border-l-4 border-l-red-500' : '' }}">
                                                <div class="flex items-start justify-between gap-4">
                                                    <div class="min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                            {{ $task->name_task }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                            <span data-translate="task_pic" data-translate-page="pjt_detail">Penanggung Jawab</span>: {{ $task->user?->nama_mahasiswa ?? 'Belum ditetapkan' }}
                                                        </p>
                                                        @if($isOverdue && !$task->is_done)
                                                            <p class="text-xs text-red-500 dark:text-red-400 font-medium mt-1"
                                                                data-translate="task_overdue_note"
                                                                data-translate-page="pjt_detail">
                                                                ⚠ Harap selesaikan tugas ini
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center gap-3">
                                                        <span class="text-xs font-semibold px-2 py-1 rounded-full
                                                            {{ $task->is_done
                                                                ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                                                : ($isOverdue
                                                                    ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                                                                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400')
                                                            }}">
                                                            @if($task->is_done)
                                                                <span data-translate="task_done" data-translate-page="pjt_detail">Selesai</span>
                                                            @elseif($isOverdue)
                                                                <span data-translate="task_overdue" data-translate-page="pjt_detail">Terlambat</span>
                                                            @else
                                                                <span data-translate="task_inprogress" data-translate-page="pjt_detail">Dalam Proses</span>
                                                            @endif
                                                        </span>
                                                        @auth
                                                            @if(!$task->is_done && auth()->user()->role === 'mahasiswa' && auth()->id() === $task->user_id)
                                                                <form method="POST" action="{{ route('project.tasks.complete', [$project->id, $task->id]) }}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white
                                                                        {{ $isOverdue ? 'bg-red-600 hover:bg-red-700' : 'bg-indigo-600 hover:bg-indigo-700' }}
                                                                        rounded-lg transition">
                                                                        <span data-translate="task_complete_btn" data-translate-page="pjt_detail">Selesaikan</span>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endauth
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Overdue warning di dalam task section (untuk member, admin, dan dosen) -->
                                @if($showOverdueWarning || ($isOverdue && $isAdminOrDosen))
                                    <div class="mt-4 flex items-center gap-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg px-3 py-2">
                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-xs text-red-700 dark:text-red-300 font-medium"
                                            data-translate="overdue_warning_desc"
                                            data-translate-page="pjt_detail">
                                            Harap selesaikan semua tugas yang masih dalam proses secepatnya.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Embed Box -->
                        @php
                            $video = $project->isi_content['link_video'] ?? null;
                            $github = $project->isi_content['link_github'] ?? null;
                            $projectLink = $project->isi_content['link_project'] ?? null;
                        @endphp

                        @if($video)
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5">
                                <h3
                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <span data-translate="video_preview" data-translate-page="pjt_detail">Preview Video</span>
                                </h3>
                                <div class="rounded-xl overflow-hidden shadow-lg border border-gray-200 dark:border-gray-600">
                                    @include('components.video_preview', [
                                        'link_video' => $video,
                                        'alt' => 'Video ' . $project->nama_project,
                                        'class' => 'w-full'
                                    ])
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- LEARNING CORNER SECTION -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 lg:p-8 hover:shadow-md transition-shadow duration-300">

                <!-- Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white" data-translate="lrn_pjt" data-translate-page="pjt_detail">Learning Corner</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" data-translate="desc_lrn_pjt" data-translate-page="pjt_detail">Catatan dan dokumentasi proyek</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        @auth
                            @php
                                $user = auth()->user();
                                $isProjectMember = $user && (
                                    $user->id === $project->id_mahasiswa ||
                                    $user->id === $project->leader_id ||
                                    $project->members->contains('id', $user->id)
                                );
                            @endphp

                            @if($isProjectMember)
                                <!-- Mass Delete Form for Owner/Leader -->
                                @if($user->id === $project->id_mahasiswa || $user->id === $project->leader_id)
                                    <form id="massDeleteForm" action="{{ route('learning-corner.mass-destroy') }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <input type="hidden" name="ids" id="massDeleteIds" value="">
                                        <button type="button" id="massDeleteBtn"
                                            class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-sm text-sm font-medium opacity-50 cursor-not-allowed flex items-center justify-center gap-2"
                                            disabled>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span><span data-translate="del_sel" data-translate-page="pjt_detail">Hapus Terpilih</span> (<span id="selectedCount">0</span>)</span>
                                        </button>
                                    </form>
                                @endif

                                <a id="project-detail-add-learning-corner" href="{{ route('learning-corner.create', ['project_id' => $project->id]) }}"
                                    class="w-full sm:w-auto px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm text-sm font-medium flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span data-translate="add_lrn" data-translate-page="pjt_detail">Tambah Catatan</span>
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                @if ($entries->isEmpty())
                    <div
                        class="text-center py-16 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-700">
                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-gray-600 dark:text-gray-400 text-lg font-medium" data-translate="empty_lrn" data-translate-page="pjt_detail">Belum ada catatan learning corner</p>
                        <p class="text-gray-500 dark:text-gray-500 text-sm mt-2" data-translate="desc_empty_lrn" data-translate-page="pjt_detail">Mulai tambahkan catatan pertama untuk proyek
                            ini</p>
                    </div>
                @else
                    <!-- Grid dengan 1 card vertikal -->
                    <div class="space-y-4 sm:space-y-5 lg:space-y-6">
                        @foreach ($entries as $index => $entry)
                            @php
                                $canManage = auth()->check() && (
                                    auth()->user()->role === 'admin' ||
                                    (auth()->user()->role === 'dosen' && $entry->canManageDosen(auth()->user())) ||
                                    $entry->canManage(auth()->user())
                                );
                                $isOwnerOrLeader = auth()->check() && (auth()->id() === $project->id_mahasiswa || auth()->id() === $project->leader_id);

                                // Parse content
                                $titles = [];
                                $texts = [];
                                $images = [];
                                $links = [];

                                if (!empty($entry->translated('content')) && is_array($entry->translated('content'))) {
                                    foreach ($entry->translated('content') as $item) {
                                        switch ($item['type'] ?? '') {
                                            case 'title':
                                                $titles[] = $item['content'] ?? '';
                                                break;
                                            case 'text':
                                                $texts[] = $item['content'] ?? '';
                                                break;
                                            case 'image':
                                                $images[] = $item['content'] ?? '';
                                                break;
                                            case 'link':
                                                $links[] = $item['content'] ?? '';
                                                break;
                                        }
                                    }
                                }
                            @endphp

                            <div
                                class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-200 dark:border-gray-700">
                                <!-- Selection Checkbox for Mass Delete -->
                                @if($isOwnerOrLeader)
                                    <div class="flex items-center mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
                                        <input type="checkbox"
                                            class="entry-checkbox w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            data-id="{{ $entry->id_learning_corner }}">
                                        <label data-translate="sel_del" data-translate-page="pjt_detail" class="ml-2 text-sm text-gray-600 dark:text-gray-400 cursor-pointer select-none">
                                            Pilih untuk dihapus
                                        </label>
                                    </div>
                                @endif

                                <div class="flex flex-col lg:flex-row gap-6">
                                    <!-- Left Side: Titles, Texts, Links -->
                                    <div class="flex-1 space-y-4">
                                        <!-- Author Info -->
                                        <div class="flex items-center gap-2 mb-2">
                                            <div
                                                class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                                <span class="text-xs font-bold text-indigo-700 dark:text-indigo-300">
                                                    {{ strtoupper(mb_substr(trim($entry->mahasiswa->nama_mahasiswa ?? 'U'), 0, 1)) }}
                                                </span>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ $entry->mahasiswa->nama_mahasiswa ?? 'Unknown' }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-auto">
                                                {{ $entry->created_at?->format('d M Y H:i') ?? ($entry->tanggal?->format('d M Y') ?? '-') }}
                                            </span>
                                        </div>

                                        <!-- Titles -->
                                        @foreach($titles as $title)
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ $title }}
                                            </h3>
                                        @endforeach

                                        <!-- Texts -->
                                        @foreach($texts as $text)
                                            <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                                                {{ $text }}
                                            </p>
                                        @endforeach

                                        <!-- Links -->
                                        @if(!empty($links))
                                            <div class="space-y-3 mt-3">
                                                @foreach($links as $link)
                                                    @php
                                                        // Detect YouTube URL and extract video ID
                                                        $youtubeId = '';
                                                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $link, $matches)) {
                                                            $youtubeId = $matches[1];
                                                        }
                                                    @endphp

                                                    @if($youtubeId)
                                                        <!-- YouTube iframe -->
                                                        <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm">
                                                            <div class="aspect-video relative">
                                                                <iframe width="100%" height="100%" style="position: absolute; top: 0; left: 0; border: none; border-radius: inherit;"
                                                                    src="https://www.youtube.com/embed/{{ $youtubeId }}"
                                                                    title="YouTube video player" 
                                                                    frameborder="0" 
                                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                                                    referrerpolicy="strict-origin-when-cross-origin" 
                                                                    allowfullscreen>
                                                                </iframe>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <!-- Regular link -->
                                                        <a href="{{ $link }}" target="_blank" rel="noopener noreferrer"
                                                            class="inline-flex items-center gap-2 text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline break-all bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1.5 rounded-lg">
                                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                            </svg>
                                                            <span class="break-all whitespace-normal max-w-xs">{{ $link }}</span>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Action Buttons -->
                                        @auth
                                            @if ($canManage)
                                                <div class="flex gap-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                                    @unless(in_array(auth()->user()->role, ['admin', 'dosen']))
                                                        <a href="{{ route('learning-corner.edit', ['id' => $entry->id_learning_corner]) }}"
                                                            class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition text-sm font-medium shadow-sm hover:shadow-md">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-7-10l3 3m0 0l-3 3m3-3H9"/>
                                                            </svg>
                                                            <span data-translate="edit" data-translate-page="pjt_detail">Edit</span>
                                                        </a>
                                                    @endunless

                                                    <form action="{{ route('learning-corner.destroy', ['id' => $entry->id_learning_corner]) }}"
                                                        method="POST" class="flex-1 delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" data-translate="del" data-translate-page="pjt_detail"
                                                            class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 active:bg-red-800 transition text-sm font-medium delete-btn shadow-sm hover:shadow-md">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                            <span data-translate="hapus" data-translate-page="pjt_detail">Hapus</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>

                                    <!-- Right Side: Images -->
                                    @if(!empty($images))
                                        <div class="lg:w-80 flex-shrink-0">
                                            <div class="grid grid-cols-2 gap-2">
                                                @foreach($images as $image)
                                                    @php
                                                        $imagePath = str_replace(['\\', '/'], '/', $image);
                                                    @endphp
                                                    <div class="relative group cursor-pointer aspect-square overflow-hidden rounded-lg border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-600 transition"
                                                        onclick="openImageModal('{{ asset('storage/' . ltrim($imagePath, '/')) }}')">
                                                        <img src="{{ asset('storage/' . ltrim($imagePath, '/')) }}" alt="Gambar konten"
                                                            class="w-full h-full object-cover transition duration-300 group-hover:scale-110"
                                                            loading="lazy"
                                                            onerror="this.onerror=null; this.src='https://via.placeholder.com/400x200?text=Gambar+Tidak+Ditemukan';">

                                                        <!-- Overlay -->
                                                        <div
                                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($entries, 'links'))
                        <div class="mt-8" data-pagination-group="project_entries">
                            {{ $entries->render('vendor.pagination.custom_ajax', ['groupName' => 'project_entries']) }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal"
        class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4 transition-opacity duration-300"
        onclick="closeImageModal()">
        <div class="relative max-w-6xl max-h-[90vh] w-full" onclick="event.stopPropagation()">
            <button onclick="closeImageModal()"
                class="absolute -top-12 right-0 text-white/80 hover:text-white transition p-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalImage" src="" alt="Full size image" class="w-full h-auto max-h-[90vh] object-contain rounded-lg">
        </div>
    </div>

@endsection