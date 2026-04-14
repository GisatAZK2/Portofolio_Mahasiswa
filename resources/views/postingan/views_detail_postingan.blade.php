@extends('Layout.Layout')
@section('title', 'Detail Postingan')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="p-6 lg:p-8 max-w-4xl mx-auto">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('postingan.index') }}"
                    class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Postingan Saya
                </a>
            </div>

            <!-- Postingan Detail -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 mb-8">
                <!-- Header -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            @if($postingan->user->photo_profile && file_exists(public_path('storage/' . $postingan->user->photo_profile)))
                                <img src="{{ asset('storage/' . $postingan->user->photo_profile) }}"
                                    class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-lg">
                                        {{ strtoupper(substr($postingan->user->nama_mahasiswa, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-lg">{{ $postingan->user->nama_mahasiswa }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $postingan->tanggal->format('d M Y') }}</p>
                            </div>
                        </div>

                        <!-- Action Buttons (only for owner) -->
                        @if(auth()->check() && auth()->id() == $postingan->id_user)
                            <div class="flex items-center gap-2">
                                <a href="{{ route('postingan.edit', $postingan->id_postingan) }}"
                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm font-medium">
                                    Edit
                                </a>
                                <form action="{{ route('postingan.destroy', $postingan->id_postingan) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?')"
                                        class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm font-medium">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    @php
                        $content = $postingan->content;
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
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $title }}</h1>
                    @endif

                    <!-- Description -->
                    @if($deskripsi)
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-6">
                            <p class="text-gray-700 dark:text-gray-300 text-lg leading-relaxed">{{ $deskripsi }}</p>
                        </div>
                    @endif

                    <!-- Items -->
                    @if(!empty($items))
                        <div class="space-y-6">
                            @foreach($items as $item)
                                @if(isset($item['type']))
                                    @if($item['type'] === 'image' && isset($item['content']))
                                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                            <img src="{{ asset('storage/' . $item['content']) }}"
                                                class="max-w-full h-auto rounded-lg shadow-md" alt="Postingan image">
                                        </div>
                                    @elseif($item['type'] === 'link' && isset($item['content']))
                                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-4 rounded-lg">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                </svg>
                                                <a href="{{ $item['content'] }}" target="_blank"
                                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 underline font-medium">
                                                    {{ $item['content'] }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Like Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 mb-8">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            @auth
                                <button class="like-btn flex items-center gap-2 {{ $postingan->likes->where('id_user', auth()->id())->count() > 0 ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }} hover:text-red-500 dark:hover:text-red-300 transition text-lg cursor-pointer"
                                    data-postingan-id="{{ $postingan->id_postingan }}">
                                    <svg class="w-6 h-6 {{ $postingan->likes->where('id_user', auth()->id())->count() > 0 ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <span class="like-count font-medium">{{ $postingan->likes->count() }}</span>
                                    <span class="text-sm">{{ $postingan->likes->count() === 1 ? 'Like' : 'Likes' }}</span>
                                </button>
                            @else
                                <button class="like-btn-view flex items-center gap-2 text-gray-500 dark:text-gray-400 text-lg cursor-default">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <span class="like-count font-medium">{{ $postingan->likes->count() }}</span>
                                    <span class="text-sm">{{ $postingan->likes->count() === 1 ? 'Like' : 'Likes' }}</span>
                                </button>
                            @endauth
                        </div>

                        @auth
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $postingan->likes->where('id_user', auth()->id())->count() > 0 ? 'Anda menyukai postingan ini' : 'Klik untuk menyukai postingan ini' }}
                            </p>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                                    Masuk
                                </a>
                                untuk menyukai postingan ini
                            </p>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        Komentar ({{ $postingan->komentar->count() }})
                    </h2>
                </div>

                <div class="p-6">
                    <!-- Add Comment Form (only for authenticated users) -->
                    @auth
                        <form action="{{ route('komentar.store') }}" method="POST" class="mb-8">
                            @csrf
                            <input type="hidden" name="id_postingan" value="{{ $postingan->id_postingan }}">

                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    @if(auth()->user()->photo_profile && file_exists(public_path('storage/' . auth()->user()->photo_profile)))
                                        <img src="{{ asset('storage/' . auth()->user()->photo_profile) }}"
                                            class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                            <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-sm">
                                                {{ strtoupper(substr(auth()->user()->nama_mahasiswa, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <textarea name="komentar" rows="3"
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:border-indigo-500 focus:ring-indigo-500 outline-none transition @error('komentar') border-red-500 @enderror"
                                            placeholder="Tulis komentar Anda...">{{ old('komentar') }}</textarea>
                                        @error('komentar')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                        Kirim Komentar
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-8 text-center">
                            <p class="text-gray-600 dark:text-gray-400">
                                <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                                    Masuk
                                </a>
                                untuk menambahkan komentar.
                            </p>
                        </div>
                    @endauth

                    <!-- Comments List -->
                    @if($postingan->komentar->count() > 0)
                        <div class="space-y-6">
                            @foreach($postingan->komentar->sortByDesc('tanggal') as $komentar)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-b-0 last:pb-0">
                                    <div class="flex items-start gap-3">
                                        @if($komentar->user->photo_profile && file_exists(public_path('storage/' . $komentar->user->photo_profile)))
                                            <img src="{{ asset('storage/' . $komentar->user->photo_profile) }}"
                                                class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                <span class="text-gray-600 dark:text-gray-400 font-semibold text-sm">
                                                    {{ strtoupper(substr($komentar->user->nama_mahasiswa, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif

                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $komentar->user->nama_mahasiswa }}</h4>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $komentar->tanggal->format('d M Y') }}</span>

                                                <!-- Edit/Delete buttons (only for comment owner) -->
                                                @auth
                                                    @if(auth()->id() == $komentar->id_user)
                                                        <div class="ml-auto flex items-center gap-2">
                                                            <button onclick="editComment({{ $komentar->id_komentar }}, '{{ $komentar->komentar }}')"
                                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm">
                                                                Edit
                                                            </button>
                                                            <form action="{{ route('komentar.destroy', $komentar->id_komentar) }}" method="POST" class="inline">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" onclick="return confirm('Hapus komentar ini?')"
                                                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm">
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                @endauth
                                            </div>

                                            <div id="comment-content-{{ $komentar->id_komentar }}">
                                                <p class="text-gray-700 dark:text-gray-300">{{ $komentar->komentar }}</p>
                                            </div>

                                            <!-- Edit Form (hidden by default) -->
                                            <form id="edit-form-{{ $komentar->id_komentar }}" action="{{ route('komentar.update', $komentar->id_komentar) }}" method="POST" class="hidden mt-3">
                                                @csrf @method('PUT')
                                                <textarea name="komentar" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:border-indigo-500 outline-none">{{ $komentar->komentar }}</textarea>
                                                <div class="flex justify-end gap-2 mt-2">
                                                    <button type="button" onclick="cancelEdit({{ $komentar->id_komentar }})"
                                                        class="px-3 py-1 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300">
                                                        Batal
                                                    </button>
                                                    <button type="submit"
                                                        class="px-3 py-1 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                                        Simpan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="mt-4 text-gray-600 dark:text-gray-400">Belum ada komentar.</p>
                            <p class="text-gray-500 dark:text-gray-500 text-sm mt-2">Jadilah yang pertama berkomentar!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Edit Comment -->
    <script>
        function editComment(commentId, currentContent) {
            document.getElementById('comment-content-' + commentId).classList.add('hidden');
            document.getElementById('edit-form-' + commentId).classList.remove('hidden');
        }

        function cancelEdit(commentId) {
            document.getElementById('comment-content-' + commentId).classList.remove('hidden');
            document.getElementById('edit-form-' + commentId).classList.add('hidden');
        }
    </script>

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
                    window.location.href = '{{ route("login") }}';
                });
            });
        });
    </script>
@endsection