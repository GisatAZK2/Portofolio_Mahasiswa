@extends('Layout.Layout')
@section('title', 'Postingan Saya')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="p-6 lg:p-8 max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8 text-center md:text-left">
                <div class="flex items-center gap-3 mb-2 justify-center md:justify-start">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Postingan Saya</h1>
                </div>
                <p class="mt-2 text-gray-600 dark:text-gray-400 text-sm md:text-base max-w-md mx-auto md:mx-0">
                    Kelola semua postingan yang telah Anda buat.
                </p>
            </div>

            <!-- Create Button -->
            <div class="mb-6 text-center md:text-left">
                <a href="{{ route('postingan.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Postingan Baru
                </a>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Data Postingan --}}
            @if ($postingan->isEmpty())
                <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="mt-4 text-gray-600 dark:text-gray-400">Belum ada postingan.</p>
                    <p class="text-gray-500 dark:text-gray-500 text-sm mt-2">Mulai buat postingan pertama Anda!</p>
                    <a href="{{ route('postingan.create') }}"
                        class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Buat Postingan
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($postingan as $post)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
                            <!-- Header -->
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-3">
                                        @if($post->user->photo_profile && file_exists(public_path('storage/' . $post->user->photo_profile)))
                                            <img src="{{ asset('storage/' . $post->user->photo_profile) }}"
                                                class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                                <span class="text-indigo-600 dark:text-indigo-400 font-semibold">
                                                    {{ strtoupper(substr($post->user->nama_mahasiswa, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $post->user->nama_mahasiswa }}</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $post->tanggal->format('d M Y') }}</p>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex items-center gap-2">
                                        <a href="#" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                @php
                                    $content = $post->content;
                                    $title = '';
                                    $deskripsi = '';
                                    $items = [];

                                    if (is_array($content)) {
                                        foreach ($content as $item) {
                                            if (isset($item['type']) && $item['type'] === 'title') {
                                                $title = $item['content'] ?? '';
                                            } elseif (isset($item['type']) && $item['type'] === 'description') {
                                                $deskripsi = $item['content'] ?? '';
                                            } else {
                                                $items[] = $item;
                                            }
                                        }
                                    }
                                @endphp

                                <!-- Title -->
                                @if($title)
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $title }}</h2>
                                @endif

                                    <!-- Deskripsi singkat (opsional) -->
                                @if ($deskripsi)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">
                                        {{ Str::limit($deskripsi, 120) }}
                                    </p>
                                @endif

                                <!-- Items -->
                                @if(!empty($items))
                                    <div class="space-y-4">
                                        @foreach($items as $item)
                                            @if(isset($item['type']))
                                                @if($item['type'] === 'text' && isset($item['content']))
                                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                                        <p class="text-gray-700 dark:text-gray-300">{{ $item['content'] }}</p>
                                                    </div>
                                                @elseif($item['type'] === 'image' && isset($item['content']))
                                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                                        <img src="{{ asset('storage/' . $item['content']) }}"
                                                            class="max-w-full h-auto rounded-lg" alt="Postingan image">
                                                    </div>
                                                @elseif($item['type'] === 'link' && isset($item['content']))
                                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                                        <a href="{{ $item['content'] }}" target="_blank"
                                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 underline">
                                                            {{ $item['content'] }}
                                                        </a>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Footer -->
                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        @auth
                                            <button class="like-btn flex items-center gap-1 {{ $post->likes->where('id_user', auth()->id())->count() > 0 ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }} hover:text-red-500 dark:hover:text-red-300 transition cursor-pointer"
                                                data-postingan-id="{{ $post->id_postingan }}">
                                                <svg class="w-5 h-5 {{ $post->likes->where('id_user', auth()->id())->count() > 0 ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                <span class="like-count text-sm">{{ $post->likes->count() }}</span>
                                            </button>
                                        @else
                                            <button class="like-btn-view flex items-center gap-1 text-gray-500 dark:text-gray-400 cursor-default">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                <span class="like-count text-sm">{{ $post->likes->count() }}</span>
                                            </button>
                                        @endauth
                                        <a href="{{ route('postingan.show', $post->id_postingan) }}" class="flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            <span class="text-sm">{{ $post->komentar->count() }}</span>
                                        </a>
                                        <a href="{{ route('postingan.show', $post->id_postingan) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm font-medium">
                                            Lihat Detail
                                        </a>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('postingan.edit', $post->id_postingan) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm font-medium">
                                            Edit
                                        </a>
                                        <form action="{{ route('postingan.destroy', $post->id_postingan) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?')"
                                                class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm font-medium">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $postingan->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript for Like Functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle like button clicks for authenticated users
            document.querySelectorAll('.like-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const postinganId = this.dataset.postinganId;
                    const likeBtn = this;
                    const likeCountSpan = this.querySelector('.like-count');
                    const heartIcon = this.querySelector('svg');

                    // Disable button temporarily
                    likeBtn.disabled = true;

                    // Send AJAX request
                    fetch(`/postingan/${postinganId}/toggle-like`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update like count
                            likeCountSpan.textContent = data.like_count;

                            // Update button appearance
                            if (data.liked) {
                                likeBtn.classList.remove('text-gray-500', 'dark:text-gray-400');
                                likeBtn.classList.add('text-red-500');
                                heartIcon.classList.add('fill-current');
                            } else {
                                likeBtn.classList.remove('text-red-500');
                                likeBtn.classList.add('text-gray-500', 'dark:text-gray-400');
                                heartIcon.classList.remove('fill-current');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    })
                    .finally(() => {
                        // Re-enable button
                        likeBtn.disabled = false;
                    });
                });
            });

            // Handle like button view clicks for non-authenticated users
            document.querySelectorAll('.like-btn-view').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.location.href = '{{ route("login") }}';\n                });
            });
        });
    </script>
@endsection