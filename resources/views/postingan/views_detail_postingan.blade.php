@extends('Layout.Layout')

@section('title', autoTranslate('Detail Postingan'))

@section('content')
<div class="bg-gradient-to-b from-gray-50 to-white dark:from-gray-950 dark:to-gray-900 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <!-- Tombol Kembali -->
        <div class="mb-4">
            <button onclick="window.location.href='{{ route('dashboard') }}'"
                class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors group">
                <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="text-sm font-medium">{{ autoTranslate('Kembali ke Beranda') }}</span>
            </button>
        </div>

        <!-- Card Utama -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300 hover:shadow-2xl">
            <!-- Header Profil -->
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 bg-gradient-to-r from-white to-gray-50 dark:from-gray-900 dark:to-gray-800/50">
                <div class="flex items-start justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <!-- Avatar -->
                        <div class="relative">
                            @if($postingan->user->photo_profile && file_exists(public_path('storage/' . $postingan->user->photo_profile)))
                                <img src="{{ asset('storage/' . $postingan->user->photo_profile) }}"
                                    class="w-14 h-14 rounded-full object-cover ring-2 ring-indigo-200 dark:ring-indigo-800 shadow-md">
                            @else
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center shadow-md ring-2 ring-indigo-200 dark:ring-indigo-800">
                                    <span class="text-white font-bold text-xl">
                                        {{ strtoupper(substr($postingan->user->nama_mahasiswa, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <div>
                                <div class="gap-2 mt-1 mb-1">
                                    <a href="{{ route('portfolio.show', ['user' => $postingan->user->username]) }}"
                                                    class="font-bold text-gray-900 dark:text-gray-100 text-lg hover:text-indigo-600 dark:hover:text-indigo-400 transition">{{ $postingan->user->nama_mahasiswa }}</a>
                                    @if(!empty($postingan->user->jurusan))
                                        <span class="text-[14px] px-2 py-1 bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300 rounded-full">{{ $postingan->user->jurusan['nama_jurusan'] ?? '-' }}</span>
                                    @endif
                                    @if(!empty($postingan->user->angkatan))
                                        <span class="text-[14px] px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded-full">{{ $postingan->user->angkatan['nama_angkatan'] ?? '-' }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{-- PERBAIKAN: Pastikan tanggal + jam tampil dengan benar --}}
                                <span>{{ \Carbon\Carbon::parse($postingan->tanggal)->translatedFormat('d F Y \j\a\m H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Tombol Share -->
                        <button onclick="toggleShare(this)" 
                            class="p-2 text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 rounded-full hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all duration-200"
                            title="{{ autoTranslate('Bagikan') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                        </button>
                        
                        @if(auth()->check() && auth()->id() == $postingan->id_user)
                            <div class="relative" id="postMenuContainer">
                                <button id="postMenuButton" 
                                    class="p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition focus:outline-none">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                    </svg>
                                </button>
                                <div id="postMenuDropdown" 
                                    class="hidden absolute right-0 mt-2 w-36 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 z-20 overflow-hidden">
                                    <a href="{{ route('postingan.edit', ['id' => $postingan->id_postingan]) }}"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        {{ autoTranslate('Edit') }}
                                    </a>
                                    <form action="{{ route('postingan.destroy', ['id' => $postingan->id_postingan]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="if(confirm('{{ autoTranslate('Apakah Anda yakin ingin menghapus postingan ini?') }}')) this.form.submit();"
                                            class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            {{ autoTranslate('Hapus') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Konten Postingan (sama seperti kode Anda, tidak diubah) -->
            <div class="p-6 space-y-6">
                @php
                    $content = $postingan->content;
                    $title = '';
                    $deskripsi = '';
                    $items = [];
                    $gameThumbnail = null;

                    if (is_array($content)) {
                        foreach ($content as $item) {
                            if (isset($item['type'])) {
                                if ($item['type'] === 'title') {
                                    $title = $item['content'] ?? '';
                                } elseif ($item['type'] === 'description') {
                                    $deskripsi = $item['content'] ?? '';
                                } else {
                                    $items[] = $item;
                                }
                            }
                        }
                    }
                    
                    $game = $postingan->game ?? null;
                    $gameThumbnailMap = [
                        'matematika' => 'assets/game-angka.svg',
                        'math' => 'assets/game-angka.svg',
                        'puzzle' => 'assets/game-puzzle.svg',
                        'tts' => 'assets/game-tts.svg',
                        'teka-teki silang' => 'assets/game-tts.svg',
                    ];

                    $gameThumbnail = '';
                    if ($game && isset($gameThumbnailMap[strtolower($game->game_name)])) {
                        $gameThumbnail = $gameThumbnailMap[strtolower($game->game_name)];
                    }
                    
                    $gameRoute = '';
                    $gameDisplayName = '';
                    if ($game) {
                        switch(strtolower(trim($game->game_name))) {
                            case 'matematika':
                            case 'math':
                                $gameRoute = route('game.matematika', ['locale' => app()->getLocale()]);
                                $gameDisplayName = 'Matematika';
                                break;
                            case 'puzzle':
                                $gameRoute = route('game.puzzle', ['locale' => app()->getLocale()]);
                                $gameDisplayName = 'Puzzle';
                                break;
                            case 'tts':
                            case 'teka-teki silang':
                                $gameRoute = route('game.tts', ['locale' => app()->getLocale()]);
                                $gameDisplayName = 'Teka-Teki Silang';
                                break;
                            default:
                                $gameRoute = route('game.matematika', ['locale' => app()->getLocale()]);
                                $gameDisplayName = $game->game_name;
                        }
                    }
                @endphp

                @if($title)
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white leading-tight">
                        {{ autoTranslate($title) }}
                    </h1>
                @endif

                @if($deskripsi)
                    <div class="prose prose-sm max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                        <p>{{ autoTranslate($deskripsi) }}</p>
                    </div>
                @endif

                @if(!empty($items))
                    <div class="space-y-5">
                        @foreach($items as $item)
                            @if(isset($item['type']))
                                @if($item['type'] === 'image' && isset($item['content']))
                                    <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-md hover:shadow-lg transition-shadow">
                                        <img src="{{ asset('storage/' . $item['content']) }}" 
                                            class="w-full h-auto object-cover" alt="Gambar postingan" id="logo-zoom">
                                    </div>
                                @elseif($item['type'] === 'text' && isset($item['content']))
                                    <div class="prose prose-sm max-w-none text-gray-700 dark:text-gray-300">
                                        <p>{{ autoTranslate($item['content']) }}</p>
                                    </div>
                                @elseif($item['type'] === 'link' && isset($item['content']))
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition group">
                                        <a href="{{ $item['content'] }}" target="_blank" rel="noopener noreferrer"
                                            class="flex items-center gap-3 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium break-all">
                                            <svg class="w-5 h-5 shrink-0 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                            </svg>
                                            <span class="truncate">{{ $item['content'] }}</span>
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                @endif

                @if($game)
                    <div class="mt-6 p-5 border border-indigo-200 dark:border-indigo-800/50 rounded-2xl bg-gradient-to-r from-indigo-50/50 to-white dark:from-indigo-950/20 dark:to-gray-900 shadow-md hover:shadow-lg transition-all">
                        <div class="flex flex-col sm:flex-row items-center gap-5">
                            <div class="shrink-0">
                                @if($gameThumbnail)
                                    <img src="{{ asset($gameThumbnail) }}" 
                                        class="w-24 h-24 object-contain rounded-xl shadow-md" 
                                        alt="Game Thumbnail">
                                @else
                                    <div class="w-24 h-24 bg-indigo-100 dark:bg-indigo-900/50 rounded-xl flex items-center justify-center">
                                        <svg class="w-12 h-12 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 text-center sm:text-left">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $gameDisplayName }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ autoTranslate('Mainkan game ini dan asah kemampuanmu!') }}</p>
                                @if($game->score > 0)
                                    <div class="inline-flex items-center gap-1 mt-2 px-2 py-1 bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300 rounded-full text-xs font-semibold">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        <span>{{ autoTranslate('Skor terbaik') }}: {{ $game->score }}</span>
                                    </div>
                                @endif
                            </div>
                            @auth
                            <a href="{{ $gameRoute }}?postingan={{ $postingan->id_postingan }}&game={{ $game->id_games }}" 
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full font-medium shadow-md hover:shadow-lg transition-all transform hover:scale-105">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm3 4v2h2V7H8zm6 0v2h2V7h-2zm-6 6v2h2v-2H8zm6 0v2h2v-2h-2z"/>
                                </svg>
                                {{ autoTranslate('Mainkan Sekarang') }}
                            </a>
                            @endauth
                        </div>
                    </div>
                @endif
            </div>

            <!-- Engagement Bar -->
            <div class="px-6 py-3 border-t border-b border-gray-100 dark:border-gray-800 flex items-center justify-between text-sm">
                <div class="flex items-center gap-6">
                    <button class="like-btn flex items-center gap-2 transition-colors hover:text-red-500 {{ $postingan->likes->contains('id_user', auth()->id()) ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}" 
                        data-postingan-id="{{ $postingan->id_postingan }}">
                        <svg class="w-5 h-5 {{ $postingan->likes->contains('id_user', auth()->id()) ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="like-count-text font-medium">{{ $postingan->likes->count() }}</span>
                        <span class="hidden sm:inline">{{ autoTranslate('Suka') }}</span>
                    </button>
                    
                    <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span class="font-medium">{{ $postingan->komentar->count() }}</span>
                        <span class="hidden sm:inline">{{ autoTranslate('Komentar') }}</span>
                    </div>
                </div>
            </div>

            <!-- Bagian Komentar -->
            <div id="comments" class="px-6 py-6 space-y-6">
                <h3 class="font-bold text-xl text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    {{ autoTranslate('Komentar') }} ({{ $postingan->komentar->count() }})
                </h3>

                <!-- Form Tambah Komentar -->
                @auth
                    <form action="{{ route('komentar.store') }}" method="POST" class="mb-6">
                        @csrf
                        <input type="hidden" name="id_postingan" value="{{ $postingan->id_postingan }}">
                        <div class="flex gap-3">
                            @if(auth()->user()->photo_profile && file_exists(public_path('storage/' . auth()->user()->photo_profile)))
                                <img src="{{ asset('storage/' . auth()->user()->photo_profile) }}"
                                    class="w-10 h-10 rounded-full object-cover shrink-0 ring-2 ring-indigo-100 dark:ring-indigo-900">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center shrink-0">
                                    <span class="text-white font-semibold text-sm">
                                        {{ strtoupper(substr(auth()->user()->nama_mahasiswa, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div class="flex-1">
                                <textarea name="komentar" rows="2"
                                    class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-800 border border-transparent rounded-2xl focus:bg-white dark:focus:bg-gray-900 focus:border-indigo-300 dark:focus:border-indigo-700 outline-none transition text-gray-900 dark:text-white placeholder-gray-500"
                                    placeholder="{{ autoTranslate('Tulis komentar...') }}">{{ old('komentar') }}</textarea>
                                @error('komentar')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                <div class="flex justify-end gap-2 mt-2">
                                    <button type="reset" 
                                        class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition">
                                        {{ autoTranslate('Batal') }}
                                    </button>
                                    <button type="submit" 
                                        class="px-4 py-1.5 text-sm bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition shadow-sm">
                                        {{ autoTranslate('Kirim') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-2xl p-4 text-center">
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            {{ autoTranslate('Silakan') }} <a href="{{ route('login') }}" class="font-semibold underline hover:no-underline">{{ autoTranslate('masuk') }}</a> {{ autoTranslate('untuk berkomentar.') }}
                        </p>
                    </div>
                @endauth

                <!-- Daftar Komentar -->
                @if($postingan->komentar->count() > 0)
                    <div class="space-y-4">
                        @foreach($postingan->komentar->sortByDesc('tanggal') as $komentar)
                            @php
                                $isOwnComment = auth()->check() && auth()->id() == $komentar->id_user;
                            @endphp
                            <div class="group flex gap-3 p-3 rounded-xl transition-all duration-200 {{ $isOwnComment ? 'bg-indigo-50/50 dark:bg-indigo-950/20 border-l-4 border-indigo-500' : 'hover:bg-gray-50 dark:hover:bg-gray-800/30' }}">
                                <!-- Avatar -->
                                @if($komentar->user->photo_profile && file_exists(public_path('storage/' . $komentar->user->photo_profile)))
                                    <img src="{{ asset('storage/' . $komentar->user->photo_profile) }}"
                                        class="w-8 h-8 rounded-full object-cover shrink-0">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shrink-0">
                                        <span class="text-white font-bold text-xs">
                                            {{ strtoupper(substr($komentar->user->nama_mahasiswa, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0">
                                    <!-- Bubble Komentar -->
                                    <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl px-4 py-2 {{ $isOwnComment ? 'bg-indigo-100 dark:bg-indigo-900/40 border border-indigo-200 dark:border-indigo-800' : '' }}">
                                        <div class="flex items-baseline gap-2 flex-wrap">
                                            <span class="font-semibold text-sm text-gray-900 dark:text-white">
                                                <a href="{{ route('portfolio.show', ['user' => $komentar->user->username]) }}"
                                                    class="text-sm text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">{{ $komentar->user->nama_mahasiswa }}</a>
                                                @if($isOwnComment)
                                                    <span class="text-xs font-normal text-indigo-600 dark:text-indigo-400 ml-1">({{ autoTranslate('Anda') }})</span>
                                                @endif
                                            </span>
                                            {{-- PERBAIKAN: Tampilkan tanggal komentar dalam format absolut (bukan relative time) --}}
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($komentar->tanggal)->translatedFormat('d F Y \j\a\m H:i') }}</span>
                                        </div>
                                        <div id="comment-content-{{ $komentar->id_komentar }}" class="text-sm text-gray-700 dark:text-gray-300 mt-1 break-words">
                                            {{ autoTranslate($komentar->komentar) }}
                                        </div>
                                        <!-- Form Edit -->
                                        <form id="edit-form-{{ $komentar->id_komentar }}" action="{{ route('komentar.update', ['id' => $komentar->id_komentar]) }}" method="POST" class="hidden mt-2">
                                            @csrf @method('PUT')
                                            <textarea name="komentar" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-xl text-sm">{{ $komentar->komentar }}</textarea>
                                            <div class="flex justify-end gap-2 mt-1">
                                                <button type="button" onclick="cancelEdit({{ $komentar->id_komentar }})" 
                                                    class="px-3 py-1 text-xs text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-full">
                                                    {{ autoTranslate('Batal') }}
                                                </button>
                                                <button type="submit" 
                                                    class="px-3 py-1 text-xs bg-indigo-600 text-white rounded-full hover:bg-indigo-700">
                                                    {{ autoTranslate('Simpan') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- Action Buttons -->
                                    @auth
                                        @if($isOwnComment)
                                            <div class="flex items-center gap-2 mt-1 ml-2 opacity-0 group-hover:opacity-100 transition">
                                                <button onclick="editComment({{ $komentar->id_komentar }})" 
                                                    class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 font-medium">
                                                    {{ autoTranslate('Edit') }}
                                                </button>
                                                <span class="text-gray-300 dark:text-gray-700">•</span>
                                                <form action="{{ route('komentar.destroy', ['id' => $komentar->id_komentar]) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button" onclick="if(confirm('{{ autoTranslate('Hapus komentar ini?') }}')) this.form.submit();" 
                                                        class="text-xs text-red-600 dark:text-red-400 hover:text-red-800 font-medium">
                                                        {{ autoTranslate('Hapus') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">{{ autoTranslate('Belum ada komentar. Jadilah yang pertama!') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
const postMenuBtn = document.getElementById('postMenuButton');
const postMenuDropdown = document.getElementById('postMenuDropdown');

    if (postMenuBtn && postMenuDropdown) {
        postMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            postMenuDropdown.classList.toggle('hidden');
        });
        
        document.addEventListener('click', function(e) {
            if (!postMenuContainer.contains(e.target)) {
                postMenuDropdown.classList.add('hidden');
            }
        });
        
        postMenuDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    function toggleShare(btn) {
        const url = window.location.href;
        if (navigator.share) {
            navigator.share({ url }).catch(() => {});
            return;
        }
        navigator.clipboard.writeText(url).then(() => {
            const original = btn.innerHTML;
            btn.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
            setTimeout(() => { btn.innerHTML = original; }, 2000);
        }).catch(() => alert('Gagal menyalin link'));
    }

    function editComment(id) {
        document.getElementById('comment-content-' + id).classList.add('hidden');
        document.getElementById('edit-form-' + id).classList.remove('hidden');
    }

    function cancelEdit(id) {
        document.getElementById('comment-content-' + id).classList.remove('hidden');
        document.getElementById('edit-form-' + id).classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const likeBtn = document.querySelector('.like-btn');
        if (likeBtn) {
            likeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const postinganId = this.dataset.postinganId;
                const locale = document.querySelector('html').getAttribute('lang') || 'id';
                fetch(`/${locale}/postingan/toggle-like?id=${postinganId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const btn = document.querySelector('.like-btn');
                        const icon = btn.querySelector('svg');
                        const countSpan = btn.querySelector('.like-count-text');
                        if (data.liked) {
                            btn.classList.add('text-red-500', 'dark:text-red-400');
                            icon.classList.add('fill-current');
                        } else {
                            btn.classList.remove('text-red-500', 'dark:text-red-400');
                            icon.classList.remove('fill-current');
                        }
                        if (countSpan) countSpan.textContent = data.like_count;
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }
    });
</script>
@endsection