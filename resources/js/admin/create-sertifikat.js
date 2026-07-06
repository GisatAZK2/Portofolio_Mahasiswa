/**
 * resources/js/admin/create-sertifikat.js
 * Admin Create Sertifikat — sertifikat/views_create_sertifikat.blade.php
 */

// ===== APPLY FILTER =====
window.applySertifikatFilters = function() {
    const url = new URL(window.location.href);
    const searchInput = document.getElementById('search-input');
    const angkatanFilter = document.getElementById('angkatan-filter');
    const jurusanFilter = document.getElementById('jurusan-filter');
    const keahlianFilter = document.getElementById('keahlian-filter');

    if (searchInput) url.searchParams.set('search', searchInput.value);
    if (angkatanFilter) url.searchParams.set('angkatan', angkatanFilter.value);
    if (jurusanFilter) url.searchParams.set('jurusan', jurusanFilter.value);
    if (keahlianFilter) url.searchParams.set('keahlian', keahlianFilter.value);
    window.location.href = url.toString();
};

// ===== SELECT USER =====
window.selectUser = function(userId, userName, photoProfile, email) {
    const selectedUserIdInput = document.getElementById('selected-user-id');
    if (selectedUserIdInput) selectedUserIdInput.value = userId;

    document.querySelectorAll('.user-radio').forEach(radio => {
        radio.checked = (radio.value == userId);
    });

    const display = document.getElementById('selected-user-display');
    const content = document.getElementById('selected-user-content');

    if (display && content) {
        let photoHtml = photoProfile
            ? `<img src="${photoProfile}" class="w-12 h-12 rounded-2xl object-cover border border-white dark:border-gray-700 shadow" alt="${userName}">`
            : `<div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-2xl shadow">
                        ${userName.charAt(0).toUpperCase()}
                   </div>`;

        content.innerHTML = `
                <div class="flex items-center gap-4">
                    ${photoHtml}
                    <div>
                        <div class="font-semibold text-lg text-green-800 dark:text-green-200">${userName}</div>
                        <div class="text-sm text-green-700 dark:text-green-300">${email}</div>
                    </div>
                </div>
            `;

        display.classList.remove('hidden');
    }
};

// ===== CLEAR SELECTED USER =====
window.clearSelectedUser = function() {
    const selectedUserIdInput = document.getElementById('selected-user-id');
    if (selectedUserIdInput) selectedUserIdInput.value = '';
    document.querySelectorAll('.user-radio').forEach(radio => radio.checked = false);
    const display = document.getElementById('selected-user-display');
    if (display) display.classList.add('hidden');
};

// ===== FILE UPLOAD & PREVIEW =====
window.updateFileLabel = function(input) {
    const fileNameEl       = document.getElementById('file-name');
    const previewContainer = document.getElementById('image-preview-container');
    const previewImg       = document.getElementById('image-preview');

    if (input.files && input.files[0]) {
        const file = input.files[0];
        if (fileNameEl) fileNameEl.textContent = file.name;

        const reader = new FileReader();
        reader.onload = function (e) {
            if (previewImg) previewImg.src = e.target.result;
            if (previewContainer) previewContainer.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
};

// ===== TOGGLE FILE UPLOAD (for edit sertifikat) =====
window.toggleFileUpload = function(checkbox) {
    const fileUploadSection = document.getElementById('file-upload-section');
    if (fileUploadSection) {
        if (checkbox.checked) {
            fileUploadSection.classList.remove('hidden');
        } else {
            fileUploadSection.classList.add('hidden');
        }
    }
};

document.addEventListener('DOMContentLoaded', function () {
    // ── Permanent Certificate Toggle ────────────────────────────────────
    const permanentCheckbox    = document.getElementById('permanent');
    const expiredDateContainer = document.getElementById('expired-date-container');
    const expiredDateInput     = document.getElementById('expired_date');

    function updateExpiredDateState() {
        if (!permanentCheckbox || !expiredDateContainer || !expiredDateInput) return;
        if (permanentCheckbox.checked) {
            expiredDateContainer.style.display = 'none';
            expiredDateInput.required           = false;
            expiredDateInput.value              = '';
        } else {
            expiredDateContainer.style.display = 'block';
            expiredDateInput.required           = true;
        }
    }

    if (permanentCheckbox) {
        permanentCheckbox.addEventListener('change', updateExpiredDateState);
        updateExpiredDateState(); // run on page load
    }

    // ── Date validation (expired_date must be after tanggal_terbit) ───────
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
        updateMinExpiredDate();
    }

    if (expiredDateInput) {
        expiredDateInput.addEventListener('change', validateExpiredDate);
    }

    // ── Drag and Drop ───────────────────────────────────────────────────
    const dropZone  = document.getElementById('drop-zone');
    const fileInput = document.getElementById('link_sertifikat');

    if (dropZone && fileInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, e => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        dropZone.addEventListener('dragenter', () => dropZone.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20'));
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20'));
        dropZone.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                window.updateFileLabel(fileInput);
            }
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
        });
    }

    // Load old selection
    const selectedUserIdInput = document.getElementById('selected-user-id');
    if (selectedUserIdInput && selectedUserIdInput.value) {
        const oldUserId = selectedUserIdInput.value;
        const radio = document.querySelector(`.user-radio[value="${oldUserId}"]`);
        if (radio) {
            const row   = radio.closest('tr');
            if (row) {
                const name  = row.cells[1].querySelector('.font-medium').textContent.trim();
                const email = row.cells[1].querySelector('.text-xs').textContent.trim();
                const img   = row.querySelector('img');
                const photo = img ? img.src : '';
                window.selectUser(oldUserId, name, photo, email);
            }
        }
    }

    // Enter key on filter search
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                window.applySertifikatFilters();
            }
        });
    }

    // ===== REAL-TIME TABLE FILTERING =====
    const userSearchInput = document.getElementById('user-search');
    const angkatanFilter = document.getElementById('angkatan-filter');
    const jurusanFilter = document.getElementById('jurusan-filter');
    const keahlianFilter = document.getElementById('keahlian-filter');

    function filterTableRows() {
        const searchTerm = userSearchInput ? userSearchInput.value.toLowerCase().trim() : '';
        const selectedAngkatan = angkatanFilter ? angkatanFilter.value : '';
        const selectedJurusan = jurusanFilter ? jurusanFilter.value : '';
        const selectedKeahlian = keahlianFilter ? keahlianFilter.value : '';
        const rows = document.querySelectorAll('#user-table-body tr');

        rows.forEach(row => {
            if (!row.cells || row.cells.length < 2) return;

            // Get search fields
            const nameEl = row.cells[1].querySelector('.font-medium');
            const emailEl = row.cells[1].querySelector('.text-xs');
            if (!nameEl) return;

            const name = nameEl.textContent.toLowerCase();
            const email = emailEl ? emailEl.textContent.toLowerCase() : '';

            // Check search term match
            const matchesSearch = searchTerm === '' || name.includes(searchTerm) || email.includes(searchTerm);

            // Get filter data from row attributes
            const rowAngkatan = row.getAttribute('data-angkatan');
            const rowJurusan = row.getAttribute('data-jurusan');
            const rowKeahlian = row.getAttribute('data-keahlian');

            // Check filter matches
            const matchesAngkatan = selectedAngkatan === '' || rowAngkatan === selectedAngkatan;
            const matchesJurusan = selectedJurusan === '' || rowJurusan === selectedJurusan;
            const matchesKeahlian = selectedKeahlian === '' || rowKeahlian === selectedKeahlian;

            // Display row only if all criteria match
            const shouldDisplay = matchesSearch && matchesAngkatan && matchesJurusan && matchesKeahlian;
            row.style.display = shouldDisplay ? '' : 'none';
        });
    }

    // Setup listeners for real-time filtering
    if (userSearchInput) {
        userSearchInput.addEventListener('input', filterTableRows);
    }

    if (angkatanFilter) angkatanFilter.addEventListener('change', filterTableRows);
    if (jurusanFilter) jurusanFilter.addEventListener('change', filterTableRows);
    if (keahlianFilter) keahlianFilter.addEventListener('change', filterTableRows);

    // ===== PAGE INFO =====
    if (typeof showPageInfo === 'function') {
        showPageInfo("popup.add_sertifikat");
    }
});
