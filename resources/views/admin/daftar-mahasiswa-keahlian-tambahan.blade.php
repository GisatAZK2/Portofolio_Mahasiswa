@extends('Layout.Layout')

@section('title', 'Kelola Pengajuan Keahlian Tambahan - Admin')

@section('content')
    <style>
        @media (max-width: 1919px),
        (max-height: 1079px) {
            .responsive-compact-table {
                font-size: 0.75rem !important;
            }

            .responsive-compact-table th,
            .responsive-compact-table td {
                padding: 0.5rem 0.75rem !important;
            }

            .responsive-compact-table .w-10.h-10 {
                width: 2rem !important;
                height: 2rem !important;
            }

            .responsive-compact-table svg.h-5.w-5 {
                width: 1rem !important;
                height: 1rem !important;
            }

            .responsive-compact-table .text-sm {
                font-size: 0.7rem !important;
            }
        }
    </style>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold dark:text-white">Kelola Pengajuan Keahlian Tambahan</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Kelola pengajuan keahlian tambahan dari mahasiswa. Anda dapat menerima atau menolak pengajuan sesuai kebutuhan.
                </p>
            </div>
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                <a href="{{ route('admin.users.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center text-sm sm:text-base flex-1 sm:flex-initial justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        {{-- Search Section --}}
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
            <form action="{{ route('admin.users.keahlian-tambahan.index') }}" method="GET" id="searchForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Search Input --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pencarian
                        </label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari berdasarkan nama, username, atau email..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-gray-100">
                            <div class="absolute left-3 top-2.5">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            @if(request('search'))
                                <a href="{{ route('admin.users.keahlian-tambahan.index') }}"
                                    class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-4 space-x-3">
                    <a href="{{ route('admin.users.keahlian-tambahan.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Reset
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Table - Desktop View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full w-full table-auto bg-white dark:bg-gray-800 text-sm responsive-compact-table">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Foto</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama / Username</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Keahlian Tambahan Diajukan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prodi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Angkatan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($applications as $application)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-3 py-3">
                                @if($application->mahasiswa->photo_profile && Storage::disk('public')->exists($application->mahasiswa->photo_profile))
                                    <img src="{{ asset('storage/' . ltrim($application->mahasiswa->photo_profile, '/')) }}"
                                        alt="{{ $application->mahasiswa->nama_mahasiswa ?? $application->mahasiswa->username }}"
                                        class="w-10 h-10 rounded-lg object-cover shadow-md ring-1 ring-gray-200 dark:ring-gray-700">
                                @else
                                    <div
                                        class="w-10 h-10 rounded-lg bg-gray-200 dark:bg-gray-600 flex items-center justify-center shadow-md ring-1 ring-gray-200 dark:ring-gray-700">
                                        <span class="text-sm font-bold text-gray-600 dark:text-gray-300">
                                            {{ strtoupper(substr($application->mahasiswa->nama_mahasiswa ?? $application->mahasiswa->username ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-3 py-3">
                                <div class="font-medium dark:text-white">{{ $application->mahasiswa->nama_mahasiswa ?? 'Pengguna' }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ '@' . ($application->mahasiswa->username ?? 'username') }}</div>
                            </td>
                            <td class="px-3 py-3 dark:text-white text-sm max-w-[220px] overflow-hidden whitespace-nowrap text-ellipsis truncate"
                                title="{{ $application->mahasiswa->email ?? '-' }}">{{ $application->mahasiswa->email ?? '-' }}</td>
                            <td class="px-3 py-3 dark:text-white text-sm">
                                @if($application->keahlian)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 rounded-full text-xs font-medium">
                                        {{ $application->keahlian->nama_keahlian }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 dark:text-white text-sm">
                                {{ $application->mahasiswa->jurusan?->nama_jurusan ?? '-' }}</td>
                            <td class="px-3 py-3 dark:text-white text-sm">
                                {{ $application->mahasiswa->angkatan?->nama_angkatan ?? '-' }}</td>
                            <td class="px-3 py-3">
                                <div class="flex space-x-2">
                                    <button type="button"
                                        onclick="openApproveModal({{ $application->id }}, '{{ addslashes($application->mahasiswa->nama_mahasiswa ?? $application->mahasiswa->username) }}')"
                                        class="text-green-500 hover:text-green-700 transition-colors" title="Terima">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                    <button type="button"
                                        onclick="openRejectModal({{ $application->id }}, '{{ addslashes($application->mahasiswa->nama_mahasiswa ?? $application->mahasiswa->username) }}')"
                                        class="text-red-500 hover:text-red-700 transition-colors" title="Tolak">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                @if(request('search'))
                                    Tidak ada hasil pencarian.
                                    <div class="mt-2">
                                        <a href="{{ route('admin.users.keahlian-tambahan.index') }}" class="text-blue-500 hover:underline">Reset Pencarian</a>
                                    </div>
                                @else
                                    Belum ada pengajuan keahlian tambahan.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View - Card Layout -->
        <div class="md:hidden space-y-4">
            @forelse($applications as $application)
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-600">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            @if($application->mahasiswa->photo_profile && Storage::disk('public')->exists($application->mahasiswa->photo_profile))
                                <img src="{{ asset('storage/' . ltrim($application->mahasiswa->photo_profile, '/')) }}"
                                    alt="{{ $application->mahasiswa->nama_mahasiswa ?? $application->mahasiswa->username }}"
                                    class="w-12 h-12 rounded-lg object-cover shadow-md ring-1 ring-gray-200 dark:ring-gray-700">
                            @else
                                <div
                                    class="w-12 h-12 rounded-lg bg-gray-200 dark:bg-gray-600 flex items-center justify-center shadow-md ring-1 ring-gray-200 dark:ring-gray-700">
                                    <span class="text-sm font-bold text-gray-600 dark:text-gray-300">
                                        {{ strtoupper(substr($application->mahasiswa->nama_mahasiswa ?? $application->mahasiswa->username ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <div class="font-medium dark:text-white">{{ $application->mahasiswa->nama_mahasiswa ?? 'Pengguna' }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ '@' . ($application->mahasiswa->username ?? 'username') }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $application->mahasiswa->email ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Keahlian Tambahan Diajukan:
                        </div>
                        @if($application->keahlian)
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 rounded-full text-xs font-medium">
                                {{ $application->keahlian->nama_keahlian }}
                            </span>
                        @else
                            <span class="text-sm text-gray-500">-</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                        <span>Prodi: {{ $application->mahasiswa->jurusan?->nama_jurusan ?? '-' }}</span>
                        <span>Angkatan: {{ $application->mahasiswa->angkatan?->nama_angkatan ?? '-' }}</span>
                    </div>
                    <div class="flex space-x-2 mt-3">
                        <button type="button"
                            onclick="openApproveModal({{ $application->id }}, '{{ addslashes($application->mahasiswa->nama_mahasiswa ?? $application->mahasiswa->username) }}')"
                            class="flex-1 bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg text-sm">
                            Terima
                        </button>
                        <button type="button"
                            onclick="openRejectModal({{ $application->id }}, '{{ addslashes($application->mahasiswa->nama_mahasiswa ?? $application->mahasiswa->username) }}')"
                            class="flex-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm">
                            Tolak
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                    @if(request('search'))
                        Tidak ada hasil pencarian.
                        <div class="mt-2">
                            <a href="{{ route('admin.users.keahlian-tambahan.index') }}" class="text-blue-500 hover:underline">Reset Pencarian</a>
                        </div>
                    @else
                        Belum ada pengajuan keahlian tambahan.
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Terima Pengajuan Keahlian Tambahan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Apakah Anda yakin ingin menerima pengajuan keahlian tambahan dari <span id="approveUserName"></span>?
                </p>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeApproveModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <form id="approveForm" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Terima
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Tolak Pengajuan Keahlian Tambahan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Berikan alasan penolakan untuk <span id="rejectUserName"></span>:
                </p>
                <form id="rejectForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan Penolakan
                        </label>
                        <textarea id="keterangan" name="keterangan" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-gray-100"
                            placeholder="Masukkan alasan penolakan..." required></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openApproveModal(userId, userName) {
            document.getElementById('approveUserName').textContent = userName;
            document.getElementById('approveForm').action = `/admin/manageUserKeahlianTambahan/${userId}/approve`;
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        function openRejectModal(userId, userName) {
            document.getElementById('rejectUserName').textContent = userName;
            document.getElementById('rejectForm').action = `/admin/manageUserKeahlianTambahan/${userId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('keterangan').value = '';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const approveModal = document.getElementById('approveModal');
            const rejectModal = document.getElementById('rejectModal');
            if (event.target == approveModal) {
                closeApproveModal();
            }
            if (event.target == rejectModal) {
                closeRejectModal();
            }
        }
    </script>
@endsection