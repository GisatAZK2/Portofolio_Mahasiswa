# Ekstrak Semua Inline Script dari Admin Blade Views ke File JS

Memindahkan semua `<script>` inline dari Blade views admin ke file `.js` terpisah di `resources/js/admin/`, lalu me-load melalui `resources/js/admin.js` yang sudah di-import via Vite.

## User Review Required

> [!IMPORTANT]
> Beberapa Blade views mengandung **Blade directives di dalam `<script>`** (contoh: `@if(session('success'))`, `@json()`, `{{ route(...) }}`). Ini **tidak bisa** dipindahkan langsung ke file JS karena Blade directives hanya diproses di `.blade.php`. Solusi: pass data dari Blade ke JS via `data-*` attribute atau `<script>` tag kecil yang set `window.__DATA__` variable, lalu file JS membaca dari situ.

> [!WARNING]
> File `views_add_user.blade.php` mengandung `{{ route('admin.users.importExcel') }}` di dalam script — ini akan ditangani dengan `<meta>` tag atau `data-*` attribute di Blade, lalu dibaca dari JS.

## Proposed Changes

### File JS Baru yang Akan Dibuat

| # | File JS Baru | Dari Blade View | Catatan |
|---|---|---|---|
| 1 | `dashboard.js` | `index.blade.php` | Skeleton loader, email toggle, section toggle, Chart.js sparklines, pageInfo |
| 2 | `learning-corner.js` | `learning-corner.blade.php` | Delete confirm, session alert, pageInfo |
| 3 | `daftar-mahasiswa-keahlian-tambahan.js` | `daftar-mahasiswa-keahlian-tambahan.blade.php` | Approve/reject modals |
| 4 | `manage-project.js` | `project.blade.php` | Filter, bulk delete, single delete, checkbox, pageInfo |
| 5 | `manage-sertifikat-list.js` | `sertifikat.blade.php` | Filter, bulk select/delete/approve, single approve, reject modal, session alerts, pageInfo |
| 6 | `create-angkatan.js` | `angkatan/views_create_angkatan.blade.php` | Date validation, pageInfo |
| 7 | `detail-angkatan.js` | `angkatan/views_detail_angkatan.blade.php` | Client-side pagination, filter, email toggle, pageInfo. **Blade data via `data-*`** |
| 8 | `detail-keahlian.js` | `keahlian/views_detail_keahlian.blade.php` | Sama seperti detail-angkatan, pageInfo. **Blade data via `data-*`** |
| 9 | `detail-prodi.js` | `prodi/views_detail_prodi.blade.php` | Sama seperti detail-angkatan, pageInfo. **Blade data via `data-*`** |
| 10 | `notifications-list.js` | `notifikasi/views-notifications.blade.php` | Bulk select/delete, checkbox |
| 11 | `create-notification.js` | `notifikasi/views_create_notification.blade.php` | User loader, selection. **Route URL via `data-*`** |
| 12 | `create-project-init.js` | `projects/views_create_project.blade.php` | Init function call |
| 13 | `edit-project-init.js` | `projects/views_edit_project.blade.php` | Init function call |
| 14 | `create-sertifikat.js` | `sertifikat/views_create_sertifikat.blade.php` | Filter, user select, file upload, permanent toggle, drag & drop, pageInfo |
| 15 | `add-user.js` | `user/views_add_user.blade.php` | Role selection, Excel import, avatar, photo upload, pageInfo. **Route URL via `data-*`** |

### Penanganan Blade Directives dalam Script

Untuk file yang mengandung Blade syntax di dalam `<script>`:

1. **Session alerts** (`@if(session('success'))`) → Tetap satu `<script>` kecil inline di Blade yang hanya set `window.__ADMIN_SESSION__` lalu JS file membaca dari situ. Atau gunakan `data-*` pada `<div id="flash-message">` (sudah ada di Layout).
2. **`@json()` / `{!! json_encode() !!}`** → Blade set `<script>window.__PAGE_DATA__ = @json($data);</script>` minimal, lalu JS baca dari `window.__PAGE_DATA__`.
3. **`{{ route() }}`** → Pass URL via `data-*` attribute pada container element.
4. **`old()` / `@if(old())`** → Pass via `data-*` attribute.

---

### Blade Views yang Akan Dimodifikasi

Setiap Blade view akan:
- Menghapus semua `<script>...</script>` inline
- Menambahkan `data-*` attributes jika diperlukan untuk pass data Blade ke JS
- Menambahkan satu `<script>` kecil untuk `window.__PAGE_DATA__` jika data Blade kompleks (seperti `@json()`)

#### [MODIFY] [index.blade.php](file:///c:/Users/Gisat/Portofolio_Mahasiswa/resources/views/admin/index.blade.php)
- Hapus 3 blok `<script>` (line 568-585, 587-741, 744-748)
- Pertahankan `<script src="chart.js CDN">` (external CDN, bukan inline)

#### [MODIFY] [learning-corner.blade.php](file:///c:/Users/Gisat/Portofolio_Mahasiswa/resources/views/admin/learning-corner.blade.php)
- Hapus 2 blok `<script>` (line 121-148, 151-155)
- Session success dihandle via flash-message div yang sudah ada di Layout

#### [MODIFY] [daftar-mahasiswa-keahlian-tambahan.blade.php](file:///c:/Users/Gisat/Portofolio_Mahasiswa/resources/views/admin/daftar-mahasiswa-keahlian-tambahan.blade.php)
- Hapus 1 blok `<script>` (line 393-434)

#### [MODIFY] [project.blade.php](file:///c:/Users/Gisat/Portofolio_Mahasiswa/resources/views/admin/project.blade.php)
- Hapus 2 blok `<script>` (line 369-516, 518-524)

#### [MODIFY] [sertifikat.blade.php](file:///c:/Users/Gisat/Portofolio_Mahasiswa/resources/views/admin/sertifikat.blade.php)
- Hapus 2 blok `<script>` (line 476-703, 705-709)
- Session alerts via Blade `data-*` attributes

#### [MODIFY] [views_create_angkatan.blade.php](file:///c:/Users/Gisat/Portofolio_Mahasiswa/resources/views/admin/angkatan/views_create_angkatan.blade.php)
- Hapus 2 blok `<script>` (line 65-76, 79-83)

#### [MODIFY] [views_detail_angkatan.blade.php](file:///c:/Users/Gisat/Portofolio_Mahasiswa/resources/views/admin/angkatan/views_detail_angkatan.blade.php)
- Hapus 2 blok `<script>` (line 323-679, 682-686)
- `{!! json_encode() !!}` → `<script>window.__PAGE_DATA__ = {..}</script>` minimal

#### [MODIFY] [views_detail_keahlian.blade.php](file:///c:/Users/Gisat/Portofolio_Mahasiswa/resources/views/admin/keahlian/views_detail_keahlian.blade.php)
- Hapus 1 blok `<script>` (line 284-615)
- Sama handling data seperti detail-angkatan

#### [MODIFY] [views_detail_prodi.blade.php](file:///c:/Users/Gisat/Portofolio_Mahasiswa/resources/views/admin/prodi/views_detail_prodi.blade.php)
- Hapus 1 blok `<script>` (line 283-614)
- Sama handling data seperti detail-angkatan

#### [MODIFY] [views-notifications.blade.php](file:///c:/Users/Gisat/Portofolio_Mahasiswa/resources/views/admin/notifikasi/views-notifications.blade.php)
- Hapus 1 blok `<script>` (line 246-357)

#### [MODIFY] [views_create_notification.blade.php](file:///c:/Users/Gisat/Portofolio_Mahasiswa/resources/views/admin/notifikasi/views_create_notification.blade.php)
- Hapus 1 blok `<script>` (line 136-253)
- Route URL via `data-*` attribute

#### [MODIFY] [views_create_project.blade.php](file:///c:/Users/Gisat/Portofolio_Mahasiswa/resources/views/admin/projects/views_create_project.blade.php)
- Hapus 1 blok `<script>` (line 288-294)

#### [MODIFY] [views_edit_project.blade.php](file:///c:/Users/Gisat/Portofolio_Mahasiswa/resources/views/admin/projects/views_edit_project.blade.php)
- Hapus 1 blok `<script>` (line 313-320)

#### [MODIFY] [views_create_sertifikat.blade.php](file:///c:/Users/Gisat/Portofolio_Mahasiswa/resources/views/admin/sertifikat/views_create_sertifikat.blade.php)
- Hapus 2 blok `<script>` (line 403-609, 612-616)

#### [MODIFY] [views_add_user.blade.php](file:///c:/Users/Gisat/Portofolio_Mahasiswa/resources/views/admin/user/views_add_user.blade.php)
- Hapus 2 blok `<script>` (line 1057-1645, 1647-1651)
- Route URL `importExcel` via `data-*` attribute
- `old('role')` via `data-*` attribute
- Pertahankan `<script src="xlsx CDN">` (external CDN)

---

### [MODIFY] [admin.js](file:///c:/Users/Gisat/Portofolio_Mahasiswa/resources/js/admin.js)
- Tambahkan import untuk semua 15 file JS baru

## Verification Plan

### Manual Verification
- Grep semua `<script>` tags di folder views admin untuk memastikan tidak ada inline script tersisa (kecuali CDN external dan data passthrough minimal)
- Pastikan `npm run build` / `npm run dev` berjalan tanpa error
