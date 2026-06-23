@extends('Layout.Layout')

@section('title', 'Profil Saya')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css">
    <script src="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.js"></script>
    <style>
        #cropper-modal {
            overflow-y: auto;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        #cropper-modal > div {
            width: min(95vw, 42rem);
            max-height: calc(90vh - 2rem);
            display: flex;
            flex-direction: column;
        }
        #cropper-modal .cropper-container {
            max-width: 100%;
            height: 100%;
        }
        #cropper-modal .cropper-body {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            padding-bottom: 1rem;
        }
        #cropper-modal .cropper-footer {
            flex-shrink: 0;
            position: sticky;
            bottom: 0;
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(10px);
            z-index: 1000;
        }
        #cropper-modal .dark .cropper-footer,
        #cropper-modal.dark .cropper-footer {
            background: rgba(15,23,42,0.98);
        }
        #cropper-preview-container {
            min-height: 170px;
            min-width: auto;
        }
        #cropper-preview-container .cropper-preview img {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }
    </style>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-6">

            <!-- Skeleton Loading Overlay -->
            <div id="skeleton-loading" class="contents">
                <div class="relative h-48 rounded-t-2xl overflow-hidden bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 animate-pulse"></div>
                <div class="bg-white dark:bg-gray-800 rounded-b-2xl shadow-sm px-6 pb-8 sm:px-10 animate-pulse">
                    <div class="relative flex">
                        <div class="relative -mt-16">
                            <div class="relative w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 bg-gray-300 dark:bg-gray-600 animate-pulse"></div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="w-48 h-8 bg-gray-300 dark:bg-gray-600 rounded mb-2 animate-pulse"></div>
                        <div class="w-32 h-4 bg-gray-300 dark:bg-gray-600 rounded animate-pulse"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 max-w-3xl mx-auto mt-8">
                        <template x-for="n in 6">
                            <div class="bg-gray-200 dark:bg-gray-700 p-5 rounded-lg animate-pulse h-20"></div>
                        </template>
                    </div>
                </div>
            </div>

             <!-- Actual Content -->
<div id="actual-content" class="contents" style="display: none;">
    <script>
        window.__keahlianTambahanConfig = {
            options: @json($keahlians),
            mainSkillId: @json(Auth::user()->id_keahlian),
            list: @json($user->keahlianTambahan ?? []),
            selected: @json(old('id_keahlian_tambahan', '')),
            indexUrl: @json(route('keahlian-tambahan.index')),
            csrfToken: @json(csrf_token())
        };
    </script>
    <div x-data="keahlianTambahan(window.__keahlianTambahanConfig)" x-init="init()" class="contents">

                    <!-- Alert Notification -->
                    <template x-if="showAlert">
                        <div class="fixed top-4 right-4 z-50 max-w-md w-full animate-slide-down">
                            <div class="rounded-lg shadow-lg p-4" :class="{
                                'bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800': alertType === 'success',
                                'bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800': alertType === 'error',
                                'bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800': alertType === 'warning'
                            }">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg x-show="alertType === 'success'" class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <svg x-show="alertType === 'error'" class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium" :class="{
                                            'text-green-800 dark:text-green-200': alertType === 'success',
                                            'text-red-800 dark:text-red-200': alertType === 'error',
                                            'text-yellow-800 dark:text-yellow-200': alertType === 'warning'
                                        }" x-text="alertMessage"></p>
                                    </div>
                                    <button @click="showAlert = false" class="ml-4 flex-shrink-0">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <form id="form-profile" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Cover Image -->
                        <div class="relative h-48 rounded-t-2xl overflow-hidden
                            @if(Auth::user()->background_url) bg-cover bg-center
                            @else bg-gradient-to-br from-indigo-500 via-indigo-600 to-blue-600 @endif"
                            @if(Auth::user()->background_url) style="background-image: url('{{ asset('storage/' . Auth::user()->background_url) }}');" @endif>
                            <label for="background_input" class="absolute top-4 right-4 bg-white/90 dark:bg-gray-800/90 dark:text-gray-200 backdrop-blur-sm px-4 py-2 rounded-lg text-sm font-medium cursor-pointer hover:bg-white dark:hover:bg-gray-800 shadow-md transition">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span data-translate="bg_cov" data-translate-page="profile"></span>
                            </label>
                            <input type="file" name="background_url" id="background_input" class="hidden" accept="image/*" />
                        </div>

                        <!-- Profile Section -->
                        <div class="bg-white dark:bg-gray-800 rounded-b-2xl shadow-sm px-6 pb-8 sm:px-10">

                            <!-- Avatar -->
                            <div class="relative flex">
                                <div class="relative -mt-16 group">
                                    <div class="relative w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 bg-white dark:bg-gray-700 shadow-xl overflow-hidden">
                                        @if (Auth::user()->photo_profile)
                                            <img id="profile-preview" src="{{ asset('storage/' . Auth::user()->photo_profile) }}" alt="Foto Profil" class="w-full h-full object-cover">
                                        @else
                                            <div id="profile-preview-placeholder" class="w-full h-full bg-indigo-600 flex items-center justify-center text-white text-5xl font-semibold">
                                                {{ strtoupper(substr(Auth::user()->nama_mahasiswa ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                            <label for="photo_profile_input" class="cursor-pointer w-full h-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </label>
                                        </div>
                                        <input type="file" name="photo_profile" id="photo_profile_input" accept="image/*" class="hidden">
                                    </div>
                                </div>
                            </div>

                            <div id="cropper-modal" class="hidden fixed inset-0 z-50 flex items-start sm:items-center justify-center bg-black/60 p-2 sm:p-4">
                                <div class="w-full max-w-[42rem] rounded-3xl bg-white dark:bg-gray-900 overflow-hidden shadow-2xl">
                                    <div class="flex items-start justify-between gap-3 px-4 py-4 border-b border-gray-200 dark:border-gray-700">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white" data-translate="cropper_title" data-translate-page="profile">Sesuaikan Foto Profil</h3>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" data-translate="cropper_desc" data-translate-page="profile">Posisikan dan perbesar gambar lalu pilih Gunakan Foto untuk melihat preview.</p>
                                        </div>
                                        <button type="button" onclick="closeCropperModal()" class="rounded-full p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-300 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="cropper-body grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-4 p-4 sm:p-5 min-h-0">
                                        <div class="h-[min(45vh,320px)] rounded-3xl bg-gray-100 dark:bg-gray-800 overflow-hidden flex items-center justify-center">
                                            <img id="cropper-image" src="#" alt="Crop preview" class="max-w-full max-h-full object-contain" />
                                        </div>
                                        <div class="space-y-4">
                                            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-4">
                                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3" data-translate="preview_label" data-translate-page="profile">Pratinjau</p>
                                                <div id="cropper-preview-container" class="cropper-preview-box overflow-hidden rounded-3xl border border-gray-200 dark:border-gray-700 bg-gray-900/5"></div>
                                            </div>
                                            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-4 space-y-3">
                                                <div class="flex items-center justify-between gap-3">
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200" data-translate="cropper_zoom" data-translate-page="profile">Zoom</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400" data-translate="cropper_zoom_desc" data-translate-page="profile">Perbesar atau perkecil gambar.</p>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <button type="button" onclick="cropperZoom(-0.1)" class="inline-flex items-center justify-center rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition">-</button>
                                                        <button type="button" onclick="cropperZoom(0.1)" class="inline-flex items-center justify-center rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition">+</button>
                                                    </div>
                                                </div>
                                                <input id="cropper-zoom-range" type="range" min="0.5" max="3" step="0.01" value="1" class="w-full accent-indigo-600">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cropper-footer flex flex-col gap-3 sm:flex-row items-stretch justify-end border-t border-gray-200 dark:border-gray-700 px-5 py-4 bg-gray-50 dark:bg-gray-950/90">
                                        <button type="button" onclick="cancelProfileCropper()" class="w-full sm:w-auto rounded-2xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition" data-translate="btn_cancel" data-translate-page="profile">Batal</button>
                                        <button type="button" onclick="confirmProfileCrop()" class="w-full sm:w-auto rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 transition" data-translate="btn_use_photo" data-translate-page="profile">Gunakan Foto</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Portfolio Buttons -->
                            <div class="flex justify-end gap-2 mt-1">
                                @php
                                    $portfolioUrl = route('portfolio.show', ['user' => Auth::user()->username]);
                                @endphp
                                <button type="button" onclick="copyLink('{{ $portfolioUrl }}')" class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700/50 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                    </svg>
                                    <span data-translate="btn_share_portfolio" data-translate-page="profile">Bagikan Portfolio</span>
                                </button>
                            </div>

                            <!-- Name and Username -->
                            <div class="mt-4">
                                <div class="relative group inline-block">
                                    <div id="nama-container">
                                        <h2 id="nama-display" class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white inline-block cursor-pointer hover:text-indigo-600 dark:hover:text-indigo-400" onclick="toggleEdit('nama')">
                                           {{ Auth::user()->nama_mahasiswa ?? 'Mahasiswa' }}
                                        </h2>
                                        <button type="button" onclick="toggleEdit('nama')" class="ml-2 opacity-0 group-hover:opacity-100 transition text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400">
                                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <input type="text" id="nama-input" name="nama_mahasiswa" class="hidden dark:bg-gray-800 dark:text-white text-2xl md:text-3xl font-bold border-b-2 border-indigo-500 focus:outline-none bg-transparent px-2 py-1" value="{{ old('nama_mahasiswa', Auth::user()->nama_mahasiswa) }}">
                                </div>
                                <div class="text-gray-500 dark:text-gray-400 text-sm mb-8 relative group">
                                    <div id="username-container">
                                        <span id="username-display" class="cursor-pointer hover:text-indigo-600 dark:hover:text-indigo-400" onclick="toggleEdit('username')">
                                            {{ Auth::user()->username ? '@' . Auth::user()->username : autoTranslate('(belum ada username)') }}
                                        </span>
                                        <button type="button" onclick="toggleEdit('username')" class="ml-2 opacity-0 group-hover:opacity-100 transition text-xs text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400" data-translate="btn_edit" data-translate-page="profile">Edit</button>
                                    </div>
                                    <input type="text" id="username-input" name="username" class="hidden dark:bg-gray-800 dark:text-white text-center border-b border-indigo-500 focus:outline-none w-64 mx-auto bg-transparent" value="{{ old('username', Auth::user()->username) }}">
                                </div>
                            </div>

                            <!-- Info Cards Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 max-w-3xl mx-auto mt-8">

                                <!-- Deskripsi -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-500 transition cursor-pointer group sm:col-span-2" onclick="toggleEdit('deskripsi')">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                                        <span data-translate="label_description" data-translate-page="profile">Deskripsi</span>
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <p id="deskripsi-display" class="text-base dark:text-gray-200 text-gray-800 break-words flex-1">{{ Auth::user()->translated('deskripsi') ?? '(belum ada deskripsi)' }}</p>
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 opacity-0 group-hover:opacity-100 transition ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </div>
                                    <textarea id="deskripsi-input" name="deskripsi" class="hidden w-full text-base text-gray-800 dark:text-gray-200 dark:bg-gray-700 border-b border-indigo-500 focus:outline-none bg-transparent rounded p-2" rows="3">{{ old('deskripsi', Auth::user()->deskripsi) }}</textarea>
                                </div>

                                <!-- NIM -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-500 transition cursor-pointer group"
                                    onclick="toggleEdit('nim')">

                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-4 0h4" />
                                        </svg>
                                        <span data-translate="label_nim" data-translate-page="profile">
                                            NIM
                                        </span>
                                    </p>

                                    <div class="flex items-center justify-between">
                                        <p id="nim-display" class="text-base font-medium text-gray-800 dark:text-gray-200">
                                            {{ Auth::user()->nim ?? '-' }}
                                        </p>

                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 opacity-0 group-hover:opacity-100 transition flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </div>

                                    <input type="text"
                                        id="nim-input"
                                        name="nim"
                                        class="hidden dark:bg-gray-800 dark:text-white text-center border-b border-indigo-500 focus:outline-none w-64 mx-auto bg-transparent"
                                        value="{{ old('nim', Auth::user()->nim) }}">
                                </div>

                                <!-- Tanggal Lahir -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-500 transition cursor-pointer group" onclick="toggleEdit('tanggal_lahir')">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v4m0 0v4m0-4h4m-4 0H8" />
                                        </svg>
                                        <span data-translate="label_tanggal_lahir" data-translate-page="profile">Tanggal Lahir</span>
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <p id="tanggal_lahir-display" class="text-base font-medium dark:text-gray-200 text-gray-800">
                                            {{ Auth::user()->tanggal_lahir ? \Carbon\Carbon::parse(Auth::user()->tanggal_lahir)->translatedFormat('d F Y') : 'Klik untuk menambahkan tanggal lahir...' }}
                                        </p>
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 opacity-0 group-hover:opacity-100 transition ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </div>
                                    <input type="date" id="tanggal_lahir-input" name="tanggal_lahir" value="{{ old('tanggal_lahir', Auth::user()->tanggal_lahir ? \Carbon\Carbon::parse(Auth::user()->tanggal_lahir)->format('Y-m-d') : '') }}"
                                        max="{{ date('Y-m-d') }}"
                                        class="hidden w-full text-base font-medium text-gray-800 dark:text-gray-200 dark:bg-gray-700 border-b border-indigo-500 focus:outline-none bg-transparent"
                                        onchange="validateTanggalLahir(this)">
                                </div>

                                <!-- Email -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-500 transition cursor-pointer group" onclick="toggleEdit('email')">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        <span data-translate="label_email" data-translate-page="profile">Email</span>
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <p id="email-display" class="text-base font-medium dark:text-gray-200 text-gray-800 break-all">{{ Auth::user()->email ?? '-' }}</p>
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 opacity-0 group-hover:opacity-100 transition ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </div>
                                    <input type="email" id="email-input" name="email" class="hidden w-full text-base font-medium text-gray-800 dark:text-gray-200 dark:bg-gray-700 border-b border-indigo-500 focus:outline-none bg-transparent" value="{{ old('email', Auth::user()->email) }}">
                                </div>

                                <!-- Jurusan -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-500 transition cursor-pointer group" onclick="toggleEdit('jurusan')">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                                        <span data-translate="label_major" data-translate-page="profile">Jurusan</span>
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <p id="jurusan-display" class="text-base font-medium dark:text-gray-200 text-gray-800">{{ Auth::user()->jurusan->nama_jurusan ?? '-' }}</p>
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 opacity-0 group-hover:opacity-100 transition ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </div>
                                    <select id="jurusan-input" name="id_jurusan" class="hidden w-full text-base font-medium dark:bg-gray-700 dark:text-white border-b border-indigo-500 focus:outline-none bg-white">
                                        <option value="" data-translate="placeholder_choose_major" data-translate-page="profile">-- Pilih Jurusan --</option>
                                        @foreach($jurusans ?? [] as $jurusan)
                                            <option value="{{ $jurusan->id_jurusan }}" {{ (Auth::user()->id_jurusan == $jurusan->id_jurusan) ? 'selected' : '' }}>{{ $jurusan->nama_jurusan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Angkatan -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                                        <span data-translate="label_cohort" data-translate-page="profile">Angkatan</span>
                                    </p>
                                    <p class="text-base font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->angkatan->nama_angkatan ?? 'Angkatan 2026' }}</p>
                                </div>

                                <!-- Keahlian Utama -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-500 transition cursor-pointer group" onclick="toggleEdit('keahlian')">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                                        <span data-translate="label_main_skill" data-translate-page="profile">Keahlian Utama</span>
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <p id="keahlian-display" class="text-base font-medium dark:text-gray-200 text-gray-800">{{ Auth::user()->keahlian->nama_keahlian ?? '-' }}</p>
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 opacity-0 group-hover:opacity-100 transition ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </div>
                                    <select id="keahlian-input" name="id_keahlian" class="hidden w-full text-base font-medium dark:bg-gray-700 dark:text-white border-b border-indigo-500 focus:outline-none bg-white">
                                        <option value="" data-translate="placeholder_choose_skill" data-translate-page="profile">-- Pilih Keahlian --</option>
                                        @foreach($keahlians ?? [] as $keahlian)
                                            <option value="{{ $keahlian->id_keahlian }}" {{ (Auth::user()->id_keahlian == $keahlian->id_keahlian) ? 'selected' : '' }}>{{ $keahlian->nama_keahlian }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Keahlian Tambahan -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 sm:col-span-2">
                                    <div class="flex items-center justify-between mb-4">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                            <span data-translate="label_additional_skill" data-translate-page="profile">Keahlian Tambahan</span>
                                        </p>
                                        <span class="text-xs bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 px-2 py-1 rounded-full">
                                            <span x-text="keahlianCount"></span>/3
                                        </span>
                                    </div>
                                    <div x-show="loading" class="flex justify-center py-4">
                                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div>
                                    </div>
                                    <template x-if="!loading">
                                        <div class="space-y-3 mb-4">
                                            <template x-for="(item, index) in keahlianList" :key="item.id">
                                                <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border"
                                                    :class="{
                                                        'border-green-500 dark:border-green-400': item.status_pengajuan === 'Di Terima',
                                                        'border-yellow-500 dark:border-yellow-400': item.status_pengajuan === 'Sedang Di Ajukan',
                                                        'border-red-500 dark:border-red-400': item.status_pengajuan === 'Di Tolak',
                                                        'border-gray-200 dark:border-gray-600': !item.status_pengajuan
                                                    }">
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                                                            <span class="font-medium text-gray-900 dark:text-white" x-text="item.keahlian?.nama_keahlian || 'Keahlian'"></span>
                                                            <span class="text-xs px-2 py-0.5 rounded-full" :class="{
                                                                'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400': item.status_pengajuan === 'Di Terima',
                                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400': item.status_pengajuan === 'Sedang Di Ajukan',
                                                                'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400': item.status_pengajuan === 'Di Tolak',
                                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300': !item.status_pengajuan
                                                            }" x-text="item.status_pengajuan || 'Unknown'"></span>
                                                        </div>
                                                        <p x-show="item.keterangan" class="text-xs text-red-600 dark:text-red-400 mt-1" x-text="'{{ 'Alasan: ' }}' + item.keterangan"></p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                            {{ 'Diajukan:' }} <span x-text="new Date(item.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })"></span>
                                                        </p>
                                                    </div>
                                                    <button type="button" @click="deleteKeahlian(item.id, index)" class="ml-2 p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition" :disabled="loading">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </div>
                                            </template>
                                            <div x-show="keahlianList.length === 0" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                                <p class="text-base font-medium" data-translate="empty_additional_skill" data-translate-page="profile">Belum ada keahlian tambahan</p>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <!-- Form Tambah Keahlian -->
                                    <div x-show="keahlianCount < 3" class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <!-- Pilihan dari dropdown -->
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" data-translate="label_select_from_list" data-translate-page="profile">
                                                    Pilih dari daftar keahlian
                                                </label>
                                                <div class="flex flex-col sm:flex-row gap-3">
                                                    <select x-model="selectedKeahlian" id="keahlian-tambahan-select" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-800 dark:text-white">
                                                        <option value="" data-translate="placeholder_choose_additional_skill" data-translate-page="profile">-- Pilih Keahlian Tambahan --</option>
                                                        <template x-for="keahlian in keahlianOptions" :key="keahlian.id_keahlian">
                                                            <option :value="keahlian.id_keahlian" :disabled="isKeahlianDisabled(keahlian)" x-text="keahlian.nama_keahlian + (keahlian.id_keahlian === mainSkillId ? ' (Keahlian Utama)' : isKeahlianExists(keahlian.id_keahlian) ? ' (Sudah Diajukan)' : '')"></option>
                                                        </template>
                                                    </select>
                                                    <button type="button" @click="submitKeahlianFromDropdown()" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition" :disabled="!selectedKeahlian || loading" data-translate="btn_request" data-translate-page="profile">Ajukan</button>
                                                </div>
                                            </div>
                                            
                                            <!-- Atau masukkan keahlian baru -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2" data-translate="label_or_custom_skill" data-translate-page="profile">
                                                    Atau masukkan keahlian tambahan baru
                                                </label>
                                                <div class="flex flex-col sm:flex-row gap-3">
                                                    <input type="text" id="custom-keahlian-input" placeholder="{{ 'Keahlian tambahan sendiri' }}" 
                                                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                                    <button type="button" onclick="submitCustomKeahlian()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition" data-translate="btn_custom_skill" data-translate-page="profile">Ajukan Keahlian Baru</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div x-show="keahlianCount >= 3" class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                        <p class="text-sm text-yellow-800 dark:text-yellow-200 flex items-center">
                                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                            <span data-translate="max_additional_skill" data-translate-page="profile">Anda sudah mencapai maksimal 3 keahlian tambahan. Hapus salah satu untuk menambah keahlian baru.</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Video Perkenalan -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-500 transition cursor-pointer group sm:col-span-2" onclick="toggleEdit('video')">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="6" width="18" height="12" rx="4" stroke-width="2"/><polygon points="10,9 10,15 15,12" stroke-width="2"/></svg>
                                        <span data-translate="label_video_intro" data-translate-page="profile">Video Perkenalan</span>
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <p id="video-display" class="text-base text-gray-800 dark:text-gray-200 break-all flex-1">{{ $user->video_url ?? 'Klik untuk menambahkan video...' }}</p>
                                        <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </div>
                                    <input type="text" id="video-input" name="video_url" value="{{ old('video_url', $user->video_url) }} "placeholder="https://www.youtube.com/watch?v=xxxx" class="hidden w-full text-base text-gray-800 dark:text-gray-200 dark:bg-gray-700 border-b border-indigo-500 focus:outline-none bg-transparent mt-2" oninput="updatePreview()">
                                    <div id="videoPreview" class="hidden mt-4">
                                        <iframe id="previewFrame" class="w-full h-64 rounded-xl" src="" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                </div>

                                <!-- Status Akun -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z" /></svg>
                                        <span data-translate="label_account_status" data-translate-page="profile">Status Akun</span>
                                    </p>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-block w-3 h-3 rounded-full {{ Auth::user()->is_active ?  'bg-green-500' : 'bg-red-500' }}"></span>
                                        <p class="text-base font-medium {{ Auth::user()->is_active ?  'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400' }}">
                                            <span data-translate="{{ Auth::user()->is_active ? 'status_active'  : 'status_inactive' }}" data-translate-page="profile">{{ Auth::user()->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                        </p>
                                    </div>
                                </div>

                            </div>{{-- end grid --}}

                            <!-- Save Button -->
                            <div id="save-button-container" class="mt-8 text-center hidden">
                                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition shadow-md" data-translate="btn_save_changes" data-translate-page="profile">Simpan Perubahan</button>
                                <button type="button" onclick="window.location.reload()" class="ml-4 px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition" data-translate="btn_cancel" data-translate-page="profile">Batal</button>
                            </div>
                        </div>
                    </form>

                    {{-- PENDIDIKAN SECTION --}}
                    <section class="mt-8 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 md:p-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-8 h-8 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                </svg>
                                <span data-translate="section_education" data-translate-page="profile">Pendidikan</span>
                            </h2>
                            <button type="button" onclick="openPendidikanModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                <span data-translate="btn_add_education" data-translate-page="profile">Tambah Pendidikan</span>
                            </button>
                        </div>

                        @php $pendidikanList = Auth::user()->pendidikan ?? []; @endphp

                        @if(!empty($pendidikanList))
                            <div class="space-y-4">
                                @foreach($pendidikanList as $pend)
                                    <div class="flex items-start gap-4 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-xl border border-blue-100 dark:border-blue-800/30 hover:border-blue-300 dark:hover:border-blue-600 transition group cursor-pointer" onclick="openDetailPendidikan('{{ $pend['id'] ?? '' }}')">
                                        <div class="flex-shrink-0 w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                                            @php
                                                $jenjangIcons = [
                                                    'S1' => '🎓', 'S2' => '🎓', 'S3' => '🎓',
                                                    'D1' => '📚', 'D2' => '📚', 'D3' => '📚', 'D4' => '📚',
                                                    'SMA/SMK' => '🏫', 'SMP' => '🏫', 'SD' => '🏫',
                                                    'Kursus/Pelatihan' => '📖',
                                                ];
                                                $icon = $jenjangIcons[$pend['jenjang'] ?? ''] ?? '🏛️';
                                            @endphp
                                            <span class="text-2xl">{{ $icon }}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2">
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-white text-base">{{ $pend['nama_sekolah'] ?? '-' }}</h4>
                                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-0.5">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 mr-2">{{ $pend['jenjang'] ?? '' }}</span>
                                                        @if(!empty($pend['jurusan_sek'])) {{ $pend['jurusan_sek'] }} @endif
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                        {{ $pend['tahun_masuk'] ?? '-' }} —
                                                        @if(!empty($pend['masih_kuliah']) && $pend['masih_kuliah'])
                                                            <span class="text-green-600 dark:text-green-400 font-medium" data-translate="status_now" data-translate-page="profile">Sekarang</span>
                                                        @else
                                                            {{ $pend['tahun_lulus'] ?? 'Belum selesai' }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-blue-50/50 dark:bg-blue-900/10 rounded-xl border-2 border-dashed border-blue-200 dark:border-blue-800/30">
                                <svg class="w-14 h-14 mx-auto text-blue-300 dark:text-blue-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                                <p class="text-gray-500 dark:text-gray-400 text-sm" data-translate="empty_education" data-translate-page="profile">Belum ada data pendidikan. Klik "Tambah Pendidikan" untuk menambahkan.</p>
                            </div>
                        @endif
                    </section>

                    {{-- PENGALAMAN KERJA SECTION --}}
                    <section class="mt-8 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 md:p-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-8 h-8 mr-2 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span data-translate="section_experience" data-translate-page="profile">Pengalaman Kerja</span>
                            </h2>
                            <button type="button" onclick="openPengalamanModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                <span data-translate="btn_add_experience" data-translate-page="profile">Tambah Pengalaman</span>
                            </button>
                        </div>

                        @php $pengalamanList = Auth::user()->pengalaman_kerja ?? []; @endphp

                        @if(!empty($pengalamanList))
                            <div class="space-y-4">
                                @foreach($pengalamanList as $pkj)
                                    <div class="flex items-start gap-4 p-4 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl border border-emerald-100 dark:border-emerald-800/30 hover:border-emerald-300 dark:hover:border-emerald-600 transition group cursor-pointer" onclick="openDetailPengalaman('{{ $pkj['id'] ?? '' }}')">
                                        <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex-1">
                                                   <div class="flex items-center gap-2 flex-wrap">
                                                        <h4 class="font-semibold text-gray-900 dark:text-white text-base">{{ $pkj['nama_pt'] ?? '-' }}</h4>
                                                        @if(!empty($pkj['jenis_pekerjaan']))
                                                            <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 font-medium">{{ $pkj['jenis_pekerjaan'] }}</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm text-emerald-700 dark:text-emerald-400 font-medium mt-0.5">{{ $pkj['bagian_kerja'] ?? '-' }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                        {{ $pkj['tahun_mulai'] ?? '-' }} —
                                                        @if(!empty($pkj['masih_bekerja']) && $pkj['masih_bekerja'])
                                                            <span class="text-green-600 dark:text-green-400 font-medium" data-translate="status_now" data-translate-page="profile">Sekarang</span>
                                                        @else
                                                            {{ $pkj['tahun_akhir'] ?? 'Selesai' }}
                                                        @endif
                                                    </p>
                                                    @if(!empty($pkj['sertifikat_pendukung']))
                                                        <a href="{{ asset('storage/' . $pkj['sertifikat_pendukung']) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 hover:underline mt-2">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                                            <span data-translate="btn_view_certificate" data-translate-page="profile">Lihat Sertifikat Pendukung</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-emerald-50/50 dark:bg-emerald-900/10 rounded-xl border-2 border-dashed border-emerald-200 dark:border-emerald-800/30">
                                <svg class="w-14 h-14 mx-auto text-emerald-300 dark:text-emerald-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                <p class="text-gray-500 dark:text-gray-400 text-sm" data-translate="empty_experience" data-translate-page="profile">Belum ada pengalaman kerja. Klik "Tambah Pengalaman" untuk menambahkan.</p>
                            </div>
                        @endif
                    </section>

                    @if(Auth::check())
                    <!-- Projects Section -->
                    <section class="mt-8 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 md:p-8" x-data="{ showAllProjects: false }">

                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-8 h-8 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                                <span data-translate="section_projects" data-translate-page="profile">Projects</span>
                            </h2>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('project.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    <span data-translate="btn_add_project" data-translate-page="profile">Tambah Projek</span>
                                </a>
                                <span class="text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-4 py-1.5 rounded-full">{{ $user->projects->count() }} <span data-translate="project_count" data-translate-page="profile">proyek</span></span>
                                @if($user->projects->count() > 3)
                                    <button @click="showAllProjects = !showAllProjects" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 font-medium flex items-center gap-1">
                                        <span x-text="showAllProjects ? 'Tampilkan lebih sedikit' : 'Lihat semua' ({{ $user->projects->count() }})'"></span>
                                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': showAllProjects }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                        @if($user->projects->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($user->projects as $index => $project)
                                    @php
                                        $content = $project->translated('isi_content') ?? [];
                                        $nama = $content['nama_project'] ?? 'Tanpa Nama Project';
                                        $deskripsi = $content['deskripsi'] ?? null;
                                        $linkProject = $content['link_project'] ?? null;
                                        $linkGithub = $content['link_github'] ?? null;
                                        $linkVideo = $content['link_video'] ?? null;
                                        $mulai = $project->tanggal_mulai ? \Carbon\Carbon::parse($project->tanggal_mulai) : null;
                                        $akhir = $project->tanggal_akhir ? \Carbon\Carbon::parse($project->tanggal_akhir) : null;
                                        $today = \Carbon\Carbon::today();
                                        $mulaiFormatted = $mulai ? $mulai->translatedFormat('M Y') : '—';
                                        $akhirFormatted = $akhir ? $akhir->translatedFormat('M Y') : 'Sekarang';
                                       if ($mulai && $akhir) {
                                            if ($akhir < $today) { 
                                                $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'; 
                                                $statusText = 'Selesai'; 
                                            }
                                            elseif ($mulai <= $today && $today <= $akhir) { 
                                                $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'; 
                                                $statusText = 'Sedang Berjalan'; 
                                            }
                                            else { 
                                                $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400'; 
                                                $statusText = 'Akan Datang'; 
                                            }
                                        } elseif ($mulai && !$akhir) {
                                            if ($mulai <= $today) { 
                                                $statusClass = 'bg-green-100 text-green-800'; 
                                                $statusText = 'Sedang Berjalan'; 
                                            }
                                            else { 
                                                $statusClass = 'bg-blue-100 text-blue-800'; 
                                                $statusText = 'Akan Datang'; 
                                            }
                                        } else { 
                                            $statusClass = 'bg-gray-100 text-gray-800'; 
                                            $statusText = 'Tidak diketahui'; 
                                        }
                                        $youtubeEmbedUrl = null; $youtubeThumbnail = null;
                                        if ($linkVideo) {
                                            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $linkVideo, $matches);
                                            if (!empty($matches[1])) {
                                                $videoId = $matches[1];
                                                $youtubeEmbedUrl = "https://www.youtube.com/embed/" . $videoId;
                                                $youtubeThumbnail = "https://img.youtube.com/vi/" . $videoId . "/maxresdefault.jpg";
                                            }
                                        }
                                    @endphp
                                    <div class="bg-white dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm sm:hover:shadow-md md:shadow-md transition-shadow duration-200 overflow-hidden flex flex-col h-full group"
                                        x-show="showAllProjects || {{ $index < 3 ? 'true' : 'false' }}"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                                        <div class="relative w-full bg-black overflow-hidden">
                                            @if($youtubeEmbedUrl)
                                                <div class="relative w-full pb-[56.25%] bg-gray-900 cursor-pointer" onclick="playVideo(this, '{{ $youtubeEmbedUrl }}')">
                                                    <img src="{{ $youtubeThumbnail }}" class="absolute inset-0 w-full h-full object-cover opacity-90" onerror="this.src='https://via.placeholder.com/480x360?text=Video'">
                                                    <div class="absolute inset-0 flex items-center justify-center md:hidden">
                                                        <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                                                            <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($linkProject)
                                                <div class="w-full h-48 bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 flex items-center justify-center">
                                                    <svg class="w-20 h-20 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                                </div>
                                            @else
                                                <div class="w-full h-48 bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                                    <svg class="w-20 h-20 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-5 flex flex-col flex-1">
                                            <div class="flex items-start justify-between mb-2 gap-2">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-2 flex-1">{{ $nama }}</h3>
                                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }} whitespace-nowrap">{{ $statusText }}</span>
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-300 mb-3">Mulai:  {{ $mulaiFormatted }} → Selesai: {{ $akhirFormatted }}</div>
                                            @if($deskripsi)<p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3 flex-1">{{ $deskripsi }}</p>@endif
                                            <div class="flex flex-wrap gap-3 mt-auto pt-4 border-t border-gray-100 dark:border-gray-700">
                                                @if($linkProject)<a href="{{ $linkProject }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>Website</a>@endif
                                                @if($linkGithub)<a href="{{ $linkGithub }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-800 dark:text-gray-300"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>GitHub</a>@endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-700">
                                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <p class="mt-4 text-gray-600 dark:text-gray-400" data-translate="empty_projects" data-translate-page="profile">Belum ada proyek yang ditambahkan.</p>
                            </div>
                        @endif
                    </section>

                    <!-- Sertifikat Section -->
                    <section class="mt-8 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 md:p-8" x-data="{ showAllSertifikat: false }">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-8 h-8 mr-2 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" /></svg>
                                <span data-translate="section_certificates" data-translate-page="profile">Sertifikat</span>
                            </h2>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('sertifikat.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    <span data-translate="btn_add_certificate" data-translate-page="profile">Tambah Sertifikat</span>
                                </a>
                                <span class="text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-4 py-1.5 rounded-full">{{ $user->sertifikats?->count() ?? 0 }} <span data-translate="certificate_count" data-translate-page="profile">sertifikat</span></span>
                            </div>
                        </div>
                        @if($user->sertifikats?->isNotEmpty() ?? false)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($user->sertifikats as $index => $sertifikat)
                                    @php
                                        $isInactive = ($sertifikat->status_pengajuan === 'Di Tolak' || !($sertifikat->is_active ?? true));
                                        $statusClass = match ($sertifikat->status_pengajuan) {
                                            'Sedang Di Ajukan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
                                            'Di Terima' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                                            'Di Tolak' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                        };
                                    @endphp
                                    <div class="bg-white dark:bg-gray-700/50 rounded-xl shadow-sm overflow-hidden sm:hover:shadow-md md:shadow-md transition-shadow border border-gray-200 dark:border-gray-600 flex flex-col h-full {{ $isInactive ? 'opacity-70' : '' }}"
                                        x-show="showAllSertifikat || {{ $index < 3 ? 'true' : 'false' }}"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                                        <div class="p-6 flex flex-col flex-1">
                                            <div class="flex items-center gap-2 mb-3 flex-wrap">
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $isInactive ? 'bg-gray-300 text-gray-700' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}" data-translate="certificate_badge" data-translate-page="profile">Sertifikat</span>
                                                @if($sertifikat->status_pengajuan)<span class="text-xs px-2 py-1 rounded-full {{ $statusClass }}">{{ $sertifikat->status_pengajuan }}</span>@endif
                                            </div>
                                            <h3 class="text-lg font-semibold {{ $isInactive ? 'text-gray-500' : 'text-gray-900 dark:text-white' }} mb-3 line-clamp-2">{{ $sertifikat->nama_sertifikat ?? 'Sertifikat Tanpa Judul' }}</h3>
                                            @if($sertifikat->lembaga_penerbit)<p class="text-sm text-gray-600 dark:text-gray-300 mb-2 flex items-center gap-1"><svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" /></svg>{{ $sertifikat->lembaga_penerbit }}</p>@endif
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->translatedFormat('d M Y') : 'Tanggal tidak tersedia' }}</p>
                                            @if($sertifikat->link_sertifikat)
                                                <a href="{{ asset('storage/' . $sertifikat->link_sertifikat) }}" target="_blank" class="mt-auto inline-flex items-center text-amber-600 dark:text-amber-400 hover:text-amber-800 font-medium text-sm gap-1"> Lihat Sertifikat <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg></a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-700">
                                <p class="text-gray-600 dark:text-gray-400" data-translate="empty_certificates" data-translate-page="profile">Belum ada sertifikat yang ditambahkan.</p>
                            </div>
                        @endif
                    </section>

                    <!-- Learning Corners -->
                    <section class="mt-8 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 md:p-8 mb-10" x-data="{ showAllLearning: false }">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-8 h-8 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                <span data-translate="section_learning_corners" data-translate-page="profile">Learning Corners</span>
                            </h2>
                            <span class="text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-4 py-1.5 rounded-full">{{ $user->learning_corners->count() }} <span data-translate="learning_corner_count" data-translate-page="profile">catatan</span></span>
                        </div>
                        @if($user->learning_corners->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($user->learning_corners as $index => $entry)
                                    <div class="bg-white dark:bg-gray-700/50 rounded-xl shadow-sm overflow-hidden sm:hover:shadow-md md:shadow-md transition-shadow border border-gray-200 dark:border-gray-600 flex flex-col h-full"
                                        x-show="showAllLearning || {{ $index < 3 ? 'true' : 'false' }}"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                                        <div class="p-6 flex-1 flex flex-col">
                                            @if (!empty($entry->content) && is_array($entry->content))
                                                @foreach ($entry->translated('content') as $item)
                                                    @if ($item['type'] === 'title')<h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 line-clamp-2">{{ $item['content'] ?? '' }}</h3>
                                                    @elseif ($item['type'] === 'text')<p class="text-gray-700 dark:text-gray-300 mb-4 line-clamp-4">{{$item['content'] }}</p>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-auto pt-4 border-t border-gray-100 dark:border-gray-700">Diposting pada:  {{ $entry->created_at?->translatedFormat('d M Y H:i') ?? '' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-700">
                                <p class="text-gray-600 dark:text-gray-400" data-translate="empty_learning_corners" data-translate-page="profile">Belum ada catatan learning corner.</p>
                            </div>
                        @endif
                    </section>
                    @endif

                    <!-- Footer -->
                    <div class="mt-8 text-center text-xs text-gray-500 dark:text-gray-400">
                        <span data-translate="last_updated" data-translate-page="profile">Terakhir diperbarui:</span> {{ now()->translatedFormat('d F Y H:i') }} WIB
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH PENDIDIKAN --}}
    <div id="modal-pendidikan" class="fixed inset-0 hidden" style="z-index: 1100000;" aria-modal="true" role="dialog">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closePendidikanModal()"></div>
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0" id="modal-pendidikan-content">
                <div class="sticky top-0 bg-white dark:bg-gray-900 px-6 py-5 border-b border-gray-100 dark:border-gray-700 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white" data-translate="modal_education_title" data-translate-page="profile">Tambah Pendidikan</h3>
                        </div>
                        <button onclick="closePendidikanModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <form action="{{ route('pendidikan.store') }}" method="POST" class="px-6 py-6 space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            <span data-translate="label_institution" data-translate-page="profile">Nama Institusi / Sekolah</span> <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="sekolah-search-input" name="nama_sekolah" placeholder="Cari nama sekolah/universitas..." required autocomplete="off"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition"
                                oninput="searchSekolah(this.value)">
                            <div class="absolute right-3 top-3">
                                <svg id="sekolah-search-icon" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                <svg id="sekolah-loading-icon" class="hidden w-5 h-5 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            </div>
                            <div id="sekolah-dropdown" class="hidden absolute top-full left-0 right-0 mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl shadow-xl z-50 max-h-52 overflow-y-auto">
                                <div id="sekolah-dropdown-list" class="divide-y divide-gray-100 dark:divide-gray-700"></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            <span data-translate="label_education_level" data-translate-page="profile">Jenjang Pendidikan</span> <span class="text-red-500">*</span>
                        </label>
                        <select name="jenjang" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                            <option value="" data-translate="placeholder_choose_education_level" data-translate-page="profile">-- Pilih Jenjang --</option>
                            @foreach(['SD','SMP','SMA/SMK','D1','D2','D3','D4','S1','S2','S3','Kursus/Pelatihan'] as $jenjang)
                                <option value="{{ $jenjang }}">{{ $jenjang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            <span data-translate="label_major_study" data-translate-page="profile">Jurusan / Program Studi</span>
                            <span class="text-xs font-normal text-gray-400">(<span data-translate="label_optional" data-translate-page="profile">opsional</span>)</span>
                        </label>
                        <input type="text" name="jurusan_sek" placeholder="Contoh: Teknik Informatika" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                    </div>

                    {{-- Tambah modal pendidikan - tahun masuk & lulus dengan validasi JS --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                <span data-translate="label_start_year" data-translate-page="profile">Tahun Masuk</span> <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="add-pend-tahun_masuk" name="tahun_masuk" required
                                max="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition"
                                onchange="onAddPendTahunMasukChange(this.value)">
                        </div>
                        <div id="add-pend-tahun-lulus-field">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                <span data-translate="label_graduation_year" data-translate-page="profile">Tahun Lulus</span>
                            </label>
                            <input type="date" id="add-pend-tahun_lulus" name="tahun_lulus"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition"
                                min=""
                                onchange="validateAddPendTahunLulus(this)">
                            <p id="add-pend-lulus-error" class="hidden text-xs text-red-500 mt-1" data-translate="error_end_before_start" data-translate-page="profile">Tahun lulus tidak boleh sebelum tahun masuk.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/10 rounded-xl">
                        <input type="checkbox" name="masih_kuliah" id="add-masih_kuliah" value="1" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" onchange="toggleAddPendTahunLulus(this)">
                        <label for="add-masih_kuliah" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer" data-translate="label_still_studying" data-translate-page="profile">Masih bersekolah / kuliah di sini</label>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closePendidikanModal()" class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl transition" data-translate="btn_cancel" data-translate-page="profile">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-sm" data-translate="btn_save_education" data-translate-page="profile">Simpan Pendidikan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL / EDIT PENDIDIKAN -->
    <div id="modal-detail-pendidikan" class="fixed inset-0 hidden" style="z-index: 100000;" aria-modal="true" role="dialog">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDetailPendidikan()"></div>
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0" id="modal-detail-pendidikan-content">

                <div class="sticky top-0 bg-white dark:bg-gray-900 px-6 py-5 border-b border-gray-100 dark:border-gray-700 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                </svg>
                            </div>
                            <div>
                                <h3 id="modal-pend-title" class="text-lg font-bold text-gray-900 dark:text-white" data-translate="detail_education_title" data-translate-page="profile">Detail Pendidikan</h3>
                                <p id="modal-pend-mode-label" class="text-xs text-blue-600 dark:text-blue-400" data-translate="mode_view" data-translate-page="profile">Mode Lihat</p>
                            </div>
                        </div>
                        <button onclick="closeDetailPendidikan()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-6 space-y-5">
                    <div id="pend-skeleton" class="hidden space-y-4 animate-pulse">
                        <div class="flex items-center gap-4 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-xl">
                            <div class="w-12 h-12 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                            <div class="flex-1">
                                <div class="h-5 bg-gray-300 dark:bg-gray-600 rounded w-3/4 mb-2"></div>
                                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"><div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-2"></div><div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-3/4"></div></div>
                            <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"><div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-2"></div><div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-3/4"></div></div>
                        </div>
                        <div class="flex gap-3 pt-2"><div class="flex-1 h-12 bg-gray-200 dark:bg-gray-700 rounded-xl"></div><div class="flex-1 h-12 bg-gray-200 dark:bg-gray-700 rounded-xl"></div></div>
                    </div>
                    <input type="hidden" id="pend-edit-id">

                    <!-- VIEW MODE -->
                    <div id="pend-view-mode" class="space-y-4">
                        <div class="flex items-center gap-4 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-xl">
                            <div id="pend-view-icon" class="text-4xl">🎓</div>
                            <div>
                                <h4 id="pend-view-nama" class="text-xl font-bold text-gray-900 dark:text-white"></h4>
                                <span id="pend-view-jenjang" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 mt-1"></span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1" data-translate="label_major_study" data-translate-page="profile">Jurusan</p>
                                <p id="pend-view-jurusan" class="text-sm font-medium text-gray-800 dark:text-gray-200">-</p>
                            </div>
                            <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1" data-translate="label_period" data-translate-page="profile">Periode</p>
                                <p id="pend-view-periode" class="text-sm font-medium text-gray-800 dark:text-gray-200"></p>
                            </div>
                        </div>
                    </div>

                    <!-- EDIT MODE -->
                    <div id="pend-edit-mode" class="hidden space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                <span data-translate="label_institution" data-translate-page="profile">Nama Institusi / Sekolah</span> <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="pend-edit-nama_sekolah" placeholder="Nama sekolah/universitas..." required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                <span data-translate="label_education_level" data-translate-page="profile">Jenjang Pendidikan</span> <span class="text-red-500">*</span>
                            </label>
                            <select id="pend-edit-jenjang" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                                <option value="" data-translate="placeholder_choose_education_level" data-translate-page="profile">-- Pilih Jenjang --</option>
                                <option>SD</option><option>SMP</option><option>SMA/SMK</option>
                                <option>D1</option><option>D2</option><option>D3</option><option>D4</option>
                                <option>S1</option><option>S2</option><option>S3</option><option>Kursus/Pelatihan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                <span data-translate="label_major_study" data-translate-page="profile">Jurusan / Program Studi</span>
                                <span class="text-xs font-normal text-gray-400">(<span data-translate="label_optional" data-translate-page="profile">opsional</span>)</span>
                            </label>
                            <input type="text" id="pend-edit-jurusan_sek" placeholder="Contoh: Teknik Informatika"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                    <span data-translate="label_start_year" data-translate-page="profile">Tahun Masuk</span> <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="pend-edit-tahun_masuk" required
                                    max="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition"
                                    onchange="onEditPendTahunMasukChange(this.value)">
                            </div>
                            <div id="pend-edit-tahun-lulus-field">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                    <span data-translate="label_graduation_year" data-translate-page="profile">Tahun Lulus</span>
                                </label>
                                <input type="date" id="pend-edit-tahun_lulus"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition"
                                    min=""
                                    onchange="validateEditPendTahunLulus(this)">
                                <p id="edit-pend-lulus-error" class="hidden text-xs text-red-500 mt-1" data-translate="error_end_before_start" data-translate-page="profile">Tahun lulus tidak boleh sebelum tahun masuk.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/10 rounded-xl">
                            <input type="checkbox" id="pend-edit-masih_kuliah" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                onchange="toggleEditTahunLulus(this)">
                            <label for="pend-edit-masih_kuliah" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer" data-translate="label_still_studying" data-translate-page="profile">Masih bersekolah / kuliah di sini</label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div id="pend-view-actions" class="flex gap-3 pt-2">
                        <button type="button" onclick="switchToPendidikanEdit()" class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <span data-translate="btn_edit" data-translate-page="profile">Edit</span>
                        </button>
                        <button type="button" id="btn-delete-pend" onclick="confirmDeletePendidikan()" class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span data-translate="btn_delete" data-translate-page="profile">Hapus</span>
                        </button>
                    </div>
                    <div id="pend-edit-actions" class="hidden flex gap-3 pt-2">
                        <button type="button" onclick="switchToPendidikanView()" class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl transition" data-translate="btn_cancel" data-translate-page="profile">Batal</button>
                        <button type="button" id="btn-save-pend-edit" onclick="savePendidikanEdit()" class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-sm flex items-center justify-center gap-2" data-translate="btn_save_changes_edit" data-translate-page="profile">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH PENGALAMAN KERJA --}}
    <div id="modal-pengalaman" class="fixed inset-0 hidden" style="z-index: 1000000;" aria-modal="true" role="dialog">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closePengalamanModal()"></div>
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0" id="modal-pengalaman-content">

                <div class="sticky top-0 bg-white dark:bg-gray-900 px-6 py-5 border-b border-gray-100 dark:border-gray-700 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white" data-translate="modal_experience_title" data-translate-page="profile">Tambah Pengalaman Kerja</h3>
                        </div>
                        <button onclick="closePengalamanModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <form id="form-pengalaman" enctype="multipart/form-data" class="px-6 py-6 space-y-5" onsubmit="handlePengalamanSubmit(event)">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            <span data-translate="label_company" data-translate-page="profile">Nama Perusahaan / Instansi</span> <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_pt" id="form-nama_pt" placeholder="Contoh: PT. Telkom Indonesia" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            <span data-translate="label_position" data-translate-page="profile">Posisi / Bagian Kerja</span> <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="bagian_kerja" id="form-bagian_kerja" placeholder="Contoh: Software Engineer" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            <span data-translate="label_job_type" data-translate-page="profile">Jenis Pekerjaan</span> <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_pekerjaan" id="form-jenis_pekerjaan" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                            <option value="" data-translate="placeholder_choose_job_type" data-translate-page="profile">-- Pilih Jenis Pekerjaan --</option>
                            @foreach(['Penuh waktu','Paruh waktu','Pekerja mandiri','Pekerja lepas','Kontrak','Magang jangka pendek','Magang','Musiman'] as $jenis)
                                <option value="{{ $jenis }}" data-translate="{{ $jenis }}" data-translate-page="profile">{{ $jenis }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            <span data-translate="label_job_description" data-translate-page="profile">Deskripsi Pekerjaan</span>
                        </label>
                        <textarea name="deskripsi" id="form-deskripsi" placeholder="Tuliskan deskripsi pekerjaan Anda" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition"></textarea>
                    </div>

                    {{-- FIX: Modal tambah pengalaman - tahun mulai & akhir dengan validasi JS --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                <span data-translate="label_start_year_work" data-translate-page="profile">Tahun Mulai</span> <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tahun_mulai" id="form-tahun_mulai"
                                max="{{ date('Y-m-d') }}"
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition"
                                onchange="onAddPkjTahunMulaiChange(this.value)">
                        </div>
                        <div id="add-pkj-tahun-akhir-field">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                <span data-translate="label_end_year_work" data-translate-page="profile">Tahun Selesai</span>
                            </label>
                            <input type="date" name="tahun_akhir" id="form-tahun_akhir"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition"
                                min=""
                                onchange="validateAddPkjTahunAkhir(this)">
                            <p id="add-pkj-akhir-error" class="hidden text-xs text-red-500 mt-1" data-translate="error_end_before_start" data-translate-page="profile">Tahun selesai tidak boleh sebelum tahun mulai.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl">
                        <input type="checkbox" name="masih_bekerja" id="form-masih_bekerja" value="1" class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500" onchange="toggleAddPkjTahunAkhir(this)">
                        <label for="form-masih_bekerja" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer" data-translate="label_still_working" data-translate-page="profile">Masih bekerja di sini</label>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            <span data-translate="label_supporting_certificate" data-translate-page="profile">Sertifikat Pendukung</span>
                            <span class="text-xs font-normal text-gray-400 ml-1">(<span data-translate="label_file_hint" data-translate-page="profile">JPG, PNG, PDF maks. 5MB</span>)</span>
                        </label>
                        <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-5 hover:border-emerald-400 dark:hover:border-emerald-500 transition cursor-pointer" onclick="document.getElementById('sertifikat-file-input').click()">
                            <div class="text-center" id="sertifikat-upload-placeholder">
                                <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400" data-translate="click_to_upload" data-translate-page="profile">Klik untuk upload file</p>
                                <p class="text-xs text-gray-400 mt-1" data-translate="drag_drop_hint" data-translate-page="profile">atau drag & drop di sini</p>
                            </div>
                            <div id="sertifikat-file-preview" class="hidden">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p id="sertifikat-file-name" class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate"></p>
                                        <p id="sertifikat-file-size" class="text-xs text-gray-500"></p>
                                    </div>
                                    <button type="button" onclick="clearSertifikatFile(event)" class="p-1 text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="file" id="sertifikat-file-input" name="sertifikat_pendukung" accept=".jpg,.jpeg,.png,.webp,.pdf" class="hidden" onchange="handleSertifikatFile(this)">
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closePengalamanModal()" class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl transition" data-translate="btn_cancel" data-translate-page="profile">Batal</button>
                        <button type="submit" id="btn-submit-pengalaman" class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition shadow-sm" data-translate="btn_save_experience" data-translate-page="profile">Simpan Pengalaman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL / EDIT PENGALAMAN KERJA -->
    <div id="modal-detail-pengalaman" class="fixed inset-0 hidden" style="z-index: 100000;" aria-modal="true" role="dialog">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDetailPengalaman()"></div>
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0" id="modal-detail-pengalaman-content">

                <div class="sticky top-0 bg-white dark:bg-gray-900 px-6 py-5 border-b border-gray-100 dark:border-gray-700 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 id="modal-pkj-title" class="text-lg font-bold text-gray-900 dark:text-white" data-translate="detail_experience_title" data-translate-page="profile">Detail Pengalaman Kerja</h3>
                                <p id="modal-pkj-mode-label" class="text-xs text-emerald-600 dark:text-emerald-400" data-translate="mode_view" data-translate-page="profile">Mode Lihat</p>
                            </div>
                        </div>
                        <button onclick="closeDetailPengalaman()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-6 space-y-5">
                    <input type="hidden" id="pkj-edit-id">

                    <div id="pkj-skeleton" class="hidden space-y-4 animate-pulse">
                        <div class="p-4 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl"><div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-3/4 mb-2"></div><div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div></div>
                        <div class="grid grid-cols-2 gap-4"><div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"><div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-2"></div><div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-3/4"></div></div><div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"><div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-2"></div><div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-3/4"></div></div></div>
                        <div class="flex gap-3 pt-2"><div class="flex-1 h-12 bg-gray-200 dark:bg-gray-700 rounded-xl"></div><div class="flex-1 h-12 bg-gray-200 dark:bg-gray-700 rounded-xl"></div></div>
                    </div>

                    <!-- VIEW MODE -->
                    <div id="pkj-view-mode" class="space-y-4">
                        <div class="p-4 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl">
                            <div class="flex items-start justify-between gap-2 flex-wrap">
                                <h4 id="pkj-view-nama" class="text-xl font-bold text-gray-900 dark:text-white"></h4>
                                <span id="pkj-view-jenis" class="text-xs px-2 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 font-medium whitespace-nowrap"></span>
                            </div>
                            <p id="pkj-view-bagian" class="text-emerald-700 dark:text-emerald-400 font-medium mt-1"></p>
                        </div>
                        <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1" data-translate="label_job_description" data-translate-page="profile">Deskripsi Pekerjaan</p>
                            <p id="pkj-view-deskripsi" class="text-sm font-medium text-gray-800 dark:text-gray-200 whitespace-pre-wrap"></p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1" data-translate="label_period" data-translate-page="profile">Periode</p>
                                <p id="pkj-view-periode" class="text-sm font-medium text-gray-800 dark:text-gray-200"></p>
                            </div>
                            <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1" data-translate="label_status" data-translate-page="profile">Status</p>
                                <p id="pkj-view-status" class="text-sm font-medium text-gray-800 dark:text-gray-200"></p>
                            </div>
                        </div>
                        <div id="pkj-view-sertifikat-wrap" class="hidden">
                            <a id="pkj-view-sertifikat-link" href="#" target="_blank" class="inline-flex items-center gap-2 text-sm text-emerald-600 dark:text-emerald-400 hover:underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                <span data-translate="btn_view_certificate" data-translate-page="profile">Lihat Sertifikat Pendukung</span>
                            </a>
                        </div>
                    </div>

                    <!-- EDIT MODE -->
                    <div id="pkj-edit-mode" class="hidden space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                <span data-translate="label_company" data-translate-page="profile">Nama Perusahaan / Instansi</span> <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="pkj-edit-nama_pt" placeholder="Contoh: PT. Telkom Indonesia" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                <span data-translate="label_position" data-translate-page="profile">Posisi / Bagian Kerja</span> <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="pkj-edit-bagian_kerja" placeholder="Contoh: Software Engineer" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                <span data-translate="label_job_type" data-translate-page="profile">Jenis Pekerjaan</span> <span class="text-red-500">*</span>
                            </label>
                            <select id="pkj-edit-jenis_pekerjaan" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                                <option value="" data-translate="placeholder_choose_job_type" data-translate-page="profile">-- Pilih Jenis Pekerjaan --</option>
                                @foreach(['Penuh waktu','Paruh waktu','Pekerja mandiri','Pekerja lepas','Kontrak','Magang jangka pendek','Magang','Musiman'] as $jenis)
                                    <option value="{{ $jenis }}" data-translate="{{ $jenis }}" data-translate-page="profile">{{ $jenis }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                <span data-translate="label_job_description" data-translate-page="profile">Deskripsi Pekerjaan</span>
                            </label>
                            <textarea id="pkj-edit-deskripsi" placeholder="Tuliskan deskripsi pekerjaan Anda" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                    <span data-translate="label_start_year_work" data-translate-page="profile">Tahun Mulai</span> <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="pkj-edit-tahun_mulai" required
                                    max="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition"
                                    onchange="onEditPkjTahunMulaiChange(this.value)">
                            </div>
                            <div id="pkj-edit-tahun-akhir-field">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                    <span data-translate="label_end_year_work" data-translate-page="profile">Tahun Selesai</span>
                                </label>
                                <input type="date" id="pkj-edit-tahun_akhir"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition"
                                    min=""
                                    onchange="validateEditPkjTahunAkhir(this)">
                                <p id="edit-pkj-akhir-error" class="hidden text-xs text-red-500 mt-1" data-translate="error_end_before_start" data-translate-page="profile">Tahun selesai tidak boleh sebelum tahun mulai.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl">
                            <input type="checkbox" id="pkj-edit-masih_bekerja" class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500"
                                onchange="toggleEditTahunAkhir(this)">
                            <label for="pkj-edit-masih_bekerja" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer" data-translate="label_still_working" data-translate-page="profile">Masih bekerja di sini</label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div id="pkj-view-actions" class="flex gap-3 pt-2">
                        <button type="button" onclick="switchToPengalamanEdit()" class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <span data-translate="btn_edit" data-translate-page="profile">Edit</span>
                        </button>
                        <button type="button" id="btn-delete-pkj" onclick="confirmDeletePengalaman()" class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span data-translate="btn_delete" data-translate-page="profile">Hapus</span>
                        </button>
                    </div>
                    <div id="pkj-edit-actions" class="hidden flex gap-3 pt-2">
                        <button type="button" onclick="switchToPengalamanView()" class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl transition" data-translate="btn_cancel" data-translate-page="profile">Batal</button>
                        <button type="button" id="btn-save-pkj-edit" onclick="savePengalamanEdit()" class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition shadow-sm flex items-center justify-center gap-2" data-translate="btn_save_changes_edit_experience" data-translate-page="profile">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
