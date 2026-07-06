/**
 * resources/js/admin/learning-corner.js
 * Admin Learning Corner — learning-corner.blade.php
 */

document.addEventListener('DOMContentLoaded', () => {
    // ===== DELETE CONFIRMATION =====
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

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
                this.closest('form').submit();
            }
        });
    });

    // ===== SESSION ALERTS =====
    // Session success is handled by the flash-message div in Layout
    // Check for page-specific session data
    const pageContainer = document.getElementById('learning-corner-container');
    if (pageContainer) {
        const successMsg = pageContainer.dataset.sessionSuccess;
        if (successMsg && typeof showSuccessAlert === 'function') {
            showSuccessAlert(successMsg);
        }
    }

    // ===== PAGE INFO =====
    if (typeof showPageInfo === 'function') {
        showPageInfo("popup.admin_learning_corner");
    }
});
