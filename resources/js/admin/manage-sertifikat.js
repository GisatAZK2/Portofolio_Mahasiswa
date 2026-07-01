/**
 * admin/manage-sertifikat.js
 * Halaman Admin Edit Sertifikat: preview, form helpers.
 */
(function () {
    function initEditSertifikat() {
        // Cek apakah ini halaman edit sertifikat
        if (!document.getElementById('editForm')) return;

        // ── Permanent toggle ────────────────────────────────────────────────
        const permanentCheckbox = document.getElementById('permanent');
        const expiredDateContainer = document.getElementById('expired-date-container');
        const expiredDateInput = document.getElementById('expired_date');

        function updateExpiredDateState() {
            if (!permanentCheckbox || !expiredDateContainer || !expiredDateInput) return;
            if (permanentCheckbox.checked) {
                expiredDateContainer.style.display = 'none';
                expiredDateInput.required = false;
                expiredDateInput.value = '';
            } else {
                expiredDateContainer.style.display = 'block';
                expiredDateInput.required = true;
            }
        }

        if (permanentCheckbox) {
            permanentCheckbox.addEventListener('change', updateExpiredDateState);
            updateExpiredDateState(); // run on page load
        }

        // ── Date validation (expired_date must be after tanggal_terbit) ────
        const tanggalTerbitInput = document.getElementById('tanggal_terbit');

        function updateMinExpiredDate() {
            if (!tanggalTerbitInput || !expiredDateInput) return;
            if (tanggalTerbitInput.value) {
                const terbitDate = new Date(tanggalTerbitInput.value + 'T00:00:00');
                const minDate = new Date(terbitDate);
                minDate.setDate(minDate.getDate() + 1);

                expiredDateInput.min = minDate.toISOString().split('T')[0];

                if (expiredDateInput.value) {
                    const expiredDate = new Date(expiredDateInput.value + 'T00:00:00');
                    if (expiredDate <= terbitDate) {
                        expiredDateInput.value = '';
                    }
                }
            }
        }

        function validateExpiredDate() {
            if (!expiredDateInput || !tanggalTerbitInput) return;
            if (!expiredDateInput.value || !tanggalTerbitInput.value) return;

            const terbitDate = new Date(tanggalTerbitInput.value + 'T00:00:00');
            const expiredDate = new Date(expiredDateInput.value + 'T00:00:00');

            if (expiredDate <= terbitDate) {
                expiredDateInput.value = '';
                alert('Tanggal expired harus setelah tanggal terbit');
            }
        }

        if (tanggalTerbitInput) {
            tanggalTerbitInput.addEventListener('change', updateMinExpiredDate);
            updateMinExpiredDate(); // run on page load
        }

        if (expiredDateInput) {
            expiredDateInput.addEventListener('change', validateExpiredDate);
        }

        // ── File upload helpers ─────────────────────────────────────────────
        window.updateFileLabel = function (input) {
            const fileName = input.files[0]?.name;
            const fileNameElement = document.getElementById('file-name');
            const previewContainer = document.getElementById('image-preview-container');
            const previewImage = document.getElementById('image-preview');
            const currentPreview = document.getElementById('current-image-preview');

            if (!fileNameElement || !previewContainer || !previewImage) return;

            if (fileName) {
                fileNameElement.textContent = fileName;

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                        if (currentPreview) currentPreview.classList.add('hidden');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            } else {
                // Reset ke default atau file lama
                const defaultText = fileNameElement.getAttribute('data-default-text') || 'PNG, JPG, GIF up to 5MB';
                fileNameElement.textContent = defaultText;
                previewContainer.classList.add('hidden');
                previewImage.src = '#';
                if (currentPreview) currentPreview.classList.remove('hidden');
            }
        };

        window.toggleFileUpload = function (checkbox) {
            const fileUploadSection = document.getElementById('file-upload-section');
            const currentPreview = document.getElementById('current-image-preview');

            if (!fileUploadSection) return;

            if (checkbox.checked) {
                fileUploadSection.classList.remove('hidden');
                if (currentPreview) currentPreview.classList.add('hidden');

                const fileInput = document.getElementById('link_sertifikat_input');
                if (fileInput) {
                    fileInput.value = '';
                    window.updateFileLabel(fileInput);
                }
            } else {
                fileUploadSection.classList.add('hidden');
                if (currentPreview) currentPreview.classList.remove('hidden');

                const previewContainer = document.getElementById('image-preview-container');
                if (previewContainer) previewContainer.classList.add('hidden');
            }
        };

        // ── Drag and drop ───────────────────────────────────────────────────
        const dropZone = document.querySelector('.border-dashed');
        const fileInput = document.getElementById('link_sertifikat_input');

        if (dropZone && fileInput) {
            const preventDefaults = function (e) {
                e.preventDefault();
                e.stopPropagation();
            };

            const highlight = function () {
                dropZone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            };

            const unhighlight = function () {
                dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            };

            const handleDrop = function (e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files && files.length > 0) {
                    // Jika ada file lama, aktifkan checkbox ganti file
                    const replaceCheckbox = document.getElementById('replace-file-checkbox');
                    if (replaceCheckbox && !replaceCheckbox.checked) {
                        replaceCheckbox.checked = true;
                        window.toggleFileUpload(replaceCheckbox);
                    }

                    fileInput.files = files;
                    window.updateFileLabel(fileInput);

                    const event = new Event('change', { bubbles: true });
                    fileInput.dispatchEvent(event);
                }
            };

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            dropZone.addEventListener('drop', handleDrop, false);
        }

        // ── Page Info ──────────────────────────────────────────────────────
        if (typeof showPageInfo === 'function') {
            showPageInfo('popup.edit_sertifikat');
        }
    }

    // Inisialisasi saat DOM siap dan setelah Turbo load
    document.addEventListener('DOMContentLoaded', initEditSertifikat);
    document.addEventListener('turbo:load', initEditSertifikat);
})();

