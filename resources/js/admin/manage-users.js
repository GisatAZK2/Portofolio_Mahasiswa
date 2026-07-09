/**
 * admin/manage-users.js
 * Halaman Kelola User (daftar-mahasiswa): export CSV/Word,
 * toggle email visibility, bulk select/delete/approve, pagination filter.
 */
(function () {
    // ============================================================
    //  MANAGE USERS PAGE  (daftar-mahasiswa)
    //  Semua fungsi di-expose ke window supaya onclick di HTML bisa memanggil.
    // ============================================================

    let allEmailsVisible = true;

    // ============ EXPORT FUNCTIONS ============
    function getExportData() {
        const users = [];
        const allItems = document.querySelectorAll('.paginated-item');
        allItems.forEach(row => {
            let userData = {};
            const desktopCells = row.querySelectorAll('td');
            if (desktopCells.length > 0) {
                userData = {
                    nama: row.querySelector('td:nth-child(3) .font-medium')?.innerText || '-',
                    username: row.querySelector('td:nth-child(3) .text-sm')?.innerText?.replace('@', '') || '-',
                    email: getEmailText(row),
                    role: row.querySelector('td:nth-child(5) span')?.innerText || '-',
                    status_pengajuan: getStatusPengajuanText(row),
                    status_aktif: getStatusAktifText(row),
                    jurusan: row.querySelector('td:nth-child(8)')?.innerText?.trim() || '-',
                    angkatan: row.querySelector('td:nth-child(9)')?.innerText?.trim() || '-'
                };
            } else {
                userData = {
                    nama: row.querySelector('h3')?.innerText || '-',
                    username: row.querySelector('.text-gray-500')?.innerText?.replace('@', '') || '-',
                    email: getMobileEmailText(row),
                    role: row.querySelector('.rounded-full')?.innerText || '-',
                    status_pengajuan: getMobileStatusPengajuanText(row),
                    status_aktif: getMobileStatusAktifText(row),
                    jurusan: getMobileJurusanText(row),
                    angkatan: getMobileAngkatanText(row)
                };
            }
            users.push(userData);
        });
        return users;
    }

    function getEmailText(row) {
        const emailCell = row.querySelector('td:nth-child(4) .email-cell');
        if (emailCell && emailCell.textContent !== '...') return emailCell.textContent;
        return row.querySelector('td:nth-child(4) [data-email]')?.getAttribute('data-email') || '-';
    }

    function getStatusPengajuanText(row) {
        const statusSpan = row.querySelector('td:nth-child(6) span');
        if (statusSpan) {
            let text = statusSpan.innerText.replace('✓', '').replace('✗', '').replace('⏳', '').trim();
            if (text === 'Diterima') return 'Di Terima';
            if (text === 'Ditolak') return 'Di Tolak';
            if (text === 'Menunggu') return 'Sedang Di Ajukan';
            return text;
        }
        return '-';
    }

    function getStatusAktifText(row) {
        const activeSpan = row.querySelector('td:nth-child(7) span');
        if (activeSpan) return activeSpan.innerText.includes('Aktif') ? 'Aktif' : 'Tidak Aktif';
        return '-';
    }

    function getMobileEmailText(row) {
        const emailDiv = row.querySelector('.email-cell-mobile');
        if (emailDiv && emailDiv.textContent !== '...') return emailDiv.textContent;
        return emailDiv?.getAttribute('data-email') || '-';
    }

    function getMobileStatusPengajuanText(row) {
        const statusText = row.querySelector('.text-green-600, .text-red-600, .text-yellow-600');
        if (statusText) {
            let text = statusText.innerText.replace('✓', '').replace('✗', '').replace('⏳', '').trim();
            if (text === 'Diterima') return 'Di Terima';
            if (text === 'Ditolak') return 'Di Tolak';
            if (text === 'Menunggu') return 'Sedang Di Ajukan';
            return text;
        }
        return '-';
    }

    function getMobileStatusAktifText(row) {
        const activeText = Array.from(row.querySelectorAll('.text-green-600, .text-red-600'))
            .find(el => el.innerText.includes('Aktif') || el.innerText.includes('Tidak'));
        if (activeText) return activeText.innerText.replace('✓', '').replace('✗', '').trim();
        return '-';
    }

    function getMobileJurusanText(row) {
        const jurusanDiv = Array.from(row.querySelectorAll('.flex.justify-between'))
            .find(div => div.innerText.includes('Jurusan:'));
        return jurusanDiv ? jurusanDiv.querySelector('span:last-child')?.innerText || '-' : '-';
    }

    function getMobileAngkatanText(row) {
        const angkatanDiv = Array.from(row.querySelectorAll('.flex.justify-between'))
            .find(div => div.innerText.includes('Angkatan:'));
        return angkatanDiv ? angkatanDiv.querySelector('span:last-child')?.innerText || '-' : '-';
    }

    function formatDate(date) {
        return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
    }

    function escapeHtml(str) {
        if (!str || str === '-') return '-';
        return String(str).replace(/[&<>]/g, function (m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    window.exportToExcel = function () {
        const users = getExportData();
        if (users.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Tidak Ada Data', text: 'Tidak ada data pengguna yang dapat diexport.', confirmButtonColor: '#3b82f6' });
            return;
        }
        const columns = ['NO', 'NAMA LENGKAP', 'USERNAME', 'EMAIL', 'ROLE', 'STATUS PENGAJUAN', 'STATUS AKTIF', 'JURUSAN/PRODI', 'ANGKATAN'];
        let csvContent = '\uFEFF';
        csvContent += columns.map(col => `"${col}"`).join(';') + '\r\n';
        users.forEach((user, index) => {
            const row = [index + 1, user.nama, user.username, user.email, user.role, user.status_pengajuan, user.status_aktif, user.jurusan, user.angkatan];
            csvContent += row.map(item => `"${String(item).replace(/"/g, '""')}"`).join(';') + '\r\n';
        });
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.href = url;
        link.download = `data_pengguna_${formatDate(new Date())}.csv`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        Swal.fire({ icon: 'success', title: 'Export Berhasil!', text: `${users.length} data berhasil diexport.`, timer: 1500, showConfirmButton: false });
    };

    window.exportToWord = function () {
        const users = getExportData();
        if (users.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Tidak Ada Data', text: 'Tidak ada data pengguna yang dapat diexport.', confirmButtonColor: '#3b82f6' });
            return;
        }
        const date = new Date();
        let html = `<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Data Pengguna</title>
<style>table{border-collapse:collapse;width:100%}th,td{border:1px solid #ddd;padding:8px;text-align:left}th{background:#f0f0f0}</style>
</head><body>
<h3>Data Pengguna - ${formatDate(date)}</h3><p>Total: ${users.length} pengguna</p>
<table><thead><tr><th>NO</th><th>NAMA LENGKAP</th><th>USERNAME</th><th>EMAIL</th><th>ROLE</th><th>STATUS PENGAJUAN</th><th>STATUS AKTIF</th><th>JURUSAN/PRODI</th><th>ANGKATAN</th></tr></thead><tbody>`;
        users.forEach((user, i) => {
            html += `<tr><td>${i + 1}</td><td>${escapeHtml(user.nama)}</td><td>${escapeHtml(user.username)}</td><td>${escapeHtml(user.email)}</td><td>${escapeHtml(user.role)}</td><td>${escapeHtml(user.status_pengajuan)}</td><td>${escapeHtml(user.status_aktif)}</td><td>${escapeHtml(user.jurusan)}</td><td>${escapeHtml(user.angkatan)}</td></tr>`;
        });
        html += `</tbody></table></body></html>`;
        const blob = new Blob([html], { type: 'application/msword' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.href = url;
        link.setAttribute('download', `laporan_pengguna_${formatDate(date)}.doc`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        Swal.fire({ icon: 'success', title: 'Export Berhasil!', text: `${users.length} data berhasil diexport.`, timer: 1500, showConfirmButton: false });
    };

    // ============ EMAIL TOGGLE FUNCTIONS ============
    window.toggleIndividualEmailVisibility = function (button, isMobile = false) {
        let emailCell, eyeShow, eyeHide;
        if (isMobile) {
            const wrapper = button.closest('.flex-1');
            emailCell = wrapper.querySelector('.email-cell-mobile');
            eyeShow = button.querySelector('.email-eye-show-mobile');
            eyeHide = button.querySelector('.email-eye-hide-mobile');
        } else {
            emailCell = button.previousElementSibling;
            eyeShow = button.querySelector('.email-eye-show');
            eyeHide = button.querySelector('.email-eye-hide');
        }
        const isHidden = emailCell.textContent === '...';
        if (isHidden) {
            emailCell.textContent = emailCell.dataset.email;
            emailCell.title = emailCell.dataset.email;
        } else {
            emailCell.textContent = '...';
            emailCell.title = 'Email tersembunyi';
        }
        eyeShow.classList.toggle('hidden', isHidden);
        eyeHide.classList.toggle('hidden', !isHidden);
    };

    function toggleAllEmailsVisibility() {
        allEmailsVisible = !allEmailsVisible;
        const headerEyeShow = document.getElementById('header-eye-icon-show');
        const headerEyeHide = document.getElementById('header-eye-icon-hide');

        document.querySelectorAll('.email-toggle-btn').forEach(btn => {
            const emailCell = btn.previousElementSibling;
            const eyeShow = btn.querySelector('.email-eye-show');
            const eyeHide = btn.querySelector('.email-eye-hide');
            if (allEmailsVisible) {
                emailCell.textContent = emailCell.dataset.email;
                emailCell.title = emailCell.dataset.email;
                eyeShow.classList.remove('hidden');
                eyeHide.classList.add('hidden');
            } else {
                emailCell.textContent = '...';
                emailCell.title = 'Email tersembunyi';
                eyeShow.classList.add('hidden');
                eyeHide.classList.remove('hidden');
            }
        });

        document.querySelectorAll('.email-toggle-btn-mobile').forEach(btn => {
            const wrapper = btn.closest('.flex-1');
            const emailCell = wrapper.querySelector('.email-cell-mobile');
            const eyeShow = btn.querySelector('.email-eye-show-mobile');
            const eyeHide = btn.querySelector('.email-eye-hide-mobile');
            if (allEmailsVisible) {
                emailCell.textContent = emailCell.dataset.email;
                emailCell.title = emailCell.dataset.email;
                eyeShow.classList.remove('hidden');
                eyeHide.classList.add('hidden');
            } else {
                emailCell.textContent = '...';
                emailCell.title = 'Email tersembunyi';
                eyeShow.classList.add('hidden');
                eyeHide.classList.remove('hidden');
            }
        });

        if (headerEyeShow && headerEyeHide) {
            headerEyeShow.classList.toggle('hidden', !allEmailsVisible);
            headerEyeHide.classList.toggle('hidden', allEmailsVisible);
        }
    }

    // ============ CHECKBOX & SELECT ALL ============
    let selectedIds = [];

    function getAllCheckboxes() {
        return Array.from(document.querySelectorAll('.item-checkbox'));
    }

    function getVisibleCheckboxes() {
        return getAllCheckboxes().filter(checkbox => checkbox.offsetParent !== null);
    }

    function setCheckboxStateByValue(value, checked) {
        getAllCheckboxes().forEach(checkbox => {
            if (checkbox.value === value) checkbox.checked = checked;
        });
    }

    function updateSelectedIds() {
        const selectedSet = new Set();
        getAllCheckboxes().forEach(checkbox => {
            if (checkbox.checked) selectedSet.add(checkbox.value);
        });
        selectedIds = Array.from(selectedSet);
        const totalSelectedEl = document.getElementById('totalSelected');
        if (totalSelectedEl) totalSelectedEl.textContent = selectedIds.length;

        const bulkForm = document.getElementById('bulkDeleteForm');
        if (bulkForm) {
            bulkForm.querySelectorAll('input[name="selected_ids[]"]').forEach(input => input.remove());
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;
                bulkForm.appendChild(input);
            });
        }

        const bulkApproveForm = document.getElementById('bulkApproveForm');
        if (bulkApproveForm) {
            bulkApproveForm.querySelectorAll('input[name="selected_ids[]"]').forEach(input => input.remove());
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;
                bulkApproveForm.appendChild(input);
            });
        }

        const allCheckboxes = getAllCheckboxes();
        const allUniqueIds = [...new Set(allCheckboxes.map(cb => cb.value))];
        const visibleCheckboxes = getVisibleCheckboxes();
        const visibleUniqueIds = [...new Set(visibleCheckboxes.map(cb => cb.value))];

        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const tableSelectAllCheckbox = document.getElementById('tableSelectAllCheckbox');

        if (selectAllCheckbox) {
            if (selectedIds.length === allUniqueIds.length && allUniqueIds.length > 0) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else if (selectedIds.length === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            }
        }

        if (tableSelectAllCheckbox) {
            const visibleSelectedCount = visibleUniqueIds.filter(id => selectedIds.includes(id)).length;
            if (visibleSelectedCount === visibleUniqueIds.length && visibleUniqueIds.length > 0) {
                tableSelectAllCheckbox.checked = true;
                tableSelectAllCheckbox.indeterminate = false;
            } else if (visibleSelectedCount === 0) {
                tableSelectAllCheckbox.checked = false;
                tableSelectAllCheckbox.indeterminate = false;
            } else {
                tableSelectAllCheckbox.checked = false;
                tableSelectAllCheckbox.indeterminate = true;
            }
        }

        const mobileDeleteBtn = document.getElementById('mobileBulkDeleteBtn');
        const mobileApproveBtn = document.getElementById('mobileBulkApproveBtn');
        const desktopDeleteBtn = document.getElementById('desktopBulkDeleteBtn');
        const desktopApproveBtn = document.getElementById('desktopBulkApproveBtn');
        const enable = selectedIds.length > 0;

        const selectedCheckboxes = getAllCheckboxes().filter(cb => selectedIds.includes(cb.value));
        const hasPending = selectedCheckboxes.some(cb => cb.dataset.status === 'Sedang Di Ajukan');

        const approveEnable = enable && hasPending;

        [mobileDeleteBtn, desktopDeleteBtn].forEach(btn => {
            if (!btn) return;
            btn.disabled = !enable;
            btn.classList.toggle('opacity-50', !enable);
            btn.classList.toggle('cursor-not-allowed', !enable);
            btn.classList.toggle('hover:bg-red-600', enable);
            btn.classList.toggle('hover:bg-red-500', !enable);
            if (!enable) btn.setAttribute('aria-disabled', 'true'); else btn.removeAttribute('aria-disabled');
        });

        [mobileApproveBtn, desktopApproveBtn].forEach(btn => {
            if (!btn) return;
            btn.disabled = !approveEnable;
            btn.classList.toggle('opacity-50', !approveEnable);
            btn.classList.toggle('cursor-not-allowed', !approveEnable);
            btn.classList.toggle('hover:bg-emerald-600', approveEnable);
            btn.classList.toggle('hover:bg-emerald-500', !approveEnable);
            if (!approveEnable) btn.setAttribute('aria-disabled', 'true'); else btn.removeAttribute('aria-disabled');
        });
    }

    window.toggleAllUsers = function (source, onlyVisible = false) {
        const targetCheckboxes = onlyVisible ? getVisibleCheckboxes() : getAllCheckboxes();
        const targetValues = [...new Set(targetCheckboxes.map(cb => cb.value))];
        targetValues.forEach(value => setCheckboxStateByValue(value, source.checked));
        updateSelectedIds();
    };

    window.confirmBulkDelete = function () {
        updateSelectedIds();
        if (selectedIds.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Tidak Ada Data Dipilih', text: 'Silakan pilih minimal satu pengguna.', confirmButtonColor: '#3b82f6' });
            return;
        }
        Swal.fire({
            title: 'Hapus Pengguna Terpilih?',
            html: `Anda akan menghapus <strong>${selectedIds.length}</strong> pengguna secara permanen.<br><br><small class="text-red-600">Tindakan ini tidak dapat dibatalkan.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Permanen',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('bulkDeleteForm').submit();
        });
    };

    window.confirmBulkApprove = function () {
        updateSelectedIds();
        if (selectedIds.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Tidak Ada Data Dipilih', text: 'Silakan pilih minimal satu pengguna.', confirmButtonColor: '#3b82f6' });
            return;
        }
        Swal.fire({
            title: 'Setujui Pengguna Terpilih?',
            html: `Anda akan menyetujui <strong>${selectedIds.length}</strong> pengajuan pengguna.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('bulkApproveForm').submit();
        });
    };

    // ============ DELETE & UPDATE MODALS ============
    window.openDeleteModal = function (id, name) {
        Swal.fire({
            title: 'Hapus Pengguna',
            html: `Apakah Anda yakin ingin menghapus <strong>${name}</strong>?<br><br><small class="text-red-600">Tindakan ini tidak dapat dibatalkan.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('hiddenDeleteForm');
                const locale = document.querySelector('html').getAttribute('lang') || 'id';
                form.action = `/${locale}/admin/manageUser/DeleteUser?id=${id}`;
                form.submit();
            }
        });
    };

    window.openUpdateModal = function (userId, userName) {
        Swal.fire({
            title: 'Update Status Pengajuan',
            html: `
            <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-left">
                <p class="text-sm text-gray-600 dark:text-gray-400">Mahasiswa: <span class="font-medium text-gray-900 dark:text-white">${userName}</span></p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Status Pengajuan <span class="text-red-500">*</span></label>
                <select id="swal-status_pengajuan" onchange="toggleKeteranganField(this)" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Pilih Status</option>
                    <option value="Di Terima">✅ Terima Pengajuan</option>
                    <option value="Di Tolak">❌ Tolak Pengajuan</option>
                </select>
            </div>
            <div class="mb-4 hidden" id="swal-keteranganTolakField">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Keterangan / Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea id="swal-keterangan_tolak" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Masukkan alasan penolakan secara jelas..."></textarea>
                <p class="text-xs text-gray-500 mt-1">Alasan ini akan dikirimkan ke email mahasiswa</p>
            </div>`,
            showCancelButton: true,
            confirmButtonText: 'Simpan Perubahan',
            cancelButtonText: 'Batal',
            width: '520px',
            preConfirm: () => {
                const status = document.getElementById('swal-status_pengajuan').value;
                const keterangan = document.getElementById('swal-keterangan_tolak') ? document.getElementById('swal-keterangan_tolak').value.trim() : '';
                if (!status) {
                    Swal.showValidationMessage('Silakan pilih status pengajuan');
                    return false;
                }
                if (status === 'Di Tolak' && !keterangan) {
                    Swal.showValidationMessage('Alasan penolakan wajib diisi!');
                    return false;
                }
                const form = document.getElementById('hiddenUpdateForm');
                form.action = `/user/${userId}/update-status`;
                document.getElementById('hidden_status_pengajuan').value = status;
                document.getElementById('hidden_keterangan_tolak').value = keterangan;
                form.submit();
                return false;
            }
        });
    };

    window.toggleKeteranganField = function (selectElement) {
        const keteranganField = document.getElementById('swal-keteranganTolakField');
        if (selectElement.value === 'Di Tolak') {
            keteranganField.classList.remove('hidden');
            setTimeout(() => document.getElementById('swal-keterangan_tolak')?.focus(), 300);
        } else {
            keteranganField.classList.add('hidden');
        }
    };

    // ============ CLIENT PAGINATION ============
    const clientPageSize = 10;
    let clientCurrentPage = 1;
    // Simpan totalCount di state agar tidak bergantung pada elemen DOM yang mungkin tidak ada
    let _paginationTotal = 0;

    function getClientPaginationItems() {
        return Array.from(document.querySelectorAll('.paginated-item')).sort((a, b) => parseInt(a.dataset.itemIndex, 10) - parseInt(b.dataset.itemIndex, 10));
    }

    function renderPaginationButtons() {
        // Gunakan state variable, bukan baca dari DOM supaya tidak crash jika elemen tidak ada
        const pageCount = Math.max(1, Math.ceil(_paginationTotal / clientPageSize));
        const paginationNav = document.getElementById('paginationNumberButtons');
        if (!paginationNav) return;
        paginationNav.innerHTML = '';

        const visiblePages = 10;
        let startPage = Math.max(1, clientCurrentPage - Math.floor(visiblePages / 2));
        let endPage = startPage + visiblePages - 1;
        if (endPage > pageCount) {
            endPage = pageCount;
            startPage = Math.max(1, endPage - visiblePages + 1);
        }

        const addNavButton = (label, page, isActive) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = label;
            button.className = `px-3 py-2 min-w-[40px] rounded-full text-sm font-medium transition ${isActive ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700'}`;
            if (!isActive) button.addEventListener('click', () => { clientCurrentPage = page; updateClientPagination(); });
            paginationNav.appendChild(button);
        };

        if (clientCurrentPage > 1) addNavButton('«', clientCurrentPage - 1, false);
        if (startPage > 1) {
            addNavButton('1', 1, false);
            if (startPage > 2) {
                const ellipsis = document.createElement('span');
                ellipsis.textContent = '...';
                ellipsis.className = 'px-3 py-2 text-sm text-gray-500 dark:text-gray-400';
                paginationNav.appendChild(ellipsis);
            }
        }
        for (let page = startPage; page <= endPage; page++) addNavButton(page, page, page === clientCurrentPage);
        if (endPage < pageCount) {
            if (endPage < pageCount - 1) {
                const ellipsis = document.createElement('span');
                ellipsis.textContent = '...';
                ellipsis.className = 'px-3 py-2 text-sm text-gray-500 dark:text-gray-400';
                paginationNav.appendChild(ellipsis);
            }
            addNavButton(pageCount, pageCount, false);
        }
        if (clientCurrentPage < pageCount) addNavButton('»', clientCurrentPage + 1, false);
    }

    function updateClientPagination() {
        const items = getClientPaginationItems();
        const uniqueIndices = [...new Set(items.map(item => parseInt(item.dataset.itemIndex, 10)))].sort((a, b) => a - b);
        _paginationTotal = uniqueIndices.length;
        const startIndex = (clientCurrentPage - 1) * clientPageSize;
        const endIndex = startIndex + clientPageSize;
        const pageIndices = new Set(uniqueIndices.slice(startIndex, endIndex));
        items.forEach(item => {
            const idx = parseInt(item.dataset.itemIndex, 10);
            item.classList.toggle('hidden', !pageIndices.has(idx));
        });
        // Null-safe: update DOM counter elements hanya jika ada di halaman
        const visibleCountEl = document.getElementById('paginationVisibleCount');
        const totalCountEl = document.getElementById('paginationTotalCount');
        if (visibleCountEl) visibleCountEl.textContent = pageIndices.size;
        if (totalCountEl) totalCountEl.textContent = _paginationTotal;
        renderPaginationButtons();
    }

    // ============ INITIALIZATION ============
    document.addEventListener('DOMContentLoaded', function () {
        // Hanya jalankan jika elemen halaman manage-users ada
        if (!document.getElementById('manage-users-data')) return;

        // Email header toggle
        const emailHeaderToggle = document.getElementById('emailHeaderToggle');
        if (emailHeaderToggle) emailHeaderToggle.addEventListener('click', toggleAllEmailsVisibility);

        // Desktop email toggle buttons
        document.querySelectorAll('.email-toggle-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                window.toggleIndividualEmailVisibility(this, false);
            });
        });

        // Mobile email toggle buttons
        document.querySelectorAll('.email-toggle-btn-mobile').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                window.toggleIndividualEmailVisibility(this, true);
            });
        });

        updateSelectedIds();
        updateClientPagination();

        document.addEventListener('change', function (event) {
            if (event.target instanceof HTMLInputElement && event.target.classList.contains('item-checkbox')) {
                setCheckboxStateByValue(event.target.value, event.target.checked);
                updateSelectedIds();
            }
        });

        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        if (selectAllCheckbox) selectAllCheckbox.addEventListener('change', function () { window.toggleAllUsers(this, false); });

        const tableSelectAllCheckbox = document.getElementById('tableSelectAllCheckbox');
        if (tableSelectAllCheckbox) tableSelectAllCheckbox.addEventListener('change', function () { window.toggleAllUsers(this, true); });

        // Flash messages dibaca dari data attribute (bukan dari @if Blade)
        const pageData = document.getElementById('manage-users-data');
        if (pageData) {
            const flashSuccess = pageData.dataset.flashSuccess;
            const flashError = pageData.dataset.flashError;
            const flashInfo = pageData.dataset.flashInfo;
            if (flashSuccess) Swal.fire({ icon: 'success', title: 'Berhasil!', text: flashSuccess, showConfirmButton: false, timer: 3000, timerProgressBar: true });
            if (flashError) Swal.fire({ icon: 'error', title: 'Gagal!', text: flashError, confirmButtonColor: '#dc2626' });
            if (flashInfo) Swal.fire({ icon: 'info', title: 'Informasi', text: flashInfo, confirmButtonColor: '#3b82f6' });
        }

    });

})();
