@extends('Layout.Layout')
@section('title', 'My Certificates')

@section('meta')
    <meta name="description" content="View your certificates, validity status, and published details in one place.">
@endsection

@section('content')
    <div class="p-6 lg:p-8" id="sertifikat-user-container">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white" data-translate="sertifikat_mahasiswa_title"
                    data-translate-page="sertifikat_user"></h1>
                <p class="text-gray-600 dark:text-gray-400">
                    <span data-translate="sertifikat_mahasiswa_desc" data-translate-page="sertifikat_user"></span>
                </p>
            </div>
        </div>

        {{-- Data Sertifikat --}}
        @if ($sertifikat->isEmpty())
            <div class="text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-4 text-gray-600 dark:text-gray-400" data-translate="sertifikat_no_data" data-translate-page="sertifikat_user"></p>
                <p class="text-gray-500 dark:text-gray-500 text-sm mt-2" data-translate="sertifikat_no_data_description"
                    data-translate-page="sertifikat_user"></p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($sertifikat as $entry)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700 flex flex-col h-full">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-blue-700 to-blue-500 p-4">
                            <div class="flex items-center justify-between">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-white text-xs font-medium bg-white/20 px-2 py-1 rounded-full"
                                    data-translate="sertifikat_title" data-translate-page="sertifikat_user"></span>
                            </div>
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
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                {{ $entry->nama_sertifikat }}
                            </h3>

                            <!-- Lembaga Penerbit -->
                            <div class="flex items-center text-gray-600 dark:text-gray-400 mb-3">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l14-7 3.5 1.5L21 21z"></path>
                                </svg>
                                <span class="text-sm">{{ $entry->lembaga_penerbit }}</span>
                            </div>

                            <!-- Tanggal Terbit -->
                            <div class="flex items-center text-gray-600 dark:text-gray-400 mb-4">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="text-sm">{{ \Carbon\Carbon::parse($entry->tanggal_terbit)->format('d F Y') }}</span>
                            </div>

                            <div class="flex items-center text-gray-600 dark:text-gray-400 mb-4">
                                <span class="text-sm font-semibold" data-translate="validity_status_label"
                                    data-translate-page="sertifikat_user">Status Berlaku:</span>
                                <span class="ml-2 text-sm font-medium {{ $validityStatusClass }}">
                                    @if($validityStatus === 'Masih Berlaku')
                                        <span data-translate="validity_valid" data-translate-page="sertifikat_user">Masih Berlaku</span>
                                    @elseif($validityStatus === 'Kadarluwasa')
                                        <span data-translate="validity_expired" data-translate-page="sertifikat_user">Kadarluwasa</span>
                                    @else
                                        <span data-translate="validity_permanent" data-translate-page="sertifikat_user">Permanen</span>
                                    @endif
                                </span>
                            </div>

                            @if($expiredAt)
                                <div class="flex items-center text-gray-600 dark:text-gray-400 mb-4">
                                    <span class="text-sm font-semibold" data-translate="expired_date_label"
                                        data-translate-page="sertifikat_user">Tanggal Kadaluarsa:</span>
                                    <span class="ml-2 text-sm">{{ $expiredAt->format('d F Y') }}</span>
                                </div>
                            @endif

                            <!-- Link Sertifikat  -->
                            @if($entry->link_sertifikat)
                                <div class="mb-4">
                                    <a href="{{ asset('storage/' . $entry->link_sertifikat) }}" target="_blank"
                                        class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 hover:underline">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        <span data-translate="sertifikat_link" data-translate-page="sertifikat_user"></span>
                                    </a>
                                </div>
                            @endif

                            <!-- Isi Content (dari JSON) -->
                            @if(!empty($entry->isi_content))
                                @php
                                    $content = is_array($entry->isi_content) ? $entry->isi_content : json_decode($entry->isi_content, true);
                                @endphp

                                @if(is_array($content) && count($content) > 0)
                                    <div class="mb-4 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 font-semibold"
                                            data-translate="additional_description_label"
                                            data-translate-page="sertifikat_user">Deskripsi Tambahan</p>
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

                            <!-- Tanggal dibuat/diupdate -->
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-auto pt-4 border-t border-gray-100 dark:border-gray-700">
                                <span data-translate="sertifikat_dibuat" data-translate-page="sertifikat_user"></span>:
                                {{ $entry->created_at ? $entry->created_at->format('d M Y') : '-' }}
                                @if($entry->created_at != $entry->updated_at)
                                    <br> <span data-translate="sertifikat_diupdate" data-translate-page="sertifikat_user"></span>:
                                    {{ $entry->updated_at->format('d M Y') }}
                                @endif
                            </p>

                        </div>
                    </div>
                @endforeach
            </div>

        @endif
    </div>



@endsection