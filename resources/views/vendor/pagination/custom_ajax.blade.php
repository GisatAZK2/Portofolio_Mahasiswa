{{-- Custom Pagination dengan AJAX - Design Elegan Minimalis --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center mt-8 mb-4">
        <div class="flex items-center gap-2 sm:gap-3 flex-wrap justify-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span
                    class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed text-sm font-medium transition-all">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="pagination-link px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-indigo-400 dark:hover:border-indigo-500 text-sm font-medium transition-all duration-200 flex items-center gap-1"
                    data-group="{{ $groupName }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Sebelumnya
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-2 text-gray-400 dark:text-gray-500 text-sm">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-bold shadow-sm transition-all">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="pagination-link px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-indigo-400 dark:hover:border-indigo-500 text-sm font-medium transition-all duration-200"
                                data-group="{{ $groupName }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="pagination-link px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-indigo-400 dark:hover:border-indigo-500 text-sm font-medium transition-all duration-200 flex items-center gap-1"
                    data-group="{{ $groupName }}">
                    Selanjutnya
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span
                    class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed text-sm font-medium transition-all flex items-center gap-1">
                    Selanjutnya
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>

    {{-- Info Pagination --}}
    <div class="text-center text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-4">
        Halaman <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $paginator->currentPage() }}</span> dari
        <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $paginator->lastPage() }}</span>
    </div>
@endif

<script>
    document.querySelectorAll('.pagination-link').forEach(link => {
        link.addEventListener('click', function (e) {
            const groupName = this.getAttribute('data-group');
            // Simpan nama grup ke sessionStorage untuk scroll otomatis
            sessionStorage.setItem('scrollToGroup', groupName);
        });
    });

    // Scroll otomatis saat page load jika ada data scrollToGroup
    document.addEventListener('DOMContentLoaded', function () {
        const groupName = sessionStorage.getItem('scrollToGroup');
        if (groupName) {
            const element = document.querySelector(`[data-pagination-group="${groupName}"]`);
            if (element) {
                // Scroll dengan smooth animation dan delay
                setTimeout(function () {
                    element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
            // Hapus data setelah digunakan
            sessionStorage.removeItem('scrollToGroup');
        }
    });
</script>