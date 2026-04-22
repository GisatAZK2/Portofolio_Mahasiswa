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

            <!-- Link Register -->
            <p class="text-center mt-6 text-gray-600 text-sm sm:text-base">
                Belum punya akun?
                <a href="{{ route('pengajuan-akun') }}"
                    class="text-blue-600 hover:text-blue-800 font-medium underline-offset-4 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">
                    Ajukan Akun Ke Admin
                </a>
            </p>


        </form>
    </div>

    <script>


        // password visibility
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


        // Dom
        document.addEventListener('DOMContentLoaded', function () {

            // Handle session success
            @if (session('success'))
                showSuccessAlert('{{ session('success') }}');
            @endif

            // Handle custom error messages
            @if ($errors->any())
                    @php
                        $firstError = $errors->first();
                    @endphp
                @if($firstError === 'PENGAJUAN_DIPROSES')
                    showErrorAlert(
                        'Pengajuan akun Anda sedang diproses. Mohon tunggu konfirmasi dari admin.',
                        'info'
                        );
                @elseif($firstError === 'PENGAJUAN_DITOLAK')
                    showErrorAlert(
                        'Pengajuan akun Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.',
                        'error'
                        );
                @elseif($firstError === 'AKUN_DIBLOKIR')
                    showErrorAlert(
                        'Akun Anda diblokir. Silakan hubungi admin untuk informasi lebih lanjut.',
                        'error'
                        );
                @elseif(!in_array($firstError, ['PENGAJUAN_DIPROSES', 'PENGAJUAN_DITOLAK', 'AKUN_DIBLOKIR']))
                    showErrorAlert('{{ $firstError }}');
                @endif
            @endif

            // Optional: Auto-hide flash messages setelah beberapa detik
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });

        // Prevent double submit
        const form = document.querySelector('form');
        if (form) {
             form.addEventListener('submit', function(e) {
                const submitButton = this.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
submitButton.innerHTML = 'Memproses...';
                    
                    // Optional: show loading
                    showLoading('Memverifikasi akun...');
                }
            });
        }

        // Handle input focus untuk menghilangkan error message
        const loginInput = document.getElementById('login');
        if (loginInput) {
             loginInput.addEventListener('focus', function() {
                const errorElement = this.parentElement.querySelector('.text-red-600');
                if (errorElement) {
                    errorElement.remove();
                }
            });
        }

        // Handle back button confirmation jika form sudah diisi
        const backButton = document.querySelector('.back-button');
const cancelButton = document.querySelector('a[href="{{ route('dashboard') }}"]');
        
        function handleBackClick(e) {
            const formInputs = document.querySelectorAll('input[type="text"], input[type="password"]');
let isFormFilled = false;
            
            formInputs.forEach(input => {
                if (input.value.trim() !== '') {
                    isFormFilled = true;
                }
});
            
            if (isFormFilled) {
                e.preventDefault();
                showConfirmAlert({
                    title: 'Yakin ingin kembali?',
                    text: 'Data yang sudah diisi akan hilang.',
                    icon: 'question',
                    confirmButtonText: 'Ya, Kembali',
                    cancelButtonText: 'Tetap di Sini'
                }).then((confirmed) => {
                    if (confirmed) {
                        window.location.href = '{{ route('dashboard') }}';
                    }
                });
            }
        }

        if (backButton) {
            backButton.addEventListener('click', handleBackClick);
}
        
        if (cancelButton) {
            cancelButton.addEventListener('click', handleBackClick);
        }
    </script>

</body>

</html>