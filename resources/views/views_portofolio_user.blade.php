@extends('Layout.Layout')

@section('title', ($user->nama_mahasiswa ?? 'Mahasiswa') . ' | Portfolio')

@section('content')
    <div class="min-h-screen bg-gray-100 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Grid utama ala LinkedIn -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                <!-- ========== SIDEBAR KIRI (Profil + Keahlian) - Sticky hanya di lg+ ========== -->
                <div class="lg:col-span-1 lg:sticky lg:top-8 lg:self-start space-y-6">

                    <!-- Kartu Profil Ringkas -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <!-- Background Banner -->
                        <div class="h-32
                            {{ $user->background_url
        ? 'bg-cover bg-center'
        : 'bg-gradient-to-r from-indigo-500 to-indigo-600' }}" @if($user->background_url)
        style="background-image: url('{{ asset('storage/' . $user->background_url) }}');" @endif></div>

                        <!-- Avatar dan Info Utama -->
                        <div class="px-5 pb-6 relative">
                            <div class="flex justify-between items-start">
                                <div class="-mt-12 mb-3">
                                    <div
                                        class="w-24 h-24 rounded-full border-4 border-white bg-white shadow-lg overflow-hidden">
                                        @if($user->photo_profile)
                                            <img src="{{ asset('storage/' . ltrim($user->photo_profile, '/')) }}"
                                                alt="{{ $user->nama_mahasiswa ?? 'Mahasiswa' }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div
                                                class="w-full h-full bg-indigo-600 flex items-center justify-center text-white text-3xl font-bold">
                                                {{ strtoupper(mb_substr(trim($user->nama_mahasiswa ?? 'M'), 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-xs text-gray-600 mt-2">
                                    <span class="inline-flex items-center gap-1">
                                        <span>Jenis kelamin :</span>
                                        {{ $user->jenis_kelamin ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            <h1 class="text-xl font-bold text-gray-900 mt-2">
                                {{ trim($user->nama_mahasiswa ?? 'Mahasiswa') }}
                            </h1>
                            <p class="text-sm text-gray-600">
                                {{ $user->jurusan?->nama_jurusan ?? 'Mahasiswa' }}
                            </p>

                            <p class="mt-4 text-sm text-gray-700 leading-relaxed">
                                {{ !empty(trim($user->deskripsi)) ? $user->deskripsi : 'Tidak ada deskripsi.' }}
                            </p>

                            <p class="mt-4 text-xs text-blue-600 font-medium cursor-pointer hover:underline">
                                Politeknik Mitra Industri
                            </p>
                        </div>
                    </div>

                    <!-- Keahlian -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                        <h3 class="text-base font-bold text-gray-900 mb-4">Keahlian</h3>

                        @if($user->keahlian)
                            <div class="mb-5">
                                <p class="text-sm font-medium text-gray-700 mb-2">Utama:</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="px-3 py-1 text-xs font-medium bg-red-50 text-red-700 rounded-full border border-red-100">
                                        {{ $user->keahlian->nama_keahlian }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if(!empty($user->keahlian_tambahan))
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-2">Tambahan:</p>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $keahlianTambahan = \App\Models\Keahlian::whereIn(
                                            'id_keahlian',
                                            $user->keahlian_tambahan
                                        )->pluck('nama_keahlian');
                                    @endphp
                                    @foreach($keahlianTambahan as $nama)
                                        <span
                                            class="px-3 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-full border border-blue-100">
                                            {{ $nama }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!$user->keahlian && empty($user->keahlian_tambahan))
                            <p class="text-sm text-gray-500 italic text-center py-2">Belum ada keahlian ditambahkan</p>
                        @endif
                    </div>

                </div>

                <!-- ========== KONTEN UTAMA ========== -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Projects -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 lg:p-6">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-lg font-bold text-gray-900">Projects</h3>
                            <span class="text-sm text-gray-500">{{ $user->projects->count() }} proyek</span>
                        </div>

                        @if($user->projects->isNotEmpty())
                            <div class="space-y-6">
                                @foreach($user->projects as $project)
                                    @php
                                        $content = $project->isi_content ?? [];
                                        $nama = $content['nama_project'] ?? 'Tanpa Nama';
                                        $deskripsi = $content['deskripsi'] ?? null;
                                        $linkWeb = $content['link_project'] ?? null;
                                        $linkGithub = $content['link_github'] ?? null;
                                        $linkVideo = $content['link_video'] ?? null;

                                        $videoId = null;
                                        if ($linkVideo) {
                                            $parsedUrl = parse_url($linkVideo);
                                            $path = $parsedUrl['path'] ?? '';
                                            $host = $parsedUrl['host'] ?? '';
                                            if (strpos($host, 'youtu.be') !== false) {
                                                $videoId = trim(explode('/', $path)[1] ?? '', '/');
                                                $videoId = explode('?', $videoId)[0];
                                            } elseif (strpos($path, '/watch') !== false || strpos($path, '/embed/') !== false || strpos($path, '/v/') !== false) {
                                                if (isset($parsedUrl['query'])) {
                                                    parse_str($parsedUrl['query'], $query);
                                                    $videoId = $query['v'] ?? null;
                                                }
                                                if (!$videoId && preg_match('/^\/(?:embed|v)\/([a-zA-Z0-9_-]{11})/', $path, $matches)) {
                                                    $videoId = $matches[1];
                                                }
                                            }
                                        }
                                    @endphp

                                    <div class="border border-gray-100 rounded-xl p-5 hover:shadow-md transition">
                                        <div onclick="window.location='{{ route('project.show', $project->id) }}'" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <!-- Kiri: Info Project + Links -->
                                            <div class="flex flex-col">
                                                <div class="flex items-start gap-4 mb-4">
                                                    <div
                                                        class="w-14 h-14 bg-indigo-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <h4 class="text-base font-semibold text-gray-900">{{ $nama }}</h4>
                                                        @if($deskripsi)
                                                            <p class="text-sm text-gray-600 mt-1 line-clamp-4">{{ $deskripsi }}</p>
                                                        @else
                                                            <p class="text-sm text-gray-500 mt-1 italic">Tidak ada deskripsi</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="flex flex-wrap gap-4 mt-2">
                                                    @if($linkWeb)
                                                        <a href="{{ $linkWeb }}" target="_blank" rel="noopener noreferrer"
                                                            class="inline-flex hover:underline items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                            </svg>
                                                            Website / Demo
                                                        </a>
                                                    @endif
                                                    @if($linkGithub)
                                                        <a href="{{ $linkGithub }}" target="_blank" rel="noopener noreferrer"
                                                            class="inline-flex hover:underline items-center text-sm text-gray-700 hover:text-gray-900 font-medium">
                                                            <svg class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                                                <path
                                                                    d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                                                            </svg>
                                                            GitHub
                                                        </a>
                                                    @endif
                                                    @if($linkVideo)
                                                        <a href="{{ $linkVideo }}" target="_blank" rel="noopener noreferrer"
                                                            class="inline-flex hover:underline items-center text-sm text-red-600 hover:text-red-800 font-medium">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                                <path
                                                                    d="M21.5 10.833l-7.5 4.33v-8.66l7.5 4.33zM2 5h12v2H4v10h10v2H2V5zm16 2v10l-8-5 8-5z" />
                                                            </svg>
                                                            Video Demo
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($videoId)
                                                <div
                                                    class="relative w-full aspect-video rounded-lg overflow-hidden bg-black/5 shadow-inner">
                                                    <iframe class="absolute inset-0 w-full h-full"
                                                        src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&modestbranding=1&showinfo=0&controls=1"
                                                        title="YouTube video player for {{ $nama }}" frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                        allowfullscreen>
                                                    </iframe>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-6">Belum ada proyek yang ditambahkan</p>
                        @endif
                    </div>

                    <!-- Sertifikat -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 lg:p-6">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-lg font-bold text-gray-900">Sertifikat</h3>
                            <span class="text-sm text-gray-500">{{ $user->sertifikats->count() }} sertifikat</span>
                        </div>

                        @if($user->sertifikats->isNotEmpty())
                            <div class="space-y-6">
                                @foreach($user->sertifikats as $sertifikat)
                                    @php
                                        $namaSertif = $sertifikat->nama_sertifikat ?? 'Sertifikat Tanpa Nama';
                                        $lembaga = $sertifikat->lembaga_penerbit ?? 'Lembaga Tidak Diketahui';
                                        $tanggal = $sertifikat->tanggal_terbit
                                            ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('F Y')
                                            : 'Tanggal Tidak Tersedia';
                                        $linkSertif = $sertifikat->link_sertifikat
                                            ? asset('storage/' . ltrim($sertifikat->link_sertifikat, '/'))
                                            : null;
                                        $isImage = $linkSertif && in_array(
                                            strtolower(pathinfo($linkSertif, PATHINFO_EXTENSION)),
                                            ['jpg', 'jpeg', 'png', 'gif', 'webp']
                                        );
                                    @endphp

                                    <div class="border border-gray-100 rounded-xl p-5 hover:shadow-md transition">
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div class="flex flex-col justify-between">
                                                <div class="flex items-start gap-4">
                                                    <div
                                                        class="w-14 h-14 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <h4 class="text-base font-semibold text-gray-900">{{ $namaSertif }}</h4>
                                                        <p class="text-sm text-gray-600 mt-1">{{ $lembaga }}</p>
                                                        <p class="text-xs text-gray-500 mt-1">Diterbitkan {{ $tanggal }}</p>
                                                    </div>
                                                </div>

                                                @if($linkSertif)
                                                    <div class="mt-5">
                                                        <a href="{{ $linkSertif }}" target="_blank" rel="noopener noreferrer"
                                                            class="inline-flex items-center px-5 py-2.5 text-amber-600  bg-amber-50 hover:bg-amber-100 text-sm font-medium rounded-lg transition">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            Lihat Sertifikat
                                                        </a>
                                                    </div>
                                                @else
                                                    <p class="mt-5 text-sm text-gray-500 italic">File sertifikat tidak tersedia</p>
                                                @endif
                                            </div>

                                            @if($linkSertif)
                                                <div
                                                    class="relative w-full aspect-[4/3] rounded-lg overflow-hidden bg-gray-100 shadow-inner flex items-center justify-center">
                                                    @if($isImage)
                                                        <img src="{{ $linkSertif }}" alt="Preview {{ $namaSertif }}"
                                                            class="w-full h-full object-contain" loading="lazy"
                                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        <div
                                                            class="absolute inset-0 hidden flex items-center justify-center bg-gray-200 text-gray-500 text-xs">
                                                            Gagal memuat preview
                                                        </div>
                                                    @else
                                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            <span class="text-sm">PDF / Dokumen</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="flex items-center justify-center text-gray-400 text-sm italic">
                                                    Tidak ada preview tersedia
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-6">Belum ada sertifikat yang ditambahkan</p>
                        @endif
                    </div>

                    <!-- Learning Corners - FIXED VERSION -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 lg:p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-5">Learning Corners</h3>

                        @if($user->learning_corners->isNotEmpty())
                            <div class="space-y-6">
                                @foreach($user->learning_corners as $entry)
                                    <div class="border border-gray-100 rounded-xl p-5 hover:shadow-md transition">
                                        <div class="space-y-4">
                                            @php
                                                // Ambil content - bisa array atau string JSON
                                                $rawContent = $entry->content ?? [];

                                                if (is_string($rawContent) && !empty($rawContent)) {
                                                    $items = json_decode($rawContent, true) ?? [];
                                                } else {
                                                    $items = is_array($rawContent) ? $rawContent : [];
                                                }
                                            @endphp

                                            @if(!empty($items) && is_array($items))
                                                @foreach($items as $item)
                                                    @if(($item['type'] ?? '') === 'title')
                                                        <h4 class="text-base font-bold text-gray-900">
                                                            {{ $item['content'] ?? 'Judul tidak tersedia' }}
                                                        </h4>

                                                    @elseif(($item['type'] ?? '') === 'text')
                                                        <p class="text-sm text-gray-700 leading-relaxed">
                                                            {{ $item['content'] ?? '' }}
                                                        </p>

                                                    @elseif(($item['type'] ?? '') === 'image' && !empty($item['content']))
                                                        <div class="my-3">
                                                            <img src="{{ asset('storage/' . $item['content']) }}" alt="Gambar learning corner"
                                                                class="w-30 rounded-lg shadow-sm object-cover max-h-[500px]" loading="lazy"
                                                                onerror="this.src='https://st4.depositphotos.com/17828278/24401/v/450/depositphotos_244011872-stock-illustration-image-vector-symbol-missing-available.jpg'; this.alt='Gambar gagal dimuat';">
                                                        </div>

                                                    @elseif(($item['type'] ?? '') === 'link' && !empty($item['content']))
                                                        <a href="{{ $item['content'] }}" target="_blank" rel="noopener noreferrer"
                                                            class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                            </svg>
                                                            {{ $item['text'] ?? $item['content'] }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            @else
                                                <p class="text-sm text-gray-600 italic">
                                                    Tidak ada konten yang dapat ditampilkan
                                                </p>
                                            @endif

                                            <p class="text-xs text-gray-500 mt-3">
                                                {{ $entry->created_at?->format('d M Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-8 italic">
                                Belum ada catatan learning corner
                            </p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection