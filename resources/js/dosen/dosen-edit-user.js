function initDosenEditUserPage() {
    const form = document.querySelector('form[action*="dosen/users/edit"]');
    if (!form) return;

    const photoInput = document.getElementById('photo_profile');
    const previewImage = document.getElementById('previewImage');
    const fileNameLabel = document.getElementById('fileNameLabel');
    const fileNameText = document.getElementById('fileNameText');
    const pwInput = document.getElementById('password');
    const pwConf = document.getElementById('password_confirmation');

    window.togglePassword = function (fieldId, iconId) {
        const input = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        icon.classList.toggle('fa-eye', !isPassword);
        icon.classList.toggle('fa-eye-slash', isPassword);
    };

    if (photoInput && previewImage) {
        photoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const reader = new FileReader();
                reader.onload = (event) => {
                    previewImage.src = event.target.result;
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

    const setHint = (iconId, valid) => {
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
    };

    const checkMatch = () => {
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
    };

    if (pwInput) {
        pwInput.addEventListener('input', () => {
            const val = pwInput.value;
            setHint('icon-length', val.length >= 8);
            setHint('icon-upper', /[A-Z]/.test(val));
            setHint('icon-space', val.length > 0 && !/\s/.test(val));
            checkMatch();
        });
    }

    if (pwConf) pwConf.addEventListener('input', checkMatch);

    const resetBtn = document.getElementById('resetBtn');
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            if (confirm('Apakah Anda yakin ingin mereset semua perubahan?')) {
                form.reset();
                if (fileNameLabel) {
                    fileNameLabel.classList.add('hidden');
                    fileNameLabel.classList.remove('flex');
                }
                const originalSrc = previewImage?.getAttribute('data-original-src') || previewImage?.src;
                if (previewImage && originalSrc) previewImage.src = originalSrc;
            }
        });
        if (previewImage) previewImage.setAttribute('data-original-src', previewImage.src);
    }

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
}

document.addEventListener('DOMContentLoaded', initDosenEditUserPage);
document.addEventListener('turbo:load', initDosenEditUserPage);
