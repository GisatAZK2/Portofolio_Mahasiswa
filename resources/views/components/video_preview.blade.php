{{-- Komponen reusable untuk video preview dengan thumbnail YouTube --}}
@php
    // Extract YouTube ID jika link_video adalah YouTube
    $youtube_id = '';
    if ($link_video) {
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $link_video, $matches)) {
            $youtube_id = $matches[1];
        }
    }
@endphp

@if($youtube_id)
    <a href="https://www.youtube.com/watch?v={{ $youtube_id }}" target="_blank" rel="noopener noreferrer"
        class="block {{ $class ?? 'rounded-lg sm:rounded-xl overflow-hidden border border-gray-200 dark:border-gray-800 shadow-sm' }}">
        <div class="aspect-video relative group">
            <!-- Thumbnail -->
            <img src="https://img.youtube.com/vi/{{ $youtube_id }}/mqdefault.jpg" alt="{{ $alt ?? 'Video' }}"
                class="w-full h-full object-cover">
            <!-- Play Button Overlay -->
            <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/40 transition">
                <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z" />
                    </svg>
                </div>
            </div>
        </div>
    </a>
@endif