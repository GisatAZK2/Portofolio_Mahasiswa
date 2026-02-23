@extends('layouts.app') <!-- atau layout yang kamu pakai -->

@section('content')
<div class="p-6 lg:p-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Project Saya</h1>
        <a href="{{ route('project.create') }}" class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Project Baru
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl">
            {{ session('success') }}
        </div>
    @endif

    @if ($projects->isEmpty())
        <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="mt-4 text-gray-600">Belum ada project yang ditambahkan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($projects as $project)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-2">{{ $project->nama_project }}</h3>
                        
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <p><span class="font-medium">Mulai:</span> {{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d M Y') }}</p>
                            @if ($project->tanggal_akhir)
                                <p><span class="font-medium">Selesai:</span> {{ \Carbon\Carbon::parse($project->tanggal_akhir)->format('d M Y') }}</p>
                            @endif
                            @if ($project->link_project)
                                <p>
                                    <a href="{{ $project->link_project }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        Lihat Project
                                    </a>
                                </p>
                            @endif
                        </div>

                        <div class="flex space-x-3">
                            <a href="{{ route('project.edit', $project->id) }}" class="flex-1 text-center py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                                Edit
                            </a>
                            <form action="{{ route('project.destroy', $project->id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition" onclick="return confirm('Yakin hapus project ini?')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection