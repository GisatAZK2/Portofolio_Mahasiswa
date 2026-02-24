@extends('Layout.Layout')
@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen bg-gray-50/50">
    <div class="mx-auto">

        <form id="form-profile" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                
                <div class="relative h-48 bg-gradient-to-br from-indigo-500 via-indigo-600 to-blue-600">
                    <div class="absolute -bottom-16 left-1/2 -translate-x-1/2 group">
                        <div class="relative w-32 h-32 rounded-full border-4 border-white bg-white shadow-lg overflow-hidden ring-1 ring-gray-200/50">

                            @if (Auth::user()->photo_profile)
                                <img id="profile-preview"
                                     src="{{ asset('storage/' . Auth::user()->photo_profile) }}"
                                     alt="Profile"
                                     class="w-full h-full object-cover">
                            @else
                                <div id="profile-preview-placeholder" class="w-full h-full bg-indigo-600 flex items-center justify-center text-white text-5xl font-semibold">
                                    {{ strtoupper(substr(Auth::user()->nama_mahasiswa ?? 'U', 0, 1)) }}
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                <label for="photo_profile_input" class="cursor-pointer w-full h-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </label>
                            </div>
                        </div>
                        <input type="file" name="photo_profile" id="photo_profile_input" accept="image/*" class="hidden">
                    </div>
                </div>

                <div class="pt-20 px-6 pb-10 sm:px-10">
                    
                    <div class="text-center mb-1 relative group">
                        <div id="nama-container">
                            <h2 id="nama-display" class="text-2xl md:text-3xl font-bold text-gray-900 inline-block cursor-pointer" onclick="toggleEdit('nama')">
                                {{ Auth::user()->nama_mahasiswa ?? 'Mahasiswa' }}
                            </h2>
                            <button type="button" onclick="toggleEdit('nama')" class="ml-2 opacity-0 group-hover:opacity-100 transition text-gray-400 hover:text-indigo-600">
                                ✏️
                            </button>
                        </div>
                        <input type="text" id="nama-input" name="nama_mahasiswa"
                               class="hidden text-2xl md:text-3xl font-bold text-center w-full max-w-lg mx-auto border-b-2 border-indigo-500 focus:outline-none bg-transparent"
                               value="{{ old('nama_mahasiswa', Auth::user()->nama_mahasiswa) }}">
                    </div>

                    <div class="text-center text-gray-500 text-sm mb-8 relative group">
                        <div id="username-container">
                            <span id="username-display" class="cursor-pointer" onclick="toggleEdit('username')">
                                {{ Auth::user()->username ? '@' . Auth::user()->username : '(belum ada username)' }}
                            </span>
                            <button type="button" onclick="toggleEdit('username')" class="ml-2 opacity-0 group-hover:opacity-100 transition text-xs text-gray-400">
                                edit
                            </button>
                        </div>
                        <input type="text" id="username-input" name="username"
                               class="hidden text-center border-b border-indigo-500 focus:outline-none w-64 mx-auto bg-transparent"
                               value="{{ old('username', Auth::user()->username) }}">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 max-w-3xl mx-auto">

                        <div class="bg-gray-50/70 p-5 rounded-lg border border-gray-100 hover:border-indigo-200 transition relative group cursor-pointer" onclick="toggleEdit('email')">
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Email</p>
                            <p id="email-display" class="text-base font-medium text-gray-800 break-all">
                                {{ Auth::user()->email ?? '-' }}
                            </p>
                            <input type="email" id="email-input" name="email"
                                   class="hidden w-full text-base font-medium border-b border-indigo-500 focus:outline-none bg-transparent"
                                   value="{{ old('email', Auth::user()->email) }}">
                        </div>

                        <div class="bg-gray-50/70 p-5 rounded-lg border border-gray-100 hover:border-indigo-200 transition relative group cursor-pointer" onclick="toggleEdit('jurusan')">
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Jurusan</p>
                            <p id="jurusan-display" class="text-base font-medium text-gray-800">
                                {{ Auth::user()->jurusan->nama_jurusan ?? '-' }}
                            </p>
                            <select id="jurusan-input" name="id_jurusan" class="hidden w-full text-base font-medium border-b border-indigo-500 focus:outline-none bg-white">
                                <option value="">-- Pilih Jurusan --</option>
                                @foreach($jurusans ?? [] as $jurusan)
                                    <option value="{{ $jurusan->id_jurusan }}" {{ (Auth::user()->id_jurusan == $jurusan->id_jurusan) ? 'selected' : '' }}>
                                        {{ $jurusan->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-gray-50/70 p-5 rounded-lg border border-gray-100 hover:border-indigo-200 transition relative group cursor-pointer" onclick="toggleEdit('keahlian')">
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Keahlian / Program Studi</p>
                            <p id="keahlian-display" class="text-base font-medium text-gray-800">
                                {{ Auth::user()->keahlian->nama_keahlian ?? '-' }}
                            </p>
                            <select id="keahlian-input" name="id_keahlian" class="hidden w-full text-base font-medium border-b border-indigo-500 focus:outline-none bg-white">
                                <option value="">-- Pilih Keahlian --</option>
                                @foreach($keahlians ?? [] as $keahlian)
                                    <option value="{{ $keahlian->id_keahlian }}" {{ (Auth::user()->id_keahlian == $keahlian->id_keahlian) ? 'selected' : '' }}>
                                        {{ $keahlian->nama_keahlian }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-gray-50/70 p-5 rounded-lg border border-gray-100 sm:col-span-1">
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Status Akun</p>
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-3 h-3 rounded-full {{ Auth::user()->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                <p class="text-base font-medium {{ Auth::user()->is_active ? 'text-green-700' : 'text-red-700' }}">
                                    {{ Auth::user()->is_active ? 'Aktif' : 'Nonaktif' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div id="save-button-container" class="mt-10 text-center hidden">
                        <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition shadow-md">
                            Simpan Perubahan
                        </button>
                        <button type="button" onclick="window.location.reload()" class="ml-4 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Batal
                        </button>
                    </div>

                </div>
            </div>
        </form>

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
                                    </div>
                                @else
                                    <p class="text-center text-gray-500 py-10">Belum ada catatan learning corner.</p>
                                @endif
                            </section>
        @endif

        <!-- Footer kecil -->
        <div class="mt-8 text-center text-xs text-gray-500">
            Terakhir diperbarui: {{ now()->format('d F Y H:i') }} WIB
        </div>

    </div>
    </div>
</div>

<script>

function toggleEdit(field) {
    const displayEl = document.getElementById(field + '-display');
    const inputEl   = document.getElementById(field + '-input');
    const saveBtn   = document.getElementById('save-button-container');

    if (!displayEl || !inputEl) return;

    // Sembunyikan text, tampilkan input
    displayEl.classList.add('hidden');
    inputEl.classList.remove('hidden');
    
    // Sembunyikan tombol edit/ikon jika ada kontainer khusus
    const container = document.getElementById(field + '-container');
    if(container) container.classList.add('hidden');

    inputEl.focus();

    // Tampilkan tombol simpan global
    saveBtn.classList.remove('hidden');

    // Submit jika tekan Enter (kecuali untuk select/textarea tertentu)
    inputEl.onkeypress = function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('form-profile').submit();
        }
    };
}

document.getElementById('photo_profile_input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(ev) {
        const preview = document.getElementById('profile-preview');
        const placeholder = document.getElementById('profile-preview-placeholder');

        if (preview) {
            preview.src = ev.target.result;
        } else if (placeholder) {
            const newImg = document.createElement('img');
            newImg.id = 'profile-preview';
            newImg.className = 'w-full h-full object-cover';
            newImg.src = ev.target.result;
            placeholder.replaceWith(newImg);
        }
    };
    reader.readAsDataURL(file);

    // Tampilkan tombol simpan karena ada perubahan (foto)
    document.getElementById('save-button-container').classList.remove('hidden');
});
</script>
@endsection