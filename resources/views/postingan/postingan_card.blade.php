@extends('Layout.Layout')
@section('title', 'Postingan Saya')

@section('content')
    <div class="p-6 lg:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-50" data-translate="ttl" data-translate-page="post">Postingan Saya</h1>
                <p data-translate="desc" data-translate-page="post" class="text-gray-600 dark:text-gray-200 mt-1">
                    Kelola semua postingan yang telah Anda buat.
                </p>
            </div>
            <a href="{{ route('postingan.create') }}"
                class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span data-translate="add" data-translate-page="post">Buat Postingan</span>
            </a>
        </div>

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

        @if ($postingan->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900">
                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-4 text-gray-600 dark:text-gray-200">Belum ada postingan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($postingan as $post)
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

                    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-100 dark:border-gray-800 flex flex-col h-full">

                        <!-- Content Area -->
                        <div class="p-6 flex-1 flex flex-col cursor-pointer group" onclick="window.location='{{ route('postingan.show', ['id' => $post->id_postingan]) }}'">
                            <!-- Author Info -->
                            <div class="flex items-center gap-3 mb-4">
                                @if($post->user->photo_profile && file_exists(public_path('storage/' . $post->user->photo_profile)))
                                    <img src="{{ asset('storage/' . $post->user->photo_profile) }}"
                                        class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                        <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-sm">
                                            {{ strtoupper(substr($post->user->nama_mahasiswa, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">{{ $post->user->nama_mahasiswa }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $post->tanggal->format('d M Y') }}</p>
                                </div>
                            </div>

                            <!-- Title -->
                            @if($title)
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ $title }}
                                </h2>
                            @endif

                            <!-- Deskripsi -->
                            @if ($deskripsi)
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">
                                    {{ Str::limit($deskripsi, 100) }}
                                </p>
                            @endif

                            <!-- Preview Items -->
                            @if(!empty($items) && count($items) > 0)
                                @foreach($items as $item)
                                    @if(isset($item['type']) && $item['type'] === 'image' && isset($item['content']))
                                        <div class="mb-4 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                                            <img src="{{ asset('storage/' . $item['content']) }}"
                                                class="w-full h-48 object-cover" alt="Postingan image">
                                        </div>
                                        @break
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <!-- Stats & Action Footer -->
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 space-y-4">
                            <!-- Like & Comment Stats -->
                            <div class="flex items-center gap-4 text-sm">
                                <span class="inline-flex items-center gap-1 text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    {{ $post->likes->count() }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    {{ $post->komentar->count() }}
                                </span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <a href="{{ route('postingan.edit', ['id' => $post->id_postingan]) }}" 
                                    class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 px-4 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 active:bg-blue-200 transition font-medium dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 dark:active:bg-blue-900/70 shadow-sm hover:shadow-md"
                                    onclick="event.stopPropagation();">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-7-10l3 3m0 0l-3 3m3-3H9"/>
                                    </svg>
                                    <span>Edit</span>
                                </a>
                                <form action="{{ route('postingan.destroy', ['id' => $post->id_postingan]) }}" method="POST" class="delete-form flex-1" onclick="event.stopPropagation();">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                        class="delete-btn w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 active:bg-red-200 transition font-medium dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 dark:active:bg-red-900/70 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span data-translate="del" data-translate-page="post">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.semua_postingan");

            // Handle delete button clicks
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                });
            });
        });
    </script>
@endsection