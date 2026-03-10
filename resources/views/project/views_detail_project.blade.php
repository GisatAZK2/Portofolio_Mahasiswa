@extends('Layout.Layout')

@section('content')
    <!-- CONTENT -->
    <div class="p-4 sm:p-6 lg:p-10 space-y-6 sm:space-y-8 lg:space-y-10">

        <!-- PROJECT CARD -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 sm:p-6 lg:p-8">
            <!-- Project Status Bar -->
            <div class="mb-6">
                @php
                    $today = now();
                    $start = \Carbon\Carbon::parse($project->tanggal_mulai);
                    $end = $project->tanggal_akhir ? \Carbon\Carbon::parse($project->tanggal_akhir) : null;
                    
                    // Determine status
                    if ($today < $start) {
                        $status = 'incoming';
                        $statusText = 'Akan Datang';
                        $statusColor = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                        $progress = 0;
                    } elseif ($end && $today > $end) {
                        $status = 'past';
                        $statusText = 'Selesai';
                        $statusColor = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                        $progress = 100;
                    } else {
                        $status = 'present';
                        $statusText = 'Sedang Berjalan';
                        $statusColor = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                        
                        // Calculate progress percentage
                        if ($end) {
                            $totalDays = $start->diffInDays($end);
                            $daysPassed = $start->diffInDays($today);
                            $progress = min(100, max(0, round(($daysPassed / $totalDays) * 100)));
                        } else {
                            $progress = 50; // Default if no end date
                        }
                    }
                @endphp

                <div class="flex flex-wrap items-center gap-3 mb-3">
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusColor }}" data-translate="status_{{ $status }}" data-translate-page="project_detail">
                        {{ $statusText }}
                    </span>
                    @if($status !== 'past')
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $progress }}% <span data-translate="progress_done" data-translate-page="project_detail"></span>
                        </span>
                    @endif
                </div>

                <!-- Progress Bar -->
                @if($status !== 'past')
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" 
                             style="width: {{ $progress }}%"></div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                <!-- LEFT SIDE -->
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold mb-4 dark:text-gray-200">
                        <span data-translate="nama_project" data-translate-page="project_detail"></span>: {{ $project->isi_content['nama_project'] ?? 'Tanpa Judul' }}
                    </h2>

                    <p class="font-semibold dark:text-gray-200" data-translate="deskripsi_opsional" data-translate-page="project_detail"></p>
                    <p class="text-gray-700 dark:text-gray-300 mb-6 text-sm sm:text-base">
                        {{ $project->isi_content['deskripsi'] ?? 'Tidak ada Deskripsi' }}
                    </p>

                    <p class="font-semibold mb-1 dark:text-gray-200" data-translate="students_involved" data-translate-page="project_detail"></p>
                    <p class="mt-4 dark:text-gray-200" data-translate="tambah_pemimpin" data-translate-page="project_detail"></p>
                    @if($project->leader)
                        <li class="ml-2 sm:ml-4 list-none">
                            <ul class="p-0">
                                <div class="flex dark:text-gray-200 dark:hover:text-indigo-300 hover:text-indigo-800 items-center gap-2 border px-2 py-1.5 mt-2 rounded-lg max-w-max">
                                    @if($project->leader->photo_profile)
                                        <img src="{{ asset('storage/' . ltrim($project->leader->photo_profile, '/')) }}"
                                             alt="{{ $project->leader->nama_mahasiswa ?? 'Mahasiswa' }}"
                                             class="w-5 h-5 rounded-full object-cover">
                                    @else
                                        <div class="w-5 h-5 bg-indigo-600 flex items-center justify-center text-white text-xs font-bold rounded-full">
                                            {{ strtoupper(mb_substr(trim($project->leader->nama_mahasiswa ?? 'M'), 0, 1)) }}
                                        </div>
                                    @endif
                                    <a href="{{ route('portfolio.show', $project->leader->id) }}" class="text-sm sm:text-base">
                                        {{ $project->leader->nama_mahasiswa }}
                                    </a>
                                </div>
                            </ul>
                        </li>
                    @else
                        <p class="dark:text-gray-100 ml-2 sm:ml-4 text-sm sm:text-base" data-translate="no_leader" data-translate-page="project_detail"></p>
                    @endif
                    
                    <p class="mt-6 dark:text-gray-200" data-translate="tambah_rekan" data-translate-page="project_detail"></p>
                    <div class="mt-2 ml-2 sm:ml-4 space-y-2 text-gray-700 dark:text-gray-300">
                        @forelse($project->members as $member)
                            @if($member->id !== $project->leader_id)
                                <li class="list-none">
                                    <ul class="p-0">
                                        <div class="flex hover:text-indigo-800 items-center gap-2 border p-1.5 max-w-max rounded-lg">
                                            @if($member->photo_profile)
                                                <img src="{{ asset('storage/' . ltrim($member->photo_profile, '/')) }}"
                                                     alt="{{ $member->nama_mahasiswa ?? 'Mahasiswa' }}"
                                                     class="w-5 h-5 rounded-full object-cover">
                                            @else
                                                <div class="w-5 h-5 bg-indigo-600 flex items-center justify-center text-white text-xs font-bold rounded-full">
                                                    {{ strtoupper(mb_substr(trim($member->nama_mahasiswa ?? 'M'), 0, 1)) }}
                                                </div>
                                            @endif
                                            <a href="{{ route('portfolio.show', $member->id) }}" class="text-sm sm:text-base">
                                                {{ $member->nama_mahasiswa }}
                                            </a> 
                                        </div>
                                    </ul>
                                </li>
                            @endif
                        @empty
                            <p class="text-gray-400 dark:text-gray-200 text-sm sm:text-base" data-translate="no_member" data-translate-page="project_detail"></p>
                        @endforelse
                    </div>
                </div>

                <!-- RIGHT SIDE -->
                <div>
                    <p class="font-semibold mb-2 dark:text-gray-200" data-translate="time_period" data-translate-page="project_detail"></p>
                    <div class="pb-2 dark:text-gray-200 text-sm sm:text-base">
                        {{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d/m/Y') }}
                        →
                        {{ $project->tanggal_akhir 
                        ? \Carbon\Carbon::parse($project->tanggal_akhir)->format('d/m/Y') 
                        : '-' }}
                    </div>
                    
                    <p class="font-semibold mb-2 dark:text-gray-200" data-translate="links" data-translate-page="project_detail"></p>
                    
                    <div class="flex flex-wrap gap-3 sm:gap-4 text-blue-500 dark:text-blue-300 mb-6">
                        @if(!empty($project->isi_content['link_video']))
                            <a href="{{ $project->isi_content['link_video'] }}" 
                               target="_blank"
                               class="hover:underline text-sm sm:text-base">
                               <span data-translate="link_video_opsional" data-translate-page="project_detail"></span>
                            </a>
                        @endif
                        
                        @if(!empty($project->isi_content['link_github']))
                            <a href="{{ $project->isi_content['link_github'] }}" 
                               target="_blank"
                               class="hover:underline text-sm sm:text-base">
                               <span data-translate="link_github_opsional" data-translate-page="project_detail"></span>
                            </a>
                        @endif
                        
                        @if(!empty($project->isi_content['link_project']))
                            <a href="{{ $project->isi_content['link_project'] }}" 
                                target="_blank"
                                class="hover:underline text-sm sm:text-base">
                                <span data-translate="link_project_opsional" data-translate-page="project_detail"></span>
                            </a>
                        @endif
                    </div>
    
                    <!-- Embed Box -->
                    @php
                        $video = $project->isi_content['link_video'] ?? null;
                        $github = $project->isi_content['link_github'] ?? null;
                        $projectLink = $project->isi_content['link_project'] ?? null;
                    @endphp

                    @if($video)
                        @php
                            $embed = null;
                            if (str_contains($video, 'watch?v=')) {
                                $embed = str_replace('watch?v=', 'embed/', $video);
                            } elseif (str_contains($video, 'youtu.be/')) {
                                $embed = str_replace('youtu.be/', 'youtube.com/embed/', $video);
                            }
                        @endphp

                        @if($embed)
                            <div class="rounded-xl overflow-hidden shadow">
                                <iframe 
                                    class="w-full h-48 sm:h-56 lg:h-64"
                                    src="{{ $embed }}"
                                    frameborder="0"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        @endif
                    @elseif($github || $projectLink)
                        <div class="rounded-xl overflow-hidden shadow">
                            <iframe 
                                class="w-full h-48 sm:h-56 lg:h-64"
                                src="{{ $github ?? $projectLink }}"
                                frameborder="0">
                            </iframe>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- LEARNING CORNER SECTION -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 sm:p-6 lg:p-8 relative mt-6 sm:mt-8 lg:mt-10">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <h2 class="text-xl sm:text-2xl font-bold dark:text-gray-50" data-translate="learning_corner" data-translate-page="project_detail"></h2>

                <div class="flex items-center gap-3">
                    @auth
                        @php
                            $user = auth()->user();
                            $isProjectMember = $user && (
                                $user->id === $project->id_mahasiswa ||
                                $user->id === $project->leader_id ||
                                $project->members->contains('id', $user->id)
                            );
                        @endphp

                        @if($isProjectMember)
                            <!-- Mass Delete Form for Owner/Leader (hanya untuk member yang login) -->
                            @if($user->id === $project->id_mahasiswa || $user->id === $project->leader_id)
                                <form id="massDeleteForm" action="{{ route('learning-corner.mass-destroy') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="ids" id="massDeleteIds" value="">
                                    <button type="button"
                                            id="massDeleteBtn"
                                            class="px-4 sm:px-5 py-2 sm:py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-sm text-sm font-medium text-center opacity-50 cursor-not-allowed"
                                            disabled>
                                        <span data-translate="delete_selected" data-translate-page="project_detail"></span> (0)
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('learning-corner.create', $project->id) }}"
                               class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm text-sm font-medium text-center">
                                <span data-translate="add_new_note" data-translate-page="project_detail"></span>
                            </a>
                        @endif
                    @else
                    @endauth
                </div>
            </div>

            @if ($entries->isEmpty())
                <div class="text-center py-8 sm:py-12 text-gray-500 dark:text-gray-400 italic text-sm sm:text-base">
                    <span data-translate="no_learning_corner" data-translate-page="project_detail"></span>
                </div>
            @else
                <!-- Grid dengan 1 card vertikal -->
                <div class="space-y-4 sm:space-y-5 lg:space-y-6">
                    @foreach ($entries as $index => $entry)
                        @php
                            $canManage = $entry->canManage(auth()->user());
                            $isOwnerOrLeader = auth()->check() && (auth()->id() === $project->id_mahasiswa || auth()->id() === $project->leader_id);
                            
                            // Parse content
                            $titles = [];
                            $texts = [];
                            $images = [];
                            $links = [];
                            
                            if (!empty($entry->content) && is_array($entry->content)) {
                                foreach ($entry->content as $item) {
                                    switch ($item['type'] ?? '') {
                                        case 'title':
                                            $titles[] = $item['content'] ?? '';
                                            break;
                                        case 'text':
                                            $texts[] = $item['content'] ?? '';
                                            break;
                                        case 'image':
                                            $images[] = $item['content'] ?? '';
                                            break;
                                        case 'link':
                                            $links[] = $item['content'] ?? '';
                                            break;
                                    }
                                }
                            }
                            
                            $firstTitle = $titles[0] ?? 'Untitled';
                        @endphp

                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 sm:p-5 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-200 dark:border-gray-700">
                            <!-- Selection Checkbox for Mass Delete (hanya untuk owner/leader yang login) -->
                            @if($isOwnerOrLeader)
                                <div class="flex items-center mb-3 pb-2 border-b border-gray-200 dark:border-gray-700">
                                    <input type="checkbox" 
                                           class="entry-checkbox w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                           data-id="{{ $entry->id_learning_corner }}">
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400" data-translate="select_to_delete" data-translate-page="project_detail"></span>
                                </div>
                            @endif

                            <div class="flex flex-col lg:flex-row gap-4 sm:gap-5 lg:gap-6">
                                <!-- Left Side: Titles, Texts, Links -->
                                <div class="flex-1 space-y-4">
                                    <!-- Titles -->
                                    @foreach($titles as $title)
                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $title }}
                                        </h3>
                                    @endforeach

                                    <!-- Texts -->
                                    @foreach($texts as $text)
                                        <p class="text-gray-700 dark:text-gray-300 text-sm sm:text-base">
                                            {{ $text }}
                                        </p>
                                    @endforeach

                                    <!-- Links -->
                                    @if(!empty($links))
                                        <div class="space-y-2">
                                            @foreach($links as $link)
                                                <a href="{{ $link }}" target="_blank" rel="noopener noreferrer"
                                                   class="inline-block text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline text-sm sm:text-base break-all">
                                                    {{ Str::limit($link, 60) }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Meta Info & Actions -->
                                    <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                                        <div class="flex flex-wrap justify-between items-center gap-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-3">
                                            <span class="truncate max-w-[120px] sm:max-w-[150px]">
                                                {{ $entry->mahasiswa->nama_mahasiswa ?? 'Unknown' }}
                                            </span>
                                            <span>{{ $entry->created_at?->format('d M Y') ?? ($entry->tanggal?->format('d M Y') ?? '-') }}</span>
                                        </div>

                                        <!-- Action Buttons - Hanya tampil jika user login DAN memiliki akses manage -->
                                        @auth
                                            @if ($canManage)
                                                <div class="flex flex-col sm:flex-row gap-2">
                                                    <a href="{{ route('learning-corner.edit', $entry->id_learning_corner) }}"
                                                       class="flex-1 text-center px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 dark:bg-blue-950 dark:text-blue-300 dark:hover:bg-blue-900 transition text-xs sm:text-sm">
                                                        <span data-translate="edit" data-translate-page="project_detail"></span>
                                                    </a>

                                                    <form action="{{ route('learning-corner.destroy', $entry->id_learning_corner) }}" 
                                                          method="POST" 
                                                          class="flex-1 delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                                class="w-full px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 dark:bg-red-950 dark:text-red-300 dark:hover:bg-red-900 transition text-xs sm:text-sm delete-btn">
                                                            <span data-translate="delete" data-translate-page="project_detail"></span>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>

                                <!-- Right Side: Images - VERTICAL LAYOUT -->
                                @if(!empty($images))
                                    <div class="lg:w-80 flex-shrink-0">
                                        <div class="space-y-3">
                                            @foreach($images as $image)
                                                @php
                                                    $imagePath = str_replace(['\\', '/'], '/', $image);
                                                @endphp
                                                <div class="relative group cursor-pointer image-thumbnail w-full"
                                                    onclick="openImageModal('{{ asset('storage/' . ltrim($imagePath, '/')) }}')">

                                                    <img src="{{ asset('storage/' . ltrim($imagePath, '/')) }}"
                                                        alt="Gambar konten"
                                                        class="w-full h-auto max-h-48 object-contain rounded-lg border border-gray-200 shadow-sm hover:opacity-90 transition bg-gray-100 dark:bg-gray-700"
                                                        loading="lazy"
                                                        onerror="this.onerror=null; this.src='https://via.placeholder.com/400x200?text=Gambar+Tidak+Ditemukan'; this.classList.add('opacity-90');">

                                                    <!-- Overlay -->
                                                    <div class="absolute inset-0 bg-transparent group-hover:bg-black/60 transition duration-300 rounded-lg flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition duration-300"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination (if needed) -->
                @if(method_exists($entries, 'links'))
                    <div class="mt-6 sm:mt-8">
                        {{ $entries->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4" onclick="closeImageModal()">
        <div class="relative max-w-4xl max-h-[90vh] w-full" onclick="event.stopPropagation()">
            <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalImage" src="" alt="Full size image" class="w-full h-auto max-h-[90vh] object-contain rounded-lg">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Individual delete buttons
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', async function (e) {
                    e.preventDefault();
                    
                    const form = this.closest('.delete-form');
                    if (!form) return;

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
                        form.submit();
                    }
                });
            });

            // Mass delete functionality
            const checkboxes = document.querySelectorAll('.entry-checkbox');
            const massDeleteBtn = document.getElementById('massDeleteBtn');
            const massDeleteIds = document.getElementById('massDeleteIds');
            const massDeleteForm = document.getElementById('massDeleteForm');

            if (checkboxes.length > 0 && massDeleteBtn && massDeleteForm) {
                function updateMassDeleteButton() {
                    const checkedBoxes = document.querySelectorAll('.entry-checkbox:checked');
                    const checkedCount = checkedBoxes.length;
                    
                    massDeleteBtn.textContent = `Hapus Terpilih (${checkedCount})`;
                    
                    // Update hidden input with selected IDs
                    const selectedIds = Array.from(checkedBoxes).map(cb => cb.dataset.id);
                    massDeleteIds.value = JSON.stringify(selectedIds);
                    
                    if (checkedCount > 0) {
                        massDeleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        massDeleteBtn.disabled = false;
                    } else {
                        massDeleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        massDeleteBtn.disabled = true;
                    }
                }

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateMassDeleteButton);
                });

                massDeleteBtn.addEventListener('click', async function() {
                    const checkedCount = document.querySelectorAll('.entry-checkbox:checked').length;
                    
                    if (checkedCount === 0) return;

                    const confirmed = await showConfirmAlert({
                        title: 'Hapus Multiple Entri?',
                        text: `Anda akan menghapus ${checkedCount} catatan. Tindakan ini tidak dapat dibatalkan.`,
                        icon: 'warning',
                        confirmButtonText: 'Ya, Hapus Semua',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                    });

                    if (confirmed) {
                        showLoading('Menghapus catatan terpilih...');
                        
                        // Parse IDs from hidden input
                        const ids = JSON.parse(massDeleteIds.value);
                        
                        // Create a new form with the IDs as array
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = massDeleteForm.action;
                        
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);
                        
                        ids.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = id;
                            form.appendChild(input);
                        });
                        
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            @if (session('success'))
                showSuccessAlert('{{ session('success') }}');
            @endif

            @if (session('error'))
                showErrorAlert('{{ session('error') }}');
            @endif
        });

        // Image Modal Functions
        function openImageModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageSrc;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
@endsection