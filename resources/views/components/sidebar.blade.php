
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-70 bg-gray-100 dark:bg-gray-900 dark:border-gray-800 border-r border-gray-200 shadow-xl
             transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out
             lg:static lg:inset-auto lg:shadow-sm
             flex flex-col overflow-hidden
             {{ session('sidebar_collapsed', false) ? 'lg:w-20' : 'lg:w-62' }}">
    <!-- Logo -->
    <div class="px-6 py-6 mt-10 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between shrink-0">
        <div class="flex-1 flex justify-center lg:justify-center">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center shadow-md overflow-hidden">
                <img id="logo-zoom" class="cursor-pointer w-20 h-20 rounded-full object-cover transition-all duration-300"
                    src="{{ asset('assets/Logo.svg') }}" alt="Logo">
            </div>
        </div>
    </div>

   
    <!-- Search Menu -->
    <div class="px-4 py-4 border-b border-gray-200 dark:border-gray-800">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 01-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" id="sidebarSearch" placeholder="{{ __('menu.search_placeholder') }}"
                data-translate-placeholder="search_placeholder"
                class="w-full bg-white dark:bg-gray-800 border dark:text-white text-black border-gray-300 dark:border-gray-700 pl-10 pr-4 py-3 rounded-2xl text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all"
                autocomplete="off">

            <!-- Search Results -->
            <div id="searchResults"
                class="hidden absolute mt-2 w-full bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 py-2 max-h-[340px] overflow-y-auto z-50">
                <!-- Results filled by JavaScript -->
            </div>
        </div>
    </div>

    <nav class="flex-1 px-2 py-6 space-y-2 overflow-y-auto overflow-x-hidden">
        
        <!-- Home - Semua user -->
        <a href="{{ route('dashboard', ['locale' => app()->getLocale()]) }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
           {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10.5L12 3l9 7.5M5 10v9a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1v-9" />
            </svg>
            <span class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"
                data-translate="dashboard_nonuser">Dashboard</span>
            @if(session('sidebar_collapsed', false))
                <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                    data-translate="dashboard_nonuser">Dashboard</span>
            @endif
        </a>

        <!-- My Dashboard - Hanya mahasiswa biasa -->
        @auth
            @if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'dosen')
                <a href="{{ route('dashboard.me', ['locale' => app()->getLocale()]) }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                           {{ request()->routeIs('dashboard.me') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                    <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 0l7-4m-7 4L9 5m3 0l7 4" />
                    </svg>
                    <span class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"
                        data-translate="my_dashboard">My Dashboard</span>
                    @if(session('sidebar_collapsed', false))
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                            data-translate="my_dashboard">My Dashboard</span>
                    @endif
                </a>
            @endif
        @endauth

        <!-- ================== MENU ADMIN ================== -->
        @auth
            @if(Auth::user()->role === 'admin')
                <!-- Admin Dashboard -->
                <a href="{{ route('admin.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                           {{ request()->routeIs('admin.index') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                    <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 0l7-4m-7 4L9 5m3 0l7 4" />
                    </svg>
                    <span class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"
                        data-translate="admin_dashboard">Admin Dashboard</span>
                    @if(session('sidebar_collapsed', false))
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                            data-translate="admin_dashboard">Admin Dashboard</span>
                    @endif
                </a>

                <!-- Manajemen Users Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group
                               {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span data-translate="manajemen_users" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">Manajemen Users</span>
                        </div>
                        <svg x-show="!{{ session('sidebar_collapsed') ? 'true' : 'false' }}" :class="open ? 'rotate-180' : ''"
                            class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    @if(session('sidebar_collapsed'))
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                            data-translate="manajemen_users">Manajemen Users</span>
                    @endif
                    <div x-show="open && (!{{ session('sidebar_collapsed') ? 'true' : 'false' }} || window.innerWidth < 1024)"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        class="mt-2 space-y-1 pl-4 {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center space-x-2 py-2 pl-9 pr-3 text-sm rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.users.index') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <span data-translate="semua_user">Lihat User</span>
                        </a>
                        <a href="{{ route('admin.users.ViewCreate') }}"
                            class="flex items-center space-x-2 py-2 pl-9 pr-3 text-sm rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.users.create') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                </path>
                            </svg>
                            <span data-translate="tambah_user">Tambah User</span>
                        </a>
                    </div>
                </div>

                <!-- Manajemen Angkatan Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.angkatan.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group
                               {{ request()->routeIs('admin.angkatan.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span data-translate="manajemen_angkatan" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">Manajemen Angkatan</span>
                        </div>
                        <svg x-show="!{{ session('sidebar_collapsed') ? 'true' : 'false' }}" :class="open ? 'rotate-180' : ''"
                            class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    @if(session('sidebar_collapsed'))
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                            data-translate="manajemen_angkatan">Manajemen Angkatan</span>
                    @endif
                    <div x-show="open && (!{{ session('sidebar_collapsed') ? 'true' : 'false' }} || window.innerWidth < 1024)"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        class="mt-2 space-y-1 pl-4 {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">
                        <a href="{{ route('admin.angkatan.index') }}"
                            class="flex items-center space-x-2 py-2 pl-9 pr-3 text-sm rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.angkatan.index') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <span data-translate="lihat_angkatan">Lihat Angkatan</span>
                        </a>
                        <a href="{{ route('admin.angkatan.create') }}"
                            class="flex items-center space-x-2 py-2 pl-9 pr-3 text-sm rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.angkatan.create') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                </path>
                            </svg>
                            <span data-translate="tambah_angkatan" data-translate-page="admin">Tambah Angkatan</span>
                        </a>
                    </div>
                </div>

                <!-- Manage Prodi Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.prodi.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group
                               {{ request()->routeIs('admin.prodi.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                                </path>
                            </svg>
                            <span data-translate="manajemen_prodi" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">Manajemen Prodi</span>
                        </div>
                        <svg x-show="!{{ session('sidebar_collapsed') ? 'true' : 'false' }}" :class="open ? 'rotate-180' : ''"
                            class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    @if(session('sidebar_collapsed'))
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                            data-translate="manajemen_prodi">Manajemen Prodi</span>
                    @endif
                    <div x-show="open && (!{{ session('sidebar_collapsed') ? 'true' : 'false' }} || window.innerWidth < 1024)"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        class="mt-2 space-y-1 pl-4 {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">
                        <a href="{{ route('admin.prodi.index') }}"
                            class="flex items-center space-x-2 py-2 pl-9 pr-3 text-sm rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.prodi.index') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <span data-translate="lihat_prodi">Lihat Prodi</span>
                        </a>
                        <a href="{{ route('admin.prodi.create') }}"
                            class="flex items-center space-x-2 py-2 pl-9 pr-3 text-sm rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.prodi.create') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                </path>
                            </svg>
                            <span data-translate="tambah_prodi">Tambah Prodi</span>
                        </a>
                    </div>
                </div>

                <!-- Manajemen Keahlian Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.keahlian.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group
                               {{ request()->routeIs('admin.keahlian.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                            <span data-translate="manajemen_keahlian" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">Manajemen Keahlian</span>
                        </div>
                        <svg x-show="!{{ session('sidebar_collapsed') ? 'true' : 'false' }}" :class="open ? 'rotate-180' : ''"
                            class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    @if(session('sidebar_collapsed'))
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                            data-translate="manajemen_keahlian">Manajemen Keahlian</span>
                    @endif
                    <div x-show="open && (!{{ session('sidebar_collapsed') ? 'true' : 'false' }} || window.innerWidth < 1024)"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        class="mt-2 space-y-1 pl-4 {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">
                        <a href="{{ route('admin.keahlian.index') }}"
                            class="flex items-center space-x-2 py-2 pl-9 pr-3 text-sm rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.keahlian.index') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <span data-translate="lihat_keahlian">Lihat Keahlian</span>
                        </a>
                        <a href="{{ route('admin.keahlian.create') }}"
                            class="flex items-center space-x-2 py-2 pl-9 pr-3 text-sm rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.keahlian.create') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                </path>
                            </svg>
                            <span data-translate="tambah_keahlian">Tambah Keahlian</span>
                        </a>
                    </div>
                </div>

                <!-- Manajemen Project -->
                <div x-data="{ open: {{ request()->routeIs('admin.projects.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group
                               {{ request()->routeIs('admin.projects.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-1a4 4 0 00-5-3.87M9 20H4v-1a4 4 0 015-3.87m8-6a4 4 0 11-8 0 4 4 0 018 0zM5 8a3 3 0 106 0 3 3 0 00-6 0z" />
                            </svg>
                            <span data-translate="manajemen_projects" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">Manajemen Project</span>
                        </div>
                        <svg x-show="!{{ session('sidebar_collapsed') ? 'true' : 'false' }}" :class="open ? 'rotate-180' : ''"
                            class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    @if(session('sidebar_collapsed'))
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                            data-translate="manajemen_projects">Manajemen Project</span>
                    @endif
                    <div x-show="open && (!{{ session('sidebar_collapsed') ? 'true' : 'false' }} || window.innerWidth < 1024)"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        class="mt-2 space-y-1 pl-4 {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">
                        <a href="{{ route('admin.projects.index') }}"
                            class="flex items-center space-x-2 py-2 pl-9 pr-3 text-sm rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.projects.index') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <span data-translate="lihat_proyek">Lihat Projek</span>
                        </a>
                        <a href="{{ route('admin.projects.create') }}"
                            class="flex items-center space-x-2 py-2 pl-9 pr-3 text-sm rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.projects.create') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span data-translate="tambah_proyek">Tambah Projek</span>
                        </a>
                    </div>
                </div>

                <!-- Manajemen Sertifikat Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.sertifikat.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group
                               {{ request()->routeIs('admin.sertifikat.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 min-w-[20px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
                                <path d="M8 2v4"></path>
                                <path d="M16 2v4"></path>
                                <path d="M3 10h18"></path>
                                <circle cx="12" cy="14" r="2"></circle>
                            </svg>
                            <span data-translate="manajemen_sertifikat" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">Manajemen Sertifikat</span>
                        </div>
                        <svg x-show="!{{ session('sidebar_collapsed') ? 'true' : 'false' }}" :class="open ? 'rotate-180' : ''"
                            class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    @if(session('sidebar_collapsed'))
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                            data-translate="manajemen_sertifikat">Manajemen Sertifikat</span>
                    @endif
                    <div x-show="open && (!{{ session('sidebar_collapsed') ? 'true' : 'false' }} || window.innerWidth < 1024)"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        class="mt-2 space-y-1 pl-4 {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">
                        <a href="{{ route('admin.sertifikat.index') }}"
                            class="flex items-center space-x-2 py-2 pl-9 pr-3 text-sm rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.sertifikat.index') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <span data-translate="lihat_sertifikat">Lihat Sertifikat</span>
                        </a>
                        <a href="{{ route('admin.sertifikat.create') }}"
                            class="flex items-center space-x-2 py-2 pl-9 pr-3 text-sm rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.sertifikat.create') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span data-translate="tambah_sertifikat">Tambah Sertifikat</span>
                        </a>
                    </div>
                </div>

                 <div x-data="{ open: {{ request()->routeIs('admin.notifications.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group
                               {{ request()->routeIs('admin.notifications.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg> 
                            <span data-translate="manajemen_notifikasi" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">Manajemen Notifikasi</span>
                        </div>
                        <svg x-show="!{{ session('sidebar_collapsed') ? 'true' : 'false' }}" :class="open ? 'rotate-180' : ''"
                            class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    @if(session('sidebar_collapsed'))
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                            data-translate="manajemen_notifikasi">Manajemen Notifikasi</span>
                    @endif
                    <div x-show="open && (!{{ session('sidebar_collapsed') ? 'true' : 'false' }} || window.innerWidth < 1024)"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        class="mt-2 space-y-1 pl-4 {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">
                        <a href="{{ route('admin.notifications.index') }}"
                            class="flex items-center space-x-2 py-2 pl-9 pr-3 text-sm rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.notifications.index') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <span data-translate="lihat_notifikasi">Lihat Notifikasi</span>
                        </a>
                        <a href="{{ route('admin.notifications.create') }}"
                            class="flex items-center space-x-2 py-2 pl-9 pr-3 text-sm rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('admin.prodi.create') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                </path>
                            </svg>
                            <span data-translate="tambah_notifikasi">Tambah Notifikasi</span>
                        </a>
                    </div>
                </div>

            @endif
        @endauth

        <!-- ================== MENU DOSEN ================== -->
        @auth
            @if(Auth::user()->role === 'dosen')
                <a href="{{ route('dosen.dashboard') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                           {{ request()->routeIs('dosen.dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                    <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z M8 8h8M8 12h8M8 16h4" />
                    </svg>
                    <span class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"
                        data-translate="dashboard_dosen">Dashboard Dosen</span>
                    @if(session('sidebar_collapsed', false))
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                            data-translate="dashboard_dosen">Dashboard Dosen</span>
                    @endif
                </a>

                <!-- Mahasiswa Bimbingan Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('dosen.users.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group
                               {{ request()->routeIs('dosen.users.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z" />
                            </svg>
                            <span data-translate="mhs_bbg" data-translate-page="dosen_sidebar" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Mahasiswa Bimbingan</span>
                        </div>
                        <svg x-show="!{{ session('sidebar_collapsed') ? 'true' : 'false' }}" :class="{ 'rotate-180': open }"
                            class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    @if(session('sidebar_collapsed'))
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                            data-translate="mhs_bbg" data-translate-page="dosen_sidebar">Mahasiswa Bimbingan</span>
                    @endif
                    <div x-show="open && (!{{ session('sidebar_collapsed') ? 'true' : 'false' }} || window.innerWidth < 1024)"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="pl-5 space-y-1 mt-1 {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">
                        <a href="{{ route('dosen.users.index') }}"
                            class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm
                                   {{ request()->routeIs('dosen.users.index') ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <span data-translate="see_mhs" data-translate-page="dosen_sidebar">Lihat Mahasiswa</span>
                        </a>
                        <a href="{{ route('dosen.users.ViewCreate') }}"
                            class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm
                                   {{ request()->routeIs('dosen.users.ViewCreate') ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                </path>
                            </svg>
                            <span data-translate="add_mhs" data-translate-page="dosen_sidebar">Tambah Mahasiswa</span>
                        </a>
                    </div>
                </div>

                <!-- Projects Bimbingan Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('dosen.projects.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group
                               {{ request()->routeIs('dosen.projects.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z" />
                            </svg>
                            <span data-translate="pjt_bbbg" data-translate-page="dosen_sidebar" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Projects Bimbingan</span>
                        </div>
                        <svg x-show="!{{ session('sidebar_collapsed') ? 'true' : 'false' }}" :class="{ 'rotate-180': open }"
                            class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    @if(session('sidebar_collapsed'))
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                            data-translate="pjt_bbbg" data-translate-page="dosen_sidebar">Projects Bimbingan</span>
                    @endif
                    <div x-show="open && (!{{ session('sidebar_collapsed') ? 'true' : 'false' }} || window.innerWidth < 1024)"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="pl-5 space-y-1 mt-1 {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">
                        <a href="{{ route('dosen.projects.index') }}"
                            class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm
                                   {{ request()->routeIs('dosen.projects.index') ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <span data-translate="see_pjt" data-translate-page="dosen_sidebar">Lihat Project</span>
                        </a>
                        <a href="{{ route('dosen.projects.create') }}"
                            class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm
                                   {{ request()->routeIs('dosen.projects.create') ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span data-translate="add_pjt" data-translate-page="dosen_sidebar">Tambah Project</span>
                        </a>
                    </div>
                </div>

                <!-- Sertifikat Bimbingan Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('dosen.sertifikat.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group
                               {{ request()->routeIs('dosen.sertifikat.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 min-w-[20px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
                                <path d="M8 2v4"></path>
                                <path d="M16 2v4"></path>
                                <path d="M3 10h18"></path>
                                <circle cx="12" cy="14" r="2"></circle>
                            </svg>
                            <span data-translate="stk_bbg" data-translate-page="dosen_sidebar" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Sertifikat Bimbingan</span>
                        </div>
                        <svg x-show="!{{ session('sidebar_collapsed') ? 'true' : 'false' }}" :class="{ 'rotate-180': open }"
                            class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    @if(session('sidebar_collapsed'))
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                            data-translate="stk_bbg" data-translate-page="dosen_sidebar">Sertifikat Bimbingan</span>
                    @endif
                    <div x-show="open && (!{{ session('sidebar_collapsed') ? 'true' : 'false' }} || window.innerWidth < 1024)"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="pl-5 space-y-1 mt-1 {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">
                        <a href="{{ route('dosen.sertifikat.index') }}"
                            class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm
                                   {{ request()->routeIs('dosen.sertifikat.index') ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <span data-translate="see_stk" data-translate-page="dosen_sidebar">Lihat Sertifikat</span>
                        </a>
                        <a href="{{ route('dosen.sertifikat.create') }}"
                            class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm
                                   {{ request()->routeIs('dosen.sertifikat.create') ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span data-translate="add_stk" data-translate-page="dosen_sidebar">Tambah Sertifikat</span>
                        </a>
                    </div>
                </div>
            @endif
        @endauth

        <!-- ================== MENU PROJECT MAHASISWA ================== -->
        @guest
            <a href="{{ route('project.project_user') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                       {{ request()->routeIs('project.project_user') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"
                    data-translate="project_mahasiswa">Project Mahasiswa</span>
                @if(session('sidebar_collapsed', false))
                    <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                        data-translate="project_mahasiswa">Project Mahasiswa</span>
                @endif
            </a>
        @else
            @if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'dosen')
                <div x-data="{ open: {{ request()->routeIs('project.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group
                               {{ request()->routeIs('project.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span data-translate="project_mahasiswa" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Project</span>
                        </div>
                        <svg x-show="!{{ session('sidebar_collapsed') ? 'true' : 'false' }}" :class="{ 'rotate-180': open }"
                            class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open && (!{{ session('sidebar_collapsed') ? 'true' : 'false' }} || window.innerWidth < 1024)"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="pl-5 space-y-1 mt-1 {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">
                        <a href="{{ route('project.index') }}"
                            class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm
                                   {{ request()->routeIs('project.index') ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            <span data-translate="project_mahasiswa_saya">Project Saya</span>
                        </a>
                        <a href="{{ route('project.create') }}"
                            class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm
                                   {{ request()->routeIs('project.create') ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span data-translate="project_mahasiswa_saya_tambah">Tambah Project Baru</span>
                        </a>
                    </div>
                </div>
            @endif
        @endguest

        <!-- ================== MENU SERTIFIKAT MAHASISWA ================== -->
        @auth
            @if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'dosen')
                <div x-data="{ open: {{ request()->routeIs('sertifikat.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group
                               {{ request()->routeIs('sertifikat.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 min-w-[20px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
                                <path d="M8 2v4"></path>
                                <path d="M16 2v4"></path>
                                <path d="M3 10h18"></path>
                                <circle cx="12" cy="14" r="2"></circle>
                            </svg>
                            <span data-translate="sertifikat_mahasiswa" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Sertifikat</span>
                        </div>
                        <svg x-show="!{{ session('sidebar_collapsed') ? 'true' : 'false' }}" :class="{ 'rotate-180': open }"
                            class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open && (!{{ session('sidebar_collapsed') ? 'true' : 'false' }} || window.innerWidth < 1024)"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="pl-5 space-y-1 mt-1 {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">
                        <a href="{{ route('sertifikat.index') }}"
                            class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm
                                   {{ request()->routeIs('sertifikat.index') ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            <span data-translate="sertifikat_mahasiswa_saya">Sertifikat Saya</span>
                        </a>
                        <a href="{{ route('sertifikat.create') }}"
                            class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm
                                   {{ request()->routeIs('sertifikat.create') ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span data-translate="sertifikat_mahasiswa_saya_tambah">Tambah Sertifikat</span>
                        </a>
                    </div>
                </div>

                
            @endif
        @endauth

        @auth 
         @if(Auth::user())
          <!-- Postingan -->
                <div x-data="{ open: {{ request()->routeIs('postingan.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group
                               {{ request()->routeIs('postingan.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 min-w-[20px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
                                <path d="M8 2v4"></path>
                                <path d="M16 2v4"></path>
                                <path d="M3 10h18"></path>
                                <circle cx="12" cy="14" r="2"></circle>
                            </svg>
                            <span data-translate="postingan" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Postingan</span>
                        </div>
                        <svg x-show="!{{ session('sidebar_collapsed') ? 'true' : 'false' }}" :class="{ 'rotate-180': open }"
                            class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open && (!{{ session('sidebar_collapsed') ? 'true' : 'false' }} || window.innerWidth < 1024)"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="pl-5 space-y-1 mt-1 {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}">
                        <a href="{{ route('postingan.index') }}"
                            class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm
                                   {{ request()->routeIs('postingan.index') ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            <span data-translate="postingan">Postingan</span>
                        </a>
                        <a href="{{ route('postingan.create') }}"
                            class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm
                                   {{ request()->routeIs('postingan.create') ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span data-translate="postingan_tambah">Tambah Postingan</span>
                        </a>
                    </div>
                </div>
            @endif
        @endauth

    </nav>

    <!-- Setting Dropdown -->
    <div class="group relative mt-auto mb-4 px-2">
        <button onclick="toggleDropdown('setting')"
            class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group relative text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.983 5.5c-.47 0-.93.05-1.372.146l-.388-1.648a.5.5 0 00-.487-.398h-1.472a.5.5 0 00-.487.398l-.388 1.648a6.987 6.987 0 00-1.186.688L4.69 5.5a.5.5 0 00-.607.06L3.04 6.603a.5.5 0 00-.06.607l.834 1.186a6.987 6.987 0 00-.688 1.186l-1.648.388a.5.5 0 00-.398.487v1.472c0 .232.158.433.388.487l1.648.388c.162.42.393.816.688 1.186l-.834 1.186a.5.5 0 00.06.607l1.043 1.043a.5.5 0 00.607.06l1.186-.834c.37.295.766.526 1.186.688l.388 1.648a.5.5 0 00.487.398h1.472a.5.5 0 00.487-.398l.388-1.648a6.987 6.987 0 001.186-.688l1.186.834a.5.5 0 00.607-.06l1.043-1.043a.5.5 0 00.06-.607l-.834-1.186c.295-.37.526-.766.688-1.186l1.648-.388a.5.5 0 00.398-.487v-1.472a.5.5 0 00-.398-.487l-1.648-.388a6.987 6.987 0 00-.688-1.186l.834-1.186a.5.5 0 00-.06-.607L19.277 5.56a.5.5 0 00-.607-.06l-1.186.834a6.987 6.987 0 00-1.186-.688l-.388-1.648a.5.5 0 00-.487-.398h-1.472a.5.5 0 00-.487.398l-.388 1.648A7.02 7.02 0 0011.983 5.5zM12 15a3 3 0 100-6 3 3 0 000 6z" />
                </svg>
                <span class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"
                    data-translate="setting">Setting</span>
            </div>
            <svg id="settingArrow" class="w-4 h-4 transition-transform {{ session('sidebar_collapsed') ? 'lg:hidden' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
            @if(session('sidebar_collapsed', false))
                <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                    data-translate="setting">Setting</span>
            @endif
        </button>
        <div id="settingMenu" class="hidden bg-white dark:bg-gray-800 rounded-xl shadow-md mt-2 p-4 border border-gray-200 dark:border-gray-700 absolute left-0 right-0 w-full lg:w-60 z-50">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-700 dark:text-gray-200" data-translate="mode">Mode</span>
                <button id="darkModeBtn" class="px-3 py-1 rounded-lg text-xs font-semibold bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200" onclick="toggleDarkMode()">Dark Mode</button>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-700 dark:text-gray-200" data-translate="bahasa">Bahasa</span>
                <select id="languageSelect" class="bg-gray-200 dark:bg-gray-700 px-3 py-1 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-200" onchange="changeLanguage()">
                    <option value="id" {{ app()->getLocale() === 'id' ? 'selected' : '' }}>Indonesia</option>
                    <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Profile Dropdown -->
    @auth
    <div class="px-2 py-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 shrink-0">
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false"
                class="w-full flex items-center space-x-3 rounded-lg transition p-2 -mx-2 hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white dark:border-gray-900 shadow-sm shrink-0">
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'dosen')
                        @if(Auth::user()->photo_profile)
                            <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                            </div>
                        @endif
                    @else
                        @if(Auth::user()->photo_profile)
                            <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}" alt="{{ Auth::user()->nama_mahasiswa ?? Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr(Auth::user()->nama_mahasiswa ?? Auth::user()->name ?? 'U', 0, 1) }}
                            </div>
                        @endif
                    @endif
                </div>

                <div class="flex-1 min-w-0 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }} text-left">
                    @if(Auth::user()->role === 'admin')
                        <p class="text-gray-900 dark:text-gray-100 text-sm font-medium truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        <span class="inline-block mt-0.5 px-1.5 py-0.5 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-[10px] font-semibold">Admin</span>
                    @elseif(Auth::user()->role === 'dosen')
                        <p class="text-gray-900 dark:text-gray-100 text-sm font-medium truncate">{{ Auth::user()->name ?? 'Dosen' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        <span class="inline-block mt-0.5 px-1.5 py-0.5 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-[10px] font-semibold">Dosen</span>
                    @else
                        <p class="text-gray-900 dark:text-gray-100 text-sm font-medium truncate">{{ Auth::user()->nama_mahasiswa ?? Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                    @endif
                </div>
                <svg x-show="!{{ session('sidebar_collapsed', false) ? 'true' : 'false' }}" :class="{ 'rotate-180': open }"
                    class="w-4 h-4 text-gray-500 transition-transform duration-200 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
           
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="absolute bottom-full left-0 right-0 mb-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
                @if (Auth::check() && Auth::user()->role == 'mahasiswa')
                 <a href="{{ route('profile') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-sm text-gray-700 dark:text-gray-200" data-translate="lihat_profil">Lihat Profil</span>
                </a>
                @endif

                @if (Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->role == 'dosen'))
                <div x-data="{ showPhotoModal: false }" class="w-full">

                    <!-- Button -->
                    <button type="button"
                        @click.stop="showPhotoModal = true"
                        class="w-full flex items-center space-x-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">

                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 12v7m0-7l-3 3m3-3l3 3M12 4a4 4 0 110 8 4 4 0 010-8z"/>
                        </svg>

                        <span class="text-sm text-gray-700 dark:text-gray-200">
                    {{ autoTranslate('Foto Profil') }}
                        </span>
                    </button>

                    <!-- Modal -->
                    <div x-cloak
                        x-show="showPhotoModal"
                        x-transition.opacity
                        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/60 px-4">

                        <div @click.stop
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md overflow-hidden">

                            <!-- Header -->
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                   {{ autoTranslate('Ubah')}}
                                </h2>

                                <button type="button"
                                    @click="showPhotoModal = false"
                                    class="text-gray-400 hover:text-red-500 text-xl">
                                    ✕
                                </button>
                            </div>

                            <!-- Form -->
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <div class="p-6">

                                    <!-- Preview -->
                                    <div class="flex justify-center mb-5">
                                        <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-gray-200 dark:border-gray-700">

                                            @if(Auth::user()->photo_profile)
                                                <img id="preview-photo"
                                                    src="{{ asset('storage/' . Auth::user()->photo_profile) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <img id="preview-photo"
                                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}"
                                                    class="w-full h-full object-cover">
                                            @endif

                                        </div>
                                    </div>

                                    <!-- Upload -->
                                    <label class="block cursor-pointer">
                                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-4 text-center hover:border-indigo-500 transition">
                                            <p class="text-sm text-gray-700 dark:text-gray-200">
                                            {{ autoTranslate('PILIH GAMBAR') }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                JPG, PNG, JPEG
                                            </p>
                                        </div>

                                        <input type="file"
                                            name="photo_profile"
                                            accept="image/*"
                                            class="hidden"
                                            onchange="previewPhoto(event)">
                                    </label>

                                </div>

                                <!-- Footer -->
                                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 flex justify-end gap-2">

                                    <button type="button"
                                        @click="showPhotoModal = false"
                                        class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-white">
                                        {{ autoTranslate('Batal') }}
                                    </button>

                                    <button type="submit"
                                        class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">
                                        {{ autoTranslate('Simpan') }}
                                    </button>

                                </div>
                            </form>

                        </div>

                        <!-- Klik background close -->
                        <div class="absolute inset-0 -z-10" @click="showPhotoModal = false"></div>
                    </div>
                </div>

                <style>
                [x-cloak]{
                    display:none !important;
                }
                </style>

                <script>
                function previewPhoto(event){
                    const file = event.target.files[0];
                    if(!file) return;

                    const reader = new FileReader();

                    reader.onload = function(e){
                        document.getElementById('preview-photo').src = e.target.result;
                    }

                    reader.readAsDataURL(file);
                }
                </script>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-sm text-red-600 dark:text-red-400" data-translate="logout">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @else
    <!-- Guest Section -->
    <div class="px-2 py-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 shrink-0">
        <div class="text-center text-sm text-gray-500 dark:text-gray-400 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"
            data-translate="login_prompt">Silakan login untuk mengakses fitur</div>
        <div class="mt-3 flex {{ session('sidebar_collapsed', false) ? 'lg:flex-col lg:space-y-2' : 'space-x-2' }}">
            <a href="{{ route('login') }}" class="flex-1 px-4 py-2.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition font-medium text-sm text-center group relative">
                <span class="{{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}" data-translate="login">Masuk</span>
                @if(session('sidebar_collapsed', false))
                    <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block"
                        data-translate="login">Masuk</span>
                @endif
            </a>
        </div>
    </div>
    @endauth
</aside>

<!-- JavaScript untuk Search Menu + Existing Scripts -->
<script>
  // ================== SEARCH MENU FUNCTIONALITY WITH AUTO TRANSLATE ==================
const searchInput = document.getElementById('sidebarSearch');
const searchResults = document.getElementById('searchResults');

// Daftar semua menu yang bisa dicari
const allMenus = [
    // === MENU UMUM ===
    { name: "{{ autoTranslate('Dashboard') }}", url: "{{ route('dashboard', ['locale' => app()->getLocale()]) }}", keyword: "dashboard home beranda utama", role: "all" },
    
    // === MENU MAHASISWA ===
    { name: "{{ autoTranslate('My Dashboard') }}", url: "{{ route('dashboard.me', ['locale' => app()->getLocale()]) }}", keyword: "my dashboard mahasiswa pribadi", role: "mahasiswa" },
    { name: "{{ autoTranslate('Project Saya') }}", url: "{{ route('project.index', ['locale' => app()->getLocale()]) }}", keyword: "project saya proyek mahasiswa", role: "mahasiswa" },
    { name: "{{ autoTranslate('Tambah Project') }}", url: "{{ route('project.create', ['locale' => app()->getLocale()]) }}", keyword: "tambah project baru proyek", role: "mahasiswa" },
    { name: "{{ autoTranslate('Sertifikat Saya') }}", url: "{{ route('sertifikat.index', ['locale' => app()->getLocale()]) }}", keyword: "sertifikat saya mahasiswa", role: "mahasiswa" },
    { name: "{{ autoTranslate('Tambah Sertifikat') }}", url: "{{ route('sertifikat.create', ['locale' => app()->getLocale()]) }}", keyword: "tambah sertifikat baru", role: "mahasiswa" },
    { name: "{{ autoTranslate('Postingan') }}", url: "{{ route('postingan.index', ['locale' => app()->getLocale()]) }}", keyword: "postingan post feed berita", role: "all_auth" },
    { name: "{{ autoTranslate('Tambah Postingan') }}", url: "{{ route('postingan.create', ['locale' => app()->getLocale()]) }}", keyword: "tambah postingan baru post", role: "all_auth" },
    { name: "{{ autoTranslate('Project Mahasiswa') }}", url: "{{ route('project.project_user', ['locale' => app()->getLocale()]) }}", keyword: "project mahasiswa guest publik", role: "guest" },
    
    // === MENU ADMIN ===
    { name: "{{ autoTranslate('Admin Dashboard') }}", url: "{{ route('admin.index', ['locale' => app()->getLocale()]) }}", keyword: "admin dashboard administrator", role: "admin" },
    { name: "{{ autoTranslate('Manajemen Users') }}", url: "{{ route('admin.users.index', ['locale' => app()->getLocale()]) }}", keyword: "users manajemen user pengguna", role: "admin" },
    { name: "{{ autoTranslate('Tambah User') }}", url: "{{ route('admin.users.ViewCreate', ['locale' => app()->getLocale()]) }}", keyword: "tambah user baru pengguna", role: "admin" },
    { name: "{{ autoTranslate('Manajemen Angkatan') }}", url: "{{ route('admin.angkatan.index', ['locale' => app()->getLocale()]) }}", keyword: "angkatan manajemen tahun masuk", role: "admin" },
    { name: "{{ autoTranslate('Tambah Angkatan') }}", url: "{{ route('admin.angkatan.create', ['locale' => app()->getLocale()]) }}", keyword: "tambah angkatan baru", role: "admin" },
    { name: "{{ autoTranslate('Manajemen Prodi') }}", url: "{{ route('admin.prodi.index', ['locale' => app()->getLocale()]) }}", keyword: "prodi program studi manajemen", role: "admin" },
    { name: "{{ autoTranslate('Tambah Prodi') }}", url: "{{ route('admin.prodi.create', ['locale' => app()->getLocale()]) }}", keyword: "tambah prodi program studi baru", role: "admin" },
    { name: "{{ autoTranslate('Manajemen Keahlian') }}", url: "{{ route('admin.keahlian.index', ['locale' => app()->getLocale()]) }}", keyword: "keahlian skill manajemen", role: "admin" },
    { name: "{{ autoTranslate('Tambah Keahlian') }}", url: "{{ route('admin.keahlian.create', ['locale' => app()->getLocale()]) }}", keyword: "tambah keahlian skill baru", role: "admin" },
    { name: "{{ autoTranslate('Manajemen Project') }}", url: "{{ route('admin.projects.index', ['locale' => app()->getLocale()]) }}", keyword: "project proyek manajemen admin", role: "admin" },
    { name: "{{ autoTranslate('Tambah Project Admin') }}", url: "{{ route('admin.projects.create', ['locale' => app()->getLocale()]) }}", keyword: "tambah project proyek baru admin", role: "admin" },
    { name: "{{ autoTranslate('Manajemen Sertifikat') }}", url: "{{ route('admin.sertifikat.index', ['locale' => app()->getLocale()]) }}", keyword: "sertifikat manajemen admin", role: "admin" },
    { name: "{{ autoTranslate('Tambah Sertifikat Admin') }}", url: "{{ route('admin.sertifikat.create', ['locale' => app()->getLocale()]) }}", keyword: "tambah sertifikat baru admin", role: "admin" },
    { name: "{{ autoTranslate('Manajemen Notifikasi') }}", url: "{{ route('admin.notifications.index', ['locale' => app()->getLocale()]) }}", keyword: "notifikasi manajemen admin", role: "admin" },
    { name: "{{ autoTranslate('Tambah Notifikasi') }}", url: "{{ route('admin.notifications.create', ['locale' => app()->getLocale()]) }}", keyword: "tambah notifikasi baru admin", role: "admin" },
    
    // === MENU DOSEN ===
    { name: "{{ autoTranslate('Dashboard Dosen') }}", url: "{{ route('dosen.dashboard', ['locale' => app()->getLocale()]) }}", keyword: "dosen dashboard pengajar lecturer", role: "dosen" },
    { name: "{{ autoTranslate('Mahasiswa Bimbingan') }}", url: "{{ route('dosen.users.index', ['locale' => app()->getLocale()]) }}", keyword: "mahasiswa bimbingan dosen supervised students", role: "dosen" },
    { name: "{{ autoTranslate('Tambah Mahasiswa Bimbingan') }}", url: "{{ route('dosen.users.ViewCreate', ['locale' => app()->getLocale()]) }}", keyword: "tambah mahasiswa bimbingan baru add supervised student", role: "dosen" },
    { name: "{{ autoTranslate('Projects Bimbingan') }}", url: "{{ route('dosen.projects.index', ['locale' => app()->getLocale()]) }}", keyword: "project bimbingan dosen proyek supervised projects", role: "dosen" },
    { name: "{{ autoTranslate('Tambah Project Bimbingan') }}", url: "{{ route('dosen.projects.create', ['locale' => app()->getLocale()]) }}", keyword: "tambah project bimbingan dosen add supervised project", role: "dosen" },
    { name: "{{ autoTranslate('Sertifikat Bimbingan') }}", url: "{{ route('dosen.sertifikat.index', ['locale' => app()->getLocale()]) }}", keyword: "sertifikat bimbingan dosen supervised certificates", role: "dosen" },
    { name: "{{ autoTranslate('Tambah Sertifikat Bimbingan') }}", url: "{{ route('dosen.sertifikat.create', ['locale' => app()->getLocale()]) }}", keyword: "tambah sertifikat bimbingan dosen add supervised certificate", role: "dosen" },
    
    // === MENU SETTING & PROFILE ===
    { name: "{{ autoTranslate('Pengaturan') }}", url: "#", keyword: "setting pengaturan mode bahasa settings preferences", role: "all" },
    { name: "{{ autoTranslate('Lihat Profil') }}", url: "{{ route('profile', ['locale' => app()->getLocale()]) }}", keyword: "profile profil lihat profil view profile account", role: "mahasiswa" },
    { name: "{{ autoTranslate('Logout') }}", url: "{{ route('logout') }}", keyword: "logout keluar sign out exit", role: "all_auth", isPost: true },
];

// Fungsi untuk mendapatkan role user saat ini
function getUserRole() {
    @auth
        return "{{ Auth::user()->role }}";
    @else
        return "guest";
    @endauth
}

// Fungsi untuk mengecek apakah user sudah login
function isAuthenticated() {
    @auth
        return true;
    @else
        return false;
    @endauth
}

function filterMenus(query) {
    if (!query) {
        searchResults.classList.add('hidden');
        return;
    }

    const currentRole = getUserRole();
    const authenticated = isAuthenticated();
    const searchQuery = query.toLowerCase().trim();
    
    const filtered = allMenus.filter(menu => {
        // Filter berdasarkan keyword (nama menu dalam bahasa apapun + keyword)
        const matchKeyword = menu.name.toLowerCase().includes(searchQuery) || 
                            menu.keyword.toLowerCase().includes(searchQuery);
        
        if (!matchKeyword) return false;
        
        // Filter berdasarkan role
        if (menu.role === "all") return true;
        if (menu.role === "all_auth" && authenticated) return true;
        if (menu.role === "guest" && !authenticated) return true;
        if (menu.role === currentRole) return true;
        
        return false;
    });

    if (filtered.length === 0) {
        searchResults.innerHTML = `
            <div class="px-4 py-3 text-gray-500 dark:text-gray-400 text-sm text-center">
                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ autoTranslate('Menu tidak ditemukan') }}
            </div>`;
    } else {
        let html = '';
        filtered.forEach(menu => {
            if (menu.isPost) {
                html += `
                    <form method="POST" action="${menu.url}" class="m-0">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors text-sm text-left">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="font-medium text-gray-700 dark:text-gray-200">${menu.name}</span>
                        </button>
                    </form>`;
            } else if (menu.url === "#") {
                html += `
                    <button onclick="document.getElementById('sidebarSearch').blur(); toggleDropdown('setting')" 
                            class="w-full flex items-center px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors text-sm text-left">
                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="font-medium text-gray-700 dark:text-gray-200">${menu.name}</span>
                    </button>`;
            } else {
                html += `
                    <a href="${menu.url}" class="flex items-center px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors text-sm">
                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                        <span class="font-medium text-gray-700 dark:text-gray-200">${menu.name}</span>
                    </a>`;
            }
        });
        searchResults.innerHTML = html;
    }

    searchResults.classList.remove('hidden');
}

// Event Listener untuk Search
searchInput.addEventListener('input', (e) => {
    filterMenus(e.target.value.trim());
});

// Close search results when clicking outside
document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.classList.add('hidden');
    }
});

// Escape key to close search
searchInput.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        searchResults.classList.add('hidden');
        searchInput.blur();
    }
});

    // ================== EXISTING SCRIPTS (dari kode lama) ==================
    function toggleDropdown(menu) {
        const settingMenu = document.getElementById('settingMenu');
        const settingArrow = document.getElementById('settingArrow');
        if (settingMenu.classList.contains('hidden')) {
            settingMenu.classList.remove('hidden');
            settingArrow.classList.add('rotate-180');
        } else {
            settingMenu.classList.add('hidden');
            settingArrow.classList.remove('rotate-180');
        }
    }

    function toggleDarkMode() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('darkMode', 'false');
            const btn = document.getElementById('darkModeBtn');
            if (btn) btn.innerHTML = 'Dark Mode';
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('darkMode', 'true');
            const btn = document.getElementById('darkModeBtn');
            if (btn) btn.innerHTML = 'Light Mode';
        }
    }

    function changeLanguage() {
        const select = document.getElementById('languageSelect');
        if (!select) return;
        const newLang = select.value;

        // Simpan preferensi bahasa ke localStorage bila diperlukan
        localStorage.setItem('lang', newLang);

        // Redirect otomatis ke halaman home locale baru
        const homeUrl = `${window.location.origin}/${newLang}`;
        window.location.href = homeUrl;
    }

    // Initialize dark mode
    if (localStorage.getItem('darkMode') === 'true') {
        document.documentElement.classList.add('dark');
        const btn = document.getElementById('darkModeBtn');
        if (btn) btn.innerHTML = 'Light Mode';
    }

    // Auto-detect language from localStorage on page load
    const savedLang = localStorage.getItem('lang');
    if (savedLang && (savedLang === 'id' || savedLang === 'en')) {
        const currentPath = window.location.pathname;
        // Check if current path already starts with /id or /en
        if (!currentPath.startsWith('/id') && !currentPath.startsWith('/en')) {
            // Redirect to the saved language home
            window.location.href = `${window.location.origin}/${savedLang}`;
        }
    }

    // Close setting dropdown when clicking outside
    document.addEventListener('click', function (event) {
        const settingMenu = document.getElementById('settingMenu');
        const settingButton = event.target.closest('button[onclick*="toggleDropdown"]');
        if (!settingButton && settingMenu && !settingMenu.contains(event.target)) {
            if (!settingMenu.classList.contains('hidden')) {
                settingMenu.classList.add('hidden');
                const settingArrow = document.getElementById('settingArrow');
                if (settingArrow) settingArrow.classList.remove('rotate-180');
            }
        }
    });

    // Sidebar collapse script (jika ada tombol toggle)
    const toggleBtn = document.getElementById('toggle-desktop-sidebar');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.classList.contains('lg:w-62')) {
                sidebar.classList.remove('lg:w-62');
                sidebar.classList.add('lg:w-20');
            } else {
                sidebar.classList.remove('lg:w-20');
                sidebar.classList.add('lg:w-62');
            }
        });
    }
</script>