@extends('Layout.Layout')

@section('content')
<div class="p-6 lg:p-8 max-w-7xl mx-auto">

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($projects->isEmpty())
        <div class="text-center py-12 text-gray-500">
            <p class="text-lg">Belum ada project yang dibagikan saat ini.</p>
            <p class="mt-2">Ayo jadi yang pertama upload projectmu!</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($projects as $project)
            <div class="group bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-gray-300 transition-all duration-300 hover:shadow-md flex flex-col h-full">
                <!-- Header / Judul -->
                <div class="p-5 pb-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="font-semibold text-lg leading-tight text-gray-900 group-hover:text-blue-700 transition-colors line-clamp-2">
                        {{ $project->nama_project }}
                    </h3>
                </div>

                <!-- Body -->
                <div class="p-5 flex-1 flex flex-col gap-3 text-sm">
                    <div class="flex items-center gap-2 text-gray-700">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>
                            <span class="font-medium text-gray-900">{{ $project->mahasiswa->nama_mahasiswa }}</span>
                        </span>
                    </div>

                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Mulai: {{ $project->tanggal_mulai->format('d M Y') }}</span>
                    </div>

                    @if($project->tanggal_akhir)
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Selesai: {{ $project->tanggal_akhir->format('d M Y') }}</span>
                    </div>
                    @endif
                </div>

                <!-- Footer / Action -->
                <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 mt-auto">
                    @if($project->link_project)
                    <a href="{{ $project->link_project }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium transition-colors">
                        <span>Lihat Project</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                    @else
                    <span class="text-gray-400 text-sm italic">Tidak ada link demo</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination (jika pakai paginate) -->
        @if($projects instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-10">
                {{ $projects->links() }}
            </div>
        @endif
    @endif
</div>
@endsection