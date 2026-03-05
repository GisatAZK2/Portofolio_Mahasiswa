@extends('Layout.Layout')

@section('content')
    <div class="p-6 lg:p-8 max-w-7xl mx-auto">

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Header -->
        <div class="mb-10 text-center md:text-left">
            <h1 class="text-3xl font-bold text-gray-800">Learning Corner Mahasiswa</h1>
            <p class="mt-2 text-gray-600">Beberapa Pameran Learning Corner Mahasiswa </p>
        </div>

        <section class=" shadow-sm p-6 md:p-8 ">

            @if($entries->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($entries as $entry)
                    <div
                        class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow border border-gray-100 flex flex-col h-full">
                        <div class="p-6 flex-1 flex flex-col">
                            <!-- Render konten dinamis -->
                            @if (!empty($entry->content) && is_array($entry->content))
                                @foreach ($entry->content as $item)
                                    @if ($item['type'] === 'title')
                                        <h3 class="text-xl font-semibold text-gray-900 mb-3 line-clamp-2">
                                            {{ $item['content'] ?? '(Tanpa Judul)' }}
                                        </h3>
                                    @elseif ($item['type'] === 'text')
                                        <p class="text-gray-700 mb-4 line-clamp-4">
                                            {{ $item['content'] }}
                                        </p>
                                    @elseif ($item['type'] === 'image')
                                        @php
                                            $imagePath = str_replace(['\\', '/'], '/', $item['content'] ?? '');
                                        @endphp
                                        <div class="mb-5">
                                            <img src="{{ asset('storage/' . ltrim($imagePath, '/')) }}"
                                                alt="{{ $item['alt'] ?? 'Gambar konten Learning Corner' }}"
                                                class="w-full h-48 object-cover rounded-lg border border-gray-200 shadow-sm" loading="lazy"
                                                onerror="this.src='https://via.placeholder.com/400x200?text=Gambar+Tidak+Ditemukan';this.onerror=null;">
                                        </div>
                                    @elseif ($item['type'] === 'link')
                                        <a href="{{ $item['content'] }}" target="_blank" rel="noopener noreferrer"
                                            class="text-indigo-600 hover:text-indigo-800 hover:underline mb-4 block line-clamp-1 break-all">
                                            {{ Str::limit($item['content'], 70) }}
                                        </a>
                                    @endif
                                @endforeach
                            @else
                                <p class="text-gray-500 italic text-center py-4">Konten tidak tersedia atau format salah</p>
                            @endif

                            <!-- Tanggal -->
                            <p class="text-sm text-gray-500 mt-auto pt-5 border-t border-gray-100">
                                Diposting pada:
                                {{ $entry->created_at?->format('d M Y H:i') ?? ($entry->tanggal?->format('d M Y') ?? 'Tanggal tidak tersedia') }}
                            </p>
      
                        </div>
                    </div>
                @endforeach
            </div>

            @else
                <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-4 text-gray-600">Belum ada proyek.</p>
                </div>
            @endif

        </section>

    </div>
@endsection