@extends('Layout.Layout')
@section('title', 'Project Saya')
@section('content')
<div class="p-6 lg:p-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Project Saya</h1>
            <p class="text-gray-600 mt-1">
                Kelola semua postingan project kamu di sini.
            </p>
        </div>
        <a href="{{ route('project.create') }}" 
           class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Project Baru
        </a>
    </div>

    {{-- Data --}}
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
                        <h3 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-2">{{ $project->isi_content['nama_project'] ?? '-' }}</h3>
                        
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <p><span class="font-medium">Mulai:</span> {{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d M Y') }}</p>
                            @if ($project->tanggal_akhir)
                                <p><span class="font-medium">Selesai:</span> {{ \Carbon\Carbon::parse($project->tanggal_akhir)->format('d M Y') }}</p>
                            @endif
                           
                            @if($project->link_project || $project->link_github || $project->link_video)
                                <div class="flex flex-wrap gap-3 text-sm mb-4">
                                    @if($project->link_project)
                                        <a href="{{ $project->link_project }}" target="_blank" class="text-indigo-600 hover:underline">Project</a>
                                    @endif
                                    @if($project->link_github)
                                        <a href="{{ $project->link_github }}" target="_blank" class="text-indigo-600 hover:underline">GitHub</a>
                                    @endif
                                    @if($project->link_video)
                                        <a href="{{ $project->link_video }}" target="_blank" class="text-indigo-600 hover:underline">Video</a>
                                    @endif
                                </div>
                            @endif 
                            <!--
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
                        </div> -->

                        <div class="flex space-x-3">
                            <a href="{{ route('project.edit', $project->id) }}" 
                               class="flex-1 text-center py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                                Edit
                            </a>

                            <form class="delete-form flex-1" 
                                  action="{{ route('project.destroy', $project->id) }}" 
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        class="delete-btn w-full py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition">
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();

            const confirmed = await showConfirmAlert({
                title: 'Hapus Project?',
                text: 'Project ini akan dihapus permanen dan tidak bisa dikembalikan.',
                icon: 'warning',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
            });

            if (confirmed) {
                showLoading('Menghapus project...');
                this.closest('form').submit();
            }
        });
    });

    @if (session('success'))
        showSuccessAlert('{{ session('success') }}');
    @endif
});
</script>
@endsection