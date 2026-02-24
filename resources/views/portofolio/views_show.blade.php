@extends('Layout.Layout')
@section('title', ($user->nama_mahasiswa ?? 'Mahasiswa') . ' | Portfolio')

@section('content')
<div class="min-h-screen bg-gray-50 pb-16">

    <!-- Hero + Avatar -->
    <div class="relative mb-12">
        <div class="h-48 md:h-64 lg:h-80 bg-indigo-600 rounded-b-3xl overflow-hidden">
            <div class="absolute inset-0 bg-black/5"></div>
        </div>
        <div class="absolute -bottom-12 left-6 md:left-10 lg:left-12 z-10">
            <div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white bg-white shadow-md overflow-hidden">
                @if($user->photo_profile)
                    <img src="{{ asset('storage/' . ltrim($user->photo_profile, '/')) }}"
                         alt="{{ $user->nama_mahasiswa ?? 'Mahasiswa' }}"
                         class="w-full h-full object-cover"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->nama_mahasiswa ?? 'M') }}&background=random';">
                @else
                    <div class="w-full h-full bg-indigo-600 flex items-center justify-center text-white text-5xl font-bold">
                        {{ strtoupper(mb_substr(trim($user->nama_mahasiswa ?? 'M'), 0, 1)) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Header Info -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                {{ trim($user->nama_mahasiswa ?? 'Mahasiswa') }}
            </h1>
            <p class="text-xl text-gray-700 mb-4">
                {{ trim($user->nama_mahasiswa ?? 'Mahasiswa') }} | {{ $user->jurusan?->nama_jurusan ?? 'Jurusan Tidak Diketahui' }}
            </p>

            <div class="flex flex-wrap gap-6 mt-4">
                <div>
                    <span class="text-sm font-medium text-gray-500 block mb-1">Jurusan</span>
                    <span class="inline-flex items-center px-4 py-1.5 bg-blue-50 text-blue-800 rounded-full text-sm font-medium">
                        {{ $user->jurusan?->nama_jurusan ?? 'Belum ditentukan' }}
                    </span>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500 block mb-1">Keahlian Utama</span>
                    @if($user->keahlian)
                        <span class="inline-flex items-center px-4 py-1.5 bg-purple-50 text-purple-800 rounded-full text-sm font-medium">
                            {{ $user->keahlian->nama_keahlian }}
                        </span>
                    @else
                        <span class="text-sm text-gray-500 italic">Belum ada keahlian yang ditambahkan</span>
                    @endif
                </div>
            </div>

            <p class="text-sm text-gray-600 mt-6">
                Bergabung sejak {{ \Carbon\Carbon::parse($user->created_at)->format('F Y') }}
            </p>
        </div>

        <!-- Projects -->
        <section class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 mb-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Projects</h2>
                <span class="text-sm text-gray-600">{{ $user->projects->count() }} proyek</span>
            </div>
            @if($user->projects->isNotEmpty())
                <div class="space-y-8">
                    @foreach($user->projects as $project)
                        <div class="border-b border-gray-100 pb-6 last:border-none last:pb-0">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $project->nama_project }}</h3>
                            <div class="text-sm text-gray-600 mb-3 flex items-center gap-3 flex-wrap">
                                <span>{{ $project->tanggal_mulai ? \Carbon\Carbon::parse($project->tanggal_mulai)->format('M Y') : '—' }}</span>
                                <span class="text-gray-400">→</span>
                                <span>{{ $project->tanggal_akhir ? \Carbon\Carbon::parse($project->tanggal_akhir)->format('M Y') : 'Sekarang' }}</span>
                            </div>
                            @if($project->deskripsi)
                                <p class="text-gray-700 leading-relaxed">{{ $project->deskripsi }}</p>
                            @endif
                            @if($project->link_project)
                                @php
                                    $isGithub = str_contains(strtolower($project->link_project), 'github.com') || str_contains(strtolower($project->link_project), 'githubusercontent.com');
                                @endphp
                                <a href="{{ $project->link_project }}" target="_blank" rel="noopener noreferrer"
                                   class="mt-4 inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-medium">
                                    @if($isGithub) Lihat di GitHub @else Buka Project @endif
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 py-10">Belum ada proyek yang ditambahkan.</p>
            @endif
        </section>

        <!-- Portofolio -->
        <section class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 mb-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Portofolio</h2>
                <span class="text-sm text-gray-600">{{ $user->portofolio->count() }} karya</span>
            </div>
            @if($user->portofolio->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                    @foreach($user->portofolio as $porto)
                        @php
                            $c = (array) ($porto->isi_content ?? []);
                            $judul       = $c['judul'] ?? 'Karya Tanpa Judul';
                            $deskripsi   = $c['deskripsi'] ?? null;
                            $link_project = $c['link_project'] ?? null;
                            $link_github  = $c['link_github'] ?? null;
                            $link_video   = $c['link_video'] ?? null;
                            $embed_video = null;
                            if ($link_video) {
                                if (str_contains($link_video, 'watch?v=')) {
                                    $embed_video = str_replace('watch?v=', 'embed/', $link_video);
                                    $embed_video = explode('&', $embed_video)[0];
                                } elseif (str_contains($link_video, 'youtu.be/')) {
                                    $embed_video = str_replace('youtu.be/', 'www.youtube.com/embed/', $link_video);
                                } else {
                                    $embed_video = $link_video;
                                }
                            }
                        @endphp
                        <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $judul }}</h3>
                                @if($deskripsi)
                                    <p class="text-gray-700 mb-4 line-clamp-3">{{ $deskripsi }}</p>
                                @endif
                                @if($embed_video)
                                    <div class="aspect-video rounded-lg overflow-hidden mb-4">
                                        <iframe class="w-full h-full" src="{{ $embed_video }}" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                @elseif(isset($c['thumbnail']) && $c['thumbnail'])
                                    <img src="{{ asset('storage/' . ltrim($c['thumbnail'], '/')) }}"
                                         alt="{{ $judul }}"
                                         class="w-full h-48 object-cover rounded-lg mb-4">
                                @endif
                                @if($link_project || $link_github || $embed_video)
                                    <div class="flex flex-wrap gap-4 text-sm">
                                        @if($link_project)
                                            <a href="{{ $link_project }}" target="_blank" class="text-indigo-600 hover:underline">Project Link</a>
                                        @endif
                                        @if($link_github)
                                            <a href="{{ $link_github }}" target="_blank" class="text-indigo-600 hover:underline">GitHub</a>
                                        @endif
                                        @if($embed_video)
                                            <a href="{{ $link_video }}" target="_blank" class="text-indigo-600 hover:underline">Video</a>
                                        @endif
                                    </div>
                                @endif
                                <p class="mt-4 text-sm text-gray-500">
                                    {{ $porto->tanggal ? \Carbon\Carbon::parse($porto->tanggal)->format('d M Y') : '—' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 py-10">Belum ada portofolio yang diunggah.</p>
            @endif
        </section>

        <!-- Learning Corners -->
        <section class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 mb-16">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Learning Corners</h2>
                <span class="text-sm text-gray-600">{{ $user->learning_corners->count() }} catatan</span>
            </div>
            @if($user->learning_corners->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($user->learning_corners as $entry)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow border border-gray-100 flex flex-col h-full">
                            <div class="p-6 flex-1 flex flex-col">
                                @if (!empty($entry->content) && is_array($entry->content))
                                    @foreach ($entry->content as $item)
                                        @if ($item['type'] === 'title')
                                            <h3 class="text-xl font-semibold text-gray-900 mb-3 line-clamp-2">
                                                {{ $item['content'] ?? '(Tanpa Judul)' }}
                                            </h3>
                                        @elseif ($item['type'] === 'text')
                                            <p class="text-gray-700 mb-4 line-clamp-4">
                                                {{ $item['content'] }}
                                            </p>
                                        @elseif ($item['type'] === 'image')
                                            @php
                                                $imagePath = str_replace(['\\', '/'], '/', $item['content'] ?? '');
                                            @endphp
                                            <div class="mb-5">
                                                <img
                                                    src="{{ asset('storage/' . ltrim($imagePath, '/')) }}"
                                                    alt="{{ $item['alt'] ?? 'Gambar konten' }}"
                                                    class="w-full h-48 object-cover rounded-lg border border-gray-200 shadow-sm"
                                                    loading="lazy"
                                                    onerror="this.src='https://via.placeholder.com/400x200?text=Gambar+Tidak+Ditemukan';this.onerror=null;"
                                                >
                                            </div>
                                        @elseif ($item['type'] === 'link')
                                            <a
                                                href="{{ $item['content'] }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="text-indigo-600 hover:text-indigo-800 hover:underline mb-4 block line-clamp-1 break-all"
                                            >
                                                {{ Str::limit($item['content'], 70) }}
                                            </a>
                                        @endif
                                    @endforeach
                                @else
                                    <p class="text-gray-700 mb-4 line-clamp-4">
                                        {{ Str::limit(strip_tags($entry->isi_learning_corner ?? ''), 150) }}
                                    </p>
                                @endif
                                <p class="text-sm text-gray-500 mt-auto pt-5 border-t border-gray-100">
                                    Diposting pada: {{ $entry->created_at?->format('d M Y H:i') ?? ($entry->tanggal?->format('d M Y') ?? 'Tanggal tidak tersedia') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 py-10">Belum ada catatan learning corner.</p>
            @endif
        </section>
    </div>
</div>
@endsection