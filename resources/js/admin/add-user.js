/**
 * resources/js/admin/add-user.js
 * Admin Add User — user/views_add_user.blade.php
 *
 * Route untuk importExcel dibaca dari data-import-url pada #add-user-form-container.
 * Old role dibaca dari data-old-role pada #add-user-form-container.
 */

let selectedRole = '';
let currentRegistrationType = 'simple';
let excelData = [];

// ===== EXCEL UPLOAD =====
window.handleExcelUpload = function(input) {
    const file = input.files[0];
    if (!file) return;

    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file terlalu besar! Maksimal 5MB.');
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = e => {
        try {
            if (typeof XLSX === 'undefined') { alert('Library XLSX belum dimuat.'); return; }
            const wb = XLSX.read(new Uint8Array(e.target.result), { type: 'array' });
            const json = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]]);
            if (!json.length) { alert('File Excel kosong!'); return; }
            excelData = json;
            displayExcelPreview(json);
        } catch (error) {
            console.error('Error parsing Excel:', error);
            alert('Gagal membaca file Excel. Pastikan format file benar.');
        }
    };
    reader.readAsArrayBuffer(file);
};

function validateExcelData() {
    if (!excelData.length) return false;
    const errors = [];
    const nims = new Set();
    excelData.forEach((row, index) => {
        const nim = row.NIM || row.nim;
        const nama = row['Nama Lengkap'] || row.nama || row.Nama;
        if (!nim) errors.push(`Baris ${index + 2}: NIM tidak boleh kosong`);
        if (!nama) errors.push(`Baris ${index + 2}: Nama tidak boleh kosong`);
        if (nims.has(nim)) errors.push(`Baris ${index + 2}: NIM duplikat dalam file`);
        nims.add(nim);
    });
    if (errors.length > 0) { alert('Validasi gagal:\n' + errors.join('\n')); return false; }
    return true;
}

window.importExcelData = async function() {
    if (!excelData.length) { alert('Tidak ada data Excel untuk diimport!'); return; }
    if (!validateExcelData()) return;

    const confirmed = confirm(`Anda akan mengimport ${excelData.length} data mahasiswa.\n\nPastikan data sudah benar.\n\nLanjutkan?`);
    if (!confirmed) return;

    const excelFile = document.getElementById('excelFile').files[0];
    if (!excelFile) { alert('File Excel tidak ditemukan!'); return; }

    const formData = new FormData();
    formData.append('excel_file', excelFile);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value);

    showImportLoading();

    const formContainer = document.getElementById('add-user-form-container');
    const importUrl = formContainer ? formContainer.dataset.importUrl : null;
    if (!importUrl) { alert('URL import tidak ditemukan.'); hideImportLoading(); return; }

    try {
        const response = await fetch(importUrl, { method: 'POST', body: formData });
        const result = await response.json();
        if (result.success) { showImportResult(result); }
        else { alert('Gagal import: ' + result.message); }
    } catch (error) {
        console.error('Error import:', error);
        alert('Terjadi kesalahan saat mengimport data.');
    } finally {
        hideImportLoading();
    }
};

function showImportLoading() {
    const dropzone = document.getElementById('excelDropzone');
    if (!dropzone) return;
    dropzone.dataset.originalContent = dropzone.innerHTML;
    dropzone.innerHTML = `<div style="text-align:center;padding:2rem;"><div style="display:inline-block;width:40px;height:40px;border:3px solid #f3f3f3;border-top:3px solid #10b981;border-radius:50%;animation:spin 1s linear infinite;"></div><p style="margin-top:1rem;color:#065f46;">Sedang mengimport data...</p></div>`;
    dropzone.style.pointerEvents = 'none';
    if (!document.querySelector('#spinner-style')) {
        const style = document.createElement('style');
        style.id = 'spinner-style';
        style.textContent = '@keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}';
        document.head.appendChild(style);
    }
}

function hideImportLoading() {
    const dropzone = document.getElementById('excelDropzone');
    if (!dropzone) return;
    if (dropzone.dataset.originalContent) { dropzone.innerHTML = dropzone.dataset.originalContent; delete dropzone.dataset.originalContent; }
    dropzone.style.pointerEvents = 'auto';
}

function showImportResult(result) {
    let message = result.message;
    if (result.warnings && result.warnings.length > 0) { message += '\n\nPeringatan:\n' + result.warnings.join('\n'); }
    if (result.failedRows && result.failedRows.length > 0) {
        message += '\n\nGagal di baris:\n';
        result.failedRows.slice(0, 10).forEach(f => { message += `Baris ${f.row}: ${f.reason}\n`; });
        if (result.failedRows.length > 10) { message += `\n... dan ${result.failedRows.length - 10} baris lainnya`; }
    }
    message += `\n\nStatistik:\n- Berhasil: ${result.stats.success}\n- Gagal: ${result.stats.failed}\n- Total: ${result.stats.total}`;
    alert(message);
    if (result.stats.success > 0 && confirm('Import selesai! Apakah Anda ingin me-refresh halaman?')) { window.location.reload(); }
}

function addImportButton() {
    const excelSection = document.getElementById('excelImportSection');
    if (!excelSection) return;
    const excelBox = excelSection.querySelector('.auw-excel-box');
    if (!excelBox || excelBox.querySelector('.auw-btn-mass-import')) return;

    const actionsDiv = document.createElement('div');
    actionsDiv.className = 'auw-excel-actions';
    actionsDiv.style.cssText = 'margin-top:15px;display:flex;gap:10px;justify-content:flex-end;';

    const importBtn = document.createElement('button');
    importBtn.type = 'button';
    importBtn.className = 'auw-btn-mass-import';
    importBtn.style.cssText = 'padding:10px 24px;background:linear-gradient(135deg,#059669,#047857);color:white;border:none;border-radius:12px;font-size:0.875rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:8px;transition:all 0.15s;';
    importBtn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg> Import Massal ke Database';
    importBtn.onclick = window.importExcelData;

    const resetBtn = document.createElement('button');
    resetBtn.type = 'button';
    resetBtn.className = 'auw-btn-reset';
    resetBtn.style.cssText = 'padding:10px 20px;background:#6b7280;color:white;border:none;border-radius:12px;font-size:0.875rem;font-weight:600;cursor:pointer;transition:all 0.15s;';
    resetBtn.innerHTML = 'Reset Preview';
    resetBtn.onclick = () => {
        excelData = [];
        document.getElementById('excelPreview')?.classList.add('hidden');
        const ef = document.getElementById('excelFile'); if (ef) ef.value = '';
        const dt = document.querySelector('#excelDropzone p:first-of-type');
        if (dt) dt.textContent = 'Klik atau seret file Excel ke sini';
    };

    actionsDiv.appendChild(resetBtn);
    actionsDiv.appendChild(importBtn);
    excelBox.appendChild(actionsDiv);
}

// ===== ROLE SELECTION =====
window.selectRole = function(role) {
    selectedRole = role;
    const selectedRoleInput = document.getElementById('selectedRole');
    if (selectedRoleInput) selectedRoleInput.value = role;

    document.querySelectorAll('.auw-role-card').forEach(c => c.classList.remove('selected-admin','selected-mahasiswa','selected-dosen'));
    document.getElementById(`card-${role}`)?.classList.add(`selected-${role}`);

    const regTypeContainer = document.getElementById('registrationTypeContainer');
    const additionalFields = document.getElementById('additionalFields');
    const excelImportSection = document.getElementById('excelImportSection');

    const f = {
        nim: document.getElementById('nimField'), tgl: document.getElementById('tanggalLahirField'),
        username: document.getElementById('usernameField'), email: document.getElementById('emailField'),
        password: document.getElementById('passwordField'), confirm: document.getElementById('passwordConfirmationField'),
        photo: document.getElementById('photoField'), nimInput: document.getElementById('nimInput'),
        tglInput: document.getElementById('tanggalLahirInput'), nimReq: document.getElementById('nimRequired'),
        tglReq: document.getElementById('tanggalLahirRequired'),
    };

    ['nim','tgl','username','email','password','confirm','photo'].forEach(k => { if (f[k]) f[k].style.display = 'block'; });
    if (additionalFields) additionalFields.classList.add('hidden');
    if (excelImportSection) excelImportSection.classList.add('hidden');
    document.querySelectorAll('#additionalFields select').forEach(s => s.required = false);

    if (role === 'mahasiswa') {
        if (regTypeContainer) regTypeContainer.classList.remove('hidden');
        window.toggleRegistrationType('simple');
    } else {
        if (regTypeContainer) regTypeContainer.classList.add('hidden');
        if (role === 'admin') {
            if (f.nim) f.nim.style.display = 'none';
            if (f.tgl) f.tgl.style.display = 'none';
            if (f.nimInput) f.nimInput.required = false;
            if (f.tglInput) f.tglInput.required = false;
            setRequired(f.username,'input',true); setRequired(f.password,'input',true); setRequired(f.confirm,'input',true);
            if (f.email) { const ei = f.email.querySelector('input'); if (ei) ei.required = false; }
        } else if (role === 'dosen') {
            if (f.nim) f.nim.style.display = 'none';
            if (f.tgl) f.tgl.style.display = 'block';
            if (f.nimInput) f.nimInput.required = false;
            if (f.tglInput) { f.tglInput.required = true; f.tglInput.disabled = false; }
            if (f.tglReq) f.tglReq.style.display = 'inline';
            setRequired(f.username,'input',true); setRequired(f.password,'input',true); setRequired(f.confirm,'input',true);
            if (additionalFields) additionalFields.classList.remove('hidden');
            document.querySelectorAll('#additionalFields select').forEach(s => s.required = true);
        }
    }
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) submitBtn.disabled = false;
};

window.toggleRegistrationType = function(type) {
    currentRegistrationType = type;
    const rInput = document.getElementById('registrationType');
    if (rInput) rInput.value = type;
    document.querySelectorAll('input[name="registration_type"]').forEach(r => r.checked = r.value === type);

    const f = {
        nim: document.getElementById('nimField'), tgl: document.getElementById('tanggalLahirField'),
        username: document.getElementById('usernameField'), email: document.getElementById('emailField'),
        password: document.getElementById('passwordField'), confirm: document.getElementById('passwordConfirmationField'),
        photo: document.getElementById('photoField'), nimInput: document.getElementById('nimInput'),
        tglInput: document.getElementById('tanggalLahirInput'), nimReq: document.getElementById('nimRequired'),
        tglReq: document.getElementById('tanggalLahirRequired'),
    };
    const additionalFields = document.getElementById('additionalFields');
    const excelImportSection = document.getElementById('excelImportSection');

    if (type === 'full') {
        ['nim','tgl','username','email','password','confirm','photo'].forEach(k => { if (f[k]) f[k].style.display = 'block'; });
        if (additionalFields) additionalFields.classList.remove('hidden');
        if (excelImportSection) excelImportSection.classList.add('hidden');
        document.querySelectorAll('#additionalFields select').forEach(s => s.required = true);
        setRequired(f.username,'input',true); setRequired(f.password,'input',true); setRequired(f.confirm,'input',true);
        if (f.nimInput) f.nimInput.required = true;
        if (f.tglInput) f.tglInput.required = true;
        if (f.nimReq) f.nimReq.style.display = 'inline';
        if (f.tglReq) f.tglReq.style.display = 'inline';
    } else {
        ['username','email','password','confirm','photo'].forEach(k => { if (f[k]) f[k].style.display = 'none'; });
        if (f.nim) f.nim.style.display = 'block';
        if (f.tgl) f.tgl.style.display = 'block';
        if (additionalFields) additionalFields.classList.remove('hidden');
        if (excelImportSection) excelImportSection.classList.remove('hidden');
        document.querySelectorAll('#additionalFields select').forEach(s => s.required = false);
        setRequired(f.username,'input',false); setRequired(f.password,'input',false); setRequired(f.confirm,'input',false);
        if (f.nimInput) f.nimInput.required = true;
        if (f.tglInput) f.tglInput.required = false;
        if (f.nimReq) f.nimReq.style.display = 'inline';
        if (f.tglReq) f.tglReq.style.display = 'none';
    }
};

function setRequired(fieldEl, selector, val) {
    if (!fieldEl) return;
    const el = fieldEl.querySelector(selector);
    if (el) el.required = val;
}

window.applyAvatar = function(filename, label) {
    const avatarDefault = document.getElementById('avatarDefault');
    if (avatarDefault) avatarDefault.value = filename;
    const imgSrc = `/assets/${filename}.png`;
    const previewImg = document.getElementById('preview');
    if (previewImg) {
        previewImg.src = imgSrc;
        previewImg.onerror = function() { this.src = 'https://via.placeholder.com/150?text=Avatar+Error'; };
    }
    document.getElementById('previewFrame')?.classList.add('has-image');
    document.getElementById('previewOverlay')?.classList.add('hidden-overlay');
    const msg = document.getElementById('avatarSelectedMsg');
    if (msg) msg.textContent = `Avatar: ${label}`;
    const fileInput = document.getElementById('photo_profile');
    if (fileInput) fileInput.value = '';
    document.querySelectorAll('input[name="avatar_choice"]').forEach(r => { if (r.value !== filename) r.checked = false; });
};

window.handlePhotoChange = function(input) {
    if (!input.files[0]) return;
    document.querySelectorAll('input[name="avatar_choice"]').forEach(r => r.checked = false);
    const avatarDefault = document.getElementById('avatarDefault');
    if (avatarDefault) avatarDefault.value = '';
    const msg = document.getElementById('avatarSelectedMsg');
    if (msg) msg.textContent = '';
    const reader = new FileReader();
    reader.onload = e => {
        const previewImg = document.getElementById('preview');
        if (previewImg) { previewImg.src = e.target.result; previewImg.onerror = null; }
        document.getElementById('previewFrame')?.classList.add('has-image');
        document.getElementById('previewOverlay')?.classList.add('hidden-overlay');
    };
    reader.readAsDataURL(input.files[0]);
};

function displayExcelPreview(data) {
    const headers = Object.keys(data[0]);
    const headerRow = document.getElementById('previewHeader');
    const body = document.getElementById('previewBody');
    if (headerRow) { headerRow.innerHTML = ''; headers.forEach(h => { const th = document.createElement('th'); th.textContent = h; headerRow.appendChild(th); }); }
    if (body) {
        body.innerHTML = '';
        data.slice(0, 5).forEach(row => {
            const tr = document.createElement('tr');
            headers.forEach(h => { const td = document.createElement('td'); td.textContent = row[h] ?? '-'; tr.appendChild(td); });
            body.appendChild(tr);
        });
    }
    document.getElementById('excelPreview')?.classList.remove('hidden');
}

window.applyExcelData = function() {
    if (!excelData.length) { alert('Tidak ada data Excel!'); return; }
    const r = excelData[0];
    const get = (...keys) => keys.reduce((v, k) => v || r[k], '');
    const setVal = (name, val) => { const el = document.querySelector(`[name="${name}"]`); if (el && val) el.value = val; };
    setVal('nim', get('NIM','nim'));
    setVal('nama_mahasiswa', get('Nama Lengkap','nama','Nama'));
    setVal('email', get('Email','email'));
    setVal('username', get('Username','username'));
    let tgl = get('Tanggal Lahir','tanggal_lahir');
    if (tgl && typeof XLSX !== 'undefined') {
        if (typeof tgl === 'number') { const d = XLSX.SSF.parse_date_code(tgl); tgl = `${d.y}-${String(d.m).padStart(2,'0')}-${String(d.d).padStart(2,'0')}`; }
        else { const parsed = new Date(tgl); if (!isNaN(parsed)) tgl = parsed.toISOString().split('T')[0]; }
        setVal('tanggal_lahir', tgl);
    }
    if (currentRegistrationType === 'full') {
        matchSelectByText('id_jurusan', get('Jurusan','jurusan'));
        matchSelectByText('id_keahlian', get('Keahlian','keahlian'));
        matchSelectByText('id_angkatan', get('Angkatan','angkatan'));
    }
    alert('Data Excel berhasil diterapkan ke form!');
};

function matchSelectByText(name, text) {
    if (!text) return;
    const sel = document.querySelector(`select[name="${name}"]`);
    if (!sel) return;
    for (const opt of sel.options) { if (opt.text.trim() === String(text).trim()) { sel.value = opt.value; break; } }
}

window.downloadTemplate = function() {
    if (typeof XLSX === 'undefined') { alert('Library XLSX belum dimuat.'); return; }
    const data = [
        { 'NIM': '20250010001', 'Nama Lengkap': 'Ahmad Budi Santoso', 'Jurusan': 'D4 Bisnis Digital', 'Keahlian': 'Web Development', 'Angkatan': 'Angkatan 2025' },
        { 'NIM': '20250010002', 'Nama Lengkap': 'Siti Nurhaliza', 'Jurusan': 'D4 Teknologi Rekayasa Perangkat Lunak', 'Keahlian': 'Mobile Development', 'Angkatan': 'Angkatan 2025' }
    ];
    const ws = XLSX.utils.json_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Template Mahasiswa');
    XLSX.writeFile(wb, 'template_import_mahasiswa.xlsx');
};

document.addEventListener('DOMContentLoaded', () => {
    const tanggalLahirInput = document.getElementById('tanggalLahirInput');
    if (tanggalLahirInput) {
        tanggalLahirInput.addEventListener('change', function () {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggalLahirClientError')?.remove();
            if (this.value > today) {
                this.classList.add('auw-input-error');
                const err = document.createElement('p');
                err.id = 'tanggalLahirClientError';
                err.className = 'auw-error-msg';
                err.textContent = 'Tanggal lahir tidak boleh melebihi tanggal hari ini';
                this.parentNode.appendChild(err);
                this.value = '';
            } else {
                this.classList.remove('auw-input-error');
            }
        });
    }

    // Auto select role from data-* attributes
    const formContainer = document.getElementById('add-user-form-container');
    if (formContainer) {
        const oldRole = formContainer.dataset.oldRole;
        const defaultRole = formContainer.dataset.defaultRole;
        if (oldRole) { window.selectRole(oldRole); }
        else if (defaultRole) { window.selectRole(defaultRole); }
    }

    addImportButton();

    if (typeof showPageInfo === 'function') {
        showPageInfo('popup.add_user');
    }
});
