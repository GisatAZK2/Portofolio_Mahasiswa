@extends('Layout.Layout')

@section('content')
    <div class="p-6 lg:p-8 max-w-7xl mx-auto dark:bg-gray-700 rounded-2xl">

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-700 border border-green-200 text-green-800 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Header -->
        <div class="mb-10 text-center md:text-left">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Project Mahasiswa</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-300">Beberapa Pameran Project Mahasiswa </p>
        </div>


        <!-- Projects -->
        <section class=" shadow-sm p-6 md:p-8 ">

            @if($projects->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($projects as $project)
                        @php
                            $content = $project->isi_content ?? [];
                            $nama = $content['nama_project'] ?? 'Tanpa Nama Project';
                            $deskripsi = $content['deskripsi'] ?? null;
                            $linkProject = $content['link_project'] ?? null;
                            $linkGithub = $content['link_github'] ?? null;
                            $linkVideo = $content['link_video'] ?? null;

                            // Tanggal dari field model (bukan dari JSON)
                            $mulaiRaw = $project->tanggal_mulai ?? null;
                            $akhirRaw = $project->tanggal_akhir ?? null;

                            $mulai = $mulaiRaw ? \Carbon\Carbon::parse($mulaiRaw) : null;
                            $akhir = $akhirRaw ? \Carbon\Carbon::parse($akhirRaw) : null;
                            $today = \Carbon\Carbon::today();

                            $mulaiFormatted = $mulai ? $mulai->translatedFormat('M Y') : '—';
                            $akhirFormatted = $akhir ? $akhir->translatedFormat('M Y') : 'Sekarang';

                            // Status logic
                            $status = '—';
                            $statusClass = 'bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-200';
                            $statusText = 'Tidak diketahui';

                            if ($mulai && $akhir) {
                                if ($akhir < $today) {
                                    $status = 'Past';
                                    $statusClass = 'bg-red-100 text-red-800 dark:bg-red-800 dark:bg-text-red-500';
                                    $statusText = 'Selesai';
                                } elseif ($mulai <= $today && $today <= $akhir) {
                                    $status = 'Now';
                                    $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                    $statusText = 'Sedang Berjalan';
                                } elseif ($mulai > $today) {
                                    $status = 'Coming';
                                    $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                                    $statusText = 'Akan Datang';
                                }
                            } elseif ($mulai && !$akhir) {
                                if ($mulai <= $today) {
                                    $status = 'Now';
                                    $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                    $statusText = 'Sedang Berjalan';
                                } else {
                                    $status = 'Coming';
                                    $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                                    $statusText = 'Akan Datang';
                                }
                            } elseif (!$mulai && $akhir) {
                                if ($akhir < $today) {
                                    $status = 'Past';
                                    $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                                    $statusText = 'Selesai';
                                }
                            }

                            // YouTube embed
                            $embedVideo = null;
                            if ($linkVideo) {
                                preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([^"&?\/\s]{11})/i', $linkVideo, $matches);
                                if (!empty($matches[1])) {
                                    $embedVideo = "https://www.youtube.com/embed/" . $matches[1];
                                }
                            }
                        @endphp

                        <div onclick="window.location='{{ route('project.show', $project->id) }}'" class=" shadow-sm hover:shadow-md transition-shadow dark:bg-gray-800 duration-200 overflow-hidden flex flex-col h-full hover:cursor-pointer">
                            <!-- Media Header -->
                            @if($embedVideo)
                                <div class="relative w-full pb-[56.25%] bg-black dark:bg-white">
                                    <iframe class="absolute inset-0 w-full h-full" src="{{ $embedVideo }}" title="Video: {{ $nama }}"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                                </div>
                            @elseif($linkProject)
                                <div class="w-full h-48 bg-gradient-to-br from-indigo-50 to-blue-50 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @else
                                <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif

                            <!-- Content -->
                            <div class="p-5 flex flex-col flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 line-clamp-2 flex-1 pr-3">
                                        {{ $nama }}
                                    </h3>
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }} whitespace-nowrap">
                                        {{ $statusText }}
                                    </span>
                                </div>

                                <div class="text-sm text-gray-600 dark:text-gray-300 mb-3 flex items-center gap-2 flex-wrap">
                                    <span>Mulai: {{ $mulaiFormatted }}</span>
                                    <span class="text-gray-400 dark:text-gray-300">→</span>
                                    <span>Selesai: {{ $akhirFormatted }}</span>
                                </div>

                                @if($deskripsi)
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3 flex-1">
                                        {{ $deskripsi }}
                                    </p>
                                @else
                                    <p class="text-gray-500 dark:text-gray-50 text-sm mb-4 italic flex-1">Tidak ada deskripsi</p>
                                @endif

                                <div class="flex flex-wrap gap-3 mt-auto pt-4 border-t border-gray-100 dark:border-gray-900">
                                    @if($linkProject)
                                        <a href="{{ $linkProject }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                            Website
                                        </a>
                                    @endif

                                    @if($linkGithub)
                                        <a href="{{ $linkGithub }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-800 dark:text-gray-200 hover:text-black">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                <path
                                                    d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                                            </svg>
                                            GitHub
                                        </a>
                                    @endif

                                    @if($linkVideo && !$embedVideo)
                                        <a href="{{ $linkVideo }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600 hover:text-red-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Video
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <div class="text-center py-12 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-4 text-gray-600 dark:text-gray-200">Belum ada proyek.</p>
                </div>
            @endif

        </section>

    </div>
@endsection