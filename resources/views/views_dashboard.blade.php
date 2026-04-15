@extends('Layout.Layout')
@section('title', 'Dashboard')
@section('content')
<div class="min-h-screen dark:bg-gray-800 rounded-2xl py-4 sm:py-6 px-3 sm:px-6 lg:px-8" data-dashboard-type="me">
    <div class="max-w-7xl mx-auto space-y-6 sm:space-y-10">

        <!-- Postingan Terbaru -->
        <div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Postingan Terbaru</h2>
            </div>

            <!-- Postingan Section -->
            <div class="mb-10">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-5 bg-indigo-600 rounded-full"></div>
                    <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Postingan Mahasiswa</h3>
                </div>

                @if($postinganTerbaru->isEmpty())
                    <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada postingan mahasiswa</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($postinganTerbaru as $post)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow cursor-pointer"
                                 onclick="window.location.href='{{ route('postingan.show', $post->id_postingan) }}'">
                                <!-- Header -->
                                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-3">
                                        @if($post->user->photo_profile && file_exists(public_path('storage/' . $post->user->photo_profile)))
                                            <img src="{{ asset('storage/' . $post->user->photo_profile) }}"
                                                class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                                <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-sm">
                                                    {{ strtoupper(substr($post->user->nama_mahasiswa, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 text-sm truncate">{{ $post->user->nama_mahasiswa }}</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $post->tanggal->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-4">
                                    @php
                                        $content = $post->content;
                                        $title = '';
                                        $deskripsi = '';

                                        if (is_array($content)) {
                                            foreach ($content as $item) {
                                                if (isset($item['type']) && $item['type'] === 'title') {
                                                    $title = $item['content'] ?? '';
                                                } elseif (isset($item['type']) && $item['type'] === 'description') {
                                                    $deskripsi = $item['content'] ?? '';
                                                }
                                            }
                                        }
                                    @endphp

                                    <!-- Title -->
                                    @if($title)
                                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm mb-2 line-clamp-2">{{ $title }}</h3>
                                    @endif

                                    <!-- Description Preview -->
                                    @if($deskripsi)
                                        <p class="text-xs text-gray-600 dark:text-gray-300 line-clamp-3 mb-3">{{ Str::limit($deskripsi, 100) }}</p>
                                    @endif

                                    <!-- Content Preview -->
                                    @if(!empty($post->content))
                                        @php
                                            $hasImage = false;
                                            $hasLink = false;
                                            foreach($post->content as $item) {
                                                if (isset($item['type'])) {
                                                    if ($item['type'] === 'image') $hasImage = true;
                                                    if ($item['type'] === 'link') $hasLink = true;
                                                }
                                            }
                                        @endphp

                                        @if($hasImage)
                                            <div class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400 mb-2">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>Mengandung gambar</span>
                                            </div>
                                        @endif

                                        @if($hasLink)
                                            <div class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400 mb-2">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                </svg>
                                                <span>Mengandung link</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <!-- Footer -->
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            @auth
                                                <button class="like-btn flex items-center gap-1 {{ $post->likes->where('id_user', auth()->id())->count() > 0 ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }} hover:text-red-500 dark:hover:text-red-300 transition cursor-pointer"
                                                    data-postingan-id="{{ $post->id_postingan }}">
                                                    <svg class="w-4 h-4 {{ $post->likes->where('id_user', auth()->id())->count() > 0 ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                    </svg>
                                                    <span class="like-count text-xs">{{ $post->likes->count() }}</span>
                                                </button>
                                            @else
                                                <button class="like-btn-view flex items-center gap-1 text-gray-500 dark:text-gray-400 cursor-default">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                    </svg>
                                                    <span class="like-count text-xs">{{ $post->likes->count() }}</span>
                                                </button>
                                            @endauth
                                            <div class="flex items-center gap-1 text-gray-500 dark:text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                <span class="text-xs">{{ $post->komentar->count() }}</span>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Klik untuk detail</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Learning Corner -->
            <div class="mb-10">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-5 bg-purple-600 rounded-full"></div>
                    <h3 class="text-lg font-semibold text-purple-700 dark:text-purple-300">Learning Corner</h3>
                </div>

                @if($learningCorners->isEmpty())
                    <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada postingan Learning Corner</p>
                    </div>
                @else
                    <div data-pagination-group="learning_corner">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($learningCorners as $post)
                                @include('components.card_postingan', ['post' => $post])
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $learningCorners->render('vendor.pagination.custom_ajax', ['groupName' => 'learning_corner']) }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Project -->
            <div class="mb-10">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-5 bg-orange-600 rounded-full"></div>
                    <h3 class="text-lg font-semibold text-orange-600 dark:text-orange-300">Project</h3>
                </div>

                @if($projects->isEmpty())
                    <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada postingan Project</p>
                    </div>
                @else
                    <div data-pagination-group="project">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($projects as $post)
                                @include('components.card_postingan', ['post' => $post])
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $projects->render('vendor.pagination.custom_ajax', ['groupName' => 'project']) }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sertifikat -->
            <div class="mb-10">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-5 bg-green-600 rounded-full"></div>
                    <h3 class="text-lg font-semibold text-green-700 dark:text-green-300">Sertifikat</h3>
                </div>

                @if($projectUsers->isEmpty())
                    <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada sertifikat</p>
                    </div>
                @else
                    <div data-pagination-group="sertifikat">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($projectUsers as $post)
                                @include('components.card_postingan', ['post' => $post])
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $projectUsers->render('vendor.pagination.custom_ajax', ['groupName' => 'sertifikat']) }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Update timestamp -->
            <div class="text-center text-gray-500 dark:text-gray-400 text-sm mt-10 pt-6 border-t border-gray-200 dark:border-gray-700">
                Terakhir diperbarui {{ now()->format('d F Y H:i') }} WIB
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    let charts = {};

    function createSparkline(canvasId, borderColor) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        // Destroy existing chart
        if (charts[canvasId]) {
            charts[canvasId].destroy();
        }

        const ctx = canvas.getContext('2d');
        
        const generateRandomData = () => {
            return Array.from({ length: 7 }, () => Math.floor(Math.random() * 40) + 10);
        };

        const data = generateRandomData();

        charts[canvasId] = new Chart(ctx, {
            type: 'line',
            data: {
                labels: Array(data.length).fill(''),
                datasets: [{
                    data: data,
                    borderColor: borderColor,
                    backgroundColor: borderColor + '20',
                    tension: 0.4,
                    pointRadius: 0,
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                },
                scales: {
                    x: { display: false },
                    y: { display: false }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        createSparkline('learningChart', '#8b5cf6');
        createSparkline('projectChart', '#f97316');
        createSparkline('sertifikatChart', '#f59e0b');
    });
</script>

<!-- Page Info -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        if (typeof showPageInfo === 'function') {
            showPageInfo("popup.dashboard");
        }
    });
</script>

<!-- Like Functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle like button clicks for authenticated users
    document.addEventListener('click', function(e) {
        const likeBtn = e.target.closest('.like-btn');
        if (likeBtn) {
            e.preventDefault();
            e.stopPropagation(); // Prevent card click navigation

            const postinganId = likeBtn.getAttribute('data-postingan-id');

            // Disable button during request
            likeBtn.disabled = true;

            // Make AJAX request
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
                    const likeCountSpan = likeBtn.querySelector('.like-count');
                    likeCountSpan.textContent = data.like_count;

                    // Update button appearance
                    const svg = likeBtn.querySelector('svg');
                    if (data.liked) {
                        likeBtn.classList.remove('text-gray-500', 'dark:text-gray-400');
                        likeBtn.classList.add('text-red-500');
                        svg.classList.add('fill-current');
                    } else {
                        likeBtn.classList.remove('text-red-500');
                        likeBtn.classList.add('text-gray-500', 'dark:text-gray-400');
                        svg.classList.remove('fill-current');
                    }
                } else {
                    // Show error
                    alert(data.message || 'Terjadi kesalahan saat memproses like');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses like');
            })
            .finally(() => {
                likeBtn.disabled = false;
            });
        }
    });

    // Handle like button view clicks for non-authenticated users
    document.addEventListener('click', function(e) {
        const likeBtnView = e.target.closest('.like-btn-view');
        if (likeBtnView) {
            e.preventDefault();
            e.stopPropagation();
            window.location.href = '{{ route("login") }}';
        }
    });
});
</script>
@endsection