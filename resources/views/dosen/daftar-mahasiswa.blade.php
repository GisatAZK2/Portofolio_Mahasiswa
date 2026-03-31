@extends('Layout.Layout')
@section('title', 'Kelola Pengguna - Dosen')
@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8 transition-colors duration-200">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 data-translate="kll_mhs" data-translate-page="dosen_kll_mhs"
                        class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Pengguna</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        <span data-translate="kll_desc_mhs" data-translate-page="dosen_kll_mhs">Kelola semua pengguna yang
                            terdaftar dalam sistem</span>
                    </p>
                </div>
                <a href="{{ route('dosen.users.ViewCreate') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl shadow-sm hover:shadow-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span data-translate="add_mhs" data-translate-page="dosen_kll_mhs"></span>
                </a>
            </div>

            <!-- Grid Users -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($users as $user)
                    <div
                        class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1">

                        <div
                            class="relative h-24 bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-600 dark:to-indigo-700 px-6 pt-6">

                            <!-- Background Image jika ada -->
                            @if($user->background_url && Storage::disk('public')->exists($user->background_url))
                                <div class="absolute inset-0">
                                    <img src="{{ asset('storage/' . ltrim($user->background_url, '/')) }}" alt="Background"
                                        class="w-full h-full object-cover opacity-80">
                                </div>
                            @endif

                            <!-- Avatar -->
                            <div class="absolute -bottom-10 left-6 z-10">
                                @if($user->photo_profile && Storage::disk('public')->exists($user->photo_profile))
                                    <img src="{{ asset('storage/' . ltrim($user->photo_profile, '/')) }}"
                                        alt="{{ $user->nama_mahasiswa ?? $user->username }}"
                                        class="w-20 h-20 rounded-xl object-cover border-4 border-white dark:border-gray-800 shadow-lg">
                                @else
                                    <div
                                        class="w-20 h-20 rounded-xl bg-white dark:bg-gray-700 border-4 border-white dark:border-gray-800 shadow-lg flex items-center justify-center">
                                        <span class="text-2xl font-bold text-gray-700 dark:text-gray-300">
                                            {{ strtoupper(substr($user->nama_mahasiswa ?? $user->username ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Role Badge -->
                            <div class="absolute top-4 right-4 z-10">
                                <span class="px-3 py-1 rounded-full text-xs font-medium shadow-sm
                                    @if($user->role == 'dosen') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                    @elseif($user->role == 'dosen') bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400
                                    @else bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                    @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="pt-12 px-6 pb-6">
                            <!-- Nama dan Username (dapat diklik) -->
                            <a href="{{ route('portfolio.show', $user->id) }}"
                                class="block group-hover:opacity-90 transition-opacity">
                                <h3
                                    class="font-semibold text-lg text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ $user->nama_mahasiswa ?? 'Pengguna' }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                    {{ '@' . ($user->username ?? 'username') }}</p>
                            </a>

                            <!-- Status Pengajuan -->
                            @if($user->status_pengajuan)
                                <div class="mb-4">
                                    @if($user->status_pengajuan == 'Di Terima')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                            <span data-translate="acc" data-translate-page="dosen_kll_mhs"></span>
                                        </span>
                                    @elseif($user->status_pengajuan == 'Di Tolak')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                            <span data-translate="rej" data-translate-page="dosen_kll_mhs"></span>
                                        </span>
                                    @elseif($user->status_pengajuan == 'Sedang Di Ajukan')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5 animate-pulse"></span>
                                            <span data-translate="pend" data-translate-page="dosen_kll_mhs"></span>
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Info Details -->
                            <div class="space-y-2 text-sm mb-4">
                                @if($user->email)
                                    <div class="flex items-center text-gray-600 dark:text-gray-300">
                                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <a href="mailto:{{ $user->email }}" class="hover:underline truncate"
                                            title="{{ $user->email }}">
                                            {{ $user->email }}
                                        </a>
                                    </div>
                                @endif

                                @if($user->jurusan?->nama_jurusan)
                                    <div class="flex items-center text-gray-600 dark:text-gray-300">
                                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                        </svg>
                                        <span class="truncate">{{ $user->jurusan->nama_jurusan }}</span>
                                    </div>
                                @endif

                                @if($user->angkatan?->tahun)
                                    <div class="flex items-center text-gray-600 dark:text-gray-300">
                                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Angkatan {{ $user->angkatan->tahun }}</span>
                                    </div>
                                @endif

                                @if($user->keahlian?->nama_keahlian)
                                    <div class="flex items-center text-gray-600 dark:text-gray-300">
                                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span class="truncate">{{ $user->keahlian->nama_keahlian }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Stats -->
                            <div class="grid grid-cols-3 gap-2 py-3 border-t border-gray-100 dark:border-gray-700">
                                <div class="text-center">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $user->projects_count ?? 0 }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><span data-translate="pjt"
                                            data-translate-page="dosen_kll_mhs"></span></p>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $user->learning_corners_count ?? 0 }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><span data-translate="lrn"
                                            data-translate-page="dosen_kll_mhs"></span></p>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $user->sertifikats_count ?? 0 }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><span data-translate="stk"
                                            data-translate-page="dosen_kll_mhs"></span></p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div
                                class="flex items-center justify-end gap-2 mt-4 pt-2 border-t border-gray-100 dark:border-gray-700">
                                @if(in_array($user->role, ['mahasiswa', 'dosen']))
                                    <a href="{{ route('portfolio.show', $user->id) }}"
                                        class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                        title="Lihat Portfolio">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                @endif

                                @if($user->status_pengajuan == 'Sedang Di Ajukan')
                                    <button type="button"
                                        onclick="openUpdateModal({{ $user->id }}, '{{ addslashes($user->nama_mahasiswa ?? $user->username) }}')"
                                        class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors"
                                        title="Update Status">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                @endif

                                <form action="{{ route('dosen.users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="delete-btn p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                        title="Hapus Pengguna"
                                        data-name="{{ addslashes($user->nama_mahasiswa ?? $user->username) }}" data-form="this">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-12 text-center">
                            <div
                                class="w-20 h-20 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum Ada Pengguna</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Mulai dengan menambahkan pengguna pertama
                                Anda</p>
                            <a href="{{ route('dosen.users.ViewCreate') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Pengguna
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal Update Status -->
    <div id="updateModal"
        class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 hidden overflow-y-auto h-full w-full z-50 transition-opacity duration-300">
        <div class="relative top-20 mx-auto p-5 w-full max-w-md">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl transform transition-all">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Update Status Pengajuan</h3>

                    <form id="updateForm" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Mahasiswa: <span id="modalNama" class="font-medium text-gray-900 dark:text-white"></span>
                            </p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status_pengajuan" id="status_pengajuan"
                                class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-colors"
                                required>
                                <option value="">Pilih Status</option>
                                <option value="Di Terima">Terima Pengajuan</option>
                                <option value="Di Tolak">Tolak Pengajuan</option>
                            </select>
                        </div>

                        <div class="mb-6 hidden" id="keteranganTolakField">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Alasan Penolakan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="keterangan_tolak" rows="4" id="keterangan_tolak"
                                class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-colors"
                                placeholder="Masukkan alasan penolakan..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">Alasan akan dikirimkan ke email mahasiswa</p>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeUpdateModal()"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal"
        class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 hidden overflow-y-auto h-full w-full z-50 transition-opacity duration-300">
        <div class="relative top-20 mx-auto p-5 w-full max-w-md">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl">
                <div class="p-6">
                    <div
                        class="w-12 h-12 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-2">Hapus Pengguna</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-4">
                        Apakah Anda yakin ingin menghapus pengguna <span id="deleteUserName"
                            class="font-medium text-gray-900 dark:text-white"></span>?
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="closeDeleteModal()"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Batal
                        </button>
                        <button type="button" id="confirmDeleteBtn"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk toggle field alasan penolakan
        function toggleKeteranganField() {
            const select = document.getElementById('status_pengajuan');
            const field = document.getElementById('keteranganTolakField');
            const textarea = document.getElementById('keterangan_tolak');

            if (select && field && textarea) {
                if (select.value === 'Di Tolak') {
                    field.classList.remove('hidden');
                    textarea.setAttribute('required', 'required');
                } else {
                    field.classList.add('hidden');
                    textarea.removeAttribute('required');
                    textarea.value = '';
                }
            }
        }

        function openUpdateModal(userId, userName) {
            const modal = document.getElementById('updateModal');
            const form = document.getElementById('updateForm');
            const namaSpan = document.getElementById('modalNama');

            if (modal && form && namaSpan) {
                form.action = `/user/${userId}/update-status`;
                namaSpan.textContent = userName;

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                // Reset form
                const statusSelect = document.getElementById('status_pengajuan');
                const keteranganField = document.getElementById('keteranganTolakField');
                const keteranganText = document.getElementById('keterangan_tolak');

                if (statusSelect) statusSelect.value = '';
                if (keteranganField) keteranganField.classList.add('hidden');
                if (keteranganText) {
                    keteranganText.removeAttribute('required');
                    keteranganText.value = '';
                }
            }
        }

        function closeUpdateModal() {
            const modal = document.getElementById('updateModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Delete confirmation
        let currentDeleteForm = null;

        document.addEventListener('DOMContentLoaded', function () {
            // Setup status select listener
            const statusSelect = document.getElementById('status_pengajuan');
            if (statusSelect) {
                statusSelect.addEventListener('change', toggleKeteranganField);
            }

            // Setup delete buttons
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    const userName = this.getAttribute('data-name');

                    if (form && userName) {
                        currentDeleteForm = form;
                        document.getElementById('deleteUserName').textContent = userName;
                        document.getElementById('deleteModal').classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                    }
                });
            });

            // Setup confirm delete button
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function () {
                    if (currentDeleteForm) {
                        // Tampilkan loading
                        showLoading('Menghapus pengguna...');
                        currentDeleteForm.submit();
                    }
                });
            }

            // Close modal when clicking outside
            const modals = document.querySelectorAll('#updateModal, #deleteModal');
            modals.forEach(modal => {
                modal.addEventListener('click', function (e) {
                    if (e.target === this) {
                        if (this.id === 'updateModal') {
                            closeUpdateModal();
                        } else {
                            closeDeleteModal();
                        }
                    }
                });
            });

            // Close with ESC key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeUpdateModal();
                    closeDeleteModal();
                }
            });
        });

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                currentDeleteForm = null;
            }
        }

        // Success/Error message handlers
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', function () {
                showSuccessAlert('{{ session('success') }}');
            });
        @endif

        @if (session('error'))
            document.addEventListener('DOMContentLoaded', function () {
                showErrorAlert('{{ session('error') }}');
            });
        @endif
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof showPageInfo === 'function') {
                showPageInfo("Kelola semua pengguna yang terdaftar dalam sistem. Anda dapat melihat detail, memperbarui status pengajuan, atau menghapus pengguna sesuai kebutuhan.");
            }
        });
    </script>

    <!-- Loading Indicator -->
    <div id="loadingOverlay"
        class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 hidden items-center justify-center z-[100]">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl flex items-center space-x-4">
            <div class="w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
            <span class="text-gray-700 dark:text-gray-300 font-medium" id="loadingText">Memproses...</span>
        </div>
    </div>

    <script>
        function showLoading(message = 'Memproses...') {
            const overlay = document.getElementById('loadingOverlay');
            const text = document.getElementById('loadingText');
            if (overlay && text) {
                text.textContent = message;
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
            }
        }

        function hideLoading() {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
            }
        }
    </script>

@endsection