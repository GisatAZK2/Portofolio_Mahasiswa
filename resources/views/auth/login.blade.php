<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk ke Akun - Portal Mahasiswa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        .bg-noise {
            background-image: url('data:image/svg+xml,%3Csvg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"%3E%3Cfilter id="n"%3E%3CfeTurbulence type="fractalNoise" baseFrequency="0.9" numOctaves="3"/%3E%3C/filter%3E%3Ccircle cx="100" cy="100" r="200" filter="url(%23n)"/%3E%3C/svg%3E');
        }
        
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            font-size: 1.25rem;
            line-height: 1;
            z-index: 10;
            color: #6b7280;
            transition: color 0.2s;
        }
        
        .toggle-password:hover {
            color: #374151;
        }

        .back-button {
            position: absolute;
            top: 1rem;
            left: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 9999px;
            color: #374151;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            z-index: 50;
        }

        .back-button:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
            transform: translateX(-4px);
        }

        .back-button:active {
            transform: translateX(0);
        }
    </style>
</head>

<body class="bg-[#f8f5f2] min-h-screen flex items-start justify-center pt-12 pb-12 px-5 sm:px-8 font-sans antialiased relative">
    
   
   <audio id="welcomeSound" preload="auto">
    <source src="audio/welcome_sound.mp3" type="audio/mpeg">
    </audio>

    <!-- Background Noise --> 
    <div class="fixed inset-0 pointer-events-none opacity-[0.03] bg-noise"></div>

    <div class="relative w-full max-w-lg">

        <!-- Header dengan efek miring -->
        <div class="relative mb-8 sm:mb-12">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 tracking-tight leading-none rotate-[-1.8deg] inline-block">
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
                <input 
                    type="text" 
                    name="login" 
                    id="login"
                    required 
                    autofocus 
                    value="{{ old('login') }}"
                    class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 focus:outline-none transition @error('login') border-red-400 @enderror"
                    placeholder="Email atau username kamu"
                >
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
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        required
                        class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 focus:outline-none transition @error('password') border-red-400 @enderror"
                        placeholder="Masukkan kata sandi"
                    >
                    <button 
                        type="button" 
                        class="toggle-password" 
                        aria-label="Toggle password visibility"
                        onclick="togglePasswordVisibility()"
                    >
                        👁
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        id="remember"
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                    >
                    <label for="remember" class="ml-2 text-sm text-gray-700 select-none">
                        Ingat saya
                    </label>
                </div>
                
                <!-- Forgot Password Link (Optional) -->
                @if(Route::has('password.request'))
                <div>
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                        Lupa password?
                    </a>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row gap-4 sm:justify-end">
                <!-- Cancel Button -->
                <a href="{{ route('dashboard') }}" 
                   class="px-8 py-4 bg-gray-100 text-gray-700 font-semibold rounded-full shadow hover:bg-gray-200 hover:shadow-md active:scale-95 transition-all duration-300 text-center">
                    Batal
                </a>
                
                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="px-10 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Masuk Sekarang →
                </button>
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
        // Fungsi untuk toggle password visibility
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.textContent = '︶';
            } else {
                passwordInput.type = 'password';
                toggleButton.textContent = '👁';
            }
        }

        // SweetAlert2 Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Show Success Alert
        function showSuccessAlert(message) {
            Toast.fire({
                icon: 'success',
                title: message
            });
        }

        // Show Error Alert
        function showErrorAlert(message, icon = 'error') {
            Swal.fire({
                icon: icon,
                title: 'Oops...',
                text: message,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Mengerti'
            });
        }

        // Show Confirm Alert
        async function showConfirmAlert(options) {
            const result = await Swal.fire({
                title: options.title || 'Apakah Anda yakin?',
                text: options.text || '',
                icon: options.icon || 'warning',
                showCancelButton: true,
                confirmButtonColor: options.confirmButtonColor || '#3085d6',
                cancelButtonColor: options.cancelButtonColor || '#d33',
                confirmButtonText: options.confirmButtonText || 'Ya',
                cancelButtonText: options.cancelButtonText || 'Batal'
            });
            
            return result.isConfirmed;
        }

        // Show Loading Alert
        function showLoading(message = 'Memproses...') {
            Swal.fire({
                title: message,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        // Handle semua alert saat DOM loaded
        document.addEventListener('DOMContentLoaded', function() {
            
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

    <!-- Optional: Add this if you want to handle AJAX login -->
    <script>
        // Optional: Handle AJAX login jika diperlukan
        function handleAjaxLogin(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            
            showLoading('Memverifikasi...');
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                
                if (data.success) {
                    showSuccessAlert(data.message);
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
                    showErrorAlert(data.message);
                }
            })
            .catch(error => {
                Swal.close();
                showErrorAlert('Terjadi kesalahan. Silakan coba lagi.');
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
        const audio = document.getElementById('welcomeSound');
        
        // Coba play otomatis
        audio.play().catch(() => {
            // Jika diblokir, play setelah user klik di mana saja
            document.body.addEventListener('click', function playOnce() {
                audio.play();
                document.body.removeEventListener('click', playOnce);
            }, { once: true });
        });
    });
    </script>

</body>

</html>