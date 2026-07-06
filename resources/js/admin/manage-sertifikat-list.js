/**
 * resources/js/admin/manage-sertifikat-list.js
 * Admin Manage Sertifikat List — sertifikat.blade.php
 *
 * Session alerts are passed via data-* attributes on #sertifikat-page-container
 */

document.addEventListener('DOMContentLoaded', () => {

    // Guard: hanya jalan di halaman Manage Sertifikat.
    // Tanpa ini, ID seperti #bulkApproveBtn yang kepakai juga di halaman
    // admin lain (mis. Keahlian Tambahan) ikut kena manipulasi (disabled dsb)
    // karena admin.js memuat semua script admin sekaligus di setiap halaman.
    if (!document.getElementById('sertifikat-page-container')) return;

    // ===== FILTER TOGGLE (Mobile) =====
    const filterToggleBtn = document.getElementById('filterToggleBtn');
    const filterContent = document.getElementById('filterContent');
    const filterChevron = document.getElementById('filterChevron');

    if (filterToggleBtn) {
        filterToggleBtn.addEventListener('click', () => {
            const isHidden = filterContent.classList.contains('hidden');
            filterContent.classList.toggle('hidden', !isHidden);
            filterChevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
        });
    }

    // ===== BULK SELECT =====
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const certificateCheckboxes = document.querySelectorAll('.certificate-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');
    const selectedCount = document.getElementById('selectedCount');
    const selectedCountApprove = document.getElementById('selectedCountApprove');
    const totalSelected = document.getElementById('totalSelected');
    const selectedIdsInput = document.getElementById('selectedIdsInput');

    function updateCardBorder(checkbox) {
        const card = checkbox.closest('.certificate-card');
        if (checkbox.checked) {
            card.classList.add('border-indigo-500', 'border-4');
            card.classList.remove('border-2');
        } else {
            card.classList.remove('border-indigo-500', 'border-4');
            card.classList.add('border-2');
        }
    }

    function updateSelectedCount() {
        const checked = document.querySelectorAll('.certificate-checkbox:checked');
        const count = checked.length;

        if (selectedCount) selectedCount.textContent = count;
        if (selectedCountApprove) selectedCountApprove.textContent = count;
        if (totalSelected) totalSelected.textContent = count;

        if (bulkDeleteBtn) bulkDeleteBtn.disabled = count === 0;
        if (bulkApproveBtn) bulkApproveBtn.disabled = count === 0;

        if (selectedIdsInput) {
            selectedIdsInput.value = JSON.stringify(Array.from(checked).map(cb => cb.value));
        }

        if (selectAllCheckbox) {
            if (count === certificateCheckboxes.length && count > 0) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else if (count === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.indeterminate = true;
            }
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            certificateCheckboxes.forEach(cb => {
                cb.checked = this.checked;
                updateCardBorder(cb);
            });
            updateSelectedCount();
        });
    }

    certificateCheckboxes.forEach(cb => {
        updateCardBorder(cb);
        cb.addEventListener('change', function () {
            updateCardBorder(this);
            updateSelectedCount();
        });
    });

    // ===== BULK DELETE =====
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            const checkedCount = document.querySelectorAll('.certificate-checkbox:checked').length;
            if (checkedCount === 0) return;

            const confirmed = await Swal.fire({
                title: 'Hapus Sertifikat Terpilih?',
                text: `${checkedCount} sertifikat akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });

            if (!confirmed.isConfirmed) return;

            Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });

            try {
                const ids = Array.from(document.querySelectorAll('.certificate-checkbox:checked')).map(cb => cb.value);
                const locale = document.querySelector('html').getAttribute('lang') || 'id';
                const response = await fetch(`/${locale}/admin/manageSertifikat/bulk-destroy`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ selected_ids: ids })
                });
                const result = await response.json();
                if (result.success) {
                    await Swal.fire({ icon: 'success', title: 'Berhasil!', text: result.message, showConfirmButton: false, timer: 2000 });
                    window.location.reload();
                } else throw new Error(result.message);
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: error.message || 'Terjadi kesalahan.', confirmButtonColor: '#dc2626' });
            }
        });
    }

    // ===== BULK APPROVE =====
    if (bulkApproveBtn) {
        bulkApproveBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            const checkedCount = document.querySelectorAll('.certificate-checkbox:checked').length;
            if (checkedCount === 0) return;

            const confirmed = await Swal.fire({
                title: 'Setujui Sertifikat Terpilih?',
                text: `${checkedCount} sertifikat akan diproses. Hanya yang "Sedang Di Ajukan" yang disetujui.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });

            if (!confirmed.isConfirmed) return;

            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });

            try {
                const ids = Array.from(document.querySelectorAll('.certificate-checkbox:checked')).map(cb => cb.value);
                const locale = document.querySelector('html').getAttribute('lang') || 'id';
                const response = await fetch(`/${locale}/admin/manageSertifikat/bulk-approve`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ selected_ids: ids })
                });
                const result = await response.json();
                if (result.success) {
                    await Swal.fire({ icon: 'success', title: 'Berhasil!', text: result.message, showConfirmButton: false, timer: 2000 });
                    window.location.reload();
                } else throw new Error(result.message);
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: error.message || 'Terjadi kesalahan.', confirmButtonColor: '#dc2626' });
            }
        });
    }

    // ===== APPROVE SINGLE =====
    document.querySelectorAll('.approve-btn').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();
            const confirmed = await Swal.fire({
                title: 'Terima Sertifikat?',
                text: 'Status akan berubah menjadi "Di Terima".',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Terima',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });
            if (confirmed.isConfirmed) {
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                this.closest('form').submit();
            }
        });
    });

    // ===== REJECT MODAL =====
    document.querySelectorAll('.reject-btn').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById(`rejectModal-${this.dataset.id}`).classList.remove('hidden');
        });
    });
    document.querySelectorAll('.cancel-reject').forEach(button => {
        button.addEventListener('click', function () {
            this.closest('.fixed').classList.add('hidden');
        });
    });
    window.addEventListener('click', function (e) {
        if (e.target.classList.contains('fixed')) {
            e.target.classList.add('hidden');
        }
    });

    // ===== SESSION ALERTS (via data-* attributes) =====
    const pageContainer = document.getElementById('sertifikat-page-container');
    if (pageContainer) {
        const successMsg = pageContainer.dataset.sessionSuccess;
        const errorMsg = pageContainer.dataset.sessionError;
        const infoMsg = pageContainer.dataset.sessionInfo;

        if (successMsg) {
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: successMsg, showConfirmButton: false, timer: 3000, timerProgressBar: true });
        }
        if (errorMsg) {
            Swal.fire({ icon: 'error', title: 'Gagal!', text: errorMsg, confirmButtonColor: '#dc2626' });
        }
        if (infoMsg) {
            Swal.fire({ icon: 'info', title: 'Informasi', text: infoMsg, confirmButtonColor: '#3b82f6' });
        }
    }

    updateSelectedCount();

    // ===== PAGE INFO =====
    if (typeof showPageInfo === 'function') {
        showPageInfo("popup.admin_sertifikat");
    }
});