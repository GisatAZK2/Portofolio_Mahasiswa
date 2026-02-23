@extends('Layout.Layout')

@section('title', 'Portfolio')

@section('content')

    <div class="space-y-8">

        @forelse($data as $user)
            @php
                $nama = trim($user->nama_mahasiswa ?? 'Mahasiswa');
                $inisial = strtoupper(mb_substr($nama, 0, 1));
            @endphp

            <section class="mb-12 lg:mb-16">
                <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-8 md:px-8 md:py-10 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-100">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                            <div class="w-20 h-20 rounded-full  flex items-center justify-center text-black bg-blue-500 font-bold text-3xl shadow-lg flex-shrink-0">
                                {{ $inisial }}
                            </div>
                            <div>
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                                    {{ $nama }}
                                </h2>
                                <p class="mt-1.5 text-gray-600">Mahasiswa</p>
                            </div>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-100">

                        <!-- PROJECTS -->
                        <div class="px-6 py-8 md:px-8 md:py-10">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl md:text-2xl font-semibold text-gray-900">Projects</h3>
                                <span class="text-sm text-gray-500">{{ $user->projects->count() }} item</span>
                            </div>

                            @if($user->projects->isNotEmpty())
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                                    @foreach($user->projects as $project)
                                        <div class="bg-gray-50/70 rounded-xl p-5 border border-gray-200 hover:border-indigo-300 hover:shadow-md transition-all duration-300 group">
                                            <h4 class="font-semibold text-gray-900 group-hover:text-indigo-700 transition-colors">
                                                {{ $project->nama_project }}
                                            </h4>
                                          <div class="mt-2 text-sm text-gray-600 flex flex-wrap gap-x-3">
    <span>
        {{ $project->tanggal_mulai 
            ? \Carbon\Carbon::parse($project->tanggal_mulai)->format('d M Y') 
            : '?' }}
    </span>

    <span class="text-gray-400">→</span>

    <span>
        {{ $project->tanggal_akhir 
            ? \Carbon\Carbon::parse($project->tanggal_akhir)->format('d M Y') 
            : 'Masih Berjalan' }}
    </span>
</div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-12 text-center text-gray-500 bg-gray-50/50 rounded-xl border border-dashed border-gray-300">
                                    Belum ada proyek yang ditambahkan
                                </div>
                            @endif
                        </div>

                        <!-- PORTOFOLIO -->
                        <div class="px-6 py-8 md:px-8 md:py-10">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl md:text-2xl font-semibold text-gray-900">Portofolio</h3>
                                <span class="text-sm text-gray-500">{{ $user->portofolio->count() }} karya</span>
                            </div>

                            @if($user->portofolio->isNotEmpty())
                                <div class="space-y-7">
                                    @foreach($user->portofolio as $porto)
                                        @php
                                            $c = (array) ($porto->isi_content ?? []);
                                            $judul = $c['judul'] ?? 'Karya Tanpa Judul';
                                            $deskripsi = $c['deskripsi'] ?? null;
                                            $video = $c['link_video'] ?? null;

                                            // Normalisasi YouTube link ke embed
                                            if ($video) {
                                                if (str_contains($video, 'watch?v=')) {
                                                    $video = str_replace('watch?v=', 'embed/', $video);
                                                    $video = explode('&', $video)[0];
                                                } elseif (str_contains($video, 'youtu.be/')) {
                                                    $video = str_replace('youtu.be/', 'www.youtube.com/embed/', $video);
                                                }
                                            }
                                        @endphp

                                        <article class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                                            <div class="p-6 md:p-7">
                                                <h4 class="text-xl font-bold text-gray-900 group-hover:text-indigo-700 transition-colors mb-3">
                                                    {{ $judul }}
                                                </h4>

                                                @if($deskripsi)
                                                    <p class="text-gray-700 leading-relaxed mb-5 line-clamp-3 group-hover:line-clamp-none transition-all duration-300">
                                                        {{ $deskripsi }}
                                                    </p>
                                                @endif

                                                @if($video)
                                                    <div class="aspect-video rounded-lg overflow-hidden border border-gray-200 shadow-sm mb-5 bg-black">
                                                        <iframe class="w-full h-full" src="{{ $video }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                @elseif(!empty($c['thumbnail']))
                                                    <img src="{{ asset('storage/' . $c['thumbnail']) }}" alt="{{ $judul }}" class="w-full h-auto rounded-lg mb-5 shadow-sm object-cover">
                                                @endif

                                                <div class="text-sm text-gray-500 mt-2">
                                                    <time> {{ $porto->tanggal ? \Carbon\Carbon::parse($porto->tanggal)->format('d M Y') : '-' }}</time>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-12 text-center text-gray-500 bg-gray-50/50 rounded-xl border border-dashed border-gray-300">
                                    Belum ada portofolio yang diunggah
                                </div>
                            @endif
                        </div>

                        <!-- LEARNING CORNERS -->
                        <div class="px-6 py-8 md:px-8 md:py-10">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl md:text-2xl font-semibold text-gray-900">Learning Corners</h3>
                                <span class="text-sm text-gray-500">{{ $user->learning_corners->count() }} catatan</span>
                            </div>

                            @if($user->learning_corners->isNotEmpty())
                                <div class="space-y-5">
                                    @foreach($user->learning_corners as $lc)
                                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 hover:border-indigo-300 transition-all duration-200">
                                            <time class="block text-sm text-gray-500 mb-2">
                                                {{ $lc->tanggal ? \Carbon\Carbon::parse($lc->tanggal)->format('d M Y') : '—' }}
                                            </time>
                                            <p class="text-gray-800 leading-relaxed">{{ $lc->isi_learning_corner }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-12 text-center text-gray-500 bg-gray-50/50 rounded-xl border border-dashed border-gray-300">
                                    Belum ada catatan learning corner
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </section>

        @empty
            <div class="py-20 text-center text-gray-500 bg-white rounded-2xl border border-gray-200 shadow-sm">
                <p class="text-xl">Belum ada data mahasiswa yang tersedia</p>
            </div>
        @endforelse
    </div>
@endsection