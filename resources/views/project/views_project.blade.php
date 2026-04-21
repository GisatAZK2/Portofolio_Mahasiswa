@extends('Layout.Layout')

@section('title', autoTranslate('Project Saya'))

@section('content')
    <div class="p-6 lg:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 data-translate="project_saya" data-translate-page="project"
                    class="text-3xl font-bold text-gray-900 dark:text-gray-50"></h1>
                <p class="text-gray-600 dark:text-gray-200 mt-1">
                    <span data-translate="deskripsi" data-translate-page="project"></span>
                </p>
            </div>
            <a href="{{ route('project.create', ['locale' => get_current_locale()]) }}"
                class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span data-translate="tambah_project" data-translate-page="project"></span>
            </a>
        </div>

        @if ($projects->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900">
                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-4 text-gray-600 dark:text-gray-200" data-translate="no_project" data-translate-page="project"></p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($projects as $project)
                    @php
                        $content = is_string($project->isi_content)
                            ? json_decode($project->isi_content, true)
                            : (array) $project->isi_content;

                        $nama_project = $content['nama_project'] ?? 'Tanpa Judul';
                        $deskripsi = $content['deskripsi'] ?? null;
                        $link_project = $content['link_project'] ?? null;
                        $link_github = $content['link_github'] ?? null;
                        $link_video = $content['link_video'] ?? null;

                        // Ekstrak YouTube ID
                        $youtube_id = null;
                        if ($link_video) {
                            $patterns = [
                                '#(?:https?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/|v\/)?([a-zA-Z0-9_-]{11})#i',
                                '#(?:https?:\/\/)?(?:www\.)?youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})#i',
                            ];
                            foreach ($patterns as $pattern) {
                                if (preg_match($pattern, $link_video, $matches)) {
                                    $youtube_id = $matches[1];
                                    break;
                                }
                            }
                        }

                        // Tentukan role user saat ini di project ini
                        $userId = auth()->id();
                        $role = 'Anggota';
                        $roleClass = 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300';

                        if ($project->id_mahasiswa == $userId) {
                            $role = 'Pemilik';
                            $roleClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                        } elseif ($project->leader_id == $userId) {
                            $role = 'Leader';
                            $roleClass = 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200';
                        }

                        $canEdit = ($project->id_mahasiswa == $userId || $project->leader_id == $userId);
                    @endphp

                    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-100 dark:border-gray-800 cursor-pointer group"
                        onclick="window.location='{{ route('project.show', ['id' => $project->id]) }}'">

                        <div class="p-6">
                            <!-- Badge Role -->
                            <div class="mb-3">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleClass }}"
                                    data-translate="{{ strtolower($role) }}" data-translate-page="project">
                                    {{ autoTranslate($role) }}
                                </span>
                            </div>

                            <h3
                                class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                {{ autoTranslate($nama_project) }}
                            </h3>

                            <!-- Info owner & leader -->
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                <span data-translate="dibuat_oleh" data-translate-page="project"></span>:
                                {{ $project->owner->nama_mahasiswa ?? '-' }} •
                                <span data-translate="leader" data-translate-page="project"></span>:
                                {{ $project->leader->nama_mahasiswa ?? '-' }}
                            </p>

                            <!-- Tanggal -->
                            <div class="space-y-1 text-sm text-gray-600 dark:text-gray-300 mb-4">
                                <p><span class="font-medium" data-translate="mulai" data-translate-page="project"></span>:
                                    {{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d M Y') }}
                                </p>
                                @if ($project->tanggal_akhir)
                                    <p><span class="font-medium" data-translate="selesai" data-translate-page="project"></span>:
                                        {{ \Carbon\Carbon::parse($project->tanggal_akhir)->format('d M Y') }}
                                    </p>
                                @endif
                            </div>

                            <!-- Deskripsi singkat (opsional) -->
                            @if ($deskripsi)
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">
                                    {{ Str::limit($deskripsi, 120) }}
                                </p>
                            @endif

                            <!-- Video Embed -->
                            @if ($youtube_id)
                                <div class="mb-5 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm">
                                    @include('components.video_preview', [
                                        'link_video' => $link_video,
                                        'alt' => 'Video ' . autoTranslate($nama_project),
                                        'class' => 'w-full'
                                    ])
                                </div>
                            @endif

                            <!-- Links -->
                            @if ($link_project || $link_github || ($link_video && !$youtube_id))
                                <div class="flex flex-wrap gap-4 text-sm mb-6">
                                    @if ($link_project)
                                        <a href="{{ $link_project }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg> <span data-translate="lihat_project" data-translate-page="project"></span>
                                        </a>
                                    @endif

                                    @if ($link_github)
                                        <a href="{{ $link_github }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
                                            </svg>
                                            <span data-translate="github" data-translate-page="project"></span>
                                        </a>
                                    @endif

                                    @if ($link_video && !$youtube_id)
                                        <a href="{{ $link_video }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span data-translate="video" data-translate-page="project"></span>
                                        </a>
                                    @endif
                                </div>
                            @endif

                            <!-- Tombol Aksi - hanya untuk Pemilik & Leader -->
                            @if ($canEdit)
                                <div class="flex gap-3 mt-5 border-t dark:border-gray-700 pt-5">
                                    <a href="{{ route('project.edit', ['locale' => app()->getLocale(), 'id' => $project->id]) }}"
                                        class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 px-4 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition font-medium dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 dark:active:bg-blue-900/70 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-7-10l3 3m0 0l-3 3m3-3H9"/>
                                        </svg>
                                        <span data-translate="edit_project" data-translate-page="project"></span>
                                    </a>
                                    <form class="delete-form flex-1" action="{{ route('project.destroy', $project->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="delete-btn w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 active:bg-red-200 transition font-medium dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 dark:active:bg-red-900/70 shadow-sm hover:shadow-md">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span data-translate="hapus_project" data-translate-page="project"></span>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>


    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.semua_project");
        });
    </script>
@endsection