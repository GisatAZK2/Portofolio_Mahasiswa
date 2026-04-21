@extends('Layout.Layout')

@section('title', 'Project Mahasiswa - Dosen')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4 sm:py-6 px-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            @if(session('success'))
                <div class="mb-4 sm:mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 rounded-2xl flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Header -->
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Project Mahasiswa</h1>
                        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Daftar proyek yang dibuat oleh mahasiswa</p>
                    </div>
                    <a href="{{ route('dosen.projects.create') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Proyek
                    </a>
                </div>
            </div>

            <!-- Search Section -->
            <div class="mb-6">
                <form action="{{ route('dosen.projects.index') }}" method="GET" class="max-w-2xl">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ $search ?? '' }}"
                               placeholder="Cari nama project mahasiswa..."
                               class="w-full pl-12 pr-5 py-3.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-base shadow-sm transition-all dark:text-gray-100 dark:placeholder-gray-400">
                        <div class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        @if(!empty($search))
                            <a href="{{ route('dosen.projects.index') }}" 
                               class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6h12v12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Bulk Delete Controls -->
            <div class="mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <input id="selectAllCheckbox" type="checkbox"
                            class="h-4 w-4 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                        <span>Pilih Semua</span>
                    </label>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        Terpilih: <strong id="selectedCount">0</strong> / <strong id="totalProjectCount">{{ $projects->count() }}</strong>
                    </span>
                </div>
                <div>
                    <button type="button" onclick="confirmBulkDelete()"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all shadow-sm text-sm">
                        Hapus Terpilih
                    </button>
                </div>
            </div>

            <form id="bulkDeleteForm" action="{{ route('dosen.projects.bulk-delete', ['locale' => app()->getLocale()]) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
                <input type="hidden" name="selected_ids" id="selectedProjectIds">
            </form>

            <!-- Projects Grid -->
            <section>
                @if($projects->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        @foreach($projects as $project)
                            @php
                                $content = $project->isi_content ?? [];
                                $nama = $content['nama_project'] ?? 'Tanpa Nama Project';
                                $deskripsi = $content['deskripsi'] ?? null;
                                $linkVideo = $content['link_video'] ?? null;
                                $thumbnail = $content['thumbnail'] ?? null;

                                $mulaiRaw = $project->tanggal_mulai ?? null;
                                $akhirRaw = $project->tanggal_akhir ?? null;
                                $mulai = $mulaiRaw ? \Carbon\Carbon::parse($mulaiRaw) : null;
                                $akhir = $akhirRaw ? \Carbon\Carbon::parse($akhirRaw) : null;
                                $today = \Carbon\Carbon::today();

                                $mulaiFormatted = $mulai ? $mulai->translatedFormat('d M Y') : '—';
                                $akhirFormatted = $akhir ? $akhir->translatedFormat('d M Y') : 'Sekarang';

                                $statusText = 'Tidak diketahui';
                                $statusBadgeClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';

                                if ($mulai && $akhir) {
                                    if ($akhir < $today) {
                                        $statusText = 'Selesai';
                                        $statusBadgeClass = 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300';
                                    } elseif ($mulai <= $today && $today <= $akhir) {
                                        $statusText = 'Sedang Berjalan';
                                        $statusBadgeClass = 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300';
                                    } elseif ($mulai > $today) {
                                        $statusText = 'Akan Datang';
                                        $statusBadgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300';
                                    }
                                } elseif ($mulai && !$akhir) {
                                    $statusText = ($mulai <= $today) ? 'Sedang Berjalan' : 'Akan Datang';
                                    $statusBadgeClass = ($mulai <= $today) 
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' 
                                        : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300';
                                } elseif (!$mulai && $akhir && $akhir < $today) {
                                    $statusText = 'Selesai';
                                    $statusBadgeClass = 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300';
                                }

                                $embedVideo = null;
                                if ($linkVideo) {
                                    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([^"&?\/\s]{11})/i', $linkVideo, $matches);
                                    if (!empty($matches[1])) {
                                        $embedVideo = "https://www.youtube.com/embed/" . $matches[1];
                                    }
                                }

                                $mahasiswa = $project->mahasiswa;
                                $leader = $project->leader;
                                $isSameUser = $mahasiswa && $leader && $mahasiswa->id === $leader->id;

                                $dosen = auth()->user();
                                $isMahasiswaInScope = fn($user) => $user && (
                                    (!$dosen->id_jurusan || $user->id_jurusan === $dosen->id_jurusan) &&
                                    (!$dosen->id_angkatan || $user->id_angkatan === $dosen->id_angkatan) &&
                                    (!$dosen->id_keahlian || $user->id_keahlian === $dosen->id_keahlian)
                                );

                                $membersInScope = $project->members->every(fn($member) => $isMahasiswaInScope($member));

                                $isInteractive = $isMahasiswaInScope($mahasiswa)
                                    && (!$leader || $isMahasiswaInScope($leader))
                                    && $membersInScope;

                                $canEdit = $isInteractive && auth()->user()->role === 'dosen';
                            @endphp

                            <div class="relative bg-white dark:bg-gray-900 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col h-full border border-gray-200 dark:border-gray-700 {{ $isInteractive ? '' : 'opacity-70' }}">

                                <!-- Checkbox -->
                                <div class="absolute top-3 left-3 z-10">
                                    <label class="inline-flex items-center p-2 bg-white/90 dark:bg-gray-900/90 rounded-full shadow-sm">
                                        <input type="checkbox"
                                            class="project-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700"
                                            data-project-id="{{ $project->id }}"
                                            {{ $isInteractive ? '' : 'disabled' }}>
                                    </label>
                                </div>

                                <!-- Media Header -->
                                <div class="relative w-full h-40 sm:h-48 bg-gray-100 dark:bg-gray-800 overflow-hidden">
                                    @if($embedVideo)
                                        <iframe class="absolute inset-0 w-full h-full" 
                                            src="{{ $embedVideo }}?rel=0&modestbranding=1" 
                                            frameborder="0" allowfullscreen></iframe>
                                    @elseif($thumbnail && Storage::disk('public')->exists($thumbnail))
                                        <img src="{{ Storage::url($thumbnail) }}" alt="{{ $nama }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="absolute top-2 right-2">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusBadgeClass }} shadow-sm">
                                            {{ $statusText }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-4 sm:p-5 flex flex-col flex-1">
                                    <!-- User Info -->
                                    <div class="flex items-center gap-3 mb-3">
                                        @php
                                            $displayUser = $leader ?? $mahasiswa;
                                            $userName = $displayUser->nama_mahasiswa ?? 'User';
                                        @endphp
                                        <a href="{{ route('portfolio.show', ['user' => $displayUser?->id ?? '#']) }}" class="flex-shrink-0 hover:opacity-80 transition-opacity">
                                            @if($displayUser && $displayUser->photo_profile && Storage::disk('public')->exists($displayUser->photo_profile))
                                                <img src="{{ Storage::url($displayUser->photo_profile) }}" 
                                                     alt="{{ $userName }}" 
                                                     class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                                    {{ substr($userName, 0, 1) }}
                                                </div>
                                            @endif
                                        </a>
                                        <div>
                                            <a href="{{ route('portfolio.show', ['user' => $displayUser?->id ?? '#']) }}" 
                                               class="font-semibold text-gray-900 dark:text-white hover:text-indigo-600 transition-colors">
                                                {{ $userName }}
                                            </a>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                {{ $project->created_at?->diffForHumans() ?? 'Baru saja' }}
                                            </p>
                                        </div>
                                    </div>

                                    @if($mahasiswa && $leader && !$isSameUser)
                                        <div class="flex items-center gap-2 mb-3 pl-2 border-l-2 border-gray-300 dark:border-gray-600">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Owner:</span>
                                            <a href="{{ route('portfolio.show', ['user' => $mahasiswa->id]) }}" class="font-medium hover:text-indigo-600 dark:hover:text-indigo-400">
                                                {{ $mahasiswa->nama_mahasiswa }}
                                            </a>
                                        </div>
                                    @endif

                                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white line-clamp-2 mb-2">
                                        <a href="{{ route('project.show', ['id' => $project->id]) }}" 
                                           class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                            {{ $nama }}
                                        </a>
                                    </h3>

                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-3 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $mulaiFormatted }} - {{ $akhirFormatted }}</span>
                                    </div>

                                    @if($deskripsi)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2 flex-1">
                                            {{ $deskripsi }}
                                        </p>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="flex flex-wrap gap-2 mt-auto pt-3 border-t border-gray-100 dark:border-gray-700">
                                        @if($canEdit)
                                            <a href="{{ route('dosen.projects.details', ['id' => $project->id]) }}"
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 rounded-lg text-sm font-medium hover:bg-yellow-200 dark:hover:bg-yellow-900 transition">
                                                Edit
                                            </a>
                                        @endif

                                        <a href="{{ route('project.show', ['id' => $project->id]) }}"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300 rounded-lg text-sm font-medium hover:bg-indigo-200 dark:hover:bg-indigo-900 transition">
                                            Detail
                                        </a>

                                        @if($isInteractive)
                                            <button type="button"
                                                onclick="handleSingleDelete(this, {{ $project->id }})"
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 rounded-lg text-sm font-medium hover:bg-red-200 dark:hover:bg-red-900 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        @else
                                            <button disabled
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 rounded-lg text-sm font-medium opacity-50 cursor-not-allowed">
                                                Hapus
                                            </button>
                                        @endif
                                    </div>

                                    <div class="mt-3 text-xs text-gray-400 dark:text-gray-500">
                                        Diposting pada {{ $project->created_at?->format('d M Y H:i') ?? '—' }} WIB
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(method_exists($projects, 'links'))
                        <div class="mt-10 flex justify-center">
                            {{ $projects->links() }}
                        </div>
                    @endif

                @else
                    <div class="text-center py-16 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700">
                        <svg class="w-20 h-20 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-6 text-lg text-gray-600 dark:text-gray-400">Belum ada project yang ditemukan</p>
                        <p class="text-sm text-gray-500 mt-2">Coba cari dengan kata kunci lain atau tambahkan project baru.</p>
                    </div>
                @endif
            </section>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Fungsi showConfirm untuk konfirmasi delete - DITEMUKAN PERTAMA
        async function showConfirm() {
            const result = await Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });
            return result.isConfirmed;
        }

        // SINGLE DELETE - Modal SweetAlert2
        async function handleSingleDelete(button, projectId) {
            event.preventDefault();
            
            const confirmed = await showConfirm();
            
            if (confirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const form = document.createElement('form');
                form.method = 'POST';
                
                const locale = document.querySelector('html').getAttribute('lang') || 'id';
                form.action = `/${locale}/dosen/manageProject/DeleteProject?id=${projectId}`;
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                
                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // BULK DELETE
        async function confirmBulkDelete() {
            const checkedCheckboxes = document.querySelectorAll('.project-checkbox:checked');
            const ids = Array.from(checkedCheckboxes).map(cb => cb.dataset.projectId);

            if (ids.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak ada yang dipilih',
                    text: 'Pilih minimal satu project terlebih dahulu.',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            const confirmed = await showConfirm();
            if (confirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                document.getElementById('selectedProjectIds').value = ids.join(',');
                document.getElementById('bulkDeleteForm').submit();
            }
        }

        // Update selected count
        function updateSelectionState() {
            const checkedCount = document.querySelectorAll('.project-checkbox:checked').length;
            const selectedCountEl = document.getElementById('selectedCount');
            if (selectedCountEl) {
                selectedCountEl.textContent = checkedCount;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.project-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectionState);
            });

            const selectAll = document.getElementById('selectAllCheckbox');
            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(cb => {
                        if (!cb.disabled) cb.checked = this.checked;
                    });
                    updateSelectionState();
                });
            }

            updateSelectionState();
        });
        
    </script>

    <!-- Page Info -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof showPageInfo === 'function') {
                showPageInfo("popup.dosen_projects");
            }
        });
    </script>
@endsection