{{-- Komponen reusable untuk video preview dengan iframe YouTube --}}
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
    <div class="rounded-lg sm:rounded-xl overflow-hidden border border-gray-200 dark:border-gray-800 shadow-sm">
        <div class="aspect-video relative">
            <iframe width="100%" height="100%" style="position: absolute; top: 0; left: 0; border: none; border-radius: inherit;"
                src="https://www.youtube.com/embed/{{ $youtube_id }}"
                title="YouTube video player" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                referrerpolicy="strict-origin-when-cross-origin" 
                allowfullscreen>
            </iframe>
        </div>
    </div>
@endif