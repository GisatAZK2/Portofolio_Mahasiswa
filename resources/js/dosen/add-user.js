const XLSX = window.XLSX;

function initDosenAddUserPage() {
    const pageRoot = document.querySelector('[data-dosen-add-user]') || document.getElementById('dosenUserForm');
    if (!pageRoot) return;

    let excelData = [];
    let currentType = 'simple';

    window.toggleRegistrationType = function (type) {
        currentType = type;
        document.getElementById('registrationType').value = type;
        const fields = {
            nim: document.getElementById('nimField'),
            tgl: document.getElementById('tanggalLahirField'),
            username: document.getElementById('usernameField'),
            email: document.getElementById('emailField'),
            password: document.getElementById('passwordField'),
            jenisKelamin: document.getElementById('jenisKelaminField'),
            confirm: document.getElementById('passwordConfirmField'),
            photo: document.getElementById('photoField'),
            nimInput: document.getElementById('nimInput'),
            tglInput: document.getElementById('tanggalLahirInput'),
            usernameInput: document.getElementById('usernameInput'),
            passwordInput: document.getElementById('passwordInput'),
            confirmInput: document.getElementById('passwordConfirmInput'),
            tglReq: document.getElementById('tanggalLahirRequired'),
            nimHint: document.getElementById('nimHint'),
            excel: document.getElementById('excelImportSection'),
        };

        if (type === 'simple') {
            show(fields.nim); show(fields.tgl); show(fields.excel);
            hide(fields.jenisKelamin); hide(fields.username); hide(fields.email); hide(fields.password); hide(fields.confirm); hide(fields.photo);
            setRequired(fields.nimInput, true); setRequired(fields.tglInput, false); setRequired(fields.usernameInput, false); setRequired(fields.passwordInput, false); setRequired(fields.confirmInput, false);
            if (fields.tglReq) fields.tglReq.style.display = 'none';
            if (fields.nimHint) fields.nimHint.style.display = 'block';
        } else {
            show(fields.nim); show(fields.tgl); show(fields.jenisKelamin); show(fields.username); show(fields.email); show(fields.password); show(fields.confirm); show(fields.photo);
            setRequired(fields.nimInput, true); setRequired(fields.tglInput, true); setRequired(fields.usernameInput, true); setRequired(fields.passwordInput, true); setRequired(fields.confirmInput, true);
            setRequired(document.querySelector('select[name="jenis_kelamin"]'), false);
            setRequired(document.getElementById('photo_profile'), false);
            if (fields.tglReq) fields.tglReq.style.display = 'inline';
            if (fields.nimHint) fields.nimHint.style.display = 'none';
            hide(fields.excel);
            resetExcel();
        }
    };

    const show = (el) => { if (el) el.style.display = 'block'; };
    const hide = (el) => { if (el) el.style.display = 'none'; };
    const setRequired = (el, val) => { if (el) el.required = val; };

    window.handlePhotoChange = function (input) {
        if (!input.files[0]) return;
        const reader = new FileReader();
        reader.onload = (event) => {
            const img = document.getElementById('preview');
            const previewFrame = document.getElementById('previewFrame');
            const previewOverlay = document.getElementById('previewOverlay');
            if (img) img.src = event.target.result;
            if (previewFrame) previewFrame.classList.add('has-image');
            if (previewOverlay) previewOverlay.classList.add('hidden-overlay');
        };
        reader.readAsDataURL(input.files[0]);
    };

    document.getElementById('tanggalLahirInput')?.addEventListener('change', function () {
        const existing = document.getElementById('tanggalLahirClientError');
        if (existing) existing.remove();
        if (this.value > new Date().toISOString().split('T')[0]) {
            this.classList.add('daw-input-error');
            const err = document.createElement('p');
            err.id = 'tanggalLahirClientError';
            err.className = 'daw-error-msg';
            err.textContent = 'Tanggal lahir tidak boleh melebihi tanggal hari ini';
            this.parentNode.appendChild(err);
            this.value = '';
        } else {
            this.classList.remove('daw-input-error');
        }
    });

    window.handleExcelUpload = function (input) {
        const file = input.files[0];
        if (!file) return;
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 5MB.');
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = (event) => {
            try {
                const wb = XLSX.read(new Uint8Array(event.target.result), { type: 'array' });
                const json = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]]);
                if (!json.length) { alert('File Excel kosong!'); return; }
                excelData = json;
                displayExcelPreview(json);
            } catch (error) {
                console.error(error);
                alert('Gagal membaca file Excel. Pastikan format file benar.');
            }
        };
        reader.readAsArrayBuffer(file);
    };

    const displayExcelPreview = (data) => {
        const headers = Object.keys(data[0]);
        const headerRow = document.getElementById('previewHeader');
        const body = document.getElementById('previewBody');
        if (headerRow) headerRow.innerHTML = '';
        if (body) body.innerHTML = '';
        headers.forEach((header) => {
            const th = document.createElement('th');
            th.textContent = header;
            headerRow?.appendChild(th);
        });
        data.slice(0, 5).forEach((row) => {
            const tr = document.createElement('tr');
            headers.forEach((header) => {
                const td = document.createElement('td');
                td.textContent = row[header] ?? '-';
                tr.appendChild(td);
            });
            body?.appendChild(tr);
        });
        document.getElementById('excelPreview')?.classList.remove('hidden');
    };

    const resetExcel = () => {
        excelData = [];
        document.getElementById('excelPreview')?.classList.add('hidden');
        const excelFile = document.getElementById('excelFile');
        if (excelFile) excelFile.value = '';
    };

    window.resetExcel = resetExcel;

    window.validateExcelData = function () {
        const errors = [];
        const nims = new Set();
        excelData.forEach((row, index) => {
            const nim = row.NIM || row.nim || row['Nama Lengkap'] ? (row.NIM || row.nim) : '';
            const nama = row['Nama Lengkap'] || row.nama || row.Nama;
            if (!nim) errors.push(`Baris ${index + 2}: NIM tidak boleh kosong`);
            if (!nama) errors.push(`Baris ${index + 2}: Nama tidak boleh kosong`);
            if (nims.has(nim)) errors.push(`Baris ${index + 2}: NIM duplikat dalam file`);
            if (nim) nims.add(nim);
        });
        if (errors.length) {
            alert('Validasi gagal:\n' + errors.join('\n'));
            return false;
        }
        return true;
    };

    window.importExcelData = async function () {
        if (!excelData.length) {
            alert('Tidak ada data Excel untuk diimport!');
            return;
        }
        if (!window.validateExcelData()) return;

        const confirmed = confirm(
            `Anda akan mengimport ${excelData.length} data mahasiswa.\n` +
            `Jurusan, Keahlian, dan Angkatan akan otomatis diisi dari data Anda.\n\n` +
            `Password default = NIM masing-masing mahasiswa.\n\n` +
            `Lanjutkan?`
        );
        if (!confirmed) return;

        const excelFile = document.getElementById('excelFile').files[0];
        if (!excelFile) {
            alert('File Excel tidak ditemukan!');
            return;
        }

        const formData = new FormData();
        formData.append('excel_file', excelFile);
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        const dropzone = document.getElementById('excelDropzone');
        const originalHTML = dropzone?.innerHTML ?? '';
        if (dropzone) {
            dropzone.innerHTML = `
                <div style="text-align:center;padding:2rem;">
                    <div style="display:inline-block;width:40px;height:40px;border:3px solid #bbf7d0;border-top:3px solid #059669;border-radius:50%;animation:dawSpin 1s linear infinite;"></div>
                    <p style="margin-top:1rem;color:#065f46;font-weight:500;">Sedang mengimport data...</p>
                </div>`;
            dropzone.style.pointerEvents = 'none';
        }

        if (!document.querySelector('#daw-spin-style')) {
            const style = document.createElement('style');
            style.id = 'daw-spin-style';
            style.textContent = '@keyframes dawSpin{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}';
            document.head.appendChild(style);
        }

        try {
            const route = pageRoot?.dataset.importExcelRoute || document.getElementById('dosenUserForm')?.dataset.importExcelRoute || '';
            const response = await fetch(route, {
                method: 'POST',
                body: formData,
            });
            const result = await response.json();

            if (dropzone) {
                dropzone.innerHTML = originalHTML;
                dropzone.style.pointerEvents = 'auto';
            }

            if (result.success) {
                let msg = result.message;
                if (result.failedRows && result.failedRows.length) {
                    msg += '\n\n❌ Gagal di baris:\n';
                    result.failedRows.slice(0, 10).forEach((failedRow) => {
                        msg += `Baris ${failedRow.row}: ${failedRow.reason}\n`;
                    });
                    if (result.failedRows.length > 10) {
                        msg += `... dan ${result.failedRows.length - 10} baris lainnya`;
                    }
                }
                msg += `\n\n📊 Statistik:\n- Berhasil: ${result.stats.success}\n- Gagal: ${result.stats.failed}\n- Total: ${result.stats.total}`;
                alert(msg);
                if (result.stats.success > 0 && confirm('Import selesai! Me-refresh halaman untuk melihat data terbaru?')) {
                    window.location.reload();
                }
            } else {
                alert('Gagal import: ' + result.message);
            }
        } catch (error) {
            if (dropzone) {
                dropzone.innerHTML = originalHTML;
                dropzone.style.pointerEvents = 'auto';
            }
            console.error(error);
            alert('Terjadi kesalahan saat mengimport data.');
        }
    };

    window.downloadTemplate = function () {
        const data = [
            { 'NIM': '20250010001', 'Nama Lengkap': 'Ahmad Budi Santoso', 'Tanggal Lahir': '2005-03-15' },
            { 'NIM': '20250010002', 'Nama Lengkap': 'Siti Nurhaliza', 'Tanggal Lahir': '2004-07-22' },
        ];
        const ws = XLSX.utils.json_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Template Mahasiswa');
        XLSX.writeFile(wb, 'template_import_mahasiswa_dosen.xlsx');
    };

    document.addEventListener('DOMContentLoaded', () => {
        window.toggleRegistrationType('simple');
        document.querySelectorAll('input[name="registration_type_ui"]').forEach((radio) => {
            if (radio.checked) window.toggleRegistrationType(radio.value);
        });
    });

    if (typeof window.showPageInfo === 'function') {
        window.showPageInfo('popup.dosen_add_user');
    }
}

document.addEventListener('DOMContentLoaded', initDosenAddUserPage);
document.addEventListener('turbo:load', initDosenAddUserPage);
