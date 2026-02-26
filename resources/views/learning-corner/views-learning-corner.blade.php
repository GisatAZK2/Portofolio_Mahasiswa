@extends('Layout.Layout')
@section('title', 'Learning Corner Saya')
@section('content')
    <div class="p-6 lg:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Learning Corner Saya</h1>
                <p class="text-gray-600">
                    Kelola semua postingan Learning Corner kamu di sini.
                </p>
            </div>
            <a href="{{ route('learning-corner.create') }}"
                class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Entri Baru
            </a>
        </div>

        {{-- Data --}}
        @if ($entries->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-4 text-gray-600">Belum ada entri di Learning Corner.</p>
            </div>
        @else
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

                            <!-- Action buttons -->
                            <div class="flex space-x-3 mt-6">
                                <a href="{{ route('learning-corner.edit', $entry->id_learning_corner) }}"
                                    class="flex-1 text-center py-2.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition font-medium border border-blue-200">
                                    Edit
                                </a>

                                <form class="delete-form flex-1"
                                    action="{{ route('learning-corner.destroy', $entry->id_learning_corner) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="delete-btn w-full py-2.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition font-medium border border-red-200">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', async function (e) {
                    e.preventDefault();

                    const confirmed = await showConfirmAlert({
                        title: 'Hapus Entri Learning Corner?',
                        text: 'Catatan ini akan dihapus permanen dan tidak bisa dikembalikan.',
                        icon: 'warning',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                    });

                    if (confirmed) {
                        showLoading('Menghapus catatan...');
                        this.closest('form').submit();
                    }
                });
            });

            @if (session('success'))
                showSuccessAlert('{{ session('success') }}');
            @endif
    });
    </script>
@endsection