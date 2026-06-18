@extends('Layout.Layout')
@section('title', 'Sertifikat Saya')
@section('content')
    <div class="p-6 lg:p-8 rounded-2xl">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 data-translate="sertifikat_title" data-translate-page="sertifikat"
                    class="text-3xl font-bold text-gray-900 dark:text-gray-50"></h1>
                <p class="text-gray-600 dark:text-gray-200">
                    <span data-translate="sertifikat_desc" data-translate-page="sertifikat"></span>
                </p>
            </div>
            <a href="{{ route('sertifikat.create') }}"
                class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span data-translate="sertifikat_create_button" data-translate-page="sertifikat"
                    class="text-sm font-medium"></span>
            </a>
        </div>

        {{-- Filter Status --}}
        <div class="mb-6 flex flex-wrap gap-2">
            <button type="button" onclick="filterStatus('all')"
                class="filter-btn active px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-indigo-600 text-white hover:bg-indigo-700"
                data-filter="all">
                Semua
            </button>
            <button type="button" onclick="filterStatus('Sedang Di Ajukan')"
                class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300"
                data-filter="Sedang Di Ajukan">
                Sedang Diajukan
            </button>
            <button type="button" onclick="filterStatus('Di Terima')"
                class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-300"
                data-filter="Di Terima">
                Diterima
            </button>
            <button type="button" onclick="filterStatus('Di Tolak')"
                class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300"
                data-filter="Di Tolak">
                Ditolak
            </button>
        </div>

        {{-- Data Sertifikat --}}
        @if ($sertifikat->isEmpty())
            <div class="text-center py-12 bg-gray-50 dark:bg-gray-900 dark:border-gray-900 rounded-xl border border-gray-200">
                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p data-translate="sertifikat_no_data" data-translate-page="sertifikat"
                    class="mt-4 text-gray-600 dark:text-gray-200"></p>
                <p data-translate="sertifikat_no_data_description" data-translate-page="sertifikat"
                    class="text-gray-500 dark:text-gray-50 text-sm mt-2"></p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($sertifikat as $entry)
                    <div class="sertifikat-card bg-white dark:border-gray-900 dark:bg-gray-900 dark:text-gray-200 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow border border-gray-100 flex flex-col h-full"
                        data-status="{{ $entry->status_pengajuan }}">
                        <!-- Header dengan ikon sertifikat dan status -->
                        <div class="bg-gradient-to-r from-blue-700 to-blue-500 p-4 relative">
                            <div class="flex items-center justify-between">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>

                                {{-- Status Badge --}}
                                @php
                                    $statusClass = match ($entry->status_pengajuan) {
                                        'Sedang Diajukan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
                                        'Diterima' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                                        'Ditolak' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                    };
                                @endphp
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $statusClass }}">
                                    {{ $entry->status_pengajuan }}
                                </span>
                            </div>

                            {{-- Active Status Indicator --}}
                            @if($entry->is_active)
                                <div class="absolute top-0 right-0 mt-2 mr-2">
                                    <span class="flex h-3 w-3">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="p-6 flex-1 flex flex-col">
                            @php
                                $expiredAt = $entry->expired_date ? \Carbon\Carbon::parse($entry->expired_date) : null;
                                $validityStatus = $expiredAt
                                    ? ($expiredAt->isFuture() || $expiredAt->isToday()
                                        ? 'Masih Berlaku'
                                        : 'Kadarluwasa')
                                    : 'Permanen';
                                $validityStatusClass = $expiredAt
                                    ? ($expiredAt->isFuture() || $expiredAt->isToday() ? 'text-green-600' : 'text-red-600')
                                    : 'text-indigo-600';
                            @endphp

                            <!-- Nama Sertifikat -->
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">
                                {{ $entry->nama_sertifikat }}
                            </h3>

                            <!-- Lembaga Penerbit -->
                            <div class="flex items-center text-gray-600 dark:text-gray-200 mb-3">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l14-7 3.5 1.5L21 21z"></path>
                                </svg>
                                <span class="text-sm">{{ $entry->lembaga_penerbit }}</span>
                            </div>

                            <!-- Tanggal Terbit -->
                            <div class="flex items-center text-gray-600 dark:text-gray-300 mb-4">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="text-sm">{{ \Carbon\Carbon::parse($entry->tanggal_terbit)->format('d F Y') }}</span>
                            </div>

                            <div class="flex items-center text-gray-600 dark:text-gray-300 mb-4">
                                <span class="text-sm font-semibold">{{ 'Status Berlaku' }}:</span>
                                <span class="ml-2 text-sm font-medium {{ $validityStatusClass }}">{{ $validityStatus }}</span>
                            </div>

                            @if($expiredAt)
                                <div class="flex items-center text-gray-600 dark:text-gray-300 mb-4">
                                    <span class="text-sm font-semibold">{{ 'Tanggal Kadaluarsa' }}:</span>
                                    <span class="ml-2 text-sm">{{ $expiredAt->format('d F Y') }}</span>
                                </div>
                            @endif

                            <!-- Link Sertifikat -->
                            @if($entry->link_sertifikat)
                                <div class="mb-4">
                                    <a href="{{ asset('storage/' . $entry->link_sertifikat) }}" target="_blank"
                                        class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 hover:underline">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        <span data-translate="sertifikat_link" data-translate-page="sertifikat"></span>
                                    </a>
                                </div>
                            @endif

                            <!-- Isi Content (dari JSON) -->
                            @if(!empty($entry->isi_content))
                                @php
                                    $content = is_array($entry->isi_content) ? $entry->isi_content : json_decode($entry->isi_content, true);
                                @endphp

                                @if(is_array($content) && count($content) > 0)
                                    <div class="mb-4 bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 font-semibold">
                                            <span data-translate="sertifikat_deskripsi_tambahan" data-translate-page="sertifikat"></span>
                                        </p>
                                        @foreach($content as $item)
                                            @if(is_array($item))
                                                @if(isset($item['type']) && $item['type'] === 'text' && isset($item['content']))
                                                    <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-3">{{ $item['content'] }}</p>
                                                @elseif(isset($item['text']))
                                                    <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-3">{{ $item['text'] }}</p>
                                                @elseif(is_string($item))
                                                    <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-3">{{ $item }}</p>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            @endif

                            {{-- Keterangan (untuk status Ditolak) --}}
                            @if($entry->status_pengajuan === 'Ditolak' && $entry->keterangan)
                                <div class="mb-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-3 rounded">
                                    <p class="text-xs text-red-800 dark:text-red-300 font-semibold mb-1">
                                        <span data-translate="sertifikat_alasan_penolakan" data-translate-page="sertifikat">Alasan
                                            Penolakan:</span>
                                    </p>
                                    <p class="text-sm text-red-700 dark:text-red-200">{{ $entry->keterangan }}</p>
                                </div>
                            @endif

                            <!-- Tanggal dibuat/diupdate -->
                            <p class="text-xs text-gray-400 mt-auto pt-4 border-t border-gray-100 dark:border-gray-800">
                                <span data-translate="sertifikat_dibuat" data-translate-page="sertifikat"></span>
                                {{ $entry->created_at ? $entry->created_at->format('d M Y') : '-' }}
                                @if($entry->created_at != $entry->updated_at)
                                    <br><span data-translate="sertifikat_diupdate" data-translate-page="sertifikat"></span>:
                                    {{ $entry->updated_at->format('d M Y') }}
                                @endif
                            </p>

                            <!-- Action buttons -->
                            <div class="flex gap-3 mt-4">
                                <a href="{{ route('sertifikat.edit', ['id' => $entry->id]) }}"
                                    class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition font-medium shadow-sm hover:shadow-md dark:bg-blue-600 dark:hover:bg-blue-700 dark:active:bg-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-7-10l3 3m0 0l-3 3m3-3H9" />
                                    </svg>
                                    <span data-translate="edit_button" data-translate-page="sertifikat"></span>
                                </a>

                                <form class="delete-form flex-1" action="{{ route('sertifikat.destroy', ['id' => $entry->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="delete-btn w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 bg-red-600 text-white rounded-lg hover:bg-red-700 active:bg-red-800 transition font-medium shadow-sm hover:shadow-md dark:bg-red-600 dark:hover:bg-red-700 dark:active:bg-red-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span data-translate="delete_button" data-translate-page="sertifikat"></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination jika ada --}}
            @if(method_exists($sertifikat, 'links'))
                <div class="mt-6">
                    {{ $sertifikat->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Filter Script -->
    <script>

        function filterStatus(status) {
            // Update active button style
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-indigo-600', 'text-white', 'hover:bg-indigo-700');
                if (btn.dataset.filter === status) {
                    btn.classList.add('bg-indigo-600', 'text-white', 'hover:bg-indigo-700');
                } else {
                    // Reset ke warna default berdasarkan status
                    const filterValue = btn.dataset.filter;
                    btn.classList.remove('bg-indigo-600', 'text-white', 'hover:bg-indigo-700');

                    // Tambahkan class warna sesuai status
                    if (filterValue === 'Sedang Di Ajukan') {
                        btn.classList.add('bg-yellow-100', 'text-yellow-800', 'hover:bg-yellow-200', 'dark:bg-yellow-900/30', 'dark:text-yellow-300');
                    } else if (filterValue === 'Di Terima') {
                        btn.classList.add('bg-green-100', 'text-green-800', 'hover:bg-green-200', 'dark:bg-green-900/30', 'dark:text-green-300');
                    } else if (filterValue === 'Di Tolak') {
                        btn.classList.add('bg-red-100', 'text-red-800', 'hover:bg-red-200', 'dark:bg-red-900/30', 'dark:text-red-300');
                    } else {
                        btn.classList.add('bg-gray-100', 'text-gray-800', 'hover:bg-gray-200', 'dark:bg-gray-700', 'dark:text-gray-300');
                    }
                }
            });

            // Filter cards
            const cards = document.querySelectorAll('.sertifikat-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const cardStatus = card.dataset.status;
                if (status === 'all' || cardStatus === status) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Tampilkan pesan jika tidak ada data
            const noDataMessage = document.querySelector('.no-data-message');
            if (visibleCount === 0) {
                if (!noDataMessage) {
                    const container = document.querySelector('.grid');
                    const message = document.createElement('div');
                    message.className = 'no-data-message col-span-full text-center py-12 bg-gray-50 dark:bg-gray-900 dark:border-gray-900 rounded-xl border border-gray-200';
                    message.innerHTML = `
                            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-4 text-gray-600 dark:text-gray-200">Tidak ada sertifikat dengan status ${status}</p>
                        `;
                    container.parentNode.insertBefore(message, container.nextSibling);
                }
            } else {
                const existingMessage = document.querySelector('.no-data-message');
                if (existingMessage) {
                    existingMessage.remove();
                }
            }

            // Save filter to localStorage
            localStorage.setItem('sertifikatFilter', status);
        }

        // Delete confirmation
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data sertifikat akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showPageInfo("popup.semua_sertifikat");
        });
    </script>

@endsection