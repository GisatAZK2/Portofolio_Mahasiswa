/**
 * modules/profile/portfolio.js
 * Profile page helpers: edit fields, video preview, date helpers,
 * pendidikan/pengalaman modal CRUD, sertifikat upload, keahlian tambahan.
 */

// ==========================================
// URL PARAM HELPER
// ==========================================
window.updateUrlParam = function (key, value) {
    const url = new URL(window.location.href);
    if (value) url.searchParams.set(key, value);
    else url.searchParams.delete(key);
    window.history.replaceState({}, '', url.toString());
};

// ==========================================
// SCROLL-BACK-TO-SECTION HELPER
// Dipakai sebelum reload/redirect setelah CRUD pendidikan/pengalaman,
// supaya user langsung diarahkan kembali ke section terkait.
// ==========================================
window.markScrollTarget = function (sectionId) {
    try { sessionStorage.setItem('portfolioScrollSection', sectionId); } catch (e) { /* ignore */ }
};

window.scrollToPendingSection = function () {
    let target;
    try { target = sessionStorage.getItem('portfolioScrollSection'); } catch (e) { return; }
    if (!target) return;
    try { sessionStorage.removeItem('portfolioScrollSection'); } catch (e) { /* ignore */ }
    const el = document.getElementById(target);
    if (!el) return;
    requestAnimationFrame(() => {
        setTimeout(() => {
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 150);
    });
};

window.toYMD = function (dateStr) {
    if (!dateStr) return '';
    const s = String(dateStr).trim();
    if (/^\d{2}-\d{2}-\d{4}$/.test(s)) {
        const [d, m, y] = s.split('-');
        return `${y}-${m}-${d}`;
    }
    return s.substring(0, 10);
};

// ==========================================
// TOGGLE EDIT FIELD
// ==========================================
window.toggleEdit = function (field) {
    const displayEl = document.getElementById(field + '-display');
    const inputEl = document.getElementById(field + '-input');
    const customEl = document.getElementById(field + '-custom-input');
    const saveBtn = document.getElementById('save-button-container');
    if (!displayEl || !inputEl) return;
    displayEl.classList.add('hidden');
    inputEl.classList.remove('hidden');
    if (customEl) customEl.classList.remove('hidden');
    const container = document.getElementById(field + '-container');
    if (container) container.classList.add('hidden');
    inputEl.focus();
    if (saveBtn) saveBtn.classList.remove('hidden');
};

window.validateTanggalLahir = function (input) {
    const selectedDate = new Date(input.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    if (selectedDate > today) { alert('Tanggal lahir tidak boleh lebih dari hari ini!'); input.value = ''; }
    else if (selectedDate.getFullYear() < 1900) { alert('Tahun lahir minimal 1900!'); input.value = ''; }
    else {
        const displayEl = document.getElementById('tanggal_lahir-display');
        if (displayEl && input.value) {
            displayEl.textContent = new Date(input.value).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }
    }
};

// ==========================================
// VIDEO PREVIEW
// ==========================================
window.updatePreview = function () {
    const inputEl = document.getElementById('video-input');
    if (!inputEl) return;
    const input = inputEl.value;
    const preview = document.getElementById('videoPreview');
    const iframe = document.getElementById('previewFrame');
    if (!preview || !iframe) return;
    const embedUrl = convertToEmbed(input);
    if (embedUrl) { iframe.src = embedUrl; preview.classList.remove('hidden'); }
    else { preview.classList.add('hidden'); iframe.src = ''; }
};

function convertToEmbed(url) {
    if (!url) return '';
    if (url.includes('watch?v=')) return url.replace('watch?v=', 'embed/');
    if (url.includes('youtu.be/')) return url.replace('youtu.be/', 'youtube.com/embed/');
    return url;
}

window.playVideo = function (element, embedUrl) {
    const iframe = document.createElement('iframe');
    iframe.className = 'absolute inset-0 w-full h-full';
    iframe.src = embedUrl + '?autoplay=1&rel=0';
    iframe.setAttribute('frameborder', '0');
    iframe.allowFullscreen = true;
    element.innerHTML = '';
    element.appendChild(iframe);
};

// ==========================================
// MODAL PENDIDIKAN TOGGLES
// ==========================================
window.openPendidikanModal = function () {
    window.hideUserGuide?.();
    const modal = document.getElementById('modal-pendidikan');
    const content = document.getElementById('modal-pendidikan-content');
    if (!modal || !content) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => { content.classList.remove('scale-95', 'opacity-0'); content.classList.add('scale-100', 'opacity-100'); });
};

window.closePendidikanModal = function () {
    const modal = document.getElementById('modal-pendidikan');
    const content = document.getElementById('modal-pendidikan-content');
    if (!modal || !content) return;
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
    document.getElementById('sekolah-dropdown')?.classList.add('hidden');
};

window.openPengalamanModal = function () {
    window.hideUserGuide?.();
    const modal = document.getElementById('modal-pengalaman');
    const content = document.getElementById('modal-pengalaman-content');
    if (!modal || !content) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => { content.classList.remove('scale-95', 'opacity-0'); content.classList.add('scale-100', 'opacity-100'); });
};

window.closePengalamanModal = function () {
    const modal = document.getElementById('modal-pengalaman');
    const content = document.getElementById('modal-pengalaman-content');
    if (!modal || !content) return;
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
};

// ==========================================
// AJAX PENGALAMAN SUBMIT
// ==========================================
window.handlePengalamanSubmit = async function (event) {
    event.preventDefault();
    const form = event.target;
    const submitBtn = document.getElementById('btn-submit-pengalaman');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const locale = document.querySelector('html').getAttribute('lang') || 'id';
    const mulaiVal = document.getElementById('form-tahun_mulai')?.value;
    const akhirVal = document.getElementById('form-tahun_akhir')?.value;
    const masihChecked = document.getElementById('form-masih_bekerja')?.checked;
    if (!masihChecked && mulaiVal && akhirVal && akhirVal < mulaiVal) {
        document.getElementById('add-pkj-akhir-error')?.classList.remove('hidden');
        window.showErrorAlert?.('Tahun selesai tidak boleh sebelum tahun mulai.');
        return;
    }
    const formData = new FormData(form);
    formData.set('masih_bekerja', masihChecked ? '1' : '0');
    try {
        if (submitBtn) { submitBtn.disabled = true; submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span>Menyimpan...'; }
        const response = await fetch(`/${locale}/pengalaman-kerja/store`, {
            method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: formData
        });
        const result = await response.json();
        if (response.ok && result.success) {
            window.closePengalamanModal();
            window.markScrollTarget('experience-section');
            window.showSuccessAlert?.('Pengalaman kerja berhasil ditambahkan!');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            const errMsg = result.errors ? Object.values(result.errors).flat().join(', ') : (result.message || 'Gagal menyimpan');
            window.showErrorAlert?.(errMsg);
            if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = 'Simpan Pengalaman'; }
        }
    } catch (error) {
        console.error('Error:', error);
        window.showErrorAlert?.('Terjadi kesalahan: ' + error.message);
        if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = 'Simpan Pengalaman'; }
    }
};

// ==========================================
// SEARCH SEKOLAH LOGIC
// ==========================================
window.searchSekolah = function (query) {
    const dropdown = document.getElementById('sekolah-dropdown');
    const list = document.getElementById('sekolah-list');
    if (!dropdown || !list) return;
    if (!query || query.length < 2) { dropdown.classList.add('hidden'); return; }
    const locale = document.querySelector('html').getAttribute('lang') || 'id';
    fetch(`/${locale}/pendidikan/search-sekolah?q=${encodeURIComponent(query)}`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.results?.length) {
                list.innerHTML = data.results.map(s =>
                    `<button type="button" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700" onclick="window.selectSekolah('${s.name.replace(/'/g, "\\'")}', '${s.type || ''}')">${s.name}</button>`
                ).join('');
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        })
        .catch(() => dropdown.classList.add('hidden'));
};

window.selectSekolah = function (name, type) {
    const input = document.getElementById('add-pend-nama_sekolah');
    if (input) input.value = name;
    if (type) {
        const jenjang = document.getElementById('add-pend-jenjang');
        if (jenjang) jenjang.value = type;
    }
    document.getElementById('sekolah-dropdown')?.classList.add('hidden');
};

// ==========================================
// SERTIFIKAT UPLOAD HELPERS
// ==========================================
window.handleSertifikatFile = function (input) {
    const file = input.files[0];
    if (!file) return;
    document.getElementById('sertifikat-upload-placeholder')?.classList.add('hidden');
    document.getElementById('sertifikat-file-preview')?.classList.remove('hidden');
    const nameEl = document.getElementById('sertifikat-file-name');
    if (nameEl) nameEl.textContent = file.name;
    const sizeEl = document.getElementById('sertifikat-file-size');
    if (sizeEl) sizeEl.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
};

window.clearSertifikatFile = function (event) {
    if (event) event.stopPropagation();
    const input = document.getElementById('sertifikat-file-input');
    if (input) input.value = '';
    document.getElementById('sertifikat-upload-placeholder')?.classList.remove('hidden');
    document.getElementById('sertifikat-file-preview')?.classList.add('hidden');
};

// ==========================================
// DETAIL/EDIT PENDIDIKAN MODAL
// ==========================================
window.openDetailPendidikan = async function (id) {
    window.updateUrlParam('pend', id);
    const modal = document.getElementById('modal-detail-pendidikan');
    const content = document.getElementById('modal-detail-pendidikan-content');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const locale = document.querySelector('html').getAttribute('lang') || 'id';
    const TODAY = new Date().toISOString().split('T')[0];
    if (!modal || !content) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => { content.classList.remove('scale-95', 'opacity-0'); content.classList.add('scale-100', 'opacity-100'); });
    ['pend-view-mode', 'pend-view-actions', 'pend-edit-mode', 'pend-edit-actions'].forEach(id => document.getElementById(id)?.classList.add('hidden'));
    document.getElementById('pend-skeleton')?.classList.remove('hidden');
    try {
        const res = await fetch(`/${locale}/pendidikan/detail?id=${id}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
        const json = await res.json();
        document.getElementById('pend-skeleton')?.classList.add('hidden');
        if (!json.success) return;
        const d = json.data;
        if (document.getElementById('pend-edit-id')) document.getElementById('pend-edit-id').value = d.id;
        const jenjangIcons = { 'S1': '🎓', 'S2': '🎓', 'S3': '🎓', 'D1': '📚', 'D2': '📚', 'D3': '📚', 'D4': '📚', 'SMA/SMK': '🏫', 'SMP': '🏫', 'SD': '🏫', 'Kursus/Pelatihan': '📖' };
        const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
        set('pend-view-icon', jenjangIcons[d.jenjang] || '🏛️');
        set('pend-view-nama', d.nama_sekolah || '-');
        set('pend-view-jenjang', d.jenjang || '');
        set('pend-view-jurusan', d.jurusan_sek || '-');
        set('pend-view-periode', `${d.tahun_masuk || '-'} — ${d.masih_kuliah ? 'Sekarang' : (d.tahun_lulus || 'Belum selesai')}`);
        const masukVal = window.toYMD(d.tahun_masuk);
        const lulusVal = window.toYMD(d.tahun_lulus);
        const val = (id, v) => { const el = document.getElementById(id); if (el) el.value = v; };
        val('pend-edit-nama_sekolah', d.nama_sekolah || '');
        val('pend-edit-jenjang', d.jenjang || '');
        val('pend-edit-jurusan_sek', d.jurusan_sek || '');
        const editMasuk = document.getElementById('pend-edit-tahun_masuk');
        if (editMasuk) { editMasuk.value = masukVal; editMasuk.max = TODAY; }
        const lulusInput = document.getElementById('pend-edit-tahun_lulus');
        if (lulusInput) { lulusInput.min = masukVal; lulusInput.value = lulusVal; }
        const masihKuliah = !!d.masih_kuliah;
        const editMasih = document.getElementById('pend-edit-masih_kuliah');
        if (editMasih) editMasih.checked = masihKuliah;
        const lulusField = document.getElementById('pend-edit-tahun-lulus-field');
        if (lulusField) lulusField.style.opacity = masihKuliah ? '0.4' : '1';
        if (lulusInput) lulusInput.disabled = masihKuliah;
        document.getElementById('edit-pend-lulus-error')?.classList.add('hidden');
        window.switchToPendidikanView();
    } catch (e) {
        document.getElementById('pend-skeleton')?.classList.add('hidden');
        console.error(e);
    }
};

window.closeDetailPendidikan = function () {
    window.updateUrlParam('pend', null);
    const modal = document.getElementById('modal-detail-pendidikan');
    const content = document.getElementById('modal-detail-pendidikan-content');
    if (!modal || !content) return;
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
};

window.switchToPendidikanEdit = function () {
    document.getElementById('pend-view-mode')?.classList.add('hidden');
    document.getElementById('pend-edit-mode')?.classList.remove('hidden');
    document.getElementById('pend-view-actions')?.classList.add('hidden');
    document.getElementById('pend-edit-actions')?.classList.remove('hidden');
    const label = document.getElementById('modal-pend-mode-label');
    if (label) { label.textContent = 'Mode Edit'; label.className = label.className.replace('text-blue-600', 'text-amber-600'); }
};

window.switchToPendidikanView = function () {
    document.getElementById('pend-view-mode')?.classList.remove('hidden');
    document.getElementById('pend-edit-mode')?.classList.add('hidden');
    document.getElementById('pend-view-actions')?.classList.remove('hidden');
    document.getElementById('pend-edit-actions')?.classList.add('hidden');
    const label = document.getElementById('modal-pend-mode-label');
    if (label) { label.textContent = 'Mode Lihat'; label.className = label.className.replace('text-amber-600', 'text-blue-600'); }
};

window.savePendidikanEdit = async function () {
    const id = document.getElementById('pend-edit-id').value;
    const nama_sekolah = document.getElementById('pend-edit-nama_sekolah').value.trim();
    const jenjang = document.getElementById('pend-edit-jenjang').value;
    const jurusan_sek = document.getElementById('pend-edit-jurusan_sek').value.trim();
    const tahun_masuk = document.getElementById('pend-edit-tahun_masuk').value;
    const tahun_lulus = document.getElementById('pend-edit-tahun_lulus').value;
    const masih_kuliah = document.getElementById('pend-edit-masih_kuliah').checked ? '1' : '0';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const locale = document.querySelector('html').getAttribute('lang') || 'id';
    if (!nama_sekolah || !jenjang || !tahun_masuk) { window.showErrorAlert?.('Harap isi field yang wajib diisi.'); return; }
    if (masih_kuliah === '0' && tahun_lulus && tahun_lulus < tahun_masuk) {
        document.getElementById('edit-pend-lulus-error')?.classList.remove('hidden');
        window.showErrorAlert?.('Tahun lulus tidak boleh sebelum tahun masuk.');
        return;
    }
    const body = new URLSearchParams({ _method: 'PATCH', nama_sekolah, jenjang, jurusan_sek, tahun_masuk, masih_kuliah });
    if (masih_kuliah === '0' && tahun_lulus) body.append('tahun_lulus', tahun_lulus);
    try {
        const response = await fetch(`/${locale}/pendidikan/update?id=${id}`, {
            method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded' }, body: body.toString()
        });
        const json = await response.json();
        if (json.success) {
            window.closeDetailPendidikan();
            window.markScrollTarget('education-section');
            window.showSuccessAlert?.('Pendidikan Berhasil diperbarui!');
            setTimeout(() => window.location.reload(), 1800);
        } else {
            window.showErrorAlert?.(json.errors ? Object.values(json.errors).flat().join(', ') : (json.message || 'Gagal menyimpan perubahan.'));
        }
    } catch (e) { window.showErrorAlert?.('Terjadi kesalahan jaringan.'); }
};

window.confirmDeletePendidikan = async function () {
    const id = document.getElementById('pend-edit-id').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const locale = document.querySelector('html').getAttribute('lang') || 'id';
    const confirmed = await window.showConfirmAlert?.();
    if (!confirmed) return;
    try {
        const res = await fetch(`/${locale}/pendidikan/destroy?id=${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } });
        const json = await res.json();
        if (json.success) { window.closeDetailPendidikan(); window.markScrollTarget('education-section'); window.showSuccessAlert?.('Pendidikan Berhasil dihapus!'); setTimeout(() => window.location.reload(), 1800); }
        else { window.showErrorAlert?.(json.message || 'Gagal menghapus.'); }
    } catch (e) { window.showErrorAlert?.('Terjadi kesalahan jaringan.'); }
};

// ==========================================
// DETAIL/EDIT PENGALAMAN MODAL
// ==========================================
window.openDetailPengalaman = async function (id) {
    window.updateUrlParam('pkj', id);
    const modal = document.getElementById('modal-detail-pengalaman');
    const content = document.getElementById('modal-detail-pengalaman-content');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const locale = document.querySelector('html').getAttribute('lang') || 'id';
    const TODAY = new Date().toISOString().split('T')[0];
    if (!modal || !content) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => { content.classList.remove('scale-95', 'opacity-0'); content.classList.add('scale-100', 'opacity-100'); });
    ['pkj-view-mode', 'pkj-view-actions', 'pkj-edit-mode', 'pkj-edit-actions'].forEach(id => document.getElementById(id)?.classList.add('hidden'));
    document.getElementById('pkj-skeleton')?.classList.remove('hidden');
    try {
        const res = await fetch(`/${locale}/pengalaman-kerja/detail?id=${id}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
        const json = await res.json();
        if (!json.success) return;
        const d = json.data;
        document.getElementById('pkj-skeleton')?.classList.add('hidden');
        if (document.getElementById('pkj-edit-id')) document.getElementById('pkj-edit-id').value = d.id;
        const set = (elId, val) => { const el = document.getElementById(elId); if (el) el.textContent = val; };
        set('pkj-view-nama', d.nama_pt || '-');
        set('pkj-view-bagian', d.bagian_kerja || '-');
        set('pkj-view-jenis', d.jenis_pekerjaan || '');
        set('pkj-view-deskripsi', d.deskripsi || '-');
        const akhirText = d.masih_bekerja ? 'Sekarang' : (d.tahun_akhir || 'Selesai');
        set('pkj-view-periode', `${d.tahun_mulai || '-'} — ${akhirText}`);
        const mulaiVal = window.toYMD(d.tahun_mulai);
        const akhirVal = window.toYMD(d.tahun_akhir);
        const val = (elId, v) => { const el = document.getElementById(elId); if (el) el.value = v; };
        val('pkj-edit-nama_pt', d.nama_pt || '');
        val('pkj-edit-bagian_kerja', d.bagian_kerja || '');
        val('pkj-edit-jenis_pekerjaan', d.jenis_pekerjaan || '');
        val('pkj-edit-deskripsi', d.deskripsi || '');
        const editMulai = document.getElementById('pkj-edit-tahun_mulai');
        if (editMulai) { editMulai.value = mulaiVal; editMulai.max = TODAY; }
        const akhirInput = document.getElementById('pkj-edit-tahun_akhir');
        if (akhirInput) { akhirInput.min = mulaiVal; akhirInput.value = akhirVal; }
        const masihBekerja = !!d.masih_bekerja;
        const editMasih = document.getElementById('pkj-edit-masih_bekerja');
        if (editMasih) editMasih.checked = masihBekerja;
        const akhirField = document.getElementById('pkj-edit-tahun-akhir-field');
        if (akhirField) akhirField.style.opacity = masihBekerja ? '0.4' : '1';
        if (akhirInput) akhirInput.disabled = masihBekerja;
        document.getElementById('edit-pkj-akhir-error')?.classList.add('hidden');
        window.switchToPengalamanView();
    } catch (e) { document.getElementById('pkj-skeleton')?.classList.add('hidden'); console.error(e); }
};

window.closeDetailPengalaman = function () {
    window.updateUrlParam('pkj', null);
    const modal = document.getElementById('modal-detail-pengalaman');
    const content = document.getElementById('modal-detail-pengalaman-content');
    if (!modal || !content) return;
    content.classList.remove('scale-100', 'opacity-100'); content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
};

window.switchToPengalamanEdit = function () {
    document.getElementById('pkj-view-mode')?.classList.add('hidden'); document.getElementById('pkj-edit-mode')?.classList.remove('hidden');
    document.getElementById('pkj-view-actions')?.classList.add('hidden'); document.getElementById('pkj-edit-actions')?.classList.remove('hidden');
    const label = document.getElementById('modal-pkj-mode-label');
    if (label) { label.textContent = 'Mode Edit'; label.className = label.className.replace('text-emerald-600', 'text-amber-600'); }
};

window.switchToPengalamanView = function () {
    document.getElementById('pkj-view-mode')?.classList.remove('hidden'); document.getElementById('pkj-edit-mode')?.classList.add('hidden');
    document.getElementById('pkj-view-actions')?.classList.remove('hidden'); document.getElementById('pkj-edit-actions')?.classList.add('hidden');
    const label = document.getElementById('modal-pkj-mode-label');
    if (label) { label.textContent = 'Mode Lihat'; label.className = label.className.replace('text-amber-600', 'text-emerald-600'); }
};

window.savePengalamanEdit = async function () {
    const id = document.getElementById('pkj-edit-id').value;
    const nama_pt = document.getElementById('pkj-edit-nama_pt').value.trim();
    const bagian_kerja = document.getElementById('pkj-edit-bagian_kerja').value.trim();
    const jenis_pekerjaan = document.getElementById('pkj-edit-jenis_pekerjaan').value;
    const deskripsi = document.getElementById('pkj-edit-deskripsi').value.trim();
    const tahun_mulai = document.getElementById('pkj-edit-tahun_mulai').value;
    const tahun_akhir = document.getElementById('pkj-edit-tahun_akhir').value;
    const masih_bekerja = document.getElementById('pkj-edit-masih_bekerja').checked ? '1' : '0';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const locale = document.querySelector('html').getAttribute('lang') || 'id';
    if (!nama_pt || !bagian_kerja || !jenis_pekerjaan || !tahun_mulai) { window.showErrorAlert?.('Harap isi field yang wajib diisi.'); return; }
    if (masih_bekerja === '0' && tahun_akhir && tahun_akhir < tahun_mulai) {
        document.getElementById('edit-pkj-akhir-error')?.classList.remove('hidden');
        window.showErrorAlert?.('Tahun selesai tidak boleh sebelum tahun mulai.');
        return;
    }
    const body = new URLSearchParams({ _method: 'PATCH', nama_pt, bagian_kerja, jenis_pekerjaan, deskripsi, tahun_mulai, masih_bekerja });
    if (masih_bekerja === '0' && tahun_akhir) body.append('tahun_akhir', tahun_akhir);
    try {
        const res = await fetch(`/${locale}/pengalaman-kerja/update?id=${id}`, {
            method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded' }, body: body.toString()
        });
        const json = await res.json();
        if (json.success) { window.closeDetailPengalaman(); window.markScrollTarget('experience-section'); window.showSuccessAlert?.('Pengalaman Berhasil diperbarui!'); setTimeout(() => window.location.reload(), 1800); }
        else { window.showErrorAlert?.(json.errors ? Object.values(json.errors).flat().join(', ') : (json.message || 'Gagal menyimpan perubahan.')); }
    } catch (e) { window.showErrorAlert?.('Terjadi kesalahan jaringan.'); }
};

window.confirmDeletePengalaman = async function () {
    const id = document.getElementById('pkj-edit-id').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const locale = document.querySelector('html').getAttribute('lang') || 'id';
    const confirmed = await window.showConfirmAlert?.();
    if (!confirmed) return;
    try {
        const res = await fetch(`/${locale}/pengalaman-kerja/destroy?id=${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } });
        const json = await res.json();
        if (json.success) { window.closeDetailPengalaman(); window.markScrollTarget('experience-section'); window.showSuccessAlert?.('Pengalaman Berhasil dihapus!'); setTimeout(() => window.location.reload(), 1800); }
        else { window.showErrorAlert?.(json.message || 'Gagal menghapus.'); }
    } catch (e) { window.showErrorAlert?.('Terjadi kesalahan jaringan.'); }
};

// ==========================================
// DATE HELPERS (pendidikan & pengalaman forms)
// ==========================================
window.onAddPendTahunMasukChange = function (masukVal) {
    const lulusInput = document.getElementById('add-pend-tahun_lulus');
    if (!lulusInput) return;
    lulusInput.min = masukVal;
    if (lulusInput.value && lulusInput.value < masukVal) { lulusInput.value = ''; document.getElementById('add-pend-lulus-error')?.classList.remove('hidden'); }
    else { document.getElementById('add-pend-lulus-error')?.classList.add('hidden'); }
};
window.validateAddPendTahunLulus = function (input) {
    const masukVal = document.getElementById('add-pend-tahun_masuk')?.value;
    if (masukVal && input.value && input.value < masukVal) { document.getElementById('add-pend-lulus-error')?.classList.remove('hidden'); input.value = ''; }
    else { document.getElementById('add-pend-lulus-error')?.classList.add('hidden'); }
};
window.toggleAddPendTahunLulus = function (checkbox) {
    const field = document.getElementById('add-pend-tahun-lulus-field');
    const input = document.getElementById('add-pend-tahun_lulus');
    if (!field || !input) return;
    field.style.opacity = checkbox.checked ? '0.4' : '1';
    input.disabled = checkbox.checked;
    if (checkbox.checked) { input.value = ''; document.getElementById('add-pend-lulus-error')?.classList.add('hidden'); }
};
window.onEditPendTahunMasukChange = function (masukVal) {
    const lulusInput = document.getElementById('pend-edit-tahun_lulus');
    if (!lulusInput) return;
    lulusInput.min = masukVal;
    if (lulusInput.value && lulusInput.value < masukVal) { lulusInput.value = ''; document.getElementById('edit-pend-lulus-error')?.classList.remove('hidden'); }
    else { document.getElementById('edit-pend-lulus-error')?.classList.add('hidden'); }
};
window.validateEditPendTahunLulus = function (input) {
    const masukVal = document.getElementById('pend-edit-tahun_masuk')?.value;
    if (masukVal && input.value && input.value < masukVal) { document.getElementById('edit-pend-lulus-error')?.classList.remove('hidden'); input.value = ''; }
    else { document.getElementById('edit-pend-lulus-error')?.classList.add('hidden'); }
};
window.toggleEditTahunLulus = function (checkbox) {
    const field = document.getElementById('pend-edit-tahun-lulus-field');
    const input = document.getElementById('pend-edit-tahun_lulus');
    if (!field || !input) return;
    field.style.opacity = checkbox.checked ? '0.4' : '1';
    input.disabled = checkbox.checked;
    if (checkbox.checked) { input.value = ''; document.getElementById('edit-pend-lulus-error')?.classList.add('hidden'); }
};
window.onAddPkjTahunMulaiChange = function (mulaiVal) {
    const akhirInput = document.getElementById('form-tahun_akhir');
    if (!akhirInput) return;
    akhirInput.min = mulaiVal;
    if (akhirInput.value && akhirInput.value < mulaiVal) { akhirInput.value = ''; document.getElementById('add-pkj-akhir-error')?.classList.remove('hidden'); }
    else { document.getElementById('add-pkj-akhir-error')?.classList.add('hidden'); }
};
window.validateAddPkjTahunAkhir = function (input) {
    const mulaiVal = document.getElementById('form-tahun_mulai')?.value;
    if (mulaiVal && input.value && input.value < mulaiVal) { document.getElementById('add-pkj-akhir-error')?.classList.remove('hidden'); input.value = ''; }
    else { document.getElementById('add-pkj-akhir-error')?.classList.add('hidden'); }
};
window.toggleAddPkjTahunAkhir = function (checkbox) {
    const field = document.getElementById('add-pkj-tahun-akhir-field');
    const input = document.getElementById('form-tahun_akhir');
    if (!field || !input) return;
    field.style.opacity = checkbox.checked ? '0.4' : '1';
    input.disabled = checkbox.checked;
    if (checkbox.checked) { input.value = ''; document.getElementById('add-pkj-akhir-error')?.classList.add('hidden'); }
};
window.onEditPkjTahunMulaiChange = function (mulaiVal) {
    const akhirInput = document.getElementById('pkj-edit-tahun_akhir');
    if (!akhirInput) return;
    akhirInput.min = mulaiVal;
    if (akhirInput.value && akhirInput.value < mulaiVal) { akhirInput.value = ''; document.getElementById('edit-pkj-akhir-error')?.classList.remove('hidden'); }
    else { document.getElementById('edit-pkj-akhir-error')?.classList.add('hidden'); }
};
window.validateEditPkjTahunAkhir = function (input) {
    const mulaiVal = document.getElementById('pkj-edit-tahun_mulai')?.value;
    if (mulaiVal && input.value && input.value < mulaiVal) { document.getElementById('edit-pkj-akhir-error')?.classList.remove('hidden'); input.value = ''; }
    else { document.getElementById('edit-pkj-akhir-error')?.classList.add('hidden'); }
};
window.toggleEditTahunAkhir = function (checkbox) {
    const field = document.getElementById('pkj-edit-tahun-akhir-field');
    const input = document.getElementById('pkj-edit-tahun_akhir');
    if (!field || !input) return;
    field.style.opacity = checkbox.checked ? '0.4' : '1';
    input.disabled = checkbox.checked;
    if (checkbox.checked) { input.value = ''; document.getElementById('edit-pkj-akhir-error')?.classList.add('hidden'); }
};

// ==========================================
// KEAHLIAN TAMBAHAN Alpine Component
// ==========================================
window.keahlianTambahan = function (config = {}) {
    const initialList = Array.isArray(config.list) ? config.list : [];

    return {
        keahlianOptions: Array.isArray(config.options) ? config.options : [],
        mainSkillId: config.mainSkillId || null,
        keahlianList: initialList,
        selectedKeahlian: config.selected || '',
        loading: false,
        showAlert: false,
        alertMessage: '',
        alertType: 'success',
        get keahlianCount() { return Array.isArray(this.keahlianList) ? this.keahlianList.length : 0; },
        isKeahlianDisabled(keahlian) { return !keahlian || keahlian.id_keahlian === this.mainSkillId || this.isKeahlianExists(keahlian.id_keahlian); },
        isKeahlianSelectedInTambahan(id) { return Array.isArray(this.keahlianList) && this.keahlianList.some(item => item?.id_keahlian == id); },
        init() { this.refreshKeahlianList(); },
        isKeahlianExists(id) { return Array.isArray(this.keahlianList) && this.keahlianList.some(item => item?.id_keahlian == id); },
        async submitKeahlianFromDropdown() {
            if (!this.selectedKeahlian) return;
            this.loading = true;
            try {
                const locale = document.querySelector('html').getAttribute('lang') || 'id';
                const body = new URLSearchParams();
                body.append('id_keahlian_tambahan', this.selectedKeahlian);
                const response = await fetch(`/${locale}/keahlian-tambahan`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': config.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body
                });
                const data = await response.json();
                if (data.success) { await this.refreshKeahlianList(); this.selectedKeahlian = ''; this.showNotification(data.message || 'Pengajuan keahlian berhasil dikirim', 'success'); }
                else { this.showNotification(data.message || 'Gagal mengirim keahlian', 'error'); }
            } catch (error) { this.showNotification('Terjadi kesalahan jaringan', 'error'); }
            finally { this.loading = false; }
        },
        showNotification(message, type = 'success') { this.alertMessage = message; this.alertType = type; this.showAlert = true; setTimeout(() => { this.showAlert = false; }, 5000); },
        async deleteKeahlian(id) {
            if (!confirm('Hapus keahlian tambahan ini?')) return;
            this.loading = true;
            try {
                const locale = document.querySelector('html').getAttribute('lang') || 'id';
                const response = await fetch(`/${locale}/keahlian-tambahan/destroy?id=${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': config.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' }
                });
                const data = await response.json();
                if (data.success) { await this.refreshKeahlianList(); this.showNotification(data.message, 'success'); }
                else { this.showNotification(data.message || 'Gagal menghapus', 'error'); }
            } catch (error) { this.showNotification('Terjadi kesalahan jaringan', 'error'); }
            finally { this.loading = false; }
        },
        async refreshKeahlianList() {
            try {
                const url = config.indexUrl || '/keahlian-tambahan';
                const response = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await response.json();
                if (data?.success) {
                    this.keahlianList = Array.isArray(data?.data) ? data.data : Array.isArray(data?.list) ? data.list : [];
                } else {
                    this.keahlianList = [];
                }
            } catch (error) {
                console.error('Error refreshing keahlian list:', error);
                this.keahlianList = [];
            }
        }
    };
};

window.submitCustomKeahlian = async function () {
    const input = document.getElementById('custom-keahlian-input');
    if (!input) return;
    const customKeahlian = input.value.trim();
    if (!customKeahlian) { window.showErrorAlert?.('Masukkan nama keahlian terlebih dahulu'); return; }
    if (customKeahlian.length > 100) { window.showErrorAlert?.('Nama keahlian maksimal 100 karakter'); return; }
    const submitBtn = document.querySelector('#custom-keahlian-input + button');
    const originalText = submitBtn?.innerHTML || '';
    if (submitBtn) { submitBtn.disabled = true; submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Mengirim...'; }
    const locale = document.querySelector('html').getAttribute('lang') || 'id';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const formData = new FormData();
    formData.append('custom_keahlian_tambahan', customKeahlian);
    try {
        const response = await fetch(`/${locale}/keahlian-tambahan/custom`, {
            method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }, body: formData
        });
        const data = await response.json();
        if (data.success) { input.value = ''; window.showSuccessAlert?.(data.message); setTimeout(() => window.location.reload(), 1500); }
        else { window.showErrorAlert?.(data.message); }
    } catch (error) { window.showErrorAlert?.('Terjadi kesalahan jaringan'); }
    finally { if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = originalText; } }
};

document.addEventListener('DOMContentLoaded', function () {
    // Form tambah pendidikan submit native (bukan AJAX) -> tandai target scroll
    // sebelum browser redirect, supaya setelah reload halaman langsung
    // lompat ke education-section.
    document.getElementById('form-pendidikan')?.addEventListener('submit', function () {
        window.markScrollTarget('education-section');
    });

    const customInput = document.getElementById('custom-keahlian-input');
    if (customInput) {
        customInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); window.submitCustomKeahlian(); }
        });
    }
});