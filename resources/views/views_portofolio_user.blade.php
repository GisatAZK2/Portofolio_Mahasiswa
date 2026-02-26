@extends('Layout.Layout')
@section('title', ($user->nama_mahasiswa ?? 'Mahasiswa') . ' | Portfolio')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Grid 2 Kolom ala LinkedIn -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            
            <!-- ========== KOLOM KANAN (SIDEBAR) ========== -->
            <div class="lg:col-span-1 space-y-4">
                
                <!-- Kartu Profil Ringkas -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <!-- Background Banner -->
                    <div class="h-16 bg-gradient-to-r from-indigo-500 to-indigo-600"></div>
                    
                    <!-- Avatar dan Info Utama -->
                    <div class="px-4 pb-4 relative">
                        <div class="flex justify-between">
                            <div class="-mt-8 mb-2">
                                <div class="w-20 h-20 rounded-full border-4 border-white bg-white shadow-md overflow-hidden">
                                    @if($user->photo_profile)
                                        <img src="{{ asset('storage/' . ltrim($user->photo_profile, '/')) }}"
                                             alt="{{ $user->nama_mahasiswa ?? 'Mahasiswa' }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-indigo-600 flex items-center justify-center text-white text-2xl font-bold">
                                            {{ strtoupper(mb_substr(trim($user->nama_mahasiswa ?? 'M'), 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                    He/Him
                                </span>
                            </div>
                        </div>
                        
                        <h1 class="text-xl font-bold text-gray-900">{{ trim($user->nama_mahasiswa ?? 'Mahasiswa') }}</h1>
                        <p class="text-sm text-gray-600">{{ $user->jurusan?->nama_jurusan ?? 'Mahasiswa' }}</p>
                        <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Kota Bekasi, Jawa Barat, Indonesia
                        </p>
                        
                        <p class="text-xs text-blue-600 font-medium mt-2 cursor-pointer hover:underline">
                            Informasi kontak
                        </p>
                        
                        <!-- Koneksi -->
                        <div class="flex items-center gap-2 mt-3 text-xs">
                            <div class="flex -space-x-2">
                                <div class="w-6 h-6 rounded-full bg-gray-300 border-2 border-white"></div>
                                <div class="w-6 h-6 rounded-full bg-gray-400 border-2 border-white"></div>
                                <div class="w-6 h-6 rounded-full bg-gray-500 border-2 border-white"></div>
                            </div>
                            <span class="text-gray-600">362 koneksi</span>
                        </div>
                        
                        <!-- Open to Work -->
                        <div class="mt-3 p-2 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-xs text-green-800 font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                #OpenToWork
                            </p>
                            <p class="text-xs text-gray-600 mt-1">Frontend Developer Intern</p>
                        </div>
                        
                        <!-- Analytics -->
                        <div class="mt-3 text-xs border-t border-gray-100 pt-3">
                            <div class="flex justify-between text-gray-600">
                                <span>Profil viewers</span>
                                <span class="font-bold text-gray-900">47</span>
                            </div>
                            <div class="flex justify-between text-gray-600 mt-1">
                                <span>Post impressions</span>
                                <span class="font-bold text-gray-900">1,234</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Kartu Keahlian -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
                    <h3 class="text-base font-bold text-gray-900 mb-3">Keahlian</h3>
                    
                    @if($user->keahlian)
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700">Utama</p>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-sm text-gray-600">{{ $user->keahlian->nama_keahlian }}</span>
                            <span class="text-xs text-gray-400">• 12 endorsements</span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">PHP Frameworks</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">Laravel</span>
                                <span class="text-xs text-gray-400">• 8</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">SMK CIBITUNG 1</p>
                        </div>
                        
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">React.js</span>
                                <span class="text-xs text-gray-400">• 5</span>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">Vue.js</span>
                                <span class="text-xs text-gray-400">• 3</span>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">Node.js</span>
                                <span class="text-xs text-gray-400">• 4</span>
                            </div>
                        </div>
                    </div>
                    
                    <button class="text-sm text-gray-600 hover:text-gray-900 mt-3 flex items-center gap-1">
                        Tampilkan semua 5 keahlian
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Minat -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
                    <h3 class="text-base font-bold text-gray-900 mb-3">Minat</h3>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-indigo-100 rounded flex items-center justify-center text-indigo-600 text-xs font-bold">
                                LK
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Lowongan Kerja</p>
                                <p class="text-xs text-gray-500">441.484 pengikut</p>
                            </div>
                        </div>
                        <button class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-medium hover:bg-blue-100">
                            ✔ Mengikuti
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- ========== KOLOM KIRI (KONTEN UTAMA) ========== -->
            <div class="lg:col-span-2 space-y-4">
                
                <!-- Tentang -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
                    <h3 class="text-base font-bold text-gray-900 mb-2">Tentang</h3>
                    <p class="text-sm text-gray-700">
                        Frontend Developer Intern dengan pengalaman dalam membangun aplikasi web modern menggunakan Laravel, React.js, dan Vue.js. Memiliki ketertarikan kuat dalam UI/UX design dan pengembangan website responsif.
                    </p>
                </div>
                
                <!-- Projects -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base font-bold text-gray-900">Projects</h3>
                        <span class="text-xs text-gray-500">{{ $user->projects->count() }} proyek</span>
                    </div>
                    
                    @if($user->projects->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($user->projects as $project)
                                @php
                                    $content = $project->isi_content ?? [];
                                    $nama = $content['nama_project'] ?? 'Tanpa Nama';
                                    $deskripsi = $content['deskripsi'] ?? null;
                                    $linkProject = $content['link_project'] ?? null;
                                    $linkGithub = $content['link_github'] ?? null;
                                @endphp
                                
                                <div class="border border-gray-100 rounded-xl p-3 hover:shadow-sm transition">
                                    <div class="flex items-start gap-3">
                                        <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-sm font-bold text-gray-900">{{ $nama }}</h4>
                                            <p class="text-xs text-gray-500 mt-1">{{ $deskripsi ?? 'Tidak ada deskripsi' }}</p>
                                            <div class="flex gap-3 mt-2">
                                                @if($linkProject)
                                                    <a href="{{ $linkProject }}" class="text-xs text-blue-600 hover:underline">Website</a>
                                                @endif
                                                @if($linkGithub)
                                                    <a href="{{ $linkGithub }}" class="text-xs text-gray-600 hover:underline">GitHub</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada proyek</p>
                    @endif
                </div>
                
                <!-- Sertifikat -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base font-bold text-gray-900">Sertifikat</h3>
                        <span class="text-xs text-gray-500">{{ $user->sertifikats->count() }} sertifikat</span>
                    </div>
                    
                    @if($user->sertifikats->isNotEmpty())
                        <div class="space-y-3">
                            @foreach($user->sertifikats as $sertifikat)
                                <div class="flex items-start gap-3 p-2 hover:bg-gray-50 rounded-lg">
                                    <div class="w-10 h-10 bg-amber-100 rounded flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">{{ $sertifikat->nama_sertifikat ?? 'Sertifikat' }}</h4>
                                        <p class="text-xs text-gray-600">{{ $sertifikat->lembaga_penerbit ?? '' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            Diterbitkan {{ $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('M Y') : '' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada sertifikat</p>
                    @endif
                </div>
                
                <!-- Learning Corners -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
                    <h3 class="text-base font-bold text-gray-900 mb-4">Learning Corners</h3>
                    
                    @if($user->learning_corners->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($user->learning_corners as $entry)
                                <div class="border-b border-gray-100 last:border-0 pb-4 last:pb-0">
                                    @if(!empty($entry->content) && is_array($entry->content))
                                        @foreach($entry->content as $item)
                                            @if($item['type'] === 'title')
                                                <h4 class="text-sm font-bold text-gray-900 mb-1">{{ $item['content'] }}</h4>
                                            @elseif($item['type'] === 'text')
                                                <p class="text-xs text-gray-700 line-clamp-3">{{ $item['content'] }}</p>
                                            @endif
                                        @endforeach
                                    @else
                                        <p class="text-xs text-gray-700 line-clamp-3">{{ Str::limit(strip_tags($entry->isi_learning_corner ?? ''), 150) }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-2">
                                        {{ $entry->created_at?->format('d M Y') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada catatan learning corner</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection