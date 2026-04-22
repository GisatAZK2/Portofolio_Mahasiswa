<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi OTP - Portal Mahasiswa</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/Logo.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body
    class="bg-[#f8f5f2] min-h-screen flex items-start justify-center pt-12 pb-12 px-5 sm:px-8 font-sans antialiased relative">

    <!-- Background Noise -->
    <div class="fixed inset-0 pointer-events-none opacity-[0.03] bg-noise"></div>

    <div class="relative w-full max-w-lg">

        <!-- Header dengan efek miring -->
        <div class="relative mb-8 sm:mb-12">
            <a href="{{ route('password.forgot') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 mb-4 group transition-all duration-300">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Lupa Password
            </a>
            <h1
                class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 tracking-tight leading-none rotate-[-1.8deg] inline-block">
                Verifikasi OTP
            </h1>
            <p class="mt-3 text-base sm:text-lg text-gray-600 max-w-md rotate-[-0.8deg]">
                Masukkan kode verifikasi yang dikirim ke email kamu.
            </p>
            <div class="absolute -top-4 -left-8 w-24 sm:w-32 h-1 bg-purple-400 rotate-[-42deg] rounded-full opacity-80">
            </div>
        </div>

        <!-- Informasi Email -->
        <div class="mb-6 p-4 bg-purple-50 border-l-4 border-purple-500 rounded-lg shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-purple-700">
                        Kode OTP telah dikirim ke: <strong>{{ $email ?? old('email') }}</strong>
                    </p>
                    <p class="text-xs text-purple-600 mt-1">
                        * Kode valid selama 5 menit
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Verify OTP -->
        <form method="POST" action="{{ route('password.verify') }}" class="space-y-7 sm:space-y-8">
            @csrf

            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

            <!-- Input OTP -->
            <div>
                <label for="otp" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Kode OTP
                </label>
                <div class="relative">
                    <input type="text" name="otp" id="otp" required autofocus value="{{ old('otp') }}"
                        class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-purple-500 focus:ring-0 focus:outline-none transition @error('otp') border-red-400 @enderror text-center text-2xl tracking-widest font-mono"
                        placeholder="• • • • • •"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        inputmode="numeric">
                    
                    <!-- Clear button -->
                    <button type="button"
                        id="clear-otp-btn"
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-400 hover:text-gray-600 transition opacity-0 pointer-events-none"
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
                        class="text-purple-600 hover:text-purple-800 font-medium hover:underline focus:outline-none focus:ring-2 focus:ring-purple-500 rounded px-2 py-1 transition"
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
                    <div class="font-mono text-lg font-bold text-purple-600" id="timer">
                        05:00
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                    <div id="timer-bar" class="bg-purple-500 h-1.5 rounded-full transition-all duration-1000" style="width: 100%"></div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col gap-6 sm:gap-8">
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <!-- Cancel Button -->
                    <a href="{{ route('password.forgot') }}"
                        class="flex-1 sm:flex-none px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-full shadow hover:bg-gray-200 hover:shadow-md active:scale-95 transition-all duration-300 text-center text-sm sm:text-base">
                        Batal
                    </a>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="flex-1 sm:flex-none px-8 sm:px-10 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 text-sm sm:text-base">
                        Verifikasi OTP →
                    </button>
                </div>

                <!-- Link ke halaman lain -->
                <p class="text-center mt-6 text-gray-600 text-sm sm:text-base">
                    Belum menerima kode?
                    <button type="button"
                        onclick="resendOtp()"
                        class="text-purple-600 hover:text-purple-800 font-medium underline-offset-4 hover:underline focus:outline-none focus:ring-2 focus:ring-purple-500 rounded">
                        Kirim Ulang Kode
                    </button>
                </p>

                <p class="text-center text-gray-500 text-xs">
                    Atau
                    <a href="{{ route('login') }}"
                        class="text-blue-600 hover:text-blue-800 hover:underline">
                        Kembali ke halaman login
                    </a>
                </p>
            </div>

        </form>
    </div>

    <script>
        // Timer variables
        let timerInterval;
        let timeLeft = 300; // 5 minutes in seconds
        let canResend = false;
        
        // Format time as MM:SS
        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
        
        // Update timer display
        function updateTimer() {
            const timerElement = document.getElementById('timer');
            const timerBar = document.getElementById('timer-bar');
            
            if (timerElement) {
                timerElement.textContent = formatTime(timeLeft);
            }
            
            if (timerBar) {
                const percentage = (timeLeft / 300) * 100;
                timerBar.style.width = `${percentage}%`;
                
                // Change color based on time left
                if (percentage < 20) {
                    timerBar.classList.remove('bg-purple-500');
                    timerBar.classList.add('bg-red-500');
                } else if (percentage < 50) {
                    timerBar.classList.remove('bg-purple-500');
                    timerBar.classList.add('bg-orange-500');
                }
            }
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                canResend = true;
                const resendBtn = document.getElementById('resend-otp-btn');
                if (resendBtn) {
                    resendBtn.disabled = false;
                    resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
                
                // Show warning
                Swal.fire({
                    icon: 'warning',
                    title: 'Kode OTP Kadaluarsa',
                    text: 'Kode OTP sudah kadaluarsa. Silakan kirim ulang kode baru.',
                    confirmButtonColor: '#8b5cf6',
                    confirmButtonText: 'Kirim Ulang'
                }).then((result) => {
                    if (result.isConfirmed) {
                        resendOtp();
                    }
                });
            }
        }
        
        // Start timer
        function startTimer() {
            clearInterval(timerInterval);
            timeLeft = 300;
            canResend = false;
            
            // Disable resend button initially
            const resendBtn = document.getElementById('resend-otp-btn');
            if (resendBtn) {
                resendBtn.disabled = true;
                resendBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
            
            updateTimer();
            timerInterval = setInterval(() => {
                if (timeLeft > 0) {
                    timeLeft--;
                    updateTimer();
                } else {
                    clearInterval(timerInterval);
                }
            }, 1000);
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
                confirmButtonColor: '#8b5cf6',
                confirmButtonText: 'Lanjutkan',
                timer: 2000,
                timerProgressBar: true
            }).then(() => {
                // Redirect to reset password page with email
                const email = document.querySelector('input[name="email"]').value;
                window.location.href = '{{ route("password.reset.form") }}?email=' + encodeURIComponent(email);
            });
        }
        
        function showErrorAlert(message, icon = 'error') {
            Swal.fire({
                icon: icon,
                title: icon === 'error' ? 'Oops...' : 'Info',
                text: message,
                confirmButtonColor: '#8b5cf6',
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
            // Start timer
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
                    
                    // Auto-submit when 6 digits are entered
                    if (this.value.length === 6) {
                        // Optional: auto-submit form
                        // document.querySelector('form').submit();
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
            
            // Auto-hide flash messages
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert, .bg-green-50, .bg-red-50, .bg-purple-50');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
        
        // Prevent double submit with validation
        const form = document.querySelector('form');
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
                    
                    // Optional: show loading
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
        const cancelButton = document.querySelector('a[href="{{ route("password.forgot") }}"]');
        
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
                    confirmButtonColor: '#8b5cf6',
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
        document.getElementById('otp').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const form = document.querySelector('form');
                if (form && this.value.length === 6) {
                    form.dispatchEvent(new Event('submit'));
                }
            }
        });
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
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
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
    </style>

</body>

</html>