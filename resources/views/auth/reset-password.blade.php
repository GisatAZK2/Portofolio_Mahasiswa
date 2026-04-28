@extends('auth.layout')

@section('title', autoTranslate('Reset Password'))

@section('content')

    <main class="flex-grow flex items-start justify-center pt-12 pb-12 px-5 sm:px-8">


    <div class="relative w-full max-w-lg">

        <!-- Header dengan efek miring -->
        <div class="relative mb-8 sm:mb-12">
            <a href="{{ route('login') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 mb-4 group transition-all duration-300">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Login
            </a>
            <h1
                class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 tracking-tight leading-none rotate-[-1.8deg] inline-block">
                Reset Password
            </h1>
            <p class="mt-3 text-base sm:text-lg text-gray-600 max-w-md rotate-[-0.8deg]">
                Buat password baru untuk akunmu.
            </p>
            <div class="absolute -top-4 -left-8 w-24 sm:w-32 h-1 bg-green-400 rotate-[-42deg] rounded-full opacity-80">
            </div>
        </div>

        <!-- Informasi Email -->
        <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Mereset password untuk email: <strong>{{ $email ?? old('email') }}</strong>
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Reset Password -->
        <form method="POST" action="{{ route('password.reset') }}" class="space-y-7 sm:space-y-8">
            @csrf

            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

            <!-- Password Baru -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Password Baru
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-green-500 focus:ring-0 focus:outline-none transition @error('password') border-red-400 @enderror"
                        placeholder="Minimal 8 karakter">
                    <button type="button"
                        class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-500 hover:text-gray-700 transition"
                        onclick="togglePasswordVisibility('password', 'eye-icon-show-password', 'eye-icon-hide-password')">
                        <svg id="eye-icon-show-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg id="eye-icon-hide-password" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
                <p class="mt-2 text-xs text-gray-500">
                    * Password minimal 8 karakter, mengandung huruf dan angka
                </p>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Konfirmasi Password Baru
                </label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password-confirm" required
                        class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-green-500 focus:ring-0 focus:outline-none transition"
                        placeholder="Ulangi password baru">
                    <button type="button"
                        class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-500 hover:text-gray-700 transition"
                        onclick="togglePasswordVisibility('password-confirm', 'eye-icon-show-confirm', 'eye-icon-hide-confirm')">
                        <svg id="eye-icon-show-confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg id="eye-icon-hide-confirm" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Password Strength Indicator -->
            <div class="space-y-2">
                <div class="flex items-center gap-2 text-xs">
                    <span class="text-gray-600">Kekuatan Password:</span>
                    <span id="password-strength-text" class="font-medium">-</span>
                </div>
                <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                    <div id="password-strength-bar" class="h-full w-0 transition-all duration-300 rounded-full"></div>
                </div>
                <div class="flex gap-2 text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Lemah
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span> Sedang
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Kuat
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col gap-6 sm:gap-8">
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <!-- Cancel Button -->
                    <a href="{{ route('login') }}"
                        class="flex-1 sm:flex-none px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-full shadow hover:bg-gray-200 hover:shadow-md active:scale-95 transition-all duration-300 text-center text-sm sm:text-base">
                        Batal
                    </a>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="flex-1 sm:flex-none px-8 sm:px-10 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-sm sm:text-base">
                        Reset Password →
                    </button>
                </div>


                <p class="text-center text-gray-500 text-xs">
                    Atau
                    <a href="{{ route('password.forgot') }}"
                        class="text-blue-600 hover:text-blue-800 hover:underline">
                        Kirim ulang kode OTP
                    </a>
                </p>
            </div>

        </form>
    </div>
</mian>

    <script>
        // Password visibility toggle function
        function togglePasswordVisibility(inputId, showIconId, hideIconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeShowIcon = document.getElementById(showIconId);
            const eyeHideIcon = document.getElementById(hideIconId);

            if (passwordInput && eyeShowIcon && eyeHideIcon) {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeShowIcon.classList.add('hidden');
                    eyeHideIcon.classList.remove('hidden');
                } else {
                    passwordInput.type = 'password';
                    eyeShowIcon.classList.remove('hidden');
                    eyeHideIcon.classList.add('hidden');
                }
            }
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;
            
            let strengthLevel = '';
            let strengthColor = '';
            let strengthText = '';
            
            if (password.length === 0) {
                strengthLevel = '';
                strengthColor = '';
                strengthText = '-';
            } else if (strength <= 2) {
                strengthLevel = 'weak';
                strengthColor = 'bg-red-500';
                strengthText = 'Lemah';
            } else if (strength === 3 || strength === 4) {
                strengthLevel = 'medium';
                strengthColor = 'bg-orange-500';
                strengthText = 'Sedang';
            } else {
                strengthLevel = 'strong';
                strengthColor = 'bg-green-500';
                strengthText = 'Kuat';
            }
            
            return { level: strengthLevel, color: strengthColor, text: strengthText, width: (strength / 5) * 100 };
        }

        // SweetAlert functions
        function showSuccessAlert(message) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            }).then(() => {
                window.location.href = '{{ route("login") }}';
            });
        }

        function showErrorAlert(message, icon = 'error') {
            Swal.fire({
                icon: icon,
                title: icon === 'error' ? 'Oops...' : 'Info',
                text: message,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'OK'
            });
        }

        function showLoading(message = 'Memproses...') {
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
            // Password strength checker
            const passwordInput = document.getElementById('password');
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');
            
            if (passwordInput && strengthBar && strengthText) {
                passwordInput.addEventListener('input', function() {
                    const result = checkPasswordStrength(this.value);
                    strengthBar.className = `h-full transition-all duration-300 rounded-full ${result.color}`;
                    strengthBar.style.width = `${result.width}%`;
                    strengthText.textContent = result.text;
                    strengthText.className = `font-medium ${
                        result.level === 'weak' ? 'text-red-600' : 
                        result.level === 'medium' ? 'text-orange-600' : 
                        result.level === 'strong' ? 'text-green-600' : ''
                    }`;
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
                const alerts = document.querySelectorAll('.alert, .bg-green-50, .bg-red-50, .bg-blue-50');
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
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password-confirm').value;
                
                // Validasi password
                if (!password) {
                    e.preventDefault();
                    showErrorAlert('Silakan masukkan password baru Anda.');
                    return false;
                }
                
                if (password.length < 8) {
                    e.preventDefault();
                    showErrorAlert('Password minimal 8 karakter.');
                    return false;
                }
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    showErrorAlert('Konfirmasi password tidak cocok. Silakan periksa kembali.');
                    return false;
                }
                
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = 'Memproses...';
                    
                    // Optional: show loading
                    showLoading('Mereset password...');
                }
            });
        }

        // Handle input focus untuk menghilangkan error message
        const inputs = ['password', 'password-confirm'];
        inputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('focus', function() {
                    const errorElement = this.parentElement.parentElement.querySelector('.text-red-600');
                    if (errorElement) {
                        errorElement.style.transition = 'opacity 0.3s';
                        errorElement.style.opacity = '0';
                        setTimeout(() => errorElement.remove(), 300);
                    }
                });
            }
        });

        // Handle back button confirmation jika password sudah diisi
        const cancelButton = document.querySelector('a[href="{{ route("login") }}"]');
        
        function handleBackClick(e) {
            const passwordInput = document.getElementById('password');
            const isPasswordFilled = passwordInput && passwordInput.value.trim() !== '';
            
            if (isPasswordFilled) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin ingin kembali?',
                    text: 'Password yang sudah diisi akan hilang.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Kembali',
                    cancelButtonText: 'Tetap di Sini'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("login") }}';
                    }
                });
            }
        }

        if (cancelButton) {
            cancelButton.addEventListener('click', handleBackClick);
        }

        // Real-time password match indicator
        const passwordField = document.getElementById('password');
        const confirmField = document.getElementById('password-confirm');
        
        if (passwordField && confirmField) {
            const createMatchIndicator = () => {
                let indicator = document.getElementById('password-match-indicator');
                if (!indicator) {
                    indicator = document.createElement('div');
                    indicator.id = 'password-match-indicator';
                    indicator.className = 'mt-2 text-xs';
                    confirmField.parentElement.parentElement.appendChild(indicator);
                }
                return indicator;
            };
            
            const checkMatch = () => {
                const indicator = createMatchIndicator();
                if (confirmField.value === '') {
                    indicator.innerHTML = '';
                    indicator.className = 'mt-2 text-xs';
                } else if (passwordField.value === confirmField.value) {
                    indicator.innerHTML = '<span class="text-green-600 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Password cocok</span>';
                    indicator.className = 'mt-2 text-xs';
                } else {
                    indicator.innerHTML = '<span class="text-red-600 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Password tidak cocok</span>';
                    indicator.className = 'mt-2 text-xs';
                }
            };
            
            passwordField.addEventListener('input', checkMatch);
            confirmField.addEventListener('input', checkMatch);
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
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
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
        
        /* Password strength animation */
        #password-strength-bar {
            transition: width 0.3s ease, background-color 0.3s ease;
        }
    </style>
@endsection