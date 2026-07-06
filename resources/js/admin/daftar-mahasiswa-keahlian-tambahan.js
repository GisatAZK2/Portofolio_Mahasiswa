/**
 * resources/js/admin/daftar-mahasiswa-keahlian-tambahan.js
 * Admin Daftar Mahasiswa Keahlian Tambahan — daftar-mahasiswa-keahlian-tambahan.blade.php
 */

function getCurrentLocale() {
    const path = window.location.pathname;
    const match = path.match(/^\/(id|en)\//);
    return match ? match[1] : 'id'; // default ke id
}

window.openApproveModal = function(userId, userName) {
    const locale = getCurrentLocale();
    document.getElementById('approveUserName').textContent = userName;
    document.getElementById('approveForm').action = `/${locale}/admin/manageUserKeahlianTambahan/approve?user_id=${userId}`;
    document.getElementById('approveModal').classList.remove('hidden');
};

window.closeApproveModal = function() {
    document.getElementById('approveModal').classList.add('hidden');
};

window.openRejectModal = function(userId, userName) {
    const locale = getCurrentLocale();
    document.getElementById('rejectUserName').textContent = userName;
    document.getElementById('rejectForm').action = `/${locale}/admin/manageUserKeahlianTambahan/reject?user_id=${userId}`;
    document.getElementById('rejectModal').classList.remove('hidden');
};

window.closeRejectModal = function() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('keterangan').value = '';
};

// Bulk reject modal triggers
window.openBulkRejectModal = function() {
    document.getElementById('bulkRejectModal').classList.remove('hidden');
};

window.closeBulkRejectModal = function() {
    document.getElementById('bulkRejectModal').classList.add('hidden');
    document.getElementById('bulk_keterangan').value = '';
};

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    const approveModal = document.getElementById('approveModal');
    const rejectModal = document.getElementById('rejectModal');
    const bulkRejectModal = document.getElementById('bulkRejectModal');
    
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
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCountText = document.getElementById('selectedCountText');
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');
    const bulkRejectBtn = document.getElementById('bulkRejectBtn');
    const bulkRejectForm = document.getElementById('bulkRejectForm');

    function getSelectedIds() {
        const checkboxes = document.querySelectorAll('.application-checkbox:checked, .application-checkbox-mobile:checked');
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
            const allCheckboxes = document.querySelectorAll('.application-checkbox, .application-checkbox-mobile');
            const checkedCount = document.querySelectorAll('.application-checkbox:checked, .application-checkbox-mobile:checked').length;
            
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
            document.querySelectorAll('.application-checkbox, .application-checkbox-mobile').forEach(cb => {
                cb.checked = isChecked;
            });
            updateBulkActionsBar();
        });
    }

    // Individual checkbox handlers
    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('application-checkbox') || e.target.classList.contains('application-checkbox-mobile')) {
            // Sync checkbox state between desktop and mobile if they represent the same ID
            const val = e.target.value;
            const isChecked = e.target.checked;
            document.querySelectorAll(`.application-checkbox[value="${val}"], .application-checkbox-mobile[value="${val}"]`).forEach(cb => {
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
            const keterangan = document.getElementById('bulk_keterangan').value;

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
