@extends('Layout.Layout')
@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen bg-gray-50/50 ">
    <div class=" mx-auto">

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            <!-- Cover + Avatar -->
            <div class="relative h-48 bg-linear-to-br from-indigo-500 via-indigo-600 to-blue-600">
                <div class="absolute -bottom-16 left-1/2 -translate-x-1/2">
                    <div class="w-32 h-32 rounded-full border-4 border-white bg-white shadow-lg overflow-hidden ring-1 ring-gray-200/50">
                        @if (Auth::user()->photo_profile)
                            <img 
                                src="{{ asset('storage/' . Auth::user()->photo_profile) }}" 
                                alt="{{ Auth::user()->nama_mahasiswa ?? 'Profile' }}"
                                class="w-full h-full object-cover"
                            >
                        @else
                            <div class="w-full h-full bg-indigo-600 flex items-center justify-center text-white text-5xl font-semibold">
                                {{ strtoupper(substr(Auth::user()->nama_mahasiswa ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Konten utama -->
            <div class="pt-20 px-6 pb-10 sm:px-10">

                <!-- Nama -->
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 text-center mb-2">
                    {{ Auth::user()->nama_mahasiswa ?? 'Mahasiswa' }}
                </h2>
                <p class="text-center text-gray-500 text-sm mb-8">
                    {{ Auth::user()->username ? '@' . Auth::user()->username : '' }}
                </p>

                <!-- Grid informasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 max-w-3xl mx-auto">

                    <div class="bg-gray-50/70 p-5 rounded-lg border border-gray-100 hover:border-indigo-200 transition">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Email</p>
                        <p class="text-base font-medium text-gray-800 break-all">
                            {{ Auth::user()->email ?? '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50/70 p-5 rounded-lg border border-gray-100 hover:border-indigo-200 transition">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Jurusan</p>
                        <p class="text-base font-medium text-gray-800">
                            {{ Auth::user()->jurusan->nama_jurusan ?? '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50/70 p-5 rounded-lg border border-gray-100 hover:border-indigo-200 transition">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Keahlian / Program Studi</p>
                        <p class="text-base font-medium text-gray-800">
                            {{ Auth::user()->keahlian->nama_keahlian ?? '-' }}
                        </p>
                    </div>

                    <div class="bg-gray-50/70 p-5 rounded-lg border border-gray-100 hover:border-indigo-200 transition sm:col-span-2">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Status Akun</p>
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-3 h-3 rounded-full {{ Auth::user()->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            <p class="text-base font-medium {{ Auth::user()->is_active ? 'text-green-700' : 'text-red-700' }}">
                                {{ Auth::user()->is_active ? 'Aktif' : 'Nonaktif' }}
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Tombol aksi -->
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                  

                    <button 
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="px-6 py-3 bg-white text-red-600 font-medium rounded-lg border border-red-200 hover:bg-red-50 transition">
                        Keluar
                    </button>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>

            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(Auth::check())
            <!-- === MODE LOGIN: Hanya Portfolio Sendiri === -->
            @php
                $user = Auth::user();
                $nama = trim($user->nama_mahasiswa ?? 'Mahasiswa');
                $inisial = strtoupper(mb_substr($nama, 0, 1));
                $jurusan = $user->jurusan ? $user->jurusan->nama_jurusan : 'Jurusan Tidak Diketahui';
                $headline = $nama . ' | ' . $jurusan;
            @endphp
                <section class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 mt-6 mb-10">
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
                
                            <!-- Portofolio (tetap sama) -->
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
                                                
                                                <div class="relative p-6">
                                                    <div class="max-w-min rounded-2xl">
                                                        <a href="{{route('portofolio.edit', $porto->id_portfolio)}}" class="absolute top-4 right-4">
                                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                                        class="w-6 h-6 text-blue-600  cursor-pointer"
                                                        fill="none" 
                                                        viewBox="0 0 24 24" 
                                                        stroke="currentColor" 
                                                        stroke-width="2">
                                                       
                                                        <path stroke-linecap="round" 
                                                              stroke-linejoin="round" 
                                                              d="M15.232 5.232l3.536 3.536M9 11l6-6a2.121 2.121 0 113 3l-6 6-4 1 1-4z"/>
                                                    </svg> </a>
                                                    </div>
                                                
                                                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $judul }}</h3>
                                                    
                                                    @if($deskripsi)
                                                        <p class="text-gray-700 mb-4 line-clamp-3">{{ $deskripsi }}</p>
                                                    @endif
                                                    @if($embed_video)
                                                        <div class="aspect-video rounded-lg overflow-hidden mb-4">
                                                            <iframe class="w-full h-full" src="{{ $embed_video }}" frameborder="0" allowfullscreen></iframe>
                                                        </div>
                                                    @elseif(!empty($c['thumbnail']))
                                                        <img src="{{ asset('storage/' . $c['thumbnail']) }}" alt="{{ $judul }}" class="w-full h-48 object-cover rounded-lg mb-4">
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
                
                            <!-- Learning Corners - DISESUAIKAN DENGAN STYLE BARU -->
                            <section class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">
                                <div class="flex justify-between items-center mb-6">
                                    <h2 class="text-2xl font-bold text-gray-900">Learning Corners</h2>
                                    <span class="text-sm text-gray-600">{{ $user->learning_corners->count() }} catatan</span>
                                </div>
                
                                @if($user->learning_corners->isNotEmpty())
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @foreach($user->learning_corners as $entry)
                                            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow border border-gray-100 flex flex-col h-full">
                                                <div class="p-6 flex-1 flex flex-col">
                                                    <!-- Render konten dinamis -->
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
                                                                        alt="{{ $item['alt'] ?? 'Gambar konten Learning Corner' }}"
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
                                                        <!-- Fallback jika content bukan array -->
                                                        <p class="text-gray-700 mb-4 line-clamp-4">
                                                            {{ Str::limit(strip_tags($entry->isi_learning_corner ?? ''), 150) }}
                                                        </p>
                                                    @endif
                
                                                    <!-- Tanggal -->
                                                    <p class="text-sm text-gray-500 mt-auto pt-5 border-t border-gray-100">
                                                        Diposting pada: {{ $entry->created_at?->format('d M Y H:i') ?? ($entry->tanggal?->format('d M Y') ?? 'Tanggal tidak tersedia') }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                    <p class="text-center text-gray-500 py-10">Belum ada catatan learning corner.</p>
                                    @endif
                                    </div>
                            </section>
                                
        @endif

        <!-- Footer kecil -->
        <div class="mt-8 text-center text-xs text-gray-500">
            Terakhir diperbarui: {{ now()->format('d F Y H:i') }} WIB
        </div>

    </div>
</div>
@endsection