function initDosenSertifikatEditPage() {
    const pageRoot = document.querySelector('[data-dosen-sertifikat-edit]') || document.getElementById('link_sertifikat');
    if (!pageRoot) return;

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
        const currentPreview = document.getElementById('current-image-preview');

        if (fileName) {
            if (fileNameElement) fileNameElement.textContent = fileName;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    if (previewImage) previewImage.src = event.target.result;
                    if (previewContainer) previewContainer.classList.remove('hidden');
                    if (currentPreview) currentPreview.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        } else {
            if (fileNameElement) fileNameElement.textContent = 'PNG, JPG, GIF up to 5MB';
            if (previewContainer) previewContainer.classList.add('hidden');
            if (previewImage) previewImage.src = '#';
            if (currentPreview) currentPreview.classList.remove('hidden');
        }
    };

    const toggleFileUpload = (checkbox) => {
        const fileUploadSection = document.getElementById('file-upload-section');
        const currentPreview = document.getElementById('current-image-preview');
        if (checkbox.checked) {
            fileUploadSection?.classList.remove('hidden');
            currentPreview?.classList.add('hidden');
            const fileInput = document.getElementById('link_sertifikat');
            if (fileInput) {
                fileInput.value = '';
                updateFileLabel(fileInput);
            }
        } else {
            fileUploadSection?.classList.add('hidden');
            currentPreview?.classList.remove('hidden');
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

    const replaceCheckbox = document.getElementById('replace-file-checkbox');
    if (replaceCheckbox) {
        replaceCheckbox.addEventListener('change', () => toggleFileUpload(replaceCheckbox));
    }

    const dropZone = document.querySelector('.border-dashed');
    if (dropZone && fileInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach((eventName) => {
            dropZone.addEventListener(eventName, (event) => {
                event.preventDefault();
                event.stopPropagation();
            }, false);
        });
        ['dragenter', 'dragover'].forEach((eventName) => dropZone.addEventListener(eventName, () => dropZone.classList.add('border-indigo-500', 'bg-indigo-50'), false));
        ['dragleave', 'drop'].forEach((eventName) => dropZone.addEventListener(eventName, () => dropZone.classList.remove('border-indigo-500', 'bg-indigo-50'), false));
        dropZone.addEventListener('drop', (event) => {
            const files = event.dataTransfer?.files;
            if (files && files.length > 0) {
                if (replaceCheckbox && !replaceCheckbox.checked) {
                    replaceCheckbox.checked = true;
                    toggleFileUpload(replaceCheckbox);
                }
                fileInput.files = files;
                updateFileLabel(fileInput);
            }
        }, false);
    }

    document.querySelector('form')?.addEventListener('submit', () => {
        if (fileInput?.files?.length > 0 && replaceCheckbox && !replaceCheckbox.checked) {
            replaceCheckbox.checked = true;
            toggleFileUpload(replaceCheckbox);
        }
    });

    if (typeof window.showPageInfo === 'function') {
        window.showPageInfo('popup.dosen_edit_sertifikat');
    }
}

document.addEventListener('DOMContentLoaded', initDosenSertifikatEditPage);
document.addEventListener('turbo:load', initDosenSertifikatEditPage);
