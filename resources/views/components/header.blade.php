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
</style>

<header class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 z-50 transition-all">
    <div class="px-4 py-3 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">

            <!-- Mobile: Hamburger + Search Icon (left side) -->
            <div class="flex items-center gap-3 lg:hidden">
                <button id="toggle-sidebar" class="text-gray-700 dark:text-gray-300 focus:outline-none">
                    <svg id="sidebar-hamburger" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="sidebar-close" class="w-7 h-7 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <button id="toggle-search-mobile" class="text-gray-700 dark:text-gray-300 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>

            <!-- MOBILE TITLE -->
            <div class="absolute left-1/2 -translate-x-1/2 lg:hidden">
                <a href="{{ route('dashboard') }}">
                    <h1 class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 tracking-wide">PORTOFOLIO MAHASISWA</h1>
                </a>
            </div>

            <!-- Mobile Notification Bell (right side) -->
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

            <!-- DESKTOP SEARCH -->
            <div id="search-container" class="hidden lg:flex lg:items-center lg:gap-3 w-full max-w-5xl mx-auto">
                <form method="GET" action="{{ route('search') }}" class="flex items-center gap-2.5 w-full">
                    <div class="relative grow min-w-0">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none z-10">
                            <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input id="search-input" type="text" name="q" value="{{ request('q') }}"
                            class="w-full pl-11 pr-4 py-2.5 border border-gray-300/80 dark:border-gray-700/80 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-gray-700 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 text-sm transition shadow-sm bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm"
                            placeholder="Cari..." autocomplete="off">
                        <div id="search-suggestions" class="hidden absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl rounded-xl overflow-hidden z-50 max-h-72 overflow-y-auto"></div>
                    </div>
                    <a href="{{ route('search') }}" class="bg-white/60 dark:bg-gray-700/60 text-gray-700 dark:text-gray-300 px-5 py-2.5 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition text-sm font-medium backdrop-blur-sm border border-gray-300/50 dark:border-gray-600/50">Reset</a>
                    
                    <select name="jurusan" class="border border-gray-200 dark:border-gray-600 bg-white/80 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300 rounded-lg py-2.5 px-3 text-sm backdrop-blur-sm focus:ring-indigo-400 min-w-[140px]">
                        <option value="">Semua Prodi</option>
                        @foreach($jurusanList ?? [] as $jurusan)
                            <option value="{{ $jurusan->id_jurusan }}" {{ request('jurusan') == $jurusan->id_jurusan ? 'selected' : '' }}>{{ Str::limit($jurusan->nama_jurusan, 20) }}</option>
                        @endforeach
                    </select>
                    
                    <select name="keahlian" class="border border-gray-200 dark:border-gray-600 bg-white/80 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300 rounded-lg py-2.5 px-3 text-sm backdrop-blur-sm focus:ring-indigo-400 min-w-[140px]">
                        <option value="">Semua Keahlian</option>
                        @foreach($keahlianList ?? [] as $keahlian)
                            <option value="{{ $keahlian->id_keahlian }}" {{ request('keahlian') == $keahlian->id_keahlian ? 'selected' : '' }}>{{ Str::limit($keahlian->nama_keahlian, 20) }}</option>
                        @endforeach
                    </select>
                    
                    <select name="angkatan" class="border border-gray-200 dark:border-gray-600 bg-white/80 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300 rounded-lg py-2.5 px-3 text-sm backdrop-blur-sm focus:ring-indigo-400 min-w-[120px]">
                        <option value="">Semua Angkatan</option>
                        @foreach($angkatanList ?? [] as $angkatan)
                            <option value="{{ $angkatan->id }}" {{ request('angkatan') == $angkatan->id ? 'selected' : '' }}>{{ $angkatan->nama_angkatan }}</option>
                        @endforeach
                    </select>
                    
                    <button type="submit" class="bg-indigo-600 dark:bg-indigo-500 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 transition text-sm font-medium flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cari
                    </button>
                </form>

                <!-- Desktop Notification Bell -->
                @auth
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'mahasiswa')
                    <div class="ml-3 hidden lg:block" x-data="notificationBell({ userId: {{ Auth::id() }}, userRole: '{{ Auth::user()->role }}' })" x-init="init()">
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

    <!-- MOBILE Search Dropdown -->
    <div id="mobile-search-dropdown" class="lg:hidden bg-white/90 dark:bg-gray-900/90 backdrop-blur-lg border-b border-gray-200/50 dark:border-gray-700/50 overflow-hidden hidden">
        <div class="px-4 py-5 space-y-5 sm:px-6">
            <form method="GET" action="{{ route('search') }}" class="space-y-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input id="search-input-mobile" type="text" name="q" value="{{ request('q') }}" class="w-full pl-11 pr-4 py-3 text-sm border border-gray-300/80 dark:border-gray-700/80 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-gray-700 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm" placeholder="Cari mahasiswa, proyek, portofolio..." autocomplete="off">
                    <div id="search-suggestions-mobile" class="hidden absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl rounded-xl overflow-hidden z-50 max-h-72 overflow-y-auto"></div>
                </div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <select name="jurusan" class="block w-full border border-gray-300/80 dark:border-gray-700/80 rounded-lg py-3 px-3.5 text-sm bg-white/80 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300 backdrop-blur-sm focus:ring-indigo-400">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusanList ?? [] as $jurusan)
                            <option value="{{ $jurusan->id_jurusan }}" {{ request('jurusan') == $jurusan->id_jurusan ? 'selected' : '' }}>{{ $jurusan->nama_jurusan }}</option>
                        @endforeach
                    </select>
                    <select name="keahlian" class="block w-full border border-gray-300/80 dark:border-gray-700/80 rounded-lg py-3 px-3.5 text-sm bg-white/80 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300 backdrop-blur-sm focus:ring-indigo-400">
                        <option value="">Semua Keahlian</option>
                        @foreach($keahlianList ?? [] as $keahlian)
                            <option value="{{ $keahlian->id_keahlian }}" {{ request('keahlian') == $keahlian->id_keahlian ? 'selected' : '' }}>{{ $keahlian->nama_keahlian }}</option>
                        @endforeach
                    </select>
                    <select name="angkatan" class="block w-full border border-gray-300/80 dark:border-gray-700/80 rounded-lg py-3 px-3.5 text-sm bg-white/80 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300 backdrop-blur-sm focus:ring-indigo-400 sm:col-span-2">
                        <option value="">Semua Angkatan</option>
                        @foreach($angkatanList ?? [] as $angkatan)
                            <option value="{{ $angkatan->id }}" {{ request('angkatan') == $angkatan->id ? 'selected' : '' }}>{{ $angkatan->nama_angkatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:gap-4">
                    <button type="submit" class="flex-1 bg-indigo-600 dark:bg-indigo-500 text-white py-3.5 rounded-lg text-sm font-medium hover:bg-indigo-700 dark:hover:bg-indigo-600 transition flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Cari
                    </button>
                    <a href="{{ route('search') }}" class="flex-1 bg-white/60 dark:bg-gray-700/60 text-gray-700 dark:text-gray-300 py-3.5 rounded-lg text-sm font-medium hover:bg-gray-100/80 dark:hover:bg-gray-600 transition flex items-center justify-center border border-gray-300/50 dark:border-gray-600/50">Reset</a>
                </div>
            </form>
        </div>
    </div>
</header>

<script>
    const currentLocale = document.documentElement.lang || 'id';
    // Notification Bell Component
    function notificationBell(data) {
    return {
        userId: data.userId,
        userRole: data.userRole,
        isOpen: false,
        notifications: [],
        unreadCount: 0,
        page: 1,
        pollingInterval: null,

        filterNotifications(notifications) {
            if (!notifications || !Array.isArray(notifications)) return [];
            
            return notifications.filter(item => {
             if (item.read === 1 || item.read === true) {
            return false;
        }
                const notifData = item.data || {};
                const selected = notifData.selected_users;

                // Jika notifikasi khusus user tertentu
                if (notifData.target_type === 'specific') {
                    if (!selected) return false;

                    // array
                    if (Array.isArray(selected)) {
                        return selected.map(Number).includes(Number(this.userId));
                    }

                    // string JSON array "[38,2]"
                    if (typeof selected === 'string' && selected.startsWith('[')) {
                        try {
                            const parsed = JSON.parse(selected);
                            return Array.isArray(parsed)
                                ? parsed.map(Number).includes(Number(this.userId))
                                : false;
                        } catch (e) {}
                    }

                    // string biasa "13"
                    return Number(selected) === Number(this.userId);
                }

                // broadcast semua
                if (notifData.target_type === 'all') {
                    return true;
                }

                // role tertentu
                if (notifData.target_role) {
                    return notifData.target_role === this.userRole;
                }

                // notifikasi admin system
                if (!notifData.admin_id && !notifData.sender_id) {
                    return this.userRole === 'admin';
                }
                return false;
            });
        },
        
        async init() {
            await this.loadNotifications();
            this.startPolling();
            if (Notification.permission === 'default') Notification.requestPermission();
        },

        getCurrentNotificationIds() {
            // Ambil semua ID notifikasi yang tampil di dropdown
            return this.notifications.map(n => n.id);
        },

        getUnreadNotificationIds() {
            // Ambil ID notifikasi yang belum dibaca
            return this.notifications.filter(n => !n.read).map(n => n.id);
        },

        getIconBg(type) {
            const colors = {
                'user-registered': 'bg-gradient-to-br from-blue-500 to-indigo-600',
                'project-created': 'bg-gradient-to-br from-green-500 to-emerald-600',
                'certificate-uploaded': 'bg-gradient-to-br from-purple-500 to-pink-600',
            };
            return colors[type] || 'bg-gradient-to-br from-gray-500 to-gray-600';
        },

        formatTime(timestamp) {
            if (!timestamp) return '';
            const date = new Date(timestamp);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000);
            if (diff < 60) return 'Baru saja';
            if (diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
            if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        },

        toggleDropdown() { 
            this.isOpen = !this.isOpen; 
        },

        startPolling() {
            this.pollingInterval = setInterval(async () => {
                try {
                    const res = await fetch(`/${currentLocale}/api/notifications/unread-count`);
                    const data = await res.json();
                    if (data.count > this.unreadCount) { 
                        this.page = 1; 
                        await this.loadNotifications(); 
                    }
                } catch (e) {
                    console.error('Polling error:', e);
                }
            }, 10000);
        },

        async loadNotifications() {
            try {
                const res = await fetch(`/${currentLocale}/api/notifications?page=${this.page}`);
                const data = await res.json();
                this.notifications = this.filterNotifications(data.data || []);
                this.updateUnreadCount();
            } catch (error) {
                console.error('Load notifications error:', error);
            }
        },

        async handleNotificationClick(item) {
            if (!item.read) await this.markAsRead(item.id);
            
            const locale = document.documentElement.lang || 'id';
            
            // Redirect berdasarkan tipe notifikasi
            if (item.data.link) {
                window.location.href = item.data.link;
            } else {
                // Fallback route berdasarkan tipe
                const routes = {
                    'user-registered': `/${locale}/admin/manageUser`,
                    'project-created': `/${locale}/admin/manageProject`,
                    'certificate-uploaded': `/${locale}/admin/manageSertifikat`,
                };
                window.location.href = routes[item.type] || `/${locale}/dashboard`;
            }
        },

        async markAsRead(id) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const url = `/${currentLocale}/api/notifications/notifications/mark-read?id=${id}`;
                
                const response = await fetch(url, { 
                    method: 'POST', 
                    headers: { 
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin'
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Update local state
                    const notif = this.notifications.find(n => n.id === id);
                    if (notif) {
                        notif.read = true;
                        notif.read_at = new Date().toISOString();
                    }
                    this.updateUnreadCount();
                    
                    console.log('Notification marked as read');
                } else {
                    console.error('Failed to mark as read:', result.message);
                }
            } catch(e) {
                console.error('Mark as read error:', e);
            }
        },

        async markAllAsRead() {
            // Ambil semua ID notifikasi yang belum dibaca
            const unreadIds = this.getUnreadNotificationIds();
            
            if (unreadIds.length === 0) {
                // Tampilkan pesan bahwa tidak ada notifikasi yang perlu ditandai
                const noNotifMsg = document.createElement('div');
                noNotifMsg.className = 'fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg z-50 text-sm';
                noNotifMsg.textContent = 'Tidak ada notifikasi yang belum dibaca';
                document.body.appendChild(noNotifMsg);
                setTimeout(() => noNotifMsg.remove(), 2000);
                return;
            }
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const url = `/${currentLocale}/api/notifications/mark-all-read`;
                
                const response = await fetch(url, { 
                    method: 'POST', 
                    headers: { 
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ notification_ids: unreadIds })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Update semua notifikasi yang belum dibaca menjadi read
                    this.notifications.forEach(n => {
                        if (!n.read) {
                            n.read = true;
                            n.read_at = new Date().toISOString();
                        }
                    });
                    this.updateUnreadCount();
                    
                    // Show success message
                    const successMsg = document.createElement('div');
                    successMsg.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 text-sm';
                    successMsg.textContent = result.message || 'Semua notifikasi telah ditandai dibaca';
                    document.body.appendChild(successMsg);
                    setTimeout(() => successMsg.remove(), 2000);
                    
                    console.log('All notifications marked as read');
                } else {
                    console.error('Failed to mark all as read:', result.message);
                }
            } catch(e) {
                console.error('Mark all as read error:', e);
                alert('Gagal menandai notifikasi. Silakan coba lagi.');
            }
        },

        async clearAll() {
            // Ambil semua ID notifikasi yang tampil
            const allIds = this.getCurrentNotificationIds();
            
            if (allIds.length === 0) {
                const noNotifMsg = document.createElement('div');
                noNotifMsg.className = 'fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg z-50 text-sm';
                noNotifMsg.textContent = 'Tidak ada notifikasi yang dapat dihapus';
                document.body.appendChild(noNotifMsg);
                setTimeout(() => noNotifMsg.remove(), 2000);
                return;
            }
            
            // Konfirmasi sebelum hapus
            const confirmed = confirm(`Apakah Anda yakin ingin menghapus ${allIds.length} notifikasi?`);
            if (!confirmed) return;
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const url = `/${currentLocale}/api/notifications/clear-all`;
                
                const response = await fetch(url, { 
                    method: 'POST', 
                    headers: { 
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ notification_ids: allIds })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Kosongkan array notifikasi
                    this.notifications = [];
                    this.updateUnreadCount();
                    
                    // Tampilkan pesan sukses
                    const successMsg = document.createElement('div');
                    successMsg.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 text-sm';
                    successMsg.textContent = result.message || 'Notifikasi yang dipilih telah dihapus';
                    document.body.appendChild(successMsg);
                    setTimeout(() => successMsg.remove(), 2000);
                    
                    console.log('Selected notifications cleared');
                } else {
                    console.error('Failed to clear notifications:', result.message);
                    alert('Gagal menghapus notifikasi. Silakan coba lagi.');
                }
            } catch(e) {
                console.error('Clear all error:', e);
                alert('Gagal menghapus notifikasi. Silakan coba lagi.');
            }
        },

        updateUnreadCount() { 
            this.unreadCount = this.notifications.filter(n => !n.read).length; 
        }
    };
}

    // Placeholder typing effect
    function getPlaceholderTexts() {
        const lang = localStorage.getItem('lang') || 'id';
        const texts = {
            id: ["Cari Mahasiswa...", "Cari Portofolio...", "Cari Sertifikat..."],
            en: ["Search Students...", "Search Portfolio...", "Search Certificate..."]
        };
        return texts[lang] || texts['id'];
    }

    let texts = getPlaceholderTexts();
    const inputs = document.querySelectorAll('input[name="q"]');
    let textIndex = 0, charIndex = 0, isDeleting = false, speed = 80;

    function typeEffect() {
        const currentText = texts[textIndex];
        inputs.forEach(input => input.setAttribute("placeholder", currentText.substring(0, charIndex)));
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

    document.addEventListener("DOMContentLoaded", () => {
        typeEffect();

        // Mobile search toggle
        const mobileSearchBtn = document.getElementById('toggle-search-mobile');
        const mobileDropdown = document.getElementById('mobile-search-dropdown');
        if(mobileSearchBtn && mobileDropdown) {
            mobileSearchBtn.addEventListener('click', () => {
                mobileDropdown.classList.toggle('hidden');
            });
        }

        // Search suggestions
        const searchInputs = [document.getElementById('search-input'), document.getElementById('search-input-mobile')].filter(Boolean);
        searchInputs.forEach(input => {
            const container = input.id === 'search-input' ? document.getElementById('search-suggestions') : document.getElementById('search-suggestions-mobile');
            let timer;
            input.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(timer);
                if (query.length < 2) { container.classList.add('hidden'); return; }
                timer = setTimeout(() => {
                    fetch(`{{ route('search.suggestions') }}?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            if (!data.length) {
                                container.innerHTML = `<div class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">Tidak ada hasil</div>`;
                            } else {
                                const grouped = data.reduce((acc, item) => { acc[item.type] = acc[item.type] || []; acc[item.type].push(item); return acc; }, {});
                                const titles = { mahasiswa: 'Mahasiswa', project: 'Project', sertifikat: 'Sertifikat', postingan: 'Postingan' };
                                let html = '';
                                Object.keys(titles).forEach(type => {
                                    const items = grouped[type] || [];
                                    if (items.length) {
                                        html += `<div class="border-b border-gray-100 dark:border-gray-700"><div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">${titles[type]}</div>`;
                                        items.forEach(item => html += `<a href="${item.url}" class="block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 text-sm text-gray-700 dark:text-gray-200"><div class="font-medium">${item.name}</div><div class="text-xs text-gray-500 dark:text-gray-400">${item.label}</div></a>`);
                                        html += `</div>`;
                                    }
                                });
                                html += `<div class="px-4 py-3 bg-white dark:bg-gray-800"><a href="{{ route('search') }}?q=${encodeURIComponent(query)}" class="block text-center text-sm text-indigo-600 dark:text-indigo-400 font-medium">Lihat semua hasil</a></div>`;
                                container.innerHTML = html;
                            }
                            container.classList.remove('hidden');
                        })
                        .catch(() => container.classList.add('hidden'));
                }, 250);
            });
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#search-input') && !e.target.closest('#search-suggestions') && 
                !e.target.closest('#search-input-mobile') && !e.target.closest('#search-suggestions-mobile')) {
                document.querySelectorAll('#search-suggestions, #search-suggestions-mobile').forEach(box => box.classList.add('hidden'));
            }
        });
    });
</script>