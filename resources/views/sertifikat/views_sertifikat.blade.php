@extends('Layout.Layout')
@section('title', 'Sertifikat Saya')
@section('content')
    <div class="p-6 lg:p-8 rounded-2xl" id="sertifikat-list-container">
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
                <span data-translate="filter_all" data-translate-page="sertifikat">Semua</span>
            </button>
            <button type="button" onclick="filterStatus('Sedang Di Ajukan')"
                class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300"
                data-filter="Sedang Di Ajukan">
                <span data-translate="filter_pending" data-translate-page="sertifikat">Sedang Diajukan</span>
            </button>
            <button type="button" onclick="filterStatus('Di Terima')"
                class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-300"
                data-filter="Di Terima">
                <span data-translate="filter_accepted" data-translate-page="sertifikat">Diterima</span>
            </button>
            <button type="button" onclick="filterStatus('Di Tolak')"
                class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300"
                data-filter="Di Tolak">
                <span data-translate="filter_rejected" data-translate-page="sertifikat">Ditolak</span>
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
                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                      class="w-8 h-8 text-white" fill="currentColor"  viewBox="0 0 459 459" style="enable-background:new 0 0 459 459;" xml:space="preserve">
                                    <g>
                                        <g>
                                            <rect x="286.875" y="239.062" width="114.75" height="19.125"/>
                                            <rect x="229.5" y="181.688" width="172.125" height="19.125"/>
                                            <path d="M420.75,28.688H38.25C17.212,28.688,0,45.9,0,66.938v248.625c0,21.037,17.212,38.25,38.25,38.25H76.5v76.5l47.812-47.812
                                                l47.812,47.812v-76.5H420.75c21.037,0,38.25-17.213,38.25-38.25V66.938C459,45.9,441.787,28.688,420.75,28.688z M153,384.412
                                                l-28.688-28.688l-28.688,28.688v-74.587c9.562,3.825,19.125,5.737,28.688,5.737s19.125-1.912,28.688-5.737V384.412z
                                                M124.312,296.438c-26.775,0-47.812-21.037-47.812-47.812s21.038-47.812,47.812-47.812s47.812,21.037,47.812,47.812
                                                S151.087,296.438,124.312,296.438z M439.875,315.562c0,11.475-7.65,19.125-19.125,19.125H172.125v-40.162
                                                c11.475-11.476,19.125-28.688,19.125-45.9c0-36.337-30.6-66.938-66.938-66.938s-66.938,30.6-66.938,66.938
                                                c0,19.125,7.65,34.425,19.125,45.9v40.162H38.25c-11.475,0-19.125-9.562-19.125-19.125V66.938c0-11.475,7.65-19.125,19.125-19.125
                                                h382.5c11.475,0,19.125,9.562,19.125,19.125V315.562z"/>
                                            <rect x="57.375" y="124.312" width="344.25" height="19.125"/>
                                        </g>
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
                                <span class="text-sm font-semibold" data-translate="validity_status_label"
                                    data-translate-page="sertifikat">Status Berlaku:</span>
                                <span class="ml-2 text-sm font-medium {{ $validityStatusClass }}">
                                    @if($validityStatus === 'Masih Berlaku')
                                        <span data-translate="validity_valid" data-translate-page="sertifikat">Masih Berlaku</span>
                                    @elseif($validityStatus === 'Kadarluwasa')
                                        <span data-translate="validity_expired" data-translate-page="sertifikat">Kadarluwasa</span>
                                    @else
                                        <span data-translate="validity_permanent" data-translate-page="sertifikat">Permanen</span>
                                    @endif
                                </span>
                            </div>

                            @if($expiredAt)
                                <div class="flex items-center text-gray-600 dark:text-gray-300 mb-4">
                                    <span class="text-sm font-semibold" data-translate="expired_date_label"
                                        data-translate-page="sertifikat">Tanggal Kadaluarsa:</span>
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
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 font-semibold"
                                            data-translate="additional_description_label" data-translate-page="sertifikat">
                                            Deskripsi Tambahan
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
                                    <p class="text-xs text-red-800 dark:text-red-300 font-semibold mb-1"
                                        data-translate="rejection_reason_label" data-translate-page="sertifikat">
                                        Alasan Penolakan:
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



@endsection