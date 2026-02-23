<aside class="w-64 bg-white border-r border-gray-200 text-gray-800 h-screen flex flex-col shadow-sm fixed lg:static top-0 left-0 z-30 lg:z-auto transition-transform duration-300 lg:translate-x-0">

    <div class="px-6 py-6 border-b border-gray-200 flex items-center justify-center">
        <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow-md">
            <img src="{{ asset('assets/profile.jpg') }}" alt="Logo" class="w-20 h-20 rounded-full logo-zoom" style="cursor:pointer;">
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 0l7-4m-7 4L9 5m3 0l7 4"></path>
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Portfolio Dropdown -->
        <div class="space-y-1">
            <button onclick="togglePortfolioMenu()" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('portofolio.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span class="font-medium flex-1 text-left">Portfolio</span>
                <svg id="portfolioArrow" class="w-4 h-4 transition-transform duration-300 {{ request()->routeIs('portofolio.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div id="portfolioMenu" class="pl-4 space-y-1 {{ request()->routeIs('portofolio.*') ? '' : 'hidden' }} mt-1">
                <a href="{{ route('portofolio.index') }}" class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('portofolio.index') ? 'bg-blue-100 text-blue-800 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                    <span>Lihat Portfolio</span>
                </a>

                @auth
                    <a href="{{ route('portofolio.create') }}" class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('portofolio.create') ? 'bg-blue-100 text-blue-800 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Buat Portfolio Baru</span>
                    </a>

                    <a href="{{ route('portofolio.edit', Auth::user()->id) }}" class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('portofolio.edit') ? 'bg-blue-100 text-blue-800 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit Portfolio Saya</span>
                    </a>
                @endauth
            </div>
        </div>

        @auth
        <!-- Project Dropdown -->
        <div class="space-y-1">
            <button onclick="toggleProjectMenu()" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('project.*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium flex-1 text-left">Project</span>
                <svg id="projectArrow" class="w-4 h-4 transition-transform duration-300 {{ request()->routeIs('project.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div id="projectMenu" class="pl-4 space-y-1 {{ request()->routeIs('project.*') ? '' : 'hidden' }} mt-1">
                <a href="{{ route('project.index') }}" class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('project.index') ? 'bg-blue-100 text-blue-800 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                    <span>Lihat Semua Project Saya</span>
                </a>

                <a href="{{ route('project.create') }}" class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('project.create') ? 'bg-blue-100 text-blue-800 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Tambah Project Baru</span>
                </a>

                    <a href="{{ route('project.edit', Auth::user()->id) }}" class="flex items-center space-x-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('project.edit') ? 'bg-blue-100 text-blue-800 font-medium' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit Project Saya</span>
                    </a>
            </div>
        </div>
        @endauth

        <!-- Learning Corner (contoh menu lain) -->
        @auth
            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.25m20-11.197C21.5 6.253 17 10.998 17 17.25m0-13V6a2 2 0 10-4 0v.253m4 0C13.5 5.482 12.8 5 12 5c-.8 0-1.5.482-1.5 1.253v13M12 21a2 2 0 100-4 2 2 0 000 4z"></path>
                </svg>
                <span class="font-medium">Learning Corner</span>
            </a>
        @endauth
    </nav>

    <!-- Footer Sidebar -->
    <div class="px-4 py-5 border-t border-gray-200 bg-gray-50">
        @auth
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-sm">
                    @if (Auth::user()->photo_profile)
                        <img src="{{ asset('storage/' . Auth::user()->photo_profile) }}" 
                             alt="{{ Auth::user()->nama_mahasiswa }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                            {{ substr(Auth::user()->nama_mahasiswa ?? 'U', 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">{{ Auth::user()->nama_mahasiswa }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? Auth::user()->username }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        @else
            <div class="space-y-3">
                <a href="{{ route('login') }}" class="block w-full py-3 bg-blue-600 text-white text-center rounded-xl hover:bg-blue-700 transition font-medium shadow-sm">
                    Login
                </a>
                <a href="{{ route('register') }}" class="block w-full py-3 border border-blue-600 text-blue-600 text-center rounded-xl hover:bg-blue-50 transition font-medium">
                    Daftar
                </a>
            </div>
        @endauth
    </div>

</aside>
