/**
 * resources/js/admin/detail-angkatan.js
 * Admin Detail Angkatan — angkatan/views_detail_angkatan.blade.php
 *
 * Data mahasiswa is passed via window.__PAGE_DATA__.allMahasiswa (set in Blade)
 */

document.addEventListener('DOMContentLoaded', function () {
    const pageData = window.__PAGE_DATA__;
    if (!pageData || !pageData.allMahasiswa) return;

    const allMahasiswa = pageData.allMahasiswa;

    let filteredMahasiswa = [];
    let allEmailsVisible = false;

    // Pagination variables
    const pageSize = 10;
    let currentPage = 1;

    // Toggle individual email
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

    // Toggle all emails
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

    // Render pagination buttons
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

    // Attach email toggle listeners
    function attachEmailToggleListeners() {
        document.querySelectorAll('.email-toggle-btn').forEach(button => {
            button.removeEventListener('click', toggleIndividualEmail);
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleIndividualEmail(this);
            });
        });
    }

    // Email header toggle
    const emailHeaderToggle = document.getElementById('emailHeaderToggle');
    if (emailHeaderToggle) {
        emailHeaderToggle.addEventListener('click', function() {
            toggleAllEmails(!allEmailsVisible);
        });
    }

    // Apply filters
    function applyFiltersMahasiswa() {
        const search = document.getElementById('search-mahasiswa').value.toLowerCase().trim();
        const jurusanFilter = document.getElementById('filter-jurusan').value;
        const statusFilter = document.getElementById('filter-status').value;

        filteredMahasiswa = allMahasiswa.filter(m => {
            const matchesSearch = m.nama_mahasiswa.toLowerCase().includes(search);
            const matchesJurusan = !jurusanFilter || m.id_jurusan == jurusanFilter;
            const matchesStatus = !statusFilter ||
                (statusFilter === 'aktif' ? m.is_active : !m.is_active);

            return matchesSearch && matchesJurusan && matchesStatus;
        });

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
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">${m.jurusan_nama}</td>
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
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 w-16">Prodi:</span>
                                <span class="text-sm dark:text-white">${m.jurusan_nama}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 w-16">Email:</span>
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

    // Expose for onclick in Blade
    window.resetFiltersMahasiswa = function() {
        document.getElementById('search-mahasiswa').value = '';
        document.getElementById('filter-jurusan').value = '';
        document.getElementById('filter-status').value = '';
        applyFiltersMahasiswa();
    };

    // Expose for onclick in Blade
    window.applyFiltersMahasiswa = applyFiltersMahasiswa;

    // Init
    applyFiltersMahasiswa();

    document.getElementById('search-mahasiswa').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            applyFiltersMahasiswa();
        }
    });

    document.getElementById('filter-jurusan').addEventListener('change', applyFiltersMahasiswa);
    document.getElementById('filter-status').addEventListener('change', applyFiltersMahasiswa);

    // ===== PAGE INFO =====
    if (typeof showPageInfo === 'function') {
        showPageInfo("popup.detail_angkatan");
    }
});
