<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 bg-gray-100 dark:bg-gray-900 dark:border-gray-800 border-r border-gray-200 shadow-xl
             transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out
             lg:static lg:inset-auto lg:shadow-sm
             flex flex-col overflow-hidden
             {{ session('sidebar_collapsed', false) ? 'lg:w-20' : 'lg:w-62' }}">

    <!-- Tombol Collapse Sidebar (Desktop) -->
    <button id="toggle-desktop-sidebar"
            class="hidden lg:flex absolute -right-3 top-16 w-6 h-6 bg-white dark:bg-gray-900 border dark:border-gray-700 border-gray-300 rounded-full items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-900 z-10 shadow-sm">
        <svg id="toggleCollapseIcon"
             class="w-4 h-4 text-gray-600 dark:text-gray-400 transition-transform duration-300 {{ session('sidebar_collapsed', false) ? 'rotate-180' : '' }}"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <!-- Logo -->
    <div class="px-6 py-6 mt-10 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between shrink-0">
        <div class="flex-1 flex justify-center lg:justify-center">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center shadow-md overflow-hidden">
                <img id="logo-zoom" class="cursor-pointer w-20 h-20 rounded-full object-cover transition-all duration-300"
                     src="{{ asset('assets/Logo.svg') }}" alt="Logo">
            </div>
        </div>
    </div>

    <nav class="flex-1 px-2 py-6 space-y-2 overflow-y-auto overflow-x-hidden">

        <!-- Home - Semua user -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
           {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 10.5L12 3l9 7.5M5 10v9a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1v-9" />
            </svg>
            <span class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Home</span>
            @if(session('sidebar_collapsed', false))
                <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                    Home
                </span>
            @endif
        </a>

        <!-- My Dashboard - Hanya mahasiswa biasa -->
        @auth
            @if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'dosen')
                <a href="{{ route('dashboard.me') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                   {{ request()->routeIs('dashboard.me') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                    <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 0l7-4m-7 4L9 5m3 0l7 4" />
                    </svg>
                    <span class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">My Dashboard</span>
                    @if(session('sidebar_collapsed', false))
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                            My Dashboard
                        </span>
                    @endif
                </a>
            @endif
        @endauth

        <!-- ================== MENU ADMIN ================== -->
        @auth
            @if(Auth::user()->role === 'admin')
                <!-- Manajemen Users -->
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                   {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                    <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span  data-translate="manajemen_users" data-translate-page="sidebar" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"></span>
                    @if(session('sidebar_collapsed', false))
                        <span data-translate="manajemen_users" data-translate-page="sidebar" class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                           
                        </span>
                    @endif
                </a>

                <!-- Manajemen Projects -->
                <a href="{{ route('admin.projects.index') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                   {{ request()->routeIs('admin.projects.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                    <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span  data-translate="manajemen_projects" data-translate-page="sidebar" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"></span>
                    @if(session('sidebar_collapsed', false))
                        <span data-translate="manajemen_projects" data-translate-page="sidebar" class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                           
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.angkatan.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                    {{ request()->routeIs('admin.angkatan.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:text-blue-700' }}">

                    <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-1a4 4 0 00-5-3.87M9 20H4v-1a4 4 0 015-3.87m8-6a4 4 0 11-8 0 4 4 0 018 0zM5 8a3 3 0 106 0 3 3 0 00-6 0z"/>
                    </svg>

                    <!-- TEXT NORMAL -->
                    <span data-translate="manajemen_angkatan" data-translate-page="sidebar"
                    class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">
                    </span>

                    @if(session('sidebar_collapsed', false))
                        <span 
                            data-translate="manajemen_angkatan" 
                            data-translate-page="sidebar"
                            class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                        </span>
                        @endif

                </a>

                <!-- Manajemen Sertifikat -->
                <a href="{{ route('admin.sertifikat.index') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                   {{ request()->routeIs('admin.sertifikat.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                    <svg class="w-5 h-5 min-w-[20px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <path d="M3 10h18"></path>
                        <circle cx="12" cy="14" r="2"></circle>
                    </svg>
                    <span  data-translate="manajemen_sertifikat" data-translate-page="sidebar" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"></span>
                    @if(session('sidebar_collapsed', false))
                        <span data-translate="manajemen_sertifikat" data-translate-page="sidebar" class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                         
                        </span>
                    @endif
                </a>
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
                    <span  data-translate="dashboard_dosen" data-translate-page="sidebar" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"></span>
                    @if(session('sidebar_collapsed', false))
                        <span data-translate="dashboard_dosen" data-translate-page="sidebar" class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                           
                        </span>
                    @endif
                </a>

                <a href="{{ route('dosen.users.index') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                   {{ request()->routeIs('dosen.mahasiswa.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                    <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span  data-translate="mahasiswa_bimbingan" data-translate-page="sidebar" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"></span>
                    @if(session('sidebar_collapsed', false))
                        <span data-translate="mahasiswa_bimbingan" data-translate-page="sidebar" class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                          
                        </span>
                    @endif
                </a>

                <a href="{{ route('dosen.projects.index') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                   {{ request()->routeIs('dosen.projects.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                    <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span  data-translate="projects_bimbingan" data-translate-page="sidebar" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"></span>
                    @if(session('sidebar_collapsed', false))
                        <span data-translate="projects_bimbingan" data-translate-page="sidebar" class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                            
                        </span>
                    @endif
                </a>

                <a href="{{ route('dosen.sertifikat.index') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                   {{ request()->routeIs('dosen.sertifikat.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                    <svg class="w-5 h-5 min-w-[20px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <path d="M3 10h18"></path>
                        <circle cx="12" cy="14" r="2"></circle>
                    </svg>
                    <span  data-translate="sertifikat_bimbingan" data-translate-page="sidebar" class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"></span>
                    @if(session('sidebar_collapsed', false))
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                            <span data-translate="sertifikat_bimbingan" data-translate-page="sidebar"></span>
                        </span>
                    @endif
                </a>
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
                <span class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Project Mahasiswa</span>
                @if(session('sidebar_collapsed', false))
                    <span data-translate="project_mahasiswa" data-translate-page="sidebar" class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                       
                    </span>
                @endif
            </a>
        @else
            @if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'dosen')
                <div class="space-y-1 relative" x-data="{ open: {{ request()->routeIs('project.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                            {{ request()->routeIs('project.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium flex-1 text-left whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Project</span>
                        <svg x-show="!{{ session('sidebar_collapsed', false) ? 'false' : 'true' }}"
                             :class="{ 'rotate-180': open }"
                             class="w-4 h-4 transition-transform duration-300 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        @if(session('sidebar_collapsed', false))
                            <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                                Project
                            </span>
                        @endif
                    </button>

                    <div x-show="open && !{{ session('sidebar_collapsed', false) ? 'true' : 'false' }}"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="pl-5 space-y-1 mt-1 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">
                        <a href="{{ route('project.index') }}"
                           class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                           {{ request()->routeIs('project.index') ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            <span data-translate="project_mahasiswa_saya" data-translate-page="sidebar"></span>
                        </a>
                        <a href="{{ route('project.create') }}"
                           class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                           {{ request()->routeIs('project.create') ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span data-translate="project_mahasiswa_saya_tambah" data-translate-page="sidebar"></span>
                        </a>
                    </div>
                </div>
            @endif
        @endguest

        <!-- ================== MENU SERTIFIKAT MAHASISWA ================== -->
        @auth
            @if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'dosen')
                <div class="space-y-1 relative" x-data="{ open: {{ request()->routeIs('sertifikat.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative
                            {{ request()->routeIs('sertifikat.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <svg class="w-5 h-5 min-w-[20px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
                            <path d="M8 2v4"></path>
                            <path d="M16 2v4"></path>
                            <path d="M3 10h18"></path>
                            <circle cx="12" cy="14" r="2"></circle>
                        </svg>
                        <span class="font-medium flex-1 text-left whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Sertifikat</span>
                        <svg x-show="!{{ session('sidebar_collapsed', false) ? 'false' : 'true' }}"
                             :class="{ 'rotate-180': open }"
                             class="w-4 h-4 transition-transform duration-300 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        @if(session('sidebar_collapsed', false))
                            <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                                <span data-translate="sertifikat" data-translate-page="sidebar"></span>
                            </span>
                        @endif
                    </button>

                    <div x-show="open && !{{ session('sidebar_collapsed', false) ? 'true' : 'false' }}"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="pl-5 space-y-1 mt-1 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">
                        <a href="{{ route('sertifikat.index') }}"
                           class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                           {{ request()->routeIs('sertifikat.index') ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            <span data-translate="sertifikat_mahasiswa_saya" data-translate-page="sidebar"></span>
                        </a>
                        <a href="{{ route('sertifikat.create') }}"
                           class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                           {{ request()->routeIs('sertifikat.create') ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700 dark:hover:text-blue-400' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span data-translate="sertifikat_mahasiswa_saya_tambah" data-translate-page="sidebar"></span>
                        </a>
                    </div>
                </div>
            @endif
        @endauth

    </nav>

    <!-- Setting Dropdown -->
    <div class="group relative mt-auto mb-4 px-2">
        <button onclick="toggleDropdown('setting')"
                class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-700">
            <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11.983 5.5c-.47 0-.93.05-1.372.146l-.388-1.648a.5.5 0 00-.487-.398h-1.472a.5.5 0 00-.487.398l-.388 1.648a6.987 6.987 0 00-1.186.688L4.69 5.5a.5.5 0 00-.607.06L3.04 6.603a.5.5 0 00-.06.607l.834 1.186a6.987 6.987 0 00-.688 1.186l-1.648.388a.5.5 0 00-.398.487v1.472c0 .232.158.433.388.487l1.648.388c.162.42.393.816.688 1.186l-.834 1.186a.5.5 0 00.06.607l1.043 1.043a.5.5 0 00.607.06l1.186-.834c.37.295.766.526 1.186.688l.388 1.648a.5.5 0 00.487.398h1.472a.5.5 0 00.487-.398l.388-1.648a6.987 6.987 0 001.186-.688l1.186.834a.5.5 0 00.607-.06l1.043-1.043a.5.5 0 00.06-.607l-.834-1.186c.295-.37.526-.766.688-1.186l1.648-.388a.5.5 0 00.398-.487v-1.472a.5.5 0 00-.398-.487l-1.648-.388a6.987 6.987 0 00-.688-1.186l.834-1.186a.5.5 0 00-.06-.607L19.277 5.56a.5.5 0 00-.607-.06l-1.186.834a6.987 6.987 0 00-1.186-.688l-.388-1.648a.5.5 0 00-.487-.398h-1.472a.5.5 0 00-.487.398l-.388 1.648A7.02 7.02 0 0011.983 5.5zM12 15a3 3 0 100-6 3 3 0 000 6z"/>
            </svg>
            <span class="font-medium whitespace-nowrap {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Setting</span>
            <svg id="settingArrow" class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
            @if(session('sidebar_collapsed', false))
                <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                    Setting
                </span>
            @endif
        </button>

        <div id="settingMenu"
             class="hidden bg-white dark:bg-gray-800 rounded-xl shadow-md mt-2 p-4 border border-gray-200 dark:border-gray-700 absolute left-0 w-60 z-50">
            <div class="flex items-center justify-between mb-4">
                <span data-translate="mode" data-translate-page="sidebar" class="text-gray-700 dark:text-gray-200"></span>
                <button id="darkModeBtn"
                        class="px-3 py-1 rounded-lg text-xs font-semibold bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200"
                        onclick="toggleDarkMode()">Dark Mode</button>
            </div>
            <div class="flex items-center justify-between">
                <span data-translate="bahasa" data-translate-page="sidebar" class="text-gray-700 dark:text-gray-200"></span>
                <select id="languageSelect"
                        class="bg-gray-200 dark:bg-gray-700 px-3 py-1 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-200"
                        onchange="changeLanguage()">
                    <option value="id">Indonesia</option>
                    <option value="en">English</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Footer - User Info & Logout -->
    <div class="px-2 py-5 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 shrink-0">
        @auth
            @if(Auth::user()->role === 'admin')
                <div class="block rounded-lg transition p-2 -mx-2 group relative cursor-default">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white dark:border-gray-900 shadow-sm shrink-0">
                            @if(Auth::user()->photo_profile)
                                <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}"
                                     alt="{{ Auth::user()->name ?? 'Admin' }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">
                            <p class="text-gray-900 dark:text-gray-100 text-sm font-medium truncate">
                                {{ Auth::user()->name ?? 'Admin' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                {{ Auth::user()->email }}
                                <span class="ml-1 px-1.5 py-0.5 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-[10px] font-semibold">
                                    Admin
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            @elseif(Auth::user()->role === 'dosen')
                <a href="{{ route('profile') }}"
                   class="block hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition p-2 -mx-2 group relative">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white dark:border-gray-900 shadow-sm shrink-0">
                            @if(Auth::user()->photo_profile)
                                <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}"
                                     alt="{{ Auth::user()->name ?? 'Dosen' }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr(Auth::user()->name ?? 'D', 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">
                            <p class="text-gray-900 dark:text-gray-100 text-sm font-medium truncate">
                                {{ Auth::user()->name ?? 'Dosen' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                {{ Auth::user()->email }}
                                <span class="ml-1 px-1.5 py-0.5 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-[10px] font-semibold">
                                    Dosen
                                </span>
                            </p>
                        </div>
                    </div>
                </a>
            @else
                <a href="{{ route('profile') }}"
                   class="block hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition p-2 -mx-2 group relative">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white dark:border-gray-900 shadow-sm shrink-0">
                            @if(Auth::user()->photo_profile)
                                <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}"
                                     alt="{{ Auth::user()->nama_mahasiswa ?? Auth::user()->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr(Auth::user()->nama_mahasiswa ?? Auth::user()->name ?? 'U', 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">
                            <p class="text-gray-900 dark:text-gray-100 text-sm font-medium truncate">
                                {{ Auth::user()->nama_mahasiswa ?? Auth::user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                    </div>
                </a>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center space-x-2 px-4 py-2.5 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/50 transition font-medium group relative">
                    <svg class="w-5 h-5 min-w-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="{{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Logout</span>
                    @if(session('sidebar_collapsed', false))
                        <span data-translate="logout" data-translate-page="sidebar" class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                        
                        </span>
                    @endif
                </button>
            </form>
        @else
            <div data-translate="login_prompt" data-translate-page="sidebar" class="text-center text-sm text-gray-500 dark:text-gray-400 {{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">
            </div>
            <div class="mt-3 flex {{ session('sidebar_collapsed', false) ? 'lg:flex-col lg:space-y-2' : 'space-x-2' }}">
                <a href="{{ route('login') }}"
                   class="flex-1 px-4 py-2.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition font-medium text-sm text-center group relative">
                    <span class="{{ session('sidebar_collapsed', false) ? 'lg:hidden' : '' }}">Login</span>
                    @if(session('sidebar_collapsed', false))
                        <span data-translate="login" data-translate-page="sidebar" class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap hidden lg:block">
                           
                        </span>
                    @endif
                </a>
            </div>
        @endauth
    </div>
</aside>

<!-- JavaScript -->
<script>
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
        document.getElementById('darkModeBtn').innerHTML = 'Dark Mode';
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('darkMode', 'true');
        document.getElementById('darkModeBtn').innerHTML = 'Light Mode';
    }
}

function changeLanguage() {
    const lang = document.getElementById('languageSelect').value;
    console.log('Language changed to:', lang);
    // Di sini bisa ditambahkan logika ganti bahasa (misal redirect dengan ?lang= atau cookie)
}

if (localStorage.getItem('darkMode') === 'true') {
    document.documentElement.classList.add('dark');
    const btn = document.getElementById('darkModeBtn');
    if (btn) btn.innerHTML = 'Light Mode';
}

document.addEventListener('click', function(event) {
    const settingMenu = document.getElementById('settingMenu');
    const settingButton = event.target.closest('button[onclick="toggleDropdown(\'setting\')"]');

    if (!settingButton && settingMenu && !settingMenu.contains(event.target)) {
        if (!settingMenu.classList.contains('hidden')) {
            settingMenu.classList.add('hidden');
            document.getElementById('settingArrow')?.classList.remove('rotate-180');
        }
    }
});
</script>