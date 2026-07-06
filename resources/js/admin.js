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

// ==========================================
// ADMIN: DASHBOARD (index.blade.php)
// ==========================================
import './admin/dashboard.js';

// ==========================================
// ADMIN: LEARNING CORNER
// ==========================================
import './admin/learning-corner.js';

// ==========================================
// ADMIN: DAFTAR MAHASISWA KEAHLIAN TAMBAHAN
// ==========================================
import './admin/daftar-mahasiswa-keahlian-tambahan.js';

// ==========================================
// ADMIN: MANAGE PROJECT LIST (project.blade.php)
// ==========================================
import './admin/manage-project.js';

// ==========================================
// ADMIN: MANAGE SERTIFIKAT LIST (sertifikat.blade.php)
// ==========================================
import './admin/manage-sertifikat-list.js';

// ==========================================
// ADMIN: CREATE ANGKATAN
// ==========================================
import './admin/create-angkatan.js';

// ==========================================
// ADMIN: DETAIL ANGKATAN
// ==========================================
import './admin/detail-angkatan.js';

// ==========================================
// ADMIN: DETAIL KEAHLIAN
// ==========================================
import './admin/detail-keahlian.js';

// ==========================================
// ADMIN: DETAIL PRODI
// ==========================================
import './admin/detail-prodi.js';

// ==========================================
// ADMIN: NOTIFICATIONS LIST
// ==========================================
import './admin/notifications-list.js';

// ==========================================
// ADMIN: CREATE NOTIFICATION
// ==========================================
import './admin/create-notification.js';

// ==========================================
// ADMIN: CREATE PROJECT INIT
// ==========================================
import './admin/create-project-init.js';

// ==========================================
// ADMIN: EDIT PROJECT INIT
// ==========================================
import './admin/edit-project-init.js';

// ==========================================
// ADMIN: CREATE SERTIFIKAT
// ==========================================
import './admin/create-sertifikat.js';

// ==========================================
// ADMIN: ADD USER
// ==========================================
import './admin/add-user.js';
