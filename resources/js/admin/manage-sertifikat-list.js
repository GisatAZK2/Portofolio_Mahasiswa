function initSertifikatListPage() {
    const container = document.getElementById('sertifikat-page-container');
    if (!container) return;

    // Guard: cegah initSertifikatListPage() jalan dobel dalam satu page load
    // (mis. karena DOMContentLoaded & turbo:load sama-sama fire)
    if (container.dataset.sertifikatInitialized === 'true') return;
    container.dataset.sertifikatInitialized = 'true';

    // ===== FILTER TOGGLE (Mobile) =====
    const filterToggleBtn = document.getElementById('filterToggleBtnSertifikat');
    const filterContent = document.getElementById('filterContentSertifikat');
    const filterChevron = document.getElementById('filterChevronSertifikat');

    if (filterToggleBtn) {
        filterToggleBtn.addEventListener('click', () => {
            const isHidden = filterContent.classList.contains('hidden');
            filterContent.classList.toggle('hidden', !isHidden);
            filterChevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
        });
    }

    // ===== BULK SELECT =====
    let selectAllCheckbox = document.getElementById('selectAllCheckboxSertifikat');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtnSertifikat');
    const bulkApproveBtn = document.getElementById('bulkApproveBtnSertifikat');
    const selectedCount = document.getElementById('selectedCountSertifikat');
    const selectedCountApprove = document.getElementById('selectedCountApproveSertifikat');
    const totalSelected = document.getElementById('totalSelectedSertifikat');
    const selectedIdsInput = document.getElementById('selectedIdsInputSertifikat');

    function getCertificateCheckboxes() {
        return container.querySelectorAll('.certificate-checkbox');
    }

    function updateCardBorder(checkbox) {
        const card = checkbox.closest('.certificate-card');
        if (!card) return;

        if (checkbox.checked) {
            card.classList.add('border-indigo-500', 'border-4');
            card.classList.remove('border-2');
        } else {
            card.classList.remove('border-indigo-500', 'border-4');
            card.classList.add('border-2');
        }
    }

    function updateSelectedCount() {
        const allCheckboxes = getCertificateCheckboxes();
        const checked = container.querySelectorAll('.certificate-checkbox:checked');
        const count = checked.length;
        const total = allCheckboxes.length;

        if (selectedCount) selectedCount.textContent = count;
        if (selectedCountApprove) selectedCountApprove.textContent = count;
        if (totalSelected) totalSelected.textContent = count;

        if (bulkDeleteBtn) bulkDeleteBtn.disabled = count === 0;
        if (bulkApproveBtn) bulkApproveBtn.disabled = count === 0;

        if (selectedIdsInput) {
            selectedIdsInput.value = JSON.stringify(Array.from(checked).map(cb => cb.value));
        }

        // Update selectAllCheckbox state
        if (selectAllCheckbox && total > 0) {
            selectAllCheckbox.indeterminate = false;
            if (count === total) {
                selectAllCheckbox.checked = true;
            } else if (count === 0) {
                selectAllCheckbox.checked = false;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            }
        }
    }

    // Setup selectAllCheckbox listener - clean approach (clone untuk buang listener lama)
    if (selectAllCheckbox) {
        const freshSelectAll = selectAllCheckbox.cloneNode(true);
        selectAllCheckbox.parentNode.replaceChild(freshSelectAll, selectAllCheckbox);
        selectAllCheckbox = freshSelectAll; // reassign, bukan redeclare

        selectAllCheckbox.addEventListener('change', function () {
            const isChecked = this.checked;
            getCertificateCheckboxes().forEach(cb => {
                cb.checked = isChecked;
                updateCardBorder(cb);
            });
            updateSelectedCount();
        }, false);
    }

    // Setup individual checkbox listeners
    function setupCheckboxListeners() {
        getCertificateCheckboxes().forEach(cb => {
            // Hapus semua event listener lama
            const newCb = cb.cloneNode(true);
            cb.parentNode.replaceChild(newCb, cb);

            // Setup ulang dengan listener baru
            newCb.addEventListener('change', function () {
                updateCardBorder(this);
                updateSelectedCount();
            }, false);

            // Update visual state
            updateCardBorder(newCb);
        });
    }

    setupCheckboxListeners();
    updateSelectedCount();

    // ===== BULK DELETE =====
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            const checkedCount = container.querySelectorAll('.certificate-checkbox:checked').length;
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
                const ids = Array.from(container.querySelectorAll('.certificate-checkbox:checked')).map(cb => cb.value);
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
            const checkedCount = container.querySelectorAll('.certificate-checkbox:checked').length;
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
                const ids = Array.from(container.querySelectorAll('.certificate-checkbox:checked')).map(cb => cb.value);
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
    container.querySelectorAll('.approve-btn').forEach(button => {
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
    container.querySelectorAll('.reject-btn').forEach(button => {
        button.addEventListener('click', function () {
            const modal = container.querySelector(`#rejectModal-${this.dataset.id}`);
            if (modal) modal.classList.remove('hidden');
        });
    });
    container.querySelectorAll('.cancel-reject').forEach(button => {
        button.addEventListener('click', function () {
            const fixed = this.closest('.fixed');
            if (fixed && container.contains(fixed)) fixed.classList.add('hidden');
        });
    });
    // Handle outside clicks only for modals inside this container
    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('fixed') && container.contains(e.target)) {
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
}

// Export untuk dipanggil dari app.js
window.initSertifikatListPage = initSertifikatListPage;