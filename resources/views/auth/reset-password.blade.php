@extends('auth.layout')

@section('title', autoTranslate('Reset Password'))

@section('content')
<div class="flex-grow flex items-start justify-center pt-12 pb-12 px-5 sm:px-8">
    <div class="relative w-full max-w-lg">
        <!-- Header dengan efek miring -->
        <div class="relative mb-8 sm:mb-12">
            <a href="{{ route('login') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 mb-4 group transition-all duration-300">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Login
            </a>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 tracking-tight leading-none rotate-[-1.8deg] inline-block">
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
                        onclick="togglePasswordVisibility('password')">
                        <svg class="eye-icon-show w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg class="eye-icon-hide w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        onclick="togglePasswordVisibility('password-confirm')">
                        <svg class="eye-icon-show w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg class="eye-icon-hide w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            <!-- Session Alert Data Container -->
            <div id="session-alert-data" 
                 class="hidden" 
                 data-session-success="{{ session('success') }}" 
                 data-session-error="{{ session('error') }}" 
                 data-errors-first="{{ $errors->any() ? $errors->first() : '' }}"></div>
        </form>
    </div>
</div>
@endsection