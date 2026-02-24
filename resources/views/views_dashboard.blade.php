@extends('Layout.Layout')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-8">

    {{-- ===================== --}}
    {{-- STATISTIC CARDS --}}
    {{-- ===================== --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <h3 class="text-gray-500">Total Mahasiswa</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">
                {{ $totalMahasiswa }}
            </p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <h3 class="text-gray-500">Total Portfolio</h3>
            <p class="text-3xl font-bold text-green-600 mt-2">
                {{ $totalPortofolio }}
            </p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <h3 class="text-gray-500">Learning Corner</h3>
            <p class="text-3xl font-bold text-purple-600 mt-2">
                {{ $totalLearning }}
            </p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 text-center">
            <h3 class="text-gray-500">Project</h3>
            <p class="text-3xl font-bold text-orange-600 mt-2">
                {{ $totalProject }}
            </p>
        </div>

    </div>

    {{-- ===================== --}}
    {{-- RANDOM POSTS --}}
    {{-- ===================== --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            🔥 Postingan Acak
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($randomPosts as $post)
                <div class="bg-white rounded-lg shadow p-6 space-y-3">

                    {{-- USER --}}
                    <div class="flex items-center space-x-3">
                        <img src="{{ $post->mahasiswa->photo_profile ?? 'https://ui-avatars.com/api/?name='.$post->mahasiswa->nama_mahasiswa }}"
                             class="w-10 h-10 rounded-full object-cover">

                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $post->mahasiswa->nama_mahasiswa }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $post->created_at ?? $post->tanggal }}
                            </p>
                        </div>
                    </div>

                    {{-- CONTENT --}}
                    <div class="text-gray-700 text-sm">

                        @if($post->type === 'portofolio')
                            <p class="font-semibold text-green-600 mb-2">Portfolio</p>
                            <p>
                                {{ $post->isi_content['judul'] ?? 'Portfolio Content' }}
                            </p>

                        @elseif($post->type === 'learning')
                            <p class="font-semibold text-purple-600 mb-2">Learning Corner</p>
                            <p>
                                {{ \Illuminate\Support\Str::limit($post->isi_learning_corner, 100) }}
                            </p>

                        @elseif($post->type === 'project')
                            <p class="font-semibold text-orange-600 mb-2">Project</p>
                            <p>
                                {{ $post->nama_project }}
                            </p>
                        @endif

                    </div>

                </div>
            @endforeach

        </div>
    </div>

</div>

@endsection
