<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 bg-gray-100 dark:bg-gray-800 dark:border-gray-800 border-r border-gray-200 shadow-xl
             transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out
             lg:static lg:inset-auto lg:shadow-sm
             flex flex-col overflow-hidden
             {{ session('sidebar_collapsed', false) ? 'lg:w-20' : 'lg:w-62' }}">

    <!-- Tombol Toggle Collapse untuk Desktop -->
    <button id="toggle-desktop-sidebar"
        class="hidden lg:flex absolute -right-3 top-16 w-6 h-6 bg-white dark:bg-gray-900 border dark:border-gray-700 border-gray-300 rounded-full items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-900 z-10 shadow-sm">
        <svg id="toggleCollapseIcon"
            class="w-4 h-4 text-gray-600 dark:text-gray-400 transition-transform duration-300 {{ session('sidebar_collapsed', false) ? 'rotate-180' : '' }}"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <div class="px-6 py-6 mt-10 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between shrink-0">
        <div class="flex-1 flex justify-center lg:justify-center">
            <div
                class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center shadow-md overflow-hidden">
                <img id="logo-zoom" src="{{ asset('assets/Logo.svg') }}" alt="Logo" class="w-20 h-20 rounded-full object-cover transition-all duration-300
                            {{ session('sidebar_collapsed', false) ? 'lg:w-10 lg:h-10' : '' }}">
            </div>
        </div>
    </div>

    <nav class="flex-1 px-2 py-6 space-y-2 overflow-y-auto overflow-x-hidden">

        <a href="{{ route('dashboard') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:text-blue-700' }}">

                <!-- Icon Home -->
                <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 10.5L12 3l9 7.5M5 10v9a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1v-9" />
                </svg>

                <!-- Text -->
                <span class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">
                    Home
                </span>

                <!-- Tooltip ketika sidebar collapse -->
                @if(session('sidebar_collapsed', false))
                    <span
                        class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                        Home
                    </span>
                @endif

            </a>

        <!-- Dashboard -->
        @auth
            <a href="{{ route('dashboard.me') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                          {{ request()->routeIs('dashboard.me') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 hover:text-blue-700' }}">
                <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 0l7-4m-7 4L9 5m3 0l7 4" />
                </svg>
                <span class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">My
                    Dashboard</span>

                <!-- Tooltip untuk collapsed mode -->
                @if(session('sidebar_collapsed', false))
                    <span
                        class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                        My Dashboard
                    </span>
                @endif
            </a>
        @endauth


        <!-- ================== PROJECT ================== -->
        @guest
            <a href="{{ route('project.project_user') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                          {{ request()->routeIs('project.project_user') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:text-blue-700' }}">
                <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span
                    class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Project
                    Mahasiswa</span>

                @if(session('sidebar_collapsed', false))
                    <span
                        class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                        Project Mahasiswa
                    </span>
                @endif
            </a>
        @else
            <div class="space-y-1 relative" x-data="{ open: {{ request()->routeIs('project.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                                   {{ request()->routeIs('project.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:text-blue-700' }}">
                    <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span
                        class="font-medium flex-1 text-left whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Project</span>
                    <svg x-show="!{{ session('sidebar_collapsed', false) ? 'false' : 'true' }}"
                        :class="{ 'rotate-180': open }"
                        class="w-4 h-4 transition-transform duration-300 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>

                    @if(session('sidebar_collapsed', false))
                        <span
                            class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                            Project
                        </span>
                    @endif
                </button>

                <div x-show="open && !{{ session('sidebar_collapsed', false) ? 'true' : 'false' }}"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    class="pl-5 space-y-1 mt-1 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">
                    <a href="{{ route('project.index') }}"
                        class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                                  {{ request()->routeIs('project.index') ? 'bg-blue-100 text-blue-800 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:text-blue-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        <span>Lihat Project Saya</span>
                    </a>

                    <a href="{{ route('project.create') }}"
                        class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                                  {{ request()->routeIs('project.create') ? 'bg-blue-100 text-blue-800 font-medium' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:text-blue-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Project Baru</span>
                    </a>
                </div>
            </div>
        @endguest

        

        <!-- Sertifikat - hanya untuk user yang sudah login -->
        @auth
            <div class="space-y-1 relative" x-data="{ open: {{ request()->routeIs('sertifikat.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                               {{ request()->routeIs('sertifikat.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:text-blue-700' }}">
                    <svg class="w-5 h-5 min-w-[20px]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <path d="M3 10h18"></path>
                        <circle cx="12" cy="14" r="2"></circle>
                    </svg>
                    <span
                        class="font-medium flex-1 text-left whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Sertifikat</span>
                    <svg x-show="!{{ session('sidebar_collapsed', false) ? 'false' : 'true' }}"
                        :class="{ 'rotate-180': open }"
                        class="w-4 h-4 transition-transform duration-300 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>

                    @if(session('sidebar_collapsed', false))
                        <span
                            class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                            Sertifikat
                        </span>
                    @endif
                </button>

                <div x-show="open && !{{ session('sidebar_collapsed', false) ? 'true' : 'false' }}"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    class="pl-5 space-y-1 mt-1 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">
                    <a href="{{ route('sertifikat.index') }}"
                        class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                              {{ request()->routeIs('sertifikat.index') ? 'bg-blue-100 text-blue-800 font-medium' : ' dark:text-gray-200 text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        <span>Lihat Sertifikat</span>
                    </a>
                    <a href="{{ route('sertifikat.create') }}"
                        class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                              {{ request()->routeIs('sertifikat.create') ? 'bg-blue-100 text-blue-800 font-medium' : ' dark:text-gray-200 text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Sertifikat Baru</span>
                    </a>
                </div>
            </div>
        @endauth
        <!-- Settings -->
            <a href="/settings" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                          {{ request()->routeIs('settings') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:text-blue-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-4 w-4"
                         viewBox="0 0 24 24" 
                         fill="none" 
                         stroke="currentColor" 
                         stroke-width="2" 
                         stroke-linecap="round" 
                         stroke-linejoin="round">
                        
                        <!-- Gear shape -->
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15
                                 a1.65 1.65 0 0 0 .33 1.82
                                 l.06.06
                                 a2 2 0 1 1-2.83 2.83
                                 l-.06-.06
                                 a1.65 1.65 0 0 0-1.82-.33
                                 1.65 1.65 0 0 0-1 1.51V21
                                 a2 2 0 1 1-4 0v-.09
                                 a1.65 1.65 0 0 0-1-1.51
                                 1.65 1.65 0 0 0-1.82.33
                                 l-.06.06
                                 a2 2 0 1 1-2.83-2.83
                                 l.06-.06
                                 a1.65 1.65 0 0 0 .33-1.82
                                 1.65 1.65 0 0 0-1.51-1H3
                                 a2 2 0 1 1 0-4h.09
                                 a1.65 1.65 0 0 0 1.51-1
                                 1.65 1.65 0 0 0-.33-1.82
                                 l-.06-.06
                                 a2 2 0 1 1 2.83-2.83
                                 l.06.06
                                 a1.65 1.65 0 0 0 1.82.33H9
                                 a1.65 1.65 0 0 0 1-1.51V3
                                 a2 2 0 1 1 4 0v.09
                                 a1.65 1.65 0 0 0 1 1.51
                                 1.65 1.65 0 0 0 1.82-.33
                                 l.06-.06
                                 a2 2 0 1 1 2.83 2.83
                                 l-.06.06
                                 a1.65 1.65 0 0 0-.33 1.82V9
                                 a1.65 1.65 0 0 0 1.51 1H21
                                 a2 2 0 1 1 0 4h-.09
                                 a1.65 1.65 0 0 0-1.51 1z">
                            </path>
                    </svg>
                    <span class="font-medium whitespace-nowrap">Settings</span>
                    </a>
    </nav>

    <!-- FOOTER -->
    <div class="px-2 py-5 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-700 shrink-0">
        @auth
            <a href="{{ route('profile') ?? '/profile' }}"
                class="block hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg dark:bg-gray-700 transition p-2 -mx-2 group relative">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white dark:border-gray-900 shadow-sm shrink-0">
                        @if (Auth::user()->photo_profile)
                            <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}"
                                alt="{{ Auth::user()->nama_mahasiswa }}" class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full bg-linear-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr(Auth::user()->nama_mahasiswa ?? 'U', 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">
                        <p class="text-gray-900 dark:text-gray-100 text-sm font-medium truncate">{{ Auth::user()->nama_mahasiswa }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-300 truncate">{{ Auth::user()->email ?? Auth::user()->username }}</p>
                    </div>

                    @if(session('sidebar_collapsed', false))
                        <span
                            class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                            {{ Auth::user()->nama_mahasiswa }}
                        </span>
                    @endif
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center space-x-2 px-4 py-2.5 bg-red-50 dark:bg-red-600 text-red-700 dark:text-red-50 rounded-lg hover:bg-red-100 transition font-medium group relative">
                    <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span class="{{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Logout</span>

                    @if(session('sidebar_collapsed', false))
                        <span
                            class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                            Logout
                        </span>
                    @endif
                </button>
            </form>
        @else
            <div class="text-center text-sm text-gray-500 dark:text-gray-50 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">
                Kamu Mahasiswa? Login Sekarang!
            </div>
            <div class="mt-3 flex {{ session('sidebar_collapsed', false) ? 'lg:flex-col lg:space-y-2' : '' }} space-x-2">
                <a href="{{ route('login') }}"
                    class="flex-1 px-4 py-2.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition font-medium text-sm text-center group relative">
                    <span class="{{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Login</span>
                    @if(session('sidebar_collapsed', false))
                        <svg class="w-5 h-5 hidden lg:block mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span
                            class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                            Login
                        </span>
                    @endif
                </a>
            </div>
        @endauth
    </div>
</aside>