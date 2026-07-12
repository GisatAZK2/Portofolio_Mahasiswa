function initDosenDaftarMahasiswaPage() {
    const pageRoot = document.querySelector('[data-dosen-daftar-mahasiswa]') || document.getElementById('updateModal');
    if (!pageRoot) return;

    let currentDeleteForm = null;

    window.openUpdateModal = function (userId, userName) {
        const modal = document.getElementById('updateModal');
        const form = document.getElementById('updateForm');
        const modalName = document.getElementById('modalNama');
        if (modal && form && modalName) {
            modalName.textContent = userName;
            form.action = `/user/${userId}/update-status`;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeUpdateModal = function () {
        const modal = document.getElementById('updateModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    };

    window.closeDeleteModal = function () {
        const modal = document.getElementById('deleteModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        currentDeleteForm = null;
    };

    const statusSelect = document.getElementById('status_pengajuan');
    if (statusSelect) {
        statusSelect.addEventListener('change', function () {
            const field = document.getElementById('keteranganTolakField');
            const textarea = document.getElementById('keterangan_tolak');
            if (this.value === 'Di Tolak') {
                field?.classList.remove('hidden');
                if (textarea) textarea.required = true;
            } else {
                field?.classList.add('hidden');
                if (textarea) textarea.required = false;
            }
        });
    }

    document.querySelectorAll('.delete-btn').forEach((btn) => {
        btn.addEventListener('click', function () {
            currentDeleteForm = this.closest('form');
            const userName = this.getAttribute('data-name');
            const deleteUserName = document.getElementById('deleteUserName');
            const deleteModal = document.getElementById('deleteModal');
            if (deleteUserName) deleteUserName.textContent = userName;
            if (deleteModal) deleteModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });

    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function () {
            if (currentDeleteForm) currentDeleteForm.submit();
        });
    }

    document.querySelectorAll('#updateModal, #deleteModal').forEach((modal) => {
        modal.addEventListener('click', function (event) {
            if (event.target === this) {
                if (this.id === 'updateModal') window.closeUpdateModal();
                else window.closeDeleteModal();
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            window.closeUpdateModal();
            window.closeDeleteModal();
        }
    });

    if (typeof window.showPageInfo === 'function') {
        window.showPageInfo('popup.dosen_daftar_mahasiswa');
    }
}

document.addEventListener('DOMContentLoaded', initDosenDaftarMahasiswaPage);
document.addEventListener('turbo:load', initDosenDaftarMahasiswaPage);
