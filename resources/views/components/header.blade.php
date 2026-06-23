<header class="bg-white/80 dark:bg-gray-900/70 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 z-50 transition-all">
    <div class="px-4 py-3 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-3">

            <!-- Mobile left -->
            <div class="flex items-center gap-3 lg:hidden">
                @auth
                    @if(Auth::user()->role !== 'mahasiswa')
                    <button id="toggle-sidebar" class="text-gray-700 dark:text-gray-300 focus:outline-none">
                        <svg id="sidebar-hamburger" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="sidebar-close" class="w-7 h-7 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    @endif
                @endauth
            </div>

            <!-- Mobile logo -->
            <div class="absolute left-1/2 -translate-x-1/2 lg:hidden">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 select-none whitespace-nowrap">
                    <img src="{{ asset('assets/Logo.svg') }}" alt="Logo" class="h-8 rounded-xl w-auto">
                    <span class="text-xs font-semibold tracking-tight text-gray-800 dark:text-gray-100 font-sans">
                        Portofolio<span class="bg-gradient-to-r from-indigo-600 to-indigo-500 dark:from-indigo-400 dark:to-indigo-300 bg-clip-text text-transparent font-extrabold ml-1">Mahasiswa</span>
                    </span>
                </a>
            </div>

            <!-- Mobile right -->
            <div class="flex items-center gap-2 lg:hidden">
                <button id="toggle-search-mobile" class="text-gray-700 dark:text-gray-300 focus:outline-none p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                @auth
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'mahasiswa')
                    <div x-data="notificationBell({ userId: {{ Auth::id() }}, userRole: '{{ Auth::user()->role }}' })" x-init="init()" class="relative">
                        <button type="button" @click="toggleDropdown" class="relative p-2 bg-white/80 dark:bg-gray-800/80 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full shadow-md transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span x-show="unreadCount > 0" x-text="unreadCount > 99 ? '99+' : unreadCount" class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white animate-pulse"></span>
                        </button>
                        <div x-show="isOpen" @click.away="isOpen = false" x-transition class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
                            <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-900 dark:text-white" data-translate="notifikasi" data-translate-page="header">Notifikasi</h3>
                                    <div class="flex gap-2">
                                        <button @click="markAllAsRead" class="text-xs text-blue-600 dark:text-blue-400 hover:underline" data-translate="tandai" data-translate-page="header">Tandai</button>
                                        <button @click="clearAll" class="text-xs text-red-600 dark:text-red-400 hover:underline" data-translate="hapus" data-translate-page="header">Hapus</button>
                                    </div>
                                </div>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <template x-if="notifications.length === 0">
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="text-sm" data-translate="belum_ada_notifikasi" data-translate-page="header">Belum ada notifikasi</p>
                                    </div>
                                </template>
                                <template x-for="item in notifications" :key="item.id">
                                    <div @click="item.data.link ? handleNotificationClick(item) : null" class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 transition" :class="{ 'cursor-pointer': item.data.link, 'bg-blue-50 dark:bg-blue-900/20': !item.read }">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center" :class="getIconBg(item.type)">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="item.data.title"></p>
                                                    <span x-show="item.priority === 'high'" class="ml-2 px-1.5 py-0.5 text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded" data-translate="penting" data-translate-page="header">PENTING</span>
                                                </div>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1" x-text="item.data.message"></p>
                                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="formatTime(item.created_at)"></p>
                                            </div>
                                            <span x-show="!item.read" class="flex-shrink-0 w-2 h-2 bg-blue-600 dark:bg-blue-400 rounded-full"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    @endif
                @endauth
            </div>

            <!-- Desktop -->
            <div id="search-container" class="hidden lg:flex lg:items-center lg:gap-2 w-full max-w-6xl mx-auto">

                <div class="post-search-wrapper flex items-center gap-2 flex-1 min-w-0" id="post-search-area">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none z-10">
                            <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            id="unified-search-input"
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Cari postingan, mahasiswa, project..."
                            class="w-full pl-10 pr-8 py-2.5 border border-gray-300/80 dark:border-gray-700/80 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 text-sm transition shadow-sm bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm"
                            autocomplete="off"
                            data-suggestions-url="{{ route('search.suggestions') }}"
                            data-search-url="{{ route('search') }}"
                            data-translate-placeholder="cari_postingan_mahasiswa_project"
                            data-translate-page="header"
                        >
                        <button id="header-post-search-clear" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <div id="search-suggestions" class="hidden absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl rounded-xl overflow-hidden max-h-72 overflow-y-auto"></div>
                    </div>
                </div>

                <div class="h-6 w-px bg-gray-300 dark:bg-gray-600 flex-shrink-0 mx-1"></div>

                <div class="flex items-center gap-2 flex-shrink-0">
                    <select id="filter-jurusan" name="jurusan"
                        class="border border-gray-300/80 dark:border-gray-600/80 rounded-lg py-2.5 px-3 text-sm bg-white/80 dark:bg-gray-800/80 text-gray-700 dark:text-gray-200 shadow-sm transition hover:border-indigo-400 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 backdrop-blur-sm cursor-pointer">
                        <option value="" data-translate="semua_prodi" data-translate-page="header">Semua Prodi</option>
                        @foreach($jurusanList ?? [] as $jurusan)
                            <option value="{{ $jurusan->id_jurusan }}" {{ request('jurusan') == $jurusan->id_jurusan ? 'selected' : '' }}>{{ Str::limit($jurusan->nama_jurusan, 18) }}</option>
                        @endforeach
                    </select>

                    <select id="filter-keahlian" name="keahlian"
                        class="border border-gray-300/80 dark:border-gray-600/80 rounded-lg py-2.5 px-3 text-sm bg-white/80 dark:bg-gray-800/80 text-gray-700 dark:text-gray-200 shadow-sm transition hover:border-indigo-400 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 backdrop-blur-sm cursor-pointer">
                        <option value="" data-translate="semua_keahlian" data-translate-page="header">Semua Keahlian</option>
                        @foreach($keahlianList ?? [] as $keahlian)
                            <option value="{{ $keahlian->id_keahlian }}" {{ request('keahlian') == $keahlian->id_keahlian ? 'selected' : '' }}>{{ Str::limit($keahlian->nama_keahlian, 18) }}</option>
                        @endforeach
                    </select>

                    <select id="filter-angkatan" name="angkatan"
                        class="border border-gray-300/80 dark:border-gray-600/80 rounded-lg py-2.5 px-3 text-sm bg-white/80 dark:bg-gray-800/80 text-gray-700 dark:text-gray-200 shadow-sm transition hover:border-indigo-400 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 backdrop-blur-sm cursor-pointer">
                        <option value="" data-translate="semua_angkatan" data-translate-page="header">Semua Angkatan</option>
                        @foreach($angkatanList ?? [] as $angkatan)
                            <option value="{{ $angkatan->id }}" {{ request('angkatan') == $angkatan->id ? 'selected' : '' }}>{{ $angkatan->nama_angkatan }}</option>
                        @endforeach
                    </select>

                    <button id="filter-search-btn"
                        class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm flex-shrink-0"
                        title="Cari & Filter">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                        <span data-translate="filter" data-translate-page="header">Filter</span>
                    </button>

                    <a href="{{ route('search') }}" id="filter-reset-btn"
                        class="flex items-center px-3 py-2.5 border border-gray-300/80 dark:border-gray-600/80 rounded-lg bg-white/80 dark:bg-gray-800/80 text-gray-500 dark:text-gray-400 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm flex-shrink-0"
                        title="Reset filter"
                        style="display: none;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span data-translate="reset" data-translate-page="header">Reset</span>
                    </a>
                </div>

                @auth
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'mahasiswa')
                    <div class="ml-1 relative flex-shrink-0" x-data="notificationBell({ userId: {{ Auth::id() }}, userRole: '{{ Auth::user()->role }}' })" x-init="init()">
                        <button type="button" @click="toggleDropdown" class="relative p-2.5 bg-white/70 dark:bg-gray-800/70 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full shadow-md transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span x-show="unreadCount > 0" x-text="unreadCount > 99 ? '99+' : unreadCount" class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white animate-pulse"></span>
                        </button>
                        <div x-show="isOpen" @click.away="isOpen = false" x-transition class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
                            <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-900 dark:text-white" data-translate="notifikasi" data-translate-page="header">Notifikasi</h3>
                                    <div class="flex gap-2">
                                        <button @click="markAllAsRead" class="text-xs text-blue-600 dark:text-blue-400 hover:underline" data-translate="tandai" data-translate-page="header">Tandai</button>
                                        <button @click="clearAll" class="text-xs text-red-600 dark:text-red-400 hover:underline" data-translate="hapus" data-translate-page="header">Hapus</button>
                                    </div>
                                </div>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <template x-if="notifications.length === 0">
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="text-sm" data-translate="belum_ada_notifikasi" data-translate-page="header">Belum ada notifikasi</p>
                                    </div>
                                </template>
                                <template x-for="item in notifications" :key="item.id">
                                    <div @click="item.data.link ? handleNotificationClick(item) : null" class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 transition" :class="{ 'cursor-pointer': item.data.link, 'bg-blue-50 dark:bg-blue-900/20': !item.read }">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center" :class="getIconBg(item.type)">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="item.data.title"></p>
                                                    <span x-show="item.priority === 'high'" class="ml-2 px-1.5 py-0.5 text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded" data-translate="penting" data-translate-page="header">PENTING</span>
                                                </div>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1" x-text="item.data.message"></p>
                                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="formatTime(item.created_at)"></p>
                                            </div>
                                            <span x-show="!item.read" class="flex-shrink-0 w-2 h-2 bg-blue-600 dark:bg-blue-400 rounded-full"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- Mobile Search Dropdown -->
    <div id="mobile-search-dropdown" class="lg:hidden bg-white/90 dark:bg-gray-900/90 backdrop-blur-lg border-b border-gray-200/50 dark:border-gray-700/50 hidden">
        <div class="px-4 py-5 space-y-4 sm:px-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    id="unified-search-input-mobile"
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari postingan, mahasiswa, project..."
                    class="w-full pl-11 pr-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-gray-700 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm bg-white/80 dark:bg-gray-800/80"
                    autocomplete="off"
                    data-suggestions-url="{{ route('search.suggestions') }}"
                    data-search-url="{{ route('search') }}"
                    data-translate-placeholder="cari_postingan_mahasiswa_project"
                    data-translate-page="header"
                >
                <p id="header-post-search-badge-mobile" class="mt-1.5 text-xs text-indigo-600 dark:text-indigo-400 hidden">
                    <span id="header-post-search-count-mobile">0</span> <span data-translate="postingan_ditemukan" data-translate-page="header">postingan ditemukan</span>
                </p>
                <div id="search-suggestions-mobile" class="hidden absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl rounded-xl overflow-hidden max-h-72 overflow-y-auto"></div>
            </div>

            <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2">
                <select id="filter-jurusan-mobile" name="jurusan"
                    class="block w-full border border-gray-300/80 dark:border-gray-700/80 rounded-lg py-2.5 px-3 text-sm bg-white/80 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300">
                    <option value="" data-translate="semua_prodi" data-translate-page="header">Semua Prodi</option>
                    @foreach($jurusanList ?? [] as $jurusan)
                        <option value="{{ $jurusan->id_jurusan }}" {{ request('jurusan') == $jurusan->id_jurusan ? 'selected' : '' }}>{{ $jurusan->nama_jurusan }}</option>
                    @endforeach
                </select>
                <select id="filter-keahlian-mobile" name="keahlian"
                    class="block w-full border border-gray-300/80 dark:border-gray-700/80 rounded-lg py-2.5 px-3 text-sm bg-white/80 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300">
                    <option value="" data-translate="semua_keahlian" data-translate-page="header">Semua Keahlian</option>
                    @foreach($keahlianList ?? [] as $keahlian)
                        <option value="{{ $keahlian->id_keahlian }}" {{ request('keahlian') == $keahlian->id_keahlian ? 'selected' : '' }}>{{ $keahlian->nama_keahlian }}</option>
                    @endforeach
                </select>
                <select id="filter-angkatan-mobile" name="angkatan"
                    class="block w-full border border-gray-300/80 dark:border-gray-700/80 rounded-lg py-2.5 px-3 text-sm bg-white/80 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300 sm:col-span-2">
                    <option value="" data-translate="semua_angkatan" data-translate-page="header">Semua Angkatan</option>
                    @foreach($angkatanList ?? [] as $angkatan)
                        <option value="{{ $angkatan->id }}" {{ request('angkatan') == $angkatan->id ? 'selected' : '' }}>{{ $angkatan->nama_angkatan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-3">
                <button id="filter-search-btn-mobile" type="button"
                    class="flex-1 bg-indigo-600 text-white py-3 rounded-lg text-sm font-medium hover:bg-indigo-700 transition flex items-center justify-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    <span data-translate="filter_cari" data-translate-page="header">Filter & Cari</span>
                </button>
                <a href="{{ route('search') }}"
                    class="flex-1 bg-white/60 dark:bg-gray-700/60 text-gray-700 dark:text-gray-300 py-3 rounded-lg text-sm font-medium hover:bg-gray-100/80 dark:hover:bg-gray-600 transition flex items-center justify-center border border-gray-300/50 dark:border-gray-600/50">
                    <span data-translate="reset" data-translate-page="header">Reset</span>
                </a>
            </div>
        </div>
    </div>
</header>