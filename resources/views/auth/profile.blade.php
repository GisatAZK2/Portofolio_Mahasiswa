@extends('Layout.Layout')

@section('title', autoTranslate('Profil Saya'))

@section('content')
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

                <div x-data="keahlianTambahan()" x-init="init()" class="contents">

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
                                            <img id="profile-preview" src="{{ asset('storage/' . Auth::user()->photo_profile) }}" alt="{{ autoTranslate('Foto Profil') }}" class="w-full h-full object-cover">
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

                            <!-- Portfolio Buttons -->
                            <div class="flex justify-end gap-2 mt-1">
                                @php
                                    $portfolioUrl = route('portfolio.show', ['user' => Auth::user()->username]);
                                @endphp
                                <button type="button" onclick="copyLink('{{ $portfolioUrl }}')" class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700/50 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                    </svg>
                                    <span>{{ autoTranslate('Bagikan Portfolio') }}</span>
                                </button>
                            </div>

                            <!-- Name and Username -->
                            <div class="mt-4">
                                <div class="relative group inline-block">
                                    <div id="nama-container">
                                        <h2 id="nama-display" class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white inline-block cursor-pointer hover:text-indigo-600 dark:hover:text-indigo-400" onclick="toggleEdit('nama')">
                                            {{ autoTranslate(Auth::user()->nama_mahasiswa ?? 'Mahasiswa') }}
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
                                            {{ Auth::user()->username ? '@' . autoTranslate(Auth::user()->username) : autoTranslate('(belum ada username)') }}
                                        </span>
                                        <button type="button" onclick="toggleEdit('username')" class="ml-2 opacity-0 group-hover:opacity-100 transition text-xs text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400">{{ autoTranslate('edit') }}</button>
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
                                        <span data-translate="desc" data-translate-page="profile">{{ autoTranslate('Deskripsi') }}</span>
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <p id="deskripsi-display" class="text-base dark:text-gray-200 text-gray-800 break-words flex-1">{{ autoTranslate($user->deskripsi ?? 'Klik untuk menambahkan deskripsi...') }}</p>
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 opacity-0 group-hover:opacity-100 transition ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </div>
                                    <textarea id="deskripsi-input" name="deskripsi" class="hidden w-full text-base text-gray-800 dark:text-gray-200 dark:bg-gray-700 border-b border-indigo-500 focus:outline-none bg-transparent rounded p-2" rows="3">{{ old('deskripsi', $user->deskripsi) }}</textarea>
                                </div>

                                <!-- Email -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-500 transition cursor-pointer group" onclick="toggleEdit('email')">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        {{ autoTranslate('Email') }}
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <p id="email-display" class="text-base font-medium dark:text-gray-200 text-gray-800 break-all">{{ autoTranslate(Auth::user()->email ?? '-') }}</p>
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 opacity-0 group-hover:opacity-100 transition ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </div>
                                    <input type="email" id="email-input" name="email" class="hidden w-full text-base font-medium text-gray-800 dark:text-gray-200 dark:bg-gray-700 border-b border-indigo-500 focus:outline-none bg-transparent" value="{{ old('email', Auth::user()->email) }}">
                                </div>

                                <!-- Jurusan -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-500 transition cursor-pointer group" onclick="toggleEdit('jurusan')">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                                        <span data-translate="jrs" data-translate-page="profile">{{ autoTranslate('Jurusan') }}</span>
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <p id="jurusan-display" class="text-base font-medium dark:text-gray-200 text-gray-800">{{ autoTranslate(Auth::user()->jurusan->nama_jurusan ?? '-') }}</p>
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 opacity-0 group-hover:opacity-100 transition ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </div>
                                    <select id="jurusan-input" name="id_jurusan" class="hidden w-full text-base font-medium dark:bg-gray-700 dark:text-white border-b border-indigo-500 focus:outline-none bg-white">
                                        <option value="">{{ autoTranslate('-- Pilih Jurusan --') }}</option>
                                        @foreach($jurusans ?? [] as $jurusan)
                                            <option value="{{ $jurusan->id_jurusan }}" {{ (Auth::user()->id_jurusan == $jurusan->id_jurusan) ? 'selected' : '' }}>{{ autoTranslate($jurusan->nama_jurusan) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Angkatan -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                                        <span data-translate="agkt" data-translate-page="profile">{{ autoTranslate('Angkatan') }}</span>
                                    </p>
                                    <p class="text-base font-medium text-gray-800 dark:text-gray-200">{{ autoTranslate(Auth::user()->angkatan->nama_angkatan ?? 'Angkatan 2026') }}</p>
                                </div>

                                <!-- Keahlian Utama -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-500 transition cursor-pointer group" onclick="toggleEdit('keahlian')">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                                        <span data-translate="khl_main" data-translate-page="profile">{{ autoTranslate('Keahlian Utama') }}</span>
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <p id="keahlian-display" class="text-base font-medium dark:text-gray-200 text-gray-800">{{ autoTranslate(Auth::user()->keahlian->nama_keahlian ?? '-') }}</p>
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 opacity-0 group-hover:opacity-100 transition ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </div>
                                    <select id="keahlian-input" name="id_keahlian" class="hidden w-full text-base font-medium dark:bg-gray-700 dark:text-white border-b border-indigo-500 focus:outline-none bg-white">
                                        <option value="">{{ autoTranslate('-- Pilih Keahlian --') }}</option>
                                        @foreach($keahlians ?? [] as $keahlian)
                                            <option value="{{ $keahlian->id_keahlian }}" {{ (Auth::user()->id_keahlian == $keahlian->id_keahlian) ? 'selected' : '' }}>{{ autoTranslate($keahlian->nama_keahlian) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Keahlian Tambahan -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 sm:col-span-2">
                                    <div class="flex items-center justify-between mb-4">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                            <span data-translate="khl_add" data-translate-page="profile">{{ autoTranslate('Keahlian Tambahan') }}</span>
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
                                                        <p x-show="item.keterangan" class="text-xs text-red-600 dark:text-red-400 mt-1" x-text="'{{ autoTranslate('Alasan: ') }}' + item.keterangan"></p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                            {{ autoTranslate('Diajukan:') }} <span x-text="new Date(item.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })"></span>
                                                        </p>
                                                    </div>
                                                    <button type="button" @click="deleteKeahlian(item.id, index)" class="ml-2 p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition" :disabled="loading">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </div>
                                            </template>
                                            <div x-show="keahlianList.length === 0" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                                <p class="text-base font-medium" data-translate="empty_khl" data-translate-page="profile">{{ autoTranslate('Belum ada keahlian tambahan') }}</p>
                                            </div>
                                        </div>
                                    </template>
                                    <div x-show="keahlianCount < 3" class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <form action="{{ route('keahlian-tambahan.store') }}" method="POST">
                                            @csrf
                                            <div class="flex flex-col sm:flex-row gap-3">
                                                <select x-model="selectedKeahlian" name="id_keahlian_tambahan" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-800 dark:text-white">
                                                    <option value="">{{ autoTranslate('-- Pilih Keahlian Tambahan --') }}</option>
                                                    <template x-for="keahlian in keahlianOptions" :key="keahlian.id_keahlian">
                                                        <option :value="keahlian.id_keahlian" :disabled="isKeahlianDisabled(keahlian)" x-text="keahlian.nama_keahlian + (keahlian.id_keahlian === mainSkillId ? ' (Keahlian Utama)' : isKeahlianExists(keahlian.id_keahlian) ? ' (Sudah Diajukan)' : '')"></option>
                                                    </template>
                                                </select>
                                                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">{{ autoTranslate('Ajukan') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div x-show="keahlianCount >= 3" class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                        <p class="text-sm text-yellow-800 dark:text-yellow-200 flex items-center">
                                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                            <span data-translate="max_req" data-translate-page="profile">{{ autoTranslate('Anda sudah mencapai maksimal 3 keahlian tambahan.') }}</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Video Perkenalan -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-500 transition cursor-pointer group sm:col-span-2" onclick="toggleEdit('video')">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="6" width="18" height="12" rx="4" stroke-width="2"/><polygon points="10,9 10,15 15,12" stroke-width="2"/></svg>
                                        <span data-translate="vid_intro" data-translate-page="profile">{{ autoTranslate('Video Perkenalan') }}</span>
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <p id="video-display" class="text-base text-gray-800 dark:text-gray-200 break-all flex-1">{{ autoTranslate($user->video_url ?? 'Klik untuk menambahkan video...') }}</p>
                                        <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </div>
                                    <input type="text" id="video-input" name="video_url" value="{{ old('video_url', $user->video_url) }}" placeholder="{{ autoTranslate('https://www.youtube.com/watch?v=xxxx') }}" class="hidden w-full text-base text-gray-800 dark:text-gray-200 dark:bg-gray-700 border-b border-indigo-500 focus:outline-none bg-transparent mt-2" oninput="updatePreview()">
                                    <div id="videoPreview" class="hidden mt-4">
                                        <iframe id="previewFrame" class="w-full h-64 rounded-xl" src="" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                </div>

                                <!-- Status Akun -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z" /></svg>
                                        <span data-translate="stat_acc" data-translate-page="profile">{{ autoTranslate('Status Akun') }}</span>
                                    </p>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-block w-3 h-3 rounded-full {{ Auth::user()->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        <p class="text-base font-medium {{ Auth::user()->is_active ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400' }}">{{ autoTranslate(Auth::user()->is_active ? 'Aktif' : 'Nonaktif') }}</p>
                                    </div>
                                </div>

                            </div>{{-- end grid --}}

                            <!-- Save Button -->
                            <div id="save-button-container" class="mt-8 text-center hidden">
                                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition shadow-md">{{ autoTranslate('Simpan Perubahan') }}</button>
                                <button type="button" onclick="window.location.reload()" class="ml-4 px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition">{{ autoTranslate('Batal') }}</button>
                            </div>
                        </div>
                    </form>

                    {{-- ============================================================ --}}
                    {{-- PENDIDIKAN SECTION --}}
                    {{-- ============================================================ --}}
                    <section class="mt-8 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 md:p-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-8 h-8 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                </svg>
                                {{ autoTranslate('Pendidikan') }}
                            </h2>
                            <button type="button" onclick="openPendidikanModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                {{ autoTranslate('Tambah Pendidikan') }}
                            </button>
                        </div>

                        @php $pendidikanList = Auth::user()->pendidikan ?? []; @endphp

                        @if(!empty($pendidikanList))
                            <div class="space-y-4">
                                @foreach($pendidikanList as $pend)
                                    <div class="flex items-start gap-4 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-xl border border-blue-100 dark:border-blue-800/30 hover:border-blue-300 dark:hover:border-blue-600 transition group">
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
                                                            <span class="text-green-600 dark:text-green-400 font-medium">{{ autoTranslate('Sekarang') }}</span>
                                                        @else
                                                            {{ $pend['tahun_lulus'] ?? autoTranslate('Belum selesai') }}
                                                        @endif
                                                    </p>
                                                </div>
                                                <button type="button" onclick="deletePendidikan('{{ $pend['id'] ?? '' }}')" class="opacity-0 group-hover:opacity-100 transition p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-blue-50/50 dark:bg-blue-900/10 rounded-xl border-2 border-dashed border-blue-200 dark:border-blue-800/30">
                                <svg class="w-14 h-14 mx-auto text-blue-300 dark:text-blue-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ autoTranslate('Belum ada data pendidikan. Klik "Tambah Pendidikan" untuk menambahkan.') }}</p>
                            </div>
                        @endif
                    </section>

                    {{-- ============================================================ --}}
                    {{-- PENGALAMAN KERJA SECTION --}}
                    {{-- ============================================================ --}}
                    <section class="mt-8 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 md:p-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-8 h-8 mr-2 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ autoTranslate('Pengalaman Kerja') }}
                            </h2>
                            <button type="button" onclick="openPengalamanModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                {{ autoTranslate('Tambah Pengalaman') }}
                            </button>
                        </div>

                        @php $pengalamanList = Auth::user()->pengalaman_kerja ?? []; @endphp

                        @if(!empty($pengalamanList))
                            <div class="space-y-4">
                                @foreach($pengalamanList as $pkj)
                                    <div class="flex items-start gap-4 p-4 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl border border-emerald-100 dark:border-emerald-800/30 hover:border-emerald-300 dark:hover:border-emerald-600 transition group">
                                        <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-gray-900 dark:text-white text-base">{{ $pkj['nama_pt'] ?? '-' }}</h4>
                                                    <p class="text-sm text-emerald-700 dark:text-emerald-400 font-medium mt-0.5">{{ $pkj['bagian_kerja'] ?? '-' }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                        {{ $pkj['tahun_mulai'] ?? '-' }} —
                                                        @if(!empty($pkj['masih_bekerja']) && $pkj['masih_bekerja'])
                                                            <span class="text-green-600 dark:text-green-400 font-medium">{{ autoTranslate('Sekarang') }}</span>
                                                        @else
                                                            {{ $pkj['tahun_akhir'] ?? autoTranslate('Selesai') }}
                                                        @endif
                                                    </p>
                                                    @if(!empty($pkj['sertifikat_pendukung']))
                                                        <a href="{{ asset('storage/' . $pkj['sertifikat_pendukung']) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 hover:underline mt-2">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                                            {{ autoTranslate('Lihat Sertifikat Pendukung') }}
                                                        </a>
                                                    @endif
                                                </div>
                                                <button type="button" onclick="deletePengalaman('{{ $pkj['id'] ?? '' }}')" class="opacity-0 group-hover:opacity-100 transition p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-emerald-50/50 dark:bg-emerald-900/10 rounded-xl border-2 border-dashed border-emerald-200 dark:border-emerald-800/30">
                                <svg class="w-14 h-14 mx-auto text-emerald-300 dark:text-emerald-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ autoTranslate('Belum ada pengalaman kerja. Klik "Tambah Pengalaman" untuk menambahkan.') }}</p>
                            </div>
                        @endif
                    </section>

                    @if(Auth::check())
                    <!-- Projects Section -->
                    <section class="mt-8 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 md:p-8" x-data="{ showAllProjects: false }">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-8 h-8 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                                <span data-translate="pjt" data-translate-page="profile">{{ autoTranslate('Projects') }}</span>
                            </h2>
                            <div class="flex items-center gap-3">
                                <span class="text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-4 py-1.5 rounded-full">{{ $user->projects->count() }} {{ autoTranslate('proyek') }}</span>
                                @if($user->projects->count() > 3)
                                    <button @click="showAllProjects = !showAllProjects" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 font-medium flex items-center gap-1">
                                        <span x-text="showAllProjects ? '{{ autoTranslate('Tampilkan lebih sedikit') }}' : '{{ autoTranslate('Lihat semua') }} ({{ $user->projects->count() }})'"></span>
                                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': showAllProjects }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                        @if($user->projects->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($user->projects as $index => $project)
                                    @php
                                        $content = $project->isi_content ?? [];
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
                                            if ($akhir < $today) { $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'; $statusText = autoTranslate('Selesai'); }
                                            elseif ($mulai <= $today && $today <= $akhir) { $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'; $statusText = autoTranslate('Sedang Berjalan'); }
                                            else { $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400'; $statusText = autoTranslate('Akan Datang'); }
                                        } elseif ($mulai && !$akhir) {
                                            if ($mulai <= $today) { $statusClass = 'bg-green-100 text-green-800'; $statusText = autoTranslate('Sedang Berjalan'); }
                                            else { $statusClass = 'bg-blue-100 text-blue-800'; $statusText = autoTranslate('Akan Datang'); }
                                        } else { $statusClass = 'bg-gray-100 text-gray-800'; $statusText = autoTranslate('Tidak diketahui'); }
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
                                    <div class="bg-white dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden flex flex-col h-full group"
                                        x-show="showAllProjects || {{ $index < 3 ? 'true' : 'false' }}"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                                        <div class="relative w-full bg-black overflow-hidden">
                                            @if($youtubeEmbedUrl)
                                                <div class="relative w-full pb-[56.25%] bg-gray-900 cursor-pointer" onclick="playVideo(this, '{{ $youtubeEmbedUrl }}')">
                                                    <img src="{{ $youtubeThumbnail }}" class="absolute inset-0 w-full h-full object-cover opacity-90" onerror="this.src='https://via.placeholder.com/480x360?text=Video'">
                                                    <div class="absolute inset-0 flex items-center justify-center">
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
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-2 flex-1">{{ autoTranslate($nama) }}</h3>
                                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }} whitespace-nowrap">{{ $statusText }}</span>
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-300 mb-3">{{ autoTranslate('Mulai:') }} {{ autoTranslate($mulaiFormatted) }} → {{ autoTranslate('Selesai:') }} {{ autoTranslate($akhirFormatted) }}</div>
                                            @if($deskripsi)<p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3 flex-1">{{ autoTranslate($deskripsi) }}</p>@endif
                                            <div class="flex flex-wrap gap-3 mt-auto pt-4 border-t border-gray-100 dark:border-gray-700">
                                                @if($linkProject)<a href="{{ $linkProject }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>{{ autoTranslate('Website') }}</a>@endif
                                                @if($linkGithub)<a href="{{ $linkGithub }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-800 dark:text-gray-300"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>GitHub</a>@endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-700">
                                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <p class="mt-4 text-gray-600 dark:text-gray-400">{{ autoTranslate('Belum ada proyek yang ditambahkan.') }}</p>
                            </div>
                        @endif
                    </section>

                    <!-- Sertifikat Section -->
                    <section class="mt-8 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 md:p-8" x-data="{ showAllSertifikat: false }">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-8 h-8 mr-2 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" /></svg>
                                <span data-translate="stk" data-translate-page="profile">{{ autoTranslate('Sertifikat') }}</span>
                            </h2>
                            <span class="text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-4 py-1.5 rounded-full">{{ $user->sertifikats?->count() ?? 0 }} {{ autoTranslate('sertifikat') }}</span>
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
                                    <div class="bg-white dark:bg-gray-700/50 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow border border-gray-200 dark:border-gray-600 flex flex-col h-full {{ $isInactive ? 'opacity-70' : '' }}"
                                        x-show="showAllSertifikat || {{ $index < 3 ? 'true' : 'false' }}"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                                        <div class="p-6 flex flex-col flex-1">
                                            <div class="flex items-center gap-2 mb-3 flex-wrap">
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $isInactive ? 'bg-gray-300 text-gray-700' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}">{{ autoTranslate('Sertifikat') }}</span>
                                                @if($sertifikat->status_pengajuan)<span class="text-xs px-2 py-1 rounded-full {{ $statusClass }}">{{ autoTranslate($sertifikat->status_pengajuan) }}</span>@endif
                                            </div>
                                            <h3 class="text-lg font-semibold {{ $isInactive ? 'text-gray-500' : 'text-gray-900 dark:text-white' }} mb-3 line-clamp-2">{{ autoTranslate($sertifikat->nama_sertifikat ?? 'Sertifikat Tanpa Judul') }}</h3>
                                            @if($sertifikat->lembaga_penerbit)<p class="text-sm text-gray-600 dark:text-gray-300 mb-2 flex items-center gap-1"><svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" /></svg>{{ autoTranslate($sertifikat->lembaga_penerbit) }}</p>@endif
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->translatedFormat('d M Y') : autoTranslate('Tanggal tidak tersedia') }}</p>
                                            @if($sertifikat->link_sertifikat)
                                                <a href="{{ asset('storage/' . $sertifikat->link_sertifikat) }}" target="_blank" class="mt-auto inline-flex items-center text-amber-600 dark:text-amber-400 hover:text-amber-800 font-medium text-sm gap-1">{{ autoTranslate('Lihat Sertifikat') }}<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg></a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-700">
                                <p class="text-gray-600 dark:text-gray-400">{{ autoTranslate('Belum ada sertifikat yang ditambahkan.') }}</p>
                            </div>
                        @endif
                    </section>

                    <!-- Learning Corners -->
                    <section class="mt-8 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 md:p-8 mb-10" x-data="{ showAllLearning: false }">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-8 h-8 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                {{ autoTranslate('Learning Corners') }}
                            </h2>
                            <span class="text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-4 py-1.5 rounded-full">{{ $user->learning_corners->count() }} {{ autoTranslate('catatan') }}</span>
                        </div>
                        @if($user->learning_corners->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($user->learning_corners as $index => $entry)
                                    <div class="bg-white dark:bg-gray-700/50 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow border border-gray-200 dark:border-gray-600 flex flex-col h-full"
                                        x-show="showAllLearning || {{ $index < 3 ? 'true' : 'false' }}"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                                        <div class="p-6 flex-1 flex flex-col">
                                            @if (!empty($entry->content) && is_array($entry->content))
                                                @foreach ($entry->content as $item)
                                                    @if ($item['type'] === 'title')<h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 line-clamp-2">{{ autoTranslate($item['content'] ?? '') }}</h3>
                                                    @elseif ($item['type'] === 'text')<p class="text-gray-700 dark:text-gray-300 mb-4 line-clamp-4">{{ autoTranslate($item['content']) }}</p>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-auto pt-4 border-t border-gray-100 dark:border-gray-700">{{ autoTranslate('Diposting pada:') }} {{ $entry->created_at?->translatedFormat('d M Y H:i') ?? '' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-700">
                                <p class="text-gray-600 dark:text-gray-400">{{ autoTranslate('Belum ada catatan learning corner.') }}</p>
                            </div>
                        @endif
                    </section>
                    @endif

                    <!-- Footer -->
                    <div class="mt-8 text-center text-xs text-gray-500 dark:text-gray-400">
                        {{ autoTranslate('Terakhir diperbarui:') }} {{ now()->translatedFormat('d F Y H:i') }} WIB
                    </div>

                </div>{{-- end x-data keahlianTambahan --}}
            </div>{{-- end actual-content --}}
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- MODAL TAMBAH PENDIDIKAN --}}
    {{-- ================================================================ --}}
    <div id="modal-pendidikan" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closePendidikanModal()"></div>
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0" id="modal-pendidikan-content">

                <!-- Header -->
                <div class="sticky top-0 bg-white dark:bg-gray-900 px-6 py-5 border-b border-gray-100 dark:border-gray-700 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ autoTranslate('Tambah Pendidikan') }}</h3>
                        </div>
                        <button onclick="closePendidikanModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <form action="{{ route('pendidikan.store') }}" method="POST" class="px-6 py-6 space-y-5">
                    @csrf

                    <!-- Nama Sekolah dengan Autocomplete -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ autoTranslate('Nama Institusi / Sekolah') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" id="sekolah-search-input" name="nama_sekolah" placeholder="{{ autoTranslate('Cari nama sekolah/universitas...') }}" required autocomplete="off"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition"
                                oninput="searchSekolah(this.value)">
                            <div class="absolute right-3 top-3">
                                <svg id="sekolah-search-icon" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                <svg id="sekolah-loading-icon" class="hidden w-5 h-5 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            </div>
                            <!-- Dropdown Autocomplete -->
                            <div id="sekolah-dropdown" class="hidden absolute top-full left-0 right-0 mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl shadow-xl z-50 max-h-52 overflow-y-auto">
                                <div id="sekolah-dropdown-list" class="divide-y divide-gray-100 dark:divide-gray-700"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Jenjang -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ autoTranslate('Jenjang Pendidikan') }} <span class="text-red-500">*</span></label>
                        <select name="jenjang" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                            <option value="">{{ autoTranslate('-- Pilih Jenjang --') }}</option>
                            @foreach(['SD','SMP','SMA/SMK','D1','D2','D3','D4','S1','S2','S3','Kursus/Pelatihan'] as $jenjang)
                                <option value="{{ $jenjang }}">{{ $jenjang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jurusan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ autoTranslate('Jurusan / Program Studi') }} <span class="text-xs font-normal text-gray-400">({{ autoTranslate('opsional') }})</span></label>
                        <input type="text" name="jurusan_sek" placeholder="{{ autoTranslate('Contoh: Teknik Informatika') }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                    </div>

                    <!-- Tahun -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ autoTranslate('Tahun Masuk') }} <span class="text-red-500">*</span></label>
                            <input type="number" name="tahun_masuk" placeholder="2020" min="1950" max="{{ date('Y') + 1 }}" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                        </div>
                        <div id="tahun-lulus-field">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ autoTranslate('Tahun Lulus') }}</label>
                            <input type="number" name="tahun_lulus" placeholder="{{ date('Y') }}" min="1950" max="{{ date('Y') + 1 }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                        </div>
                    </div>

                    <!-- Masih Kuliah -->
                    <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/10 rounded-xl">
                        <input type="checkbox" name="masih_kuliah" id="masih_kuliah" value="1" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" onchange="toggleTahunLulus(this)">
                        <label for="masih_kuliah" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">{{ autoTranslate('Masih bersekolah / kuliah di sini') }}</label>
                    </div>

                    <!-- Footer -->
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closePendidikanModal()" class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl transition">{{ autoTranslate('Batal') }}</button>
                        <button type="submit" class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-sm">{{ autoTranslate('Simpan Pendidikan') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- MODAL TAMBAH PENGALAMAN KERJA --}}
    {{-- ================================================================ --}}
    <div id="modal-pengalaman" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closePengalamanModal()"></div>
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0" id="modal-pengalaman-content">

                <!-- Header -->
                <div class="sticky top-0 bg-white dark:bg-gray-900 px-6 py-5 border-b border-gray-100 dark:border-gray-700 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ autoTranslate('Tambah Pengalaman Kerja') }}</h3>
                        </div>
                        <button onclick="closePengalamanModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <form action="{{ route('pengalaman-kerja.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-5">
                    @csrf

                    <!-- Nama Perusahaan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ autoTranslate('Nama Perusahaan / Instansi') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pt" placeholder="{{ autoTranslate('Contoh: PT. Telkom Indonesia') }}" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                    </div>

                    <!-- Posisi / Bagian Kerja -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ autoTranslate('Posisi / Bagian Kerja') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="bagian_kerja" placeholder="{{ autoTranslate('Contoh: Software Engineer') }}" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                    </div>

                    <!-- Tahun -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ autoTranslate('Tahun Mulai') }} <span class="text-red-500">*</span></label>
                            <input type="number" name="tahun_mulai" placeholder="2022" min="1950" max="{{ date('Y') + 1 }}" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                        </div>
                        <div id="tahun-akhir-field">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ autoTranslate('Tahun Selesai') }}</label>
                            <input type="number" name="tahun_akhir" placeholder="{{ date('Y') }}" min="1950" max="{{ date('Y') + 1 }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:bg-gray-800 dark:text-white text-sm transition">
                        </div>
                    </div>

                    <!-- Masih Bekerja -->
                    <div class="flex items-center gap-3 p-3 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl">
                        <input type="checkbox" name="masih_bekerja" id="masih_bekerja" value="1" class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500" onchange="toggleTahunAkhir(this)">
                        <label for="masih_bekerja" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">{{ autoTranslate('Masih bekerja di sini') }}</label>
                    </div>

                    <!-- Sertifikat Pendukung -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            {{ autoTranslate('Sertifikat Pendukung') }}
                            <span class="text-xs font-normal text-gray-400 ml-1">({{ autoTranslate('JPG, PNG, PDF maks. 5MB') }})</span>
                        </label>
                        <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-5 hover:border-emerald-400 dark:hover:border-emerald-500 transition cursor-pointer" onclick="document.getElementById('sertifikat-file-input').click()">
                            <div class="text-center" id="sertifikat-upload-placeholder">
                                <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ autoTranslate('Klik untuk upload file') }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ autoTranslate('atau drag & drop di sini') }}</p>
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

                    <!-- Footer -->
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closePengalamanModal()" class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl transition">{{ autoTranslate('Batal') }}</button>
                        <button type="submit" class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition shadow-sm">{{ autoTranslate('Simpan Pengalaman') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // ===================== SKELETON =====================
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const skeleton = document.getElementById('skeleton-loading');
                const content = document.getElementById('actual-content');
                if (skeleton && content) {
                    skeleton.style.display = 'none';
                    content.style.display = 'block';
                }
            }, 500);
        });

        // ===================== TOGGLE EDIT =====================
        function toggleEdit(field) {
            const displayEl = document.getElementById(field + '-display');
            const inputEl = document.getElementById(field + '-input');
            const saveBtn = document.getElementById('save-button-container');
            if (!displayEl || !inputEl) return;
            displayEl.classList.add('hidden');
            inputEl.classList.remove('hidden');
            const container = document.getElementById(field + '-container');
            if (container) container.classList.add('hidden');
            inputEl.focus();
            saveBtn.classList.remove('hidden');
        }

        // ===================== PHOTO PROFILE =====================
        document.getElementById('photo_profile_input')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(ev) {
                const preview = document.getElementById('profile-preview');
                const placeholder = document.getElementById('profile-preview-placeholder');
                if (preview) { preview.src = ev.target.result; }
                else if (placeholder) {
                    const newImg = document.createElement('img');
                    newImg.id = 'profile-preview';
                    newImg.className = 'w-full h-full object-cover';
                    newImg.src = ev.target.result;
                    placeholder.replaceWith(newImg);
                }
            };
            reader.readAsDataURL(file);
            document.getElementById('save-button-container').classList.remove('hidden');
        });

        // ===================== BACKGROUND =====================
        document.getElementById('background_input')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(ev) {
                const coverDiv = document.querySelector('#actual-content .relative.h-48');
                if (coverDiv) {
                    coverDiv.style.backgroundImage = `url('${ev.target.result}')`;
                    coverDiv.classList.add('bg-cover', 'bg-center');
                }
            };
            reader.readAsDataURL(file);
            document.getElementById('save-button-container').classList.remove('hidden');
        });

        // ===================== VIDEO PREVIEW =====================
        function convertToEmbed(url) {
            if (!url) return '';
            if (url.includes('watch?v=')) return url.replace('watch?v=', 'embed/');
            if (url.includes('youtu.be/')) return url.replace('youtu.be/', 'youtube.com/embed/');
            return url;
        }
        function updatePreview() {
            const input = document.getElementById('video-input').value;
            const preview = document.getElementById('videoPreview');
            const iframe = document.getElementById('previewFrame');
            const embedUrl = convertToEmbed(input);
            if (embedUrl) { iframe.src = embedUrl; preview.classList.remove('hidden'); }
            else { preview.classList.add('hidden'); iframe.src = ''; }
        }
        document.addEventListener('DOMContentLoaded', updatePreview);

        function playVideo(element, embedUrl) {
            const iframe = document.createElement('iframe');
            iframe.className = 'absolute inset-0 w-full h-full';
            iframe.src = embedUrl + '?autoplay=1&rel=0';
            iframe.frameborder = '0';
            iframe.allowFullscreen = true;
            element.innerHTML = '';
            element.appendChild(iframe);
        }

        // ===================== COPY LINK =====================
        function copyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                const n = document.createElement('div');
                n.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                n.innerHTML = '<div class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>{{ autoTranslate('Link berhasil disalin!') }}</span></div>';
                document.body.appendChild(n);
                setTimeout(() => n.remove(), 3000);
            });
        }

        // ===================== MODAL PENDIDIKAN =====================
        function openPendidikanModal() {
            const modal = document.getElementById('modal-pendidikan');
            const content = document.getElementById('modal-pendidikan-content');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            });
        }
        function closePendidikanModal() {
            const modal = document.getElementById('modal-pendidikan');
            const content = document.getElementById('modal-pendidikan-content');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
            document.getElementById('sekolah-dropdown').classList.add('hidden');
        }

        // ===================== MODAL PENGALAMAN =====================
        function openPengalamanModal() {
            const modal = document.getElementById('modal-pengalaman');
            const content = document.getElementById('modal-pengalaman-content');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            });
        }
        function closePengalamanModal() {
            const modal = document.getElementById('modal-pengalaman');
            const content = document.getElementById('modal-pengalaman-content');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
        }

        // ===================== SEARCH SEKOLAH =====================
        let searchSekolahTimer = null;
        function searchSekolah(query) {
            clearTimeout(searchSekolahTimer);
            const dropdown = document.getElementById('sekolah-dropdown');
            const list = document.getElementById('sekolah-dropdown-list');
            const loadingIcon = document.getElementById('sekolah-loading-icon');
            const searchIcon = document.getElementById('sekolah-search-icon');

            if (query.length < 2) {
                dropdown.classList.add('hidden');
                return;
            }

            loadingIcon.classList.remove('hidden');
            searchIcon.classList.add('hidden');

            searchSekolahTimer = setTimeout(async () => {
                try {
                    const locale = document.querySelector('html').getAttribute('lang') || 'id';
                    const response = await fetch(`/${locale}/sekolah/search?q=${encodeURIComponent(query)}`);
                    const data = await response.json();

                    list.innerHTML = '';

                    if (data.length === 0) {
                        list.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center">{{ autoTranslate('Tidak ada hasil. Ketik nama secara manual.') }}</div>';
                    } else {
                        data.forEach(sekolah => {
                            const item = document.createElement('button');
                            item.type = 'button';
                            item.className = 'w-full text-left px-4 py-3 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition';
                            item.innerHTML = `
                                <div class="font-medium text-sm text-gray-900 dark:text-white">${sekolah.nama}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">${sekolah.jenjang ? sekolah.jenjang + ' · ' : ''}${sekolah.kota || ''}${sekolah.provinsi ? ', ' + sekolah.provinsi : ''}</div>
                            `;
                            item.addEventListener('click', () => {
                                document.getElementById('sekolah-search-input').value = sekolah.nama;
                                dropdown.classList.add('hidden');
                            });
                            list.appendChild(item);
                        });
                    }

                    dropdown.classList.remove('hidden');
                } catch (err) {
                    console.error('Search sekolah error:', err);
                } finally {
                    loadingIcon.classList.add('hidden');
                    searchIcon.classList.remove('hidden');
                }
            }, 400);
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('sekolah-dropdown');
            const input = document.getElementById('sekolah-search-input');
            if (dropdown && input && !input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // ===================== TOGGLE TAHUN =====================
        function toggleTahunLulus(checkbox) {
            const field = document.getElementById('tahun-lulus-field');
            const input = field.querySelector('input');
            if (checkbox.checked) {
                field.style.opacity = '0.4';
                input.disabled = true;
                input.value = '';
            } else {
                field.style.opacity = '1';
                input.disabled = false;
            }
        }
        function toggleTahunAkhir(checkbox) {
            const field = document.getElementById('tahun-akhir-field');
            const input = field.querySelector('input');
            if (checkbox.checked) {
                field.style.opacity = '0.4';
                input.disabled = true;
                input.value = '';
            } else {
                field.style.opacity = '1';
                input.disabled = false;
            }
        }

        // ===================== SERTIFIKAT FILE =====================
        function handleSertifikatFile(input) {
            const file = input.files[0];
            if (!file) return;
            const placeholder = document.getElementById('sertifikat-upload-placeholder');
            const preview = document.getElementById('sertifikat-file-preview');
            const nameEl = document.getElementById('sertifikat-file-name');
            const sizeEl = document.getElementById('sertifikat-file-size');
            nameEl.textContent = file.name;
            sizeEl.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            placeholder.classList.add('hidden');
            preview.classList.remove('hidden');
        }
        function clearSertifikatFile(event) {
            event.stopPropagation();
            const input = document.getElementById('sertifikat-file-input');
            input.value = '';
            document.getElementById('sertifikat-upload-placeholder').classList.remove('hidden');
            document.getElementById('sertifikat-file-preview').classList.add('hidden');
        }

        // ===================== DELETE PENDIDIKAN =====================
        async function deletePendidikan(id) {
            if (!confirm('{{ autoTranslate('Hapus data pendidikan ini?') }}')) return;
            try {
                const locale = document.querySelector('html').getAttribute('lang') || 'id';
                const response = await fetch(`/${locale}/pendidikan/destroy?id=${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                const data = await response.json();
                if (data.success) { window.location.reload(); }
                else { alert(data.message || '{{ autoTranslate('Gagal menghapus') }}'); }
            } catch (err) { console.error(err); }
        }

        // ===================== DELETE PENGALAMAN =====================
        async function deletePengalaman(id) {
            if (!confirm('{{ autoTranslate('Hapus pengalaman kerja ini?') }}')) return;
            try {
                const locale = document.querySelector('html').getAttribute('lang') || 'id';
                const response = await fetch(`/${locale}/pengalaman-kerja/destroy?id=${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                const data = await response.json();
                if (data.success) { window.location.reload(); }
                else { alert(data.message || '{{ autoTranslate('Gagal menghapus') }}'); }
            } catch (err) { console.error(err); }
        }

        // Success alert
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showSuccessAlert === 'function') showSuccessAlert('{{ autoTranslate(session('success')) }}');
            });
        @endif

        document.addEventListener("DOMContentLoaded", () => {
            if (typeof showPageInfo === 'function') {
                showPageInfo("{{ autoTranslate('Kelola profile diri anda.') }}");
            }
        });
    </script>

    <!-- Alpine.js Data -->
    <script>
        function keahlianTambahan() {
            return {
                keahlianOptions: @json($keahlians),
                mainSkillId: @json(Auth::user()->id_keahlian),
                keahlianList: @json($user->keahlianTambahan ?? []),
                selectedKeahlian: @json(old('id_keahlian_tambahan', '')),
                loading: false,
                showAlert: false,
                alertMessage: '',
                alertType: 'success',
                get keahlianCount() { return this.keahlianList ? this.keahlianList.length : 0; },
                isKeahlianDisabled(keahlian) { return keahlian.id_keahlian === this.mainSkillId || this.isKeahlianExists(keahlian.id_keahlian); },
                isKeahlianSelectedInTambahan(id) { return this.keahlianList.some(item => item.id_keahlian == id); },
                init() { this.refreshKeahlianList(); },
                isKeahlianExists(id) { return this.keahlianList.some(item => item.id_keahlian == id); },
                showNotification(message, type = 'success') {
                    this.alertMessage = message; this.alertType = type; this.showAlert = true;
                    setTimeout(() => { this.showAlert = false; }, 5000);
                },
                async deleteKeahlian(id, index) {
                    if (!confirm('{{ autoTranslate('Hapus keahlian tambahan ini?') }}')) return;
                    this.loading = true;
                    try {
                        const locale = document.querySelector('html').getAttribute('lang') || 'id';
                        const response = await fetch(`/${locale}/keahlian-tambahan/destroy?id=${id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                        });
                        const data = await response.json();
                        if (data.success) { await this.refreshKeahlianList(); this.showNotification(data.message, 'success'); }
                        else { this.showNotification(data.message || 'Gagal menghapus', 'error'); }
                    } catch (error) { this.showNotification('Terjadi kesalahan jaringan', 'error'); }
                    finally { this.loading = false; }
                },
                async refreshKeahlianList() {
                    try {
                        const response = await fetch('{{ route("keahlian-tambahan.index") }}', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                        const data = await response.json();
                        if (data.success) this.keahlianList = data.data;
                    } catch (error) { console.error('Error refreshing keahlian list:', error); }
                }
            }
        }
    </script>
@endsection