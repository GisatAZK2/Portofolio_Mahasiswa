@extends('auth.layout')

@section('title', 'Forgot Password')

@section('content')

    <main class="flex-grow flex items-start justify-center pt-12 pb-12 px-5 sm:px-8">

    <div class="relative w-full max-w-lg">

        <!-- Header dengan efek miring -->
        <div class="relative mb-13 sm:mb-15">
            <h1
                class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 tracking-tight leading-none rotate-[-1.8deg] inline-block">
                Lupa Password
            </h1>
            <p class="mt-3 text-base sm:text-lg text-gray-600 max-w-md rotate-[-0.8deg]">
                Tenang, kami akan kirimkan kode OTP ke email kamu.
            </p>
            <div class="absolute -top-4 -left-8 w-24 sm:w-32 h-1 bg-orange-400 rotate-[-42deg] rounded-full opacity-80">
            </div>
        </div>

        <!-- Form Forgot Password -->
        <form method="POST" action="{{ route('password.sendOtp') }}" class="space-y-7 mt-30 sm:space-y-8">
            @csrf

            <!-- Informasi Alert -->
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Input Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Alamat Email
                </label>
                <input type="email" name="email" id="email" required autofocus value="{{ old('email') }}"
                    class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-orange-500 focus:ring-0 focus:outline-none transition @error('email') border-red-400 @enderror"
                    placeholder="Masukkan email terdaftar">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-500">
                    * Pastikan email yang dimasukkan sudah terdaftar di sistem
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="mt-20 flex flex-col gap-6 sm:gap-8">
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <!-- Cancel Button -->
                    <a href="{{ route('login') }}"
                        class="flex-1 sm:flex-none px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-full shadow hover:bg-gray-200 hover:shadow-md active:scale-95 transition-all duration-300 text-center text-sm sm:text-base">
                        Batal
                    </a>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="flex-1 sm:flex-none px-8 sm:px-10 py-3 bg-gradient-to-r from-orange-600 to-orange-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 text-sm sm:text-base">
                        Kirim Kode OTP →
                    </button>
                </div>
            <!-- Session Alert Data Container -->
            <div id="session-alert-data" 
                 class="hidden" 
                 data-session-success="{{ session('success') }}" 
                 data-session-error="{{ session('error') }}" 
                 data-errors-first="{{ $errors->any() ? $errors->first() : '' }}"></div>
        </form>
    </div>
    </main>
@endsection