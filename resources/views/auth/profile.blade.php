@extends('Layout.Layout')
@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-6">
        <form id="form-profile" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            
            <!-- Cover Image -->
            <div class="relative h-48 rounded-t-2xl overflow-hidden
                @if(Auth::user()->background_url)
                    bg-cover bg-center
                @else
                    bg-gradient-to-br from-indigo-500 via-indigo-600 to-blue-600
                @endif
                "
                @if(Auth::user()->background_url)
                    style="background-image: url('{{ asset('storage/' . Auth::user()->background_url) }}');"
                @endif
            >
                <label for="background_input" 
                    class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-lg text-sm font-medium cursor-pointer hover:bg-white shadow-md transition">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Ganti Cover
                </label>
                <input type="file" name="background_url" id="background_input" class="hidden" accept="image/*" />
            </div>

            <!-- Profile Section -->
            <div class="bg-white rounded-b-2xl shadow-sm px-6 pb-8 sm:px-10">
                <!-- Avatar -->
                <div class="relative flex">
                    <div class="relative -mt-16 group">
                        <div class="relative w-32 h-32 rounded-full border-4 border-white bg-white shadow-xl overflow-hidden">
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
                            
                            <!-- Upload Overlay -->
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                <label for="photo_profile_input" class="cursor-pointer w-full h-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </label>
                            </div>
                            <input type="file" name="photo_profile" id="photo_profile_input" accept="image/*" class="hidden">
                        </div>
                    </div>
                </div>

                <!-- Name and Username -->
                <div class=" mt-4">
                    <div class="relative group inline-block">
                        <div id="nama-container">
                            <h2 id="nama-display" class="text-2xl md:text-3xl font-bold text-gray-900 inline-block cursor-pointer hover:text-indigo-600" onclick="toggleEdit('nama')">
                                {{ Auth::user()->nama_mahasiswa ?? 'Mahasiswa' }}
                            </h2>
                            <button type="button" onclick="toggleEdit('nama')" class="ml-2 opacity-0 group-hover:opacity-100 transition text-gray-400 hover:text-indigo-600">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                        </div>
                        <input type="text" id="nama-input" name="nama_mahasiswa"
                            class="hidden text-2xl md:text-3xl font-bold text-center w-full max-w-lg mx-auto border-b-2 border-indigo-500 focus:outline-none bg-transparent px-2 py-1"
                            value="{{ old('nama_mahasiswa', Auth::user()->nama_mahasiswa) }}">
                    </div>

                    <div class=" text-gray-500 text-sm mb-8 relative group">
                            <div id="username-container">
                                <span id="username-display" class="cursor-pointer" onclick="toggleEdit('username')">
                                    {{ Auth::user()->username ? '@' . Auth::user()->username : '(belum ada username)' }}
                                </span>
                                <button type="button" onclick="toggleEdit('username')"
                                    class="ml-2 opacity-0 group-hover:opacity-100 transition text-xs text-gray-400">
                                    edit
                                </button>
                            </div>
                            <input type="text" id="username-input" name="username"
                                class="hidden text-center border-b border-indigo-500 focus:outline-none w-64 mx-auto bg-transparent"
                                value="{{ old('username', Auth::user()->username) }}">
                        </div>
                </div>

                <!-- Info Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 max-w-3xl mx-auto mt-8">
                    <!-- Deskripsi -->
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:border-indigo-300 transition cursor-pointer group sm:col-span-2" onclick="toggleEdit('deskripsi')">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Deskripsi</p>
                        <div class="flex items-center justify-between">
                            <p id="deskripsi-display" class="text-base text-gray-800 break-all">
                                {{ $user->deskripsi ?? 'Klik untuk menambahkan deskripsi...' }}
                            </p>
                            <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </div>
                        <textarea id="deskripsi-input" name="deskripsi"
                            class="hidden w-full text-base text-gray-800 border-b border-indigo-500 focus:outline-none bg-transparent"
                            rows="3">{{ old('deskripsi', $user->deskripsi) }}</textarea>
                    </div>

                    <!-- Email -->
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:border-indigo-300 transition cursor-pointer group" onclick="toggleEdit('email')">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Email</p>
                        <div class="flex items-center justify-between">
                            <p id="email-display" class="text-base font-medium text-gray-800 break-all">
                                {{ Auth::user()->email ?? '-' }}
                            </p>
                            <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </div>
                        <input type="email" id="email-input" name="email"
                            class="hidden w-full text-base font-medium text-gray-800 border-b border-indigo-500 focus:outline-none bg-transparent"
                            value="{{ old('email', Auth::user()->email) }}">
                    </div>

                    <!-- Jurusan -->
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:border-indigo-300 transition cursor-pointer group" onclick="toggleEdit('jurusan')">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Jurusan</p>
                        <div class="flex items-center justify-between">
                            <p id="jurusan-display" class="text-base font-medium text-gray-800">
                                {{ Auth::user()->jurusan->nama_jurusan ?? '-' }}
                            </p>
                            <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </div>
                        <select id="jurusan-input" name="id_jurusan"
                            class="hidden w-full text-base font-medium text-gray-800 border-b border-indigo-500 focus:outline-none bg-white">
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach($jurusans ?? [] as $jurusan)
                                <option value="{{ $jurusan->id_jurusan }}" {{ (Auth::user()->id_jurusan == $jurusan->id_jurusan) ? 'selected' : '' }}>
                                    {{ $jurusan->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Angkatan -->
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Angkatan</p>
                        <p class="text-base font-medium text-gray-800">
                            {{ Auth::user()->angkatan->nama_angkatan ?? 'Angkatan 2026' }}
                        </p>
                    </div>

                    <!-- Keahlian -->
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:border-indigo-300 transition cursor-pointer group" onclick="toggleEdit('keahlian')">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Keahlian / Program Studi</p>
                        <div class="flex items-center justify-between">
                            <p id="keahlian-display" class="text-base font-medium text-gray-800">
                                {{ Auth::user()->keahlian->nama_keahlian ?? '-' }}
                            </p>
                            <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </div>
                        <select id="keahlian-input" name="id_keahlian"
                            class="hidden w-full text-base font-medium text-gray-800 border-b border-indigo-500 focus:outline-none bg-white">
                            <option value="">-- Pilih Keahlian --</option>
                            @foreach($keahlians ?? [] as $keahlian)
                                <option value="{{ $keahlian->id_keahlian }}" {{ (Auth::user()->id_keahlian == $keahlian->id_keahlian) ? 'selected' : '' }}>
                                    {{ $keahlian->nama_keahlian }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Akun -->
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Status Akun</p>
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-3 h-3 rounded-full {{ Auth::user()->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            <p class="text-base font-medium {{ Auth::user()->is_active ? 'text-green-700' : 'text-red-700' }}">
                                {{ Auth::user()->is_active ? 'Aktif' : 'Nonaktif' }}
                            </p>
                        </div>
                    </div>

                    <div onclick="toggleEdit('jenis_kelamin')" class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:border-indigo-300 transition cursor-pointer group">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Jenis Kelamin</p>
                        <p id="jenis_kelamin-display" class="text-base font-medium text-gray-800">
                            {{ Auth::user()->jenis_kelamin ?? 'Klik untuk memilih' }}
                        </p>

                        <select id="jenis_kelamin-input" name="jenis_kelamin"
                                                         class="hidden w-full text-base font-medium text-gray-800 border-b border-indigo-500 focus:outline-none bg-white">

                            <option value="">-- Pilih --</option>

                            <option value="laki-laki"
                            {{ Auth::user()->jenis_kelamin == 'laki-laki' ? 'selected' : '' }}>
                                Laki-laki
                            </option>

                            <option value="perempuan"
                            {{ Auth::user()->jenis_kelamin == 'perempuan' ? 'selected' : '' }}>
                                Perempuan
                            </option>

                            <option value="tidak ingin memberi tahu"
                            {{ Auth::user()->jenis_kelamin == 'tidak ingin memberi tahu' ? 'selected' : '' }}>
                                Tidak ingin memberi tahu
                            </option>
                        </select>
                        </p>
                    </div>
                </div>

                <!-- Save Button -->
                <div id="save-button-container" class="mt-8 text-center hidden">
                    <button type="submit"
                        class="px-8 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition shadow-md">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="window.location.reload()"
                        class="ml-4 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
                        Batal
                    </button>
                </div>
            </div>
        </form>

        @if(Auth::check())
            <!-- Projects Section -->
            <section class="mt-8 bg-white rounded-2xl border border-gray-200 shadow-sm p-6 md:p-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Projects</h2>
                    <span class="text-sm text-gray-600 bg-gray-100 px-4 py-1.5 rounded-full">
                        {{ $user->projects->count() }} proyek
                    </span>
                </div>

                @if($user->projects->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($user->projects as $project)
                            @php
                                $content = $project->isi_content ?? [];
                                $nama = $content['nama_project'] ?? 'Tanpa Nama Project';
                                $deskripsi = $content['deskripsi'] ?? null;
                                $linkProject = $content['link_project'] ?? null;
                                $linkGithub = $content['link_github'] ?? null;
                                $linkVideo = $content['link_video'] ?? null;

                                $mulaiRaw = $project->tanggal_mulai ?? null;
                                $akhirRaw = $project->tanggal_akhir ?? null;
                                $mulai = $mulaiRaw ? \Carbon\Carbon::parse($mulaiRaw) : null;
                                $akhir = $akhirRaw ? \Carbon\Carbon::parse($akhirRaw) : null;
                                $today = \Carbon\Carbon::today();

                                $mulaiFormatted = $mulai ? $mulai->translatedFormat('M Y') : '—';
                                $akhirFormatted = $akhir ? $akhir->translatedFormat('M Y') : 'Sekarang';

                                $statusClass = 'bg-gray-100 text-gray-700';
                                $statusText = 'Tidak diketahui';

                                if ($mulai && $akhir) {
                                    if ($akhir < $today) {
                                        $statusClass = 'bg-red-100 text-red-800';
                                        $statusText = 'Selesai';
                                    } elseif ($mulai <= $today && $today <= $akhir) {
                                        $statusClass = 'bg-green-100 text-green-800';
                                        $statusText = 'Sedang Berjalan';
                                    } elseif ($mulai > $today) {
                                        $statusClass = 'bg-blue-100 text-blue-800';
                                        $statusText = 'Akan Datang';
                                    }
                                } elseif ($mulai && !$akhir) {
                                    if ($mulai <= $today) {
                                        $statusClass = 'bg-green-100 text-green-800';
                                        $statusText = 'Sedang Berjalan';
                                    } else {
                                        $statusClass = 'bg-blue-100 text-blue-800';
                                        $statusText = 'Akan Datang';
                                    }
                                } elseif (!$mulai && $akhir) {
                                    if ($akhir < $today) {
                                        $statusClass = 'bg-red-100 text-red-800';
                                        $statusText = 'Selesai';
                                    }
                                }

                                // Extract YouTube Video ID
                                $youtubeEmbedUrl = null;
                                $youtubeThumbnail = null;
                                
                                if ($linkVideo) {
                                    // Match various YouTube URL formats
                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $linkVideo, $matches);
                                    
                                    if (!empty($matches[1])) {
                                        $videoId = $matches[1];
                                        $youtubeEmbedUrl = "https://www.youtube.com/embed/" . $videoId;
                                        $youtubeThumbnail = "https://img.youtube.com/vi/" . $videoId . "/maxresdefault.jpg";
                                    }
                                }
                            @endphp

                            <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden flex flex-col h-full group">
                                <!-- Media Header with YouTube Thumbnail/Embed -->
                                <div class="relative w-full bg-black overflow-hidden">
                                    @if($youtubeEmbedUrl)
                                        <!-- YouTube Thumbnail with Play Button -->
                                        <div class="relative w-full pb-[56.25%] bg-gray-900 cursor-pointer" onclick="playVideo(this, '{{ $youtubeEmbedUrl }}')">
                                            <img src="{{ $youtubeThumbnail }}" 
                                                 alt="YouTube thumbnail" 
                                                 class="absolute inset-0 w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity"
                                                 onerror="this.src='https://via.placeholder.com/480x360?text=Video+Tidak+Tersedia'">
                                            
                                            <!-- Play Button Overlay -->
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                                                    <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            
                                            <!-- YouTube Logo -->
                                            <div class="absolute bottom-2 right-2 bg-black/70 text-white text-xs px-2 py-1 rounded flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                </svg>
                                                YouTube
                                            </div>
                                        </div>
                                        
                                        <!-- Hidden iframe container -->
                                        <div id="player-{{ $loop->index }}" class="hidden absolute inset-0 w-full h-full"></div>
                                    @elseif($linkProject)
                                        <!-- Project Link Thumbnail -->
                                        <div class="w-full h-48 bg-gradient-to-br from-indigo-50 to-blue-50 flex items-center justify-center">
                                            <svg class="w-20 h-20 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @else
                                        <!-- Default Thumbnail -->
                                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                            <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="p-5 flex flex-col flex-1">
                                    <div class="flex items-start justify-between mb-2 gap-2">
                                        <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 flex-1">
                                            {{ $nama }}
                                        </h3>
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }} whitespace-nowrap">
                                            {{ $statusText }}
                                        </span>
                                    </div>

                                    <div class="text-sm text-gray-600 mb-3 flex items-center gap-2 flex-wrap">
                                        <span>Mulai: {{ $mulaiFormatted }}</span>
                                        <span class="text-gray-400">→</span>
                                        <span>Selesai: {{ $akhirFormatted }}</span>
                                    </div>

                                    @if($deskripsi)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-3 flex-1">
                                            {{ $deskripsi }}
                                        </p>
                                    @else
                                        <p class="text-gray-500 text-sm mb-4 italic flex-1">Tidak ada deskripsi</p>
                                    @endif

                                    <div class="flex flex-wrap gap-3 mt-auto pt-4 border-t border-gray-100">
                                        @if($linkProject)
                                            <a href="{{ $linkProject }}" target="_blank" rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                                Website
                                            </a>
                                        @endif

                                        @if($linkGithub)
                                            <a href="{{ $linkGithub }}" target="_blank" rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-800 hover:text-black">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                                                </svg>
                                                GitHub
                                            </a>
                                        @endif

                                        @if($linkVideo && !$youtubeEmbedUrl)
                                            <a href="{{ $linkVideo }}" target="_blank" rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600 hover:text-red-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                    <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-4 text-gray-600">Belum ada proyek yang ditambahkan.</p>
                    </div>
                @endif
            </section>

            <!-- Sertifikat Section -->
            <section class="mt-8 bg-white rounded-2xl border border-gray-200 shadow-sm p-6 md:p-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Sertifikat</h2>
                    <span class="text-sm text-gray-600 bg-gray-100 px-4 py-1.5 rounded-full">
                        {{ $user->sertifikats?->count() ?? 0 }} sertifikat
                    </span>
                </div>

                @if($user->sertifikats?->isNotEmpty() ?? false)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($user->sertifikats as $sertifikat)
                            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow border border-gray-200 flex flex-col h-full">
                                <div class="p-6 flex flex-col flex-1">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 mb-3 w-fit">
                                        Sertifikat
                                    </span>

                                    <h3 class="text-lg font-semibold text-gray-900 mb-3 line-clamp-2">
                                        {{ $sertifikat->nama_sertifikat ?? 'Sertifikat Tanpa Judul' }}
                                    </h3>

                                    @if($sertifikat->lembaga_penerbit)
                                        <div class="flex items-center text-sm text-gray-600 mb-2">
                                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                            </svg>
                                            {{ $sertifikat->lembaga_penerbit }}
                                        </div>
                                    @endif

                                    <div class="flex items-center text-sm text-gray-600 mb-4">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('d M Y') : 'Tanggal tidak tersedia' }}
                                    </div>

                                    @if($sertifikat->link_sertifikat)
                                        <a href="{{ $sertifikat->link_sertifikat }}" target="_blank" rel="noopener noreferrer"
                                            class="mt-auto inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
                                            Lihat Sertifikat
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @else
                                        <p class="mt-auto text-sm text-gray-500 italic">Tidak ada link sertifikat</p>
                                    @endif

                                    <p class="text-xs text-gray-500 mt-5 pt-4 border-t border-gray-100">
                                        Ditambahkan: {{ $sertifikat->created_at?->format('d M Y') ?? '—' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-4 text-gray-600">Belum ada sertifikat yang ditambahkan.</p>
                    </div>
                @endif
            </section>

            <!-- Learning Corners Section -->
            <section class="mt-8 bg-white rounded-2xl border border-gray-200 shadow-sm p-6 md:p-8 mb-10">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Learning Corners</h2>
                    <span class="text-sm text-gray-600 bg-gray-100 px-4 py-1.5 rounded-full">
                        {{ $user->learning_corners->count() }} catatan
                    </span>
                </div>

                @if($user->learning_corners->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($user->learning_corners as $entry)
                            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow border border-gray-200 flex flex-col h-full">
                                <div class="p-6 flex-1 flex flex-col">
                                    @if (!empty($entry->content) && is_array($entry->content))
                                        @foreach ($entry->content as $item)
                                            @if ($item['type'] === 'title')
                                                <h3 class="text-lg font-semibold text-gray-900 mb-3 line-clamp-2">
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
                                                <div class="mb-4">
                                                    <img src="{{ asset('storage/' . ltrim($imagePath, '/')) }}"
                                                        alt="{{ $item['alt'] ?? 'Gambar konten' }}"
                                                        class="w-full h-40 object-cover rounded-lg border border-gray-200 shadow-sm"
                                                        loading="lazy"
                                                        onerror="this.src='https://via.placeholder.com/400x200?text=Gambar+Tidak+Ditemukan';this.onerror=null;">
                                                </div>
                                            @elseif ($item['type'] === 'link')
                                                <a href="{{ $item['content'] }}" target="_blank" rel="noopener noreferrer"
                                                    class="text-indigo-600 hover:text-indigo-800 hover:underline mb-4 block line-clamp-1 break-all">
                                                    {{ Str::limit($item['content'], 70) }}
                                                </a>
                                            @endif
                                        @endforeach
                                    @else
                                        <p class="text-gray-700 mb-4 line-clamp-4">
                                            {{ Str::limit(strip_tags($entry->isi_learning_corner ?? ''), 150) }}
                                        </p>
                                    @endif

                                    <p class="text-sm text-gray-500 mt-auto pt-4 border-t border-gray-100">
                                        Diposting pada:
                                        {{ $entry->created_at?->format('d M Y H:i') ?? ($entry->tanggal?->format('d M Y') ?? 'Tanggal tidak tersedia') }}
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

        <!-- Footer -->
        <div class="mt-8 text-center text-xs text-gray-500">
            Terakhir diperbarui: {{ now()->format('d F Y H:i') }} WIB
        </div>
    </div>
</div>

<script>
function toggleEdit(field) {
    const displayEl = document.getElementById(field + '-display');
    const inputEl   = document.getElementById(field + '-input');
    const saveBtn   = document.getElementById('save-button-container');

    if (!displayEl || !inputEl) return;

    displayEl.classList.add('hidden');
    inputEl.classList.remove('hidden');

    const container = document.getElementById(field + '-container');
    if (container) container.classList.add('hidden');

    inputEl.focus();
    saveBtn.classList.remove('hidden');

    inputEl.onkeypress = function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('form-profile').submit();
        }
    };
}

// Photo Profile Preview
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
    document.getElementById('save-button-container').classList.remove('hidden');
});

// Background Preview
document.getElementById('background_input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();

    reader.onload = function(ev) {
        const coverDiv = document.querySelector('.relative.h-48');
        coverDiv.style.backgroundImage = `url('${ev.target.result}')`;
        coverDiv.classList.add('bg-cover', 'bg-center');
    };

    reader.readAsDataURL(file);
    document.getElementById('save-button-container').classList.remove('hidden');
});

// YouTube Video Player
function playVideo(element, embedUrl) {
    const container = element;
    const playerId = 'player-' + Math.random().toString(36).substr(2, 9);
    
    // Create iframe
    const iframe = document.createElement('iframe');
    iframe.className = 'absolute inset-0 w-full h-full';
    iframe.src = embedUrl + '?autoplay=1&rel=0&modestbranding=1';
    iframe.frameborder = '0';
    iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
    iframe.allowFullscreen = true;
    
    // Replace container content with iframe
    container.innerHTML = '';
    container.appendChild(iframe);
    container.classList.remove('cursor-pointer');
}
</script>

<style>
/* YouTube player container styles */
.relative.w-full.pb-\[56\.25\%\] {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
}

.relative.w-full.pb-\[56\.25\%\] iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

/* Hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

.transition-transform {
    transition-property: transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}
</style>
@endsection