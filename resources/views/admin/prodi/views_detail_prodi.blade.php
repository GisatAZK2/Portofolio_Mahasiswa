@extends('Layout.Layout')
@section('title', 'Detail Prodi - ' . ($prodi->nama_jurusan ?? ''))

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
        }

        /* Style untuk tabel responsif dengan scroll horizontal */
        .table-responsive-wrapper {
            width: 100%;
            overflow-x: auto;
            overflow-y: visible;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
            position: relative;
            z-index: 1;
        }

        .table-responsive-wrapper::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive-wrapper::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .table-responsive-wrapper::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .table-responsive-wrapper::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark .table-responsive-wrapper::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark .table-responsive-wrapper::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        .dark .table-responsive-wrapper::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }

        /* Tabel dengan lebar setengah container */
        .half-width-table {
            min-width: 100%;
            width: max-content;
            table-layout: auto;
            position: relative;
            z-index: 1;
        }

        /* Container untuk membatasi lebar */
        .table-half-container {
            width: 100%;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        @media (min-width: 768px) {
            .table-half-container {
                width: 95%;
            }
        }

        @media (min-width: 1024px) {
            .table-half-container {
                width: 90%;
            }
        }
    </style>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
                            Detail Prodi
                        </h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            {{ $prodi->nama_jurusan ?? 'Prodi' }}
                        </p>
                    </div>
                    <a href="{{ route('admin.prodi.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-gray-100 dark:bg-gray-800 px-5 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all shadow-md hover:shadow-lg border border-gray-200 dark:border-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Info Prodi Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Prodi</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $prodi->nama_jurusan }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m5-2v-2c0-.656-.126-1.284-.356-1.852M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.284.356-1.852m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Mahasiswa</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $prodi->users_count ?? 0 }}</p>
                </div>
            </div>

            <!-- Statistik Mahasiswa -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Mahasiswa</p>
                            <p class="text-4xl font-bold mt-2">{{ $prodi->users_count ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m5-2v-2c0-.656-.126-1.284-.356-1.852M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.284.356-1.852m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Mahasiswa Aktif</p>
                            <p class="text-4xl font-bold mt-2">{{ $prodi->users->where('is_active', true)->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Mahasiswa Tidak Aktif</p>
                            <p class="text-4xl font-bold mt-2">{{ $prodi->users->where('is_active', false)->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Mahasiswa -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Daftar Mahasiswa</h2>
                </div>

                <!-- Filter Section -->
                <div class="p-6 bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="relative flex-1">
                            <input type="text" id="search-mahasiswa" placeholder="Cari nama mahasiswa..."
                                class="w-full pl-10 pr-4 py-2.5 text-sm bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500">
                            <svg class="absolute left-3 top-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" onclick="resetFiltersMahasiswa()"
                                class="px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                Reset
                            </button>
                            <button type="button" onclick="applyFiltersMahasiswa()"
                                class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all shadow-sm">
                                Terapkan
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Table - Desktop View -->
                <div class="hidden md:block">
                    <div class="table-half-container">
                        <div class="table-responsive-wrapper">
                            <table id="mahasiswa-table-desktop" class="half-width-table divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 responsive-compact-table">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-12">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[200px]">Nama Mahasiswa</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[250px]">
                                            <div class="flex items-center gap-2">
                                                Email
                                                <button type="button" id="emailHeaderToggle" class="p-1 hover:bg-gray-200 dark:hover:bg-gray-600 rounded transition" title="Toggle email visibility">
                                                    <svg id="header-eye-show" class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <svg id="header-eye-hide" class="w-3.5 h-3.5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[100px]">Status</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[100px]">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="mahasiswa-tbody-desktop" class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <!-- Desktop rows loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Mobile View - Card Layout -->
                <div id="mobile-cards-container" class="md:hidden p-4 space-y-3">
                    <!-- Mobile cards loaded here -->
                </div>

                <!-- Empty State -->
                <div id="empty-state-mahasiswa" class="hidden py-16">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0v2h-2v-2a7 7 0 00-14 0v2H6v-2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tidak ada mahasiswa ditemukan</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-center max-w-md">
                            Coba ubah filter pencarian Anda
                        </p>
                    </div>
                </div>

                <!-- Pagination Controls -->
                <div id="clientPaginationControls" class="p-6 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span>Menampilkan</span> 
                        <span id="paginationVisibleCount">0</span> 
                        <span>dari</span> 
                        <span id="paginationTotalCount">0</span> 
                        <span>mahasiswa</span>
                    </p>
                    <nav id="paginationNumberButtons" class="flex flex-wrap items-center gap-2"></nav>
                </div>
            </div>
        </div>
    </div>

    <script>
        const allMahasiswa = {!! json_encode($prodi->users->map(function ($m) {
            return [
                'id' => $m->id,
                'nama_mahasiswa' => $m->nama_mahasiswa,
                'email' => $m->email,
                'is_active' => $m->is_active,
            ];
        })) !!};

        let filteredMahasiswa = [];
        let allEmailsVisible = false;
        
        const pageSize = 10;
        let currentPage = 1;

        function toggleIndividualEmail(button) {
            const container = button.closest('.email-container');
            if (!container) return;
            
            const emailDisplay = container.querySelector('.email-display');
            const eyeShow = button.querySelector('.email-eye-show');
            const eyeHide = button.querySelector('.email-eye-hide');
            
            if (!emailDisplay || !eyeShow || !eyeHide) return;
            
            if (emailDisplay.textContent === '••••••••') {
                emailDisplay.textContent = emailDisplay.dataset.email || '-';
                eyeShow.classList.add('hidden');
                eyeHide.classList.remove('hidden');
            } else {
                emailDisplay.textContent = '••••••••';
                eyeShow.classList.remove('hidden');
                eyeHide.classList.add('hidden');
            }
        }

        function toggleAllEmails(show) {
            document.querySelectorAll('.email-container').forEach(container => {
                const emailDisplay = container.querySelector('.email-display');
                const button = container.querySelector('.email-toggle-btn');
                
                if (!emailDisplay || !button) return;
                
                const eyeShow = button.querySelector('.email-eye-show');
                const eyeHide = button.querySelector('.email-eye-hide');
                
                if (show) {
                    emailDisplay.textContent = emailDisplay.dataset.email || '-';
                    if (eyeShow) eyeShow.classList.add('hidden');
                    if (eyeHide) eyeHide.classList.remove('hidden');
                } else {
                    emailDisplay.textContent = '••••••••';
                    if (eyeShow) eyeShow.classList.remove('hidden');
                    if (eyeHide) eyeHide.classList.add('hidden');
                }
            });
            
            const headerEyeShow = document.getElementById('header-eye-show');
            const headerEyeHide = document.getElementById('header-eye-hide');
            if (headerEyeShow && headerEyeHide) {
                if (show) {
                    headerEyeShow.classList.add('hidden');
                    headerEyeHide.classList.remove('hidden');
                } else {
                    headerEyeShow.classList.remove('hidden');
                    headerEyeHide.classList.add('hidden');
                }
            }
            allEmailsVisible = show;
        }

        function renderPaginationButtons() {
            const totalCount = filteredMahasiswa.length;
            const pageCount = Math.max(1, Math.ceil(totalCount / pageSize));
            const paginationNav = document.getElementById('paginationNumberButtons');

            if (!paginationNav) return;
            paginationNav.innerHTML = '';

            const visiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(visiblePages / 2));
            let endPage = startPage + visiblePages - 1;

            if (endPage > pageCount) {
                endPage = pageCount;
                startPage = Math.max(1, endPage - visiblePages + 1);
            }

            const addNavButton = (label, page, isActive) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = label;
                button.className = 'px-3 py-1.5 min-w-[36px] rounded-lg text-sm font-medium transition';
                if (isActive) {
                    button.classList.add('bg-blue-600', 'text-white');
                } else {
                    button.classList.add('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300', 'border', 'border-gray-200', 'dark:border-gray-700', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
                    button.addEventListener('click', () => {
                        currentPage = page;
                        displayMahasiswa();
                    });
                }
                paginationNav.appendChild(button);
            };

            if (currentPage > 1) {
                addNavButton('«', currentPage - 1, false);
            }

            if (startPage > 1) {
                addNavButton('1', 1, false);
                if (startPage > 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.className = 'px-2 text-gray-400';
                    paginationNav.appendChild(ellipsis);
                }
            }

            for (let page = startPage; page <= endPage; page++) {
                addNavButton(page, page, page === currentPage);
            }

            if (endPage < pageCount) {
                if (endPage < pageCount - 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.className = 'px-2 text-gray-400';
                    paginationNav.appendChild(ellipsis);
                }
                addNavButton(pageCount, pageCount, false);
            }

            if (currentPage < pageCount) {
                addNavButton('»', currentPage + 1, false);
            }
        }

        function attachEmailToggleListeners() {
            document.querySelectorAll('.email-toggle-btn').forEach(button => {
                button.removeEventListener('click', toggleIndividualEmail);
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleIndividualEmail(this);
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const emailHeaderToggle = document.getElementById('emailHeaderToggle');
            if (emailHeaderToggle) {
                emailHeaderToggle.addEventListener('click', function() {
                    toggleAllEmails(!allEmailsVisible);
                });
            }

            applyFiltersMahasiswa();

            document.getElementById('search-mahasiswa').addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    applyFiltersMahasiswa();
                }
            });
        });

        function applyFiltersMahasiswa() {
            const search = document.getElementById('search-mahasiswa').value.toLowerCase().trim();

            filteredMahasiswa = allMahasiswa.filter(m => 
                m.nama_mahasiswa.toLowerCase().includes(search)
            );

            currentPage = 1;
            displayMahasiswa();
        }

        function displayMahasiswa() {
            const tbodyDesktop = document.getElementById('mahasiswa-tbody-desktop');
            const mobileContainer = document.getElementById('mobile-cards-container');
            const emptyState = document.getElementById('empty-state-mahasiswa');
            const tableDesktop = document.getElementById('mahasiswa-table-desktop');
            const paginationControls = document.getElementById('clientPaginationControls');

            const totalCount = filteredMahasiswa.length;
            const startIndex = (currentPage - 1) * pageSize;
            const endIndex = Math.min(startIndex + pageSize, totalCount);
            const paginatedData = filteredMahasiswa.slice(startIndex, endIndex);

            document.getElementById('paginationTotalCount').textContent = totalCount;
            document.getElementById('paginationVisibleCount').textContent = totalCount > 0 ? endIndex : 0;

            if (totalCount === 0) {
                if (tbodyDesktop) tbodyDesktop.innerHTML = '';
                if (mobileContainer) mobileContainer.innerHTML = '';
                if (tableDesktop) tableDesktop.classList.add('hidden');
                mobileContainer?.classList.add('hidden');
                if (emptyState) emptyState.classList.remove('hidden');
                if (paginationControls) paginationControls.classList.add('hidden');
                return;
            }

            if (tableDesktop) tableDesktop.classList.remove('hidden');
            mobileContainer?.classList.remove('hidden');
            if (emptyState) emptyState.classList.add('hidden');
            if (paginationControls) paginationControls.classList.remove('hidden');

            const locale = document.querySelector('html').getAttribute('lang') || 'id';
            
            // Render Desktop Table
            if (tbodyDesktop) {
                let desktopHtml = '';
                paginatedData.forEach((m, index) => {
                    const globalIndex = startIndex + index + 1;
                    const statusColor = m.is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
                    const statusText = m.is_active ? 'Aktif' : 'Tidak Aktif';
                    const portfolioUrl = `/${locale}/portofolio?user=${m.id}`;
                    const emailDisplay = m.email || '-';

                    desktopHtml += `
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition paginated-item">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">${globalIndex}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">${m.nama_mahasiswa}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                <div class="email-container flex items-center gap-2">
                                    <span class="email-display" data-email="${emailDisplay}">••••••••</span>
                                    <button type="button" class="email-toggle-btn p-1 hover:bg-gray-200 dark:hover:bg-gray-600 rounded transition" title="Toggle email">
                                        <svg class="email-eye-show w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg class="email-eye-hide w-3.5 h-3.5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 text-xs font-medium ${statusColor} rounded-full">
                                    ${statusText}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="${portfolioUrl}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    `;
                });
                tbodyDesktop.innerHTML = desktopHtml;
            }

            // Render Mobile Cards
            if (mobileContainer) {
                let mobileHtml = '';
                paginatedData.forEach((m, index) => {
                    const globalIndex = startIndex + index + 1;
                    const statusColor = m.is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
                    const statusText = m.is_active ? 'Aktif' : 'Tidak Aktif';
                    const portfolioUrl = `/${locale}/portofolio?user=${m.id}`;
                    const emailDisplay = m.email || '-';

                    mobileHtml += `
                        <div class="paginated-item bg-white dark:bg-gray-700 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-600">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                        <span class="text-blue-700 dark:text-blue-300 font-medium text-sm">
                                            ${m.nama_mahasiswa.charAt(0).toUpperCase()}
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold dark:text-white">${m.nama_mahasiswa}</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">#${globalIndex}</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium ${statusColor}">
                                    ${statusText}
                                </span>
                            </div>

                            <div class="space-y-2 mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 w-12">Email:</span>
                                    <div class="email-container flex items-center gap-2 flex-1">
                                        <span class="email-display text-sm dark:text-gray-300" data-email="${emailDisplay}">••••••••</span>
                                        <button type="button" class="email-toggle-btn p-1 hover:bg-gray-200 dark:hover:bg-gray-600 rounded transition" title="Toggle email">
                                            <svg class="email-eye-show w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg class="email-eye-hide w-3.5 h-3.5 text-gray-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-3 border-t border-gray-200 dark:border-gray-600">
                                <a href="${portfolioUrl}" class="inline-flex items-center gap-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat Portfolio
                                </a>
                            </div>
                        </div>
                    `;
                });
                mobileContainer.innerHTML = mobileHtml;
            }
            
            attachEmailToggleListeners();
            renderPaginationButtons();
            
            if (allEmailsVisible) {
                toggleAllEmails(true);
            }
        }

        function resetFiltersMahasiswa() {
            document.getElementById('search-mahasiswa').value = '';
            applyFiltersMahasiswa();
        }
    </script>
@endsection