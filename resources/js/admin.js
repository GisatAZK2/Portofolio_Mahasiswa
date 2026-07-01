/**
 * resources/js/admin.js  — Admin Sub-Entrypoint
 *
 * Load ini HANYA di halaman admin (via @vite di layout admin atau
 * di Blade admin dengan @push('scripts') / @vite(['resources/js/admin.js'])).
 *
 * Tidak perlu import Alpine atau alert.js ulang — sudah di-load oleh app.js.
 * File ini hanya berisi script khusus panel admin.
 */

// ==========================================
// ADMIN: KELOLA USER (daftar-mahasiswa)
// ==========================================
import './admin/manage-users.js';

// ==========================================
// ADMIN: KELOLA ANGKATAN
// ==========================================
import './admin/manage-angkatan.js';

// ==========================================
// ADMIN: KELOLA KEAHLIAN
// ==========================================
import './admin/manage-keahlian.js';

// ==========================================
// ADMIN: KELOLA PRODI / JURUSAN
// ==========================================
import './admin/manage-prodi.js';

// ==========================================
// ADMIN: EDIT SERTIFIKAT
// ==========================================
import './admin/manage-sertifikat.js';

// ==========================================
// ADMIN: EDIT USER
// ==========================================
import './admin/edit-user.js';

// ==========================================
// DOSEN: EDIT USER
// ==========================================
import './admin/dosen-edit-user.js';
