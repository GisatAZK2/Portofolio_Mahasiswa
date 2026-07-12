import Swal from 'sweetalert2';

function updateDosenCertificateSelectionState() {
    const dosenCheckboxes = document.querySelectorAll('.dosenCertificateCheckbox');
    const dosenCheckedBoxes = document.querySelectorAll('.dosenCertificateCheckbox:checked');
    const dosenCheckedCount = dosenCheckedBoxes.length;

    // Sinkronkan kedua counter di halaman (tombol header & bar "Pilih Semua")
    const dosenSelectedCountEl = document.getElementById('dosenSelectedCount');
    if (dosenSelectedCountEl) dosenSelectedCountEl.textContent = dosenCheckedCount;

    const dosenTotalSelectedEl = document.getElementById('dosenTotalSelected');
    if (dosenTotalSelectedEl) dosenTotalSelectedEl.textContent = dosenCheckedCount;

    // Enable/disable tombol hapus terpilih
    const dosenBulkDeleteBtn = document.getElementById('dosenBulkDeleteBtn');
    if (dosenBulkDeleteBtn) dosenBulkDeleteBtn.disabled = dosenCheckedCount === 0;

    // Simpan id terpilih ke hidden input (jaga-jaga jika form native dipakai)
    const dosenSelectedIdsInput = document.getElementById('dosenSelectedIdsInput');
    if (dosenSelectedIdsInput) {
        dosenSelectedIdsInput.value = Array.from(dosenCheckedBoxes).map((cb) => cb.value).join(',');
    }

    // Sinkronkan status checkbox "Pilih Semua" (checked / indeterminate / kosong)
    const dosenSelectAllCheckbox = document.getElementById('dosenSelectAllCheckbox');
    if (dosenSelectAllCheckbox) {
        if (dosenCheckboxes.length === 0) {
            dosenSelectAllCheckbox.checked = false;
            dosenSelectAllCheckbox.indeterminate = false;
        } else if (dosenCheckedCount === 0) {
            dosenSelectAllCheckbox.checked = false;
            dosenSelectAllCheckbox.indeterminate = false;
        } else if (dosenCheckedCount === dosenCheckboxes.length) {
            dosenSelectAllCheckbox.checked = true;
            dosenSelectAllCheckbox.indeterminate = false;
        } else {
            dosenSelectAllCheckbox.checked = false;
            dosenSelectAllCheckbox.indeterminate = true;
        }
    }
}

function bindDosenCertificateCheckboxEvents() {
    const dosenSelectAllCheckbox = document.getElementById('dosenSelectAllCheckbox');
    if (dosenSelectAllCheckbox) {
        dosenSelectAllCheckbox.addEventListener('change', function () {
            document.querySelectorAll('.dosenCertificateCheckbox').forEach((cb) => {
                cb.checked = dosenSelectAllCheckbox.checked;
            });
            updateDosenCertificateSelectionState();
        });
    }

    document.querySelectorAll('.dosenCertificateCheckbox').forEach((cb) => {
        cb.addEventListener('change', updateDosenCertificateSelectionState);
    });
}

function initDosenSertifikatPage() {
    const pageRoot = document.querySelector('[data-dosen-sertifikat-list]') || document.getElementById('dosenBulkDeleteForm');
    if (!pageRoot) return;

    bindDosenCertificateCheckboxEvents();
    updateDosenCertificateSelectionState();

    const dosenBulkDeleteBtn = document.getElementById('dosenBulkDeleteBtn');
    if (dosenBulkDeleteBtn) {
        dosenBulkDeleteBtn.addEventListener('click', async function (event) {
            event.preventDefault();
            const checkedCount = document.querySelectorAll('.dosenCertificateCheckbox:checked').length;

            if (checkedCount === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Ada Data Dipilih',
                    text: 'Silakan pilih sertifikat yang ingin dihapus.',
                    confirmButtonColor: '#3b82f6',
                });
                return;
            }

            const confirmed = await Swal.fire({
                title: 'Hapus Sertifikat Terpilih?',
                text: `${checkedCount} sertifikat akan dihapus permanen dan tidak bisa dikembalikan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus Semua',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            });

            if (confirmed.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => Swal.showLoading(),
                });

                try {
                    const formData = new FormData();
                    const ids = Array.from(document.querySelectorAll('.dosenCertificateCheckbox:checked')).map((cb) => cb.value);
                    formData.append('_method', 'DELETE');
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
                    ids.forEach((id) => formData.append('selected_ids[]', id));

                    // Ambil URL dari action form (sudah locale-aware via route()),
                    // jangan hardcode prefix '/id/' karena locale aktif bisa 'en'.
                    const dosenBulkDeleteForm = document.getElementById('dosenBulkDeleteForm');
                    const dosenBulkDeleteUrl = dosenBulkDeleteForm?.action || window.location.pathname;

                    const response = await fetch(dosenBulkDeleteUrl, {
                        method: 'POST',
                        body: formData,
                        headers: { 'Accept': 'application/json' },
                    });

                    if (!response.ok) {
                        throw new Error(`Request gagal (status ${response.status})`);
                    }

                    const contentType = response.headers.get('content-type') || '';
                    if (!contentType.includes('application/json')) {
                        throw new Error('Server tidak mengembalikan JSON. Sesi mungkin habis, silakan muat ulang halaman dan login kembali.');
                    }

                    const result = await response.json();

                    if (result.success) {
                        await Swal.fire({ icon: 'success', title: 'Berhasil!', text: result.message, showConfirmButton: false, timer: 2000 });
                        window.location.reload();
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: error.message || 'Terjadi kesalahan saat menghapus data.', confirmButtonColor: '#dc2626' });
                }
            }
        });
    }

    document.querySelectorAll('.delete-btn').forEach((button) => {
        button.addEventListener('click', async function (event) {
            event.preventDefault();
            const confirmed = await Swal.fire({
                title: 'Hapus Sertifikat?',
                text: 'Sertifikat ini akan dihapus permanen dan tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            });

            if (confirmed.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => Swal.showLoading(),
                });
                this.closest('form').submit();
            }
        });
    });

    document.querySelectorAll('.approve-btn').forEach((button) => {
        button.addEventListener('click', async function (event) {
            event.preventDefault();
            const confirmed = await Swal.fire({
                title: 'Terima Sertifikat?',
                text: 'Sertifikat akan diterima dan status akan menjadi "Di Terima".',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Terima',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            });
            if (confirmed.isConfirmed) {
                Swal.fire({ title: 'Memproses...', text: 'Mohon tunggu sebentar', allowOutsideClick: false, showConfirmButton: false, willOpen: () => Swal.showLoading() });
                this.closest('form').submit();
            }
        });
    });

    document.querySelectorAll('.reject-btn').forEach((button) => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const modal = document.getElementById(`rejectModal-${id}`);
            if (modal) modal.style.display = 'block';
        });
    });

    document.querySelectorAll('.cancel-reject').forEach((button) => {
        button.addEventListener('click', function () {
            const modal = this.closest('.fixed');
            if (modal) modal.style.display = 'none';
        });
    });

    window.addEventListener('click', function (event) {
        if (event.target.classList.contains('fixed')) {
            event.target.style.display = 'none';
        }
    });

    if (typeof window.showPageInfo === 'function') {
        window.showPageInfo('popup.dosen_certificates');
    }
}

document.addEventListener('DOMContentLoaded', initDosenSertifikatPage);
document.addEventListener('turbo:load', initDosenSertifikatPage);