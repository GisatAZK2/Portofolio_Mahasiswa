/**
 * resources/js/admin/daftar-mahasiswa-keahlian-tambahan.js
 * Admin Daftar Mahasiswa Keahlian Tambahan — daftar-mahasiswa-keahlian-tambahan.blade.php
 */

function getCurrentLocale() {
    const path = window.location.pathname;
    const match = path.match(/^\/(id|en)\//);
    return match ? match[1] : 'id'; // default ke id
}

(function () {
    const root = document.getElementById('keahlian-tambahan-page') || document.getElementById('manage-keahlian-tambahan');
    if (!root) return;

    window.openApproveModal = function(userId, userName) {
        const locale = getCurrentLocale();
        const approveUserName = root.querySelector('#approveUserName');
        const approveForm = root.querySelector('#approveForm');
        const approveModal = root.querySelector('#approveModal');
        if (approveUserName) approveUserName.textContent = userName;
        if (approveForm) approveForm.action = `/${locale}/admin/manageUserKeahlianTambahan/approve?user_id=${userId}`;
        if (approveModal) approveModal.classList.remove('hidden');
    };

    window.closeApproveModal = function() {
        const approveModal = root.querySelector('#approveModal');
        if (approveModal) approveModal.classList.add('hidden');
    };

    window.openRejectModal = function(userId, userName) {
        const locale = getCurrentLocale();
        const rejectUserName = root.querySelector('#rejectUserName');
        const rejectForm = root.querySelector('#rejectForm');
        const rejectModal = root.querySelector('#rejectModal');
        if (rejectUserName) rejectUserName.textContent = userName;
        if (rejectForm) rejectForm.action = `/${locale}/admin/manageUserKeahlianTambahan/reject?user_id=${userId}`;
        if (rejectModal) rejectModal.classList.remove('hidden');
    };

    window.closeRejectModal = function() {
        const rejectModal = root.querySelector('#rejectModal');
        const keterangan = root.querySelector('#keterangan');
        if (rejectModal) rejectModal.classList.add('hidden');
        if (keterangan) keterangan.value = '';
    };

    // Bulk reject modal triggers
    window.openBulkRejectModal = function() {
        const bulk = root.querySelector('#bulkRejectModal');
        if (bulk) bulk.classList.remove('hidden');
    };

    window.closeBulkRejectModal = function() {
        const bulk = root.querySelector('#bulkRejectModal');
        const bulk_keterangan = root.querySelector('#bulk_keterangan');
        if (bulk) bulk.classList.add('hidden');
        if (bulk_keterangan) bulk_keterangan.value = '';
    };

    // Close modals when clicking outside
    root.addEventListener('click', function(event) {
        const approveModal = root.querySelector('#approveModal');
        const rejectModal = root.querySelector('#rejectModal');
        const bulkRejectModal = root.querySelector('#bulkRejectModal');
        if (event.target == approveModal) {
            window.closeApproveModal();
        }
        if (event.target == rejectModal) {
            window.closeRejectModal();
        }
        if (event.target == bulkRejectModal) {
            window.closeBulkRejectModal();
        }
    });

    // ===== BULK OPERATIONS LOGIC =====
    document.addEventListener('DOMContentLoaded', () => {
        const selectAllCheckbox = root.querySelector('#selectAllCheckbox');
        const bulkActionsBar = root.querySelector('#bulkActionsBar');
        const selectedCountText = root.querySelector('#selectedCountText');
        const bulkApproveBtn = root.querySelector('#bulkApproveBtn');
        const bulkRejectBtn = root.querySelector('#bulkRejectBtn');
        const bulkRejectForm = root.querySelector('#bulkRejectForm');

        function getSelectedIds() {
            const checkboxes = root.querySelectorAll('.application-checkbox:checked, .application-checkbox-mobile:checked');
            const ids = new Set();
            checkboxes.forEach(cb => ids.add(parseInt(cb.value)));
            return Array.from(ids);
        }

        function updateBulkActionsBar() {
            const selectedIds = getSelectedIds();
            const count = selectedIds.length;

            if (count > 0) {
                if (bulkActionsBar) bulkActionsBar.classList.remove('hidden');
                if (selectedCountText) selectedCountText.textContent = count;
            } else {
                if (bulkActionsBar) bulkActionsBar.classList.add('hidden');
            }

            if (selectAllCheckbox) {
                const allCheckboxes = root.querySelectorAll('.application-checkbox, .application-checkbox-mobile');
                const checkedCount = root.querySelectorAll('.application-checkbox:checked, .application-checkbox-mobile:checked').length;
                if (checkedCount === 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else if (checkedCount === allCheckboxes.length) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = true;
                }
            }
        }

        // Select all event handler
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                root.querySelectorAll('.application-checkbox, .application-checkbox-mobile').forEach(cb => {
                    cb.checked = isChecked;
                });
                updateBulkActionsBar();
            });
        }

        // Individual checkbox handlers
        root.addEventListener('change', (e) => {
            if (e.target.classList && (e.target.classList.contains('application-checkbox') || e.target.classList.contains('application-checkbox-mobile'))) {
                const val = e.target.value;
                const isChecked = e.target.checked;
                root.querySelectorAll(`.application-checkbox[value="${val}"], .application-checkbox-mobile[value="${val}"]`).forEach(cb => {
                    cb.checked = isChecked;
                });
                updateBulkActionsBar();
            }
        });

        // Bulk Approve
        if (bulkApproveBtn) {
            bulkApproveBtn.addEventListener('click', async () => {
                const selectedIds = getSelectedIds();
                if (selectedIds.length === 0) return;

                const confirmed = confirm(`Apakah Anda yakin ingin menyetujui ${selectedIds.length} pengajuan terpilih?`);
                if (!confirmed) return;

                const locale = getCurrentLocale();
                const token = document.querySelector('meta[name="csrf-token"]')?.content;

                try {
                    const response = await fetch(`/${locale}/admin/manageUserKeahlianTambahan/bulk-approve`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ selected_ids: selectedIds })
                    });

                    const result = await response.json();
                    if (result.success) {
                        alert(result.message);
                        window.location.reload();
                    } else {
                        alert(result.message || 'Terjadi kesalahan saat memproses pengajuan.');
                    }
                } catch (error) {
                    console.error('Error bulk approve:', error);
                    alert('Gagal menyetujui pengajuan secara massal.');
                }
            });
        }

        // Bulk Reject Trigger
        if (bulkRejectBtn) {
            bulkRejectBtn.addEventListener('click', () => {
                const selectedIds = getSelectedIds();
                if (selectedIds.length > 0) {
                    window.openBulkRejectModal();
                }
            });
        }

        // Bulk Reject Form Submission
        if (bulkRejectForm) {
            bulkRejectForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const selectedIds = getSelectedIds();
                const keteranganEl = root.querySelector('#bulk_keterangan');
                const keterangan = keteranganEl ? keteranganEl.value : '';

                if (selectedIds.length === 0 || !keterangan.trim()) return;

                const locale = getCurrentLocale();
                const token = document.querySelector('meta[name="csrf-token"]')?.content;

                try {
                    const response = await fetch(`/${locale}/admin/manageUserKeahlianTambahan/bulk-reject`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            selected_ids: selectedIds,
                            keterangan: keterangan
                        })
                    });

                    const result = await response.json();
                    if (result.success) {
                        alert(result.message);
                        window.closeBulkRejectModal();
                        window.location.reload();
                    } else {
                        alert(result.message || 'Terjadi kesalahan saat memproses penolakan.');
                    }
                } catch (error) {
                    console.error('Error bulk reject:', error);
                    alert('Gagal menolak pengajuan secara massal.');
                }
            });
        }
    });

})();
