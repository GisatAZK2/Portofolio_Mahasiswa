/**
 * resources/js/admin/create-angkatan.js
 * Admin Create Angkatan — angkatan/views_create_angkatan.blade.php
 */

document.addEventListener('DOMContentLoaded', function () {
    // ===== DATE VALIDATION =====
    const tahunKeluar = document.getElementById('tahun_keluar');
    if (tahunKeluar) {
        tahunKeluar.addEventListener('change', function () {
            let tahunMasuk = document.getElementById('tahun_masuk').value;
            let tahunKeluarVal = this.value;

            if (tahunKeluarVal && tahunKeluarVal < tahunMasuk) {
                alert('Tahun keluar tidak boleh lebih kecil dari tahun masuk');
                this.value = '';
            }
        });
    }

});
