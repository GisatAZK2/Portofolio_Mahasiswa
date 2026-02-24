@extends('Layout.Layout')

@section('title', 'Portfolio Mahasiswa')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto"> <!-- Lebih sempit agar nyaman dibaca vertikal -->

        <!-- Pesan login untuk guest -->
    @if(!Auth::check())
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 md:p-10 text-center">
                <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-4">
                    Lihat Portfolio Mahasiswa Lain
                </h2>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    Ini adalah contoh portfolio dari berbagai mahasiswa.<br>
                    Mau punya halaman portfolio sendiri? Login dulu ya!
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('login') }}"
                       class="inline-block px-8 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-block px-8 py-3 border border-indigo-600 text-indigo-600 font-medium rounded-lg hover:bg-indigo-50 transition">
                        Daftar Akun
                    </a>
                </div>
            </div>
        </div>
    @endif



        @if($data->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-10 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-6 text-2xl font-medium text-gray-900">Belum ada portfolio</h3>
                <p class="mt-3 text-gray-500">Portfolio akan muncul setelah mahasiswa mengunggah karya mereka.</p>
            </div>
        @else
            <!-- Daftar portfolio - selalu 1 kolom -->
            <div class="space-y-8">
                @foreach($data as $portfolio)
                    @php
                        $content      = $portfolio->isi_content ?? [];
                        $judul        = $content['judul']       ?? 'Portfolio Tanpa Judul';
                        $deskripsi    = $content['deskripsi']   ?? '';
                        $link_project = $content['link_project'] ?? null;
                        $link_github  = $content['link_github']  ?? null;
                        $link_video   = $content['link_video']   ?? null;
                    @endphp

                    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden transition-all hover:shadow-xl">

                        <!-- Header / Judul dengan gradient -->
                        <div class="h-40 bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 flex items-center justify-center px-6 text-center">
                            <h2 class="text-2xl sm:text-3xl font-bold text-white leading-tight line-clamp-3">
                                {{ $judul }}
                            </h2>
                        </div>

                        <div class="p-5 sm:p-6 flex flex-col gap-5">

                            <!-- Profile mahasiswa -->
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-full overflow-hidden bg-gray-200 flex-shrink-0 border border-gray-300 shadow-sm">
                                    @if($portfolio->mahasiswa->photo_profile)
                                        <img src="{{ asset('storage/' . $portfolio->mahasiswa->photo_profile) }}"
                                             alt="{{ $portfolio->mahasiswa->nama_mahasiswa }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-2xl">
                                            {{ strtoupper(substr($portfolio->mahasiswa->nama_mahasiswa ?? 'N', 0, 1)) }}
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <h3 class="font-semibold text-lg text-gray-900 group-hover:text-indigo-700 transition-colors">
                                        {{ $portfolio->mahasiswa->nama_mahasiswa ?? 'Mahasiswa' }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-0.5">
                                        {{ $portfolio->mahasiswa->jurusan?->nama_jurusan ?? '—' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="prose prose-sm sm:prose text-gray-700 leading-relaxed">
                                {!! nl2br(e($deskripsi)) !!}
                            </div>

                            <!-- Embed Links -->
                            <div class="space-y-6 mt-2">

                                @if($link_video)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Video Demo</h4>
                                        @if(str_contains($link_video, 'youtube.com') || str_contains($link_video, 'youtu.be'))
                                            <?php
                                                $videoId = preg_replace('/^.*(v=|youtu\.be\/)([^&?]*).*/', '$2', $link_video);
                                            ?>
                                            <div class="aspect-video rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                                <iframe class="w-full h-full"
                                                        src="https://www.youtube.com/embed/{{ $videoId }}?rel=0"
                                                        title="Video Demo Project"
                                                        frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                        allowfullscreen></iframe>
                                            </div>
                                        @else
                                            <a href="{{ $link_video }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 hover:underline">
                                                <span>Lihat Video</span>
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                @if($link_project)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Link Project</h4>
                                        <a href="{{ $link_project }}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors">
                                            <span>Buka Project</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                @endif

                                @if($link_github)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Repository GitHub</h4>
                                        <a href="{{ $link_github }}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 2C6.477 2 2 6.477 2 12c0 4.418 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.026 2.747-1.026.546 1.377.202 2.394.1 2.647.64.699 1.026 1.592 1.026 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.854 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12c0-5.523-4.478-10-10-10z"/>
                                            </svg>
                                            <span>Repository GitHub</span>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Tanggal -->
                            <p class="text-sm text-gray-500 mt-3">
                                Diposting {{ \Carbon\Carbon::parse($portfolio->tanggal)->diffForHumans() }}
                            </p>

                            <!-- Tombol lihat detail -->
                            <div class="mt-4">
                                <a href="{{ route('portfolio.show', $portfolio->mahasiswa) }}"
                                   class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                                    Lihat Portfolio Lengkap
                                    <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection