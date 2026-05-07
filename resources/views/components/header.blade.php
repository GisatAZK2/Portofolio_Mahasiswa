<style>
    .backdrop-blur-md {
        backdrop-filter: blur(12px);
    }
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }
    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .dark .overflow-y-auto::-webkit-scrollbar-track {
        background: #1f2937;
    }
    .dark .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #4b5563;
    }

    /* ── Post Search (header) ── */
    #unified-search-input::placeholder {
        color: #9ca3af;
    }
    .post-search-wrapper {
        position: relative;
    }
    #header-post-search-clear {
        display: none;
    }
    /* result badge */
    #header-post-search-badge {
        display: none;
        font-size: 11px;
        white-space: nowrap;
    }

    /* Search suggestions dropdown */
    #search-suggestions {
        z-index: 9999;
    }
    #search-suggestions-mobile {
        z-index: 9999;
    }

    /* Notification dropdown */
    [x-data] [x-show] {
        z-index: 9998;
    }
</style>

<header class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 z-50 transition-all">
    <div class="px-4 py-3 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-3">

            <!-- ── Mobile left: Hamburger + search icon ── -->
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

                <!-- Mobile search toggle -->
                <button id="toggle-search-mobile" class="text-gray-700 dark:text-gray-300 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>

            <!-- ── Mobile title ── -->
            <div class="absolute left-1/2 -translate-x-1/2 lg:hidden">
                <a href="{{ route('dashboard') }}">
                    <h1 class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 tracking-wide">PORTOFOLIO MAHASISWA</h1>
                </a>
            </div>

            <!-- ── Mobile right: notification bell ── -->
            <div class="flex items-center gap-2 lg:hidden">
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
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Notifikasi</h3>
                                    <div class="flex gap-2">
                                        <button @click="markAllAsRead" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Tandai</button>
                                        <button @click="clearAll" class="text-xs text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                    </div>
                                </div>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <template x-if="notifications.length === 0">
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="text-sm">Belum ada notifikasi</p>
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
                                                    <span x-show="item.priority === 'high'" class="ml-2 px-1.5 py-0.5 text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded">PENTING</span>
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

            <!-- ══════════════════════════════════════════════════════════
                 DESKTOP HEADER BAR
            ══════════════════════════════════════════════════════════ -->
            <div id="search-container" class="hidden lg:flex lg:items-center lg:gap-2 w-full max-w-6xl mx-auto">

                <!-- ── UNIFIED Search Input (post search + global search in one) ── -->
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
                            placeholder="{{ autoTranslate('Cari postingan, mahasiswa, project...') }}"
                            class="w-full pl-10 pr-8 py-2.5 border border-gray-300/80 dark:border-gray-700/80 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 text-sm transition shadow-sm bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm"
                            autocomplete="off"
                        >
                        <!-- clear button -->
                        <button id="header-post-search-clear" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <!-- Global search suggestions dropdown -->
                        <div id="search-suggestions" class="hidden absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl rounded-xl overflow-hidden max-h-72 overflow-y-auto"></div>
                    </div>
                </div>

                <!-- ── Divider ── -->
                <div class="h-6 w-px bg-gray-300 dark:bg-gray-600 flex-shrink-0 mx-1"></div>

                <!-- ── Filter Dropdowns (Program Studi, Keahlian, Angkatan) ── -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    <select id="filter-jurusan" name="jurusan"
                        class="border border-gray-300/80 dark:border-gray-600/80 rounded-lg py-2.5 px-3 text-sm bg-white/80 dark:bg-gray-800/80 text-gray-700 dark:text-gray-200 shadow-sm transition hover:border-indigo-400 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 backdrop-blur-sm cursor-pointer">
                        <option value="">{{ autoTranslate('Semua Prodi') }}</option>
                        @foreach($jurusanList ?? [] as $jurusan)
                            <option value="{{ $jurusan->id_jurusan }}" {{ request('jurusan') == $jurusan->id_jurusan ? 'selected' : '' }}>{{ Str::limit($jurusan->nama_jurusan, 18) }}</option>
                        @endforeach
                    </select>

                    <select id="filter-keahlian" name="keahlian"
                        class="border border-gray-300/80 dark:border-gray-600/80 rounded-lg py-2.5 px-3 text-sm bg-white/80 dark:bg-gray-800/80 text-gray-700 dark:text-gray-200 shadow-sm transition hover:border-indigo-400 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 backdrop-blur-sm cursor-pointer">
                        <option value="">{{ autoTranslate('Semua Keahlian') }}</option>
                        @foreach($keahlianList ?? [] as $keahlian)
                            <option value="{{ $keahlian->id_keahlian }}" {{ request('keahlian') == $keahlian->id_keahlian ? 'selected' : '' }}>{{ Str::limit($keahlian->nama_keahlian, 18) }}</option>
                        @endforeach
                    </select>

                    <select id="filter-angkatan" name="angkatan"
                        class="border border-gray-300/80 dark:border-gray-600/80 rounded-lg py-2.5 px-3 text-sm bg-white/80 dark:bg-gray-800/80 text-gray-700 dark:text-gray-200 shadow-sm transition hover:border-indigo-400 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 backdrop-blur-sm cursor-pointer">
                        <option value="">{{ autoTranslate('Semua Angkatan') }}</option>
                        @foreach($angkatanList ?? [] as $angkatan)
                            <option value="{{ $angkatan->id }}" {{ request('angkatan') == $angkatan->id ? 'selected' : '' }}>{{ $angkatan->nama_angkatan }}</option>
                        @endforeach
                    </select>

                    <!-- ── Filter/Search Button (triggers global search) ── -->
                    <button id="filter-search-btn"
                        class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm flex-shrink-0"
                        title="{{ autoTranslate('Cari & Filter') }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                        <span>{{ autoTranslate('Filter') }}</span>
                    </button>

                    <!-- ── Reset link ── -->
                    <a href="{{ route('search') }}" id="filter-reset-btn"
                        class="flex items-center px-3 py-2.5 border border-gray-300/80 dark:border-gray-600/80 rounded-lg bg-white/80 dark:bg-gray-800/80 text-gray-500 dark:text-gray-400 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm flex-shrink-0"
                        title="{{ autoTranslate('Reset filter') }}"
                        style="display: none;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </a>
                </div>

                <!-- ── Desktop Notification Bell ── -->
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
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ autoTranslate('Notifikasi') }}</h3>
                                    <div class="flex gap-2">
                                        <button @click="markAllAsRead" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">{{ autoTranslate('Tandai dibaca') }}</button>
                                        <button @click="clearAll" class="text-xs text-red-600 dark:text-red-400 hover:underline">{{ autoTranslate('Hapus semua') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <template x-if="notifications.length === 0">
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="text-sm">Belum ada notifikasi</p>
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
                                                    <span x-show="item.priority === 'high'" class="ml-2 px-1.5 py-0.5 text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded">{{ autoTranslate('PENTING') }}</span>
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

    <!-- ══════════════════════════════════════════════════════════
         MOBILE Search Dropdown
    ══════════════════════════════════════════════════════════ -->
    <div id="mobile-search-dropdown" class="lg:hidden bg-white/90 dark:bg-gray-900/90 backdrop-blur-lg border-b border-gray-200/50 dark:border-gray-700/50 hidden">
        <div class="px-4 py-5 space-y-4 sm:px-6">

            <!-- Mobile unified search -->
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
                    placeholder="{{ autoTranslate('Cari postingan, mahasiswa, project...') }}"
                    class="w-full pl-11 pr-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-gray-700 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm bg-white/80 dark:bg-gray-800/80"
                    autocomplete="off"
                >
                <!-- Mobile search badge -->
                <p id="header-post-search-badge-mobile" class="mt-1.5 text-xs text-indigo-600 dark:text-indigo-400 hidden">
                    <span id="header-post-search-count-mobile">0</span> {{ autoTranslate('postingan ditemukan') }}
                </p>
                <div id="search-suggestions-mobile" class="hidden absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl rounded-xl overflow-hidden max-h-72 overflow-y-auto"></div>
            </div>

            <!-- Mobile filter dropdowns -->
            <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2">
                <select id="filter-jurusan-mobile" name="jurusan"
                    class="block w-full border border-gray-300/80 dark:border-gray-700/80 rounded-lg py-2.5 px-3 text-sm bg-white/80 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300">
                    <option value="">{{ autoTranslate('Semua Prodi') }}</option>
                    @foreach($jurusanList ?? [] as $jurusan)
                        <option value="{{ $jurusan->id_jurusan }}" {{ request('jurusan') == $jurusan->id_jurusan ? 'selected' : '' }}>{{ $jurusan->nama_jurusan }}</option>
                    @endforeach
                </select>
                <select id="filter-keahlian-mobile" name="keahlian"
                    class="block w-full border border-gray-300/80 dark:border-gray-700/80 rounded-lg py-2.5 px-3 text-sm bg-white/80 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300">
                    <option value="">{{ autoTranslate('Semua Keahlian') }}</option>
                    @foreach($keahlianList ?? [] as $keahlian)
                        <option value="{{ $keahlian->id_keahlian }}" {{ request('keahlian') == $keahlian->id_keahlian ? 'selected' : '' }}>{{ $keahlian->nama_keahlian }}</option>
                    @endforeach
                </select>
                <select id="filter-angkatan-mobile" name="angkatan"
                    class="block w-full border border-gray-300/80 dark:border-gray-700/80 rounded-lg py-2.5 px-3 text-sm bg-white/80 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300 sm:col-span-2">
                    <option value="">{{ autoTranslate('Semua Angkatan') }}</option>
                    @foreach($angkatanList ?? [] as $angkatan)
                        <option value="{{ $angkatan->id }}" {{ request('angkatan') == $angkatan->id ? 'selected' : '' }}>{{ $angkatan->nama_angkatan }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Mobile action buttons -->
            <div class="flex gap-3">
                <button id="filter-search-btn-mobile" type="button"
                    class="flex-1 bg-indigo-600 text-white py-3 rounded-lg text-sm font-medium hover:bg-indigo-700 transition flex items-center justify-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    {{ autoTranslate('Filter & Cari') }}
                </button>
                <a href="{{ route('search') }}"
                    class="flex-1 bg-white/60 dark:bg-gray-700/60 text-gray-700 dark:text-gray-300 py-3 rounded-lg text-sm font-medium hover:bg-gray-100/80 dark:hover:bg-gray-600 transition flex items-center justify-center border border-gray-300/50 dark:border-gray-600/50">
                    {{ autoTranslate('Reset') }}
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    const currentLocale = document.documentElement.lang || 'id';

    // ══════════════════════════════════════════════
    //  UNIFIED SEARCH — live post filter + global search
    // ══════════════════════════════════════════════
    (function () {
        function isDashboard() {
            return !!document.getElementById('postingan-container');
        }

        // Live post filter logic (only runs on dashboard)
        function performPostSearch(term) {
            if (!isDashboard()) return;

            const allPosts   = document.querySelectorAll('#postingan-container .post-card');
            const pagination = document.getElementById('postingan-pagination');
            const noResultEl = document.getElementById('postingan-no-results');

            const badge      = document.getElementById('header-post-search-badge');
            const countEl    = document.getElementById('header-post-search-count');
            const badgeMob   = document.getElementById('header-post-search-badge-mobile');
            const countMobEl = document.getElementById('header-post-search-count-mobile');
            const clearBtn   = document.getElementById('header-post-search-clear');


            if (term.length < 2) {
                allPosts.forEach(p => { p.style.display = ''; restoreTitle(p); });
                if (pagination) pagination.style.display = '';
                if (badge)    badge.style.display    = 'none';
                if (badgeMob) badgeMob.classList.add('hidden');
                if (clearBtn) clearBtn.style.display = 'none';
                if (noResultEl) noResultEl.remove();
                if (window.performDashboardPostSearch) window.performDashboardPostSearch('', 0); // ← tambah ini sebelum return
                return;
            }

            if (clearBtn) clearBtn.style.display = 'flex';
            if (pagination) pagination.style.display = 'none';

            let visible = 0;
            allPosts.forEach(post => {
                const dataTitle = post.getAttribute('data-post-title') || '';
                const dataDesc  = post.getAttribute('data-post-description') || '';
                const dataAuth  = post.getAttribute('data-post-author') || '';
                const uiTitleEl = post.querySelector('h3');
                const uiTitle   = uiTitleEl ? uiTitleEl.innerText.toLowerCase() : '';
                const lc  = term.toLowerCase();
                const hit = dataTitle.includes(lc) || dataDesc.includes(lc) || dataAuth.includes(lc) || uiTitle.includes(lc);

                if (hit) {
                    post.style.display = '';
                    visible++;
                    highlightTitle(post, term);
                } else {
                    post.style.display = 'none';
                    restoreTitle(post);
                }
            });

            if (window.performDashboardPostSearch) window.performDashboardPostSearch(term, visible);

            let noRes = document.getElementById('postingan-no-results');
            // Kirim ke dashboard info bar
            if (visible === 0) {
                if (!noRes) {
                    noRes = document.createElement('div');
                    noRes.id = 'postingan-no-results';
                    noRes.className = 'text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm mt-4';
                    noRes.innerHTML = `
                        <svg class="w-14 h-14 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada postingan ditemukan</p>
                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Coba kata kunci lain</p>`;
                    const container = document.getElementById('postingan-container');
                    if (container) container.parentNode.insertBefore(noRes, container.nextSibling);
                } else {
                    noRes.style.display = '';
                }
            } else if (noRes) {
                noRes.style.display = 'none';
            }
        }

        function highlightTitle(post, term) {
            const h3 = post.querySelector('h3');
            if (!h3) return;
            if (!h3.dataset.originalText) h3.dataset.originalText = h3.innerText;
            const original = h3.dataset.originalText;
            const regex = new RegExp(`(${term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
            h3.innerHTML = original.replace(regex, '<mark style="background:#fef08a;color:inherit;border-radius:2px;padding:0 1px;" class="search-highlight">$1</mark>');
        }

        function restoreTitle(post) {
            const h3 = post.querySelector('h3');
            if (!h3 || !h3.dataset.originalText) return;
            h3.innerHTML = h3.dataset.originalText;
        }

        // Global search suggestions
        function fetchSuggestions(query, containerEl) {
            if (!containerEl) return;
            if (query.length < 2) { containerEl.classList.add('hidden'); return; }
            fetch(`{{ route('search.suggestions') }}?q=${encodeURIComponent(query)}`)
                .then(r => r.json())
                .then(data => {
                    if (!data.length) {
                        containerEl.innerHTML = `<div class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">Tidak ada hasil</div>`;
                    } else {
                        const grouped = data.reduce((acc, item) => { acc[item.type] = acc[item.type] || []; acc[item.type].push(item); return acc; }, {});
                        const titles  = { mahasiswa: 'Mahasiswa', project: 'Project', sertifikat: 'Sertifikat', postingan: 'Postingan' };
                        let html = '';
                        Object.keys(titles).forEach(type => {
                            const items = grouped[type] || [];
                            if (items.length) {
                                html += `<div class="border-b border-gray-100 dark:border-gray-700">
                                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">${titles[type]}</div>`;
                                items.forEach(item => {
                                    html += `<a href="${item.url}" class="block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 text-sm text-gray-700 dark:text-gray-200">
                                                <div class="font-medium">${item.name}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">${item.label}</div>
                                            </a>`;
                                });
                                html += `</div>`;
                            }
                        });
                        html += `<div class="px-4 py-3 bg-white dark:bg-gray-800">
                                    <a href="{{ route('search') }}?q=${encodeURIComponent(query)}" class="block text-center text-sm text-indigo-600 dark:text-indigo-400 font-medium">Lihat semua hasil</a>
                                 </div>`;
                        containerEl.innerHTML = html;
                    }
                    containerEl.classList.remove('hidden');
                })
                .catch(() => containerEl.classList.add('hidden'));
        }

        

        function initUnifiedSearch() {
            const desktopInput = document.getElementById('unified-search-input');
            const mobileInput  = document.getElementById('unified-search-input-mobile');
            const clearBtn     = document.getElementById('header-post-search-clear');
            const suggDesktop  = document.getElementById('search-suggestions');
            const suggMobile   = document.getElementById('search-suggestions-mobile');

            // Check if any filter is active — show reset button if so
            const resetBtn = document.getElementById('filter-reset-btn');
            function checkFiltersActive() {
                if (!resetBtn) return;
                const j = document.getElementById('filter-jurusan')?.value;
                const k = document.getElementById('filter-keahlian')?.value;
                const a = document.getElementById('filter-angkatan')?.value;
                const q = desktopInput?.value?.trim();
                resetBtn.style.display = (j || k || a || q) ? 'flex' : 'none';
            }

            let postTimer, suggTimer;

            function onInput(e) {
                const term = e.target.value.trim();
                // Sync both inputs
                if (e.target === desktopInput && mobileInput) mobileInput.value = e.target.value;
                if (e.target === mobileInput && desktopInput)  desktopInput.value = e.target.value;

                // Live post filter (dashboard only)
                clearTimeout(postTimer);
                postTimer = setTimeout(() => performPostSearch(term), 280);

                // Suggestions (global search)
                clearTimeout(suggTimer);
                const targetSugg = e.target === desktopInput ? suggDesktop : suggMobile;
                suggTimer = setTimeout(() => fetchSuggestions(term, targetSugg), 250);

                checkFiltersActive();
            }

            if (desktopInput) desktopInput.addEventListener('input', onInput);
            if (mobileInput)  mobileInput.addEventListener('input', onInput);

            if (clearBtn) {
                clearBtn.addEventListener('click', function () {
                    if (desktopInput) desktopInput.value = '';
                    if (mobileInput)  mobileInput.value  = '';
                    performPostSearch('');
                    if (suggDesktop) suggDesktop.classList.add('hidden');
                    if (suggMobile)  suggMobile.classList.add('hidden');
                    checkFiltersActive();
                });
            }

            // Filter dropdowns — also trigger post search sync
            ['filter-jurusan','filter-keahlian','filter-angkatan'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.addEventListener('change', checkFiltersActive);
            });

            // Initial check (if page loaded with active filters from URL)
            checkFiltersActive();

            // Close suggestions on outside click
            document.addEventListener('click', function (e) {
                if (!e.target.closest('#unified-search-input') && !e.target.closest('#search-suggestions') &&
                    !e.target.closest('#unified-search-input-mobile') && !e.target.closest('#search-suggestions-mobile')) {
                    if (suggDesktop) suggDesktop.classList.add('hidden');
                    if (suggMobile)  suggMobile.classList.add('hidden');
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initUnifiedSearch);
        } else {
            initUnifiedSearch();
        }
    })();

    // ══════════════════════════════════════════════
    //  FILTER BUTTON — navigate to search route
    // ══════════════════════════════════════════════
    document.addEventListener('DOMContentLoaded', function () {
        function buildSearchUrl(inputId, jurusanId, keahlianId, angkatanId) {
            const q        = document.getElementById(inputId)?.value?.trim() || '';
            const jurusan  = document.getElementById(jurusanId)?.value  || '';
            const keahlian = document.getElementById(keahlianId)?.value || '';
            const angkatan = document.getElementById(angkatanId)?.value || '';
            const params   = new URLSearchParams();
            if (q)        params.set('q', q);
            if (jurusan)  params.set('jurusan', jurusan);
            if (keahlian) params.set('keahlian', keahlian);
            if (angkatan) params.set('angkatan', angkatan);
            return `{{ route('search') }}${params.toString() ? '?' + params.toString() : ''}`;
        }

        // Desktop filter button
        const filterBtn = document.getElementById('filter-search-btn');
        if (filterBtn) {
            filterBtn.addEventListener('click', function () {
                window.location.href = buildSearchUrl(
                    'unified-search-input',
                    'filter-jurusan',
                    'filter-keahlian',
                    'filter-angkatan'
                );
            });
        }

        // Also allow pressing Enter on the unified search input
        const unifiedInput = document.getElementById('unified-search-input');
        if (unifiedInput) {
            unifiedInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    window.location.href = buildSearchUrl(
                        'unified-search-input',
                        'filter-jurusan',
                        'filter-keahlian',
                        'filter-angkatan'
                    );
                }
            });
        }

        // Mobile filter button
        const filterBtnMobile = document.getElementById('filter-search-btn-mobile');
        if (filterBtnMobile) {
            filterBtnMobile.addEventListener('click', function () {
                window.location.href = buildSearchUrl(
                    'unified-search-input-mobile',
                    'filter-jurusan-mobile',
                    'filter-keahlian-mobile',
                    'filter-angkatan-mobile'
                );
            });
        }

        // Mobile Enter key
        const mobileInput = document.getElementById('unified-search-input-mobile');
        if (mobileInput) {
            mobileInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    window.location.href = buildSearchUrl(
                        'unified-search-input-mobile',
                        'filter-jurusan-mobile',
                        'filter-keahlian-mobile',
                        'filter-angkatan-mobile'
                    );
                }
            });
        }
    });

    // ══════════════════════════════════════════════
    //  NOTIFICATION BELL COMPONENT
    // ══════════════════════════════════════════════
    function notificationBell(data) {
        return {
            userId: data.userId,
            userRole: data.userRole,
            isOpen: false,
            notifications: [],
            unreadCount: 0,
            page: 1,
            pollingInterval: null,
            isLoading: false,

            filterNotifications(notifications) {
                if (!notifications || !Array.isArray(notifications)) return [];
                return notifications.filter(item => {
                    if (item.read === 1 || item.read === true) return false;
                    const notifData = item.data || {};
                    const selected  = notifData.selected_users;
                    if (notifData.target_type === 'specific') {
                        if (!selected) return false;
                        if (Array.isArray(selected)) return selected.map(Number).includes(Number(this.userId));
                        if (typeof selected === 'string' && selected.startsWith('[')) {
                            try { const p = JSON.parse(selected); return Array.isArray(p) ? p.map(Number).includes(Number(this.userId)) : false; } catch(e) {}
                        }
                        return Number(selected) === Number(this.userId);
                    }
                    if (notifData.target_type === 'all') return true;
                    if (notifData.target_role) return notifData.target_role === this.userRole;
                    if (!notifData.admin_id && !notifData.sender_id) return this.userRole === 'admin';
                    return false;
                });
            },

            async init() {
                await this.loadNotifications();
                this.startPolling();
                if (Notification.permission === 'default') Notification.requestPermission();
            },

            getCurrentNotificationIds() { return this.notifications.map(n => n.id); },
            getUnreadNotificationIds()  { return this.notifications.filter(n => !n.read).map(n => n.id); },

            getIconBg(type) {
                const colors = {
                    'user-registered':      'bg-gradient-to-br from-blue-500 to-indigo-600',
                    'project-created':      'bg-gradient-to-br from-green-500 to-emerald-600',
                    'certificate-uploaded': 'bg-gradient-to-br from-purple-500 to-pink-600',
                };
                return colors[type] || 'bg-gradient-to-br from-gray-500 to-gray-600';
            },

            formatTime(timestamp) {
                if (!timestamp) return '';
                const date = new Date(timestamp);
                const now  = new Date();
                const diff = Math.floor((now - date) / 1000);
                if (diff < 60)    return 'Baru saja';
                if (diff < 3600)  return Math.floor(diff / 60) + ' menit lalu';
                if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
                return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            },

            toggleDropdown() {
                this.isOpen = !this.isOpen;
                if (this.isOpen && this.unreadCount > 0) this.loadNotifications();
            },

            startPolling() {
                if (this.pollingInterval) clearInterval(this.pollingInterval);
                this.pollingInterval = setInterval(async () => {
                    try {
                        const res  = await fetch(`/${currentLocale}/api/notifications/unread-count`);
                        const data = await res.json();
                        if (data.count !== this.unreadCount) await this.loadNotifications();
                    } catch (e) { console.error('Polling error:', e); }
                }, 10000);
            },

            async loadNotifications() {
                if (this.isLoading) return;
                this.isLoading = true;
                try {
                    const res  = await fetch(`/${currentLocale}/api/notifications?page=${this.page}`);
                    const data = await res.json();
                    this.notifications = this.filterNotifications(data.data || []);
                    this.updateUnreadCount();
                } catch (e) { console.error('Load notifications error:', e); }
                finally { this.isLoading = false; }
            },

            async handleNotificationClick(item) {
                if (!item.read) await this.markAsRead(item.id);
                const locale = document.documentElement.lang || 'id';
                if (item.data.link) {
                    window.location.href = item.data.link;
                } else {
                    const routes = {
                        'user-registered':      `/${locale}/admin/manageUser`,
                        'project-created':      `/${locale}/admin/manageProject`,
                        'certificate-uploaded': `/${locale}/admin/manageSertifikat`,
                    };
                    window.location.href = routes[item.type] || `/${locale}/dashboard`;
                }
            },

            async markAsRead(id) {
                try {
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                    const res  = await fetch(`/${currentLocale}/api/notifications/mark-read?id=${id}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                        credentials: 'same-origin'
                    });
                    const result = await res.json();
                    if (result.success) await this.loadNotifications();
                } catch(e) { console.error('Mark as read error:', e); }
            },

            async markAllAsRead() {
                const ids = this.getUnreadNotificationIds();
                if (!ids.length) { this.showToast('Tidak ada notifikasi yang belum dibaca', 'info'); return; }
                try {
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                    const res  = await fetch(`/${currentLocale}/api/notifications/mark-all-read`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                        credentials: 'same-origin',
                        body: JSON.stringify({ notification_ids: ids })
                    });
                    const result = await res.json();
                    if (result.success) { await this.loadNotifications(); this.showToast(result.message || 'Semua notifikasi telah ditandai dibaca', 'success'); }
                    else this.showToast('Gagal menandai notifikasi', 'error');
                } catch(e) { this.showToast('Gagal menandai notifikasi. Silakan coba lagi.', 'error'); }
            },

            async clearAll() {
                const ids = this.getCurrentNotificationIds();
                if (!ids.length) { this.showToast('Tidak ada notifikasi yang dapat dihapus', 'info'); return; }
                if (!confirm(`Apakah Anda yakin ingin menghapus ${ids.length} notifikasi?`)) return;
                try {
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                    const res  = await fetch(`/${currentLocale}/api/notifications/clear-all`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                        credentials: 'same-origin',
                        body: JSON.stringify({ notification_ids: ids })
                    });
                    const result = await res.json();
                    if (result.success) { await this.loadNotifications(); this.showToast(result.message || 'Notifikasi telah dihapus', 'success'); }
                    else this.showToast('Gagal menghapus notifikasi', 'error');
                } catch(e) { this.showToast('Gagal menghapus notifikasi. Silakan coba lagi.', 'error'); }
            },

            updateUnreadCount() { this.unreadCount = this.notifications.filter(n => !n.read).length; },

            showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `fixed bottom-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg text-sm ${
                    type === 'success' ? 'bg-green-600' : (type === 'error' ? 'bg-red-600' : 'bg-gray-800')
                } text-white`;
                toast.textContent = message;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }
        };
    }

    // ══════════════════════════════════════════════
    //  PLACEHOLDER TYPING EFFECT
    // ══════════════════════════════════════════════
    (function () {
        function getPlaceholderTexts() {
            const lang  = localStorage.getItem('lang') || 'id';
            const texts = {
                id: ['Cari Mahasiswa...', 'Cari Portofolio...', 'Cari Sertifikat...', 'Cari Postingan...'],
                en: ['Search Students...', 'Search Portfolio...', 'Search Certificate...', 'Search Posts...']
            };
            return texts[lang] || texts['id'];
        }

        const getInputs = () => [
            document.getElementById('unified-search-input'),
            document.getElementById('unified-search-input-mobile')
        ].filter(Boolean);

        let texts = getPlaceholderTexts();
        let textIndex = 0, charIndex = 0, isDeleting = false, speed = 80;

        function typeEffect() {
            const currentText = texts[textIndex];
            getInputs().forEach(input => {
                // Only animate placeholder when input is empty and not focused
                if (!input.value && document.activeElement !== input) {
                    input.setAttribute('placeholder', currentText.substring(0, charIndex));
                }
            });
            if (!isDeleting) {
                charIndex++;
                if (charIndex > currentText.length) { isDeleting = true; setTimeout(typeEffect, 1500); return; }
                speed = 60 + Math.random() * 40;
            } else {
                charIndex--;
                if (charIndex === 0) { isDeleting = false; textIndex = (textIndex + 1) % texts.length; texts = getPlaceholderTexts(); }
                speed = 30 + Math.random() * 30;
            }
            setTimeout(typeEffect, speed);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', typeEffect);
        } else {
            typeEffect();
        }
    })();

    // ══════════════════════════════════════════════
    //  MOBILE SEARCH TOGGLE
    // ══════════════════════════════════════════════
    document.addEventListener('click', function(e) {
            if (!e.target.closest('#search-input') && !e.target.closest('#search-suggestions') && 
                !e.target.closest('#search-input-mobile') && !e.target.closest('#search-suggestions-mobile')) {
                document.querySelectorAll('#search-suggestions, #search-suggestions-mobile').forEach(box => box.classList.add('hidden'));
            }
        });
</script>