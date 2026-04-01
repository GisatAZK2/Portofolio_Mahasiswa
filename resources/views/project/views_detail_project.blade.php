@extends('Layout.Layout')

@section('content')
    <!-- CONTENT -->
    <div class="min-h-screen bg-gray-50 dark:bg-gray-800 transition-colors duration-200">
        <div class="p-4 sm:p-6 lg:p-10 space-y-6 sm:space-y-8 lg:space-y-10 max-w-7xl mx-auto">

            <!-- PROJECT CARD -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 lg:p-8 hover:shadow-md transition-shadow duration-300">

                <!-- Project Status Bar -->
                <div class="mb-6">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}"
                                data-translate="status_{{ $status }}" data-translate-page="project_detail">
                                {{ $statusText }}
                            </span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $projectProgress }}% <span data-translate="progress_done"
                                    data-translate-page="project_detail">Selesai</span>
                            </span>
                        </div>

                        <!-- Project Title -->
                        <h1
                            class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white order-first sm:order-none w-full sm:w-auto">
                            {{ $project->isi_content['nama_project'] ?? 'Tanpa Judul' }}
                        </h1>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-indigo-600 dark:bg-indigo-500 h-2.5 rounded-full transition-all duration-500"
                            style="width: {{ $projectProgress }}%"></div>
                    </div>
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
                                Deskripsi Proyek
                            </h3>
                            <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                                {{ $project->isi_content['deskripsi'] ?? 'Tidak ada deskripsi untuk proyek ini.' }}
                            </p>
                        </div>

                        <!-- Team Section -->
                        @php
                            $owner = $project->mahasiswa;
                            $leader = $project->leader;
                            $sameOwnerLeader = $owner && $leader && $owner->id === $leader->id;
                        @endphp
                        <div class="space-y-4">
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
                                            <a href="{{ route('portfolio.show', $owner->id) }}"
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
                                        Project Leader
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
                                            <a href="{{ route('portfolio.show', $leader->id) }}"
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
                                    Anggota Tim ({{ $project->members->where('id', '!=', $project->leader_id)->count() }})
                                </h3>

                                <div class="space-y-2">
                                    @forelse($project->members as $member)
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
                                                <a href="{{ route('portfolio.show', $member->id) }}"
                                                    class="text-sm text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                                    {{ $member->nama_mahasiswa }}
                                                </a>
                                            </div>
                                        @endif
                                    @empty
                                        <p class="text-gray-500 dark:text-gray-400 text-sm italic">Belum ada anggota tim</p>
                                    @endforelse
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
                                Timeline Proyek
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
                                    class="flex-1 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Selesai</p>
                                    <p class="font-medium text-gray-900 dark:text-white">
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
                                            Task Proyek
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $taskDoneCount }} dari {{ $taskTotalCount }} task selesai
                                        </p>
                                    </div>
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-200">
                                        {{ $projectProgress }}% Progress
                                    </span>
                                </div>

                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-4">
                                    <div class="bg-indigo-600 dark:bg-indigo-500 h-2.5 rounded-full transition-all duration-500"
                                        style="width: {{ $projectProgress }}%"></div>
                                </div>

                                @if($visibleTasks->isEmpty())
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada task yang dapat ditampilkan.</p>
                                @else
                                    <div class="space-y-3">
                                        @foreach($visibleTasks as $task)
                                            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-600">
                                                <div class="flex items-start justify-between gap-4">
                                                    <div class="min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                            {{ $task->name_task }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                            Penanggung Jawab: {{ $task->user?->nama_mahasiswa ?? 'Belum ditetapkan' }}
                                                        </p>
                                                    </div>
                                                    <div class="flex items-center gap-3">
                                                        <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $task->is_done ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                                                            {{ $task->is_done ? 'Selesai' : 'Dalam Proses' }}
                                                        </span>
                                                        @auth
                                                            @if(!$task->is_done && (
                                                                auth()->user()->role !== 'mahasiswa' ||
                                                                auth()->id() === $task->user_id
                                                            ))
                                                                <form method="POST" action="{{ route('project.tasks.complete', [$project->id, $task->id]) }}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                                                                        Selesaikan
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
                            </div>
                        @endif

                        <!-- Links -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5">
                            <h3
                                class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                                Link Terkait
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
                                        <div class="flex-1">
                                            <p
                                                class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                                Website Proyek</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
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
                                        <div class="flex-1">
                                            <p
                                                class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-gray-700 dark:group-hover:text-gray-300">
                                                GitHub Repository</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
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
                                        <div class="flex-1">
                                            <p
                                                class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-red-600 dark:group-hover:text-red-400">
                                                Video Demo</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                {{ $project->isi_content['link_video'] }}
                                            </p>
                                        </div>
                                    </a>
                                @endif

                                @if(empty($project->isi_content['link_project']) && empty($project->isi_content['link_github']) && empty($project->isi_content['link_video']))
                                    <p class="text-gray-500 dark:text-gray-400 text-sm italic text-center py-4">Belum ada link
                                        terkait</p>
                                @endif
                            </div>
                        </div>

                        <!-- Embed Box -->
                        @php
                            $video = $project->isi_content['link_video'] ?? null;
                            $github = $project->isi_content['link_github'] ?? null;
                            $projectLink = $project->isi_content['link_project'] ?? null;
                        @endphp

                        @if($video)
                            @php
                                $embed = null;
                                if (str_contains($video, 'watch?v=')) {
                                    $embed = str_replace('watch?v=', 'embed/', $video);
                                } elseif (str_contains($video, 'youtu.be/')) {
                                    $embed = str_replace('youtu.be/', 'youtube.com/embed/', $video);
                                }
                            @endphp

                            @if($embed)
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5">
                                    <h3
                                        class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        Preview Video
                                    </h3>
                                    <div class="rounded-xl overflow-hidden shadow-lg border border-gray-200 dark:border-gray-600">
                                        <iframe class="w-full aspect-video" src="{{ $embed }}" frameborder="0" allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            @endif
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
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Learning Corner</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Catatan dan dokumentasi proyek</p>
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
                                            <span>Hapus Terpilih (<span id="selectedCount">0</span>)</span>
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('learning-corner.create', $project->id) }}"
                                    class="w-full sm:w-auto px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm text-sm font-medium flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span>Tambah Catatan</span>
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
                        <p class="text-gray-600 dark:text-gray-400 text-lg font-medium">Belum ada catatan learning corner</p>
                        <p class="text-gray-500 dark:text-gray-500 text-sm mt-2">Mulai tambahkan catatan pertama untuk proyek
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

                                if (!empty($entry->content) && is_array($entry->content)) {
                                    foreach ($entry->content as $item) {
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
                                        <label class="ml-2 text-sm text-gray-600 dark:text-gray-400 cursor-pointer select-none">
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
                                            <div class="space-y-2 mt-3">
                                                @foreach($links as $link)
                                                    <a href="{{ $link }}" target="_blank" rel="noopener noreferrer"
                                                        class="inline-flex items-center gap-2 text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline break-all bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1.5 rounded-lg">
                                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                        </svg>
                                                        <span class="truncate max-w-xs">{{ $link }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Action Buttons -->
                                        @auth
                                            @if ($canManage)
                                                <div class="flex gap-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                                    @unless(in_array(auth()->user()->role, ['admin', 'dosen']))
                                                        <a href="{{ route('learning-corner.edit', $entry->id_learning_corner) }}"
                                                            class="flex-1 text-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                                                            Edit
                                                        </a>
                                                    @endunless

                                                    <form action="{{ route('learning-corner.destroy', $entry->id_learning_corner) }}"
                                                        method="POST" class="flex-1 delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium delete-btn">
                                                            Hapus
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Individual delete buttons
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', async function (e) {
                    e.preventDefault();

                    const form = this.closest('.delete-form');
                    if (!form) return;

                    const confirmed = await showConfirmAlert({
                        title: 'Hapus Catatan?',
                        text: 'Catatan ini akan dihapus permanen dan tidak bisa dikembalikan.',
                        icon: 'warning',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                    });

                    if (confirmed) {
                        showLoading('Menghapus catatan...');
                        form.submit();
                    }
                });
            });

            // Mass delete functionality
            const checkboxes = document.querySelectorAll('.entry-checkbox');
            const massDeleteBtn = document.getElementById('massDeleteBtn');
            const massDeleteIds = document.getElementById('massDeleteIds');
            const massDeleteForm = document.getElementById('massDeleteForm');
            const selectedCount = document.getElementById('selectedCount');

            if (checkboxes.length > 0 && massDeleteBtn && massDeleteForm && selectedCount) {
                function updateMassDeleteButton() {
                    const checkedBoxes = document.querySelectorAll('.entry-checkbox:checked');
                    const checkedCount = checkedBoxes.length;

                    selectedCount.textContent = checkedCount;

                    // Update hidden input with selected IDs
                    const selectedIds = Array.from(checkedBoxes).map(cb => cb.dataset.id);
                    massDeleteIds.value = JSON.stringify(selectedIds);

                    if (checkedCount > 0) {
                        massDeleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        massDeleteBtn.disabled = false;
                    } else {
                        massDeleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        massDeleteBtn.disabled = true;
                    }
                }

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateMassDeleteButton);
                });

                massDeleteBtn.addEventListener('click', async function () {
                    const checkedCount = document.querySelectorAll('.entry-checkbox:checked').length;

                    if (checkedCount === 0) return;

                    const confirmed = await showConfirmAlert({
                        title: 'Hapus Multiple Catatan?',
                        text: `Anda akan menghapus ${checkedCount} catatan. Tindakan ini tidak dapat dibatalkan.`,
                        icon: 'warning',
                        confirmButtonText: 'Ya, Hapus Semua',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                    });

                    if (confirmed) {
                        showLoading('Menghapus catatan terpilih...');

                        // Parse IDs from hidden input
                        const ids = JSON.parse(massDeleteIds.value);

                        // Create a new form with the IDs as array
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = massDeleteForm.action;

                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);

                        ids.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = id;
                            form.appendChild(input);
                        });

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            @if (session('success'))
                showSuccessAlert('{{ session('success') }}');
            @endif

            @if (session('error'))
                showErrorAlert('{{ session('error') }}');
            @endif
                });

        // Image Modal Functions
        function openImageModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageSrc;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
@endsection