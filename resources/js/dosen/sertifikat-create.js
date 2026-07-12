function initDosenSertifikatUserSelection() {
    const hiddenInput = document.getElementById('selected-user-id');
    if (!hiddenInput) return; // Halaman ini tidak punya tabel pemilihan mahasiswa

    const displayBox = document.getElementById('selected-user-display');
    const nameLabel = document.getElementById('selected-user-name');
    const rows = Array.from(document.querySelectorAll('tr.user-row[data-user-id]'));

    const highlightRow = (userId) => {
        rows.forEach((row) => {
            row.classList.toggle('user-row-selected', String(userId) === row.dataset.userId);
        });
    };

    const selectUser = (id, name) => {
        hiddenInput.value = id;
        if (nameLabel) nameLabel.textContent = name;
        displayBox?.classList.remove('hidden');

        const radio = document.getElementById(`user-radio-${id}`);
        if (radio) radio.checked = true;

        highlightRow(id);
    };

    const clearSelectedUser = () => {
        hiddenInput.value = '';
        if (nameLabel) nameLabel.textContent = '';
        displayBox?.classList.add('hidden');

        document.querySelectorAll('.user-radio').forEach((radio) => {
            radio.checked = false;
        });

        highlightRow(null);
    };

    const applyFilters = () => {
        const params = new URLSearchParams(window.location.search);
        const searchValue = document.getElementById('search-input')?.value.trim() || '';
        const angkatanValue = document.getElementById('angkatan-filter')?.value || '';
        const keahlianValue = document.getElementById('keahlian-filter')?.value || '';

        searchValue ? params.set('search', searchValue) : params.delete('search');
        angkatanValue ? params.set('angkatan', angkatanValue) : params.delete('angkatan');
        keahlianValue ? params.set('keahlian', keahlianValue) : params.delete('keahlian');
        params.delete('page');

        const queryString = params.toString();
        window.location.href = queryString ? `${window.location.pathname}?${queryString}` : window.location.pathname;
    };

    // Expose secara global untuk kompatibilitas jika ada pemanggilan lain
    window.selectUser = selectUser;
    window.clearSelectedUser = clearSelectedUser;
    window.applyFilters = applyFilters;

    // Klik di mana saja pada baris akan memilih mahasiswa tersebut (bukan hanya radio button)
    rows.forEach((row) => {
        row.addEventListener('click', (event) => {
            // Hindari double-trigger saat user memang mengklik langsung radio-nya
            if (event.target.closest('.user-radio')) return;
            selectUser(row.dataset.userId, row.dataset.userName);
        });

        const radio = row.querySelector('.user-radio');
        radio?.addEventListener('change', () => {
            selectUser(row.dataset.userId, row.dataset.userName);
        });
    });

    document.getElementById('clear-selected-user-btn')?.addEventListener('click', clearSelectedUser);
    document.getElementById('apply-filters-btn')?.addEventListener('click', applyFilters);

    // Pulihkan tampilan pilihan setelah reload akibat validation error (old('user_id'))
    if (hiddenInput.value) {
        const selectedRow = rows.find((row) => row.dataset.userId === String(hiddenInput.value));
        if (selectedRow) {
            selectUser(selectedRow.dataset.userId, selectedRow.dataset.userName);
        }
    }
}

function initDosenSertifikatCreatePage() {
    const pageRoot = document.querySelector('[data-dosen-sertifikat-create]') || document.getElementById('link_sertifikat');
    if (!pageRoot) return;

    initDosenSertifikatUserSelection();

    const permanentCheckbox = document.getElementById('permanent');
    const expiredDateContainer = document.getElementById('expired-date-container');
    const expiredDateInput = document.getElementById('expired_date');
    const tanggalTerbitInput = document.getElementById('tanggal_terbit');

    const updateExpiredDateState = () => {
        if (permanentCheckbox?.checked) {
            expiredDateContainer.style.display = 'none';
            if (expiredDateInput) {
                expiredDateInput.required = false;
                expiredDateInput.value = '';
            }
        } else {
            expiredDateContainer.style.display = 'block';
            if (expiredDateInput) expiredDateInput.required = true;
        }
    };

    const updateMinExpiredDate = () => {
        if (!expiredDateInput || !tanggalTerbitInput?.value) return;
        const terbitDate = new Date(tanggalTerbitInput.value + 'T00:00:00');
        const minDate = new Date(terbitDate);
        minDate.setDate(minDate.getDate() + 1);
        expiredDateInput.min = minDate.toISOString().split('T')[0];
        if (expiredDateInput.value) {
            const expiredDate = new Date(expiredDateInput.value + 'T00:00:00');
            if (expiredDate <= terbitDate) expiredDateInput.value = '';
        }
    };

    const validateExpiredDate = () => {
        if (!expiredDateInput?.value || !tanggalTerbitInput?.value) return;
        const terbitDate = new Date(tanggalTerbitInput.value + 'T00:00:00');
        const expiredDate = new Date(expiredDateInput.value + 'T00:00:00');
        if (expiredDate <= terbitDate) {
            expiredDateInput.value = '';
            alert('Tanggal expired harus setelah tanggal terbit');
        }
    };

    const updateFileLabel = (input) => {
        const fileName = input.files[0]?.name;
        const fileNameElement = document.getElementById('file-name');
        const previewContainer = document.getElementById('image-preview-container');
        const previewImage = document.getElementById('image-preview');

        if (fileName) {
            if (fileNameElement) fileNameElement.textContent = fileName;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    if (previewImage) previewImage.src = event.target.result;
                    if (previewContainer) previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        } else {
            if (fileNameElement) fileNameElement.textContent = 'PNG, JPG, GIF up to 5MB';
            if (previewContainer) previewContainer.classList.add('hidden');
            if (previewImage) previewImage.src = '#';
        }
    };

    if (permanentCheckbox) {
        permanentCheckbox.addEventListener('change', updateExpiredDateState);
        updateExpiredDateState();
    }

    if (tanggalTerbitInput) {
        tanggalTerbitInput.addEventListener('change', updateMinExpiredDate);
        updateMinExpiredDate();
    }

    if (expiredDateInput) {
        expiredDateInput.addEventListener('change', validateExpiredDate);
    }

    const fileInput = document.getElementById('link_sertifikat');
    if (fileInput) {
        fileInput.addEventListener('change', () => updateFileLabel(fileInput));
    }

    const dropZone = document.querySelector('.border-dashed');
    if (dropZone && fileInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach((eventName) => {
            dropZone.addEventListener(eventName, (event) => {
                event.preventDefault();
                event.stopPropagation();
            }, false);
        });
        ['dragenter', 'dragover'].forEach((eventName) => dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
        }, false));
        ['dragleave', 'drop'].forEach((eventName) => dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
        }, false));
        dropZone.addEventListener('drop', (event) => {
            const files = event.dataTransfer?.files;
            if (files && files.length > 0) {
                fileInput.files = files;
                updateFileLabel(fileInput);
            }
        }, false);
    }

    document.querySelector('form')?.addEventListener('submit', () => {
        if (fileInput?.files?.length > 0) {
            updateFileLabel(fileInput);
        }
    });

    if (typeof window.showPageInfo === 'function') {
        window.showPageInfo('popup.dosen_add_sertifikat');
    }
}

document.addEventListener('DOMContentLoaded', initDosenSertifikatCreatePage);
document.addEventListener('turbo:load', initDosenSertifikatCreatePage);