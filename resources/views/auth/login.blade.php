<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk ke Akun - Portal Mahasiswa</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/Logo.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    </style>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- WebAuth PASSKEY -->
    <script src="https://cdn.jsdelivr.net/npm/@simplewebauthn/browser@10/dist/bundle/index.umd.min.js"></script>

</head>

<body
    class="bg-[#f8f5f2] min-h-screen flex items-start justify-center pt-12 pb-12 px-5 sm:px-8 font-sans antialiased relative">

    <!--    
    <audio id="welcomeSound" preload="auto">
    <source src="audio/welcome_sound.mp3" type="audio/mpeg">
    </audio> -->

    <!-- Background Noise -->
    <div class="fixed inset-0 pointer-events-none opacity-[0.03] bg-noise"></div>

    <div class="relative w-full max-w-lg">

        <!-- Header dengan efek miring -->
        <div class="relative mb-8 sm:mb-12">
            <h1
                class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 tracking-tight leading-none rotate-[-1.8deg] inline-block">
                Masuk Yuk
            </h1>
            <p class="mt-3 text-base sm:text-lg text-gray-600 max-w-md rotate-[-0.8deg]">
                Lanjutkan perjalanan kampusmu dari sini.
            </p>
            <div class="absolute -top-4 -left-8 w-24 sm:w-32 h-1 bg-blue-400 rotate-[-42deg] rounded-full opacity-80">
            </div>
        </div>

        <!-- Form Login -->
        <form method="POST" action="{{ route('login') }}" class="space-y-7 sm:space-y-8">
            @csrf

            <!-- Input Email/Username -->
            <div>
                <label for="login" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Email / Username
                </label>
                <input type="text" name="login" id="login" required autofocus value="{{ old('login') }}"
                    class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 focus:outline-none transition @error('login') border-red-400 @enderror"
                    placeholder="Email atau username kamu">
                @error('login')
                    @if(!in_array($message, ['PENGAJUAN_DIPROSES', 'PENGAJUAN_DITOLAK', 'AKUN_DIBLOKIR']))
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @endif
                @enderror
            </div>

            <!-- Input Password dengan Toggle -->
            <div class="relative w-full">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Password
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 focus:outline-none transition @error('password') border-red-400 @enderror"
                        placeholder="Masukkan kata sandi">
                    <button type="button" id="toggle-password-btn"
                        class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-500 hover:text-gray-700 transition"
                        aria-label="Toggle password visibility" onclick="togglePasswordVisibility()">
                        <!-- Eye Icon (Show) -->
                        <svg id="eye-icon-show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        <!-- Eye Off Icon (Hide) -->
                        <svg id="eye-icon-hide" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                            </path>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember"
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    <label for="remember" class="ml-2 text-sm text-gray-700 select-none">
                        Ingat saya
                    </label>
                </div>

                <!-- Forgot Password Link (Optional) -->
                @if(Route::has('password.forgot'))
                    <div>
                        <a href="{{ route('password.forgot') }}"
                            class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                            Lupa password?
                        </a>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col gap-6 sm:gap-8">
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 sm:items-center sm:justify-between">
                    
                    <div class="flex gap-3 sm:gap-4 justify-center sm:justify-start">
                        <!-- Website -->
                        <a href="{{ env('SOCIAL_WEBSITE', 'https://example.com') }}" target="_blank" rel="noopener noreferrer"
                            class="group w-11 h-11 sm:w-12 sm:h-12 bg-gradient-to-br rounded-full flex items-center justify-center border-2 border-blue-300 shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-300 transform"
                            title="Website">
                          <svg viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M498.183 .005c-147.445 .678 -291.229 66.62 -387.347 186.018l153.958 236.639c38.92 -110.963 150.567 -181.877 267.325 -170.051l414.127 22.061c-42.323 -84.026 -108.752 -157.098 -196.299 -207.641 -79.421 -45.855 -166.209 -67.42 -251.763 -67.026zm-416.533 226.203c-51.608 78.666 -81.65 172.734 -81.65 273.825 0 249.758 183.248 456.794 422.595 493.996l127.929 -251.638c-115.557 21.774 -232.78 -39.492 -280.918 -146.521l-187.956 -369.662zm884.812 93.837l-281.918 14.999c76.637 89.189 82.213 221.338 13.593 316.541l-226.172 347.6c93.931 5.361 190.433 -15.638 277.98 -66.183 216.297 -124.878 303.971 -387.076 216.516 -612.956zm-466.498 11.374c-93.11 0 -168.613 75.503 -168.613 168.613s75.503 168.613 168.613 168.613 168.613 -75.503 168.613 -168.613 -75.503 -168.613 -168.613 -168.613z" fill="#0e63ec"></path></g></svg>  
                        </a>

                        <!-- Instagram -->
                        <a href="{{ env('SOCIAL_INSTAGRAM', 'https://instagram.com') }}" target="_blank" rel="noopener noreferrer"
                            class="group w-11 h-11 sm:w-12 sm:h-12 bg-gradient-to-br from-pink-400 via-pink-500 to-red-500 rounded-full flex items-center justify-center border-2 border-pink-300 shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-300 transform"
                            title="Instagram">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M7 2H17C19.7614 2 22 4.23858 22 7V17C22 19.7614 19.7614 22 17 22H7C4.23858 22 2 19.7614 2 17V7C2 4.23858 4.23858 2 7 2ZM12 7C9.24 7 7 9.24 7 12C7 14.76 9.24 17 12 17C14.76 17 17 14.76 17 12C17 9.24 14.76 7 12 7ZM19.5 6.5C19.5 7.328 18.828 8 18 8C17.172 8 16.5 7.328 16.5 6.5C16.5 5.672 17.172 5 18 5C18.828 5 19.5 5.672 19.5 6.5ZM12 9C13.657 9 15 10.343 15 12C15 13.657 13.657 15 12 15C10.343 15 9 13.657 9 12C9 10.343 10.343 9 12 9Z"></path>
                            </svg>
                        </a>

                        <!-- YouTube -->
                        <a href="{{ env('SOCIAL_YOUTUBE', 'https://youtube.com') }}" target="_blank" rel="noopener noreferrer"
                            class="group w-11 h-11 sm:w-12 sm:h-12 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center border-2 border-red-400 shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-300 transform"
                            title="YouTube">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21.543 6.498C22 8.28 22 12 22 12s0 3.72-.457 5.502c-.254.985-.997 1.76-1.938 2.022C17.896 20 12 20 12 20s-5.893 0-6.605-.476c-.945-.266-1.687-1.04-1.938-2.022C3 15.72 3 12 3 12s0-3.72.457-5.502c.254-.985.997-1.76 1.938-2.022C6.107 4 12 4 12 4s5.896 0 6.605.476c.945.266 1.687 1.04 1.938 2.022zM10 15l6-3-6-3v6z"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Buttons on Right -->
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <!-- Cancel Button -->
                        <a href="{{ route('dashboard') }}"
                            class="flex-1 sm:flex-none px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-full shadow hover:bg-gray-200 hover:shadow-md active:scale-95 transition-all duration-300 text-center text-sm sm:text-base">
                            Batal
                        </a>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="flex-1 sm:flex-none px-8 sm:px-10 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm sm:text-base">
                            Masuk Sekarang →
                        </button>
                    </div>
                </div>

                           <!-- SEPARATOR -->
                <div class="relative my-2">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-3 bg-[#f8f5f2] text-gray-500">atau</span>
                    </div>
                </div>

                <!-- TOMBOL LOGIN DENGAN PASSKEY -->
                <button type="button" id="loginWithPasskeyBtn"
                    class="w-full flex items-center justify-center gap-3 border-2 border-gray-300 rounded-xl px-4 py-3 hover:bg-gray-100 hover:border-blue-400 transition-all duration-300">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                    </svg>
                    <span class="font-medium text-gray-700">Masuk dengan Passkey (Face ID / Fingerprint)</span>
                </button>

                <p class="text-center mt-6 text-gray-600 text-sm sm:text-base">
                    Belum punya akun?
                    <a href="{{ route('pengajuan-akun') }}"
                        class="text-blue-600 hover:text-blue-800 font-medium underline-offset-4 hover:underline">
                        Ajukan Akun Ke Admin
                    </a>
                </p>
            </div>
        </form>
    </div>

 <script>
        // Password visibility toggle
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeShowIcon = document.getElementById('eye-icon-show');
            const eyeHideIcon = document.getElementById('eye-icon-hide');

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

        // Login dengan Passkey
        async function loginWithPasskey() {
            const loginInput = document.getElementById('login');
            const emailOrUsername = loginInput.value.trim();
            
            if (!emailOrUsername) {
                showAlert('Silakan masukkan email atau username terlebih dahulu', 'warning');
                loginInput.focus();
                return;
            }

            // Disable button dan show loading
            const btn = document.getElementById('loginWithPasskeyBtn');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses...</span>
            `;

            try {
                // Step 1: Get authentication options from server
                const optionsResponse = await fetch('/webauthn/login/options', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: emailOrUsername })
                });

                if (!optionsResponse.ok) {
                    const error = await optionsResponse.json();
                    throw new Error(error.message || 'Gagal mendapatkan options');
                }

                const options = await optionsResponse.json();

                // Step 2: Prompt device for biometric/PIN
                const assertion = await SimpleWebAuthnBrowser.startAuthentication(options);

                // Step 3: Verify and login
                const verifyResponse = await fetch('/webauthn/login/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ credential: assertion })
                });

                const result = await verifyResponse.json();

                if (result.success) {
                    showAlert('Login berhasil! Mengalihkan...', 'success');
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1000);
                } else {
                    throw new Error(result.message || 'Login gagal');
                }

            } catch (error) {
                console.error('Passkey login error:', error);
                
                let errorMessage = 'Terjadi kesalahan saat login dengan passkey';
                if (error.name === 'NotAllowedError') {
                    errorMessage = 'Autentikasi dibatalkan atau ditolak';
                } else if (error.name === 'NotSupportedError') {
                    errorMessage = 'Browser Anda tidak mendukung WebAuthn/Passkey';
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                showAlert(errorMessage, 'error');
            } finally {
                // Restore button
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        }

        // Show alert function
        function showAlert(message, type = 'error') {
            const alertDiv = document.getElementById('passkeyAlert');
            alertDiv.classList.remove('hidden', 'bg-green-100', 'bg-red-100', 'bg-yellow-100', 'text-green-800', 'text-red-800', 'text-yellow-800');
            
            if (type === 'success') {
                alertDiv.classList.add('bg-green-100', 'text-green-800');
            } else if (type === 'warning') {
                alertDiv.classList.add('bg-yellow-100', 'text-yellow-800');
            } else {
                alertDiv.classList.add('bg-red-100', 'text-red-800');
            }
            
            alertDiv.innerHTML = message;
            alertDiv.classList.remove('hidden');
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                alertDiv.classList.add('hidden');
            }, 5000);
        }

        // Event listener for passkey button
        document.getElementById('loginWithPasskeyBtn').addEventListener('click', loginWithPasskey);

        // Handle Enter key on login input
        document.getElementById('login').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                loginWithPasskey();
            }
        });

        // Session success/error handling
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showAlert('{{ session('success') }}', 'success');
            @endif

            @if ($errors->any())
                @php
                    $firstError = $errors->first();
                @endphp
                @if($firstError === 'PENGAJUAN_DIPROSES')
                    showAlert('Pengajuan akun Anda sedang diproses. Mohon tunggu konfirmasi dari admin.', 'warning');
                @elseif($firstError === 'PENGAJUAN_DITOLAK')
                    showAlert('Pengajuan akun Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.', 'error');
                @elseif($firstError === 'AKUN_DIBLOKIR')
                    showAlert('Akun Anda diblokir. Silakan hubungi admin untuk informasi lebih lanjut.', 'error');
                @elseif(!in_array($firstError, ['PENGAJUAN_DIPROSES', 'PENGAJUAN_DITOLAK', 'AKUN_DIBLOKIR']))
                    showAlert('{{ $firstError }}', 'error');
                @endif
            @endif
        });

        // Prevent double submit on normal form
        const form = document.getElementById('loginForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitButton = this.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = 'Memproses...';
                }
            });
        }
    </script>

</body>

</html>