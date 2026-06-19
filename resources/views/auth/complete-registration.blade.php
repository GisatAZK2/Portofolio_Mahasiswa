@extends('auth.layout')

@section('title', __('Lengkapi Data Akun'))

@section('content')
    <main class="flex-grow flex items-start justify-center pt-12 pb-12 px-5 sm:px-8">
        <div class="relative max-w-4xl w-full">
            <div class="mb-8 sm:mb-12">
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 tracking-tight leading-none inline-block"
                    data-translate="title" data-translate-page="complete_registration">
                    Lengkapi Data Akun
                </h1>
                <p class="mt-3 text-base sm:text-lg text-gray-600"
                   data-translate="subtitle" data-translate-page="complete_registration">
                    Isi data berikut untuk melengkapi pendaftaran Anda.
                </p>
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700"
                           data-translate="alert_info" data-translate-page="complete_registration">
                            Data berikut sudah terisi berdasarkan informasi awal Anda. Mohon lengkapi data yang masih kosong.
                        </p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('register.complete.submit') }}" enctype="multipart/form-data" class="space-y-7 sm:space-y-8">
                @csrf
                <input type="hidden" name="completing_registration" value="true">

                <!-- Read-only Data Section -->
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4"
                        data-translate="readonly_title" data-translate-page="complete_registration">
                        Data Diri (Tidak Dapat Diubah)
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- NIM -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5"
                                   data-translate="label_nim" data-translate-page="complete_registration">NIM</label>
                            <input type="text" value="{{ $tempUserData['nim'] }}" readonly
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-600">
                        </div>
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5"
                                   data-translate="label_nama_mahasiswa" data-translate-page="complete_registration">Nama Lengkap</label>
                            <input type="text" value="{{ $tempUserData['nama_mahasiswa'] }}" readonly
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-600">
                        </div>
                        <!-- Tanggal Lahir -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5"
                                   data-translate="label_tanggal_lahir" data-translate-page="complete_registration">Tanggal Lahir</label>
                            <input type="text" value="{{ \Carbon\Carbon::parse($tempUserData['tanggal_lahir'])->format('d/m/Y') }}" readonly
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-600">
                        </div>
                        <!-- Jurusan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5"
                                   data-translate="label_jurusan" data-translate-page="complete_registration">Jurusan</label>
                            @php
                                $jurusan = $jurusans->firstWhere('id_jurusan', $tempUserData['id_jurusan']);
                            @endphp
                            <input type="text" value="{{ $jurusan->nama_jurusan ?? '-' }}" readonly
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-600">
                        </div>
                        <!-- Keahlian -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5"
                                   data-translate="label_keahlian" data-translate-page="complete_registration">Keahlian</label>
                            @php
                                $keahlian = $keahlians->firstWhere('id_keahlian', $tempUserData['id_keahlian']);
                            @endphp
                            <input type="text" value="{{ $keahlian->nama_keahlian ?? '-' }}" readonly
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-600">
                        </div>
                        <!-- Angkatan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5"
                                   data-translate="label_angkatan" data-translate-page="complete_registration">Angkatan</label>
                            @php
                                $angkatan = $angkatans->firstWhere('id', $tempUserData['id_angkatan']);
                            @endphp
                            <input type="text" value="{{ $angkatan->nama_angkatan ?? '-' }}" readonly
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-600">
                        </div>
                    </div>
                </div>

                <!-- Fields to Complete -->
                <div class="space-y-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4"
                        data-translate="complete_title" data-translate-page="complete_registration">
                        Data yang Perlu Dilengkapi
                    </h2>

                    <div class="flex flex-col items-center">
                        <label class="block text-sm font-medium text-gray-700 mb-3"
                               data-translate="photo_profile_label" data-translate-page="complete_registration">Foto Profil (opsional)</label>

                        <div class="relative group cursor-pointer">
                            <div class="w-28 h-28 sm:w-32 sm:h-32 md:w-36 md:h-36 rounded-full overflow-hidden border-4 border-white shadow-xl transition-all duration-300 group-hover:shadow-blue-300/50 bg-gray-100 flex items-center justify-center">
                                <img id="profile-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                                <svg id="profile-placeholder" class="w-12 h-12 sm:w-14 sm:h-14 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="absolute inset-0 rounded-full bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <span class="text-white text-xs sm:text-sm font-medium px-3 py-1.5 bg-blue-600/80 rounded-full"
                                      data-translate="choose_photo" data-translate-page="complete_registration">Pilih Foto</span>
                            </div>
                            <input type="file" name="photo_profile" id="photo_profile" accept="image/*"
                                class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>

                        @error('photo_profile')
                            <p class="mt-2 text-xs sm:text-sm text-red-600 text-center">{{ $message }}</p>
                        @enderror

                        <p class="mt-2 text-xs text-gray-500 text-center"
                           data-translate="photo_hint" data-translate-page="complete_registration">JPG/PNG • Maks. 2MB</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5"
                               data-translate="label_email" data-translate-page="complete_registration">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl shadow-[inset_0_2px_6px_rgba(0,0,0,0.04)] focus:border-blue-500 focus:ring-0 transition @error('email') border-red-400 @enderror"
                            placeholder="{{ __('email_placeholder') }}" data-translate-placeholder="email_placeholder" data-translate-page="complete_registration">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5"
                               data-translate="label_username" data-translate-page="complete_registration">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" required value="{{ old('username') }}"
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition @error('username') border-red-400 @enderror"
                            placeholder="{{ __('username_placeholder') }}" data-translate-placeholder="username_placeholder" data-translate-page="complete_registration">
                        <p class="mt-1 text-xs text-gray-500"
                           data-translate="username_hint" data-translate-page="complete_registration">Hanya huruf, angka, dan underscore (_)</p>
                        @error('username') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5"
                               data-translate="label_password" data-translate-page="complete_registration">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                minlength="8" pattern="^(?=.*[A-Z])(?!.*\s).{8,}$"
                                title="{{ __('password_title') }}"
                                class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition @error('password') border-red-400 @enderror"
                                placeholder="{{ __('password_placeholder') }}" data-translate-placeholder="password_placeholder" data-translate-page="complete_registration">
                            <button type="button" class="toggle-password-btn absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-500 hover:text-gray-700 transition"
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
                        <div class="mt-2 p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <p class="text-xs font-semibold text-blue-900 mb-2"
                               data-translate="password_requirements_title" data-translate-page="complete_registration">Syarat Password:</p>
                            <ul class="text-xs text-blue-800 space-y-1">
                                <li class="flex items-center gap-2"
                                    data-translate="password_req_min" data-translate-page="complete_registration">✓ Minimal 8 karakter</li>
                                <li class="flex items-center gap-2"
                                    data-translate="password_req_upper" data-translate-page="complete_registration">✓ Harus mengandung minimal 1 huruf besar (A-Z)</li>
                                <li class="flex items-center gap-2"
                                    data-translate="password_req_nospace" data-translate-page="complete_registration">✓ Tidak boleh ada spasi</li>
                            </ul>
                        </div>
                        @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5"
                               data-translate="label_password_confirmation" data-translate-page="complete_registration">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition"
                                placeholder="{{ __('password_confirmation_placeholder') }}" data-translate-placeholder="password_confirmation_placeholder" data-translate-page="complete_registration">
                            <button type="button" class="toggle-password-btn absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-500 hover:text-gray-700 transition"
                                onclick="togglePasswordVisibility('password_confirmation')">
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5"
                               data-translate="label_description" data-translate-page="complete_registration">Deskripsi Singkat</label>
                        <textarea name="description" rows="3"
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition @error('description') border-red-400 @enderror"
                            placeholder="{{ __('description_placeholder') }}" data-translate-placeholder="description_placeholder" data-translate-page="complete_registration">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5"
                               data-translate="label_video_url" data-translate-page="complete_registration">Video URL (YouTube/Vimeo)</label>
                        <input type="url" name="video_url" value="{{ old('video_url') }}"
                            class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition @error('video_url') border-red-400 @enderror"
                            placeholder="{{ __('video_url_placeholder') }}" data-translate-placeholder="video_url_placeholder" data-translate-page="complete_registration">
                        @error('video_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5"
                               data-translate="label_background_image" data-translate-page="complete_registration">Gambar Background</label>
                        <div id="backgroundPreviewContainer" class="mb-4 hidden">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2"
                               data-translate="background_preview_label" data-translate-page="complete_registration">Preview Background:</p>
                            <img id="backgroundPreview" src="#" alt="Background Preview"
                                class="w-full max-h-48 object-cover rounded-lg border-2 border-blue-500">
                        </div>
                        <div class="relative group">
                            <input type="file" name="background_image" id="background_image" accept="image/jpeg,image/png,image/jpg,image/webp"
                                class="w-full px-4 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition @error('background_image') border-red-400 @enderror">
                        </div>
                        <p class="mt-1 text-xs text-gray-500"
                           data-translate="background_hint" data-translate-page="complete_registration">Format: JPG, PNG, JPEG, WEBP. Maksimal 5MB.</p>
                        @error('background_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-10 flex justify-end gap-4">
                    <a href="{{ route('login') }}"
                        class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition"
                        data-translate="btn_cancel" data-translate-page="complete_registration">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transition"
                        data-translate="btn_submit" data-translate-page="complete_registration">
                        Lengkapi Data →
                    </button>
                </div>
            </form>
            <!-- Session Alert Data Container -->
            <div id="session-alert-data" 
                 class="hidden" 
                 data-session-warning="{{ session('warning') }}" 
                 data-errors-all="{{ implode('<br>', $errors->all()) }}"></div>
        </div>
    </main>
@endsection