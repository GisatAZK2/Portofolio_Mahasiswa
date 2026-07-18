/**
 * resources/js/shared/project-duplicate-check.js
 *
 * Reusable real-time "nama_project sudah dipakai" checker.
 * Dipakai oleh form create/edit project di panel Admin dan Dosen
 * (modul mahasiswa punya implementasi sendiri di resources/js/modules/project/*.js).
 *
 * Cara pakai (di init script masing-masing halaman):
 *
 *   window.setupProjectDuplicateNameCheck({
 *       checkUrl: 'https://.../check-duplicate-name',
 *       excludeId: 123 // opsional, dipakai saat mode edit supaya project itu sendiri
 *                       // tidak dianggap duplikat
 *   });
 */
window.setupProjectDuplicateNameCheck = function (options) {
    const {
        checkUrl,
        excludeId = null,
        nameInputSelector = '#nama_project, input[name="nama_project"]',
        warningId = 'duplicate-warning',
        warningTextId = 'duplicate-warning-text',
        submitSelector = '#submit-btn, button[type="submit"]',
        formSelector = '#projectForm',
    } = options || {};

    if (!checkUrl) return null;

    const nameInput = document.querySelector(nameInputSelector);
    if (!nameInput) return null;

    // Pastikan ada elemen peringatan; kalau blade belum menyediakan, buat otomatis
    // supaya tetap tampil meski view belum diupdate.
    let warning = document.getElementById(warningId);
    let warningText = document.getElementById(warningTextId);
    if (!warning) {
        warning = document.createElement('div');
        warning.id = warningId;
        warning.className = 'hidden mt-2 p-4 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 rounded-r-xl';
        warning.innerHTML = `
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <p id="${warningTextId}" class="text-sm font-medium"></p>
            </div>`;
        nameInput.insertAdjacentElement('afterend', warning);
        warningText = warning.querySelector(`#${warningTextId}`);
    }

    let isDuplicate = false;
    let debounceTimer = null;

    function setSubmitDisabled(disabled) {
        document.querySelectorAll(submitSelector).forEach((btn) => {
            btn.disabled = disabled;
        });
    }

    function showWarning(message) {
        if (warningText) warningText.textContent = message;
        if (warning) warning.classList.remove('hidden');
        nameInput.classList.add('border-red-500');
    }

    function hideWarning() {
        if (warning) warning.classList.add('hidden');
        nameInput.classList.remove('border-red-500');
    }

    function checkName(name) {
        if (!name || !name.trim()) {
            isDuplicate = false;
            hideWarning();
            setSubmitDisabled(false);
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch(checkUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ nama_project: name, exclude_id: excludeId }),
        })
            .then((res) => res.json())
            .then((data) => {
                isDuplicate = !!data.is_duplicate;
                if (isDuplicate) {
                    showWarning(data.message || 'Nama project ini sudah digunakan.');
                } else {
                    hideWarning();
                }
                setSubmitDisabled(isDuplicate);
            })
            .catch(() => {
                // Kalau gagal cek (jaringan dsb), jangan blok user - validasi server tetap final.
                isDuplicate = false;
                hideWarning();
                setSubmitDisabled(false);
            });
    }

    nameInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const value = this.value;
        debounceTimer = setTimeout(() => checkName(value), 400);
    });

    // Cek nilai awal (misal hasil restore dari validasi gagal sebelumnya)
    if (nameInput.value.trim()) {
        checkName(nameInput.value);
    }

    const form = document.querySelector(formSelector);
    if (form) {
        form.addEventListener('submit', function (e) {
            if (isDuplicate) {
                e.preventDefault();
            }
        });
    }

    return {
        isDuplicate: () => isDuplicate,
    };
};
