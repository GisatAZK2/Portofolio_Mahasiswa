@extends('Layout.Layout')

@section('content')
    <!-- CONTENT -->
    <!-- CONTENT -->
    <div class=" p-10 space-y-10">

        <!-- PROJECT CARD -->
        <div class="bg-white rounded-xl shadow-md p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- LEFT SIDE -->
                <div>
                    <h2 class="text-2xl font-bold mb-4">
                        {{ $project->isi_content['nama_project'] ?? 'Tanpa Judul' }}
                    </h2>

                    <p class="font-semibold">Deskripsi:</p>
                    <p class="text-gray-700 mb-6">
                        {{ $project->isi_content['nama_project'] ?? 'Tanpa Judul' }}
                    </p>

                    <p class="font-semibold">Siswa Terlibat:</p>
                    <p class="mt-2">
                        <span class="font-medium">Project Leader:</span> 
                        {{ $project->leader->nama_mahasiswa ?? 'Tidak ada leader' }}
                    </p>

                    <div class="mt-3 space-y-1 text-gray-700">
                        @forelse($project->members as $member)
                        <p>{{ $member->nama_mahasiswa }}</p>
                        @empty
                        <p class="text-gray-400">Tidak ada rekan</p>
                        @endforelse
                    </div>
                </div>

                <!-- RIGHT SIDE -->
                <div>
                    <p class="font-semibold mb-2">Time Period</p>
                    <div class="pb-2">

                    {{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d/m/Y') }}
                    →
                    {{ $project->tanggal_akhir 
                    ? \Carbon\Carbon::parse($project->tanggal_akhir)->format('d/m/Y') 
                    : '-' }}
                    </div>
                    
                    <p class="font-semibold mb-2">
                        Link:
                    </p>
                    
        <div class="flex gap-4 text-blue-500 mb-6">
        @if(!empty($project->isi_content['link_video']))
        <a href="{{ $project->isi_content['link_video'] }}" 
           target="_blank"
           class="hover:underline">
           🎥 Video
        </a>
        @endif
        
        @if(!empty($project->isi_content['link_github']))
        <a href="{{ $project->isi_content['link_github'] }}" 
           target="_blank"
           class="hover:underline">
           💻 GitHub
        </a>
        @endif
        
        @if(!empty($project->isi_content['link_project']))
        <a href="{{ $project->isi_content['link_project'] }}" 
            target="_blank"
            class="hover:underline">
            🌐 Project
        </a>
        @endif
    </div>
    
    <!-- Embed Box -->
    @php
    $video = $project->isi_content['link_video'] ?? null;
    $github = $project->isi_content['link_github'] ?? null;
    $projectLink = $project->isi_content['link_project'] ?? null;
    @endphp

    @if($video)
        {{-- VIDEO PRIORITY --}}
        @php
            // Convert youtube link ke embed format
            $embed = null;

            if (str_contains($video, 'watch?v=')) {
                $embed = str_replace('watch?v=', 'embed/', $video);
            } elseif (str_contains($video, 'youtu.be/')) {
                $embed = str_replace('youtu.be/', 'youtube.com/embed/', $video);
            }
        @endphp

        @if($embed)
            <div class="rounded-xl overflow-hidden shadow">
                <iframe 
                    class="w-full h-64"
                    src="{{ $embed }}"
                    frameborder="0"
                    allowfullscreen>
                </iframe>
            </div>
        @endif

    @elseif($github || $projectLink)
        {{-- WEBSITE FALLBACK --}}
        <div class="rounded-xl overflow-hidden shadow">
            <iframe 
                class="w-full h-64"
                src="{{ $github ?? $projectLink }}"
                frameborder="0">
            </iframe>
        </div>

    @endif
                </div>

            </div>
        </div>

        <!-- LEARNING CORNER -->
        <div class="bg-white rounded-xl shadow-md p-8">
            <h2 class="text-2xl font-bold mb-6">
                Learning Corner
            </h2>

            <div class="w-full md:w-1/2 bg-gray-100 rounded-xl overflow-hidden shadow">

                <div class="bg-gray-800 h-48 flex items-center justify-center text-white">
                    embed video/github
                </div>

                <div class="p-4">
                    <h3 class="font-bold text-lg">
                        Title Title Title Title
                    </h3>
                    <p class="text-gray-600 mt-2">
                        Body body body body body body body...
                    </p>

                    <div class="mt-4 text-right">
                        <a href="#" class="text-blue-500">
                            Lihat lebih lengkap >>
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection