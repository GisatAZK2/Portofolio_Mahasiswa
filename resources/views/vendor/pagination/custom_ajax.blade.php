{{-- Custom Pagination dengan AJAX --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center mt-6">
        <div class="flex items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span
                    class="px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                    ← Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="pagination-link px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition-colors"
                    data-group="{{ $groupName }}">
                    ← Sebelumnya
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-3 py-2 text-gray-500 dark:text-gray-400">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-2 rounded-lg bg-indigo-600 text-white font-bold">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="pagination-link px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
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
                    class="pagination-link px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition-colors"
                    data-group="{{ $groupName }}">
                    Selanjutnya →
                </a>
            @else
                <span
                    class="px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                    Selanjutnya →
                </span>
            @endif
        </div>
    </nav>
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