@extends('Layout.Layout')
@section('title', 'Portofolio Saya')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Portofolio Saya</h1>
                <p class="text-gray-600 mt-1">
                    Kelola semua postingan portofolio kamu di sini.
                </p>
            </div>
            <a href="{{ route('portofolio.create') }}"
               class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                + Tambah Portofolio
            </a>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Data --}}
        @if($portofolio->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach($portofolio as $porto)
                    @php
                        $c = (array) ($porto->isi_content ?? []);
                        $judul = $c['judul'] ?? 'Tanpa Judul';
                        $deskripsi = $c['deskripsi'] ?? null;
                        $thumbnail = $c['thumbnail'] ?? null;
                        $link_project = $c['link_project'] ?? null;
                        $link_github = $c['link_github'] ?? null;
                        $link_video = $c['link_video'] ?? null;

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

                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col overflow-hidden">

                        {{-- Media --}}
                        @if($embed_video)
                            <div class="aspect-video">
                                <iframe class="w-full h-full"
                                        src="{{ $embed_video }}"
                                        frameborder="0"
                                        allowfullscreen>
                                </iframe>
                            </div>
                        @elseif($thumbnail)
                            <img src="{{ asset('storage/' . ltrim($thumbnail, '/')) }}"
                                 class="w-full h-48 object-cover"
                                 alt="{{ $judul }}">
                        @endif

                        {{-- Content --}}
                        <div class="p-5 flex-1 flex flex-col">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                {{ $judul }}
                            </h3>

                            @if($deskripsi)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ $deskripsi }}
                                </p>
                            @endif

                            {{-- Links --}}
                            @if($link_project || $link_github || $link_video)
                                <div class="flex flex-wrap gap-3 text-sm mb-4">
                                    @if($link_project)
                                        <a href="{{ $link_project }}" target="_blank"
                                           class="text-indigo-600 hover:underline">
                                            Project
                                        </a>
                                    @endif
                                    @if($link_github)
                                        <a href="{{ $link_github }}" target="_blank"
                                           class="text-indigo-600 hover:underline">
                                            GitHub
                                        </a>
                                    @endif
                                    @if($link_video)
                                        <a href="{{ $link_video }}" target="_blank"
                                           class="text-indigo-600 hover:underline">
                                            Video
                                        </a>
                                    @endif
                                </div>
                            @endif

                            <p class="text-xs text-gray-500 mt-auto">
                                {{ $porto->tanggal ? \Carbon\Carbon::parse($porto->tanggal)->format('d M Y') : '-' }}
                            </p>

                            {{-- Action Buttons --}}
                            <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">

                                <a href="{{ route('portofolio.edit', $porto->id_portfolio) }}"
                                   class="text-sm px-4 py-1.5 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200 transition">
                                    Edit
                                </a>

                                <form action="{{ route('portofolio.destroy', $porto->id_portfolio  ) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus portofolio ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-sm px-4 py-1.5 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition">
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </div>

                    </div>
                @endforeach

            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="mt-4 text-gray-600">Belum ada portfolio yang ditambahkan.</p>
        </div>
        @endif

    </div>
</div>
@endsection