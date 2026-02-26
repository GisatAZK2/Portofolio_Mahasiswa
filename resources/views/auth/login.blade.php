<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk ke Akun</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f8f5f2] min-h-screen flex items-start justify-center pt-12 pb-12 px-5 sm:px-8">

    <div class="fixed inset-0 pointer-events-none opacity-[0.03]"
        style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\" 0 0 200 200\"
        xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cfilter id=\"n\"%3E%3CfeTurbulence type=\"fractalNoise\"
        baseFrequency=\"0.9\" numOctaves=\"3\"/%3E%3C/filter%3E%3Ccircle cx=\"100\" cy=\"100\" r=\"200\"
        filter=\"url(%23n)\"/%3E%3C/svg%3E');"></div>

    <div class="relative w-full max-w-lg">

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

        <form method="POST" action="{{ route('login') }}" class="space-y-7 sm:space-y-8">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email / Username</label>
                <input type="text" name="login" required autofocus value="{{ old('login') }}"
                    class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition @error('login') border-red-400 @enderror"
                    placeholder="Email atau username kamu">
                @error('login') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="relative w-full">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition @error('password') border-red-400 @enderror"
                    placeholder="Masukkan kata sandi">
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                <button type="button" data-toggle-password
                    class="absolute top-1/2 right-4 -translate-y-1/2 text-gray-500 hover:text-gray-700 z-10">

                </button>
            </div>



            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember"
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="remember" class="ml-2 text-sm text-gray-700">Ingat saya</label>
                </div>
            </div>

            <div class="mt-8 flex justify-center sm:justify-end">
                <button type="submit"
                    class="px-10 py-4 bg-linear-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300">
                    Masuk Sekarang →
                </button>
            </div>

            <p class="text-center mt-6 text-gray-600 text-sm sm:text-base">
                Belum punya akun?
                <a href="{{ route('register') }}"
                    class="text-blue-600 hover:text-blue-800 font-medium underline-offset-4 hover:underline">
                    Daftar dulu yuk
                </a>
            </p>
        </form>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showSuccessAlert('{{ session('success') }}');
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showErrorAlert('{{ $errors->first() }}');
            });

            document.addEventListener('click', function (e) {

                const toggleBtn = e.target.closest('[data-toggle-password]');
                if (!toggleBtn) return;

                const input = toggleBtn.closest('div').querySelector('input[type="password"], input[type="text"]');
                if (!input) return;

                if (input.type === 'password') {
                    input.type = 'text';
                    toggleBtn.textContent = '🙈';
                } else {
                    input.type = 'password';
                    toggleBtn.textContent = '👁';
                }
            });
        </script>
    @endif

</body>

</html>