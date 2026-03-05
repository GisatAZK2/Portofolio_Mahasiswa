@extends('Layout.Layout')

@section('content')
    <!-- CONTENT -->

    <div class=" p-10 space-y-10">

        <!-- PROJECT CARD -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- LEFT SIDE -->
                <div>
                    <h2 class="text-2xl font-bold mb-4 dark:text-gray-200">
                        {{ $project->isi_content['nama_project'] ?? 'Tanpa Judul' }}
                    </h2>

                    <p class="font-semibold dark:text-gray-200">Deskripsi:</p>
                    <p class="text-gray-700 m dark:text-gray-300 b-6">
                        {{ $project->isi_content['deskripsi'] ?? 'Tidak ada Deskripsi' }}
                    </p>

                    <p class="font-semibold mb-1 dark:text-gray-200">Siswa Terlibat:</p>
                    <p class=" mt-4 dark:text-gray-200">Pemimpin Tim:</p>
                    @if($project->leader)
                        <li class="ml-4">    
                            <ul>
                                <div class="flex dark:text-gray-200 dark:hover:text-indigo-300 hover:text-indigo-800 items-center gap-2 border px-1 py-1 mt-2 rounded-lg max-w-max">
                                    @if($project->leader)
                                        <img src="{{ asset('storage/' . ltrim($project->leader->photo_profile)) }}"
                                             alt="{{ $project->leader->nama_mahasiswa ?? 'Mahasiswa' }}"
                                             class="w-4 h-4 rounded-full text-indigo-600 object-cover">
                                    @else
                                        <div
                                            class="w-4 h-4 bg-indigo-600 flex items-center justify-center text-white text-3xl font-bold">
                                            {{ strtoupper(mb_substr(trim($project->leader->nama_mahasiswa ?? 'M'), 0, 1)) }}
                                        </div>
                                    @endif
                                    <a href="{{ route('portfolio.show', $project->leader->id) }}">
                                        {{ $project->leader->nama_mahasiswa }}
                                    </a>
                                </div>
                            </ul>
                        </li>
                    @else
                        <p class="dark:text-gray-100 ml-4">Tidak ada leader</p>
                    @endif
                    
                    <p class="mt-6 dark:text-gray-200">Rekan Rekan Kerja:</p>
                    <div class="mt-2 ml-4 space-y-1 text-gray-700 dark:text-gray-300">
                        @forelse($project->members as $member)
                        <li>
                            <ul>
                                @if($member->photo_profile)
                                    <img src="{{ asset('storage/' . ltrim($member->photo_profile, '/')) }}"
                                         alt="{{ $user->nama_mahasiswa ?? 'Mahasiswa' }}"
                                         class="w-4 h-4 rounded-full object-cover text-indigo-600 hover:underline hover:text-indigo-800">
                                @else
                                <div class="flex hover:text-indigo-800 items-center gap-2 border p-1 max-w-max rounded-lg">
                                    <div
                                        class="w-4 h-4 bg-indigo-600 flex items-center justify-center text-white text-xs font-bold rounded-full">
                                        {{ strtoupper(mb_substr(trim($member->nama_mahasiswa ?? 'M'), 0, 1)) }}
                                        
                                    </div>
                                    <a href="{{ route('portfolio.show', $member->id) }}"
                                       class="">
                                    {{ $member->nama_mahasiswa }}
                                    </a> 
                                </div>
                                @endif
                                
                            </ul>
                        </li>
                        
                        @empty
                        <p class="text-gray-400 dark:text-gray-200">Tidak ada rekan</p>
                        @endforelse
                    </div>
                </div>

                <!-- RIGHT SIDE -->
                <div>
                    <p class="font-semibold mb-2 dark:text-gray-200">Time Period</p>
                    <div class="pb-2 dark:text-gray-200">

                    {{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d/m/Y') }}
                    →
                    {{ $project->tanggal_akhir 
                    ? \Carbon\Carbon::parse($project->tanggal_akhir)->format('d/m/Y') 
                    : '-' }}
                    </div>
                    
                    <p class="font-semibold mb-2 dark:text-gray-200">
                        Link:
                    </p>
                    
        <div class="flex gap-4 text-blue-500 dark:text-blue-300 mb-6">
        @if(!empty($project->isi_content['link_video']))
        <a href="{{ $project->isi_content['link_video'] }}" 
           target="_blank"
           class="hover:underline">
           🎥 Video
        </a>
        @endif
        
        @if(!empty($project->isi_content['link_github']))
        <a href="{{ $project->isi_content['link_github'] }}" 
           target="_blank"
           class="hover:underline">
           💻 GitHub
        </a>
        @endif
        
        @if(!empty($project->isi_content['link_project']))
        <a href="{{ $project->isi_content['link_project'] }}" 
            target="_blank"
            class="hover:underline">
            🌐 Project
        </a>
        @endif
    </div>
    
    <!-- Embed Box -->
    @php
    $video = $project->isi_content['link_video'] ?? null;
    $github = $project->isi_content['link_github'] ?? null;
    $projectLink = $project->isi_content['link_project'] ?? null;
    @endphp

    @if($video)
        {{-- VIDEO PRIORITY --}}
        @php
            // Convert youtube link ke embed format
            $embed = null;

            if (str_contains($video, 'watch?v=')) {
                $embed = str_replace('watch?v=', 'embed/', $video);
            } elseif (str_contains($video, 'youtu.be/')) {
                $embed = str_replace('youtu.be/', 'youtube.com/embed/', $video);
            }
        @endphp

        @if($embed)
            <div class="rounded-xl overflow-hidden shadow">
                <iframe 
                    class="w-full h-64"
                    src="{{ $embed }}"
                    frameborder="0"
                    allowfullscreen>
                </iframe>
            </div>
        @endif

    @elseif($github || $projectLink)
        {{-- WEBSITE FALLBACK --}}
        <div class="rounded-xl overflow-hidden shadow">
            <iframe 
                class="w-full h-64"
                src="{{ $github ?? $projectLink }}"
                frameborder="0">
            </iframe>
        </div>

    @endif
                </div>

            </div>
        </div>

        <!-- LEARNING CORNER -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-8 relative">
            <h2 class="text-2xl dark:text-gray-50 font-bold mb-6">
                Learning Corner
            </h2>

            <div class="flex justify-end items-center mb-6">
                @auth
                @if (auth()->id()===$project->id_mahasiswa)
                <a href="{{ route('learning-corner.create', $project->id) }}" 
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Tambahkan Learning Corner
                </a>
                @endif
                @endauth
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                @forelse($entries ?? [] as $entry)
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow">
                        @if (!empty($entry->content) && is_array($entry->content))
                                @foreach ($entry->content as $item)
                                    @if ($item['type'] === 'title')
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-50 mb-3 line-clamp-2">
                                            {{ $item['content'] ?? '(Tanpa Judul)' }}
                                        </h3>
                                    @elseif ($item['type'] === 'text')
                                        <p class="text-gray-700 dark:text-gray-300 mb-4 line-clamp-4">
                                            {{ $item['content'] }}
                                        </p>
                                    @elseif ($item['type'] === 'image')
                                        @php
                                            $imagePath = str_replace(['\\', '/'], '/', $item['content'] ?? '');
                                        @endphp
                                        <div class="mb-5">
                                            <img src="{{ asset('storage/' . ltrim($imagePath, '/')) }}"
                                                alt="{{ $item['alt'] ?? 'Gambar konten Learning Corner' }}"
                                                class="w-full h-48 object-cover rounded-lg border border-gray-200 shadow-sm" loading="lazy"
                                                onerror="this.src='https://via.placeholder.com/400x200?text=Gambar+Tidak+Ditemukan';this.onerror=null;">
                                        </div>
                                    @elseif ($item['type'] === 'link')
                                        <a href="{{ $item['content'] }}" target="_blank" rel="noopener noreferrer"
                                            class="text-indigo-600 dark:text-indigo-300 dark:hover:text-indigo-500 hover:text-indigo-800 hover:underline mb-4 block line-clamp-1 break-all">
                                            {{ Str::limit($item['content'], 70) }}
                                        </a>
                                    @endif
                                @endforeach
                            @else
                                <p class="text-gray-500 italic text-center py-4">Konten tidak tersedia atau format salah</p>
                            @endif

                            <!-- Tanggal -->
                            <p class="text-sm text-gray-500 mt-auto pt-5 border-t border-gray-100 dark:text-white">
                                Diposting pada:
                                {{ $entry->created_at?->format('d M Y H:i') ?? ($entry->tanggal?->format('d M Y') ?? 'Tanggal tidak tersedia') }}
                            </p>

                        <div class="mt-4 text-right">
                            <a href="#" class="text-blue-500">
                                Lihat lebih lengkap >>
                            </a>
                        </div>
                        <div>
                            @auth
                                @if (auth()->id()===$project->id_mahasiswa)
                                    <div class="flex space-x-3 mt-6">
                                        <a href="{{ route('learning-corner.edit', $entry->id_learning_corner) }}"
                                           class="flex-1 text-center py-2.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition font-medium border border-blue-200">
                                            Edit
                                        </a>

                                    <form class="delete-form flex-1"
                                        action="{{ route('learning-corner.destroy', $entry->id_learning_corner) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="delete-btn w-full py-2.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition font-medium border border-red-200">
                                                    Hapus
                                        </button>
                                    </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                    @empty
                       <div class="col-span-2 flex justify-center items-center py-10 text-gray-400 dark:text-white dark:bg-gray-900 bg-white italic">
                            <p>Belum ada Learning Corner.</p>
                       </div>
                    @endforelse

            </div>
        </div>
                <!--<div class="bg-gray-800 h-48 flex items-center justify-center text-white">
                    embed video/github
                </div>

                <div class="p-4">
                    <h3 class="font-bold text-lg">
                        Title Title Title Title
                    </h3>
                    <p class="text-gray-600 mt-2">
                        Body body body body body body body...
                    </p>

                    <div class="mt-4 text-right">
                        <a href="#" class="text-blue-500">
                            Lihat lebih lengkap >>
                        </a>
                    </div>
                </div>-->
<script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', async function (e) {
                    e.preventDefault();

                    const confirmed = await showConfirmAlert({
                        title: 'Hapus Entri Learning Corner?',
                        text: 'Catatan ini akan dihapus permanen dan tidak bisa dikembalikan.',
                        icon: 'warning',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                    });

                    if (confirmed) {
                        showLoading('Menghapus catatan...');
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