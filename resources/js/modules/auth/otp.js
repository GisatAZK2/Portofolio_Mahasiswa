/**
 * modules/auth/otp.js
 * OTP timer, resend OTP, password strength, init pages.
 */

let otpTimerInterval;
let otpTimeLeft = 300;
let otpCanResend = false;
let otpIsTimerRunning = true;

window.initOtpTimer = function (initialSeconds = 300, resendUrl) {
    otpTimeLeft = initialSeconds;
    otpIsTimerRunning = true;
    otpCanResend = false;
    clearInterval(otpTimerInterval);
    updateOtpTimerDisplay();

    otpTimerInterval = setInterval(() => {
        if (otpTimeLeft > 0 && otpIsTimerRunning) {
            otpTimeLeft--;
            updateOtpTimerDisplay();
        } else if (otpTimeLeft <= 0) {
            clearInterval(otpTimerInterval);
            otpIsTimerRunning = false;
            otpCanResend = true;
            const resendBtn = document.getElementById('resend-otp-btn');
            if (resendBtn) {
                resendBtn.disabled = false;
                resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning', title: 'Kode OTP Kadaluarsa',
                    text: 'Kode OTP sudah kadaluarsa. Silakan kirim ulang kode baru.',
                    confirmButtonColor: '#3b82f6', confirmButtonText: 'Kirim Ulang'
                }).then((result) => {
                    if (result.isConfirmed) window.resendOtp(resendUrl);
                });
            }
        }
    }, 1000);
};

function formatOtpTime(seconds) {
    const mins = Math.floor(Math.max(0, seconds) / 60);
    const secs = Math.max(0, seconds) % 60;
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}

function updateOtpTimerDisplay() {
    const timerElement = document.getElementById('timer');
    const timerBar = document.getElementById('timer-bar');
    if (timerElement) timerElement.textContent = formatOtpTime(otpTimeLeft);
    if (timerBar) {
        const percentage = (Math.max(0, otpTimeLeft) / 300) * 100;
        timerBar.style.width = `${percentage}%`;
        if (percentage < 20) {
            timerBar.classList.remove('bg-blue-500', 'bg-orange-500');
            timerBar.classList.add('bg-red-500');
        } else if (percentage < 50) {
            timerBar.classList.remove('bg-blue-500', 'bg-red-500');
            timerBar.classList.add('bg-orange-500');
        } else {
            timerBar.classList.remove('bg-red-500', 'bg-orange-500');
            timerBar.classList.add('bg-blue-500');
        }
    }
}

window.resendOtp = function (resendUrl) {
    if (!otpCanResend && otpTimeLeft > 0) {
        Swal.fire?.({
            icon: 'info', title: 'Tunggu Sebentar',
            text: `Silakan tunggu ${formatOtpTime(otpTimeLeft)} sebelum meminta kode baru.`,
            confirmButtonColor: '#3b82f6', confirmButtonText: 'OK'
        });
        return;
    }
    const email = document.getElementById('email-input')?.value || '';
    if (!email) {
        Swal.fire?.({ icon: 'error', title: 'Error', text: 'Email tidak ditemukan. Silakan ulangi proses dari awal.', confirmButtonColor: '#3b82f6' });
        return;
    }
    Swal.fire?.({ title: 'Mengirim Kode OTP...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    fetch(resendUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ email })
    })
        .then(r => r.json())
        .then(data => {
            Swal.close?.();
            if (data.success) {
                Swal.fire?.({ icon: 'success', title: 'Berhasil!', text: 'Kode OTP baru telah dikirim ke email Anda.', confirmButtonColor: '#3b82f6', timer: 2000 });
                window.initOtpTimer(300, resendUrl);
                const otpInput = document.getElementById('otp');
                if (otpInput) otpInput.value = '';
            } else {
                Swal.fire?.({ icon: 'error', title: 'Gagal', text: data.message || 'Gagal mengirim kode OTP.', confirmButtonColor: '#3b82f6' });
            }
        })
        .catch(() => {
            Swal.close?.();
            Swal.fire?.({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan. Silakan coba lagi.', confirmButtonColor: '#3b82f6' });
        });
};

window.clearOtp = function () {
    const otpInput = document.getElementById('otp');
    if (otpInput) { otpInput.value = ''; otpInput.focus(); }
};

// ==========================================
// PAGE INITIALIZERS (auth pages)
// ==========================================

export function initVerifyOtp() {
    const form = document.getElementById('otpForm');
    const otpInput = document.getElementById('otp');
    const clearBtn = document.getElementById('clear-otp-btn');
    const resendBtn = document.getElementById('resend-otp-btn');
    if (!form || !otpInput) return;

    const resendUrl = resendBtn?.getAttribute('data-resend-url') || '';
    window.initOtpTimer(300, resendUrl);

    if (clearBtn) clearBtn.addEventListener('click', window.clearOtp);
    if (resendBtn) resendBtn.addEventListener('click', () => window.resendOtp(resendUrl));

    otpInput.addEventListener('input', function (e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 6) this.value = this.value.slice(0, 6);
        if (clearBtn) {
            clearBtn.classList.toggle('opacity-0', this.value.length === 0);
            clearBtn.classList.toggle('pointer-events-none', this.value.length === 0);
            clearBtn.classList.toggle('opacity-100', this.value.length > 0);
            clearBtn.classList.toggle('pointer-events-auto', this.value.length > 0);
        }
    });
}

export function initForgotPassword() {
    const emailForm = document.querySelector('form[action*="sendOtp"]');
    if (!emailForm) return;
    // basic submit handling — let the form submit naturally, just show loading
    emailForm.addEventListener('submit', function () {
        const btn = emailForm.querySelector('[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span>Mengirim...';
        }
    });
}

export function initResetPassword() {
    const pwdInput = document.getElementById('password');
    const confirmInput = document.getElementById('password-confirm');
    if (!pwdInput || !confirmInput) return;
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');

    pwdInput.addEventListener('input', function () {
        const result = calculatePasswordStrength(this.value);
        if (strengthBar) {
            strengthBar.style.width = result.width + '%';
            strengthBar.style.backgroundColor = result.color;
        }
        if (strengthText) {
            strengthText.textContent = result.text;
            strengthText.style.color = result.color;
        }
    });

    confirmInput.addEventListener('input', function () {
        const errEl = document.getElementById('password-confirm-error');
        if (!errEl) return;
        if (this.value && this.value !== pwdInput.value) {
            errEl.classList.remove('hidden');
        } else {
            errEl.classList.add('hidden');
        }
    });
}

function calculatePasswordStrength(password) {
    if (!password) return { level: 0, color: '#e5e7eb', text: '', width: 0 };
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;

    const levels = [
        { color: '#ef4444', text: 'Sangat Lemah' },
        { color: '#f97316', text: 'Lemah' },
        { color: '#eab308', text: 'Cukup' },
        { color: '#22c55e', text: 'Kuat' },
        { color: '#16a34a', text: 'Sangat Kuat' },
    ];
    const level = Math.min(strength - 1, 4);
    return { level: strength, ...(levels[Math.max(0, level)] || levels[0]), width: (strength / 5) * 100 };
}
