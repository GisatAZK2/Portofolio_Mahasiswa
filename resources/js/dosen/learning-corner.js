import Swal from 'sweetalert2';

function initLearningCornerPage() {
    const pageRoot = document.querySelector('[data-dosen-learning-corner]') || document.querySelector('.delete-btn');
    if (!pageRoot) return;

    document.querySelectorAll('.delete-btn').forEach((button) => {
        button.addEventListener('click', async (event) => {
            event.preventDefault();
            const confirmed = await window.showConfirmAlert?.({
                title: 'Hapus Entri Learning Corner?',
                text: 'Catatan ini akan dihapus permanen dan tidak bisa dikembalikan.',
                icon: 'warning',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
            });

            if (confirmed) {
                window.showLoading?.('Menghapus catatan...');
                button.closest('form').submit();
            }
        });
    });

    if (typeof window.showPageInfo === 'function') {
        window.showPageInfo('popup.dosen_learning_corner');
    }
}

document.addEventListener('DOMContentLoaded', initLearningCornerPage);
document.addEventListener('turbo:load', initLearningCornerPage);
