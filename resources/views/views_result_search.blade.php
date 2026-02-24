@extends('Layout.Layout')
@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Ringkasan Hasil -->
    @if($results->count() > 0)
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap gap-3">
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-800 font-medium text-sm">
                    Mahasiswa: {{ $results->where('type', 'mahasiswa')->count() }}
                </span>
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-green-100 text-green-800 font-medium text-sm">
                    Project: {{ $results->where('type', 'project')->count() }}
                </span>
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-100 text-indigo-800 font-medium text-sm">
                    Portofolio: {{ $results->where('type', 'portofolio')->count() }}
                </span>
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 font-medium text-sm">
                    Learning: {{ $results->where('type', 'learning')->count() ?? 0 }}
                </span>
            </div>
            <span class="text-gray-700 font-medium">
                Total: {{ $results->count() }} hasil ditemukan
            </span>
        </div>
    @endif

    <!-- Hasil dalam Grid Card -->
    @if($results->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($results as $item)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full">

                    @if($item->type == 'mahasiswa')
                        <div class="p-6 flex flex-col flex-1">
                            <!-- Foto + Nama + Badge -->
                            <div class="flex items-start gap-4 mb-4">
                                <div class="flex-shrink-0">
                                    @if($item->photo_profile)
                                        <img src="{{ asset('storage/' . $item->photo_profile) }}"
                                             class="w-14 h-14 rounded-full object-cover border-2 border-gray-200 shadow-sm"
                                             alt="{{ $item->nama_mahasiswa ?? 'Profil' }}">
                                    @else
                                        <div class="w-14 h-14 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xl shadow-sm">
                                            {{ strtoupper(substr($item->nama_mahasiswa ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $item->nama_mahasiswa ?? 'Nama tidak tersedia' }}
                                        </h3>
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Mahasiswa
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600 flex items-center gap-1">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $item->email ?? 'Email tidak tersedia' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Jurusan & Keahlian (seperti contoh: Teknik Informatika Web Development) -->
                            <div class="flex flex-wrap gap-2 mb-5">
                                @if($item->jurusan)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5"/>
                                        </svg>
                                        {{ $item->jurusan->nama_jurusan }}
                                    </span>
                                @endif

                                @if($item->keahlian)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                        {{ $item->keahlian->nama_keahlian }}
                                    </span>
                                @endif
                            </div>

                            <!-- Statistik -->
                            <div class="mt-auto text-sm text-gray-600 flex justify-between border-t pt-4">
                                <span><strong class="text-gray-900">{{ $item->projects_count ?? 0 }}</strong> Project</span>
                                <span><strong class="text-gray-900">{{ $item->portofolios_count ?? 0 }}</strong> Portofolio</span>
                                <span><strong class="text-gray-900">{{ $item->learning_count ?? 0 }}</strong> Learning</span>
                            </div>
                        </div>

                    @elseif($item->type == 'project')
                        <div class="p-6 flex flex-col flex-1 border-t-4 border-green-500">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-3">
                                Project
                            </span>

                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $item->nama_project ?? 'Project Tanpa Judul' }}
                            </h3>

                            @if($item->mahasiswa)
                                <p class="text-sm text-gray-600 mb-3">
                                    Oleh <strong>{{ $item->mahasiswa->nama_mahasiswa ?? '—' }}</strong>
                                </p>
                            @endif

                            <p class="text-sm text-gray-500 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') : '—' }}
                                @if($item->tanggal_akhir)
                                    - {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d M Y') }}
                                @else
                                    - Sekarang
                                @endif
                            </p>

                            @if($item->link_project)
                                <a href="{{ $item->link_project }}" target="_blank" rel="noopener noreferrer"
                                   class="mt-auto inline-flex items-center justify-center px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                                    Lihat Project →
                                </a>
                            @else
                                <p class="mt-auto text-sm text-gray-500 italic">Tidak ada link project</p>
                            @endif
                        </div>

                    @elseif(in_array($item->type, ['portofolio', 'learning']))
                        <!-- Portofolio & Learning tetap seperti sebelumnya -->
                        <div class="p-6 flex flex-col flex-1 border-t-4 border-{{ $item->type == 'portofolio' ? 'indigo' : 'yellow' }}-500">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-{{ $item->type == 'portofolio' ? 'indigo' : 'yellow' }}-100 text-{{ $item->type == 'portofolio' ? 'indigo' : 'yellow' }}-800 mb-3">
                                {{ ucfirst($item->type) }}
                            </span>

                            @if($item->mahasiswa)
                                <p class="text-sm text-gray-600 mb-3">
                                    Oleh <strong>{{ $item->mahasiswa->nama_mahasiswa ?? '—' }}</strong>
                                </p>
                            @endif

                            <p class="text-gray-700 line-clamp-4 mb-4 text-sm">
                                {{ Str::limit(strip_tags($item->isi_content ?? $item->content ?? $item->isi_learning_corner ?? 'Tidak ada deskripsi'), 150) }}
                            </p>

                            <p class="text-xs text-gray-500 mt-auto pt-4 border-t border-gray-100">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($item->tanggal ?? $item->created_at)->diffForHumans() }}
                            </p>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

        <!-- Pagination (jika menggunakan paginator) -->
        @if(method_exists($results, 'links'))
            <div class="mt-10 flex justify-center">
                {{ $results->links() }}
            </div>
        @endif

    @else
        <!-- Tidak ada hasil -->
        <div class="text-center py-20 bg-white rounded-xl shadow-sm border border-gray-200">
            <svg class="mx-auto h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <h3 class="mt-6 text-2xl font-medium text-gray-900">Tidak ada hasil ditemukan</h3>
            <p class="mt-3 text-gray-600 max-w-md mx-auto">
                Coba ubah kata kunci, pilih jurusan/keahlian lain, atau hapus filter di search bar atas.
            </p>
        </div>
    @endif

</div>
@endsection