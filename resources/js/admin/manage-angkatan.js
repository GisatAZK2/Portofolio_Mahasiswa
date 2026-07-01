/**
 * admin/manage-angkatan.js
 * Halaman Kelola Angkatan: bulk delete, confirm delete, CRUD AJAX.
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

    window.toggleAllAngkatan = function (source) {
        document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.checked = source.checked;
        });
        updateSelectedIds();
    };

    window.confirmBulkDeleteAngkatan = function () {
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
            title: 'Hapus Angkatan Terpilih?',
            text: `${selectedIdsArray.length} angkatan akan dihapus permanen.`,
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

    window.openEditModalAngkatan = function (angkatan) {
        const editNama = document.getElementById('edit_nama');
        const editMasuk = document.getElementById('edit_masuk');
        const editKeluar = document.getElementById('edit_keluar');
        const editForm = document.getElementById('editForm');
        if (!editNama || !editMasuk || !editKeluar || !editForm) return;

        editNama.value = angkatan.nama_angkatan;
        editMasuk.value = angkatan.tahun_masuk.split(' ')[0];
        editKeluar.value = angkatan.tahun_keluar ? angkatan.tahun_keluar.split(' ')[0] : '';

        const locale = document.querySelector('html').getAttribute('lang') || 'id';
        editForm.action = `/${locale}/admin/manageAngkatan/edit?id=${angkatan.id}`;
        editForm.method = 'POST'; // karena ada @method('PATCH') di dalam form

        document.getElementById('editModal').classList.remove('hidden');
    };

    window.closeModalAngkatan = function () {
        document.getElementById('editModal').classList.add('hidden');
    };

    window.openDeleteModalAngkatan = function (id, name) {
        deleteId = id;
        document.getElementById('deleteName').textContent = name;
        const deleteForm = document.getElementById('deleteForm');
        const locale = document.querySelector('html').getAttribute('lang') || 'id';
        deleteForm.action = `/${locale}/admin/manageAngkatan/DeleteAngkatan?id=${id}`;
        deleteForm.method = 'POST'; // dengan @method('DELETE')
        document.getElementById('deleteModal').classList.remove('hidden');
    };

    window.closeDeleteModalAngkatan = function () {
        document.getElementById('deleteModal').classList.add('hidden');
        deleteId = null;
    };

    // Tutup modal saat klik di luar
    document.addEventListener('click', function (event) {
        const editModal = document.getElementById('editModal');
        const deleteModal = document.getElementById('deleteModal');
        if (event.target === editModal) {
            window.closeModalAngkatan();
        }
        if (event.target === deleteModal) {
            window.closeDeleteModalAngkatan();
        }
    });

    // Inisialisasi checkbox dan state setelah DOM siap
    function initAngkatanPage() {
        if (!document.getElementById('bulkDeleteForm')) return; // halaman bukan kelola angkatan
        updateSelectedIds();
        document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.addEventListener('change', updateSelectedIds);
        });
    }

    document.addEventListener('DOMContentLoaded', initAngkatanPage);
    document.addEventListener('turbo:load', initAngkatanPage);

    // Tampilkan popup info halaman (jika fungsi showPageInfo tersedia)
    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('bulkDeleteForm')) {
            if (typeof showPageInfo === 'function') {
                showPageInfo('popup.manage_angkatan');
            }
        }
    });
})();

