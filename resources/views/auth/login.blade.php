@extends('auth.layout')

@section('title', __('Login'))

@section('content')
    <main class="flex-grow flex items-start justify-center pt-12 pb-12 px-5 sm:px-8">
        <div class="relative w-full max-w-lg">

            <div class="relative mb-8 sm:mb-12">
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 tracking-tight leading-none rotate-[-1.8deg] inline-block"
                    data-translate="title" data-translate-page="login">
                    Masuk Yuk
                </h1>
                <p class="mt-3 text-base sm:text-lg text-gray-600 max-w-md rotate-[-0.8deg]"
                   data-translate="subtitle" data-translate-page="login">
                    Lanjutkan perjalanan kampusmu dari sini.
                </p>
                <div class="absolute -top-4 -left-8 w-24 sm:w-32 h-1 bg-blue-400 rotate-[-42deg] rounded-full opacity-80"></div>
            </div>

            <div id="alertMessage" class="hidden mb-4 p-3 rounded-lg text-sm"></div>

            <form method="POST" action="{{ route('login') }}" class="space-y-7 sm:space-y-8" id="loginForm">
                @csrf

                <div>
                    <label for="login" class="block text-sm font-medium text-gray-700 mb-1.5"
                           data-translate="label_login" data-translate-page="login">
                        Email / Username
                    </label>
                    <input type="text" name="login" id="login" required autofocus value="{{ old('login') }}"
                        class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 focus:outline-none transition @error('login') border-red-400 @enderror"
                        placeholder="{{ __('login_placeholder') }}" data-translate-placeholder="login_placeholder" data-translate-page="login">
                    @error('login')
                        @if(!in_array($message, ['PENGAJUAN_DIPROSES', 'PENGAJUAN_DITOLAK', 'AKUN_DIBLOKIR']))
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @endif
                    @enderror
                </div>

                <div class="relative w-full">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5"
                           data-translate="label_password" data-translate-page="login">
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 focus:outline-none transition @error('password') border-red-400 @enderror"
                            placeholder="{{ __('password_placeholder') }}" data-translate-placeholder="password_placeholder" data-translate-page="login">
                        <button type="button" id="toggle-password-btn"
                            class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-500 hover:text-gray-700 transition"
                            onclick="togglePasswordVisibility()">
                            <svg id="eye-icon-show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg id="eye-icon-hide" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember"
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <label for="remember" class="ml-2 text-sm text-gray-700 select-none"
                               data-translate="label_remember" data-translate-page="login">
                            Ingat saya
                        </label>
                    </div>
                    @if(Route::has('password.forgot'))
                        <div>
                            <a href="{{ route('password.forgot') }}"
                                class="text-sm text-blue-600 hover:text-blue-800 hover:underline"
                                data-translate="link_forgot_password" data-translate-page="login">
                                Lupa password?
                            </a>
                        </div>
                    @endif
                </div>

                <div class="mt-8 flex flex-col gap-6 sm:gap-8">
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 sm:items-center sm:justify-between">
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <a href="{{ route('dashboard') }}"
                                class="flex-1 sm:flex-none px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-full shadow hover:bg-gray-200 hover:shadow-md active:scale-95 transition-all duration-300 text-center text-sm sm:text-base"
                                data-translate="btn_cancel" data-translate-page="login">
                                Batal
                            </a>
                            <button type="submit"
                                class="flex-1 sm:flex-none px-8 sm:px-10 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 text-sm sm:text-base"
                                data-translate="btn_submit" data-translate-page="login">
                                Masuk Sekarang →
                            </button>
                        </div>
                    </div>

                    <p class="text-center mt-6 text-gray-600 text-sm sm:text-base">
                        <span data-translate="register_prompt" data-translate-page="login">
                            Belum punya akun?
                        </span>
                        <a href="{{ route('pengajuan-akun') }}"
                            class="text-blue-600 hover:text-blue-800 font-medium underline-offset-4 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500 rounded"
                            data-translate="register_link"
                            data-translate-page="login">
                            Daftar sekarang
                        </a>
                    </p>

                    <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium"
                                   data-translate="passkey_info_title" data-translate-page="login">Keamanan Ekstra dengan Passkey</p>
                                <p class="text-xs mt-0.5"
                                   data-translate="passkey_info_desc" data-translate-page="login">
                                    Jika Anda memiliki passkey (Face ID/Touch ID), Anda akan diminta verifikasi 
                                    tambahan setelah memasukkan password untuk keamanan maksimal.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div id="session-alert-data" 
                         class="hidden" 
                         data-session-success="{{ session('success') }}" 
                         data-session-error="{{ session('error') }}" 
                         data-errors-first="{{ $errors->any() ? $errors->first() : '' }}"></div>
                </div>
            </form>
        </div>
    </main>
@endsection