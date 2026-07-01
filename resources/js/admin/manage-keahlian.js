/**
 * admin/manage-keahlian.js
 * Halaman Kelola Keahlian: bulk delete, confirm delete.
 */
(function () {
    let selectedIds = [];
    let deleteId = null;

    function updateSelectedIds() {
        selectedIds = [];
        document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
            selectedIds.push(cb.value);
        });
        const selectedIdsInput = document.getElementById('selectedIds');
        if (selectedIdsInput) {
            selectedIdsInput.value = JSON.stringify(selectedIds);
        }
    }

    window.toggleAllKeahlian = function (source) {
        document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.checked = source.checked;
        });
        updateSelectedIds();
    };

    window.confirmBulkDeleteKeahlian = function () {
        updateSelectedIds();
        let selectedIdsArray = [];
        try {
            const val = document.getElementById('selectedIds')?.value;
            if (val) selectedIdsArray = JSON.parse(val);
        } catch (e) { selectedIdsArray = []; }
        if (selectedIdsArray.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak Ada Data Dipilih',
                text: 'Pilih data yang akan dihapus',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }
        Swal.fire({
            title: 'Hapus Keahlian Terpilih?',
            text: `${selectedIdsArray.length} keahlian akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('bulkDeleteForm').submit();
            }
        });
    };

    window.openEditModalKeahlian = function (keahlian) {
        const editNama = document.getElementById('edit_nama');
        const editForm = document.getElementById('editForm');
        if (!editNama || !editForm) return;

        editNama.value = keahlian.nama_keahlian;
        const locale = document.querySelector('html').getAttribute('lang') || 'id';
        editForm.action = `/${locale}/admin/manageKeahlian/edit?id=${keahlian.id_keahlian}`;
        editForm.method = 'POST';

        document.getElementById('editModal').classList.remove('hidden');
    };

    window.closeModalKeahlian = function () {
        document.getElementById('editModal').classList.add('hidden');
    };

    window.closeDeleteModalKeahlian = function () {
        document.getElementById('deleteModal').classList.add('hidden');
        deleteId = null;
    };

    window.handleDeleteKeahlian = async function (id, name) {
        const confirmed = await window.showConfirm?.() ?? confirm('Apakah Anda yakin ingin menghapus keahlian ini?');
        if (!confirmed) return;

        if (window.showLoading) window.showLoading('Menghapus...');

        const locale = document.querySelector('html').getAttribute('lang') || 'id';
        const url = `/${locale}/admin/manageKeahlian/DeleteKeahlian?id=${id}`;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';

        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    };

    // Tutup modal saat klik di luar
    document.addEventListener('click', function (event) {
        const editModal = document.getElementById('editModal');
        const deleteModal = document.getElementById('deleteModal');
        if (event.target === editModal) {
            window.closeModalKeahlian();
        }
        if (event.target === deleteModal) {
            window.closeDeleteModalKeahlian();
        }
    });

    // Inisialisasi checkbox
    function initKeahlianPage() {
        if (!document.getElementById('bulkDeleteForm')) return; // bukan halaman ini
        updateSelectedIds();
        document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.addEventListener('change', updateSelectedIds);
        });
    }

    document.addEventListener('DOMContentLoaded', initKeahlianPage);
    document.addEventListener('turbo:load', initKeahlianPage);

    // Page Info
    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('bulkDeleteForm')) {
            if (typeof showPageInfo === 'function') {
                showPageInfo('popup.manage_keahlian');
            }
        }
    });
})();

