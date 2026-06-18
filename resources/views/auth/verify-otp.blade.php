@extends('auth.layout')

@section('title', 'Verifikasi')

@section('content')

    <main class="flex-grow flex items-start justify-center pt-12 pb-12 px-5 sm:px-8">
    <!-- Background Decorative Elements -->
    <div class="fixed top-0 right-0 w-96 h-96 bg-blue-200/20 rounded-full blur-3xl -z-10"></div>
    <div class="fixed bottom-0 left-0 w-96 h-96 bg-indigo-200/20 rounded-full blur-3xl -z-10"></div>

    <div class="relative w-full max-w-lg fade-in-up">

        <!-- Header dengan efek miring -->
        <div class="relative mb-8 sm:mb-12">
            <a href="{{ route('password.forgot') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-4 group transition-all duration-300">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Lupa Password
            </a>
            <h1
                class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 tracking-tight leading-none rotate-[-1.8deg] inline-block bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                Verifikasi OTP
            </h1>
            <p class="mt-3 text-base sm:text-lg text-gray-600 max-w-md rotate-[-0.8deg]">
                Masukkan kode verifikasi yang dikirim ke email kamu.
            </p>
            <div class="absolute -top-4 -left-8 w-24 sm:w-32 h-1 bg-blue-400 rotate-[-42deg] rounded-full opacity-80">
            </div>
        </div>

        <!-- Informasi Email -->
        <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Kode OTP telah dikirim ke: <strong id="email-display">{{ $email ?? old('email') }}</strong>
                    </p>
                    <p class="text-xs text-blue-600 mt-1">
                        * Kode valid selama 5 menit
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Verify OTP -->
        <form method="POST" action="{{ route('password.verify') }}" class="space-y-7 sm:space-y-8" id="otpForm">
            @csrf

            <input type="hidden" name="email" id="email-input" value="{{ $email ?? old('email') }}">

            <!-- Input OTP -->
            <div>
                <label for="otp" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Kode OTP
                </label>
                <div class="relative">
                    <input type="text" name="otp" id="otp" required autofocus 
                        class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 focus:outline-none transition text-center text-2xl tracking-widest font-mono @error('otp') border-red-400 @enderror"
                        placeholder="• • • • • •"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        inputmode="numeric">
                    
                    <!-- Clear button -->
                    <button type="button"
                        id="clear-otp-btn"
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-400 hover:text-blue-600 transition opacity-0 pointer-events-none"
                        onclick="clearOtp()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- OTP Hint -->
                <div class="mt-2 flex items-center justify-between text-xs">
                    <p class="text-gray-500">
                        * Masukkan kode 6 digit yang dikirim ke email
                    </p>
                    <button type="button"
                        id="resend-otp-btn"
                        class="text-blue-600 hover:text-blue-800 font-medium hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-2 py-1 transition"
                        onclick="resendOtp()">
                        Kirim Ulang Kode
                    </button>
                </div>
                
                @error('otp')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Timer Countdown -->
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm text-gray-600">Kode berlaku selama:</span>
                    </div>
                    <div class="font-mono text-lg font-bold text-blue-600" id="timer">
                        05:00
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                    <div id="timer-bar" class="bg-blue-500 h-1.5 rounded-full transition-all duration-1000" style="width: 100%"></div>
                </div>
            </div>

            <!-- Action Buttons - Tombol di Kanan -->
            <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3 sm:gap-4">
                <!-- Cancel Button -->
                <a href="{{ route('password.forgot') }}"
                    class="cancel-btn px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-full shadow hover:bg-gray-200 hover:shadow-md active:scale-95 transition-all duration-300 text-center text-sm sm:text-base">
                    Batal
                </a>

                <!-- Submit Button -->
                <button type="submit"
                    class="px-8 sm:px-10 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-full shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm sm:text-base">
                    Verifikasi OTP →
                </button>
            </div>

            <!-- Link ke halaman lain -->
            <div class="text-center mt-4">
                <p class="text-gray-600 text-sm">
                    Atau
                    <a href="{{ route('login') }}"
                        class="text-blue-600 hover:text-blue-800 hover:underline">
                        Kembali ke halaman login
                    </a>
                </p>
            </div>

        </form>
    </div>
    </main>

    <script>
        // Timer variables - selalu reset ke 5 menit setiap halaman dimuat
        let timerInterval;
        let timeLeft = 300; // 5 minutes in seconds (RESET setiap load)
        let canResend = false;
        let isTimerRunning = true;
        
        // Format time as MM:SS
        function formatTime(seconds) {
            const mins = Math.floor(Math.max(0, seconds) / 60);
            const secs = Math.max(0, seconds) % 60;
            return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
        
        // Update timer display
        function updateTimerDisplay() {
            const timerElement = document.getElementById('timer');
            const timerBar = document.getElementById('timer-bar');
            
            if (timerElement) {
                timerElement.textContent = formatTime(timeLeft);
            }
            
            if (timerBar) {
                const percentage = (Math.max(0, timeLeft) / 300) * 100;
                timerBar.style.width = `${percentage}%`;
                
                // Change color based on time left
                if (percentage < 20) {
                    timerBar.classList.remove('bg-blue-500');
                    timerBar.classList.add('bg-red-500');
                } else if (percentage < 50) {
                    timerBar.classList.remove('bg-blue-500');
                    timerBar.classList.add('bg-orange-500');
                } else {
                    timerBar.classList.remove('bg-red-500', 'bg-orange-500');
                    timerBar.classList.add('bg-blue-500');
                }
            }
        }
        
        // Enable resend button
        function enableResendButton() {
            canResend = true;
            const resendBtn = document.getElementById('resend-otp-btn');
            if (resendBtn) {
                resendBtn.disabled = false;
                resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }
        
        // Update timer and check expiry
        function updateTimer() {
            if (!isTimerRunning) return;
            
            updateTimerDisplay();
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                isTimerRunning = false;
                enableResendButton();
                
                // Show warning
                Swal.fire({
                    icon: 'warning',
                    title: 'Kode OTP Kadaluarsa',
                    text: 'Kode OTP sudah kadaluarsa. Silakan kirim ulang kode baru.',
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: 'Kirim Ulang'
                }).then((result) => {
                    if (result.isConfirmed) {
                        resendOtp();
                    }
                });
            }
        }
        
        // Start timer (selalu mulai dari 5 menit)
        function startTimer() {
            clearInterval(timerInterval);
            
            // Disable resend button
            canResend = false;
            const resendBtn = document.getElementById('resend-otp-btn');
            if (resendBtn) {
                resendBtn.disabled = true;
                resendBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
            
            updateTimerDisplay();
            timerInterval = setInterval(() => {
                if (timeLeft > 0 && isTimerRunning) {
                    timeLeft--;
                    updateTimer();
                } else if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                }
            }, 1000);
        }
        
        // Reset timer ke 5 menit
        function resetTimer() {
            clearInterval(timerInterval);
            timeLeft = 300;
            isTimerRunning = true;
            startTimer();
        }

        // Resend OTP function
        function resendOtp() {
            if (!canResend && timeLeft > 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Tunggu Sebentar',
                    text: `Silakan tunggu ${formatTime(timeLeft)} sebelum meminta kode baru.`,
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            // Get email from hidden input
            const emailInput = document.getElementById('email-input');
            const email = emailInput ? emailInput.value : '';
            
            if (!email) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Email tidak ditemukan. Silakan ulangi proses dari awal.',
                    confirmButtonColor: '#3b82f6'
                }).then(() => {
                    window.location.href = '{{ route("password.forgot") }}';
                });
                return;
            }
            
            // Show loading
            Swal.fire({
                title: 'Mengirim Kode OTP...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send AJAX request to resend OTP
            fetch('{{ route("password.resendOtp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Kode OTP baru telah dikirim ke email Anda.',
                        confirmButtonColor: '#3b82f6',
                        timer: 2000
                    });
                    resetTimer();
                    // Clear OTP input
                    const otpInput = document.getElementById('otp');
                    if (otpInput) otpInput.value = '';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Gagal mengirim kode OTP. Silakan coba lagi.',
                        confirmButtonColor: '#3b82f6'
                    });
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan. Silakan coba lagi.',
                    confirmButtonColor: '#3b82f6'
                });
            });
        }        
        
        // Clear OTP input
        function clearOtp() {
            const otpInput = document.getElementById('otp');
            if (otpInput) {
                otpInput.value = '';
                otpInput.focus();
            }
        }
        
        // SweetAlert functions
        function showSuccessAlert(message) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'Lanjutkan',
                timer: 2000,
                timerProgressBar: true
            }).then(() => {
                const email = document.getElementById('email-input').value;
                window.location.href = '{{ route("password.reset.form") }}?email=' + encodeURIComponent(email);
            });
        }
        
        function showErrorAlert(message, icon = 'error') {
            Swal.fire({
                icon: icon,
                title: icon === 'error' ? 'Oops...' : 'Info',
                text: message,
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'OK'
            });
        }
        
        function showLoading(message = 'Memverifikasi...') {
            Swal.fire({
                title: message,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        // DOM Ready
        document.addEventListener('DOMContentLoaded', function () {
            // Mulai timer dari 5 menit (RESET setiap halaman dimuat)
            startTimer();
            
            // Auto-format OTP input (only numbers)
            const otpInput = document.getElementById('otp');
            const clearBtn = document.getElementById('clear-otp-btn');
            
            if (otpInput) {
                otpInput.addEventListener('input', function(e) {
                    // Remove non-numeric characters
                    this.value = this.value.replace(/[^0-9]/g, '');
                    
                    // Limit to 6 characters
                    if (this.value.length > 6) {
                        this.value = this.value.slice(0, 6);
                    }
                    
                    // Show/hide clear button
                    if (clearBtn) {
                        if (this.value.length > 0) {
                            clearBtn.classList.remove('opacity-0', 'pointer-events-none');
                            clearBtn.classList.add('opacity-100', 'pointer-events-auto');
                        } else {
                            clearBtn.classList.remove('opacity-100', 'pointer-events-auto');
                            clearBtn.classList.add('opacity-0', 'pointer-events-none');
                        }
                    }
                });
                
                // Add paste event handler
                otpInput.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                    const numbers = pastedText.replace(/[^0-9]/g, '').slice(0, 6);
                    this.value = numbers;
                    
                    // Trigger input event
                    const event = new Event('input', { bubbles: true });
                    this.dispatchEvent(event);
                });
            }
            
            // Handle session success
            @if (session('success'))
                showSuccessAlert('{{ session('success') }}');
            @endif
            
            // Handle errors from server
            @if ($errors->any())
                @php
                    $firstError = $errors->first();
                @endphp
                showErrorAlert('{{ $firstError }}');
            @endif
        });
        
        // Prevent double submit with validation
        const form = document.getElementById('otpForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitButton = this.querySelector('button[type="submit"]');
                const otp = document.getElementById('otp').value;
                
                // Validasi OTP
                if (!otp) {
                    e.preventDefault();
                    showErrorAlert('Silakan masukkan kode OTP.');
                    return false;
                }
                
                if (otp.length !== 6) {
                    e.preventDefault();
                    showErrorAlert('Kode OTP harus 6 digit.');
                    return false;
                }
                
                if (!/^\d+$/.test(otp)) {
                    e.preventDefault();
                    showErrorAlert('Kode OTP hanya boleh berisi angka.');
                    return false;
                }
                
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = 'Memverifikasi...';
                    showLoading('Memverifikasi kode OTP...');
                }
            });
        }
        
        // Handle input focus untuk menghilangkan error message
        const otpField = document.getElementById('otp');
        if (otpField) {
            otpField.addEventListener('focus', function() {
                const errorElement = this.parentElement.parentElement.querySelector('.text-red-600');
                if (errorElement) {
                    errorElement.style.transition = 'opacity 0.3s';
                    errorElement.style.opacity = '0';
                    setTimeout(() => errorElement.remove(), 300);
                }
            });
        }
        
        // Handle back button confirmation jika OTP sudah diisi
        const cancelButton = document.querySelector('.cancel-btn');
        
        function handleBackClick(e) {
            const otpInput = document.getElementById('otp');
            const isOtpFilled = otpInput && otpInput.value.trim() !== '';
            
            if (isOtpFilled) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin ingin kembali?',
                    text: 'Kode OTP yang sudah dimasukkan akan hilang.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3b82f6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Kembali',
                    cancelButtonText: 'Tetap di Sini'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("password.forgot") }}';
                    }
                });
            }
        }
        
        if (cancelButton) {
            cancelButton.addEventListener('click', handleBackClick);
        }
        
        // Add keyboard support for OTP input (Enter key)
        if (otpField) {
            otpField.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && this.value.length === 6) {
                    e.preventDefault();
                    form.dispatchEvent(new Event('submit'));
                }
            });
        }
    </script>

    <style>
        /* Additional custom styles for better animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .bg-noise {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
            background-repeat: repeat;
            background-size: 200px;
        }
        
        /* Smooth transition untuk semua elemen */
        * {
            transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
        
        /* Custom focus ring */
        input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* OTP input styling */
        #otp {
            letter-spacing: 0.5em;
            font-size: 1.5rem;
        }
        
        #otp::placeholder {
            letter-spacing: normal;
            font-size: 1rem;
        }
        
        /* Scroll behavior */
        html {
            scroll-behavior: smooth;
        }
        
        /* Loading animation untuk button */
        button:disabled {
            cursor: not-allowed;
            opacity: 0.7;
        }
        
        /* Timer animation */
        #timer-bar {
            transition: width 1s linear, background-color 0.3s ease;
        }
        
        /* Resend button animation */
        #resend-otp-btn:active {
            transform: scale(0.95);
        }
        
        /* Clear button animation */
        #clear-otp-btn {
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        
        #clear-otp-btn:hover {
            transform: scale(1.1);
        }
        
        /* Dark mode input autofill style - REMOVED */
        /* Autofill style kept but dark mode specific rules removed */
        input:-webkit-autofill,
        input:-webkit-autofill:focus {
            transition: background-color 600000s 0s, color 600000s 0s;
        }
    </style>
@endsection