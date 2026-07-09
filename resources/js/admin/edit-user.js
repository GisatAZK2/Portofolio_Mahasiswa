/**
 * admin/edit-user.js
 * Halaman Admin Edit User: photo preview, form validation.
 */
(function () {
    function initEditUser() {
        // Cek apakah ini halaman edit user
        const form = document.querySelector('form[action*="admin/users/edit"]');
        if (!form) return;

        const photoInput = document.getElementById('photo_profile');
        const previewImage = document.getElementById('previewImage');
        const fileNameLabel = document.getElementById('fileNameLabel');
        const fileNameText = document.getElementById('fileNameText');

        // ── Photo preview ──
        if (photoInput && previewImage) {
            photoInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                    if (fileNameText && fileNameLabel) {
                        fileNameText.textContent = file.name;
                        fileNameLabel.classList.remove('hidden');
                        fileNameLabel.classList.add('flex');
                    }
                }
            });
        }

        // ── Password visibility toggle ──
        window.togglePassword = function (inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        };

        // ── Password strength hints ──
        const pwInput = document.getElementById('password');
        const pwConf = document.getElementById('password_confirmation');

        function setHint(iconId, valid) {
            const icon = document.getElementById(iconId);
            if (!icon) return;
            const check = icon.querySelector('i');
            if (valid) {
                icon.classList.add('border-green-400', 'bg-green-50', 'dark:bg-green-900/30');
                icon.classList.remove('border-amber-300', 'dark:border-amber-600');
                if (check) check.classList.remove('hidden');
            } else {
                icon.classList.remove('border-green-400', 'bg-green-50', 'dark:bg-green-900/30');
                icon.classList.add('border-amber-300', 'dark:border-amber-600');
                if (check) check.classList.add('hidden');
            }
        }

        function checkMatch() {
            const hint = document.getElementById('confirmMatchHint');
            if (!hint) return;
            if (!pwConf || !pwConf.value) {
                hint.classList.add('hidden');
                return;
            }
            hint.classList.remove('hidden');
            hint.classList.add('flex');
            if (pwInput && pwConf && pwInput.value === pwConf.value) {
                hint.innerHTML = '<i class="fas fa-circle-check text-green-500"></i><span class="text-green-600 dark:text-green-400">Password cocok</span>';
            } else {
                hint.innerHTML = '<i class="fas fa-circle-xmark text-red-500"></i><span class="text-red-500">Password tidak cocok</span>';
            }
        }

        if (pwInput) {
            pwInput.addEventListener('input', function () {
                const val = this.value;
                setHint('icon-length', val.length >= 8);
                setHint('icon-upper', /[A-Z]/.test(val));
                setHint('icon-space', val.length > 0 && !/\s/.test(val));
                checkMatch();
            });
        }

        if (pwConf) {
            pwConf.addEventListener('input', checkMatch);
        }

        // ── Reset button ──
        const resetBtn = document.getElementById('resetBtn');
        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                if (confirm('Apakah Anda yakin ingin mereset semua perubahan?')) {
                    const form = document.querySelector('form');
                    if (form) {
                        form.reset();
                        if (fileNameLabel) {
                            fileNameLabel.classList.add('hidden');
                            fileNameLabel.classList.remove('flex');
                        }
                        // Reset preview ke gambar awal (dari server)
                        const originalSrc = previewImage?.getAttribute('data-original-src') ||
                            previewImage?.src;
                        if (previewImage && originalSrc) {
                            previewImage.src = originalSrc;
                        }
                    }
                }
            });
            // Simpan src asli untuk reset
            if (previewImage) {
                previewImage.setAttribute('data-original-src', previewImage.src);
            }
        }

        // ── Tanggal lahir validation ──
        const tanggalLahirInput = document.getElementById('tanggal_lahir');
        if (tanggalLahirInput) {
            tanggalLahirInput.addEventListener('change', function () {
                const today = new Date().toISOString().split('T')[0];
                if (this.value > today) {
                    alert('Tanggal lahir tidak boleh melebihi tanggal hari ini!');
                    this.value = '';
                }
            });
        }

        // ── Page Info ──
    }

    // Inisialisasi saat DOM siap dan setelah Turbo load
    document.addEventListener('DOMContentLoaded', initEditUser);
    document.addEventListener('turbo:load', initEditUser);
})();

