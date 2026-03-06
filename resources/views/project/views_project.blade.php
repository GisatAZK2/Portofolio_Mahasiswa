@extends('Layout.Layout')

@section('title', 'Project Saya')

@section('content')
    <div class="p-6 lg:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-50">Project Saya</h1>
                <p class="text-gray-600 dark:text-gray-200 mt-1">
                    Kelola semua postingan project kamu di sini.
                </p>
            </div>
            <a href="{{ route('project.create') }}"
                class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Project Baru
            </a>
        </div>

        @if ($projects->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900">
                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-4 text-gray-600 dark:text-gray-200">Belum ada project yang ditambahkan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($projects as $project)
                    @php
                        $content = is_string($project->isi_content)
                            ? json_decode($project->isi_content, true)
                            : (array) $project->isi_content;

                        $nama_project = $content['nama_project'] ?? '-';
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
                    @endphp

                    <div
                        onclick="window.location='{{ route('project.show', $project->id) }}'"
                        class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-100 dark:bg-gray-900 dark:border-gray-900 cursor-pointer">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3 line-clamp-2">
                                {{ $nama_project }}
                            </h3>

                            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-200  mb-4">
                                <p><span class="font-medium">Mulai:</span>
                                    {{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d M Y') }}</p>
                                @if ($project->tanggal_akhir)
                                    <p><span class="font-medium dark:text-gray-200 ">Selesai:</span>
                                        {{ \Carbon\Carbon::parse($project->tanggal_akhir)->format('d M Y') }}</p>
                                @endif
                            </div>

                            {{-- Video YouTube Embed (pakai aspect-video Tailwind native) --}}
                            @if ($youtube_id)
                                <div class="mb-5 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                    <div class="aspect-video w-full">
                                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $youtube_id }}?rel=0"
                                            title="YouTube video player for {{ $nama_project }}" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            @endif

                            {{-- Links dengan icon --}}
                            @if ($link_project || $link_github || ($link_video && !$youtube_id))
                                <div class="flex flex-wrap gap-5 text-sm mb-6">
                                    @if ($link_project)
                                        <a href="{{ $link_project }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center text-indigo-600 hover:underline hover:text-indigo-800 font-medium">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                            </svg>
                                            Lihat Project
                                        </a>
                                    @endif

                                    @if ($link_github)
                                        <a href="{{ $link_github }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center text-indigo-600 hover:underline hover:text-indigo-800 font-medium">
                                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
                                            </svg>
                                            GitHub
                                        </a>
                                    @endif

                                    @if ($link_video && !$youtube_id)
                                        <a href="{{ $link_video }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center text-indigo-600 hover:underline hover:text-indigo-800 font-medium">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Video
                                        </a>
                                    @endif
                                </div>
                            @endif

                            <div class="flex space-x-3 mt-4">
                                <a href="{{ route('project.edit', $project->id) }}" onclick="event.stopPropagation()"
                                    class="flex-1 text-center py-2.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition font-medium">
                                    Edit
                                </a>
                                <form class="delete-form flex-1" action="{{ route('project.destroy', $project->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="event.stopPropagation()"
                                        class="delete-btn w-full py-2.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition font-medium">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', async function (e) {
                    e.preventDefault();
                    const confirmed = await showConfirmAlert({
                        title: 'Hapus Project?',
                        text: 'Project ini akan dihapus permanen dan tidak bisa dikembalikan.',
                        icon: 'warning',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                    });
                    if (confirmed) {
                        showLoading('Menghapus project...');
                        this.closest('form').submit();
                    }
                });
            });

            @if (session('success'))
                showSuccessAlert('{{ session('success') }}');
            @endif
    });
    </script>
@endsection