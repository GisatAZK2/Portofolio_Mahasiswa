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
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-400 hover:text-blue-600 transition opacity-0 pointer-events-none">
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
                        data-resend-url="{{ route('password.resendOtp') }}"
                        class="text-blue-600 hover:text-blue-800 font-medium hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-2 py-1 transition">
                        Kirim Ulang Kode
                    </button>
                </div>
                
                @error('otp')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Session Alert Data Container -->
            <div id="session-alert-data" 
                 class="hidden" 
                 data-session-success="{{ session('success') }}" 
                 data-session-error="{{ session('error') }}" 
                 data-errors-first="{{ $errors->any() ? $errors->first() : '' }}"></div>

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
@endsection